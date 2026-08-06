<?php $__env->startSection('title', 'Dashboard DPS'); ?>

<?php $__env->startSection('content'); ?>
<header class="mobile-header d-md-none mb-3">
    <div class="d-flex align-items-center justify-content-between px-2 py-2">
        <div class="d-flex align-items-center gap-2">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                    <i class="bx bx-shield-quarter"></i>
                </div>
            </div>
            <div>
                <div class="fw-semibold" style="line-height:1.1;"><?php echo e($selectedMadrasah->name); ?></div>
                <div class="text-muted small">SCOD: <?php echo e($selectedMadrasah->scod ?? '-'); ?></div>
            </div>
        </div>
        <div class="text-end">
            <div class="small text-muted">DPS</div>
            <div class="fw-semibold" style="font-size:13px;"><?php echo e($user->name); ?></div>
        </div>
    </div>
</header>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="mb-3">
            <label class="form-label mb-1">Pilih Sekolah</label>
            <select class="form-select" name="madrasah_id" onchange="this.form.submit()">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <option value="<?php echo e($m->id); ?>" <?php if($m->id === $selectedMadrasah->id): echo 'selected'; endif; ?>>
                        <?php echo e($m->scod ?? '-'); ?> - <?php echo e($m->name ?? '-'); ?>

                    </option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </select>
        </form>

        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="text-muted small">Kepala Sekolah</div>
                <div class="fw-semibold"><?php echo e($kepalaSekolah); ?></div>
            </div>
            <div class="text-end">
                <div class="text-muted small">Kabupaten</div>
                <div class="fw-semibold"><?php echo e($selectedMadrasah->kabupaten ?? '-'); ?></div>
            </div>
        </div>
        <div class="text-muted small mt-2">
            <?php echo e($selectedMadrasah->alamat ?? 'Alamat belum diisi.'); ?>

        </div>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Tenaga Pendidik</div>
                <div class="fw-bold" style="font-size:18px;"><?php echo e($jumlahGuru); ?></div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Jumlah Siswa</div>
                <div class="fw-bold" style="font-size:18px;"><?php echo e($jumlahSiswa); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="fw-semibold">Data Tenaga Pendidik</div>
            <div class="text-muted small">Tampil maks 20</div>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenagaPendidik->isEmpty()): ?>
            <div class="text-muted text-center py-2">Belum ada data tenaga pendidik.</div>
        <?php else: ?>
            <div class="d-grid gap-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tenagaPendidik; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center border rounded px-2 py-1">
                        <div class="fw-semibold" style="font-size:13px;"><?php echo e($tp->name); ?></div>
                        <div class="text-muted" style="font-size:11px;"><?php echo e($tp->ketugasan ?? '-'); ?></div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="fw-semibold mb-2">Menu DPS</div>
        <div class="d-grid gap-2">
            <a class="btn btn-outline-primary" href="<?php echo e(route('mobile.dps.presensi-kehadiran')); ?>">
                <i class="bx bx-check-square me-1"></i> Rekap Presensi Kehadiran
            </a>
            <a class="btn btn-outline-success" href="<?php echo e(route('mobile.dps.presensi-mengajar')); ?>">
                <i class="bx bx-calendar-check me-1"></i> Rekap Presensi Mengajar
            </a>
            <a class="btn btn-outline-secondary" href="<?php echo e(route('mobile.dps.profile')); ?>">
                <i class="bx bx-user me-1"></i> Profile DPS
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/dps/dashboard.blade.php ENDPATH**/ ?>