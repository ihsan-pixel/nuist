<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?php echo e(asset('images/logo favicon 1.png')); ?>" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="" height="40">
                    </span>
                </a>

                <a href="" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?php echo e(asset('images/logo favicon 1.png')); ?>" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="" height="50">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn" onclick="document.body.classList.toggle('sidebar-enable'); if (window.innerWidth >= 992) { document.body.classList.toggle('vertical-collpsed'); } else { document.body.classList.remove('vertical-collpsed'); }">
                <i class="fa fa-fw fa-bars"></i>
            </button>

           <!-- App Search-->
           

        <div class="dropdown dropdown-mega d-none d-lg-block ms-2">
            
        </div>
    </div>

    <div class="d-flex">

<div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="<?php echo e(isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg')); ?>"
                    alt="Header Avatar">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry"><?php echo e(ucfirst(Auth::user()->name)); ?></span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <?php if(Auth::user()->role === 'tenaga_pendidik' && !Auth::user()->password_changed): ?>
                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target=".change-password"><i class="bx bx-key font-size-16 align-middle me-1"></i> Ubah Password</a>
                <?php endif; ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout"><?php echo app('translator')->get('translation.Logout'); ?></span></a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            </div>
        </div>
    </div>
</div>
</header>
<!--  Change-Password example -->
<div class="modal fade change-password" tabindex="-1" role="dialog"
aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="change-password">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" value="<?php echo e(Auth::user()->id); ?>" id="data_id">
                    <div class="mb-3">
                        <label for="current_password">Current Password <span class="text-danger">*</span></label>
                        <input id="current-password" type="password"
                            class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            name="current_password" autocomplete="current_password"
                            placeholder="Enter Current Password" value="<?php echo e(old('current_password')); ?>">
                        <div class="text-danger" id="current_passwordError" data-ajax-feedback="current_password"></div>
                    </div>

                    <div class="mb-3">
                        <label for="newpassword">New Password <span class="text-danger">*</span></label>
                        <input id="password" type="password"
                            class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                            autocomplete="new_password" placeholder="Enter New Password">
                        <div class="text-danger" id="passwordError" data-ajax-feedback="password"></div>
                    </div>

                    <div class="mb-3">
                        <label for="userpassword">Confirm Password <span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            autocomplete="new_password" placeholder="Enter New Confirm password">
                        <div class="text-danger" id="password_confirmError" data-ajax-feedback="password-confirm"></div>
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light UpdatePassword" data-id="<?php echo e(Auth::user()->id); ?>"
                            type="submit">Update Password</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/layouts/topbar.blade.php ENDPATH**/ ?>