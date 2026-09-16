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

        $validated = $request->validate([
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $requestHeader = BorrowingRequest::create([
            'user_id' => Auth::id(),
            'teacher_id' => $request->input('teacher_id') ?? null,
            'purpose' => $request->input('purpose') ?? 'Peminjaman multi-barang',
            'borrow_date' => $validated['borrow_date'],
            'return_date' => $validated['return_date'],
            'return_time' => $validated['return_time'],
            'notes' => $validated['notes'] ?? null,
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'siswa',
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

        return view('pages.siswa.loans-edit', [
            'borrowing' => $borrowing,
            'teachers' => $teachers,
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

        $item = Item::findOrFail($borrowing->item_id);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $item->stock,
            'purpose' => 'required|string|min:5',
            'borrow_date' => 'required|date|after_or_equal:today',
            'return_date' => 'required|date|after_or_equal:borrow_date',
            'return_time' => 'required|date_format:H:i',
            'teacher_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($data['return_date'] === $data['borrow_date'] && $data['return_date'] === now()->toDateString()) {
            $currentHour = now()->format('H:i');
            if ($data['return_time'] <= $currentHour) {
                return redirect()->back()->withInput()->withErrors([
                    'return_time' => 'Jam kembali harus lebih besar dari waktu saat ini untuk pengembalian di hari yang sama.',
                ]);
            }
        }

        $borrowing->update([
            'quantity' => $data['quantity'],
            'purpose' => $data['purpose'],
            'borrow_date' => $data['borrow_date'],
            'return_date' => $data['return_date'],
            'return_time' => $data['return_time'],
            'teacher_id' => $data['teacher_id'],
            'notes' => $data['notes'] ?? null,
        ]);

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
}
