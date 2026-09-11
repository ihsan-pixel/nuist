<?php $__env->startSection('title'); ?>
    Masuk - Nuist Mobile
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
                    <img
                        src="<?php echo e(asset('images/logo1.png')); ?>"
                        alt="Nuist"
                        onerror="this.onerror=null;this.src='<?php echo e(asset('images/logo1.png')); ?>';"
                        style="width:auto;height:auto;max-width:120px;max-height:72px;display:block;object-fit:contain;"
                    >
                </div>

                <h1 class="welcome-title">Selamat Datang Kembali</h1>
                <p class="welcome-subtitle">Masuk untuk mengakses seluruh layanan NUIST Mobile.</p>
            </div>

            <div class="welcome-card <?php echo e($errors->any() ? 'is-open' : ''); ?>" id="welcomeCard">
                <div class="card-body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
                        <div class="status-stack">
                            <div class="status-alert success"><?php echo e(session('status')); ?></div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <div class="status-stack">
                            <div class="status-alert error"><?php echo e(session('error')); ?></div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="login-panel" id="loginPanel">
                        <form method="POST" action="<?php echo e(route('mobile.login.authenticate')); ?>" class="login-form">
                            <?php echo csrf_field(); ?>

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
                                        type="text"
                                        class="input-control"
                                        value="<?php echo e(old('email', request()->cookie('mobile_login_email'))); ?>"
                                        placeholder="Masukkan email"
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
                                <label class="input-label" for="password">Password</label>
                                <div class="field-shell field-shell-password">
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
                                        placeholder="Masukkan password"
                                        required
                                    >
                                    <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                                        <svg viewBox="0 0 24 24" role="presentation">
                                            <path d="M12 5c5.33 0 9.73 3.61 11 7-1.27 3.39-5.67 7-11 7S2.27 15.39 1 12c1.27-3.39 5.67-7 11-7Zm0 2C8.08 7 4.72 9.37 3.34 12 4.72 14.63 8.08 17 12 17s7.28-2.37 8.66-5C19.28 9.37 15.92 7 12 7Zm0 2.5A2.5 2.5 0 1 1 9.5 12 2.5 2.5 0 0 1 12 9.5Z"/>
                                        </svg>
                                    </button>
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

                            <div class="form-actions">
                                <label class="remember-check">
                                    <input type="checkbox" name="remember" value="1" <?php echo e(old('remember', request()->cookie('mobile_login_remember')) ? 'checked' : ''); ?>>
                                    <span>Ingat saya</span>
                                </label>
                                <a href="<?php echo e(route('mobile.password.request')); ?>" class="forgot-link">Lupa Password?</a>
                            </div>

                            <button class="submit-btn" type="submit">Masuk</button>
                        </form>
                    </div>
                </div>
            </div>

            <p class="page-version">NUIST Mobile v1.0.0</p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('mobile._auth-loader-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var passwordInput = document.getElementById('password');
            var togglePasswordBtn = document.getElementById('togglePassword');

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function () {
                    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/mobile/login.blade.php ENDPATH**/ ?>