<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Jurusan;
use App\Models\LateWarningLog;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KajurWarningTest extends TestCase
{
    use RefreshDatabase;

    private Jurusan $jurusanA;
    private Jurusan $jurusanB;
    private User $kajurA;
    private User $studentA;
    private User $studentB;
    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        Role::firstOrCreate(['name' => 'kepala_jurusan', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $category = Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $location = Location::create(['building' => 'Lab', 'floor' => '1', 'room' => 'Lab 1']);

        $this->item = Item::create([
            'name' => 'Laptop Lab PPLG',
            'code' => 'LPT-001',
            'inventory_number' => 'INV-LPT-001',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'stock' => 10,
            'status' => 'Tersedia',
            'condition' => 'Baik',
        ]);

        $this->jurusanA = Jurusan::create(['nama' => 'Pengembangan Perangkat Lunak', 'kode' => 'PPLG']);
        $this->jurusanB = Jurusan::create(['nama' => 'Akuntansi', 'kode' => 'AKL']);

        $this->kajurA = User::factory()->create([
            'name' => 'Kajur PPLG',
            'email' => 'kajur.pplg@smkn1bangsri.sch.id',
            'jurusan_id' => $this->jurusanA->id,
        ]);
        $this->kajurA->assignRole('kepala_jurusan');

        $this->studentA = User::factory()->create([
            'name' => 'Siswa PPLG',
            'email' => 'siswa.pplg@smkn1bangsri.sch.id',
            'jurusan_id' => $this->jurusanA->id,
            'kelas' => 'XII PPLG 1',
            'phone' => '081234567890',
        ]);
        $this->studentA->assignRole('siswa');

        $this->studentB = User::factory()->create([
            'name' => 'Siswa AKL',
            'email' => 'siswa.akl@smkn1bangsri.sch.id',
            'jurusan_id' => $this->jurusanB->id,
            'kelas' => 'XII AKL 1',
            'phone' => '081234567891',
        ]);
        $this->studentB->assignRole('siswa');
    }

    public function test_non_kajur_roles_cannot_access_warning_page(): void
    {
        // Siswa
        $this->actingAs($this->studentA)
            ->get(route('kajur.warnings.index'))
            ->assertStatus(403);

        // Guru
        $guru = User::factory()->create(['email' => 'guru@smkn1bangsri.sch.id']);
        $guru->assignRole('guru');
        $this->actingAs($guru)
            ->get(route('kajur.warnings.index'))
            ->assertStatus(403);

        // Admin
        $admin = User::factory()->create(['email' => 'admintu@smkn1bangsri.sch.id']);
        $admin->assignRole('admin');
        $this->actingAs($admin)
            ->get(route('kajur.warnings.index'))
            ->assertStatus(403);
    }

    public function test_kajur_only_sees_overdue_loans_from_their_own_jurusan(): void
    {
        // Overdue loan in Jurusan A
        $loanA = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'Praktikum PPLG',
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_OVERDUE,
            'tipe_peminjam' => 'siswa',
        ]);

        // Overdue loan in Jurusan B
        $loanB = BorrowingRequest::create([
            'user_id' => $this->studentB->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'Tugas AKL',
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_OVERDUE,
            'tipe_peminjam' => 'siswa',
        ]);

        $response = $this->actingAs($this->kajurA)->get(route('kajur.warnings.index'));

        $response->assertStatus(200);
        $response->assertSee('Siswa PPLG');
        $response->assertDontSee('Siswa AKL');
    }

    public function test_kajur_cannot_send_warning_to_loan_from_different_jurusan(): void
    {
        $loanB = BorrowingRequest::create([
            'user_id' => $this->studentB->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'Tugas AKL',
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_OVERDUE,
            'tipe_peminjam' => 'siswa',
        ]);

        $response = $this->actingAs($this->kajurA)
            ->post(route('kajur.warnings.send', $loanB->id));

        $response->assertStatus(403);
    }

    public function test_kajur_can_send_warning_and_it_records_in_log(): void
    {
        $loanA = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'Praktikum PPLG',
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_OVERDUE,
            'tipe_peminjam' => 'siswa',
            'whatsapp_number' => '081234567890',
        ]);

        $response = $this->actingAs($this->kajurA)
            ->post(route('kajur.warnings.send', $loanA->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('late_warning_logs', [
            'borrowing_request_id' => $loanA->id,
            'sender_id' => $this->kajurA->id,
            'sender_role' => 'kepala_jurusan',
            'status' => 'sent',
        ]);
    }

    public function test_anti_spam_prevents_duplicate_warning_within_24_hours(): void
    {
        $loanA = BorrowingRequest::create([
            'user_id' => $this->studentA->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'Praktikum PPLG',
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_OVERDUE,
            'tipe_peminjam' => 'siswa',
            'whatsapp_number' => '081234567890',
        ]);

        // First warning send
        $this->actingAs($this->kajurA)
            ->post(route('kajur.warnings.send', $loanA->id));

        $this->assertEquals(1, LateWarningLog::where('borrowing_request_id', $loanA->id)->count());

        // Second warning send immediately (within 24h)
        $response = $this->actingAs($this->kajurA)
            ->post(route('kajur.warnings.send', $loanA->id));

        $response->assertSessionHas('warning');
        // Count remains 1 because second attempt is rejected by anti-spam
        $this->assertEquals(1, LateWarningLog::where('borrowing_request_id', $loanA->id)->count());
    }

    public function test_teacher_without_jurusan_directed_to_kajur_a_appears_in_kajur_a_and_not_in_kajur_b(): void
    {
        $kajurB = User::factory()->create([
            'name' => 'Kajur AKL',
            'email' => 'kajur.akl@smkn1bangsri.sch.id',
            'jurusan_id' => $this->jurusanB->id,
        ]);
        $kajurB->assignRole('kepala_jurusan');

        // Guru without jurusan_id
        $guruNullJurusan = User::factory()->create([
            'name' => 'Guru Tanpa Jurusan',
            'email' => 'guru.null@smkn1bangsri.sch.id',
            'jurusan_id' => null,
            'nip' => '198405142011011012',
        ]);
        $guruNullJurusan->assignRole('guru');

        // Overdue borrowing directed to Kajur A
        $loanTeacher = BorrowingRequest::create([
            'user_id' => $guruNullJurusan->id,
            'approved_by_kajur_id' => $this->kajurA->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'KBM Lab',
            'borrow_date' => now()->subDays(10),
            'return_date' => now()->subDays(3),
            'status' => BorrowingRequest::STATUS_OVERDUE,
            'tipe_peminjam' => 'guru',
        ]);

        // Kajur A can see this teacher loan
        $resA = $this->actingAs($this->kajurA)->get(route('kajur.warnings.index'));
        $resA->assertStatus(200);
        $resA->assertSee('Guru Tanpa Jurusan');

        // Kajur B cannot see this teacher loan
        $resB = $this->actingAs($kajurB)->get(route('kajur.warnings.index'));
        $resB->assertStatus(200);
        $resB->assertDontSee('Guru Tanpa Jurusan');

        // Kajur A can send warning
        $sendA = $this->actingAs($this->kajurA)->post(route('kajur.warnings.send', $loanTeacher->id));
        $sendA->assertRedirect();
        $sendA->assertSessionHas('success');

        // Kajur B cannot send warning (403)
        $sendB = $this->actingAs($kajurB)->post(route('kajur.warnings.send', $loanTeacher->id));
        $sendB->assertStatus(403);
    }

    public function test_unassigned_borrowing_counted_in_banner(): void
    {
        // Peminjam without jurusan and without kajur tujuan
        $userNoJurusan = User::factory()->create([
            'name' => 'User Lepas',
            'email' => 'lepas@smkn1bangsri.sch.id',
            'jurusan_id' => null,
        ]);
        $userNoJurusan->assignRole('guru');

        BorrowingRequest::create([
            'user_id' => $userNoJurusan->id,
            'approved_by_kajur_id' => null,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'Kegiatan',
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_OVERDUE,
            'tipe_peminjam' => 'guru',
        ]);

        $response = $this->actingAs($this->kajurA)->get(route('kajur.warnings.index'));
        $response->assertStatus(200);
        $response->assertSee('peminjaman terlambat tidak terhubung ke jurusan manapun, hubungi admin');
    }

    public function test_backfill_command_dry_run_and_apply_and_ambiguous(): void
    {
        $guru1 = User::factory()->create([
            'name' => 'Guru PPLG Backfill',
            'email' => 'guru.pplg.backfill@smkn1bangsri.sch.id',
            'jurusan_id' => null,
        ]);
        $guru1->assignRole('guru');

        $guru2Ambigu = User::factory()->create([
            'name' => 'Guru Ambigu Backfill',
            'email' => 'guru.ambigu@smkn1bangsri.sch.id',
            'jurusan_id' => null,
        ]);
        $guru2Ambigu->assignRole('guru');

        $kajurB = User::factory()->create([
            'name' => 'Kajur AKL 2',
            'email' => 'kajur.akl2@smkn1bangsri.sch.id',
            'jurusan_id' => $this->jurusanB->id,
        ]);
        $kajurB->assignRole('kepala_jurusan');

        // Guru 1 borrows consistently to Kajur A (PPLG)
        BorrowingRequest::create([
            'user_id' => $guru1->id,
            'approved_by_kajur_id' => $this->kajurA->id,
            'item_id' => $this->item->id,
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'guru',
        ]);

        // Guru 2 borrows to both Kajur A (PPLG) and Kajur B (AKL) -> Ambigu
        BorrowingRequest::create([
            'user_id' => $guru2Ambigu->id,
            'approved_by_kajur_id' => $this->kajurA->id,
            'item_id' => $this->item->id,
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'guru',
        ]);
        BorrowingRequest::create([
            'user_id' => $guru2Ambigu->id,
            'approved_by_kajur_id' => $kajurB->id,
            'item_id' => $this->item->id,
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDays(2),
            'status' => BorrowingRequest::STATUS_BORROWED,
            'tipe_peminjam' => 'guru',
        ]);

        // 1. Pengujian Dry Run: Database TIDAK boleh berubah
        $this->artisan('guru:backfill-jurusan')
            ->expectsOutputToContain('Mode: DRY-RUN')
            ->expectsOutputToContain('Guru PPLG Backfill')
            ->expectsOutputToContain('Ambigu (>1 Jurusan)')
            ->assertExitCode(0);

        $this->assertNull($guru1->fresh()->jurusan_id);
        $this->assertNull($guru2Ambigu->fresh()->jurusan_id);

        // 2. Apply test: guru1 gets jurusanA, guru2 remains null (ambiguous)
        $this->artisan('guru:backfill-jurusan', ['--apply' => true])
            ->expectsOutputToContain('Mode: APPLY')
            ->assertExitCode(0);

        $this->assertEquals($this->jurusanA->id, $guru1->fresh()->jurusan_id);
        $this->assertNull($guru2Ambigu->fresh()->jurusan_id);
    }
}

