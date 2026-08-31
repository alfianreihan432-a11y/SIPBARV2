<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\WhatsAppNotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class WhatsAppNotificationService
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout = 10;
    
    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.base_url') ?? '';
        $this->apiKey = config('services.whatsapp.api_key') ?? '';
    }
    
    /**
     * Notify teacher about new borrowing request using a direct WhatsApp link,
     * without requiring a WhatsApp bot service.
     */
    public function notifyNewRequest(BorrowingRequest $request): void
    {
        $teacherPhone = (string) ($request->teacher->phone ?? '');

        if ($teacherPhone === '') {
            Log::warning('WhatsApp notification new request skipped: teacher phone is empty', [
                'borrowing_request_id' => $request->id,
                'teacher_id' => $request->teacher_id,
            ]);

            return;
        }

        $payload = [
            'nomorGuru' => $teacherPhone,
            'namaSiswa' => $request->user->name,
            'kelas' => $request->user->kelas ?? '',
            'barang' => $request->item->name,
            'jumlah' => $request->quantity,
            'tglPinjam' => $request->borrow_date->format('d-m-Y'),
            'tglKembali' => $request->return_date->format('d-m-Y'),
            'keperluan' => $request->purpose,
            'linkKeputusan' => $this->getDirectWaLink($request),
        ];

        Log::info('WA direct link generated for teacher approval', [
            'borrowing_request_id' => $request->id,
            'teacher_phone' => $teacherPhone,
            'wa_link' => $payload['linkKeputusan'],
        ]);
    }

    public function getDirectWaLink(BorrowingRequest $request): string
    {
        $teacherPhone = (string) ($request->teacher->phone ?? '');

        if ($teacherPhone === '') {
            return '';
        }

        $approvalUrl = URL::temporarySignedRoute(
            'approval.show',
            now()->addDays(3),
            ['borrowingRequest' => $request->id]
        );

        $waPhone = $this->normalizePhone($teacherPhone);
        $message = urlencode(
            "Halo, ada pengajuan peminjaman baru.\n" .
            "Siswa: {$request->user->name}\n" .
            "Barang: {$request->item->name}\n" .
            "Jumlah: {$request->quantity}\n" .
            "Keperluan: {$request->purpose}\n\n" .
            "Klik link berikut untuk meninjau dan memutuskan: {$approvalUrl}"
        );

        return "https://wa.me/{$waPhone}?text={$message}";
    }
    
    /**
     * Notify student about rejection
     */
    public function notifyRejected(BorrowingRequest $request): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'alasan' => $request->rejection_reason,
        ];
        
        $this->sendNotification(
            $request->id,
            'ditolak',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    /**
     * Notify student about approval with QR code
     */
    public function notifyApproved(BorrowingRequest $request, string $qrBase64): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'tglKembali' => $request->return_date->format('d-m-Y'),
            'qrBase64' => $qrBase64,
        ];
        
        $this->sendNotification(
            $request->id,
            'disetujui',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    /**
     * Send H-1 reminder to student
     */
    public function notifyReminder(BorrowingRequest $request): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'tglKembali' => $request->return_date->format('d-m-Y'),
        ];
        
        $this->sendNotification(
            $request->id,
            'reminder_h1',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    /**
     * Notify student about successful checkout
     */
    public function notifyCheckout(BorrowingRequest $request): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'jumlah' => $request->quantity,
            'tglKembali' => $request->return_date->format('d-m-Y'),
        ];
        
        $this->sendNotification(
            $request->id,
            'checkout',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    /**
     * Notify student about successful return
     */
    public function notifyReturned(BorrowingRequest $request): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'waktuKembali' => $request->returned_at ? $request->returned_at->format('d-m-Y H:i') : now()->format('d-m-Y H:i'),
        ];
        
        $this->sendNotification(
            $request->id,
            'dikembalikan',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    /**
     * Normalisasi nomor WA untuk format wa.me/62xxx.
     */
    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phone ?? '');

        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '62')) {
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        return '62' . $digits;
    }

    /**
     * Send notification to WhatsApp bot
     *
     * Dihentikan untuk flow baru karena user meminta memakai link WA saja,
     * bukan bot WhatsApp.
     */
    private function sendNotification(
        int $borrowingRequestId,
        string $type,
        string $recipientPhone,
        array $payload
    ): void {
        Log::info('WhatsApp bot integration skipped; using direct wa.me link flow instead.', [
            'borrowing_request_id' => $borrowingRequestId,
            'notification_type' => $type,
            'recipient_phone' => $recipientPhone,
            'payload' => $payload,
        ]);
    }
    
    /**
     * Check WhatsApp bot status
     * 
     * @return array
     */
    public function checkBotStatus(): array
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->get("{$this->baseUrl}/status");
            
            return [
                'online' => $response->successful(),
                'status_code' => $response->status(),
                'message' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'online' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
