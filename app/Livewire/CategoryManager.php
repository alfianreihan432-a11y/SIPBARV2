<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryManager extends Component
{
    protected $listeners = ['categoryUpdated' => 'loadCategories'];
    
    public $poll = '5s';
    
    public $categories;
    public $name = '';
    public $icon = '';
    public $color = '#2563eb';
    public $description = '';
    public $editingId = null;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|min:3',
        'icon' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:20',
        'description' => 'nullable|string',
    ];

    public function mount(): void
    {
        $this->loadCategories();
    }

    public function render()
    {
        $this->categories = Category::withCount('items')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->latest()->get();
        return view('livewire.category-manager');
    }

    public function loadCategories(): void
    {
        $this->categories = Category::withCount('items')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->latest()->get();
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'icon' => $this->icon,
            'color' => $this->color,
            'description' => $this->description,
        ];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
            session()->flash('message', 'Kategori berhasil diperbarui.');
        } else {
            Category::create($data);
            session()->flash('message', 'Kategori berhasil ditambahkan.');
        }

        $this->resetForm();
        $this->loadCategories();
        $this->dispatch('categoryUpdated');
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->icon = $category->icon;
        $this->color = $category->color;
        $this->description = $category->description;
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        $this->dispatch('categoryUpdated');
        $this->loadCategories();
        session()->flash('message', 'Kategori berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->icon = '';
        $this->color = '#2563eb';
        $this->description = '';
        $this->editingId = null;
    }
}
