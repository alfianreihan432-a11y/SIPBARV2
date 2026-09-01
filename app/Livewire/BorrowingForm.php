<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BorrowingForm extends Component
{
    public $item;
    public $quantity = 1;
    public $purpose = '';
    public $borrow_date;
    public $return_date;
    public $return_time;
    public $teacher_id;
    public $notes = '';
    public $student_phone = '';

    public function mount($itemId)
    {
        $this->item = Item::findOrFail($itemId);
        $this->borrow_date = now()->toDateString();
        $this->return_date = now()->addDays(7)->toDateString();
        $this->return_time = '14:00';
        $this->student_phone = Auth::user()->phone ?? '';

        if ($this->item->teacher_id) {
            $this->teacher_id = $this->item->teacher_id;
        }
    }

    public function close()
    {
        $this->dispatch('close-borrow-modal');
    }

    public function submit()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:' . $this->item->stock,
            'purpose' => 'required|string|min:5',
            'student_phone' => 'required|string|min:9|max:20',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'teacher_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->phone = $this->student_phone;
        $user->save();

        if ($this->return_date === $this->borrow_date && $this->return_date === now()->toDateString()) {
            $currentHour = now()->format('H:i');
            if ($this->return_time <= $currentHour) {
                $this->addError('return_time', 'Jam kembali harus lebih besar dari waktu saat ini untuk pengembalian di hari yang sama.');
                return;
            }
        }

        $request = BorrowingRequest::create([
            'user_id' => Auth::id(),
            'item_id' => $this->item->id,
            'teacher_id' => $this->teacher_id,
            'quantity' => $this->quantity,
            'purpose' => $this->purpose,
            'borrow_date' => $this->borrow_date,
            'return_date' => $this->return_date,
            'return_time' => $this->return_time,
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        $this->sendEmailNotification($request);

        session()->flash('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu persetujuan guru.');

        return redirect()->route('student.dashboard');
    }

    protected function sendEmailNotification(BorrowingRequest $request): void
    {
        try {
            app(EmailNotificationService::class)->notifyNewRequest($request);
            app(WhatsAppNotificationService::class)->notifyNewRequest($request);
        } catch (\Exception $e) {
            \Log::error('New request notification failed', [
                'borrowing_request_id' => $request->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        $teachers = User::role('guru')->get();

        return view('livewire.borrowing-form', [
            'teachers' => $teachers,
        ]);
    }
}
