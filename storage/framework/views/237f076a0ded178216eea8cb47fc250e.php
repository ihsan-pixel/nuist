<?php $__env->startSection('title', 'Rekap Presensi Kehadiran'); ?>

<?php $__env->startSection('content'); ?>
<header class="mobile-header d-md-none mb-3">
    <div class="d-flex align-items-center justify-content-between px-2 py-2">
        <div>
            <div class="fw-semibold">Presensi Kehadiran</div>
            <div class="text-muted small"><?php echo e($selectedMadrasah->name); ?> (SCOD: <?php echo e($selectedMadrasah->scod ?? '-'); ?>)</div>
        </div>
        <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('mobile.dps.dashboard')); ?>">
            <i class="bx bx-home"></i>
        </a>
    </div>
</header>

<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12">
                <label class="form-label mb-1">Sekolah</label>
                <select class="form-select" name="madrasah_id" onchange="this.form.submit()">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($m->id); ?>" <?php if($m->id === $selectedMadrasah->id): echo 'selected'; endif; ?>>
                            <?php echo e($m->scod ?? '-'); ?> - <?php echo e($m->name ?? '-'); ?>

                        </option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
            </div>
            <div class="col-8">
                <label class="form-label mb-1">Bulan</label>
                <input type="month" name="month" class="form-control" value="<?php echo e($selectedMonth); ?>">
            </div>
            <div class="col-4">
                <button class="btn btn-primary w-100" type="submit">Terapkan</button>
            </div>
        </form>
        <div class="text-muted small mt-2">
            <?php echo e($monthly['month_name']); ?> | Hari KBM: <?php echo e($monthly['hari_kbm']); ?> | Hari kerja: <?php echo e($monthly['total_working_days']); ?>

        </div>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Total Guru</div>
                <div class="fw-bold"><?php echo e($monthly['summary']['total_teachers']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Persentase Hadir</div>
                <div class="fw-bold"><?php echo e($monthly['summary']['persentase_sekolah']); ?>%</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Hadir</div>
                <div class="fw-bold text-success"><?php echo e($monthly['summary']['total_hadir']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Izin</div>
                <div class="fw-bold text-warning"><?php echo e($monthly['summary']['total_izin']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Alpha</div>
                <div class="fw-bold text-danger"><?php echo e($monthly['summary']['total_alpha']); ?></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="fw-semibold mb-2">Rekap Per Guru</div>
        <div class="table-responsive">
            <table class="table table-sm table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th class="text-center">H</th>
                        <th class="text-center">I</th>
                        <th class="text-center">A</th>
                        <th class="text-center">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $monthly['teachers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo e($t['name']); ?></div>
                                <div class="text-muted" style="font-size:11px;"><?php echo e($t['ketugasan'] ?? '-'); ?></div>
                            </td>
                            <td class="text-center text-success fw-semibold"><?php echo e($t['hadir']); ?></td>
                            <td class="text-center text-warning fw-semibold"><?php echo e($t['izin']); ?></td>
                            <td class="text-center text-danger fw-semibold"><?php echo e($t['alpha']); ?></td>
                            <td class="text-center fw-semibold"><?php echo e($t['persentase_hadir']); ?>%</td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data.</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/dps/presensi-kehadiran.blade.php ENDPATH**/ ?>