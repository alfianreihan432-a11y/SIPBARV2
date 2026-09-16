<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\QRCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentQRCodeController extends Controller
{
    /**
     * Tampilkan halaman HTML view QR Code peminjaman siswa.
     * Jika diakses via AJAX / JSON, otomatis fallback mengembalikan JSON.
     */
    public function show(int $id, Request $request): View|JsonResponse
    {
        $qrData = $this->getQRCodeData($id);

        if ($qrData instanceof JsonResponse) {
            return $qrData;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($qrData);
        }

        return view('pages.siswa.qr-result', $qrData);
    }

    /**
     * Mengembalikan data QR Code sebagai JSON untuk AJAX modal.
     */
    public function data(int $id): JsonResponse
    {
        $qrData = $this->getQRCodeData($id);

        if ($qrData instanceof JsonResponse) {
            return $qrData;
        }

        return response()->json($qrData);
    }

    /**
     * Helper privat untuk memproses dan mengambil data QR Code.
     */
    private function getQRCodeData(int $id): array|JsonResponse
    {
        // Ambil borrowing request milik siswa yang login
        $borrowingRequest = BorrowingRequest::with(['itemWithTrashed', 'items.itemWithTrashed', 'user', 'qrCode'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hanya boleh akses jika status approved, qr_ready, atau borrowed
        if (! in_array($borrowingRequest->status, ['approved', 'qr_ready', 'borrowed'])) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code hanya tersedia untuk peminjaman yang sudah disetujui.',
            ], 403);
        }

        // Cek apakah sudah ada QR code aktif, jika tidak buat baru
        $qrCodeRecord = $borrowingRequest->qrCode;

        if (! $qrCodeRecord || ! $qrCodeRecord->isValid()) {
            // Generate token unik 32 karakter
            $token = Str::random(32);

            // Data yang di-encode dalam QR
            $qrDataString = json_encode([
                'borrowing_id' => $borrowingRequest->id,
                'user_id'      => $borrowingRequest->user_id,
                'item_id'      => $borrowingRequest->item_id,
                'token'        => $token,
                'issued_at'    => now()->toIso8601String(),
            ]);

            // Simpan/update record QR code di database
            $qrCodeRecord = QRCode::updateOrCreate(
                ['borrowing_request_id' => $borrowingRequest->id],
                [
                    'code'       => $token,
                    'data'       => $qrDataString,
                    'is_active'  => true,
                    'expires_at' => now()->addDays(7), // valid 7 hari
                ]
            );
        }

        // URL verifikasi untuk discan oleh petugas inventaris
        $verifyUrl = route('admin.qr.verify', ['token' => $qrCodeRecord->code]);

        // Generate QR Code menggunakan Endroid QR Code v6
        $qrCode = new EndroidQrCode(
            data: $verifyUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 260,
            margin: 10
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $statusLabel = match($borrowingRequest->status) {
            'approved' => 'Disetujui',
            'qr_ready' => 'Siap Ambil',
            'borrowed' => 'Sedang Dipinjam',
            default    => ucfirst($borrowingRequest->status),
        };

        $itemNames = $borrowingRequest->items->isNotEmpty()
            ? $borrowingRequest->items->map(fn ($detail) => ($detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang tidak tersedia') . ' (' . ($detail->quantity ?? 1) . ')')->implode(', ')
            : ($borrowingRequest->itemWithTrashed?->name ?? $borrowingRequest->item?->name ?? 'Barang tidak tersedia');

        return [
            'success'      => true,
            'qr_image'     => $result->getDataUri(),
            'token'        => $qrCodeRecord->code,
            'item_name'    => $itemNames,
            'borrowing_id' => $borrowingRequest->id,
            'expires_at'   => $qrCodeRecord->expires_at?->format('d M Y, H:i'),
            'status'       => $borrowingRequest->status,
            'status_label' => $statusLabel,
        ];
    }
}
