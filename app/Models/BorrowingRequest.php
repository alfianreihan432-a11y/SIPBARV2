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
        'approved_at',
        'borrowed_at',
        'returned_at',
        'return_condition',
        'return_notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'approved_at' => 'datetime',
        'borrowed_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function qrCode()
    {
        return $this->hasOne(QRCode::class, 'borrowing_request_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan Guru',
            'approved' => 'Disetujui Guru',
            'rejected' => 'Ditolak',
            'qr_ready' => 'QR Code Siap Digunakan',
            'borrowed' => 'Sedang Dipinjam',
            'returned' => 'Sudah Dikembalikan',
            'overdue' => 'Terlambat',
            default => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'qr_ready' => 'success',
            'borrowed' => 'primary',
            'returned' => 'success',
            'overdue' => 'danger',
            default => 'secondary',
        };
    }
}
