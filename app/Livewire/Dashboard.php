<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $poll = '5s';
    
    public $totalItems;
    public $borrowedItems;
    public $availableItems;
    public $totalCategories;
    public $recentBorrowings;
    public $itemConditionPercentage;
    
    public function mount(): void
    {
        $this->loadStats();
    }
    
    public function loadStats(): void
    {
        $this->totalItems = Item::count();
        $this->borrowedItems = Item::where('status', 'borrowed')->count();
        $this->availableItems = Item::where('status', 'available')->count();
        $this->totalCategories = Category::count();
        
        $this->recentBorrowings = Borrowing::with(['user', 'details.item'])
            ->latest()
            ->take(5)
            ->get();
            
        $goodConditionItems = Item::where('condition', 'good')->count();
        $this->itemConditionPercentage = $this->totalItems > 0 
            ? round(($goodConditionItems / $this->totalItems) * 100) 
            : 0;
    }
    
    public function render()
    {
        return view('livewire.dashboard');
    }
}
