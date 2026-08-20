<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class WhatsAppNotificationLog extends Model
{
    protected $table = 'whatsapp_notification_logs';

    protected $fillable = [
        'borrowing_request_id',
        'notification_type',
        'recipient_phone',
        'payload',
        'status',
        'http_status_code',
        'error_message',
        'sent_at',
    ];
    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
    ];
    public function borrowingRequest(): BelongsTo
    {
        return $this->belongsTo(BorrowingRequest::class);
    }
}