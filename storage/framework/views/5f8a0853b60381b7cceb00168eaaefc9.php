<?php
if (!function_exists('_5f8a0853b60381b7cceb00168eaaefc9')):
function _5f8a0853b60381b7cceb00168eaaefc9($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
$__env = $__blaze->env;
$__slots['slot'] ??= new \Illuminate\View\ComponentSlot('');
if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = \Livewire\Blaze\Runtime\BlazeAttributeBag::make($__data, $__bound, $__keys);
unset($__data, $__bound, $__keys);
ob_start();
?>


<?php
$__defaults = [
    'as' => null,
];
$as ??= $attributes['as'] ?? $__defaults['as']; unset($attributes['as']);
unset($__defaults);
?>

<?php if ($as === 'button'): ?>
    <button <?php echo e($attributes->merge(['type' => 'button'])); ?>>
        <?php echo e($slot); ?>

    </button>
<?php else: ?>
    <div <?php echo e($attributes); ?>>
        <?php echo e($slot); ?>

    </div>
<?php endif; ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\button-or-div.blade.php ENDPATH**/ ?>