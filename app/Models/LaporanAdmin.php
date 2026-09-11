<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanAdmin extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'periode_awal',
        'periode_akhir',
        'dikirim_oleh',
        'status',
        'catatan_superadmin',
        'data_rekap',
    ];

    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'data_rekap' => 'array',
    ];

    // Status constants
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikirim_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING_REVIEW => 'Pending Review',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING_REVIEW => 'warning',
            self::STATUS_DISETUJUI => 'success',
            self::STATUS_DITOLAK => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Scope: Get pending reports
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING_REVIEW);
    }

    /**
     * Scope: Get approved reports
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    /**
     * Scope: Get rejected reports
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_DITOLAK);
    }
}