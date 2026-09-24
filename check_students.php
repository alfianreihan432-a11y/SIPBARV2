<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$sipintu = app(\App\Services\SipintuService::class);
$result = $sipintu->getStudents(forceRefresh: true);

echo "Total students: " . count($result['data']) . PHP_EOL . PHP_EOL;

// Sample 10 students
$students = array_slice($result['data'], 0, 10);

echo "Sample 10 students from SiPintu:" . PHP_EOL;
foreach ($students as $student) {
    $className = isset($student['classroom']) && is_array($student['classroom']) 
        ? ($student['classroom']['name'] ?? 'N/A') 
        : 'N/A';
    
    echo "NIS: " . ($student['nis'] ?? 'N/A') . 
         " - Nama: " . ($student['nama'] ?? 'N/A') . 
         " - Graduated: " . ($student['graduated'] ?? 'N/A') . 
         " - Tahun Lulus: " . ($student['tahun_lulus'] ?? 'N/A') . 
         " - Kelas: " . $className . PHP_EOL;
}
