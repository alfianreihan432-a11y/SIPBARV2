<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\Classroom;
use App\Models\LateWarningLog;
use App\Services\KajurWarningService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KepalaJurusanWarningController extends Controller
{
    protected KajurWarningService $warningService;

    public function __construct(KajurWarningService $warningService)
    {
        $this->warningService = $warningService;
    }

    /**
     * Display overdue warnings page with summary cards, search/filters, and paginated table.
     */
    public function index(Request $request): View
    {
        $kajur = Auth::user();
        $jurusanId = (int) ($kajur->jurusan_id ?? 0);

        if (!$jurusanId) {
            // If kajur has no jurusan_id assigned, return empty view state
            return view('pages.kepala-jurusan.warnings', [
                'borrowings' => collect(),
                'summary' => [
                    'total_overdue' => 0,
                    'overdue_gt_3'  => 0,
                    'overdue_gt_7'  => 0,
                    'warned_today'  => 0,
                ],
                'classrooms' => collect(),
                'activeFilter' => [
                    'q' => '',
                    'kelas' => '',
                    'level' => 'all',
                ],
            ]);
        }

        $baseQuery = $this->warningService->getOverdueQuery($jurusanId);

        // ── Summary Cards Calculations ──
        $allOverdue = (clone $baseQuery)->get();
        $nowJakarta = now()->timezone('Asia/Jakarta')->startOfDay();

        $totalOverdue = $allOverdue->count();
        $overdueGt3 = 0;
        $overdueGt7 = 0;
        $overdueIds = $allOverdue->pluck('id')->toArray();

        foreach ($allOverdue as $item) {
            $days = $this->warningService->calculateDaysOverdue($item);
            if ($days > 7) {
                $overdueGt7++;
                $overdueGt3++;
            } elseif ($days > 3) {
                $overdueGt3++;
            }
        }

        // Count warned within last 24 hours
        $warnedToday = !empty($overdueIds)
            ? LateWarningLog::whereIn('borrowing_request_id', $overdueIds)
                ->where('sent_at', '>=', now()->subHours(24))
                ->distinct('borrowing_request_id')
                ->count('borrowing_request_id')
            : 0;

        $summary = [
            'total_overdue' => $totalOverdue,
            'overdue_gt_3'  => $overdueGt3,
            'overdue_gt_7'  => $overdueGt7,
            'warned_today'  => $warnedToday,
        ];

        // ── Filtering ──
        $query = clone $baseQuery;

        $search = trim($request->input('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('nis', 'like', "%{$search}%")
                       ->orWhere('nip', 'like', "%{$search}%")
                       ->orWhere('kelas', 'like', "%{$search}%");
                })
                ->orWhereHas('item', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('kode_kibb', 'like', "%{$search}%");
                })
                ->orWhereHas('itemWithTrashed', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('items.itemWithTrashed', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%");
                });
            });
        }

        $kelasFilter = trim($request->input('kelas', ''));
        if ($kelasFilter !== '') {
            $query->whereHas('user', function ($uq) use ($kelasFilter) {
                $uq->where('kelas', $kelasFilter);
            });
        }

        $levelFilter = trim($request->input('level', 'all'));
        if ($levelFilter === 'gt3') {
            // Overdue > 3 days (return_date < now - 3 days)
            $cutoff = now()->timezone('Asia/Jakarta')->subDays(3)->toDateString();
            $query->whereDate('return_date', '<', $cutoff);
        } elseif ($levelFilter === 'gt7') {
            // Overdue > 7 days (return_date < now - 7 days)
            $cutoff = now()->timezone('Asia/Jakarta')->subDays(7)->toDateString();
            $query->whereDate('return_date', '<', $cutoff);
        } elseif ($levelFilter === '1-3') {
            // Overdue 1 - 3 days
            $cutoff3 = now()->timezone('Asia/Jakarta')->subDays(3)->toDateString();
            $cutoff0 = now()->timezone('Asia/Jakarta')->toDateString();
            $query->whereDate('return_date', '>=', $cutoff3)
                  ->whereDate('return_date', '<=', $cutoff0);
        }

        // Pagination 12 per page with withQueryString()
        $borrowings = $query->orderBy('return_date', 'asc')->paginate(12)->withQueryString();

        // Get class list of students in this jurusan for filter dropdown
        $jurusan = $kajur->jurusan;
        $jurusanKode = $jurusan?->kode ?? '';
        $classrooms = Classroom::query();
        if ($jurusanKode) {
            $classrooms->where('name', 'like', "%{$jurusanKode}%");
        }
        $classroomsList = $classrooms->orderBy('name')->pluck('name');

        return view('pages.kepala-jurusan.warnings', [
            'borrowings' => $borrowings,
            'summary' => $summary,
            'classrooms' => $classroomsList,
            'activeFilter' => [
                'q' => $search,
                'kelas' => $kelasFilter,
                'level' => $levelFilter,
            ],
            'warningService' => $this->warningService,
        ]);
    }

    /**
     * Send warning for a single borrowing request.
     */
    public function send(Request $request, int $id)
    {
        $kajur = Auth::user();
        $borrowing = BorrowingRequest::with(['user.jurusan', 'items.itemWithTrashed', 'itemWithTrashed'])->findOrFail($id);

        // Security / Scope Authorization: Kajur ONLY can send warnings to their own jurusan's borrowers
        if ((int) ($borrowing->user?->jurusan_id ?? 0) !== (int) ($kajur->jurusan_id ?? -1)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Peminjaman ini bukan milik siswa/guru di jurusan Anda.',
                ], 403);
            }
            abort(403, 'Akses ditolak: Peminjaman ini bukan milik siswa/guru di jurusan Anda.');
        }

        $result = $this->warningService->sendWarning($borrowing, $kajur);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => $result['status'] === 'sent',
                'status' => $result['status'],
                'message' => $result['message'],
                'wa_link' => $result['wa_link'],
            ]);
        }

        if ($result['status'] === 'skipped_antispam') {
            return redirect()->back()
                ->with('warning', $result['message'])
                ->with('wa_link', $result['wa_link']);
        }

        return redirect()->back()
            ->with('success', $result['message'])
            ->with('wa_link', $result['wa_link']);
    }

    /**
     * Send bulk warnings to all overdue loans in kajur's jurusan (max 50).
     */
    public function sendAll(Request $request): RedirectResponse
    {
        $kajur = Auth::user();
        $jurusanId = (int) ($kajur->jurusan_id ?? 0);

        if (!$jurusanId) {
            return redirect()->back()->with('error', 'Akun Anda belum ditautkan ke jurusan manapun.');
        }

        $stats = $this->warningService->sendBulkWarnings($jurusanId, $kajur, 50);

        $msg = "Proses peringatan massal selesai: {$stats['sent']} terkirim";
        if ($stats['skipped_antispam'] > 0) {
            $msg .= ", {$stats['skipped_antispam']} dilewati (anti-spam 24 jam)";
        }
        if ($stats['failed'] > 0) {
            $msg .= ", {$stats['failed']} gagal";
        }
        $msg .= ".";

        if ($stats['sent'] > 0) {
            return redirect()->back()->with('success', $msg);
        } elseif ($stats['skipped_antispam'] > 0) {
            return redirect()->back()->with('warning', $msg);
        } else {
            return redirect()->back()->with('info', 'Tidak ada peminjaman terlambat yang memerlukan peringatan.');
        }
    }

    /**
     * Preview warning message (AJAX helper for modal).
     */
    public function preview(Request $request, int $id): JsonResponse
    {
        $kajur = Auth::user();
        $borrowing = BorrowingRequest::with(['user.jurusan', 'items.itemWithTrashed', 'itemWithTrashed'])->findOrFail($id);

        if ((int) ($borrowing->user?->jurusan_id ?? 0) !== (int) ($kajur->jurusan_id ?? -1)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = $this->warningService->generateWarningMessage($borrowing, $kajur);
        $waLink = $this->warningService->generateWhatsAppLink($borrowing, $kajur);
        $hasRecent = LateWarningLog::hasRecentWarning($borrowing->id);

        return response()->json([
            'borrowing_id' => $borrowing->id,
            'borrower_name' => $borrowing->user?->name,
            'phone' => $borrowing->whatsapp_number ?? $borrowing->user?->phone,
            'email' => $borrowing->user?->email,
            'message' => $message,
            'wa_link' => $waLink,
            'has_recent_warning' => $hasRecent,
        ]);
    }
}
