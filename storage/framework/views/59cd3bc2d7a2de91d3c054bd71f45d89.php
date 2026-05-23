<?php $__env->startSection('title', 'Dashboard PPDB Sekolah'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h4 class="mb-4">Dashboard PPDB — <?php echo e($ppdb->nama_sekolah); ?></h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6>Total Pendaftar: <?php echo e($pendaftar->count()); ?></h6>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Asal Sekolah</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendaftar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($p->nama_lengkap); ?></td>
                        <td><?php echo e($p->asal_sekolah); ?></td>
                        <td><?php echo e($p->jurusan_pilihan); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($p->status == 'pending' ? 'secondary' : ($p->status == 'verifikasi' ? 'info' : ($p->status == 'lulus' ? 'success' : 'danger'))); ?>">
                                <?php echo e(ucfirst($p->status)); ?>

                            </span>
                        </td>
                        <td>
                            <form action="<?php echo e(route('ppdb.sekolah.verifikasi', $p->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-outline-primary">Verifikasi</button>
                            </form>

                            <form action="<?php echo e(route('ppdb.sekolah.seleksi', $p->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm d-inline w-auto">
                                    <option value="">Seleksi...</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="tidak_lulus">Tidak Lulus</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/ppdb/dashboard/sekolah.blade.php ENDPATH**/ ?>