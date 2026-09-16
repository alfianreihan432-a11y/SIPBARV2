<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CartBorrowingFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'kepala_jurusan', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
    }

    public function test_student_catalog_uses_cart_flow_instead_of_single_item_modal(): void
    {
        $user = User::factory()->create();
        $user->assignRole('siswa');

        Item::create([
            'code' => 'S-001',
            'inventory_number' => 'INV-S-001',
            'name' => 'Laptop Uji Siswa',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 3,
        ]);

        $response = $this->actingAs($user)->get(route('student.catalog'));

        $response->assertOk();
        $response->assertSee('Katalog Barang');
        $response->assertSee('Pinjam Barang');
        $response->assertSee('addToCart');
        $response->assertDontSee('student.loans.create');
    }

    public function test_teacher_catalog_uses_cart_flow_instead_of_single_item_create_route(): void
    {
        $user = User::factory()->create();
        $user->assignRole('guru');

        Item::create([
            'code' => 'G-101',
            'inventory_number' => 'INV-G-101',
            'name' => 'Proyektor Uji Guru',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 4,
        ]);

        $response = $this->actingAs($user)->get(route('teacher.barang'));

        $response->assertOk();
        $response->assertSee('Katalog Barang Tersedia');
        $response->assertSee('Pinjam Barang');
        $response->assertSee(route('teacher.peminjaman-guru.cart.add'));
        $response->assertDontSee(route('teacher.peminjaman-guru.create'));
    }

    public function test_student_cart_submit_creates_two_items_for_one_request(): void
    {
        $user = User::factory()->create();
        $user->assignRole('siswa');

        $itemA = Item::create([
            'code' => 'A-001',
            'inventory_number' => 'INV-A-001',
            'name' => 'Laptop Lenovo',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 5,
        ]);

        $itemB = Item::create([
            'code' => 'A-002',
            'inventory_number' => 'INV-A-002',
            'name' => 'Proyektor',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 5,
        ]);

        $this->withSession([
            'student_borrowing_cart' => [
                $itemA->id => ['quantity' => 1],
                $itemB->id => ['quantity' => 2],
            ],
        ]);

        $response = $this->actingAs($user)->post(route('student.loans.cart.submit'), [
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'return_time' => '14:00',
            'teacher_id' => null,
            'purpose' => 'Praktikum multi-barang',
            'notes' => 'uji cart',
        ]);

        $response->assertRedirect(route('student.loans'));

        $request = BorrowingRequest::where('user_id', $user->id)->firstOrFail();
        $this->assertCount(2, BorrowingRequestItem::where('borrowing_request_id', $request->id)->get());
        $this->assertDatabaseHas('borrowing_request_items', [
            'borrowing_request_id' => $request->id,
            'item_id' => $itemA->id,
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('borrowing_request_items', [
            'borrowing_request_id' => $request->id,
            'item_id' => $itemB->id,
            'quantity' => 2,
        ]);
    }

    public function test_teacher_cart_submit_creates_two_items_for_one_request(): void
    {
        $user = User::factory()->create();
        $user->assignRole('guru');

        $kajur = User::factory()->create();
        $kajur->assignRole('kepala_jurusan');

        $itemA = Item::create([
            'code' => 'G-001',
            'inventory_number' => 'INV-G-001',
            'name' => 'Microscope',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 10,
        ]);

        $itemB = Item::create([
            'code' => 'G-002',
            'inventory_number' => 'INV-G-002',
            'name' => 'Kamera Digital',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 7,
        ]);

        $this->withSession([
            'teacher_borrowing_cart' => [
                $itemA->id => ['quantity' => 1],
                $itemB->id => ['quantity' => 1],
            ],
        ]);

        $response = $this->actingAs($user)->post(route('teacher.peminjaman-guru.cart.submit'), [
            'kepala_jurusan_id' => $kajur->id,
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'return_time' => '15:00',
            'purpose' => 'Kegiatan pembelajaran guru',
            'notes' => 'uji cart guru',
        ]);

        $response->assertRedirect(route('teacher.peminjaman-guru'));

        $request = BorrowingRequest::where('user_id', $user->id)->firstOrFail();
        $this->assertCount(2, BorrowingRequestItem::where('borrowing_request_id', $request->id)->get());
        $this->assertDatabaseHas('borrowing_request_items', [
            'borrowing_request_id' => $request->id,
            'item_id' => $itemA->id,
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('borrowing_request_items', [
            'borrowing_request_id' => $request->id,
            'item_id' => $itemB->id,
            'quantity' => 1,
        ]);
    }

    public function test_multi_item_request_can_be_approved_when_item_id_is_null(): void
    {
        $student = User::factory()->create();
        $student->assignRole('siswa');

        $teacher = User::factory()->create();
        $teacher->assignRole('guru');

        $itemA = Item::create([
            'code' => 'M-001',
            'inventory_number' => 'INV-M-001',
            'name' => 'Laptop',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 5,
        ]);

        $itemB = Item::create([
            'code' => 'M-002',
            'inventory_number' => 'INV-M-002',
            'name' => 'Proyektor',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 3,
        ]);

        $request = BorrowingRequest::create([
            'user_id' => $student->id,
            'teacher_id' => $teacher->id,
            'item_id' => null,
            'quantity' => null,
            'purpose' => 'Praktikum multi-item',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '13:00',
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'siswa',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $request->id,
            'item_id' => $itemA->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $request->id,
            'item_id' => $itemB->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $service = app(\App\Services\BorrowingApprovalService::class);
        $service->approve($request, $teacher->id);

        $request->refresh();
        $this->assertSame(BorrowingRequest::STATUS_APPROVED, $request->status);
        $this->assertCount(2, $request->items()->get());
    }
}
