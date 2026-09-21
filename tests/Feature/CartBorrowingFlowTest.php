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

    public function test_student_can_update_cart_item_quantity(): void
    {
        $user = User::factory()->create();
        $user->assignRole('siswa');

        $item = Item::create([
            'code' => 'TEST-001',
            'inventory_number' => 'INV-TEST-001',
            'name' => 'Kamera DSLR',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 5,
        ]);

        $this->withSession([
            'student_borrowing_cart' => [
                $item->id => ['quantity' => 1, 'purpose' => ''],
            ],
        ]);

        $response = $this->actingAs($user)->post(route('student.loans.cart.update', $item->id), [
            'quantity' => 3,
            'purpose' => 'Untuk fotografi acara',
        ]);

        $response->assertRedirect(route('student.loans.cart'));
        $response->assertSessionHas('success');
        $this->assertEquals(3, session('student_borrowing_cart')[$item->id]['quantity']);
        $this->assertEquals('Untuk fotografi acara', session('student_borrowing_cart')[$item->id]['purpose']);
    }

    public function test_student_cart_submit_saves_whatsapp_number(): void
    {
        $user = User::factory()->create(['phone' => '081234567890']);
        $user->assignRole('siswa');

        $item = Item::create([
            'code' => 'TEST-002',
            'inventory_number' => 'INV-TEST-002',
            'name' => 'Proyektor Epson',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 5,
        ]);

        $this->withSession([
            'student_borrowing_cart' => [
                $item->id => ['quantity' => 1],
            ],
        ]);

        $response = $this->actingAs($user)->post(route('student.loans.cart.submit'), [
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00',
            'whatsapp_number' => '089876543210',
            'purpose' => 'Presentasi kelas',
        ]);

        $response->assertRedirect(route('student.loans'));
        $this->assertDatabaseHas('borrowing_requests', [
            'user_id' => $user->id,
            'whatsapp_number' => '089876543210',
            'purpose' => 'Presentasi kelas',
        ]);
    }

    public function test_magic_approval_page_shows_multi_items_teacher_whatsapp_and_schedule(): void
    {
        $teacher = User::factory()->create(['name' => 'Pak Budi Guru Pembimbing']);
        $teacher->assignRole('guru');

        $student = User::factory()->create([
            'name' => 'Ahmad Siswa',
            'kelas' => 'XII RPL 1',
            'phone' => '081234567890',
        ]);
        $student->assignRole('siswa');

        $item1 = Item::create([
            'code' => 'ITM-001',
            'inventory_number' => 'INV-001',
            'name' => 'ALAT RUMAH TANGGA LAINNYA.ALAT RUMAH TANGGA LAINNYA.MEJA KURSI - [ ]',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 10,
        ]);

        $item2 = Item::create([
            'code' => 'ITM-002',
            'inventory_number' => 'INV-002',
            'name' => 'Kamera DSLR Canon',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 5,
        ]);

        $borrowingRequest = BorrowingRequest::create([
            'user_id' => $student->id,
            'teacher_id' => $teacher->id,
            'purpose' => 'Kebutuhan Dokumentasi Event',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'return_time' => '15:30',
            'whatsapp_number' => '089988776655',
            'notes' => 'Harap konfirmasi segera',
            'status' => BorrowingRequest::STATUS_PENDING,
            'tipe_peminjam' => 'siswa',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowingRequest->id,
            'item_id' => $item1->id,
            'quantity' => 2,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowingRequest->id,
            'item_id' => $item2->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('approval.show', [
            'borrowingRequest' => $borrowingRequest->id,
        ]);

        $response = $this->actingAs($teacher)->get($signedUrl);

        $response->assertOk();
        $response->assertSee('Ahmad Siswa');
        $response->assertSee('XII RPL 1');
        $response->assertSee('Pak Budi Guru Pembimbing');
        $response->assertSee('089988776655');
        $response->assertSee('Meja Kursi');
        $response->assertSee('Kamera Dslr Canon');
        $response->assertSee('2 unit');
        $response->assertSee('1 unit');
        $response->assertSee('15:30 WIB');
        $response->assertSee('Kebutuhan Dokumentasi Event');
        $response->assertSee('Harap konfirmasi segera');
    }

    public function test_teacher_cart_update_item_quantity(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('guru');

        $item = Item::create([
            'code' => 'G-200',
            'inventory_number' => 'INV-G-200',
            'name' => 'Speaker Portabel',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 4,
        ]);

        $this->withSession([
            'teacher_borrowing_cart' => [
                $item->id => ['quantity' => 1],
            ],
        ]);

        // Valid update within stock
        $response = $this->actingAs($teacher)->post(route('teacher.peminjaman-guru.cart.update', $item->id), [
            'quantity' => 3,
        ]);

        $response->assertRedirect(route('teacher.peminjaman-guru.cart'));
        $response->assertSessionHas('success');
        $this->assertEquals(3, session('teacher_borrowing_cart')[$item->id]['quantity']);

        // Exceeding stock validation
        $responseFail = $this->actingAs($teacher)->post(route('teacher.peminjaman-guru.cart.update', $item->id), [
            'quantity' => 99,
        ]);

        $responseFail->assertSessionHasErrors('quantity');
        // Session remains 3
        $this->assertEquals(3, session('teacher_borrowing_cart')[$item->id]['quantity']);
    }

    public function test_magic_approval_guru_page_shows_multi_items_and_details(): void
    {
        $jurusan = \App\Models\Jurusan::firstOrCreate(
            ['nama' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'TKJ']
        );

        $kajur = User::factory()->create(['jurusan_id' => $jurusan->id]);
        $kajur->assignRole('kepala_jurusan');

        $teacher = User::factory()->create(['name' => 'Ibu Siti Guru', 'jurusan_id' => $jurusan->id]);
        $teacher->assignRole('guru');

        $cat1 = \App\Models\Category::firstOrCreate(['name' => 'Audio Visual']);
        $item1 = Item::create([
            'code' => 'K-101',
            'inventory_number' => 'INV-K-101',
            'name' => 'Audio.Microphone Wireless',
            'category_id' => $cat1->id,
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 10,
        ]);

        $item2 = Item::create([
            'code' => 'K-102',
            'inventory_number' => 'INV-K-102',
            'name' => 'Proyektor BenQ',
            'condition' => 'Baik',
            'status' => 'Tersedia',
            'stock' => 5,
        ]);

        $borrowingRequest = BorrowingRequest::create([
            'user_id' => $teacher->id,
            'approved_by_kajur_id' => $kajur->id,
            'purpose' => 'Praktikum TKJ di Lab 2',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'return_time' => '14:00:00',
            'status' => BorrowingRequest::STATUS_PENDING,
            'notes' => 'Membutuhkan kabel HDMI tambahan',
            'is_teacher_request' => true,
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowingRequest->id,
            'item_id' => $item1->id,
            'quantity' => 2,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $borrowingRequest->id,
            'item_id' => $item2->id,
            'quantity' => 1,
            'kondisi_saat_pinjam' => 'baik',
        ]);

        $signedUrl = \Illuminate\Support\Facades\URL::signedRoute('approval-guru.show', [
            'borrowingRequest' => $borrowingRequest->id,
        ]);

        $response = $this->actingAs($kajur)->get($signedUrl);

        $response->assertOk();
        $response->assertSee('Ibu Siti Guru');
        $response->assertSee('Microphone Wireless');
        $response->assertSee('Proyektor Benq');
        $response->assertSee('2 unit');
        $response->assertSee('1 unit');
        $response->assertSee('Praktikum TKJ di Lab 2');
        $response->assertSee('Membutuhkan kabel HDMI tambahan');
        $response->assertSee('Audio Visual');
    }
}

