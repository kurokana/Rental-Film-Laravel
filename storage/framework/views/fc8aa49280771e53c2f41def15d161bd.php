<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['film']));

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

foreach (array_filter((['film']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
    <div class="h-64 bg-gray-300 flex items-center justify-center">
        <?php if($film->poster): ?>
            <img src="<?php echo e(Storage::url($film->poster)); ?>" alt="<?php echo e($film->title); ?>" class="h-full w-full object-cover">
        <?php else: ?>
            <span class="text-gray-400 text-5xl">🎬</span>
        <?php endif; ?>
    </div>
    <div class="p-4">
        <h3 class="font-bold text-lg mb-2 truncate"><?php echo e($film->title); ?></h3>
        <p class="text-gray-600 text-sm mb-2"><?php echo e($film->genre->name); ?> • <?php echo e($film->year); ?></p>
        
        <?php if($film->average_rating > 0): ?>
            <div class="flex items-center mb-3">
                <span class="text-yellow-500">⭐</span>
                <span class="ml-1 text-sm"><?php echo e(number_format($film->average_rating, 1)); ?></span>
                <span class="ml-2 text-gray-500 text-sm">(<?php echo e($film->total_reviews); ?> reviews)</span>
            </div>
        <?php endif; ?>
        
        <div class="flex justify-between items-center">
            <span class="text-indigo-600 font-bold">Rp <?php echo e(number_format($film->rental_price, 0, ',', '.')); ?>/hari</span>
            <a href="<?php echo e(route('films.show', $film->slug)); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                Detail
            </a>
        </div>
    </div>
</div>
<?php /**PATH E:\appls\RentalFilm\resources\views/components/film-card.blade.php ENDPATH**/ ?>