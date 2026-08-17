<?php $__env->startSection('title', 'Monitoring Jurnal Mengajar'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard Admin <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Monitoring Jurnal Mengajar <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);">
            <div class="card-body p-4 text-white">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h4 class="mb-1 text-white"><?php echo e(Auth::user()->madrasah->name ?? '-'); ?></h4>
                        <p class="mb-0 text-white-50">Monitoring jurnal mengajar guru dan jadwal yang belum diisi</p>
                    </div>
                    <div class="text-end">
                        <div class="small text-white-50">Periode</div>
                        <div class="fw-semibold"><?php echo e($summary['bulan']); ?></div>
                    </div>
                </div>

                <form method="GET" class="mt-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label text-white-50 mb-1">Bulan</label>
                            <input type="month" name="month" class="form-control form-control-sm" value="<?php echo e($selectedMonth); ?>">
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <label class="form-label text-white-50 mb-1">Kelas</label>
                            <select name="class_name" class="form-select form-select-sm">
                                <option value="">Semua</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($className); ?>" <?php if($selectedClass === $className): echo 'selected'; endif; ?>><?php echo e($className); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <button type="submit" class="btn btn-light btn-sm w-100 fw-semibold">Terapkan</button>
                        </div>
                    </div>
                </form>

                <div class="row g-2 mt-3">
                    <div class="col-md-3 col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                            <div class="small text-white-50">Guru aktif</div>
                            <div class="fs-4 fw-bold"><?php echo e($summary['total_guru']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                            <div class="small text-white-50">Jurnal tercatat</div>
                            <div class="fs-4 fw-bold"><?php echo e($summary['total_jurnal']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                            <div class="small text-white-50">Jadwal wajib</div>
                            <div class="fs-4 fw-bold"><?php echo e($summary['total_jadwal']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                            <div class="small text-white-50">Belum jurnal</div>
                            <div class="fs-4 fw-bold"><?php echo e($summary['total_belum_jurnal']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="mb-1 text-dark">Jurnal Sudah Tercatat</h5>
                        <p class="mb-0 text-muted small">Data tampil berdasarkan bulan dan filter kelas</p>
                    </div>
                    <span class="badge bg-success-subtle text-success"><?php echo e($records->total()); ?></span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($records->isEmpty()): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="mdi mdi-book-open-page-variant fs-1"></i>
                        <div class="mt-2">Belum ada jurnal mengajar pada periode ini.</div>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Guru</th>
                                    <th>Kelas</th>
                                    <th>Mapel</th>
                                    <th>Jam</th>
                                    <th>Materi</th>
                                    <th>Siswa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <?php ($schedule = $record->teachingSchedule); ?>
                                    <tr>
                                        <td><?php echo e(\Carbon\Carbon::parse($record->tanggal)->format('d M')); ?></td>
                                        <td><?php echo e($schedule->teacher->name ?? '-'); ?></td>
                                        <td><?php echo e($schedule->classNameLabel() ?: ($schedule->class_name ?? '-')); ?></td>
                                        <td><?php echo e($schedule->subject ?? '-'); ?></td>
                                        <td><?php echo e(trim(($schedule->start_time ?? '-') . ' - ' . ($schedule->end_time ?? '-'))); ?></td>
                                        <td style="max-width: 240px;"><?php echo e($record->materi ?: '-'); ?></td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!is_null($record->present_students) && !is_null($record->class_total_students)): ?>
                                                <?php echo e($record->present_students); ?>/<?php echo e($record->class_total_students); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <?php echo e($records->links('vendor.pagination.bootstrap-5')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="mb-1 text-dark">Belum Jurnal</h5>
                        <p class="mb-0 text-muted small">Jadwal yang sudah ada tapi belum dicatat jurnalnya</p>
                    </div>
                    <span class="badge bg-warning-subtle text-warning"><?php echo e($missingJournals->count()); ?></span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($missingJournals->isEmpty()): ?>
                    <div class="text-center py-4 text-muted">Semua jadwal pada periode ini sudah punya jurnal.</div>
                <?php else: ?>
                    <div class="d-grid gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $missingJournals->take(12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="border rounded-3 p-3">
                                <div class="d-flex justify-content-between gap-2">
                                    <div class="fw-semibold"><?php echo e($item['teacher']); ?></div>
                                    <div class="text-muted small"><?php echo e(\Carbon\Carbon::parse($item['date'])->format('d M')); ?></div>
                                </div>
                                <div class="text-muted small mt-1">
                                    <?php echo e($item['class_name']); ?> | <?php echo e($item['subject']); ?> | <?php echo e($item['time']); ?>

                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="mb-1 text-dark">Jurnal Tercatat</h5>
                        <p class="mb-0 text-muted small">Ringkasan entri yang sudah masuk</p>
                    </div>
                    <span class="badge bg-success-subtle text-success"><?php echo e($completedJournals->count()); ?></span>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($completedJournals->isEmpty()): ?>
                    <div class="text-center py-4 text-muted">Belum ada jurnal tercatat.</div>
                <?php else: ?>
                    <div class="d-grid gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $completedJournals->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="border rounded-3 p-3">
                                <div class="d-flex justify-content-between gap-2">
                                    <div class="fw-semibold"><?php echo e($item['teacher']); ?></div>
                                    <div class="text-muted small"><?php echo e(\Carbon\Carbon::parse($item['date'])->format('d M')); ?></div>
                                </div>
                                <div class="text-muted small mt-1">
                                    <?php echo e($item['class_name']); ?> | <?php echo e($item['subject']); ?> | <?php echo e($item['time']); ?>

                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/monitor-jurnal-mengajar.blade.php ENDPATH**/ ?>