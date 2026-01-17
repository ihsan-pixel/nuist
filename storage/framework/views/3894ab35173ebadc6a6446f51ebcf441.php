<?php $__env->startSection('title', 'Laporan Presensi Mengajar Mingguan'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Presensi Mengajar Mingguan</h3>
                    <div class="card-tools">
                        <form method="GET" class="d-inline">
                            <div class="input-group input-group-sm">
                                <input type="week" name="week" value="<?php echo e($startOfWeek->format('Y-W')); ?>" class="form-control" onchange="this.form.submit()">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th rowspan="2" class="text-center align-middle" style="position: sticky; left: 0; background: #f8f9fa; z-index: 10;">SCOD</th>
                                <th rowspan="2" class="text-center align-middle" style="position: sticky; left: 60px; background: #f8f9fa; z-index: 10;">Nama Sekolah / Madrasah</th>
                                <th rowspan="2" class="text-center align-middle">Jumlah Tenaga Pendidik</th>
                                <th rowspan="2" class="text-center align-middle">Hari KBM</th>
                                <th colspan="2" class="text-center">Senin</th>
                                <th colspan="2" class="text-center">Selasa</th>
                                <th colspan="2" class="text-center">Rabu</th>
                                <th colspan="2" class="text-center">Kamis</th>
                                <th colspan="2" class="text-center">Jumat</th>
                                <th colspan="2" class="text-center">Sabtu</th>
                                <th rowspan="2" class="text-center align-middle">Persentase Kehadiran (%)</th>
                            </tr>
                            <tr>
                                <?php for($i = 0; $i < 6; $i++): ?>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Alpha</th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $laporanData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kabupaten): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="bg-info">
                                <td colspan="21" class="font-weight-bold text-center"><?php echo e($kabupaten['kabupaten']); ?></td>
                            </tr>
                            <?php $__currentLoopData = collect($kabupaten['madrasahs'])->sortBy(function($madrasah) { return (int)$madrasah['scod']; }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center" style="position: sticky; left: 0; background: white;"><?php echo e($madrasah['scod']); ?></td>
                                <td style="position: sticky; left: 60px; background: white;"><?php echo e($madrasah['nama']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['jumlah_tenaga_pendidik']); ?></td>
                                <td class="text-center"><?php echo e($madrasah['hari_kbm']); ?></td>
                                <?php $__currentLoopData = $madrasah['presensi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="text-center"><?php echo e($presensi['hadir']); ?></td>
                                <td class="text-center"><?php echo e($presensi['alpha']); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="text-center font-weight-bold"><?php echo e(number_format($madrasah['persentase_kehadiran'], 2)); ?>%</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr class="bg-warning font-weight-bold">
                                <td colspan="2" class="text-center" style="position: sticky; left: 0; background: #fff3cd;">TOTAL <?php echo e($kabupaten['kabupaten']); ?></td>
                                <td></td>
                                <?php for($i = 0; $i < 6; $i++): ?>
                                <td class="text-center"><?php echo e($kabupaten['total_hadir']); ?></td>
                                <td class="text-center"><?php echo e($kabupaten['total_alpha']); ?></td>
                                <?php endfor; ?>
                                <td class="text-center"><?php echo e(number_format($kabupaten['persentase_kehadiran'], 2)); ?>%</td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.table-responsive {
    max-height: 70vh;
    overflow-y: auto;
}

.table thead th {
    position: sticky;
    top: 0;
    background: #f8f9fa;
    z-index: 5;
}

.table thead th[rowspan="2"] {
    z-index: 10;
}

.table tbody tr:hover {
    background-color: #f5f5f5;
}

.bg-info {
    background-color: #d1ecf1 !important;
}

.bg-warning {
    background-color: #fff3cd !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/teaching_progress.blade.php ENDPATH**/ ?>