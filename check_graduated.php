<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$currentYear = (int) date('Y');
echo "Tahun sekarang: " . $currentYear . PHP_EOL . PHP_EOL;

$sipintu = app(\App\Services\SipintuService::class);
$result = $sipintu->getStudents(forceRefresh: true);

echo "Total students from SiPintu: " . count($result['data']) . PHP_EOL . PHP_EOL;

// Cari siswa dengan tahun_lulus yang menunjukkan sudah lulus
$graduatedStudents = [];
foreach ($result['data'] as $student) {
    $tahunLulus = $student['tahun_lulus'] ?? null;
    $graduated = $student['graduated'] ?? null;
    
    if ($tahunLulus && $tahunLulus < $currentYear) {
        $className = isset($student['classroom']) && is_array($student['classroom']) 
            ? ($student['classroom']['name'] ?? null) 
            : null;
            
        $graduatedStudents[] = [
            'nis' => $student['nis'],
            'nama' => $student['nama'],
            'tahun_lulus' => $tahunLulus,
            'graduated' => $graduated,
            'kelas' => $className
        ];
    }
}

echo "Siswa dengan tahun_lulus < " . $currentYear . ": " . count($graduatedStudents) . PHP_EOL;

if (count($graduatedStudents) > 0) {
    echo PHP_EOL . "Sample siswa yang sudah lulus:" . PHP_EOL;
    foreach (array_slice($graduatedStudents, 0, 10) as $s) {
        echo "- " . $s['nama'] . " (NIS: " . $s['nis'] . ") - Tahun Lulus: " . $s['tahun_lulus'] . " - Graduated: " . $s['graduated'] . " - Kelas: " . $s['kelas'] . PHP_EOL;
    }
}
