<?php

use App\Http\Controllers\Admin\AdminQRVerificationController;
use App\Http\Controllers\Admin\AdminReturnController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MagicApprovalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SipintuAuthController;
use App\Http\Controllers\SipintuStatusController;
use App\Http\Controllers\Student\StudentQRCodeController;
use App\Http\Controllers\Student\StudentReturnController;
use App\Http\Controllers\TeacherApprovalController;
use App\Http\Controllers\TransactionHistoryController;
use App\Livewire\AddStudent;
use App\Livewire\AddTeacher;
use App\Livewire\AddClassroom;
use App\Livewire\AddExtracurricular;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// ─── MAGIC LINK APPROVAL (Signed URL — tidak perlu login) ───────────────────
// Guru mengklik link dari email, tanpa harus punya akun / login ke SIPBAR.
// Middleware 'signed' memvalidasi signature dari URL::temporarySignedRoute().
// PENTING: GET show() tidak boleh ada side effect (aman dari prefetcher email).
Route::middleware('signed')->group(function () {
    Route::get(
        '/approval/{borrowingRequest}',
        [MagicApprovalController::class, 'show']
    )->name('approval.show');

    Route::post(
        '/approval/{borrowingRequest}/approve',
        [MagicApprovalController::class, 'approve']
    )->name('approval.approve');

    Route::post(
        '/approval/{borrowingRequest}/reject',
        [MagicApprovalController::class, 'reject']
    )->name('approval.reject');
});
// ────────────────────────────────────────────────────────────────────────────

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
    
    // Add forms routes
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/tambah-siswa', AddStudent::class)->name('admin.add-student');
        Route::get('admin/tambah-guru', AddTeacher::class)->name('admin.add-teacher');
        Route::get('admin/tambah-kelas', AddClassroom::class)->name('admin.add-classroom');
        Route::get('admin/tambah-ekstra', AddExtracurricular::class)->name('admin.add-extracurricular');
    });
    
    // Admin Return Verification routes
    Route::get('returns', [AdminReturnController::class, 'index'])->name('returns.index');
    Route::get('returns/{id}', [AdminReturnController::class, 'show'])->name('admin.returns.show');
    Route::post('returns/{id}/approve', [AdminReturnController::class, 'approve'])->name('admin.returns.approve');
    Route::post('returns/{id}/reject', [AdminReturnController::class, 'reject'])->name('admin.returns.reject');

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
    Route::get('siswa/peminjaman/{id}/edit', [\App\Http\Controllers\Student\StudentBorrowingController::class, 'edit'])
        ->name('student.loans.edit');
    Route::put('siswa/peminjaman/{id}/update', [\App\Http\Controllers\Student\StudentBorrowingController::class, 'update'])
        ->name('student.loans.update');
    Route::post('siswa/peminjaman/{id}/cancel', [\App\Http\Controllers\Student\StudentBorrowingController::class, 'cancel'])
        ->name('student.loans.cancel');

    // Student Return System routes
    Route::get('siswa/pengembalian', [StudentReturnController::class, 'index'])->name('student.returns.index');
    Route::get('siswa/pengembalian/ajukan/{id}', [StudentReturnController::class, 'create'])->name('student.returns.create');
    Route::post('siswa/pengembalian/ajukan', [StudentReturnController::class, 'store'])->name('student.returns.store');
    Route::get('siswa/pengembalian/riwayat', [StudentReturnController::class, 'history'])->name('student.returns.history');

    Route::view('siswa/riwayat', 'pages.siswa.history')->name('student.history');
    Route::view('siswa/pengumuman', 'pages.siswa.announcements')->name('student.announcements');
    Route::view('siswa/profil', 'pages.siswa.profile')->name('student.profile');

    // Student QR Code — generate/tampilkan QR untuk peminjaman yang disetujui
    Route::get('siswa/peminjaman/{id}/qrcode', [StudentQRCodeController::class, 'show'])
        ->name('student.qrcode.show');

    // Admin QR Verification — verifikasi token QR saat scan & konfirmasi pengambilan
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/qr/verify/{token}', [AdminQRVerificationController::class, 'verify'])->name('admin.qr.verify');
        Route::get('admin/verifikasi-pengambilan/{token}', [AdminQRVerificationController::class, 'verify'])->name('admin.qr.verify.alias');
        Route::post('admin/qr/confirm-checkout/{id}', [AdminQRVerificationController::class, 'confirmCheckout'])->name('admin.qr.confirm-checkout');
    });

    // Teacher pages
    Route::get('guru/dashboard', function() {
        return view('dashboard-guru');
    })->name('teacher.dashboard');

    // Teacher approval routes
    Route::middleware('role:guru')->prefix('guru')->name('teacher.')->group(function () {
        Route::get('sidebar-counts', function () {
            $teacherId = auth()->id();

            return response()->json([
                'pending_requests' => \App\Models\BorrowingRequest::where('teacher_id', $teacherId)
                    ->where('status', \App\Models\BorrowingRequest::STATUS_PENDING)
                    ->count(),
                'active_loans' => \App\Models\BorrowingRequest::where('teacher_id', $teacherId)
                    ->whereIn('status', [
                        \App\Models\BorrowingRequest::STATUS_APPROVED,
                        \App\Models\BorrowingRequest::STATUS_BORROWED,
                        \App\Models\BorrowingRequest::STATUS_OVERDUE,
                    ])
                    ->count(),
            ]);
        })->name('sidebar.counts');

        Route::get('permohonan', [TeacherApprovalController::class, 'index'])->name('requests');
        Route::post('permohonan/{id}/approve', [TeacherApprovalController::class, 'approve'])->name('requests.approve');
        Route::post('permohonan/{id}/reject', [TeacherApprovalController::class, 'reject'])->name('requests.reject');
        
        // QR Scanner
        Route::get('qr/scan', function() {
            return view('pages.guru.qr-scan');
        })->name('qr.scan');
        
        // Returns processing
        Route::post('pengembalian/{id}/process', function($id) {
            // Process return logic here
            $request = \App\Models\BorrowingRequest::findOrFail($id);
            $request->update(['status' => 'returned']);
            return redirect()->route('teacher.returns')->with('success', 'Barang berhasil dikembalikan');
        })->name('returns.process');
    });

    Route::view('guru/siswa-bimbingan', 'pages.guru.students')->name('teacher.students');
    Route::view('guru/peminjaman-aktif', 'pages.guru.loans')->name('teacher.loans');
    Route::view('guru/pengembalian', 'pages.guru.returns')->name('teacher.returns');
    Route::view('guru/laporan', 'pages.guru.reports')->name('teacher.reports');
    Route::view('guru/profil', 'pages.guru.profile')->name('teacher.profile');

    // ─── Notification & Message APIs ───
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // ─── SIPINTU Internal API (AJAX dari admin panel) ───
    Route::prefix('api/internal/sipintu')->name('sipintu.')->group(function () {
        Route::get('status',   [SipintuStatusController::class, 'status'])->name('status');
        Route::post('validate', [SipintuStatusController::class, 'validate'])->name('validate');
    });
});

require __DIR__.'/settings.php';
