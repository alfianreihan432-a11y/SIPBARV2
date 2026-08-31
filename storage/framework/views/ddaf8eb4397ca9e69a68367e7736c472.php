<?php $__env->startSection('title', 'QR Barang – SIPBAR'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $overdueBorrowings = \App\Models\BorrowingRequest::with('itemWithTrashed')
        ->where('user_id', auth()->id())
        ->where('status', 'borrowed')
        ->whereDate('return_date', '<', now())
        ->get();

    $dueSoonBorrowings = \App\Models\BorrowingRequest::with('itemWithTrashed')
        ->where('user_id', auth()->id())
        ->where('status', 'borrowed')
        ->whereDate('return_date', '>=', now())
        ->whereDate('return_date', '<=', now()->addDays(2))
        ->get();

    $recentApprovals = \App\Models\BorrowingRequest::with('itemWithTrashed')
        ->where('user_id', auth()->id())
        ->where('status', 'approved')
        ->latest('approved_at')
        ->take(3)->get();

    $recentRejections = \App\Models\BorrowingRequest::with('itemWithTrashed')
        ->where('user_id', auth()->id())
        ->where('status', 'rejected')
        ->latest('updated_at')
        ->take(3)->get();

    $totalAlerts = $overdueBorrowings->count() + $dueSoonBorrowings->count() + $recentApprovals->count() + $recentRejections->count();
?>


<div id="qr-modal-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,.6);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .25s ease">
    <div id="qr-modal" style="background:var(--card);border:1px solid var(--border2);border-radius:20px;padding:28px 24px;max-width:380px;width:100%;text-align:center;transform:scale(.94) translateY(12px);transition:transform .28s cubic-bezier(.34,1.56,.64,1),opacity .25s;opacity:0;position:relative">
        <button onclick="closeQRModal()" style="position:absolute;top:14px;right:14px;background:var(--bg3);border:1px solid var(--border2);border-radius:8px;width:30px;height:30px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:15px;height:15px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div style="font-family:var(--font-head);font-size:16px;font-weight:800;color:var(--text);margin-bottom:4px">QR Code Peminjaman</div>
        <div style="font-size:12px;color:var(--muted);margin-bottom:20px">Tunjukkan kepada petugas saat mengambil barang</div>
        <div id="qr-spinner" style="width:40px;height:40px;border:3px solid var(--border2);border-top-color:var(--primary);border-radius:50%;animation:qr-spin .7s linear infinite;margin:40px auto"></div>
        <div id="qr-error" style="display:none;color:var(--s-rejected);font-size:13px;padding:16px;background:var(--s-rejected-bg);border-radius:10px;margin-bottom:12px"></div>
        <div id="qr-img-wrap" style="display:none;width:260px;height:260px;margin:0 auto 16px;border-radius:14px;border:2px solid var(--border2);overflow:hidden;background:#fff">
            <img id="qr-img" src="" alt="QR Code" style="width:100%;height:100%;object-fit:contain">
        </div>
        <div id="qr-item-name" style="font-family:var(--font-head);font-size:15px;font-weight:700;color:var(--text);margin-bottom:4px"></div>
        <div id="qr-token" style="font-size:11px;color:var(--muted);font-family:monospace;background:var(--bg3);border-radius:6px;padding:4px 10px;display:inline-block;margin-bottom:14px;letter-spacing:.04em"></div>
        <div style="font-size:12px;color:var(--muted);line-height:1.6;background:var(--primary-light);border:1px solid var(--primary-muted);border-radius:10px;padding:10px 14px;margin-bottom:12px;text-align:left">
            Petugas inventaris akan men-scan QR Code ini untuk konfirmasi pengambilan barang.
        </div>
        <div id="qr-expires" style="font-size:11px;color:var(--subtle)"></div>
    </div>
</div>
<style>@keyframes qr-spin{to{transform:rotate(360deg)}}</style>


<div class="page-header">
    <div class="page-header-left">
        <div class="page-title">
            QR Barang
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalAlerts > 0): ?>
            <span class="page-title-count" style="background:var(--s-rejected-bg);color:var(--s-rejected);border-color:var(--s-rejected-bdr)"><?php echo e($totalAlerts); ?> status</span>
            <?php else: ?>
            <span class="page-title-count">Siap dipakai</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="page-subtitle">Status peminjaman, QR Code, dan informasi pengambilan barang</div>
    </div>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($overdueBorrowings->isNotEmpty()): ?>
<div class="s-card" style="border-color:var(--s-rejected-bdr);margin-bottom:16px">
    <div class="s-card-header" style="padding-bottom:14px;border-bottom:1px solid var(--s-rejected-bdr);margin-bottom:16px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:#dc2626;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <div class="s-card-title" style="color:var(--s-rejected)">Terlambat Dikembalikan</div>
                <div class="s-card-sub">Segera kembalikan barang untuk menghindari sanksi</div>
            </div>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $overdueBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $overdue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <?php $daysOverdue = now()->diffInDays(\Carbon\Carbon::parse($overdue->return_date)); ?>
    <div class="s-loan-row s-loan-row--rejected">
        <div class="s-loan-icon" style="background:var(--s-rejected-bg)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-rejected)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name"><?php echo e($overdue->itemWithTrashed?->name ?? 'Barang tidak tersedia'); ?></div>
            <div class="s-loan-meta">
                <span>Seharusnya kembali: <?php echo e(\Carbon\Carbon::parse($overdue->return_date)->format('d M Y')); ?></span>
            </div>
        </div>
        <div class="s-loan-right">
            <span class="s-badge s-badge--rejected">Terlambat <?php echo e($daysOverdue); ?> hari</span>
            <a href="<?php echo e(route('student.returns.index')); ?>" class="s-btn s-btn--danger s-btn--sm">Kembalikan</a>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dueSoonBorrowings->isNotEmpty()): ?>
<div class="s-card" style="border-color:var(--s-pending-bdr);margin-bottom:16px">
    <div class="s-card-header" style="padding-bottom:14px;border-bottom:1px solid var(--s-pending-bdr);margin-bottom:16px">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:#d97706;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:#fff" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="s-card-title" style="color:var(--s-pending)">Segera Kembalikan</div>
                <div class="s-card-sub">Barang ini harus dikembalikan dalam waktu dekat</div>
            </div>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dueSoonBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dueSoon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <?php $daysLeft = \Carbon\Carbon::parse($dueSoon->return_date)->diffInDays(now()); ?>
    <div class="s-loan-row s-loan-row--pending">
        <div class="s-loan-icon" style="background:var(--s-pending-bg)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-pending)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name"><?php echo e($dueSoon->itemWithTrashed?->name ?? 'Barang tidak tersedia'); ?></div>
            <div class="s-loan-meta">
                <span>Harus kembali: <?php echo e(\Carbon\Carbon::parse($dueSoon->return_date)->format('d M Y')); ?></span>
            </div>
        </div>
        <div class="s-loan-right">
            <span class="s-badge s-badge--pending"><?php echo e($daysLeft); ?> hari lagi</span>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentApprovals->isNotEmpty()): ?>
<div class="s-card" style="margin-bottom:16px">
    <div class="s-card-header">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--s-returned-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-returned)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="s-card-title">Peminjaman Disetujui</div>
                <div class="s-card-sub">Ambil barang dari ruang inventaris dengan QR Code</div>
            </div>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $approval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <div class="s-loan-row s-loan-row--approved">
        <div class="s-loan-icon" style="background:var(--s-approved-bg)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-approved)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name"><?php echo e($approval->itemWithTrashed?->name ?? 'Barang tidak tersedia'); ?></div>
            <div class="s-loan-meta">
                <span>Disetujui <?php echo e($approval->approved_at ? $approval->approved_at->diffForHumans() : 'baru saja'); ?></span>
            </div>
        </div>
        <div class="s-loan-right">
            <span class="s-badge s-badge--approved">Disetujui</span>
            <button onclick="openQRModal(<?php echo e($approval->id); ?>, '<?php echo e(addslashes($approval->itemWithTrashed?->name ?? 'Barang')); ?>')" class="s-btn s-btn--sm s-btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                QR Code
            </button>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentRejections->isNotEmpty()): ?>
<div class="s-card" style="margin-bottom:16px">
    <div class="s-card-header">
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:36px;height:36px;border-radius:10px;background:var(--s-rejected-bg);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-rejected)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="s-card-title">Peminjaman Ditolak</div>
                <div class="s-card-sub">Pengajuan yang tidak dapat disetujui</div>
            </div>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentRejections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rejection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <div class="s-loan-row s-loan-row--rejected">
        <div class="s-loan-icon" style="background:var(--s-rejected-bg)">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:var(--s-rejected)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="s-loan-content">
            <div class="s-loan-name"><?php echo e($rejection->itemWithTrashed?->name ?? 'Barang tidak tersedia'); ?></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rejection->rejection_reason): ?>
            <div style="margin-top:8px;padding:8px 12px;background:var(--s-rejected-bg);border:1px solid var(--s-rejected-bdr);border-radius:8px;font-size:12px;color:var(--s-rejected)">
                <strong>Alasan:</strong> <?php echo e($rejection->rejection_reason); ?>

            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="s-loan-right">
            <span class="s-badge s-badge--rejected">Ditolak</span>
            <span class="s-loan-time"><?php echo e($rejection->updated_at->diffForHumans()); ?></span>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<div class="s-card" style="margin-bottom:16px">
    <div class="s-card-header">
        <div>
            <div class="s-card-title">Panduan QR Barang</div>
            <div class="s-card-sub">Cara menampilkan dan menggunakan QR Code peminjaman</div>
        </div>
    </div>

    <?php
    $sysAnn = [
        ['icon'=>'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'color'=>'var(--primary)', 'bg'=>'var(--primary-light)', 'title'=>'Cara Menggunakan QR Barang', 'body'=>'Setelah peminjaman disetujui, QR Code barang akan muncul di halaman ini. Tunjukkan QR Code kepada admin untuk konfirmasi pengambilan barang dari ruang inventaris.', 'date'=>'Hari ini'],
        ['icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color'=>'#d97706', 'bg'=>'rgba(217,119,6,.1)', 'title'=>'Tanggal Kembali Barang', 'body'=>'Pastikan barang dikembalikan sesuai tanggal yang tertera. Jika mendekati tenggat, cek kembali status peminjaman di halaman QR Barang.', 'date'=>'2 hari lalu'],
        ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color'=>'#059669', 'bg'=>'rgba(5,150,105,.1)', 'title'=>'Kondisi Barang Saat Pinjam', 'body'=>'Jaga barang tetap baik selama masa peminjaman. Jika ada kerusakan atau perubahan kondisi, segera laporkan agar proses pengembalian mudah dipantau.', 'date'=>'1 minggu lalu'],
    ];
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $sysAnn; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ann): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
    <div style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid var(--border2)">
        <div style="width:40px;height:40px;border-radius:10px;background:<?php echo e($ann['bg']); ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;color:<?php echo e($ann['color']); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($ann['icon']); ?>"/></svg>
        </div>
        <div style="flex:1">
            <div style="font-family:var(--font-head);font-size:14px;font-weight:700;color:var(--text);margin-bottom:5px"><?php echo e($ann['title']); ?></div>
            <div style="font-size:13px;color:var(--muted);line-height:1.6"><?php echo e($ann['body']); ?></div>
            <div style="display:inline-flex;align-items:center;gap:5px;margin-top:10px;font-size:11px;color:var(--subtle);background:var(--bg3);border:1px solid var(--border2);padding:3px 10px;border-radius:6px">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <?php echo e($ann['date']); ?>

            </div>
        </div>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    <div style="padding-top:4px"></div>
</div>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($overdueBorrowings->isEmpty() && $dueSoonBorrowings->isEmpty() && $recentApprovals->isEmpty() && $recentRejections->isEmpty()): ?>
<div class="s-empty" style="margin-top:-10px">
    <div class="s-empty-icon-wrap" style="background:var(--s-returned-bg);border-color:var(--s-returned-bdr)">
        <svg xmlns="http://www.w3.org/2000/svg" style="width:32px;height:32px;color:var(--s-returned)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="s-empty-title">Semua Lancar!</div>
    <div class="s-empty-sub">Tidak ada peringatan atau notifikasi penting saat ini. Peminjaman kamu dalam status yang baik.</div>
    <a href="<?php echo e(route('student.catalog')); ?>" class="s-btn s-btn--primary">Lihat Katalog Barang</a>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
var qrOverlay  = document.getElementById('qr-modal-overlay');
var qrModal    = document.getElementById('qr-modal');
var qrImgWrap  = document.getElementById('qr-img-wrap');
var qrImgEl    = document.getElementById('qr-img');
var qrItemName = document.getElementById('qr-item-name');
var qrToken    = document.getElementById('qr-token');
var qrExpires  = document.getElementById('qr-expires');
var qrSpinner  = document.getElementById('qr-spinner');
var qrError    = document.getElementById('qr-error');

function openQRModal(borrowingId, itemName) {
    qrImgWrap.style.display = 'none';
    qrSpinner.style.display = 'block';
    qrError.style.display   = 'none';
    qrItemName.textContent  = itemName;
    qrToken.textContent     = '';
    qrExpires.textContent   = '';

    qrOverlay.style.opacity        = '1';
    qrOverlay.style.pointerEvents  = 'all';
    qrModal.style.opacity           = '1';
    qrModal.style.transform         = 'scale(1) translateY(0)';
    document.body.style.overflow    = 'hidden';

    fetch('/siswa/peminjaman/' + borrowingId + '/qrcode', {
        headers: {'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
    })
    .then(function(r){return r.json();})
    .then(function(data) {
        qrSpinner.style.display = 'none';
        if(data.success) {
            qrImgEl.src = data.qr_image;
            qrImgWrap.style.display = 'flex';
            qrToken.textContent = '#' + data.borrowing_id + ' · ' + data.token.substring(0,8).toUpperCase() + '...';
            qrExpires.textContent = data.expires_at ? 'Berlaku hingga: ' + data.expires_at : '';
        } else {
            qrError.style.display = 'block';
            qrError.textContent = data.message || 'Gagal memuat QR Code.';
        }
    })
    .catch(function() {
        qrSpinner.style.display = 'none';
        qrError.style.display = 'block';
        qrError.textContent = 'Koneksi gagal. Coba lagi beberapa saat.';
    });
}

function closeQRModal() {
    qrOverlay.style.opacity       = '0';
    qrOverlay.style.pointerEvents = 'none';
    qrModal.style.opacity          = '0';
    qrModal.style.transform        = 'scale(.94) translateY(12px)';
    document.body.style.overflow   = '';
}

qrOverlay.addEventListener('click', function(e) {
    if(e.target === qrOverlay) closeQRModal();
});
document.addEventListener('keydown', function(e) {
    if(e.key === 'Escape') closeQRModal();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views/pages/siswa/announcements.blade.php ENDPATH**/ ?>