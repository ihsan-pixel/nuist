<?php $__env->startSection('title', 'Progres Mengajar'); ?>

<?php $__env->startSection('content'); ?>
<?php $__currentLoopData = $kabupatenOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-white">Kabupaten: <?php echo e($kabupaten); ?></h4>
            </div>
            <div class="card-body">
                <table id="datatable-<?php echo e(Str::slug($kabupaten)); ?>" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>SCOD</th>
                            <th>Nama Sekolah</th>
                            <th>Sudah Input Jadwal (%)</th>
                            <th>Rincian Jadwal</th>
                            <th>Sudah Presensi Mengajar (%)</th>
                            <th>Rincian Presensi</th>
                            <th>Guru Belum Presensi (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $madrasahs[$kabupaten] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($madrasah->scod); ?></td>
                            <td><?php echo e($madrasah->name); ?></td>
                            <td style="text-align: center; font-weight: bold;">
                                <span class="badge bg-<?php echo e($madrasah->schedule_input_percentage >= 80 ? 'success' : ($madrasah->schedule_input_percentage >= 50 ? 'warning' : 'danger')); ?>">
                                    <?php echo e($madrasah->schedule_input_percentage); ?>%
                                </span>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                                <div>Total: <?php echo e($madrasah->total_teachers ?? 0); ?></div>
                                <div>Sudah: <?php echo e($madrasah->teachers_with_schedule ?? 0); ?></div>
                                <div>Belum: <?php echo e($madrasah->teachers_without_schedule ?? 0); ?></div>
                            </td>
                            <td style="text-align: center; font-weight: bold;">
                                <span class="badge bg-<?php echo e($madrasah->attendance_percentage >= 80 ? 'success' : ($madrasah->attendance_percentage >= 50 ? 'warning' : 'danger')); ?>">
                                    <?php echo e($madrasah->attendance_percentage); ?>%
                                </span>
                            </td>
                            <td style="text-align: center; font-size: 12px;">
                                <div>Total: <?php echo e($madrasah->total_teachers ?? 0); ?></div>
                                <div>Sudah: <?php echo e($madrasah->teachers_with_attendance ?? 0); ?></div>
                                <div>Belum: <?php echo e($madrasah->teachers_without_attendance ?? 0); ?></div>
                            </td>
                            <td style="text-align: center; font-weight: bold;">
                                <span class="badge bg-<?php echo e($madrasah->attendance_pending_percentage <= 20 ? 'success' : ($madrasah->attendance_pending_percentage <= 50 ? 'warning' : 'danger')); ?>">
                                    <?php echo e($madrasah->attendance_pending_percentage); ?>%
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        <?php $__currentLoopData = $kabupatenOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        $('#datatable-<?php echo e(Str::slug($kabupaten)); ?>').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'pdf', 'print', 'colvis'
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
                    </thead>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/teaching_progress.blade.php ENDPATH**/ ?>