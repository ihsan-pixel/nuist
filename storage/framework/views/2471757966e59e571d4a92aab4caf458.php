<?php $__env->startSection('title'); ?>Laporan SPP Siswa <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
.report-shell {
    display: grid;
    gap: 1.5rem;
}

.report-hero {
    background:
        radial-gradient(circle at top right, rgba(245, 166, 35, 0.18), transparent 30%),
        linear-gradient(135deg, #0e3940 0%, #156257 52%, #1d8970 100%);
    border: 0;
    border-radius: 24px;
    box-shadow: 0 18px 44px rgba(12, 40, 56, 0.14);
    color: #fff;
}

.report-card {
    background: #fff;
    border: 1px solid #d9e5e8;
    border-radius: 20px;
    box-shadow: 0 16px 38px rgba(12, 40, 56, 0.07);
}

.report-stat {
    height: 100%;
    padding: 1.25rem;
}

.report-stat-label {
    color: #627885;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 0.55rem;
    text-transform: uppercase;
}

.report-stat-value {
    color: #13353d;
    font-size: 1.9rem;
    font-weight: 800;
    line-height: 1.05;
    margin: 0;
}

.report-stat-note {
    color: #627885;
    font-size: 0.92rem;
    margin-top: 0.7rem;
}

.report-icon {
    align-items: center;
    border-radius: 16px;
    color: #fff;
    display: inline-flex;
    font-size: 1.15rem;
    height: 48px;
    justify-content: center;
    width: 48px;
}

.report-section-title {
    color: #13353d;
    font-size: 1.08rem;
    font-weight: 800;
    margin: 0;
}

.report-section-subtitle {
    color: #6a7f8b;
    margin: 0.35rem 0 0;
}

.report-class-item {
    align-items: center;
    background: #f6fbfb;
    border: 1px solid #e2ecee;
    border-radius: 16px;
    display: flex;
    justify-content: space-between;
    padding: 0.95rem 1rem;
}

.report-class-item + .report-class-item {
    margin-top: 0.75rem;
}

.table thead th {
    white-space: nowrap;
    vertical-align: middle;
}

.report-progress {
    background: #e8eff1;
    border-radius: 999px;
    height: 8px;
    overflow: hidden;
}

.report-progress > span {
    background: linear-gradient(90deg, #0f8f6a, #4ec9a8);
    border-radius: inherit;
    display: block;
    height: 100%;
}

.report-empty {
    background: #f7fbfc;
    border: 1px dashed #d3e0e4;
    border-radius: 16px;
    color: #6b7f8b;
    padding: 2rem 1rem;
    text-align: center;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SPP Siswa <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Laporan <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php
    $nominalTagihan = (float) ($reportSummary['nominal_tagihan'] ?? 0);
    $nominalTerbayar = (float) ($reportSummary['nominal_terbayar'] ?? 0);
    $outstandingNominal = max(0, $nominalTagihan - $nominalTerbayar);
    $paidPercentage = $nominalTagihan > 0 ? round(($nominalTerbayar / $nominalTagihan) * 100, 1) : 0;
?>

<div class="report-shell">
    <div class="card report-hero">
        <div class="card-body p-4 p-xl-5">
            <div class="d-flex flex-column flex-xl-row justify-content-between gap-4 align-items-xl-end">
                <div>
                    <div class="small text-white-50 text-uppercase fw-bold mb-2" style="letter-spacing: 0.16em;">Laporan Pembayaran</div>
                    <h3 class="text-white mb-2">Rekap SPP siswa yang lebih ringkas dan mudah dipantau</h3>
                </div>
                <form method="GET" class="row g-2 align-items-end" style="min-width: 280px;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin_spp'): ?>
                        <div class="col-12">
                            <label class="form-label text-white">Madrasah</label>
                            <select name="madrasah_id" class="form-select">
                                <option value="">Semua Madrasah</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($madrasah->id); ?>" <?php echo e((string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : ''); ?>><?php echo e($madrasah->name); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-12 d-grid">
                            <button class="btn btn-light"><i class="bx bx-filter-alt me-1"></i>Terapkan Filter</button>
                        </div>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="px-3 py-2 rounded-3" style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.08);">
                                <div class="small text-white-50">Cakupan laporan</div>
                                <div class="fw-semibold text-white"><?php echo e(optional($madrasahOptions->first())->name ?? 'Madrasah Aktif'); ?></div>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="report-card report-stat">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="report-stat-label">Total Siswa</div>
                        <p class="report-stat-value"><?php echo e(number_format($reportSummary['total_siswa'] ?? 0)); ?></p>
                    </div>
                    <span class="report-icon" style="background: linear-gradient(135deg, #0f7cda, #52a2f2);"><i class="bx bx-user"></i></span>
                </div>
                
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="report-card report-stat">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="report-stat-label">Total Kelas</div>
                        <p class="report-stat-value"><?php echo e(number_format($reportSummary['total_kelas'] ?? 0)); ?></p>
                    </div>
                    <span class="report-icon" style="background: linear-gradient(135deg, #6b53f2, #9b85ff);"><i class="bx bx-grid-alt"></i></span>
                </div>
                
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="report-card report-stat">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="report-stat-label">Total Tagihan</div>
                        <p class="report-stat-value"><?php echo e(number_format($reportSummary['total_tagihan'] ?? 0)); ?></p>
                    </div>
                    <span class="report-icon" style="background: linear-gradient(135deg, #0f8f6a, #48c39a);"><i class="bx bx-receipt"></i></span>
                </div>
                
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="report-card report-stat">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="report-stat-label">Pembayaran Berhasil</div>
                        <p class="report-stat-value"><?php echo e(number_format($paidPercentage, 1)); ?>%</p>
                    </div>
                    <span class="report-icon" style="background: linear-gradient(135deg, #d4881d, #f0b450);"><i class="bx bx-line-chart"></i></span>
                </div>
                
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="report-card h-100">
                <div class="card-body p-4">
                    <h5 class="report-section-title">Ringkasan kelas</h5>
                    

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $classSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="report-class-item">
                            <div>
                                <div class="fw-semibold text-dark"><?php echo e($row->kelas ?: '-'); ?></div>
                                <div class="small text-muted">Kelas aktif pada laporan</div>
                            </div>
                            <span class="badge bg-light text-dark border"><?php echo e(number_format($row->jumlah_siswa)); ?> siswa</span>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="report-empty">Belum ada data siswa untuk ditampilkan pada ringkasan kelas.</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="report-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                        <div>
                            <h5 class="report-section-title">Ringkasan nominal pembayaran</h5>
                            
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="rounded-4 p-3" style="background: #f5faf8;">
                                <div class="report-stat-label mb-2">Total Tagihan</div>
                                <div class="h4 mb-0 text-dark">Rp <?php echo e(number_format($nominalTagihan, 0, ',', '.')); ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-4 p-3" style="background: #f7f9ff;">
                                <div class="report-stat-label mb-2">Sudah Terbayar</div>
                                <div class="h4 mb-0 text-dark">Rp <?php echo e(number_format($nominalTerbayar, 0, ',', '.')); ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-4 p-3" style="background: #fff8f2;">
                                <div class="report-stat-label mb-2">Outstanding</div>
                                <div class="h4 mb-0 text-dark">Rp <?php echo e(number_format($outstandingNominal, 0, ',', '.')); ?></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="report-progress"><span style="width: <?php echo e(min(100, $paidPercentage)); ?>%;"></span></div>
                            <div class="small text-muted mt-2"><?php echo e(number_format($paidPercentage, 1)); ?>% nominal tagihan sudah tercatat sebagai pembayaran berhasil.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="report-card">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                <div>
                    <h5 class="report-section-title">Rekap per siswa</h5>
                    
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Total Tagihan</th>
                            <th>Lunas</th>
                            <th>Belum Lunas</th>
                            <th>Total Nominal</th>
                            <th>Terbayar</th>
                            <th>Progres</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $reportRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            $rowNominal = (float) ($row->total_nominal_tagihan ?? 0);
                            $rowTerbayar = (float) ($row->total_nominal_terbayar ?? 0);
                            $rowPercentage = $rowNominal > 0 ? round(($rowTerbayar / $rowNominal) * 100, 1) : 0;
                        ?>
                        <tr>
                            <td>
                                <div class="fw-semibold"><?php echo e($row->nama_lengkap); ?></div>
                                <small class="text-muted"><?php echo e($row->nis); ?></small>
                            </td>
                            <td><?php echo e($row->kelas ?: '-'); ?></td>
                            <td><?php echo e(number_format($row->total_tagihan)); ?></td>
                            <td><span class="badge bg-success-subtle text-success"><?php echo e(number_format($row->tagihan_lunas)); ?></span></td>
                            <td><span class="badge bg-warning-subtle text-warning"><?php echo e(number_format($row->tagihan_belum_lunas)); ?></span></td>
                            <td>Rp <?php echo e(number_format($rowNominal, 0, ',', '.')); ?></td>
                            <td>Rp <?php echo e(number_format($rowTerbayar, 0, ',', '.')); ?></td>
                            <td style="min-width: 180px;">
                                <div class="report-progress mb-2"><span style="width: <?php echo e(min(100, $rowPercentage)); ?>%;"></span></div>
                                <div class="small text-muted"><?php echo e(number_format($rowPercentage, 1)); ?>% terbayar</div>
                            </td>
                        </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data laporan SPP siswa.</td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <?php echo e($reportRows->links('pagination::bootstrap-5')); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/spp-siswa/laporan.blade.php ENDPATH**/ ?>