<?php # [BlazeFolded]:{flux::menu}:{C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/menu/index.blade.php}:{1781785518} ?>
<?php
if (!function_exists('_fd8cffe454e0cb8dcda28900d0777f37')):
function _fd8cffe454e0cb8dcda28900d0777f37($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
$__env = $__blaze->env;
$__slots['slot'] ??= new \Illuminate\View\ComponentSlot('');
if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = \Livewire\Blaze\Runtime\BlazeAttributeBag::make($__data, $__bound, $__keys);
unset($__data, $__bound, $__keys);
ob_start();
?>


<?php $iconTrailing ??= $attributes->pluck('icon:trailing'); ?>
<?php $iconVariant ??= $attributes->pluck('icon:variant'); ?>

<?php
$__defaults = [
    'iconVariant' => 'mini',
    'iconTrailing' => null,
    'heading' => '',
    'icon' => null,
    'keepOpen' => false,
];
$iconVariant ??= $attributes['icon-variant'] ?? $attributes['iconVariant'] ?? $__defaults['iconVariant']; unset($attributes['iconVariant'], $attributes['icon-variant']);
$iconTrailing ??= $attributes['icon-trailing'] ?? $attributes['iconTrailing'] ?? $__defaults['iconTrailing']; unset($attributes['iconTrailing'], $attributes['icon-trailing']);
$heading ??= $attributes['heading'] ?? $__defaults['heading']; unset($attributes['heading']);
$icon ??= $attributes['icon'] ?? $__defaults['icon']; unset($attributes['icon']);
$keepOpen ??= $attributes['keep-open'] ?? $attributes['keepOpen'] ?? $__defaults['keepOpen']; unset($attributes['keepOpen'], $attributes['keep-open']);
unset($__defaults);
?>

<?php
$iconClasses = Flux::classes()
    ->add('ms-auto text-zinc-400 [[data-flux-menu-item]:hover_&]:text-current')
    // When using the outline icon variant, we need to size it down to match the default icon sizes...
    ->add($iconVariant === 'outline' ? 'size-5' : '');
?>

<ui-submenu data-flux-menu-submenu>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/menu/item.blade.php', $__blaze->compiledPath.'/9c815b84370159337c61b86be1c149b3.php'); ?>
<?php if (isset($__slots9c815b84370159337c61b86be1c149b3)) { $__slotsStack9c815b84370159337c61b86be1c149b3[] = $__slots9c815b84370159337c61b86be1c149b3; } ?>
<?php if (isset($__attrs9c815b84370159337c61b86be1c149b3)) { $__attrsStack9c815b84370159337c61b86be1c149b3[] = $__attrs9c815b84370159337c61b86be1c149b3; } ?>
<?php $__attrs9c815b84370159337c61b86be1c149b3 = ['icon' => $icon,'iconVariant' => $iconVariant]; ?>
<?php $__slots9c815b84370159337c61b86be1c149b3 = []; ?>
<?php $__blaze->pushData($__attrs9c815b84370159337c61b86be1c149b3); ?>
<?php ob_start(); ?>
        <?php echo e($heading); ?>


         <?php ob_start(); ?>
            <?php if (is_string($iconTrailing) && $iconTrailing !== ''): ?>
                <?php $blaze_memoized_key = \Livewire\Blaze\Memoizer\Memo::key("flux::icon", ['icon' => $iconTrailing, 'variant' => $iconVariant, 'class' => $iconClasses]); ?><?php if ($blaze_memoized_key !== null && \Livewire\Blaze\Memoizer\Memo::has($blaze_memoized_key)) : ?><?php echo \Livewire\Blaze\Memoizer\Memo::get($blaze_memoized_key); ?><?php else : ?><?php ob_start(); ?><?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/index.blade.php', $__blaze->compiledPath.'/afb80f9a481bc6f154b41a9a23b85b61.php'); ?>
<?php $__blaze->pushData(['icon' => $iconTrailing,'variant' => $iconVariant,'class' => $iconClasses]); ?>
<?php _afb80f9a481bc6f154b41a9a23b85b61($__blaze, ['icon' => $iconTrailing,'variant' => $iconVariant,'class' => $iconClasses], [], ['icon', 'variant', 'class'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?><?php $blaze_memoized_html = ob_get_clean(); ?><?php if ($blaze_memoized_key !== null) { \Livewire\Blaze\Memoizer\Memo::put($blaze_memoized_key, $blaze_memoized_html); } ?><?php echo $blaze_memoized_html; ?><?php endif; ?>
            <?php elseif ($iconTrailing): ?>
                <?php echo e($iconTrailing); ?>

            <?php else: ?>
                <?php $blaze_memoized_key = \Livewire\Blaze\Memoizer\Memo::key("flux::icon", ['icon' => 'chevron-right', 'variant' => $iconVariant, 'class' => $iconClasses->add('rtl:hidden')]); ?><?php if ($blaze_memoized_key !== null && \Livewire\Blaze\Memoizer\Memo::has($blaze_memoized_key)) : ?><?php echo \Livewire\Blaze\Memoizer\Memo::get($blaze_memoized_key); ?><?php else : ?><?php ob_start(); ?><?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/index.blade.php', $__blaze->compiledPath.'/afb80f9a481bc6f154b41a9a23b85b61.php'); ?>
<?php $__blaze->pushData(['icon' => 'chevron-right','variant' => $iconVariant,'class' => $iconClasses->add('rtl:hidden')]); ?>
<?php _afb80f9a481bc6f154b41a9a23b85b61($__blaze, ['icon' => 'chevron-right','variant' => $iconVariant,'class' => $iconClasses->add('rtl:hidden')], [], ['variant', 'class'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?><?php $blaze_memoized_html = ob_get_clean(); ?><?php if ($blaze_memoized_key !== null) { \Livewire\Blaze\Memoizer\Memo::put($blaze_memoized_key, $blaze_memoized_html); } ?><?php echo $blaze_memoized_html; ?><?php endif; ?>
                <?php $blaze_memoized_key = \Livewire\Blaze\Memoizer\Memo::key("flux::icon", ['icon' => 'chevron-left', 'variant' => $iconVariant, 'class' => $iconClasses->add('hidden rtl:inline')]); ?><?php if ($blaze_memoized_key !== null && \Livewire\Blaze\Memoizer\Memo::has($blaze_memoized_key)) : ?><?php echo \Livewire\Blaze\Memoizer\Memo::get($blaze_memoized_key); ?><?php else : ?><?php ob_start(); ?><?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/index.blade.php', $__blaze->compiledPath.'/afb80f9a481bc6f154b41a9a23b85b61.php'); ?>
<?php $__blaze->pushData(['icon' => 'chevron-left','variant' => $iconVariant,'class' => $iconClasses->add('hidden rtl:inline')]); ?>
<?php _afb80f9a481bc6f154b41a9a23b85b61($__blaze, ['icon' => 'chevron-left','variant' => $iconVariant,'class' => $iconClasses->add('hidden rtl:inline')], [], ['variant', 'class'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?><?php $blaze_memoized_html = ob_get_clean(); ?><?php if ($blaze_memoized_key !== null) { \Livewire\Blaze\Memoizer\Memo::put($blaze_memoized_key, $blaze_memoized_html); } ?><?php echo $blaze_memoized_html; ?><?php endif; ?>
            <?php endif; ?>
        <?php $__slots9c815b84370159337c61b86be1c149b3['suffix'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
    <?php $__slots9c815b84370159337c61b86be1c149b3['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots9c815b84370159337c61b86be1c149b3); ?>
<?php _9c815b84370159337c61b86be1c149b3($__blaze, $__attrs9c815b84370159337c61b86be1c149b3, $__slots9c815b84370159337c61b86be1c149b3, ['icon', 'iconVariant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack9c815b84370159337c61b86be1c149b3)) { $__slots9c815b84370159337c61b86be1c149b3 = array_pop($__slotsStack9c815b84370159337c61b86be1c149b3); } ?>
<?php if (! empty($__attrsStack9c815b84370159337c61b86be1c149b3)) { $__attrs9c815b84370159337c61b86be1c149b3 = array_pop($__attrsStack9c815b84370159337c61b86be1c149b3); } ?>
<?php $__blaze->popData(); ?>

    <?php ob_start(); ?><ui-menu
    class="[:where(&amp;)]:min-w-48 p-[.3125rem] rounded-lg shadow-xs border border-zinc-200 dark:border-zinc-600 bg-white dark:bg-zinc-700 focus:outline-hidden" <?php if (($__blazeAttr = $keepOpen) !== false && !is_null($__blazeAttr)): ?>keep-open="<?php echo e($__blazeAttr === true ? 'keep-open' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?>

    popover="manual"
    data-flux-menu
>
    <?php ob_start(); ?>
        <?php echo e($slot); ?>

    <?php echo trim(ob_get_clean()); ?>

</ui-menu>
<?php echo ltrim(ob_get_clean()); ?>
</ui-submenu>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\menu\submenu.blade.php ENDPATH**/ ?>