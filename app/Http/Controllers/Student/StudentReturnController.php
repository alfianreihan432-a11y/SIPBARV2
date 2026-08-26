<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\ItemReturn;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentReturnController extends Controller
{
    /**
     * Halaman "Barang Saya" - Daftar barang yang sedang dipinjam oleh siswa
     */
    public function index()
    {
        $userId = Auth::id();

        // Ambil peminjaman yang berstatus 'borrowed' atau 'approved' milik siswa
        $activeLoans = BorrowingRequest::where('user_id', $userId)
            ->whereIn('status', [BorrowingRequest::STATUS_BORROWED, BorrowingRequest::STATUS_APPROVED])
            ->with(['item.category', 'teacher', 'latestReturn'])
            ->latest()
            ->get();

        // Ringkasan statistik
        $totalBorrowed = $activeLoans->count();
        $pendingReturns = ItemReturn::where('user_id', $userId)
            ->where('status', ItemReturn::STATUS_MENUNGGU)
            ->count();
        $completedReturns = ItemReturn::where('user_id', $userId)
            ->where('status', ItemReturn::STATUS_DISETUJUI)
            ->count();

        return view('pages.siswa.returns.index', compact('activeLoans', 'totalBorrowed', 'pendingReturns', 'completedReturns'));
    }

    /**
     * Form Pengajuan Pengembalian Barang
     */
    public function create($id)
    {
        $userId = Auth::id();

        $borrowing = BorrowingRequest::with(['item.category', 'teacher'])->findOrFail($id);

        // Otorisasi: Harus milik siswa yang sedang login
        if ($borrowing->user_id !== $userId) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        // Cek apakah status peminjaman memang sedang dipinjam/disetujui
        if (!in_array($borrowing->status, [BorrowingRequest::STATUS_BORROWED, BorrowingRequest::STATUS_APPROVED])) {
            return redirect()->route('student.returns.index')
                ->with('error', 'Barang ini tidak dalam status sedang dipinjam.');
        }

        // Cek apakah sudah ada pengajuan yang berstatus "menunggu"
        $existingPending = ItemReturn::where('borrowing_request_id', $borrowing->id)
            ->where('status', ItemReturn::STATUS_MENUNGGU)
            ->first();

        if ($existingPending) {
            return redirect()->route('student.returns.history')
                ->with('warning', 'Pengembalian untuk barang ini sudah diajukan dan sedang menunggu verifikasi admin.');
        }

        return view('pages.siswa.returns.create', compact('borrowing'));
    }

    /**
     * Simpan Pengajuan Pengembalian Barang
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrowing_request_id' => 'required|exists:borrowing_requests,id',
            'kondisi_barang'       => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'catatan'              => 'nullable|string|max:1000',
            'foto_bukti'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kondisi_barang.required' => 'Pilih kondisi barang saat ini.',
            'kondisi_barang.in'       => 'Kondisi barang tidak valid.',
            'catatan.max'             => 'Catatan maksimal 1000 karakter.',
            'foto_bukti.image'        => 'File bukti harus berupa gambar.',
            'foto_bukti.mimes'        => 'Format foto yang didukung: jpg, jpeg, png, webp.',
            'foto_bukti.max'          => 'Ukuran foto maksimal 2MB.',
        ]);

        $userId = Auth::id();
        $borrowing = BorrowingRequest::with('item')->findOrFail($request->borrowing_request_id);

        // Otorisasi
        if ($borrowing->user_id !== $userId) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if (!in_array($borrowing->status, [BorrowingRequest::STATUS_BORROWED, BorrowingRequest::STATUS_APPROVED])) {
            return redirect()->route('student.returns.index')
                ->with('error', 'Status peminjaman tidak valid untuk pengembalian.');
        }

        // Cek duplikasi pengajuan yang berstatus 'menunggu'
        $existing = ItemReturn::where('borrowing_request_id', $borrowing->id)
            ->where('status', ItemReturn::STATUS_MENUNGGU)
            ->exists();

        if ($existing) {
            return redirect()->route('student.returns.history')
                ->with('error', 'Pengajuan pengembalian untuk barang ini sudah ada dalam antrean verifikasi.');
        }

        // Handle upload foto bukti
        $fotoPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoPath = $request->file('foto_bukti')->store('returns', 'public');
        }

        // Buat record pengembalian dengan status default 'menunggu'
        $returnRecord = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $userId,
            'kondisi_barang'       => $request->kondisi_barang,
            'catatan'              => $request->catatan,
            'foto_bukti'           => $fotoPath,
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        // Kirim notifikasi sistem ke para Admin
        try {
            Notification::sendToAdmins(
                'pengembalian_baru',
                "Pengajuan pengembalian baru dari {$borrowing->user->name} untuk barang '{$borrowing->item->name}'.",
                ['item_return_id' => $returnRecord->id]
            );
        } catch (\Exception $e) {
            \Log::warning('Gagal membuat in-app notification admin: ' . $e->getMessage());
        }

        return redirect()->route('student.returns.history')
            ->with('success', 'Pengajuan pengembalian berhasil dikirim! Menunggu verifikasi petugas/admin.');
    }

    /**
     * Halaman Riwayat Pengembalian Milik Siswa
     */
    public function history()
    {
        $userId = Auth::id();

        $returns = ItemReturn::where('user_id', $userId)
            ->with(['borrowingRequest.item.category', 'verifier'])
            ->latest()
            ->paginate(10);

        return view('pages.siswa.returns.history', compact('returns'));
    }
}
