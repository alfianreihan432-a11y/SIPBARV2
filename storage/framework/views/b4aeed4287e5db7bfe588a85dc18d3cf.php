<?php
if (!function_exists('_b4aeed4287e5db7bfe588a85dc18d3cf')):
function _b4aeed4287e5db7bfe588a85dc18d3cf($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
<?php $__attrs82a0844d6e93dc1021581ff49c94ea6d = ['attributes' => $attributes,'size' => $size === 'sm' || $size === 'xs' ? 'xs' : 'sm','xData' => 'fluxInputViewable','xOn:click' => 'toggle()','xBind:dataViewableOpen' => 'open','ariaLabel' => e(__('Toggle password visibility'))]; ?>
<?php $__slots82a0844d6e93dc1021581ff49c94ea6d = []; ?>
<?php $__blaze->pushData($__attrs82a0844d6e93dc1021581ff49c94ea6d); ?>
<?php ob_start(); ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/eye-slash.blade.php', $__blaze->compiledPath.'/9b8da661df58ea60c983c200965465e8.php'); ?>
<?php $__blaze->pushData(['variant' => $iconVariant,'class' => 'hidden [[data-viewable-open]>&]:block']); ?>
<?php _9b8da661df58ea60c983c200965465e8($__blaze, ['variant' => $iconVariant,'class' => 'hidden [[data-viewable-open]>&]:block'], [], ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/eye.blade.php', $__blaze->compiledPath.'/86789dfb6eb540a01932ba639644adb6.php'); ?>
<?php $__blaze->pushData(['variant' => $iconVariant,'class' => 'block [[data-viewable-open]>&]:hidden']); ?>
<?php _86789dfb6eb540a01932ba639644adb6($__blaze, ['variant' => $iconVariant,'class' => 'block [[data-viewable-open]>&]:hidden'], [], ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
<?php $__slots82a0844d6e93dc1021581ff49c94ea6d['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots82a0844d6e93dc1021581ff49c94ea6d); ?>
<?php _82a0844d6e93dc1021581ff49c94ea6d($__blaze, $__attrs82a0844d6e93dc1021581ff49c94ea6d, $__slots82a0844d6e93dc1021581ff49c94ea6d, ['attributes', 'size'], ['xData' => 'x-data', 'xOn:click' => 'x-on:click', 'xBind:dataViewableOpen' => 'x-bind:data-viewable-open', 'ariaLabel' => 'aria-label'], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack82a0844d6e93dc1021581ff49c94ea6d)) { $__slots82a0844d6e93dc1021581ff49c94ea6d = array_pop($__slotsStack82a0844d6e93dc1021581ff49c94ea6d); } ?>
<?php if (! empty($__attrsStack82a0844d6e93dc1021581ff49c94ea6d)) { $__attrs82a0844d6e93dc1021581ff49c94ea6d = array_pop($__attrsStack82a0844d6e93dc1021581ff49c94ea6d); } ?>
<?php $__blaze->popData(); ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\input\viewable.blade.php ENDPATH**/ ?>