

<?php $__env->startSection('title', 'Statistik'); ?>
<?php $__env->startSection('page-heading', 'Statistik'); ?>

<?php $__env->startSection('content'); ?>
    <div class="panel">
        <div class="panel-title">Halaman Statistik</div>
        <p class="panel-text">Di sini akan ditampilkan statistik penggunaan barang dan peminjaman. Halaman ini sudah aktif dan dapat diakses.</p>
        <a href="<?php echo e(route('dashboard')); ?>" class="action-link">Kembali ke Dashboard</a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\pages\admin\statistics.blade.php ENDPATH**/ ?>