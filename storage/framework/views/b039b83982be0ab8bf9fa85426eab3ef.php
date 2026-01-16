<?php $__env->startSection('title'); ?>Tagihan UPPM@endsection

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tagihan UPPM - Tahun <?php echo e(request('tahun', date('Y'))); ?></h4>
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
                                <th>Tahun Anggaran</th>
                                <th>Total Tagihan UPPM</th>
                                <th>Status Pembayaran</th>
                                <th>Tanggal Jatuh Tempo</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($item->madrasah->name); ?></td>
                                <td><?php echo e($item->tahun_anggaran); ?></td>
                                <td>Rp <?php echo e(number_format($item->total_nominal)); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'sebagian' ? 'warning' : 'danger')); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $item->status_pembayaran))); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $setting = \App\Models\UppmSetting::where('tahun_anggaran', $item->tahun_anggaran)->where('aktif', true)->first();
                                        $jatuhTempo = $setting ? $setting->jatuh_tempo : '-';
                                    ?>
                                    <?php echo e($jatuhTempo); ?>

                                </td>
                                <td>
                                    <a href="<?php echo e(route('uppm.invoice', $item->id)); ?>" class="btn btn-sm btn-info">
                                        <i class="bx bx-receipt"></i> Lihat Invoice
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data tagihan untuk tahun ini</td>
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

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/tagihan.blade.php ENDPATH**/ ?>