<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\Supplier;
use Illuminate\Support\Facades\Cache;
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
    public $nomor_registrasi = '';
    public $ukuran = '';
    public $bahan = '';
    public $tahun_pembelian = '';
    public $asal_usul = '';
    public $harga = '';

    // ── READONLY MODE for Superadmin ──
    public bool $readonly = false;

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
        'nomor_registrasi' => 'nullable|string',
        'ukuran' => 'nullable|in:Kecil,Sedang,Besar',
        'bahan' => 'nullable|string',
        'tahun_pembelian' => 'nullable|integer|min:1900|max:2100',
        'asal_usul' => 'nullable|string',
        'harga' => 'nullable|numeric|min:0',
    ];

    public function mount(bool $readonly = false): void
    {
        $this->readonly = $readonly;
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
        if ($this->readonly) {
            session()->flash('error', 'Mode read-only aktif untuk halaman ini.');
            return;
        }

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
        if ($this->readonly) {
            session()->flash('error', 'Mode read-only aktif untuk halaman ini.');
            return;
        }

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
            'brand' => $this->brand ?: null,
            'type' => $this->type ?: null,
            'purchase_year' => $this->tahun_pembelian ?: ($this->purchase_year ?: null),
            'tahun_pembelian' => $this->tahun_pembelian ?: ($this->purchase_year ?: null),
            'price' => $this->harga ?: ($this->price ?: 0),
            'harga' => $this->harga ?: ($this->price ?: 0),
            'condition' => $this->condition,
            'status' => $this->status,
            'stock' => $this->stock,
            'nomor_registrasi' => $this->nomor_registrasi ?: null,
            'nomor_reg' => $this->nomor_registrasi ?: null,
            'ukuran' => $this->ukuran ?: null,
            'bahan' => $this->bahan ?: null,
            'asal_usul' => $this->asal_usul ?: null,
        ];

        if ($path) {
            $data['photo_path'] = $path;
        }

        if ($this->editingId) {
            Item::findOrFail($this->editingId)->update($data);
        } else {
            $data['code'] = strtoupper('BRG-'.substr(md5(uniqid()), 0, 6));
            $data['inventory_number'] = $this->generateInventoryNumber();
            Item::create($data);
        }

        $this->clearItemCaches();
        $this->resetForm();
        $this->loadItems();
        session()->flash('message', 'Inventaris berhasil disimpan.');
    }

    public function edit($id): void
    {
        if ($this->readonly) {
            session()->flash('error', 'Mode read-only aktif untuk halaman ini.');
            return;
        }

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
        $this->purchase_year = $item->purchase_year ?: $item->tahun_pembelian;
        $this->price = $item->price ?: $item->harga;
        $this->condition = $item->condition;
        $this->status = $item->status;
        $this->stock = $item->stock;
        $this->nomor_registrasi = $item->nomor_registrasi ?: $item->nomor_reg;
        $this->ukuran = $item->ukuran;
        $this->bahan = $item->bahan;
        $this->tahun_pembelian = $item->tahun_pembelian ?: $item->purchase_year;
        $this->asal_usul = $item->asal_usul;
        $this->harga = $item->harga ?: $item->price;
    }

    public function delete($id): void
    {
        if ($this->readonly) {
            session()->flash('error', 'Mode read-only aktif untuk halaman ini.');
            return;
        }

        Item::findOrFail($id)->delete();
        $this->clearItemCaches();
        $this->loadItems();
        $this->dispatch('itemUpdated');
        session()->flash('message', 'Inventaris berhasil dihapus.');
    }

    protected function clearItemCaches(): void
    {
        $keys = [
            'inventory.items',
            'inventory.stats',
            'inventory.total',
            'inventory.summary',
            'inventory.dashboard',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    protected function generateInventoryNumber(): string
    {
        $latest = Item::withTrashed()
            ->where('inventory_number', 'like', 'INV-%')
            ->orderByRaw('CAST(SUBSTRING(inventory_number, 5) AS UNSIGNED) DESC')
            ->value('inventory_number');

        if ($latest) {
            $number = (int) substr($latest, 4) + 1;
        } else {
            $number = 1;
        }

        return 'INV-' . str_pad($number, 4, '0', STR_PAD_LEFT);
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
        $this->nomor_registrasi = '';
        $this->ukuran = '';
        $this->bahan = '';
        $this->tahun_pembelian = '';
        $this->asal_usul = '';
        $this->harga = '';
    }
}
