<div>
    
    <div class="ta-page-header">
        <div class="ta-header-left">
            <div class="ta-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h1 class="ta-page-title">Persetujuan Peminjaman</h1>
                <p class="ta-page-subtitle">Tinjau dan proses permintaan peminjaman siswa bimbingan</p>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$pendingRequests->isEmpty()): ?>
            <div class="ta-count-badge">
                <span class="ta-count-num"><?php echo e($pendingRequests->count()); ?></span>
                <span class="ta-count-label">Menunggu</span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="ta-alert ta-alert-success">
            <div class="ta-alert-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="ta-alert ta-alert-error">
            <div class="ta-alert-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingRequests->isEmpty()): ?>
        <div class="ta-empty">
            <div class="ta-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="ta-empty-title">Tidak Ada Permintaan</h3>
            <p class="ta-empty-desc">Belum ada permintaan peminjaman yang menunggu persetujuan Anda saat ini.</p>
        </div>
    <?php else: ?>
        <div class="ta-list">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="ta-card">

                    
                    <div class="ta-card-header">
                        <div class="ta-student-info">
                            <div class="ta-student-avatar">
                                <?php echo e(strtoupper(substr($request->user->name ?? 'N', 0, 2))); ?>

                            </div>
                            <div class="ta-student-meta">
                                <h3 class="ta-student-name"><?php echo e($request->user->name ?? 'N/A'); ?></h3>
                                <p class="ta-student-class">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:12px;height:12px;display:inline;margin-right:3px;vertical-align:middle">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <?php echo e($request->user->kelas ?? 'N/A'); ?> &bull; <?php echo e($request->user->jurusan ?? 'N/A'); ?>

                                </p>
                            </div>
                        </div>
                        <span class="ta-status-badge">
                            <span class="ta-status-dot"></span>
                            Menunggu Persetujuan
                        </span>
                    </div>

                    
                    <div class="ta-card-body">
                        
                        <div class="ta-item-highlight">
                            <div class="ta-item-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="ta-item-detail">
                                <span class="ta-field-label">Nama Barang</span>
                                <span class="ta-item-name"><?php echo e($request->item->name ?? 'N/A'); ?></span>
                            </div>
                            <div class="ta-stock-info <?php echo e($request->item && $request->item->available_stock >= $request->quantity ? 'ta-stock-ok' : 'ta-stock-low'); ?>">
                                <span class="ta-field-label">Stok Tersedia</span>
                                <span class="ta-stock-num"><?php echo e($request->item->available_stock ?? 'N/A'); ?> unit</span>
                            </div>
                        </div>

                        
                        <div class="ta-detail-grid">
                            <div class="ta-detail-item">
                                <span class="ta-field-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:11px;height:11px">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    Jumlah
                                </span>
                                <span class="ta-field-value ta-qty"><?php echo e($request->quantity); ?> unit</span>
                            </div>
                            <div class="ta-detail-item">
                                <span class="ta-field-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:11px;height:11px">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Tgl. Peminjaman
                                </span>
                                <span class="ta-field-value"><?php echo e(\Carbon\Carbon::parse($request->borrow_date)->format('d M Y')); ?></span>
                            </div>
                            <div class="ta-detail-item">
                                <span class="ta-field-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:11px;height:11px">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Tgl. Pengembalian
                                </span>
                                <span class="ta-field-value"><?php echo e(\Carbon\Carbon::parse($request->return_date)->format('d M Y')); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->return_time): ?> · <?php echo e($request->return_time); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
                            </div>
                            <div class="ta-detail-item">
                                <span class="ta-field-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:11px;height:11px">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Keperluan
                                </span>
                                <span class="ta-field-value"><?php echo e($request->purpose ?? '-'); ?></span>
                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->notes): ?>
                            <div class="ta-notes">
                                <span class="ta-field-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:11px;height:11px">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Catatan
                                </span>
                                <p class="ta-notes-text"><?php echo e($request->notes); ?></p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div class="ta-card-footer">
                        <form action="<?php echo e(route('teacher.requests.approve', $request->id)); ?>" method="POST" class="ta-btn-wrap">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="ta-btn ta-btn-approve">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Setujui Permintaan
                            </button>
                        </form>
                        <button wire:click="openRejectModal(<?php echo e($request->id); ?>)" class="ta-btn ta-btn-reject">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Tolak
                        </button>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showRejectModal): ?>
        <div class="ta-modal-overlay" wire:click="closeRejectModal">
            <div class="ta-modal" wire:click.stop>
                <form action="<?php echo e(route('teacher.requests.reject', $selectedRequestId)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="ta-modal-header">
                        <div class="ta-modal-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                        <div style="flex:1;min-width:0">
                            <h3 class="ta-modal-title">Tolak Permintaan</h3>
                            <p class="ta-modal-sub">Berikan alasan penolakan yang jelas kepada siswa</p>
                        </div>
                        <button type="button" wire:click="closeRejectModal" class="ta-modal-close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="ta-modal-body">
                        <label class="ta-textarea-label">Alasan Penolakan <span style="color:#ef4444">*</span></label>
                        <textarea name="rejection_reason"
                                  wire:model="rejectionReason"
                                  rows="4"
                                  required
                                  minlength="10"
                                  maxlength="500"
                                  class="ta-textarea"
                                  placeholder="Contoh: Barang sedang dipinjam oleh kelas lain dan belum tersedia untuk tanggal yang diminta..."></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['rejection_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="ta-field-error"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="ta-modal-footer">
                        <button type="button" wire:click="closeRejectModal" class="ta-btn-modal-cancel">Batal</button>
                        <button type="submit" class="ta-btn-modal-reject">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Tolak Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <style>
        /* =========================================
           PAGE HEADER
        ========================================= */
        .ta-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .ta-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .ta-header-icon {
            width: 44px; height: 44px;
            background: #5a9590;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(90,149,144,.22);
        }
        .ta-header-icon svg { width: 22px; height: 22px; color: #fff; }
        .ta-page-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            margin: 0;
        }
        .ta-page-subtitle {
            font-size: 13px;
            color: var(--muted);
            margin: 2px 0 0;
        }
        .ta-count-badge {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            border-radius: 12px;
            padding: 8px 16px;
            box-shadow: 0 4px 12px rgba(245,158,11,.3);
        }
        .ta-count-num { font-size: 22px; font-weight: 800; color: #fff; line-height: 1; }
        .ta-count-label { font-size: 10px; font-weight: 600; color: rgba(255,255,255,.85); text-transform: uppercase; letter-spacing: .5px; }

        /* =========================================
           ALERTS
        ========================================= */
        .ta-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            border-left: 4px solid transparent;
        }
        .ta-alert-success { background: #ecfdf5; border-left-color: #10b981; color: #065f46; }
        .ta-alert-error   { background: #fef2f2; border-left-color: #ef4444; color: #991b1b; }
        html.dark .ta-alert-success { background: rgba(16,185,129,.12); color: #6ee7b7; }
        html.dark .ta-alert-error   { background: rgba(239,68,68,.12); color: #fca5a5; }
        .ta-alert-icon { width: 20px; height: 20px; flex-shrink: 0; }
        .ta-alert-icon svg { width: 20px; height: 20px; }
        .ta-alert p { font-size: 14px; font-weight: 500; margin: 0; line-height: 1.5; }

        /* =========================================
           EMPTY STATE
        ========================================= */
        .ta-empty {
            text-align: center;
            padding: 64px 32px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
        }
        .ta-empty-icon {
            width: 72px; height: 72px;
            background: var(--bg3);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .ta-empty-icon svg { width: 36px; height: 36px; color: var(--muted); }
        .ta-empty-title { font-size: 18px; font-weight: 700; color: var(--text); margin: 0 0 8px; }
        .ta-empty-desc  { font-size: 14px; color: var(--muted); margin: 0 auto; max-width: 340px; }

        /* =========================================
           CARD LIST
        ========================================= */
        .ta-list { display: flex; flex-direction: column; gap: 16px; }

        .ta-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-left: 4px solid #f59e0b;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
            transition: box-shadow .2s, transform .2s;
        }
        .ta-card:hover {
            box-shadow: 0 6px 24px rgba(0,0,0,.1);
            transform: translateY(-1px);
        }
        html.dark .ta-card { box-shadow: 0 1px 4px rgba(0,0,0,.3); }

        /* CARD HEADER */
        .ta-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
        }
        .ta-student-info { display: flex; align-items: center; gap: 12px; min-width: 0; flex: 1; }
        .ta-student-avatar {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: #5a9590;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; font-weight: 800; color: #fff;
            flex-shrink: 0;
        }
        .ta-student-meta { min-width: 0; }
        .ta-student-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            margin: 0 0 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ta-student-class {
            font-size: 12px;
            color: var(--muted);
            margin: 0;
            display: flex;
            align-items: center;
        }
        .ta-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: .4px;
            flex-shrink: 0;
        }
        html.dark .ta-status-badge { background: rgba(245,158,11,.15); color: #fcd34d; border-color: rgba(245,158,11,.3); }
        .ta-status-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #f59e0b;
            animation: ta-pulse 1.8s ease-in-out infinite;
        }
        @keyframes ta-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .5; transform: scale(.85); }
        }

        /* CARD BODY */
        .ta-card-body { padding: 20px; display: flex; flex-direction: column; gap: 16px; }

        /* Item Highlight Bar */
        .ta-item-highlight {
            display: flex;
            align-items: center;
            gap: 14px;
            background: var(--bg3);
            border-radius: 12px;
            padding: 14px 16px;
            flex-wrap: wrap;
        }
        .ta-item-icon {
            width: 40px; height: 40px;
            background: #5a9590;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .ta-item-icon svg { width: 20px; height: 20px; color: #fff; }
        .ta-item-detail { flex: 1; min-width: 100px; }
        .ta-item-name {
            display: block;
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-top: 2px;
        }
        .ta-stock-info { text-align: right; flex-shrink: 0; }
        .ta-stock-ok .ta-stock-num { color: #059669; font-weight: 700; font-size: 15px; display: block; margin-top: 2px; }
        .ta-stock-low .ta-stock-num { color: #dc2626; font-weight: 700; font-size: 15px; display: block; margin-top: 2px; }
        html.dark .ta-stock-ok .ta-stock-num { color: #34d399; }
        html.dark .ta-stock-low .ta-stock-num { color: #f87171; }

        /* Field label */
        .ta-field-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        /* Detail Grid */
        .ta-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        @media (max-width: 520px) {
            .ta-detail-grid { grid-template-columns: 1fr; }
        }
        .ta-detail-item {
            background: var(--bg3);
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .ta-field-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }
        .ta-qty {
            color: #5a9590;
            font-size: 16px;
            font-weight: 800;
        }
        html.dark .ta-qty { color: #9BCEC1; }

        /* Notes */
        .ta-notes {
            background: var(--bg3);
            border-radius: 10px;
            padding: 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .ta-notes-text {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.6;
            margin: 0;
        }

        /* CARD FOOTER */
        .ta-card-footer {
            display: flex;
            gap: 10px;
            padding: 14px 20px;
            background: var(--bg3);
            border-top: 1px solid var(--border);
        }
        .ta-btn-wrap { flex: 1; }

        .ta-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all .15s;
            width: 100%;
        }
        .ta-btn-approve {
            background: #5a9590;
            color: #fff;
            box-shadow: 0 3px 10px rgba(90,149,144,.22);
        }
        .ta-btn-approve:hover {
            background: #2d5550;
            box-shadow: 0 5px 16px rgba(90,149,144,.32);
            transform: translateY(-1px);
        }
        .ta-btn-reject {
            flex: 0 0 auto;
            width: auto;
            background: #fff;
            color: #dc2626;
            border: 2px solid #fca5a5;
            padding: 10px 18px;
        }
        .ta-btn-reject:hover { background: #fef2f2; border-color: #ef4444; }
        html.dark .ta-btn-reject { background: rgba(239,68,68,.1); color: #f87171; border-color: rgba(239,68,68,.3); }
        html.dark .ta-btn-reject:hover { background: rgba(239,68,68,.2); }

        /* =========================================
           MODAL
        ========================================= */
        .ta-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }
        .ta-modal {
            background: var(--card);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(0,0,0,.2);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            animation: ta-modal-in .2s ease-out;
        }
        @keyframes ta-modal-in {
            from { opacity: 0; transform: scale(.96) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .ta-modal-header {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
        }
        .ta-modal-icon {
            width: 42px; height: 42px;
            background: #fef2f2;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        html.dark .ta-modal-icon { background: rgba(239,68,68,.15); }
        .ta-modal-icon svg { width: 20px; height: 20px; color: #ef4444; }
        .ta-modal-title { font-size: 17px; font-weight: 800; color: var(--text); margin: 0 0 2px; }
        .ta-modal-sub   { font-size: 12px; color: var(--muted); margin: 0; }
        .ta-modal-close {
            margin-left: auto;
            width: 34px; height: 34px;
            border: none; background: var(--bg3);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: var(--muted);
            transition: all .15s;
            flex-shrink: 0;
        }
        .ta-modal-close:hover { background: var(--border); color: var(--text); }
        .ta-modal-close svg { width: 16px; height: 16px; }
        .ta-modal-body { padding: 20px 22px; }
        .ta-textarea-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 8px;
        }
        .ta-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid var(--border2);
            border-radius: 12px;
            background: var(--input-bg);
            color: var(--text);
            font-size: 14px;
            font-family: inherit;
            line-height: 1.6;
            resize: none;
            outline: none;
            transition: border-color .15s;
        }
        .ta-textarea:focus { border-color: #ef4444; }
        .ta-field-error { font-size: 12px; color: #ef4444; margin: 6px 0 0; }
        .ta-modal-footer {
            display: flex;
            gap: 10px;
            padding: 16px 22px;
            border-top: 1px solid var(--border);
        }
        .ta-btn-modal-cancel {
            flex: 1;
            padding: 10px 18px;
            border: 2px solid var(--border2);
            border-radius: 10px;
            background: transparent;
            color: var(--text2);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
        }
        .ta-btn-modal-cancel:hover { background: var(--bg3); }
        .ta-btn-modal-reject {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 18px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(220,38,38,.3);
            transition: all .15s;
        }
        .ta-btn-modal-reject:hover {
            background: linear-gradient(135deg, #b91c1c, #dc2626);
            transform: translateY(-1px);
        }
    </style>
</div>
<?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/livewire/teacher-approval.blade.php ENDPATH**/ ?>