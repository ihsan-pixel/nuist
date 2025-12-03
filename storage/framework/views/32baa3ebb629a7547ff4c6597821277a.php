<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Daftar Pengajuan Izin</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Surat Izin</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $izinRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $presensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($presensi->user->name); ?></td>
                    <td><?php echo e($presensi->tanggal->format('d-m-Y')); ?></td>
                    <td><?php echo e($presensi->keterangan); ?></td>
                    <td>
                        <a href="<?php echo e(asset('storage/app/public/'.$presensi->surat_izin_path)); ?>" target="_blank">Lihat Surat</a>
                    </td>
                    <td><?php echo e($presensi->status_izin); ?></td>
                    <td>
                        <?php if($presensi->status_izin === 'pending'): ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve', $presensi)): ?>
                                <form action="<?php echo e(route('izin.approve', $presensi)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-success">Setujui</button>
                                </form>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reject', $presensi)): ?>
                                <form action="<?php echo e(route('izin.reject', $presensi)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/izin/index.blade.php ENDPATH**/ ?>