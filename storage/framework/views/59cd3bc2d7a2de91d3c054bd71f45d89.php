<<<<<<< ours


<?php $__env->startSection('title', 'Dashboard PPDB - ' . $ppdbSetting->nama_sekolah); ?>

<?php $__env->startSection('content'); ?>
<?php
    $isSpmbHost = request()->getHost() === 'spmb.nuist.id';
    $statusPpdb = $ppdbSetting->ppdb_status ?? 'tutup';
    $dashboardUrl = $isSpmbHost ? url('/dashboard') : route('ppdb.sekolah.dashboard');
    $pendaftarUrl = $isSpmbHost
        ? url('/ppdb/lp/pendaftar/' . $ppdbSetting->slug)
        : route('ppdb.lp.pendaftar', $ppdbSetting->slug);
    $pengaturanUrl = $isSpmbHost
        ? url('/ppdb/lp/ppdb-settings/' . $ppdbSetting->sekolah_id)
        : route('ppdb.lp.ppdb-settings', $ppdbSetting->sekolah_id);
    $profilUrl = $isSpmbHost
        ? url('/ppdb/lp/edit/' . $ppdbSetting->sekolah_id)
        : route('ppdb.lp.edit', $ppdbSetting->sekolah_id);
?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-8">
            <h3 class="mb-1">Dashboard PPDB</h3>
            <p class="text-muted mb-0"><?php echo e($ppdbSetting->nama_sekolah); ?> - Tahun <?php echo e($ppdbSetting->tahun); ?></p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <span class="badge <?php echo e($statusPpdb === 'buka' ? 'bg-success' : 'bg-danger'); ?> fs-6 px-3 py-2">
                PPDB <?php echo e(strtoupper($statusPpdb)); ?>

            </span>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Pendaftar</p>
                    <h2 class="mb-0"><?php echo e(number_format($statistik['total_pendaftar'])); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Menunggu Verifikasi</p>
                    <h2 class="mb-0 text-warning"><?php echo e(number_format($statistik['pending'])); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Terverifikasi</p>
                    <h2 class="mb-0 text-info"><?php echo e(number_format($statistik['verifikasi'])); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Lulus</p>
                    <h2 class="mb-0 text-success"><?php echo e(number_format($statistik['lulus'])); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted mb-2">Tidak Lulus</p>
                    <h2 class="mb-0 text-danger"><?php echo e(number_format($statistik['tidak_lulus'])); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">Ringkasan PPDB</h5>
                            <p class="text-muted mb-0">Akses cepat ke menu PPDB admin sekolah.</p>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSpmbHost): ?>
                        <a href="<?php echo e($dashboardUrl); ?>" class="btn btn-outline-primary btn-sm">Refresh</a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="list-group list-group-flush">
                        <a href="<?php echo e($pendaftarUrl); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Data Pendaftar</span>
                            <span class="badge bg-primary rounded-pill"><?php echo e(number_format($statistik['total_pendaftar'])); ?></span>
                        </a>
                        <a href="<?php echo e($pengaturanUrl); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Pengaturan PPDB</span>
                            <span class="text-muted small">Kelola jadwal, status, dan jalur</span>
                        </a>
                        <a href="<?php echo e($profilUrl); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Edit Profile PPDB</span>
                            <span class="text-muted small">Perbarui informasi sekolah</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Status Saat Ini</h5>
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Nama Sekolah</span>
                            <span class="fw-semibold text-end"><?php echo e($ppdbSetting->nama_sekolah); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span>Tahun PPDB</span>
                            <span class="fw-semibold"><?php echo e($ppdbSetting->tahun); ?></span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span>Status</span>
                            <span class="fw-semibold <?php echo e($statusPpdb === 'buka' ? 'text-success' : 'text-danger'); ?>">
                                <?php echo e(strtoupper($statusPpdb)); ?>

                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Aksi Cepat</h5>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e($pendaftarUrl); ?>" class="btn btn-primary">Buka Data Pendaftar</a>
                        <a href="<?php echo e($pengaturanUrl); ?>" class="btn btn-outline-secondary">Pengaturan PPDB</a>
                        <a href="<?php echo e($profilUrl); ?>" class="btn btn-outline-secondary">Edit Profile PPDB</a>
                    </div>
                </div>
            </div>
=======


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
>>>>>>> theirs
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make(request()->getHost() === 'spmb.nuist.id' ? 'layouts.master' : 'layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/ppdb/dashboard/sekolah.blade.php ENDPATH**/ ?>