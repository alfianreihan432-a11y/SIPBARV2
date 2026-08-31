<?php $__env->startSection('title', 'Verifikasi Pengembalian Barang – SIPBAR Admin'); ?>
<?php $__env->startSection('page-heading', 'Verifikasi Pengembalian'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Stats Bar */
    .admin-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    @media (max-width: 1024px) { .admin-stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .admin-stats-grid { grid-template-columns: 1fr; } }

    .stat-card-admin {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: var(--card-shadow);
        transition: transform .15s;
    }
    .stat-card-admin:hover { transform: translateY(-2px); }
    .stat-card-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
    .stat-card-value { font-size: 22px; font-weight: 800; color: var(--text-primary); margin-top: 3px; }
    .stat-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Filters & Search Toolbar */
    .toolbar-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        box-shadow: var(--card-shadow);
    }
    .status-filter-pills {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .pill-btn {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        color: var(--text-muted);
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        transition: all .15s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .pill-btn:hover {
        background: var(--bg-hover);
        color: var(--text-primary);
    }
    .pill-btn.active {
        background: var(--blue-dark);
        color: #fff;
        border-color: var(--blue-dark);
    }
    .pill-badge {
        font-size: 10px;
        font-weight: 800;
        padding: 1px 6px;
        border-radius: 999px;
        background: rgba(255,255,255,0.2);
    }
    .pill-btn:not(.active) .pill-badge {
        background: var(--bg-hover);
        color: var(--text-muted);
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .search-input-wrap {
        display: flex;
        align-items: center;
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        border-radius: 8px;
        padding: 6px 12px;
        gap: 6px;
        width: 240px;
    }
    .search-input-wrap input {
        background: none;
        border: none;
        outline: none;
        font-size: 12px;
        color: var(--text-primary);
        width: 100%;
    }
    .search-input-wrap input::placeholder { color: var(--text-muted); }

    /* Returns Table Panel */
    .table-panel {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: var(--card-shadow);
    }
    .table-responsive {
        overflow-x: auto;
    }
    .returns-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }
    .returns-table th {
        background: var(--table-head-bg);
        color: var(--text-muted);
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 14px 16px;
        text-align: left;
        border-bottom: 1px solid var(--border-alt);
    }
    .returns-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-alt);
        color: var(--text-primary);
        vertical-align: middle;
    }
    .returns-table tr:hover td {
        background: var(--table-hover);
    }
    .returns-table tr:last-child td {
        border-bottom: none;
    }

    /* Badges */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .badge-menunggu {
        background: rgba(245, 158, 11, 0.15);
        color: #f59e0b;
        border: 1px solid rgba(245, 158, 11, 0.3);
    }
    .badge-disetujui {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .badge-ditolak {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .badge-kondisi {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 5px;
        background: var(--bg-card-subtle);
        color: var(--text-secondary);
        border: 1px solid var(--border-subtle);
    }

    .photo-thumb {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        border: 1px solid var(--border-alt);
        transition: transform .15s;
    }
    .photo-thumb:hover { transform: scale(1.1); }

    /* Action Buttons */
    .btn-approve {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #10b981;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-approve:hover { background: #059669; }

    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: transparent;
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
        font-size: 11px;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all .15s;
    }
    .btn-reject:hover {
        background: rgba(239, 68, 68, 0.1);
        border-color: #ef4444;
    }

    /* Modal Styles */
    .admin-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .admin-modal.active { display: flex; }
    .modal-box {
        background: var(--bg-card);
        border: 1px solid var(--border-alt);
        border-radius: 16px;
        max-width: 520px;
        width: 100%;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        position: relative;
    }
    .modal-box-title { font-size: 16px; font-weight: 800; color: var(--text-primary); margin-bottom: 6px; }
    .modal-box-sub { font-size: 12px; color: var(--text-muted); margin-bottom: 18px; }

    .form-textarea {
        width: 100%;
        background: var(--input-bg);
        border: 1.5px solid var(--border-alt);
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
        color: var(--text-primary);
        outline: none;
        font-family: inherit;
        transition: border-color .2s;
    }
    .form-textarea:focus {
        border-color: var(--blue);
    }

    .modal-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 18px;
    }
    .btn-cancel {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: var(--text-muted);
        background: var(--bg-card-subtle);
        border: 1px solid var(--border-subtle);
        cursor: pointer;
    }
    .btn-submit-reject {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        background: #ef4444;
        border: none;
        cursor: pointer;
    }
    .btn-submit-reject:hover { background: #dc2626; }
</style>

<div>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; padding: 12px 18px; border-radius: 10px; margin-bottom: 16px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;flex-shrink:0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="admin-stats-grid">
        <div class="stat-card-admin">
            <div>
                <div class="stat-card-label">Semua Pengajuan</div>
                <div class="stat-card-value"><?php echo e($countSemua); ?></div>
            </div>
            <div class="stat-card-icon" style="background: rgba(59, 130, 246, 0.15); color: #3b82f6;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>

        <div class="stat-card-admin">
            <div>
                <div class="stat-card-label">Menunggu Verifikasi</div>
                <div class="stat-card-value" style="color: #f59e0b;"><?php echo e($countMenunggu); ?></div>
            </div>
            <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.15); color: #f59e0b;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card-admin">
            <div>
                <div class="stat-card-label">Disetujui</div>
                <div class="stat-card-value" style="color: #10b981;"><?php echo e($countDisetujui); ?></div>
            </div>
            <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <div class="stat-card-admin">
            <div>
                <div class="stat-card-label">Ditolak</div>
                <div class="stat-card-value" style="color: #ef4444;"><?php echo e($countDitolak); ?></div>
            </div>
            <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </div>
    </div>

    
    <div class="toolbar-panel">
        <div class="status-filter-pills">
            <a href="<?php echo e(route('returns.index', ['status' => 'semua', 'q' => $search])); ?>" class="pill-btn <?php echo e($status === 'semua' ? 'active' : ''); ?>">
                Semua
                <span class="pill-badge"><?php echo e($countSemua); ?></span>
            </a>
            <a href="<?php echo e(route('returns.index', ['status' => 'menunggu', 'q' => $search])); ?>" class="pill-btn <?php echo e($status === 'menunggu' ? 'active' : ''); ?>">
                Menunggu Verifikasi
                <span class="pill-badge"><?php echo e($countMenunggu); ?></span>
            </a>
            <a href="<?php echo e(route('returns.index', ['status' => 'disetujui', 'q' => $search])); ?>" class="pill-btn <?php echo e($status === 'disetujui' ? 'active' : ''); ?>">
                Disetujui
                <span class="pill-badge"><?php echo e($countDisetujui); ?></span>
            </a>
            <a href="<?php echo e(route('returns.index', ['status' => 'ditolak', 'q' => $search])); ?>" class="pill-btn <?php echo e($status === 'ditolak' ? 'active' : ''); ?>">
                Ditolak
                <span class="pill-badge"><?php echo e($countDitolak); ?></span>
            </a>
        </div>

        <form method="GET" action="<?php echo e(route('returns.index')); ?>" class="search-form">
            <input type="hidden" name="status" value="<?php echo e($status); ?>">
            <div class="search-input-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;color:var(--text-muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="Cari siswa atau barang...">
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search): ?>
                <a href="<?php echo e(route('returns.index', ['status' => $status])); ?>" style="font-size: 11px; color: var(--text-muted); text-decoration: none;">Reset</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
    </div>

    
    <div class="table-panel">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($returns->count() > 0): ?>
            <div class="table-responsive">
                <table class="returns-table">
                    <thead>
                        <tr>
                            <th>Siswa Peminjam</th>
                            <th>Barang</th>
                            <th>Kondisi & Bukti</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ret): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php
                                $borrowing = $ret->borrowingRequest;
                                $item = $borrowing?->item;
                                $user = $ret->user;
                            ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-primary);">
                                        <?php echo e($user?->name ?? 'Siswa'); ?>

                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                        NIS: <?php echo e($user?->nis ?? '-'); ?> &bull; Kelas: <?php echo e($user?->kelas ?? '-'); ?>

                                    </div>
                                </td>

                                <td>
                                    <div style="font-weight: 700; color: var(--text-primary);">
                                        <?php echo e($item?->name ?? 'Barang #' . $ret->borrowing_request_id); ?>

                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">
                                        Kode: <?php echo e($item?->code ?? '-'); ?> &bull; ID Pinjam: #<?php echo e($ret->borrowing_request_id); ?>

                                    </div>
                                </td>

                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ret->foto_bukti): ?>
                                            <img src="<?php echo e(asset('storage/' . $ret->foto_bukti)); ?>" alt="Bukti" class="photo-thumb" onclick="openPhotoModal('<?php echo e(asset('storage/' . $ret->foto_bukti)); ?>')">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div>
                                            <span class="badge-kondisi">
                                                <?php echo e($ret->kondisi_label); ?>

                                            </span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ret->catatan): ?>
                                                <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo e($ret->catatan); ?>">
                                                    "<?php echo e($ret->catatan); ?>"
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div style="font-weight: 600; color: var(--text-primary);">
                                        <?php echo e($ret->created_at->format('d M Y')); ?>

                                    </div>
                                    <div style="font-size: 11px; color: var(--text-muted);">
                                        <?php echo e($ret->created_at->format('H:i')); ?> WIB
                                    </div>
                                </td>

                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ret->status === 'menunggu'): ?>
                                        <span class="badge-status badge-menunggu">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Menunggu
                                        </span>
                                    <?php elseif($ret->status === 'disetujui'): ?>
                                        <span class="badge-status badge-disetujui">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Disetujui
                                        </span>
                                        <div style="font-size: 10px; color: var(--text-muted); margin-top: 3px;">
                                            oleh <?php echo e($ret->verifier?->name ?? 'Admin'); ?>

                                        </div>
                                    <?php elseif($ret->status === 'ditolak'): ?>
                                        <span class="badge-status badge-ditolak">
                                            <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Ditolak
                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ret->alasan_ditolak): ?>
                                            <div style="font-size: 10px; color: #ef4444; margin-top: 3px; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo e($ret->alasan_ditolak); ?>">
                                                "<?php echo e($ret->alasan_ditolak); ?>"
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>

                                <td style="text-align: right;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ret->status === 'menunggu'): ?>
                                        <div style="display: inline-flex; align-items: center; gap: 6px;">
                                            
                                            <form action="<?php echo e(route('admin.returns.approve', $ret->id)); ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin menyetujui pengembalian barang ini? Stok inventaris dan status peminjaman akan diperbarui.')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn-approve" title="Setujui Pengembalian">
                                                    <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>

                                            
                                            <button type="button" class="btn-reject" onclick="openRejectModal('<?php echo e($ret->id); ?>', '<?php echo e(addslashes($user?->name ?? 'Siswa')); ?>', '<?php echo e(addslashes($item?->name ?? 'Barang')); ?>')" title="Tolak Pengembalian">
                                                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Reject
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span style="font-size: 11px; color: var(--text-muted); font-style: italic;">
                                            Selesai diverifikasi
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="padding: 16px 20px; border-top: 1px solid var(--border-alt);">
                <?php echo e($returns->links()); ?>

            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:48px;height:48px;margin:0 auto 12px;color:var(--text-subtle)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div style="font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">Tidak Ada Pengajuan Pengembalian</div>
                <div style="font-size: 12px; color: var(--text-muted);">Tidak ada data pengembalian yang sesuai dengan filter atau pencarian saat ini.</div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


<div class="admin-modal" id="rejectModal">
    <div class="modal-box">
        <div class="modal-box-title">Tolak Pengajuan Pengembalian</div>
        <div class="modal-box-sub" id="rejectModalSub">Tuliskan alasan penolakan agar siswa dapat mengetahui dan memperbaiki pengajuan.</div>

        <form id="rejectForm" method="POST" action="">
            <?php echo csrf_field(); ?>
            <div style="margin-bottom: 14px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px;">
                    Alasan Penolakan <span style="color: #ef4444;">*</span>
                </label>
                <textarea 
                    name="alasan_ditolak" 
                    id="alasanDitolakInput" 
                    rows="3" 
                    class="form-textarea" 
                    placeholder="Contoh: Fisik barang terdapat retak tambahan yang belum dijelaskan, harap cek kembali aksesoris kabel charger..." 
                    required></textarea>
                <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Minimal 5 karakter, maksimal 500 karakter.</div>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn-submit-reject">
                    Konfirmasi Penolakan
                </button>
            </div>
        </form>
    </div>
</div>


<div class="admin-modal" id="photoModal" onclick="closePhotoModal()">
    <div class="modal-box" style="max-width: 600px; padding: 12px;" onclick="event.stopPropagation()">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 8px;">
            <button onclick="closePhotoModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <img id="modalImg" src="" alt="Foto Bukti" style="width: 100%; max-height: 70vh; object-fit: contain; border-radius: 8px;">
    </div>
</div>

<script>
    function openRejectModal(id, studentName, itemName) {
        const form = document.getElementById('rejectForm');
        form.action = `/returns/${id}/reject`;
        document.getElementById('rejectModalSub').textContent = `Pengembalian barang "${itemName}" oleh siswa "${studentName}".`;
        document.getElementById('alasanDitolakInput').value = '';
        document.getElementById('rejectModal').classList.add('active');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('active');
    }

    function openPhotoModal(src) {
        document.getElementById('modalImg').src = src;
        document.getElementById('photoModal').classList.add('active');
    }
    function closePhotoModal() {
        document.getElementById('photoModal').classList.remove('active');
        document.getElementById('modalImg').src = '';
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/pages/admin/returns.blade.php ENDPATH**/ ?>