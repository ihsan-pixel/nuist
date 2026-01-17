<?php $__env->startSection('title'); ?>
    Kelas Berjalan - <?php echo e($school->name); ?> (<?php echo e($selectedDay); ?>)
<?php $__env->stopSection(); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> <a href="<?php echo e(route('teaching-schedules.index')); ?>">Jadwal Mengajar</a> <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Kelas Berjalan <?php echo e($school->name); ?> (<?php echo e($selectedDay); ?>) <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />


<style>
    /* Pastikan konten bisa di-scroll dan tidak terpotong */
    .main-content,
    .page-content,
    body,
    html {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        min-height: 100vh;
    }

    /* Pastikan grid membungkus (wrap) semua kolom hari */
    .row {
        flex-wrap: wrap !important;
    }

    /* Jarak antar kolom agar tidak dempet */
    .row > [class*='col-'] {
        margin-bottom: 1rem;
    }

    /* Sidebar handling: beri margin ke konten */
    .content-page {
        margin-left: 260px;
        transition: all 0.3s ease;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 992px) {
        .content-page {
            margin-left: 0;
        }
    }

    /* Card header styling */
    .card-header.bg-success {
        background-color: #007b5e !important;
    }

    /* Scroll halus */
    html {
        scroll-behavior: smooth;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="bx bx-group me-2"></i>Kelas Berjalan - <?php echo e($school->name); ?>

                            </h4>
                            <p class="mb-0 text-muted">
                                Kabupaten: <?php echo e($school->kabupaten); ?> | SCOD: <?php echo e($school->scod); ?>

                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form method="GET" action="<?php echo e(route('teaching-schedules.school-classes', $school->id)); ?>" class="d-flex align-items-center" id="date-form">
                                <input
                                    type="date"
                                    id="date-picker"
                                    name="date"
                                    class="form-control form-control-sm"
                                    value="<?php echo e($selectedDate->format('Y-m-d')); ?>">
                            </form>
                        </div>
                    </div>


                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <a href="<?php echo e(route('teaching-schedules.index')); ?>" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali ke Daftar Madrasah
                        </a>
                    </div>

                    <div class="row">
                        <?php if($classesByDay->has($selectedDay)): ?>
                            <?php
                                $dayClasses = $classesByDay[$selectedDay];
                            ?>

                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-success text-white py-3">
                                        <h5 class="mb-0">
                                            <i class="bx bx-calendar-week me-2"></i><?php echo e($selectedDay); ?>

                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <?php if($dayClasses->isNotEmpty()): ?>
                                            <?php $__currentLoopData = $dayClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className => $schedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="mb-4">
                                                <div class="d-flex align-items-center mb-3">
                                                    <i class="bx bx-group me-2 text-muted" style="font-size: 1.1rem;"></i>
                                                    <strong class="text-success h5 mb-0"><?php echo e($className); ?></strong>
                                                </div>
                                                <div class="row">
                                                    <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-lg-6 col-xl-4 col-md-6 col-sm-12 mb-3">
                                                        <div class="d-flex justify-content-between align-items-start p-3 border rounded bg-light h-100">
                                                            <div class="flex-grow-1 me-3">
                                                                <div class="d-flex align-items-center flex-wrap mb-2">
                                                                    <?php if($schedule->teacher): ?>
                                                                        <span class="badge bg-success me-1">Terisi</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-warning me-1">Kosong</span>
                                                                    <?php endif; ?>
                                                                    <span class="badge bg-primary me-1"><?php echo e($schedule->subject); ?></span>
                                                                    <?php if($schedule->has_attendance_today): ?>
                                                                        <span class="badge bg-info me-1">
                                                                            <i class="bx bx-check me-1"></i>Hadir
                                                                        </span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-secondary me-1">
                                                                            <i class="bx bx-time me-1"></i>Belum Presensi
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div>
                                                                    <small class="text-muted">
                                                                        <i class="bx bx-user me-1"></i><?php echo e($schedule->teacher ? $schedule->teacher->name : 'Belum ada guru'); ?>

                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <small class="text-muted">
                                                                    <?php echo e($schedule->start_time); ?><br><?php echo e($schedule->end_time); ?>

                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <div class="text-center py-5">
                                                <i class="bx bx-calendar-x text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0 mt-3" style="font-size: 1rem;">Tidak ada kelas pada hari <?php echo e($selectedDay); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-success text-white py-3">
                                        <h5 class="mb-0">
                                            <i class="bx bx-calendar-week me-2"></i><?php echo e($selectedDay); ?>

                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="text-center py-5">
                                            <i class="bx bx-calendar-x text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0 mt-3" style="font-size: 1rem;">Tidak ada kelas pada hari <?php echo e($selectedDay); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if($classesByDay->isEmpty()): ?>
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light rounded-circle">
                                <i class="bx bx-group font-size-24 text-muted"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">Belum ada kelas berjalan</h5>
                        <p class="text-muted">Belum ada kelas berjalan untuk madrasah ini pada hari <?php echo e($selectedDay); ?>.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    // Handle date change
    $('#date-picker').on('change', function(e) {
        e.preventDefault();
        $('#date-form').submit();
        return false;
    });
});


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/teaching-schedules/school-classes.blade.php ENDPATH**/ ?>