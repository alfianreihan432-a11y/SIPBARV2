<?php $__env->startSection('title', 'Scanner QR Code'); ?>
<?php $__env->startSection('page-heading', 'Scanner QR Code'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .page-header {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 20px;
    }
    .page-header-content {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    @media (min-width: 768px) {
        .page-header-content {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 4px;
    }
    .page-subtitle {
        font-size: 14px;
        color: var(--muted);
    }
    
    .scanner-container {
        background: var(--card);
        border: 1px solid var(--border2);
        border-radius: 14px;
        padding: 24px;
    }
</style>

<div>
    
    <div class="page-header">
        <div class="page-header-content">
            <div>
                <h1 class="page-title">Scanner QR Code</h1>
                <p class="page-subtitle">Scan QR code untuk memverifikasi pengembalian barang</p>
            </div>
        </div>
    </div>

    
    <div class="scanner-container">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('q-r-scanner', []);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1672866444-0', $__key);

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
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\pages\guru\qr-scan.blade.php ENDPATH**/ ?>