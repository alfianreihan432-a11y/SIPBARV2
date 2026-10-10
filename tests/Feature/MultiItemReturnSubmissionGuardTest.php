<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MultiItemReturnSubmissionGuardTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    private User $teacher;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);

        $this->student = User::factory()->create();
        $this->student->assignRole('siswa');

        $this->teacher = User::factory()->create();
        $this->teacher->assignRole('guru');
    }

    public function test_teacher_multi_item_hilang_is_rejected_without_mutation(): void
    {
        $borrowing = $this->createMultiItemBorrowing($this->teacher, 'guru');

        $response = $this->actingAs($this->teacher)->post(
            route('teacher.pengembalian-guru.store', $borrowing->id),
            ['kondisi_barang' => 'hilang']
        );

        $this->assertRejectedSubmission($response, $borrowing);
    }

    public function test_teacher_multi_item_rusak_berat_is_rejected_without_mutation(): void
    {
        $borrowing = $this->createMultiItemBorrowing($this->teacher, 'guru');

        $response = $this->actingAs($this->teacher)->post(
            route('teacher.pengembalian-guru.store', $borrowing->id),
            ['kondisi_barang' => 'rusak_berat']
        );

        $this->assertRejectedSubmission($response, $borrowing);
    }

    public function test_student_multi_item_hilang_is_rejected_without_mutation(): void
    {
        $borrowing = $this->createMultiItemBorrowing($this->student, 'siswa');

        $response = $this->actingAs($this->student)->post(route('student.returns.store'), [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'hilang',
        ]);

        $this->assertRejectedSubmission($response, $borrowing);
    }

    public function test_student_multi_item_rusak_berat_is_rejected_without_mutation(): void
    {
        $borrowing = $this->createMultiItemBorrowing($this->student, 'siswa');

        $response = $this->actingAs($this->student)->post(route('student.returns.store'), [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'rusak_berat',
        ]);

        $this->assertRejectedSubmission($response, $borrowing);
    }

    public function test_teacher_multi_item_baik_submission_still_succeeds(): void
    {
        $borrowing = $this->createMultiItemBorrowing($this->teacher, 'guru');

        $response = $this->actingAs($this->teacher)->post(
            route('teacher.pengembalian-guru.store', $borrowing->id),
            ['kondisi_barang' => 'baik']
        );

        $response->assertRedirect(route('teacher.pengembalian-guru'));
        $response->assertSessionHas('success');
        $this->assertSame(BorrowingRequest::STATUS_RETURNED, $borrowing->fresh()->status);
        $this->assertDatabaseHas('item_returns', [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);
    }

    public function test_teacher_multi_item_rusak_ringan_submission_still_succeeds(): void
    {
        $borrowing = $this->createMultiItemBorrowing($this->teacher, 'guru');

        $response = $this->actingAs($this->teacher)->post(
            route('teacher.pengembalian-guru.store', $borrowing->id),
            ['kondisi_barang' => 'rusak_ringan']
        );

        $response->assertRedirect(route('teacher.pengembalian-guru'));
        $response->assertSessionHas('success');
        $this->assertSame(BorrowingRequest::STATUS_RETURNED, $borrowing->fresh()->status);
        $this->assertDatabaseHas('item_returns', [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'rusak_ringan',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);
    }

    public function test_student_multi_item_baik_submission_still_succeeds(): void
    {
        $borrowing = $this->createMultiItemBorrowing($this->student, 'siswa');

        $response = $this->actingAs($this->student)->post(route('student.returns.store'), [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'baik',
        ]);

        $response->assertRedirect(route('student.returns.history'));
        $response->assertSessionHas('success');
        $this->assertSame(BorrowingRequest::STATUS_BORROWED, $borrowing->fresh()->status);
        $this->assertDatabaseHas('item_returns', [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);
    }

    public function test_student_multi_item_rusak_ringan_submission_still_succeeds(): void
    {
        $borrowing = $this->createMultiItemBorrowing($this->student, 'siswa');

        $response = $this->actingAs($this->student)->post(route('student.returns.store'), [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'rusak_ringan',
        ]);

        $response->assertRedirect(route('student.returns.history'));
        $response->assertSessionHas('success');
        $this->assertSame(BorrowingRequest::STATUS_BORROWED, $borrowing->fresh()->status);
        $this->assertDatabaseHas('item_returns', [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'rusak_ringan',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);
    }

    public function test_teacher_single_item_hilang_submission_still_succeeds(): void
    {
        $item = $this->createItem();
        $borrowing = BorrowingRequest::create([
            'user_id' => $this->teacher->id,
            'tipe_peminjam' => 'guru',
            'approved_by_kajur_id' => null,
            'item_id' => $item->id,
            'quantity' => 2,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00',
            'purpose' => 'Single item return guard test',
            'status' => BorrowingRequest::STATUS_BORROWED,
        ]);

        $response = $this->actingAs($this->teacher)->post(
            route('teacher.pengembalian-guru.store', $borrowing->id),
            ['kondisi_barang' => 'hilang']
        );

        $response->assertRedirect(route('teacher.pengembalian-guru'));
        $response->assertSessionHas('success');
        $this->assertSame(BorrowingRequest::STATUS_RETURNED, $borrowing->fresh()->status);
        $this->assertDatabaseHas('item_returns', [
            'borrowing_request_id' => $borrowing->id,
            'kondisi_barang' => 'hilang',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);
    }

    private function assertRejectedSubmission($response, BorrowingRequest $borrowing): void
    {
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pengembalian multi-item dengan kondisi hilang atau rusak berat belum bisa diajukan lewat form. Hubungi admin untuk penanganan manual.');
        $response->assertSessionMissing('success');
        $this->assertSame(BorrowingRequest::STATUS_BORROWED, $borrowing->fresh()->status);
        $this->assertDatabaseMissing('item_returns', [
            'borrowing_request_id' => $borrowing->id,
        ]);
    }

    private function createMultiItemBorrowing(User $user, string $borrowerType): BorrowingRequest
    {
        $borrowing = BorrowingRequest::create([
            'user_id' => $user->id,
            'tipe_peminjam' => $borrowerType,
            'item_id' => null,
            'quantity' => null,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00',
            'purpose' => 'Multi-item return guard test',
            'status' => BorrowingRequest::STATUS_BORROWED,
        ]);

        foreach ([1, 2] as $quantity) {
            BorrowingRequestItem::create([
                'borrowing_request_id' => $borrowing->id,
                'item_id' => $this->createItem()->id,
                'quantity' => $quantity,
                'kondisi_saat_pinjam' => 'baik',
            ]);
        }

        return $borrowing;
    }

    private function createItem(): Item
    {
        $suffix = Str::upper(Str::random(10));

        return Item::create([
            'code' => 'RET-'.$suffix,
            'inventory_number' => 'INV-RET-'.$suffix,
            'name' => 'Return Guard Item '.$suffix,
            'stock' => 5,
            'condition' => 'Baik',
            'status' => 'Tersedia',
        ]);
    }
}
