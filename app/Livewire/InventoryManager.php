<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithFileUploads;

class InventoryManager extends Component
{
    use WithFileUploads;

    protected $listeners = ['itemUpdated' => 'loadItems'];
    
    public $poll = '5s';
    
    public $items;
    public $search = '';
    public $filterCategory = '';
    public $filterStatus = '';
    public $filterCondition = '';
    public $viewMode = 'grid'; // grid or table
    public $showForm = false;

    public $name = '';
    public $description = '';
    public $category_id = '';
    public $location_id = '';
    public $supplier_id = '';
    public $brand = '';
    public $type = '';
    public $purchase_year = '';
    public $price = '';
    public $condition = 'Baik';
    public $status = 'Tersedia';
    public $stock = 1;
    public $photo;
    public $editingId = null;

    protected $rules = [
        'name' => 'required|string|min:3',
        'description' => 'nullable|string',
        'category_id' => 'nullable|exists:categories,id',
        'location_id' => 'nullable|exists:locations,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'brand' => 'nullable|string',
        'type' => 'nullable|string',
        'purchase_year' => 'nullable|digits:4',
        'price' => 'nullable|numeric',
        'condition' => 'required|string',
        'status' => 'required|string',
        'stock' => 'required|integer|min:1',
        'photo' => 'nullable|image|max:2048',
    ];

    public function mount(): void
    {
        $this->loadItems();
    }

    public function updatedSearch(): void
    {
        $this->loadItems();
    }

    public function updatedFilterCategory(): void
    {
        $this->loadItems();
    }

    public function updatedFilterStatus(): void
    {
        $this->loadItems();
    }

    public function updatedFilterCondition(): void
    {
        $this->loadItems();
    }

    public function toggleForm(): void
    {
        $this->showForm = !$this->showForm;
    }

    public function setViewMode($mode): void
    {
        $this->viewMode = $mode;
    }

    public function render()
    {
        $allItems = Item::all();
        $stats = [
            'total' => $allItems->count(),
            'total_stock' => $allItems->sum('stock'),
            'tersedia' => $allItems->where('status', 'Tersedia')->count(),
            'dipinjam' => $allItems->where('status', 'Dipinjam')->count(),
            'maintenance' => $allItems->where('status', 'Maintenance')->count(),
            'baik' => $allItems->where('condition', 'Baik')->count(),
        ];

        return view('livewire.inventory-manager', [
            'categories' => Category::latest()->get(),
            'locations' => Location::latest()->get(),
            'suppliers' => Supplier::latest()->get(),
            'stats' => $stats,
        ]);
    }

    public function loadItems(): void
    {
        $query = Item::query()->with(['category', 'location', 'supplier']);

        if ($this->search !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('inventory_number', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%")
                  ->orWhere('brand', 'like', "%{$this->search}%")
                  ->orWhere('type', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterCategory !== '') {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterCondition !== '') {
            $query->where('condition', $this->filterCondition);
        }

        $this->items = $query->latest()->get();
    }

    public function save(): void
    {
        $this->validate();

        $path = null;
        if ($this->photo) {
            $path = $this->photo->store('items', 'public');
        }

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id ?: null,
            'location_id' => $this->location_id ?: null,
            'supplier_id' => $this->supplier_id ?: null,
            'brand' => $this->brand,
            'type' => $this->type,
            'purchase_year' => $this->purchase_year ?: null,
            'price' => $this->price ?: 0,
            'condition' => $this->condition,
            'status' => $this->status,
            'stock' => $this->stock,
            'photo_path' => $path,
            'code' => strtoupper('BRG-'.substr(md5(uniqid()), 0, 6)),
            'inventory_number' => 'INV-'.str_pad((Item::count() + 1), 4, '0', STR_PAD_LEFT),
        ];

        if ($this->editingId) {
            Item::findOrFail($this->editingId)->update($data);
        } else {
            Item::create($data);
        }

        $this->resetForm();
        $this->loadItems();
        session()->flash('message', 'Inventaris berhasil disimpan.');
    }

    public function edit($id): void
    {
        $item = Item::findOrFail($id);
        $this->editingId = $item->id;
        $this->showForm = true;
        $this->name = $item->name;
        $this->description = $item->description;
        $this->category_id = $item->category_id;
        $this->location_id = $item->location_id;
        $this->supplier_id = $item->supplier_id;
        $this->brand = $item->brand;
        $this->type = $item->type;
        $this->purchase_year = $item->purchase_year;
        $this->price = $item->price;
        $this->condition = $item->condition;
        $this->status = $item->status;
        $this->stock = $item->stock;
    }

    public function delete($id): void
    {
        Item::findOrFail($id)->delete();
        $this->loadItems();
        $this->dispatch('itemUpdated');
        session()->flash('message', 'Inventaris berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->description = '';
        $this->category_id = '';
        $this->location_id = '';
        $this->supplier_id = '';
        $this->brand = '';
        $this->type = '';
        $this->purchase_year = '';
        $this->price = '';
        $this->condition = 'Baik';
        $this->status = 'Tersedia';
        $this->stock = 1;
        $this->photo = null;
        $this->editingId = null;
    }
}
