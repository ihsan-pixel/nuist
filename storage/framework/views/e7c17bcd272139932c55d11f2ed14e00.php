<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?> | Nuist - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</title>
    <base href="<?php echo e(url('/')); ?>/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Digital LP. Ma'arif NU PWNU DIY - Platform terintegrasi untuk manajemen madrasah, presensi, dan data pendidikan." />
    <meta name="keywords" content="nuist, ma'arif, nu, pwnu diy, sistem informasi, madrasah, presensi, pendidikan" />
    <meta name="author" content="LP. Ma'arif NU PWNU DIY" />
    <link rel="canonical" href="<?php echo e(url()->current()); ?>" />

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $__env->yieldContent('title'); ?> | Nuist - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta property="og:description" content="Sistem Informasi Digital LP. Ma'arif NU PWNU DIY - Platform terintegrasi untuk manajemen madrasah, presensi, dan data pendidikan." />
    <meta property="og:url" content="<?php echo e(url()->current()); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?php echo e(asset('images/logo favicon 1.png')); ?>" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('images/logo favicon 1.png')); ?>">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo $__env->make('layouts.head-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('css'); ?>
</head>

<body data-topbar="light" data-layout-mode="light">

    <div id="layout-wrapper">
        <?php echo $__env->make('layouts.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>

            <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <?php echo $__env->make('layouts.right-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- JAVASCRIPT -->
    <?php echo $__env->make('layouts.vendor-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Leaflet Core -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Draw Plugin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <!-- Script khusus halaman -->
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/layouts/master.blade.php ENDPATH**/ ?>