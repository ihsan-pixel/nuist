<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?> | Nuist.id - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- SEO Meta -->
    <meta name="description" content="Nuist.id adalah Sistem Informasi Digital LP. Ma'arif NU PWNU DIY untuk manajemen data madrasah, tenaga pendidik, dan laporan pendidikan berbasis web.">
    <meta name="keywords" content="nuist.id, sistem informasi madrasah, LP Ma'arif NU, pendidikan DIY, aplikasi sekolah, aplikasi madrasah, pendidikan digital">
    <meta name="author" content="Nuist.id">

    <!-- Open Graph -->
    <meta property="og:title" content="Nuist.id - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY" />
    <meta property="og:description" content="Sistem Informasi Madrasah & Pendidikan berbasis web untuk LP. Ma'arif NU PWNU DIY." />
    <meta property="og:url" content="https://nuist.id" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?php echo e(asset('images/logo favicon 1.png')); ?>" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('images/logo favicon 1.png')); ?>">

    <!-- Prevent caching -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    
    <?php echo $__env->make('layouts.head-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->yieldContent('css'); ?>
</head>


<?php echo $__env->yieldContent('body'); ?>

    
    <?php echo $__env->yieldContent('content'); ?>

    
    <?php echo $__env->make('layouts.vendor-scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->yieldContent('script-bottom'); ?>

    
    <?php echo $__env->yieldContent('scripts'); ?>

</html>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/layouts/master-without-nav.blade.php ENDPATH**/ ?>