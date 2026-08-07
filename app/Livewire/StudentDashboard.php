<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentDashboard extends Component
{
    public $poll = '5s';
    
    public $myRequests;
    public $availableItems;
    public $totalBorrowed;
    public $totalReturned;
    public $pendingRequests;
    public $showQRModal = false;
    public $selectedQR = null;
    
    public function mount(): void
    {
        $this->loadStats();
    }
    
    public function loadStats(): void
    {
        $userId = Auth::id();
        
        $this->myRequests = BorrowingRequest::with(['item', 'teacher', 'qrCode'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();
            
        $this->availableItems = Item::where('status', 'Tersedia')
            ->where('condition', 'Baik')
            ->where('stock', '>', 0)
            ->count();
            
        $this->totalBorrowed = BorrowingRequest::where('user_id', $userId)
            ->where('status', 'borrowed')
            ->count();
            
        $this->totalReturned = BorrowingRequest::where('user_id', $userId)
            ->where('status', 'returned')
            ->count();
            
        $this->pendingRequests = BorrowingRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
    }
    
    public function showQRCode($requestId)
    {
        $request = BorrowingRequest::with('qrCode')->findOrFail($requestId);
        
        if ($request->user_id !== Auth::id()) {
            session()->flash('error', 'Anda tidak memiliki izin untuk melihat QR Code ini.');
            return;
        }
        
        if (!$request->qrCode || !$request->qrCode->isValid()) {
            session()->flash('error', 'QR Code tidak tersedia atau tidak valid.');
            return;
        }
        
        $this->selectedQR = $request->qrCode;
        $this->showQRModal = true;
    }
    
    public function closeQRModal()
    {
        $this->showQRModal = false;
        $this->selectedQR = null;
    }
    
    public function render()
    {
        return view('livewire.student-dashboard');
    }
}
