<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$service = app(App\Services\SipintuService::class);

echo "=== Testing SiPintu Connection ===\n\n";

// Test 1: Ping
echo "1. Testing Ping...\n";
$pingResult = $service->ping();
var_dump($pingResult);
echo "\n";

// Test 2: Validate Client
echo "2. Validating Client Credentials...\n";
$validateResult = $service->validateClient();
var_dump($validateResult);
echo "\n";

// Test 3: Get Students
echo "3. Getting Students Data...\n";
$studentsResult = $service->getStudents();
var_dump($studentsResult);
echo "\n";

// Test 4: Get Teachers
echo "4. Getting Teachers Data...\n";
$teachersResult = $service->getTeachers();
var_dump($teachersResult);
echo "\n";

echo "=== Test Complete ===\n";
