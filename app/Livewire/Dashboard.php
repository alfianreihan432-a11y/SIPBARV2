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
        $this->totalItems      = Item::count();
        // Status di kolom items adalah 'Dipinjam' dan 'Tersedia' (bukan lowercase)
        $this->borrowedItems   = Item::where('status', 'Dipinjam')->count();
        $this->availableItems  = Item::where('status', 'Tersedia')->hasAvailableStock()->count();
        $this->totalCategories = Category::count();

        $this->recentBorrowings = \App\Models\BorrowingRequest::with(['user', 'item', 'itemWithTrashed'])
            ->latest()
            ->take(5)
            ->get();

        $goodConditionItems          = Item::where('condition', 'Baik')->count();
        $this->itemConditionPercentage = $this->totalItems > 0
            ? round(($goodConditionItems / $this->totalItems) * 100)
            : 0;
    }
    
    public function render()
    {
        $this->loadStats();

        return view('livewire.dashboard', [
            'totalItems'              => $this->totalItems,
            'borrowedItems'           => $this->borrowedItems,
            'availableItems'          => $this->availableItems,
            'totalCategories'         => $this->totalCategories,
            'recentBorrowings'        => $this->recentBorrowings,
            'itemConditionPercentage' => $this->itemConditionPercentage,
        ]);
    }
}
