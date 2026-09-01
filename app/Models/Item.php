<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'inventory_number',
        'name',
        'description',
        'category_id',
        'location_id',
        'supplier_id',
        'teacher_id',
        'brand',
        'type',
        'purchase_year',
        'price',
        'condition',
        'status',
        'stock',
        'photo_path',
        'qr_code',
        'barcode',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'purchase_year' => 'integer',
        'stock'         => 'integer',
    ];

    // ==========================================
    // Relationships
    // ==========================================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Borrowing requests that are currently reserving stock
     * (status: approved atau borrowed)
     */
    public function activeBorrowingRequests()
    {
        return $this->hasMany(BorrowingRequest::class)
            ->whereIn('status', [
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_BORROWED,
            ]);
    }

    // ==========================================
    // Accessors
    // ==========================================

    /**
     * Hitung stok tersedia secara dinamis dari database.
     * Stok tersedia = stok_total - jumlah yang sedang disetujui/dipinjam.
     *
     * Tidak disimpan di DB untuk mencegah desync; selalu fresh dari transaksi aktif.
     */
    public function getAvailableStockAttribute(): int
    {
        $reserved = BorrowingRequest::where('item_id', $this->id)
            ->whereIn('status', [
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_BORROWED,
            ])
            ->sum('quantity');

        return max(0, $this->stock - (int) $reserved);
    }

    // ==========================================
    // Scopes
    // ==========================================

    /**
     * Scope: Filter barang yang stok tersedianya benar-benar > 0.
     * Menggunakan subquery agar akurat meski status di kolom belum di-sync.
     */
    public function scopeHasAvailableStock($query)
    {
        return $query->whereRaw('stock > (
            SELECT COALESCE(SUM(br.quantity), 0)
            FROM borrowing_requests br
            WHERE br.item_id = items.id
              AND br.status IN ("approved", "borrowed")
        )');
    }

    // ==========================================
    // Business Logic
    // ==========================================

    /**
     * Hitung ulang dan simpan status barang berdasarkan stok tersedia saat ini.
     *
     * Aturan:
     * - 'Tersedia'  : stok tersedia > 0
     * - 'Dipinjam'  : stok tersedia = 0 (semua unit sedang dipinjam/disetujui)
     *
     * TIDAK mengubah status 'Maintenance' — perubahan maintenance harus manual.
     */
    public function recalculateStatus(): void
    {
        if ($this->status === 'Maintenance') {
            return;
        }

        $availableStock = $this->available_stock;
        $newStatus      = $availableStock > 0 ? 'Tersedia' : 'Dipinjam';

        $this->status = $newStatus;
        $this->saveQuietly();
    }
}
