<?php $__env->startSection('title', config('app.name')); ?>

<?php $__env->startSection('body'); ?>
<body>
    
<?php $__env->stopSection(); ?>



<?php $__env->startSection('script-bottom'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/layouts/app.blade.php ENDPATH**/ ?>