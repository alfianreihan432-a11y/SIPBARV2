<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\QRCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminQRVerificationController extends Controller
{
    /**
     * Tampilkan halaman verifikasi QR Code saat discan oleh admin/petugas.
     */
    public function verify(string $token)
    {
        if (Auth::user()?->hasRole('kepala_jurusan')) {
            return redirect()->route('kajur.qr.verify', ['token' => $token]);
        }

        $qrRecord = QRCode::where('code', $token)
            ->with([
                'borrowingRequest.user.classroom',
                'borrowingRequest.itemWithTrashed',
                'borrowingRequest.items.itemWithTrashed',
                'borrowingRequest.teacher',
            ])
            ->first();

        if (! $qrRecord) {
            return view('pages.admin.qr-verify', [
                'valid'   => false,
                'message' => 'QR Code tidak ditemukan dalam sistem.',
            ]);
        }

        if ($qrRecord->isExpired()) {
            return view('pages.admin.qr-verify', [
                'valid'   => false,
                'message' => 'QR Code sudah kadaluarsa.',
            ]);
        }

        $borrowingRequest = $qrRecord->borrowingRequest;

        if (! $borrowingRequest) {
            return view('pages.admin.qr-verify', [
                'valid'   => false,
                'message' => 'Data permohonan peminjaman tidak ditemukan.',
            ]);
        }

        if (! $qrRecord->is_active && $borrowingRequest->status !== BorrowingRequest::STATUS_REJECTED) {
            return view('pages.admin.qr-verify', [
                'valid'   => false,
                'message' => 'QR Code sudah tidak aktif.',
            ]);
        }

        return view('pages.admin.qr-verify', [
            'valid'            => true,
            'qrRecord'         => $qrRecord,
            'borrowingRequest' => $borrowingRequest,
        ]);
    }

    /**
     * Konfirmasi bahwa siswa telah mengambil barang secara fisik (update status ke borrowed).
     */
    public function confirmCheckout(Request $request, int $id, \App\Services\BorrowingApprovalService $approvalService)
    {
        $borrowingRequest = BorrowingRequest::with(['itemWithTrashed', 'items.itemWithTrashed', 'qrCode'])->findOrFail($id);

        try {
            $approvalService->processCheckout($borrowingRequest, (int) Auth::id());

            return redirect()->route('admin.qr.verify', ['token' => $borrowingRequest->qrCode->code ?? ''])
                ->with('success', 'Pengambilan barang berhasil dikonfirmasi! Status kini menjadi Dipinjam.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Tolak pengambilan barang oleh admin/petugas inventaris.
     * Wajib menyertakan alasan penolakan dan diproses lewat BorrowingApprovalService.
     */
    public function rejectCheckout(Request $request, int $id, \App\Services\BorrowingApprovalService $approvalService)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan pengambilan barang wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 5 karakter.',
            'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $borrowingRequest = BorrowingRequest::with(['itemWithTrashed', 'qrCode'])->findOrFail($id);

        if (! in_array($borrowingRequest->status, ['approved', 'qr_ready'])) {
            return redirect()->back()->with('error', 'Peminjaman dengan status saat ini (' . $borrowingRequest->status_label . ') tidak dapat ditolak.');
        }

        try {
            $approvalService->reject(
                $borrowingRequest,
                $validated['rejection_reason'],
                Auth::id()
            );

            // Non-aktifkan QR Code
            if ($borrowingRequest->qrCode) {
                $borrowingRequest->qrCode->update(['is_active' => false]);
            }

            return redirect()->route('admin.qr.verify', ['token' => $borrowingRequest->qrCode?->code ?? ''])
                ->with('success', 'Pengambilan barang berhasil ditolak dan alasan telah disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak pengambilan: ' . $e->getMessage());
        }
    }
}
