<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanJurusanHistory extends Model
{
    protected $table = 'laporan_jurusan_histories';

    protected $fillable = [
        'laporan_jurusan_id',
        'jurusan_id',
        'periode_awal',
        'periode_akhir',
        'dikirim_oleh',
        'status',
        'catatan_admin',
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function laporanJurusan(): BelongsTo
    {
        return $this->belongsTo(LaporanJurusan::class);
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikirim_oleh');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            LaporanJurusan::STATUS_PENDING_REVIEW => 'Pending Review',
            LaporanJurusan::STATUS_DISETUJUI => 'Disetujui',
            LaporanJurusan::STATUS_DITOLAK => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            LaporanJurusan::STATUS_PENDING_REVIEW => 'warning',
            LaporanJurusan::STATUS_DISETUJUI => 'success',
            LaporanJurusan::STATUS_DITOLAK => 'danger',
            default => 'secondary',
        };
    }
}
