<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LateWarningLog extends Model
{
    protected $table = 'late_warning_logs';

    protected $fillable = [
        'borrowing_request_id',
        'sender_id',
        'sender_role',
        'channel',
        'recipient_phone',
        'recipient_email',
        'status',
        'message_content',
        'notes',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function borrowingRequest(): BelongsTo
    {
        return $this->belongsTo(BorrowingRequest::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Check if a warning was already sent for this borrowing request in the last 24 hours.
     */
    public static function hasRecentWarning(int $borrowingRequestId): bool
    {
        return static::where('borrowing_request_id', $borrowingRequestId)
            ->whereIn('status', ['sent', 'queued', 'success'])
            ->where('sent_at', '>=', now()->subHours(24))
            ->exists();
    }

    /**
     * Get the latest warning log for a borrowing request.
     */
    public static function getLatestFor(int $borrowingRequestId): ?self
    {
        return static::where('borrowing_request_id', $borrowingRequestId)
            ->latest('sent_at')
            ->first();
    }
}
