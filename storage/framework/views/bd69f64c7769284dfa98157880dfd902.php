<?php
if (!function_exists('_bd69f64c7769284dfa98157880dfd902')):
function _bd69f64c7769284dfa98157880dfd902($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
$classes = Flux::classes()
    ->add('*:data-flux-field:mb-3')
    ->add('[&>[data-flux-field]:has(>[data-flux-description])]:mb-4')
    ->add('[&>[data-flux-field]:last-child]:mb-0!')
    ;

// Support adding the .self modifier to the wire:model directive...
if (($wireModel = $attributes->wire('model')) && $wireModel->directive && ! $wireModel->hasModifier('self')) {
    unset($attributes[$wireModel->directive]);

    $wireModel->directive .= '.self';

    $attributes = $attributes->merge([$wireModel->directive => $wireModel->value]);
}
?>

<?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/with-field.blade.php', $__blaze->compiledPath.'/9144d9f96a4df80329387fde1b1c2adc.php'); ?>
<?php if (isset($__slots9144d9f96a4df80329387fde1b1c2adc)) { $__slotsStack9144d9f96a4df80329387fde1b1c2adc[] = $__slots9144d9f96a4df80329387fde1b1c2adc; } ?>
<?php if (isset($__attrs9144d9f96a4df80329387fde1b1c2adc)) { $__attrsStack9144d9f96a4df80329387fde1b1c2adc[] = $__attrs9144d9f96a4df80329387fde1b1c2adc; } ?>
<?php $__attrs9144d9f96a4df80329387fde1b1c2adc = ['attributes' => $attributes]; ?>
<?php $__slots9144d9f96a4df80329387fde1b1c2adc = []; ?>
<?php $__blaze->pushData($__attrs9144d9f96a4df80329387fde1b1c2adc); ?>
<?php ob_start(); ?>
    <ui-checkbox-group <?php echo e($attributes->class($classes)); ?> data-flux-checkbox-group>
        <?php echo e($slot); ?>

    </ui-checkbox-group>
<?php $__slots9144d9f96a4df80329387fde1b1c2adc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots9144d9f96a4df80329387fde1b1c2adc); ?>
<?php _9144d9f96a4df80329387fde1b1c2adc($__blaze, $__attrs9144d9f96a4df80329387fde1b1c2adc, $__slots9144d9f96a4df80329387fde1b1c2adc, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack9144d9f96a4df80329387fde1b1c2adc)) { $__slots9144d9f96a4df80329387fde1b1c2adc = array_pop($__slotsStack9144d9f96a4df80329387fde1b1c2adc); } ?>
<?php if (! empty($__attrsStack9144d9f96a4df80329387fde1b1c2adc)) { $__attrs9144d9f96a4df80329387fde1b1c2adc = array_pop($__attrsStack9144d9f96a4df80329387fde1b1c2adc); } ?>
<?php $__blaze->popData(); ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\checkbox\group\variants\default.blade.php ENDPATH**/ ?>