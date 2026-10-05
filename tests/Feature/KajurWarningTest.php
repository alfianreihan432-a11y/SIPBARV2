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
}
