<?php
if (!function_exists('_ac99271c4fe57da72cd30135a76ff273')):
function _ac99271c4fe57da72cd30135a76ff273($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
    'name' => $attributes->whereStartsWith('wire:model')->first(),
];
$name ??= $attributes['name'] ?? $__defaults['name']; unset($attributes['name']);
unset($__defaults);
?>

<?php
$classes = Flux::classes()
    ->add('w-full flex')
    ->add('*:data-flux-input:grow')
    ->add([
        // With the external borders, let's always make sure the first and last children have outside borders.
        // For internal borders, we will ensure that all left borders are removed, but the right borders remain.
        // But when there is a input groupsuffix, then there should be no right internal border.
        // That way we shouldn't ever have a double border...

        // All inputs borders...
        '[&>[data-flux-input]:last-child:not(:first-child)>[data-flux-group-target]:not([data-invalid])]:border-s-0',
        '[&>[data-flux-input]:not(:first-child):not(:last-child)>[data-flux-group-target]:not([data-invalid])]:border-s-0',
        '[&>[data-flux-input]:has(+[data-flux-input-group-suffix])>[data-flux-group-target]:not([data-invalid])]:border-e-0',

        // Selects and date pickers borders...
        '[&>*:last-child:not(:first-child)>[data-flux-group-target]:not([data-invalid])]:border-s-0',
        '[&>*:not(:first-child):not(:last-child)>[data-flux-group-target]:not([data-invalid])]:border-s-0',
        '[&>*:has(+[data-flux-input-group-suffix])>[data-flux-group-target]:not([data-invalid])]:border-e-0',

        // Buttons borders...
        '[&>[data-flux-group-target]:last-child:not(:first-child)]:border-s-0',
        '[&>[data-flux-group-target]:not(:first-child):not(:last-child)]:border-s-0',
        '[&>[data-flux-group-target]:has(+[data-flux-input-group-suffix])]:border-e-0',

        // "Weld" the borders of inputs together by overriding their border radiuses...
        '[&>[data-flux-group-target]:not(:first-child):not(:last-child)]:rounded-none',
        '[&>[data-flux-group-target]:first-child:not(:last-child)]:rounded-e-none',
        '[&>[data-flux-group-target]:last-child:not(:first-child)]:rounded-s-none',

        // "Weld" borders for sub-children of group targets (button element inside ui-select element, etc.)...
        '[&>*:not(:first-child):not(:last-child):not(:only-child)>[data-flux-group-target]]:rounded-none',
        '[&>*:first-child:not(:last-child)>[data-flux-group-target]]:rounded-e-none',
        '[&>*:last-child:not(:first-child)>[data-flux-group-target]]:rounded-s-none',

        // "Weld" borders for sub-sub-children of group targets (input element inside div inside ui-select element (combobox))...
        '[&>*:not(:first-child):not(:last-child):not(:only-child)>[data-flux-input]>[data-flux-group-target]]:rounded-none',
        '[&>*:first-child:not(:last-child)>[data-flux-input]>[data-flux-group-target]]:rounded-e-none',
        '[&>*:last-child:not(:first-child)>[data-flux-input]>[data-flux-group-target]]:rounded-s-none',

        // "Weld" borders for sub-children wrapped in tooltips (button inside tooltip inside modal trigger, etc.)...
        '[&>*:not(:first-child):not(:last-child):not(:only-child)>[data-flux-tooltip]>[data-flux-group-target]]:rounded-none',
        '[&>*:first-child:not(:last-child)>[data-flux-tooltip]>[data-flux-group-target]]:rounded-e-none',
        '[&>*:last-child:not(:first-child)>[data-flux-tooltip]>[data-flux-group-target]]:rounded-s-none',

        // Borders for sub-children wrapped in tooltips...
        '[&>*:last-child:not(:first-child)>[data-flux-tooltip]>[data-flux-group-target]:not([data-invalid])]:border-s-0',
        '[&>*:not(:first-child):not(:last-child)>[data-flux-tooltip]>[data-flux-group-target]:not([data-invalid])]:border-s-0',
    ])
    ;
?>

<?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/with-field.blade.php', $__blaze->compiledPath.'/9144d9f96a4df80329387fde1b1c2adc.php'); ?>
<?php if (isset($__slots9144d9f96a4df80329387fde1b1c2adc)) { $__slotsStack9144d9f96a4df80329387fde1b1c2adc[] = $__slots9144d9f96a4df80329387fde1b1c2adc; } ?>
<?php if (isset($__attrs9144d9f96a4df80329387fde1b1c2adc)) { $__attrsStack9144d9f96a4df80329387fde1b1c2adc[] = $__attrs9144d9f96a4df80329387fde1b1c2adc; } ?>
<?php $__attrs9144d9f96a4df80329387fde1b1c2adc = ['attributes' => $attributes,'name' => $name]; ?>
<?php $__slots9144d9f96a4df80329387fde1b1c2adc = []; ?>
<?php $__blaze->pushData($__attrs9144d9f96a4df80329387fde1b1c2adc); ?>
<?php ob_start(); ?>
    <div <?php echo e($attributes->class($classes)); ?> data-flux-input-group>
        <?php echo e($slot); ?>

    </div>
<?php $__slots9144d9f96a4df80329387fde1b1c2adc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots9144d9f96a4df80329387fde1b1c2adc); ?>
<?php _9144d9f96a4df80329387fde1b1c2adc($__blaze, $__attrs9144d9f96a4df80329387fde1b1c2adc, $__slots9144d9f96a4df80329387fde1b1c2adc, ['attributes', 'name'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack9144d9f96a4df80329387fde1b1c2adc)) { $__slots9144d9f96a4df80329387fde1b1c2adc = array_pop($__slotsStack9144d9f96a4df80329387fde1b1c2adc); } ?>
<?php if (! empty($__attrsStack9144d9f96a4df80329387fde1b1c2adc)) { $__attrs9144d9f96a4df80329387fde1b1c2adc = array_pop($__attrsStack9144d9f96a4df80329387fde1b1c2adc); } ?>
<?php $__blaze->popData(); ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\input\group\index.blade.php ENDPATH**/ ?>