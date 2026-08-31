<?php # [BlazeFolded]:{flux::radio.indicator}:{C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/radio/indicator.blade.php}:{1781785518} ?>
<?php
if (!function_exists('_691bbf48e61aa14de14899df06c0c299')):
function _691bbf48e61aa14de14899df06c0c299($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
    'name' => $attributes->whereStartsWith('wire:model')->first(),
];
$name ??= $attributes['name'] ?? $__defaults['name']; unset($attributes['name']);
unset($__defaults);
?>

<?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/with-inline-field.blade.php', $__blaze->compiledPath.'/68af09ea288c36ea7d8f1982aea8eebc.php'); ?>
<?php if (isset($__slots68af09ea288c36ea7d8f1982aea8eebc)) { $__slotsStack68af09ea288c36ea7d8f1982aea8eebc[] = $__slots68af09ea288c36ea7d8f1982aea8eebc; } ?>
<?php if (isset($__attrs68af09ea288c36ea7d8f1982aea8eebc)) { $__attrsStack68af09ea288c36ea7d8f1982aea8eebc[] = $__attrs68af09ea288c36ea7d8f1982aea8eebc; } ?>
<?php $__attrs68af09ea288c36ea7d8f1982aea8eebc = ['variant' => 'inline','attributes' => $attributes]; ?>
<?php $__slots68af09ea288c36ea7d8f1982aea8eebc = []; ?>
<?php $__blaze->pushData($__attrs68af09ea288c36ea7d8f1982aea8eebc); ?>
<?php ob_start(); ?>
    
    
    
    <ui-radio <?php echo e($attributes->class('flex size-[1.125rem] rounded-full mt-px outline-offset-2')); ?> data-flux-control data-flux-radio tabindex="-1">
        <?php ob_start(); ?><div class="shrink-0 size-[1.125rem] rounded-full text-sm text-zinc-700 dark:text-zinc-800 shadow-xs [ui-radio[disabled]_&amp;]:opacity-75 [ui-radio[data-checked][disabled]_&amp;]:opacity-50 [ui-radio[disabled]_&amp;]:shadow-none [ui-radio[data-checked]_&amp;]:shadow-none flex justify-center items-center [ui-radio[data-checked]_&amp;&gt;div]:block border border-zinc-300 dark:border-white/10 [ui-radio[disabled]_&amp;]:border-zinc-200 dark:[ui-radio[disabled]_&amp;]:border-white/5 [ui-radio[data-checked]_&amp;]:border-transparent data-indeterminate:border-transparent [ui-radio[data-checked]_&amp;]:[ui-radio[disabled]_&amp;]:border-transparent data-indeterminate:border-transparent [print-color-adjust:exact] bg-white dark:bg-white/10 [ui-radio[data-checked]_&amp;]:bg-[var(--color-accent)] hover:[ui-radio[data-checked]_&amp;]:bg-(--color-accent) focus:[ui-radio[data-checked]_&amp;]:bg-(--color-accent)" data-flux-radio-indicator>
    <div class="hidden size-2 rounded-full bg-[var(--color-accent-foreground)]"></div>
</div>
<?php echo ltrim(ob_get_clean()); ?>
    </ui-radio>
<?php $__slots68af09ea288c36ea7d8f1982aea8eebc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots68af09ea288c36ea7d8f1982aea8eebc); ?>
<?php _68af09ea288c36ea7d8f1982aea8eebc($__blaze, $__attrs68af09ea288c36ea7d8f1982aea8eebc, $__slots68af09ea288c36ea7d8f1982aea8eebc, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack68af09ea288c36ea7d8f1982aea8eebc)) { $__slots68af09ea288c36ea7d8f1982aea8eebc = array_pop($__slotsStack68af09ea288c36ea7d8f1982aea8eebc); } ?>
<?php if (! empty($__attrsStack68af09ea288c36ea7d8f1982aea8eebc)) { $__attrs68af09ea288c36ea7d8f1982aea8eebc = array_pop($__attrsStack68af09ea288c36ea7d8f1982aea8eebc); } ?>
<?php $__blaze->popData(); ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\radio\variants\default.blade.php ENDPATH**/ ?>