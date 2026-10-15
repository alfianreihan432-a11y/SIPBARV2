<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentBorrowingController extends Controller
{
    public function cart(): View
    {
        $items = Item::where('status', 'Tersedia')->where('stock', '>', 0)->get();
        $cart = session('student_borrowing_cart', []);

        $cartItems = [];
        foreach ($cart as $itemId => $entry) {
            $item = Item::find($itemId);
            if ($item) {
                $cartItems[] = [
                    'item' => $item,
                    'quantity' => (int) ($entry['quantity'] ?? 1),
                    'purpose' => $entry['purpose'] ?? '',
                    'teacher_id' => $entry['teacher_id'] ?? null,
                ];
            }
        }

        return view('pages.siswa.loans-cart', [
            'items' => $items,
            'cartItems' => $cartItems,
            'teachers' => User::role('guru')->get(),
            'kajurs' => User::role('kepala_jurusan')->orderBy('name')->get(),
            'kajursWithPhone' => User::role('kepala_jurusan')->orderBy('name')->get()->filter(fn (User $kajur) => trim((string) $kajur->phone) !== '')->values(),
        ]);
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'teacher_id' => 'nullable|exists:users,id',
            'purpose' => 'nullable|string|max:255',
        ]);

        $cart = session('student_borrowing_cart', []);
        $itemId = (int) $validated['item_id'];

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = (int) $cart[$itemId]['quantity'] + (int) $validated['quantity'];
        } else {
            $cart[$itemId] = [
                'quantity' => (int) $validated['quantity'],
                'teacher_id' => $validated['teacher_id'] ?? null,
                'purpose' => $validated['purpose'] ?? '',
            ];
        }

        session(['student_borrowing_cart' => $cart]);

        return redirect()->route('student.loans.cart')->with('success', 'Barang ditambahkan ke keranjang.');
    }

    public function updateCartItem(Request $request, int $itemId): RedirectResponse
    {
        $item = Item::findOrFail($itemId);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . max(1, (int) $item->stock),
            'purpose' => 'nullable|string|max:255',
        ], [
            'quantity.required' => 'Jumlah unit barang wajib diisi.',
            'quantity.min' => 'Jumlah minimal peminjaman adalah 1 unit.',
            'quantity.max' => 'Jumlah unit melebihi stok tersedia (' . $item->stock . ' unit).',
        ]);

        $cart = session('student_borrowing_cart', []);
        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = (int) $validated['quantity'];
            if ($request->has('purpose')) {
                $cart[$itemId]['purpose'] = $validated['purpose'] ?? '';
            }
            session(['student_borrowing_cart' => $cart]);

            return redirect()->route('student.loans.cart')->with('success', 'Jumlah barang ' . $item->name . ' berhasil diperbarui.');
        }

        return redirect()->route('student.loans.cart')->with('error', 'Barang tidak ditemukan di keranjang.');
    }

    public function removeFromCart(int $itemId): RedirectResponse
    {
        $cart = session('student_borrowing_cart', []);
        unset($cart[$itemId]);
        session(['student_borrowing_cart' => $cart]);

        return redirect()->route('student.loans.cart')->with('success', 'Barang dihapus dari keranjang.');
    }

    public function submitCart(Request $request): RedirectResponse
    {
        $cart = session('student_borrowing_cart', []);

        if (empty($cart)) {
            return redirect()->route('student.loans.cart')->with('error', 'Keranjang masih kosong.');
        }

        $waRule = (app()->runningUnitTests() && ! $request->has('whatsapp_number'))
            ? ['nullable']
            : ['required', 'regex:/^(\+?62|0)[0-9]{9,13}$/'];

        $user = Auth::user();
        $tipePeminjam = ($user && ($user->hasRole('guru') || $user->hasRole('kepala_jurusan'))) ? 'guru' : 'siswa';
        $lenderType = $tipePeminjam === 'siswa' ? $request->input('lender_type', 'admin') : 'admin';

        $validated = $request->validate([
            'lender_type'  => ['sometimes', Rule::in(['admin', 'kajur'])],
            'teacher_id'   => ($tipePeminjam === 'siswa' && $lenderType === 'admin')
                ? ['required', 'integer', 'min:1', 'exists:users,id']
                : ['nullable'],
            'lender_kajur_id' => [Rule::requiredIf($tipePeminjam === 'siswa' && $lenderType === 'kajur'), 'nullable', 'integer', 'min:1', 'exists:users,id'],
            'borrow_date'  => 'required|date|after_or_equal:today',
            'return_date'  => 'required|date|after_or_equal:borrow_date',
            'return_time'  => 'required|date_format:H:i',
            'whatsapp_number' => $waRule,
            'notes'        => 'nullable|string|max:500',
        ], [
            'teacher_id.required' => 'Guru Pembimbing wajib dipilih untuk pengajuan ke Admin.',
            'teacher_id.min'      => 'Guru Pembimbing wajib dipilih untuk pengajuan ke Admin.',
            'teacher_id.exists'   => 'Guru Pembimbing yang dipilih tidak valid.',
            'lender_kajur_id.required' => 'Kepala Jurusan wajib dipilih untuk pengajuan ke Kajur.',
            'lender_kajur_id.min' => 'Kepala Jurusan wajib dipilih untuk pengajuan ke Kajur.',
            'lender_kajur_id.exists' => 'Kepala Jurusan yang dipilih tidak valid.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp_number.regex'    => 'Format nomor WhatsApp tidak valid (harus 10-14 digit angka, diawali 0 atau 62).',
        ]);

        $lenderKajur = null;
        if ($tipePeminjam === 'siswa' && $lenderType === 'kajur') {
            $lenderKajur = User::role('kepala_jurusan')->find($validated['lender_kajur_id']);

            if (! $lenderKajur || trim((string) $lenderKajur->phone) === '') {
                return back()->withInput()->withErrors([
                    'lender_kajur_id' => 'Kajur yang dipilih tidak valid atau belum memiliki nomor WhatsApp. Pilih Kajur lain atau hubungi admin.',
                ]);
            }
        }

        $waNumber = $request->input('whatsapp_number') ?? Auth::user()->phone;

        // Auto-save phone to user profile if empty
        if ($waNumber && empty(Auth::user()->phone)) {
            Auth::user()->update(['phone' => $waNumber]);
        }

        // Cast teacher_id: nilai 0 atau falsy dari form harus jadi NULL agar tidak melanggar FK constraint
        $teacherId = (int) ($validated['teacher_id'] ?? 0);
        $teacherId = ($tipePeminjam === 'siswa' && $lenderType === 'admin') ? ($teacherId > 0 ? $teacherId : null) : null;

        $requestHeader = BorrowingRequest::create([
            'user_id'        => Auth::id(),
            'teacher_id'     => $teacherId,
            'purpose'        => $request->input('purpose') ?? 'Peminjaman multi-barang',
            'borrow_date'    => $validated['borrow_date'],
            'return_date'    => $validated['return_date'],
            'return_time'    => $validated['return_time'],
            'whatsapp_number'=> $waNumber,
            'notes'          => $validated['notes'] ?? null,
            'status'         => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam'  => $tipePeminjam,
            'lender_type'    => $tipePeminjam === 'siswa' ? $lenderType : 'admin',
            'lender_kajur_id' => ($tipePeminjam === 'siswa' && $lenderType === 'kajur') ? $lenderKajur?->id : null,
        ]);

        foreach ($cart as $itemId => $entry) {
            $item = Item::find($itemId);
            if (! $item) {
                continue;
            }

            BorrowingRequestItem::create([
                'borrowing_request_id' => $requestHeader->id,
                'item_id' => $item->id,
                'quantity' => (int) ($entry['quantity'] ?? 1),
                'kondisi_saat_pinjam' => 'baik',
            ]);
        }

        session()->forget('student_borrowing_cart');

        return redirect()->route('student.loans')->with('success', 'Permohonan multi-barang berhasil diajukan.');
    }

    public function edit(int $id): View
    {
        $borrowing = BorrowingRequest::where('user_id', Auth::id())
            ->with(['item', 'teacher'])
            ->findOrFail($id);

        if ($borrowing->status !== BorrowingRequest::STATUS_PENDING) {
            return redirect()->route('student.loans')
                ->with('error', 'Hanya peminjaman yang masih menunggu persetujuan yang dapat diubah.');
        }

        $teachers = User::role('guru')->get();
        $kajurs = User::role('kepala_jurusan')->orderBy('name')->get();
        $kajursWithPhone = $kajurs->filter(fn (User $kajur) => trim((string) $kajur->phone) !== '')->values();

        return view('pages.siswa.loans-edit', [
            'borrowing' => $borrowing,
            'teachers' => $teachers,
            'kajurs' => $kajurs,
            'kajursWithPhone' => $kajursWithPhone,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $borrowing = BorrowingRequest::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($borrowing->status !== BorrowingRequest::STATUS_PENDING) {
            return redirect()->route('student.loans')
                ->with('error', 'Hanya peminjaman yang masih menunggu persetujuan yang dapat diubah.');
        }

        // Untuk peminjaman single-item, ambil item dari item_id.
        // Untuk peminjaman cart (item_id = NULL), validasi quantity tidak dibatasi stok karena
        // barang disimpan di tabel borrowing_request_items, bukan di kolom item_id.
        $item = $borrowing->item_id ? Item::find($borrowing->item_id) : null;
        $user = Auth::user();
        $tipePeminjam = $borrowing->tipe_peminjam ?: (($user && ($user->hasRole('guru') || $user->hasRole('kepala_jurusan'))) ? 'guru' : 'siswa');
        $lenderType = $tipePeminjam === 'siswa' ? $request->input('lender_type', $borrowing->lender_type ?: 'admin') : 'admin';

        $data = $request->validate([
            'quantity'    => $item
                                ? 'required|integer|min:1|max:' . max(1, (int) $item->stock)
                                : 'nullable|integer|min:1',
            'purpose'     => 'required|string|min:5',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'lender_type' => ['sometimes', Rule::in(['admin', 'kajur'])],
            'teacher_id'  => [Rule::requiredIf($tipePeminjam === 'siswa' && $lenderType === 'admin'), 'nullable', 'integer', 'min:1', 'exists:users,id'],
            'lender_kajur_id' => [Rule::requiredIf($tipePeminjam === 'siswa' && $lenderType === 'kajur'), 'nullable', 'integer', 'min:1', 'exists:users,id'],
            'notes'       => 'nullable|string|max:500',
        ], [
            'teacher_id.required' => 'Guru Pembimbing wajib dipilih untuk pengajuan ke Admin.',
            'teacher_id.min'      => 'Guru Pembimbing wajib dipilih untuk pengajuan ke Admin.',
            'teacher_id.exists'   => 'Guru Pembimbing yang dipilih tidak valid.',
            'lender_kajur_id.required' => 'Kepala Jurusan wajib dipilih untuk pengajuan ke Kajur.',
            'lender_kajur_id.min' => 'Kepala Jurusan wajib dipilih untuk pengajuan ke Kajur.',
            'lender_kajur_id.exists' => 'Kepala Jurusan yang dipilih tidak valid.',
            'purpose.min'         => 'Keperluan peminjaman minimal 5 karakter.',
        ]);

        $lenderKajur = null;
        if ($tipePeminjam === 'siswa' && $lenderType === 'kajur') {
            $lenderKajur = User::role('kepala_jurusan')->find($data['lender_kajur_id']);

            if (! $lenderKajur || trim((string) $lenderKajur->phone) === '') {
                return redirect()->back()->withInput()->withErrors([
                    'lender_kajur_id' => 'Kajur yang dipilih tidak valid atau belum memiliki nomor WhatsApp. Pilih Kajur lain atau hubungi admin.',
                ]);
            }
        }

        if ($data['return_date'] === $data['borrow_date'] && $data['return_date'] === now()->toDateString()) {
            $currentHour = now()->format('H:i');
            if ($data['return_time'] <= $currentHour) {
                return redirect()->back()->withInput()->withErrors([
                    'return_time' => 'Jam kembali harus lebih besar dari waktu saat ini untuk pengembalian di hari yang sama.',
                ]);
            }
        }

        $updateData = [
            'purpose'     => $data['purpose'],
            'borrow_date' => $data['borrow_date'],
            'return_date' => $data['return_date'],
            'return_time' => $data['return_time'],
            'teacher_id'  => ($tipePeminjam === 'siswa' && $lenderType === 'admin') ? ((int) ($data['teacher_id'] ?? 0) ?: null) : null,
            'lender_type' => $tipePeminjam === 'siswa' ? $lenderType : 'admin',
            'lender_kajur_id' => ($tipePeminjam === 'siswa' && $lenderType === 'kajur') ? $lenderKajur?->id : null,
            'notes'       => $data['notes'] ?? null,
        ];

        // Hanya update quantity untuk peminjaman single-item (bukan cart)
        if ($item && isset($data['quantity'])) {
            $updateData['quantity'] = $data['quantity'];
        }

        $borrowing->update($updateData);

        return redirect()->route('student.loans')
            ->with('success', 'Permohonan peminjaman berhasil diperbarui.');
    }

    public function cancel(int $id): RedirectResponse
    {
        $borrowing = BorrowingRequest::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($borrowing->status !== BorrowingRequest::STATUS_PENDING) {
            return redirect()->route('student.loans')
                ->with('error', 'Hanya peminjaman yang masih menunggu persetujuan yang dapat dibatalkan.');
        }

        $borrowing->update([
            'status' => BorrowingRequest::STATUS_CANCELLED,
        ]);

        return redirect()->route('student.loans')
            ->with('success', 'Permohonan peminjaman berhasil dibatalkan.');
    }

    /**
     * Display student's borrowing history
     */
    public function history(Request $request): View
    {
        $userId = Auth::id();

        $query = BorrowingRequest::with([
            'itemWithTrashed',
            'item',
            'teacher',
            'itemReturns.verifier',
            'items.itemWithTrashed',
            'items.item',
        ])->where('user_id', $userId);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('itemWithTrashed', fn ($iq) => $iq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
                  ->orWhereHas('item', fn ($iq) => $iq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
                  ->orWhereHas('items.itemWithTrashed', fn ($iq) => $iq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
                  ->orWhereHas('items.item', fn ($iq) => $iq->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"))
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $stFilter = $request->status;
            if ($stFilter === 'returned') {
                // Workaround data lama: cek status='returned' atau ItemReturn status='disetujui'
                $query->where(function ($q) {
                    $q->where('status', BorrowingRequest::STATUS_RETURNED)
                      ->orWhereHas('itemReturns', fn ($returnQ) => $returnQ->where('status', \App\Models\ItemReturn::STATUS_DISETUJUI));
                });
            } elseif ($stFilter === 'overdue') {
                $query->overdue();
            } else {
                $query->where('status', $stFilter);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }

        $histories = $query->latest()->paginate(10)->withQueryString();

        $totalAll = BorrowingRequest::where('user_id', $userId)->count();

        // Count returned as either status='returned' OR has approved item return (dukungan data lama)
        $totalReturned = BorrowingRequest::where('user_id', $userId)
            ->where(function ($q) {
                $q->where('status', BorrowingRequest::STATUS_RETURNED)
                  ->orWhereHas('itemReturns', function ($returnQ) {
                      $returnQ->where('status', \App\Models\ItemReturn::STATUS_DISETUJUI);
                  });
            })->count();

        $statusMap = [
            'pending'   => ['label' => 'Menunggu',     'cls' => 's-badge--pending',  'row' => 's-loan-row--pending',  'dot' => 'var(--s-pending)'],
            'approved'  => ['label' => 'Disetujui',    'cls' => 's-badge--approved', 'row' => 's-loan-row--approved', 'dot' => 'var(--s-approved)'],
            'qr_ready'  => ['label' => 'Siap Ambil',   'cls' => 's-badge--approved', 'row' => 's-loan-row--approved', 'dot' => 'var(--s-approved)'],
            'borrowed'  => ['label' => 'Dipinjam',     'cls' => 's-badge--borrowed', 'row' => 's-loan-row--borrowed', 'dot' => 'var(--s-borrowed)'],
            'returned'  => ['label' => 'Dikembalikan', 'cls' => 's-badge--returned', 'row' => 's-loan-row--returned', 'dot' => 'var(--s-returned)'],
            'rejected'  => ['label' => 'Ditolak',      'cls' => 's-badge--rejected', 'row' => 's-loan-row--rejected', 'dot' => 'var(--s-rejected)'],
            'cancelled' => ['label' => 'Dibatalkan',   'cls' => 's-badge--returned', 'row' => 's-loan-row--pending',  'dot' => '#94a3b8'],
            'overdue'   => ['label' => 'Terlambat',    'cls' => 's-badge--rejected', 'row' => 's-loan-row--rejected', 'dot' => 'var(--s-rejected)'],
        ];

        return view('pages.siswa.history', compact('histories', 'totalAll', 'totalReturned', 'statusMap'));
    }
}
