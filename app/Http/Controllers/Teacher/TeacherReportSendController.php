<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\LaporanGuru;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeacherReportSendController extends Controller
{
    /**
     * Kirim snapshot laporan peminjaman guru ke Super Admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $teacher = auth()->user();
        $teacherId = $teacher->id;

        // Ambil snapshot data statistik guru saat ini
        $totalRequests     = BorrowingRequest::where('teacher_id', $teacherId)->count();
        $pendingRequests   = BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'pending')->count();
        $approvedRequests  = BorrowingRequest::where('teacher_id', $teacherId)->whereIn('status', ['approved', 'borrowed', 'qr_ready'])->count();
        $completedRequests = BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'returned')->count();
        $rejectedRequests  = BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'rejected')->count();

        $thisMonthRequests = BorrowingRequest::where('teacher_id', $teacherId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $activeStudents = BorrowingRequest::where('teacher_id', $teacherId)
            ->distinct('user_id')
            ->count('user_id');

        $completionRate = $totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100) : 0;
        $rejectionRate  = $totalRequests > 0 ? round(($rejectedRequests / $totalRequests) * 100) : 0;

        $monthName = now()->translatedFormat('F Y');
        $judul = 'Laporan Peminjaman Siswa Bimbingan - ' . $teacher->name;
        $periode = 'Periode ' . $monthName;

        $snapshot = [
            'total_requests'      => $totalRequests,
            'pending_requests'    => $pendingRequests,
            'approved_requests'   => $approvedRequests,
            'completed_requests'  => $completedRequests,
            'rejected_requests'   => $rejectedRequests,
            'this_month_requests' => $thisMonthRequests,
            'active_students'     => $activeStudents,
            'completion_rate'     => $completionRate,
            'rejection_rate'      => $rejectionRate,
        ];

        LaporanGuru::create([
            'guru_id'      => $teacherId,
            'judul'        => $judul,
            'periode'      => $periode,
            'data_laporan' => $snapshot,
            'catatan'      => $request->input('catatan'),
            'status'       => LaporanGuru::STATUS_BELUM_DIBACA,
        ]);

        return redirect()->route('teacher.reports')
            ->with('success', 'Laporan berhasil dikirimkan ke Super Admin!');
    }
}
