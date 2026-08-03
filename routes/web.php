<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/inventory', [InventoryController::class, 'index'])->middleware('auth')->name('inventory.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin pages
    Route::view('categories', 'pages.admin.categories')->name('categories.index');
    Route::view('loans', 'pages.admin.loans')->name('loans.index');
    Route::view('returns', 'pages.admin.returns')->name('returns.index');
    Route::view('reports', 'pages.admin.reports')->name('reports.index');
    Route::view('statistics', 'pages.admin.statistics')->name('statistics.index');
    Route::view('users', 'pages.admin.users')->name('users.index');

    // Student pages
    Route::view('siswa/dashboard', 'dashboard-siswa')->name('student.dashboard');
    Route::view('siswa/katalog', 'pages.siswa.katalog')->name('student.catalog');
    Route::view('siswa/peminjaman', 'pages.siswa.loans')->name('student.loans');
    Route::view('siswa/riwayat', 'pages.siswa.history')->name('student.history');
    Route::view('siswa/pengumuman', 'pages.siswa.announcements')->name('student.announcements');

    // Teacher pages
    Route::view('guru/dashboard', 'dashboard-guru')->name('teacher.dashboard');
    Route::view('guru/permohonan', 'pages.guru.requests')->name('teacher.requests');
    Route::view('guru/siswa-bimbingan', 'pages.guru.students')->name('teacher.students');
    Route::view('guru/peminjaman-aktif', 'pages.guru.loans')->name('teacher.loans');
    Route::view('guru/pengembalian', 'pages.guru.returns')->name('teacher.returns');
    Route::view('guru/laporan', 'pages.guru.reports')->name('teacher.reports');
});

require __DIR__.'/settings.php';
