<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\ItemReturn;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ItemReturnWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected User $admin;
    protected Item $item;
    protected BorrowingRequest $borrowing;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        $this->student = User::factory()->create(['name' => 'Siswa Test']);
        $this->student->assignRole('siswa');

        $this->admin = User::factory()->create(['name' => 'Admin Test']);
        $this->admin->assignRole('admin');

        $this->item = Item::create([
            'code' => 'TEST-001',
            'inventory_number' => 'INV-TEST-001',
            'name' => 'Laptop Asus ROG',
            'condition' => 'Baik',
            'status' => 'Dipinjam',
            'stock' => 5,
        ]);

        $this->borrowing = BorrowingRequest::create([
            'user_id' => $this->student->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'Praktikum Jaringan',
            'borrow_date' => now()->subDays(3)->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'status' => BorrowingRequest::STATUS_BORROWED,
            'borrowed_at' => now()->subDays(3),
        ]);
    }

    public function test_student_can_view_borrowed_items_page(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.returns.index'));
        $response->assertStatus(200);
        $response->assertSee('Laptop Asus ROG');
        $response->assertSee('Ajukan Pengembalian');
    }

    public function test_student_can_view_and_return_approved_borrowing_request(): void
    {
        $approvedBorrowing = BorrowingRequest::create([
            'user_id' => $this->student->id,
            'item_id' => $this->item->id,
            'quantity' => 1,
            'purpose' => 'Praktikum Robotik',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'status' => BorrowingRequest::STATUS_APPROVED,
            'approved_at' => now(),
        ]);

        $response = $this->actingAs($this->student)->get(route('student.returns.index'));
        $response->assertStatus(200);
        $response->assertSee('Laptop Asus ROG');

        $responseForm = $this->actingAs($this->student)->get(route('student.returns.create', $approvedBorrowing->id));
        $responseForm->assertStatus(200);

        $responseStore = $this->actingAs($this->student)->post(route('student.returns.store'), [
            'borrowing_request_id' => $approvedBorrowing->id,
            'kondisi_barang' => 'baik',
        ]);
        $responseStore->assertRedirect(route('student.returns.history'));
    }

    public function test_student_can_submit_return_request(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('bukti_rusak.jpg');

        $response = $this->actingAs($this->student)->post(route('student.returns.store'), [
            'borrowing_request_id' => $this->borrowing->id,
            'kondisi_barang' => 'rusak_ringan',
            'catatan' => 'Ada lecet sedikit di bagian cover bawah',
            'foto_bukti' => $file,
        ]);

        $response->assertRedirect(route('student.returns.history'));

        $this->assertDatabaseHas('item_returns', [
            'borrowing_request_id' => $this->borrowing->id,
            'user_id' => $this->student->id,
            'kondisi_barang' => 'rusak_ringan',
            'status' => 'menunggu',
        ]);

        // Status peminjaman belum berubah sebelum admin verifikasi
        $this->assertEquals(BorrowingRequest::STATUS_BORROWED, $this->borrowing->fresh()->status);
    }

    public function test_student_cannot_submit_duplicate_pending_return(): void
    {
        ItemReturn::create([
            'borrowing_request_id' => $this->borrowing->id,
            'user_id' => $this->student->id,
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);

        $response = $this->actingAs($this->student)->post(route('student.returns.store'), [
            'borrowing_request_id' => $this->borrowing->id,
            'kondisi_barang' => 'baik',
        ]);

        $response->assertSessionHas('error');
    }

    public function test_admin_can_approve_return_request(): void
    {
        $return = ItemReturn::create([
            'borrowing_request_id' => $this->borrowing->id,
            'user_id' => $this->student->id,
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.returns.approve', $return->id));
        $response->assertSessionHas('success');

        // Status return menjadi disetujui
        $this->assertEquals('disetujui', $return->fresh()->status);
        $this->assertEquals($this->admin->id, $return->fresh()->diverifikasi_oleh);

        // Status peminjaman menjadi returned
        $this->assertEquals(BorrowingRequest::STATUS_RETURNED, $this->borrowing->fresh()->status);

        // Status item menjadi Tersedia
        $this->assertEquals('Tersedia', $this->item->fresh()->status);

        // Siswa mendapat notifikasi
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->student->id,
            'type' => 'pengembalian_disetujui',
        ]);
    }

    public function test_admin_can_reject_return_request(): void
    {
        $return = ItemReturn::create([
            'borrowing_request_id' => $this->borrowing->id,
            'user_id' => $this->student->id,
            'kondisi_barang' => 'baik',
            'status' => ItemReturn::STATUS_MENUNGGU,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.returns.reject', $return->id), [
            'alasan_ditolak' => 'Kabel charger belum disertakan saat pengembalian.',
        ]);

        $response->assertSessionHas('success');

        // Status return menjadi ditolak
        $this->assertEquals('ditolak', $return->fresh()->status);
        $this->assertEquals('Kabel charger belum disertakan saat pengembalian.', $return->fresh()->alasan_ditolak);

        // Status peminjaman tetap borrowed
        $this->assertEquals(BorrowingRequest::STATUS_BORROWED, $this->borrowing->fresh()->status);

        // Siswa mendapat notifikasi penolakan
        $this->assertDatabaseHas('notifications', [
            'user_id' => $this->student->id,
            'type' => 'pengembalian_ditolak',
        ]);
    }
}
