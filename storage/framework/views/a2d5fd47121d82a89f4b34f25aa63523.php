<?php $__env->startSection('title'); ?>Invoice UPPM@endsection

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Invoice UPPM - <?php echo e($schoolData->madrasah->name); ?></h4>
                <div class="card-tools">
                    <a href="<?php echo e(route('uppm.invoice.download', $schoolData->id)); ?>" class="btn btn-primary">
                        <i class="bx bx-download"></i> Download PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Identitas Sekolah</h5>
                        <p><strong>Nama Sekolah:</strong> <?php echo e($schoolData->madrasah->name); ?></p>
                        <p><strong>Alamat:</strong> <?php echo e($schoolData->madrasah->address ?? '-'); ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5>Identitas UPPM</h5>
                        <p><strong>Unit Pengembangan Pendidikan Ma'arif</strong></p>
                        <p><strong>Tahun Anggaran:</strong> <?php echo e($schoolData->tahun_anggaran); ?></p>
                        <p><strong>Jatuh Tempo:</strong> <?php echo e($setting ? $setting->jatuh_tempo : '-'); ?></p>
                    </div>
                </div>

                <hr>

                <h5>Rincian Perhitungan Iuran</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Komponen</th>
                            <th>Jumlah</th>
                            <th>Nominal per Bulan</th>
                            <th>Total per Tahun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Siswa</td>
                            <td><?php echo e(number_format($schoolData->jumlah_siswa)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_siswa)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['siswa'])); ?></td>
                        </tr>
                        <tr>
                            <td>Guru Tetap</td>
                            <td><?php echo e(number_format($schoolData->jumlah_guru_tetap)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_guru_tetap)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['guru_tetap'])); ?></td>
                        </tr>
                        <tr>
                            <td>Guru Tidak Tetap</td>
                            <td><?php echo e(number_format($schoolData->jumlah_guru_tidak_tetap)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_guru_tidak_tetap)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['guru_tidak_tetap'])); ?></td>
                        </tr>
                        <tr>
                            <td>Guru PNS</td>
                            <td><?php echo e(number_format($schoolData->jumlah_guru_pns)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_guru_pns)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['guru_pns'])); ?></td>
                        </tr>
                        <tr>
                            <td>Guru PPPK</td>
                            <td><?php echo e(number_format($schoolData->jumlah_guru_pppk)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_guru_pppk)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['guru_pppk'])); ?></td>
                        </tr>
                        <tr>
                            <td>Karyawan Tetap</td>
                            <td><?php echo e(number_format($schoolData->jumlah_karyawan_tetap)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_karyawan_tetap)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['karyawan_tetap'])); ?></td>
                        </tr>
                        <tr>
                            <td>Karyawan Tidak Tetap</td>
                            <td><?php echo e(number_format($schoolData->jumlah_karyawan_tidak_tetap)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_karyawan_tidak_tetap)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['karyawan_tidak_tetap'])); ?></td>
                        </tr>
                        <tr class="table-primary">
                            <td colspan="3"><strong>Total Tagihan UPPM Tahunan</strong></td>
                            <td><strong>Rp <?php echo e(number_format($totalTahunan)); ?></strong></td>
                        </tr>
                    </tbody>
                </table>

                <?php if($setting && $setting->catatan): ?>
                <div class="mt-3">
                    <h6>Catatan:</h6>
                    <p><?php echo e($setting->catatan); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/invoice.blade.php ENDPATH**/ ?>