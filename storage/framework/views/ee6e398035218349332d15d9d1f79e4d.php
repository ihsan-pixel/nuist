<nav class="bottom-nav-siswa">
    <a href="<?php echo e(route('mobile.siswa.dashboard')); ?>" class="<?php echo e(request()->routeIs('mobile.siswa.dashboard') ? 'active' : ''); ?>">
        <i class="bx bx-home-alt-2"></i>Dashboard
    </a>
    <a href="<?php echo e(route('mobile.siswa.tagihan')); ?>" class="<?php echo e(request()->routeIs('mobile.siswa.tagihan') || request()->routeIs('mobile.siswa.detail') ? 'active' : ''); ?>">
        <i class="bx bx-receipt"></i>Tagihan
    </a>
    <a href="<?php echo e(route('mobile.siswa.riwayat')); ?>" class="<?php echo e(request()->routeIs('mobile.siswa.riwayat') || request()->routeIs('mobile.siswa.bukti') ? 'active' : ''); ?>">
        <i class="bx bx-history"></i>Riwayat
    </a>
    <a href="<?php echo e(route('mobile.siswa.chat')); ?>" class="<?php echo e(request()->routeIs('mobile.siswa.chat') ? 'active' : ''); ?>">
        <i class="bx bx-message-dots"></i>Chat
    </a>
    <a href="<?php echo e(route('mobile.siswa.profile')); ?>" class="<?php echo e(request()->routeIs('mobile.siswa.profile') ? 'active' : ''); ?>">
        <i class="bx bx-user-circle"></i>Profil
    </a>
</nav>

<?php echo $__env->make('mobile._auth-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('mobile._auth-loader-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/siswa/partials/nav.blade.php ENDPATH**/ ?>