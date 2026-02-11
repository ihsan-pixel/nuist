<?php $__env->startSection('title'); ?> Dashboard MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> MGMP <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard MGMP <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-4 col-12">
        <!-- Welcome Card - Mobile Optimized -->
        <div class="card border-0 shadow-sm hover-lift overflow-hidden mb-3" style="border-radius: 15px;">
            <div style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="text-white p-3">
                            <h5 class="text-white">Selamat Datang!</h5>
                            <p class="mb-0 text-white-50">MGMP NUIST</p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        
                    </div>
                </div>
            </div>
            
        </div>

        
        
    </div>

    
    <div class="col-xl-8 col-12">
        <!-- MGMP Overview Header -->
        

        <!-- Quick Actions Row -->
        

        
        
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/dashboard.blade.php ENDPATH**/ ?>