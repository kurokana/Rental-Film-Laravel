

<?php $__env->startSection('title', 'Browse Films - Rental Film'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header & Search -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Browse Films</h1>
        
        <!-- Search & Filter Form -->
        <form method="GET" action="<?php echo e(route('films.index')); ?>" class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                        placeholder="Search films..." 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                
                <!-- Genre Filter -->
                <div>
                    <select name="genre" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="">All Genres</option>
                        <?php $__currentLoopData = $genres; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $genre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($genre->id); ?>" <?php echo e(request('genre') == $genre->id ? 'selected' : ''); ?>>
                                <?php echo e($genre->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <!-- Year Filter -->
                <div>
                    <input type="number" name="year" value="<?php echo e(request('year')); ?>" 
                        placeholder="Year" min="1900" max="<?php echo e(date('Y') + 5); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                </div>
                
                <!-- Sort -->
                <div>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                        <option value="title" <?php echo e(request('sort') == 'title' ? 'selected' : ''); ?>>Title (A-Z)</option>
                        <option value="year_desc" <?php echo e(request('sort') == 'year_desc' ? 'selected' : ''); ?>>Year (Newest)</option>
                        <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?>>Price (Low to High)</option>
                        <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?>>Price (High to Low)</option>
                        <option value="rating" <?php echo e(request('sort') == 'rating' ? 'selected' : ''); ?>>Rating (High to Low)</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex gap-2">
                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'primary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary']); ?>
                    🔍 Search
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                <a href="<?php echo e(route('films.index')); ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Films Grid -->
    <?php if($films->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <?php $__currentLoopData = $films; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $film): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginalf57467a5a128f140d0526aea26ba124a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf57467a5a128f140d0526aea26ba124a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.film-card','data' => ['film' => $film]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('film-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['film' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($film)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf57467a5a128f140d0526aea26ba124a)): ?>
<?php $attributes = $__attributesOriginalf57467a5a128f140d0526aea26ba124a; ?>
<?php unset($__attributesOriginalf57467a5a128f140d0526aea26ba124a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf57467a5a128f140d0526aea26ba124a)): ?>
<?php $component = $__componentOriginalf57467a5a128f140d0526aea26ba124a; ?>
<?php unset($__componentOriginalf57467a5a128f140d0526aea26ba124a); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mb-8">
            <?php echo e($films->links()); ?>

        </div>
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg">No films found.</p>
                <a href="<?php echo e(route('films.index')); ?>" class="mt-4 inline-block text-indigo-600 hover:underline">
                    View all films
                </a>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\appls\RentalFilm\resources\views/films/index.blade.php ENDPATH**/ ?>