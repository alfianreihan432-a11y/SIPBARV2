<?php

namespace Database\Seeders;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\User;
use App\Services\BorrowingApprovalService;
use App\Services\QRCodeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QREnhancementDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates demo data for testing QR enhancement features:
     * - Sample users (teacher, students)
     * - Sample items with stock
     * - Borrowing requests in various statuses
     * - QR codes for approved/borrowed requests
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding QR Enhancement Demo Data...');
        
        // 1. Create Demo Teacher
        $teacher = $this->createTeacher();
        $this->command->info('✓ Demo teacher created');
        
        // 2. Create Demo Students
        $students = $this->createStudents(5);
        $this->command->info('✓ Demo students created (5)');
        
        // 3. Create Demo Items
        $items = $this->createItems();
        $this->command->info('✓ Demo items created (5)');
        
        // 4. Create Borrowing Requests in Various Statuses
        $this->createBorrowingRequests($teacher, $students, $items);
        $this->command->info('✓ Borrowing requests created in various statuses');
        
        $this->command->info('🎉 QR Enhancement Demo Data seeded successfully!');
        $this->command->newLine();
        $this->command->info('Demo Accounts:');
        $this->command->info('Teacher: guru.demo@sipbar.test / password');
        $this->command->info('Student 1: siswa1@sipbar.test / password');
        $this->command->info('Student 2: siswa2@sipbar.test / password');
    }
    
    private function createTeacher(): User
    {
        return User::firstOrCreate(
            ['email' => 'guru.demo@sipbar.test'],
            [
                'name' => 'Pak Budi (Demo)',
                'password' => Hash::make('password'),
                'phone' => '6281234567890',
            ]
        );
    }
    
    private function createStudents(int $count): array
    {
        $students = [];
        $classes = ['X RPL 1', 'X RPL 2', 'XI RPL 1', 'XI RPL 2', 'XII RPL 1'];
        
        for ($i = 1; $i <= $count; $i++) {
            $students[] = User::firstOrCreate(
                ['email' => "siswa{$i}@sipbar.test"],
                [
                    'name' => "Siswa Demo {$i}",
                    'password' => Hash::make('password'),
                    'phone' => '628123456789' . $i,
                    'kelas' => $classes[array_rand($classes)],
                    'jurusan' => 'Rekayasa Perangkat Lunak',
                ]
            );
        }
        
        return $students;
    }
    
    private function createItems(): array
    {
        $itemsData = [
            ['name' => 'Proyektor LCD', 'stock' => 5],
            ['name' => 'Laptop ASUS', 'stock' => 10],
            ['name' => 'Kamera DSLR Canon', 'stock' => 3],
            ['name' => 'Bola Basket', 'stock' => 20],
            ['name' => 'Mikrofon Wireless', 'stock' => 8],
        ];
        
        $items = [];
        foreach ($itemsData as $data) {
            $items[] = Item::firstOrCreate(
                ['name' => $data['name']],
                ['stock' => $data['stock']]
            );
        }
        
        return $items;
    }
    
    private function createBorrowingRequests(User $teacher, array $students, array $items): void
    {
        $qrService = app(QRCodeService::class);
        $approvalService = app(BorrowingApprovalService::class);
        
        // 1. PENDING - Menunggu persetujuan
        BorrowingRequest::create([
            'user_id' => $students[0]->id,
            'teacher_id' => $teacher->id,
            'item_id' => $items[0]->id,
            'quantity' => 1,
            'purpose' => 'Presentasi tugas akhir',
            'borrow_date' => now()->addDay(),
            'return_date' => now()->addDays(3),
            'status' => BorrowingRequest::STATUS_PENDING,
        ]);
        
        BorrowingRequest::create([
            'user_id' => $students[1]->id,
            'teacher_id' => $teacher->id,
            'item_id' => $items[1]->id,
            'quantity' => 2,
            'purpose' => 'Praktikum pemrograman web',
            'borrow_date' => now()->addDay(),
            'return_date' => now()->addDays(5),
            'status' => BorrowingRequest::STATUS_PENDING,
        ]);
        
        // 2. APPROVED - Disetujui, sudah ada QR
        $approvedRequest = BorrowingRequest::create([
            'user_id' => $students[2]->id,
            'teacher_id' => $teacher->id,
            'item_id' => $items[2]->id,
            'quantity' => 1,
            'purpose' => 'Dokumentasi acara sekolah',
            'borrow_date' => now(),
            'return_date' => now()->addDays(2),
            'status' => BorrowingRequest::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
        $qrService->generateForRequest($approvedRequest);
        
        // 3. BORROWED - Sedang dipinjam
        $borrowedRequest = BorrowingRequest::create([
            'user_id' => $students[3]->id,
            'teacher_id' => $teacher->id,
            'item_id' => $items[3]->id,
            'quantity' => 5,
            'purpose' => 'Latihan basket ekstrakurikuler',
            'borrow_date' => now()->subDay(),
            'return_date' => now()->addDay(), // Due tomorrow (for reminder test)
            'status' => BorrowingRequest::STATUS_BORROWED,
            'approved_at' => now()->subDay(),
            'borrowed_at' => now()->subDay(),
            'checkout_by' => $teacher->id,
        ]);
        $qrService->generateForRequest($borrowedRequest);
        
        // 4. RETURNED - Sudah dikembalikan
        $returnedRequest = BorrowingRequest::create([
            'user_id' => $students[4]->id,
            'teacher_id' => $teacher->id,
            'item_id' => $items[4]->id,
            'quantity' => 2,
            'purpose' => 'Acara seminar',
            'borrow_date' => now()->subDays(5),
            'return_date' => now()->subDay(),
            'status' => BorrowingRequest::STATUS_RETURNED,
            'approved_at' => now()->subDays(5),
            'borrowed_at' => now()->subDays(5),
            'returned_at' => now()->subDay(),
            'checkout_by' => $teacher->id,
            'checkin_by' => $teacher->id,
            'return_condition' => 'good',
            'return_notes' => 'Barang dikembalikan dalam kondisi baik',
        ]);
        $qrService->generateForRequest($returnedRequest);
        
        // 5. REJECTED - Ditolak
        BorrowingRequest::create([
            'user_id' => $students[0]->id,
            'teacher_id' => $teacher->id,
            'item_id' => $items[0]->id,
            'quantity' => 3,
            'purpose' => 'Kegiatan pribadi',
            'borrow_date' => now()->addDay(),
            'return_date' => now()->addDays(7),
            'status' => BorrowingRequest::STATUS_REJECTED,
            'rejection_reason' => 'Proyektor sedang digunakan untuk ujian semester',
        ]);
    }
}
