<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING LOGIN LOGIC FOR SUPERADMIN ===\n\n";

$user = \App\Models\User::where('email', 'superadmin@smkn1bangsri.sch.id')->first();

if (!$user) {
    echo "❌ User not found!\n";
    exit(1);
}

echo "✅ User found: {$user->name} ({$user->email})\n\n";

// Test authentication logic from AuthenticateUser.php
echo "--- Testing AuthenticateUser Logic ---\n\n";

// Check password
$password = 'superadmin123';
$passwordMatch = \Illuminate\Support\Facades\Hash::check($password, $user->password);
echo "Password check: " . ($passwordMatch ? '✅ MATCH' : '❌ NO MATCH') . "\n\n";

// Check login restriction config
echo "--- Testing Login Restriction Logic ---\n\n";
$restrictionEnabled = config('sipbar.login_restriction.enabled', true);
echo "Login Restriction Enabled: " . ($restrictionEnabled ? '✅ YES' : '❌ NO') . "\n\n";

if ($restrictionEnabled) {
    echo "Checking role-based bypass:\n";
    
    $isAdminOrSuperAdmin = $user->hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']);
    echo "hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']): " . ($isAdminOrSuperAdmin ? '✅ YES (SHOULD BYPASS)' : '❌ NO') . "\n";
    
    $isTeacher = $user->hasRole('guru');
    echo "hasRole('guru'): " . ($isTeacher ? '✅ YES' : '❌ NO') . "\n";
    
    $isKepalaJurusan = $user->hasRole('kepala_jurusan');
    echo "hasRole('kepala_jurusan'): " . ($isKepalaJurusan ? '✅ YES' : '❌ NO') . "\n\n";
    
    if ($isAdminOrSuperAdmin || $isTeacher || $isKepalaJurusan) {
        echo "✅✅✅ RESULT: User SHOULD BE ALLOWED to login (bypassed restriction)\n";
    } else {
        echo "❌❌❌ RESULT: User would be BLOCKED (not in allowed roles)\n";
        
        echo "\nChecking whitelist...\n";
        $whitelist = config('sipbar.login_restriction.whitelisted_students', []);
        echo "Whitelist: " . implode(', ', $whitelist) . "\n";
    }
} else {
    echo "✅✅✅ RESULT: User SHOULD BE ALLOWED (restriction disabled)\n";
}

echo "\n--- Testing Middleware Logic (CheckLoginRestriction) ---\n\n";

if (!$restrictionEnabled) {
    echo "✅ Middleware would ALLOW (restriction disabled)\n";
} else {
    // Check if user has admin/superadmin role (line 29-31 in middleware)
    if ($user->hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin'])) {
        echo "✅ Middleware would ALLOW at line 29-31 (has admin/superadmin role)\n";
    } elseif ($user->hasRole('guru') || $user->hasRole('kepala_jurusan')) {
        echo "✅ Middleware would ALLOW at line 34-37 (is teacher/kepala jurusan)\n";
    } else {
        echo "❌ Middleware would check WHITELIST (user is student)\n";
    }
}

echo "\n=== Checking all role variations ===\n";
$roleVariations = ['admin', 'superadmin', 'super-admin', 'super_admin'];
foreach ($roleVariations as $roleName) {
    $has = $user->hasRole($roleName);
    echo "hasRole('{$roleName}'): " . ($has ? '✅ YES' : '❌ NO') . "\n";
}

echo "\n=== END TEST ===\n";
