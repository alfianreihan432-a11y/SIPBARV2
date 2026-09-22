<?php

namespace App\Livewire;

use App\Models\BorrowingRequest;
use Livewire\Component;

class LoanManager extends Component
{
    public $borrowings;
    public string $filterStatus = 'semua';
    public string $filterType = 'semua'; // 'semua' | 'siswa' | 'guru'

    public bool $showModal = false;
    public ?BorrowingRequest $selectedBorrowing = null;

    // ── READONLY MODE for Superadmin ──
    public bool $readonly = false;

    // ── WhatsApp Link Property ──
    public string $waLink = '';

    public function mount(bool $readonly = false): void
    {
        $this->readonly = $readonly;
        $this->loadBorrowings();
    }

    public function render()
    {
        return view('livewire.loan-manager', [
            'typeCounts'   => $this->getTypeCounts(),
            'statusCounts' => $this->getStatusCounts(),
        ]);
    }

    public function updatedFilterStatus(): void
    {
        $this->loadBorrowings();
    }

    public function updatedFilterType(): void
    {
        $this->loadBorrowings();
    }

    public function getTypeCounts(): array
    {
        return [
            'semua' => BorrowingRequest::count(),
            'siswa' => BorrowingRequest::where(function ($q) {
                $q->where('tipe_peminjam', 'siswa')->orWhereNull('tipe_peminjam');
            })->count(),
            'guru'  => BorrowingRequest::where('tipe_peminjam', 'guru')->count(),
        ];
    }

    public function getStatusCounts(): array
    {
        $baseQuery = BorrowingRequest::query();

        if ($this->filterType === 'siswa') {
            $baseQuery->where(function ($q) {
                $q->where('tipe_peminjam', 'siswa')->orWhereNull('tipe_peminjam');
            });
        } elseif ($this->filterType === 'guru') {
            $baseQuery->where('tipe_peminjam', 'guru');
        }

        return [
            'semua'     => (clone $baseQuery)->count(),
            'pending'   => (clone $baseQuery)->where('status', 'pending')->count(),
            'approved'  => (clone $baseQuery)->where('status', 'approved')->count(),
            'borrowed'  => (clone $baseQuery)->where('status', 'borrowed')->count(),
            'returned'  => (clone $baseQuery)->where('status', 'returned')->count(),
            'overdue'   => (clone $baseQuery)->where('status', 'overdue')->count(),
        ];
    }

    public function loadBorrowings(): void
    {
        $query = BorrowingRequest::with([
            'user.classroom',
            'user.jurusan',
            'itemWithTrashed.category',
            'teacher',
            'approvedByKajur',
            'qrCode'
        ])->latest();

        // Filter Tipe (Siswa / Guru)
        if ($this->filterType === 'siswa') {
            $query->where(function ($q) {
                $q->where('tipe_peminjam', 'siswa')->orWhereNull('tipe_peminjam');
            });
        } elseif ($this->filterType === 'guru') {
            $query->where('tipe_peminjam', 'guru');
        }

        // Filter Status
        if ($this->filterStatus !== 'semua') {
            $query->where('status', $this->filterStatus);
        }

        $this->borrowings = $query->get();
    }

    /**
     * Buka Modal Detail Peminjaman
     */
    public function openDetail(int $id): void
    {
        $this->selectedBorrowing = BorrowingRequest::with([
            'user.classroom',
            'user.jurusan',
            'itemWithTrashed.category',
            'teacher',
            'approvedByKajur',
            'qrCode'
        ])->find($id);

        if ($this->selectedBorrowing) {
            $this->showModal = true;
        }
    }

    /**
     * Tutup Modal Detail
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedBorrowing = null;
    }

    /**
     * Setujui peminjaman (pending → approved)
     * Hanya berlaku untuk peminjaman SISWA. Peminjaman GURU disetujui oleh Kepala Jurusan.
     */
    public function approve(int $id): void
    {
        // ── READONLY CHECK: Superadmin cannot approve ──
        if ($this->readonly || auth()->user()->hasRole('superadmin')) {
            session()->flash('error', 'Superadmin tidak memiliki izin untuk menyetujui peminjaman. Halaman ini read-only.');
            return;
        }

        $borrowing = BorrowingRequest::findOrFail($id);

        // Role-based validation: Admin cannot approve teacher requests
        if (auth()->user()->hasRole('admin') && $borrowing->tipe_peminjam === 'guru') {
            session()->flash('error', 'Akses ditolak: Admin tidak dapat menyetujui peminjaman Guru. Silakan hubungi Kepala Jurusan terkait.');
            return;
        }

        // Type-based validation: Teacher requests must go through Kajur
        if ($borrowing->tipe_peminjam === 'guru') {
            session()->flash('error', 'Peminjaman Guru hanya dapat disetujui oleh Kepala Jurusan yang bersangkutan.');
            return;
        }

        try {
            app(\App\Services\BorrowingApprovalService::class)->approve($borrowing, (int) auth()->id());
        } catch (\Exception $e) {
            $borrowing->update([
                'status'      => 'approved',
                'approved_at' => now(),
            ]);
            app(\App\Services\QRCodeService::class)->generateForRequest($borrowing);
        }

        $this->loadBorrowings();
        session()->flash('message', 'Peminjaman Siswa #BR-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) . ' telah disetujui.');
    }

    /**
     * Tandai dipinjam (approved → borrowed)
     */
    public function markBorrowed(int $id): void
    {
        // ── READONLY CHECK: Superadmin cannot mark borrowed ──
        if ($this->readonly || auth()->user()->hasRole('superadmin')) {
            session()->flash('error', 'Superadmin tidak memiliki izin untuk mengubah status peminjaman. Halaman ini read-only.');
            return;
        }

        $borrowing = BorrowingRequest::findOrFail($id);

        // Admin cannot manage teacher borrowing status
        if (auth()->user()->hasRole('admin') && $borrowing->tipe_peminjam === 'guru') {
            session()->flash('error', 'Akses ditolak: Admin tidak dapat mengelola status peminjaman Guru.');
            return;
        }

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
        // ── READONLY CHECK: Superadmin cannot mark returned ──
        if ($this->readonly || auth()->user()->hasRole('superadmin')) {
            session()->flash('error', 'Superadmin tidak memiliki izin untuk mengubah status peminjaman. Halaman ini read-only.');
            return;
        }

        $borrowing = BorrowingRequest::with('item')->findOrFail($id);

        // Admin cannot manage teacher borrowing status
        if (auth()->user()->hasRole('admin') && $borrowing->tipe_peminjam === 'guru') {
            session()->flash('error', 'Akses ditolak: Admin tidak dapat mengelola status peminjaman Guru.');
            return;
        }

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
     * Hanya berlaku untuk peminjaman SISWA. Peminjaman GURU ditolak oleh Kepala Jurusan.
     */
    public function reject(int $id): void
    {
        // ── READONLY CHECK: Superadmin cannot reject ──
        if ($this->readonly || auth()->user()->hasRole('superadmin')) {
            session()->flash('error', 'Superadmin tidak memiliki izin untuk menolak peminjaman. Halaman ini read-only.');
            return;
        }

        $borrowing = BorrowingRequest::findOrFail($id);

        // Role-based validation: Admin cannot reject teacher requests
        if (auth()->user()->hasRole('admin') && $borrowing->tipe_peminjam === 'guru') {
            session()->flash('error', 'Akses ditolak: Admin tidak dapat menolak peminjaman Guru. Silakan hubungi Kepala Jurusan terkait.');
            return;
        }

        // Type-based validation: Teacher requests must go through Kajur
        if ($borrowing->tipe_peminjam === 'guru') {
            session()->flash('error', 'Peminjaman Guru hanya dapat ditolak oleh Kepala Jurusan yang bersangkutan.');
            return;
        }

        $borrowing->update([
            'status' => 'rejected',
        ]);

        $this->loadBorrowings();
        session()->flash('message', 'Peminjaman Siswa #BR-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) . ' telah ditolak.');
    }

    /**
     * Generate WhatsApp warning link for overdue loans
     */
    public function sendWhatsAppWarning(int $id): void
    {
        $borrowing = BorrowingRequest::with(['user', 'itemWithTrashed'])->findOrFail($id);

        // Normalize WhatsApp number
        $phoneNumber = $this->normalizeWhatsAppNumber($borrowing->whatsapp_number);

        if (!$phoneNumber) {
            session()->flash('error', 'Nomor WhatsApp tidak tersedia untuk peminjaman ini.');
            return;
        }

        // Generate warning message
        $message = $this->generateWarningMessage($borrowing);

        // Create wa.me link
        $waLink = "https://wa.me/{$phoneNumber}?text=" . urlencode($message);

        // Dispatch event to open WhatsApp link
        $this->dispatch('openWhatsApp', $waLink);
    }

    /**
     * Normalize WhatsApp number to international format
     */
    private function normalizeWhatsAppNumber(?string $number): ?string
    {
        if (!$number) {
            return null;
        }

        // Remove all non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // If starts with 0, replace with 62
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        // If already starts with 62, keep as is
        // If starts with other prefix (like +62), remove + and ensure 62
        if (str_starts_with($number, '+62')) {
            $number = '62' . substr($number, 3);
        }

        return $number;
    }

    /**
     * Generate warning message template
     */
    private function generateWarningMessage(BorrowingRequest $borrowing): string
    {
        $studentName = $borrowing->user->name ?? 'Siswa';
        $itemName = $borrowing->itemWithTrashed?->name ?? $borrowing->item?->name ?? 'Barang';
        $borrowingNumber = 'BR-' . str_pad($borrowing->id, 4, '0', STR_PAD_LEFT);
        
        $returnDate = $borrowing->return_date->format('d F Y');
        $returnTime = $borrowing->return_time ?? '-';
        $returnDateTime = $returnTime !== '-' ? "{$returnDate} · {$returnTime}" : $returnDate;

        $message = "Assalamu'alaikum/Halo {$studentName},

Kami informasikan bahwa peminjaman barang {$itemName} dengan nomor peminjaman {$borrowingNumber} telah melewati batas waktu pengembalian yang dijadwalkan pada {$returnDateTime}.

Mohon kesediaannya untuk segera mengembalikan barang tersebut ke petugas inventaris SIPBAR SMKN 1 Bangsri. Jika ada kendala, silakan hubungi kami.

Terima kasih atas perhatian dan kerja samanya.";

        return $message;
    }
}
