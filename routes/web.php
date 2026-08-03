<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/inventory', [InventoryController::class, 'index'])->middleware('auth')->name('inventory.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
