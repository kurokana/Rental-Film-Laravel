

<?php $__env->startSection('title', 'Home - Rental Film'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg shadow-xl p-12 text-white mb-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Selamat Datang di RentalFilm</h1>
        <p class="text-xl mb-8">Sewa film favorit Anda dengan mudah dan cepat</p>
        <a href="<?php echo e(route('films.index')); ?>" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
            Browse Films
        </a>
    </div>

    <!-- Featured Films -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Film Unggulan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $featuredFilms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $film): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-64 bg-gray-300 flex items-center justify-center">
                        <?php if($film->poster): ?>
                            <img src="<?php echo e(Storage::url($film->poster)); ?>" alt="<?php echo e($film->title); ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <span class="text-gray-400 text-5xl">🎬</span>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2"><?php echo e($film->title); ?></h3>
                        <p class="text-gray-600 text-sm mb-2"><?php echo e($film->genre->name); ?> • <?php echo e($film->year); ?></p>
                        <div class="flex items-center mb-3">
                            <span class="text-yellow-500">⭐</span>
                            <span class="ml-1 text-sm"><?php echo e(number_format($film->average_rating, 1)); ?></span>
                            <span class="ml-2 text-gray-500 text-sm">(<?php echo e($film->total_reviews); ?> reviews)</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-600 font-bold">Rp <?php echo e(number_format($film->rental_price, 0, ',', '.')); ?>/hari</span>
                            <a href="<?php echo e(route('films.show', $film->slug)); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <!-- New Releases -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Rilis Terbaru</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $newReleases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $film): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-64 bg-gray-300 flex items-center justify-center">
                        <?php if($film->poster): ?>
                            <img src="<?php echo e(Storage::url($film->poster)); ?>" alt="<?php echo e($film->title); ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <span class="text-gray-400 text-5xl">🎬</span>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2"><?php echo e($film->title); ?></h3>
                        <p class="text-gray-600 text-sm mb-2"><?php echo e($film->genre->name); ?> • <?php echo e($film->year); ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-600 font-bold">Rp <?php echo e(number_format($film->rental_price, 0, ',', '.')); ?>/hari</span>
                            <a href="<?php echo e(route('films.show', $film->slug)); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <!-- Popular Films -->
    <section class="mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Film Populer</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $popularFilms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $film): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="h-64 bg-gray-300 flex items-center justify-center">
                        <?php if($film->poster): ?>
                            <img src="<?php echo e(Storage::url($film->poster)); ?>" alt="<?php echo e($film->title); ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                            <span class="text-gray-400 text-5xl">🎬</span>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2"><?php echo e($film->title); ?></h3>
                        <p class="text-gray-600 text-sm mb-2"><?php echo e($film->genre->name); ?> • <?php echo e($film->year); ?></p>
                        <div class="flex items-center mb-3">
                            <span class="text-gray-600 text-sm">👥 <?php echo e($film->rentals_count); ?> rentals</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-600 font-bold">Rp <?php echo e(number_format($film->rental_price, 0, ',', '.')); ?>/hari</span>
                            <a href="<?php echo e(route('films.show', $film->slug)); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\appls\RentalFilm\resources\views/home.blade.php ENDPATH**/ ?>