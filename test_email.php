<?php
// Tinker-compatible test script — jalankan dengan: php artisan tinker < test_email.php

$req = App\Models\BorrowingRequest::with(['user', 'item', 'teacher'])->latest()->first();

if (!$req) {
    echo "SKIP: Tidak ada BorrowingRequest di database.\n";
} else {
    echo "Found request ID: {$req->id}, status: {$req->status}\n";
    echo "Teacher email: " . ($req->teacher?->email ?? '(kosong)') . "\n";
    echo "User email: " . ($req->user?->email ?? '(kosong)') . "\n";

    // Test notifyNewRequest (queue ke log)
    app(\App\Services\EmailNotificationService::class)->notifyNewRequest($req);
    echo "notifyNewRequest() => OK (diantrekan / dicatat di log)\n";
}
