<?php $__env->startSection('title', 'Kelengkapan Data Madrasah'); ?>

<?php $__env->startSection('content'); ?>
<?php $__currentLoopData = $kabupatenOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-white">Kabupaten: <?php echo e($kabupaten); ?></h4>
                <a href="<?php echo e(route('admin.data_madrasah.export', ['kabupaten' => $kabupaten])); ?>" class="btn btn-success btn-sm">
                    Export Excel
                </a>
            </div>
            <div class="card-body">
                <table id="datatable-<?php echo e(Str::slug($kabupaten)); ?>" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>SCOD</th>
                            <th>Nama Madrasah</th>
                            <th>Alamat</th>
                            <th>Logo</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Map Link</th>
                            <th>Polygon (koordinat)</th>
                            <th>Hari KBM</th>
                            <th>Status Guru</th>
                            <th>Kelengkapan (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $madrasahs[$kabupaten] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($madrasah->scod); ?></td>
                            <td><?php echo e($madrasah->name); ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['alamat'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['logo'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['latitude'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['longitude'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['map_link'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;">
                                <?php echo $madrasah->field_status['polygon_koordinat'] ?? '❌'; ?>

                                <?php if($madrasah->enable_dual_polygon && $madrasah->field_status['polygon_koordinat_2'] === '✅'): ?>
                                    <br><small class="text-success">+ Dual</small>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['hari_kbm'] ?? '❌'; ?></td>
                            <td style="font-size: 20px; text-align: center;"><?php echo $madrasah->field_status['status_guru'] ?? '❌'; ?></td>
                            <td style="font-weight: bold; text-align: center;"><?php echo e($madrasah->completeness_percentage); ?>%</td>
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

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/data_madrasah.blade.php ENDPATH**/ ?>