<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG VALLIN ALVARO (NIS 5768) ===" . PHP_EOL . PHP_EOL;

// Get data from SiPintu
$sipintu = app(\App\Services\SipintuService::class);
$result = $sipintu->getStudents(nis: '5768', forceRefresh: true);

if ($result['success'] && !empty($result['data'])) {
    if (isset($result['data'][0])) {
        $student = $result['data'][0];
    } elseif (isset($result['data']['id'])) {
        $student = $result['data'];
    } else {
        echo "DATA STRUCTURE ERROR" . PHP_EOL;
        exit;
    }
    
    echo "SiPintu Data:" . PHP_EOL;
    echo "Nama: " . ($student['nama'] ?? 'N/A') . PHP_EOL;
    echo "NIS: " . ($student['nis'] ?? 'N/A') . PHP_EOL;
    echo "Tahun Lulus: " . ($student['tahun_lulus'] ?? 'N/A') . PHP_EOL;
    echo "Graduated: " . ($student['graduated'] ?? 'N/A') . PHP_EOL;
    echo "Graduated Type: " . gettype($student['graduated'] ?? null) . PHP_EOL;
    
    if (isset($student['classroom']) && is_array($student['classroom'])) {
        echo "Classroom: " . ($student['classroom']['name'] ?? 'N/A') . PHP_EOL;
    } else {
        echo "Classroom: null" . PHP_EOL;
    }
    
    // Test the graduated logic
    $currentYear = (int) date('Y');
    $tahunLulus = $student['tahun_lulus'] ?? null;
    $graduated = $student['graduated'] ?? null;
    
    echo PHP_EOL . "Logic Test:" . PHP_EOL;
    echo "Current Year: {$currentYear}" . PHP_EOL;
    echo "Tahun Lulus: " . ($tahunLulus ?? 'null') . PHP_EOL;
    echo "Graduated: " . ($graduated ?? 'null') . PHP_EOL;
    
    $isGraduated = ($tahunLulus && $tahunLulus <= $currentYear) || $graduated;
    echo "Is Graduated: " . ($isGraduated ? 'true' : 'false') . PHP_EOL;
    echo "Is Graduated Type: " . gettype($isGraduated) . PHP_EOL;
}

echo PHP_EOL . "Local Database:" . PHP_EOL;
$user = \App\Models\User::where('nis', '5768')->first();
if ($user) {
    echo "Nama: {$user->name}" . PHP_EOL;
    echo "Kelas: " . ($user->kelas ?? 'null') . PHP_EOL;
    echo "Classroom ID: " . ($user->classroom_id ?? 'null') . PHP_EOL;
    echo "Data Source: {$user->data_source}" . PHP_EOL;
}
