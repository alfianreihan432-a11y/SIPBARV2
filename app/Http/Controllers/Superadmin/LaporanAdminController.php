<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\LaporanAdmin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanAdminController extends Controller
{
    /**
     * Display list of admin reports for superadmin approval
     */
    public function index(Request $request): View
    {
        $query = LaporanAdmin::with(['pengirim'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(20)->withQueryString();

        return view('pages.superadmin.laporan-admin', [
            'reports' => $reports,
        ]);
    }

    /**
     * Show detailed admin report for superadmin review
     */
    public function show(int $id): View
    {
        $report = LaporanAdmin::with(['pengirim'])->findOrFail($id);

        return view('pages.superadmin.laporan-admin-detail', [
            'report' => $report,
        ]);
    }

    /**
     * Approve admin report
     */
    public function approve(Request $request, int $id): RedirectResponse
    {
        $report = LaporanAdmin::findOrFail($id);

        if ($report->status !== LaporanAdmin::STATUS_PENDING_REVIEW) {
            return redirect()->back()
                ->with('error', 'Hanya laporan dengan status Pending Review yang dapat disetujui.');
        }

        $validated = $request->validate([
            'catatan_superadmin' => 'nullable|string|max:500',
        ]);

        $report->update([
            'status' => LaporanAdmin::STATUS_DISETUJUI,
            'catatan_superadmin' => $validated['catatan_superadmin'] ?? null,
        ]);

        return redirect()->route('superadmin.laporan-admin')
            ->with('success', 'Laporan Admin berhasil disetujui.');
    }

    /**
     * Reject admin report
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $report = LaporanAdmin::findOrFail($id);

        if ($report->status !== LaporanAdmin::STATUS_PENDING_REVIEW) {
            return redirect()->back()
                ->with('error', 'Hanya laporan dengan status Pending Review yang dapat ditolak.');
        }

        $validated = $request->validate([
            'catatan_superadmin' => 'required|string|min:5|max:500',
        ]);

        $report->update([
            'status' => LaporanAdmin::STATUS_DITOLAK,
            'catatan_superadmin' => $validated['catatan_superadmin'],
        ]);

        return redirect()->route('superadmin.laporan-admin')
            ->with('success', 'Laporan Admin berhasil ditolak.');
    }

    /**
     * Delete admin report
     */
    public function destroy(int $id): RedirectResponse
    {
        $report = LaporanAdmin::findOrFail($id);
        $report->delete();

        return redirect()->route('superadmin.laporan-admin')
            ->with('success', 'Laporan Admin berhasil dihapus.');
    }
}