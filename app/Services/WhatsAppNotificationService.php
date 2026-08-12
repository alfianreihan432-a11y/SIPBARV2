<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\WhatsAppNotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     * Notify teacher about new borrowing request
     */
    public function notifyNewRequest(BorrowingRequest $request): void
    {
        $payload = [
            'nomorGuru' => $request->teacher->phone ?? '',
            'namaSiswa' => $request->user->name,
            'kelas' => $request->user->kelas ?? '',
            'barang' => $request->item->name,
            'jumlah' => $request->quantity,
            'tglPinjam' => $request->borrow_date->format('d-m-Y'),
            'tglKembali' => $request->return_date->format('d-m-Y'),
            'keperluan' => $request->purpose,
            'linkKeputusan' => route('teacher.requests'),
        ];
        
        $this->sendNotification(
            $request->id,
            'pengajuan_baru',
            $request->teacher->phone ?? '',
            $payload
        );
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
     * Send notification to WhatsApp bot
     * 
     * @param int $borrowingRequestId
     * @param string $type
     * @param string $recipientPhone
     * @param array $payload
     * @return void
     */
    private function sendNotification(
        int $borrowingRequestId,
        string $type,
        string $recipientPhone,
        array $payload
    ): void {
        // Create log entry
        $log = WhatsAppNotificationLog::create([
            'borrowing_request_id' => $borrowingRequestId,
            'notification_type' => $type,
            'recipient_phone' => $recipientPhone,
            'payload' => $payload,
            'status' => 'pending',
        ]);
        
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post("{$this->baseUrl}/notify/{$type}", $payload);
            
            $log->update([
                'status' => $response->successful() ? 'success' : 'failed',
                'http_status_code' => $response->status(),
                'error_message' => $response->successful() ? null : $response->body(),
                'sent_at' => now(),
            ]);
            
            if (!$response->successful()) {
                Log::error("WhatsApp notification failed: {$type}", [
                    'log_id' => $log->id,
                    'borrowing_request_id' => $borrowingRequestId,
                    'status_code' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
            
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            Log::error("WhatsApp notification exception: {$type}", [
                'log_id' => $log->id,
                'borrowing_request_id' => $borrowingRequestId,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
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
