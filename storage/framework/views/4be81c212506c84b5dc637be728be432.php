<?php $__env->startSection('title', 'Riwayat Peminjaman'); ?>
<?php $__env->startSection('page-heading', 'Riwayat Peminjaman'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .history-header {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; padding: 24px; margin-bottom: 20px;
    }
    .history-title { font-size: 20px; font-weight: 700; color: var(--text); margin-bottom: 4px; }
    .history-sub { font-size: 13px; color: var(--muted); }
    .history-stats { display: flex; gap: 12px; }
    .history-stat {
        padding: 12px 16px; background: var(--bg3); border: 1px solid var(--border2);
        border-radius: 10px; text-align: center;
    }
    .history-stat-label { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; }
    .history-stat-value { font-size: 18px; font-weight: 700; color: var(--text); margin-top: 4px; }

    .history-filters {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; padding: 20px; margin-bottom: 20px;
    }
    .filter-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
    @media (max-width: 1024px) { .filter-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .filter-grid { grid-template-columns: 1fr; } }
    .filter-label { font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 8px; display: block; }
    .filter-input {
        width: 100%; padding: 10px 12px; background: var(--input-bg);
        border: 1px solid var(--border2); border-radius: 8px;
        font-size: 13px; color: var(--text);
    }
    .filter-input:focus { outline: none; border-color: var(--primary); }
    .filter-btn {
        padding: 10px 20px; background: var(--primary); color: #fff;
        border: none; border-radius: 8px; font-size: 13px; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
    }
    .filter-btn:hover { background: var(--primary-dark); }
    .filter-reset {
        padding: 10px 20px; background: var(--bg3); color: var(--text);
        border: 1px solid var(--border2); border-radius: 8px;
        font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none;
    }
    .filter-reset:hover { background: var(--border2); }

    .history-list {
        background: var(--card); border: 1px solid var(--border2);
        border-radius: 14px; overflow: hidden;
    }
    .history-list-header {
        padding: 20px; border-bottom: 1px solid var(--border2);
    }
    .history-list-title { font-size: 16px; font-weight: 700; color: var(--text); }

    .history-item {
        padding: 20px; border-bottom: 1px solid var(--border2);
        transition: background .15s;
    }
    .history-item:hover { background: var(--bg3); }
    .history-item:last-child { border-bottom: none; }
    .history-item-top { display: flex; gap: 16px; }
    .history-item-icon {
        width: 48px; height: 48px; background: var(--bg3);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .history-item-content { flex: 1; }
    .history-item-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .history-item-name { font-size: 16px; font-weight: 700; color: var(--text); }
    .history-item-code { font-size: 13px; color: var(--muted); margin-top: 4px; }

    .history-badge {
        padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 600;
    }
    .history-badge-pending { background: rgba(100,116,139,.12); color: #64748b; }
    .history-badge-approved { background: rgba(37,99,235,.1); color: #2563eb; }
    .history-badge-borrowed { background: rgba(37,99,235,.1); color: #2563eb; }
    .history-badge-returned { background: rgba(16,185,129,.1); color: #10b981; }
    .history-badge-rejected { background: rgba(239,68,68,.1); color: #dc2626; }

    .history-details { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 12px; }
    .history-detail-label { font-size: 12px; color: var(--muted); }
    .history-detail-value { font-size: 13px; font-weight: 600; color: var(--text); }

    .history-meta { display: flex; gap: 16px; font-size: 13px; color: var(--muted); }
    .history-meta-item { display: flex; align-items: center; gap: 6px; }

    .history-rejection {
        background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2);
        padding: 12px; border-radius: 8px; margin-top: 12px;
    }
    .history-rejection-text { font-size: 13px; color: #dc2626; }

    .history-return {
        background: var(--bg3); border: 1px solid var(--border2);
        padding: 12px; border-radius: 8px; margin-top: 12px;
    }
    .history-return-text { font-size: 13px; color: var(--text); }

    .history-timeline { display: flex; flex-wrap: wrap; gap: 16px; margin-top: 12px; font-size: 12px; color: var(--subtle); }

    .history-empty { padding: 48px 20px; text-align: center; }
    .history-empty-icon { width: 80px; height: 80px; margin: 0 auto 16px; color: var(--subtle); }
    .history-empty-title { font-size: 18px; font-weight: 600; color: var(--text); margin-bottom: 8px; }
    .history-empty-sub { font-size: 14px; color: var(--muted); margin-bottom: 16px; }

    .pagination { padding: 16px 20px; border-top: 1px solid var(--border2); }
</style>

<div>
    
    <div class="history-header">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:20px;flex-wrap:wrap">
            <div>
                <div class="history-title">Riwayat Peminjaman</div>
                <div class="history-sub">Daftar lengkap semua transaksi peminjaman Anda</div>
            </div>
            <div class="history-stats">
                <div class="history-stat">
                    <div class="history-stat-label">Total</div>
                    <div class="history-stat-value">
                        <?php echo e(\App\Models\BorrowingRequest::where('user_id', auth()->id())->count()); ?>

                    </div>
                </div>
                <div class="history-stat">
                    <div class="history-stat-label">Selesai</div>
                    <div class="history-stat-value">
                        <?php echo e(\App\Models\BorrowingRequest::where('user_id', auth()->id())->where('status', 'returned')->count()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="history-filters">
        <form method="GET" action="<?php echo e(route('student.history')); ?>">
            <div class="filter-grid">
                <div>
                    <label class="filter-label">Cari Barang</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nama barang..." class="filter-input">
                </div>
                <div>
                    <label class="filter-label">Status</label>
                    <select name="status" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Menunggu</option>
                        <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Disetujui</option>
                        <option value="borrowed" <?php echo e(request('status') === 'borrowed' ? 'selected' : ''); ?>>Dipinjam</option>
                        <option value="returned" <?php echo e(request('status') === 'returned' ? 'selected' : ''); ?>>Dikembalikan</option>
                        <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="filter-label">Dari Tanggal</label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="filter-input">
                </div>
                <div>
                    <label class="filter-label">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="filter-input">
                </div>
            </div>
            <div style="margin-top:16px;display:flex;gap:12px">
                <button type="submit" class="filter-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                <a href="<?php echo e(route('student.history')); ?>" class="filter-reset">Reset</a>
            </div>
        </form>
    </div>

    
    <?php
        $query = \App\Models\BorrowingRequest::with(['item', 'teacher'])
            ->where('user_id', auth()->id());
        
        if (request('search')) {
            $query->whereHas('item', function($q) {
                $q->where('name', 'like', '%' . request('search') . '%');
            });
        }
        
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        if (request('date_from')) {
            $query->whereDate('borrow_date', '>=', request('date_from'));
        }
        if (request('date_to')) {
            $query->whereDate('borrow_date', '<=', request('date_to'));
        }
        
        $histories = $query->latest()->paginate(10);
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($histories->isNotEmpty()): ?>
    <div class="history-list">
        <div class="history-list-header">
            <div class="history-list-title">Hasil: <?php echo e($histories->total()); ?> Transaksi</div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <div class="history-item">
            <div class="history-item-top">
                <div class="history-item-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:24px;height:24px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="history-item-content">
                    <div class="history-item-header">
                        <div>
                            <div class="history-item-name"><?php echo e($history->item?->name ?? 'Barang tidak tersedia'); ?></div>
                            <div class="history-item-code">Kode: <?php echo e($history->item?->code ?? '-'); ?></div>
                        </div>
                        <span class="history-badge
                            <?php if($history->status === 'pending'): ?> history-badge-pending
                            <?php elseif(in_array($history->status, ['approved', 'borrowed'])): ?> history-badge-approved
                            <?php elseif($history->status === 'returned'): ?> history-badge-returned
                            <?php elseif($history->status === 'rejected'): ?> history-badge-rejected
                            <?php endif; ?>">
                            <?php echo e($history->status_label); ?>

                        </span>
                    </div>

                    <div class="history-details">
                        <div>
                            <div class="history-detail-label">Jumlah</div>
                            <div class="history-detail-value"><?php echo e($history->quantity); ?> unit</div>
                        </div>
                        <div>
                            <div class="history-detail-label">Keperluan</div>
                            <div class="history-detail-value"><?php echo e($history->purpose); ?></div>
                        </div>
                        <div>
                            <div class="history-detail-label">Tanggal Pinjam</div>
                            <div class="history-detail-value"><?php echo e(\Carbon\Carbon::parse($history->borrow_date)->format('d M Y')); ?></div>
                        </div>
                        <div>
                            <div class="history-detail-label">Tanggal Kembali</div>
                            <div class="history-detail-value"><?php echo e(\Carbon\Carbon::parse($history->return_date)->format('d M Y')); ?></div>
                        </div>
                    </div>

                    <div class="history-meta">
                        <div class="history-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Guru: <span style="color:var(--text);font-weight:600"><?php echo e($history->teacher?->name ?? '-'); ?></span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->notes): ?>
                        <div class="history-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <?php echo e($history->notes); ?>

                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->status === 'rejected' && $history->rejection_reason): ?>
                    <div class="history-rejection">
                        <div class="history-rejection-text"><strong>Alasan Ditolak:</strong> <?php echo e($history->rejection_reason); ?></div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->status === 'returned' && $history->return_condition): ?>
                    <div class="history-return">
                        <div class="history-return-text">
                            <strong>Kondisi Pengembalian:</strong> <?php echo e(ucfirst($history->return_condition)); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->return_notes): ?>
                            <div style="margin-top:4px"><?php echo e($history->return_notes); ?></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->status === 'returned'): ?>
                    <div class="history-timeline">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->approved_at): ?>
                        <div>✓ Disetujui: <?php echo e($history->approved_at->format('d M Y H:i')); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->borrowed_at): ?>
                        <div>✓ Diambil: <?php echo e($history->borrowed_at->format('d M Y H:i')); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->returned_at): ?>
                        <div>✓ Dikembalikan: <?php echo e($history->returned_at->format('d M Y H:i')); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <div class="pagination">
            <?php echo e($histories->appends(request()->query())->links()); ?>

        </div>
    </div>
    <?php else: ?>
    <div class="history-empty">
        <svg xmlns="http://www.w3.org/2000/svg" class="history-empty-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <div class="history-empty-title">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search', 'status', 'date_from', 'date_to'])): ?>
                Tidak Ada Hasil
            <?php else: ?>
                Belum Ada Riwayat Peminjaman
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="history-empty-sub">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search', 'status', 'date_from', 'date_to'])): ?>
                Coba ubah filter pencarian Anda
            <?php else: ?>
                Mulai ajukan peminjaman barang dari katalog
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search', 'status', 'date_from', 'date_to'])): ?>
            <a href="<?php echo e(route('student.history')); ?>" class="filter-btn">Reset Filter</a>
        <?php else: ?>
            <a href="<?php echo e(route('student.catalog')); ?>" class="filter-btn">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Lihat Katalog Barang
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\pages\siswa\history.blade.php ENDPATH**/ ?>