<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ItemCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $showBorrowModal = false;
    public $selectedItem = null;

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->categoryFilter = '';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function openBorrowModal($itemId)
    {
        $this->selectedItem = Item::with('category')->findOrFail($itemId);
        $this->showBorrowModal = true;
    }

    #[On('close-borrow-modal')]
    public function closeBorrowModal()
    {
        $this->showBorrowModal = false;
        $this->selectedItem = null;
    }

    public function getItemsProperty()
    {
        $query = Item::with('category', 'teacher')
            ->where('status', 'Tersedia')
            ->where('condition', 'Baik')
            ->hasAvailableStock();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category_id', $this->categoryFilter);
        }

        return $query->latest()->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return Category::all();
    }

    public function render()
    {
        return view('livewire.item-catalog', [
            'items' => $this->items,
            'categories' => $this->categories,
        ]);
    }
}
