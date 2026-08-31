<?php $__env->startSection('title', 'Laporan'); ?>
<?php $__env->startSection('page-heading', 'Laporan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="panel">
        <div class="panel-title">Halaman Laporan</div>
        <p class="panel-text">Di sini akan ditampilkan laporan inventaris dan peminjaman. Halaman ini sudah aktif dan dapat diakses.</p>
        <a href="<?php echo e(route('dashboard')); ?>" class="action-link">Kembali ke Dashboard</a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Dell\SIPBARV2\resources\views/pages/admin/reports.blade.php ENDPATH**/ ?>