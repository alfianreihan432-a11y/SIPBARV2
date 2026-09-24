<?php

namespace Tests\Feature;

use App\Livewire\ItemCatalog;
use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DynamicItemCatalogStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_lifecycle_dynamic_status_in_catalog()
    {
        // 1. Setup Data: Buat 1 barang baru dengan stok 1
        $category = Category::firstOrCreate(['name' => 'Elektronik'], ['slug' => 'elektronik']);
        $item = Item::create([
            'name' => 'Kamera DSLR Canon EOS Test',
            'code' => 'TEST-DSLR-001',
            'inventory_number' => 'INV-DSLR-001',
            'stock' => 1,
            'status' => 'Tersedia',
            'condition' => 'Baik',
            'category_id' => $category->id,
        ]);

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);

        $student = User::firstOrCreate(['email' => 'siswa_test@example.com'], [
            'name' => 'Siswa Test',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole('siswa');

        $teacher = User::firstOrCreate(['email' => 'guru_test@example.com'], [
            'name' => 'Guru Test',
            'password' => bcrypt('password'),
        ]);
        $teacher->assignRole('guru');

        // =========================================================================
        // STEP 1: Kondisi Awal -> TERSEDIA
        // =========================================================================
        $infoInitial = $item->getCatalogStatusInfo();
        $this->assertEquals('tersedia', $infoInitial['status']);
        $this->assertEquals('Tersedia', $infoInitial['badge_label']);
        $this->assertFalse($infoInitial['button_disabled']);
        $this->assertEquals('primary', $infoInitial['button_variant']);

        // Livewire Component Assertion
        Livewire::actingAs($student)
            ->test(ItemCatalog::class)
            ->set('search', 'TEST-DSLR-001')
            ->assertSee('Tersedia')
            ->assertSee('Pinjam Barang')
            ->assertDontSee('Menunggu')
            ->assertDontSee('Dipinjam');

        // =========================================================================
        // STEP 2: Buat Pengajuan Peminjaman Baru -> Status jadi MENUNGGU
        // =========================================================================
        $borrowRequest = BorrowingRequest::create([
            'user_id' => $student->id,
            'teacher_id' => $teacher->id,
            'purpose' => 'Praktikum Fotografi',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '15:00',
            'whatsapp_number' => '081234567890',
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'siswa',
        ]);

        $borrowItem = BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowRequest->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $infoPending = $item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('menunggu', $infoPending['status']);
        $this->assertEquals('Menunggu', $infoPending['badge_label']);
        $this->assertTrue($infoPending['button_disabled']);
        $this->assertEquals('gray', $infoPending['button_variant']);

        // Livewire Component Assertion
        Livewire::actingAs($student)
            ->test(ItemCatalog::class)
            ->set('search', 'TEST-DSLR-001')
            ->assertSee('Menunggu')
            ->assertDontSee('Tersedia')
            ->assertDontSee('Dipinjam');

        // =========================================================================
        // STEP 3: Approve / Setujui Peminjaman -> Status jadi DIPINJAM
        // =========================================================================
        $borrowRequest->update(['status' => BorrowingRequest::STATUS_APPROVED]);

        $infoApproved = $item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('dipinjam', $infoApproved['status']);
        $this->assertEquals('Dipinjam', $infoApproved['badge_label']);
        $this->assertTrue($infoApproved['button_disabled']);
        $this->assertEquals('orange', $infoApproved['button_variant']);

        // Also test status BORROWED (sedang dibawa peminjam)
        $borrowRequest->update(['status' => BorrowingRequest::STATUS_BORROWED]);

        $infoBorrowed = $item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('dipinjam', $infoBorrowed['status']);
        $this->assertEquals('Dipinjam', $infoBorrowed['badge_label']);
        $this->assertTrue($infoBorrowed['button_disabled']);
        $this->assertEquals('orange', $infoBorrowed['button_variant']);

        // Livewire Component Assertion
        Livewire::actingAs($student)
            ->test(ItemCatalog::class)
            ->set('search', 'TEST-DSLR-001')
            ->assertSee('Dipinjam')
            ->assertDontSee('Tersedia')
            ->assertDontSee('Menunggu');

        // =========================================================================
        // STEP 4: Pengembalian Barang -> Status KEMBALI ke TERSEDIA
        // =========================================================================
        $borrowRequest->update(['status' => BorrowingRequest::STATUS_RETURNED]);

        $infoReturned = $item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('tersedia', $infoReturned['status']);
        $this->assertEquals('Tersedia', $infoReturned['badge_label']);
        $this->assertFalse($infoReturned['button_disabled']);
        $this->assertEquals('primary', $infoReturned['button_variant']);

        // Livewire Component Assertion
        Livewire::actingAs($student)
            ->test(ItemCatalog::class)
            ->set('search', 'TEST-DSLR-001')
            ->assertSee('Tersedia')
            ->assertSee('Pinjam Barang')
            ->assertDontSee('Menunggu')
            ->assertDontSee('Dipinjam');

        // Cleanup test record
        $borrowItem->delete();
        $borrowRequest->delete();
        $item->delete();
    }

    public function test_multi_stock_item_partial_availability()
    {
        $category = Category::firstOrCreate(['name' => 'Elektronik'], ['slug' => 'elektronik']);
        $item = Item::create([
            'name' => 'Laptop Acer Multi-Unit Test',
            'code' => 'TEST-LAPTOP-MULTI',
            'inventory_number' => 'INV-LAPTOP-002',
            'stock' => 3,
            'status' => 'Tersedia',
            'condition' => 'Baik',
            'category_id' => $category->id,
        ]);

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);

        $student = User::firstOrCreate(['email' => 'siswa_test@example.com'], [
            'name' => 'Siswa Test',
            'password' => bcrypt('password'),
        ]);
        $student->assignRole('siswa');

        $teacher = User::firstOrCreate(['email' => 'guru_test@example.com'], [
            'name' => 'Guru Test',
            'password' => bcrypt('password'),
        ]);
        $teacher->assignRole('guru');

        // 3 unit total -> 3 tersedia
        $info = $item->getCatalogStatusInfo();
        $this->assertEquals('tersedia', $info['status']);
        $this->assertEquals('Tersedia: 3', $info['badge_label']);
        $this->assertEquals(3, $info['available_stock']);

        // 1 unit dipinjam -> 2 unit tersedia
        $req1 = BorrowingRequest::create([
            'user_id' => $student->id,
            'teacher_id' => $teacher->id,
            'purpose' => 'Test 1',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(1)->toDateString(),
            'return_time' => '12:00',
            'status' => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);
        $itemReq1 = BorrowingRequestItem::create([
            'borrowing_request_id' => $req1->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $info = $item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('tersedia', $info['status']);
        $this->assertEquals('Tersedia: 2', $info['badge_label']);
        $this->assertEquals(2, $info['available_stock']);

        // 1 unit pending lagi -> sisa 1 unit tersedia
        $req2 = BorrowingRequest::create([
            'user_id' => $student->id,
            'teacher_id' => $teacher->id,
            'purpose' => 'Test 2',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(1)->toDateString(),
            'return_time' => '12:00',
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'siswa',
        ]);
        $itemReq2 = BorrowingRequestItem::create([
            'borrowing_request_id' => $req2->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $info = $item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('tersedia', $info['status']);
        $this->assertEquals('Tersedia: 1', $info['badge_label']);
        $this->assertEquals(1, $info['available_stock']);

        // 1 unit pending terakhir -> sisa 0 unit -> Status jadi DIPINJAM (karena ada 1 yang borrowed)
        $req3 = BorrowingRequest::create([
            'user_id' => $student->id,
            'teacher_id' => $teacher->id,
            'purpose' => 'Test 3',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(1)->toDateString(),
            'return_time' => '12:00',
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'siswa',
        ]);
        $itemReq3 = BorrowingRequestItem::create([
            'borrowing_request_id' => $req3->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $info = $item->fresh()->getCatalogStatusInfo();
        $this->assertEquals('dipinjam', $info['status']);
        $this->assertEquals('Dipinjam', $info['badge_label']);
        $this->assertEquals(0, $info['available_stock']);

        // Cleanup
        $itemReq1->delete();
        $itemReq2->delete();
        $itemReq3->delete();
        $req1->delete();
        $req2->delete();
        $req3->delete();
        $item->delete();
    }
}
