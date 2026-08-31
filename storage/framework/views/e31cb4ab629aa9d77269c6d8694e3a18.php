<?php
if (!function_exists('_e31cb4ab629aa9d77269c6d8694e3a18')):
function _e31cb4ab629aa9d77269c6d8694e3a18($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
$classes = Flux::classes('[grid-area:footer]')
    ->add($attributes->has('container') ? '' : 'p-6 lg:p-8')
    ;
?>

<div <?php echo e($attributes->class($classes)); ?> data-flux-footer>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/with-container.blade.php', $__blaze->compiledPath.'/f6238a13832c988a7bc301492f8bb83d.php'); ?>
<?php if (isset($__slotsf6238a13832c988a7bc301492f8bb83d)) { $__slotsStackf6238a13832c988a7bc301492f8bb83d[] = $__slotsf6238a13832c988a7bc301492f8bb83d; } ?>
<?php if (isset($__attrsf6238a13832c988a7bc301492f8bb83d)) { $__attrsStackf6238a13832c988a7bc301492f8bb83d[] = $__attrsf6238a13832c988a7bc301492f8bb83d; } ?>
<?php $__attrsf6238a13832c988a7bc301492f8bb83d = ['attributes' => $attributes->except('class')->class('p-6 lg:p-8')]; ?>
<?php $__slotsf6238a13832c988a7bc301492f8bb83d = []; ?>
<?php $__blaze->pushData($__attrsf6238a13832c988a7bc301492f8bb83d); ?>
<?php ob_start(); ?>
        <?php echo e($slot); ?>

    <?php $__slotsf6238a13832c988a7bc301492f8bb83d['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsf6238a13832c988a7bc301492f8bb83d); ?>
<?php _f6238a13832c988a7bc301492f8bb83d($__blaze, $__attrsf6238a13832c988a7bc301492f8bb83d, $__slotsf6238a13832c988a7bc301492f8bb83d, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackf6238a13832c988a7bc301492f8bb83d)) { $__slotsf6238a13832c988a7bc301492f8bb83d = array_pop($__slotsStackf6238a13832c988a7bc301492f8bb83d); } ?>
<?php if (! empty($__attrsStackf6238a13832c988a7bc301492f8bb83d)) { $__attrsf6238a13832c988a7bc301492f8bb83d = array_pop($__attrsStackf6238a13832c988a7bc301492f8bb83d); } ?>
<?php $__blaze->popData(); ?>
</div>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\stubs\resources\views\flux\footer.blade.php ENDPATH**/ ?>