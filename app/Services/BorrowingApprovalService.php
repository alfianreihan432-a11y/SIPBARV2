<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\InsufficientStockException;

class BorrowingApprovalService
{
    public function __construct(
        private QRCodeService $qrCodeService,
        private WhatsAppNotificationService $whatsAppService, // Kept for easy re-activation
        private BorrowingStateMachine $stateMachine,
        private EmailNotificationService $emailService
    ) {}
    
    /**
     * Approve a borrowing request
     * 
     * @throws InsufficientStockException
     * @throws \App\Exceptions\InvalidStateTransitionException
     */
    public function approve(BorrowingRequest $request, int $teacherId): void
    {
        DB::transaction(function () use ($request, $teacherId) {
            // 1. Validate stock availability
            $this->validateStock($request);
            
            // 2. Update status to approved
            $this->stateMachine->transitionTo(
                $request,
                BorrowingRequest::STATUS_APPROVED,
                $teacherId
            );
            
            // 3. Generate QR code
            $qrCode = $this->qrCodeService->generateForRequest($request);

            // 4. Send notification (non-blocking - errors only logged)
            try {
                $qrBase64 = $this->qrCodeService->getImageBase64($qrCode);

                // WhatsApp langsung ke siswa agar QR/code approval cepat dikirim
                $this->whatsAppService->notifyApproved($request, $qrBase64);

                // Email (aktif) --
                $this->emailService->notifyApproved($request, $qrBase64);

            } catch (\Exception $e) {
                // Log but don't fail transaction
                Log::error('Email notification failed during approval', [
                    'request_id' => $request->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        });
    }
    
    /**
     * Reject a borrowing request
     * 
     * @throws \App\Exceptions\InvalidStateTransitionException
     */
    public function reject(BorrowingRequest $request, string $reason, int $teacherId): void
    {
        DB::transaction(function () use ($request, $reason, $teacherId) {
            // 1. Set rejection reason
            $request->rejection_reason = $reason;
            
            // 2. Update status to rejected
            $this->stateMachine->transitionTo(
                $request,
                BorrowingRequest::STATUS_REJECTED,
                $teacherId
            );
            
            // 3. Send notification (non-blocking)
            try {
                // WhatsApp langsung ke siswa agar alasan penolakan sampai ke siswa
                $this->whatsAppService->notifyRejected($request);

                // Email (aktif) --
                $this->emailService->notifyRejected($request);

            } catch (\Exception $e) {
                Log::error('Email notification failed during rejection', [
                    'request_id' => $request->id,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }
    
    /**
     * Validate that item has sufficient stock
     * 
     * @throws InsufficientStockException
     */
    private function validateStock(BorrowingRequest $request): void
    {
        $item = $request->item;
        
        // Calculate reserved stock (approved or borrowed)
        $reservedStock = BorrowingRequest::whereIn('status', [
            BorrowingRequest::STATUS_APPROVED,
            BorrowingRequest::STATUS_BORROWED
        ])
        ->where('item_id', $item->id)
        ->where('id', '!=', $request->id) // Exclude current request
        ->sum('quantity');
        
        $availableStock = $item->stock - $reservedStock;
        
        if ($availableStock < $request->quantity) {
            throw new InsufficientStockException(
                "Stok tidak mencukupi. Tersedia: {$availableStock}, Diminta: {$request->quantity}"
            );
        }
    }
    
    /**
     * Get available stock for an item
     */
    public function getAvailableStock(Item $item): int
    {
        $reservedStock = BorrowingRequest::whereIn('status', [
            BorrowingRequest::STATUS_APPROVED,
            BorrowingRequest::STATUS_BORROWED
        ])
        ->where('item_id', $item->id)
        ->sum('quantity');
        
        return max(0, $item->stock - $reservedStock);
    }
}
