
<!-- Bootstrap Css -->
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link rel="preload" href="<?php echo e(asset('build/icons/boxicons.woff2')); ?>" as="font" type="font/woff2" crossorigin>
<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@5.9.55/css/materialdesignicons.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- App Css-->
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />

<style id="nuist-global-glass-sidebar">
    .vertical-menu{background:rgba(255,255,255,.68)!important;border-right:1px solid rgba(255,255,255,.78);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);box-shadow:8px 0 30px rgba(35,76,83,.06)}
    .vertical-menu #sidebar-menu>ul>li>a{margin:3px 12px;border-radius:16px;transition:background .22s ease,color .22s ease,transform .22s ease,box-shadow .22s ease}
    .vertical-menu #sidebar-menu>ul>li>a:hover{background:rgba(7,116,90,.08);transform:translateX(3px)}
    .vertical-menu #sidebar-menu>ul>li.mm-active>a,.vertical-menu #sidebar-menu>ul>li>a.active{background:rgba(7,116,90,.13);color:#075f4a!important;box-shadow:0 8px 18px rgba(7,116,90,.08)}
    .vertical-menu .menu-title{color:#6c7c86}
    body.vertical-collpsed .vertical-menu #sidebar-menu>ul>li>a{margin:3px 6px;padding:15px 0!important;min-height:55px;text-align:center;border-radius:16px}
    body.vertical-collpsed .vertical-menu #sidebar-menu>ul>li>a i{margin:0!important;min-width:0;font-size:1.45rem}
    body.vertical-collpsed .vertical-menu #sidebar-menu>ul>li.mm-active>a,body.vertical-collpsed .vertical-menu #sidebar-menu>ul>li>a.active{width:auto;background:rgba(7,116,90,.13)}
</style>
<!-- App js -->
<script src="<?php echo e(asset('build/js/plugin.js')); ?>"></script>

<!-- Laporan Akhir Tahun Create Styles -->
<link href="<?php echo e(asset('css/mobile/laporan-akhir-tahun-create.css')); ?>" rel="stylesheet" type="text/css" />

<?php echo $__env->yieldPushContent('css'); ?>
<?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/layouts/head-css.blade.php ENDPATH**/ ?>