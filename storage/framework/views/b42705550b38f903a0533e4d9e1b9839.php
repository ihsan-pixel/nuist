<?php $__env->startSection('title'); ?>
    Masuk - Nuist Mobile
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('mobile._auth-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('mobile._auth-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="mobile-auth-page">
        <div class="welcome-card <?php echo e($errors->any() ? 'is-open' : ''); ?>" id="welcomeCard">
            <div class="card-top">
            </div>

            <div class="card-body">
                <div class="brand-pill">
                    <img src="<?php echo e(asset('images/logo favicon 1.png')); ?>" alt="Nuist">
                </div>
                <h1 class="welcome-title">Welcome!</h1>
                <p class="welcome-subtitle">Nuist Mobile LP. Ma'arif NU PWNU DIY</p>

                

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
                    <p class="panel-title">Masuk ke akun Anda</p>

                    <form method="POST" action="<?php echo e(route('mobile.login.authenticate')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="input-group">
                            <label class="input-label" for="email">Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                class="input-control"
                                value="<?php echo e(old('email')); ?>"
                                placeholder="Masukkan email"
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
                            <label class="input-label" for="password">Password</label>
                            
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="input-control"
                                placeholder="Masukkan password"
                                required
                            >
                            <button type="button" class="toggle-password" id="togglePassword" aria-label="Tampilkan password">
                                &#128065;
                            </button>
                            
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

                        <button class="submit-btn" type="submit">Login Sekarang</button>
                    </form>

                    <div class="panel-footer">
                        <a href="<?php echo e(route('mobile.password.request')); ?>">Forgot password?</a>
                    </div>
                </div>

                <div class="action-stack">
                    <a class="action-btn action-btn-primary" href="<?php echo e(route('mobile.register')); ?>">Sign Up</a>
                    <button
                        type="button"
                        class="action-btn action-btn-secondary"
                        id="toggleLoginPanel"
                        aria-expanded="<?php echo e($errors->any() ? 'true' : 'false'); ?>"
                        aria-controls="loginPanel"
                    >
                        Login
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php echo $__env->make('mobile._auth-loader-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var card = document.getElementById('welcomeCard');
            var panel = document.getElementById('loginPanel');
            var togglePanelBtn = document.getElementById('toggleLoginPanel');
            var passwordInput = document.getElementById('password');
            var togglePasswordBtn = document.getElementById('togglePassword');

            function setPanelState(isOpen) {
                if (!card || !togglePanelBtn) return;

                card.classList.toggle('is-open', isOpen);
                togglePanelBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');

                if (isOpen) {
                    var emailInput = document.getElementById('email');
                    if (emailInput) {
                        setTimeout(function () {
                            emailInput.focus();
                        }, 120);
                    }
                }
            }

            if (togglePanelBtn) {
                togglePanelBtn.addEventListener('click', function () {
                    setPanelState(!card.classList.contains('is-open'));
                });
            }

            if (togglePasswordBtn && passwordInput) {
                togglePasswordBtn.addEventListener('click', function () {
                    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                });
            }

            if (panel && card.classList.contains('is-open')) {
                setPanelState(true);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/login.blade.php ENDPATH**/ ?>