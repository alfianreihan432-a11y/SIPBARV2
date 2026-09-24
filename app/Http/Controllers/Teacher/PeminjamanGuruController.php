<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
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
    public function cart(): View
    {
        $items = Item::where('status', 'Tersedia')->where('stock', '>', 0)->get();
        $cart = session('teacher_borrowing_cart', []);

        $cartItems = [];
        foreach ($cart as $itemId => $entry) {
            $item = Item::with('category')->find($itemId);
            if ($item) {
                $cartItems[] = [
                    'item' => $item,
                    'quantity' => (int) ($entry['quantity'] ?? 1),
                ];
            }
        }

        return view('pages.guru.peminjaman-guru-cart', [
            'items' => $items,
            'cartItems' => $cartItems,
            'kepalaJurusans' => User::whereHas('roles', fn ($query) => $query->where('name', 'kepala_jurusan'))->get(),
        ]);
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session('teacher_borrowing_cart', []);
        $itemId = (int) $validated['item_id'];

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = (int) $cart[$itemId]['quantity'] + (int) $validated['quantity'];
        } else {
            $cart[$itemId] = [
                'quantity' => (int) $validated['quantity'],
            ];
        }

        session(['teacher_borrowing_cart' => $cart]);

        return redirect()->route('teacher.peminjaman-guru.cart')->with('success', 'Barang ditambahkan ke keranjang guru.');
    }

    public function updateCartItem(Request $request, int $itemId): RedirectResponse
    {
        $item = Item::findOrFail($itemId);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . max(1, $item->stock),
        ], [
            'quantity.required' => 'Jumlah unit harus diisi.',
            'quantity.min' => 'Jumlah unit minimal 1.',
            'quantity.max' => 'Jumlah unit melebihi stok yang tersedia (' . $item->stock . ' unit).',
        ]);

        $cart = session('teacher_borrowing_cart', []);

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = (int) $validated['quantity'];
            session(['teacher_borrowing_cart' => $cart]);

            return redirect()->route('teacher.peminjaman-guru.cart')->with('success', 'Jumlah barang ' . $item->name . ' berhasil diperbarui.');
        }

        return redirect()->route('teacher.peminjaman-guru.cart')->with('error', 'Barang tidak ditemukan di keranjang.');
    }

    public function removeFromCart(int $itemId): RedirectResponse
    {
        $cart = session('teacher_borrowing_cart', []);
        unset($cart[$itemId]);
        session(['teacher_borrowing_cart' => $cart]);

        return redirect()->route('teacher.peminjaman-guru.cart')->with('success', 'Barang dihapus dari keranjang.');
    }

    public function submitCart(Request $request): RedirectResponse
    {
        $cart = session('teacher_borrowing_cart', []);

        if (empty($cart)) {
            return redirect()->route('teacher.peminjaman-guru.cart')->with('error', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'kepala_jurusan_id' => 'required|exists:users,id',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'purpose' => 'required|string|min:5',
            'notes' => 'nullable|string|max:500',
        ], [
            'kepala_jurusan_id.required' => 'Kepala jurusan tujuan wajib dipilih.',
            'kepala_jurusan_id.exists' => 'Kepala jurusan yang dipilih tidak valid.',
            'borrow_date.required' => 'Tanggal pinjam wajib diisi.',
            'borrow_date.after_or_equal' => 'Tanggal pinjam tidak boleh sebelum hari ini.',
            'return_date.required' => 'Tanggal kembali wajib diisi.',
            'return_date.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
            'return_time.required' => 'Batas jam pengembalian wajib diisi.',
            'purpose.required' => 'Tujuan/keperluan peminjaman wajib diisi.',
            'purpose.min' => 'Tujuan peminjaman harus minimal 5 karakter.',
            'notes.max' => 'Catatan tambahan maksimal 500 karakter.',
        ]);

        $header = BorrowingRequest::create([
            'user_id' => Auth::id(),
            'item_id' => null,
            'teacher_id' => null, // Fixed: teacher_id should be null for guru own borrowing
            'quantity' => null,
            'purpose' => $validated['purpose'],
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'return_time' => $validated['return_time'],
            'notes' => $validated['notes'] ?? null,
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'guru',
            'approved_by_kajur_id' => $validated['kepala_jurusan_id'],
        ]);

        foreach ($cart as $itemId => $entry) {
            $item = Item::find($itemId);
            if (! $item) {
                continue;
            }

            BorrowingRequestItem::create([
                'borrowing_request_id' => $header->id,
                'item_id' => $item->id,
                'quantity' => (int) ($entry['quantity'] ?? 1),
                'kondisi_saat_pinjam' => 'baik',
            ]);
        }

        session()->forget('teacher_borrowing_cart');

        return redirect()->route('teacher.peminjaman-guru')->with('success', 'Permohonan peminjaman multi-barang berhasil diajukan.');
    }

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
        })->whereNotNull('jurusan_id')->get();
        
        // Manually load jurusan relationship for each user
        foreach ($kepalaJurusans as $kajur) {
            $kajur->load('jurusan');
        }

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
            'teacher_id' => null, // Fixed: teacher_id should be null for guru own borrowing
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
        })->whereNotNull('jurusan_id')->get();
        
        // Manually load jurusan relationship for each user
        foreach ($kepalaJurusans as $kajur) {
            $kajur->load('jurusan');
        }

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
            'teacher_id' => null, // Fixed: ensure teacher_id remains null for guru own borrowing
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
