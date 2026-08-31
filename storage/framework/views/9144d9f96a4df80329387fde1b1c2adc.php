<?php
if (!function_exists('_9144d9f96a4df80329387fde1b1c2adc')):
function _9144d9f96a4df80329387fde1b1c2adc($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
extract(Flux::forwardedAttributes($attributes, [
    'name',
    'descriptionTrailing',
    'description',
    'label',
    'badge',
]));
?>

<?php $descriptionTrailing = $descriptionTrailing ??= $attributes->pluck('description:trailing'); ?>

<?php
$__defaults = [
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'descriptionTrailing' => null,
    'description' => null,
    'label' => null,
    'badge' => null,
];
$name ??= $attributes['name'] ?? $__defaults['name']; unset($attributes['name']);
$descriptionTrailing ??= $attributes['description-trailing'] ?? $attributes['descriptionTrailing'] ?? $__defaults['descriptionTrailing']; unset($attributes['descriptionTrailing'], $attributes['description-trailing']);
$description ??= $attributes['description'] ?? $__defaults['description']; unset($attributes['description']);
$label ??= $attributes['label'] ?? $__defaults['label']; unset($attributes['label']);
$badge ??= $attributes['badge'] ?? $__defaults['badge']; unset($attributes['badge']);
unset($__defaults);
?>

<?php if (isset($label) || isset($description) || isset($descriptionTrailing)): ?>
    <?php

        $fieldAttributes = Flux::attributesAfter('field:', $attributes, []);
        $labelAttributes = Flux::attributesAfter('label:', $attributes, ['badge' => $badge]);
        $descriptionAttributes = Flux::attributesAfter('description:', $attributes, []);
        $errorAttributes = Flux::attributesAfter('error:', $attributes, ['name' => $name]);
    ?>
    <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/field.blade.php', $__blaze->compiledPath.'/4d8e09116264dafe2df9f5ca1eb14bd8.php'); ?>
<?php if (isset($__slots4d8e09116264dafe2df9f5ca1eb14bd8)) { $__slotsStack4d8e09116264dafe2df9f5ca1eb14bd8[] = $__slots4d8e09116264dafe2df9f5ca1eb14bd8; } ?>
<?php if (isset($__attrs4d8e09116264dafe2df9f5ca1eb14bd8)) { $__attrsStack4d8e09116264dafe2df9f5ca1eb14bd8[] = $__attrs4d8e09116264dafe2df9f5ca1eb14bd8; } ?>
<?php $__attrs4d8e09116264dafe2df9f5ca1eb14bd8 = ['attributes' => $fieldAttributes]; ?>
<?php $__slots4d8e09116264dafe2df9f5ca1eb14bd8 = []; ?>
<?php $__blaze->pushData($__attrs4d8e09116264dafe2df9f5ca1eb14bd8); ?>
<?php ob_start(); ?>
        <?php if (isset($label)): ?>
            <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/label.blade.php', $__blaze->compiledPath.'/1f019748e307eeadf12a38a7c5c37317.php'); ?>
<?php if (isset($__slots1f019748e307eeadf12a38a7c5c37317)) { $__slotsStack1f019748e307eeadf12a38a7c5c37317[] = $__slots1f019748e307eeadf12a38a7c5c37317; } ?>
<?php if (isset($__attrs1f019748e307eeadf12a38a7c5c37317)) { $__attrsStack1f019748e307eeadf12a38a7c5c37317[] = $__attrs1f019748e307eeadf12a38a7c5c37317; } ?>
<?php $__attrs1f019748e307eeadf12a38a7c5c37317 = ['attributes' => $labelAttributes]; ?>
<?php $__slots1f019748e307eeadf12a38a7c5c37317 = []; ?>
<?php $__blaze->pushData($__attrs1f019748e307eeadf12a38a7c5c37317); ?>
<?php ob_start(); ?><?php echo e($label); ?><?php $__slots1f019748e307eeadf12a38a7c5c37317['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots1f019748e307eeadf12a38a7c5c37317); ?>
<?php _1f019748e307eeadf12a38a7c5c37317($__blaze, $__attrs1f019748e307eeadf12a38a7c5c37317, $__slots1f019748e307eeadf12a38a7c5c37317, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack1f019748e307eeadf12a38a7c5c37317)) { $__slots1f019748e307eeadf12a38a7c5c37317 = array_pop($__slotsStack1f019748e307eeadf12a38a7c5c37317); } ?>
<?php if (! empty($__attrsStack1f019748e307eeadf12a38a7c5c37317)) { $__attrs1f019748e307eeadf12a38a7c5c37317 = array_pop($__attrsStack1f019748e307eeadf12a38a7c5c37317); } ?>
<?php $__blaze->popData(); ?>
        <?php endif; ?>

        <?php if (isset($description)): ?>
            <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/description.blade.php', $__blaze->compiledPath.'/d7bb790918ee87c2c54a86c0bfe6fb94.php'); ?>
<?php if (isset($__slotsd7bb790918ee87c2c54a86c0bfe6fb94)) { $__slotsStackd7bb790918ee87c2c54a86c0bfe6fb94[] = $__slotsd7bb790918ee87c2c54a86c0bfe6fb94; } ?>
<?php if (isset($__attrsd7bb790918ee87c2c54a86c0bfe6fb94)) { $__attrsStackd7bb790918ee87c2c54a86c0bfe6fb94[] = $__attrsd7bb790918ee87c2c54a86c0bfe6fb94; } ?>
<?php $__attrsd7bb790918ee87c2c54a86c0bfe6fb94 = ['attributes' => $descriptionAttributes]; ?>
<?php $__slotsd7bb790918ee87c2c54a86c0bfe6fb94 = []; ?>
<?php $__blaze->pushData($__attrsd7bb790918ee87c2c54a86c0bfe6fb94); ?>
<?php ob_start(); ?><?php echo e($description); ?><?php $__slotsd7bb790918ee87c2c54a86c0bfe6fb94['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsd7bb790918ee87c2c54a86c0bfe6fb94); ?>
<?php _d7bb790918ee87c2c54a86c0bfe6fb94($__blaze, $__attrsd7bb790918ee87c2c54a86c0bfe6fb94, $__slotsd7bb790918ee87c2c54a86c0bfe6fb94, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackd7bb790918ee87c2c54a86c0bfe6fb94)) { $__slotsd7bb790918ee87c2c54a86c0bfe6fb94 = array_pop($__slotsStackd7bb790918ee87c2c54a86c0bfe6fb94); } ?>
<?php if (! empty($__attrsStackd7bb790918ee87c2c54a86c0bfe6fb94)) { $__attrsd7bb790918ee87c2c54a86c0bfe6fb94 = array_pop($__attrsStackd7bb790918ee87c2c54a86c0bfe6fb94); } ?>
<?php $__blaze->popData(); ?>
        <?php endif; ?>

        <?php echo e($slot); ?>


        
        <?php $__getScope = fn($scope = []) => $scope; ?><?php if (isset($scope)) $__scope = $scope; ?><?php $scope = $__getScope(scope: ['attributes' => $errorAttributes->getAttributes()]); ?>
        <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/error.blade.php', $__blaze->compiledPath.'/07325fa23a0500117ba48afedc5c2e88.php'); ?>
<?php $__blaze->pushData(['attributes' => new \Illuminate\View\ComponentAttributeBag($scope['attributes'])]); ?>
<?php _07325fa23a0500117ba48afedc5c2e88($__blaze, ['attributes' => new \Illuminate\View\ComponentAttributeBag($scope['attributes'])], [], ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
        <?php if (isset($__scope)) { $scope = $__scope; unset($__scope); } ?>

        <?php if (isset($descriptionTrailing)): ?>
            <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/description.blade.php', $__blaze->compiledPath.'/d7bb790918ee87c2c54a86c0bfe6fb94.php'); ?>
<?php if (isset($__slotsd7bb790918ee87c2c54a86c0bfe6fb94)) { $__slotsStackd7bb790918ee87c2c54a86c0bfe6fb94[] = $__slotsd7bb790918ee87c2c54a86c0bfe6fb94; } ?>
<?php if (isset($__attrsd7bb790918ee87c2c54a86c0bfe6fb94)) { $__attrsStackd7bb790918ee87c2c54a86c0bfe6fb94[] = $__attrsd7bb790918ee87c2c54a86c0bfe6fb94; } ?>
<?php $__attrsd7bb790918ee87c2c54a86c0bfe6fb94 = ['attributes' => $descriptionAttributes]; ?>
<?php $__slotsd7bb790918ee87c2c54a86c0bfe6fb94 = []; ?>
<?php $__blaze->pushData($__attrsd7bb790918ee87c2c54a86c0bfe6fb94); ?>
<?php ob_start(); ?><?php echo e($descriptionTrailing); ?><?php $__slotsd7bb790918ee87c2c54a86c0bfe6fb94['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsd7bb790918ee87c2c54a86c0bfe6fb94); ?>
<?php _d7bb790918ee87c2c54a86c0bfe6fb94($__blaze, $__attrsd7bb790918ee87c2c54a86c0bfe6fb94, $__slotsd7bb790918ee87c2c54a86c0bfe6fb94, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackd7bb790918ee87c2c54a86c0bfe6fb94)) { $__slotsd7bb790918ee87c2c54a86c0bfe6fb94 = array_pop($__slotsStackd7bb790918ee87c2c54a86c0bfe6fb94); } ?>
<?php if (! empty($__attrsStackd7bb790918ee87c2c54a86c0bfe6fb94)) { $__attrsd7bb790918ee87c2c54a86c0bfe6fb94 = array_pop($__attrsStackd7bb790918ee87c2c54a86c0bfe6fb94); } ?>
<?php $__blaze->popData(); ?>
        <?php endif; ?>
    <?php $__slots4d8e09116264dafe2df9f5ca1eb14bd8['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots4d8e09116264dafe2df9f5ca1eb14bd8); ?>
<?php _4d8e09116264dafe2df9f5ca1eb14bd8($__blaze, $__attrs4d8e09116264dafe2df9f5ca1eb14bd8, $__slots4d8e09116264dafe2df9f5ca1eb14bd8, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack4d8e09116264dafe2df9f5ca1eb14bd8)) { $__slots4d8e09116264dafe2df9f5ca1eb14bd8 = array_pop($__slotsStack4d8e09116264dafe2df9f5ca1eb14bd8); } ?>
<?php if (! empty($__attrsStack4d8e09116264dafe2df9f5ca1eb14bd8)) { $__attrs4d8e09116264dafe2df9f5ca1eb14bd8 = array_pop($__attrsStack4d8e09116264dafe2df9f5ca1eb14bd8); } ?>
<?php $__blaze->popData(); ?>
<?php else: ?>
    <?php echo e($slot); ?>

<?php endif; ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/with-field.blade.php ENDPATH**/ ?>