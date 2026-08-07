<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
use App\Models\QRCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class TeacherApproval extends Component
{
    public $requests;
    public $rejectionReason = '';
    public $selectedRequestId = null;

    public function mount()
    {
        $this->loadRequests();
    }

    public function loadRequests()
    {
        $this->requests = BorrowingRequest::with(['user', 'item', 'teacher'])
            ->where('teacher_id', Auth::id())
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->latest()
            ->get();
    }

    public function approve($requestId)
    {
        $request = BorrowingRequest::findOrFail($requestId);
        
        if ($request->teacher_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menyetujui permintaan ini.');
            return;
        }

        $request->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Generate QR Code
        $this->generateQRCode($request);

        // Update status to qr_ready
        $request->update(['status' => 'qr_ready']);

        // Send notification to student
        $this->notifyStudent($request, 'approved');

        session()->flash('success', 'Permintaan peminjaman berhasil disetujui. QR Code telah dibuat.');
        $this->loadRequests();
    }

    public function showRejectModal($requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->rejectionReason = '';
    }

    public function reject()
    {
        $request = BorrowingRequest::findOrFail($this->selectedRequestId);
        
        if ($request->teacher_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki izin untuk menolak permintaan ini.');
            return;
        }

        $request->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectionReason,
        ]);

        // Send notification to student
        $this->notifyStudent($request, 'rejected');

        session()->flash('success', 'Permintaan peminjaman berhasil ditolak.');
        $this->selectedRequestId = null;
        $this->loadRequests();
    }

    protected function generateQRCode($request)
    {
        $qrData = [
            'id' => $request->id,
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'borrow_date' => $request->borrow_date,
            'return_date' => $request->return_date,
            'token' => Str::random(32),
        ];

        $qrCode = Builder::create()
            ->data(json_encode($qrData))
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->writer(new PngWriter())
            ->build();

        $fileName = 'qr_codes/' . $request->id . '_' . time() . '.png';
        Storage::disk('public')->put($fileName, $qrCode->getString());

        QRCode::create([
            'borrowing_request_id' => $request->id,
            'code' => $qrData['token'],
            'data' => json_encode($qrData),
            'image_path' => $fileName,
            'is_active' => true,
            'expires_at' => $request->return_date->endOfDay(),
        ]);
    }

    protected function notifyStudent($request, $status)
    {
        $whatsappService = new \App\Services\WhatsAppNotificationService();
        
        if ($status === 'approved') {
            $whatsappService->sendApprovalNotification($request);
        } else {
            $whatsappService->sendRejectionNotification($request);
        }
    }

    public function render()
    {
        return view('livewire.teacher-approval');
    }
}
