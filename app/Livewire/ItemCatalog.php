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

    public function addToCart(int $itemId): void
    {
        $item = Item::findOrFail($itemId);

        if ($item->available_stock <= 0) {
            session()->flash('error', 'Barang tidak tersedia saat ini.');
            return;
        }

        $cart = session('student_borrowing_cart', []);

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = (int) $cart[$itemId]['quantity'] + 1;
        } else {
            $cart[$itemId] = [
                'quantity' => 1,
                'teacher_id' => null,
                'purpose' => '',
            ];
        }

        session(['student_borrowing_cart' => $cart]);

        $this->redirect(route('student.loans.cart'), navigate: true);
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
