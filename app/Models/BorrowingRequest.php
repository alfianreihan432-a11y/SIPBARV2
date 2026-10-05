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
     * Accessor untuk display_status (pengecekan keterlambatan secara real-time)
     */
    public function getDisplayStatusAttribute(): string
    {
        // Jika sudah terlambat di database, kembalikan langsung
        if ($this->status === self::STATUS_OVERDUE) {
            return 'overdue';
        }

        // Pengecekan real-time untuk peminjaman yang sedang aktif
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
        $itemsRel = $this->relationLoaded('items') ? $this->getRelation('items') : $this->items()->with(['itemWithTrashed', 'item'])->get();
        $details = $itemsRel instanceof \Illuminate\Support\Collection ? $itemsRel : collect($itemsRel);

        if ($details->isNotEmpty()) {
            return $details->map(function ($detail) {
                if (is_array($detail)) {
                    $item = isset($detail['item_id']) ? Item::withTrashed()->find($detail['item_id']) : null;
                    $itemName = $item?->name ?? 'Barang #' . ($detail['item_id'] ?? '-');
                    return $itemName . ' (' . ($detail['quantity'] ?? 1) . ')';
                }
                $item = $detail->relationLoaded('itemWithTrashed') ? $detail->itemWithTrashed : ($detail->itemWithTrashed ?? $detail->item);
                $itemName = $item?->name ?? 'Barang #' . ($detail->item_id ?? '-');
                return $itemName . ' (' . ($detail->quantity ?? 1) . ')';
            })->implode(', ');
        }

        $singleItem = $this->relationLoaded('itemWithTrashed') ? $this->itemWithTrashed : ($this->itemWithTrashed ?? $this->item);
        if ($singleItem) {
            return $singleItem->name;
        }

        return $this->item_id ? ('Barang #' . $this->item_id) : 'Barang tidak tersedia';
    }

    /**
     * Nama barang display untuk kartu atau baris peminjaman:
     * - Menangani peminjaman multi-item (keranjang)
     * - Menangani peminjaman single item (legacy)
     * - Tetap menampilkan nama asli jika barang sudah di-soft-delete
     * - Fallback "Barang tidak tersedia" HANYA jika data barang benar-benar corrupt/null
     */
    public function getItemDisplayNameAttribute(): string
    {
        $itemsRel = $this->relationLoaded('items') ? $this->getRelation('items') : $this->items()->with(['itemWithTrashed', 'item'])->get();
        $details = $itemsRel instanceof \Illuminate\Support\Collection ? $itemsRel : collect($itemsRel);

        if ($details->isNotEmpty()) {
            if ($details->count() === 1) {
                $detail = $details->first();
                if (is_array($detail)) {
                    $item = isset($detail['item_id']) ? Item::withTrashed()->find($detail['item_id']) : null;
                    return $item?->name ?? (isset($detail['item_id']) ? ('Barang #' . $detail['item_id']) : 'Barang tidak tersedia');
                }
                $item = $detail->relationLoaded('itemWithTrashed') ? $detail->itemWithTrashed : ($detail->itemWithTrashed ?? $detail->item);
                return $item?->name ?? ($detail->item_id ? ('Barang #' . $detail->item_id) : 'Barang tidak tersedia');
            }
            $first = $details->first();
            if (is_array($first)) {
                $item = isset($first['item_id']) ? Item::withTrashed()->find($first['item_id']) : null;
                $firstName = $item?->name ?? 'Barang';
                return $firstName . ' (+' . ($details->count() - 1) . ' lainnya)';
            }
            $item = $first->relationLoaded('itemWithTrashed') ? $first->itemWithTrashed : ($first->itemWithTrashed ?? $first->item);
            $firstName = $item?->name ?? 'Barang';
            return $firstName . ' (+' . ($details->count() - 1) . ' lainnya)';
        }

        $singleItem = $this->relationLoaded('itemWithTrashed') ? $this->itemWithTrashed : ($this->itemWithTrashed ?? $this->item);
        if ($singleItem) {
            return $singleItem->name;
        }

        return $this->item_id ? ('Barang #' . $this->item_id) : 'Barang tidak tersedia';
    }

    public function totalQuantity(): int
    {
        $itemsRel = $this->relationLoaded('items') ? $this->getRelation('items') : $this->items()->get();
        $details = $itemsRel instanceof \Illuminate\Support\Collection ? $itemsRel : collect($itemsRel);

        if ($details->isNotEmpty()) {
            return (int) $details->sum(function ($detail) {
                return is_array($detail) ? ($detail['quantity'] ?? 1) : ($detail->quantity ?? 1);
            });
        }

        return (int) ($this->quantity ?? 1);
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

    public function lateWarningLogs()
    {
        return $this->hasMany(LateWarningLog::class, 'borrowing_request_id');
    }

    public function latestLateWarning()
    {
        return $this->hasOne(LateWarningLog::class, 'borrowing_request_id')->latestOfMany('sent_at');
    }

    public function latestReturn()
    {
        return $this->hasOne(ItemReturn::class, 'borrowing_request_id')->latestOfMany();
    }

    // ==========================================
    // Metode Pembantu
    // ==========================================
    
    /**
     * Periksa apakah peminjaman sudah terlambat
     */
    public function isOverdue(): bool
    {
        if ($this->status !== self::STATUS_BORROWED) {
            return false;
        }

        // Gunakan zona waktu Asia/Jakarta untuk perbandingan yang akurat
        $nowJakarta = now()->timezone('Asia/Jakarta');

        // Periksa apakah tanggal kembali sudah terlewat
        if ($this->return_date->lt($nowJakarta->toDateString())) {
            return true;
        }

        // Jika tanggal kembali hari ini, periksa apakah jam kembali sudah terlewat
        if ($this->return_date->toDateString() === $nowJakarta->toDateString() && $this->return_time) {
            if ($this->return_time < $nowJakarta->toTimeString()) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Hitung sisa hari hingga tanggal kembali
     */
    public function daysUntilReturn(): ?int
    {
        if ($this->status !== self::STATUS_BORROWED) {
            return null;
        }
        
        return now()->diffInDays($this->return_date, false);
    }
    
    /**
     * Periksa apakah pengingat perlu dikirim (H-1)
     */
    public function shouldSendReminder(): bool
    {
        return $this->status === self::STATUS_BORROWED
            && is_null($this->reminder_sent_at)
            && $this->return_date->isTomorrow();
    }
    
    /**
     * Periksa apakah transaksi berada di status akhir (tidak bisa diubah lagi)
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
     * Periksa apakah QR Code masih aktif (bisa dipindai untuk aksi)
     */
    public function isQRActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_APPROVED,
            self::STATUS_BORROWED
        ]);
    }
    
    /**
     * Dapatkan HTML badge status yang mudah dibaca
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
    // Scope Query
    // ==========================================
    
    /**
     * Scope: Ambil peminjaman yang sudah terlambat
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
     * Scope: Ambil peminjaman yang sedang aktif (status borrowed)
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_BORROWED);
    }
    
    /**
     * Scope: Ambil peminjaman yang menunggu persetujuan
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope: Ambil peminjaman yang perlu diingatkan (H-1)
     */
    public function scopeNeedReminder($query)
    {
        return $query->where('status', self::STATUS_BORROWED)
            ->whereNull('reminder_sent_at')
            ->whereDate('return_date', now()->addDay()->toDateString());
    }
}
