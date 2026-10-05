<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

/**
 * ReturnStockService
 *
 * Menangani logika pemulihan stok inventaris saat pengembalian barang disetujui.
 * Dipakai bersama oleh AdminReturnController::approve dan
 * KepalaJurusanController::verifyReturn agar tidak ada duplikasi.
 *
 * Aturan stok per kondisi:
 *  - hilang       : stok TIDAK dikembalikan (unit dianggap hilang permanen).
 *  - rusak_berat  : stok TIDAK dikembalikan ke inventaris tersedia
 *                   (unit rusak berat tidak dapat dipakai kembali).
 *  - baik         : stok dikembalikan (increment sesuai quantity).
 *  - rusak_ringan : stok dikembalikan (unit masih bisa dipakai meski kondisinya berkurang).
 *
 * Aturan status master item:
 *  - Stok > 0  : 'Tersedia'
 *  - Stok <= 0 :
 *      - Jika masih ada peminjaman aktif (approved / borrowed / overdue) -> 'Dipinjam'
 *      - Jika tidak ada peminjaman aktif -> 'Hilang' (jika kondisi hilang) atau 'Rusak' (jika rusak berat)
 *  - Status 'Maintenance' tidak diubah otomatis.
 *
 * Aturan kolom `condition` master item:
 *  - Keputusan desain lama yang disengaja: saat kondisi 'rusak_ringan', kolom `condition`
 *    pada master item diperbarui menjadi 'Rusak Ringan'.
 *  - Kondisi 'hilang' dan 'rusak_berat' TIDAK mengubah kolom `condition` master.
 */
class ReturnStockService
{
    /**
     * Cek apakah peminjaman memiliki lebih dari satu jenis item (multi-item).
     */
    public function isMultiItem(BorrowingRequest $borrowing): bool
    {
        $count = $borrowing->relationLoaded('items')
            ? $borrowing->items->count()
            : $borrowing->items()->count();

        return $count > 1;
    }

    /**
     * Terapkan perubahan stok pada semua item dalam satu BorrowingRequest
     * berdasarkan kondisi pengembalian.
     *
     * @param BorrowingRequest $borrowing  Model peminjaman (harus sudah load relasi items.item)
     * @param string           $kondisi    Kondisi pengembalian dalam format internal:
     *                                     'baik' | 'rusak_ringan' | 'rusak_berat' | 'hilang'
     */
    public function applyStockForReturn(BorrowingRequest $borrowing, string $kondisi): void
    {
        $itemRows = $this->collectItemsWithQuantities($borrowing);

        foreach ($itemRows as [$item, $qty]) {
            $this->applyToItem($item, $qty, $kondisi);
        }
    }

    /**
     * Terapkan logika stok ke satu item dengan quantity tertentu.
     *
     * @param Item   $item    Instance item yang stoknya akan disesuaikan
     * @param int    $qty     Jumlah unit yang dipinjam (akan dikembalikan jika kondisi memungkinkan)
     * @param string $kondisi Kondisi pengembalian ('baik'|'rusak_ringan'|'rusak_berat'|'hilang')
     */
    public function applyToItem(Item $item, int $qty, string $kondisi): void
    {
        match (true) {
            // Hilang: unit tidak kembali ke stok tersedia sama sekali
            in_array($kondisi, ['hilang', 'lost']) => $this->handleHilang($item, $kondisi),

            // Rusak Berat: unit dikembalikan secara fisik tapi tidak dapat dipakai lagi
            in_array($kondisi, ['rusak_berat', 'rusak berat']) => $this->handleRusakBerat($item, $kondisi),

            // Baik atau Rusak Ringan: unit kembali ke inventaris (masih bisa dipakai)
            default => $this->handleKembali($item, $qty, $kondisi),
        };
    }

    // -----------------------------------------------------------------------
    // Private handlers
    // -----------------------------------------------------------------------

    /**
     * Unit hilang: stok master TIDAK bertambah.
     * Kolom condition TIDAK diubah.
     * Status disinkronkan (jika stok 0 & tidak ada pinjaman aktif -> 'Hilang').
     */
    private function handleHilang(Item $item, string $kondisi): void
    {
        // Tidak ada increment stok — unit dianggap hilang permanen
        $this->syncItemStatus($item, $kondisi);
    }

    /**
     * Unit rusak berat: stok master TIDAK dikembalikan ke inventaris.
     * Kolom condition TIDAK diubah otomatis.
     * Status disinkronkan (jika stok 0 & tidak ada pinjaman aktif -> 'Rusak').
     */
    private function handleRusakBerat(Item $item, string $kondisi): void
    {
        // Tidak ada increment stok — unit rusak berat tidak masuk stok tersedia
        $this->syncItemStatus($item, $kondisi);
    }

    /**
     * Unit baik atau rusak ringan: stok dikembalikan.
     *
     * CATATAN DESAIN: Keputusan desain lama yang disengaja:
     * Saat unit dikembalikan dengan kondisi 'rusak_ringan', kolom `condition` pada
     * master item diperbarui menjadi 'Rusak Ringan'.
     */
    private function handleKembali(Item $item, int $qty, string $kondisi): void
    {
        $item->increment('stock', $qty);

        // Keputusan desain lama: perbarui condition master saat rusak ringan
        if (in_array($kondisi, ['rusak_ringan', 'rusak ringan'])) {
            $item->condition = 'Rusak Ringan';
            $item->saveQuietly();
        }

        $this->syncItemStatus($item, $kondisi);
    }

    /**
     * Sinkronisasi status master item berdasarkan jumlah stok dan pinjaman aktif.
     *
     * Logika:
     * - Status 'Maintenance' tidak diubah.
     * - Stok > 0 -> 'Tersedia'
     * - Stok <= 0:
     *     - Ada pinjaman aktif -> 'Dipinjam'
     *     - Tidak ada pinjaman aktif:
     *         - 'hilang' -> 'Hilang'
     *         - 'rusak_berat' / 'rusak' -> 'Rusak'
     *         - Default -> 'Dipinjam'
     */
    public function syncItemStatus(Item $item, string $kondisi = 'baik'): void
    {
        // Refresh instance untuk mendapatkan nilai stok terbaru dari DB
        $item->refresh();

        if ($item->status === 'Maintenance') {
            return;
        }

        if ($item->stock > 0) {
            $item->status = 'Tersedia';
            $item->saveQuietly();
            return;
        }

        // Stok <= 0: Cek apakah masih ada peminjaman aktif untuk item ini
        $hasActiveBorrowing = DB::table('borrowing_request_items')
            ->join('borrowing_requests', 'borrowing_requests.id', '=', 'borrowing_request_items.borrowing_request_id')
            ->where('borrowing_request_items.item_id', $item->id)
            ->whereIn('borrowing_requests.status', [
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_BORROWED,
                BorrowingRequest::STATUS_OVERDUE,
            ])
            ->exists();

        if (!$hasActiveBorrowing) {
            // Cek juga fallback untuk borrowing_requests legacy tanpa detail borrowing_request_items
            $hasActiveBorrowing = BorrowingRequest::where('item_id', $item->id)
                ->whereIn('status', [
                    BorrowingRequest::STATUS_APPROVED,
                    BorrowingRequest::STATUS_BORROWED,
                    BorrowingRequest::STATUS_OVERDUE,
                ])
                ->exists();
        }

        if ($hasActiveBorrowing) {
            $item->status = 'Dipinjam';
        } else {
            $item->status = match (true) {
                in_array($kondisi, ['hilang', 'lost'])                      => 'Hilang',
                in_array($kondisi, ['rusak_berat', 'rusak berat', 'rusak']) => 'Rusak',
                default                                                    => 'Dipinjam',
            };
        }

        $item->saveQuietly();
    }

    // -----------------------------------------------------------------------
    // Helper
    // -----------------------------------------------------------------------

    /**
     * Kumpulkan pasangan [Item, quantity] dari peminjaman, mendukung
     * peminjaman multi-item (tabel borrowing_request_items) maupun
     * single-item (kolom item_id + quantity di borrowing_requests).
     *
     * @return array<int, array{0: Item, 1: int}>
     */
    public function collectItemsWithQuantities(BorrowingRequest $borrowing): array
    {
        $result = [];

        // Multi-item: relasi items (borrowing_request_items)
        $items = $borrowing->relationLoaded('items') ? $borrowing->items : $borrowing->items()->with('item')->get();

        if ($items instanceof \Illuminate\Support\Collection && $items->isNotEmpty()) {
            foreach ($items as $detail) {
                $item = $detail->relationLoaded('item') ? $detail->item : $detail->item;
                if ($item instanceof Item) {
                    $result[] = [$item, (int) ($detail->quantity ?? 1)];
                }
            }
            return $result;
        }

        // Single-item: kolom item_id pada borrowing_requests
        $singleItem = $borrowing->relationLoaded('item') ? $borrowing->item : $borrowing->item;
        if ($singleItem instanceof Item) {
            $result[] = [$singleItem, (int) ($borrowing->quantity ?? 1)];
        }

        return $result;
    }
}

