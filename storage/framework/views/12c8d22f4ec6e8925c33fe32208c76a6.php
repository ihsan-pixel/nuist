<?php $__env->startSection('title', 'Monitoring Jurnal Mengajar'); ?>
<?php $__env->startSection('subtitle', 'Rekap mingguan dan navigasi hari'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $selectedDayLabel = \Carbon\Carbon::parse($selectedRecap['date'] ?? $selectedDate->toDateString())
        ->locale('id')
        ->isoFormat('dddd, D MMMM YYYY');
    $weekLabel = $summary['week_label'] ?? '-';
    $selectedTotal = (int) ($selectedRecap['total'] ?? 0);
    $selectedHadir = (int) ($selectedRecap['hadir'] ?? 0);
    $selectedIzin = (int) ($selectedRecap['izin'] ?? 0);
    $selectedLibur = (int) ($selectedRecap['libur'] ?? 0);
    $selectedBelum = (int) ($selectedRecap['belum'] ?? 0);
    $selectedGroups = collect($selectedRecap['items'] ?? []);
    $selectedIzinEvent = $selectedGroups
        ->flatMap(fn ($group) => collect($group['items'] ?? []))
        ->first(fn ($item) => ($item['status'] ?? null) === 'izin' && !empty($item['event']));
    $weeklyCompleted = collect($dailyRecaps ?? [])->filter(fn ($day) => (int) ($day['belum'] ?? 0) === 0 && (int) ($day['total'] ?? 0) > 0)->count();
    $weeklyActive = collect($dailyRecaps ?? [])->filter(fn ($day) => (int) ($day['total'] ?? 0) > 0)->count();
    $weeklyProgress = $weeklyActive > 0 ? (int) round(($weeklyCompleted / $weeklyActive) * 100) : 0;
?>

<div class="container-fluid px-3 py-3 monitor-jurnal-mobile">
    <style>
        .monitor-jurnal-mobile {
            --nj-green: #0e8549;
            --nj-green-dark: #004b4c;
            --nj-green-soft: #e8f5ee;
            --nj-border: #e6ece8;
            --nj-text: #1f2937;
            --nj-muted: #6b7280;
            background: #f6f8f7;
        }

        .monitor-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .monitor-header .title {
            color: var(--nj-green-dark);
            font-size: 16px;
            line-height: 1.2;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .monitor-header .subtitle {
            color: var(--nj-muted);
            font-size: 12px;
            line-height: 1.35;
        }

        .hero-card {
            border: 1px solid rgba(14, 133, 73, 0.10);
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(0,75,76,.98), rgba(14,133,73,.98));
            color: #fff;
            box-shadow: 0 8px 20px rgba(0, 75, 76, 0.10);
        }

        .hero-muted {
            opacity: .84;
            font-size: 12px;
            line-height: 1.3;
        }

        .hero-compact {
            font-size: 13px;
            line-height: 1.35;
        }

        .active-day-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,.14);
            color: #fff;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,.18);
        }

        .filter-card,
        .section-card,
        .summary-card,
        .session-card,
        .week-card {
            border-radius: 14px;
            background: #fff;
            border: 1px solid var(--nj-border);
            box-shadow: 0 1px 2px rgba(16,24,40,.03);
        }

        .filter-card .form-control,
        .filter-card .form-select {
            min-height: 44px;
            border-radius: 12px;
            font-size: 13px;
        }

        .btn-nj {
            background: var(--nj-green);
            border-color: var(--nj-green);
            color: #fff;
            min-height: 44px;
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-nj:hover,
        .btn-nj:focus {
            background: #0b7440;
            border-color: #0b7440;
            color: #fff;
        }

        .day-nav {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .day-nav::-webkit-scrollbar {
            display: none;
        }

        .day-pill {
            flex: 0 0 auto;
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid var(--nj-border);
            background: #fff;
            color: var(--nj-text);
        }

        .day-pill.active {
            background: var(--nj-green);
            color: #fff;
            border-color: var(--nj-green);
        }

        .summary-card {
            padding: 12px;
            min-height: 80px;
        }

        .summary-label {
            font-size: 11px;
            color: var(--nj-muted);
            line-height: 1.2;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 700;
            color: var(--nj-text);
            line-height: 1.1;
            margin-top: 4px;
        }

        .summary-note {
            margin-top: 4px;
            font-size: 11px;
            color: var(--nj-green);
            font-weight: 600;
        }

        .section-title {
            font-size: 15px;
            line-height: 1.2;
            color: var(--nj-text);
            font-weight: 700;
            margin: 0;
        }

        .section-subtitle {
            font-size: 12px;
            color: var(--nj-muted);
            margin-top: 2px;
        }

        .session-card {
            padding: 10px;
        }

        .session-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
        }

        .session-main {
            flex: 1;
            min-width: 0;
        }

        .session-subject {
            font-size: 13px;
            font-weight: 700;
            color: var(--nj-text);
            line-height: 1.25;
            margin-bottom: 2px;
        }

        .session-time {
            font-size: 12px;
            color: var(--nj-muted);
        }

        .session-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 10px;
            margin-top: 6px;
        }

        .session-meta div {
            font-size: 12px;
            color: var(--nj-text);
            line-height: 1.35;
        }

        .session-meta .muted {
            color: var(--nj-muted);
        }

        .session-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 0 10px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .02em;
            border: 1px solid transparent;
            flex-shrink: 0;
        }

        .session-status.success {
            color: #127a43;
            background: #e8f5ee;
            border-color: #ccead8;
        }

        .session-status.info {
            color: #0f6fa8;
            background: #e8f4fb;
            border-color: #d0e8f7;
        }

        .session-status.warning {
            color: #9a6700;
            background: #fff4db;
            border-color: #f5dfac;
        }

        .session-status.neutral {
            color: #4b5563;
            background: #f3f4f6;
            border-color: #e5e7eb;
        }

        .journal-state {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 12px;
            color: var(--nj-muted);
        }

        .journal-state strong {
            color: var(--nj-text);
        }

        .weekly-toggle {
            width: 100%;
            border: 1px solid var(--nj-border);
            background: #fff;
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--nj-text);
            font-size: 13px;
            font-weight: 600;
        }

        .weekly-list {
            display: grid;
            gap: 8px;
            margin-top: 10px;
        }

        .weekly-item {
            border: 1px solid var(--nj-border);
            background: #fff;
            border-radius: 12px;
            padding: 9px 11px;
        }

        .weekly-item.active {
            border-color: rgba(14, 133, 73, 0.30);
            background: var(--nj-green-soft);
        }

        .weekly-item .line1 {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
        }

        .weekly-item .line2 {
            margin-top: 4px;
            font-size: 12px;
            color: var(--nj-muted);
        }

        .compact-gap {
            gap: 12px;
        }

        .session-list {
            display: grid;
            gap: 10px;
        }

        @media (min-width: 768px) {
            .monitor-jurnal-mobile {
                max-width: 720px;
                margin: 0 auto;
            }
        }
    </style>

    <div class="monitor-header">
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('mobile.dashboard')); ?>" class="btn btn-link p-0 text-decoration-none d-inline-flex align-items-center justify-content-center" style="color: var(--nj-green-dark); width: 36px; height: 36px;">
                <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
            </a>
            <div>
                <div class="title">Monitoring Jurnal Mengajar</div>
                <div class="subtitle">Rekap mingguan dan navigasi hari</div>
            </div>
        </div>
    </div>

    <div class="hero-card mb-3">
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div class="flex-grow-1" style="min-width: 0;">
                    <div class="fw-semibold hero-compact text-truncate"><?php echo e(Auth::user()->madrasah->name ?? '-'); ?></div>
                    <div class="hero-muted"><?php echo e($weekLabel); ?></div>
                </div>
                <div class="text-end flex-shrink-0">
                    <div class="active-day-badge mb-1">Hari Aktif</div>
                    <div class="fw-semibold hero-compact"><?php echo e($selectedDayLabel); ?></div>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedIzinEvent): ?>
                <div class="mt-2 rounded-3 px-3 py-2" style="background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.16);">
                    <div style="font-size: 11px; opacity: .86;">Izin kegiatan terdeteksi</div>
                    <div class="fw-semibold" style="font-size: 12px; line-height: 1.35;">
                        <?php echo e($selectedIzinEvent['event']?->name ?? 'Kegiatan sekolah'); ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="filter-card mb-3">
        <div class="p-3">
            <form method="GET">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label small text-muted mb-1">Tanggal</label>
                        <input type="date" name="date" class="form-control" value="<?php echo e($selectedDate->format('Y-m-d')); ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label small text-muted mb-1">Kelas</label>
                        <select name="class_name" class="form-select">
                            <option value="">Semua Kelas</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($className); ?>" <?php if($selectedClass === $className): echo 'selected'; endif; ?>><?php echo e($className); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-nj w-100">Terapkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="day-nav mb-3 pb-1">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weekDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <?php
                $shortDay = mb_substr(ucfirst($day['key']), 0, 3);
                $isActiveDay = ($selectedDay ?? '') === $day['key'];
            ?>
            <a href="<?php echo e(request()->fullUrlWithQuery(['date' => $day['date'], 'day' => $day['key']])); ?>" class="text-decoration-none">
                <span class="day-pill <?php echo e($isActiveDay ? 'active' : ''); ?>"><?php echo e($shortDay); ?></span>
            </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="summary-card">
                <div class="summary-label">Sudah Jurnal</div>
                <div class="summary-value"><?php echo e($selectedHadir); ?></div>
            </div>
        </div>
        <div class="col-4">
            <div class="summary-card">
                <div class="summary-label">Izin</div>
                <div class="summary-value"><?php echo e($selectedIzin); ?></div>
            </div>
        </div>
        <div class="col-4">
            <div class="summary-card">
                <div class="summary-label">Libur</div>
                <div class="summary-value"><?php echo e($selectedLibur); ?></div>
            </div>
        </div>
        <div class="col-4">
            <div class="summary-card">
                <div class="summary-label">Belum Jurnal</div>
                <div class="summary-value"><?php echo e($selectedBelum); ?></div>
            </div>
        </div>
    </div>

    <div class="section-card mb-3">
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center gap-2">
                <div>
                    <div class="section-title">Jurnal Harian</div>
                    <div class="section-subtitle"><?php echo e($selectedDayLabel); ?> • <?php echo e($selectedTotal); ?> sesi</div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedTotal > 0): ?>
                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle"><?php echo e($weeklyProgress); ?>% selesai</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedTotal === 0): ?>
                <div class="mt-3 border rounded-3 bg-light p-3">
                    <div class="fw-semibold text-dark">Tidak ada jadwal mengajar</div>
                    <div class="text-muted small mt-1">Tidak terdapat sesi mengajar pada <?php echo e($selectedDayLabel); ?>.</div>
                </div>
            <?php else: ?>
                <div class="d-grid gap-2 mt-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $selectedGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="border rounded-3" style="border-color: var(--nj-border); background: #fff;">
                            <div class="d-flex justify-content-between align-items-center px-3 pt-2 pb-2" style="border-bottom: 1px solid var(--nj-border);">
                                <div class="fw-semibold text-dark" style="font-size: 12px; line-height: 1.2;"><?php echo e($group['class_name'] ?? '-'); ?></div>
                                <span class="badge rounded-pill bg-light text-dark border" style="font-size: 10px; padding: 5px 8px;"><?php echo e(count($group['items'] ?? [])); ?> sesi</span>
                            </div>

                            <div class="session-list p-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <?php
                                        $schedule = $item['schedule'] ?? null;
                                        $status = $item['status'] ?? 'belum';
                                        $statusClass = $status === 'hadir' ? 'success' : ($status === 'izin' ? 'info' : ($status === 'libur' ? 'secondary' : 'warning'));
                                        $journalFilled = in_array($status, ['hadir', 'izin'], true);
                                        $timeLabel = \Illuminate\Support\Str::of((string) ($item['time'] ?? ''))->replace(':00 - ', ' - ')->replace(':00', '')->toString();
                                    ?>
                                    <div class="border rounded-3 px-2 py-2" style="background:#fbfcfb; border-color: var(--nj-border) !important;">
                                        <div class="d-flex justify-content-between gap-2">
                                            <div class="flex-grow-1" style="min-width: 0;">
                                                <div class="d-flex flex-wrap align-items-center gap-2">
                                                    <div class="session-subject mb-0"><?php echo e($item['subject'] ?? '-'); ?></div>
                                                    <span class="text-muted" style="font-size: 11px;"><?php echo e($timeLabel ?: '-'); ?></span>
                                                </div>
                                                <div class="session-meta">
                                                    <div><?php echo e($schedule?->teacher?->name ?? ($item['teacher'] ?? '-')); ?></div>
                                                    <div class="muted"><?php echo e($item['class_name'] ?? '-'); ?></div>
                                                </div>
                                                <div class="journal-state">
                                                    <strong>
                                                        <?php echo e($status === 'izin'
                                                            ? ($item['izin']?->type === \App\Services\ExternalTeachingPermissionService::TYPE
                                                                ? 'Mengajar di sekolah lain'
                                                                : ($item['izin']?->alasan ?: 'Izin aktif terdeteksi'))
                                                            : ($status === 'libur'
                                                                ? 'Tanggal merah, tidak perlu jurnal'
                                                                : ($journalFilled ? 'Jurnal sudah diisi' : 'Belum mengisi jurnal'))); ?>

                                                    </strong>
                                                </div>
                                            </div>

                                            <div class="text-end flex-shrink-0">
                                                <div class="session-status <?php echo e($statusClass); ?>">
                                                    <?php echo e($status === 'izin' ? 'IZIN' : ($status === 'libur' ? 'LIBUR' : strtoupper($status))); ?>

                                                </div>
                                            </div>
                                        </div>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item['attendance']) && !empty($item['attendance']->materi)): ?>
                                            <div class="mt-2 text-dark" style="font-size: 11px; line-height: 1.35;">
                                                <span class="text-muted">Materi:</span> <?php echo e(\Illuminate\Support\Str::limit((string) $item['attendance']->materi, 90)); ?>

                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($status === 'izin' && !empty($item['event'])): ?>
                                            <div class="mt-1 text-success" style="font-size: 11px; line-height: 1.35;">
                                                <?php echo e($item['event']?->name ?? 'Kegiatan sekolah'); ?>

                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($status === 'libur'): ?>
                                            <div class="mt-1 text-muted" style="font-size: 11px; line-height: 1.35;">
                                                <?php echo e($item['holiday']?->name ?? 'Tanggal merah'); ?>

                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="section-card">
        <div class="p-3">
            <button class="weekly-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#weeklySummary" aria-expanded="false" aria-controls="weeklySummary">
                <span>Ringkasan Minggu Ini</span>
                <span>⌄</span>
            </button>

            <div class="collapse mt-2" id="weeklySummary">
                <div class="weekly-list">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dailyRecaps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $daily): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $dayDate = \Carbon\Carbon::parse($daily['date']);
                            $isSelected = ($selectedRecap['date'] ?? '') === $daily['date'];
                            $dayLabel = $dayDate->locale('id')->isoFormat('ddd, D MMM');
                        ?>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['date' => $daily['date'], 'day' => $dayDate->locale('id')->dayName])); ?>" class="text-decoration-none">
                            <div class="weekly-item <?php echo e($isSelected ? 'active' : ''); ?>">
                                <div class="line1">
                                    <div class="fw-semibold text-dark"><?php echo e($dayLabel); ?></div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSelected): ?>
                                        <span class="badge rounded-pill bg-success text-white">Dipilih</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="line2">
                                    Hadir <?php echo e($daily['hadir']); ?> • Izin <?php echo e($daily['izin']); ?> • Belum <?php echo e($daily['belum']); ?>

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

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/mobile/monitor-jurnal-mengajar.blade.php ENDPATH**/ ?>