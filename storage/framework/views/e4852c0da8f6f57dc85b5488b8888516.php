<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'button', 'variant' => 'primary', 'size' => 'md']));

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

foreach (array_filter((['type' => 'button', 'variant' => 'primary', 'size' => 'md']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $variantClasses = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700',
        'secondary' => 'bg-gray-600 text-white hover:bg-gray-700',
        'success' => 'bg-green-600 text-white hover:bg-green-700',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700',
        'outline' => 'border border-indigo-600 text-indigo-600 hover:bg-indigo-50',
    ][$variant];
    
    $sizeClasses = [
        'sm' => 'px-3 py-1 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ][$size];
?>

<button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => "inline-flex items-center justify-center font-medium rounded transition {$variantClasses} {$sizeClasses}"])); ?>>
    <?php echo e($slot); ?>

</button>
<?php /**PATH E:\appls\RentalFilm\resources\views/components/button.blade.php ENDPATH**/ ?>