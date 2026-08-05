<?php

namespace App\Livewire;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\User;
use Livewire\Component;

class LoanManager extends Component
{
    public $borrowings;
    public $filterStatus = 'semua';

    public function mount(): void
    {
        $this->loadBorrowings();
    }

    public function render()
    {
        return view('livewire.loan-manager');
    }

    public function updatedFilterStatus(): void
    {
        $this->loadBorrowings();
    }

    public function loadBorrowings(): void
    {
        $query = Borrowing::with(['user', 'details.item'])->latest();

        if ($this->filterStatus !== 'semua') {
            $query->where('status', $this->filterStatus);
        }

        $this->borrowings = $query->get();
    }

    /**
     * Setujui peminjaman (pending → approved)
     */
    public function approve(int $id): void
    {
        $borrowing = Borrowing::findOrFail($id);
        $borrowing->update(['status' => 'borrowed']);

        $this->loadBorrowings();
        session()->flash('message', 'Peminjaman #' . $borrowing->number . ' telah disetujui.');
    }

    /**
     * Tandai dikembalikan (approved/borrowed → returned)
     */
    public function markReturned(int $id): void
    {
        $borrowing = Borrowing::with('details.item')->findOrFail($id);
        $borrowing->update([
            'status'      => 'returned',
            'returned_at' => now(),
        ]);

        // Kembalikan stok
        foreach ($borrowing->details as $detail) {
            if ($detail->item) {
                $detail->item->increment('stock', $detail->quantity);
            }
        }

        $this->loadBorrowings();
        session()->flash('message', 'Peminjaman #' . $borrowing->number . ' berhasil dikembalikan.');
    }
}
