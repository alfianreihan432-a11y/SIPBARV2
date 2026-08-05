<?php

namespace App\Livewire;

use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class LoanManager extends Component
{
    public $students;
    public $items;
    public $borrowings;

    public $student_id = '';
    public $item_id = '';
    public $quantity = 1;
    public $due_at = '';
    public $notes = '';

    protected $rules = [
        'student_id' => 'required|exists:users,id',
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|integer|min:1',
        'due_at' => 'nullable|date',
        'notes' => 'nullable|string',
    ];

    public function mount(): void
    {
        $this->loadStudents();
        $this->loadItems();
        $this->loadBorrowings();
    }

    public function render()
    {
        return view('livewire.loan-manager');
    }

    public function loadStudents(): void
    {
        $this->students = User::role('siswa')->orderBy('name')->get();
    }

    public function loadItems(): void
    {
        $this->items = Item::where('stock', '>', 0)->orderBy('name')->get();
    }

    public function loadBorrowings(): void
    {
        $this->borrowings = Borrowing::with(['user', 'details.item'])->latest()->take(20)->get();
    }

    public function save(): void
    {
        $this->validate();

        $item = Item::findOrFail($this->item_id);

        if ($this->quantity > $item->stock) {
            $this->addError('quantity', 'Jumlah melebihi stok yang tersedia.');
            return;
        }

        $borrowing = Borrowing::create([
            'number' => strtoupper('PNJ-'.str_pad((Borrowing::count() + 1), 4, '0', STR_PAD_LEFT)),
            'user_id' => $this->student_id,
            'borrowed_at' => now(),
            'due_at' => $this->due_at ?: null,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);

        BorrowingDetail::create([
            'borrowing_id' => $borrowing->id,
            'item_id' => $item->id,
            'quantity' => $this->quantity,
            'status' => 'pending',
        ]);

        $item->decrement('stock', $this->quantity);

        $this->resetForm();
        $this->loadItems();
        $this->loadBorrowings();

        session()->flash('message', 'Peminjaman siswa berhasil ditambahkan.');
    }

    public function resetForm(): void
    {
        $this->student_id = '';
        $this->item_id = '';
        $this->quantity = 1;
        $this->due_at = '';
        $this->notes = '';
    }
}
