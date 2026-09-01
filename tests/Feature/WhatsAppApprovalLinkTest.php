<?php

namespace Tests\Feature;

use App\Models\BorrowingRequest;
use App\Models\Item;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class WhatsAppApprovalLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_receives_signed_approval_link_in_wa_notification(): void
    {
        config()->set('services.whatsapp.base_url', 'https://wa-bot.test');
        config()->set('services.whatsapp.api_key', 'test-key');

        $teacher = User::factory()->create([
            'name' => 'Guru Pembimbing',
            'phone' => '081234567890',
            'email' => 'guru@example.com',
        ]);

        $student = User::factory()->create([
            'name' => 'Siswa A',
            'phone' => '081111111111',
            'email' => 'siswa@example.com',
        ]);

        $item = Item::create([
            'code' => 'LAP-001',
            'inventory_number' => 'INV-001',
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

        Http::fake([
            'https://wa-bot.test/*' => Http::response(['status' => 'ok'], 200),
        ]);

        app(WhatsAppNotificationService::class)->notifyNewRequest($request);

        $waLink = app(WhatsAppNotificationService::class)->getDirectWaLink($request);

        $this->assertNotEmpty($waLink);
        $this->assertStringContainsString('https://api.whatsapp.com/send', $waLink);
        $this->assertStringContainsString('phone=', $waLink);
        $this->assertStringContainsString('text=', $waLink);
        $this->assertStringContainsString('approval', strtolower($waLink));
        Http::assertNothingSent();
    }

    public function test_student_receives_whatsapp_message_when_api_is_configured(): void
    {
        config()->set('services.whatsapp.base_url', 'https://wa-bot.test');
        config()->set('services.whatsapp.api_key', 'test-key');

        $teacher = User::factory()->create([
            'name' => 'Guru Pembimbing',
            'phone' => '081234567890',
            'email' => 'guru@example.com',
        ]);

        $student = User::factory()->create([
            'name' => 'Siswa A',
            'phone' => '081111111111',
            'email' => 'siswa@example.com',
        ]);

        $item = Item::create([
            'code' => 'LAP-002',
            'inventory_number' => 'INV-002',
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
            'status' => BorrowingRequest::STATUS_APPROVED,
        ]);

        Http::fake([
            '*wa-bot.test*' => Http::response(['status' => 'sent'], 200),
        ]);

        $service = new WhatsAppNotificationService();
        $service->notifyApproved($request, 'dummy-qr');

        Http::assertSentCount(1);
        Http::assertSent(function ($httpRequest) {
            return str_contains($httpRequest->url(), 'wa-bot.test')
                && ($httpRequest['to'] ?? null) === '628111111111'
                && str_contains($httpRequest['message'], 'Pengajuan peminjaman Anda telah disetujui');
        });
    }
}
