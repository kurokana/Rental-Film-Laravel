

<?php $__env->startSection('title', 'Login - Rental Film'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Login</h2>

        <?php if($errors->any()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500">
            </div>

            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-sm text-gray-700">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Login
            </button>
        </form>

        <p class="text-center mt-6 text-gray-600">
            Belum punya akun?
            <a href="<?php echo e(route('register')); ?>" class="text-indigo-600 hover:underline">Register</a>
        </p>

        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 font-semibold mb-2">Demo Accounts:</p>
            <div class="text-xs text-gray-600 space-y-1">
                <p><strong>Owner:</strong> owner@rentalfilm.com / password</p>
                <p><strong>Pegawai:</strong> staff@rentalfilm.com / password</p>
                <p><strong>User:</strong> john@example.com / password</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\appls\RentalFilm\resources\views/auth/login.blade.php ENDPATH**/ ?>