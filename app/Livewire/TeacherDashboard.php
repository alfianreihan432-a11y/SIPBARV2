<?php

namespace App\Livewire;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeacherDashboard extends Component
{
    public $poll = '5s';
    
    public $myBorrowings;
    public $availableItems;
    public $totalBorrowed;
    public $totalReturned;
    public $departmentItems;
    public $recentDepartmentBorrowings;
    
    public function mount(): void
    {
        $this->loadStats();
    }
    
    public function loadStats(): void
    {
        $userId = Auth::id();
        
        $this->myBorrowings = Borrowing::with(['details.item'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();
            
        $this->availableItems = Item::where('status', 'Tersedia')
            ->where('condition', 'Baik')
            ->count();
            
        $this->totalBorrowed = Borrowing::where('user_id', $userId)
            ->whereIn('status', ['approved', 'borrowed'])
            ->count();
            
        $this->totalReturned = Borrowing::where('user_id', $userId)
            ->where('status', 'returned')
            ->count();
            
        $this->departmentItems = Item::count();
        
        $this->recentDepartmentBorrowings = Borrowing::with(['user', 'details.item'])
            ->latest()
            ->take(5)
            ->get();
    }
    
    public function render()
    {
        return view('livewire.teacher-dashboard');
    }
}
