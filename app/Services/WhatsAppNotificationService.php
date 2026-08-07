<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        // Configuration for WhatsApp API (using Twilio or similar service)
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
    }

    public function sendBorrowingRequestNotification(BorrowingRequest $request)
    {
        $teacher = $request->teacher;
        $student = $request->user;
        $item = $request->item;

        if (!$teacher || !$teacher->phone) {
            Log::warning('Teacher phone not found for borrowing request #' . $request->id);
            return false;
        }

        $message = $this->formatBorrowingRequestMessage($request, $student, $item);
        
        return $this->sendMessage($teacher->phone, $message);
    }

    public function sendApprovalNotification(BorrowingRequest $request)
    {
        $student = $request->user;
        $item = $request->item;

        if (!$student || !$student->phone) {
            Log::warning('Student phone not found for borrowing request #' . $request->id);
            return false;
        }

        $message = $this->formatApprovalMessage($request, $student, $item);
        
        return $this->sendMessage($student->phone, $message);
    }

    public function sendRejectionNotification(BorrowingRequest $request)
    {
        $student = $request->user;
        $item = $request->item;

        if (!$student || !$student->phone) {
            Log::warning('Student phone not found for borrowing request #' . $request->id);
            return false;
        }

        $message = $this->formatRejectionMessage($request, $student, $item);
        
        return $this->sendMessage($student->phone, $message);
    }

    protected function formatBorrowingRequestMessage($request, $student, $item)
    {
        return "🔔 *PERMOHONAN PEMINJAMAN BARANG*\n\n" .
               "Halo Bapak/Ibu Guru,\n\n" .
               "Terdapat permohonan peminjaman barang baru:\n\n" .
               "👤 *Siswa:* {$student->name}\n" .
               "📚 *Kelas:* {$student->kelas}\n" .
               "📖 *Jurusan:* {$student->jurusan}\n\n" .
               "📦 *Barang:* {$item->name}\n" .
               "🔢 *Jumlah:* {$request->quantity}\n" .
               "📝 *Keperluan:* {$request->purpose}\n" .
               "📅 *Tanggal Pinjam:* {$request->borrow_date}\n" .
               "📅 *Tanggal Kembali:* {$request->return_date}\n\n" .
               "Silakan login ke sistem untuk menyetujui atau menolak permintaan ini.\n\n" .
               "_SIPBAR - Sistem Inventaris Barang_";
    }

    protected function formatApprovalMessage($request, $student, $item)
    {
        return "✅ *PERSETUJUAN PEMINJAMAN*\n\n" .
               "Halo {$student->name},\n\n" .
               "Permintaan peminjaman Anda telah disetujui!\n\n" .
               "📦 *Barang:* {$item->name}\n" .
               "🔢 *Jumlah:* {$request->quantity}\n" .
               "📅 *Tanggal Pinjam:* {$request->borrow_date}\n" .
               "📅 *Tanggal Kembali:* {$request->return_date}\n\n" .
               "QR Code telah dibuat dan siap digunakan. Silakan tunjukkan QR Code kepada admin saat mengambil barang.\n\n" .
               "_SIPBAR - Sistem Inventaris Barang_";
    }

    protected function formatRejectionMessage($request, $student, $item)
    {
        return "❌ *PENOLAKAN PEMINJAMAN*\n\n" .
               "Halo {$student->name},\n\n" .
               "Mohon maaf, permintaan peminjaman Anda ditolak.\n\n" .
               "📦 *Barang:* {$item->name}\n" .
               "📝 *Alasan:* {$request->rejection_reason}\n\n" .
               "Silakan hubungi guru penanggung jawab untuk informasi lebih lanjut.\n\n" .
               "_SIPBAR - Sistem Inventaris Barang_";
    }

    protected function sendMessage($phoneNumber, $message)
    {
        try {
            // Normalize phone number
            $phone = $this->normalizePhoneNumber($phoneNumber);
            
            // Log the message for now (replace with actual API call)
            Log::info("WhatsApp Message to {$phone}: {$message}");
            
            // Example API call (uncomment and configure with actual WhatsApp API)
            /*
            $response = Http::post($this->apiUrl, [
                'api_key' => $this->apiKey,
                'phone' => $phone,
                'message' => $message,
            ]);
            
            return $response->successful();
            */
            
            return true; // Return true for now since we're just logging
        } catch (\Exception $e) {
            Log::error('WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function normalizePhoneNumber($phone)
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Add country code if not present
        if (strlen($phone) === 10 && str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        return $phone;
    }
}
