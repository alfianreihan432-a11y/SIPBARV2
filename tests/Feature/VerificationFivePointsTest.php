<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Category;
use App\Models\Item;
use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VerificationFivePointsTest extends TestCase
{
    use RefreshDatabase;

    protected User $guru;
    protected User $siswa;
    protected User $kajur;
    protected Jurusan $jurusan;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kepala_jurusan', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);

        $this->jurusan = Jurusan::firstOrCreate(
            ['nama' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'TKJ']
        );

        $this->category = Category::firstOrCreate(
            ['name' => 'Elektronik Audio Visual'],
            ['slug' => 'elektronik-audio-visual', 'description' => 'Peralatan multimedia dan AV']
        );

        $this->siswa = User::factory()->create([
            'name' => 'Ahmad Siswa Test',
            'email' => 'siswa@test.sipbar.id',
            'nis' => '12345678',
            'kelas' => 'XII TKJ 1',
            'jurusan_id' => $this->jurusan->id,
        ]);
        $this->siswa->assignRole('siswa');

        $this->guru = User::factory()->create([
            'name' => 'Budi Santoso Guru',
            'email' => 'guru@test.sipbar.id',
            'nip' => '198501012010011001',
            'jurusan_id' => $this->jurusan->id,
        ]);
        $this->guru->assignRole('guru');

        $this->kajur = User::factory()->create([
            'name' => 'Dr. Hendra Kajur',
            'email' => 'kajur@test.sipbar.id',
            'nip' => '197501012000011002',
            'jurusan_id' => $this->jurusan->id,
        ]);
        $this->kajur->assignRole('kepala_jurusan');
    }

    protected function createTestItem(string $name, string $code, int $stock = 10): Item
    {
        return Item::create([
            'code' => $code,
            'inventory_number' => 'INV-' . $code,
            'name' => $name,
            'category_id' => $this->category->id,
            'stock' => $stock,
            'condition' => 'Baik',
            'status' => 'Tersedia',
        ]);
    }

    /**
     * 1. VERIFIKASI BUG 1 - SIDEBAR MENU GURU
     */
    public function test_point_1_guru_sidebar_and_dashboard_integrity(): void
    {
        // 1. Visit /guru/dashboard
        $response = $this->actingAs($this->guru)->get(route('teacher.dashboard'));
        $response->assertStatus(200);

        // Assert sidebar has "Barang" and "QR Barang" links
        $response->assertSee(route('teacher.barang'), false);
        $response->assertSee(route('teacher.qr-barang'), false);
        $response->assertSee('Barang');
        $response->assertSee('QR Barang');

        // Assert teacher dashboard content is present
        $response->assertSee('Ringkasan Statistik');
        $response->assertSee('Katalog Barang');

        // Assert "Kelola Inventaris" card points to route('teacher.barang')
        $response->assertSee('href="' . route('teacher.barang') . '"', false);
        $response->assertDontSee('route(\'inventory.index\')', false);

        // 2. Navigate to another teacher menu (e.g. /guru/barang)
        $responseOther = $this->actingAs($this->guru)->get(route('teacher.barang'));
        $responseOther->assertStatus(200);
        $responseOther->assertSee(route('teacher.dashboard'), false);
        $responseOther->assertSee(route('teacher.barang'), false);

        // 3. Navigate back to /guru/dashboard
        $responseBack = $this->actingAs($this->guru)->get(route('teacher.dashboard'));
        $responseBack->assertStatus(200);
        $responseBack->assertSee(route('teacher.barang'), false);
        $responseBack->assertSee(route('teacher.qr-barang'), false);
    }

    /**
     * 2. VERIFIKASI FITUR 2 - MODAL HAPUS KERANJANG
     */
    public function test_point_2_siswa_and_guru_cart_modal_delete(): void
    {
        $item = $this->createTestItem('Proyektor Test Epson', 'PRJ-TEST-01', 5);

        // SISWA: Set session cart
        $siswaCart = [
            $item->id => [
                'id' => $item->id,
                'name' => $item->name,
                'code' => $item->code,
                'quantity' => 2,
                'stok' => 5,
                'category' => $this->category->name,
            ]
        ];

        $responseSiswaCart = $this->actingAs($this->siswa)
            ->withSession(['student_borrowing_cart' => $siswaCart])
            ->get(route('student.loans.cart'));

        $responseSiswaCart->assertStatus(200);
        $responseSiswaCart->assertSee('id="deleteCartModal"', false);
        $responseSiswaCart->assertSee('openDeleteModal', false);
        $responseSiswaCart->assertSee('closeDeleteModal', false);
        $responseSiswaCart->assertSee('Proyektor Test Epson');

        // Test removing item via POST
        $responseRemoveSiswa = $this->actingAs($this->siswa)
            ->withSession(['student_borrowing_cart' => $siswaCart])
            ->post(route('student.loans.cart.remove', ['itemId' => $item->id]));

        $responseRemoveSiswa->assertRedirect();
        $responseRemoveSiswa->assertSessionMissing('student_borrowing_cart.' . $item->id);

        // GURU: Set session cart
        $guruCart = [
            $item->id => [
                'id' => $item->id,
                'nama' => $item->name,
                'kode' => $item->code,
                'jumlah' => 3,
                'kategori' => $this->category->name,
            ]
        ];

        $responseGuruCart = $this->actingAs($this->guru)
            ->withSession(['teacher_borrowing_cart' => $guruCart])
            ->get(route('teacher.peminjaman-guru.cart'));

        $responseGuruCart->assertStatus(200);
        $responseGuruCart->assertSee('id="deleteCartModal"', false);
        $responseGuruCart->assertSee('openDeleteModal', false);
        $responseGuruCart->assertSee('closeDeleteModal', false);
        $responseGuruCart->assertSee('Proyektor Test Epson');

        // Test removing item via POST
        $responseRemoveGuru = $this->actingAs($this->guru)
            ->withSession(['teacher_borrowing_cart' => $guruCart])
            ->post(route('teacher.peminjaman-guru.cart.remove', ['itemId' => $item->id]));

        $responseRemoveGuru->assertRedirect();
        $responseRemoveGuru->assertSessionMissing('teacher_borrowing_cart.' . $item->id);
    }

    /**
     * 3. VERIFIKASI FITUR 3 - PENGHAPUSAN EDIT FOTO PROFIL
     */
    public function test_point_3_profile_pages_photo_upload_removed(): void
    {
        // 1. Siswa Profile
        $responseSiswa = $this->actingAs($this->siswa)->get(route('student.profile'));
        $responseSiswa->assertStatus(200);
        $responseSiswa->assertDontSee('type="file"', false);
        $responseSiswa->assertDontSee('previewAndUpload', false);
        $responseSiswa->assertDontSee('for="foto_profil"', false);

        // 2. Guru Profile
        $responseGuru = $this->actingAs($this->guru)->get(route('teacher.profile'));
        $responseGuru->assertStatus(200);
        $responseGuru->assertDontSee('type="file"', false);
        $responseGuru->assertDontSee('previewAndUpload', false);

        // 3. Kajur Profile
        $responseKajur = $this->actingAs($this->kajur)->get(route('kajur.profile'));
        $responseKajur->assertStatus(200);
        $responseKajur->assertDontSee('type="file"', false);
        $responseKajur->assertDontSee('uploadAvatar', false);
    }

    /**
     * 4. VERIFIKASI BUG 4 - SEARCH BAR BERFUNGSI
     */
    public function test_point_4_search_bar_functionality(): void
    {
        $itemMatch = $this->createTestItem('Laptop Dell Latitude TestMatch', 'LAT-TEST-99', 5);

        // --- GURU SEARCH ---
        // Search with matching keyword
        $responseGuruMatch = $this->actingAs($this->guru)->get(route('teacher.barang', ['search' => 'Latitude TestMatch']));
        $responseGuruMatch->assertStatus(200);
        $responseGuruMatch->assertSee('Laptop Dell Latitude TestMatch');

        // Search with non-matching keyword
        $responseGuruNonMatch = $this->actingAs($this->guru)->get(route('teacher.barang', ['search' => 'XYZ_NONEXISTENT_ITEM_KEYWORD']));
        $responseGuruNonMatch->assertStatus(200);
        $responseGuruNonMatch->assertDontSee('Laptop Dell Latitude TestMatch');
        // View menampilkan "Barang tidak ditemukan" dan "Tidak ada barang yang sesuai" saat ada filter pencarian aktif
        $responseGuruNonMatch->assertSee('Barang tidak ditemukan');

        // --- SISWA LIVEWIRE CATALOG SEARCH ---
        Livewire::actingAs($this->siswa)
            ->test(\App\Livewire\ItemCatalog::class, ['search' => 'Latitude TestMatch'])
            ->assertSee('Laptop Dell Latitude TestMatch');

        Livewire::actingAs($this->siswa)
            ->test(\App\Livewire\ItemCatalog::class, ['search' => 'XYZ_NONEXISTENT_ITEM_KEYWORD'])
            ->assertDontSee('Laptop Dell Latitude TestMatch')
            ->assertSee('Barang tidak ditemukan');

        // --- KAJUR PENDING APPROVAL SEARCH ---
        $responseKajur = $this->actingAs($this->kajur)->get(route('kajur.pending-approvals', ['search' => 'NONEXISTENT_TEACHER_KEYWORD']));
        $responseKajur->assertStatus(200);
    }

    /**
     * 5. VERIFIKASI FITUR 5 - MODAL APPROVAL DASHBOARD KAJUR
     */
    public function test_point_5_kajur_dashboard_approval_modal_and_actions(): void
    {
        $item1 = $this->createTestItem('Kamera DSLR Canon Test', 'CAM-TEST-01', 3);
        $item2 = $this->createTestItem('Tripod Takara Test', 'TRP-TEST-01', 5);

        // Create a multi-item BorrowingRequest for teacher
        $loan = BorrowingRequest::create([
            'user_id' => $this->guru->id,
            'tipe_peminjam' => 'guru',
            'approved_by_kajur_id' => $this->kajur->id,
            'item_id' => $item1->id,
            'quantity' => 1,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00',
            'purpose' => 'Ujian Praktik Multimedia Verification Test',
            'status' => BorrowingRequest::STATUS_PENDING,
        ]);

        // Attach details
        BorrowingRequestItem::create([
            'borrowing_request_id' => $loan->id,
            'item_id' => $item1->id,
            'quantity' => 1,
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $loan->id,
            'item_id' => $item2->id,
            'quantity' => 2,
        ]);

        // 1. Visit Kajur Dashboard
        $responseDashboard = $this->actingAs($this->kajur)->get(route('kajur.dashboard'));
        $responseDashboard->assertStatus(200);

        // Assert table has Review & Proses button and modal
        $responseDashboard->assertSee('Permohonan Peminjaman Terbaru');
        $responseDashboard->assertSee('Review', false);
        $responseDashboard->assertSee('Proses', false);
        $responseDashboard->assertSee('id="kajurApprovalModal"', false);
        $responseDashboard->assertSee('openApprovalModal', false);
        $responseDashboard->assertSee('Ujian Praktik Multimedia Verification Test');

        // 2. Test Approval Action
        $responseApprove = $this->actingAs($this->kajur)->post(route('kajur.approve-request', $loan->id));
        $responseApprove->assertRedirect();
        
        $loan->refresh();
        $this->assertEquals(BorrowingRequest::STATUS_APPROVED, $loan->status);

        // 3. Buat peminjaman lain untuk Pengujian Penolakan
        $loanReject = BorrowingRequest::create([
            'user_id' => $this->guru->id,
            'tipe_peminjam' => 'guru',
            'approved_by_kajur_id' => $this->kajur->id,
            'item_id' => $item1->id,
            'quantity' => 1,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(1)->toDateString(),
            'return_time' => '15:00',
            'purpose' => 'Peminjaman Ditolak Verification Test',
            'status' => BorrowingRequest::STATUS_PENDING,
        ]);

        // Test rejection with empty reason (should fail validation)
        $responseRejectEmpty = $this->actingAs($this->kajur)->post(route('kajur.reject-request', $loanReject->id), ['rejection_reason' => '']);
        $responseRejectEmpty->assertSessionHasErrors('rejection_reason');

        // Test rejection with valid reason
        $responseRejectValid = $this->actingAs($this->kajur)->post(route('kajur.reject-request', $loanReject->id), [
            'rejection_reason' => 'Barang sedang dalam perbaikan',
        ]);
        $responseRejectValid->assertRedirect();

        $loanReject->refresh();
        $this->assertEquals(BorrowingRequest::STATUS_REJECTED, $loanReject->status);
        $this->assertEquals('Barang sedang dalam perbaikan', $loanReject->rejection_reason);
    }

    /**
     * KAJUR - STOK 0 DENGAN PEMINJAMAN AKTIF (DIPINJAM)
     */
    public function test_kajur_stock_zero_with_active_borrowing(): void
    {
        $item = $this->createTestItem('Kamera DSLR Canon Stock Zero Active', 'CAM-STOCK-ZERO-ACTIVE-01', 1);

        $loan = BorrowingRequest::create([
            'user_id' => $this->guru->id,
            'tipe_peminjam' => 'guru',
            'approved_by_kajur_id' => $this->kajur->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00',
            'purpose' => 'Peminjaman Stock Zero Active Test',
            'status' => BorrowingRequest::STATUS_BORROWED,
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $loan->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        // Set stock to 0 to simulate all units borrowed
        $item->stock = 0;
        $item->saveQuietly();

        // Item status should be Dipinjam
        $item->recalculateStatus();
        $this->assertEquals('Dipinjam', $item->status);

        // Cleanup
        BorrowingRequestItem::where('borrowing_request_id', $loan->id)->delete();
        $loan->delete();
        $item->delete();
    }

    /**
     * KAJUR - STOK 0 TANPA PEMINJAMAN AKTIF (HILANG/RUSAK)
     */
    public function test_kajur_stock_zero_without_active_borrowing(): void
    {
        // Test Hilang
        $itemHilang = $this->createTestItem('Kamera DSLR Canon Hilang Stock Zero', 'CAM-HILANG-ZERO-01', 0);
        $itemHilang->status = 'Hilang';
        $itemHilang->condition = 'hilang';
        $itemHilang->saveQuietly();

        $itemHilang->recalculateStatus();
        $this->assertEquals('Hilang', $itemHilang->status);

        // Test Rusak
        $itemRusak = $this->createTestItem('Kamera DSLR Canon Rusak Stock Zero', 'CAM-RUSAK-ZERO-01', 0);
        $itemRusak->status = 'Rusak';
        $itemRusak->condition = 'rusak_berat';
        $itemRusak->saveQuietly();

        $itemRusak->recalculateStatus();
        $this->assertEquals('Rusak', $itemRusak->status);

        // Cleanup
        $itemHilang->delete();
        $itemRusak->delete();
    }

    /**
     * ADMIN DAN KAJUR - RUSAK BERAT STOK 0 DENGAN PEMINJAMAN AKTIF (DIPINJAM)
     */
    public function test_admin_and_kajur_rusak_berat_stock_zero_with_active_borrowing(): void
    {
        $superadmin = User::factory()->create([
            'name' => 'Superadmin Test',
            'email' => 'superadmin@test.sipbar.id',
        ]);
        $superadmin->assignRole('superadmin');

        $item = $this->createTestItem('Kamera DSLR Canon Rusak Berat Active', 'CAM-RUSAK-BERAT-ACTIVE-01', 1);

        $loan = BorrowingRequest::create([
            'user_id' => $this->guru->id,
            'tipe_peminjam' => 'guru',
            'approved_by_kajur_id' => $this->kajur->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00',
            'purpose' => 'Peminjaman Rusak Berat Active Test',
            'status' => BorrowingRequest::STATUS_BORROWED,
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $loan->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        // Set stock to 0, status to Rusak
        $item->stock = 0;
        $item->status = 'Rusak';
        $item->condition = 'rusak_berat';
        $item->saveQuietly();

        // Item status should remain Rusak even with active borrowing
        $item->recalculateStatus();
        $this->assertEquals('Rusak', $item->status);

        // Cleanup
        BorrowingRequestItem::where('borrowing_request_id', $loan->id)->delete();
        $loan->delete();
        $item->delete();
        $superadmin->delete();
    }

    /**
     * QUANTITY > 1 PADA SATU JENIS BARANG - DEFAULT BEHAVIOR
     *
     * Test ini mendokumentasikan perilaku saat ini:
     * - Pada single-item (legacy), seluruh quantity dianggap hilang/rusak_berat saat dilaporkan
     * - Form belum mendukung input jumlah parsial untuk kondisi hilang/rusak_berat
     * - 1 jenis barang, quantity 3, 'hilang' -> stok tidak bertambah
     */
    public function test_single_item_quantity_gt_1_hilang_does_not_increment_stock(): void
    {
        $item = $this->createTestItem('Laptop Dell Quantity Test', 'LAP-QTY-TEST-01', 5);

        // Create single-item borrowing request with quantity 3
        $loan = BorrowingRequest::create([
            'user_id' => $this->guru->id,
            'tipe_peminjam' => 'guru',
            'approved_by_kajur_id' => $this->kajur->id,
            'item_id' => $item->id,
            'quantity' => 3,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00',
            'purpose' => 'Peminjaman Quantity Test',
            'status' => BorrowingRequest::STATUS_BORROWED,
        ]);

        // Reduce stock to simulate borrowed items
        $item->stock = 2;
        $item->saveQuietly();

        // Teacher submits return request as hilang
        $loan->update(['status' => BorrowingRequest::STATUS_RETURNED]);

        // Admin approves with condition 'hilang'
        // Pada single-item legacy, seluruh quantity (3) dianggap hilang
        $service = new \App\Services\ReturnStockService();
        $service->applyToItem($item, 3, 'hilang');

        // Stock should NOT increase (all 3 units considered lost)
        $item->refresh();
        $this->assertEquals(2, $item->stock);
        // Status becomes 'Tersedia' because stock > 0 (perilaku ReturnStockService::syncItemStatus)
        // Catatan: perilaku ini berbeda dengan Item::recalculateStatus() yang baru
        $this->assertEquals('Tersedia', $item->status);

        // Cleanup
        $loan->delete();
        $item->delete();
    }
}
