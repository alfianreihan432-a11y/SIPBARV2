<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRequest;
use App\Services\QRCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * API Controller for mobile app or external integrations
 * 
 * This controller is prepared for future mobile app development
 * All endpoints return JSON responses
 */
class BorrowingApiController extends Controller
{
    public function __construct(
        private QRCodeService $qrCodeService
    ) {}
    
    /**
     * Get student's borrowing history
     * 
     * GET /api/borrowings/my-history
     */
    public function myHistory(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $borrowings = BorrowingRequest::with(['item', 'teacher'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'data' => $borrowings->map(function ($borrowing) {
                return [
                    'id' => $borrowing->id,
                    'item_name' => $borrowing->item->name,
                    'quantity' => $borrowing->quantity,
                    'status' => $borrowing->status,
                    'status_label' => $borrowing->status_label,
                    'borrow_date' => $borrowing->borrow_date->format('Y-m-d'),
                    'return_date' => $borrowing->return_date->format('Y-m-d'),
                    'is_overdue' => $borrowing->isOverdue(),
                    'days_until_return' => $borrowing->daysUntilReturn(),
                    'qr_active' => $borrowing->isQRActive(),
                    'created_at' => $borrowing->created_at->toIso8601String(),
                ];
            }),
            'meta' => [
                'current_page' => $borrowings->currentPage(),
                'total' => $borrowings->total(),
                'per_page' => $borrowings->perPage(),
            ]
        ]);
    }
    
    /**
     * Get QR code for a borrowing
     * 
     * GET /api/borrowings/{id}/qr-code
     */
    public function getQRCode(int $id): JsonResponse
    {
        $borrowing = BorrowingRequest::findOrFail($id);
        
        // Authorization check
        if ($borrowing->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        if (!$borrowing->qr_token) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code not available yet'
            ], 404);
        }
        
        // Get QR as base64
        $qrBase64 = $this->qrCodeService->getImageBase64($borrowing);
        
        return response()->json([
            'success' => true,
            'data' => [
                'token' => $borrowing->qr_token,
                'qr_image' => $qrBase64,
                'status' => $borrowing->status,
                'is_active' => $borrowing->isQRActive(),
                'message' => $borrowing->isQRActive() 
                    ? 'QR Code siap digunakan' 
                    : 'QR Code tidak aktif'
            ]
        ]);
    }
    
    /**
     * Validate QR token (for scanner)
     * 
     * POST /api/qr/validate
     * Body: { "token": "uuid-string" }
     */
    public function validateQR(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
        ]);
        
        $borrowing = $this->qrCodeService->findByToken($request->token);
        
        if (!$borrowing) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid'
            ], 404);
        }
        
        $action = null;
        $message = '';
        
        switch ($borrowing->status) {
            case BorrowingRequest::STATUS_APPROVED:
                $action = 'checkout';
                $message = 'Siap untuk checkout (serah terima barang)';
                break;
            case BorrowingRequest::STATUS_BORROWED:
                $action = 'checkin';
                $message = 'Siap untuk checkin (pengembalian barang)';
                break;
            default:
                $action = 'read-only';
                $message = 'QR Code tidak aktif (sudah selesai atau belum disetujui)';
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'borrowing_id' => $borrowing->id,
                'student' => [
                    'name' => $borrowing->user->name,
                    'class' => $borrowing->user->kelas ?? '-',
                ],
                'item' => [
                    'name' => $borrowing->item->name,
                    'quantity' => $borrowing->quantity,
                ],
                'dates' => [
                    'borrow' => $borrowing->borrow_date->format('Y-m-d'),
                    'return' => $borrowing->return_date->format('Y-m-d'),
                ],
                'status' => $borrowing->status,
                'action' => $action,
                'message' => $message,
            ]
        ]);
    }
    
    /**
     * Get borrowing statistics (for teacher/admin)
     * 
     * GET /api/statistics/borrowings
     */
    public function statistics(): JsonResponse
    {
        $user = Auth::user();
        
        // Base query depends on role
        $query = BorrowingRequest::query();
        if ($user->hasRole('guru')) {
            $query->where('teacher_id', $user->id);
        }
        
        $stats = [
            'total' => $query->count(),
            'pending' => (clone $query)->where('status', BorrowingRequest::STATUS_PENDING)->count(),
            'approved' => (clone $query)->where('status', BorrowingRequest::STATUS_APPROVED)->count(),
            'borrowed' => (clone $query)->where('status', BorrowingRequest::STATUS_BORROWED)->count(),
            'returned' => (clone $query)->where('status', BorrowingRequest::STATUS_RETURNED)->count(),
            'rejected' => (clone $query)->where('status', BorrowingRequest::STATUS_REJECTED)->count(),
            'overdue' => (clone $query)->overdue()->count(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
