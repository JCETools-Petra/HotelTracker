<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['active', 'icon']));

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

foreach (array_filter((['active', 'icon']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md transition-colors duration-150'
            : 'flex items-center px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md transition-colors duration-150';
?>

<a <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php if(isset($icon)): ?>
        <span class="mr-3">
            <?php echo e($icon); ?>

        </span>
    <?php endif; ?>
    <span><?php echo e($slot); ?></span>
</a><?php /**PATH C:\xampp\htdocs\HotelTracker\resources\views/components/side-nav-link.blade.php ENDPATH**/ ?>