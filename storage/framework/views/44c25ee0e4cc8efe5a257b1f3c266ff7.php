<?php $__env->startSection('title', 'Laporan Peminjaman'); ?>
<?php $__env->startSection('page-heading', 'Laporan Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .page-header {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .page-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .page-subtitle {
        font-size: 14px;
        color: var(--muted);
    }
    .page-icon {
        width: 48px;
        height: 48px;
        background: var(--bg3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 20px;
    }
    .stat-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .stat-icon {
        width: 40px;
        height: 40px;
        background: var(--bg3);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .stat-desc {
        font-size: 13px;
        color: var(--muted);
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    .content-card {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
    }
    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
    }
    
    .status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 10px;
        margin-bottom: 8px;
    }
    .status-row:last-child {
        margin-bottom: 0;
    }
    .status-row-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .status-icon {
        width: 32px;
        height: 32px;
        background: var(--bg3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .status-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
    }
    .status-count {
        font-size: 20px;
        font-weight: 700;
        color: var(--accent);
    }
    
    .monthly-stat {
        text-align: center;
        padding: 24px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 12px;
        margin-bottom: 16px;
    }
    .monthly-value {
        font-size: 36px;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 8px;
    }
    .monthly-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
    }
    .rate-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    .rate-card {
        text-align: center;
        padding: 16px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 10px;
    }
    .rate-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .rate-label {
        font-size: 11px;
        color: var(--muted);
    }
    
    .activity-list {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        overflow: hidden;
    }
    .activity-header {
        padding: 20px;
        border-bottom: 1px solid var(--border2);
    }
    .activity-header-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .activity-header-subtitle {
        font-size: 13px;
        color: var(--muted);
    }
    .activity-item {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border2);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .activity-item:last-child {
        border-bottom: none;
    }
    .activity-item:hover {
        background: var(--bg3);
    }
    .activity-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    .activity-avatar {
        width: 40px;
        height: 40px;
        background: var(--bg3);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .activity-info {
        flex: 1;
    }
    .activity-name {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 2px;
    }
    .activity-detail {
        font-size: 12px;
        color: var(--muted);
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
    }
    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }
    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }
    .status-borrowed {
        background: #dbeafe;
        color: #1e40af;
    }
    .status-returned {
        background: #f3f4f6;
        color: #374151;
    }
    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }
</style>

<div>
    
    <div class="page-header">
        <div class="page-header-content">
            <div>
                <h1 class="page-title">Laporan & Statistik</h1>
                <p class="page-subtitle">Ringkasan peminjaman siswa bimbingan Anda</p>
            </div>
            <div class="page-icon">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:var(--accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <?php
        $teacherId = auth()->id();
        
        // Summary statistics
        $totalRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->count();
        $pendingRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'pending')->count();
        $approvedRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->whereIn('status', ['approved', 'borrowed'])->count();
        $completedRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'returned')->count();
        $rejectedRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)->where('status', 'rejected')->count();
        
        // This month
        $thisMonthRequests = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Active students
        $activeStudents = \App\Models\BorrowingRequest::where('teacher_id', $teacherId)
            ->distinct('user_id')
            ->count('user_id');
    ?>

    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="stat-label">Total</span>
            </div>
            <p class="stat-value"><?php echo e($totalRequests); ?></p>
            <p class="stat-desc">Total Pengajuan</p>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="stat-label">Pending</span>
            </div>
            <p class="stat-value"><?php echo e($pendingRequests); ?></p>
            <p class="stat-desc">Menunggu Persetujuan</p>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="stat-label">Done</span>
            </div>
            <p class="stat-value"><?php echo e($completedRequests); ?></p>
            <p class="stat-desc">Selesai</p>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#8b5cf6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="stat-label">Students</span>
            </div>
            <p class="stat-value"><?php echo e($activeStudents); ?></p>
            <p class="stat-desc">Siswa Aktif</p>
        </div>
    </div>

    
    <div class="content-grid">
        
        <div class="content-card">
            <h2 class="card-title">Distribusi Status</h2>
            <div class="status-row">
                <div class="status-row-left">
                    <div class="status-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#f59e0b" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="status-name">Pending</span>
                </div>
                <span class="status-count"><?php echo e($pendingRequests); ?></span>
            </div>

            <div class="status-row">
                <div class="status-row-left">
                    <div class="status-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#3b82f6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <span class="status-name">Aktif</span>
                </div>
                <span class="status-count"><?php echo e($approvedRequests); ?></span>
            </div>

            <div class="status-row">
                <div class="status-row-left">
                    <div class="status-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="status-name">Selesai</span>
                </div>
                <span class="status-count"><?php echo e($completedRequests); ?></span>
            </div>

            <div class="status-row">
                <div class="status-row-left">
                    <div class="status-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;color:#ef4444" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="status-name">Ditolak</span>
                </div>
                <span class="status-count"><?php echo e($rejectedRequests); ?></span>
            </div>
        </div>

        
        <div class="content-card">
            <h2 class="card-title">Statistik Bulan Ini</h2>
            <div class="monthly-stat">
                <p class="monthly-value"><?php echo e($thisMonthRequests); ?></p>
                <p class="monthly-label">Pengajuan Bulan <?php echo e(now()->format('F')); ?></p>
            </div>

            <div class="rate-grid">
                <div class="rate-card">
                    <p class="rate-value"><?php echo e(round($totalRequests > 0 ? ($completedRequests / $totalRequests) * 100 : 0)); ?>%</p>
                    <p class="rate-label">Completion Rate</p>
                </div>
                <div class="rate-card">
                    <p class="rate-value"><?php echo e(round($totalRequests > 0 ? ($rejectedRequests / $totalRequests) * 100 : 0)); ?>%</p>
                    <p class="rate-label">Rejection Rate</p>
                </div>
            </div>
        </div>
    </div>

    
    <?php
        $recentActivity = \App\Models\BorrowingRequest::with(['user', 'item'])
            ->where('teacher_id', $teacherId)
            ->latest()
            ->take(10)
            ->get();
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentActivity->isNotEmpty()): ?>
    <div class="activity-list">
        <div class="activity-header">
            <h2 class="activity-header-title">Aktivitas Terakhir</h2>
            <p class="activity-header-subtitle">10 transaksi terakhir dari siswa bimbingan Anda</p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="activity-item">
            <div class="activity-left">
                <div class="activity-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--accent)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="activity-info">
                    <p class="activity-name"><?php echo e($activity->user->name ?? 'N/A'); ?></p>
                    <p class="activity-detail"><?php echo e($activity->item->name ?? 'N/A'); ?> • <?php echo e($activity->created_at->diffForHumans()); ?></p>
                </div>
            </div>
            <span class="status-badge
                <?php if($activity->status === 'pending'): ?> status-pending
                <?php elseif($activity->status === 'approved'): ?> status-approved
                <?php elseif($activity->status === 'borrowed'): ?> status-borrowed
                <?php elseif($activity->status === 'returned'): ?> status-returned
                <?php elseif($activity->status === 'rejected'): ?> status-rejected
                <?php endif; ?>">
                <?php echo e($activity->status_label); ?>

            </span>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/pages/guru/reports.blade.php ENDPATH**/ ?>