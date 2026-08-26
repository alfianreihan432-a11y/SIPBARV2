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
        $qrRecord = QRCode::where('code', $token)
            ->where('is_active', true)
            ->with(['borrowingRequest.user', 'borrowingRequest.itemWithTrashed', 'borrowingRequest.teacher'])
            ->first();

        if (! $qrRecord || $qrRecord->isExpired()) {
            return view('pages.admin.qr-verify', [
                'valid'   => false,
                'message' => 'QR Code tidak valid atau sudah kadaluarsa.',
            ]);
        }

        $borrowingRequest = $qrRecord->borrowingRequest;

        if (! $borrowingRequest) {
            return view('pages.admin.qr-verify', [
                'valid'   => false,
                'message' => 'Data permohonan peminjaman tidak ditemukan.',
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
    public function confirmCheckout(Request $request, int $id)
    {
        $borrowingRequest = BorrowingRequest::with('itemWithTrashed', 'qrCode')->findOrFail($id);

        if (! in_array($borrowingRequest->status, ['approved', 'qr_ready'])) {
            return redirect()->back()->with('error', 'Status peminjaman saat ini (' . $borrowingRequest->status_label . ') tidak dapat dikonfirmasi pengambilan.');
        }

        // Cek stok barang jika item masih ada di inventaris aktif
        $item = $borrowingRequest->item;
        if ($item) {
            if ($item->stock < $borrowingRequest->quantity) {
                return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk memenuhi peminjaman.');
            }
            // Kurangi stok barang
            $item->decrement('stock', $borrowingRequest->quantity);
        }

        // Update status peminjaman menjadi borrowed / barang diambil
        $borrowingRequest->update([
            'status'      => BorrowingRequest::STATUS_BORROWED,
            'borrowed_at' => now(),
            'checkout_by' => Auth::id(),
        ]);

        // Catat aktivitas scan pada record QR Code
        if ($borrowingRequest->qrCode) {
            $borrowingRequest->qrCode->update([
                'scanned_at'      => $borrowingRequest->qrCode->scanned_at ?? now(),
                'last_scanned_at' => now(),
                'scan_count'      => ($borrowingRequest->qrCode->scan_count ?? 0) + 1,
            ]);
        }

        return redirect()->route('admin.qr.verify', ['token' => $borrowingRequest->qrCode->code ?? ''])
            ->with('success', 'Pengambilan barang berhasil dikonfirmasi! Status kini menjadi Dipinjam.');
    }
}
