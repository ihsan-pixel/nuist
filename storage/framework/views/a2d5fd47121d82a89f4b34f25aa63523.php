<?php $__env->startSection('title'); ?>Invoice UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Invoice UPPM <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Invoice <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Invoice UPPM - <?php echo e($madrasah->name); ?></h4>
                <a href="<?php echo e(route('uppm.invoice.download', ['madrasah_id' => $madrasah->id, 'tahun' => $tahun])); ?>" class="btn btn-primary">
                    <i class="bx bx-download"></i> Download PDF
                </a>
            </div>
            <div class="card-body">
                <table style="width: 100%; border: none; margin-bottom: 1rem;">
                    <tr>
                        <td style="width: 30%; padding: 0; vertical-align: top;">
                            <img src="<?php echo e(asset('images/logo1.png')); ?>" alt="" height="50">
                        </td>
                        <td style="width: 70%; padding: 0; vertical-align: top; text-align: right;">
                            <h1 style="margin-top: 1rem; font-size: 24px;">INVOICE PEMBAYARAN UPPM</h1>
                        </td>
                    </tr>
                </table>
                <hr style="border-top: 1px dotted #484747; margin: 1rem 0;">
                <div class="row">
                    <div class="col-md-6">
                        
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="width: 40%; padding: 0; vertical-align: top;"><strong>Nama Sekolah/Madrasah</strong></td>
                                <td><strong>:</strong></td>
                                <td style="padding: 0; vertical-align: top;"><?php echo e($madrasah->name); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 40%; padding: 0; vertical-align: top;"><strong>SCOD</strong></td>
                                <td><strong>:</strong></td>
                                <td style="padding: 0; vertical-align: top;"><?php echo e($madrasah->scod); ?></td>
                            </tr>
                            <tr>
                                <td style="width: 40%; padding: 0; vertical-align: top;"><strong>Asal Kabupaten</strong></td>
                                <td><strong>:</strong></td>
                                <td style="padding: 0; vertical-align: top;"><?php echo e($madrasah->kabupaten); ?></td>
                            </tr>
                            <tr>
                                <td style="padding: 0; vertical-align: top;"><strong>Alamat</strong></td>
                                <td><strong>:</strong></td>
                                <td style="padding: 0; vertical-align: top;"><?php echo e($madrasah->alamat ?? '-'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table style="width: 100%; border: none;">
                            <tr>
                                <td style="width: 40%; padding: 0; vertical-align: top;"><strong>Tahun Anggaran</strong></td>
                                <td><strong>:</strong></td>
                                <td style="padding: 0; vertical-align: top;"><?php echo e($tahun); ?></td>
                            </tr>
                            <tr>
                                <td style="padding: 0; vertical-align: top;"><strong>Jatuh Tempo</strong></td>
                                <td><strong>:</strong></td>
                                <td style="padding: 0; vertical-align: top;"><?php echo e($setting ? $setting->jatuh_tempo : '-'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5 style="text-align: center; margin-bottom: 1rem;"><strong>RICIAN PERHITUNGAN UPPM</strong></h5>
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
                            <td><?php echo e(number_format($dataSekolah->jumlah_siswa ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_siswa ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['siswa'] ?? 0)); ?></td>
                        </tr>
                        <tr>
                            <td>PNS Sertifikasi</td>
                            <td><?php echo e(number_format($dataSekolah->jumlah_pns_sertifikasi ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_pns_sertifikasi ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['pns_sertifikasi'] ?? 0)); ?></td>
                        </tr>
                        <tr>
                            <td>PNS Non Sertifikasi</td>
                            <td><?php echo e(number_format($dataSekolah->jumlah_pns_non_sertifikasi ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_pns_non_sertifikasi ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['pns_non_sertifikasi'] ?? 0)); ?></td>
                        </tr>
                        <tr>
                            <td>GTY Sertifikasi</td>
                            <td><?php echo e(number_format($dataSekolah->jumlah_gty_sertifikasi ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_gty_sertifikasi ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['gty_sertifikasi'] ?? 0)); ?></td>
                        </tr>
                        <tr>
                            <td>GTY Sertifikasi Inpassing</td>
                            <td><?php echo e(number_format($dataSekolah->jumlah_gty_sertifikasi_inpassing ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_gty_sertifikasi_inpassing ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['gty_sertifikasi_inpassing'] ?? 0)); ?></td>
                        </tr>
                        <tr>
                            <td>GTY Non Sertifikasi</td>
                            <td><?php echo e(number_format($dataSekolah->jumlah_gty_non_sertifikasi ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_gty_non_sertifikasi ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['gty_non_sertifikasi'] ?? 0)); ?></td>
                        </tr>
                        <tr>
                            <td>GTT</td>
                            <td><?php echo e(number_format($dataSekolah->jumlah_gtt ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_gtt ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['gtt'] ?? 0)); ?></td>
                        </tr>
                        <tr>
                            <td>PTY</td>
                            <td><?php echo e(number_format($dataSekolah->jumlah_pty ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_pty ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['pty'] ?? 0)); ?></td>
                        </tr>
                        <tr>
                            <td>PTT</td>
                            <td><?php echo e(number_format($dataSekolah->jumlah_ptt ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($setting->nominal_ptt ?? 0)); ?></td>
                            <td>Rp <?php echo e(number_format($rincian['ptt'] ?? 0)); ?></td>
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

<?php $__env->startSection('script'); ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/invoice.blade.php ENDPATH**/ ?>