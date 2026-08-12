<?php

namespace App\Http\Controllers;

use App\Models\BorrowingRequest;
use App\Services\BorrowingApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherApprovalController extends Controller
{
    public function __construct(
        private BorrowingApprovalService $approvalService
    ) {}
    
    /**
     * Display pending borrowing requests for the logged-in teacher
     */
    public function index()
    {
        $pendingRequests = BorrowingRequest::where('teacher_id', Auth::id())
            ->where('status', BorrowingRequest::STATUS_PENDING)
            ->with(['user', 'item'])
            ->latest()
            ->get();
        
        return view('pages.guru.requests', [
            'pendingRequests' => $pendingRequests
        ]);
    }
    
    /**
     * Approve a borrowing request
     */
    public function approve(Request $request, int $id)
    {
        try {
            $borrowingRequest = BorrowingRequest::findOrFail($id);
            
            // Authorization check
            if ($borrowingRequest->teacher_id !== Auth::id()) {
                return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui permintaan ini');
            }
            
            // Check current status
            if ($borrowingRequest->status !== BorrowingRequest::STATUS_PENDING) {
                return back()->with('error', 'Permintaan ini tidak dalam status menunggu persetujuan');
            }
            
            // Approve via service
            $this->approvalService->approve($borrowingRequest, Auth::id());
            
            return back()->with('success', 'Permintaan peminjaman berhasil disetujui. QR Code telah dikirim ke siswa via WhatsApp.');
            
        } catch (\App\Exceptions\InsufficientStockException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Error approving borrowing request', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat menyetujui permintaan: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject a borrowing request
     */
    public function reject(Request $request, int $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ], [
            'rejection_reason.required' => 'Alasan penolakan harus diisi',
            'rejection_reason.min' => 'Alasan penolakan minimal 10 karakter',
            'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter',
        ]);
        
        try {
            $borrowingRequest = BorrowingRequest::findOrFail($id);
            
            // Authorization check
            if ($borrowingRequest->teacher_id !== Auth::id()) {
                return back()->with('error', 'Anda tidak memiliki akses untuk menolak permintaan ini');
            }
            
            // Check current status
            if ($borrowingRequest->status !== BorrowingRequest::STATUS_PENDING) {
                return back()->with('error', 'Permintaan ini tidak dalam status menunggu persetujuan');
            }
            
            // Reject via service
            $this->approvalService->reject(
                $borrowingRequest,
                $validated['rejection_reason'],
                Auth::id()
            );
            
            return back()->with('success', 'Permintaan peminjaman berhasil ditolak. Notifikasi telah dikirim ke siswa.');
            
        } catch (\Exception $e) {
            \Log::error('Error rejecting borrowing request', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat menolak permintaan: ' . $e->getMessage());
        }
    }
}

