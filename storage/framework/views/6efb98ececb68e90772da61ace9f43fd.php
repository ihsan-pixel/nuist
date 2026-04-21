<?php $__env->startSection('title', 'Penanda Hari'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Pengaturan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Penanda Hari <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Tambah / Ubah Penanda</h6>

                <form method="POST" action="<?php echo e(route('day-markers.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-2">
                        <label class="form-label">Bulan</label>
                        <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>">
                        <div class="form-text">Untuk menampilkan daftar di kanan.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Scope</label>
                        <select name="scope_type" class="form-select" required>
                            <option value="global">Global</option>
                            <option value="school">Sekolah</option>
                            <option value="class">Kelas</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Sekolah (untuk scope sekolah/kelas)</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">-</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($m->id); ?>" <?php if($m->id === $selectedMadrasahId): echo 'selected'; endif; ?>>
                                    <?php echo e($m->scod ?? '-'); ?> - <?php echo e($m->name); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Kelas (untuk scope kelas)</label>
                        <input name="class_name" class="form-control" list="classNames" placeholder="Contoh: X-A">
                        <datalist id="classNames">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $classNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($className); ?>"></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </datalist>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Penanda</label>
                        <select name="marker" class="form-select" required>
                            <option value="normal">Hari Normal</option>
                            <option value="libur">Hari Libur</option>
                            <option value="ujian">Hari Ujian</option>
                            <option value="kegiatan_khusus">Hari Kegiatan Khusus (PKL, Study Tour, dll)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Opsional"></textarea>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-semibold mb-0">Daftar Penanda (<?php echo e(\Carbon\Carbon::createFromFormat('Y-m', $month)->locale('id')->isoFormat('MMMM YYYY')); ?>)</h6>
                    <form method="GET" class="d-flex gap-2">
                        <input type="month" name="month" class="form-control form-control-sm" value="<?php echo e($month); ?>">
                        <select name="madrasah_id" class="form-select form-select-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($m->id); ?>" <?php if($m->id === $selectedMadrasahId): echo 'selected'; endif; ?>>
                                    <?php echo e($m->scod ?? '-'); ?> - <?php echo e($m->name); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <button class="btn btn-sm btn-outline-secondary" type="submit">Terapkan</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Scope</th>
                                <th>Penanda</th>
                                <th>Catatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $markers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td style="white-space:nowrap;"><?php echo e(\Carbon\Carbon::parse($m->date)->format('Y-m-d')); ?></td>
                                    <td style="max-width:220px;">
                                        <div class="fw-semibold"><?php echo e($m->scope_key); ?></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($m->class_name): ?>
                                            <div class="text-muted small">Kelas: <?php echo e($m->class_name); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td style="min-width:170px;">
                                        <form method="POST" action="<?php echo e(route('day-markers.update', $m)); ?>" class="d-flex gap-2">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <select name="marker" class="form-select form-select-sm" style="min-width:170px;">
                                                <option value="normal" <?php if($m->marker === 'normal'): echo 'selected'; endif; ?>>Hari Normal</option>
                                                <option value="libur" <?php if($m->marker === 'libur'): echo 'selected'; endif; ?>>Hari Libur</option>
                                                <option value="ujian" <?php if($m->marker === 'ujian'): echo 'selected'; endif; ?>>Hari Ujian</option>
                                                <option value="kegiatan_khusus" <?php if($m->marker === 'kegiatan_khusus'): echo 'selected'; endif; ?>>Kegiatan Khusus</option>
                                            </select>
                                    </td>
                                    <td style="min-width:220px;">
                                            <input name="notes" class="form-control form-control-sm" value="<?php echo e($m->notes); ?>">
                                    </td>
                                    <td class="text-center" style="white-space:nowrap;">
                                            <button class="btn btn-sm btn-success" type="submit">Update</button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('day-markers.destroy', $m)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Hapus penanda ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada penanda untuk bulan ini.</td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-muted small mt-2">
                    Prioritas penanda: <span class="fw-semibold">Kelas</span> &gt; <span class="fw-semibold">Sekolah</span> &gt; <span class="fw-semibold">Global</span>.
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/day-markers/index.blade.php ENDPATH**/ ?>