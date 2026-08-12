# Technical Design: SIPBAR QR Enhancement & WhatsApp Integration

**Project**: SIPBARV2 Enhancement  
**Version**: 1.0  
**Date**: 2026-08-12  
**Status**: Draft

---

## Table of Contents
1. [Architecture Overview](#architecture-overview)
2. [Database Schema Changes](#database-schema-changes)
3. [State Machine Implementation](#state-machine-implementation)
4. [API Endpoints & Routes](#api-endpoints--routes)
5. [Service Layer Design](#service-layer-design)
6. [Frontend Components](#frontend-components)
7. [Integration Patterns](#integration-patterns)
8. [Security Considerations](#security-considerations)
9. [Testing Strategy](#testing-strategy)

---

## 1. Architecture Overview

### 1.1 System Components

```
┌─────────────────────────────────────────────────────────────────┐
│                         SIPBARV2 Enhanced                        │
│                     (Laravel 11 Application)                     │
└─────────────────────────────────────────────────────────────────┘
                                │
                ┌───────────────┼───────────────┐
                │               │               │
                ↓               ↓               ↓
        ┌───────────┐   ┌──────────┐   ┌──────────────┐
        │  Student  │   │  Teacher │   │    Admin     │
        │   Portal  │   │  Portal  │   │   Portal     │
        └───────────┘   └──────────┘   └──────────────┘
                                │
                        ┌───────┴───────┐
                        │               │
                        ↓               ↓
                ┌───────────────┐   ┌──────────────┐
                │  QR Scanner   │   │  History &   │
                │  (Livewire)   │   │   Search     │
                └───────────────┘   └──────────────┘
                        │               │
                        └───────┬───────┘
                                │
                                ↓
                    ┌───────────────────────┐
                    │   Core Services       │
                    ├───────────────────────┤
                    │ • BorrowingService    │
                    │ • QRCodeService       │
                    │ • WhatsAppService     │
                    │ • NotificationService │
                    └───────────────────────┘
                                │
                ┌───────────────┼───────────────┐
                │               │               │
                ↓               ↓               ↓
        ┌──────────┐    ┌──────────┐   ┌──────────────┐
        │  MySQL   │    │ Laravel  │   │  WhatsApp    │
        │ Database │    │  Queue   │   │  Bot Service │
        │          │    │ (Jobs)   │   │  (External)  │
        └──────────┘    └──────────┘   └──────────────┘
                                                │
                                        ┌───────┴───────┐
                                        │  HTTP Client  │
                                        │  (Guzzle)     │
                                        └───────────────┘
```

### 1.2 Technology Stack

**Backend:**
- Laravel 11.x
- PHP 8.2+
- MySQL 8.0+

**Frontend:**
- Blade Templates
- Livewire 3.x (for QR Scanner)
- TailwindCSS 3.x
- html5-qrcode.js (QR scanning)

**External Integrations:**
- WhatsApp Bot Service (Node.js microservice)
- Laravel HTTP Client

**Task Scheduling:**
- Laravel Scheduler (Cron)

---

## 2. Database Schema Changes

### 2.1 Migration: Add Columns to `borrowing_requests`

**File**: `database/migrations/2026_08_12_100000_enhance_borrowing_requests_for_qr_workflow.php`

```php
public function up(): void
{
    Schema::table('borrowing_requests', function (Blueprint $table) {
        // QR Token (denormalized for fast lookup)
        $table->string('qr_token', 64)->nullable()->unique()
            ->after('rejection_reason');
        
        // H-1 Reminder tracking
        $table->timestamp('reminder_sent_at')->nullable()
            ->after('qr_token');
        
        // Track who performed checkout/checkin
        $table->foreignId('checkout_by')->nullable()
            ->after('borrowed_at')
            ->constrained('users')
            ->onDelete('set null');
        
        $table->foreignId('checkin_by')->nullable()
            ->after('returned_at')
            ->constrained('users')
            ->onDelete('set null');
        
        // Indexes for performance
        $table->index(['status', 'return_date'], 'idx_status_return_date');
        $table->index('qr_token', 'idx_qr_token');
    });
}

public function down(): void
{
    Schema::table('borrowing_requests', function (Blueprint $table) {
        $table->dropForeign(['checkout_by']);
        $table->dropForeign(['checkin_by']);
        $table->dropIndex('idx_status_return_date');
        $table->dropIndex('idx_qr_token');
        $table->dropColumn([
            'qr_token',
            'reminder_sent_at',
            'checkout_by',
            'checkin_by'
        ]);
    });
}
```

### 2.2 Migration: Enhance `qr_codes` Table

**File**: `database/migrations/2026_08_12_100100_enhance_qr_codes_table.php`

```php
public function up(): void
{
    Schema::table('qr_codes', function (Blueprint $table) {
        // Track scan count for analytics
        $table->integer('scan_count')->default(0)
            ->after('scanned_at');
        
        // Rename scanned_at to first_scanned_at for clarity
        $table->renameColumn('scanned_at', 'first_scanned_at');
        
        // Track last scan
        $table->timestamp('last_scanned_at')->nullable()
            ->after('scan_count');
    });
}

public function down(): void
{
    Schema::table('qr_codes', function (Blueprint $table) {
        $table->renameColumn('first_scanned_at', 'scanned_at');
        $table->dropColumn(['scan_count', 'last_scanned_at']);
    });
}
```

### 2.3 Migration: Create `whatsapp_notification_logs` Table

**File**: `database/migrations/2026_08_12_100200_create_whatsapp_notification_logs_table.php`

```php
public function up(): void
{
    Schema::create('whatsapp_notification_logs', function (Blueprint $table) {
        $table->id();
        
        // Link to borrowing request
        $table->foreignId('borrowing_request_id')
            ->nullable()
            ->constrained()
            ->onDelete('cascade');
        
        // Notification metadata
        $table->enum('notification_type', [
            'pengajuan_baru',
            'ditolak',
            'disetujui',
            'reminder_h1',
            'dikembalikan'
        ]);
        
        $table->string('recipient_phone', 20);
        $table->json('payload'); // Full payload sent
        
        // Response tracking
        $table->enum('status', ['pending', 'success', 'failed'])
            ->default('pending');
        $table->integer('http_status_code')->nullable();
        $table->text('error_message')->nullable();
        
        $table->timestamp('sent_at')->nullable();
        $table->timestamps();
        
        // Indexes
        $table->index('borrowing_request_id');
        $table->index(['notification_type', 'status']);
    });
}

public function down(): void
{
    Schema::dropIfExists('whatsapp_notification_logs');
}
```

### 2.4 Updated Entity Relationship Diagram

```
┌─────────────┐          ┌──────────────────┐          ┌─────────┐
│    users    │◄─────────│ borrowing_       │─────────►│  items  │
│             │          │ requests         │          │         │
│ - id        │  1    M  │                  │  M    1  │ - id    │
│ - name      │          │ - id             │          │ - name  │
│ - role      │          │ - user_id        │          │ - stock │
│ - phone     │          │ - item_id        │          └─────────┘
└─────────────┘          │ - teacher_id     │
      ▲                  │ - status         │
      │                  │ - qr_token  ←────┼──┐
      │                  │ - reminder_sent  │  │
      │  checkout_by     │ - checkout_by    │  │
      │  checkin_by      │ - checkin_by     │  │
      └──────────────────┤                  │  │
                         └──────────────────┘  │
                                  │            │
                                  │ 1          │
                                  │            │
                                  │ 1          │ Denormalized
                                  ↓            │ for fast lookup
                         ┌──────────────────┐  │
                         │    qr_codes      │  │
                         │                  │  │
                         │ - id             │  │
                         │ - borrowing_req  │  │
                         │ - code    ←──────┼──┘
                         │ - scan_count     │
                         │ - first_scanned  │
                         │ - last_scanned   │
                         └──────────────────┘
                                  ▲
                                  │
                                  │
                                  │ M
                         ┌──────────────────────┐
                         │ whatsapp_           │
                         │ notification_logs   │
                         │                      │
                         │ - id                 │
                         │ - borrowing_req_id   │
                         │ - notification_type  │
                         │ - recipient_phone    │
                         │ - payload (JSON)     │
                         │ - status             │
                         └──────────────────────┘
```

---

## 3. State Machine Implementation

### 3.1 Status Enum Definition

We'll keep existing English status names for backward compatibility:

```php
// In BorrowingRequest model
public const STATUS_PENDING = 'pending';
public const STATUS_APPROVED = 'approved';
public const STATUS_REJECTED = 'rejected';
public const STATUS_BORROWED = 'borrowed';
public const STATUS_RETURNED = 'returned';
public const STATUS_OVERDUE = 'overdue';

// Remove 'qr_ready' - merge into 'approved'
```

### 3.2 State Transition Class

**File**: `app/Services/BorrowingStateMachine.php`

```php
<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Exceptions\InvalidStateTransitionException;

class BorrowingStateMachine
{
    public function canTransitionTo(BorrowingRequest $request, string $newStatus): bool
    {
        $transitions = [
            BorrowingRequest::STATUS_PENDING => [
                BorrowingRequest::STATUS_APPROVED,
                BorrowingRequest::STATUS_REJECTED,
            ],
            BorrowingRequest::STATUS_APPROVED => [
                BorrowingRequest::STATUS_BORROWED,
            ],
            BorrowingRequest::STATUS_BORROWED => [
                BorrowingRequest::STATUS_RETURNED,
                BorrowingRequest::STATUS_OVERDUE,
            ],
            // Terminal states: rejected, returned
        ];

        $currentStatus = $request->status;
        $allowedTransitions = $transitions[$currentStatus] ?? [];

        return in_array($newStatus, $allowedTransitions);
    }

    public function transitionTo(
        BorrowingRequest $request,
        string $newStatus,
        ?int $performedBy = null
    ): void {
        if (!$this->canTransitionTo($request, $newStatus)) {
            throw new InvalidStateTransitionException(
                "Cannot transition from {$request->status} to {$newStatus}"
            );
        }

        $request->status = $newStatus;

        // Set timestamps based on new status
        match($newStatus) {
            BorrowingRequest::STATUS_APPROVED => $request->approved_at = now(),
            BorrowingRequest::STATUS_BORROWED => [
                $request->borrowed_at = now(),
                $request->checkout_by = $performedBy,
            ],
            BorrowingRequest::STATUS_RETURNED => [
                $request->returned_at = now(),
                $request->checkin_by = $performedBy,
            ],
            default => null,
        };

        $request->save();
    }
}
```

### 3.3 State Machine Diagram (Code Flow)

```
Student Submit Form
        │
        ↓
    transitionTo(STATUS_PENDING)  ← Initial
        │
        ↓
    [Teacher Review]
        │
        ├──→ transitionTo(STATUS_REJECTED, reason)  [TERMINAL]
        │
        └──→ transitionTo(STATUS_APPROVED)  ← Generate QR here
                 │
                 ↓
            [QR Scan #1]
                 │
                 ↓
            transitionTo(STATUS_BORROWED, checkout_by)
                 │
                 ↓
            [QR Scan #2]
                 │
                 ↓
            transitionTo(STATUS_RETURNED, checkin_by)  [TERMINAL]
```

---

## 4. API Endpoints & Routes

### 4.1 Student Routes

```php
// routes/web.php

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('student.')->group(function () {
    // Borrowing request submission
    Route::get('/peminjaman/create', [BorrowingRequestController::class, 'create'])
        ->name('borrowing.create');
    
    Route::post('/peminjaman', [BorrowingRequestController::class, 'store'])
        ->name('borrowing.store');
    
    // View own borrowing history
    Route::get('/riwayat', [BorrowingRequestController::class, 'studentHistory'])
        ->name('history');
});
```

### 4.2 Teacher Routes

```php
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('teacher.')->group(function () {
    // Approval workflow
    Route::get('/permohonan', [TeacherApprovalController::class, 'index'])
        ->name('requests');
    
    Route::post('/permohonan/{id}/approve', [TeacherApprovalController::class, 'approve'])
        ->name('requests.approve');
    
    Route::post('/permohonan/{id}/reject', [TeacherApprovalController::class, 'reject'])
        ->name('requests.reject');
    
    // QR Scanner (Livewire component)
    Route::get('/qr/scan', function () {
        return view('livewire.qr-scanner');
    })->name('qr.scanner');
    
    // QR Scan API (called by Livewire)
    Route::post('/qr/process', [QRScanController::class, 'process'])
        ->name('qr.process');
});
```

### 4.3 Admin Routes

```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Transaction history & search
    Route::get('/transactions', [TransactionHistoryController::class, 'index'])
        ->name('transactions.index');
    
    Route::get('/transactions/export', [TransactionHistoryController::class, 'export'])
        ->name('transactions.export');
    
    // WhatsApp notification logs
    Route::get('/notifications/logs', [WhatsAppLogController::class, 'index'])
        ->name('notifications.logs');
    
    // Resend failed notification (optional)
    Route::post('/notifications/{id}/retry', [WhatsAppLogController::class, 'retry'])
        ->name('notifications.retry');
});
```

### 4.4 API Response Structure

**Success Response:**
```json
{
    "success": true,
    "message": "Permintaan peminjaman berhasil dibuat",
    "data": {
        "id": 123,
        "reference_number": "BRW-2026-08-00123",
        "status": "pending"
    }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Stok barang tidak mencukupi",
    "errors": {
        "item_id": ["Stok tersisa: 0, diminta: 1"]
    }
}
```

---

## 5. Service Layer Design

### 5.1 QRCodeService

**File**: `app/Services/QRCodeService.php`

```php
<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\QRCode;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    public function generateForRequest(BorrowingRequest $request): QRCode
    {
        // Generate cryptographically secure token
        $token = Str::uuid()->toString();
        
        // Create QR code image
        $qrImage = QrCodeFacade::format('png')
            ->size(400)
            ->margin(2)
            ->generate($token);
        
        // Store image
        $filename = "qr-codes/{$request->id}_{$token}.png";
        Storage::disk('public')->put($filename, $qrImage);
        
        // Create QR code record
        $qrCode = QRCode::create([
            'borrowing_request_id' => $request->id,
            'code' => $token,
            'data' => json_encode([
                'borrowing_request_id' => $request->id,
                'student_name' => $request->user->name,
                'item_name' => $request->item->name,
            ]),
            'image_path' => $filename,
            'is_active' => true,
        ]);
        
        // Denormalize token to borrowing_requests for fast lookup
        $request->update(['qr_token' => $token]);
        
        return $qrCode;
    }
    
    public function getImageBase64(QRCode $qrCode): string
    {
        $imagePath = Storage::disk('public')->path($qrCode->image_path);
        $imageData = file_get_contents($imagePath);
        return base64_encode($imageData);
    }
    
    public function findByToken(string $token): ?BorrowingRequest
    {
        return BorrowingRequest::where('qr_token', $token)
            ->with(['user', 'item', 'teacher', 'qrCode'])
            ->first();
    }
    
    public function recordScan(QRCode $qrCode): void
    {
        $qrCode->increment('scan_count');
        $qrCode->update([
            'last_scanned_at' => now(),
            'first_scanned_at' => $qrCode->first_scanned_at ?? now(),
        ]);
    }
}
```

### 5.2 WhatsAppNotificationService

**File**: `app/Services/WhatsAppNotificationService.php`

```php
<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\WhatsAppNotificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    private string $baseUrl;
    private string $apiKey;
    private int $timeout = 10;
    
    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.base_url');
        $this->apiKey = config('services.whatsapp.api_key');
    }
    
    public function notifyNewRequest(BorrowingRequest $request): void
    {
        $payload = [
            'nomorGuru' => $request->teacher->phone ?? '',
            'namaSiswa' => $request->user->name,
            'kelas' => $request->user->kelas ?? '',
            'barang' => $request->item->name,
            'jumlah' => $request->quantity,
            'tglPinjam' => $request->borrow_date->format('d-m-Y'),
            'tglKembali' => $request->return_date->format('d-m-Y'),
            'keperluan' => $request->purpose,
            'linkKeputusan' => route('teacher.requests'),
        ];
        
        $this->sendNotification(
            $request->id,
            'pengajuan_baru',
            $request->teacher->phone ?? '',
            $payload
        );
    }
    
    public function notifyRejected(BorrowingRequest $request): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'alasan' => $request->rejection_reason,
        ];
        
        $this->sendNotification(
            $request->id,
            'ditolak',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    public function notifyApproved(BorrowingRequest $request, string $qrBase64): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'tglKembali' => $request->return_date->format('d-m-Y'),
            'qrBase64' => $qrBase64,
        ];
        
        $this->sendNotification(
            $request->id,
            'disetujui',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    public function notifyReminder(BorrowingRequest $request): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'tglKembali' => $request->return_date->format('d-m-Y'),
        ];
        
        $this->sendNotification(
            $request->id,
            'reminder_h1',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    public function notifyReturned(BorrowingRequest $request): void
    {
        $payload = [
            'nomorSiswa' => $request->user->phone ?? '',
            'namaSiswa' => $request->user->name,
            'barang' => $request->item->name,
            'waktuKembali' => $request->returned_at->format('d-m-Y H:i'),
        ];
        
        $this->sendNotification(
            $request->id,
            'dikembalikan',
            $request->user->phone ?? '',
            $payload
        );
    }
    
    private function sendNotification(
        int $borrowingRequestId,
        string $type,
        string $recipientPhone,
        array $payload
    ): void {
        // Create log entry
        $log = WhatsAppNotificationLog::create([
            'borrowing_request_id' => $borrowingRequestId,
            'notification_type' => $type,
            'recipient_phone' => $recipientPhone,
            'payload' => $payload,
            'status' => 'pending',
        ]);
        
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->post("{$this->baseUrl}/notify/{$type}", $payload);
            
            $log->update([
                'status' => $response->successful() ? 'success' : 'failed',
                'http_status_code' => $response->status(),
                'error_message' => $response->successful() ? null : $response->body(),
                'sent_at' => now(),
            ]);
            
            if (!$response->successful()) {
                Log::error("WhatsApp notification failed: {$type}", [
                    'log_id' => $log->id,
                    'status_code' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
            
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            Log::error("WhatsApp notification exception: {$type}", [
                'log_id' => $log->id,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
    
    public function checkBotStatus(): array
    {
        try {
            $response = Http::timeout(5)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->get("{$this->baseUrl}/status");
            
            return [
                'online' => $response->successful(),
                'status_code' => $response->status(),
                'message' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'online' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
```

### 5.3 BorrowingApprovalService

**File**: `app/Services/BorrowingApprovalService.php`

```php
<?php

namespace App\Services;

use App\Models\BorrowingRequest;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use App\Exceptions\InsufficientStockException;

class BorrowingApprovalService
{
    public function __construct(
        private QRCodeService $qrCodeService,
        private WhatsAppNotificationService $whatsAppService,
        private BorrowingStateMachine $stateMachine
    ) {}
    
    public function approve(BorrowingRequest $request, int $teacherId): void
    {
        DB::transaction(function () use ($request, $teacherId) {
            // 1. Validate stock
            $this->validateStock($request);
            
            // 2. Update status
            $this->stateMachine->transitionTo(
                $request,
                BorrowingRequest::STATUS_APPROVED,
                $teacherId
            );
            
            // 3. Generate QR code
            $qrCode = $this->qrCodeService->generateForRequest($request);
            
            // 4. Send WhatsApp notification (non-blocking)
            try {
                $qrBase64 = $this->qrCodeService->getImageBase64($qrCode);
                $this->whatsAppService->notifyApproved($request, $qrBase64);
            } catch (\Exception $e) {
                // Log but don't fail transaction
                \Log::error('WhatsApp notification failed during approval', [
                    'request_id' => $request->id,
                    'error' => $e->getMessage()
                ]);
            }
        });
    }
    
    public function reject(BorrowingRequest $request, string $reason, int $teacherId): void
    {
        DB::transaction(function () use ($request, $reason, $teacherId) {
            // 1. Update status and reason
            $request->rejection_reason = $reason;
            $this->stateMachine->transitionTo(
                $request,
                BorrowingRequest::STATUS_REJECTED,
                $teacherId
            );
            
            // 2. Send WhatsApp notification (non-blocking)
            try {
                $this->whatsAppService->notifyRejected($request);
            } catch (\Exception $e) {
                \Log::error('WhatsApp notification failed during rejection', [
                    'request_id' => $request->id,
                    'error' => $e->getMessage()
                ]);
            }
        });
    }
    
    private function validateStock(BorrowingRequest $request): void
    {
        $item = $request->item;
        
        // Calculate available stock
        $reservedStock = BorrowingRequest::whereIn('status', [
            BorrowingRequest::STATUS_APPROVED,
            BorrowingRequest::STATUS_BORROWED
        ])
        ->where('item_id', $item->id)
        ->sum('quantity');
        
        $availableStock = $item->stock - $reservedStock;
        
        if ($availableStock < $request->quantity) {
            throw new InsufficientStockException(
                "Stok tidak mencukupi. Tersedia: {$availableStock}, Diminta: {$request->quantity}"
            );
        }
    }
}
```

---

## 6. Frontend Components

### 6.1 QR Scanner (Livewire Component)

**File**: `app/Livewire/QRScanner.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\QRCodeService;
use App\Services\BorrowingStateMachine;
use App\Models\BorrowingRequest;

class QRScanner extends Component
{
    public ?string $scannedToken = null;
    public ?BorrowingRequest $transaction = null;
    public ?string $action = null; // 'checkout', 'checkin', 'readonly'
    public ?string $error = null;
    
    // For checkin form
    public ?string $returnCondition = null;
    public ?string $returnNotes = null;
    
    public function processQR(string $token)
    {
        $this->reset(['transaction', 'action', 'error']);
        $this->scannedToken = $token;
        
        $qrCodeService = app(QRCodeService::class);
        $transaction = $qrCodeService->findByToken($token);
        
        if (!$transaction) {
            $this->error = 'QR Code tidak valid atau tidak terdaftar dalam sistem';
            return;
        }
        
        $this->transaction = $transaction;
        
        // Record scan
        if ($transaction->qrCode) {
            $qrCodeService->recordScan($transaction->qrCode);
        }
        
        // Determine action based on status
        match($transaction->status) {
            BorrowingRequest::STATUS_APPROVED => $this->action = 'checkout',
            BorrowingRequest::STATUS_BORROWED => $this->action = 'checkin',
            default => $this->action = 'readonly',
        };
    }
    
    public function confirmCheckout()
    {
        $stateMachine = app(BorrowingStateMachine::class);
        
        try {
            $stateMachine->transitionTo(
                $this->transaction,
                BorrowingRequest::STATUS_BORROWED,
                auth()->id()
            );
            
            session()->flash('success', "Barang berhasil diserahkan kepada {$this->transaction->user->name}");
            $this->reset();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
    
    public function confirmCheckin()
    {
        $this->validate([
            'returnCondition' => 'required|in:good,damaged,lost',
        ]);
        
        $stateMachine = app(BorrowingStateMachine::class);
        $whatsappService = app(\App\Services\WhatsAppNotificationService::class);
        
        try {
            $this->transaction->update([
                'return_condition' => $this->returnCondition,
                'return_notes' => $this->returnNotes,
            ]);
            
            $stateMachine->transitionTo(
                $this->transaction,
                BorrowingRequest::STATUS_RETURNED,
                auth()->id()
            );
            
            // Send WhatsApp notification
            try {
                $whatsappService->notifyReturned($this->transaction);
            } catch (\Exception $e) {
                \Log::error('WhatsApp notification failed during checkin', [
                    'request_id' => $this->transaction->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            session()->flash('success', 'Pengembalian barang berhasil dicatat');
            $this->reset();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
    
    public function render()
    {
        return view('livewire.qr-scanner');
    }
}
```

### 6.2 QR Scanner Blade View

**File**: `resources/views/livewire/qr-scanner.blade.php`

```html
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Scanner QR Code Peminjaman</h1>
    
    {{-- Camera Scanner --}}
    @if(!$transaction)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div id="qr-reader" class="w-full"></div>
        <p class="text-sm text-gray-600 mt-4">
            Arahkan kamera ke QR Code untuk memindai
        </p>
    </div>
    @endif
    
    {{-- Error Message --}}
    @if($error)
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        {{ $error }}
        <button wire:click="$set('error', null)" class="float-right">×</button>
    </div>
    @endif
    
    {{-- Transaction Details --}}
    @if($transaction)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Detail Transaksi</h2>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nama Siswa</p>
                <p class="font-semibold">{{ $transaction->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kelas</p>
                <p class="font-semibold">{{ $transaction->user->kelas ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Barang</p>
                <p class="font-semibold">{{ $transaction->item->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jumlah</p>
                <p class="font-semibold">{{ $transaction->quantity }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Pinjam</p>
                <p class="font-semibold">{{ $transaction->borrow_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Kembali</p>
                <p class="font-semibold">{{ $transaction->return_date->format('d M Y') }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-600">Keperluan</p>
                <p class="font-semibold">{{ $transaction->purpose }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    @if($transaction->status === 'approved') bg-blue-100 text-blue-800
                    @elseif($transaction->status === 'borrowed') bg-yellow-100 text-yellow-800
                    @elseif($transaction->status === 'returned') bg-green-100 text-green-800
                    @endif">
                    {{ $transaction->status_label }}
                </span>
            </div>
        </div>
        
        {{-- Actions based on status --}}
        @if($action === 'checkout')
        <div class="mt-6">
            <button wire:click="confirmCheckout" 
                class="w-full bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                Konfirmasi Serah Terima Barang
            </button>
        </div>
        @elseif($action === 'checkin')
        <div class="mt-6">
            <h3 class="font-semibold mb-4">Form Pengembalian</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Kondisi Barang *</label>
                    <select wire:model="returnCondition" class="w-full border rounded-lg px-4 py-2">
                        <option value="">Pilih kondisi</option>
                        <option value="good">Baik</option>
                        <option value="damaged">Rusak</option>
                        <option value="lost">Hilang</option>
                    </select>
                    @error('returnCondition') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Catatan (Opsional)</label>
                    <textarea wire:model="returnNotes" class="w-full border rounded-lg px-4 py-2" rows="3"></textarea>
                </div>
                <button wire:click="confirmCheckin" 
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">
                    Konfirmasi Pengembalian
                </button>
            </div>
        </div>
        @elseif($action === 'readonly')
        <div class="mt-6 bg-gray-100 p-4 rounded-lg">
            <p class="text-gray-700">Transaksi ini sudah selesai dan tidak dapat diubah.</p>
            @if($transaction->status === 'rejected')
                <p class="mt-2"><strong>Alasan Penolakan:</strong> {{ $transaction->rejection_reason }}</p>
            @elseif($transaction->status === 'returned')
                <p class="mt-2"><strong>Kondisi Pengembalian:</strong> {{ ucfirst($transaction->return_condition) }}</p>
                @if($transaction->return_notes)
                    <p class="mt-1"><strong>Catatan:</strong> {{ $transaction->return_notes }}</p>
                @endif
            @endif
        </div>
        @endif
        
        <button wire:click="$set('transaction', null)" class="mt-4 text-blue-600 hover:underline">
            ← Scan QR Lain
        </button>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let html5QrcodeScanner = null;
    
    function initScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear();
        }
        
        html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            false
        );
        
        html5QrcodeScanner.render((decodedText) => {
            @this.call('processQR', decodedText);
            html5QrcodeScanner.clear();
        });
    }
    
    document.addEventListener('livewire:init', () => {
        if (!@this.transaction) {
            initScanner();
        }
        
        Livewire.on('resetScanner', () => {
            setTimeout(() => initScanner(), 500);
        });
    });
</script>
@endpush
```

---

## 7. Console Command - Reminder Scheduler

**File**: `app/Console/Commands/SendBorrowingReminders.php`

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowingRequest;
use App\Services\WhatsAppNotificationService;
use Carbon\Carbon;

class SendBorrowingReminders extends Command
{
    protected $signature = 'borrowing:send-reminders';
    protected $description = 'Send H-1 reminders for borrowings due tomorrow';
    
    public function handle(WhatsAppNotificationService $whatsappService)
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $today = Carbon::today()->toDateString();
        
        // Find borrowed items due tomorrow that haven't been reminded today
        $borrowings = BorrowingRequest::where('status', BorrowingRequest::STATUS_BORROWED)
            ->whereDate('return_date', $tomorrow)
            ->where(function ($query) use ($today) {
                $query->whereNull('reminder_sent_at')
                    ->orWhereDate('reminder_sent_at', '<', $today);
            })
            ->with(['user', 'item'])
            ->get();
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($borrowings as $borrowing) {
            try {
                $whatsappService->notifyReminder($borrowing);
                $borrowing->update(['reminder_sent_at' => now()]);
                $successCount++;
            } catch (\Exception $e) {
                $this->error("Failed to send reminder for ID {$borrowing->id}: {$e->getMessage()}");
                $failCount++;
            }
        }
        
        $this->info("Reminders sent: {$successCount} success, {$failCount} failed");
        return Command::SUCCESS;
    }
}
```

**Scheduler Registration** in `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('borrowing:send-reminders')
    ->dailyAt('08:00')
    ->timezone('Asia/Jakarta');
```

---

## 8. Security Considerations

### 8.1 Authentication & Authorization

```php
// Middleware stack for QR Scanner
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/qr/scan', [QRScanController::class, 'index']);
});

// Policy for BorrowingRequest
class BorrowingRequestPolicy
{
    public function approve(User $user, BorrowingRequest $request): bool
    {
        return $user->hasRole('guru') 
            && $request->teacher_id === $user->id
            && $request->status === BorrowingRequest::STATUS_PENDING;
    }
    
    public function viewHistory(User $user, BorrowingRequest $request): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        
        if ($user->hasRole('guru')) {
            return $request->teacher_id === $user->id;
        }
        
        return $request->user_id === $user->id;
    }
}
```

### 8.2 Input Validation

```php
// Form Request for Approval
class ApproveBorrowingRequest extends FormRequest
{
    public function rules(): array
    {
        return [];
    }
    
    public function authorize(): bool
    {
        $request = $this->route('borrowingRequest');
        return $this->user()->can('approve', $request);
    }
}

// Form Request for Rejection
class RejectBorrowingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'rejection_reason' => 'required|string|min:10|max:500',
        ];
    }
}
```

### 8.3 Rate Limiting

```php
// In routes/web.php
Route::middleware(['auth', 'throttle:qr-scan'])->group(function () {
    Route::post('/qr/process', [QRScanController::class, 'process']);
});

// In app/Providers/RouteServiceProvider.php
RateLimiter::for('qr-scan', function (Request $request) {
    return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
});
```

---

## 9. Testing Strategy

### 9.1 Unit Tests

```php
// tests/Unit/Services/BorrowingStateMachineTest.php
class BorrowingStateMachineTest extends TestCase
{
    public function test_can_transition_from_pending_to_approved()
    {
        $stateMachine = new BorrowingStateMachine();
        $request = BorrowingRequest::factory()->create(['status' => 'pending']);
        
        $canTransition = $stateMachine->canTransitionTo($request, 'approved');
        
        $this->assertTrue($canTransition);
    }
    
    public function test_cannot_transition_from_returned_to_borrowed()
    {
        $stateMachine = new BorrowingStateMachine();
        $request = BorrowingRequest::factory()->create(['status' => 'returned']);
        
        $canTransition = $stateMachine->canTransitionTo($request, 'borrowed');
        
        $this->assertFalse($canTransition);
    }
}
```

### 9.2 Feature Tests

```php
// tests/Feature/QRScanWorkflowTest.php
class QRScanWorkflowTest extends TestCase
{
    public function test_teacher_can_scan_qr_for_checkout()
    {
        $teacher = User::factory()->create()->assignRole('guru');
        $request = BorrowingRequest::factory()->create([
            'status' => 'approved',
            'qr_token' => 'test-token-123'
        ]);
        
        $response = $this->actingAs($teacher)
            ->post('/guru/qr/process', ['token' => 'test-token-123']);
        
        $response->assertOk();
        $request->refresh();
        $this->assertEquals('borrowed', $request->status);
    }
}
```

---

## 10. Configuration Files

### 10.1 WhatsApp Service Config

**File**: `config/services.php`

```php
return [
    // ... existing services
    
    'whatsapp' => [
        'base_url' => env('WA_BOT_URL', 'http://localhost:3000'),
        'api_key' => env('WA_BOT_API_KEY', ''),
        'timeout' => env('WA_BOT_TIMEOUT', 10),
    ],
];
```

### 10.2 Environment Variables

**Add to `.env.example`:**

```env
# WhatsApp Bot Service
WA_BOT_URL=http://localhost:3000
WA_BOT_API_KEY=your-api-key-here
WA_BOT_TIMEOUT=10
```

---

## 11. Deployment Checklist

- [ ] Run migrations: `php artisan migrate`
- [ ] Install QR code package: `composer require simplesoftwareio/simple-qrcode`
- [ ] Set up cron job: `* * * * * cd /path && php artisan schedule:run`
- [ ] Configure WhatsApp bot credentials in `.env`
- [ ] Test WhatsApp bot connectivity: `php artisan tinker` → `app(\App\Services\WhatsAppNotificationService::class)->checkBotStatus()`
- [ ] Seed test data if needed
- [ ] Configure storage link: `php artisan storage:link`
- [ ] Set permissions for storage directory
- [ ] Test QR scanner on mobile device
- [ ] Verify all state transitions work correctly
- [ ] Monitor logs for WhatsApp notification failures

---

**Document Status**: ✅ COMPLETE - Ready for implementation  
**Last Updated**: 2026-08-12  
**Next Step**: Create tasks.md
