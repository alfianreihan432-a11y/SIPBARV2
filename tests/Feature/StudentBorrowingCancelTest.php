<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentBorrowingCancelTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_cancel_pending_borrowing_request(): void
    {
        $teacher = User::factory()->create([
            'name' => 'Guru A',
            'phone' => '081234567890',
            'email' => 'guru@example.com',
        ]);

        $student = User::factory()->create([
            'name' => 'Siswa A',
            'phone' => '081111111111',
            'email' => 'siswa@example.com',
        ]);

        $item = Item::create([
            'code' => 'LAP-101',
            'inventory_number' => 'INV-101',
            'name' => 'Laptop',
            'stock' => 5,
            'status' => 'available',
            'teacher_id' => $teacher->id,
            'condition' => 'baik',
            'price' => 15000000,
        ]);

        $request = BorrowingRequest::create([
            'user_id' => $student->id,
            'item_id' => $item->id,
            'teacher_id' => $teacher->id,
            'quantity' => 1,
            'purpose' => 'Untuk praktikum',
            'borrow_date' => now()->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
            'status' => BorrowingRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($student)
            ->from(route('student.loans'))
            ->post(route('student.loans.cancel', $request->id));

        $response->assertRedirect(route('student.loans'));
        $response->assertSessionHas('success', 'Permohonan peminjaman berhasil dibatalkan.');

        $request->refresh();
        $this->assertSame(BorrowingRequest::STATUS_CANCELLED, $request->status);
    }
}
