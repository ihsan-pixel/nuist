<?php $__env->startSection('title', 'Nuist Mobile (APK)'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width:420px;margin:auto;">
    <h5 class="mb-3">Nuist Mobile (Bundled)</h5>

    <p>This view is used by the bundled APK. The app will load static assets from inside the APK
       and communicate with the server for API requests.</p>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="presensi-endpoint" content="<?php echo e(route('mobile.presensi.store')); ?>">

    <div class="d-grid">
        <button class="btn btn-success btn-lg" onclick="absenMobile()">Absen Sekarang</button>
    </div>

    <p class="mt-3"><small>If login is required, use the mobile login screen first.</small></p>

    <script type="module" src="/js/presensi-mobile.js"></script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/index.blade.php ENDPATH**/ ?>