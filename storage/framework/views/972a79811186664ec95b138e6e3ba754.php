<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'error' => null
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
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'error' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="mb-4">
    <label for="<?php echo e($name); ?>" class="block text-gray-700 font-medium mb-2">
        <?php echo e($label); ?>

        <?php if($required): ?>
            <span class="text-red-500">*</span>
        <?php endif; ?>
    </label>
    
    <input 
        type="<?php echo e($type); ?>"
        name="<?php echo e($name); ?>"
        id="<?php echo e($name); ?>"
        value="<?php echo e(old($name, $value)); ?>"
        placeholder="<?php echo e($placeholder); ?>"
        <?php echo e($required ? 'required' : ''); ?>

        <?php echo e($attributes->merge(['class' => 'w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-indigo-500' . ($error ? ' border-red-500' : ' border-gray-300')])); ?>

    >
    
    <?php if($error): ?>
        <p class="mt-1 text-sm text-red-600"><?php echo e($error); ?></p>
    <?php endif; ?>
</div>
<?php /**PATH E:\appls\RentalFilm\resources\views/components/form/input.blade.php ENDPATH**/ ?>