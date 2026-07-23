<?php $__env->startSection('title', 'Persentase Kehadiran'); ?>
<?php $__env->startSection('subtitle', 'Rekap Presensi Kepala Sekolah'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <a href="<?php echo e(route('mobile.laporan')); ?>" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </a>
    <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
</div>

<div class="container px-0 pb-4" style="max-width: 720px; margin: auto;">
    <style>
        .hero-card,
        .filter-card,
        .overview-card,
        .recap-card,
        .summary-card,
        .detail-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.07);
            margin-bottom: 14px;
            background: #fff;
        }

        .hero-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            padding: 16px;
        }

        .hero-label {
            font-size: 11px;
            opacity: 0.86;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .hero-title {
            font-size: 20px;
            font-weight: 700;
            margin-top: 2px;
        }

        .hero-caption {
            font-size: 12px;
            opacity: 0.84;
            margin-top: 4px;
        }

        .filter-card .card-body,
        .overview-card .card-body,
        .recap-card .card-body,
        .detail-card .card-body {
            padding: 14px;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 10px;
        }

        .overview-stat {
            background: #f5f8f7;
            border: 1px solid #e8efec;
            border-radius: 14px;
            padding: 12px 10px;
            text-align: center;
        }

        .overview-stat .value {
            font-size: 18px;
            font-weight: 700;
            color: #004b4c;
            line-height: 1.1;
        }

        .overview-stat .label {
            font-size: 10px;
            color: #6c757d;
            margin-top: 4px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #004b4c;
        }

        .section-subtitle {
            font-size: 11px;
            color: #6c757d;
        }

        .recap-list {
            display: grid;
            gap: 10px;
        }

        .recap-item {
            border: 1px solid #edf2f1;
            border-radius: 14px;
            padding: 12px;
            background: #fbfcfc;
        }

        .recap-name {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
        }

        .recap-role {
            font-size: 11px;
            color: #6c757d;
            margin-top: 2px;
        }

        .recap-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 10px;
        }

        .recap-metric {
            border-radius: 12px;
            padding: 10px;
            color: #fff;
        }

        .recap-metric.weekly {
            background: linear-gradient(135deg, #1f9d68 0%, #36b37e 100%);
        }

        .recap-metric.monthly {
            background: linear-gradient(135deg, #004b4c 0%, #0b6e70 100%);
        }

        .recap-metric .label {
            font-size: 10px;
            opacity: 0.85;
        }

        .recap-metric .value {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.1;
            margin-top: 4px;
        }

        .recap-metric .meta {
            font-size: 10px;
            opacity: 0.9;
            margin-top: 4px;
        }

        .selected-teacher-box {
            background: linear-gradient(135deg, #fff8e7 0%, #fff 100%);
            border: 1px solid rgba(255, 193, 7, 0.25);
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 14px;
        }

        .summary-card {
            box-shadow: 0 10px 24px rgba(0, 75, 76, 0.10);
        }

        .summary-header {
            padding: 16px;
            color: #fff;
        }

        .summary-header.weekly {
            background: linear-gradient(135deg, #0e8549 0%, #36b37e 100%);
        }

        .summary-header.monthly {
            background: linear-gradient(135deg, #004b4c 0%, #0b6e70 100%);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 14px 16px 16px;
            background: #fff;
        }

        .summary-metric {
            background: #f6f8fb;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
            border: 0;
            width: 100%;
            cursor: pointer;
        }

        .summary-metric .value {
            font-size: 16px;
            font-weight: 700;
            color: #004b4c;
        }

        .summary-metric .label {
            font-size: 10px;
            color: #6c757d;
            margin-top: 2px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #eef2f6;
        }

        .detail-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .detail-item:first-child {
            padding-top: 0;
        }

        .status-chip {
            font-size: 10px;
            font-weight: 700;
            border-radius: 999px;
            padding: 5px 8px;
            white-space: nowrap;
        }

        .status-hadir {
            background: rgba(25, 135, 84, 0.12);
            color: #198754;
        }

        .status-izin {
            background: rgba(13, 110, 253, 0.12);
            color: #0d6efd;
        }

        .status-nonhadir {
            background: rgba(255, 193, 7, 0.18);
            color: #9a6700;
        }

        .status-excluded {
            background: rgba(108, 117, 125, 0.14);
            color: #495057;
        }

        .empty-state {
            text-align: center;
            color: #6c757d;
            padding: 12px 0 4px;
        }

        .modal-detail-item {
            padding: 10px 0;
            border-bottom: 1px solid #eef2f6;
        }

        .modal-detail-item:last-child {
            border-bottom: 0;
        }

        @media (max-width: 576px) {
            .overview-grid,
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .recap-metrics {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="hero-card">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <div class="hero-label">Rekap Sekolah</div>
                <div class="hero-title">Persentase Presensi Kehadiran</div>
                
            </div>
            <i class="bx bx-bar-chart-alt-2" style="font-size: 34px;"></i>
        </div>
    </div>

    <div class="card filter-card">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('mobile.laporan.persentase-kehadiran')); ?>">
                <div class="row g-2">
                    <div class="col-md-4 col-12">
                        <label for="teacher_id" class="form-label mb-1" style="font-size: 11px;">Detail Tenaga Pendidik</label>
                        <select id="teacher_id" name="teacher_id" class="form-select form-select-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teacherOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($teacher->id); ?>" <?php echo e((int) optional($selectedTeacher)->id === (int) $teacher->id ? 'selected' : ''); ?>>
                                    <?php echo e($teacher->name); ?><?php echo e($teacher->ketugasan === 'kepala madrasah/sekolah' ? ' - Kepala Madrasah/Sekolah' : ''); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="week" class="form-label mb-1" style="font-size: 11px;">Minggu</label>
                        <input type="week" id="week" name="week" value="<?php echo e($selectedWeekValue); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3 col-6">
                        <label for="month" class="form-label mb-1" style="font-size: 11px;">Bulan</label>
                        <input type="month" id="month" name="month" value="<?php echo e($selectedMonthValue); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-2 col-12 d-grid">
                        <label class="form-label mb-1 d-none d-md-block" style="font-size: 11px;">Aksi</label>
                        <button type="submit" class="btn btn-sm" style="background: #004b4c; color: #fff;">Tampilkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card filter-card">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('mobile.laporan.persentase-kehadiran.download')); ?>">
                <div class="row g-2 align-items-end">
                    <div class="col-md-8 col-12">
                        <label for="export_month" class="form-label mb-1" style="font-size: 11px;">Bulan Rekap PDF</label>
                        <select id="export_month" name="export_month" class="form-select form-select-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $availableMonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $monthOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($monthOption['value']); ?>" <?php echo e($selectedMonthValue === $monthOption['value'] ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($monthOption['label'])); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <small class="text-muted">Pilihan bulan mengikuti data presensi yang tersedia.</small>
                    </div>
                    <div class="col-md-4 col-12 d-grid">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bx bxs-file-pdf me-1"></i>Export PDF Bulanan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card overview-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <div class="section-title">Ringkasan Sekolah</div>
                    <div class="section-subtitle">Minggu <?php echo e($selectedWeekLabel); ?> dan bulan <?php echo e(ucfirst($selectedMonthLabel)); ?></div>
                </div>
            </div>

            <div class="overview-grid">
                <div class="overview-stat">
                    <div class="value"><?php echo e($schoolOverview['teacher_count']); ?></div>
                    <div class="label">Total Guru</div>
                </div>
                <div class="overview-stat">
                    <div class="value"><?php echo e(number_format($schoolOverview['weekly_average'], 1)); ?>%</div>
                    <div class="label">Rata-rata Mingguan</div>
                </div>
                <div class="overview-stat">
                    <div class="value"><?php echo e(number_format($schoolOverview['monthly_average'], 1)); ?>%</div>
                    <div class="label">Rata-rata Bulanan</div>
                </div>
                <div class="overview-stat">
                    <div class="value"><?php echo e($schoolOverview['monthly_hadir_total']); ?></div>
                    <div class="label">Total Hadir Bulanan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card recap-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="section-title">Rekap Semua Tenaga Pendidik</div>
                    <div class="section-subtitle">Setiap guru menampilkan persentase mingguan dan bulanan secara langsung.</div>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schoolTeacherSummaries->isEmpty()): ?>
                <div class="empty-state">Belum ada data tenaga pendidik pada sekolah ini.</div>
            <?php else: ?>
                <div class="recap-list">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schoolTeacherSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacherSummary): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="recap-item">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <div class="recap-name"><?php echo e($teacherSummary['teacher']->name); ?></div>
                                    <div class="recap-role"><?php echo e($teacherSummary['teacher']->ketugasan ?? 'Tenaga pendidik'); ?></div>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(optional($selectedTeacher)->id === $teacherSummary['teacher']->id): ?>
                                    <span class="badge rounded-pill text-bg-warning">Detail Aktif</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="recap-metrics">
                                <div class="recap-metric weekly">
                                    <div class="label">Persentase Mingguan</div>
                                    <div class="value"><?php echo e(number_format($teacherSummary['weekly']['persentase_kehadiran'], 1)); ?>%</div>
                                    <div class="meta">Hadir <?php echo e($teacherSummary['weekly']['total_hadir']); ?> • Izin <?php echo e($teacherSummary['weekly']['total_izin']); ?> • Belum <?php echo e($teacherSummary['weekly']['total_belum_hadir']); ?></div>
                                </div>
                                <div class="recap-metric monthly">
                                    <div class="label">Persentase Bulanan</div>
                                    <div class="value"><?php echo e(number_format($teacherSummary['monthly']['persentase_kehadiran'], 1)); ?>%</div>
                                    <div class="meta">Hadir <?php echo e($teacherSummary['monthly']['total_hadir']); ?> • Izin <?php echo e($teacherSummary['monthly']['total_izin']); ?> • Belum <?php echo e($teacherSummary['monthly']['total_belum_hadir']); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedTeacher): ?>
        <div class="selected-teacher-box">
            <div class="fw-semibold" style="color: #8a6500;">Detail Tenaga Pendidik Terpilih</div>
            <div style="font-size: 15px; color: #004b4c;"><?php echo e($selectedTeacher->name); ?></div>
            <small class="text-muted"><?php echo e($selectedTeacher->ketugasan ?? 'Tenaga pendidik'); ?></small>
        </div>

        <div class="summary-card">
            <div class="summary-header weekly">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size: 11px; opacity: .9;">Persentase Mingguan</div>
                        <h5 class="mb-1"><?php echo e(number_format($weeklySummary['persentase_kehadiran'], 1)); ?>%</h5>
                        <small><?php echo e($weeklySummary['periode_label']); ?></small>
                    </div>
                    <i class="bx bx-calendar-week" style="font-size: 28px;"></i>
                </div>
            </div>
            <div class="summary-grid">
                <button type="button" class="summary-metric" onclick='showBreakdownModal("Hari Kerja Mingguan", <?php echo json_encode($weeklySummary["breakdown"]["hari_kerja"], 15, 512) ?>)'>
                    <div class="value"><?php echo e($weeklySummary['total_hari_kerja']); ?></div>
                    <div class="label">Hari Kerja</div>
                </button>
                <button type="button" class="summary-metric" onclick='showBreakdownModal("Sudah Hadir Mingguan", <?php echo json_encode($weeklySummary["breakdown"]["hadir"], 15, 512) ?>)'>
                    <div class="value"><?php echo e($weeklySummary['total_hadir']); ?></div>
                    <div class="label">Sudah Hadir</div>
                </button>
                <button type="button" class="summary-metric" onclick='showBreakdownModal("Izin Mingguan", <?php echo json_encode($weeklySummary["breakdown"]["izin"], 15, 512) ?>)'>
                    <div class="value"><?php echo e($weeklySummary['total_izin']); ?></div>
                    <div class="label">Izin</div>
                </button>
                <button type="button" class="summary-metric" onclick='showBreakdownModal("Belum Hadir Mingguan", <?php echo json_encode($weeklySummary["breakdown"]["belum_hadir"], 15, 512) ?>)'>
                    <div class="value"><?php echo e($weeklySummary['total_belum_hadir']); ?></div>
                    <div class="label">Belum Hadir</div>
                </button>
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-header monthly">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div style="font-size: 11px; opacity: .9;">Persentase Bulanan</div>
                        <h5 class="mb-1"><?php echo e(number_format($monthlySummary['persentase_kehadiran'], 1)); ?>%</h5>
                        <small><?php echo e($monthlySummary['periode_label']); ?></small>
                    </div>
                    <i class="bx bx-calendar" style="font-size: 28px;"></i>
                </div>
            </div>
            <div class="summary-grid">
                <button type="button" class="summary-metric" onclick='showBreakdownModal("Hari Kerja Bulanan", <?php echo json_encode($monthlySummary["breakdown"]["hari_kerja"], 15, 512) ?>)'>
                    <div class="value"><?php echo e($monthlySummary['total_hari_kerja']); ?></div>
                    <div class="label">Hari Kerja</div>
                </button>
                <button type="button" class="summary-metric" onclick='showBreakdownModal("Sudah Hadir Bulanan", <?php echo json_encode($monthlySummary["breakdown"]["hadir"], 15, 512) ?>)'>
                    <div class="value"><?php echo e($monthlySummary['total_hadir']); ?></div>
                    <div class="label">Sudah Hadir</div>
                </button>
                <button type="button" class="summary-metric" onclick='showBreakdownModal("Izin Bulanan", <?php echo json_encode($monthlySummary["breakdown"]["izin"], 15, 512) ?>)'>
                    <div class="value"><?php echo e($monthlySummary['total_izin']); ?></div>
                    <div class="label">Izin</div>
                </button>
                <button type="button" class="summary-metric" onclick='showBreakdownModal("Belum Hadir Bulanan", <?php echo json_encode($monthlySummary["breakdown"]["belum_hadir"], 15, 512) ?>)'>
                    <div class="value"><?php echo e($monthlySummary['total_belum_hadir']); ?></div>
                    <div class="label">Belum Hadir</div>
                </button>
            </div>
        </div>

        <div class="card detail-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Detail Mingguan</h6>
                    <small class="text-muted"><?php echo e($weeklySummary['periode_label']); ?></small>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($weeklySummary['details']->isEmpty()): ?>
                    <div class="empty-state">Belum ada hari kerja pada periode ini.</div>
                <?php else: ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weeklySummary['details']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="detail-item">
                            <div>
                                <div class="fw-semibold"><?php echo e($item['tanggal']->format('d M Y')); ?></div>
                                <small class="text-muted"><?php echo e(ucfirst($item['hari'])); ?></small>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['keterangan']): ?>
                                    <div class="text-muted mt-1" style="font-size: 11px;"><?php echo e($item['keterangan']); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <span class="status-chip <?php echo e(($item['is_excluded'] ?? false) ? 'status-excluded' : ($item['is_hadir'] ? 'status-hadir' : ($item['is_izin'] ? 'status-izin' : 'status-nonhadir'))); ?>">
                                <?php echo e($item['status']); ?>

                            </span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="modal fade" id="breakdownModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0" style="border-radius: 18px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="breakdownModalTitle">Detail Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="breakdownModalBody"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function showBreakdownModal(title, items) {
        const modalTitle = document.getElementById('breakdownModalTitle');
        const modalBody = document.getElementById('breakdownModalBody');

        modalTitle.textContent = title;

        if (!items || items.length === 0) {
            modalBody.innerHTML = '<div class="text-center text-muted py-3">Tidak ada data pada kategori ini.</div>';
        } else {
            modalBody.innerHTML = items.map(item => `
                <div class="modal-detail-item">
                    <div class="fw-semibold">${item.tanggal}</div>
                    <div class="text-muted small">${item.hari} • ${item.status}</div>
                    ${item.keterangan ? `<div class="small mt-1">${item.keterangan}</div>` : ''}
                </div>
            `).join('');
        }

        const modal = new bootstrap.Modal(document.getElementById('breakdownModal'));
        modal.show();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/laporan-persentase-kehadiran.blade.php ENDPATH**/ ?>