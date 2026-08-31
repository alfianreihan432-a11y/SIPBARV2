<div>
    <style>
        .db-greeting-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
        .db-greeting-title { font-size: 24px; font-weight: 800; color: var(--text); margin-bottom: 4px; }
        .db-greeting-sub   { font-size: 13px; color: var(--muted); }

        .db-stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        @media (max-width: 1024px) { .db-stat-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px)  { .db-stat-grid { grid-template-columns: 1fr; } }

        .db-stat-card {
            background: var(--card); border: 1px solid var(--border2);
            border-radius: 14px; padding: 18px 20px;
            display: flex; align-items: center; gap: 14px;
            transition: border-color .2s, transform .2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .db-stat-card:hover { border-color: var(--primary); transform: translateY(-2px); }
        .db-stat-icon-box { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .db-stat-num   { font-size: 22px; font-weight: 800; color: var(--text); line-height: 1; margin-bottom: 3px; }
        .db-stat-label { font-size: 12px; color: var(--muted); }
        .db-stat-change { font-size: 11px; font-weight: 600; margin-top: 3px; }

        .db-panel { background: var(--card); border: 1px solid var(--border2); border-radius: 14px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .db-panel-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
        .db-panel-title { font-size: 14px; font-weight: 700; color: var(--text); }
        .db-panel-sub { font-size: 11px; color: var(--muted); margin-top: 2px; }

        .db-txn-item { background: var(--bg3); border: 1px solid var(--border2); border-radius: 10px; padding: 12px 14px; margin-bottom: 10px; }
        .db-txn-item:last-child { margin-bottom: 0; }
        .db-txn-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 6px; }
        .db-txn-name { font-size: 13px; font-weight: 700; color: var(--text); }
        .db-txn-type { font-size: 11px; color: var(--muted); margin-top: 2px; }
        .db-txn-meta { display: flex; align-items: center; justify-content: space-between; font-size: 11px; color: var(--subtle); }

        .db-badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 600; letter-spacing: .03em; }
        .db-badge-pending { background: rgba(100,116,139,.12); color: #64748b; border: 1px solid rgba(100,116,139,.2); }
        .db-badge-approved { background: rgba(37,99,235,.1); color: #2563eb; border: 1px solid rgba(37,99,235,.2); }
        .db-badge-borrowed { background: rgba(37,99,235,.1); color: #2563eb; border: 1px solid rgba(37,99,235,.2); }
        .db-badge-returned { background: rgba(100,116,139,.12); color: #64748b; border: 1px solid rgba(100,116,139,.2); }
        .db-badge-rejected { background: rgba(239,68,68,.1); color: #dc2626; border: 1px solid rgba(239,68,68,.2); }

        .db-empty { text-align: center; padding: 40px 20px; color: var(--muted); }
        .db-empty-icon { width: 48px; height: 48px; margin: 0 auto 12px; color: var(--subtle); }
        .db-empty-title { font-size: 14px; font-weight: 600; color: var(--text); margin-bottom: 4px; }
        .db-empty-sub { font-size: 12px; color: var(--muted); }

        .db-btn-primary {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; background: var(--primary-dark); color: #fff;
            border-radius: 8px; font-size: 12px; font-weight: 600;
            border: none; cursor: pointer; transition: all .2s;
            box-shadow: 0 2px 8px rgba(37,99,235,.25);
        }
        .db-btn-primary:hover { background: var(--primary); transform: translateY(-1px); }

        .db-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); display: flex; align-items: center; justify-content: center; z-index: 50; }
        .db-modal { background: var(--card); border-radius: 16px; padding: 24px; max-width: 400px; width: calc(100% - 32px); box-shadow: 0 20px 40px rgba(0,0,0,.3); }
        .db-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .db-modal-title { font-size: 16px; font-weight: 700; color: var(--text); }
        .db-modal-close { width: 32px; height: 32px; border-radius: 8px; background: var(--bg3); border: 1px solid var(--border2); display: flex; align-items: center; justify-content: center; color: var(--muted); cursor: pointer; transition: all .2s; }
        .db-modal-close:hover { background: var(--bg2); color: var(--text); }
        .db-modal-content { text-align: center; }
        .db-modal-qr { margin: 0 auto 16px; border-radius: 12px; overflow: hidden; max-width: 100%; }
        .db-modal-info { font-size: 13px; color: var(--muted); margin-bottom: 8px; }
        .db-modal-code { font-family: monospace; font-size: 14px; font-weight: 700; color: var(--text); margin-bottom: 12px; }
        .db-modal-note { font-size: 11px; color: var(--subtle); padding: 12px; background: var(--bg3); border-radius: 8px; border: 1px solid var(--border2); }
    </style>

    
    <div class="db-greeting-row">
        <div>
            <div class="db-greeting-title">
                <?php
                    $hour = now()->hour;
                    $greet = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
                ?>
                <?php echo e($greet); ?>, <?php echo e(auth()->check() ? explode(' ', auth()->user()->name)[0] : 'Siswa'); ?>

            </div>
            <div class="db-greeting-sub"><?php echo e(now()->translatedFormat('l, d F Y')); ?></div>
        </div>
    </div>

    
    <div class="db-stat-grid">
        <div class="db-stat-card">
            <div class="db-stat-icon-box" style="background:rgba(100,116,139,.12)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#64748b" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo e($totalBorrowed); ?></div>
                <div class="db-stat-label">Sedang Dipinjam</div>
            </div>
        </div>
        
        <div class="db-stat-card">
            <div class="db-stat-icon-box" style="background:rgba(100,116,139,.12)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#64748b" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo e($totalReturned); ?></div>
                <div class="db-stat-label">Sudah Dikembalikan</div>
            </div>
        </div>
        
        <div class="db-stat-card">
            <div class="db-stat-icon-box" style="background:rgba(100,116,139,.12)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#64748b" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo e($pendingRequests); ?></div>
                <div class="db-stat-label">Menunggu Persetujuan</div>
            </div>
        </div>
        
        <div class="db-stat-card">
            <div class="db-stat-icon-box" style="background:rgba(37,99,235,.1)">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:#2563eb" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <div class="db-stat-num"><?php echo e($availableItems); ?></div>
                <div class="db-stat-label">Barang Tersedia</div>
            </div>
        </div>
    </div>

    
    <div class="db-panel">
        <div class="db-panel-header">
            <div>
                <div class="db-panel-title">Peminjaman Saya</div>
                <div class="db-panel-sub">Riwayat peminjaman terbaru</div>
            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($myRequests->count() > 0): ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $myRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="db-txn-item">
                <div class="db-txn-top">
                    <div>
                        <div class="db-txn-name"><?php echo e($request->item?->name ?? 'Barang tidak tersedia'); ?></div>
                        <div class="db-txn-type">Peminjaman #<?php echo e($request->id); ?></div>
                    </div>
                    <span class="db-badge 
                        <?php if($request->status === 'pending'): ?> db-badge-pending
                        <?php elseif($request->status === 'approved' || $request->status === 'qr_ready'): ?> db-badge-approved
                        <?php elseif($request->status === 'rejected'): ?> db-badge-rejected
                        <?php elseif($request->status === 'borrowed'): ?> db-badge-borrowed
                        <?php elseif($request->status === 'returned'): ?> db-badge-returned
                        <?php else: ?> db-badge-pending <?php endif; ?>">
                        <?php echo e(strtoupper($request->status)); ?>

                    </span>
                </div>
                <div class="db-txn-meta">
                    <div style="display:flex;align-items:center;gap:5px;color:var(--text-muted)">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <?php echo e($request->created_at->diffForHumans()); ?>

                    </div>
                    <div style="font-size:11px;color:var(--text-subtle)">
                        Qty: <?php echo e($request->quantity); ?> | <?php echo e($request->borrow_date->format('d M Y')); ?> - <?php echo e($request->return_date->format('d M Y')); ?>

                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->status === 'qr_ready' && $request->qrCode): ?>
                    <div style="margin-top:10px">
                        <button wire:click="showQRCode(<?php echo e($request->id); ?>)" class="db-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            Tampilkan QR Code
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showQRModal && $selectedQR): ?>
        <div class="db-modal-overlay" wire:click="closeQRModal">
            <div class="db-modal" wire:click.stop>
                <div class="db-modal-header">
                    <div class="db-modal-title">QR Code Peminjaman</div>
                    <button wire:click="closeQRModal" class="db-modal-close">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="db-modal-content">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedQR->image_path): ?>
                        <img src="<?php echo e(asset('storage/' . $selectedQR->image_path)); ?>" alt="QR Code" class="db-modal-qr">
                    <?php else: ?>
                        <div style="background:var(--bg-card-subtle);border-radius:12px;padding:48px;margin-bottom:16px;border:1px solid var(--border-subtle)">
                            <p style="color:var(--text-muted)">QR Code Image</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="db-modal-code"><?php echo e($selectedQR->code); ?></div>
                    <div class="db-modal-info">
                        Berlaku sampai: <?php echo e($selectedQR->expires_at ? $selectedQR->expires_at->format('d M Y H:i') : 'Tidak terbatas'); ?>

                    </div>
                    <div class="db-modal-note">
                        💡 Tunjukkan QR Code ini kepada admin saat mengambil barang
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php $__sessionArgs = ['success'];
if (session()->has($__sessionArgs[0])) :
if (isset($value)) { $__sessionPrevious[] = $value; }
$value = session()->get($__sessionArgs[0]); ?>
        <div style="margin-top:16px;padding:14px 18px;background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.2);color:#10b981;border-radius:12px;font-size:13px">
            ✓ <?php echo e(session('success')); ?>

        </div>
    <?php unset($value);
if (isset($__sessionPrevious) && !empty($__sessionPrevious)) { $value = array_pop($__sessionPrevious); }
if (isset($__sessionPrevious) && empty($__sessionPrevious)) { unset($__sessionPrevious); }
endif;
unset($__sessionArgs); ?>

    <?php $__sessionArgs = ['error'];
if (session()->has($__sessionArgs[0])) :
if (isset($value)) { $__sessionPrevious[] = $value; }
$value = session()->get($__sessionArgs[0]); ?>
        <div style="margin-top:16px;padding:14px 18px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);color:#ef4444;border-radius:12px;font-size:13px">
            ✕ <?php echo e(session('error')); ?>

        </div>
    <?php unset($value);
if (isset($__sessionPrevious) && !empty($__sessionPrevious)) { $value = array_pop($__sessionPrevious); }
if (isset($__sessionPrevious) && empty($__sessionPrevious)) { unset($__sessionPrevious); }
endif;
unset($__sessionArgs); ?>
</div>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\livewire\student-dashboard.blade.php ENDPATH**/ ?>