<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentBorrowingController extends Controller
{
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
