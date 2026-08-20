<?php

use App\Http\Controllers\Api\BorrowingApiController;
use App\Http\Controllers\Api\BotApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public API - No Authentication Required
Route::prefix('v1')->group(function () {
    Route::get('health', function () {
        return response()->json([
            'status' => 'ok',
            'version' => '2.1.0',
            'timestamp' => now()->toIso8601String(),
        ]);
    });
});

// Protected API - Requires Authentication
Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {

    // Borrowing Endpoints
    Route::prefix('borrowings')->group(function () {
        // Student endpoints
        Route::get('my-history', [BorrowingApiController::class, 'myHistory'])
            ->name('api.borrowings.my-history');

        Route::get('{id}/qr-code', [BorrowingApiController::class, 'getQRCode'])
            ->name('api.borrowings.qr-code');

        // Teacher/Admin endpoints
        Route::get('statistics', [BorrowingApiController::class, 'statistics'])
            ->middleware('role:guru|admin')
            ->name('api.borrowings.statistics');
    });

    // QR Scanner Endpoints
    Route::prefix('qr')->middleware('role:guru|admin')->group(function () {
        Route::post('validate', [BorrowingApiController::class, 'validateQR'])
            ->name('api.qr.validate');
    });

    // User Info
    Route::get('me', function () {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'roles' => $user->getRoleNames(),
            ]
        ]);
    })->name('api.me');
});

// Bot API - untuk WhatsApp Bot (autentikasi via API Key, bukan Sanctum)
Route::prefix('v1/bot')->middleware('bot.auth')->group(function () {
    Route::get('cek/{id}', [BotApiController::class, 'cekStatus'])
        ->name('api.bot.cek');

    Route::post('pinjam', [BotApiController::class, 'ajukanPinjam'])
        ->name('api.bot.pinjam');

    Route::get('barang', [BotApiController::class, 'daftarBarang'])
        ->name('api.bot.barang');

    Route::get('riwayat/{phone}', [BotApiController::class, 'riwayat'])
        ->name('api.bot.riwayat');

    Route::get('permohonan/{phone}', [BotApiController::class, 'permohonanPending'])
        ->name('api.bot.permohonan');

    Route::post('approve/{id}', [BotApiController::class, 'approveViaBot'])
        ->name('api.bot.approve');

    Route::post('reject/{id}', [BotApiController::class, 'rejectViaBot'])
        ->name('api.bot.reject');
});