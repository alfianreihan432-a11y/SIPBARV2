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
    protected Item $itemA;
    protected Item $itemB;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kepala_jurusan', 'guard_name' => 'web']);

        $this->studentA = User::factory()->create(['name' => 'Ahmad Siswa A', 'email' => 'siswaA@test.com']);
        $this->studentA->assignRole('siswa');

        $this->studentB = User::factory()->create(['name' => 'Budi Siswa B', 'email' => 'siswaB@test.com']);
        $this->studentB->assignRole('siswa');

        $this->teacher = User::factory()->create(['name' => 'Pak Guru Test', 'email' => 'guru@test.com']);
        $this->teacher->assignRole('guru');

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
        $response->assertSee('Dikembalikan');
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
        $response->assertDontSee('Kamera DSLR Canon EOS');
    }
}
