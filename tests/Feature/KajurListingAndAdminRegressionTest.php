<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\Jurusan;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Poin 3: Daftar pengajuan dan riwayat kajur untuk siswa
 *   - kajur lender melihat peminjaman siswa-nya di pendingApprovals dan history
 *   - kajur lain tidak melihatnya
 *   - borrowing admin path tidak tampil di daftar kajur
 *
 * Poin 4: Regresi jalur admin
 *   - pengajuan siswa jalur admin tersimpan dengan lender_type=admin, lender_kajur_id=null
 *   - magic link guru (teacher_id) tetap bekerja untuk borrowing lender admin
 */
class KajurListingAndAdminRegressionTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $kajurA;
    private User $kajurB;
    private User $admin;
    private User $guru;
    private Item $item;
    private Jurusan $jurusan;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['siswa', 'kepala_jurusan', 'admin', 'guru', 'superadmin'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->jurusan = Jurusan::create(['nama' => 'Teknik Komputer', 'kode' => 'TK']);

        $this->student = User::factory()->create(['jurusan_id' => $this->jurusan->id]);
        $this->student->assignRole('siswa');

        $this->kajurA = User::factory()->create([
            'jurusan_id' => $this->jurusan->id,
            'phone'      => '081234567890',
        ]);
        $this->kajurA->assignRole('kepala_jurusan');

        $this->kajurB = User::factory()->create([
            'jurusan_id' => $this->jurusan->id,
            'phone'      => '081234567891',
        ]);
        $this->kajurB->assignRole('kepala_jurusan');

        $this->guru = User::factory()->create(['jurusan_id' => $this->jurusan->id]);
        $this->guru->assignRole('guru');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $category = \App\Models\Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $this->item = Item::create([
            'name'             => 'Laptop Listing Test',
            'code'             => 'LIST-001',
            'inventory_number' => 'INV-LIST-001',
            'category_id'      => $category->id,
            'stock'            => 10,
            'status'           => 'Tersedia',
            'condition'        => 'Baik',
        ]);
    }

    private function makeStudentKajurBorrowing(
        string $status = BorrowingRequest::STATUS_PENDING
    ): BorrowingRequest {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(3)->toDateString(),
            'purpose'         => 'Test purpose',
            'status'          => $status,
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowing->id,
            'item_id'              => $this->item->id,
            'quantity'             => 1,
        ]);
        return $borrowing;
    }

    // =====================================================================
    // POIN 3A: kajur lender melihat peminjaman siswa di pendingApprovals
    // =====================================================================
    public function test_kajur_lender_sees_student_borrowing_in_pending_approvals(): void
    {
        $borrowing = $this->makeStudentKajurBorrowing(BorrowingRequest::STATUS_PENDING);

        $response = $this->actingAs($this->kajurA)
            ->get(route('kajur.pending-approvals'));

        $response->assertOk()
            ->assertViewHas('pendingRequests', function ($list) use ($borrowing) {
                return $list->contains('id', $borrowing->id);
            });
    }

    // =====================================================================
    // POIN 3B: kajur lain TIDAK melihat peminjaman siswa itu
    // =====================================================================
    public function test_kajur_other_does_not_see_student_borrowing_in_pending_approvals(): void
    {
        $borrowing = $this->makeStudentKajurBorrowing(BorrowingRequest::STATUS_PENDING);

        $response = $this->actingAs($this->kajurB)
            ->get(route('kajur.pending-approvals'));

        $response->assertOk()
            ->assertViewHas('pendingRequests', function ($list) use ($borrowing) {
                return ! $list->contains('id', $borrowing->id);
            });
    }

    // =====================================================================
    // POIN 3C: kajur lender melihat peminjaman siswa di history
    // =====================================================================
    public function test_kajur_lender_sees_student_borrowing_in_history(): void
    {
        $borrowing = $this->makeStudentKajurBorrowing(BorrowingRequest::STATUS_APPROVED);

        $response = $this->actingAs($this->kajurA)
            ->get(route('kajur.history'));

        $response->assertOk()
            ->assertViewHas('history', function ($list) use ($borrowing) {
                return $list->contains('id', $borrowing->id);
            });
    }

    // =====================================================================
    // POIN 3D: kajur lain TIDAK melihat peminjaman siswa itu di history
    // =====================================================================
    public function test_kajur_other_does_not_see_student_borrowing_in_history(): void
    {
        $borrowing = $this->makeStudentKajurBorrowing(BorrowingRequest::STATUS_APPROVED);

        $response = $this->actingAs($this->kajurB)
            ->get(route('kajur.history'));

        $response->assertOk()
            ->assertViewHas('history', function ($list) use ($borrowing) {
                return ! $list->contains('id', $borrowing->id);
            });
    }

    public function test_kajur_history_view_displays_multi_item_student_loan_with_nis_and_guru_loan_with_nip_and_hides_from_other_kajur(): void
    {
        $category = \App\Models\Category::first();
        $item2 = Item::create([
            'name'             => 'Proyektor Epson',
            'code'             => 'PROY-001',
            'inventory_number' => 'INV-PROY-001',
            'category_id'      => $category->id,
            'stock'            => 5,
            'status'           => 'Tersedia',
            'condition'        => 'Baik',
        ]);

        $studentWithNis = User::factory()->create([
            'jurusan_id' => $this->jurusan->id,
            'name'       => 'Siswa Multi Budi',
            'nis'        => '12345678',
            'kelas'      => 'XII TK 1',
        ]);
        $studentWithNis->assignRole('siswa');

        $teacherWithNip = User::factory()->create([
            'jurusan_id' => $this->jurusan->id,
            'name'       => 'Pak Guru Joko',
            'nip'        => '198501012010011001',
        ]);
        $teacherWithNip->assignRole('guru');

        // Multi-item borrowing for student directed to kajurA
        $studentLoan = BorrowingRequest::create([
            'user_id'         => $studentWithNis->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'purpose'         => 'Praktikum TK Multi Item',
            'status'          => BorrowingRequest::STATUS_BORROWED,
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $studentLoan->id,
            'item_id'              => $this->item->id,
            'quantity'             => 2,
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $studentLoan->id,
            'item_id'              => $item2->id,
            'quantity'             => 1,
        ]);

        // Guru borrowing in kajurA scope
        $guruLoan = BorrowingRequest::create([
            'user_id'       => $teacherWithNip->id,
            'tipe_peminjam' => 'guru',
            'item_id'       => $this->item->id,
            'quantity'      => 1,
            'borrow_date'   => now()->toDateString(),
            'return_date'   => now()->addDays(1)->toDateString(),
            'purpose'       => 'Mengajar di kelas',
            'status'        => BorrowingRequest::STATUS_BORROWED,
            'teacher_id'    => $this->kajurA->id,
        ]);

        // 1. Kajur lender (kajurA) visits history
        $responseA = $this->actingAs($this->kajurA)->get(route('kajur.history'));
        $responseA->assertOk();

        // Check student row & modal payload
        $responseA->assertSee('Siswa Multi Budi');
        $responseA->assertSee('NIS: 12345678');
        $responseA->assertSee('Laptop Listing Test');
        $responseA->assertSee('Proyektor Epson');

        // Check strict HTML-escaped modal payload in onclick attribute
        $responseA->assertSee('&quot;borrower_id&quot;:&quot;12345678&quot;', false);
        $responseA->assertSee('&quot;tipe_peminjam&quot;:&quot;siswa&quot;', false);
        $responseA->assertSee('&quot;name&quot;:&quot;Laptop Listing Test&quot;', false);
        $responseA->assertSee('&quot;name&quot;:&quot;Proyektor Epson&quot;', false);

        // Check teacher row & modal payload
        $responseA->assertSee('Pak Guru Joko');
        $responseA->assertSee('NIP: 198501012010011001');

        // Check strict HTML-escaped modal payload for teacher (borrower_id = NIP)
        $responseA->assertSee('&quot;borrower_id&quot;:&quot;198501012010011001&quot;', false);
        $responseA->assertSee('&quot;tipe_peminjam&quot;:&quot;guru&quot;', false);

        // 2. Kajur lain (kajurB) does NOT see student loan
        $responseB = $this->actingAs($this->kajurB)->get(route('kajur.history'));
        $responseB->assertOk();
        $responseB->assertDontSee('Siswa Multi Budi');
        $responseB->assertDontSee('12345678');
        $responseB->assertDontSee('Praktikum TK Multi Item');
    }

    // =====================================================================
    // POIN 3E: borrowing guru masih muncul di kajurA (scope jurusan sama)
    // =====================================================================
    public function test_guru_borrowing_still_appears_for_kajur_same_jurusan(): void
    {
        $guruBorrowing = BorrowingRequest::create([
            'user_id'       => $this->guru->id,
            'tipe_peminjam' => 'guru',
            'borrow_date'   => now()->toDateString(),
            'return_date'   => now()->addDays(3)->toDateString(),
            'purpose'       => 'Guru purpose',
            'status'        => BorrowingRequest::STATUS_PENDING,
            'teacher_id'    => $this->kajurA->id,
            'item_id'       => $this->item->id,
            'quantity'      => 1,
        ]);

        $response = $this->actingAs($this->kajurA)
            ->get(route('kajur.pending-approvals'));

        $response->assertOk()
            ->assertViewHas('pendingRequests', function ($list) use ($guruBorrowing) {
                return $list->contains('id', $guruBorrowing->id);
            });
    }

    // =====================================================================
    // POIN 3F: admin path student borrowing tidak muncul di daftar kajurA
    // =====================================================================
    public function test_admin_path_student_borrowing_not_visible_in_kajur_pending(): void
    {
        $adminBorrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'lender_kajur_id' => null,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(3)->toDateString(),
            'purpose'         => 'Admin path',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($this->kajurA)
            ->get(route('kajur.pending-approvals'));

        $response->assertOk()
            ->assertViewHas('pendingRequests', function ($list) use ($adminBorrowing) {
                return ! $list->contains('id', $adminBorrowing->id);
            });
    }

    // =====================================================================
    // POIN 4A: pengajuan siswa jalur admin tersimpan lender_type=admin, lender_kajur_id=null
    // =====================================================================
    public function test_student_admin_borrowing_stored_with_lender_type_admin_and_null_kajur(): void
    {
        $existing = BorrowingRequest::where('user_id', $this->student->id)->first();
        $this->assertNull($existing, 'DB harus bersih sebelum test ini');

        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'lender_kajur_id' => null,
            'teacher_id'      => $this->guru->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(3)->toDateString(),
            'purpose'         => 'Test admin path direct',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $this->assertDatabaseHas('borrowing_requests', [
            'id'              => $borrowing->id,
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'lender_kajur_id' => null,
        ]);
    }

    // =====================================================================
    // POIN 4B: magic link guru tetap bekerja untuk borrowing lender_type=admin
    // =====================================================================
    public function test_magic_link_teacher_works_for_admin_path_borrowing(): void
    {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'lender_kajur_id' => null,
            'teacher_id'      => $this->guru->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(3)->toDateString(),
            'purpose'         => 'Test magic link admin path',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $url = URL::signedRoute('approval.show', ['borrowingRequest' => $borrowing->id]);

        $this->actingAs($this->guru)
            ->get($url)
            ->assertOk();
    }

    // =====================================================================
    // POIN 5A: AdminReturnController menampilkan return admin & sembunyikan kajur
    // =====================================================================
    public function test_admin_return_list_shows_admin_return_and_hides_kajur_return(): void
    {
        // 1. Borrowing siswa jalur admin
        $adminBorrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'lender_kajur_id' => null,
            'item_id'         => $this->item->id,
            'borrow_date'     => now()->subDays(2)->toDateString(),
            'return_date'     => now()->toDateString(),
            'purpose'         => 'Admin return test',
            'status'          => BorrowingRequest::STATUS_BORROWED,
        ]);
        $adminReturn = \App\Models\ItemReturn::create([
            'borrowing_request_id' => $adminBorrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'catatan'              => 'Pengembalian admin unik 987654',
            'status'               => \App\Models\ItemReturn::STATUS_MENUNGGU,
        ]);

        // 2. Borrowing siswa jalur kajur
        $kajurBorrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id'         => $this->item->id,
            'borrow_date'     => now()->subDays(2)->toDateString(),
            'return_date'     => now()->toDateString(),
            'purpose'         => 'Kajur return test',
            'status'          => BorrowingRequest::STATUS_BORROWED,
        ]);
        $kajurReturn = \App\Models\ItemReturn::create([
            'borrowing_request_id' => $kajurBorrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'catatan'              => 'Pengembalian kajur unik 123456',
            'status'               => \App\Models\ItemReturn::STATUS_MENUNGGU,
        ]);

        // Admin mengakses daftar return
        $response = $this->actingAs($this->admin)->get(route('returns.index'));
        $response->assertOk();

        // Harus melihat pengembalian jalur admin
        $response->assertSee('Pengembalian admin unik 987654');

        // TIDAK boleh melihat pengembalian jalur kajur
        $response->assertDontSee('Pengembalian kajur unik 123456');
    }

    // =====================================================================
    // POIN 5B: Edit pengajuan pending (loans-edit): ganti dari admin ke kajur
    // =====================================================================
    public function test_student_can_edit_pending_borrowing_from_admin_to_kajur(): void
    {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'lender_kajur_id' => null,
            'teacher_id'      => $this->guru->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->addDays(2)->toDateString(),
            'return_date'     => now()->addDays(5)->toDateString(),
            'return_time'     => '14:00',
            'purpose'         => 'Original admin purpose',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->student);

        // Edit tujuan ke Kajur
        $response = $this->put(route('student.loans.update', $borrowing->id), [
            'purpose'         => 'Updated purpose for kajur',
            'borrow_date'     => now()->addDays(2)->toDateString(),
            'return_date'     => now()->addDays(5)->toDateString(),
            'return_time'     => '15:00',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'quantity'        => 1,
        ]);

        $response->assertRedirect(route('student.loans'));
        $borrowing->refresh();

        $this->assertEquals('kajur', $borrowing->lender_type);
        $this->assertEquals($this->kajurA->id, $borrowing->lender_kajur_id);
        $this->assertNull($borrowing->teacher_id);
        $this->assertEquals('Updated purpose for kajur', $borrowing->purpose);
    }

    // =====================================================================
    // POIN 5C: Edit pengajuan pending (loans-edit): ganti dari kajur ke admin
    // =====================================================================
    public function test_student_can_edit_pending_borrowing_from_kajur_to_admin(): void
    {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->addDays(2)->toDateString(),
            'return_date'     => now()->addDays(5)->toDateString(),
            'return_time'     => '14:00',
            'purpose'         => 'Original kajur purpose',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->student);

        // Edit tujuan ke Admin (wajib menyertakan teacher_id)
        $response = $this->put(route('student.loans.update', $borrowing->id), [
            'purpose'         => 'Updated purpose for admin',
            'borrow_date'     => now()->addDays(2)->toDateString(),
            'return_date'     => now()->addDays(5)->toDateString(),
            'return_time'     => '15:00',
            'lender_type'     => 'admin',
            'teacher_id'      => $this->guru->id,
            'quantity'        => 1,
        ]);

        $response->assertRedirect(route('student.loans'));
        $borrowing->refresh();

        $this->assertEquals('admin', $borrowing->lender_type);
        $this->assertNull($borrowing->lender_kajur_id);
        $this->assertEquals($this->guru->id, $borrowing->teacher_id);
        $this->assertEquals('Updated purpose for admin', $borrowing->purpose);
    }

    // =====================================================================
    // POIN 5D: Edit ke kajur gagal jika kajur tidak memiliki nomor HP
    // =====================================================================
    public function test_student_edit_to_kajur_fails_when_kajur_has_no_phone(): void
    {
        $kajurNoPhone = User::factory()->create([
            'jurusan_id' => $this->jurusan->id,
            'phone'      => null,
        ]);
        $kajurNoPhone->assignRole('kepala_jurusan');

        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'lender_kajur_id' => null,
            'teacher_id'      => $this->guru->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->addDays(2)->toDateString(),
            'return_date'     => now()->addDays(5)->toDateString(),
            'return_time'     => '14:00',
            'purpose'         => 'Original purpose',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->student);

        $response = $this->from(route('student.loans.edit', $borrowing->id))
            ->put(route('student.loans.update', $borrowing->id), [
                'purpose'         => 'Edit without valid phone',
                'borrow_date'     => now()->addDays(2)->toDateString(),
                'return_date'     => now()->addDays(5)->toDateString(),
                'return_time'     => '15:00',
                'lender_type'     => 'kajur',
                'lender_kajur_id' => $kajurNoPhone->id,
                'quantity'        => 1,
            ]);

        $response->assertRedirect(route('student.loans.edit', $borrowing->id));
        $response->assertSessionHasErrors('lender_kajur_id');

        $borrowing->refresh();
        $this->assertEquals('admin', $borrowing->lender_type);
    }
}
