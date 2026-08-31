<?php
if (!function_exists('_96df369fcdb1a6d078ac974a261abe40')):
function _96df369fcdb1a6d078ac974a261abe40($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
    'name' => null,
    'align' => 'right',
    'checked' => null
];
$name ??= $attributes['name'] ?? $__defaults['name']; unset($attributes['name']);
$align ??= $attributes['align'] ?? $__defaults['align']; unset($attributes['align']);
$checked ??= $attributes['checked'] ?? $__defaults['checked']; unset($attributes['checked']);
unset($__defaults);
?>

<?php
// We only want to show the name attribute it has been set manually
// but not if it has been set from the `wire:model` attribute...
$showName = isset($name);
if (! isset($name)) {
    $name = $attributes->whereStartsWith('wire:model')->first();
}

$classes = Flux::classes()
    ->add('group h-5 w-8 min-w-8 relative inline-flex items-center outline-offset-2')
    ->add('rounded-full')
    ->add('transition')
    ->add('bg-zinc-800/15 [&[disabled]]:opacity-50 dark:bg-transparent dark:border dark:border-white/20 dark:[&[disabled]]:border-white/10')
    ->add('[print-color-adjust:exact]')
    ->add([
        'data-checked:bg-(--color-accent)',
        'data-checked:border-0',
    ])
    ;

$indicatorClasses = Flux::classes()
    ->add('size-3.5')
    ->add('rounded-full')
    ->add('transition translate-x-[0.1875rem] dark:translate-x-[0.125rem] rtl:-translate-x-[0.1875rem] dark:rtl:-translate-x-[0.125rem]')
    ->add('bg-white')
    ->add([
        'group-data-checked:translate-x-[0.9375rem] rtl:group-data-checked:-translate-x-[0.9375rem]',
        // We have to add the dark variant of the `translate-x-[0.9375rem]` to ensure that if `.dark` is added to an element mid way
        // down the DOM instead of on the root HTML element, that the above `dark:translate-x-[0.125rem]` doesn't over ride it...
        'dark:group-data-checked:translate-x-[0.9375rem] dark:rtl:group-data-checked:-translate-x-[0.9375rem]',
        'group-data-checked:bg-(--color-accent-foreground)',
    ]);
?>

<?php if ($align === 'left' || $align === 'start'): ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/with-inline-field.blade.php', $__blaze->compiledPath.'/68af09ea288c36ea7d8f1982aea8eebc.php'); ?>
<?php if (isset($__slots68af09ea288c36ea7d8f1982aea8eebc)) { $__slotsStack68af09ea288c36ea7d8f1982aea8eebc[] = $__slots68af09ea288c36ea7d8f1982aea8eebc; } ?>
<?php if (isset($__attrs68af09ea288c36ea7d8f1982aea8eebc)) { $__attrsStack68af09ea288c36ea7d8f1982aea8eebc[] = $__attrs68af09ea288c36ea7d8f1982aea8eebc; } ?>
<?php $__attrs68af09ea288c36ea7d8f1982aea8eebc = ['attributes' => $attributes]; ?>
<?php $__slots68af09ea288c36ea7d8f1982aea8eebc = []; ?>
<?php $__blaze->pushData($__attrs68af09ea288c36ea7d8f1982aea8eebc); ?>
<?php ob_start(); ?>
        <ui-switch <?php echo e($attributes->class($classes)); ?> <?php if($showName): ?> name="<?php echo e($name); ?>" <?php endif; ?> <?php if($checked): ?> checked data-checked <?php endif; ?> data-flux-control data-flux-switch>
            <span class="<?php echo e(\Illuminate\Support\Arr::toCssClasses($indicatorClasses)); ?>"></span>
        </ui-switch>
    <?php $__slots68af09ea288c36ea7d8f1982aea8eebc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots68af09ea288c36ea7d8f1982aea8eebc); ?>
<?php _68af09ea288c36ea7d8f1982aea8eebc($__blaze, $__attrs68af09ea288c36ea7d8f1982aea8eebc, $__slots68af09ea288c36ea7d8f1982aea8eebc, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack68af09ea288c36ea7d8f1982aea8eebc)) { $__slots68af09ea288c36ea7d8f1982aea8eebc = array_pop($__slotsStack68af09ea288c36ea7d8f1982aea8eebc); } ?>
<?php if (! empty($__attrsStack68af09ea288c36ea7d8f1982aea8eebc)) { $__attrs68af09ea288c36ea7d8f1982aea8eebc = array_pop($__attrsStack68af09ea288c36ea7d8f1982aea8eebc); } ?>
<?php $__blaze->popData(); ?>
<?php else: ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/with-reversed-inline-field.blade.php', $__blaze->compiledPath.'/bb241a0c7600fd24eeef5bfbb0f63cac.php'); ?>
<?php if (isset($__slotsbb241a0c7600fd24eeef5bfbb0f63cac)) { $__slotsStackbb241a0c7600fd24eeef5bfbb0f63cac[] = $__slotsbb241a0c7600fd24eeef5bfbb0f63cac; } ?>
<?php if (isset($__attrsbb241a0c7600fd24eeef5bfbb0f63cac)) { $__attrsStackbb241a0c7600fd24eeef5bfbb0f63cac[] = $__attrsbb241a0c7600fd24eeef5bfbb0f63cac; } ?>
<?php $__attrsbb241a0c7600fd24eeef5bfbb0f63cac = ['attributes' => $attributes]; ?>
<?php $__slotsbb241a0c7600fd24eeef5bfbb0f63cac = []; ?>
<?php $__blaze->pushData($__attrsbb241a0c7600fd24eeef5bfbb0f63cac); ?>
<?php ob_start(); ?>
        <ui-switch <?php echo e($attributes->class($classes)); ?> <?php if($showName): ?> name="<?php echo e($name); ?>" <?php endif; ?> <?php if($checked): ?> checked data-checked <?php endif; ?> data-flux-control data-flux-switch>
            <span class="<?php echo e(\Illuminate\Support\Arr::toCssClasses($indicatorClasses)); ?>"></span>
        </ui-switch>
    <?php $__slotsbb241a0c7600fd24eeef5bfbb0f63cac['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsbb241a0c7600fd24eeef5bfbb0f63cac); ?>
<?php _bb241a0c7600fd24eeef5bfbb0f63cac($__blaze, $__attrsbb241a0c7600fd24eeef5bfbb0f63cac, $__slotsbb241a0c7600fd24eeef5bfbb0f63cac, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackbb241a0c7600fd24eeef5bfbb0f63cac)) { $__slotsbb241a0c7600fd24eeef5bfbb0f63cac = array_pop($__slotsStackbb241a0c7600fd24eeef5bfbb0f63cac); } ?>
<?php if (! empty($__attrsStackbb241a0c7600fd24eeef5bfbb0f63cac)) { $__attrsbb241a0c7600fd24eeef5bfbb0f63cac = array_pop($__attrsStackbb241a0c7600fd24eeef5bfbb0f63cac); } ?>
<?php $__blaze->popData(); ?>
<?php endif; ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\switch.blade.php ENDPATH**/ ?>