<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaporanGuru extends Model
{
    use HasFactory;

    protected $table = 'laporan_gurus';

    protected $fillable = [
        'guru_id',
        'judul',
        'periode',
        'data_laporan',
        'catatan',
        'status',
        'read_at',
    ];

    protected $casts = [
        'data_laporan' => 'array',
        'read_at'      => 'datetime',
    ];

    public const STATUS_BELUM_DIBACA = 'belum_dibaca';
    public const STATUS_SUDAH_DIBACA = 'sudah_dibaca';

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function studentBorrowingRequests()
    {
        return $this->hasMany(BorrowingRequest::class, 'teacher_id')
            ->where('tipe_peminjam', 'siswa');
    }

    public function isUnread(): bool
    {
        return $this->status === self::STATUS_BELUM_DIBACA;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_BELUM_DIBACA => 'Belum Dibaca',
            self::STATUS_SUDAH_DIBACA => 'Sudah Dibaca',
            default                   => ucfirst($this->status),
        };
    }
}
