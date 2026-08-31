<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Testing SiPintu Connection ===\n\n";

echo "Config API URL: " . config('sipintu.api_url') . "\n";
echo "Config Client ID: " . config('sipintu.client_id') . "\n";
echo "Config Client Secret: " . (config('sipintu.client_secret') ? '***SET***' : 'NOT SET') . "\n\n";

$service = app(\App\Services\SipintuService::class);

// Test ping
echo "Testing ping()...\n";
try {
    $ping = $service->ping();
    var_dump($ping);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n";

// Test getStudents
echo "Testing getStudents()...\n";
try {
    $students = $service->getStudents();
    echo "Success: " . ($students['success'] ? 'YES' : 'NO') . "\n";
    echo "Total: " . $students['total'] . "\n";
    if (!$students['success']) {
        echo "Error: " . $students['error'] . "\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n";

// Test getTeachers
echo "Testing getTeachers()...\n";
try {
    $teachers = $service->getTeachers();
    echo "Success: " . ($teachers['success'] ? 'YES' : 'NO') . "\n";
    echo "Total: " . $teachers['total'] . "\n";
    if (!$teachers['success']) {
        echo "Error: " . $teachers['error'] . "\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
