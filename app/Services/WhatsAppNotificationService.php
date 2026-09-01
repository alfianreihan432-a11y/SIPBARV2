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

    private function getApiSettings(): array
    {
        return [
            'base_url' => config('services.whatsapp.base_url') ?? $this->baseUrl,
            'api_key' => config('services.whatsapp.api_key') ?? $this->apiKey,
        ];
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

        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');

        $payload = [
            'nomorGuru' => $teacherPhone,
            'namaSiswa' => $studentName,
            'kelas' => $request->user?->kelas ?? '',
            'barang' => $itemName,
            'jumlah' => $request->quantity,
            'tglPinjam' => $request->borrow_date ? $request->borrow_date->format('d-m-Y') : '-',
            'tglKembali' => $request->return_date ? $request->return_date->format('d-m-Y') : '-',
            'keperluan' => $request->purpose,
            'linkKeputusan' => $this->getDirectWaLink($request),
        ];

        Log::info('WA direct link generated for teacher approval', [
            'borrowing_request_id' => $request->id,
            'teacher_phone' => $teacherPhone,
            'wa_link' => $payload['linkKeputusan'],
        ]);
    }

    public function getApprovalUrl(BorrowingRequest $request): string
    {
        return URL::temporarySignedRoute(
            'approval.show',
            now()->addDays(7),
            ['borrowingRequest' => $request->id]
        );
    }

    public function getDirectWaLink(BorrowingRequest $request): string
    {
        $teacherPhone = trim((string) ($request->teacher?->phone ?? ''));
        $approvalUrl = $this->getApprovalUrl($request);

        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');

        $message = urlencode(
            "Halo, ada pengajuan peminjaman baru.\n" .
            "Siswa: {$studentName}\n" .
            "Barang: {$itemName}\n" .
            "Jumlah: {$request->quantity}\n" .
            "Keperluan: {$request->purpose}\n\n" .
            "Klik link berikut untuk meninjau dan memutuskan:\n{$approvalUrl}"
        );

        if ($teacherPhone !== '') {
            $waPhone = $this->normalizePhone($teacherPhone);
            return "https://api.whatsapp.com/send?phone={$waPhone}&text={$message}";
        }

        // Jika nomor guru belum diisi, buat link share WA umum (user dapat memilih kontak di WhatsApp)
        return "https://api.whatsapp.com/send?text={$message}";
    }
    
    /**
     * Notify student about rejection
     */
    public function getRejectedStudentWaLink(BorrowingRequest $request): string
    {
        $studentPhone = (string) ($request->user?->phone ?? '');

        if ($studentPhone === '') {
            return '';
        }

        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');

        $message = "Halo {$studentName},\n" .
            "Pengajuan peminjaman barang Anda ditolak.\n\n" .
            "Barang: {$itemName}\n" .
            "Alasan: " . ($request->rejection_reason ?: 'Tidak ada alasan yang diberikan') . "\n\n" .
            "Silakan ajukan ulang atau hubungi guru pembimbing untuk informasi lebih lanjut.";

        return $this->buildWaLink($studentPhone, $message);
    }

    public function notifyRejected(BorrowingRequest $request): void
    {
        $studentPhone = (string) ($request->user?->phone ?? '');

        if ($studentPhone === '') {
            Log::warning('WhatsApp notification rejected skipped: student phone is empty', [
                'borrowing_request_id' => $request->id,
                'user_id' => $request->user_id,
            ]);

            return;
        }

        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');

        $message = "Halo {$studentName},\n" .
            "Pengajuan peminjaman barang Anda ditolak.\n\n" .
            "Barang: {$itemName}\n" .
            "Alasan: " . ($request->rejection_reason ?: 'Tidak ada alasan yang diberikan') . "\n\n" .
            "Silakan ajukan ulang atau hubungi guru pembimbing untuk informasi lebih lanjut.";

        $waLink = $this->buildWaLink($studentPhone, $message);
        $sent = $this->sendThroughConfiguredApi($studentPhone, $message);

        Log::info('WA notification processed for student rejection', [
            'borrowing_request_id' => $request->id,
            'student_phone' => $studentPhone,
            'wa_link' => $waLink,
            'sent_via_api' => $sent,
        ]);
    }
    
    /**
     * Notify student about approval with QR code
     */
    public function getApprovedStudentWaLink(BorrowingRequest $request): string
    {
        $studentPhone = (string) ($request->user?->phone ?? '');

        if ($studentPhone === '') {
            return '';
        }

        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');
        $returnDate = $request->return_date ? $request->return_date->format('d-m-Y') : '-';

        $qrUrl = route('student.qrcode.show', ['id' => $request->id]);
        $message = "Halo {$studentName},\n" .
            "Pengajuan peminjaman Anda telah disetujui.\n\n" .
            "Barang: {$itemName}\n" .
            "Tanggal kembali: {$returnDate}\n\n" .
            "Klik link berikut untuk melihat QR Code pengambilan barang:\n{$qrUrl}\n\n" .
            "Tunjukkan QR Code ini kepada petugas saat mengambil barang.";

        return $this->buildWaLink($studentPhone, $message);
    }

    public function notifyApproved(BorrowingRequest $request, string $qrBase64): void
    {
        $studentPhone = (string) ($request->user?->phone ?? '');

        if ($studentPhone === '') {
            Log::warning('WhatsApp notification approved skipped: student phone is empty', [
                'borrowing_request_id' => $request->id,
                'user_id' => $request->user_id,
            ]);

            return;
        }

        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');
        $returnDate = $request->return_date ? $request->return_date->format('d-m-Y') : '-';

        $qrUrl = route('student.qrcode.show', ['id' => $request->id]);
        $message = "Halo {$studentName},\n" .
            "Pengajuan peminjaman Anda telah disetujui.\n\n" .
            "Barang: {$itemName}\n" .
            "Tanggal kembali: {$returnDate}\n\n" .
            "Klik link berikut untuk melihat QR Code pengambilan barang:\n{$qrUrl}\n\n" .
            "Tunjukkan QR Code ini kepada petugas saat mengambil barang.";

        $waLink = $this->buildWaLink($studentPhone, $message);
        $sent = $this->sendThroughConfiguredApi($studentPhone, $message);

        Log::info('WA notification processed for student approval', [
            'borrowing_request_id' => $request->id,
            'student_phone' => $studentPhone,
            'wa_link' => $waLink,
            'sent_via_api' => $sent,
        ]);
    }
    
    /**
     * Send H-1 reminder to student
     */
    public function notifyReminder(BorrowingRequest $request): void
    {
        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');
        $returnDate = $request->return_date ? $request->return_date->format('d-m-Y') : '-';

        $payload = [
            'nomorSiswa' => $request->user?->phone ?? '',
            'namaSiswa' => $studentName,
            'barang' => $itemName,
            'tglKembali' => $returnDate,
        ];
        
        $this->sendNotification(
            $request->id,
            'reminder_h1',
            $request->user?->phone ?? '',
            $payload
        );
    }
    
    /**
     * Notify student about successful checkout
     */
    public function notifyCheckout(BorrowingRequest $request): void
    {
        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');
        $returnDate = $request->return_date ? $request->return_date->format('d-m-Y') : '-';

        $payload = [
            'nomorSiswa' => $request->user?->phone ?? '',
            'namaSiswa' => $studentName,
            'barang' => $itemName,
            'jumlah' => $request->quantity,
            'tglKembali' => $returnDate,
        ];
        
        $this->sendNotification(
            $request->id,
            'checkout',
            $request->user?->phone ?? '',
            $payload
        );
    }
    
    /**
     * Notify student about successful return
     */
    public function notifyReturned(BorrowingRequest $request): void
    {
        $studentName = $request->user?->name ?? 'Siswa';
        $itemName = $request->item?->name ?? ($request->itemWithTrashed?->name ?? 'Barang');

        $payload = [
            'nomorSiswa' => $request->user?->phone ?? '',
            'namaSiswa' => $studentName,
            'barang' => $itemName,
            'waktuKembali' => $request->returned_at ? $request->returned_at->format('d-m-Y H:i') : now()->format('d-m-Y H:i'),
        ];
        
        $this->sendNotification(
            $request->id,
            'dikembalikan',
            $request->user?->phone ?? '',
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

    private function buildWaLink(string $phone, string $message): string
    {
        $waPhone = $this->normalizePhone($phone);

        if ($waPhone === '') {
            return '';
        }

        return 'https://api.whatsapp.com/send?phone=' . $waPhone . '&text=' . urlencode($message);
    }

    /**
     * Send message via configured WhatsApp API when available.
     * If no provider is configured, keep the direct wa.me link flow as fallback.
     */
    private function sendThroughConfiguredApi(string $recipientPhone, string $message): bool
    {
        $waPhone = $this->normalizePhone($recipientPhone);
        $settings = $this->getApiSettings();
        $baseUrl = $settings['base_url'] ?? '';
        $apiKey = $settings['api_key'] ?? '';

        if ($waPhone === '' || empty($baseUrl) || empty($apiKey)) {
            Log::warning('WhatsApp send skipped because provider config is empty', [
                'base_url_set' => !empty($baseUrl),
                'api_key_set' => !empty($apiKey),
                'recipient_phone' => $waPhone,
            ]);

            return false;
        }

        $endpoint = rtrim($baseUrl, '/');
        $payload = [
            'phone' => $waPhone,
            'to' => $waPhone,
            'message' => $message,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])
                ->timeout($this->timeout)
                ->post($endpoint, $payload);

            if ($response->successful()) {
                return true;
            }

            Log::warning('WhatsApp API delivery failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'recipient_phone' => $waPhone,
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp API delivery exception', [
                'message' => $e->getMessage(),
                'recipient_phone' => $waPhone,
            ]);

            return false;
        }
    }

    /**
     * Send notification to WhatsApp bot.
     *
     * Deprecated in favor of configured API + direct wa.me link fallback.
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
