<?php $__env->startSection('title'); ?>Perhitungan Iuran UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Perhitungan Iuran UPPM - Tahun <?php echo e(request('tahun', date('Y'))); ?></h4>
                <div class="card-tools">
                    <form method="GET" class="d-inline">
                        <div class="input-group">
                            <select name="tahun" class="form-control" onchange="this.form.submit()">
                                <?php for($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e(request('tahun', date('Y')) == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Sekolah</th>
                                <th>Jumlah Siswa</th>
                                <th>Jumlah PNS Sertifikasi</th>
                                <th>Jumlah PNS Non Sertifikasi</th>
                                <th>Jumlah GTY Sertifikasi</th>
                                <th>Jumlah GTY Sertifikasi Inpassing</th>
                                <th>Jumlah GTY Non Sertifikasi</th>
                                <th>Jumlah GTT</th>
                                <th>Jumlah PTY</th>
                                <th>Jumlah PTT</th>
                                <th>Jumlah Karyawan Tetap</th>
                                <th>Jumlah Karyawan Tidak Tetap</th>
                                <th>Nominal Bulanan</th>
                                <th>Total Tahunan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $perhitungan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($item['madrasah']->name); ?></td>
                                <td><?php echo e(number_format($item['data']->jumlah_siswa)); ?></td>
                                <td><?php echo e(number_format($item['data']->jumlah_guru_tetap)); ?></td>
                                <td><?php echo e(number_format($item['data']->jumlah_guru_tidak_tetap)); ?></td>
                                <td><?php echo e(number_format($item['data']->jumlah_guru_pns)); ?></td>
                                <td><?php echo e(number_format($item['data']->jumlah_guru_pppk)); ?></td>
                                <td><?php echo e(number_format($item['data']->jumlah_karyawan_tetap)); ?></td>
                                <td><?php echo e(number_format($item['data']->jumlah_karyawan_tidak_tetap)); ?></td>
                                <td>Rp <?php echo e(number_format($item['nominal_bulanan'])); ?></td>
                                <td>Rp <?php echo e(number_format($item['total_tahunan'])); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="17" class="text-center">Tidak ada data sekolah untuk tahun ini</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($perhitungan): ?>
                <div class="mt-3">
                    <h5>Rincian Pengaturan Iuran Tahun <?php echo e($tahun); ?></h5>
                    <ul>
                        <li>Siswa: Rp <?php echo e(number_format($setting->nominal_siswa)); ?> per bulan</li>
                        <li>PNS Sertifikasi: Rp <?php echo e(number_format($setting->nominal_pns_sertifikasi)); ?> per bulan</li>
                        <li>PNS Non Sertifikasi: Rp <?php echo e(number_format($setting->nominal_pns_non_sertifikasi)); ?> per bulan</li>
                        <li>GTY Sertifikasi: Rp <?php echo e(number_format($setting->nominal_gty_sertifikasi)); ?> per bulan</li>
                        <li>GTY Sertifikasi Inpassing: Rp <?php echo e(number_format($setting->nominal_gty_sertifikasi_inpassing)); ?> per bulan</li>
                        <li>GTY Non Sertifikasi: Rp <?php echo e(number_format($setting->nominal_gty_non_sertifikasi)); ?> per bulan</li>
                        <li>GTT: Rp <?php echo e(number_format($setting->nominal_gtt)); ?> per bulan</li>
                        <li>PTY: Rp <?php echo e(number_format($setting->nominal_pty)); ?> per bulan</li>
                        <li>PTT: Rp <?php echo e(number_format($setting->nominal_ptt)); ?> per bulan</li>
                        <li>Karyawan Tetap: Rp <?php echo e(number_format($setting->nominal_karyawan_tetap)); ?> per bulan</li>
                        <li>Karyawan Tidak Tetap: Rp <?php echo e(number_format($setting->nominal_karyawan_tidak_tetap)); ?> per bulan</li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/perhitungan-iuran.blade.php ENDPATH**/ ?>