<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
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
        $query = BorrowingRequest::with(['user', 'item'])->latest();

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
        $borrowing = BorrowingRequest::findOrFail($id);
        $borrowing->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        $this->loadBorrowings();
        session()->flash('message', 'Peminjaman #BR-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) . ' telah disetujui.');
    }

    /**
     * Tandai dipinjam (approved → borrowed)
     */
    public function markBorrowed(int $id): void
    {
        $borrowing = BorrowingRequest::findOrFail($id);
        $borrowing->update([
            'status'      => 'borrowed',
            'borrowed_at' => now(),
        ]);

        $this->loadBorrowings();
        session()->flash('message', 'Peminjaman #BR-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) . ' ditandai dipinjam.');
    }

    /**
     * Tandai dikembalikan (borrowed → returned)
     */
    public function markReturned(int $id): void
    {
        $borrowing = BorrowingRequest::with('item')->findOrFail($id);
        $borrowing->update([
            'status'      => 'returned',
            'returned_at' => now(),
        ]);

        // Kembalikan stok barang
        if ($borrowing->item) {
            $borrowing->item->increment('stock', $borrowing->quantity ?? 1);
        }

        $this->loadBorrowings();
        session()->flash('message', 'Peminjaman #BR-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) . ' berhasil dikembalikan.');
    }

    /**
     * Tolak peminjaman (pending → rejected)
     */
    public function reject(int $id): void
    {
        $borrowing = BorrowingRequest::findOrFail($id);
        $borrowing->update(['status' => 'rejected']);

        $this->loadBorrowings();
        session()->flash('message', 'Peminjaman #BR-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) . ' telah ditolak.');
    }
}
