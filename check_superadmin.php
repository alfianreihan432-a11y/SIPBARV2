<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING SUPERADMIN USER ===\n\n";

// Get Superadmin
$superadmin = \App\Models\User::where('email', 'superadmin@smkn1bangsri.sch.id')->first();

if (!$superadmin) {
    echo "❌ Superadmin user NOT FOUND!\n";
    exit(1);
}

echo "✅ Superadmin user FOUND\n\n";

// Display all columns
echo "--- USER DATA ---\n";
echo "ID: " . $superadmin->id . "\n";
echo "Name: " . $superadmin->name . "\n";
echo "Email: " . $superadmin->email . "\n";
echo "NIP: " . ($superadmin->nip ?? 'NULL') . "\n";
echo "NIS: " . ($superadmin->nis ?? 'NULL') . "\n";
echo "Status: " . ($superadmin->status ?? 'NULL') . "\n";
echo "Is Active: " . ($superadmin->is_active ?? 'NULL') . "\n";
echo "Email Verified At: " . ($superadmin->email_verified_at ?? 'NULL') . "\n";
echo "Created At: " . $superadmin->created_at . "\n";
echo "Updated At: " . $superadmin->updated_at . "\n";

echo "\n--- ROLES ---\n";
$roles = $superadmin->roles->pluck('name')->toArray();
if (empty($roles)) {
    echo "❌ NO ROLES ASSIGNED!\n";
} else {
    echo "Roles: " . implode(', ', $roles) . "\n";
}

echo "\n--- ROLE CHECKS ---\n";
echo "hasRole('superadmin'): " . ($superadmin->hasRole('superadmin') ? '✅ YES' : '❌ NO') . "\n";
echo "hasRole('super-admin'): " . ($superadmin->hasRole('super-admin') ? '✅ YES' : '❌ NO') . "\n";
echo "hasRole('super_admin'): " . ($superadmin->hasRole('super_admin') ? '✅ YES' : '❌ NO') . "\n";
echo "hasRole('admin'): " . ($superadmin->hasRole('admin') ? '✅ YES' : '❌ NO') . "\n";
echo "hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']): " . ($superadmin->hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']) ? '✅ YES' : '❌ NO') . "\n";

echo "\n--- COMPARISON WITH ADMIN USER ---\n";

// Get Admin that works
$admin = \App\Models\User::where('email', 'admintu@smkn1bangsri.sch.id')->first();

if (!$admin) {
    echo "❌ Admin user NOT FOUND for comparison!\n";
} else {
    echo "✅ Admin user FOUND\n\n";
    
    echo "Admin Data:\n";
    echo "ID: " . $admin->id . "\n";
    echo "Name: " . $admin->name . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "NIP: " . ($admin->nip ?? 'NULL') . "\n";
    echo "NIS: " . ($admin->nis ?? 'NULL') . "\n";
    echo "Status: " . ($admin->status ?? 'NULL') . "\n";
    echo "Is Active: " . ($admin->is_active ?? 'NULL') . "\n";
    echo "Email Verified At: " . ($admin->email_verified_at ?? 'NULL') . "\n";
    
    echo "\nAdmin Roles: " . implode(', ', $admin->roles->pluck('name')->toArray()) . "\n";
    echo "hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']): " . ($admin->hasAnyRole(['admin', 'superadmin', 'super-admin', 'super_admin']) ? '✅ YES' : '❌ NO') . "\n";
}

echo "\n--- AVAILABLE ROLES IN SYSTEM ---\n";
$allRoles = \Spatie\Permission\Models\Role::all();
foreach ($allRoles as $role) {
    echo "- " . $role->name . " (guard: " . $role->guard_name . ")\n";
}

echo "\n=== END CHECK ===\n";
