<?php $__env->startSection('title', 'Rekap Presensi Mengajar'); ?>

<?php $__env->startSection('content'); ?>
<header class="mobile-header d-md-none mb-3">
    <div class="d-flex align-items-center justify-content-between px-2 py-2">
        <div>
            <div class="fw-semibold">Presensi Mengajar</div>
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
                <button class="btn btn-success w-100" type="submit">Terapkan</button>
            </div>
        </form>
        <div class="text-muted small mt-2"><?php echo e($teaching['month_name']); ?></div>
    </div>
</div>

<div class="row g-2 mb-3">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Terjadwal</div>
                <div class="fw-bold"><?php echo e($teaching['summary']['total_scheduled_classes']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">Tercatat</div>
                <div class="fw-bold"><?php echo e($teaching['summary']['total_conducted_classes']); ?></div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <div class="text-muted small">%</div>
                <div class="fw-bold"><?php echo e($teaching['summary']['persentase_pelaksanaan']); ?>%</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="fw-semibold mb-2">Riwayat Presensi Mengajar</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($teaching['records'])): ?>
            <div class="text-muted text-center py-3">Belum ada data presensi mengajar.</div>
        <?php else: ?>
            <div class="d-grid gap-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teaching['records']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="border rounded p-2">
                        <div class="d-flex justify-content-between">
                            <div class="fw-semibold"><?php echo e($r['teacher']); ?></div>
                            <div class="text-muted small"><?php echo e($r['date']); ?> <?php echo e($r['time']); ?></div>
                        </div>
                        <div class="text-muted small">
                            <?php echo e($r['subject']); ?> | <?php echo e($r['class_name']); ?> | <?php echo e($r['schedule_time']); ?>

                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($r['materi'])): ?>
                            <div class="small mt-1"><span class="text-muted">Materi:</span> <?php echo e($r['materi']); ?></div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!is_null($r['present_students']) && !is_null($r['class_total_students'])): ?>
                            <div class="small mt-1">
                                <span class="text-muted">Siswa:</span>
                                <?php echo e($r['present_students']); ?>/<?php echo e($r['class_total_students']); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!is_null($r['percentage'])): ?>
                                    (<?php echo e($r['percentage']); ?>%)
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/dps/presensi-mengajar.blade.php ENDPATH**/ ?>