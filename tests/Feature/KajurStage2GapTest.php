<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\Jurusan;
use App\Models\QRCode;
use App\Models\User;
use App\Services\QRCodeService;
use App\Services\WhatsAppNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Langkah B — Celah yang belum dicakup StudentKajurLendingFlowTest:
 *  1. WA link (getApprovalUrlForStudentKajur / getDirectWaLinkForStudentKajur)
 *  2. Blade render cart dengan kajur tanpa phone (disabled)
 *  3. AdminQRVerificationController guard (GET verify + POST checkout + POST rejectCheckout)
 *  4. Kajur return: list scope, detail (showReturn), approve (verifyReturn), reject (rejectReturn)
 *  5. Magic link tiap kasus penolakan individual
 */
class KajurStage2GapTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $kajurA;  // lender, punya phone
    private User $kajurB;  // bukan lender, punya phone
    private User $kajurNoPhone; // kajur tanpa phone
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
            'phone' => '081234567890',
        ]);
        $this->kajurA->assignRole('kepala_jurusan');

        $this->kajurB = User::factory()->create([
            'jurusan_id' => $this->jurusan->id,
            'phone' => '081234567891',
        ]);
        $this->kajurB->assignRole('kepala_jurusan');

        $this->kajurNoPhone = User::factory()->create([
            'jurusan_id' => $this->jurusan->id,
            'phone' => null,
        ]);
        $this->kajurNoPhone->assignRole('kepala_jurusan');

        $this->guru = User::factory()->create(['jurusan_id' => $this->jurusan->id]);
        $this->guru->assignRole('guru');

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $category = \App\Models\Category::create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $this->item = Item::create([
            'name'             => 'Laptop Gap Test',
            'code'             => 'GAP-001',
            'inventory_number' => 'INV-GAP-001',
            'category_id'      => $category->id,
            'stock'            => 10,
            'status'           => 'Tersedia',
            'condition'        => 'Baik',
        ]);
    }

    // =========================================================
    // SECTION 1: WA LINK (getApprovalUrlForStudentKajur / getDirectWaLinkForStudentKajur)
    // =========================================================

    public function test_get_approval_url_for_student_kajur_returns_signed_route(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $borrowing->load(['lenderKajur', 'items.item']);

        /** @var WhatsAppNotificationService $wa */
        $wa = app(WhatsAppNotificationService::class);
        $url = $wa->getApprovalUrlForStudentKajur($borrowing);

        $this->assertStringContainsString('/approval-siswa-kajur/', $url);
        $this->assertStringContainsString('signature=', $url);
        $this->assertStringContainsString((string) $borrowing->id, $url);
    }

    public function test_get_direct_wa_link_for_student_kajur_uses_kajur_phone_and_signed_url(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $borrowing->load(['user', 'lenderKajur', 'items.item', 'items.itemWithTrashed']);

        /** @var WhatsAppNotificationService $wa */
        $wa = app(WhatsAppNotificationService::class);
        $link = $wa->getDirectWaLinkForStudentKajur($borrowing);

        // Must be a WA link
        $this->assertStringStartsWith('https://api.whatsapp.com/send?phone=', $link);
        // Must contain phone number (normalized)
        $this->assertStringContainsString('62', $link);
        // Must embed the approval URL (signed)
        $this->assertStringContainsString('approval-siswa-kajur', urldecode($link));
    }

    public function test_get_direct_wa_link_returns_empty_when_kajur_has_no_phone(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurNoPhone);
        $borrowing->load(['user', 'lenderKajur', 'items.item', 'items.itemWithTrashed']);

        /** @var WhatsAppNotificationService $wa */
        $wa = app(WhatsAppNotificationService::class);
        $link = $wa->getDirectWaLinkForStudentKajur($borrowing);

        $this->assertSame('', $link);
    }

    // =========================================================
    // SECTION 2: BLADE RENDER — cart halaman dengan kajur tanpa phone
    // =========================================================

    public function test_cart_page_renders_with_kajur_options_disabled_for_no_phone(): void
    {
        // Seeding cart session
        session(['student_borrowing_cart' => [$this->item->id => ['quantity' => 1, 'purpose' => 'Uji']]]);

        $response = $this->actingAs($this->student)->get(route('student.loans.cart'));

        $response->assertStatus(200);
        // Kajur dengan phone harus muncul (tidak disabled)
        $response->assertSee($this->kajurA->name);
        // Kajur tanpa phone harus muncul dengan keterangan
        $response->assertSee('nomor WhatsApp belum tersedia');
    }

    // =========================================================
    // SECTION 3: AdminQRVerificationController guard
    // =========================================================

    public function test_admin_qr_verify_rejects_kajur_qr_with_specific_message(): void
    {
        $borrowing = $this->createApprovedKajurBorrowing();
        $qrCode = $borrowing->qrCode;

        $this->actingAs($this->admin);
        $response = $this->get(route('admin.qr.verify', ['token' => $qrCode->code]));

        $response->assertStatus(200);
        $response->assertSee('QR Kajur hanya dapat diproses oleh Kajur pemberi pinjaman.');
    }

    public function test_admin_qr_checkout_rejects_kajur_qr_with_specific_message(): void
    {
        $borrowing = $this->createApprovedKajurBorrowing();

        $this->actingAs($this->admin);
        $response = $this->post(route('admin.qr.confirm-checkout', ['id' => $borrowing->id]));

        $response->assertSessionHas('error', 'QR Kajur hanya dapat diproses oleh Kajur pemberi pinjaman.');
        $this->assertSame(BorrowingRequest::STATUS_APPROVED, $borrowing->fresh()->status);
    }

    public function test_admin_can_still_process_admin_qr_after_kajur_guard(): void
    {
        $borrowing = $this->createApprovedAdminBorrowing();
        $qrCode = $borrowing->qrCode;

        $this->actingAs($this->admin);
        $verify = $this->get(route('admin.qr.verify', ['token' => $qrCode->code]));
        $verify->assertStatus(200);
        $verify->assertSee($this->student->name);

        $checkout = $this->post(route('admin.qr.confirm-checkout', ['id' => $borrowing->id]));
        $checkout->assertRedirect(route('admin.qr.verify', ['token' => $qrCode->code]));
        $this->assertSame(BorrowingRequest::STATUS_BORROWED, $borrowing->fresh()->status);
    }

    public function test_admin_reject_checkout_rejects_kajur_qr(): void
    {
        $borrowing = $this->createApprovedKajurBorrowing();

        $this->actingAs($this->admin);
        $response = $this->post(route('admin.qr.reject-checkout', ['id' => $borrowing->id]), [
            'rejection_reason' => 'Test penolakan kajur QR oleh admin',
        ]);

        $response->assertSessionHas('error', 'QR Kajur hanya dapat diproses oleh Kajur pemberi pinjaman.');
        $this->assertSame(BorrowingRequest::STATUS_APPROVED, $borrowing->fresh()->status);
    }

    // =========================================================
    // SECTION 4: Kajur return — list, detail, approve, reject
    // =========================================================

    public function test_kajur_return_list_shows_own_student_return_not_others(): void
    {
        $ownReturn   = $this->createPendingKajurReturn($this->kajurA);
        $otherReturn = $this->createPendingKajurReturn($this->kajurB);

        $this->actingAs($this->kajurA);
        $response = $this->get(route('kajur.pending-returns'));
        $response->assertOk();
        $response->assertSee('Return-' . $ownReturn->id);
        $response->assertDontSee('Return-' . $otherReturn->id);
    }

    public function test_kajur_return_show_allows_own_and_forbids_other(): void
    {
        $ownReturn   = $this->createPendingKajurReturn($this->kajurA);
        $otherReturn = $this->createPendingKajurReturn($this->kajurB);

        $this->actingAs($this->kajurA);
        $this->get(route('kajur.pending-returns.show', $ownReturn->id))->assertOk();
        $this->get(route('kajur.pending-returns.show', $otherReturn->id))->assertForbidden();
    }

    public function test_kajur_return_approve_success_restores_stock_and_sets_returned(): void
    {
        $borrowing = $this->createApprovedKajurBorrowingCheckout();
        $this->item->update(['stock' => 8]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->kajurA);
        $response = $this->post(route('kajur.verify-return', $itemReturn->id), [
            'verified_condition' => 'Baik',
        ]);

        $response->assertSessionHas('success');
        $this->assertSame(10, $this->item->fresh()->stock);
        $this->assertSame(BorrowingRequest::STATUS_RETURNED, $borrowing->fresh()->status);
    }

    public function test_kajur_return_approve_forbidden_for_other_kajur(): void
    {
        $borrowing = $this->createApprovedKajurBorrowingCheckout();
        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->kajurB);
        $response = $this->post(route('kajur.verify-return', $itemReturn->id), [
            'verified_condition' => 'Baik',
        ]);
        $response->assertForbidden();
        $this->assertSame(ItemReturn::STATUS_MENUNGGU, $itemReturn->fresh()->status);
    }

    public function test_kajur_return_reject_sets_status_ditolak_with_reason(): void
    {
        $borrowing = $this->createApprovedKajurBorrowingCheckout();
        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'rusak_ringan',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->kajurA);
        $response = $this->post(route('kajur.pending-returns.reject', $itemReturn->id), [
            'alasan_ditolak' => 'Kondisi barang tidak sesuai',
        ]);

        $response->assertSessionHas('success');
        $fresh = $itemReturn->fresh();
        $this->assertSame(ItemReturn::STATUS_DITOLAK, $fresh->status);
        $this->assertSame('Kondisi barang tidak sesuai', $fresh->catatan);
        $this->assertSame($this->kajurA->id, $fresh->diverifikasi_oleh);
    }

    public function test_kajur_return_reject_forbidden_for_other_kajur(): void
    {
        $borrowing = $this->createApprovedKajurBorrowingCheckout();
        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'rusak_ringan',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->kajurB);
        $response = $this->post(route('kajur.pending-returns.reject', $itemReturn->id), [
            'alasan_ditolak' => 'Tidak sesuai kondisi barang',
        ]);
        $response->assertForbidden();
        $this->assertSame(ItemReturn::STATUS_MENUNGGU, $itemReturn->fresh()->status);
    }

    public function test_admin_return_show_forbidden_for_kajur_return(): void
    {
        $borrowing = $this->createApprovedKajurBorrowingCheckout();
        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin);
        $this->get(route('admin.returns.show', $itemReturn->id))->assertForbidden();
        $this->getJson(route('admin.returns.show', $itemReturn->id))->assertForbidden();
    }

    public function test_admin_return_show_returns_404_for_non_json_request(): void
    {
        $adminBorrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'item_id'         => $this->item->id,
            'borrow_date'     => now()->subDays(2)->toDateString(),
            'return_date'     => now()->toDateString(),
            'purpose'         => 'Admin loan',
            'status'          => BorrowingRequest::STATUS_BORROWED,
        ]);
        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $adminBorrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin);
        $response = $this->get(route('admin.returns.show', $itemReturn->id));
        $response->assertNotFound();
    }

    public function test_admin_return_show_returns_data_for_json_request_admin_loan(): void
    {
        $adminBorrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'admin',
            'item_id'         => $this->item->id,
            'borrow_date'     => now()->subDays(2)->toDateString(),
            'return_date'     => now()->toDateString(),
            'purpose'         => 'Admin loan',
            'status'          => BorrowingRequest::STATUS_BORROWED,
        ]);
        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $adminBorrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin);
        $response = $this->getJson(route('admin.returns.show', $itemReturn->id));
        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $itemReturn->id,
            ],
        ]);
    }

    public function test_admin_return_approve_forbidden_for_kajur_return(): void
    {
        $borrowing = $this->createApprovedKajurBorrowingCheckout();
        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin);
        $this->post(route('admin.returns.approve', $itemReturn->id), ['kondisi' => 'baik'])->assertForbidden();
    }

    public function test_admin_return_reject_forbidden_for_kajur_return(): void
    {
        $borrowing = $this->createApprovedKajurBorrowingCheckout();
        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->admin);
        $this->post(route('admin.returns.reject', $itemReturn->id), ['alasan_ditolak' => 'Barang tidak sesuai deskripsi'])->assertForbidden();
    }

    // =========================================================
    // SECTION 5: Magic link — tiap kasus penolakan individual
    // =========================================================

    public function test_magic_link_rejects_non_kajur_user_with_403(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $url = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);
        $this->actingAs($this->admin)->get($url)->assertForbidden();
        $this->actingAs($this->student)->get($url)->assertForbidden();
        $this->actingAs($this->guru)->get($url)->assertForbidden();
    }

    public function test_magic_link_rejects_wrong_kajur_lender_with_403(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $url = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);
        // kajurB bukan lender
        $this->actingAs($this->kajurB)->get($url)->assertForbidden();
    }

    public function test_magic_link_rejects_non_kajur_lender_type_with_403(): void
    {
        // Borrowing ke admin, bukan kajur
        $borrowing = $this->createPendingAdminBorrowing();
        $url = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);
        // kajurA bukan lender karena borrowing ini ke admin
        $this->actingAs($this->kajurA)->get($url)->assertForbidden();
    }

    public function test_magic_link_rejects_already_processed_borrowing_with_403(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $borrowing->update(['status' => BorrowingRequest::STATUS_APPROVED]);

        $url = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);
        $this->actingAs($this->kajurA)->get($url)->assertForbidden();
    }

    public function test_magic_link_rejects_expired_url_with_403(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $expiredUrl = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->subMinute(), [
            'borrowingRequest' => $borrowing->id,
        ]);
        $this->actingAs($this->kajurA)->get($expiredUrl)->assertForbidden();
    }

    public function test_magic_link_rejects_tampered_signature_with_403(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $validUrl = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);
        $tampered = preg_replace('/signature=[^&]+/', 'signature=tampered_invalid_sig', $validUrl);
        $this->actingAs($this->kajurA)->get($tampered)->assertForbidden();
    }

    public function test_magic_link_correct_lender_kajur_can_access_and_approve(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $validUrl = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);

        $this->actingAs($this->kajurA);
        $this->get($validUrl)->assertOk();

        $approveUrl = URL::temporarySignedRoute('approval-siswa-kajur.approve', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);
        $response = $this->followingRedirects()->post($approveUrl);
        $response->assertOk();
        $this->assertNotSame(BorrowingRequest::STATUS_PENDING, $borrowing->fresh()->status);
    }

    public function test_magic_link_correct_lender_kajur_can_reject_with_reason(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);

        $rejectUrl = URL::temporarySignedRoute('approval-siswa-kajur.reject', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);

        $response = $this->actingAs($this->kajurA)->followingRedirects()->post($rejectUrl, [
            'rejection_reason' => 'Barang sedang diperlukan untuk ujian kompetensi jurusan.',
        ]);

        $response->assertOk();
        $this->assertSame(BorrowingRequest::STATUS_REJECTED, $borrowing->fresh()->status);
        $this->assertSame('Barang sedang diperlukan untuk ujian kompetensi jurusan.', $borrowing->fresh()->rejection_reason);
    }

    public function test_magic_link_reject_forbidden_for_other_kajur(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);

        $rejectUrl = URL::temporarySignedRoute('approval-siswa-kajur.reject', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);

        $this->actingAs($this->kajurB)->post($rejectUrl, [
            'rejection_reason' => 'Penolakan dari kajur yang salah',
        ])->assertForbidden();

        $this->assertSame(BorrowingRequest::STATUS_PENDING, $borrowing->fresh()->status);
    }

    public function test_kajur_multi_item_return_lost_or_damaged_is_rejected_by_existing_guard(): void
    {
        $borrowing = BorrowingRequest::create([
            'user_id' => $this->student->id,
            'tipe_peminjam' => 'siswa',
            'lender_type' => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id' => null,
            'quantity' => null,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'purpose' => 'Uji multi-item return hilang',
            'status' => BorrowingRequest::STATUS_BORROWED,
        ]);

        $item2 = Item::create([
            'name' => 'Barang Kedua Gap',
            'code' => 'GAP-002',
            'inventory_number' => 'INV-GAP-002',
            'category_id' => $this->item->category_id,
            'stock' => 5,
            'status' => 'Tersedia',
            'condition' => 'Baik',
        ]);

        BorrowingRequestItem::create(['borrowing_request_id' => $borrowing->id, 'item_id' => $this->item->id, 'quantity' => 1]);
        BorrowingRequestItem::create(['borrowing_request_id' => $borrowing->id, 'item_id' => $item2->id, 'quantity' => 1]);

        $itemReturn = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id' => $this->student->id,
            'tipe_peminjam' => 'siswa',
            'kondisi_barang' => 'hilang',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);

        $this->actingAs($this->kajurA)->post(route('kajur.verify-return', $itemReturn->id), [
            'verified_condition' => 'Hilang',
        ])->assertSessionHas('error');

        $this->assertSame(ItemReturn::STATUS_MENUNGGU, $itemReturn->fresh()->status);
    }


    // =========================================================
    // HELPERS
    // =========================================================

    private function createPendingKajurBorrowing(User $kajur): BorrowingRequest
    {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $kajur->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(3)->toDateString(),
            'purpose'         => 'Gap test',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowing->id,
            'item_id'              => $this->item->id,
            'quantity'             => 1,
        ]);
        return $borrowing->fresh();
    }

    private function createPendingAdminBorrowing(): BorrowingRequest
    {
        $borrowing = BorrowingRequest::create([
            'user_id'       => $this->student->id,
            'teacher_id'    => $this->guru->id,
            'tipe_peminjam' => 'siswa',
            'lender_type'   => 'admin',
            'item_id'       => $this->item->id,
            'quantity'      => 1,
            'borrow_date'   => now()->toDateString(),
            'return_date'   => now()->addDays(3)->toDateString(),
            'purpose'       => 'Gap test admin',
            'status'        => BorrowingRequest::STATUS_PENDING,
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowing->id,
            'item_id'              => $this->item->id,
            'quantity'             => 1,
        ]);
        return $borrowing->fresh();
    }

    private function createApprovedKajurBorrowing(): BorrowingRequest
    {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id'         => $this->item->id,
            'quantity'        => 2,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(3)->toDateString(),
            'purpose'         => 'Gap test approved',
            'status'          => BorrowingRequest::STATUS_APPROVED,
            'approved_at'     => now(),
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowing->id,
            'item_id'              => $this->item->id,
            'quantity'             => 2,
        ]);
        app(QRCodeService::class)->generateForRequest($borrowing);
        return $borrowing->fresh(['qrCode']);
    }

    private function createApprovedAdminBorrowing(): BorrowingRequest
    {
        $borrowing = BorrowingRequest::create([
            'user_id'       => $this->student->id,
            'teacher_id'    => $this->guru->id,
            'tipe_peminjam' => 'siswa',
            'lender_type'   => 'admin',
            'item_id'       => $this->item->id,
            'quantity'      => 1,
            'borrow_date'   => now()->toDateString(),
            'return_date'   => now()->addDays(3)->toDateString(),
            'purpose'       => 'Gap test admin approved',
            'status'        => BorrowingRequest::STATUS_APPROVED,
            'approved_at'   => now(),
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowing->id,
            'item_id'              => $this->item->id,
            'quantity'             => 1,
        ]);
        app(QRCodeService::class)->generateForRequest($borrowing);
        return $borrowing->fresh(['qrCode']);
    }

    private function createApprovedKajurBorrowingCheckout(): BorrowingRequest
    {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id'         => $this->item->id,
            'quantity'        => 2,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(3)->toDateString(),
            'purpose'         => 'Gap test checkout',
            'status'          => BorrowingRequest::STATUS_BORROWED,
            'approved_at'     => now(),
            'borrowed_at'     => now(),
            'checkout_by'     => $this->kajurA->id,
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowing->id,
            'item_id'              => $this->item->id,
            'quantity'             => 2,
        ]);
        return $borrowing->fresh();
    }

    private function createPendingKajurReturn(User $kajur): ItemReturn
    {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $kajur->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'purpose'         => 'Uji return scoped',
            'status'          => BorrowingRequest::STATUS_BORROWED,
        ]);
        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowing->id,
            'item_id'              => $this->item->id,
            'quantity'             => 1,
        ]);
        return ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id'              => $this->student->id,
            'tipe_peminjam'        => 'siswa',
            'kondisi_barang'       => 'baik',
            'status'               => ItemReturn::STATUS_MENUNGGU,
        ]);
    }

    public function test_kajur_with_empty_or_whitespace_phone_rejected_in_cart_submit(): void
    {
        $kajurEmptyPhone = User::factory()->create([
            'phone' => '   ',
            'jurusan_id' => $this->jurusan->id,
        ]);
        $kajurEmptyPhone->assignRole('kepala_jurusan');

        $this->actingAs($this->student);
        $response = $this->withSession([
            'student_borrowing_cart' => [
                $this->item->id => ['quantity' => 1, 'purpose' => 'Test phone'],
            ],
        ])->post(route('student.loans.cart.submit'), [
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $kajurEmptyPhone->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'whatsapp_number' => '081234567890',
        ]);

        $response->assertSessionHasErrors('lender_kajur_id');
        $this->assertDatabaseMissing('borrowing_requests', [
            'user_id' => $this->student->id,
            'lender_kajur_id' => $kajurEmptyPhone->id,
        ]);
    }

    public function test_kajur_with_empty_or_whitespace_phone_rejected_in_update(): void
    {
        $kajurEmptyPhone = User::factory()->create([
            'phone' => '',
            'jurusan_id' => $this->jurusan->id,
        ]);
        $kajurEmptyPhone->assignRole('kepala_jurusan');

        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'purpose'         => 'Peminjaman awal',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->student);
        $response = $this->put(route('student.loans.update', $borrowing->id), [
            'quantity'        => 1,
            'purpose'         => 'Update keperluan peminjaman',
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $kajurEmptyPhone->id,
        ]);

        $response->assertSessionHasErrors('lender_kajur_id');
        $this->assertSame($this->kajurA->id, $borrowing->fresh()->lender_kajur_id);
    }

    public function test_kajurs_with_phone_in_cart_and_edit_excludes_empty_or_whitespace_phone(): void
    {
        $kajurWhitespace = User::factory()->create([
            'name' => 'Kajur Whitespace',
            'phone' => '   ',
            'jurusan_id' => $this->jurusan->id,
        ]);
        $kajurWhitespace->assignRole('kepala_jurusan');

        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'purpose'         => 'Test edit view',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->student);

        // Cart view
        $cartResponse = $this->get(route('student.loans.cart'));
        $cartResponse->assertOk();
        $kajursInCart = $cartResponse->viewData('kajursWithPhone');
        $this->assertFalse($kajursInCart->contains('id', $kajurWhitespace->id));
        $this->assertTrue($kajursInCart->contains('id', $this->kajurA->id));

        // Edit view
        $editResponse = $this->get(route('student.loans.edit', $borrowing->id));
        $editResponse->assertOk();
        $kajursInEdit = $editResponse->viewData('kajursWithPhone');
        $this->assertFalse($kajursInEdit->contains('id', $kajurWhitespace->id));
        $this->assertTrue($kajursInEdit->contains('id', $this->kajurA->id));
    }

    public function test_pending_approvals_renders_review_link_for_student_and_approve_button_for_teacher(): void
    {
        $studentBorrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'purpose'         => 'Pengajuan Siswa Uji Tinjau',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $teacher = User::factory()->create(['jurusan_id' => $this->jurusan->id]);
        $teacher->assignRole('guru');

        $teacherBorrowing = BorrowingRequest::create([
            'user_id'         => $teacher->id,
            'tipe_peminjam'   => 'guru',
            'lender_type'     => 'admin',
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'purpose'         => 'Pengajuan Guru Uji Approve',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $this->actingAs($this->kajurA);
        $response = $this->get(route('kajur.pending-approvals'));
        $response->assertOk();

        // Baris siswa harus memiliki URL signed 'approval-siswa-kajur.show' dan teks Tinjau
        $expectedStudentUrl = route('approval-siswa-kajur.show', ['borrowingRequest' => $studentBorrowing->id]);
        $response->assertSee($expectedStudentUrl);
        $response->assertSee('Tinjau');

        // Baris guru harus memiliki form action 'kajur.approve-request' dan tombol Setujui
        $expectedTeacherAction = route('kajur.approve-request', $teacherBorrowing->id);
        $response->assertSee($expectedTeacherAction);
        $response->assertSee('Setujui');
    }

    public function test_non_student_cart_submit_forces_admin_lender_type_and_null_lender_kajur(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('guru');

        $this->actingAs($teacher);
        $response = $this->withSession([
            'student_borrowing_cart' => [
                $this->item->id => ['quantity' => 1, 'purpose' => 'Guru coba pilih kajur'],
            ],
        ])->post(route('student.loans.cart.submit'), [
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'whatsapp_number' => '081234567890',
        ]);

        $response->assertRedirect(route('student.loans'));
        $created = BorrowingRequest::where('user_id', $teacher->id)->latest('id')->first();
        $this->assertNotNull($created);
        $this->assertSame('guru', $created->tipe_peminjam);
        $this->assertSame('admin', $created->lender_type);
        $this->assertNull($created->lender_kajur_id);
    }

    public function test_non_student_update_forces_admin_lender_type_and_null_lender_kajur(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('guru');

        $borrowing = BorrowingRequest::create([
            'user_id'         => $teacher->id,
            'tipe_peminjam'   => 'guru',
            'lender_type'     => 'admin',
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'purpose'         => 'Pinjaman awal guru',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $this->actingAs($teacher);
        $response = $this->put(route('student.loans.update', $borrowing->id), [
            'quantity'        => 1,
            'purpose'         => 'Guru update coba pilih kajur',
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'return_time'     => '14:00',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
        ]);

        $response->assertRedirect(route('student.loans'));
        $fresh = $borrowing->fresh();
        $this->assertSame('admin', $fresh->lender_type);
        $this->assertNull($fresh->lender_kajur_id);
    }

    public function test_reject_student_kajur_authorizes_before_input_validation_so_non_lender_gets_403_even_with_invalid_payload(): void
    {
        $borrowing = BorrowingRequest::create([
            'user_id'         => $this->student->id,
            'tipe_peminjam'   => 'siswa',
            'lender_type'     => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id'         => $this->item->id,
            'quantity'        => 1,
            'borrow_date'     => now()->toDateString(),
            'return_date'     => now()->addDays(2)->toDateString(),
            'purpose'         => 'Gap test payload invalid reject',
            'status'          => BorrowingRequest::STATUS_PENDING,
        ]);

        $signedRejectUrl = URL::temporarySignedRoute(
            'approval-siswa-kajur.reject',
            now()->addDays(7),
            ['borrowingRequest' => $borrowing->id]
        );

        $this->actingAs($this->kajurB);
        $response = $this->post($signedRejectUrl, [
            'rejection_reason' => '',
        ]);

        $response->assertForbidden();
    }

    public function test_magic_link_approve_redirects_to_kajur_history_and_displays_flash(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $approveUrl = URL::temporarySignedRoute('approval-siswa-kajur.approve', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);

        $this->actingAs($this->kajurA);
        $response = $this->followingRedirects()->post($approveUrl);

        $response->assertOk();
        $response->assertSee('Pengajuan siswa berhasil disetujui. QR Code telah diterbitkan.');
    }

    public function test_magic_link_reject_redirects_to_kajur_history_and_displays_flash(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $rejectUrl = URL::temporarySignedRoute('approval-siswa-kajur.reject', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);

        $this->actingAs($this->kajurA);
        $response = $this->followingRedirects()->post($rejectUrl, [
            'rejection_reason' => 'Alasan penolakan valid minimal sepuluh karakter.',
        ]);

        $response->assertOk();
        $response->assertSee('Pengajuan siswa berhasil ditolak.');
    }
}
