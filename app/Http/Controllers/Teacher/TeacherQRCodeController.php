<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\QRCode;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class TeacherQRCodeController extends Controller
{
    /**
     * Display QR code page for teacher borrowing request
     */
    public function show(int $id): View
    {
        // Get borrowing request for the logged-in teacher
        $borrowingRequest = BorrowingRequest::with(['item', 'approvedByKajur'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('tipe_peminjam', 'guru')
            ->firstOrFail();

        // Only allow access if status is approved by kajur
        if (! in_array($borrowingRequest->status, ['approved', 'qr_ready', 'borrowed'])) {
            abort(403, 'QR Code hanya tersedia untuk peminjaman yang sudah disetujui Kepala Jurusan.');
        }

        return view('pages.guru.qr-result', [
            'borrowing' => $borrowingRequest,
        ]);
    }

    /**
     * Generate QR code as JSON for AJAX requests
     */
    public function generate(int $id): JsonResponse
    {
        // Get borrowing request for the logged-in teacher
        $borrowingRequest = BorrowingRequest::with(['item', 'itemWithTrashed', 'approvedByKajur', 'qrCode'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->where('tipe_peminjam', 'guru')
            ->firstOrFail();

        // Only allow access if status is approved by kajur
        if (! in_array($borrowingRequest->status, ['approved', 'qr_ready', 'borrowed'])) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code hanya tersedia untuk peminjaman yang sudah disetujui Kepala Jurusan.',
            ], 403);
        }

        // Check if QR code already exists and is valid
        $qrCodeRecord = $borrowingRequest->qrCode;

        if (! $qrCodeRecord || ! $qrCodeRecord->isValid()) {
            // Generate unique 32-character token
            $token = Str::random(32);

            // Data to encode in QR
            $qrData = json_encode([
                'borrowing_id' => $borrowingRequest->id,
                'user_id'      => $borrowingRequest->user_id,
                'item_id'      => $borrowingRequest->item_id,
                'token'        => $token,
                'issued_at'    => now()->toIso8601String(),
                'tipe_peminjam' => 'guru',
            ]);

            // Save/update QR code record in database
            $qrCodeRecord = QRCode::updateOrCreate(
                ['borrowing_request_id' => $borrowingRequest->id],
                [
                    'code'       => $token,
                    'data'       => $qrData,
                    'is_active'  => true,
                    'expires_at' => now()->addDays(7), // valid for 7 days
                ]
            );
        }

        // Verification URL for Kepala Jurusan to scan
        $verifyUrl = route('kajur.qr.verify', ['token' => $qrCodeRecord->code]);

        // Generate QR Code using Endroid QR Code v6
        $qrCode = new EndroidQrCode(
            data: $verifyUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return response()->json([
            'success'      => true,
            'qr_image'     => $result->getDataUri(),
            'token'        => $qrCodeRecord->code,
            'item_name'    => $borrowingRequest->itemWithTrashed?->name ?? $borrowingRequest->item?->name ?? 'Barang tidak tersedia',
            'borrowing_id' => $borrowingRequest->id,
            'expires_at'   => $qrCodeRecord->expires_at?->format('d M Y'),
            'status'       => $borrowingRequest->status,
        ]);
    }
}
