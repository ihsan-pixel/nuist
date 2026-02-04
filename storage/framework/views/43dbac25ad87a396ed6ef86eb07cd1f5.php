<?php $__env->startSection('title', 'Kelengkapan Data'); ?>

<?php $__env->startSection('content'); ?>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        background-color: #f5f6fa;
        color: #333;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .progress {
        height: 6px;
        border-radius: 3px;
        background: #e9ecef;
    }

    .progress-bar {
        border-radius: 3px;
    }

    .badge-custom {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }

    .stat-card {
        border-radius: 12px;
        padding: 16px 12px;
        color: #fff;
        text-align: center;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
    }

    .stat-label {
        font-size: 11px;
        opacity: 0.9;
    }

    .school-name {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
    }

    .school-location {
        font-size: 12px;
        color: #6c757d;
    }

    .percentage-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        color: #fff;
    }

    .section-label {
        font-size: 11px;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 4px;
    }
</style>

<div class="container py-3" style="max-width: 540px; margin: auto;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <button onclick="window.history.back()" class="btn btn-link p-0 me-3" style="color: #004b4c;">
                <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
            </button>
            <h5 class="mb-0" style="font-size: 16px; font-weight: 600; color: #004b4c;">
                <i class="bx bx-data me-2"></i>Kelengkapan Data
            </h5>
        </div>
        <span class="badge bg-secondary badge-custom"><?php echo e($totalSekolah); ?> Sekolah</span>
    </div>

    <!-- Statistics Summary -->
    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #004b4c, #006666);">
                <div class="stat-value"><?php echo e(number_format($avgMadrasahCompleteness, 0)); ?>%</div>
                <div class="stat-label">Data</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #0e8549, #28a745);">
                <div class="stat-value"><?php echo e(number_format($avgPresensiKehadiran / max(1, $totalSekolah), 0)); ?>%</div>
                <div class="stat-label">Hadir</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #6f42c1, #9b59b6);">
                <div class="stat-value"><?php echo e(number_format($avgPresensiMengajar / max(1, $totalSekolah), 0)); ?>%</div>
                <div class="stat-label">Mengajar</div>
            </div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #fd7e14, #ffc107);">
                <div class="stat-value"><?php echo e($laporanCompletenessPercentage); ?>%</div>
                <div class="stat-label">Laporan</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8, #20c997);">
                <div class="stat-value"><?php echo e(number_format($avgPPDB / max(1, $totalSekolah), 0)); ?>%</div>
                <div class="stat-label">PPDB</div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="stat-value"><?php echo e($sekolahLengkap); ?></div>
                <div class="stat-label">Lengkap</div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-3">
        <div class="card-body py-2 px-3">
            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari sekolah..." value="<?php echo e($search); ?>" style="border-radius: 8px;">
                <select name="kabupaten" class="form-select form-select-sm" style="width: 140px; border-radius: 8px;">
                    <option value="">Semua Kab.</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $kabupatenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($kab); ?>" <?php echo e($kabupaten == $kab ? 'selected' : ''); ?>><?php echo e($kab); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 8px;">
                    <i class="bx bx-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Schools List -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card mb-3">
            <div class="card-body p-3">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="grow">
                        <div class="school-name"><?php echo e($madrasah->name); ?></div>
                        <div class="school-location">
                            <i class="bx bx-map me-1"></i><?php echo e($madrasah->kabupaten ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <!-- Percentages Grid -->
                <div class="row g-2 mb-2">
                    <div class="col-3">
                        <div class="text-center">
                            <div class="percentage-circle mx-auto mb-1" style="background: <?php echo e($madrasah->completeness_percentage >= 80 ? '#28a745' : ($madrasah->completeness_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;">
                                <?php echo e($madrasah->completeness_percentage); ?>%
                            </div>
                            <div class="section-label">Data</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center">
                            <div class="percentage-circle mx-auto mb-1" style="background: <?php echo e($madrasah->presensi_kehadiran_percentage >= 80 ? '#28a745' : ($madrasah->presensi_kehadiran_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;">
                                <?php echo e($madrasah->presensi_kehadiran_percentage); ?>%
                            </div>
                            <div class="section-label">Hadir</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center">
                            <div class="percentage-circle mx-auto mb-1" style="background: <?php echo e($madrasah->presensi_mengajar_percentage >= 80 ? '#28a745' : ($madrasah->presensi_mengajar_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;">
                                <?php echo e($madrasah->presensi_mengajar_percentage); ?>%
                            </div>
                            <div class="section-label">Mengajar</div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="text-center">
                            <div class="percentage-circle mx-auto mb-1" style="background: <?php echo e($madrasah->laporan_akhir_tahun_percentage >= 80 ? '#28a745' : ($madrasah->laporan_akhir_tahun_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;">
                                <?php echo e($madrasah->laporan_akhir_tahun_percentage); ?>%
                            </div>
                            <div class="section-label">Laporan</div>
                        </div>
                    </div>
                </div>

                <!-- Progress Bars -->
                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-label">Kelengkapan Data Madrasah</span>
                        <span class="badge bg-secondary badge-custom" style="font-size: 10px;"><?php echo e($madrasah->completeness_percentage); ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo e($madrasah->completeness_percentage); ?>%; background: <?php echo e($madrasah->completeness_percentage >= 80 ? '#28a745' : ($madrasah->completeness_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;"></div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-label">Presensi Kehadiran</span>
                        <span class="badge bg-secondary badge-custom" style="font-size: 10px;"><?php echo e($madrasah->presensi_kehadiran_percentage); ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo e($madrasah->presensi_kehadiran_percentage); ?>%; background: <?php echo e($madrasah->presensi_kehadiran_percentage >= 80 ? '#28a745' : ($madrasah->presensi_kehadiran_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;"></div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-label">Presensi Mengajar</span>
                        <span class="badge bg-secondary badge-custom" style="font-size: 10px;"><?php echo e($madrasah->presensi_mengajar_percentage); ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo e($madrasah->presensi_mengajar_percentage); ?>%; background: <?php echo e($madrasah->presensi_mengajar_percentage >= 80 ? '#28a745' : ($madrasah->presensi_mengajar_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;"></div>
                    </div>
                </div>

                <div class="mb-2">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-label">Laporan Akhir Tahun <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasah->laporan_akhir_tahun_details['madrasah_id']): ?> (Sudah Diisi) <?php else: ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
                        <span class="badge bg-secondary badge-custom" style="font-size: 10px;"><?php echo e($madrasah->laporan_akhir_tahun_percentage); ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo e($madrasah->laporan_akhir_tahun_percentage); ?>%; background: <?php echo e($madrasah->laporan_akhir_tahun_percentage >= 80 ? '#28a745' : ($madrasah->laporan_akhir_tahun_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="section-label">Data PPDB (<?php echo e(now()->year); ?>)</span>
                        <span class="badge bg-secondary badge-custom" style="font-size: 10px;"><?php echo e($madrasah->ppdb_percentage); ?>%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?php echo e($madrasah->ppdb_percentage); ?>%; background: <?php echo e($madrasah->ppdb_percentage >= 80 ? '#28a745' : ($madrasah->ppdb_percentage >= 50 ? '#ffc107' : '#dc3545')); ?>;"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-data mb-2" style="font-size: 48px; color: #dee2e6;"></i>
                <p class="text-muted mb-0">Tidak ada data sekolah</p>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Pagination -->
    <div class="mt-3">
        <?php echo e($madrasahs->appends(['search' => $search, 'kabupaten' => $kabupaten])->links('vendor.pagination.bootstrap-5')); ?>

    </div>
</div>

<div style="height: 75px;"></div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/pengurus/kelengkapan-data.blade.php ENDPATH**/ ?>