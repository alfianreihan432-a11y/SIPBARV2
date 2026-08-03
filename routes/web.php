<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/inventory', [InventoryController::class, 'index'])->middleware('auth')->name('inventory.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/settings.php';
