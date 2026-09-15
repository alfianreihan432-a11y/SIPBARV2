<?php

use App\Http\Controllers\Admin\AdminQRVerificationController;
use App\Http\Controllers\Admin\AdminReturnController;
use App\Http\Controllers\Admin\LaporanJurusanController;
use App\Http\Controllers\Admin\LaporanAdminController;
use App\Http\Controllers\Superadmin\LaporanAdminController as SuperadminLaporanAdminController;
use App\Http\Controllers\Superadmin\SuperadminReportController;
use App\Http\Controllers\Superadmin\SuperadminTeacherReportController;
use App\Http\Controllers\Teacher\TeacherReportSendController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemImportController;
use App\Http\Controllers\KepalaJurusanController;
use App\Http\Controllers\MagicApprovalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SipintuAuthController;
use App\Http\Controllers\SipintuStatusController;
use App\Http\Controllers\Student\StudentQRCodeController;
use App\Http\Controllers\Student\StudentReturnController;
use App\Http\Controllers\TeacherApprovalController;
use App\Http\Controllers\Teacher\BarangController;
use App\Http\Controllers\Teacher\PeminjamanGuruController;
use App\Http\Controllers\Teacher\PengembalianGuruController;
use App\Http\Controllers\Teacher\TeacherQRCodeController;
use App\Http\Controllers\TransactionHistoryController;
use App\Livewire\AddStudent;
use App\Livewire\AddTeacher;
use App\Livewire\AddClassroom;
use App\Livewire\AddExtracurricular;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// ─── MAGIC LINK APPROVAL (Signed URL — tidak perlu login) ───────────────────
// Siswa → Guru (Approval oleh Guru)
Route::get(
    '/approval/{borrowingRequest}',
    [MagicApprovalController::class, 'show']
)->middleware('signed')->name('approval.show');

Route::post(
    '/approval/{borrowingRequest}/approve',
    [MagicApprovalController::class, 'approve']
)->name('approval.approve');

Route::post(
    '/approval/{borrowingRequest}/reject',
    [MagicApprovalController::class, 'reject']
)->name('approval.reject');

// Guru → Kepala Jurusan (Approval oleh Kepala Jurusan)
Route::get(
    '/approval-guru/{borrowingRequest}',
    [MagicApprovalController::class, 'showGuru']
)->middleware('signed')->name('approval-guru.show');

Route::post(
    '/approval-guru/{borrowingRequest}/approve',
    [MagicApprovalController::class, 'approveGuru']
)->name('approval-guru.approve');

Route::post(
    '/approval-guru/{borrowingRequest}/reject',
    [MagicApprovalController::class, 'rejectGuru']
)->name('approval-guru.reject');
// ────────────────────────────────────────────────────────────────────────────

// ─── SIPINTU OAUTH 2.0 SSO (public — sebelum middleware auth) ───
Route::get('/oauth/sipintu', [SipintuAuthController::class, 'redirect'])->name('sipintu.oauth.redirect');
Route::get('/oauth/callback', [SipintuAuthController::class, 'callback'])->name('sipintu.oauth.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── ADMIN ONLY routes (role:admin or superadmin can access) ────────────
    Route::middleware('role:admin|superadmin')->group(function () {
        // Inventory & barang
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::view('kelola-barang', 'pages.admin.kelola-barang')->name('kelola-barang.index');
        Route::view('categories', 'pages.admin.categories')->name('categories.index');
        Route::view('loans', 'pages.admin.loans')->name('loans.index');
        Route::view('reports', 'pages.admin.reports')->name('reports.index');
        Route::view('statistics', 'pages.admin.statistics')->name('statistics.index');
        Route::view('users', 'pages.admin.users')->name('users.index');

        // Admin Return Verification routes
        Route::get('returns', [AdminReturnController::class, 'index'])->name('returns.index');
        Route::get('returns/{id}', [AdminReturnController::class, 'show'])->name('admin.returns.show');
        Route::post('returns/{id}/approve', [AdminReturnController::class, 'approve'])->name('admin.returns.approve')->middleware('superadmin.restrict');
        Route::post('returns/{id}/reject', [AdminReturnController::class, 'reject'])->name('admin.returns.reject')->middleware('superadmin.restrict');

        // Add forms routes
        Route::get('admin/tambah-siswa', AddStudent::class)->name('admin.add-student');
        Route::get('admin/tambah-guru', AddTeacher::class)->name('admin.add-teacher');
        Route::get('admin/tambah-kelas', AddClassroom::class)->name('admin.add-classroom');
        Route::get('admin/tambah-ekstra', AddExtracurricular::class)->name('admin.add-extracurricular');

        // KIBB Import routes
        Route::prefix('items/import')->name('items.import.')->group(function () {
            Route::get('template', [ItemImportController::class, 'downloadTemplate'])->name('template');
            Route::post('upload', [ItemImportController::class, 'upload'])->name('upload');
            Route::get('preview', [ItemImportController::class, 'preview'])->name('preview');
            Route::post('confirm', [ItemImportController::class, 'confirm'])->name('confirm');
            Route::get('cancel', [ItemImportController::class, 'cancel'])->name('cancel');
        });

        // Admin Laporan Jurusan routes
        Route::prefix('admin/laporan-jurusan')->group(function () {
            Route::get('/', [LaporanJurusanController::class, 'index'])->name('admin.laporan-jurusan');
            Route::get('/{id}', [LaporanJurusanController::class, 'show'])->name('admin.laporan-jurusan.show');
            Route::post('/{id}/approve', [LaporanJurusanController::class, 'approve'])->name('admin.laporan-jurusan.approve');
            Route::post('/{id}/reject', [LaporanJurusanController::class, 'reject'])->name('admin.laporan-jurusan.reject');
            Route::delete('/{id}', [LaporanJurusanController::class, 'destroy'])->name('admin.laporan-jurusan.destroy');
        });

        // Admin Laporan Admin routes (send consolidation report to superadmin)
        Route::prefix('admin/laporan-admin')->group(function () {
            Route::get('/', [LaporanAdminController::class, 'index'])->name('admin.laporan-admin');
            Route::get('/create', [LaporanAdminController::class, 'create'])->name('admin.laporan-admin.create');
            Route::post('/', [LaporanAdminController::class, 'store'])->name('admin.laporan-admin.store');
            Route::get('/{id}', [LaporanAdminController::class, 'show'])->name('admin.laporan-admin.show');
        });

        // Transaction History (Admin)
        Route::get('transactions', [TransactionHistoryController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{id}', [TransactionHistoryController::class, 'show'])->name('transactions.show');
    }); // end role:admin
    // ────────────────────────────────────────────────────────────────────────

    // ─── SUPERADMIN ONLY routes (role:superadmin required) ──────────────────
    Route::middleware('role:superadmin')->prefix('superadmin')->name('superadmin.')->group(function () {
        // Dashboard
        Route::get('dashboard', function() {
            return view('pages.superadmin.dashboard');
        })->name('dashboard');

        // QR Scanner
        Route::get('qr-scanner', function() {
            return view('pages.superadmin.qr-scanner');
        })->name('qr-scanner');

        // Inventory & barang (read-only)
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory');
        Route::view('manage-items', 'pages.superadmin.manage-items')->name('manage-items');
        Route::view('categories', 'pages.superadmin.categories')->name('categories');

        // Loans & Returns (read-only - no approve/reject)
        Route::view('loans', 'pages.superadmin.loans')->name('loans');
        Route::get('returns', [AdminReturnController::class, 'index'])->name('returns');

        // Reports (System-wide statistics)
        Route::get('reports', [SuperadminReportController::class, 'index'])->name('reports');
        Route::prefix('laporan-jurusan')->group(function () {
            Route::get('/', [LaporanJurusanController::class, 'index'])->name('laporan-jurusan');
            Route::get('/{id}', [LaporanJurusanController::class, 'show'])->name('laporan-jurusan.show');
            Route::post('/{id}/approve', [LaporanJurusanController::class, 'approve'])->name('laporan-jurusan.approve');
            Route::post('/{id}/reject', [LaporanJurusanController::class, 'reject'])->name('laporan-jurusan.reject');
        });

        // Admin Reports (receive and approve/reject consolidation reports from admin)
        Route::prefix('laporan-admin')->group(function () {
            Route::get('/', [SuperadminLaporanAdminController::class, 'index'])->name('laporan-admin');
            Route::get('/{id}', [SuperadminLaporanAdminController::class, 'show'])->name('laporan-admin.show');
            Route::post('/{id}/approve', [SuperadminLaporanAdminController::class, 'approve'])->name('laporan-admin.approve');
            Route::post('/{id}/reject', [SuperadminLaporanAdminController::class, 'reject'])->name('laporan-admin.reject');
            Route::delete('/{id}', [SuperadminLaporanAdminController::class, 'destroy'])->name('laporan-admin.destroy');
        });

        // Teacher Reports (received from teachers)
        Route::prefix('laporan-guru')->name('laporan-guru.')->group(function () {
            Route::get('/', [SuperadminTeacherReportController::class, 'index'])->name('index');
            Route::get('/{id}', [SuperadminTeacherReportController::class, 'show'])->name('show');
            Route::delete('/{id}', [SuperadminTeacherReportController::class, 'destroy'])->name('destroy');
        });

        // Users & Settings
        Route::view('users', 'pages.superadmin.users')->name('users');
        Route::view('statistics', 'pages.superadmin.statistics')->name('statistics');
        Route::view('settings', 'pages.superadmin.settings')->name('settings');
    }); // end role:superadmin
    // ────────────────────────────────────────────────────────────────────────

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
    Route::view('siswa/qr-barang', 'pages.siswa.announcements')->name('student.qr-barang');
    Route::view('siswa/profil', 'pages.siswa.profile')->name('student.profile');
    
    // Student profile photo routes
    Route::post('siswa/profil/foto', [SettingsController::class, 'updateProfilePhoto'])->name('student.profile.photo.update');
    Route::delete('siswa/profil/foto', [SettingsController::class, 'deleteProfilePhoto'])->name('student.profile.photo.delete');
    Route::post('siswa/profil/phone', [SettingsController::class, 'updatePhone'])->name('student.profile.phone.update');

    // Student QR Code — generate/tampilkan QR untuk peminjaman yang disetujui
    Route::get('siswa/peminjaman/{id}/qrcode', [StudentQRCodeController::class, 'show'])
        ->name('student.qrcode.show');
    Route::get('siswa/peminjaman/{id}/qrcode/data', [StudentQRCodeController::class, 'data'])
        ->name('student.qrcode.data');

    // Admin QR Verification — verifikasi token QR saat scan & konfirmasi pengambilan
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/qr/scan', function() {
            return view('pages.admin.qr-scanner');
        })->name('admin.qr.scan');
        Route::get('admin/qr/verify/{token}', [AdminQRVerificationController::class, 'verify'])->name('admin.qr.verify');
        Route::get('admin/verifikasi-pengambilan/{token}', [AdminQRVerificationController::class, 'verify'])->name('admin.qr.verify.alias');
        Route::post('admin/qr/confirm-checkout/{id}', [AdminQRVerificationController::class, 'confirmCheckout'])->name('admin.qr.confirm-checkout');
        Route::post('admin/qr/reject-checkout/{id}', [AdminQRVerificationController::class, 'rejectCheckout'])->name('admin.qr.reject-checkout');
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

        // Teacher own borrowing routes
        Route::get('peminjaman-guru', [PeminjamanGuruController::class, 'index'])->name('peminjaman-guru');
        Route::get('peminjaman-guru/create', [PeminjamanGuruController::class, 'create'])->name('peminjaman-guru.create');
        Route::post('peminjaman-guru', [PeminjamanGuruController::class, 'store'])->name('peminjaman-guru.store');
        Route::get('peminjaman-guru/{id}/edit', [PeminjamanGuruController::class, 'edit'])->name('peminjaman-guru.edit');
        Route::put('peminjaman-guru/{id}', [PeminjamanGuruController::class, 'update'])->name('peminjaman-guru.update');
        Route::post('peminjaman-guru/{id}/cancel', [PeminjamanGuruController::class, 'cancel'])->name('peminjaman-guru.cancel');

        // Teacher barang catalog route
        Route::get('barang', [BarangController::class, 'index'])->name('barang');

        // Teacher QR code routes
        Route::get('qr-barang', [PeminjamanGuruController::class, 'qrBarang'])->name('qr-barang');
        Route::get('peminjaman-guru/{id}/qr', [TeacherQRCodeController::class, 'show'])->name('qr.show');
        Route::get('peminjaman-guru/{id}/qr/generate', [TeacherQRCodeController::class, 'generate'])->name('qr.generate');

        // Teacher own return routes
        Route::get('pengembalian-guru', [PengembalianGuruController::class, 'index'])->name('pengembalian-guru');
        Route::get('pengembalian-guru/{id}/create', [PengembalianGuruController::class, 'create'])->name('pengembalian-guru.create');
        Route::post('pengembalian-guru/{id}', [PengembalianGuruController::class, 'store'])->name('pengembalian-guru.store');
        Route::get('pengembalian-guru/history', [PengembalianGuruController::class, 'history'])->name('pengembalian-guru.history');

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
    Route::post('guru/laporan/kirim', [TeacherReportSendController::class, 'store'])->name('teacher.reports.send')->middleware('role:guru');
    Route::view('guru/profil', 'pages.guru.profile')->name('teacher.profile');

    // Kepala Jurusan routes
    Route::middleware('role:kepala_jurusan')->prefix('kajur')->name('kajur.')->group(function () {
        Route::get('dashboard', [KepalaJurusanController::class, 'dashboard'])->name('dashboard');
        Route::get('pending-approvals', [KepalaJurusanController::class, 'pendingApprovals'])->name('pending-approvals');
        Route::post('pending-approvals/{id}/approve', [KepalaJurusanController::class, 'approveRequest'])->name('approve-request');
        Route::post('pending-approvals/{id}/reject', [KepalaJurusanController::class, 'rejectRequest'])->name('reject-request');
        Route::get('qr-scanner', [KepalaJurusanController::class, 'qrScanner'])->name('qr-scanner');
        Route::get('qr/verify/{token}', [KepalaJurusanController::class, 'verifyQR'])->name('qr.verify');
        Route::post('qr/confirm-checkout/{id}', [KepalaJurusanController::class, 'confirmCheckout'])->name('qr.confirm-checkout');
        Route::get('pending-returns', [KepalaJurusanController::class, 'pendingReturns'])->name('pending-returns');
        Route::post('pending-returns/{id}/verify', [KepalaJurusanController::class, 'verifyReturn'])->name('verify-return');
        Route::get('history', [KepalaJurusanController::class, 'history'])->name('history');
        Route::get('reporting', [KepalaJurusanController::class, 'reporting'])->name('reporting');
        Route::post('reporting/create', [KepalaJurusanController::class, 'createReport'])->name('create-report');
        // Profil Kepala Jurusan
        Route::get('profil', [KepalaJurusanController::class, 'profile'])->name('profile');
        Route::post('profil/foto', [SettingsController::class, 'updateProfilePhoto'])->name('profile.photo.update');
        Route::delete('profil/foto', [SettingsController::class, 'deleteProfilePhoto'])->name('profile.photo.delete');
        Route::post('profil/phone', [SettingsController::class, 'updatePhone'])->name('profile.phone.update');
    });

    // Teacher profile photo routes
    Route::post('guru/profil/foto', [SettingsController::class, 'updateProfilePhoto'])->name('teacher.profile.photo.update');
    Route::delete('guru/profil/foto', [SettingsController::class, 'deleteProfilePhoto'])->name('teacher.profile.photo.delete');
    Route::post('guru/profil/phone', [SettingsController::class, 'updatePhone'])->name('teacher.profile.phone.update');

    // ─── Notification & Message APIs ───
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // ─── Notification & Message APIs ───
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // ─── SIPINTU Internal API (AJAX dari admin panel) ───
    Route::prefix('api/internal/sipintu')->name('sipintu.')->group(function () {
        Route::get('status',   [SipintuStatusController::class, 'status'])->name('status');
        Route::post('validate', [SipintuStatusController::class, 'validate'])->name('validate');
        Route::get('students', [SipintuStatusController::class, 'students'])->name('students');
        Route::get('teachers', [SipintuStatusController::class, 'teachers'])->name('teachers');
        Route::post('sync',     [SipintuStatusController::class, 'sync'])->name('sync');
    });
});

require __DIR__.'/settings.php';
