

<?php $__env->startSection('title', 'Payment Verification - Rental Film'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Payment Verification</h1>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b">
        <nav class="flex space-x-8">
            <a href="<?php echo e(route('pegawai.payments.index')); ?>" 
                class="py-4 px-1 border-b-2 font-medium text-sm <?php echo e(!request('status') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'); ?>">
                All Payments
            </a>
            <a href="<?php echo e(route('pegawai.payments.index', ['status' => 'pending'])); ?>" 
                class="py-4 px-1 border-b-2 font-medium text-sm <?php echo e(request('status') === 'pending' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'); ?>">
                Pending
            </a>
            <a href="<?php echo e(route('pegawai.payments.index', ['status' => 'paid'])); ?>" 
                class="py-4 px-1 border-b-2 font-medium text-sm <?php echo e(request('status') === 'paid' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'); ?>">
                Paid
            </a>
            <a href="<?php echo e(route('pegawai.payments.index', ['status' => 'rejected'])); ?>" 
                class="py-4 px-1 border-b-2 font-medium text-sm <?php echo e(request('status') === 'rejected' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700'); ?>">
                Rejected
            </a>
        </nav>
    </div>

    <?php if($payments->count() > 0): ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Payment Info -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-lg"><?php echo e($payment->payment_code); ?></h3>
                                    <p class="text-gray-600"><?php echo e($payment->rental->user->name); ?></p>
                                </div>
                                <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['type' => $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'error')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'error'))]); ?>
                                    <?php echo e(strtoupper($payment->status)); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <span class="text-gray-600 text-sm">Payment Method:</span>
                                    <p class="font-medium"><?php echo e(ucfirst($payment->payment_method)); ?></p>
                                </div>
                                <div>
                                    <span class="text-gray-600 text-sm">Amount:</span>
                                    <p class="font-medium text-indigo-600">Rp <?php echo e(number_format($payment->amount, 0, ',', '.')); ?></p>
                                </div>
                                <div>
                                    <span class="text-gray-600 text-sm">Created:</span>
                                    <p class="font-medium"><?php echo e($payment->created_at->format('d M Y H:i')); ?></p>
                                </div>
                                <?php if($payment->payment_date): ?>
                                    <div>
                                        <span class="text-gray-600 text-sm">Paid Date:</span>
                                        <p class="font-medium"><?php echo e($payment->payment_date->format('d M Y H:i')); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Film Info -->
                            <div class="border-t pt-4">
                                <p class="text-sm text-gray-600 mb-2">Rental Details:</p>
                                <p class="font-medium"><?php echo e($payment->rental->film->title); ?></p>
                                <p class="text-sm text-gray-600">
                                    <?php echo e($payment->rental->rental_days); ?> days • 
                                    <?php echo e($payment->rental->rental_date->format('d M Y')); ?> - <?php echo e($payment->rental->due_date->format('d M Y')); ?>

                                </p>
                            </div>
                        </div>

                        <!-- Proof Image (Manual Payment) -->
                        <?php if($payment->payment_method === 'manual' && $payment->proof_image): ?>
                            <div class="w-full md:w-64">
                                <p class="text-sm text-gray-600 mb-2">Proof of Transfer:</p>
                                <a href="<?php echo e(Storage::url($payment->proof_image)); ?>" target="_blank">
                                    <img src="<?php echo e(Storage::url($payment->proof_image)); ?>" alt="Proof" 
                                        class="w-full h-auto rounded border hover:opacity-75 transition">
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions -->
                    <?php if($payment->status === 'pending'): ?>
                        <div class="flex gap-2 mt-4 pt-4 border-t">
                            <form method="POST" action="<?php echo e(route('pegawai.payments.verify', $payment)); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'success','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'success','size' => 'sm']); ?>Approve Payment <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                            </form>

                            <form method="POST" action="<?php echo e(route('pegawai.payments.reject', $payment)); ?>" 
                                class="inline" onsubmit="return confirm('Reject this payment?')">
                                <?php echo csrf_field(); ?>
                                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['type' => 'submit','variant' => 'danger','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'danger','size' => 'sm']); ?>Reject Payment <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                            </form>

                            <a href="<?php echo e(route('pegawai.payments.show', $payment)); ?>">
                                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['variant' => 'outline','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','size' => 'sm']); ?>View Details <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                            </a>
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($payments->links()); ?>

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
                <p class="text-gray-500 text-lg">No payments found</p>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\appls\RentalFilm\resources\views/pegawai/payments/index.blade.php ENDPATH**/ ?>