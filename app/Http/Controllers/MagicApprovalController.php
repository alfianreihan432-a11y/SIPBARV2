<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Services\BorrowingApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MagicApprovalController extends Controller
{
    public function __construct(
        private BorrowingApprovalService $approvalService
    ) {}

    /**
     * Show the magic link approval page (read-only).
     *
     * GET — middleware: signed
     * TIDAK ada side effect di sini agar aman dari email link prefetching
     * oleh client seperti Outlook / Gmail Safety Scanner yang membuka GET
     * URL secara otomatis.
     */
    public function show(BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load(['user', 'item', 'teacher']);

        return view('pages.magic-approval', [
            'borrowingRequest' => $borrowingRequest,
        ]);
    }

    /**
     * Approve the borrowing request via magic link.
     *
     * POST — middleware: signed
     * Menggunakan BorrowingApprovalService yang sama dengan TeacherApprovalController
     * agar logika bisnis tidak terduplikasi.
     */
    public function approve(Request $request, BorrowingRequest $borrowingRequest)
    {
        $borrowingRequest->load(['user', 'item', 'teacher', 'qrCode']);

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
            // Gunakan teacher_id dari pengajuan itu sendiri sebagai approver
            $this->approvalService->approve($borrowingRequest, $borrowingRequest->teacher_id);

            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('success', 'Pengajuan berhasil disetujui. QR Code telah dikirim ke email siswa.');

        } catch (\App\Exceptions\InsufficientStockException $e) {
            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('error', $e->getMessage());

        } catch (\Exception $e) {
            Log::error('MagicApprovalController: gagal menyetujui pengajuan', [
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
     * Reject the borrowing request via magic link.
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

        $borrowingRequest->load(['user', 'item', 'teacher']);

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
            $this->approvalService->reject(
                $borrowingRequest,
                $validated['rejection_reason'],
                $borrowingRequest->teacher_id
            );

            return redirect()
                ->route('approval.show', array_merge(
                    ['borrowingRequest' => $borrowingRequest->id],
                    $request->query()
                ))
                ->with('success', 'Pengajuan berhasil ditolak. Notifikasi telah dikirim ke email siswa.');

        } catch (\Exception $e) {
            Log::error('MagicApprovalController: gagal menolak pengajuan', [
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
}
