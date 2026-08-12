<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryController extends Controller
{
    /**
     * Display transaction history with filters
     */
    public function index(Request $request)
    {
        $query = BorrowingRequest::with(['user', 'item', 'teacher', 'checkoutBy', 'checkinBy']);
        
        // Role-based filtering
        if (Auth::user()->hasRole('guru')) {
            // Teachers see only their assigned transactions
            $query->where('teacher_id', Auth::id());
        }
        // Admin sees all transactions (no filter needed)
        
        // Filter by student name
        if ($request->filled('student_name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->student_name . '%');
            });
        }
        
        // Filter by item name
        if ($request->filled('item_name')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->item_name . '%');
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }
        
        // Sort by latest first
        $query->latest();
        
        // Paginate results
        $transactions = $query->paginate(25)->withQueryString();
        
        // Statistics for dashboard
        $stats = [
            'total' => BorrowingRequest::count(),
            'pending' => BorrowingRequest::where('status', BorrowingRequest::STATUS_PENDING)->count(),
            'approved' => BorrowingRequest::where('status', BorrowingRequest::STATUS_APPROVED)->count(),
            'borrowed' => BorrowingRequest::where('status', BorrowingRequest::STATUS_BORROWED)->count(),
            'returned' => BorrowingRequest::where('status', BorrowingRequest::STATUS_RETURNED)->count(),
            'rejected' => BorrowingRequest::where('status', BorrowingRequest::STATUS_REJECTED)->count(),
        ];
        
        return view('pages.admin.transactions.index', compact('transactions', 'stats'));
    }
    
    /**
     * Show single transaction detail
     */
    public function show(int $id)
    {
        $transaction = BorrowingRequest::with([
            'user',
            'item',
            'teacher',
            'checkoutBy',
            'checkinBy',
            'whatsappLogs'
        ])->findOrFail($id);
        
        // Authorization check for teachers
        if (Auth::user()->hasRole('guru') && $transaction->teacher_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat transaksi ini');
        }
        
        return view('pages.admin.transactions.show', compact('transaction'));
    }
}
