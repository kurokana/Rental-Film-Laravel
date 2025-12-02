

<?php $__env->startSection('title', $film->title . ' - Rental Film'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="<?php echo e(route('films.index')); ?>" class="text-indigo-600 hover:underline">← Back to Films</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Film Poster & Info -->
        <div class="lg:col-span-1">
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['padding' => false]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false)]); ?>
                <div class="aspect-w-2 aspect-h-3 bg-gray-300">
                    <?php if($film->poster): ?>
                        <img src="<?php echo e(Storage::url($film->poster)); ?>" alt="<?php echo e($film->title); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="flex items-center justify-center h-96">
                            <span class="text-gray-400 text-6xl">🎬</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <div class="space-y-3">
                        <div>
                            <span class="text-gray-600 text-sm">Genre</span>
                            <p class="font-medium"><?php echo e($film->genre->name); ?></p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Year</span>
                            <p class="font-medium"><?php echo e($film->year); ?></p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Duration</span>
                            <p class="font-medium"><?php echo e($film->duration); ?> minutes</p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Director</span>
                            <p class="font-medium"><?php echo e($film->director); ?></p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Stock Available</span>
                            <p class="font-medium <?php echo e($film->stock > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($film->stock); ?> copies
                            </p>
                        </div>
                        <div>
                            <span class="text-gray-600 text-sm">Rental Price</span>
                            <p class="text-2xl font-bold text-indigo-600">Rp <?php echo e(number_format($film->rental_price, 0, ',', '.')); ?>/hari</p>
                        </div>
                    </div>
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
        </div>

        <!-- Film Details & Reviews -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Title & Rating -->
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
                <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php echo e($film->title); ?></h1>
                
                <?php if($film->average_rating > 0): ?>
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <span class="text-2xl <?php echo e($i <= $film->average_rating ? 'text-yellow-500' : 'text-gray-300'); ?>">⭐</span>
                            <?php endfor; ?>
                        </div>
                        <span class="ml-3 text-lg font-semibold"><?php echo e(number_format($film->average_rating, 1)); ?></span>
                        <span class="ml-2 text-gray-600">(<?php echo e($film->total_reviews); ?> reviews)</span>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 mb-4">No reviews yet</p>
                <?php endif; ?>

                <!-- Cast -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Cast</h3>
                    <p class="text-gray-600"><?php echo e($film->cast); ?></p>
                </div>

                <!-- Synopsis -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Synopsis</h3>
                    <p class="text-gray-700 leading-relaxed"><?php echo e($film->synopsis); ?></p>
                </div>

                <!-- Add to Cart Form -->
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isUser()): ?>
                        <?php if($film->isAvailable()): ?>
                            <form method="POST" action="<?php echo e(route('cart.store')); ?>" class="border-t pt-6">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="film_id" value="<?php echo e($film->id); ?>">
                                
                                <div class="flex items-end gap-4">
                                    <div class="flex-1">
                                        <label for="rental_days" class="block text-gray-700 font-medium mb-2">
                                            Rental Duration (days)
                                        </label>
                                        <input type="number" name="rental_days" id="rental_days" value="1" min="1" max="30" required
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
                                    </div>
                                    <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'primary','size' => 'lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','size' => 'lg']); ?>
                                        🛒 Add to Cart
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
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="border-t pt-6">
                                <?php if (isset($component)) { $__componentOriginal5194778a3a7b899dcee5619d0610f5cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => ['type' => 'warning','message' => 'Film is currently out of stock']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'warning','message' => 'Film is currently out of stock']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $attributes = $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $component = $__componentOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="border-t pt-6">
                        <?php if (isset($component)) { $__componentOriginal5194778a3a7b899dcee5619d0610f5cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => ['type' => 'info','message' => 'Please login to rent this film']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'info','message' => 'Please login to rent this film']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $attributes = $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $component = $__componentOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
                        <div class="mt-4">
                            <a href="<?php echo e(route('login')); ?>" class="text-indigo-600 hover:underline">Login</a> or 
                            <a href="<?php echo e(route('register')); ?>" class="text-indigo-600 hover:underline">Register</a>
                        </div>
                    </div>
                <?php endif; ?>
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

            <!-- Reviews Section -->
            <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['title' => 'Reviews ('.e($film->reviews->count()).')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Reviews ('.e($film->reviews->count()).')']); ?>
                <?php if($film->reviews->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $film->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-b pb-4 last:border-b-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div>
                                        <p class="font-semibold"><?php echo e($review->user->name); ?></p>
                                        <div class="flex items-center">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <span class="text-sm <?php echo e($i <= $review->rating ? 'text-yellow-500' : 'text-gray-300'); ?>">⭐</span>
                                            <?php endfor; ?>
                                            <span class="ml-2 text-sm text-gray-600"><?php echo e($review->created_at->format('d M Y')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if($review->comment): ?>
                                    <p class="text-gray-700"><?php echo e($review->comment); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-4">No reviews yet. Be the first to review!</p>
                <?php endif; ?>
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
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\appls\RentalFilm\resources\views/films/show.blade.php ENDPATH**/ ?>