<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\BorrowingRequestItem;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class StudentLoansPageTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private Category $category;
    private Location $location;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        $this->category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $this->location = Location::create([
            'building' => 'Gedung Utama',
            'floor'    => '1',
            'room'     => 'Lab Komputer',
        ]);

        $this->student = User::factory()->create([
            'email'    => 'student-test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->student->assignRole('siswa');
    }

    public function test_cart_multi_item_loan_displays_real_item_names(): void
    {
        // Item 1
        $item1 = Item::create([
            'name'             => 'Meja Kursi Belajar',
            'code'             => 'MJK-001',
            'inventory_number' => 'INV-MJK-001',
            'category_id'      => $this->category->id,
            'location_id'      => $this->location->id,
            'stock'            => 10,
            'status'           => 'Tersedia',
            'condition'        => 'Baik',
        ]);

        // Peminjaman via keranjang (item_id pada borrowing_requests adalah NULL)
        $br = BorrowingRequest::create([
            'user_id'     => $this->student->id,
            'item_id'     => null, // Peminjaman baru selalu null
            'status'      => 'pending',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'purpose'     => 'Kegiatan belajar',
        ]);

        BorrowingRequestItem::create([
            'borrowing_request_id' => $br->id,
            'item_id'              => $item1->id,
            'quantity'             => 2,
        ]);

        // Akses halaman /siswa/peminjaman
        $response = $this->actingAs($this->student)->get(route('student.loans'));

        $response->assertStatus(200);
        // Harus menampilkan nama barang asli, BUKAN "Barang tidak tersedia"
        $response->assertSee('Meja Kursi Belajar');
        $response->assertDontSee('Barang tidak tersedia');
        $response->assertSee('s-loans-grid');
        $response->assertSee('s-loan-card');
    }

    public function test_legacy_soft_deleted_item_displays_original_name(): void
    {
        // Item lama yang sudah di-soft-delete
        $item = Item::create([
            'name'             => 'Mic Portable Jadul',
            'code'             => 'MIC-001',
            'inventory_number' => 'INV-MIC-001',
            'category_id'      => $this->category->id,
            'location_id'      => $this->location->id,
            'stock'            => 1,
            'status'           => 'Tersedia',
            'condition'        => 'Baik',
        ]);
        $item->delete(); // Soft delete item

        // Peminjaman legacy (item_id terisi langsung)
        $br = BorrowingRequest::create([
            'user_id'     => $this->student->id,
            'item_id'     => $item->id,
            'quantity'    => 1,
            'status'      => 'returned',
            'borrow_date' => now()->subDays(10)->toDateString(),
            'return_date' => now()->subDays(7)->toDateString(),
            'purpose'     => 'Upacara bendera',
        ]);

        $response = $this->actingAs($this->student)->get(route('student.loans'));

        $response->assertStatus(200);
        // Harus tetap menampilkan nama asli barang meskipun sudah di-soft-delete
        $response->assertSee('Mic Portable Jadul');
        $response->assertDontSee('Barang tidak tersedia');
    }
}
