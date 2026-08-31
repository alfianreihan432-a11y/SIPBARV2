<?php $__env->startSection('title', 'Siswa Bimbingan'); ?>
<?php $__env->startSection('page-heading', 'Siswa Bimbingan'); ?>

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
        flex-direction: column;
        gap: 16px;
    }
    @media (min-width: 768px) {
        .page-header-content {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
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
    .stats-container {
        display: flex;
        gap: 12px;
    }
    .stat-box {
        text-align: center;
        padding: 12px 16px;
        background: var(--bg3);
        border: 1px solid var(--border2);
        border-radius: 10px;
        min-width: 80px;
    }
    .stat-label {
        font-size: 12px;
        color: var(--muted);
        font-weight: 600;
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 20px;
        font-weight: 700;
        color: var(--accent);
    }
    
    .search-box {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .search-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .search-input {
        flex: 1;
        min-width: 200px;
        padding: 10px 14px;
        background: var(--input-bg);
        border: 1px solid var(--border2);
        border-radius: 10px;
        font-size: 14px;
        color: var(--text);
    }
    .search-input::placeholder {
        color: var(--subtle);
    }
    .search-input:focus {
        outline: none;
        border-color: var(--accent);
    }
    .btn {
        padding: 10px 20px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
    
    .students-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .student-card {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        overflow: hidden;
        transition: box-shadow 0.15s;
    }
    .student-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .student-header {
        padding: 20px;
        background: var(--bg3);
        border-bottom: 1px solid var(--border2);
    }
    .student-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .student-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    .student-details {
        flex: 1;
        min-width: 0;
    }
    .student-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .student-email {
        font-size: 12px;
        color: var(--muted);
    }
    .student-stats {
        padding: 16px;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 16px;
    }
    .mini-stat {
        text-align: center;
        padding: 8px;
        background: var(--bg3);
        border-radius: 8px;
    }
    .mini-stat-value {
        font-size: 18px;
        font-weight: 700;
        color: var(--accent);
    }
    .mini-stat-label {
        font-size: 11px;
        color: var(--muted);
        margin-top: 2px;
    }
    .info-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 8px;
    }
    .info-row svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }
    .student-actions {
        padding: 16px;
        border-top: 1px solid var(--border2);
    }
    .btn-full {
        width: 100%;
        justify-content: center;
    }
    
    .pagination-box {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 16px;
    }
    
    .empty-state {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 48px;
        text-align: center;
    }
    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 16px;
        color: var(--subtle);
    }
    .empty-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 8px;
    }
    .empty-text {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 16px;
    }
</style>

<div>
    
    <div class="page-header">
        <div class="page-header-content">
            <div>
                <h1 class="page-title">Siswa Bimbingan</h1>
                <p class="page-subtitle">Daftar siswa yang memilih Anda sebagai guru pembimbing</p>
            </div>
            
            
            <div class="stats-container">
                <?php
                    $totalStudents = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->distinct('user_id')
                        ->count('user_id');
                    $activeToday = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                        ->whereDate('created_at', today())
                        ->distinct('user_id')
                        ->count('user_id');
                ?>
                <div class="stat-box">
                    <p class="stat-label">Total</p>
                    <p class="stat-value"><?php echo e($totalStudents); ?></p>
                </div>
                <div class="stat-box">
                    <p class="stat-label">Aktif Hari Ini</p>
                    <p class="stat-value"><?php echo e($activeToday); ?></p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="search-box">
        <form method="GET" action="<?php echo e(route('teacher.students')); ?>" class="search-form">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                placeholder="Cari nama siswa..." 
                class="search-input">
            <button type="submit" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Cari
            </button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
            <a href="<?php echo e(route('teacher.students')); ?>" class="btn btn-outline">
                Reset
            </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
    </div>

    
    <?php
        $studentsQuery = \App\Models\BorrowingRequest::with(['user'])
            ->where('teacher_id', auth()->id())
            ->select('user_id', \DB::raw('COUNT(*) as total_requests'), \DB::raw('MAX(created_at) as last_activity'))
            ->groupBy('user_id');
        
        if (request('search')) {
            $studentsQuery->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }
        
        $students = $studentsQuery->paginate(12);
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($students->isNotEmpty()): ?>
    <div class="students-grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studentData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php
                $student = $studentData->user ?? null;
                if (!$student) {
                    continue;
                }
                $pending = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                    ->where('user_id', $student->id)
                    ->where('status', 'pending')
                    ->count();
                $active = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                    ->where('user_id', $student->id)
                    ->whereIn('status', ['approved', 'borrowed'])
                    ->count();
                $completed = \App\Models\BorrowingRequest::where('teacher_id', auth()->id())
                    ->where('user_id', $student->id)
                    ->where('status', 'returned')
                    ->count();
            ?>
            
            <div class="student-card">
                <div class="student-header">
                    <div class="student-info">
                        <div class="student-avatar">
                            <?php echo e(strtoupper(substr($student->name, 0, 2))); ?>

                        </div>
                        <div class="student-details">
                            <h3 class="student-name"><?php echo e($student->name); ?></h3>
                            <p class="student-email"><?php echo e($student->email); ?></p>
                        </div>
                    </div>
                </div>

                <div class="student-stats">
                    <div class="stats-grid">
                        <div class="mini-stat">
                            <p class="mini-stat-value"><?php echo e($pending); ?></p>
                            <p class="mini-stat-label">Pending</p>
                        </div>
                        <div class="mini-stat">
                            <p class="mini-stat-value"><?php echo e($active); ?></p>
                            <p class="mini-stat-label">Aktif</p>
                        </div>
                        <div class="mini-stat">
                            <p class="mini-stat-value"><?php echo e($completed); ?></p>
                            <p class="mini-stat-label">Selesai</p>
                        </div>
                    </div>

                    <div class="info-row">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span><strong>Total:</strong> <?php echo e($studentData->total_requests); ?> pengajuan</span>
                    </div>
                    <div class="info-row">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><strong>Terakhir:</strong> <?php echo e(\Carbon\Carbon::parse($studentData->last_activity)->diffForHumans()); ?></span>
                    </div>
                </div>

                <div class="student-actions">
                    <a href="<?php echo e(route('teacher.requests')); ?>?student=<?php echo e($student->id); ?>" class="btn btn-full">
                        Lihat Pengajuan
                    </a>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    
    <div class="pagination-box">
        <?php echo e($students->appends(request()->query())->links()); ?>

    </div>
    <?php else: ?>
    <div class="empty-state">
        <svg class="empty-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h3 class="empty-title">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
                Siswa Tidak Ditemukan
            <?php else: ?>
                Belum Ada Siswa Bimbingan
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </h3>
        <p class="empty-text">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
                Coba ubah kata kunci pencarian Anda
            <?php else: ?>
                Siswa akan muncul setelah mengajukan peminjaman dengan Anda sebagai pembimbing
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
            <a href="<?php echo e(route('teacher.students')); ?>" class="btn">
                Lihat Semua Siswa
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/pages/guru/students.blade.php ENDPATH**/ ?>