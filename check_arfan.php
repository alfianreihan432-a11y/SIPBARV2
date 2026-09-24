<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$sipintu = app(\App\Services\SipintuService::class);
$result = $sipintu->getStudents(nis: '4693', forceRefresh: true);

echo "API Response Success: " . ($result['success'] ? 'true' : 'false') . PHP_EOL;
echo "Total: " . ($result['total'] ?? 0) . PHP_EOL . PHP_EOL;

if (isset($result['data']) && is_array($result['data'])) {
    echo "Data structure: " . json_encode(array_keys($result['data'])) . PHP_EOL;
    
    // Handle different response structures
    if (isset($result['data'][0])) {
        $student = $result['data'][0];
    } elseif (isset($result['data']['id'])) {
        $student = $result['data'];
    } else {
        echo "Data structure unexpected: " . json_encode($result['data']) . PHP_EOL;
        exit;
    }
    
    echo "Student Data for NIS 4693:" . PHP_EOL;
    echo "Nama: " . ($student['nama'] ?? 'N/A') . PHP_EOL;
    echo "NIS: " . ($student['nis'] ?? 'N/A') . PHP_EOL;
    echo "Graduated: " . ($student['graduated'] ?? 'null') . PHP_EOL;
    echo "Tahun Lulus: " . ($student['tahun_lulus'] ?? 'null') . PHP_EOL;
    echo "Status: " . ($student['status'] ?? 'null') . PHP_EOL;
    
    if (isset($student['classroom']) && is_array($student['classroom'])) {
        echo "Classroom: " . ($student['classroom']['name'] ?? 'N/A') . PHP_EOL;
    } else {
        echo "Classroom: null" . PHP_EOL;
    }
}
