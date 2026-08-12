<?php

namespace App\Livewire;

use App\Models\Borrowing;
use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class TeacherDashboard extends Component
{
    public $poll = '5s';
    
    public $myBorrowings;
    public $availableItems;
    public $totalBorrowed;
    public $totalReturned;
    public $departmentItems;
    public $recentDepartmentBorrowings;
    public $pendingRequests;
    public $upcomingDeadlines;
    public $myStudentsCount;
    
    public function mount(): void
    {
        $this->loadStats();
    }
    
    public function loadStats(): void
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        // Existing stats
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
        
        // NEW: Pending requests count (for badge in menu)
        $this->pendingRequests = BorrowingRequest::where('teacher_id', $userId)
            ->where('teacher_approved', false)
            ->whereNull('teacher_approval_at')
            ->count();
        
        // NEW: Upcoming deadlines (return date within 3 days)
        $this->upcomingDeadlines = Borrowing::with(['user', 'details.item'])
            ->where('status', 'borrowed')
            ->whereBetween('return_date', [Carbon::now(), Carbon::now()->addDays(3)])
            ->orderBy('return_date', 'asc')
            ->take(5)
            ->get();
        
        // NEW: My students count (students with same jurusan as teacher)
        if ($user && $user->jurusan) {
            $this->myStudentsCount = User::role('siswa')
                ->where('jurusan', $user->jurusan)
                ->count();
        } else {
            $this->myStudentsCount = 0;
        }
    }
    
    public function render()
    {
        return view('livewire.teacher-dashboard');
    }
}
