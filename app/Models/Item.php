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
        'kode_kibb',
        'nomor_reg',
        'nomor_registrasi',
        'ukuran',
        'bahan',
        'tahun_pembelian',
        'asal_usul',
        'harga',
    ];

    protected $casts = [
        'price'            => 'decimal:2',
        'purchase_year'    => 'integer',
        'stock'            => 'integer',
        'tahun_pembelian'  => 'integer',
        'harga'            => 'decimal:2',
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

    /**
     * Hitung stok tersedia secara dinamis dari database.
     * Stok tersedia = stok_total - jumlah yang sedang diajukan/disetujui/dipinjam.
     *
     * Tidak disimpan di DB untuk mencegah desync; selalu fresh dari transaksi aktif.
     */
    public function getAvailableStockAttribute(): int
    {
        $reserved = (int) \Illuminate\Support\Facades\DB::table('borrowing_request_items')
            ->join('borrowing_requests', 'borrowing_requests.id', '=', 'borrowing_request_items.borrowing_request_id')
            ->where('borrowing_request_items.item_id', $this->id)
            ->whereIn('borrowing_requests.status', [
                BorrowingRequest::STATUS_PENDING,
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_BORROWED,
                BorrowingRequest::STATUS_OVERDUE,
            ])
            ->sum('borrowing_request_items.quantity');

        return max(0, (int) $this->stock - $reserved);
    }

    /**
     * Hitung status dinamis katalog secara real-time berdasarkan transaksi peminjaman aktif.
     * 
     * @return array{
     *   status: string,
     *   badge_label: string,
     *   badge_bg: string,
     *   badge_color: string,
     *   button_disabled: bool,
     *   button_variant: string,
     *   button_label: string,
     *   available_stock: int,
     *   total_stock: int,
     *   borrowed_stock: int,
     *   pending_stock: int
     * }
     */
    public function getCatalogStatusInfo(): array
    {
        $totalStock = max(0, (int) $this->stock);

        // Jumlah unit yang sedang disetujui / dipinjam / terlambat (active approved loans)
        $borrowedStock = (int) \Illuminate\Support\Facades\DB::table('borrowing_request_items')
            ->join('borrowing_requests', 'borrowing_requests.id', '=', 'borrowing_request_items.borrowing_request_id')
            ->where('borrowing_request_items.item_id', $this->id)
            ->whereIn('borrowing_requests.status', [
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_BORROWED,
                BorrowingRequest::STATUS_OVERDUE,
            ])
            ->sum('borrowing_request_items.quantity');

        // Jumlah unit yang sedang diajukan dan menunggu persetujuan (pending)
        $pendingStock = (int) \Illuminate\Support\Facades\DB::table('borrowing_request_items')
            ->join('borrowing_requests', 'borrowing_requests.id', '=', 'borrowing_request_items.borrowing_request_id')
            ->where('borrowing_request_items.item_id', $this->id)
            ->where('borrowing_requests.status', BorrowingRequest::STATUS_PENDING)
            ->sum('borrowing_request_items.quantity');

        // Sisa unit yang benar-benar siap dipinjam saat ini
        $availableStock = max(0, $totalStock - $borrowedStock - $pendingStock);

        // 1. TERSEDIA: Jika masih ada minimal 1 unit tersedia
        if ($availableStock > 0) {
            return [
                'status'          => 'tersedia',
                'badge_label'     => $totalStock > 1 ? 'Tersedia: ' . $availableStock : 'Tersedia',
                'badge_bg'        => '#10b981', // Hijau
                'badge_color'     => '#ffffff',
                'button_disabled' => false,
                'button_variant'  => 'primary', // Biru aktif
                'button_label'    => 'Pinjam Barang',
                'available_stock' => $availableStock,
                'total_stock'     => $totalStock,
                'borrowed_stock'  => $borrowedStock,
                'pending_stock'   => $pendingStock,
            ];
        }

        // 2. MENUNGGU: Jika semua unit pending persetujuan (belum ada yang disetujui)
        if ($pendingStock > 0 && $borrowedStock == 0) {
            return [
                'status'          => 'menunggu',
                'badge_label'     => 'Menunggu',
                'badge_bg'        => '#f59e0b', // Kuning/Amber/Orange Muda
                'badge_color'     => '#ffffff',
                'button_disabled' => true,
                'button_variant'  => 'gray', // Abu-abu disabled
                'button_label'    => 'Menunggu',
                'available_stock' => 0,
                'total_stock'     => $totalStock,
                'borrowed_stock'  => $borrowedStock,
                'pending_stock'   => $pendingStock,
            ];
        }

        // 3. DIPINJAM: Jika unit sedang dipinjam aktif (approved / borrowed / overdue)
        if ($borrowedStock > 0) {
            return [
                'status'          => 'dipinjam',
                'badge_label'     => 'Dipinjam',
                'badge_bg'        => '#ea580c', // Orange solid
                'badge_color'     => '#ffffff',
                'button_disabled' => true,
                'button_variant'  => 'orange', // Orange disabled
                'button_label'    => 'Dipinjam',
                'available_stock' => 0,
                'total_stock'     => $totalStock,
                'borrowed_stock'  => $borrowedStock,
                'pending_stock'   => $pendingStock,
            ];
        }

        if ($pendingStock > 0) {
            return [
                'status'          => 'menunggu',
                'badge_label'     => 'Menunggu',
                'badge_bg'        => '#f59e0b',
                'badge_color'     => '#ffffff',
                'button_disabled' => true,
                'button_variant'  => 'gray',
                'button_label'    => 'Menunggu',
                'available_stock' => 0,
                'total_stock'     => $totalStock,
                'borrowed_stock'  => 0,
                'pending_stock'   => $pendingStock,
            ];
        }

        // Default habis / stok 0
        return [
            'status'          => 'dipinjam',
            'badge_label'     => 'Habis',
            'badge_bg'        => '#ea580c',
            'badge_color'     => '#ffffff',
            'button_disabled' => true,
            'button_variant'  => 'orange',
            'button_label'    => 'Stok Habis',
            'available_stock' => 0,
            'total_stock'     => $totalStock,
            'borrowed_stock'  => 0,
            'pending_stock'   => 0,
        ];
    }

    public function getCatalogStatusInfoAttribute(): array
    {
        return $this->getCatalogStatusInfo();
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
            SELECT COALESCE(SUM(bri.quantity), 0)
            FROM borrowing_request_items bri
            JOIN borrowing_requests br ON br.id = bri.borrowing_request_id
            WHERE bri.item_id = items.id
              AND br.status IN ("pending", "approved", "borrowed", "overdue")
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
