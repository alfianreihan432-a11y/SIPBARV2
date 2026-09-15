<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class SuperadminReportController extends Controller
{
    /**
     * Tampilkan halaman Laporan & Statistik Keseluruhan Sistem untuk Super Admin.
     */
    public function index(Request $request)
    {
        // ─── Summary Transaction Stats ───
        $totalRequests     = BorrowingRequest::count();
        $pendingRequests   = BorrowingRequest::where('status', BorrowingRequest::STATUS_PENDING)->count();
        $approvedRequests  = BorrowingRequest::whereIn('status', [
            BorrowingRequest::STATUS_APPROVED,
            BorrowingRequest::STATUS_QR_READY,
            BorrowingRequest::STATUS_BORROWED,
        ])->count();
        $completedRequests = BorrowingRequest::where('status', BorrowingRequest::STATUS_RETURNED)->count();
        $rejectedRequests  = BorrowingRequest::where('status', BorrowingRequest::STATUS_REJECTED)->count();

        // ─── System-wide Inventory & User Stats ───
        $totalItemTypes  = Item::count();
        $totalItemStock  = (int) Item::sum('stock');
        $borrowedStock   = (int) BorrowingRequest::whereIn('status', [
            BorrowingRequest::STATUS_APPROVED,
            BorrowingRequest::STATUS_QR_READY,
            BorrowingRequest::STATUS_BORROWED,
        ])->sum('quantity');
        $availableStock  = max(0, $totalItemStock - $borrowedStock);

        $totalStudents = User::role('siswa')->count();
        $totalTeachers = User::role(['guru', 'kepala_jurusan'])->count();

        // ─── Monthly Stats ───
        $thisMonthRequests = BorrowingRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $completionRate = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100) : 0;
        $rejectionRate  = $totalRequests > 0 ? round(($rejectedRequests / $totalRequests) * 100) : 0;

        // ─── 10 Recent Activities Across System ───
        $recentActivities = BorrowingRequest::with(['user', 'itemWithTrashed', 'teacher'])
            ->latest()
            ->take(10)
            ->get();

        return view('pages.superadmin.reports', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',
            'totalItemTypes',
            'totalItemStock',
            'borrowedStock',
            'availableStock',
            'totalStudents',
            'totalTeachers',
            'thisMonthRequests',
            'completionRate',
            'rejectionRate',
            'recentActivities'
        ));
    }
}
