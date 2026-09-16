<?php

namespace App\Services;

use App\Exceptions\InsufficientStockException;
use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $requestedItems = $request->items()->with('item')->get();

        if ($requestedItems->isEmpty()) {
            if (is_null($request->item_id)) {
                return;
            }

            $item = Item::lockForUpdate()->findOrFail($request->item_id);
            $reservedStock = BorrowingRequest::whereIn('status', [
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_BORROWED,
            ])
                ->where('item_id', $item->id)
                ->where('id', '!=', $request->id)
                ->lockForUpdate()
                ->sum('quantity');

            $availableStock = $item->stock - $reservedStock;

            if ($availableStock < (int) ($request->quantity ?? 0)) {
                throw new InsufficientStockException(
                    "Stok tidak mencukupi. Tersedia: {$availableStock}, Diminta: {$request->quantity}"
                );
            }

            return;
        }

        foreach ($requestedItems as $detail) {
            $item = $detail->item;

            if (! $item) {
                throw new InsufficientStockException('Barang pada permohonan tidak ditemukan di inventaris.');
            }

            $reservedStock = BorrowingRequestItem::query()
                ->join('borrowing_requests', 'borrowing_requests.id', '=', 'borrowing_request_items.borrowing_request_id')
                ->whereIn('borrowing_requests.status', [
                    BorrowingRequest::STATUS_APPROVED,
                    BorrowingRequest::STATUS_BORROWED,
                ])
                ->where('borrowing_request_items.item_id', $item->id)
                ->where('borrowing_request_items.borrowing_request_id', '!=', $request->id)
                ->sum('borrowing_request_items.quantity');

            $availableStock = $item->stock - (int) $reservedStock;

            if ($availableStock < (int) $detail->quantity) {
                throw new InsufficientStockException(
                    "Stok tidak mencukupi untuk {$item->name}. Tersedia: {$availableStock}, Diminta: {$detail->quantity}"
                );
            }
        }
    }
    
    /**
     * Get available stock for an item
     */
    public function getAvailableStock(Item $item): int
    {
        $reservedStock = BorrowingRequestItem::query()
            ->join('borrowing_requests', 'borrowing_requests.id', '=', 'borrowing_request_items.borrowing_request_id')
            ->whereIn('borrowing_requests.status', [
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_BORROWED,
            ])
            ->where('borrowing_request_items.item_id', $item->id)
            ->sum('borrowing_request_items.quantity');

        return max(0, $item->stock - (int) $reservedStock);
    }
}
