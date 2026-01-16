<?php $__env->startSection('title'); ?>Dashboard UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Dashboard UPPM (Unit Pengembangan Pendidikan Ma'arif)</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h5>Deskripsi Umum</h5>
                            <p>UPPM (Unit Pengembangan Pendidikan Ma'arif) adalah sistem pengelolaan dan pembayaran iuran yang dibayarkan oleh sekolah-sekolah di bawah naungan Yayasan/Lembaga Pendidikan Ma'arif.</p>
                            <p>Iuran UPPM bersifat tahunan, dengan perhitungan nominal berdasarkan jumlah siswa, guru, dan karyawan sesuai status kepegawaiannya.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-success">
                            <h5>Tujuan Modul</h5>
                            <ul>
                                <li>Pengelolaan data sekolah dan SDM</li>
                                <li>Pengaturan dan perhitungan iuran UPPM</li>
                                <li>Penerbitan tagihan dan invoice UPPM</li>
                                <li>Monitoring pembayaran UPPM secara tahunan dan transparan</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-3">
                        <a href="<?php echo e(route('uppm.data-sekolah')); ?>" class="btn btn-primary btn-lg w-100">
                            <i class="bx bx-school"></i><br>
                            Data Sekolah
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo e(route('uppm.perhitungan-iuran')); ?>" class="btn btn-info btn-lg w-100">
                            <i class="bx bx-calculator"></i><br>
                            Perhitungan Iuran
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo e(route('uppm.tagihan')); ?>" class="btn btn-warning btn-lg w-100">
                            <i class="bx bx-receipt"></i><br>
                            Tagihan
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo e(route('uppm.pengaturan')); ?>" class="btn btn-secondary btn-lg w-100">
                            <i class="bx bx-cog"></i><br>
                            Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/index.blade.php ENDPATH**/ ?>