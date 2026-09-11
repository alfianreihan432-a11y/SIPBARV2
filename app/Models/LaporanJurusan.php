<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BorrowingRequest;

class LaporanJurusan extends Model
{
    protected $fillable = [
        'jurusan_id',
        'periode_awal',
        'periode_akhir',
        'dikirim_oleh',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
    ];

    // Status constants
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikirim_oleh');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(LaporanJurusanHistory::class, 'laporan_jurusan_id')->latest('created_at');
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

    /**
     * Build base query for borrowing requests belonging to this report's jurusan and period
     */
    public function borrowingRequestsQuery()
    {
        $jurusanId = $this->jurusan_id;
        $dikirimOleh = $this->dikirim_oleh;

        return BorrowingRequest::where('tipe_peminjam', 'guru')
            ->where(function ($q) use ($jurusanId, $dikirimOleh) {
                if ($jurusanId) {
                    $q->whereHas('user', function ($uq) use ($jurusanId) {
                        $uq->where('jurusan_id', $jurusanId);
                    });
                    $q->orWhereHas('approvedByKajur', function ($kq) use ($jurusanId) {
                        $kq->where('jurusan_id', $jurusanId);
                    });
                }
                if ($dikirimOleh) {
                    $q->orWhere('approved_by_kajur_id', $dikirimOleh);
                }
            })
            ->whereDate('borrow_date', '>=', $this->periode_awal)
            ->whereDate('borrow_date', '<=', $this->periode_akhir);
    }

    /**
     * Get list of borrowing requests with relationships
     */
    public function getBorrowingRequestsAttribute()
    {
        return $this->borrowingRequestsQuery()
            ->with(['user.jurusan', 'itemWithTrashed', 'approvedByKajur', 'checkoutBy', 'checkinBy'])
            ->latest('borrow_date')
            ->get();
    }

    /**
     * Get borrowing statistics for this report period and jurusan
     */
    public function getStatisticsAttribute(): array
    {
        $borrowings = $this->borrowingRequestsQuery()->get();

        return [
            'total_transaksi' => $borrowings->count(),
            'total_dipinjam' => $borrowings->whereIn('status', ['approved', 'borrowed'])->count(),
            'total_dikembalikan' => $borrowings->where('status', 'returned')->count(),
            'total_pending' => $borrowings->where('status', 'pending')->count(),
            'total_rejected' => $borrowings->where('status', 'rejected')->count(),
            'barang_kondisi_baik' => $borrowings->whereIn('return_condition', ['Baik', 'good'])->count(),
            'barang_kondisi_rusak_ringan' => $borrowings->whereIn('return_condition', ['Rusak Ringan', 'damaged'])->count(),
            'barang_kondisi_rusak_berat' => $borrowings->whereIn('return_condition', ['Rusak Berat', 'lost'])->count(),
        ];
    }
}