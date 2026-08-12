<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    // Profile
    Route::get('settings/profile', [SettingsController::class, 'profile'])->name('profile.edit');
    Route::patch('settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');

    // Security
    Route::get('settings/security', [SettingsController::class, 'security'])->name('security.edit');
    Route::put('settings/security', [SettingsController::class, 'updatePassword'])->name('settings.password.update');

    // Appearance
    Route::get('settings/appearance', [SettingsController::class, 'appearance'])->name('appearance.edit');
});

Route::get('.well-known/passkey-endpoints', function () {
    return response()->json([
        'enroll' => route('security.edit'),
        'manage' => route('security.edit'),
    ]);
})->name('well-known.passkeys');
