<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RiwayatPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    protected User $studentA;
    protected User $studentB;
    protected User $teacher;
    protected User $admin;
    protected Item $itemA;
    protected Item $itemB;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kepala_jurusan', 'guard_name' => 'web']);

        $this->studentA = User::factory()->create(['name' => 'Ahmad Siswa A', 'email' => 'siswaA@test.com']);
        $this->studentA->assignRole('siswa');

        $this->studentB = User::factory()->create(['name' => 'Budi Siswa B', 'email' => 'siswaB@test.com']);
        $this->studentB->assignRole('siswa');

        $this->teacher = User::factory()->create(['name' => 'Pak Guru Test', 'email' => 'guru@test.com']);
        $this->teacher->assignRole('guru');

        $this->admin = User::factory()->create(['name' => 'Admin Test', 'email' => 'admin@test.com']);
        $this->admin->assignRole('admin');

        $this->itemA = Item::create([
            'code' => 'BRG-001',
            'inventory_number' => 'INV-001',
            'name' => 'Proyektor Epson EB-X400',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 10,
        ]);

        $this->itemB = Item::create([
            'code' => 'BRG-002',
            'inventory_number' => 'INV-002',
            'name' => 'Kamera DSLR Canon EOS',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 5,
        ]);
    }

    /**
     * Siswa dapat melihat riwayat peminjaman miliknya sendiri termasuk yang berstatus returned (single & multi-item).
     */
    public function test_siswa_can_view_own_history_including_returned_single_and_multi_item(): void
    {
        // 1. Single item returned
        BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Presentasi Kelas',
            'borrow_date' => now()->subDays(5)->toDateString(),
            'return_date' => now()->subDays(2)->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => now()->subDays(2),
            'tipe_peminjam' => 'siswa',
        ]);

        // 2. Multi item returned
        $multiReq = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => null,
            'quantity' => 2,
            'purpose' => 'Praktikum Multimedia',
            'borrow_date' => now()->subDays(4)->toDateString(),
            'return_date' => now()->subDays(1)->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => now()->subDays(1),
            'tipe_peminjam' => 'siswa',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $multiReq->id,
            'item_id' => $this->itemB->id,
            'quantity' => 2,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $response = $this->actingAs($this->studentA)->get(route('student.history'));

        $response->assertStatus(200);
        $response->assertSee('Proyektor Epson EB-X400');
        $response->assertSee('Kamera DSLR Canon EOS');
        $response->assertSee('s-badge--returned');
        $response->assertSeeInOrder(['Proyektor Epson EB-X400', 's-badge--returned']);
        $response->assertSeeInOrder(['Kamera DSLR Canon EOS', 's-badge--returned']);
    }

    /**
     * Guru dapat melihat riwayat pengembalian miliknya meski memiliki legacy tipe_peminjam 'siswa'.
     */
    public function test_guru_can_view_own_history_including_returned_with_legacy_tipe_peminjam_siswa(): void
    {
        // Peminjaman guru dengan tipe_peminjam='siswa' (legacy)
        BorrowingRequest::create([
            'user_id' => $this->teacher->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Mengajar Lab',
            'borrow_date' => now()->subDays(7)->toDateString(),
            'return_date' => now()->subDays(3)->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => now()->subDays(3),
            'tipe_peminjam' => 'siswa', // legacy data mismatch
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher.pengembalian-guru.history'));

        $response->assertStatus(200);
        $response->assertSee('Proyektor Epson EB-X400');
    }

    /**
     * Isolasi data: Siswa B tidak melihat peminjaman Siswa A, dan Siswa tidak melihat peminjaman Guru.
     */
    public function test_user_cannot_see_other_users_history_isolation(): void
    {
        // Milik Siswa A
        BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Tugas Siswa A Khusus',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'tipe_peminjam' => 'siswa',
        ]);

        // Milik Guru
        BorrowingRequest::create([
            'user_id' => $this->teacher->id,
            'item_id' => $this->itemB->id,
            'quantity' => 1,
            'purpose' => 'Rapat Dewan Guru',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'tipe_peminjam' => 'guru',
        ]);

        // Siswa B mengakses riwayat
        $responseSiswaB = $this->actingAs($this->studentB)->get(route('student.history'));
        $responseSiswaB->assertStatus(200);
        $responseSiswaB->assertDontSee('Proyektor Epson EB-X400');
        $responseSiswaB->assertDontSee('Kamera DSLR Canon EOS');

        // Siswa A mengakses riwayat
        $responseSiswaA = $this->actingAs($this->studentA)->get(route('student.history'));
        $responseSiswaA->assertStatus(200);
        $responseSiswaA->assertSee('Proyektor Epson EB-X400');
        $responseSiswaA->assertDontSee('Kamera DSLR Canon EOS');
    }

    /**
     * Pencarian di halaman riwayat siswa bekerja untuk nama barang.
     */
    public function test_siswa_history_search_filter_by_item_name(): void
    {
        BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Presentasi',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'tipe_peminjam' => 'siswa',
        ]);

        BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemB->id,
            'quantity' => 1,
            'purpose' => 'Dokumentasi',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'tipe_peminjam' => 'siswa',
        ]);

        $response = $this->actingAs($this->studentA)->get(route('student.history', ['search' => 'Epson']));
        $response->assertStatus(200);
        $response->assertSee('Proyektor Epson EB-X400');
        $response->assertDontSee('Kamera DSLR Canon EOS');
    }

    /**
     * Filter status di halaman riwayat siswa berfungsi baik.
     */
    public function test_siswa_history_status_filter(): void
    {
        BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Barang Returned',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'tipe_peminjam' => 'siswa',
        ]);

        BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemB->id,
            'quantity' => 1,
            'purpose' => 'Barang Pending',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'siswa',
        ]);

        $response = $this->actingAs($this->studentA)->get(route('student.history', ['status' => 'returned']));
        $response->assertStatus(200);
        $response->assertSee('Proyektor Epson EB-X400');
        $response->assertSee('Barang Returned');
        $response->assertDontSee('Kamera DSLR Canon EOS');
        $response->assertDontSee('Barang Pending');
        $response->assertSeeInOrder(['Proyektor Epson EB-X400', 's-badge--returned']);
    }

    /**
     * (1) Search nama barang pada peminjaman multi-item.
     */
    public function test_siswa_history_search_by_item_name_on_multi_item_borrowing(): void
    {
        // Multi-item request
        $multiReq = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => null,
            'quantity' => 2,
            'purpose' => 'Praktikum Multi',
            'borrow_date' => now()->subDays(2)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => now(),
            'tipe_peminjam' => 'siswa',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $multiReq->id,
            'item_id' => $this->itemB->id,
            'quantity' => 2,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        // Single-item request with Item A
        BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Presentasi Single',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => now(),
            'tipe_peminjam' => 'siswa',
        ]);

        // Search for 'Canon' (item B in multi-item)
        $response = $this->actingAs($this->studentA)->get(route('student.history', ['search' => 'Canon']));
        $response->assertStatus(200);
        $response->assertSee('Kamera DSLR Canon EOS');
        $response->assertDontSee('Presentasi Single');
    }

    /**
     * (2) Filter terlambat (borrowed lewat tenggat tampil, borrowed belum lewat dan approved tidak tampil, badge Terlambat muncul).
     */
    public function test_siswa_history_filter_overdue_and_badge_display(): void
    {
        // 1. Borrowed & lewat tenggat (overdue)
        $overdueReq = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Peminjaman Terlambat Test',
            'borrow_date' => now()->subDays(5)->toDateString(),
            'return_date' => now()->subDays(1)->toDateString(),
            'return_time' => '12:00',
            'status' => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        // 2. Borrowed & belum lewat (active, not overdue)
        $activeReq = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemB->id,
            'quantity' => 1,
            'purpose' => 'Peminjaman Belum Lewat Test',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00',
            'status' => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        // 3. Approved tapi return_date lewat (approved is not borrowed, should NOT be overdue)
        $approvedReq = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Peminjaman Approved Lewat Test',
            'borrow_date' => now()->subDays(5)->toDateString(),
            'return_date' => now()->subDays(1)->toDateString(),
            'return_time' => '12:00',
            'status' => BorrowingRequest::STATUS_APPROVED,
            'tipe_peminjam' => 'siswa',
        ]);

        $response = $this->actingAs($this->studentA)->get(route('student.history', ['status' => 'overdue']));
        $response->assertStatus(200);
        $response->assertSee('Peminjaman Terlambat Test');
        $response->assertDontSee('Peminjaman Belum Lewat Test');
        $response->assertDontSee('Peminjaman Approved Lewat Test');
        $response->assertSee('s-badge--rejected');
        $response->assertSeeInOrder(['Peminjaman Terlambat Test', 's-badge--rejected']);

        // Verifikasi pada tampilan semua riwayat: baris belum lewat berstatus borrowed, baris lewat tenggat berstatus rejected/terlambat
        $responseAll = $this->actingAs($this->studentA)->get(route('student.history'));
        $responseAll->assertStatus(200);
        $responseAll->assertSeeInOrder(['Peminjaman Belum Lewat Test', 's-badge--borrowed']);
        $responseAll->assertSeeInOrder(['Peminjaman Terlambat Test', 's-badge--rejected']);
    }

    /**
     * (3) Filter tanggal guru dengan returned_at null fallback ke borrow_date.
     */
    public function test_guru_history_date_filter_with_null_returned_at(): void
    {
        // Guru request with returned_at NULL, borrow_date in range
        BorrowingRequest::create([
            'user_id' => $this->teacher->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Guru In Range Borrow Date',
            'borrow_date' => '2026-03-10',
            'return_date' => '2026-03-12',
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => null,
            'tipe_peminjam' => 'guru',
        ]);

        // Guru request with returned_at outside range
        BorrowingRequest::create([
            'user_id' => $this->teacher->id,
            'item_id' => $this->itemB->id,
            'quantity' => 1,
            'purpose' => 'Guru Outside Range',
            'borrow_date' => '2026-01-01',
            'return_date' => '2026-01-05',
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => '2026-01-05 10:00:00',
            'tipe_peminjam' => 'guru',
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher.pengembalian-guru.history', [
            'date_from' => '2026-03-01',
            'date_to' => '2026-03-15',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Proyektor Epson EB-X400');
        $response->assertDontSee('Kamera DSLR Canon EOS');
    }

    /**
     * (4) Filter returned menampilkan peminjaman yang status-nya belum sinkron tapi ItemReturn disetujui.
     */
    public function test_siswa_history_returned_filter_shows_unsynced_borrowing_with_approved_item_return(): void
    {
        // Borrowing request with status 'borrowed' (not yet synced to 'returned')
        $unsyncedBorrowing = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Pengembalian Belum Sinkron',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        // ItemReturn is approved
        ItemReturn::create([
            'borrowing_request_id' => $unsyncedBorrowing->id,
            'user_id' => $this->studentA->id,
            'tipe_peminjam' => 'siswa',
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_DISETUJUI,
            'diverifikasi_oleh' => $this->admin->id,
            'tanggal_verifikasi' => now(),
        ]);

        $response = $this->actingAs($this->studentA)->get(route('student.history', ['status' => 'returned']));
        $response->assertStatus(200);
        $response->assertSee('Pengembalian Belum Sinkron');
    }

    /**
     * (5) Verifikasi admin menyinkronkan status dan mengembalikan stok multi-item.
     */
    public function test_admin_verification_syncs_status_and_restores_multi_item_stock(): void
    {
        $this->itemA->update(['stock' => 5]);
        $this->itemB->update(['stock' => 3]);

        $multiBorrowing = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => null,
            'quantity' => 3,
            'purpose' => 'Praktikum Admin Sync Multi',
            'borrow_date' => now()->subDays(2)->toDateString(),
            'return_date' => now()->toDateString(),
            'status' => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $multiBorrowing->id,
            'item_id' => $this->itemA->id,
            'quantity' => 2,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $multiBorrowing->id,
            'item_id' => $this->itemB->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $multiBorrowing->id,
            'user_id' => $this->studentA->id,
            'tipe_peminjam' => 'siswa',
            'kondisi_barang' => 'baik',
            'catatan' => 'Semua barang kembali lengkap dan baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id));
        $response->assertSessionHas('success');

        // Check ItemReturn status
        $this->assertEquals(ItemReturn::STATUS_DISETUJUI, $itemReturn->fresh()->status);
        $this->assertEquals($this->admin->id, $itemReturn->fresh()->diverifikasi_oleh);

        // Check BorrowingRequest status & returned_at
        $freshBorrowing = $multiBorrowing->fresh();
        $this->assertEquals(BorrowingRequest::STATUS_RETURNED, $freshBorrowing->status);
        $this->assertNotNull($freshBorrowing->returned_at);
        $this->assertEquals('Baik', $freshBorrowing->return_condition);

        // Check stock restorations
        $this->assertEquals(7, $this->itemA->fresh()->stock); // 5 + 2
        $this->assertEquals(4, $this->itemB->fresh()->stock); // 3 + 1
    }

    /**
     * Riwayat guru menampilkan badge "Menunggu Kajur" saat checkin_by null dan "Terverifikasi" saat checkin_by terisi.
     */
    public function test_guru_history_displays_menunggu_kajur_and_terverifikasi_badges(): void
    {
        $kajur = User::factory()->create(['name' => 'Kajur PPLG Test']);
        $kajur->assignRole('kepala_jurusan');

        // Peminjaman 1: Menunggu verifikasi (checkin_by null)
        BorrowingRequest::create([
            'user_id' => $this->teacher->id,
            'item_id' => $this->itemA->id,
            'quantity' => 1,
            'purpose' => 'Peminjaman Belum Verif Kajur',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->subDay()->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => now()->subDay(),
            'checkin_by' => null,
            'tipe_peminjam' => 'guru',
        ]);

        // Peminjaman 2: Sudah terverifikasi (checkin_by terisi ID kajur)
        BorrowingRequest::create([
            'user_id' => $this->teacher->id,
            'item_id' => $this->itemB->id,
            'quantity' => 1,
            'purpose' => 'Peminjaman Sudah Verif Kajur',
            'borrow_date' => now()->subDays(4)->toDateString(),
            'return_date' => now()->subDays(2)->toDateString(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'returned_at' => now()->subDays(2),
            'checkin_by' => $kajur->id,
            'tipe_peminjam' => 'guru',
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher.pengembalian-guru.history'));

        $response->assertStatus(200);
        $response->assertSee('Proyektor Epson EB-X400');
        $response->assertSee('Kamera DSLR Canon EOS');
        $response->assertSeeInOrder(['Proyektor Epson EB-X400', 'Menunggu Kajur']);
        $response->assertSeeInOrder(['Kamera DSLR Canon EOS', 'Terverifikasi']);
    }

    /**
     * AdminReturnController::approve single-item 'baik' -> stok bertambah, status 'Tersedia'.
     */
    public function test_admin_approve_single_item_baik_increments_stock(): void
    {
        $this->itemA->update(['stock' => 5, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'item_id'       => $this->itemA->id,
            'quantity'      => 2,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'baik',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $fresh = $this->itemA->fresh();
        $this->assertEquals(7, $fresh->stock); // 5 + 2 = 7
        $this->assertEquals('Tersedia', $fresh->status);
        $this->assertEquals('Baik', $fresh->condition);
    }

    /**
     * AdminReturnController::approve single-item 'rusak_ringan' -> stok bertambah,
     * status 'Tersedia', dan kolom condition master diperbarui menjadi 'Rusak Ringan'.
     */
    public function test_admin_approve_single_item_rusak_ringan_increments_stock_and_updates_condition(): void
    {
        $this->itemA->update(['stock' => 1, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'item_id'       => $this->itemA->id,
            'quantity'      => 1,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'rusak_ringan',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'rusak_ringan',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $fresh = $this->itemA->fresh();
        $this->assertEquals(2, $fresh->stock); // 1 + 1 = 2
        $this->assertEquals('Tersedia', $fresh->status);
        $this->assertEquals('Rusak Ringan', $fresh->condition); // Perilaku lama: diperbarui jadi Rusak Ringan
    }

    /**
     * AdminReturnController::approve single-item 'hilang' (stok > 0) -> stok TIDAK bertambah,
     * status tetap 'Tersedia', condition tidak berubah.
     */
    public function test_admin_approve_single_item_hilang_does_not_increment_stock(): void
    {
        $this->itemA->update(['stock' => 3, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'item_id'       => $this->itemA->id,
            'quantity'      => 2,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'hilang',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'hilang',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $fresh = $this->itemA->fresh();
        $this->assertEquals(3, $fresh->stock); // Stok tidak bertambah
        $this->assertEquals('Tersedia', $fresh->status); // stock 3 > 0 -> Tersedia
        $this->assertEquals('Baik', $fresh->condition);  // Condition tidak diubah otomatis
    }

    /**
     * AdminReturnController::approve single-item 'rusak_berat' (stok > 0) -> stok TIDAK bertambah,
     * status tetap 'Tersedia', condition tidak berubah.
     */
    public function test_admin_approve_single_item_rusak_berat_does_not_increment_stock(): void
    {
        $this->itemB->update(['stock' => 4, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'item_id'       => $this->itemB->id,
            'quantity'      => 1,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'rusak_berat',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'rusak_berat',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $fresh = $this->itemB->fresh();
        $this->assertEquals(4, $fresh->stock); // Stok tidak bertambah
        $this->assertEquals('Tersedia', $fresh->status);
        $this->assertEquals('Baik', $fresh->condition);
    }

    /**
     * AdminReturnController::approve single-item 'hilang' saat stok 0 dan TIDAK ada pinjaman aktif lain
     * -> status master item menjadi 'Hilang'.
     */
    public function test_admin_approve_single_item_hilang_stock_zero_without_active_loans_sets_status_hilang(): void
    {
        $this->itemA->update(['stock' => 0, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'item_id'       => $this->itemA->id,
            'quantity'      => 1,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'hilang',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'hilang',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $fresh = $this->itemA->fresh();
        $this->assertEquals(0, $fresh->stock);
        $this->assertEquals('Hilang', $fresh->status); // Aturan baru: stok 0 tanpa pinjaman aktif -> Hilang
    }

    /**
     * AdminReturnController::approve single-item 'hilang' saat stok 0 tetapi MASIH ADA pinjaman aktif lain
     * -> status master item tetap 'Dipinjam'.
     */
    public function test_admin_approve_single_item_hilang_stock_zero_with_active_loans_sets_status_dipinjam(): void
    {
        $this->itemA->update(['stock' => 0, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        // Peminjaman lain yang masih aktif
        BorrowingRequest::create([
            'user_id'       => $this->studentB->id,
            'item_id'       => $this->itemA->id,
            'quantity'      => 1,
            'borrow_date'   => now()->subDays(1)->toDateString(),
            'return_date'   => now()->addDays(2)->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'item_id'       => $this->itemA->id,
            'quantity'      => 1,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'hilang',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'hilang',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $fresh = $this->itemA->fresh();
        $this->assertEquals(0, $fresh->stock);
        $this->assertEquals('Dipinjam', $fresh->status); // Masih ada active loans -> Dipinjam
    }

    /**
     * AdminReturnController::approve single-item 'rusak_berat' saat stok 0 dan TIDAK ada pinjaman aktif lain
     * -> status master item menjadi 'Rusak'.
     */
    public function test_admin_approve_single_item_rusak_berat_stock_zero_without_active_loans_sets_status_rusak(): void
    {
        $this->itemB->update(['stock' => 0, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'item_id'       => $this->itemB->id,
            'quantity'      => 1,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'rusak_berat',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'rusak_berat',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $fresh = $this->itemB->fresh();
        $this->assertEquals(0, $fresh->stock);
        $this->assertEquals('Rusak', $fresh->status); // Aturan baru: stok 0 tanpa pinjaman aktif -> Rusak
    }

    /**
     * Multi-item: kondisi 'baik' dan 'rusak_ringan' menambah stok tiap item secara normal.
     */
    public function test_admin_approve_multi_item_baik_and_rusak_ringan_increments_stock(): void
    {
        $this->itemA->update(['stock' => 2, 'status' => 'Dipinjam', 'condition' => 'Baik']);
        $this->itemB->update(['stock' => 3, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'quantity'      => 3,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemA->id, 'quantity' => 2]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemB->id, 'quantity' => 1]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'rusak_ringan',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'rusak_ringan',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $freshA = $this->itemA->fresh();
        $freshB = $this->itemB->fresh();
        $this->assertEquals(4, $freshA->stock); // 2 + 2 = 4
        $this->assertEquals(4, $freshB->stock); // 3 + 1 = 4
        $this->assertEquals('Tersedia', $freshA->status);
        $this->assertEquals('Tersedia', $freshB->status);
        $this->assertEquals('Rusak Ringan', $freshA->condition);
        $this->assertEquals('Rusak Ringan', $freshB->condition);
    }

    /**
     * Multi-item: kondisi 'hilang' atau 'rusak_berat' DITOLAK oleh Admin untuk penanganan manual.
     * Status return tetap 'menunggu', stok dan status item tidak berubah.
     */
    public function test_admin_approve_multi_item_hilang_or_rusak_berat_rejected_for_manual_handling(): void
    {
        $this->itemA->update(['stock' => 5, 'status' => 'Dipinjam', 'condition' => 'Baik']);
        $this->itemB->update(['stock' => 2, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'quantity'      => 2,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemA->id, 'quantity' => 1]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemB->id, 'quantity' => 1]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'hilang',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'hilang',
        ]);
        $res->assertSessionHas('error');

        // Status return tetap 'menunggu'
        $this->assertEquals(ItemReturn::STATUS_MENUNGGU, $itemReturn->fresh()->status);
        // Status borrowing tetap 'borrowed'
        $this->assertEquals(BorrowingRequest::STATUS_BORROWED, $req->fresh()->status);
        // Stok dan status item tidak berubah
        $this->assertEquals(5, $this->itemA->fresh()->stock);
        $this->assertEquals(2, $this->itemB->fresh()->stock);
    }

    /**
     * AdminReturnController::approve dua kali tidak menambah stok dua kali.
     */
    public function test_admin_approve_twice_does_not_increment_stock_twice(): void
    {
        $this->itemA->update(['stock' => 5]);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'item_id'       => $this->itemA->id,
            'quantity'      => 2,
            'borrow_date'   => now()->subDays(2)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);

        $ret = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        // First approve
        $res1 = $this->actingAs($this->admin)->post(route('admin.returns.approve', $ret->id));
        $res1->assertSessionHas('success');
        $this->assertEquals(7, $this->itemA->fresh()->stock); // 5 + 2 = 7

        // Second approve attempt on already approved return
        $res2 = $this->actingAs($this->admin)->post(route('admin.returns.approve', $ret->id));
        $res2->assertSessionHas('error');
        $this->assertEquals(7, $this->itemA->fresh()->stock); // Tetap 7, tidak menjadi 9
    }

    /**
     * KepalaJurusanController::verifyReturn single-item kondisi Baik & Rusak Ringan.
     */
    public function test_kajur_verify_single_item_baik_and_rusak_ringan(): void
    {
        $kajur = User::factory()->create(['name' => 'Kajur Single Test']);
        $kajur->assignRole('kepala_jurusan');

        $this->itemA->update(['stock' => 4, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'              => $this->teacher->id,
            'quantity'             => 1,
            'borrow_date'          => now()->subDays(3)->toDateString(),
            'return_date'          => now()->toDateString(),
            'status'               => BorrowingRequest::STATUS_BORROWED,
            'approved_by_kajur_id' => $kajur->id,
            'tipe_peminjam'        => 'guru',
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $req->id,
            'item_id'              => $this->itemA->id,
            'quantity'             => 1,
        ]);
        $ret = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->teacher->id,
            'kajur_id'             => $kajur->id,
            'tipe_peminjam'        => 'guru',
            'kondisi_barang'       => 'rusak_ringan',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $res = $this->actingAs($kajur)->post(route('kajur.verify-return', $ret->id), [
            'verified_condition' => 'Rusak Ringan',
            'verified_notes'     => 'Kabel sedikit terkelupas',
        ]);
        $res->assertSessionHas('success');

        $fresh = $this->itemA->fresh();
        $this->assertEquals(5, $fresh->stock); // 4 + 1 = 5
        $this->assertEquals('Tersedia', $fresh->status);
        $this->assertEquals('Rusak Ringan', $fresh->condition);
    }

    /**
     * KepalaJurusanController::verifyReturn single-item kondisi Hilang & Rusak Berat (stok 0 tanpa pinjaman aktif).
     */
    public function test_kajur_verify_single_item_hilang_and_rusak_berat_sets_status_correctly(): void
    {
        $kajur = User::factory()->create(['name' => 'Kajur Status Test']);
        $kajur->assignRole('kepala_jurusan');

        // 1. Kondisi Hilang dengan stok 0 -> status master menjadi 'Hilang'
        $this->itemA->update(['stock' => 0, 'status' => 'Dipinjam', 'condition' => 'Baik']);
        $reqHilang = BorrowingRequest::create([
            'user_id'              => $this->teacher->id,
            'quantity'             => 1,
            'borrow_date'          => now()->subDays(3)->toDateString(),
            'return_date'          => now()->toDateString(),
            'status'               => BorrowingRequest::STATUS_BORROWED,
            'approved_by_kajur_id' => $kajur->id,
            'tipe_peminjam'        => 'guru',
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $reqHilang->id,
            'item_id'              => $this->itemA->id,
            'quantity'             => 1,
        ]);
        $retHilang = ItemReturn::create([
            'borrowing_request_id' => $reqHilang->id,
            'user_id'              => $this->teacher->id,
            'kajur_id'             => $kajur->id,
            'tipe_peminjam'        => 'guru',
            'kondisi_barang'       => 'hilang',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $res1 = $this->actingAs($kajur)->post(route('kajur.verify-return', $retHilang->id), [
            'verified_condition' => 'Hilang',
            'verified_notes'     => 'Hilang',
        ]);
        $res1->assertSessionHas('success');

        $freshA = $this->itemA->fresh();
        $this->assertEquals(0, $freshA->stock);
        $this->assertEquals('Hilang', $freshA->status);

        // 2. Kondisi Rusak Berat dengan stok 0 -> status master menjadi 'Rusak'
        $this->itemB->update(['stock' => 0, 'status' => 'Dipinjam', 'condition' => 'Baik']);
        $reqRusak = BorrowingRequest::create([
            'user_id'              => $this->teacher->id,
            'quantity'             => 1,
            'borrow_date'          => now()->subDays(3)->toDateString(),
            'return_date'          => now()->toDateString(),
            'status'               => BorrowingRequest::STATUS_BORROWED,
            'approved_by_kajur_id' => $kajur->id,
            'tipe_peminjam'        => 'guru',
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $reqRusak->id,
            'item_id'              => $this->itemB->id,
            'quantity'             => 1,
        ]);
        $retRusak = ItemReturn::create([
            'borrowing_request_id' => $reqRusak->id,
            'user_id'              => $this->teacher->id,
            'kajur_id'             => $kajur->id,
            'tipe_peminjam'        => 'guru',
            'kondisi_barang'       => 'rusak_berat',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $res2 = $this->actingAs($kajur)->post(route('kajur.verify-return', $retRusak->id), [
            'verified_condition' => 'Rusak Berat',
            'verified_notes'     => 'Patah total',
        ]);
        $res2->assertSessionHas('success');

        $freshB = $this->itemB->fresh();
        $this->assertEquals(0, $freshB->stock);
        $this->assertEquals('Rusak', $freshB->status);
    }

    /**
     * KepalaJurusanController::verifyReturn multi-item: kondisi 'hilang' atau 'rusak_berat' DITOLAK.
     */
    public function test_kajur_verify_multi_item_hilang_or_rusak_berat_rejected_for_manual_handling(): void
    {
        $kajur = User::factory()->create(['name' => 'Kajur Multi Reject Test']);
        $kajur->assignRole('kepala_jurusan');

        $this->itemA->update(['stock' => 3, 'status' => 'Dipinjam', 'condition' => 'Baik']);
        $this->itemB->update(['stock' => 2, 'status' => 'Dipinjam', 'condition' => 'Baik']);

        $req = BorrowingRequest::create([
            'user_id'              => $this->teacher->id,
            'quantity'             => 2,
            'borrow_date'          => now()->subDays(3)->toDateString(),
            'return_date'          => now()->toDateString(),
            'status'               => BorrowingRequest::STATUS_BORROWED,
            'approved_by_kajur_id' => $kajur->id,
            'tipe_peminjam'        => 'guru',
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemA->id, 'quantity' => 1]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemB->id, 'quantity' => 1]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->teacher->id,
            'kajur_id'             => $kajur->id,
            'tipe_peminjam'        => 'guru',
            'kondisi_barang'       => 'rusak_berat',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $res = $this->actingAs($kajur)->post(route('kajur.verify-return', $itemReturn->id), [
            'verified_condition' => 'Rusak Berat',
        ]);
        $res->assertSessionHas('error');

        $this->assertEquals(ItemReturn::STATUS_MENUNGGU, $itemReturn->fresh()->status);
        $this->assertEquals(3, $this->itemA->fresh()->stock);
        $this->assertEquals(2, $this->itemB->fresh()->stock);
    }

    /**
     * KepalaJurusanController::verifyReturn proteksi verifikasi ganda.
     */
    public function test_kajur_verify_twice_does_not_increment_stock_twice(): void
    {
        $kajur = User::factory()->create(['name' => 'Kajur Double Test']);
        $kajur->assignRole('kepala_jurusan');

        $this->itemA->update(['stock' => 5]);

        $req = BorrowingRequest::create([
            'user_id'              => $this->teacher->id,
            'quantity'             => 1,
            'borrow_date'          => now()->subDays(2)->toDateString(),
            'return_date'          => now()->toDateString(),
            'status'               => BorrowingRequest::STATUS_BORROWED,
            'approved_by_kajur_id' => $kajur->id,
            'tipe_peminjam'        => 'guru',
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemA->id, 'quantity' => 1]);

        $ret = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->teacher->id,
            'kajur_id'             => $kajur->id,
            'tipe_peminjam'        => 'guru',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        // First verify
        $res1 = $this->actingAs($kajur)->post(route('kajur.verify-return', $ret->id), [
            'verified_condition' => 'Baik',
        ]);
        $res1->assertSessionHas('success');
        $this->assertEquals(6, $this->itemA->fresh()->stock); // 5 + 1 = 6

        // Second verify attempt
        $res2 = $this->actingAs($kajur)->post(route('kajur.verify-return', $ret->id), [
            'verified_condition' => 'Baik',
        ]);
        $res2->assertSessionHas('error');
        $this->assertEquals(6, $this->itemA->fresh()->stock); // Tetap 6
    }

    /**
     * Notifikasi approve admin multi-item menggunakan itemSummary -> approve berhasil, stok keduanya naik.
     */
    public function test_admin_approve_notification_uses_item_summary_for_multi_item(): void
    {
        $this->itemA->update(['stock' => 2, 'status' => 'Dipinjam', 'name' => 'Kabel LAN']);
        $this->itemB->update(['stock' => 3, 'status' => 'Dipinjam', 'name' => 'Switch D-Link']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'quantity'      => 2,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemA->id, 'quantity' => 1]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemB->id, 'quantity' => 1]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.approve', $itemReturn->id), [
            'kondisi' => 'baik',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        // Pastikan kedua item bertambah stoknya
        $this->assertEquals(3, $this->itemA->fresh()->stock); // 2 + 1 = 3
        $this->assertEquals(4, $this->itemB->fresh()->stock); // 3 + 1 = 4
    }

    /**
     * Notifikasi reject admin multi-item menggunakan itemSummary.
     */
    public function test_admin_reject_notification_uses_item_summary_for_multi_item(): void
    {
        $this->itemA->update(['stock' => 2, 'status' => 'Dipinjam', 'name' => 'Kabel LAN']);
        $this->itemB->update(['stock' => 3, 'status' => 'Dipinjam', 'name' => 'Switch D-Link']);

        $req = BorrowingRequest::create([
            'user_id'       => $this->studentA->id,
            'quantity'      => 2,
            'borrow_date'   => now()->subDays(3)->toDateString(),
            'return_date'   => now()->toDateString(),
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'siswa',
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemA->id, 'quantity' => 1]);
        BorrowingRequestItem::create(['borrowing_request_id' => $req->id, 'item_id' => $this->itemB->id, 'quantity' => 1]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $req->id,
            'user_id'              => $this->studentA->id,
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
            'tipe_peminjam'        => 'siswa',
        ]);

        $res = $this->actingAs($this->admin)->post(route('admin.returns.reject', $itemReturn->id), [
            'alasan_ditolak' => 'Barang fisik belum diterima di lab',
        ]);
        $res->assertRedirect();
        $res->assertSessionHas('success');

        $this->assertEquals(ItemReturn::STATUS_DITOLAK, $itemReturn->fresh()->status);
    }
}



