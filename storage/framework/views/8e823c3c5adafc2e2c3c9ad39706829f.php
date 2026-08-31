<?php $__env->startSection('title', 'Profil Guru'); ?>
<?php $__env->startSection('page-heading', 'Profil Guru'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .page-header {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    .profile-info h1 {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .profile-info p {
        font-size: 14px;
        color: var(--muted);
    }
    .profile-role {
        display: inline-block;
        padding: 4px 12px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: var(--text);
        margin-top: 8px;
    }

    .profile-section {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    @media (max-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .info-item {
        padding: 16px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 10px;
    }
    .info-label {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 4px;
    }
    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    @media (max-width: 640px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .stat-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .stat-card {
        padding: 20px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 10px;
        text-align: center;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--accent);
    }
    .stat-label {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        margin-top: 4px;
    }

    .btn {
        padding: 10px 20px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn:hover {
        opacity: 0.9;
    }
    .btn-outline {
        background: transparent;
        color: var(--text);
        border: 1px solid var(--border2);
    }
    .btn-outline:hover {
        background: var(--bg3);
    }
</style>

<div>
    
    <div class="page-header">
        <div class="profile-header-content">
            <div class="profile-avatar">
                <?php echo e(auth()->check() ? strtoupper(substr(auth()->user()->name,0,2)) : 'GU'); ?>

            </div>
            <div class="profile-info">
                <h1><?php echo e(auth()->check() ? auth()->user()->name : 'Guru'); ?></h1>
                <p><?php echo e(auth()->check() ? auth()->user()->email : '-'); ?></p>
                <div class="profile-role">Guru Pembimbing</div>
            </div>
        </div>
    </div>

    
    <div class="profile-section">
        <div class="section-title">Statistik Persetujuan</div>
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo e(\App\Models\BorrowingRequest::where('teacher_id', auth()->id())->count()); ?>

                </div>
                <div class="stat-label">Total Permohonan</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo e(\App\Models\BorrowingRequest::where('teacher_id', auth()->id())->where('status', 'pending')->count()); ?>

                </div>
                <div class="stat-label">Menunggu Persetujuan</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo e(\App\Models\BorrowingRequest::where('teacher_id', auth()->id())->where('status', 'approved')->count()); ?>

                </div>
                <div class="stat-label">Disetujui</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">
                    <?php echo e(\App\Models\BorrowingRequest::where('teacher_id', auth()->id())->where('status', 'rejected')->count()); ?>

                </div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>
    </div>

    
    <div class="profile-section">
        <div class="section-title">Informasi Pribadi</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nama Lengkap</div>
                <div class="info-value"><?php echo e(auth()->check() ? auth()->user()->name : '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value"><?php echo e(auth()->check() ? auth()->user()->email : '-'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Role</div>
                <div class="info-value">Guru Pembimbing</div>
            </div>
            <div class="info-item">
                <div class="info-label">Terdaftar Sejak</div>
                <div class="info-value">
                    <?php echo e(auth()->check() ? \Carbon\Carbon::parse(auth()->user()->created_at)->format('d M Y') : '-'); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="profile-section">
        <div class="section-title">Aksi Cepat</div>
        <div style="display:flex;gap:12px;flex-wrap:wrap">
            <a href="<?php echo e(route('teacher.requests')); ?>" class="btn">Lihat Permohonan</a>
            <a href="<?php echo e(route('teacher.loans')); ?>" class="btn btn-outline">Lihat Peminjaman Aktif</a>
            <a href="<?php echo e(route('teacher.returns')); ?>" class="btn btn-outline">Lihat Pengembalian</a>
            <a href="<?php echo e(route('teacher.reports')); ?>" class="btn btn-outline">Lihat Laporan</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/pages/guru/profile.blade.php ENDPATH**/ ?>