<?php
if (!function_exists('_8246172bba3b8b8b87e0a98151e1329c')):
function _8246172bba3b8b8b87e0a98151e1329c($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
extract(Flux::forwardedAttributes($attributes, [
    'tooltipPosition',
    'tooltipKbd',
    'tooltip',
]));
?>

<?php $tooltipPosition = $tooltipPosition ??= $attributes->pluck('tooltip:position'); ?>
<?php $tooltipKbd = $tooltipKbd ??= $attributes->pluck('tooltip:kbd'); ?>
<?php $tooltip = $tooltip ??= $attributes->pluck('tooltip'); ?>

<?php
$__defaults = [
    'tooltipPosition' => 'top',
    'tooltipKbd' => null,
    'tooltip' => null,
];
$tooltipPosition ??= $attributes['tooltip-position'] ?? $attributes['tooltipPosition'] ?? $__defaults['tooltipPosition']; unset($attributes['tooltipPosition'], $attributes['tooltip-position']);
$tooltipKbd ??= $attributes['tooltip-kbd'] ?? $attributes['tooltipKbd'] ?? $__defaults['tooltipKbd']; unset($attributes['tooltipKbd'], $attributes['tooltip-kbd']);
$tooltip ??= $attributes['tooltip'] ?? $__defaults['tooltip']; unset($attributes['tooltip']);
unset($__defaults);
?>

<?php if ($tooltip): ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/tooltip/index.blade.php', $__blaze->compiledPath.'/d1f84488d7653a582f8cf4944a30b59b.php'); ?>
<?php if (isset($__slotsd1f84488d7653a582f8cf4944a30b59b)) { $__slotsStackd1f84488d7653a582f8cf4944a30b59b[] = $__slotsd1f84488d7653a582f8cf4944a30b59b; } ?>
<?php if (isset($__attrsd1f84488d7653a582f8cf4944a30b59b)) { $__attrsStackd1f84488d7653a582f8cf4944a30b59b[] = $__attrsd1f84488d7653a582f8cf4944a30b59b; } ?>
<?php $__attrsd1f84488d7653a582f8cf4944a30b59b = ['content' => $tooltip,'position' => $tooltipPosition,'kbd' => $tooltipKbd]; ?>
<?php $__slotsd1f84488d7653a582f8cf4944a30b59b = []; ?>
<?php $__blaze->pushData($__attrsd1f84488d7653a582f8cf4944a30b59b); ?>
<?php ob_start(); ?>
        <?php echo e($slot); ?>

    <?php $__slotsd1f84488d7653a582f8cf4944a30b59b['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsd1f84488d7653a582f8cf4944a30b59b); ?>
<?php _d1f84488d7653a582f8cf4944a30b59b($__blaze, $__attrsd1f84488d7653a582f8cf4944a30b59b, $__slotsd1f84488d7653a582f8cf4944a30b59b, ['content', 'position', 'kbd'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackd1f84488d7653a582f8cf4944a30b59b)) { $__slotsd1f84488d7653a582f8cf4944a30b59b = array_pop($__slotsStackd1f84488d7653a582f8cf4944a30b59b); } ?>
<?php if (! empty($__attrsStackd1f84488d7653a582f8cf4944a30b59b)) { $__attrsd1f84488d7653a582f8cf4944a30b59b = array_pop($__attrsStackd1f84488d7653a582f8cf4944a30b59b); } ?>
<?php $__blaze->popData(); ?>
<?php else: ?>
    <?php echo e($slot); ?>

<?php endif; ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\with-tooltip.blade.php ENDPATH**/ ?>