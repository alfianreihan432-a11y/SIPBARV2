<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\Jurusan;
use App\Models\User;
use App\Services\EmailNotificationService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SiswaKajurBorrowingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa']);
        Role::firstOrCreate(['name' => 'guru']);
        Role::firstOrCreate(['name' => 'kepala_jurusan']);
    }

    /**
     * Test siswa berhasil mengajukan pinjam ke Kajur yang sejurusan.
     */
    public function test_student_can_submit_cart_to_kajur_of_same_jurusan(): void
    {
        $jurusan = Jurusan::firstOrCreate(['nama' => 'Teknik Komputer dan Jaringan'], ['kode' => 'TKJ']);

        $student = User::factory()->create([
            'jurusan_id' => $jurusan->id,
            'nis'        => '12345678',
        ]);
        $student->assignRole('siswa');

        $kajur = User::factory()->create([
            'jurusan_id' => $jurusan->id,
            'nip'        => '198001012005011001',
            'phone'      => '081234567890',
            'email'      => 'kajur.tkj@example.com',
        ]);
        $kajur->assignRole('kepala_jurusan');

        $item = Item::create([
            'code'             => 'TKJ-001',
            'inventory_number' => 'INV-TKJ-001',
            'name'             => 'Router Mikrotik',
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
            'stock'            => 10,
        ]);

        $this->withSession([
            'student_borrowing_cart' => [
                $item->id => ['quantity' => 2],
            ],
        ]);

        $response = $this->actingAs($student)->post(route('student.loans.cart.submit'), [
            'target_type'     => 'kajur',
            'kajur_tujuan_id' => $kajur->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'whatsapp_number' => '081234567891',
            'purpose'         => 'Praktikum Jaringan Dasar',
            'notes'           => 'Mohon persetujuan Kajur TKJ',
        ]);

        $response->assertRedirect(route('student.loans'));

        $loan = BorrowingRequest::where('user_id', $student->id)->first();
        $this->assertNotNull($loan);
        $this->assertEquals($kajur->id, $loan->kajur_tujuan_id);
        $this->assertNull($loan->teacher_id);
        $this->assertEquals('siswa', $loan->tipe_peminjam);
        $this->assertEquals(BorrowingRequest::STATUS_PENDING, $loan->status);

        $this->assertDatabaseHas('borrowing_request_items', [
            'borrowing_request_id' => $loan->id,
            'item_id'              => $item->id,
            'quantity'             => 2,
        ]);
    }

    /**
     * (3a) Test penolakan jika guru pembimbing dan kajur_tujuan_id diisi bersamaan.
     */
    public function test_cannot_submit_with_both_teacher_and_kajur_filled(): void
    {
        $jurusan = Jurusan::firstOrCreate(['nama' => 'Teknik Komputer dan Jaringan'], ['kode' => 'TKJ']);

        $student = User::factory()->create(['jurusan_id' => $jurusan->id]);
        $student->assignRole('siswa');

        $teacher = User::factory()->create();
        $teacher->assignRole('guru');

        $kajur = User::factory()->create(['jurusan_id' => $jurusan->id]);
        $kajur->assignRole('kepala_jurusan');

        $item = Item::create([
            'code'             => 'TKJ-010',
            'inventory_number' => 'INV-TKJ-010',
            'name'             => 'Kabel UTP',
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
            'stock'            => 10,
        ]);

        $this->withSession([
            'student_borrowing_cart' => [
                $item->id => ['quantity' => 1],
            ],
        ]);

        $response = $this->actingAs($student)->post(route('student.loans.cart.submit'), [
            'teacher_id'      => $teacher->id,
            'kajur_tujuan_id' => $kajur->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'whatsapp_number' => '081234567891',
            'purpose'         => 'Uji coba dual target',
        ]);

        $response->assertSessionHasErrors('target_type');
        $this->assertDatabaseMissing('borrowing_requests', [
            'user_id' => $student->id,
        ]);
    }

    /**
     * (3b) Test kajur jurusan lain tidak bisa approve / reject via dashboard dan magic link ditolak (403).
     */
    public function test_kajur_from_different_jurusan_cannot_approve_or_reject(): void
    {
        $jurusanTKJ = Jurusan::firstOrCreate(['nama' => 'Teknik Komputer dan Jaringan'], ['kode' => 'TKJ']);
        $jurusanRPL = Jurusan::firstOrCreate(['nama' => 'Rekayasa Perangkat Lunak'], ['kode' => 'RPL']);

        $student = User::factory()->create(['jurusan_id' => $jurusanTKJ->id]);
        $student->assignRole('siswa');

        $kajurTKJ = User::factory()->create(['jurusan_id' => $jurusanTKJ->id]);
        $kajurTKJ->assignRole('kepala_jurusan');

        $kajurRPL = User::factory()->create(['jurusan_id' => $jurusanRPL->id]);
        $kajurRPL->assignRole('kepala_jurusan');

        $item = Item::create([
            'code'             => 'TKJ-011',
            'inventory_number' => 'INV-TKJ-011',
            'name'             => 'Crimping Tool',
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
            'stock'            => 5,
        ]);

        $loan = BorrowingRequest::create([
            'user_id'         => $student->id,
            'kajur_tujuan_id' => $kajurTKJ->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(1)->toDateString(),
            'return_time'     => '14:00',
            'status'          => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam'   => 'siswa',
            'purpose'         => 'Praktik crimping kabel',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $loan->id,
            'item_id'              => $item->id,
            'quantity'             => 1,
            'kondisi_saat_pinjam'  => 'baik',
        ]);

        // 1. Dashboard approve oleh kajur jurusan lain -> 404 (tidak ditemukan dalam scope kajur RPL)
        $responseDashboardApprove = $this->actingAs($kajurRPL)->post(route('kajur.approve-request', $loan->id));
        $responseDashboardApprove->assertNotFound();

        // 2. Dashboard reject oleh kajur jurusan lain -> 404
        $responseDashboardReject = $this->actingAs($kajurRPL)->post(route('kajur.reject-request', $loan->id), [
            'rejection_reason' => 'Bukan siswa kami',
        ]);
        $responseDashboardReject->assertNotFound();

        // 3. Magic link oleh kajur jurusan lain -> 403 Forbidden
        $signedUrl = URL::signedRoute('approval-guru.show', ['borrowingRequest' => $loan->id]);
        $responseMagicLink = $this->actingAs($kajurRPL)->get($signedUrl);
        $responseMagicLink->assertForbidden();
    }

    /**
     * (3c) Test jika pengiriman email melempar exception, pengajuan tetap tersimpan dan berhasil.
     */
    public function test_submission_succeeds_even_when_email_throws_exception(): void
    {
        $jurusan = Jurusan::firstOrCreate(['nama' => 'Teknik Komputer dan Jaringan'], ['kode' => 'TKJ']);

        $student = User::factory()->create(['jurusan_id' => $jurusan->id]);
        $student->assignRole('siswa');

        $kajur = User::factory()->create([
            'jurusan_id' => $jurusan->id,
            'email'      => 'kajur@example.com',
            'phone'      => '081234567890',
        ]);
        $kajur->assignRole('kepala_jurusan');

        $item = Item::create([
            'code'             => 'TKJ-012',
            'inventory_number' => 'INV-TKJ-012',
            'name'             => 'LAN Tester',
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
            'stock'            => 5,
        ]);

        $this->withSession([
            'student_borrowing_cart' => [
                $item->id => ['quantity' => 1],
            ],
        ]);

        // Mock EmailNotificationService melempar exception
        $mockEmailService = $this->createMock(EmailNotificationService::class);
        $mockEmailService->method('notifyNewRequest')->willThrowException(new \RuntimeException('SMTP Connection Error'));
        $this->app->instance(EmailNotificationService::class, $mockEmailService);

        $response = $this->actingAs($student)->post(route('student.loans.cart.submit'), [
            'target_type'     => 'kajur',
            'kajur_tujuan_id' => $kajur->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'whatsapp_number' => '081234567891',
            'purpose'         => 'Test exception email handling',
        ]);

        $response->assertRedirect(route('student.loans'));
        $this->assertDatabaseHas('borrowing_requests', [
            'user_id'         => $student->id,
            'kajur_tujuan_id' => $kajur->id,
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);
    }

    /**
     * (3d) Test siswa tanpa jurusan_id memilih kajur -> ditolak.
     */
    public function test_student_without_jurusan_cannot_submit_to_kajur(): void
    {
        $jurusan = Jurusan::firstOrCreate(['nama' => 'Teknik Komputer dan Jaringan'], ['kode' => 'TKJ']);

        // Siswa tanpa jurusan_id (NULL)
        $student = User::factory()->create(['jurusan_id' => null]);
        $student->assignRole('siswa');

        $kajur = User::factory()->create(['jurusan_id' => $jurusan->id]);
        $kajur->assignRole('kepala_jurusan');

        $item = Item::create([
            'code'             => 'TKJ-013',
            'inventory_number' => 'INV-TKJ-013',
            'name'             => 'Kabel Power',
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
            'stock'            => 5,
        ]);

        $this->withSession([
            'student_borrowing_cart' => [
                $item->id => ['quantity' => 1],
            ],
        ]);

        $response = $this->actingAs($student)->post(route('student.loans.cart.submit'), [
            'target_type'     => 'kajur',
            'kajur_tujuan_id' => $kajur->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'whatsapp_number' => '081234567891',
            'purpose'         => 'Uji siswa tanpa jurusan',
        ]);

        $response->assertSessionHasErrors('kajur_tujuan_id');
        $this->assertDatabaseMissing('borrowing_requests', [
            'user_id' => $student->id,
        ]);
    }

    /**
     * (3e) Regresi: pengajuan siswa ke guru pembimbing tetap berjalan normal.
     */
    public function test_student_can_still_submit_to_teacher(): void
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');

        $teacher = User::factory()->create(['phone' => '081122334455']);
        $teacher->assignRole('guru');

        $item = Item::create([
            'code'             => 'TKJ-014',
            'inventory_number' => 'INV-TKJ-014',
            'name'             => 'Proyektor EPSON',
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
            'stock'            => 5,
        ]);

        $this->withSession([
            'student_borrowing_cart' => [
                $item->id => ['quantity' => 1],
            ],
        ]);

        $response = $this->actingAs($student)->post(route('student.loans.cart.submit'), [
            'target_type'     => 'guru',
            'teacher_id'      => $teacher->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(1)->toDateString(),
            'return_time'     => '14:00',
            'whatsapp_number' => '081234567891',
            'purpose'         => 'Presentasi di kelas',
        ]);

        $response->assertRedirect(route('student.loans'));

        $loan = BorrowingRequest::where('user_id', $student->id)->first();
        $this->assertNotNull($loan);
        $this->assertEquals($teacher->id, $loan->teacher_id);
        $this->assertNull($loan->kajur_tujuan_id);
        $this->assertEquals(BorrowingRequest::STATUS_PENDING, $loan->status);
    }

    /**
     * (3f) Test link WA di halaman loans siswa: jika ke kajur, mengarah ke nomor kajur_tujuan.
     * Jika kajur belum punya nomor WA, tidak crash / error.
     */
    public function test_wa_button_in_student_loans_targets_kajur_or_handles_empty_phone(): void
    {
        $jurusan = Jurusan::firstOrCreate(['nama' => 'Teknik Komputer dan Jaringan'], ['kode' => 'TKJ']);

        $student = User::factory()->create(['jurusan_id' => $jurusan->id]);
        $student->assignRole('siswa');

        $kajurWithPhone = User::factory()->create([
            'jurusan_id' => $jurusan->id,
            'phone'      => '081234567890',
        ]);
        $kajurWithPhone->assignRole('kepala_jurusan');

        $kajurNoPhone = User::factory()->create([
            'jurusan_id' => $jurusan->id,
            'phone'      => null,
        ]);
        $kajurNoPhone->assignRole('kepala_jurusan');

        $item = Item::create([
            'code'             => 'TKJ-015',
            'inventory_number' => 'INV-TKJ-015',
            'name'             => 'Kamera DSLR',
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
            'stock'            => 5,
        ]);

        // 1. Peminjaman ke kajur dengan nomor telepon
        $loan1 = BorrowingRequest::create([
            'user_id'         => $student->id,
            'kajur_tujuan_id' => $kajurWithPhone->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(1)->toDateString(),
            'return_time'     => '14:00',
            'status'          => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam'   => 'siswa',
            'purpose'         => 'Dokumentasi',
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $loan1->id,
            'item_id'              => $item->id,
            'quantity'             => 1,
            'kondisi_saat_pinjam'  => 'baik',
        ]);

        // 2. Peminjaman ke kajur tanpa nomor telepon
        $loan2 = BorrowingRequest::create([
            'user_id'         => $student->id,
            'kajur_tujuan_id' => $kajurNoPhone->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(1)->toDateString(),
            'return_time'     => '14:00',
            'status'          => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam'   => 'siswa',
            'purpose'         => 'Dokumentasi 2',
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $loan2->id,
            'item_id'              => $item->id,
            'quantity'             => 1,
            'kondisi_saat_pinjam'  => 'baik',
        ]);

        $response = $this->actingAs($student)->get(route('student.loans'));
        $response->assertOk();
        // Memastikan link WA mengarah ke nomor normalisasi kajur (6281234567890)
        $response->assertSee('phone=6281234567890');
        $response->assertSee('WA Kajur');
    }

    /**
     * (3g) Pesan WA untuk kajur berisi nama siswa, kelas, daftar barang, dan magic link.
     */
    public function test_wa_message_content_for_kajur(): void
    {
        $jurusan = Jurusan::firstOrCreate(['nama' => 'Teknik Komputer dan Jaringan'], ['kode' => 'TKJ']);

        $student = User::factory()->create([
            'jurusan_id' => $jurusan->id,
            'name'       => 'Budi Santoso',
            'kelas'      => 'XII TKJ 1',
        ]);
        $student->assignRole('siswa');

        $kajur = User::factory()->create([
            'jurusan_id' => $jurusan->id,
            'phone'      => '081234567890',
        ]);
        $kajur->assignRole('kepala_jurusan');

        $item = Item::create([
            'code'             => 'TKJ-016',
            'inventory_number' => 'INV-TKJ-016',
            'name'             => 'Server Rak 1U',
            'condition'        => 'Baik',
            'status'           => 'Tersedia',
            'stock'            => 2,
        ]);

        $loan = BorrowingRequest::create([
            'user_id'         => $student->id,
            'kajur_tujuan_id' => $kajur->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(1)->toDateString(),
            'return_time'     => '14:00',
            'status'          => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam'   => 'siswa',
            'purpose'         => 'Praktik instalasi server',
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $loan->id,
            'item_id'              => $item->id,
            'quantity'             => 1,
            'kondisi_saat_pinjam'  => 'baik',
        ]);

        $waService = app(WhatsAppNotificationService::class);
        $waLink = $waService->buildKajurNewRequestWaLink($loan, '081234567890');

        $this->assertStringContainsString('6281234567890', $waLink);
        $this->assertStringContainsString(urlencode('Budi Santoso'), $waLink);
        $this->assertStringContainsString(urlencode('XII TKJ 1'), $waLink);
        $this->assertStringContainsString(urlencode('Server Rak 1U'), $waLink);
        $this->assertStringContainsString(urlencode('approval-guru'), $waLink);
    }

    /**
     * (5) Test normalisasi format nomor WA: 08..., +62..., dan 62...
     */
    public function test_phone_number_normalization(): void
    {
        $waService = app(WhatsAppNotificationService::class);

        $this->assertEquals('6281234567890', $waService->normalizePhone('081234567890'));
        $this->assertEquals('6281234567890', $waService->normalizePhone('+6281234567890'));
        $this->assertEquals('6281234567890', $waService->normalizePhone('6281234567890'));
        $this->assertEquals('6281234567890', $waService->normalizePhone('+62 812-3456-7890'));
    }
}
