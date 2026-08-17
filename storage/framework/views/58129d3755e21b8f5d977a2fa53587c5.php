<?php $__env->startSection('title', 'Monitoring Jurnal Mengajar'); ?>
<?php $__env->startSection('subtitle', 'Rekap kegiatan mengajar guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 720px; margin: auto;">
    <style>
        body {
            background: #f7faf8;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .hero-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(0, 75, 76, 0.18);
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.14);
            border-radius: 14px;
            padding: 8px 10px;
            min-width: 0;
        }

        .stat-box small {
            display: block;
            opacity: 0.8;
            font-size: 10px;
            line-height: 1.2;
        }

        .stat-box strong {
            font-size: 15px;
            line-height: 1.1;
        }

        .journal-card {
            border: 1px solid #e7eeea;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(15, 56, 57, 0.06);
        }

        .journal-meta {
            font-size: 11px;
            color: #6c757d;
        }

        .journal-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            background: #eef7f4;
            color: #0e8549;
        }

        .journal-detail {
            display: grid;
            gap: 6px;
            margin-top: 10px;
        }

        .journal-detail-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
        }

        .journal-detail-row span:first-child {
            color: #6c757d;
            min-width: 88px;
        }

        .empty-state {
            border-radius: 18px;
            border: 1px dashed #cfe1d9;
            background: #fff;
        }

    </style>

    <div class="d-flex align-items-center mb-3">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <div>
            <div class="fw-bold" style="color: #004b4c; font-size: 16px;">Monitoring Jurnal Mengajar</div>
            <small class="text-muted">Kepala sekolah dapat melihat detail kegiatan guru per bulan</small>
        </div>
    </div>

    <div class="card border-0 hero-card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <div class="fw-semibold" style="font-size: 15px;"><?php echo e(Auth::user()->madrasah->name ?? '-'); ?></div>
                    <div style="font-size: 11px; opacity: 0.85;">Periode <?php echo e($summary['bulan']); ?></div>
                </div>
                <div class="text-end">
                    <div style="font-size: 11px; opacity: 0.75;">Total jurnal</div>
                    <div class="fw-bold" style="font-size: 22px; line-height: 1;"><?php echo e($summary['total_jurnal']); ?></div>
                </div>
            </div>

            <form method="GET" class="mt-3">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label mb-1" style="font-size: 11px; opacity: 0.85;">Bulan</label>
                        <input type="month" name="month" class="form-control form-control-sm" value="<?php echo e($selectedMonth); ?>">
                    </div>
                    <div class="col-6">
                        <label class="form-label mb-1" style="font-size: 11px; opacity: 0.85;">Kelas</label>
                        <select name="class_name" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($className); ?>" <?php if($selectedClass === $className): echo 'selected'; endif; ?>><?php echo e($className); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-light btn-sm w-100 mt-2" style="font-weight: 600;">Terapkan</button>
            </form>

            <div class="stat-grid mt-3">
                <div class="stat-box">
                    <small>Guru aktif</small>
                    <strong><?php echo e($summary['total_guru']); ?></strong>
                </div>
                <div class="stat-box">
                    <small>Jurnal tampil</small>
                    <strong><?php echo e($summary['total_jurnal']); ?></strong>
                </div>
                <div class="stat-box">
                    <small>Belum jurnal</small>
                    <strong><?php echo e($summary['total_belum_jurnal']); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 mb-3">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="fw-semibold">Jurnal sudah tercatat</div>
                <span class="text-muted small"><?php echo e($completedJournals->count()); ?></span>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($records->isEmpty()): ?>
        <div class="card border-0 empty-state">
            <div class="card-body text-center py-5">
                <i class="bx bx-book-open fs-1 text-muted"></i>
                <h6 class="mt-3 mb-1">Belum ada jurnal mengajar</h6>
                <p class="text-muted mb-0">Belum ada data presensi mengajar pada bulan yang dipilih.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="d-grid gap-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <?php
                    $schedule = $record->teachingSchedule;
                ?>
                <div class="card border-0 journal-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold" style="font-size: 13px;"><?php echo e($schedule->teacher->name ?? '-'); ?></div>
                                <div class="journal-meta">
                                    <?php echo e(\Carbon\Carbon::parse($record->tanggal)->format('d M')); ?>

                                    <?php echo e($record->waktu ? '• ' . \Carbon\Carbon::parse($record->waktu)->format('H:i') : ''); ?>

                                </div>
                            </div>
                            <span class="journal-pill">
                                <?php echo e(strtoupper($record->status ?? 'hadir')); ?>

                            </span>
                        </div>

                        <div class="journal-detail mt-2">
                            <div class="journal-detail-row">
                                <span><?php echo e($schedule->classNameLabel() ?: ($schedule->class_name ?? '-')); ?></span>
                                <strong class="text-dark text-end"><?php echo e($schedule->subject ?? '-'); ?></strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Jam</span>
                                <strong class="text-dark text-end"><?php echo e(trim(($schedule->start_time ?? '-') . ' - ' . ($schedule->end_time ?? '-'))); ?></strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Materi</span>
                                <strong class="text-dark text-end text-truncate" style="max-width: 62%;"><?php echo e($record->materi ?: '-'); ?></strong>
                            </div>
                            <div class="journal-detail-row">
                                <span>Siswa</span>
                                <strong class="text-dark text-end">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!is_null($record->present_students) && !is_null($record->class_total_students)): ?>
                                        <?php echo e($record->present_students); ?>/<?php echo e($record->class_total_students); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>

        <div class="mt-3">
            <?php echo e($records->links('vendor.pagination.bootstrap-5')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($missingJournals->isNotEmpty()): ?>
        <div class="card border-0 mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-semibold">Belum jurnal</div>
                    <span class="text-muted small"><?php echo e($missingJournals->count()); ?></span>
                </div>
                <div class="d-grid gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $missingJournals->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="border rounded-3 px-2 py-2">
                            <div class="d-flex justify-content-between gap-2">
                                <div class="fw-semibold" style="font-size: 12px;"><?php echo e($item['teacher']); ?></div>
                                <div class="text-muted small"><?php echo e(\Carbon\Carbon::parse($item['date'])->format('d M')); ?></div>
                            </div>
                            <div class="text-muted small">
                                <?php echo e($item['class_name']); ?> · <?php echo e($item['subject']); ?> · <?php echo e($item['time']); ?>

                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/monitor-jurnal-mengajar.blade.php ENDPATH**/ ?>