<?php $__env->startSection('title', 'Detail Pembayaran UPPM - ' . $madrasah->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title mb-0">
                                <i class="bx bx-money me-2"></i>Detail Pembayaran
                            </h4>
                            <p class="card-title-desc mb-0">
                                <?php echo e($madrasah->name); ?> - Tahun <?php echo e($tahun); ?>

                            </p>
                        </div>
                        <div>
                            <a href="<?php echo e(route('uppm.pembayaran', ['tahun' => $tahun])); ?>" class="btn btn-secondary">
                                <i class="bx bx-arrow-back me-1"></i>Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- School Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title">Informasi Madrasah</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Nama Madrasah:</strong></td>
                                            <td><?php echo e($madrasah->name); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat:</strong></td>
                                            <td><?php echo e($madrasah->address ?? '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kepala Madrasah:</strong></td>
                                            <td><?php echo e($madrasah->kepala_madrasah ?? '-'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title">Ringkasan Pembayaran</h5>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Nominal Bulanan:</strong></td>
                                            <td>Rp <?php echo e(number_format($nominalBulanan, 0, ',', '.')); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Tahunan:</strong></td>
                                            <td><strong>Rp <?php echo e(number_format($totalTahunan, 0, ',', '.')); ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge badge-modern bg-warning">Belum Lunas</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bx bx-cash me-2"></i>Pembayaran Cash
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form action="<?php echo e(route('uppm.pembayaran.cash')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="madrasah_id" value="<?php echo e($madrasah->id); ?>">
                                        <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">

                                        <div class="mb-3">
                                            <label for="nominal" class="form-label">Nominal Pembayaran</label>
                                            <input type="number" class="form-control" id="nominal" name="nominal"
                                                   placeholder="Masukkan nominal" required min="0">
                                        </div>

                                        <div class="mb-3">
                                            <label for="keterangan" class="form-label">Keterangan</label>
                                            <textarea class="form-control" id="keterangan" name="keterangan"
                                                      rows="3" placeholder="Keterangan pembayaran"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="bx bx-check me-1"></i>Catat Pembayaran
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bx bx-credit-card me-2"></i>Pembayaran Digital
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Integrasi dengan Midtrans sedang dalam pengembangan.
                                    </div>

                                    <form id="midtrans-form">
                                        <input type="hidden" name="madrasah_id" value="<?php echo e($madrasah->id); ?>">
                                        <input type="hidden" name="tahun" value="<?php echo e($tahun); ?>">
                                        <input type="hidden" name="nominal" value="<?php echo e($totalTahunan); ?>">

                                        <button type="button" class="btn btn-primary" disabled>
                                            <i class="bx bx-credit-card me-1"></i>Bayar via Midtrans
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="bx bx-history me-2"></i>Riwayat Pembayaran
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Riwayat pembayaran akan ditampilkan di sini.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/pembayaran/detail.blade.php ENDPATH**/ ?>