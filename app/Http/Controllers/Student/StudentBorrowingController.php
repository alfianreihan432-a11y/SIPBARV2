<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class StudentBorrowingController extends Controller
{
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
