<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'sidebar' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'sidebar' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sidebar): ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/sidebar/brand.blade.php', $__blaze->compiledPath.'/7aec265e31533a900e0065eb7e443b97.php'); ?>
<?php if (isset($__slots7aec265e31533a900e0065eb7e443b97)) { $__slotsStack7aec265e31533a900e0065eb7e443b97[] = $__slots7aec265e31533a900e0065eb7e443b97; } ?>
<?php if (isset($__attrs7aec265e31533a900e0065eb7e443b97)) { $__attrsStack7aec265e31533a900e0065eb7e443b97[] = $__attrs7aec265e31533a900e0065eb7e443b97; } ?>
<?php $__attrs7aec265e31533a900e0065eb7e443b97 = ['name' => config('app.name', 'Laravel'),'attributes' => $attributes]; ?>
<?php $__slots7aec265e31533a900e0065eb7e443b97 = []; ?>
<?php $__blaze->pushData($__attrs7aec265e31533a900e0065eb7e443b97); ?>
<?php ob_start(); ?>
         <?php ob_start(); ?>
            <?php if (isset($component)) { $__componentOriginal159d6670770cb479b1921cea6416c26c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal159d6670770cb479b1921cea6416c26c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-logo-icon','data' => ['class' => 'size-5 fill-current text-white dark:text-black']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 fill-current text-white dark:text-black']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $attributes = $__attributesOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__attributesOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $component = $__componentOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__componentOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
        <?php $__slots7aec265e31533a900e0065eb7e443b97['logo'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), ['class' => 'flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground']); ?>
    <?php $__slots7aec265e31533a900e0065eb7e443b97['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots7aec265e31533a900e0065eb7e443b97); ?>
<?php _7aec265e31533a900e0065eb7e443b97($__blaze, $__attrs7aec265e31533a900e0065eb7e443b97, $__slots7aec265e31533a900e0065eb7e443b97, ['name', 'attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack7aec265e31533a900e0065eb7e443b97)) { $__slots7aec265e31533a900e0065eb7e443b97 = array_pop($__slotsStack7aec265e31533a900e0065eb7e443b97); } ?>
<?php if (! empty($__attrsStack7aec265e31533a900e0065eb7e443b97)) { $__attrs7aec265e31533a900e0065eb7e443b97 = array_pop($__attrsStack7aec265e31533a900e0065eb7e443b97); } ?>
<?php $__blaze->popData(); ?>
<?php else: ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/brand.blade.php', $__blaze->compiledPath.'/121e7327c157e851029f12c0d12f011a.php'); ?>
<?php if (isset($__slots121e7327c157e851029f12c0d12f011a)) { $__slotsStack121e7327c157e851029f12c0d12f011a[] = $__slots121e7327c157e851029f12c0d12f011a; } ?>
<?php if (isset($__attrs121e7327c157e851029f12c0d12f011a)) { $__attrsStack121e7327c157e851029f12c0d12f011a[] = $__attrs121e7327c157e851029f12c0d12f011a; } ?>
<?php $__attrs121e7327c157e851029f12c0d12f011a = ['name' => config('app.name', 'Laravel'),'attributes' => $attributes]; ?>
<?php $__slots121e7327c157e851029f12c0d12f011a = []; ?>
<?php $__blaze->pushData($__attrs121e7327c157e851029f12c0d12f011a); ?>
<?php ob_start(); ?>
         <?php ob_start(); ?>
            <?php if (isset($component)) { $__componentOriginal159d6670770cb479b1921cea6416c26c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal159d6670770cb479b1921cea6416c26c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-logo-icon','data' => ['class' => 'size-5 fill-current text-white dark:text-black']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 fill-current text-white dark:text-black']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $attributes = $__attributesOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__attributesOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $component = $__componentOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__componentOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
        <?php $__slots121e7327c157e851029f12c0d12f011a['logo'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), ['class' => 'flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground']); ?>
    <?php $__slots121e7327c157e851029f12c0d12f011a['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots121e7327c157e851029f12c0d12f011a); ?>
<?php _121e7327c157e851029f12c0d12f011a($__blaze, $__attrs121e7327c157e851029f12c0d12f011a, $__slots121e7327c157e851029f12c0d12f011a, ['name', 'attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack121e7327c157e851029f12c0d12f011a)) { $__slots121e7327c157e851029f12c0d12f011a = array_pop($__slotsStack121e7327c157e851029f12c0d12f011a); } ?>
<?php if (! empty($__attrsStack121e7327c157e851029f12c0d12f011a)) { $__attrs121e7327c157e851029f12c0d12f011a = array_pop($__attrsStack121e7327c157e851029f12c0d12f011a); } ?>
<?php $__blaze->popData(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Users\ASUS\SIPBARV2\resources\views\components\app-logo.blade.php ENDPATH**/ ?>