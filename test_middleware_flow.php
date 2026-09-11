<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== TESTING MIDDLEWARE FLOW ===\n\n";

$user = \App\Models\User::where('email', 'superadmin@smkn1bangsri.sch.id')->first();

if (!$user) {
    echo "❌ User not found!\n";
    exit(1);
}

echo "User: {$user->name} ({$user->email})\n";
echo "User ID: {$user->id}\n\n";

// Simulate middleware check
echo "--- Simulating CheckLoginRestriction Middleware ---\n\n";

$restrictionEnabled = config('sipbar.login_restriction.enabled', true);
echo "1. Restriction enabled: " . ($restrictionEnabled ? 'YES' : 'NO') . "\n\n";

if (!$restrictionEnabled) {
    echo "✅ PASS: Restriction disabled\n";
    exit(0);
}

echo "2. Checking roles...\n";
echo "   User roles: " . $user->roles->pluck('name')->implode(', ') . "\n\n";

// Test each variation
$roleChecks = [
    'admin' => $user->hasRole('admin'),
    'superadmin' => $user->hasRole('superadmin'),
    'super-admin' => $user->hasRole('super-admin'),
    'super_admin' => $user->hasRole('super_admin'),
];

echo "   Individual role checks:\n";
foreach ($roleChecks as $role => $has) {
    echo "   - hasRole('{$role}'): " . ($has ? '✅ YES' : '❌ NO') . "\n";
}

echo "\n3. Checking hasAnyRole (Line 29 in middleware)...\n";
$hasAnyRole = $user->hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']);
echo "   hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']): " . ($hasAnyRole ? '✅ YES' : '❌ NO') . "\n\n";

if ($hasAnyRole) {
    echo "✅✅✅ RESULT: Middleware would ALLOW (bypass at line 29-31)\n";
} else {
    echo "❌❌❌ RESULT: Middleware would continue to check guru/siswa\n\n";
    
    echo "4. Checking guru role...\n";
    $isGuru = $user->hasRole('guru');
    echo "   hasRole('guru'): " . ($isGuru ? '✅ YES' : '❌ NO') . "\n\n";
    
    echo "5. Checking kepala_jurusan role...\n";
    $isKajur = $user->hasRole('kepala_jurusan');
    echo "   hasRole('kepala_jurusan'): " . ($isKajur ? '✅ YES' : '❌ NO') . "\n\n";
    
    if ($isGuru || $isKajur) {
        echo "✅ RESULT: Middleware would ALLOW (teacher/kajur at line 34-37)\n";
    } else {
        echo "❌ RESULT: Middleware would check WHITELIST (siswa check)\n";
        echo "   This would cause LOGOUT!\n";
    }
}

echo "\n=== Testing with fresh role load ===\n";
$freshUser = \App\Models\User::with('roles')->find($user->id);
echo "Fresh user roles: " . $freshUser->roles->pluck('name')->implode(', ') . "\n";
$freshCheck = $freshUser->hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']);
echo "hasAnyRole (fresh): " . ($freshCheck ? '✅ YES' : '❌ NO') . "\n";

echo "\n=== END TEST ===\n";
