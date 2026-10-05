<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\Jurusan;
use App\Models\QRCode;
use App\Models\User;
use App\Services\QRCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class KajurScanStudentQRTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $kajurTkj;
    private User $guruTkj;
    private User $guruRpl;
    private User $siswaTkj;
    private User $siswaRpl;
    private User $siswaNoJurusan;
    private Jurusan $jurusanTkj;
    private Jurusan $jurusanRpl;
    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'superadmin']);
        Role::firstOrCreate(['name' => 'guru']);
        Role::firstOrCreate(['name' => 'siswa']);
        Role::firstOrCreate(['name' => 'kepala_jurusan']);

        $this->jurusanTkj = Jurusan::create(['nama' => 'Teknik Komputer dan Jaringan', 'kode' => 'TKJ']);
        $this->jurusanRpl = Jurusan::create(['nama' => 'Rekayasa Perangkat Lunak', 'kode' => 'RPL']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->kajurTkj = User::factory()->create([
            'jurusan_id' => $this->jurusanTkj->id,
        ]);
        $this->kajurTkj->assignRole('kepala_jurusan');

        $this->guruTkj = User::factory()->create([
            'jurusan_id' => $this->jurusanTkj->id,
        ]);
        $this->guruTkj->assignRole('guru');

        $this->guruRpl = User::factory()->create([
            'jurusan_id' => $this->jurusanRpl->id,
        ]);
        $this->guruRpl->assignRole('guru');

        $this->siswaTkj = User::factory()->create([
            'jurusan_id' => $this->jurusanTkj->id,
        ]);
        $this->siswaTkj->assignRole('siswa');

        $this->siswaRpl = User::factory()->create([
            'jurusan_id' => $this->jurusanRpl->id,
        ]);
        $this->siswaRpl->assignRole('siswa');

        $this->siswaNoJurusan = User::factory()->create([
            'jurusan_id' => null,
        ]);
        $this->siswaNoJurusan->assignRole('siswa');

        $category = \App\Models\Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $location = \App\Models\Location::create(['building' => 'Lab', 'floor' => '1', 'room' => 'Lab 1']);

        $this->item = Item::create([
            'name' => 'Laptop Lab Test',
            'code' => 'LPT-TEST-001',
            'inventory_number' => 'INV-TEST-001',
            'category_id' => $category->id,
            'location_id' => $location->id,
            'stock' => 10,
            'status' => 'Tersedia',
            'condition' => 'Baik',
        ]);
    }

    private function createApprovedStudentBorrowing(User $student, User $teacher, int $quantity = 2): array
    {
        $borrowing = BorrowingRequest::create([
            'user_id' => $student->id,
            'teacher_id' => $teacher->id,
            'item_id' => $this->item->id,
            'quantity' => $quantity,
            'tipe_peminjam' => 'siswa',
            'status' => BorrowingRequest::STATUS_APPROVED,
            'approved_at' => now(),
            'borrow_date' => now(),
            'return_date' => now()->addDays(3),
            'purpose' => 'Praktikum Siswa',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowing->id,
            'item_id' => $this->item->id,
            'quantity' => $quantity,
        ]);

        $qrService = app(QRCodeService::class);
        $qrCode = $qrService->generateForRequest($borrowing);

        return [$borrowing, $qrCode];
    }

    public function test_kajur_can_verify_and_confirm_checkout_student_qr_same_jurusan(): void
    {
        [$borrowing, $qrCode] = $this->createApprovedStudentBorrowing($this->siswaTkj, $this->guruTkj, 2);

        $this->actingAs($this->kajurTkj);

        // 1. Verify QR page
        $verifyResponse = $this->get(route('kajur.qr.verify', ['token' => $qrCode->code]));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee($this->siswaTkj->name);
        $verifyResponse->assertSee('Konfirmasi Pengambilan Barang');

        // 2. Confirm checkout
        $checkoutResponse = $this->post(route('kajur.qr.confirm-checkout', ['id' => $borrowing->id]));
        $checkoutResponse->assertRedirect(route('kajur.qr.verify', ['token' => $qrCode->code]));

        // 3. Verify state
        $borrowing->refresh();
        $this->item->refresh();

        $this->assertEquals(BorrowingRequest::STATUS_BORROWED, $borrowing->status);
        $this->assertEquals($this->kajurTkj->id, $borrowing->checkout_by);
        $this->assertNotNull($borrowing->borrowed_at);
        $this->assertEquals(8, $this->item->stock); // 10 - 2 = 8
    }

    public function test_kajur_can_process_student_qr_from_other_jurusan(): void
    {
        [$borrowing, $qrCode] = $this->createApprovedStudentBorrowing($this->siswaRpl, $this->guruRpl, 3);

        $this->actingAs($this->kajurTkj);

        $verifyResponse = $this->get(route('kajur.qr.verify', ['token' => $qrCode->code]));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee($this->siswaRpl->name);

        $checkoutResponse = $this->post(route('kajur.qr.confirm-checkout', ['id' => $borrowing->id]));
        $checkoutResponse->assertRedirect(route('kajur.qr.verify', ['token' => $qrCode->code]));

        $borrowing->refresh();
        $this->item->refresh();

        $this->assertEquals(BorrowingRequest::STATUS_BORROWED, $borrowing->status);
        $this->assertEquals($this->kajurTkj->id, $borrowing->checkout_by);
        $this->assertEquals(7, $this->item->stock);
    }

    public function test_kajur_can_process_student_qr_without_jurusan(): void
    {
        [$borrowing, $qrCode] = $this->createApprovedStudentBorrowing($this->siswaNoJurusan, $this->guruTkj, 1);

        $this->actingAs($this->kajurTkj);

        $verifyResponse = $this->get(route('kajur.qr.verify', ['token' => $qrCode->code]));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee($this->siswaNoJurusan->name);

        $checkoutResponse = $this->post(route('kajur.qr.confirm-checkout', ['id' => $borrowing->id]));
        $checkoutResponse->assertRedirect(route('kajur.qr.verify', ['token' => $qrCode->code]));

        $borrowing->refresh();
        $this->item->refresh();

        $this->assertEquals(BorrowingRequest::STATUS_BORROWED, $borrowing->status);
        $this->assertEquals($this->kajurTkj->id, $borrowing->checkout_by);
        $this->assertEquals(9, $this->item->stock);
    }

    public function test_unapproved_or_already_borrowed_or_invalid_qr_is_rejected(): void
    {
        // 1. Pending borrowing
        $pendingBorrowing = BorrowingRequest::create([
            'user_id' => $this->siswaTkj->id,
            'teacher_id' => $this->guruTkj->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'tipe_peminjam' => 'siswa',
            'status' => BorrowingRequest::STATUS_PENDING,
            'borrow_date' => now(),
            'return_date' => now()->addDays(2),
            'purpose' => 'Pending',
        ]);

        $this->actingAs($this->kajurTkj);

        $checkoutPending = $this->post(route('kajur.qr.confirm-checkout', ['id' => $pendingBorrowing->id]));
        $checkoutPending->assertSessionHas('error');
        $this->assertNotEquals(BorrowingRequest::STATUS_BORROWED, $pendingBorrowing->fresh()->status);

        // 2. Invalid QR token
        $invalidVerify = $this->get(route('kajur.qr.verify', ['token' => 'non-existent-token']));
        $invalidVerify->assertStatus(200);
        $invalidVerify->assertSee('QR Code tidak ditemukan dalam sistem.');
    }

    public function test_teacher_and_student_cannot_access_kajur_qr_verify_or_checkout(): void
    {
        [$borrowing, $qrCode] = $this->createApprovedStudentBorrowing($this->siswaTkj, $this->guruTkj, 1);

        // Guru
        $this->actingAs($this->guruTkj);
        $this->get(route('kajur.qr.verify', ['token' => $qrCode->code]))->assertStatus(403);
        $this->post(route('kajur.qr.confirm-checkout', ['id' => $borrowing->id]))->assertStatus(403);

        // Siswa
        $this->actingAs($this->siswaTkj);
        $this->get(route('kajur.qr.verify', ['token' => $qrCode->code]))->assertStatus(403);
        $this->post(route('kajur.qr.confirm-checkout', ['id' => $borrowing->id]))->assertStatus(403);
    }

    public function test_admin_can_still_verify_and_confirm_checkout_student_qr(): void
    {
        [$borrowing, $qrCode] = $this->createApprovedStudentBorrowing($this->siswaTkj, $this->guruTkj, 1);

        $this->actingAs($this->admin);

        $verifyResponse = $this->get(route('admin.qr.verify', ['token' => $qrCode->code]));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee($this->siswaTkj->name);

        $checkoutResponse = $this->post(route('admin.qr.confirm-checkout', ['id' => $borrowing->id]));
        $checkoutResponse->assertRedirect(route('admin.qr.verify', ['token' => $qrCode->code]));

        $borrowing->refresh();
        $this->assertEquals(BorrowingRequest::STATUS_BORROWED, $borrowing->status);
        $this->assertEquals($this->admin->id, $borrowing->checkout_by);
    }

    public function test_kajur_can_still_scan_teacher_borrowing_qr_of_same_jurusan(): void
    {
        $teacherBorrowing = BorrowingRequest::create([
            'user_id' => $this->guruTkj->id,
            'approved_by_kajur_id' => $this->kajurTkj->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'tipe_peminjam' => 'guru',
            'status' => BorrowingRequest::STATUS_APPROVED,
            'approved_at' => now(),
            'borrow_date' => now(),
            'return_date' => now()->addDays(3),
            'purpose' => 'Keperluan Guru TKJ',
        ]);

        $qrCode = app(QRCodeService::class)->generateForRequest($teacherBorrowing);

        $this->actingAs($this->kajurTkj);

        $verifyResponse = $this->get(route('kajur.qr.verify', ['token' => $qrCode->code]));
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertSee($this->guruTkj->name);

        $checkoutResponse = $this->post(route('kajur.qr.confirm-checkout', ['id' => $teacherBorrowing->id]));
        $checkoutResponse->assertRedirect(route('kajur.qr.verify', ['token' => $qrCode->code]));

        $teacherBorrowing->refresh();
        $this->assertEquals(BorrowingRequest::STATUS_BORROWED, $teacherBorrowing->status);
        $this->assertEquals($this->kajurTkj->id, $teacherBorrowing->checkout_by);
    }

    public function test_kajur_accessing_admin_qr_verify_url_redirects_to_kajur_qr_verify(): void
    {
        [$borrowing, $qrCode] = $this->createApprovedStudentBorrowing($this->siswaTkj, $this->guruTkj, 1);

        $this->actingAs($this->kajurTkj);

        $response = $this->get(route('admin.qr.verify', ['token' => $qrCode->code]));
        $response->assertRedirect(route('kajur.qr.verify', ['token' => $qrCode->code]));
    }
}
