<?php $__env->startSection('title'); ?>
    Reset Password - Nuist Mobile
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('mobile._auth-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('mobile._auth-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="mobile-auth-page">
        <div class="reset-card">
            <div class="card-top">
            </div>

            <div class="card-body">
                <div class="brand-pill">
                    <img src="<?php echo e(asset('images/logo favicon 1.png')); ?>" alt="Nuist">
                </div>
                <h1 class="welcome-title">Welcome!</h1>
                <p class="welcome-subtitle">Set a new password for your account</p>

                

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                    <div class="status-stack">
                        <div class="status-alert success"><?php echo e(session('status')); ?></div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="status-stack">
                        <div class="status-alert error">Periksa kembali data reset password Anda.</div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <form class="reset-form" method="POST" action="<?php echo e(route('mobile.password.update')); ?>">
                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="token" value="<?php echo e($token ?? old('token')); ?>">

                    <div class="input-group">
                        <label class="input-label" for="email">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            class="input-control"
                            value="<?php echo e($email ?? old('email')); ?>"
                            placeholder="Masukkan email akun"
                            required
                        >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="field-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password">Password Baru</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="input-control"
                            placeholder="Masukkan password baru"
                            required
                        >
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="field-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="input-group">
                        <label class="input-label" for="password_confirmation">Konfirmasi Password</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="input-control"
                            placeholder="Ulangi password baru"
                            required
                        >
                    </div>

                    <button class="submit-btn" type="submit">Reset Password</button>
                    <a class="secondary-btn" href="<?php echo e(route('mobile.login')); ?>">Kembali ke Login</a>
                </form>

                <div class="panel-footer">
                    Butuh halaman login? <a href="<?php echo e(route('mobile.login')); ?>">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('mobile._auth-loader-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/reset-password.blade.php ENDPATH**/ ?>