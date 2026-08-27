<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\QRCode;
use App\Services\EmailNotificationService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BorrowingForm extends Component
{
    public $item;
    public $quantity = 1;
    public $purpose = '';
    public $borrow_date;
    public $return_date;
    public $teacher_id;
    public $notes = '';

    public function mount($itemId)
    {
        $this->item = Item::findOrFail($itemId);
        $this->borrow_date = now()->toDateString();
        $this->return_date = now()->addDays(7)->toDateString();
        
        // Auto-select teacher if item has assigned teacher
        if ($this->item->teacher_id) {
            $this->teacher_id = $this->item->teacher_id;
        }
    }

    public function close(): void
    {
        $this->dispatch('close-borrow-modal');
    }

    public function submit()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:' . $this->item->stock,
            'purpose' => 'required|string|min:5',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after:borrow_date',
            'teacher_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $request = BorrowingRequest::create([
            'user_id' => Auth::id(),
            'item_id' => $this->item->id,
            'teacher_id' => $this->teacher_id,
            'quantity' => $this->quantity,
            'purpose' => $this->purpose,
            'borrow_date' => $this->borrow_date,
            'return_date' => $this->return_date,
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        // Send email notification to teacher
        $this->sendEmailNotification($request);

        session()->flash('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan guru.');
        
        return redirect()->route('student.dashboard');
    }

    protected function sendEmailNotification(BorrowingRequest $request): void
    {
        try {
            app(EmailNotificationService::class)->notifyNewRequest($request);
        } catch (\Exception $e) {
            \Log::error('Email notification failed for new request', [
                'borrowing_request_id' => $request->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Metode lama WhatsApp — dinonaktifkan sementara (bot diblokir)
    // protected function sendWhatsAppNotification($request)
    // {
    //     try {
    //         $whatsappService = app(\App\Services\WhatsAppNotificationService::class);
    //         $whatsappService->notifyNewRequest($request);
    //     } catch (\Exception $e) {
    //         \Log::error('WhatsApp notification failed for new request', [
    //             'borrowing_request_id' => $request->id,
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    // }

    public function render()
    {
        $teachers = User::role('guru')->get();
        
        return view('livewire.borrowing-form', [
            'teachers' => $teachers,
        ]);
    }
}
