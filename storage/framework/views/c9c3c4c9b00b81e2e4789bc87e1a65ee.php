<div>
    <style>
        .db-greeting-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
        .db-greeting-title { font-size: 24px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
        .db-greeting-sub   { font-size: 13px; color: var(--text-muted); }

        .db-gauge-card {
            background: var(--bg-card); border: 1px solid var(--border-alt);
            border-radius: 16px; padding: 18px 22px; text-align: center; min-width: 160px;
            box-shadow: var(--card-shadow);
        }
        .db-gauge-title { font-size: 11px; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
        .db-gauge-val   { font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 4px 0 2px; }
        .db-gauge-label { font-size: 11px; color: var(--text-muted); }

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

        .db-bottom-grid { display: grid; grid-template-columns: 1fr 1fr 1.2fr; gap: 16px; margin-bottom: 20px; }
        @media (max-width: 1200px) { .db-bottom-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 900px)  { .db-bottom-grid { grid-template-columns: 1fr; } }

        .db-panel { background: var(--bg-card); border: 1px solid var(--border-alt); border-radius: 14px; padding: 18px 20px; box-shadow: var(--card-shadow); }
        .db-panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .db-panel-title { font-size: 14px; font-weight: 700; color: var(--text-primary); }
        .db-panel-sub { font-size: 11px; color: var(--text-muted); margin-top: 2px; }

        .db-txn-item { background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 10px; padding: 12px 14px; margin-bottom: 10px; }
        .db-txn-item:last-child { margin-bottom: 0; }
        .db-txn-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 6px; }
        .db-txn-name { font-size: 13px; font-weight: 700; color: var(--text-primary); }
        .db-txn-type { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
        .db-txn-link-btn {
            width: 28px; height: 28px; border-radius: 8px;
            background: var(--bg-card); border: 1px solid var(--border-alt);
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted); flex-shrink: 0; cursor: pointer;
        }
        .db-txn-meta { display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: var(--text-subtle); }
        .db-txn-user { display: flex; align-items: center; gap: 6px; color: var(--text-muted); }
        .db-txn-avatar { width: 20px; height: 20px; border-radius: 50%; background: var(--blue-dark); display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 700; color: #fff; }

        .db-chart-big-num { font-size: 32px; font-weight: 800; color: var(--text-primary); line-height: 1; }
        .db-chart-big-label { font-size: 11px; color: var(--text-muted); margin-top: 3px; }
        .db-chart-svg { width: 100%; height: 110px; margin-top: 10px; }

        .db-status-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-top: 4px; }
        .db-status-bar-item { text-align: center; }
        .db-bar-wrap { height: 70px; display: flex; align-items: flex-end; justify-content: center; margin-bottom: 6px; }
        .db-bar { width: 32px; border-radius: 6px 6px 0 0; transition: height .6s ease; }
        .db-bar-val   { font-size: 13px; font-weight: 800; color: var(--text-primary); }
        .db-bar-label { font-size: 10px; color: var(--text-muted); margin-top: 2px; }

        /* Hero Section Styles */
        .hero-section {
            position: relative;
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.9), rgba(2, 132, 199, 0.8)),
                        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            border-radius: 16px;
            padding: 40px 20px;
            margin-bottom: 24px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(29, 78, 216, 0.3);
        }
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(29, 78, 216, 0.85), rgba(2, 132, 199, 0.75));
            z-index: 1;
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }
        .hero-title {
            font-size: 36px;
            font-weight: 900;
            margin-bottom: 8px;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.5px;
        }
        .hero-subtitle {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 20px;
            opacity: 0.95;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
        }
        .hero-greeting {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
        }
        .hero-date {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);
        }
        @media (max-width: 768px) {
            .hero-section { padding: 24px 16px; margin-bottom: 16px; border-radius: 14px; }
            .hero-title { font-size: 22px; }
            .hero-subtitle { font-size: 13px; margin-bottom: 14px; }
            .hero-greeting { font-size: 16px; }
            .hero-date { font-size: 12px; }
            .db-status-grid { gap: 6px; }
            .db-bar { width: 24px; }
            .db-panel { padding: 14px 16px; }
            .db-stat-card { padding: 14px 16px; }
        }
    </style>

    
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Sistem Inventaris Modern</h1>
            <p class="hero-subtitle">Kelola Inventaris Lebih Mudah, Cepat & Terorganisir</p>
            <div class="hero-greeting">
                <?php
                    $hour = now()->hour;
                    $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                ?>
                <?php echo e($greet); ?>, <?php echo e(auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Admin'); ?> 👋
            </div>
            <div class="hero-date"><?php echo e(now()->translatedFormat('l, d F Y')); ?></div>
        </div>
    </div>

    
    <div class="db-stat-grid">
        <div class="db-stat-card">
            <div class="db-stat-icon-box" style="background:rgba(37,99,235,.12)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo e($totalItems); ?></div>
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
                <div class="db-stat-num"><?php echo e($borrowedItems); ?></div>
                <div class="db-stat-label">Sedang Dipinjam</div>
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
                <div class="db-stat-num"><?php echo e($availableItems); ?></div>
                <div class="db-stat-label">Barang Tersedia</div>
                <div class="db-stat-change" style="color:#10b981">Real-time</div>
            </div>
        </div>
        
        <div class="db-stat-card">
            <div class="db-stat-icon-box" style="background:rgba(147,51,234,.12)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#9333ea" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo e($totalCategories); ?></div>
                <div class="db-stat-label">Kategori</div>
                <div class="db-stat-change" style="color:#9333ea">Total keseluruhan</div>
            </div>
        </div>
    </div>

    
    <div class="db-bottom-grid">
        
        <div class="db-panel">
            <div class="db-panel-header">
                <div>
                    <div class="db-panel-title">Transaksi Terbaru</div>
                    <div class="db-panel-sub">Peminjaman terbaru</div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="db-txn-item">
                <div class="db-txn-top">
                    <div>
                        <div class="db-txn-name"><?php echo e($borrowing->details->first()?->item->name ?? 'Unknown Item'); ?></div>
                        <div class="db-txn-type">Peminjaman #<?php echo e($borrowing->number ?? $borrowing->id); ?></div>
                    </div>
                    <div class="db-txn-link-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                </div>
                <div class="db-txn-meta">
                    <div style="display:flex;align-items:center;gap:5px;color:var(--text-muted)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php echo e($borrowing->borrowed_at?->diffForHumans() ?? 'Just now'); ?>

                    </div>
                    <div class="db-txn-user">
                        <div class="db-txn-avatar"><?php echo e(strtoupper(substr($borrowing->user->name ?? 'U', 0, 1))); ?></div>
                        <?php echo e($borrowing->user->name ?? 'Unknown User'); ?>

                    </div>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        
        <div class="db-panel">
            <div class="db-panel-header">
                <div>
                    <div class="db-panel-title">Statistik Barang</div>
                    <div class="db-panel-sub">Overview inventaris</div>
                </div>
            </div>
            <div class="db-chart-big-num"><?php echo e($totalItems); ?></div>
            <div class="db-chart-big-label">Total Barang</div>
            <svg class="db-chart-svg" viewBox="0 0 300 110">
                <defs>
                    <linearGradient id="chartGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#1d4ed8" stop-opacity="0.25"/>
                        <stop offset="100%" stop-color="#1d4ed8" stop-opacity="0"/>
                    </linearGradient>
                </defs>
                <path d="M0,110 L0,60 Q30,50 60,55 T120,45 T180,50 T240,40 T300,45 L300,110 Z" fill="url(#chartGrad)"/>
                <path d="M0,60 Q30,50 60,55 T120,45 T180,50 T240,40 T300,45" fill="none" stroke="#1d4ed8" stroke-width="2"/>
            </svg>
        </div>

        
        <div class="db-panel">
            <div class="db-panel-header">
                <div>
                    <div class="db-panel-title">Status Barang</div>
                    <div class="db-panel-sub">Distribusi status</div>
                </div>
            </div>
            <div class="db-status-grid">
                <div class="db-status-bar-item">
                    <div class="db-bar-wrap">
                        <div class="db-bar" style="height:<?php echo e($totalItems > 0 ? ($availableItems / $totalItems * 70) : 0); ?>px;background:#10b981"></div>
                    </div>
                    <div class="db-bar-val"><?php echo e($availableItems); ?></div>
                    <div class="db-bar-label">Tersedia</div>
                </div>
                <div class="db-status-bar-item">
                    <div class="db-bar-wrap">
                        <div class="db-bar" style="height:<?php echo e($totalItems > 0 ? ($borrowedItems / $totalItems * 70) : 0); ?>px;background:#ef4444"></div>
                    </div>
                    <div class="db-bar-val"><?php echo e($borrowedItems); ?></div>
                    <div class="db-bar-label">Dipinjam</div>
                </div>
                <div class="db-status-bar-item">
                    <div class="db-bar-wrap">
                        <div class="db-bar" style="height:<?php echo e($totalItems > 0 ? (($totalItems - $availableItems - $borrowedItems) / $totalItems * 70) : 0); ?>px;background:#f59e0b"></div>
                    </div>
                    <div class="db-bar-val"><?php echo e($totalItems - $availableItems - $borrowedItems); ?></div>
                    <div class="db-bar-label">Lainnya</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/livewire/dashboard.blade.php ENDPATH**/ ?>