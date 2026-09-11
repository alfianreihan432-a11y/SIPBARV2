<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== TESTING ACTUAL LOGIN PROCESS ===\n\n";

// Simulate request
$request = new \Illuminate\Http\Request();
$request->merge([
    'email' => 'superadmin@smkn1bangsri.sch.id',
    'password' => 'superadmin123',
]);

echo "Attempting login with:\n";
echo "Email: superadmin@smkn1bangsri.sch.id\n";
echo "Password: superadmin123\n\n";

try {
    // Call the AuthenticateUser action directly
    $authenticator = new \App\Actions\Fortify\AuthenticateUser();
    $user = $authenticator($request);
    
    if ($user) {
        echo "✅✅✅ LOGIN SUCCESSFUL!\n\n";
        echo "User: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
    } else {
        echo "❌ LOGIN FAILED: AuthenticateUser returned null\n";
        echo "Possible reasons:\n";
        echo "- User not found\n";
        echo "- Password incorrect\n";
        echo "- Role check failed\n";
    }
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "❌ LOGIN FAILED WITH VALIDATION ERROR:\n\n";
    foreach ($e->errors() as $field => $messages) {
        foreach ($messages as $message) {
            echo "  {$field}: {$message}\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ LOGIN FAILED WITH EXCEPTION:\n";
    echo "  " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}

echo "\n=== END TEST ===\n";
