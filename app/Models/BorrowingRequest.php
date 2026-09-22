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
        'return_time',
        'notes',
        'whatsapp_number',
        'status',
        'rejection_reason',
        'qr_token',
        'reminder_sent_at',
        'approved_at',
        'borrowed_at',
        'returned_at',
        'return_condition',
        'return_notes',
        'foto_bukti',
        'checkout_by',
        'checkin_by',
        'tipe_peminjam',
        'approved_by_kajur_id',
    ];

    protected $appends = ['display_status'];

    /**
     * Accessor for display_status (real-time overdue check)
     */
    public function getDisplayStatusAttribute(): string
    {
        // If already overdue in database, return it
        if ($this->status === self::STATUS_OVERDUE) {
            return 'overdue';
        }

        // Real-time check for borrowed loans
        if ($this->status === self::STATUS_BORROWED) {
            $nowJakarta = now()->timezone('Asia/Jakarta');

            if ($this->return_date->lt($nowJakarta->toDateString())) {
                return 'overdue';
            }

            if ($this->return_date->toDateString() === $nowJakarta->toDateString() && $this->return_time) {
                if ($this->return_time < $nowJakarta->toTimeString()) {
                    return 'overdue';
                }
            }
        }

        return $this->status;
    }

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

    public function items()
    {
        return $this->hasMany(BorrowingRequestItem::class, 'borrowing_request_id');
    }

    public function itemDetails()
    {
        return $this->hasMany(BorrowingRequestItem::class, 'borrowing_request_id');
    }

    /**
     * Relasi item termasuk yang sudah di-soft delete.
     * Dipakai di halaman pengumuman/history agar nama barang tetap tampil
     * meski barang sudah dihapus dari inventaris.
     */
    public function itemWithTrashed(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id')->withTrashed();
    }

    public function itemSummary(): string
    {
        $details = $this->items()->with('itemWithTrashed')->get();

        if ($details->isNotEmpty()) {
            return $details->map(function ($detail) {
                $itemName = $detail->itemWithTrashed?->name ?? $detail->item?->name ?? 'Barang tidak tersedia';
                return $itemName . ' (' . $detail->quantity . ')';
            })->implode(', ');
        }

        return $this->itemWithTrashed?->name ?? $this->item?->name ?? 'Barang tidak tersedia';
    }

    public function totalQuantity(): int
    {
        $details = $this->items()->get();

        if ($details->isNotEmpty()) {
            return (int) $details->sum('quantity');
        }

        return (int) ($this->quantity ?? 0);
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

    public function approvedByKajur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_kajur_id');
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
        if ($this->status !== self::STATUS_BORROWED) {
            return false;
        }

        // Use Asia/Jakarta timezone for accurate comparison
        $nowJakarta = now()->timezone('Asia/Jakarta');

        // Check if return date has passed
        if ($this->return_date->lt($nowJakarta->toDateString())) {
            return true;
        }

        // If return date is today, check if return time has passed
        if ($this->return_date->toDateString() === $nowJakarta->toDateString() && $this->return_time) {
            if ($this->return_time < $nowJakarta->toTimeString()) {
                return true;
            }
        }

        return false;
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
            self::STATUS_OVERDUE => 'red',
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
            'overdue' => 'Terlambat',
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
            'overdue' => 'danger',
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
        $nowJakarta = now()->timezone('Asia/Jakarta');

        return $query->where('status', self::STATUS_BORROWED)
            ->where(function($q) use ($nowJakarta) {
                $q->whereDate('return_date', '<', $nowJakarta)
                  ->orWhere(function($subQ) use ($nowJakarta) {
                      $subQ->whereDate('return_date', '=', $nowJakarta)
                        ->whereNotNull('return_time')
                        ->whereTime('return_time', '<', $nowJakarta);
                  });
            });
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
