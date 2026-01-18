<?php $__env->startSection('title', 'Detail Pembayaran - ' . $madrasah->name); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
<?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> Detail Pembayaran <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>




<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <div class="card-body">
                    <!-- Payment Status Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="bx bx-info-circle me-2"></i>Status Pembayaran
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Status:</strong></p>
                                            <span class="badge badge-modern bg-<?php echo e($tagihan->status == 'lunas' ? 'success' : ($tagihan->status == 'sebagian' ? 'warning' : 'danger')); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $tagihan->status))); ?>

                                            </span>
                                        </div>
                                        <?php if($tagihan->tanggal_pembayaran): ?>
                                        <div class="col-md-6">
                                            <p class="mb-2"><strong>Tanggal Pembayaran:</strong></p>
                                            <p class="mb-0"><?php echo e(\Carbon\Carbon::parse($tagihan->updated_at)->format('d M Y H:i')); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($tagihan->keterangan): ?>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <p class="mb-2"><strong>Keterangan:</strong></p>
                                            <p class="mb-0"><?php echo e($tagihan->keterangan); ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="card-title mb-0">
                                        <i class="bx bx-receipt me-2"></i>Invoice Pembayaran
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <!-- Invoice Header -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <h6 class="text-muted">Dari:</h6>
                                            <h5 class="mb-1"><?php echo e($yayasan->name ?? 'LP Ma\'arif NU PWNU D.I. Yogyakarta'); ?></h5>
                                            <p class="text-muted mb-0"><?php echo e($yayasan->alamat ?? 'Jl. Kramat Raya No. 45<br>Jakarta Pusat 10450<br>Indonesia'); ?></p>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <h6 class="text-muted">Kepada:</h6>
                                            <h5 class="mb-1"><?php echo e($madrasah->name); ?></h5>
                                            <p class="text-muted mb-0"><?php echo e($madrasah->alamat ?? '-'); ?></p>
                                        </div>
                                    </div>

                                    <!-- Invoice Details -->
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Nomor Invoice:</strong></td>
                                                    <td><?php echo e($nomorInvoice); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Invoice:</strong></td>
                                                    <td><?php echo e($tagihan->created_at->format('d/m/Y')); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Periode:</strong></td>
                                                    <td>Januari - Desember <?php echo e($tahun); ?></td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jenis Tagihan:</strong></td>
                                                    <td><?php echo e($tagihan->jenis_tagihan ?? 'UPPM'); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Status Pembayaran:</strong></td>
                                                    <td>
                                                        <span class="badge badge-modern bg-<?php echo e($tagihan->status == 'lunas' ? 'success' : ($tagihan->status == 'sebagian' ? 'warning' : 'danger')); ?>">
                                                            <?php echo e(ucfirst(str_replace('_', ' ', $tagihan->status))); ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Pembayaran:</strong></td>
                                                    <td><?php echo e($tagihan->status == 'lunas' ? ($tagihan->tanggal_pembayaran ? $tagihan->tanggal_pembayaran->format('d/m/Y') : '-') : '-'); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <!-- Invoice Table -->
                                    <div class="table-responsive">
                                        <h5 style="text-align: center; margin-bottom: 1rem;"><strong>RINCIAN PERHITUNGAN UPPM</strong></h5>
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
                                    </div>

                                    <!-- Invoice Footer -->
                                    <div class="row mt-4">
                                        <div class="col-12">
                                            <div class="border-top pt-3">
                                                <p class="text-muted mb-0">
                                                    <small>
                                                        Pembayaran dapat dilakukan melalui transfer bank atau payment gateway yang tersedia.
                                                        Silakan pilih metode pembayaran di bawah ini.
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons Section -->
                    <div class="row">
                        <div class="col-12 text-center">
                            <a href="<?php echo e(route('uppm.pembayaran', ['tahun' => $tahun])); ?>" class="btn btn-secondary btn-lg">
                                <i class="bx bx-arrow-back me-2"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format number input with thousand separator
    const nominalInput = document.getElementById('nominal');
    if (nominalInput) {
        nominalInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            e.target.value = value;
        });
    }
});


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pembayaran/detail.blade.php ENDPATH**/ ?>