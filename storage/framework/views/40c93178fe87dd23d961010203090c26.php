<div>
    <style>
        :root {
            --text-primary: #0f172a;
            --text-secondary: #1e293b;
            --text-muted: #475569;
            --text-subtle: #64748b;
            --bg-card: #ffffff;
            --bg-card-subtle: #f8fafc;
            --border-subtle: #e2e8f0;
            --border-alt: #cbd5e1;
            --card-shadow: 0 1px 3px rgba(0,0,0,.04), 0 4px 12px rgba(0,0,0,.02);
            --emerald-main: #059669;
            --emerald-light: #ecfdf5;
            --emerald-border: #a7f3d0;
        }
        html.dark {
            --text-primary: #f0fdf4;
            --text-secondary: #dcfce7;
            --text-muted: #86efac;
            --text-subtle: #6ee7b7;
            --bg-card: #0f201d;
            --bg-card-subtle: #091210;
            --border-subtle: #1d3d37;
            --border-alt: #162e2a;
            --card-shadow: 0 4px 16px rgba(0,0,0,.35);
            --emerald-main: #10b981;
            --emerald-light: rgba(16,185,129,.15);
            --emerald-border: rgba(16,185,129,.3);
        }

        .db-greeting-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
        .db-greeting-title { font-size: 24px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; letter-spacing: -.02em; }
        .db-greeting-sub   { font-size: 13px; color: var(--text-muted); font-weight: 500; }

        /* Section Header */
        .db-section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .db-section-title-wrap { display: flex; align-items: center; gap: 8px; }
        .db-section-indicator { width: 4px; height: 16px; background: var(--emerald-main); border-radius: 2px; }
        .db-section-title { font-size: 15px; font-weight: 800; color: var(--text-primary); letter-spacing: -.01em; margin: 0; }
        .db-section-tag { font-size: 11px; font-weight: 700; color: var(--emerald-main); background: var(--emerald-light); padding: 3px 9px; border-radius: 999px; border: 1px solid var(--emerald-border); }

        /* Menu Cards Grid */
        .db-menu-grid { 
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 16px; 
        }
        @media (max-width: 1200px) { .db-menu-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px)  { .db-menu-grid { grid-template-columns: 1fr; } }

        .db-menu-card {
            background: var(--bg-card);
            border: 1.5px solid var(--border-subtle);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            text-decoration: none;
            transition: all .22s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }
        .db-menu-card:hover {
            border-color: var(--emerald-main);
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(5, 150, 105, .12);
        }
        
        /* Highlight khusus Permohonan Peminjaman */
        .db-menu-card.card-priority {
            border: 2px solid #10b981;
            background: var(--bg-card);
        }
        .db-menu-card.card-priority::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #10b981;
        }
        .db-menu-card.card-priority:hover {
            border-color: #059669;
            box-shadow: 0 12px 28px rgba(16, 185, 129, .2);
        }

        .db-menu-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform .2s ease;
        }
        .db-menu-card:hover .db-menu-icon {
            transform: scale(1.06);
        }
        .db-menu-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.3;
            letter-spacing: -.01em;
        }
        .db-menu-desc {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.5;
            font-weight: 400;
        }
        .db-menu-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            margin-top: 4px;
            align-self: flex-start;
        }
        .db-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Stat Grid */
        .db-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 24px; }
        @media (max-width: 1024px) { .db-stat-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px)  { .db-stat-grid { grid-template-columns: 1fr; } }

        .db-stat-card {
            background: var(--bg-card); 
            border: 1.5px solid var(--border-subtle);
            border-radius: 16px; 
            padding: 18px 20px;
            display: flex; 
            flex-direction: column;
            justify-content: space-between;
            gap: 14px;
            transition: all .2s ease;
            box-shadow: var(--card-shadow);
        }
        .db-stat-card:hover { 
            border-color: var(--emerald-main); 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(0,0,0,.06);
        }
        .db-stat-top { display: flex; align-items: center; justify-content: space-between; }
        .db-stat-icon-box { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .db-stat-pill { font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 999px; }
        .db-stat-num   { font-size: 26px; font-weight: 800; color: var(--text-primary); line-height: 1; margin-bottom: 4px; letter-spacing: -.02em; }
        .db-stat-label { font-size: 13px; color: var(--text-muted); font-weight: 600; }

        /* Panels & Activity */
        .db-panel { background: var(--bg-card); border: 1.5px solid var(--border-subtle); border-radius: 16px; padding: 20px; box-shadow: var(--card-shadow); margin-bottom: 20px; }
        .db-panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid var(--border-subtle); padding-bottom: 12px; }
        .db-panel-title { font-size: 15px; font-weight: 800; color: var(--text-primary); letter-spacing: -.01em; }
        .db-panel-sub { font-size: 12px; color: var(--text-muted); margin-top: 2px; font-weight: 500; }

        .db-txn-item { background: var(--bg-card-subtle); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 14px 16px; margin-bottom: 10px; transition: border-color .15s; }
        .db-txn-item:last-child { margin-bottom: 0; }
        .db-txn-item:hover { border-color: var(--emerald-main); }
        .db-txn-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 8px; gap: 10px; }
        .db-txn-name { font-size: 13.5px; font-weight: 800; color: var(--text-primary); }
        .db-txn-type { font-size: 11.5px; color: var(--text-muted); margin-top: 2px; }
        .db-txn-meta { display: flex; align-items: center; justify-content: space-between; font-size: 11.5px; color: var(--text-subtle); padding-top: 6px; border-top: 1px dashed var(--border-subtle); }

        .db-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 8px; font-size: 10.5px; font-weight: 800; letter-spacing: .02em; }
        .db-badge-pending { background: rgba(245,158,11,.12); color: #d97706; border: 1px solid rgba(245,158,11,.25); }
        .db-badge-approved { background: rgba(16,185,129,.12); color: #059669; border: 1px solid rgba(16,185,129,.25); }
        .db-badge-borrowed { background: rgba(37,99,235,.12); color: #1d4ed8; border: 1px solid rgba(37,99,235,.25); }
        .db-badge-returned { background: rgba(16,185,129,.12); color: #059669; border: 1px solid rgba(16,185,129,.25); }
        .db-badge-rejected { background: rgba(239,68,68,.12); color: #dc2626; border: 1px solid rgba(239,68,68,.25); }
        
        .db-empty { text-align: center; padding: 40px 20px; color: var(--text-muted); }
        .db-empty-icon { width: 44px; height: 44px; margin: 0 auto 10px; color: var(--text-subtle); opacity: .7; }
        .db-empty-title { font-size: 14px; font-weight: 700; color: var(--text-primary); margin-bottom: 3px; }
        .db-empty-sub { font-size: 12px; color: var(--text-muted); }

        .db-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
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
            <div class="db-greeting-sub"><?php echo e(now()->translatedFormat('l, d F Y')); ?> • Portal Pengelolaan Inventaris Guru Pembimbing</div>
        </div>
    </div>

    
    <div style="margin-bottom:28px">
        <div class="db-section-head">
            <div class="db-section-title-wrap">
                <span class="db-section-indicator"></span>
                <h2 class="db-section-title">Menu Cepat</h2>
            </div>
            <span class="db-section-tag">Akses Utama</span>
        </div>
        <div class="db-menu-grid">
            
            <a href="<?php echo e(route('teacher.requests')); ?>" class="db-menu-card card-priority">
                <div class="db-menu-icon" style="background:rgba(16,185,129,.14);color:#059669">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="db-menu-title">Permohonan Peminjaman</div>
                <div class="db-menu-desc">Kelola dan setujui pengajuan peminjaman siswa bimbingan</div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($pendingRequests) && $pendingRequests > 0): ?>
                <div class="db-menu-badge" style="background:#fef3c7;color:#b45309;border:1px solid #fde68a">
                    <span class="db-badge-dot"></span>
                    <span><?php echo e($pendingRequests); ?> Menunggu Approval</span>
                </div>
                <?php else: ?>
                <div class="db-menu-badge" style="background:#ecfdf5;color:#059669;border:1px solid #a7f3d0">
                    <span class="db-badge-dot"></span>
                    <span>Semua Terproses</span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>

            
            <a href="<?php echo e(route('teacher.students')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(37,99,235,.12);color:#2563eb">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div class="db-menu-title">Siswa Bimbingan</div>
                <div class="db-menu-desc">Pantau profil dan aktivitas siswa bimbingan Anda</div>
            </a>

            
            <a href="<?php echo e(route('teacher.loans')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(2,132,199,.12);color:#0284c7">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                </div>
                <div class="db-menu-title">Peminjaman Aktif</div>
                <div class="db-menu-desc">Monitor aset sarpras yang sedang berstatus dipinjam</div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalBorrowed > 0): ?>
                <div class="db-menu-badge" style="background:rgba(2,132,199,.12);color:#0284c7;border:1px solid rgba(2,132,199,.25)">
                    <span class="db-badge-dot"></span>
                    <span><?php echo e($totalBorrowed); ?> Sedang Dipinjam</span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>

            
            <a href="<?php echo e(route('teacher.returns')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(16,185,129,.12);color:#10b981">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="db-menu-title">Pengembalian</div>
                <div class="db-menu-desc">Verifikasi pengembalian barang dan kondisi aset</div>
            </a>

            
            <a href="<?php echo e(route('teacher.reports')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(147,51,234,.12);color:#9333ea">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="db-menu-title">Laporan & Riwayat</div>
                <div class="db-menu-desc">Rekapitulasi sirkulasi dan catatan log peminjaman</div>
            </a>

            
            <a href="<?php echo e(route('inventory.index')); ?>" class="db-menu-card">
                <div class="db-menu-icon" style="background:rgba(14,116,144,.12);color:#0e7490">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="db-menu-title">Kelola Inventaris</div>
                <div class="db-menu-desc">Katalogisasi aset dan data sarana prasarana sekolah</div>
            </a>
        </div>
    </div>


    
    <div style="margin-bottom:28px">
        <div class="db-section-head">
            <div class="db-section-title-wrap">
                <span class="db-section-indicator"></span>
                <h2 class="db-section-title">Ringkasan Statistik</h2>
            </div>
            <span class="db-section-tag">Data Terintegrasi</span>
        </div>
        <div class="db-stat-grid">
            <div class="db-stat-card">
                <div class="db-stat-top">
                    <div class="db-stat-icon-box" style="background:rgba(37,99,235,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="db-stat-pill" style="background:#eff6ff;color:#1d4ed8">Total Unit</span>
                </div>
                <div>
                    <div class="db-stat-num"><?php echo e($departmentItems); ?></div>
                    <div class="db-stat-label">Total Barang Terdata</div>
                </div>
            </div>
            
            <div class="db-stat-card">
                <div class="db-stat-top">
                    <div class="db-stat-icon-box" style="background:rgba(2,132,199,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#0284c7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <span class="db-stat-pill" style="background:#f0f9ff;color:#0284c7">Sirkulasi Guru</span>
                </div>
                <div>
                    <div class="db-stat-num"><?php echo e($totalBorrowed); ?></div>
                    <div class="db-stat-label">Peminjaman Saya</div>
                </div>
            </div>
            
            <div class="db-stat-card">
                <div class="db-stat-top">
                    <div class="db-stat-icon-box" style="background:rgba(16,185,129,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#10b981" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="db-stat-pill" style="background:#ecfdf5;color:#059669">Selesai</span>
                </div>
                <div>
                    <div class="db-stat-num"><?php echo e($totalReturned); ?></div>
                    <div class="db-stat-label">Barang Dikembalikan</div>
                </div>
            </div>
            
            <div class="db-stat-card">
                <div class="db-stat-top">
                    <div class="db-stat-icon-box" style="background:rgba(147,51,234,.12)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:22px;height:22px;color:#9333ea" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="db-stat-pill" style="background:#faf5ff;color:#7e22ce">Siap Dipinjam</span>
                </div>
                <div>
                    <div class="db-stat-num"><?php echo e($availableItems); ?></div>
                    <div class="db-stat-label">Barang Tersedia</div>
                </div>
            </div>
        </div>
    </div>

    
    <div style="margin-bottom:16px">
        <div class="db-section-head">
            <div class="db-section-title-wrap">
                <span class="db-section-indicator"></span>
                <h2 class="db-section-title">Aktivitas Terkini</h2>
            </div>
            <span class="db-section-tag">Log Transaksi</span>
        </div>
        <div class="db-grid-2">
            
            <div class="db-panel">
                <div class="db-panel-header">
                    <div>
                        <div class="db-panel-title">Peminjaman Saya</div>
                        <div class="db-panel-sub">Daftar transaksi barang yang Anda ajukan</div>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($myBorrowings->count() > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $myBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="db-txn-item">
                        <div class="db-txn-top">
                            <div>
                                <div class="db-txn-name"><?php echo e($borrowing->details->first()?->item->name ?? 'Barang Inventaris'); ?></div>
                                <div class="db-txn-type">Kode Peminjaman #<?php echo e($borrowing->number ?? $borrowing->id); ?></div>
                            </div>
                            <span class="db-badge <?php echo e($borrowing->status === 'borrowed' ? 'db-badge-borrowed' : ($borrowing->status === 'returned' ? 'db-badge-returned' : 'db-badge-pending')); ?>">
                                <?php echo e($borrowing->status === 'borrowed' ? 'DIPINJAM' : ($borrowing->status === 'returned' ? 'KEMBALI' : strtoupper($borrowing->status))); ?>

                            </span>
                        </div>
                        <div class="db-txn-meta">
                            <div style="display:flex;align-items:center;gap:5px;color:var(--text-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php echo e($borrowing->borrowed_at?->diffForHumans() ?? 'Baru saja'); ?>

                            </div>
                            <div style="font-size:11.5px;color:var(--text-subtle)">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($borrowing->return_date): ?>
                                    Batas: <?php echo e($borrowing->return_date->format('d M Y')); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($borrowing->return_time): ?> · <?php echo e($borrowing->return_time); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        <div class="db-empty-title">Belum ada peminjaman aktif</div>
                        <div class="db-empty-sub">Riwayat pengajuan Anda akan muncul di sini</div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="db-panel">
                <div class="db-panel-header">
                    <div>
                        <div class="db-panel-title">Sirkulasi Sekolah</div>
                        <div class="db-panel-sub">Aktivitas peminjaman terbaru di lingkungan sekolah</div>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentDepartmentBorrowings->count() > 0): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentDepartmentBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <div class="db-txn-item">
                        <div class="db-txn-top">
                            <div>
                                <div class="db-txn-name"><?php echo e($borrowing->details->first()?->item->name ?? 'Barang Inventaris'); ?></div>
                                <div class="db-txn-type">Diajukan oleh <?php echo e($borrowing->user->name ?? 'Siswa'); ?></div>
                            </div>
                            <span class="db-badge <?php echo e($borrowing->status === 'borrowed' ? 'db-badge-borrowed' : ($borrowing->status === 'returned' ? 'db-badge-returned' : 'db-badge-pending')); ?>">
                                <?php echo e($borrowing->status === 'borrowed' ? 'DIPINJAM' : ($borrowing->status === 'returned' ? 'KEMBALI' : strtoupper($borrowing->status))); ?>

                            </span>
                        </div>
                        <div class="db-txn-meta">
                            <div style="display:flex;align-items:center;gap:5px;color:var(--text-muted)">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php echo e($borrowing->borrowed_at?->diffForHumans() ?? 'Baru saja'); ?>

                            </div>
                            <div style="font-size:11.5px;color:var(--text-subtle)">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($borrowing->return_date): ?>
                                    Batas: <?php echo e($borrowing->return_date->format('d M Y')); ?>

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
                        <div class="db-empty-title">Belum ada aktivitas baru</div>
                        <div class="db-empty-sub">Menunggu transaksi peminjaman siswa</div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/livewire/teacher-dashboard.blade.php ENDPATH**/ ?>