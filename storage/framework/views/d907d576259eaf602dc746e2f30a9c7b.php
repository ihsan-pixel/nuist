<?php $__env->startSection('landing_shell', '1'); ?>
<?php $__env->startSection('title', 'Home'); ?>
<?php $__env->startSection('description', 'Sistem Informasi Digital LPMNU PWNU DIY'); ?>

<?php
    $homeStats = [
        [
            'id' => 'count1',
            'value' => $countMadrasah,
            'label' => 'Sekolah/Madrasah',
        ],
        [
            'id' => 'count2',
            'value' => $countTenagaPendidik . '+',
            'label' => 'Tenaga Pendidik Aktif',
        ],
        [
            'id' => 'count3',
            'value' => $countAdmin,
            'label' => 'Admin Operator Aktif',
        ],
    ];
?>

<?php $__env->startSection('content'); ?>
<div class="landing-page landing-home-page" data-landing-page="landing">
    <?php echo $__env->make('landing.partials.home.hero', ['landing' => $landing], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('landing.partials.home.carousel', ['madrasahs' => $madrasahs], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('landing.partials.home.profile', ['landing' => $landing], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('landing.partials.home.statistics', ['stats' => $homeStats], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('landing.partials.home.features', ['landing' => $landing], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('landing.partials.asset-style', [
        'buildAsset' => 'build/css/landing-home.min.css',
        'resourcePath' => resource_path('css/landing-home.css'),
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/landing/landing.blade.php ENDPATH**/ ?>