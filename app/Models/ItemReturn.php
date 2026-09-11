<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemReturn extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'borrowing_request_id',
        'user_id',
        'tipe_peminjam',
        'kajur_id',
        'kondisi_barang',
        'catatan',
        'foto_bukti',
        'status',
        'alasan_ditolak',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    // Status Constants
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';

    // Condition Constants
    public const KONDISI_BAIK = 'baik';
    public const KONDISI_RUSAK_RINGAN = 'rusak_ringan';
    public const KONDISI_RUSAK_BERAT = 'rusak_berat';
    public const KONDISI_HILANG = 'hilang';

    public function borrowingRequest(): BelongsTo
    {
        return $this->belongsTo(BorrowingRequest::class, 'borrowing_request_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function kajur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kajur_id');
    }

    /**
     * Scope: Only siswa returns (for Admin verification)
     */
    public function scopeSiswa($query)
    {
        return $query->where('tipe_peminjam', 'siswa');
    }

    /**
     * Scope: Only guru returns (for Kepala Jurusan verification)
     */
    public function scopeGuru($query)
    {
        return $query->where('tipe_peminjam', 'guru');
    }

    public function getKondisiLabelAttribute(): string
    {
        return match($this->kondisi_barang) {
            self::KONDISI_BAIK => 'Baik',
            self::KONDISI_RUSAK_RINGAN => 'Rusak Ringan',
            self::KONDISI_RUSAK_BERAT => 'Rusak Berat',
            self::KONDISI_HILANG => 'Hilang',
            default => ucfirst(str_replace('_', ' ', $this->kondisi_barang)),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_MENUNGGU => 'Menunggu Verifikasi',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_MENUNGGU => 'badge-warning',
            self::STATUS_DISETUJUI => 'badge-success',
            self::STATUS_DITOLAK => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    // Scopes
    public function scopeMenunggu($query)
    {
        return $query->where('status', self::STATUS_MENUNGGU);
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', self::STATUS_DITOLAK);
    }
}
