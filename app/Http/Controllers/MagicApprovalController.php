<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Models\User;
use App\Services\BorrowingApprovalService;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MagicApprovalController extends Controller
{
    public function __construct(
        private BorrowingApprovalService $approvalService
    ) {}

    /**
     * Otorisasi flow Siswa -> Guru:
     * - Wajib login
     * - Wajib role 'guru'
     * - Wajib guru pembimbing yang ditugaskan (teacher_id)
     */
    private function authorizeStudentApproval(BorrowingRequest $borrowingRequest): ?\Illuminate\Http\RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'))
                ->with('info', 'Silakan login terlebih dahulu sebagai Guru Pembimbing untuk mengakses halaman persetujuan.');
        }

        $user = Auth::user();

        if (! $user->hasRole('guru')) {
            abort(403, 'Akses ditolak. Halaman persetujuan ini hanya dapat diakses oleh Guru Pembimbing.');
        }

        if ($borrowingRequest->teacher_id && ((int) $borrowingRequest->teacher_id !== (int) $user->id)) {
            abort(403, 'Akses ditolak. Anda bukan guru pembimbing yang ditugaskan untuk permohonan peminjaman ini.');
        }

        return null;
    }

    /**
     * Otorisasi flow Guru -> Kepala Jurusan:
     * - Wajib login
     * - Wajib role 'kepala_jurusan'
     * - Wajib Kepala Jurusan yang dituju (approved_by_kajur_id atau jurusan yang sesuai)
     */
    private function authorizeGuruApproval(BorrowingRequest $borrowingRequest): ?\Illuminate\Http\RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'))
                ->with('info', 'Silakan login terlebih dahulu sebagai Kepala Jurusan untuk mengakses halaman persetujuan.');
        }

        $user = Auth::user();

        if (! $user->hasRole('kepala_jurusan')) {
            abort(403, 'Akses ditolak. Halaman persetujuan ini hanya dapat diakses oleh Kepala Jurusan.');
        }

        $targetKajurId = $borrowingRequest->approved_by_kajur_id;
        $targetJurusanId = $borrowingRequest->approvedByKajur?->jurusan_id
            ?? ($targetKajurId ? User::find($targetKajurId)?->jurusan_id : null)
            ?? $borrowingRequest->user?->jurusan_id;

        $isExactKajur = $targetKajurId && ((int) $targetKajurId === (int) $user->id);
        $isMatchingJurusan = $targetJurusanId && $user->jurusan_id && ((int) $user->jurusan_id === (int) $targetJurusanId);

        if (! $isExactKajur && ! $isMatchingJurusan) {
            abort(403, 'Akses ditolak. Anda bukan Kepala Jurusan yang berwenang untuk permohonan peminjaman ini.');
        }

        return null;
    }

    private function authorizeStudentKajurApproval(BorrowingRequest $borrowingRequest): ?\Illuminate\Http\RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'))
                ->with('info', 'Silakan login sebagai Kajur pemberi pinjaman untuk mengakses persetujuan.');
        }

        $user = Auth::user();
        if (! $user->hasRole('kepala_jurusan')) {
            abort(403, 'Persetujuan ini hanya dapat dilakukan oleh Kepala Jurusan.');
        }

        if (! $borrowingRequest->isStudentLoanForKajur()
            || (int) $borrowingRequest->lender_kajur_id !== (int) $user->id) {
            abort(403, 'Anda bukan Kajur pemberi pinjaman untuk pengajuan ini.');
        }

        if ($borrowingRequest->status !== BorrowingRequest::STATUS_PENDING) {
            abort(403, 'Tautan persetujuan tidak lagi aktif karena pengajuan sudah diproses.');
        }

        return null;
    }

    public function showStudentKajur(BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load([
            'user.jurusan',
            'item.category',
            'itemWithTrashed.category',
            'items.item.category',
            'items.itemWithTrashed.category',
            'lenderKajur.jurusan',
        ]);

        if ($authRedirect = $this->authorizeStudentKajurApproval($borrowingRequest)) {
            return $authRedirect;
        }

        $expiresAt = now()->addDays(7);

        return view('pages.magic-approval-siswa-kajur', [
            'borrowingRequest' => $borrowingRequest,
            'approveUrl' => \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'approval-siswa-kajur.approve',
                $expiresAt,
                ['borrowingRequest' => $borrowingRequest->id]
            ),
            'rejectUrl' => \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'approval-siswa-kajur.reject',
                $expiresAt,
                ['borrowingRequest' => $borrowingRequest->id]
            ),
        ]);
    }

    public function approveStudentKajur(Request $request, BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load(['user', 'items.item', 'items.itemWithTrashed', 'item', 'itemWithTrashed', 'lenderKajur']);

        if ($authRedirect = $this->authorizeStudentKajurApproval($borrowingRequest)) {
            return $authRedirect;
        }

        try {
            app(BorrowingApprovalService::class)->approve($borrowingRequest, (int) Auth::id());

            return redirect()->route('kajur.history')
                ->with('success', 'Pengajuan siswa berhasil disetujui. QR Code telah diterbitkan.');
        } catch (\App\Exceptions\InsufficientStockException $exception) {
            return redirect()->route('kajur.history')
                ->with('error', $exception->getMessage());
        } catch (\Exception $exception) {
            Log::error('Student Kajur approval failed', [
                'borrowing_request_id' => $borrowingRequest->id,
                'error' => $exception->getMessage(),
            ]);

            return redirect()->route('kajur.history')
                ->with('error', 'Terjadi kesalahan saat menyetujui pengajuan.');
        }
    }

    public function rejectStudentKajur(Request $request, BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load(['user', 'lenderKajur']);
        if ($authRedirect = $this->authorizeStudentKajurApproval($borrowingRequest)) {
            return $authRedirect;
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        app(BorrowingApprovalService::class)->reject($borrowingRequest, $validated['rejection_reason'], (int) Auth::id());

        return redirect()->route('kajur.history')
            ->with('success', 'Pengajuan siswa berhasil ditolak.');
    }

    /**
     * Show the magic link approval page for student borrowing (read-only).
     *
     * GET — middleware: signed
     */
    public function show(BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load([
            'user',
            'item.category',
            'itemWithTrashed.category',
            'items.item.category',
            'items.itemWithTrashed.category',
            'teacher',
            'approvedByKajur',
        ]);

        if ($authRedirect = $this->authorizeStudentApproval($borrowingRequest)) {
            return $authRedirect;
        }

        return view('pages.magic-approval', [
            'borrowingRequest' => $borrowingRequest,
        ]);
    }

    /**
     * Approve the student borrowing request via magic link.
     *
     * POST — middleware: signed
     */
    public function approve(Request $request, BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load([
            'user',
            'item.category',
            'itemWithTrashed.category',
            'items.item.category',
            'items.itemWithTrashed.category',
            'teacher',
            'approvedByKajur',
            'qrCode',
        ]);

        if ($authRedirect = $this->authorizeStudentApproval($borrowingRequest)) {
            return $authRedirect;
        }

        // Guard: pastikan masih pending
        if ($borrowingRequest->status !== BorrowingRequest::STATUS_PENDING) {
            $label = $borrowingRequest->status_label;
            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('info', "Pengajuan ini sudah diproses sebelumnya (status: {$label}).");
        }

        try {
            $approverId = (int) Auth::id();

            $this->approvalService->approve($borrowingRequest, $approverId);

            // Refresh model dan pastikan relasi qrCode ter-generate
            $borrowingRequest->refresh();
            if (! $borrowingRequest->qrCode) {
                app(QRCodeService::class)->generateForRequest($borrowingRequest);
            }

            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('success', 'Pengajuan berhasil disetujui. QR Code telah digenerate dan dikirim ke siswa.');

        } catch (\App\Exceptions\InsufficientStockException $e) {
            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('error', $e->getMessage());

        } catch (\Exception $e) {
            Log::error('MagicApprovalController: gagal menyetujui pengajuan siswa', [
                'borrowing_request_id' => $borrowingRequest->id,
                'error'                => $e->getMessage(),
                'trace'                => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('error', 'Terjadi kesalahan saat menyetujui: ' . $e->getMessage());
        }
    }

    /**
     * Reject the student borrowing request via magic link.
     *
     * POST — middleware: signed
     */
    public function reject(Request $request, BorrowingRequest $borrowingRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 10 karakter.',
            'rejection_reason.max'      => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $borrowingRequest->load([
            'user',
            'item.category',
            'itemWithTrashed.category',
            'items.item.category',
            'items.itemWithTrashed.category',
            'teacher',
        ]);

        if ($authRedirect = $this->authorizeStudentApproval($borrowingRequest)) {
            return $authRedirect;
        }

        // Guard: pastikan masih pending
        if ($borrowingRequest->status !== BorrowingRequest::STATUS_PENDING) {
            $label = $borrowingRequest->status_label;
            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('info', "Pengajuan ini sudah diproses sebelumnya (status: {$label}).");
        }

        try {
            $rejectorId = (int) Auth::id();

            $this->approvalService->reject(
                $borrowingRequest,
                $validated['rejection_reason'],
                $rejectorId
            );

            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('success', 'Pengajuan berhasil ditolak.');

        } catch (\Exception $e) {
            Log::error('MagicApprovalController: gagal menolak pengajuan siswa', [
                'borrowing_request_id' => $borrowingRequest->id,
                'error'                => $e->getMessage(),
            ]);

            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('error', 'Terjadi kesalahan saat menolak: ' . $e->getMessage());
        }
    }

    /**
     * Show the magic link approval page for Guru borrowing requests (read-only).
     *
     * GET — middleware: signed
     */
    public function showGuru(BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load([
            'user.jurusan',
            'item.category',
            'item.location',
            'itemWithTrashed',
            'items.item.category',
            'items.item.location',
            'items.itemWithTrashed.category',
            'approvedByKajur.jurusan',
            'qrCode',
        ]);

        if ($authRedirect = $this->authorizeGuruApproval($borrowingRequest)) {
            return $authRedirect;
        }

        return view('pages.magic-approval-guru', [
            'borrowingRequest' => $borrowingRequest,
        ]);
    }

    /**
     * Approve Guru borrowing request via magic link.
     *
     * POST — middleware: signed
     */
    public function approveGuru(Request $request, BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load(['user', 'item', 'items.item.category', 'approvedByKajur', 'qrCode']);

        if ($authRedirect = $this->authorizeGuruApproval($borrowingRequest)) {
            return $authRedirect;
        }

        // Guard: pastikan masih pending
        if ($borrowingRequest->status !== BorrowingRequest::STATUS_PENDING) {
            $label = $borrowingRequest->status_label;
            return redirect()
                ->route('approval-guru.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('info', "Pengajuan ini sudah diproses sebelumnya (status: {$label}).");
        }

        try {
            $approverId = (int) Auth::id();

            $this->approvalService->approve($borrowingRequest, $approverId);

            // Refresh model dan pastikan relasi qrCode ter-generate
            $borrowingRequest->refresh();
            if (! $borrowingRequest->qrCode) {
                app(QRCodeService::class)->generateForRequest($borrowingRequest);
            }

            return redirect()
                ->route('approval-guru.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('success', 'Pengajuan guru berhasil disetujui. QR Code peminjaman telah digenerate.');

        } catch (\App\Exceptions\InsufficientStockException $e) {
            return redirect()
                ->route('approval-guru.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('error', $e->getMessage());

        } catch (\Exception $e) {
            Log::error('MagicApprovalController: gagal menyetujui pengajuan guru', [
                'borrowing_request_id' => $borrowingRequest->id,
                'error'                => $e->getMessage(),
                'trace'                => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('approval-guru.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('error', 'Terjadi kesalahan saat menyetujui: ' . $e->getMessage());
        }
    }

    /**
     * Reject Guru borrowing request via magic link.
     *
     * POST — middleware: signed
     */
    public function rejectGuru(Request $request, BorrowingRequest $borrowingRequest)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi.',
            'rejection_reason.min'      => 'Alasan penolakan minimal 10 karakter.',
            'rejection_reason.max'      => 'Alasan penolakan maksimal 500 karakter.',
        ]);

        $borrowingRequest->load(['user', 'item', 'approvedByKajur']);

        if ($authRedirect = $this->authorizeGuruApproval($borrowingRequest)) {
            return $authRedirect;
        }

        // Guard: pastikan masih pending
        if ($borrowingRequest->status !== BorrowingRequest::STATUS_PENDING) {
            $label = $borrowingRequest->status_label;
            return redirect()
                ->route('approval-guru.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('info', "Pengajuan ini sudah diproses sebelumnya (status: {$label}).");
        }

        try {
            $rejectorId = (int) Auth::id();

            $this->approvalService->reject(
                $borrowingRequest,
                $validated['rejection_reason'],
                $rejectorId
            );

            return redirect()
                ->route('approval-guru.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('success', 'Pengajuan guru berhasil ditolak.');

        } catch (\Exception $e) {
            Log::error('MagicApprovalController: gagal menolak pengajuan guru', [
                'borrowing_request_id' => $borrowingRequest->id,
                'error'                => $e->getMessage(),
            ]);

            return redirect()
                ->route('approval-guru.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('error', 'Terjadi kesalahan saat menolak: ' . $e->getMessage());
        }
    }
}

