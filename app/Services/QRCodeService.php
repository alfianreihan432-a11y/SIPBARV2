<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\QRCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class QRCodeService
{
    /**
     * Generate QR code for a borrowing request
     */
    public function generateForRequest(BorrowingRequest $request): QRCode
    {
        // Generate cryptographically secure token
        $token = Str::uuid()->toString();
        
        // Create QR code image using endroid/qr-code
        $result = (new Builder(
            writer: new PngWriter(),
            data: $token,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 400,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
        ))->build();
        
        // Store image
        $filename = "qr-codes/{$request->id}_{$token}.png";
        Storage::disk('public')->put($filename, $result->getString());
        
        // Create QR code record
        $qrCode = QRCode::create([
            'borrowing_request_id' => $request->id,
            'code' => $token,
            'data' => json_encode([
                'borrowing_request_id' => $request->id,
                'student_name' => $request->user->name,
                'item_name' => $request->item->name,
            ]),
            'image_path' => $filename,
            'is_active' => true,
        ]);
        
        // Denormalize token to borrowing_requests for fast lookup
        $request->update(['qr_token' => $token]);
        
        return $qrCode;
    }
    
    /**
     * Get QR code image as base64 string.
     * Accepts a QRCode model or a BorrowingRequest that has an associated QR code.
     */
    public function getImageBase64(QRCode|BorrowingRequest $source, bool $asDataUri = false): string
    {
        $qrCode = $source instanceof BorrowingRequest
            ? $source->qrCode ?? QRCode::where('borrowing_request_id', $source->id)->first()
            : $source;

        if (!$qrCode || !$qrCode->image_path) {
            throw new \InvalidArgumentException('QR Code image not found for the given source.');
        }

        $imageContents = Storage::disk('public')->get($qrCode->image_path);
        $base64 = base64_encode($imageContents);

        return $asDataUri ? 'data:image/png;base64,'.$base64 : $base64;
    }
    
    /**
     * Find borrowing request by QR token
     */
    public function findByToken(string $token): ?BorrowingRequest
    {
        return BorrowingRequest::where('qr_token', $token)
            ->with(['user', 'item', 'teacher', 'qrCode'])
            ->first();
    }
    
    /**
     * Record a QR code scan
     */
    public function recordScan(QRCode $qrCode): void
    {
        $qrCode->increment('scan_count');
        $qrCode->update([
            'last_scanned_at' => now(),
            'scanned_at' => $qrCode->scanned_at ?? now(), // Set first scan if null
        ]);
    }
}
