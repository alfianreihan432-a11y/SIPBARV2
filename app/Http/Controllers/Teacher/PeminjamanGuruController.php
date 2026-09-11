<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\Jurusan;
use App\Models\User;
use App\Services\BorrowingApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PeminjamanGuruController extends Controller
{
    /**
     * Display teacher's own borrowing requests
     */
    public function index(): View
    {
        $borrowings = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->with(['itemWithTrashed', 'item', 'approvedByKajur', 'qrCode'])
            ->latest()
            ->get();

        return view('pages.guru.peminjaman-guru', [
            'borrowings' => $borrowings,
        ]);
    }

    /**
     * Display teacher's active QR codes for approved borrowings
     */
    public function qrBarang(): View
    {
        $activeQrLoans = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->whereIn('status', [
                BorrowingRequest::STATUS_APPROVED,
                'qr_ready',
                BorrowingRequest::STATUS_BORROWED,
            ])
            ->with(['itemWithTrashed', 'item', 'approvedByKajur', 'qrCode'])
            ->latest()
            ->get();

        return view('pages.guru.qr-barang', [
            'activeQrLoans' => $activeQrLoans,
        ]);
    }

    /**
     * Show form to create new borrowing request
     */
    public function create(Request $request): View
    {
        $items = Item::where('status', 'Tersedia')
            ->where('stock', '>', 0)
            ->with(['category', 'location'])
            ->get();

        // Get all Kepala Jurusan users with their jurusan relationship
        $kepalaJurusans = User::whereHas('roles', function($query) {
            $query->where('name', 'kepala_jurusan');
        })->whereNotNull('jurusan_id')->with('jurusan')->get();

        // Get current user's jurusan for default selection
        $currentUserJurusan = Auth::user()->jurusan;

        // Get pre-selected item from query parameter
        $preselectedItemId = $request->query('barang_id');

        return view('pages.guru.peminjaman-guru-create', [
            'items' => $items,
            'kepalaJurusans' => $kepalaJurusans,
            'currentUserJurusan' => $currentUserJurusan,
            'preselectedItemId' => $preselectedItemId,
        ]);
    }

    /**
     * Store new borrowing request
     */
    public function store(Request $request): RedirectResponse
    {
        $item = Item::findOrFail($request->item_id);

        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1|max:' . $item->stock,
            'purpose' => 'required|string|min:5',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'kepala_jurusan_id' => 'required|exists:users,id',
        ]);

        if ($validated['return_date'] === $validated['borrow_date'] && $validated['return_date'] === now()->toDateString()) {
            $currentHour = now()->format('H:i');
            if ($validated['return_time'] <= $currentHour) {
                return redirect()->back()->withInput()->withErrors([
                    'return_time' => 'Jam kembali harus lebih besar dari waktu saat ini untuk pengembalian di hari yang sama.',
                ]);
            }
        }

        BorrowingRequest::create([
            'user_id' => Auth::id(),
            'item_id' => $validated['item_id'],
            'quantity' => $validated['quantity'],
            'purpose' => $validated['purpose'],
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'return_time' => $validated['return_time'],
            'notes' => $validated['notes'] ?? null,
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'guru',
            'approved_by_kajur_id' => $validated['kepala_jurusan_id'],
        ]);

        return redirect()->route('teacher.peminjaman-guru')
            ->with('success', 'Permohonan peminjaman berhasil diajukan. Menunggu persetujuan Kepala Jurusan.');
    }

    /**
     * Show edit form for pending borrowing request
     */
    public function edit(int $id): View
    {
        $borrowing = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->with(['item'])
            ->findOrFail($id);

        if ($borrowing->status !== BorrowingRequest::STATUS_PENDING) {
            return redirect()->route('teacher.peminjaman-guru')
                ->with('error', 'Hanya peminjaman yang masih menunggu persetujuan yang dapat diubah.');
        }

        $items = Item::where('status', 'Tersedia')
            ->where('stock', '>', 0)
            ->with(['category', 'location'])
            ->get();

        // Get all Kepala Jurusan users with their jurusan relationship
        $kepalaJurusans = User::whereHas('roles', function($query) {
            $query->where('name', 'kepala_jurusan');
        })->whereNotNull('jurusan_id')->with('jurusan')->get();

        // Get current user's jurusan for default selection
        $currentUserJurusan = Auth::user()->jurusan;

        return view('pages.guru.peminjaman-guru-edit', [
            'borrowing' => $borrowing,
            'items' => $items,
            'kepalaJurusans' => $kepalaJurusans,
            'currentUserJurusan' => $currentUserJurusan,
        ]);
    }

    /**
     * Update borrowing request
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $borrowing = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->findOrFail($id);

        if ($borrowing->status !== BorrowingRequest::STATUS_PENDING) {
            return redirect()->route('teacher.peminjaman-guru')
                ->with('error', 'Hanya peminjaman yang masih menunggu persetujuan yang dapat diubah.');
        }

        $item = Item::findOrFail($borrowing->item_id);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $item->stock,
            'purpose' => 'required|string|min:5',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
            'kepala_jurusan_id' => 'required|exists:users,id',
        ]);

        if ($validated['return_date'] === $validated['borrow_date'] && $validated['return_date'] === now()->toDateString()) {
            $currentHour = now()->format('H:i');
            if ($validated['return_time'] <= $currentHour) {
                return redirect()->back()->withInput()->withErrors([
                    'return_time' => 'Jam kembali harus lebih besar dari waktu saat ini untuk pengembalian di hari yang sama.',
                ]);
            }
        }

        $borrowing->update([
            'quantity' => $validated['quantity'],
            'purpose' => $validated['purpose'],
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'return_time' => $validated['return_time'],
            'notes' => $validated['notes'] ?? null,
            'approved_by_kajur_id' => $validated['kepala_jurusan_id'],
        ]);

        return redirect()->route('teacher.peminjaman-guru')
            ->with('success', 'Permohonan peminjaman berhasil diperbarui.');
    }

    /**
     * Cancel borrowing request
     */
    public function cancel(int $id): RedirectResponse
    {
        $borrowing = BorrowingRequest::where('user_id', Auth::id())
            ->where('tipe_peminjam', 'guru')
            ->findOrFail($id);

        if ($borrowing->status !== BorrowingRequest::STATUS_PENDING) {
            return redirect()->route('teacher.peminjaman-guru')
                ->with('error', 'Hanya peminjaman yang masih menunggu persetujuan yang dapat dibatalkan.');
        }

        $borrowing->update([
            'status' => BorrowingRequest::STATUS_CANCELLED,
        ]);

        return redirect()->route('teacher.peminjaman-guru')
            ->with('success', 'Permohonan peminjaman berhasil dibatalkan.');
    }
}
