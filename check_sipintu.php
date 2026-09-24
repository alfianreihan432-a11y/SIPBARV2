<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$sipintu = app(\App\Services\SipintuService::class);
$result = $sipintu->getStudents(nis: '4693', forceRefresh: true);

echo "API Response Success: " . ($result['success'] ? 'true' : 'false') . PHP_EOL;
echo "Error: " . ($result['error'] ?? 'null') . PHP_EOL;
echo "Total: " . ($result['total'] ?? 0) . PHP_EOL;

if (isset($result['data']) && is_array($result['data'])) {
    $student = $result['data'][0] ?? null;
    if ($student) {
        echo "\nStudent Data:" . PHP_EOL;
        echo "Available fields: " . json_encode(array_keys($student)) . PHP_EOL;
        echo "Tahun lulus: " . ($student['tahun_lulus'] ?? 'NOT SET') . PHP_EOL;
        echo "Graduated: " . ($student['graduated'] ?? 'NOT SET') . PHP_EOL;
        echo "Classroom: " . json_encode($student['classroom'] ?? []) . PHP_EOL;
    }
}
