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
use Illuminate\Support\Str;

class StudentQRCodeController extends Controller
{
    /**
     * Generate (atau ambil yang sudah ada) dan kirim QR Code sebagai JSON
     * untuk ditampilkan dalam modal siswa tanpa pindah halaman.
     */
    public function show(int $id): JsonResponse
    {
        // Ambil borrowing request milik siswa yang login
        $borrowingRequest = BorrowingRequest::with('itemWithTrashed', 'user')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hanya boleh akses jika status approved atau borrowed
        if (! in_array($borrowingRequest->status, ['approved', 'borrowed'])) {
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
            $qrData = json_encode([
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
                    'data'       => $qrData,
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

        return response()->json([
            'success'      => true,
            'qr_image'     => $result->getDataUri(),
            'token'        => $qrCodeRecord->code,
            'item_name'    => $borrowingRequest->itemWithTrashed?->name ?? 'Barang tidak tersedia',
            'borrowing_id' => $borrowingRequest->id,
            'expires_at'   => $qrCodeRecord->expires_at?->format('d M Y'),
            'status'       => $borrowingRequest->status,
        ]);
    }
}
