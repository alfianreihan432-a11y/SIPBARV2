<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\LaporanJurusan;
use App\Models\LaporanJurusanHistory;
use App\Services\BorrowingApprovalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KepalaJurusanController extends Controller
{
    /**
     * Scope query untuk permohonan guru yang ditujukan ke Kajur ini:
     * - Guru yang memilih Kajur ini saat submit form (approved_by_kajur_id == Kajur ID)
     * - Atau Guru yang memiliki jurusan_id sama dengan jurusan Kajur ini
     */
    private function kajurScopeQuery(): \Closure
    {
        $kajurId = (int) Auth::id();
        $jurusanId = Auth::user()->jurusan_id ? (int) Auth::user()->jurusan_id : null;

        return function ($query) use ($kajurId, $jurusanId) {
            $query->where(function ($q) use ($kajurId, $jurusanId) {
                $q->where('approved_by_kajur_id', $kajurId);
                if ($jurusanId) {
                    $q->orWhereHas('user', function ($uq) use ($jurusanId) {
                        $uq->where('jurusan_id', $jurusanId);
                    });
                }
            });
        };
    }

    /**
     * Display Kepala Jurusan dashboard
     */
    public function dashboard(): View
    {
        $scope = $this->kajurScopeQuery();
        $kajurId = (int) Auth::id();
        $jurusanId = Auth::user()->jurusan_id ? (int) Auth::user()->jurusan_id : null;

        // Get statistics
        $stats = [
            'pending_approvals' => BorrowingRequest::where('tipe_peminjam', 'guru')
                ->where($scope)
                ->where('status', BorrowingRequest::STATUS_PENDING)
                ->count(),
            'active_borrowings' => BorrowingRequest::where('tipe_peminjam', 'guru')
                ->where($scope)
                ->whereIn('status', [BorrowingRequest::STATUS_APPROVED, BorrowingRequest::STATUS_BORROWED])
                ->count(),
            'pending_returns' => \App\Models\ItemReturn::guru()
                ->where(function ($query) use ($kajurId, $jurusanId) {
                    $query->where('kajur_id', $kajurId);
                    if ($jurusanId) {
                        $query->orWhereHas('user', function ($uq) use ($jurusanId) {
                            $uq->where('jurusan_id', $jurusanId);
                        });
                    }
                })
                ->where('status', \App\Models\ItemReturn::STATUS_MENUNGGU)
                ->count(),
            'total_completed' => \App\Models\ItemReturn::guru()
                ->where(function ($query) use ($kajurId, $jurusanId) {
                    $query->where('kajur_id', $kajurId);
                    if ($jurusanId) {
                        $query->orWhereHas('user', function ($uq) use ($jurusanId) {
                            $uq->where('jurusan_id', $jurusanId);
                        });
                    }
                })
                ->where('status', \App\Models\ItemReturn::STATUS_DISETUJUI)
                ->count(),
        ];

        // Get recent pending approvals
        $pendingApprovals = BorrowingRequest::where('tipe_peminjam', 'guru')
            ->where($scope)
            ->where('status', BorrowingRequest::STATUS_PENDING)
            ->with(['user', 'item.category', 'itemWithTrashed.category', 'items.itemWithTrashed.category', 'items.item.category'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent active borrowings
        $activeBorrowings = BorrowingRequest::where('tipe_peminjam', 'guru')
            ->where($scope)
            ->whereIn('status', [BorrowingRequest::STATUS_APPROVED, BorrowingRequest::STATUS_BORROWED])
            ->with(['user', 'item.category', 'itemWithTrashed.category', 'items.itemWithTrashed.category', 'items.item.category'])
            ->latest()
            ->take(5)
            ->get();

        return view('pages.kepala-jurusan.dashboard', [
            'stats' => $stats,
            'pendingApprovals' => $pendingApprovals,
            'activeBorrowings' => $activeBorrowings,
        ]);
    }

    /**
     * Display pending teacher borrowing requests for approval
     */
    public function pendingApprovals(Request $request): View
    {
        $query = BorrowingRequest::where('tipe_peminjam', 'guru')
            ->where($this->kajurScopeQuery())
            ->where('status', BorrowingRequest::STATUS_PENDING)
            ->with(['user', 'item.category', 'itemWithTrashed.category', 'items.itemWithTrashed.category', 'items.item.category']);

        if ($search = trim((string) $request->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('nip', 'like', "%{$search}%");
                })->orWhereHas('items.itemWithTrashed', function ($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                })->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        $pendingRequests = $query->latest()->paginate(20);

        return view('pages.kepala-jurusan.pending-approvals', [
            'pendingRequests' => $pendingRequests,
            'search' => $search,
        ]);
    }

    /**
     * Approve teacher borrowing request
     */
    public function approveRequest(Request $request, int $id, BorrowingApprovalService $approvalService): RedirectResponse
    {
        // Role validation: Only kepala_jurusan can approve teacher requests
        if (!Auth::user()->hasRole('kepala_jurusan')) {
            abort(403, 'Akses ditolak: Hanya Kepala Jurusan yang dapat menyetujui permohonan peminjaman Guru.');
        }

        $borrowing = BorrowingRequest::where('tipe_peminjam', 'guru')
            ->where($this->kajurScopeQuery())
            ->where('status', BorrowingRequest::STATUS_PENDING)
            ->findOrFail($id);

        try {
            $kajurId = (int) Auth::id();
            $approvalService->approve($borrowing, $kajurId);

            // Update approved_by_kajur_id
            $borrowing->update(['approved_by_kajur_id' => $kajurId]);

            // Refresh model dan pastikan relasi qrCode ter-generate
            $borrowing->refresh();
            if (! $borrowing->qrCode) {
                app(\App\Services\QRCodeService::class)->generateForRequest($borrowing);
            }

            return redirect()->back()
                ->with('success', 'Permohonan peminjaman guru berhasil disetujui. QR Code telah diterbitkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menyetujui permohonan: ' . $e->getMessage());
        }
    }

    /**
     * Reject teacher borrowing request
     */
    public function rejectRequest(Request $request, int $id, BorrowingApprovalService $approvalService): RedirectResponse
    {
        // Role validation: Only kepala_jurusan can reject teacher requests
        if (!Auth::user()->hasRole('kepala_jurusan')) {
            abort(403, 'Akses ditolak: Hanya Kepala Jurusan yang dapat menolak permohonan peminjaman Guru.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5|max:500',
        ]);

        $borrowing = BorrowingRequest::where('tipe_peminjam', 'guru')
            ->where($this->kajurScopeQuery())
            ->where('status', BorrowingRequest::STATUS_PENDING)
            ->findOrFail($id);

        try {
            $kajurId = (int) Auth::id();
            $approvalService->reject($borrowing, $validated['rejection_reason'], $kajurId);

            return redirect()->back()
                ->with('success', 'Permohonan peminjaman guru berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menolak permohonan: ' . $e->getMessage());
        }
    }

    /**
     * Show QR scanner page
     */
    public function qrScanner(): View
    {
        return view('pages.kepala-jurusan.qr-scanner');
    }

    /**
     * Verify QR code (reusing AdminQRVerificationController logic)
     */
    public function verifyQR(string $token): View
    {
        $qrRecord = \App\Models\QRCode::where('code', $token)
            ->with(['borrowingRequest.user', 'borrowingRequest.itemWithTrashed', 'borrowingRequest.items.itemWithTrashed'])
            ->first();

        if (!$qrRecord) {
            return view('pages.kepala-jurusan.qr-verify', [
                'valid' => false,
                'message' => 'QR Code tidak ditemukan dalam sistem.',
            ]);
        }

        if ($qrRecord->isExpired()) {
            return view('pages.kepala-jurusan.qr-verify', [
                'valid' => false,
                'message' => 'QR Code sudah kadaluarsa.',
            ]);
        }

        $borrowingRequest = $qrRecord->borrowingRequest;

        if (!$borrowingRequest) {
            return view('pages.kepala-jurusan.qr-verify', [
                'valid' => false,
                'message' => 'Data permohonan peminjaman tidak ditemukan.',
            ]);
        }

        // Check if this is a teacher borrowing for this Kajur / Jurusan
        $kajurId = (int) Auth::id();
        $jurusanId = Auth::user()->jurusan_id ? (int) Auth::user()->jurusan_id : null;
        $isAssignedKajur = ($borrowingRequest->approved_by_kajur_id && (int) $borrowingRequest->approved_by_kajur_id === $kajurId);
        $isSameJurusan = ($jurusanId && $borrowingRequest->user?->jurusan_id && (int) $borrowingRequest->user->jurusan_id === $jurusanId);

        if ($borrowingRequest->tipe_peminjam !== 'guru' || (!$isAssignedKajur && !$isSameJurusan)) {
            return view('pages.kepala-jurusan.qr-verify', [
                'valid' => false,
                'message' => 'QR Code ini bukan untuk peminjaman guru yang ditujukan ke Anda / jurusan Anda.',
            ]);
        }

        if (!$qrRecord->is_active && $borrowingRequest->status !== BorrowingRequest::STATUS_REJECTED) {
            return view('pages.kepala-jurusan.qr-verify', [
                'valid' => false,
                'message' => 'QR Code sudah tidak aktif.',
            ]);
        }

        return view('pages.kepala-jurusan.qr-verify', [
            'valid' => true,
            'qrRecord' => $qrRecord,
            'borrowingRequest' => $borrowingRequest,
        ]);
    }

    /**
     * Confirm checkout via QR scan
     */
    public function confirmCheckout(Request $request, int $id): RedirectResponse
    {
        $borrowingRequest = BorrowingRequest::where('tipe_peminjam', 'guru')
            ->where($this->kajurScopeQuery())
            ->with('itemWithTrashed', 'qrCode')
            ->findOrFail($id);

        if (!in_array($borrowingRequest->status, ['approved', 'qr_ready'])) {
            return redirect()->back()->with('error', 'Status peminjaman saat ini (' . $borrowingRequest->status_label . ') tidak dapat dikonfirmasi pengambilan.');
        }

        // Cek stok barang jika item masih ada di inventaris aktif
        $item = $borrowingRequest->item;
        if ($item) {
            if ($item->stock < $borrowingRequest->quantity) {
                return redirect()->back()->with('error', 'Stok barang tidak mencukupi untuk memenuhi peminjaman.');
            }
            // Kurangi stok barang
            $item->decrement('stock', $borrowingRequest->quantity);
        }

        // Update status peminjaman menjadi borrowed / barang diambil
        $borrowingRequest->update([
            'status' => BorrowingRequest::STATUS_BORROWED,
            'borrowed_at' => now(),
            'checkout_by' => Auth::id(),
        ]);

        // Catat aktivitas scan pada record QR Code
        if ($borrowingRequest->qrCode) {
            $borrowingRequest->qrCode->update([
                'scanned_at' => $borrowingRequest->qrCode->scanned_at ?? now(),
                'last_scanned_at' => now(),
                'scan_count' => ($borrowingRequest->qrCode->scan_count ?? 0) + 1,
            ]);
        }

        return redirect()->route('kajur.qr-scanner')
            ->with('success', 'Pengambilan barang berhasil dikonfirmasi! Status kini menjadi Dipinjam.');
    }

    /**
     * Display pending teacher returns for verification
     */
    public function pendingReturns(Request $request): View
    {
        $kajurId = (int) Auth::id();
        $jurusanId = Auth::user()->jurusan_id ? (int) Auth::user()->jurusan_id : null;

        // Use ItemReturn table with proper filtering for guru returns assigned to this Kajur
        $query = \App\Models\ItemReturn::guru()
            ->where(function ($query) use ($kajurId, $jurusanId) {
                $query->where('kajur_id', $kajurId);
                if ($jurusanId) {
                    $query->orWhereHas('user', function ($uq) use ($jurusanId) {
                        $uq->where('jurusan_id', $jurusanId);
                    });
                }
            })
            ->where('status', \App\Models\ItemReturn::STATUS_MENUNGGU)
            ->with([
                'borrowingRequest.item.category',
                'borrowingRequest.itemWithTrashed.category',
                'user',
                'kajur'
            ]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhereHas('borrowingRequest.item', function($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                })->orWhereHas('borrowingRequest.itemWithTrashed', function($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        $pendingReturns = $query->latest()->paginate(20)->withQueryString();

        return view('pages.kepala-jurusan.pending-returns', [
            'pendingReturns' => $pendingReturns,
        ]);
    }

    /**
     * Verify and approve teacher return
     */
    public function verifyReturn(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'verified_condition' => 'required|in:Baik,Rusak Ringan,Rusak Berat,Hilang',
            'verified_notes' => 'nullable|string|max:500',
        ]);

        $kajurId = (int) Auth::id();
        $jurusanId = Auth::user()->jurusan_id ? (int) Auth::user()->jurusan_id : null;

        // Find the ItemReturn record for this Kajur
        $itemReturn = \App\Models\ItemReturn::guru()
            ->where(function ($query) use ($kajurId, $jurusanId) {
                $query->where('kajur_id', $kajurId);
                if ($jurusanId) {
                    $query->orWhereHas('user', function ($uq) use ($jurusanId) {
                        $uq->where('jurusan_id', $jurusanId);
                    });
                }
            })
            ->where('status', \App\Models\ItemReturn::STATUS_MENUNGGU)
            ->with(['borrowingRequest.item', 'borrowingRequest.itemWithTrashed', 'user'])
            ->findOrFail($id);

        $borrowing = $itemReturn->borrowingRequest;

        // Update ItemReturn status
        $conditionEnumMap = [
            'Baik'        => 'baik',
            'Rusak Ringan'=> 'rusak_ringan',
            'Rusak Berat' => 'rusak_berat',
            'Hilang'      => 'hilang',
        ];

        $itemReturn->update([
            'status'             => \App\Models\ItemReturn::STATUS_DISETUJUI,
            'kondisi_barang'     => $conditionEnumMap[$validated['verified_condition']] ?? 'baik',
            'catatan'            => $validated['verified_notes'] ?? $itemReturn->catatan,
            'diverifikasi_oleh'  => $kajurId,
            'tanggal_verifikasi' => now(),
        ]);

        // Update BorrowingRequest to sync with ItemReturn
        $borrowing->update([
            'return_condition' => $validated['verified_condition'],
            'return_notes'     => $validated['verified_notes'] ?? $borrowing->return_notes,
            'checkin_by'       => $kajurId,
        ]);

        // Update kondisi item sesuai verifikasi
        $item = $borrowing->item;
        if ($item) {
            if ($validated['verified_condition'] === 'Hilang') {
                $item->update(['status' => 'Hilang', 'condition' => 'Hilang']);
            } elseif ($validated['verified_condition'] === 'Rusak Berat') {
                $item->update(['status' => 'Rusak', 'condition' => 'Rusak Berat']);
                $item->increment('stock', $borrowing->quantity);
            } else {
                // Baik atau Rusak Ringan — kembalikan stok
                $item->increment('stock', $borrowing->quantity);
                if ($validated['verified_condition'] === 'Rusak Ringan') {
                    $item->update(['condition' => 'Rusak Ringan']);
                }
            }
        }

        // Notifikasi in-app ke guru
        try {
            \App\Models\Notification::sendToUser(
                $borrowing->user_id,
                'pengembalian_disetujui',
                "Pengembalian barang '{$borrowing->item?->name}' telah DIVERIFIKASI oleh Kepala Jurusan. Terima kasih!",
                ['borrowing_id' => $borrowing->id]
            );
        } catch (\Exception $e) {
            // Non-fatal — lanjut
        }

        return redirect()->route('kajur.pending-returns')
            ->with('success', 'Pengembalian barang berhasil diverifikasi dan stok diperbarui.');
    }

    /**
     * Display borrowing history for the jurusan
     */
    public function history(Request $request): View
    {
        $query = BorrowingRequest::where('tipe_peminjam', 'guru')
            ->where($this->kajurScopeQuery())
            ->with([
                'user.jurusan',
                'item.category',
                'item.location',
                'itemWithTrashed.category',
                'itemWithTrashed.location',
                'approvedByKajur',
                'checkoutBy',
                'checkinBy'
            ]);

        // Apply filters
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                })->orWhereHas('item', function($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                })->orWhereHas('itemWithTrashed', function($iq) use ($search) {
                    $iq->where('name', 'like', "%{$search}%")
                       ->orWhere('code', 'like', "%{$search}%");
                })->orWhereHas('items.item', function($miq) use ($search) {
                    $miq->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('borrow_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('borrow_date', '<=', $request->date_to);
        }

        $history = $query->latest('borrow_date')->paginate(20)->withQueryString();

        return view('pages.kepala-jurusan.history', [
            'history' => $history,
        ]);
    }

    /**
     * Display reporting page
     */
    public function reporting(): View
    {
        $jurusanId = Auth::user()->jurusan_id;

        $existingReports = LaporanJurusan::where('jurusan_id', $jurusanId)
            ->with(['jurusan', 'pengirim', 'histories.pengirim'])
            ->latest()
            ->get();

        return view('pages.kepala-jurusan.reporting', [
            'existingReports' => $existingReports,
        ]);
    }

    /**
     * Create and send new report
     */
    public function createReport(Request $request): RedirectResponse
    {
        $jurusanId = Auth::user()->jurusan_id;

        if (!$jurusanId) {
            return redirect()->back()
                ->with('error', 'Akun Kepala Jurusan tidak terhubung dengan jurusan manapun.');
        }

        $validated = $request->validate([
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ], [
            'periode_awal.required' => 'Periode awal wajib diisi.',
            'periode_awal.date' => 'Format tanggal periode awal tidak valid.',
            'periode_akhir.required' => 'Periode akhir wajib diisi.',
            'periode_akhir.date' => 'Format tanggal periode akhir tidak valid.',
            'periode_akhir.after_or_equal' => 'Periode akhir harus sama dengan atau setelah periode awal.',
        ]);

        $existing = LaporanJurusan::where('jurusan_id', $jurusanId)->first();

        if ($existing) {
            // Archive existing report state to history so previous submissions & admin notes are preserved
            LaporanJurusanHistory::create([
                'laporan_jurusan_id' => $existing->id,
                'jurusan_id'         => $existing->jurusan_id,
                'periode_awal'       => $existing->periode_awal,
                'periode_akhir'      => $existing->periode_akhir,
                'dikirim_oleh'       => $existing->dikirim_oleh,
                'status'             => $existing->status,
                'catatan_admin'      => $existing->catatan_admin,
                'submitted_at'       => $existing->created_at,
                'reviewed_at'        => in_array($existing->status, [LaporanJurusan::STATUS_DISETUJUI, LaporanJurusan::STATUS_DITOLAK]) ? $existing->updated_at : null,
            ]);

            // Update the existing active row with new submission data and reset status to pending_review
            $existing->update([
                'periode_awal'  => $validated['periode_awal'],
                'periode_akhir' => $validated['periode_akhir'],
                'dikirim_oleh'  => Auth::id(),
                'status'        => LaporanJurusan::STATUS_PENDING_REVIEW,
                'catatan_admin' => null,
            ]);

            $existing->created_at = now();
            $existing->save();
        } else {
            LaporanJurusan::create([
                'jurusan_id'   => $jurusanId,
                'periode_awal' => $validated['periode_awal'],
                'periode_akhir' => $validated['periode_akhir'],
                'dikirim_oleh' => Auth::id(),
                'status'       => LaporanJurusan::STATUS_PENDING_REVIEW,
                'catatan_admin' => null,
            ]);
        }

        return redirect()->route('kajur.reporting')
            ->with('success', 'Laporan berhasil diperbarui dan dikirim ke Admin untuk review.');
    }

    /**
     * Display Kepala Jurusan profile page
     */
    public function profile(): View
    {
        return view('pages.kepala-jurusan.profile', [
            'user' => Auth::user()->load('jurusan'),
        ]);
    }
}
