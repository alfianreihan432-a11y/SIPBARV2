<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Exceptions\InvalidStateTransitionException;

class BorrowingStateMachine
{
    /**
     * Check if transition from current status to new status is allowed
     */
    public function canTransitionTo(BorrowingRequest $request, string $newStatus): bool
    {
        $transitions = [
            BorrowingRequest::STATUS_PENDING => [
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_REJECTED,
                BorrowingRequest::STATUS_CANCELLED,
            ],
            BorrowingRequest::STATUS_APPROVED => [
                BorrowingRequest::STATUS_BORROWED,
            ],
            BorrowingRequest::STATUS_BORROWED => [
                BorrowingRequest::STATUS_RETURNED,
                BorrowingRequest::STATUS_OVERDUE,
            ],
            // Terminal states (cancelled, rejected, returned) have no transitions
        ];

        $currentStatus = $request->status;
        $allowedTransitions = $transitions[$currentStatus] ?? [];

        return in_array($newStatus, $allowedTransitions);
    }

    /**
     * Transition request to new status with validation
     * 
     * @throws InvalidStateTransitionException
     */
    public function transitionTo(
        BorrowingRequest $request,
        string $newStatus,
        ?int $performedBy = null
    ): void {
        if (!$this->canTransitionTo($request, $newStatus)) {
            throw new InvalidStateTransitionException(
                "Tidak dapat mengubah status dari '{$request->status}' ke '{$newStatus}'"
            );
        }

        $request->status = $newStatus;

        // Set timestamps based on new status
        match($newStatus) {
            BorrowingRequest::STATUS_APPROVED => $request->approved_at = now(),
            BorrowingRequest::STATUS_BORROWED => [
                $request->borrowed_at = now(),
                $request->checkout_by = $performedBy,
            ],
            BorrowingRequest::STATUS_RETURNED => [
                $request->returned_at = now(),
                $request->checkin_by = $performedBy,
            ],
            default => null,
        };

        $request->save();
    }
    
    /**
     * Get allowed transitions for current status
     */
    public function getAllowedTransitions(BorrowingRequest $request): array
    {
        $transitions = [
            BorrowingRequest::STATUS_PENDING => [
                BorrowingRequest::STATUS_APPROVED => 'Setujui',
                BorrowingRequest::STATUS_REJECTED => 'Tolak',
                BorrowingRequest::STATUS_CANCELLED => 'Batalkan',
            ],
            BorrowingRequest::STATUS_APPROVED => [
                BorrowingRequest::STATUS_BORROWED => 'Checkout (Scan QR)',
            ],
            BorrowingRequest::STATUS_BORROWED => [
                BorrowingRequest::STATUS_RETURNED => 'Checkin (Scan QR)',
            ],
        ];

        return $transitions[$request->status] ?? [];
    }
    
    /**
     * Check if status is terminal (no further transitions)
     */
    public function isTerminalStatus(string $status): bool
    {
        return in_array($status, [
            BorrowingRequest::STATUS_CANCELLED,
            BorrowingRequest::STATUS_REJECTED,
            BorrowingRequest::STATUS_RETURNED,
        ]);
    }
}
