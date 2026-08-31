<?php
if (!function_exists('_55aeda24f9ca5eb3193746b660b2a6b5')):
function _55aeda24f9ca5eb3193746b660b2a6b5($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
    'variant' => null,
    'size' => null,
    'name' => null,
];
$variant ??= $attributes['variant'] ?? $__defaults['variant']; unset($attributes['variant']);
$size ??= $attributes['size'] ?? $__defaults['size']; unset($attributes['size']);
$name ??= $attributes['name'] ?? $__defaults['name']; unset($attributes['name']);
unset($__defaults);
?>

<?php
// We only want to show the name attribute on the radio if it has been set
// manually, but not if it has been set from the wire:model attribute...
$showName = isset($name);

if (! isset($name)) {
    $name = $attributes->whereStartsWith('wire:model')->first();
}

$classes = Flux::classes()
    ->add('flex gap-3')
    ;
?>

<?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/with-field.blade.php', $__blaze->compiledPath.'/9144d9f96a4df80329387fde1b1c2adc.php'); ?>
<?php if (isset($__slots9144d9f96a4df80329387fde1b1c2adc)) { $__slotsStack9144d9f96a4df80329387fde1b1c2adc[] = $__slots9144d9f96a4df80329387fde1b1c2adc; } ?>
<?php if (isset($__attrs9144d9f96a4df80329387fde1b1c2adc)) { $__attrsStack9144d9f96a4df80329387fde1b1c2adc[] = $__attrs9144d9f96a4df80329387fde1b1c2adc; } ?>
<?php $__attrs9144d9f96a4df80329387fde1b1c2adc = ['attributes' => $attributes]; ?>
<?php $__slots9144d9f96a4df80329387fde1b1c2adc = []; ?>
<?php $__blaze->pushData($__attrs9144d9f96a4df80329387fde1b1c2adc); ?>
<?php ob_start(); ?>
    <ui-radio-group <?php echo e($attributes->class($classes)); ?> <?php if($showName): ?> name="<?php echo e($name); ?>" <?php endif; ?> data-flux-radio-group-cards>
        <?php echo e($slot); ?>

    </ui-radio-group>
<?php $__slots9144d9f96a4df80329387fde1b1c2adc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots9144d9f96a4df80329387fde1b1c2adc); ?>
<?php _9144d9f96a4df80329387fde1b1c2adc($__blaze, $__attrs9144d9f96a4df80329387fde1b1c2adc, $__slots9144d9f96a4df80329387fde1b1c2adc, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack9144d9f96a4df80329387fde1b1c2adc)) { $__slots9144d9f96a4df80329387fde1b1c2adc = array_pop($__slotsStack9144d9f96a4df80329387fde1b1c2adc); } ?>
<?php if (! empty($__attrsStack9144d9f96a4df80329387fde1b1c2adc)) { $__attrs9144d9f96a4df80329387fde1b1c2adc = array_pop($__attrsStack9144d9f96a4df80329387fde1b1c2adc); } ?>
<?php $__blaze->popData(); ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\radio\group\variants\cards.blade.php ENDPATH**/ ?>