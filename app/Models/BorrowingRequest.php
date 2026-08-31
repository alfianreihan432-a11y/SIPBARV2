<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BorrowingRequest extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'teacher_id',
        'quantity',
        'purpose',
        'borrow_date',
        'return_date',
        'notes',
        'status',
        'rejection_reason',
        'qr_token',
        'reminder_sent_at',
        'approved_at',
        'borrowed_at',
        'returned_at',
        'return_condition',
        'return_notes',
        'checkout_by',
        'checkin_by',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'approved_at' => 'datetime',
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];
    
    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_OVERDUE = 'overdue';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Relasi item termasuk yang sudah di-soft delete.
     * Dipakai di halaman pengumuman/history agar nama barang tetap tampil
     * meski barang sudah dihapus dari inventaris.
     */
    public function itemWithTrashed(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withoutGlobalScope(\Illuminate\Database\Eloquent\SoftDeletingScope::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function qrCode()
    {
        return $this->hasOne(QRCode::class, 'borrowing_request_id');
    }
    
    public function checkoutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checkout_by');
    }
    
    public function checkinBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checkin_by');
    }
    
    public function whatsappLogs()
    {
        return $this->hasMany(WhatsAppNotificationLog::class);
    }

    public function itemReturns()
    {
        return $this->hasMany(ItemReturn::class, 'borrowing_request_id');
    }

    public function latestReturn()
    {
        return $this->hasOne(ItemReturn::class, 'borrowing_request_id')->latestOfMany();
    }

    // ==========================================
    // Helper Methods
    // ==========================================
    
    /**
     * Check if request is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_BORROWED 
            && $this->return_date->isPast();
    }
    
    /**
     * Get days until return date
     */
    public function daysUntilReturn(): ?int
    {
        if ($this->status !== self::STATUS_BORROWED) {
            return null;
        }
        
        return now()->diffInDays($this->return_date, false);
    }
    
    /**
     * Check if reminder should be sent (H-1)
     */
    public function shouldSendReminder(): bool
    {
        return $this->status === self::STATUS_BORROWED
            && is_null($this->reminder_sent_at)
            && $this->return_date->isTomorrow();
    }
    
    /**
     * Check if transaction is in terminal status
     */
    public function isTerminal(): bool
    {
        return in_array($this->status, [
            self::STATUS_RETURNED,
            self::STATUS_REJECTED,
            self::STATUS_CANCELLED,
        ]);
    }
    
    /**
     * Check if QR is active (can be scanned for actions)
     */
    public function isQRActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_BORROWED
        ]);
    }
    
    /**
     * Get human-readable status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        $colors = [
            self::STATUS_PENDING => 'amber',
            self::STATUS_CANCELLED => 'slate',
            self::STATUS_APPROVED => 'emerald',
            self::STATUS_REJECTED => 'red',
            self::STATUS_BORROWED => 'blue',
            self::STATUS_RETURNED => 'gray',
        ];
        
        $color = $colors[$this->status] ?? 'gray';
        $label = $this->status_label;
        
        return "<span class=\"px-2 py-1 rounded text-xs font-bold bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900/30 dark:text-{$color}-300\">{$label}</span>";
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'cancelled' => 'Dibatalkan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'borrowed' => 'Dipinjam',
            'returned' => 'Dikembalikan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'cancelled' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger',
            'borrowed' => 'primary',
            'returned' => 'secondary',
            default => 'secondary',
        };
    }
    
    // ==========================================
    // Scopes
    // ==========================================
    
    /**
     * Scope: Get overdue borrowings
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_BORROWED)
            ->whereDate('return_date', '<', now());
    }
    
    /**
     * Scope: Get active borrowings (borrowed status)
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_BORROWED);
    }
    
    /**
     * Scope: Get pending approval
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope: Need reminder (H-1)
     */
    public function scopeNeedReminder($query)
    {
        return $query->where('status', self::STATUS_BORROWED)
            ->whereNull('reminder_sent_at')
            ->whereDate('return_date', now()->addDay()->toDateString());
    }
}
