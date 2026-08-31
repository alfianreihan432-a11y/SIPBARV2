<?php $__env->startSection('title', 'Riwayat Peminjaman – SIPBAR'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $query = \App\Models\BorrowingRequest::with(['item', 'teacher'])
        ->where('user_id', auth()->id());
    if (request('search')) {
        $query->whereHas('item', fn($q) => $q->where('name', 'like', '%'.request('search').'%'));
    }
    if (request('status')) $query->where('status', request('status'));
    if (request('date_from')) $query->whereDate('borrow_date', '>=', request('date_from'));
    if (request('date_to'))   $query->whereDate('borrow_date', '<=', request('date_to'));
    $histories = $query->latest()->paginate(10);
    $totalAll      = \App\Models\BorrowingRequest::where('user_id', auth()->id())->count();
    $totalReturned = \App\Models\BorrowingRequest::where('user_id', auth()->id())->where('status','returned')->count();

    $statusMap = [
        'pending'  => ['label'=>'Menunggu',    'cls'=>'s-badge--pending',  'row'=>'s-loan-row--pending',  'dot'=>'var(--s-pending)'],
        'approved' => ['label'=>'Disetujui',   'cls'=>'s-badge--approved', 'row'=>'s-loan-row--approved', 'dot'=>'var(--s-approved)'],
        'qr_ready' => ['label'=>'Siap Ambil',  'cls'=>'s-badge--approved', 'row'=>'s-loan-row--approved', 'dot'=>'var(--s-approved)'],
        'borrowed' => ['label'=>'Dipinjam',    'cls'=>'s-badge--borrowed', 'row'=>'s-loan-row--borrowed', 'dot'=>'var(--s-borrowed)'],
        'returned' => ['label'=>'Dikembalikan','cls'=>'s-badge--returned', 'row'=>'s-loan-row--returned', 'dot'=>'var(--s-returned)'],
        'rejected' => ['label'=>'Ditolak',     'cls'=>'s-badge--rejected', 'row'=>'s-loan-row--rejected', 'dot'=>'var(--s-rejected)'],
    ];
?>


<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">
            Riwayat Peminjaman
            <span class="page-title-count"><?php echo e($totalAll); ?> total</span>
        </div>
        <div class="page-subtitle">Seluruh catatan transaksi peminjaman barang inventaris kamu</div>
    </div>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap">
        <div style="display:flex;gap:10px">
            <div style="padding:10px 16px;background:var(--card);border:1px solid var(--border2);border-radius:10px;text-align:center">
                <div style="font-family:var(--font-head);font-size:18px;font-weight:800;color:var(--text)"><?php echo e($totalAll); ?></div>
                <div style="font-size:11px;color:var(--muted);margin-top:2px">Total</div>
            </div>
            <div style="padding:10px 16px;background:var(--s-returned-bg);border:1px solid var(--s-returned-bdr);border-radius:10px;text-align:center">
                <div style="font-family:var(--font-head);font-size:18px;font-weight:800;color:var(--s-returned)"><?php echo e($totalReturned); ?></div>
                <div style="font-size:11px;color:var(--s-returned);margin-top:2px">Selesai</div>
            </div>
        </div>
    </div>
</div>


<div class="s-card s-card--flat" style="margin-bottom:20px">
    <form method="GET" action="<?php echo e(route('student.history')); ?>">
        <div class="s-filter-grid">
            <div class="s-filter-item" style="flex:2;min-width:200px">
                <label class="s-filter-label">Cari Barang</label>
                <div style="position:relative">
                    <svg xmlns="http://www.w3.org/2000/svg" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--subtle)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="s-filter-input" style="padding-left:38px" placeholder="Nama barang...">
                </div>
            </div>
            <div class="s-filter-item">
                <label class="s-filter-label">Status</label>
                <select name="status" class="s-filter-input">
                    <option value="">Semua Status</option>
                    <option value="pending"  <?php echo e(request('status')==='pending'  ? 'selected' : ''); ?>>Menunggu</option>
                    <option value="approved" <?php echo e(request('status')==='approved' ? 'selected' : ''); ?>>Disetujui</option>
                    <option value="borrowed" <?php echo e(request('status')==='borrowed' ? 'selected' : ''); ?>>Dipinjam</option>
                    <option value="returned" <?php echo e(request('status')==='returned' ? 'selected' : ''); ?>>Dikembalikan</option>
                    <option value="rejected" <?php echo e(request('status')==='rejected' ? 'selected' : ''); ?>>Ditolak</option>
                </select>
            </div>
            <div class="s-filter-item">
                <label class="s-filter-label">Dari Tanggal</label>
                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="s-filter-input">
            </div>
            <div class="s-filter-item">
                <label class="s-filter-label">Sampai Tanggal</label>
                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="s-filter-input">
            </div>
            <div class="s-filter-item" style="flex:none;justify-content:flex-end;flex-direction:row;align-items:flex-end;gap:8px;min-width:auto">
                <button type="submit" class="s-btn s-btn--primary">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Filter
                </button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search','status','date_from','date_to'])): ?>
                <a href="<?php echo e(route('student.history')); ?>" class="s-btn s-btn--secondary">Reset</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </form>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($histories->isNotEmpty()): ?>
<div class="s-card">
    <div class="s-card-header">
        <div>
            <div class="s-card-title"><?php echo e($histories->total()); ?> Transaksi Ditemukan</div>
            <div class="s-card-sub">Halaman <?php echo e($histories->currentPage()); ?> dari <?php echo e($histories->lastPage()); ?></div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <?php $st = $statusMap[$h->status] ?? $statusMap['pending']; ?>
    <div class="s-loan-row <?php echo e($st['row']); ?>">
        <div class="s-loan-icon">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:20px;height:20px;color:var(--muted)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name"><?php echo e($h->item?->name ?? 'Barang tidak tersedia'); ?></div>
            <div class="s-loan-code">Kode: <?php echo e($h->item?->code ?? '-'); ?> · Qty: <?php echo e($h->quantity); ?> unit</div>

            <div class="s-loan-meta" style="margin-top:8px">
                <div class="s-loan-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <?php echo e(\Carbon\Carbon::parse($h->borrow_date)->format('d M Y')); ?> – <?php echo e(\Carbon\Carbon::parse($h->return_date)->format('d M Y')); ?>

                </div>
                <div class="s-loan-meta-item">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Guru: <span style="font-weight:600;color:var(--text2);margin-left:3px"><?php echo e($h->teacher?->name ?? '-'); ?></span>
                </div>
                <div class="s-loan-meta-item">
                    <?php echo e($h->purpose); ?>

                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->status === 'rejected' && $h->rejection_reason): ?>
            <div style="margin-top:10px;padding:9px 12px;background:var(--s-rejected-bg);border:1px solid var(--s-rejected-bdr);border-radius:8px;font-size:12px;color:var(--s-rejected)">
                <strong>Ditolak:</strong> <?php echo e($h->rejection_reason); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->status === 'returned' && $h->return_condition): ?>
            <div style="margin-top:10px;padding:9px 12px;background:var(--s-returned-bg);border:1px solid var(--s-returned-bdr);border-radius:8px;font-size:12px;color:var(--s-returned)">
                <strong>Kondisi dikembalikan:</strong> <?php echo e(ucfirst($h->return_condition)); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->return_notes): ?> — <?php echo e($h->return_notes); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->status === 'returned'): ?>
            <div style="display:flex;flex-wrap:wrap;gap:14px;margin-top:10px;font-size:11px;color:var(--subtle)">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->approved_at): ?>
                <span style="display:flex;align-items:center;gap:4px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--s-approved);display:inline-block"></span>
                    Disetujui <?php echo e($h->approved_at->format('d M Y')); ?>

                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->borrowed_at): ?>
                <span style="display:flex;align-items:center;gap:4px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--s-borrowed);display:inline-block"></span>
                    Diambil <?php echo e($h->borrowed_at->format('d M Y')); ?>

                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->returned_at): ?>
                <span style="display:flex;align-items:center;gap:4px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--s-returned);display:inline-block"></span>
                    Kembali <?php echo e($h->returned_at->format('d M Y')); ?>

                </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="s-loan-right">
            <span class="s-badge <?php echo e($st['cls']); ?>">
                <span class="s-badge-dot" style="background:<?php echo e($st['dot']); ?>"></span>
                <?php echo e($st['label']); ?>

            </span>
            <span class="s-loan-time"><?php echo e($h->created_at->diffForHumans()); ?></span>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

    
    <div style="padding-top:16px;border-top:1px solid var(--border2);margin-top:8px">
        <?php echo e($histories->appends(request()->query())->links()); ?>

    </div>
</div>
<?php else: ?>
<div class="s-card">
    <div class="s-empty">
        <div class="s-empty-icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="s-empty-title">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search','status','date_from','date_to'])): ?>
                Tidak ada hasil yang sesuai
            <?php else: ?>
                Belum ada riwayat peminjaman
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="s-empty-sub">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search','status','date_from','date_to'])): ?>
                Coba ubah filter pencarian atau hapus beberapa kriteria
            <?php else: ?>
                Mulai ajukan peminjaman dari katalog barang inventaris sekolah
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search','status','date_from','date_to'])): ?>
            <a href="<?php echo e(route('student.history')); ?>" class="s-btn s-btn--secondary">Reset Filter</a>
        <?php else: ?>
            <a href="<?php echo e(route('student.catalog')); ?>" class="s-btn s-btn--primary">Lihat Katalog Barang</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/pages/siswa/history.blade.php ENDPATH**/ ?>