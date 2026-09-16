<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\LaporanJurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanJurusanController extends Controller
{
    /**
     * Display list of all department reports (Superadmin read-only view)
     */
    public function index(Request $request): View
    {
        $query = LaporanJurusan::with(['jurusan', 'pengirim'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jurusan_id')) {
            $query->where('jurusan_id', $request->jurusan_id);
        }

        $reports = $query->paginate(20)->withQueryString();

        return view('pages.superadmin.laporan-jurusan', [
            'reports' => $reports,
        ]);
    }

    /**
     * Show detailed report (Superadmin read-only view)
     */
    public function show(int $id): View
    {
        $report = LaporanJurusan::with(['jurusan', 'pengirim'])
            ->findOrFail($id);

        $statistics = $report->statistics;
        $borrowingRequests = $report->borrowing_requests;
        $histories = $report->histories()->with(['pengirim', 'jurusan'])->get();

        return view('pages.superadmin.laporan-jurusan-detail', [
            'report' => $report,
            'statistics' => $statistics,
            'borrowingRequests' => $borrowingRequests,
            'histories' => $histories,
        ]);
    }

    /**
     * Approve report (Superadmin can approve department reports)
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $report = LaporanJurusan::findOrFail($id);

        if ($report->status !== LaporanJurusan::STATUS_PENDING_REVIEW) {
            return redirect()->back()
                ->with('error', 'Hanya laporan dengan status Pending Review yang dapat disetujui.');
        }

        $validated = $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $report->update([
            'status' => LaporanJurusan::STATUS_DISETUJUI,
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return redirect()->route('superadmin.laporan-jurusan')
            ->with('success', 'Laporan berhasil disetujui.');
    }

    /**
     * Reject report (Superadmin can reject department reports)
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $report = LaporanJurusan::findOrFail($id);

        if ($report->status !== LaporanJurusan::STATUS_PENDING_REVIEW) {
            return redirect()->back()
                ->with('error', 'Hanya laporan dengan status Pending Review yang dapat ditolak.');
        }

        $validated = $request->validate([
            'catatan_admin' => 'required|string|min:5|max:500',
        ]);

        $report->update([
            'status' => LaporanJurusan::STATUS_DITOLAK,
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        return redirect()->route('superadmin.laporan-jurusan')
            ->with('success', 'Laporan berhasil ditolak.');
    }

    /**
     * Delete report (Superadmin can delete department reports)
     */
    public function destroy(int $id): RedirectResponse
    {
        $report = LaporanJurusan::findOrFail($id);
        $jurusanName = $report->jurusan->nama ?? 'Jurusan';
        $report->delete();

        return redirect()->route('superadmin.laporan-jurusan')
            ->with('success', "Laporan Jurusan {$jurusanName} berhasil dihapus.");
    }
}
