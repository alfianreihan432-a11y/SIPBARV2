<?php
if (!function_exists('_382fe7f3b21ecc74409803eb0e9197ad')):
function _382fe7f3b21ecc74409803eb0e9197ad($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
$__env = $__blaze->env;

if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = \Livewire\Blaze\Runtime\BlazeAttributeBag::make($__data, $__bound, $__keys);
unset($__data, $__bound, $__keys);
ob_start();
?>


<?php
$__defaults = [
    'iconVariant' => 'mini',
    'size' => null,
];
$iconVariant ??= $attributes['icon-variant'] ?? $attributes['iconVariant'] ?? $__defaults['iconVariant']; unset($attributes['iconVariant'], $attributes['icon-variant']);
$size ??= $attributes['size'] ?? $__defaults['size']; unset($attributes['size']);
unset($__defaults);
?>

<?php
$attributes = $attributes->merge([
    'variant' => 'subtle',
    'class' => '-me-1',
    'square' => true,
    'size' => null,
]);
?>

<?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php', $__blaze->compiledPath.'/82a0844d6e93dc1021581ff49c94ea6d.php'); ?>
<?php if (isset($__slots82a0844d6e93dc1021581ff49c94ea6d)) { $__slotsStack82a0844d6e93dc1021581ff49c94ea6d[] = $__slots82a0844d6e93dc1021581ff49c94ea6d; } ?>
<?php if (isset($__attrs82a0844d6e93dc1021581ff49c94ea6d)) { $__attrsStack82a0844d6e93dc1021581ff49c94ea6d[] = $__attrs82a0844d6e93dc1021581ff49c94ea6d; } ?>
<?php $__attrs82a0844d6e93dc1021581ff49c94ea6d = ['attributes' => $attributes,'size' => $size === 'sm' || $size === 'xs' ? 'xs' : 'sm','xData' => 'fluxInputCopyable','xOn:click' => 'copy()','xBind:dataCopyableCopied' => 'copied','ariaLabel' => e(__('Copy to clipboard'))]; ?>
<?php $__slots82a0844d6e93dc1021581ff49c94ea6d = []; ?>
<?php $__blaze->pushData($__attrs82a0844d6e93dc1021581ff49c94ea6d); ?>
<?php ob_start(); ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/clipboard-document-check.blade.php', $__blaze->compiledPath.'/5d088ab8b384fcc15b72610bc32cfc3e.php'); ?>
<?php $__blaze->pushData(['variant' => $iconVariant,'class' => 'hidden [[data-copyable-copied]>&]:block']); ?>
<?php _5d088ab8b384fcc15b72610bc32cfc3e($__blaze, ['variant' => $iconVariant,'class' => 'hidden [[data-copyable-copied]>&]:block'], [], ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/clipboard-document.blade.php', $__blaze->compiledPath.'/f5e79b35077ebbc057cff1aeaf918ddc.php'); ?>
<?php $__blaze->pushData(['variant' => $iconVariant,'class' => 'block [[data-copyable-copied]>&]:hidden']); ?>
<?php _f5e79b35077ebbc057cff1aeaf918ddc($__blaze, ['variant' => $iconVariant,'class' => 'block [[data-copyable-copied]>&]:hidden'], [], ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
<?php $__slots82a0844d6e93dc1021581ff49c94ea6d['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots82a0844d6e93dc1021581ff49c94ea6d); ?>
<?php _82a0844d6e93dc1021581ff49c94ea6d($__blaze, $__attrs82a0844d6e93dc1021581ff49c94ea6d, $__slots82a0844d6e93dc1021581ff49c94ea6d, ['attributes', 'size'], ['xData' => 'x-data', 'xOn:click' => 'x-on:click', 'xBind:dataCopyableCopied' => 'x-bind:data-copyable-copied', 'ariaLabel' => 'aria-label'], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack82a0844d6e93dc1021581ff49c94ea6d)) { $__slots82a0844d6e93dc1021581ff49c94ea6d = array_pop($__slotsStack82a0844d6e93dc1021581ff49c94ea6d); } ?>
<?php if (! empty($__attrsStack82a0844d6e93dc1021581ff49c94ea6d)) { $__attrs82a0844d6e93dc1021581ff49c94ea6d = array_pop($__attrsStack82a0844d6e93dc1021581ff49c94ea6d); } ?>
<?php $__blaze->popData(); ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\input\copyable.blade.php ENDPATH**/ ?>