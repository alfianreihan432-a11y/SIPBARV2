<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use App\Models\BorrowingRequest;
use App\Services\BorrowingApprovalService;
use App\Exceptions\InsufficientStockException;

class BotApiController extends Controller
{
    /**
     * Ubah nomor WA (62xxx / +62xxx) jadi format yang tersimpan di DB (0xxx)
     */
    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '62')) {
            $phone = '0' . substr($phone, 2);
        }

        return $phone;
    }

    // GET /api/v1/bot/cek/{id}
    public function cekStatus($id)
    {
        $req = BorrowingRequest::with('item')->find($id);

        if (!$req) {
            return response()->json(['message' => 'Kode peminjaman tidak ditemukan'], 404);
        }

        return response()->json([
            'nama_barang' => $req->item->name ?? '-',
            'status'      => $req->status_label,
            'jatuh_tempo' => optional($req->return_date)->format('d-m-Y'),
        ]);
    }

    // GET /api/v1/bot/riwayat/{phone}
    public function riwayat($phone)
    {
        $phone = $this->normalizePhone($phone);
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return response()->json(['message' => 'Nomor WhatsApp kamu belum terdaftar di sipbar. Hubungi admin untuk update data.'], 404);
        }

        $history = BorrowingRequest::with('item')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($r) => [
                'id'          => $r->id,
                'nama_barang' => $r->item->name ?? '-',
                'status'      => $r->status_label,
            ]);

        return response()->json($history);
    }

    // GET /api/v1/bot/barang
    public function daftarBarang()
    {
        $items = Item::where('stock', '>', 0)
            ->where('status', 'available')
            ->get(['name', 'stock']);

        return response()->json($items);
    }

    // POST /api/v1/bot/pinjam
    public function ajukanPinjam(Request $request)
    {
        $request->validate([
            'phone'     => 'required|string',
            'item_name' => 'required|string',
        ]);

        $phone = $this->normalizePhone($request->phone);
        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return response()->json(['message' => 'Nomor WhatsApp kamu belum terdaftar di sipbar. Hubungi admin untuk update data.'], 404);
        }

        $item = Item::where('name', 'like', '%' . $request->item_name . '%')
            ->where('stock', '>', 0)
            ->first();

        if (!$item) {
            return response()->json(['message' => 'Barang tidak ditemukan atau stok habis'], 400);
        }

        $req = BorrowingRequest::create([
            'user_id'     => $user->id,
            'item_id'     => $item->id,
            'teacher_id'  => $item->teacher_id,
            'quantity'    => 1,
            'purpose'     => 'Diajukan melalui WhatsApp Bot',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'status'      => BorrowingRequest::STATUS_PENDING,
        ]);

        return response()->json([
            'message' => 'Pengajuan berhasil dibuat, menunggu persetujuan guru',
            'kode'    => $req->id,
        ]);
    }

    // GET /api/v1/bot/permohonan/{phone}
    public function permohonanPending($phone)
    {
        $phone = $this->normalizePhone($phone);
        $teacher = User::where('phone', $phone)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Nomor WhatsApp kamu tidak terdaftar sebagai guru.'], 404);
        }

        $list = BorrowingRequest::with(['user', 'item'])
            ->where('teacher_id', $teacher->id)
            ->where('status', BorrowingRequest::STATUS_PENDING)
            ->latest()
            ->get()
            ->map(fn ($r) => [
                'id'        => $r->id,
                'siswa'     => $r->user->name ?? '-',
                'barang'    => $r->item->name ?? '-',
                'jumlah'    => $r->quantity,
                'keperluan' => $r->purpose,
            ]);

        return response()->json($list);
    }

    // POST /api/v1/bot/approve/{id}
    public function approveViaBot(Request $request, int $id, BorrowingApprovalService $approvalService)
    {
        $request->validate(['phone' => 'required|string']);
        $phone = $this->normalizePhone($request->phone);
        $teacher = User::where('phone', $phone)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Nomor kamu tidak terdaftar sebagai guru.'], 404);
        }

        $req = BorrowingRequest::with(['user', 'item'])->find($id);
        if (!$req) {
            return response()->json(['message' => 'Pengajuan tidak ditemukan.'], 404);
        }
        if ($req->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'Kamu tidak berhak menyetujui pengajuan ini.'], 403);
        }
        if ($req->status !== BorrowingRequest::STATUS_PENDING) {
            return response()->json(['message' => 'Pengajuan ini sudah diproses sebelumnya.'], 400);
        }

        try {
            $approvalService->approve($req, $teacher->id);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json([
            'message' => 'Pengajuan berhasil disetujui',
            'siswa'   => $req->user->name,
            'barang'  => $req->item->name,
        ]);
    }

    // POST /api/v1/bot/reject/{id}
    public function rejectViaBot(Request $request, int $id, BorrowingApprovalService $approvalService)
    {
        $request->validate([
            'phone'  => 'required|string',
            'reason' => 'required|string|min:5',
        ]);
        $phone = $this->normalizePhone($request->phone);
        $teacher = User::where('phone', $phone)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Nomor kamu tidak terdaftar sebagai guru.'], 404);
        }

        $req = BorrowingRequest::with(['user', 'item'])->find($id);
        if (!$req) {
            return response()->json(['message' => 'Pengajuan tidak ditemukan.'], 404);
        }
        if ($req->teacher_id !== $teacher->id) {
            return response()->json(['message' => 'Kamu tidak berhak menolak pengajuan ini.'], 403);
        }
        if ($req->status !== BorrowingRequest::STATUS_PENDING) {
            return response()->json(['message' => 'Pengajuan ini sudah diproses sebelumnya.'], 400);
        }

        $approvalService->reject($req, $request->reason, $teacher->id);

        return response()->json([
            'message' => 'Pengajuan berhasil ditolak',
            'siswa'   => $req->user->name,
            'barang'  => $req->item->name,
        ]);
    }
}