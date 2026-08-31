<?php
if (!function_exists('__1dc4eaf3a93713174b27d2d41db81190')):
function __1dc4eaf3a93713174b27d2d41db81190($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
    'scrollable' => false,
    'variant' => null,
];
$scrollable ??= $attributes['scrollable'] ?? $__defaults['scrollable']; unset($attributes['scrollable']);
$variant ??= $attributes['variant'] ?? $__defaults['variant']; unset($attributes['variant']);
unset($__defaults);
?>

<?php
$classes = Flux::classes()
    ->add('flex items-center gap-1 py-3')
    ->add($scrollable ? ['overflow-x-auto overflow-y-hidden'] : [])
    ;
?>

<nav <?php echo e($attributes->class($classes)); ?> data-flux-navbar>
    <?php echo e($slot); ?>

</nav>
<?php
echo $__blaze->processPassthroughContent('ltrim', ltrim(ob_get_clean()));
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/navbar/index.blade.php ENDPATH**/ ?>