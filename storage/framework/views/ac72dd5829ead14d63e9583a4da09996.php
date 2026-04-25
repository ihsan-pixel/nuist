<?php $__env->startSection('title', 'Persentase Kehadiran'); ?>
<?php $__env->startSection('subtitle', 'Ringkasan Presensi Mingguan dan Bulanan'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <a href="<?php echo e(route('mobile.laporan')); ?>" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </a>
    <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
</div>

<div class="container px-0" style="max-width: 420px; margin: auto;">
    <style>
        .summary-card {
            border: 0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0, 75, 76, 0.10);
            margin-bottom: 14px;
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

        .selected-teacher-box {
            background: linear-gradient(135deg, #fff8e7 0%, #fff 100%);
            border: 1px solid rgba(255, 193, 7, 0.25);
            border-radius: 14px;
            padding: 12px;
            margin-bottom: 14px;
        }

        .filter-card,
        .detail-card {
            border: 0;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
            margin-bottom: 14px;
        }

        .filter-card .card-body,
        .detail-card .card-body {
            padding: 14px;
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
    </style>

    <div class="card filter-card">
        <div class="card-body">
            <form method="GET">
                <div class="row g-2">
                    <div class="col-12">
                        <label for="teacher_id" class="form-label mb-1" style="font-size: 11px;">Nama Tenaga Pendidik</label>
                        <select id="teacher_id" name="teacher_id" class="form-select form-select-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teacherOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($teacher->id); ?>" <?php echo e((int) $selectedTeacher->id === (int) $teacher->id ? 'selected' : ''); ?>>
                                    <?php echo e($teacher->name); ?><?php echo e($teacher->ketugasan === 'kepala madrasah/sekolah' ? ' - Kepala Madrasah/Sekolah' : ''); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="week" class="form-label mb-1" style="font-size: 11px;">Minggu</label>
                        <input type="week" id="week" name="week" value="<?php echo e($selectedWeekValue); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-6">
                        <label for="month" class="form-label mb-1" style="font-size: 11px;">Bulan</label>
                        <input type="month" id="month" name="month" value="<?php echo e($selectedMonthValue); ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm w-100" style="background: #004b4c; color: #fff;">Tampilkan Laporan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="selected-teacher-box">
        <div class="fw-semibold" style="color: #8a6500;">Tenaga Pendidik Terpilih</div>
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
            <button type="button" class="summary-metric"
                onclick='showBreakdownModal("Hari Kerja Mingguan", <?php echo json_encode($weeklySummary["breakdown"]["hari_kerja"], 15, 512) ?>)'>
                <div class="value"><?php echo e($weeklySummary['total_hari_kerja']); ?></div>
                <div class="label">Hari Kerja</div>
            </button>
            <button type="button" class="summary-metric"
                onclick='showBreakdownModal("Hadir + Izin Mingguan", <?php echo json_encode($weeklySummary["breakdown"]["hadir_efektif"], 15, 512) ?>)'>
                <div class="value"><?php echo e($weeklySummary['total_hadir_efektif']); ?></div>
                <div class="label">Hadir + Izin</div>
            </button>
            <button type="button" class="summary-metric"
                onclick='showBreakdownModal("Izin Mingguan", <?php echo json_encode($weeklySummary["breakdown"]["izin"], 15, 512) ?>)'>
                <div class="value"><?php echo e($weeklySummary['total_izin']); ?></div>
                <div class="label">Izin</div>
            </button>
            <button type="button" class="summary-metric"
                onclick='showBreakdownModal("Belum Hadir Mingguan", <?php echo json_encode($weeklySummary["breakdown"]["belum_hadir"], 15, 512) ?>)'>
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
            <button type="button" class="summary-metric"
                onclick='showBreakdownModal("Hari Kerja Bulanan", <?php echo json_encode($monthlySummary["breakdown"]["hari_kerja"], 15, 512) ?>)'>
                <div class="value"><?php echo e($monthlySummary['total_hari_kerja']); ?></div>
                <div class="label">Hari Kerja</div>
            </button>
            <button type="button" class="summary-metric"
                onclick='showBreakdownModal("Hadir + Izin Bulanan", <?php echo json_encode($monthlySummary["breakdown"]["hadir_efektif"], 15, 512) ?>)'>
                <div class="value"><?php echo e($monthlySummary['total_hadir_efektif']); ?></div>
                <div class="label">Hadir + Izin</div>
            </button>
            <button type="button" class="summary-metric"
                onclick='showBreakdownModal("Izin Bulanan", <?php echo json_encode($monthlySummary["breakdown"]["izin"], 15, 512) ?>)'>
                <div class="value"><?php echo e($monthlySummary['total_izin']); ?></div>
                <div class="label">Izin</div>
            </button>
            <button type="button" class="summary-metric"
                onclick='showBreakdownModal("Belum Hadir Bulanan", <?php echo json_encode($monthlySummary["breakdown"]["belum_hadir"], 15, 512) ?>)'>
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
                <div class="empty-state">
                    Belum ada hari kerja pada periode ini.
                </div>
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
                        <span class="status-chip <?php echo e($item['is_hadir'] ? 'status-hadir' : ($item['is_izin'] ? 'status-izin' : 'status-nonhadir')); ?>">
                            <?php echo e($item['status']); ?>

                        </span>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

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