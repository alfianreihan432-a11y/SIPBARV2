

<?php $__env->startSection('title', 'Kategori'); ?>
<?php $__env->startSection('page-heading', 'Kategori'); ?>

<?php $__env->startSection('content'); ?>
    <div class="panel">
        <div class="panel-title">Manajemen Kategori</div>
        <p class="panel-text">Tambahkan, edit, atau hapus kategori inventaris. Kategori yang dibuat akan muncul di form tambah barang.</p>
    </div>

    <div class="mt-6">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('category-manager');

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1950267498-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\pages\admin\categories.blade.php ENDPATH**/ ?>