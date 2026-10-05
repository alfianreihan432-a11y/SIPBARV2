<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\Item;

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
 * Setelah setiap perubahan stok, recalculateStatus() dipanggil agar kolom `status`
 * item master (Tersedia/Dipinjam) mengikuti jumlah stok secara akurat.
 * recalculateStatus() TIDAK pernah mengubah status menjadi 'Hilang' atau 'Rusak' —
 * ia hanya memilih antara 'Tersedia' (stock > 0) dan 'Dipinjam' (stock = 0),
 * sehingga status master mencerminkan ketersediaan unit yang masih ada, bukan
 * kondisi unit yang sudah hilang/rusak.
 *
 * CATATAN DESAIN — kolom `condition` master item:
 * Kode ini TIDAK mengubah kolom `condition` master item secara diam-diam.
 * Perubahan condition (misalnya mencatat 'Rusak Ringan') sebaiknya dilakukan
 * melalui proses inspeksi/maintenance manual oleh admin, bukan otomatis saat
 * pengembalian, karena satu barang bisa memiliki banyak unit dengan kondisi berbeda.
 */
class ReturnStockService
{
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
            in_array($kondisi, ['hilang', 'lost']) => $this->handleHilang($item),

            // Rusak Berat: unit dikembalikan secara fisik tapi tidak dapat dipakai lagi
            in_array($kondisi, ['rusak_berat', 'rusak berat']) => $this->handleRusakBerat($item),

            // Baik atau Rusak Ringan: unit kembali ke inventaris (masih bisa dipakai)
            default => $this->handleKembali($item, $qty, $kondisi),
        };
    }

    // -----------------------------------------------------------------------
    // Private handlers
    // -----------------------------------------------------------------------

    /**
     * Unit hilang: stok master TIDAK bertambah, status direkalkukasi.
     * Tidak menyentuh kolom `condition` — dicatat hanya melalui ItemReturn.
     */
    private function handleHilang(Item $item): void
    {
        // Tidak ada increment stok — unit dianggap hilang permanen
        $item->recalculateStatus();
    }

    /**
     * Unit rusak berat: stok master TIDAK dikembalikan ke inventaris.
     * Unit fisik ada tapi tidak layak pakai. Status direkalkukasi.
     * Tidak menyentuh kolom `condition` — kondisi master harus diperbarui
     * secara manual oleh admin melalui halaman manajemen inventaris.
     */
    private function handleRusakBerat(Item $item): void
    {
        // Tidak ada increment stok — unit rusak berat tidak masuk stok tersedia
        $item->recalculateStatus();
    }

    /**
     * Unit baik atau rusak ringan: stok dikembalikan.
     * Status master direkalkukasi setelah increment.
     *
     * CATATAN: Kolom `condition` master item TIDAK diubah secara otomatis
     * untuk kondisi 'rusak_ringan'. Alasan: satu item master bisa punya banyak
     * unit; mencatat 'Rusak Ringan' di level master saat satu unit kembali rusak
     * ringan adalah menyesatkan. Kondisi sebaiknya diperbarui manual via admin.
     * Informasi kondisi pengembalian sudah tersimpan di kolom ItemReturn.kondisi_barang
     * dan BorrowingRequest.return_condition.
     */
    private function handleKembali(Item $item, int $qty, string $kondisi): void
    {
        $item->increment('stock', $qty);
        // Setelah stok bertambah, status master mungkin berubah dari 'Dipinjam' → 'Tersedia'
        $item->recalculateStatus();
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
