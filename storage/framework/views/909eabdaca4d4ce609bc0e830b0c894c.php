<?php $__env->startSection('title', 'Monitoring Jurnal Mengajar'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $selectedDateLabel = \Carbon\Carbon::parse($selectedRecap['date'] ?? $selectedDate->toDateString())
        ->locale('id')
        ->isoFormat('dddd, D MMMM YYYY');
    $weekStart = \Carbon\Carbon::parse($weekDays->first()['date'] ?? $selectedDate->toDateString())->locale('id');
    $activeDays = collect($dailyRecaps ?? [])->filter(fn ($day) => (int) ($day['total'] ?? 0) > 0)->count();
    $completedDays = collect($dailyRecaps ?? [])->filter(fn ($day) => (int) ($day['belum'] ?? 0) === 0 && (int) ($day['total'] ?? 0) > 0)->count();
    $progressPercent = ($activeDays > 0) ? (int) round(($completedDays / $activeDays) * 100) : 0;
    $selectedGroups = collect($selectedRecap['items'] ?? []);
    $selectedTotal = (int) ($selectedRecap['total'] ?? 0);
    $selectedHadir = (int) ($selectedRecap['hadir'] ?? 0);
    $selectedIzin = (int) ($selectedRecap['izin'] ?? 0);
    $selectedBelum = (int) ($selectedRecap['belum'] ?? 0);
?>

<div class="row g-3">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 14px;">
            <div class="card-body py-3 px-3 px-lg-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                    <div>
                        <div class="fw-semibold text-dark" style="font-size: 18px; line-height: 1.2;">Monitoring Jurnal Mengajar</div>
                        <div class="text-muted small mt-1">
                            <?php echo e(Auth::user()->madrasah->name ?? '-'); ?> • Minggu <?php echo e($summary['week_label'] ?? '-'); ?>

                        </div>
                    </div>

                    <div class="text-end">
                        <span class="badge rounded-pill bg-success-subtle text-success mb-1">Hari Aktif</span>
                        <div class="fw-semibold text-dark" style="font-size: 15px; line-height: 1.2;"><?php echo e($selectedDateLabel); ?></div>
                    </div>
                </div>

                <form method="GET" class="mt-3">
                    <div class="d-flex flex-column flex-lg-row gap-2 align-items-stretch align-items-lg-end">
                        <div class="flex-grow-1">
                            <label class="form-label text-muted mb-1 small">Tanggal</label>
                            <input type="date" name="date" class="form-control" value="<?php echo e($selectedDate->format('Y-m-d')); ?>">
                        </div>
                        <div class="flex-grow-1">
                            <label class="form-label text-muted mb-1 small">Kelas</label>
                            <select name="class_name" class="form-select">
                                <option value="">Semua Kelas</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($className); ?>" <?php if($selectedClass === $className): echo 'selected'; endif; ?>><?php echo e($className); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="d-grid" style="min-width: 120px;">
                            <button type="submit" class="btn btn-success fw-semibold">Terapkan</button>
                        </div>
                    </div>
                </form>

                <div class="d-flex gap-2 mt-3 pb-1 overflow-auto" style="scrollbar-width: thin;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weekDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $isActiveDay = ($selectedDay ?? '') === $day['key'];
                        ?>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['date' => $day['date'], 'day' => $day['key']])); ?>"
                           class="text-decoration-none flex-shrink-0">
                            <span class="badge rounded-pill px-3 py-2 border <?php echo e($isActiveDay ? 'bg-success text-white border-success' : 'bg-white text-dark border-light'); ?>"
                                  style="font-size: 12px; font-weight: 600;">
                                <?php echo e(ucfirst($day['key'])); ?>

                            </span>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
                    <div>
                        <div class="fw-semibold text-dark" style="font-size: 16px;">Jurnal Hari Ini</div>
                        <div class="text-muted small"><?php echo e($selectedDateLabel); ?> • <?php echo e($selectedTotal); ?> sesi</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Progress hari dipilih</div>
                        <div class="fw-semibold text-dark"><?php echo e($selectedTotal > 0 ? (100 - ($selectedBelum > 0 ? (int) round(($selectedBelum / max($selectedTotal, 1)) * 100) : 0)) : 0); ?>%</div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-muted small">Total Sesi</div>
                            <div class="fw-bold fs-4"><?php echo e($selectedTotal); ?></div>
                            <div class="small text-success"><?php echo e($selectedTotal > 0 && $selectedBelum === 0 ? '100% selesai' : 'Siap direkap'); ?></div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-muted small">Sudah Jurnal</div>
                            <div class="fw-bold fs-4"><?php echo e($selectedHadir); ?></div>
                            <div class="small text-muted">Jurnal terisi</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-muted small">Izin</div>
                            <div class="fw-bold fs-4"><?php echo e($selectedIzin); ?></div>
                            <div class="small text-muted">Kegiatan disetujui</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="border rounded-3 p-3 h-100">
                            <div class="text-muted small">Belum Jurnal</div>
                            <div class="fw-bold fs-4"><?php echo e($selectedBelum); ?></div>
                            <div class="small text-muted">Perlu ditindaklanjuti</div>
                        </div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="progress" style="height: 6px; border-radius: 999px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($progressPercent); ?>%;" aria-valuenow="<?php echo e($progressPercent); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="text-muted small mt-1"><?php echo e($progressPercent); ?>% hari dalam minggu ini sudah selesai jurnalnya</div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedGroups->isEmpty()): ?>
                    <div class="border rounded-3 p-4 text-center text-muted bg-light">
                        <div class="fw-semibold text-dark">Tidak ada jadwal mengajar</div>
                        <div class="small mt-1">Tidak terdapat sesi mengajar pada <?php echo e($selectedDateLabel); ?>.</div>
                    </div>
                <?php else: ?>
                    <div class="d-grid gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $selectedGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="border rounded-3 p-3">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                    <div class="fw-semibold text-dark"><?php echo e($group['class_name'] ?? '-'); ?></div>
                                    <span class="badge rounded-pill bg-light text-dark border"><?php echo e(count($group['items'] ?? [])); ?> sesi</span>
                                </div>

                                <div class="d-grid gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php
                                            $schedule = $item['schedule'] ?? null;
                                            $status = $item['status'] ?? 'belum';
                                            $statusClass = $status === 'hadir' ? 'success' : ($status === 'izin' ? 'info' : 'warning');
                                            $journalFilled = ($status === 'hadir' || $status === 'izin');
                                        ?>
                                        <div class="border rounded-3 p-3" style="background: #fbfcfb;">
                                            <div class="d-flex justify-content-between gap-3">
                                                <div class="flex-grow-1" style="min-width: 0;">
                                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                                        <div class="fw-semibold text-dark"><?php echo e($item['subject'] ?? '-'); ?></div>
                                                        <span class="text-muted small"><?php echo e($item['time'] ?? '-'); ?></span>
                                                    </div>
                                                    <div class="text-muted small"><?php echo e($schedule?->teacher?->name ?? ($item['teacher'] ?? '-')); ?> • <?php echo e($item['class_name'] ?? '-'); ?></div>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['attendance']) && !empty($item['attendance']->materi)): ?>
                                                        <div class="mt-2">
                                                            <div class="small text-muted mb-1">Materi</div>
                                                            <div class="text-dark small"><?php echo e(\Illuminate\Support\Str::limit((string) $item['attendance']->materi, 120)); ?></div>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>

                                                <div class="text-end flex-shrink-0">
                                                    <div class="badge rounded-pill bg-<?php echo e($statusClass); ?>-subtle text-<?php echo e($statusClass); ?> border border-<?php echo e($statusClass); ?>-subtle mb-1">
                                                        <?php echo e(strtoupper($status)); ?>

                                                    </div>
                                                    <div class="small text-muted"><?php echo e($journalFilled ? 'Jurnal sudah diisi' : 'Belum mengisi jurnal'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 14px;">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <div class="fw-semibold text-dark" style="font-size: 16px;">Ringkasan Mingguan</div>
                        <div class="text-muted small">Status tiap hari pada minggu ini</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Progress Minggu Ini</div>
                        <div class="fw-semibold text-dark"><?php echo e($completedDays); ?> / <?php echo e(max($activeDays, 0)); ?> hari selesai</div>
                    </div>
                </div>

                <div class="progress mb-3" style="height: 6px; border-radius: 999px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo e($activeDays > 0 ? (int) round(($completedDays / $activeDays) * 100) : 0); ?>%;" aria-valuenow="<?php echo e($activeDays > 0 ? (int) round(($completedDays / $activeDays) * 100) : 0); ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="d-grid gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dailyRecaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $daily): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $isSelected = ($selectedRecap['date'] ?? '') === $daily['date'];
                            $dayDate = \Carbon\Carbon::parse($daily['date']);
                            $dayLabel = $dayDate->locale('id')->isoFormat('dddd, D MMMM');
                            $isComplete = (int) ($daily['belum'] ?? 0) === 0 && (int) ($daily['total'] ?? 0) > 0;
                        ?>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['date' => $daily['date'], 'day' => $dayDate->locale('id')->dayName])); ?>" class="text-decoration-none">
                            <div class="border rounded-3 p-3 <?php echo e($isSelected ? 'border-success bg-success-subtle' : ''); ?>" style="<?php echo e($isSelected ? 'background:#eef8f2;' : ''); ?>">
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <div class="fw-semibold text-dark"><?php echo e($dayLabel); ?></div>
                                        <div class="text-muted small"><?php echo e($daily['total']); ?> sesi</div>
                                    </div>
                                    <div class="text-end">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSelected): ?>
                                            <span class="badge bg-success text-white rounded-pill mb-1">Dipilih</span>
                                        <?php elseif($isComplete): ?>
                                            <span class="badge bg-light text-success border rounded-pill mb-1">Selesai</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="text-muted small">
                                            <span class="text-success">Hadir <?php echo e($daily['hadir']); ?></span>
                                            <span class="mx-1">•</span>
                                            <span class="text-info">Izin <?php echo e($daily['izin']); ?></span>
                                            <span class="mx-1">•</span>
                                            <span class="text-warning">Belum <?php echo e($daily['belum']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/admin/monitor-jurnal-mengajar.blade.php ENDPATH**/ ?>