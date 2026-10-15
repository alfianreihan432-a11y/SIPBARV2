<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\Jurusan;
use App\Models\User;
use App\Services\QRCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentKajurLendingFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $kajurA;
    private User $kajurB;
    private User $admin;
    private Item $item;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['siswa', 'kepala_jurusan', 'admin', 'guru'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $guru = User::factory()->create();
        $guru->assignRole('guru');

        $jurusan = Jurusan::create(['nama' => 'Teknik Komputer', 'kode' => 'TK']);
        $this->student = User::factory()->create(['jurusan_id' => $jurusan->id]);
        $this->student->assignRole('siswa');
        $this->kajurA = User::factory()->create(['jurusan_id' => $jurusan->id, 'phone' => '081234567890']);
        $this->kajurA->assignRole('kepala_jurusan');
        $this->kajurB = User::factory()->create(['jurusan_id' => $jurusan->id, 'phone' => '081234567891']);
        $this->kajurB->assignRole('kepala_jurusan');
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $category = \App\Models\Category::create(['name' => 'Perangkat', 'slug' => 'perangkat']);
        $this->item = Item::create([
            'name' => 'Laptop Uji Kajur',
            'code' => 'KJR-LOAN-01',
            'inventory_number' => 'KJR-INV-01',
            'category_id' => $category->id,
            'stock' => 10,
            'status' => 'Tersedia',
            'condition' => 'Baik',
        ]);
    }

    public function test_student_can_submit_to_kajur_without_teacher_and_gets_owned_request(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);

        $this->assertSame('kajur', $borrowing->lender_type);
        $this->assertSame($this->kajurA->id, $borrowing->lender_kajur_id);
        $this->assertNull($borrowing->teacher_id);
        $this->assertSame(BorrowingRequest::STATUS_PENDING, $borrowing->status);
    }

    public function test_form_submission_to_kajur_without_teacher_id_redirects_with_success(): void
    {
        $this->studentCart();

        $payload = [
            'lender_type' => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'whatsapp_number' => '081234567899',
            'purpose' => 'Uji peminjaman lender kajur tanpa teacher_id',
            'notes' => 'Catatan uji',
            'borrow_date' => now()->addDay()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'return_time' => '14:00',
        ];

        $response = $this->actingAs($this->student)->post(route('student.loans.cart.submit'), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('student.loans'));
        $response->assertSessionHas('success', 'Permohonan multi-barang berhasil diajukan.');
    }

    public function test_form_submission_to_kajur_with_teacher_id_zero_succeeds(): void
    {
        $this->studentCart();

        $payload = [
            'lender_type' => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'teacher_id' => '0',
            'whatsapp_number' => '081234567899',
            'purpose' => 'Uji peminjaman lender kajur dengan teacher_id 0',
            'notes' => 'Catatan uji',
            'borrow_date' => now()->addDay()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'return_time' => '14:00',
        ];

        $response = $this->actingAs($this->student)->post(route('student.loans.cart.submit'), $payload);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('student.loans'));
        $response->assertSessionHas('success', 'Permohonan multi-barang berhasil diajukan.');
    }





    public function test_kajur_submission_requires_a_selected_kajur(): void
    {
        $this->studentCart();

        $response = $this->actingAs($this->student)->post(route('student.loans.cart.submit'), $this->submission([
            'lender_type' => 'kajur',
        ]));

        $response->assertSessionHasErrors('lender_kajur_id');
    }

    public function test_student_cannot_submit_tampered_kajur_without_phone(): void
    {
        $this->kajurA->update(['phone' => null]);
        $this->studentCart();

        $response = $this->actingAs($this->student)->post(route('student.loans.cart.submit'), $this->submission([
            'lender_type' => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
        ]));

        $response->assertSessionHasErrors('lender_kajur_id');
        $this->assertDatabaseMissing('borrowing_requests', ['lender_kajur_id' => $this->kajurA->id]);
    }

    public function test_student_to_kajur_magic_link_requires_current_signed_pending_exact_lender(): void
    {
        $borrowing = $this->createPendingKajurBorrowing($this->kajurA);
        $validUrl = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);

        $this->actingAs($this->kajurB)->get($validUrl)->assertForbidden();
        $this->actingAs($this->admin)->get($validUrl)->assertForbidden();

        $adminBorrowing = $this->createPendingAdminBorrowing();
        $adminUrl = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $adminBorrowing->id,
        ]);
        $this->actingAs($this->kajurA)->get($adminUrl)->assertForbidden();

        $expiredUrl = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->subMinute(), [
            'borrowingRequest' => $borrowing->id,
        ]);
        $this->actingAs($this->kajurA)->get($expiredUrl)->assertForbidden();

        $invalidUrl = preg_replace('/signature=[^&]+/', 'signature=invalid', $validUrl);
        $this->actingAs($this->kajurA)->get($invalidUrl)->assertForbidden();

        $borrowing->update(['status' => BorrowingRequest::STATUS_APPROVED]);
        $processedUrl = URL::temporarySignedRoute('approval-siswa-kajur.show', now()->addMinutes(10), [
            'borrowingRequest' => $borrowing->id,
        ]);
        $this->actingAs($this->kajurA)->get($processedUrl)->assertForbidden();
    }

    public function test_kajur_student_qr_is_exclusive_and_admin_qr_stays_admin_only(): void
    {
        $kajurBorrowing = $this->createApprovedKajurBorrowing();
        $kajurQr = $kajurBorrowing->qrCode;
        $kajurVerifyUrl = route('kajur.qr.verify', ['token' => $kajurQr->code]);

        $this->actingAs($this->admin)->get(route('admin.qr.verify', ['token' => $kajurQr->code]))
            ->assertSee('QR Kajur hanya dapat diproses oleh Kajur pemberi pinjaman.');
        $this->post(route('admin.qr.confirm-checkout', $kajurBorrowing->id))
            ->assertSessionHas('error', 'QR Kajur hanya dapat diproses oleh Kajur pemberi pinjaman.');

        $this->actingAs($this->kajurB)->get($kajurVerifyUrl)
            ->assertSee('QR ini hanya dapat diproses oleh Kajur pemberi pinjaman.');
        $this->post(route('kajur.qr.confirm-checkout', $kajurBorrowing->id))
            ->assertSessionHas('error', 'QR ini hanya dapat diproses oleh Kajur pemberi pinjaman.');

        $this->actingAs($this->kajurA)->get($kajurVerifyUrl)->assertSee($this->student->name);
        $this->post(route('kajur.qr.confirm-checkout', $kajurBorrowing->id))
            ->assertRedirect($kajurVerifyUrl);

        $adminBorrowing = $this->createApprovedAdminBorrowing();
        $adminQr = $adminBorrowing->qrCode;
        $this->get(route('kajur.qr.verify', ['token' => $adminQr->code]))
            ->assertSee('QR Admin hanya dapat diproses oleh Admin.');
        $this->post(route('kajur.qr.confirm-checkout', $adminBorrowing->id))
            ->assertSessionHas('error', 'QR Admin hanya dapat diproses oleh Admin.');
        $this->actingAs($this->admin)->get(route('admin.qr.verify', ['token' => $adminQr->code]))
            ->assertSee($this->student->name);
    }

    public function test_kajur_return_list_and_actions_are_scoped_and_admin_cannot_handle_kajur_return(): void
    {
        $ownedReturn = $this->createPendingKajurReturn($this->kajurA);
        $otherKajurReturn = $this->createPendingKajurReturn($this->kajurB);

        $this->actingAs($this->kajurA);
        $list = $this->get(route('kajur.pending-returns'));
        $list->assertOk()->assertSee('Return-'.$ownedReturn->id)->assertDontSee('Return-'.$otherKajurReturn->id);
        $this->get(route('kajur.pending-returns.show', $otherKajurReturn->id))->assertForbidden();
        $this->post(route('kajur.verify-return', $otherKajurReturn->id), ['verified_condition' => 'Baik'])->assertForbidden();
        $this->post(route('kajur.pending-returns.reject', $otherKajurReturn->id), ['alasan_ditolak' => 'Tidak sesuai kondisi barang'])->assertForbidden();

        $this->actingAs($this->admin);
        $this->get(route('admin.returns.show', $ownedReturn->id))->assertForbidden();
        $this->post(route('admin.returns.approve', $ownedReturn->id), ['kondisi' => 'baik'])->assertForbidden();
        $this->post(route('admin.returns.reject', $ownedReturn->id), ['alasan_ditolak' => 'Tidak sesuai kondisi barang'])->assertForbidden();
    }

    public function test_kajur_rejects_admin_return_and_admin_can_approve_admin_return(): void
    {
        $adminReturn = $this->createPendingAdminReturn();

        $this->actingAs($this->kajurA);
        $this->get(route('kajur.pending-returns'))->assertDontSee('Return-'.$adminReturn->id);
        $this->get(route('kajur.pending-returns.show', $adminReturn->id))->assertForbidden();
        $this->post(route('kajur.verify-return', $adminReturn->id), ['verified_condition' => 'Baik'])->assertForbidden();
        $this->post(route('kajur.pending-returns.reject', $adminReturn->id), ['alasan_ditolak' => 'Tidak sesuai kondisi barang'])->assertForbidden();

        $this->actingAs($this->admin);
        $this->getJson(route('admin.returns.show', $adminReturn->id))->assertOk();
        $this->post(route('admin.returns.approve', $adminReturn->id), ['kondisi' => 'baik'])->assertSessionHas('success');
    }

    public function test_kajur_return_uses_shared_stock_service_and_keeps_multi_item_guard(): void
    {
        $borrowing = $this->createApprovedKajurBorrowing();
        $this->item->update(['stock' => 8]);
        $return = ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id' => $this->student->id,
            'tipe_peminjam' => 'siswa',
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);

        $response = $this->actingAs($this->kajurA)->post(route('kajur.verify-return', $return->id), [
            'verified_condition' => 'Baik',
        ]);

        $response->assertSessionHas('success');
        $this->assertSame(10, $this->item->fresh()->stock);
        $this->assertSame(BorrowingRequest::STATUS_RETURNED, $borrowing->fresh()->status);

        $multi = $this->createApprovedKajurBorrowing(true);
        $multiReturn = ItemReturn::create([
            'borrowing_request_id' => $multi->id,
            'user_id' => $this->student->id,
            'tipe_peminjam' => 'siswa',
            'kondisi_barang' => 'rusak_berat',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);
        $this->post(route('kajur.verify-return', $multiReturn->id), [
            'verified_condition' => 'Rusak Berat',
        ])->assertSessionHas('error');
        $this->assertSame(ItemReturn::STATUS_MENUNGGU, $multiReturn->fresh()->status);
    }

    private function studentCart(): void
    {
        session(['student_borrowing_cart' => [$this->item->id => ['quantity' => 1, 'purpose' => 'Uji lender']]]);
    }

    private function submission(array $overrides = []): array
    {
        return array_merge([
            'lender_type' => 'admin',
            'teacher_id' => User::role('guru')->first()?->id,
            'borrow_date' => now()->addDay()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'return_time' => '14:00',
            'whatsapp_number' => '081234567899',
            'purpose' => 'Uji peminjaman lender',
        ], $overrides);
    }

    private function createPendingKajurBorrowing(User $kajur): BorrowingRequest
    {
        $this->studentCart();
        $response = $this->actingAs($this->student)->post(route('student.loans.cart.submit'), $this->submission([
            'lender_type' => 'kajur',
            'lender_kajur_id' => $kajur->id,
        ]));
        $response->assertSessionHas('success');
        return BorrowingRequest::where('user_id', $this->student->id)->latest('id')->firstOrFail();
    }

    private function createPendingAdminBorrowing(): BorrowingRequest
    {
        $this->studentCart();
        $this->actingAs($this->student)->post(route('student.loans.cart.submit'), $this->submission())->assertSessionHas('success');
        return BorrowingRequest::where('user_id', $this->student->id)->latest('id')->firstOrFail();
    }

    private function createApprovedKajurBorrowing(bool $multiItem = false, int $quantity = 2): BorrowingRequest
    {
        $borrowing = BorrowingRequest::create([
            'user_id' => $this->student->id,
            'tipe_peminjam' => 'siswa',
            'lender_type' => 'kajur',
            'lender_kajur_id' => $this->kajurA->id,
            'item_id' => $multiItem ? null : $this->item->id,
            'quantity' => $multiItem ? null : $quantity,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'return_time' => '14:00',
            'purpose' => 'Uji pengembalian Kajur',
            'status' => BorrowingRequest::STATUS_APPROVED,
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $borrowing->id, 'item_id' => $this->item->id, 'quantity' => $quantity]);
        if ($multiItem) {
            $item2 = Item::create([
                'name' => 'Mouse Uji Kajur',
                'code' => 'KJR-LOAN-02',
                'inventory_number' => 'KJR-INV-02',
                'category_id' => $this->item->category_id,
                'stock' => 10,
                'status' => 'Tersedia',
                'condition' => 'Baik',
            ]);
            BorrowingRequestItem::create(['borrowing_request_id' => $borrowing->id, 'item_id' => $item2->id, 'quantity' => 1]);
        }
        app(QRCodeService::class)->generateForRequest($borrowing);
        return $borrowing->fresh(['qrCode', 'items.item']);
    }

    private function createApprovedAdminBorrowing(): BorrowingRequest
    {
        $borrowing = BorrowingRequest::create([
            'user_id' => $this->student->id,
            'teacher_id' => User::role('guru')->first()?->id,
            'tipe_peminjam' => 'siswa',
            'lender_type' => 'admin',
            'item_id' => $this->item->id,
            'quantity' => 1,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'purpose' => 'Uji return Admin',
            'status' => BorrowingRequest::STATUS_APPROVED,
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $borrowing->id, 'item_id' => $this->item->id, 'quantity' => 1]);
        app(QRCodeService::class)->generateForRequest($borrowing);
        return $borrowing->fresh(['qrCode', 'items.item']);
    }

    private function createPendingKajurReturn(User $kajur): ItemReturn
    {
        $borrowing = BorrowingRequest::create([
            'user_id' => $this->student->id,
            'tipe_peminjam' => 'siswa',
            'lender_type' => 'kajur',
            'lender_kajur_id' => $kajur->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'purpose' => 'Uji return Kajur',
            'status' => BorrowingRequest::STATUS_BORROWED,
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $borrowing->id, 'item_id' => $this->item->id, 'quantity' => 1]);
        return ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id' => $this->student->id,
            'tipe_peminjam' => 'siswa',
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);
    }

    private function createPendingAdminReturn(): ItemReturn
    {
        $borrowing = BorrowingRequest::create([
            'user_id' => $this->student->id,
            'teacher_id' => User::role('guru')->first()?->id,
            'tipe_peminjam' => 'siswa',
            'lender_type' => 'admin',
            'item_id' => $this->item->id,
            'quantity' => 1,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'purpose' => 'Uji return Admin',
            'status' => BorrowingRequest::STATUS_BORROWED,
        ]);
        BorrowingRequestItem::create(['borrowing_request_id' => $borrowing->id, 'item_id' => $this->item->id, 'quantity' => 1]);
        return ItemReturn::create([
            'borrowing_request_id' => $borrowing->id,
            'user_id' => $this->student->id,
            'tipe_peminjam' => 'siswa',
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);
    }
}
