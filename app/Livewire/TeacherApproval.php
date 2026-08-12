<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
use App\Services\BorrowingApprovalService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeacherApproval extends Component
{
    public $pendingRequests;
    public $selectedRequestId = null;
    public $showRejectModal = false;
    public $rejectionReason = '';
    
    protected $listeners = ['refreshRequests' => '$refresh'];
    
    protected $rules = [
        'rejectionReason' => 'required|string|min:10|max:500',
    ];
    
    protected $messages = [
        'rejectionReason.required' => 'Alasan penolakan harus diisi',
        'rejectionReason.min' => 'Alasan penolakan minimal 10 karakter',
        'rejectionReason.max' => 'Alasan penolakan maksimal 500 karakter',
    ];

    public function mount()
    {
        $this->loadRequests();
    }

    public function loadRequests()
    {
        $this->pendingRequests = BorrowingRequest::with(['user', 'item'])
            ->where('teacher_id', Auth::id())
            ->where('status', BorrowingRequest::STATUS_PENDING)
            ->latest()
            ->get();
    }
    
    public function openRejectModal($requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->showRejectModal = true;
        $this->rejectionReason = '';
    }
    
    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->selectedRequestId = null;
        $this->rejectionReason = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.teacher-approval');
    }
}
