<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SipintuAuthController;
use App\Http\Controllers\SipintuStatusController;
use App\Http\Controllers\TeacherApprovalController;
use App\Http\Controllers\TransactionHistoryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// ─── SIPINTU OAUTH 2.0 SSO (public — sebelum middleware auth) ───
Route::get('/oauth/sipintu', [SipintuAuthController::class, 'redirect'])->name('sipintu.oauth.redirect');
Route::get('/oauth/callback', [SipintuAuthController::class, 'callback'])->name('sipintu.oauth.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin pages
    Route::get('inventory', [InventoryController::class, 'index'])->middleware('auth')->name('inventory.index');
    Route::view('kelola-barang', 'pages.admin.kelola-barang')->name('kelola-barang.index');
    Route::view('categories', 'pages.admin.categories')->name('categories.index');
    Route::view('loans', 'pages.admin.loans')->name('loans.index');
    Route::view('returns', 'pages.admin.returns')->name('returns.index');
    Route::view('reports', 'pages.admin.reports')->name('reports.index');
    Route::view('statistics', 'pages.admin.statistics')->name('statistics.index');
    Route::view('users', 'pages.admin.users')->name('users.index');
    
    // Transaction History (Admin & Teacher)
    Route::middleware('role:admin|guru')->group(function () {
        Route::get('transactions', [TransactionHistoryController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{id}', [TransactionHistoryController::class, 'show'])->name('transactions.show');
    });

    // Admin QR Scanner
    Route::get('admin/qr-scanner', function() {
        return view('pages.admin.qr-scanner');
    })->name('admin.qr-scanner')->middleware('role:admin');

    // Student pages
    Route::get('siswa/dashboard', function() {
        return view('dashboard-siswa');
    })->name('student.dashboard');

    Route::get('siswa/katalog', function() {
        return view('pages.siswa.katalog');
    })->name('student.catalog');

    Route::view('siswa/peminjaman', 'pages.siswa.loans')->name('student.loans');
    Route::view('siswa/riwayat', 'pages.siswa.history')->name('student.history');
    Route::view('siswa/pengumuman', 'pages.siswa.announcements')->name('student.announcements');
    Route::view('siswa/profil', 'pages.siswa.profile')->name('student.profile');

    // Teacher pages
    Route::get('guru/dashboard', function() {
        return view('dashboard-guru');
    })->name('teacher.dashboard');

    // Teacher approval routes
    Route::middleware('role:guru')->prefix('guru')->name('teacher.')->group(function () {
        Route::get('permohonan', [TeacherApprovalController::class, 'index'])->name('requests');
        Route::post('permohonan/{id}/approve', [TeacherApprovalController::class, 'approve'])->name('requests.approve');
        Route::post('permohonan/{id}/reject', [TeacherApprovalController::class, 'reject'])->name('requests.reject');
        
        // QR Scanner
        Route::get('qr/scan', function() {
            return view('pages.guru.qr-scan');
        })->name('qr.scan');
    });

    Route::view('guru/siswa-bimbingan', 'pages.guru.students')->name('teacher.students');
    Route::view('guru/peminjaman-aktif', 'pages.guru.loans')->name('teacher.loans');
    Route::view('guru/pengembalian', 'pages.guru.returns')->name('teacher.returns');
    Route::view('guru/laporan', 'pages.guru.reports')->name('teacher.reports');

    // ─── SIPINTU Internal API (AJAX dari admin panel) ───
    Route::prefix('api/internal/sipintu')->name('sipintu.')->group(function () {
        Route::get('status',   [SipintuStatusController::class, 'status'])->name('status');
        Route::post('validate', [SipintuStatusController::class, 'validate'])->name('validate');
    });
});

require __DIR__.'/settings.php';
