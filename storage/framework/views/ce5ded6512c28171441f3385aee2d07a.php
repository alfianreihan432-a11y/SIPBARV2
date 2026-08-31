<?php
if (!function_exists('_ce5ded6512c28171441f3385aee2d07a')):
function _ce5ded6512c28171441f3385aee2d07a($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
<?php $__attrs82a0844d6e93dc1021581ff49c94ea6d = ['attributes' => $attributes,'size' => $size === 'sm' || $size === 'xs' ? 'xs' : 'sm']; ?>
<?php $__slots82a0844d6e93dc1021581ff49c94ea6d = []; ?>
<?php $__blaze->pushData($__attrs82a0844d6e93dc1021581ff49c94ea6d); ?>
<?php ob_start(); ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/chevron-down.blade.php', $__blaze->compiledPath.'/ef6cd89082afc4024cdaf7dfb8e6c3d7.php'); ?>
<?php $__blaze->pushData(['variant' => $iconVariant]); ?>
<?php _ef6cd89082afc4024cdaf7dfb8e6c3d7($__blaze, ['variant' => $iconVariant], [], ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
<?php $__slots82a0844d6e93dc1021581ff49c94ea6d['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots82a0844d6e93dc1021581ff49c94ea6d); ?>
<?php _82a0844d6e93dc1021581ff49c94ea6d($__blaze, $__attrs82a0844d6e93dc1021581ff49c94ea6d, $__slots82a0844d6e93dc1021581ff49c94ea6d, ['attributes', 'size'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack82a0844d6e93dc1021581ff49c94ea6d)) { $__slots82a0844d6e93dc1021581ff49c94ea6d = array_pop($__slotsStack82a0844d6e93dc1021581ff49c94ea6d); } ?>
<?php if (! empty($__attrsStack82a0844d6e93dc1021581ff49c94ea6d)) { $__attrs82a0844d6e93dc1021581ff49c94ea6d = array_pop($__attrsStack82a0844d6e93dc1021581ff49c94ea6d); } ?>
<?php $__blaze->popData(); ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\input\expandable.blade.php ENDPATH**/ ?>