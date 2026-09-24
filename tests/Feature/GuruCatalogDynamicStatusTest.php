<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Memastikan halaman katalog guru (/guru/barang) menampilkan status dinamis
 * (Tersedia / Menunggu / Dipinjam) yang bersifat GLOBAL — termasuk peminjaman
 * yang diajukan oleh SISWA, bukan hanya guru.
 */
class GuruCatalogDynamicStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $guru;
    private User $siswa;
    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $location = Location::create([
            'building' => 'Gedung Utama',
            'floor'    => '1',
            'room'     => 'Lab Komputer',
        ]);

        $this->guru = User::factory()->create([
            'email'    => 'guru-test@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->guru->assignRole('guru');

        $this->siswa = User::factory()->create([
            'email'    => 'siswa-test@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->siswa->assignRole('siswa');

        $this->item = Item::create([
            'name'             => 'Kamera DSLR Test',
            'code'             => 'KAM-TEST-001',
            'inventory_number' => 'INV-TEST-001',
            'category_id'      => $category->id,
            'location_id'      => $location->id,
            'stock'            => 1,
            'status'           => 'Tersedia',
            'condition'        => 'Baik',
        ]);
    }

    private function createSiswaBorrowingRequest(string $status): BorrowingRequest
    {
        $br = BorrowingRequest::create([
            'user_id'     => $this->siswa->id,
            'status'      => $status,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'purpose'     => 'Test cross-role global status',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $br->id,
            'item_id'              => $this->item->id,
            'quantity'             => 1,
        ]);

        return $br;
    }

    public function test_guru_catalog_shows_tersedia_when_no_borrowings(): void
    {
        $statusInfo = $this->item->fresh()->getCatalogStatusInfo();

        $this->assertEquals('tersedia', $statusInfo['status']);
        $this->assertFalse($statusInfo['button_disabled']);
        $this->assertGreaterThan(0, $statusInfo['available_stock']);

        $response = $this->actingAs($this->guru)->get(route('teacher.barang'));
        $response->assertStatus(200);
        $response->assertSee('Tersedia');
        $response->assertSee('Pinjam Barang');
    }

    public function test_guru_catalog_shows_menunggu_after_siswa_pending_request(): void
    {
        // 1. Siswa mengajukan peminjaman
        $this->createSiswaBorrowingRequest(BorrowingRequest::STATUS_PENDING);

        // 2. Status di model item harus berubah jadi "menunggu"
        $statusInfo = $this->item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('menunggu', $statusInfo['status'],
            'Guru seharusnya melihat MENUNGGU karena peminjaman siswa masih pending');
        $this->assertTrue($statusInfo['button_disabled']);
        $this->assertEquals(0, $statusInfo['available_stock']);

        // 3. Halaman katalog guru (/guru/barang) harus menampilkan badge Menunggu & tombol disabled
        $response = $this->actingAs($this->guru)->get(route('teacher.barang'));
        $response->assertStatus(200);
        $response->assertSee('Menunggu');
        $response->assertSee('Menunggu Persetujuan');
    }

    public function test_guru_catalog_shows_dipinjam_after_siswa_request_approved(): void
    {
        // 1. Siswa peminjaman berstatus approved
        $this->createSiswaBorrowingRequest(BorrowingRequest::STATUS_APPROVED);

        // 2. Status di model item harus berubah jadi "dipinjam"
        $statusInfo = $this->item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('dipinjam', $statusInfo['status'],
            'Guru seharusnya melihat DIPINJAM karena peminjaman siswa sudah disetujui');
        $this->assertTrue($statusInfo['button_disabled']);
        $this->assertEquals(0, $statusInfo['available_stock']);

        // 3. Halaman katalog guru harus menampilkan badge Dipinjam & tombol disabled
        $response = $this->actingAs($this->guru)->get(route('teacher.barang'));
        $response->assertStatus(200);
        $response->assertSee('Dipinjam');
    }

    public function test_guru_catalog_returns_to_tersedia_after_item_returned(): void
    {
        // 1. Peminjaman siswa sudah dikembalikan
        $this->createSiswaBorrowingRequest('returned');

        // 2. Status di model item harus kembali ke "tersedia"
        $statusInfo = $this->item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('tersedia', $statusInfo['status'],
            'Setelah peminjaman dikembalikan, status harus kembali TERSEDIA');
        $this->assertFalse($statusInfo['button_disabled']);

        // 3. Halaman katalog guru harus menampilkan Tersedia & tombol aktif
        $response = $this->actingAs($this->guru)->get(route('teacher.barang'));
        $response->assertStatus(200);
        $response->assertSee('Tersedia');
        $response->assertSee('Pinjam Barang');
    }

    public function test_guru_barang_page_renders_correctly(): void
    {
        $response = $this->actingAs($this->guru)->get(route('teacher.barang'));

        $response->assertStatus(200);
        $response->assertSee($this->item->name);
        $response->assertSee('Tersedia');
        $response->assertSee('Pinjam Barang');
    }

    public function test_guru_catalog_handles_zero_stock_item_as_habis(): void
    {
        // Item dengan stok total memang 0 (misal rusak semua / tidak ada unit)
        $zeroStockItem = Item::create([
            'name'             => 'Proyektor Rusak Total',
            'code'             => 'PRJ-RUSAK-001',
            'inventory_number' => 'INV-RUSAK-001',
            'category_id'      => $this->item->category_id,
            'location_id'      => $this->item->location_id,
            'stock'            => 0,
            'status'           => 'Tersedia', // status kolom bawaan DB
            'condition'        => 'Rusak Berat',
        ]);

        $statusInfo = $zeroStockItem->getCatalogStatusInfo();

        // 1. Status TIDAK BOLEH "tersedia"
        $this->assertNotEquals('tersedia', $statusInfo['status']);
        $this->assertEquals(0, $statusInfo['available_stock']);

        // 2. Label badge harus "Habis" dan tombol disabled "Stok Habis"
        $this->assertEquals('Habis', $statusInfo['badge_label']);
        $this->assertEquals('Stok Habis', $statusInfo['button_label']);
        $this->assertTrue($statusInfo['button_disabled']);

        // 3. Pada halaman /guru/barang, item tampil dengan badge "Habis" & tombol disabled "Stok Habis"
        $response = $this->actingAs($this->guru)->get(route('teacher.barang'));
        $response->assertStatus(200);
        $response->assertSee('Proyektor Rusak Total');
        $response->assertSee('Habis');
        $response->assertSee('Stok Habis');
    }
}
