<?php $__env->startSection('title'); ?>
    Reset Password - Nuist Mobile
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('mobile._auth-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('mobile._auth-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="mobile-auth-page">
        <div class="login-shell">
            <div class="login-backdrop" aria-hidden="true">
                <span class="backdrop-orb backdrop-orb-left"></span>
                <span class="backdrop-orb backdrop-orb-right"></span>
            </div>

            <div class="auth-header">
                <div class="brand-card brand-card-inline">
                    <img src="<?php echo e(asset('images/nuist_logo.png')); ?>" alt="Nuist">
                </div>

                <h1 class="welcome-title">Reset Password</h1>
                <p class="welcome-subtitle">Buat password baru untuk akun Anda.</p>
            </div>

            <div class="reset-card welcome-card">
                <div class="card-body">
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

                    <div class="login-panel" id="loginPanel">
                        <form class="reset-form login-form" method="POST" action="<?php echo e(route('mobile.password.update')); ?>">
                            <?php echo csrf_field(); ?>

                            <input type="hidden" name="token" value="<?php echo e($token ?? old('token')); ?>">

                            <div class="auth-field-group">
                                <label class="input-label" for="email">Email</label>
                                <div class="field-shell">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="presentation">
                                            <path d="M12 13.5a5.5 5.5 0 1 0-5.5-5.5 5.5 5.5 0 0 0 5.5 5.5Zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5Z"/>
                                        </svg>
                                    </span>
                                    <input
                                        id="email"
                                        name="email"
                                        type="email"
                                        class="input-control"
                                        value="<?php echo e($email ?? old('email')); ?>"
                                        placeholder="Masukkan email akun"
                                        required
                                    >
                                </div>
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

                            <div class="auth-field-group">
                                <label class="input-label" for="password">Password Baru</label>
                                <div class="field-shell">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="presentation">
                                            <path d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-5 8a2 2 0 1 1 2-2 2 2 0 0 1-2 2Zm-2-8V6a2 2 0 0 1 4 0v2Z"/>
                                        </svg>
                                    </span>
                                    <input
                                        id="password"
                                        name="password"
                                        type="password"
                                        class="input-control"
                                        placeholder="Masukkan password baru"
                                        required
                                    >
                                </div>
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

                            <div class="auth-field-group">
                                <label class="input-label" for="password_confirmation">Konfirmasi Password</label>
                                <div class="field-shell">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" role="presentation">
                                            <path d="M17 8h-1V6a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-5 8a2 2 0 1 1 2-2 2 2 0 0 1-2 2Zm-2-8V6a2 2 0 0 1 4 0v2Z"/>
                                        </svg>
                                    </span>
                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        class="input-control"
                                        placeholder="Ulangi password baru"
                                        required
                                    >
                                </div>
                            </div>

                            <button class="submit-btn" type="submit">Reset Password</button>
                            <div class="form-actions">
                                <a class="forgot-link" href="<?php echo e(route('mobile.login')); ?>">Kembali ke Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('mobile._auth-loader-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/mobile/reset-password.blade.php ENDPATH**/ ?>