<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TEST SYNC SAMPLE ===" . PHP_EOL . PHP_EOL;

// Sample NIS untuk test:
// 1. Siswa yang sudah lulus di SiPintu (tahun_lulus < 2026, graduated = 1)
// 2. Siswa yang masih aktif
$testNIS = [
    '4187', // YUNITA DEA SAFITRI - tahun_lulus 2025, graduated 1, kelas XII PM 1
    '4151', // ZASKIA NORMA TRI HAPSARI - tahun_lulus 2025, graduated 1, kelas XII PM 2
    '5768', // VALLIN ALVARO - tahun_lulus 2026, masih aktif, kelas X PM 2
];

$sipintu = app(\App\Services\SipintuService::class);

echo "Checking sample students from SiPintu:" . PHP_EOL;
foreach ($testNIS as $nis) {
    $result = $sipintu->getStudents(nis: $nis, forceRefresh: true);
    if ($result['success'] && !empty($result['data'])) {
        // Handle different response structures
        if (isset($result['data'][0])) {
            $student = $result['data'][0];
        } elseif (isset($result['data']['id'])) {
            $student = $result['data'];
        } else {
            echo "- NIS: {$nis} - DATA STRUCTURE ERROR" . PHP_EOL;
            continue;
        }
        
        $className = isset($student['classroom']) && is_array($student['classroom']) 
            ? ($student['classroom']['name'] ?? 'N/A') 
            : 'N/A';
        
        echo "- NIS: {$nis} - Nama: " . ($student['nama'] ?? 'N/A') . 
             " - Tahun Lulus: " . ($student['tahun_lulus'] ?? 'N/A') . 
             " - Graduated: " . ($student['graduated'] ?? 'N/A') . 
             " - Kelas: {$className}" . PHP_EOL;
    } else {
        echo "- NIS: {$nis} - NOT FOUND in SiPintu" . PHP_EOL;
    }
}

echo PHP_EOL . "Checking local database before sync:" . PHP_EOL;
foreach ($testNIS as $nis) {
    $user = \App\Models\User::where('nis', $nis)->first();
    if ($user) {
        echo "- NIS: {$nis} - Nama: {$user->name} - Kelas: " . ($user->kelas ?? 'null') . 
             " - Classroom ID: " . ($user->classroom_id ?? 'null') . 
             " - Data Source: {$user->data_source}" . PHP_EOL;
    } else {
        echo "- NIS: {$nis} - NOT FOUND in local database" . PHP_EOL;
    }
}

echo PHP_EOL . "Running sync command..." . PHP_EOL;
$exitCode = \Illuminate\Support\Facades\Artisan::call('sipintu:sync-users', [
    '--force' => true,
    '--batch' => 10,
]);

echo "Exit code: {$exitCode}" . PHP_EOL;
echo "Output: " . \Illuminate\Support\Facades\Artisan::output() . PHP_EOL;

echo PHP_EOL . "Checking local database after sync:" . PHP_EOL;
foreach ($testNIS as $nis) {
    $user = \App\Models\User::where('nis', $nis)->first();
    if ($user) {
        echo "- NIS: {$nis} - Nama: {$user->name} - Kelas: " . ($user->kelas ?? 'null') . 
             " - Classroom ID: " . ($user->classroom_id ?? 'null') . 
             " - Data Source: {$user->data_source}" . 
             " - Synced At: " . ($user->sipintu_synced_at ?? 'null') . PHP_EOL;
    } else {
        echo "- NIS: {$nis} - NOT FOUND in local database" . PHP_EOL;
    }
}

echo PHP_EOL . "=== VERIFICATION ===" . PHP_EOL;
echo "Graduated students should have kelas = null and classroom_id = null" . PHP_EOL;
echo "Active students should have their actual classroom data" . PHP_EOL;

// Get specific data for verification
echo PHP_EOL . "NIS 4187 (graduated=1): Expected kelas=null, got " . (\App\Models\User::where('nis', '4187')->first()->kelas ?? 'null') . PHP_EOL;
echo "NIS 4151 (graduated=1): Expected kelas=null, got " . (\App\Models\User::where('nis', '4151')->first()->kelas ?? 'null') . PHP_EOL;
echo "NIS 5768 (graduated=empty): Expected kelas=X PM 2, got " . (\App\Models\User::where('nis', '5768')->first()->kelas ?? 'null') . PHP_EOL;
