<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\LaporanGuru;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperadminTeacherReportController extends Controller
{
    /**
     * Tampilkan daftar semua laporan yang dikirim oleh guru.
     */
    public function index(Request $request): View
    {
        $query = LaporanGuru::with('guru')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('periode', 'like', "%{$search}%")
                  ->orWhereHas('guru', function($g) use ($search) {
                      $g->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->paginate(15);
        $unreadCount = LaporanGuru::where('status', LaporanGuru::STATUS_BELUM_DIBACA)->count();

        return view('pages.superadmin.laporan-guru', compact('reports', 'unreadCount'));
    }

    /**
     * Tampilkan detail laporan guru dan otomatis tandai sebagai 'sudah_dibaca'.
     */
    public function show(int $id): View
    {
        $report = LaporanGuru::with('guru')->findOrFail($id);

        if ($report->status === LaporanGuru::STATUS_BELUM_DIBACA) {
            $report->update([
                'status'  => LaporanGuru::STATUS_SUDAH_DIBACA,
                'read_at' => now(),
            ]);
        }

        return view('pages.superadmin.laporan-guru-detail', compact('report'));
    }

    /**
     * Hapus laporan guru.
     */
    public function destroy(int $id): RedirectResponse
    {
        $report = LaporanGuru::findOrFail($id);
        $report->delete();

        return redirect()->route('superadmin.laporan-guru.index')
            ->with('success', 'Laporan Guru berhasil dihapus.');
    }
}
