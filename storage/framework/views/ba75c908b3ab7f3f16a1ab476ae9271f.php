<?php $__env->startSection('title'); ?>Data Sekolah UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Data Sekolah UPPM - Tahun <?php echo e(request('tahun', date('Y'))); ?></h4>
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

                                <th>Total Nominal UPPM per Tahun</th>
                                <th>Status Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($item->madrasah->name); ?></td>
                                <td><?php echo e(number_format($item->jumlah_siswa)); ?></td>
                                <td><?php echo e(number_format($item->jumlah_pns_sertifikasi ?? 0)); ?></td>
                                <td><?php echo e(number_format($item->jumlah_pns_non_sertifikasi ?? 0)); ?></td>
                                <td><?php echo e(number_format($item->jumlah_gty_sertifikasi ?? 0)); ?></td>
                                <td><?php echo e(number_format($item->jumlah_gty_sertifikasi_inpassing ?? 0)); ?></td>
                                <td><?php echo e(number_format($item->jumlah_gty_non_sertifikasi ?? 0)); ?></td>
                                <td><?php echo e(number_format($item->jumlah_gtt ?? 0)); ?></td>
                                <td><?php echo e(number_format($item->jumlah_pty ?? 0)); ?></td>
                                <td><?php echo e(number_format($item->jumlah_ptt ?? 0)); ?></td>
                                <td>Rp <?php echo e(number_format($item->total_nominal)); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'sebagian' ? 'warning' : 'danger')); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $item->status_pembayaran))); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if(isset($item->id)): ?>
                                        <a href="<?php echo e(route('uppm.invoice', $item->id)); ?>" class="btn btn-sm btn-info">
                                            <i class="bx bx-receipt"></i> Invoice
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada data</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="16" class="text-center">Tidak ada data sekolah untuk tahun ini</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/data-sekolah.blade.php ENDPATH**/ ?>