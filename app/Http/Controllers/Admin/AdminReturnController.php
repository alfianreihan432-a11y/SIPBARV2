<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\Notification;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminReturnController extends Controller
{
    /**
     * Daftar Pengajuan Pengembalian Masuk
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'semua');
        $search = $request->query('q');

        $query = ItemReturn::with([
            'borrowingRequest.item.category',
            'user',
            'verifier'
        ])->latest();

        if ($status !== 'semua' && in_array($status, ['menunggu', 'disetujui', 'ditolak'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('nis', 'like', "%{$search}%");
                })->orWhereHas('borrowingRequest.item', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $returns = $query->paginate(12)->withQueryString();

        // Hitung statistik untuk badge & widget filter
        $countSemua = ItemReturn::count();
        $countMenunggu = ItemReturn::where('status', ItemReturn::STATUS_MENUNGGU)->count();
        $countDisetujui = ItemReturn::where('status', ItemReturn::STATUS_DISETUJUI)->count();
        $countDitolak = ItemReturn::where('status', ItemReturn::STATUS_DITOLAK)->count();

        return view('pages.admin.returns', compact(
            'returns',
            'status',
            'search',
            'countSemua',
            'countMenunggu',
            'countDisetujui',
            'countDitolak'
        ));
    }

    /**
     * Detail Pengajuan Pengembalian (AJAX / modal support)
     */
    public function show($id)
    {
        $return = ItemReturn::with([
            'borrowingRequest.item.category',
            'user',
            'verifier'
        ])->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $return,
                'photo_url' => $return->foto_bukti ? asset('storage/' . $return->foto_bukti) : null,
            ]);
        }

        return view('pages.admin.returns-detail', compact('return'));
    }

    /**
     * Setujui Pengembalian Barang
     */
    public function approve(Request $request, $id)
    {
        $return = ItemReturn::with(['borrowingRequest.item', 'user'])->findOrFail($id);

        if ($return->status !== ItemReturn::STATUS_MENUNGGU) {
            return back()->with('error', 'Pengembalian ini sudah diverifikasi sebelumnya.');
        }

        try {
            DB::transaction(function () use ($return) {
                $now = now();
                $adminId = Auth::id();

                // 1. Update status ItemReturn
                $return->update([
                    'status'             => ItemReturn::STATUS_DISETUJUI,
                    'diverifikasi_oleh'  => $adminId,
                    'tanggal_verifikasi' => $now,
                ]);

                // 2. Update status BorrowingRequest -> returned
                $borrowing = $return->borrowingRequest;
                $borrowing->update([
                    'status'           => BorrowingRequest::STATUS_RETURNED,
                    'returned_at'      => $now,
                    'return_condition' => match($return->kondisi_barang) {
                        'baik'         => 'good',
                        'rusak_ringan' => 'damaged',
                        'rusak_berat'  => 'damaged',
                        'hilang'       => 'lost',
                        default        => 'good'
                    },
                    'return_notes'     => $return->catatan,
                    'checkin_by'       => $adminId,
                ]);

                // 3. Update status Item di inventaris
                $item = $borrowing->item;
                if ($item) {
                    // Update status barang kembali ke Tersedia bila kondisi masih layak
                    if (in_array($return->kondisi_barang, ['baik', 'rusak_ringan'])) {
                        $item->update(['status' => 'Tersedia']);
                    } else if ($return->kondisi_barang === 'rusak_berat') {
                        $item->update(['status' => 'Rusak', 'condition' => 'Rusak Berat']);
                    } else if ($return->kondisi_barang === 'hilang') {
                        $item->update(['status' => 'Hilang', 'condition' => 'Hilang']);
                    }
                }

                // 4. Notifikasi in-app untuk Siswa
                Notification::sendToUser(
                    $return->user_id,
                    'pengembalian_disetujui',
                    "Pengajuan pengembalian barang '{$borrowing->item->name}' telah DISETUJUI oleh Admin. Terima kasih telah mengembalikan barang!",
                    ['item_return_id' => $return->id]
                );

                // 5. WhatsApp notification jika tersedia
                try {
                    $waService = app(WhatsAppNotificationService::class);
                    $waService->notifyReturned($borrowing);
                } catch (\Exception $we) {
                    Log::info('WA notification return ignored: ' . $we->getMessage());
                }
            });

            return back()->with('success', 'Pengembalian barang berhasil disetujui! Status barang dan peminjaman telah diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error approving return request: ' . $e->getMessage(), [
                'return_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menyetujui pengembalian: ' . $e->getMessage());
        }
    }

    /**
     * Tolak Pengembalian Barang
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_ditolak' => 'required|string|min:5|max:500',
        ], [
            'alasan_ditolak.required' => 'Wajib mengisi alasan penolakan pengembalian.',
            'alasan_ditolak.min'      => 'Alasan penolakan minimal 5 karakter.',
            'alasan_ditolak.max'      => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $return = ItemReturn::with(['borrowingRequest.item', 'user'])->findOrFail($id);

        if ($return->status !== ItemReturn::STATUS_MENUNGGU) {
            return back()->with('error', 'Pengembalian ini sudah diverifikasi sebelumnya.');
        }

        try {
            DB::transaction(function () use ($return, $request) {
                $now = now();
                $adminId = Auth::id();

                // 1. Update status ItemReturn menjadi ditolak
                $return->update([
                    'status'             => ItemReturn::STATUS_DITOLAK,
                    'alasan_ditolak'     => $request->alasan_ditolak,
                    'diverifikasi_oleh'  => $adminId,
                    'tanggal_verifikasi' => $now,
                ]);

                // 2. Status peminjaman BorrowingRequest TETAP 'borrowed' agar siswa dapat mengajukan ulang
                // Siswa diberi info penolakan

                // 3. Notifikasi in-app untuk Siswa
                Notification::sendToUser(
                    $return->user_id,
                    'pengembalian_ditolak',
                    "Pengajuan pengembalian barang '{$return->borrowingRequest->item->name}' DITOLAK. Alasan: {$request->alasan_ditolak}. Silakan ajukan ulang dengan data yang sesuai.",
                    ['item_return_id' => $return->id, 'alasan' => $request->alasan_ditolak]
                );
            });

            return back()->with('success', 'Pengajuan pengembalian telah ditolak. Siswa telah menerima pemberitahuan alasan penolakan.');

        } catch (\Exception $e) {
            Log::error('Error rejecting return request: ' . $e->getMessage(), [
                'return_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menolak pengembalian: ' . $e->getMessage());
        }
    }
}
