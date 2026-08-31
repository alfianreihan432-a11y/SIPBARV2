<div>
    <style>
        :root {
            --text-primary: #0b2220;
            --text-secondary: #163633;
            --text-muted: #3f635f;
            --text-subtle: #5f8783;
            --bg-card: #ffffff;
            --bg-card-subtle: #f2faf8;
            --border-subtle: #cde9e6;
            --border-alt: #b8deda;
            --card-shadow: 0 1px 3px rgba(0,0,0,.05);
            --blue: #1d7068;
        }
        html.dark {
            --text-primary: #f0faf8;
            --text-secondary: #cde9e6;
            --text-muted: #8ec5be;
            --text-subtle: #6ba8a1;
            --bg-card: #142523;
            --bg-card-subtle: #0e1a19;
            --border-subtle: #1c3330;
            --border-alt: #2a4743;
            --card-shadow: 0 1px 3px rgba(0,0,0,.3);
            --blue: #9BCEC1;
        }

        .db-greeting-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
        .db-greeting-title { font-size: 24px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
        .db-greeting-sub   { font-size: 13px; color: var(--text-muted); }

        /* Menu Cards Grid */
        .db-menu-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 14px; 
        }
        @media (max-width: 1200px) { .db-menu-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px)  { .db-menu-grid { grid-template-columns: 1fr; } }

        .db-menu-card {
            background: var(--bg-card);
            border: 2px solid var(--border-subtle);
            border-radius: 14px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            text-decoration: none;
            transition: all .2s ease;
            box-shadow: var(--card-shadow);
            position: relative;
        }
        .db-menu-card:hover {
            border-color: #10b981;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, .15);
        }
        .db-menu-icon {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .db-menu-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.3;
        }
        .db-menu-desc {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.5;
        }
        .db-menu-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 4px;
            align-self: flex-start;
        }

        .db-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        @media (max-width: 1024px) { .db-stat-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px)  { .db-stat-grid { grid-template-columns: 1fr; } }

        .db-stat-card {
            background: var(--bg-card); border: 1px solid var(--border-alt);
            border-radius: 14px; padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: border-color .2s, transform .2s;
            box-shadow: var(--card-shadow);
        }
        .db-stat-card:hover { border-color: var(--blue); transform: translateY(-2px); }
        .db-stat-icon-box { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .db-stat-num   { font-size: 22px; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 3px; }
        .db-stat-label { font-size: 12px; color: var(--text-muted); }
        .db-stat-change { font-size: 11px; font-weight: 600; margin-top: 3px; }

        .db-panel { background: var(--bg-card); border: 1px solid var(--border-alt); border-radius: 14px; padding: 18px 20px; box-shadow: var(--card-shadow); margin-bottom: 20px; }
        .db-panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .db-panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .db-panel-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        .db-txn-item { background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 14px; margin-bottom: 10px; }
        .db-txn-item:last-child { margin-bottom: 0; }
        .db-txn-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 6px; }
        .db-txn-name { font-size: 13px; font-weight: 700; color: var(--text-primary); }
        .db-txn-type { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .db-txn-meta { display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: var(--text-subtle); }

        .db-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; letter-spacing: .03em; }
        .db-badge-pending { background: rgba(245,158,11,.12); color: #f59e0b; border: 1px solid rgba(245,158,11,.2); }
        .db-badge-approved { background: rgba(16,185,129,.12); color: #10b981; border: 1px solid rgba(16,185,129,.2); }
        .db-badge-borrowed { background: rgba(37,99,235,.12); color: #2563eb; border: 1px solid rgba(37,99,235,.2); }
        .db-badge-returned { background: rgba(16,185,129,.12); color: #10b981; border: 1px solid rgba(16,185,129,.2); }
        .db-badge-rejected { background: rgba(239,68,68,.12); color: #ef4444; border: 1px solid rgba(239,68,68,.2); }
        
        .db-empty { text-align: center; padding: 40px 20px; color: var(--text-muted); }
        .db-empty-icon { width: 48px; height: 48px; margin: 0 auto 12px; color: var(--text-subtle); }
        .db-empty-title { font-size: 14px; font-weight: 600; color: var(--text-primary); margin-bottom: 4px; }
        .db-empty-sub { font-size: 12px; color: var(--text-muted); }

        .db-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media (max-width: 900px) { .db-grid-2 { grid-template-columns: 1fr; } }
    </style>

    
    <div class="db-greeting-row">
        <div>
            <div class="db-greeting-title">
                <?php
                    $hour = now()->hour;
                    $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                ?>
                <?php echo e($greet); ?>, <?php echo e(auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Guru'); ?> 👋
            </div>
            <div class="db-greeting-sub"><?php echo e(now()->translatedFormat('l, d F Y')); ?></div>
        </div>
    </div>

    
    <div style="margin-bottom:24px">
        <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px">Menu Cepat</div>
        <div class="db-menu-grid">
            <a href="<?php echo e(route('teacher.requests')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(245,158,11,.12);color:#f59e0b">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="db-menu-title">Permohonan Peminjaman</div>
                <div class="db-menu-desc">Kelola persetujuan peminjaman siswa</div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($pendingRequests) && $pendingRequests > 0): ?>
                <div class="db-menu-badge" style="background:rgba(245,158,11,.12);color:#f59e0b"><?php echo e($pendingRequests); ?> Menunggu</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>

            <a href="<?php echo e(route('teacher.students')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(37,99,235,.12);color:#2563eb">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="db-menu-title">Siswa Bimbingan</div>
                <div class="db-menu-desc">Pantau aktivitas siswa Anda</div>
            </a>

            <a href="<?php echo e(route('teacher.loans')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(2,132,199,.12);color:#0284c7">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <div class="db-menu-title">Peminjaman Aktif</div>
                <div class="db-menu-desc">Monitor barang yang sedang dipinjam</div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalBorrowed > 0): ?>
                <div class="db-menu-badge" style="background:rgba(2,132,199,.12);color:#0284c7"><?php echo e($totalBorrowed); ?> Aktif</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>

            <a href="<?php echo e(route('teacher.returns')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(16,185,129,.12);color:#10b981">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="db-menu-title">Pengembalian</div>
                <div class="db-menu-desc">Kelola pengembalian barang</div>
            </a>

            <a href="<?php echo e(route('teacher.reports')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(147,51,234,.12);color:#9333ea">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="db-menu-title">Laporan</div>
                <div class="db-menu-desc">Lihat statistik dan laporan lengkap</div>
            </a>

            <a href="<?php echo e(route('inventory.index')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(14,116,144,.12);color:#0e7490">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="db-menu-title">Kelola Inventaris</div>
                <div class="db-menu-desc">Tambah dan kelola barang jurusan</div>
            </a>
        </div>
    </div>


    
    <div style="margin-bottom:24px">
        <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px">Ringkasan Statistik</div>
        <div class="db-stat-grid">
            <div class="db-stat-card">
                <div class="db-stat-icon-box" style="background:rgba(37,99,235,.12)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <div class="db-stat-num"><?php echo e($departmentItems); ?></div>
                    <div class="db-stat-label">Total Barang</div>
                    <div class="db-stat-change" style="color:#2563eb">Total keseluruhan</div>
                </div>
            </div>
            
            <div class="db-stat-card">
                <div class="db-stat-icon-box" style="background:rgba(2,132,199,.12)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#0284c7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <div>
                    <div class="db-stat-num"><?php echo e($totalBorrowed); ?></div>
                    <div class="db-stat-label">Peminjaman Saya</div>
                    <div class="db-stat-change" style="color:#0284c7">Real-time</div>
                </div>
            </div>
            
            <div class="db-stat-card">
                <div class="db-stat-icon-box" style="background:rgba(16,185,129,.12)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="db-stat-num"><?php echo e($totalReturned); ?></div>
                    <div class="db-stat-label">Dikembalikan</div>
                    <div class="db-stat-change" style="color:#10b981">Real-time</div>
                </div>
            </div>
            
            <div class="db-stat-card">
                <div class="db-stat-icon-box" style="background:rgba(147,51,234,.12)">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#9333ea" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <div class="db-stat-num"><?php echo e($availableItems); ?></div>
                    <div class="db-stat-label">Barang Tersedia</div>
                    <div class="db-stat-change" style="color:#9333ea">Real-time</div>
                </div>
            </div>
        </div>
    </div>

    
    <div style="margin-bottom:16px">
        <div style="font-size:14px;font-weight:700;color:var(--text-primary);margin-bottom:12px">Aktivitas Terkini</div>
        <div class="db-grid-2">
            
            <div class="db-panel">
                <div class="db-panel-header">
                    <div>
                        <div class="db-panel-title">Peminjaman Saya</div>
                        <div class="db-panel-sub">Riwayat peminjaman terbaru</div>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($myBorrowings->count() > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $myBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="db-txn-item">
                        <div class="db-txn-top">
                            <div>
                                <div class="db-txn-name"><?php echo e($borrowing->details->first()?->item->name ?? 'Unknown Item'); ?></div>
                                <div class="db-txn-type">Peminjaman #<?php echo e($borrowing->number ?? $borrowing->id); ?></div>
                            </div>
                            <span class="db-badge <?php echo e($borrowing->status === 'borrowed' ? 'db-badge-borrowed' : ($borrowing->status === 'returned' ? 'db-badge-returned' : 'db-badge-pending')); ?>">
                                <?php echo e(strtoupper($borrowing->status)); ?>

                            </span>
                        </div>
                        <div class="db-txn-meta">
                            <div style="display:flex;align-items:center;gap:5px;color:var(--text-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php echo e($borrowing->borrowed_at?->diffForHumans() ?? 'Just now'); ?>

                            </div>
                            <div style="font-size:11px;color:var(--text-subtle)">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($borrowing->return_date): ?>
                                    Deadline: <?php echo e($borrowing->return_date->format('d M Y')); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <?php else: ?>
                    <div class="db-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" class="db-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <div class="db-empty-title">Belum ada peminjaman</div>
                        <div class="db-empty-sub">Mulai pinjam barang sekarang</div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="db-panel">
                <div class="db-panel-header">
                    <div>
                        <div class="db-panel-title">Peminjaman Sekolah</div>
                        <div class="db-panel-sub">Aktivitas peminjaman terbaru</div>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentDepartmentBorrowings->count() > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentDepartmentBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="db-txn-item">
                        <div class="db-txn-top">
                            <div>
                                <div class="db-txn-name"><?php echo e($borrowing->details->first()?->item->name ?? 'Unknown Item'); ?></div>
                                <div class="db-txn-type">Oleh <?php echo e($borrowing->user->name ?? 'Unknown User'); ?></div>
                            </div>
                            <span class="db-badge <?php echo e($borrowing->status === 'borrowed' ? 'db-badge-borrowed' : ($borrowing->status === 'returned' ? 'db-badge-returned' : 'db-badge-pending')); ?>">
                                <?php echo e(strtoupper($borrowing->status)); ?>

                            </span>
                        </div>
                        <div class="db-txn-meta">
                            <div style="display:flex;align-items:center;gap:5px;color:var(--text-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php echo e($borrowing->borrowed_at?->diffForHumans() ?? 'Just now'); ?>

                            </div>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <?php else: ?>
                    <div class="db-empty">
                        <svg xmlns="http://www.w3.org/2000/svg" class="db-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <div class="db-empty-title">Belum ada aktivitas</div>
                        <div class="db-empty-sub">Menunggu peminjaman baru</div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\livewire\teacher-dashboard.blade.php ENDPATH**/ ?>