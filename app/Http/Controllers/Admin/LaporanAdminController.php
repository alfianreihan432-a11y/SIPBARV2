<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanAdmin;
use App\Models\LaporanJurusan;
use App\Models\BorrowingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanAdminController extends Controller
{
    /**
     * Display list of admin reports (for admin to send to superadmin)
     */
    public function index(Request $request): View
    {
        $query = LaporanAdmin::with(['pengirim'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(20)->withQueryString();

        return view('pages.admin.laporan-admin', [
            'reports' => $reports,
        ]);
    }

    /**
     * Show form to create new admin report
     */
    public function create(): View
    {
        // Get approved department reports for consolidation
        $approvedJurusanReports = LaporanJurusan::approved()
            ->with(['jurusan', 'pengirim'])
            ->latest()
            ->get();

        return view('pages.admin.laporan-admin-create', [
            'approvedJurusanReports' => $approvedJurusanReports,
        ]);
    }

    /**
     * Store new admin report
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
            'include_jurusan_reports' => 'nullable|array',
            'include_jurusan_reports.*' => 'exists:laporan_jurusan,id',
        ]);

        // Generate consolidated data
        $dataRekap = $this->generateConsolidatedData(
            $validated['periode_awal'],
            $validated['periode_akhir'],
            $validated['include_jurusan_reports'] ?? []
        );

        LaporanAdmin::create([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'periode_awal' => $validated['periode_awal'],
            'periode_akhir' => $validated['periode_akhir'],
            'dikirim_oleh' => auth()->id(),
            'status' => LaporanAdmin::STATUS_PENDING_REVIEW,
            'data_rekap' => $dataRekap,
        ]);

        return redirect()->route('admin.laporan-admin')
            ->with('success', 'Laporan konsolidasi berhasil dikirim ke Superadmin.');
    }

    /**
     * Show detailed admin report
     */
    public function show(int $id): View
    {
        $report = LaporanAdmin::with(['pengirim'])->findOrFail($id);

        return view('pages.admin.laporan-admin-detail', [
            'report' => $report,
        ]);
    }

    /**
     * Generate consolidated report data
     */
    private function generateConsolidatedData(string $periodeAwal, string $periodeAkhir, array $jurusanReportIds = []): array
    {
        $borrowingsQuery = BorrowingRequest::whereDate('borrow_date', '>=', $periodeAwal)
            ->whereDate('borrow_date', '<=', $periodeAkhir);

        $allBorrowings = $borrowingsQuery->get();

        // Basic statistics
        $data = [
            'total_transaksi' => $allBorrowings->count(),
            'total_dipinjam' => $allBorrowings->whereIn('status', ['approved', 'borrowed'])->count(),
            'total_dikembalikan' => $allBorrowings->where('status', 'returned')->count(),
            'total_pending' => $allBorrowings->where('status', 'pending')->count(),
            'total_rejected' => $allBorrowings->where('status', 'rejected')->count(),
            'barang_kondisi_baik' => $allBorrowings->whereIn('return_condition', ['Baik', 'good'])->count(),
            'barang_kondisi_rusak_ringan' => $allBorrowings->whereIn('return_condition', ['Rusak Ringan', 'damaged'])->count(),
            'barang_kondisi_rusak_berat' => $allBorrowings->whereIn('return_condition', ['Rusak Berat', 'lost'])->count(),
        ];

        // If specific jurusan reports are included, add their data
        if (!empty($jurusanReportIds)) {
            $jurusanReports = LaporanJurusan::whereIn('id', $jurusanReportIds)
                ->with(['jurusan', 'pengirim'])
                ->get();

            $data['jurusan_reports'] = $jurusanReports->map(function ($report) {
                return [
                    'id' => $report->id,
                    'jurusan' => $report->jurusan->nama ?? 'Unknown',
                    'pengirim' => $report->pengirim->name ?? 'Unknown',
                    'periode' => [
                        'awal' => $report->periode_awal->format('Y-m-d'),
                        'akhir' => $report->periode_akhir->format('Y-m-d'),
                    ],
                    'statistics' => $report->statistics,
                ];
            })->toArray();
        }

        return $data;
    }
}