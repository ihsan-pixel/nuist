<?php $__env->startSection('title'); ?>Dashboard SPP Siswa <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
:root {
    --spp-ink: #12343b;
    --spp-slate: #5f7381;
    --spp-line: #d8e3e7;
    --spp-panel: #f6fbfb;
    --spp-primary: #0d6b61;
    --spp-accent: #f0a43a;
    --spp-danger: #c25445;
}

.spp-desktop-shell {
    display: grid;
    gap: 1.5rem;
}

.spp-hero {
    background:
        radial-gradient(circle at top right, rgba(240, 164, 58, 0.22), transparent 30%),
        linear-gradient(135deg, #0f3940 0%, #13675d 48%, #1a8f73 100%);
    border: 0;
    border-radius: 26px;
    color: #fff;
    overflow: hidden;
    position: relative;
}

.spp-hero::after {
    background: linear-gradient(90deg, rgba(255, 255, 255, 0.08), transparent);
    content: "";
    inset: 0;
    position: absolute;
    pointer-events: none;
}

.spp-kicker {
    color: rgba(255, 255, 255, 0.72);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.18em;
    text-transform: uppercase;
}

.spp-hero-title {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.spp-hero-summary {
    max-width: 700px;
    color: rgba(255, 255, 255, 0.84);
    font-size: 1rem;
}

.spp-badge-soft {
    align-items: center;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 999px;
    color: #fff;
    display: inline-flex;
    gap: 0.5rem;
    padding: 0.55rem 0.9rem;
}

.spp-desktop-card {
    background: #fff;
    border: 1px solid var(--spp-line);
    border-radius: 22px;
    box-shadow: 0 18px 45px rgba(12, 40, 56, 0.07);
}

.spp-stat-card {
    height: 100%;
    padding: 1.25rem;
}

.spp-stat-head {
    align-items: flex-start;
    display: flex;
    justify-content: space-between;
    gap: 1rem;
}

.spp-stat-label {
    color: var(--spp-slate);
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 0.65rem;
    text-transform: uppercase;
}

.spp-stat-value {
    color: var(--spp-ink);
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
    margin: 0;
}

.spp-stat-foot {
    color: var(--spp-slate);
    font-size: 0.92rem;
    margin-top: 0.75rem;
}

.spp-stat-icon {
    align-items: center;
    border-radius: 18px;
    color: #fff;
    display: inline-flex;
    font-size: 1.25rem;
    height: 56px;
    justify-content: center;
    width: 56px;
}

.spp-section-title {
    color: var(--spp-ink);
    font-size: 1.1rem;
    font-weight: 800;
    margin: 0;
}

.spp-section-subtitle {
    color: var(--spp-slate);
    margin: 0.35rem 0 0;
}

.spp-metric-bar {
    background: #e8eff1;
    border-radius: 999px;
    height: 10px;
    overflow: hidden;
}

.spp-metric-bar > span {
    background: linear-gradient(90deg, var(--spp-primary), #4db59a);
    border-radius: inherit;
    display: block;
    height: 100%;
}

.spp-action-tile {
    background: var(--spp-panel);
    border: 1px solid rgba(13, 107, 97, 0.08);
    border-radius: 18px;
    color: inherit;
    display: block;
    height: 100%;
    padding: 1rem 1.1rem;
    text-decoration: none;
    transition: transform 0.16s ease, box-shadow 0.16s ease;
}

.spp-action-tile:hover {
    box-shadow: 0 14px 28px rgba(13, 107, 97, 0.1);
    transform: translateY(-2px);
}

.spp-action-icon {
    align-items: center;
    background: #e1f1ed;
    border-radius: 14px;
    color: var(--spp-primary);
    display: inline-flex;
    font-size: 1.1rem;
    height: 42px;
    justify-content: center;
    width: 42px;
}

.spp-table thead th {
    background: #f3f7f8;
    border-bottom: 0;
    color: var(--spp-slate);
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.spp-table tbody td {
    padding-bottom: 0.95rem;
    padding-top: 0.95rem;
    vertical-align: middle;
}

.spp-empty {
    align-items: center;
    background: var(--spp-panel);
    border: 1px dashed var(--spp-line);
    border-radius: 18px;
    color: var(--spp-slate);
    display: flex;
    justify-content: center;
    min-height: 180px;
    text-align: center;
}

.spp-operator-art {
    display: block;
    height: auto;
    margin: 0 auto;
    margin-bottom: -0.25rem;
    max-height: 280px;
    max-width: min(100%, 460px);
    width: 80%;
}

@media (min-width: 1200px) {
    .spp-hero-side {
        position: static;
    }

    .spp-hero-media-slot {
        bottom: 0;
        pointer-events: none;
        position: absolute;
        right: 1.75rem;
        width: min(32%, 460px);
        z-index: 1;
    }

    .spp-hero-media-slot .spp-operator-art {
        margin-bottom: -1.5rem;
        max-height: 300px;
    }
}

@media (max-width: 991.98px) {
    .spp-hero-title {
        font-size: 1.6rem;
    }

    .spp-operator-art {
        max-height: 220px;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SPP Siswa <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard Monitoring <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php
    $scopeLabel = $selectedMadrasah?->name ?? ($selectedMadrasahId ? 'Madrasah Terpilih' : 'Semua Madrasah');
?>

<div class="spp-desktop-shell">
    <div class="card spp-hero">
        <div class="card-body p-4 p-xl-5 position-relative">
            <div class="row g-4 align-items-end">
                <div class="col-xl-8">
                    
                    <div class="spp-hero-title mb-3">Dashboard Monitoring SPP siswa</div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="spp-badge-soft"><i class="bx bx-buildings"></i><?php echo e($scopeLabel); ?></span>
                        <span class="spp-badge-soft"><i class="bx bx-calendar"></i><?php echo e(now()->translatedFormat('F Y')); ?></span>
                        <span class="spp-badge-soft"><i class="bx bx-layer"></i><?php echo e(number_format($monitoring['created_this_month'])); ?> tagihan dibuat bulan ini</span>
                    </div>
                </div>
                <div class="col-xl-4 spp-hero-side">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole !== 'admin_spp'): ?>
                        <form method="GET" class="row g-2">
                            <div class="col-12">
                                <label class="form-label text-white">Madrasah</label>
                                <select name="madrasah_id" class="form-select">
                                    <option value="">Semua Madrasah</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($madrasah->id); ?>" <?php echo e((string) $selectedMadrasahId === (string) $madrasah->id ? 'selected' : ''); ?>>
                                            <?php echo e($madrasah->name); ?>

                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                            <div class="col-12 d-grid">
                                <button class="btn btn-light">
                                    <i class="bx bx-filter-alt me-1"></i>Terapkan Filter
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="spp-hero-media-slot" aria-hidden="true">
                            <img src="<?php echo e(asset('images/admin-spp1.png')); ?>" alt="" class="spp-operator-art">
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="spp-desktop-card spp-stat-card">
                <div class="spp-stat-head">
                    <div>
                        <div class="spp-stat-label">Tagihan Hari Ini</div>
                        <p class="spp-stat-value"><?php echo e(number_format($monitoring['created_today'])); ?></p>
                    </div>
                    <span class="spp-stat-icon" style="background: linear-gradient(135deg, #0d6b61, #33a18c);"><i class="bx bx-plus-circle"></i></span>
                </div>
                
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="spp-desktop-card spp-stat-card">
                <div class="spp-stat-head">
                    <div>
                        <div class="spp-stat-label">Tagihan Bulan Ini</div>
                        <p class="spp-stat-value"><?php echo e(number_format($monitoring['created_this_month'])); ?></p>
                    </div>
                    <span class="spp-stat-icon" style="background: linear-gradient(135deg, #1947b8, #4d83f6);"><i class="bx bx-calendar-event"></i></span>
                </div>
                
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="spp-desktop-card spp-stat-card">
                <div class="spp-stat-head">
                    <div>
                        <div class="spp-stat-label">Perlu Tindak Lanjut</div>
                        <p class="spp-stat-value"><?php echo e(number_format($monitoring['overdue_count'])); ?></p>
                    </div>
                    <span class="spp-stat-icon" style="background: linear-gradient(135deg, #9a5b0a, #f0a43a);"><i class="bx bx-error-circle"></i></span>
                </div>
                
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="spp-desktop-card spp-stat-card">
                <div class="spp-stat-head">
                    <div>
                        <div class="spp-stat-label">Pending</div>
                        <p class="spp-stat-value"><?php echo e(number_format($monitoring['pending_verification'])); ?></p>
                    </div>
                    <span class="spp-stat-icon" style="background: linear-gradient(135deg, #9b4437, #d46e5c);"><i class="bx bx-revision"></i></span>
                </div>
                
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="spp-desktop-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                        <div>
                            <h5 class="spp-section-title">Ringkasan operasional tagihan</h5>
                            
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?php echo e(route('spp-siswa.tagihan', $selectedMadrasahId ? ['madrasah_id' => $selectedMadrasahId] : [])); ?>" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i>Buat Tagihan
                            </a>
                            <a href="<?php echo e(route('spp-siswa.transaksi', $selectedMadrasahId ? ['madrasah_id' => $selectedMadrasahId] : [])); ?>" class="btn btn-outline-secondary">
                                <i class="bx bx-credit-card me-1"></i>Cek Transaksi
                            </a>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="rounded-4 p-3" style="background: #f5faf8;">
                                <div class="spp-stat-label mb-2">Cakupan Siswa</div>
                                <div class="h3 mb-1 text-dark"><?php echo e(number_format($monitoring['students_with_bills'])); ?> <span class="text-muted fs-6">/ <?php echo e(number_format($stats['total_siswa'])); ?></span></div>
                                <div class="spp-metric-bar mb-2"><span style="width: <?php echo e(min(100, $monitoring['coverage_ratio'])); ?>%;"></span></div>
                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-4 p-3" style="background: #f8f8fc;">
                                <div class="spp-stat-label mb-2">Realisasi Pembayaran</div>
                                <div class="h3 mb-1 text-dark"><?php echo e(number_format($monitoring['paid_ratio'], 1)); ?>%</div>
                                <div class="spp-metric-bar mb-2"><span style="width: <?php echo e(min(100, $monitoring['paid_ratio'])); ?>%;"></span></div>
                                
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="rounded-4 p-3" style="background: #fff8f3;">
                                <div class="spp-stat-label mb-2">Outstanding</div>
                                <div class="h3 mb-1 text-dark">Rp <?php echo e(number_format($monitoring['outstanding_amount'], 0, ',', '.')); ?></div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="<?php echo e(route('spp-siswa.tagihan', $selectedMadrasahId ? ['madrasah_id' => $selectedMadrasahId] : [])); ?>" class="spp-action-tile">
                                <span class="spp-action-icon mb-3"><i class="bx bx-receipt"></i></span>
                                <h6 class="mb-1">Manajemen Tagihan</h6>
                                
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo e(route('spp-siswa.transaksi', $selectedMadrasahId ? ['madrasah_id' => $selectedMadrasahId] : [])); ?>" class="spp-action-tile">
                                <span class="spp-action-icon mb-3"><i class="bx bx-wallet-alt"></i></span>
                                <h6 class="mb-1">Monitor Pembayaran</h6>
                                
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo e(route('spp-siswa.laporan', $selectedMadrasahId ? ['madrasah_id' => $selectedMadrasahId] : [])); ?>" class="spp-action-tile">
                                <span class="spp-action-icon mb-3"><i class="bx bx-bar-chart-alt-2"></i></span>
                                <h6 class="mb-1">Laporan Per Siswa</h6>
                                
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo e(route('spp-siswa.pengaturan', $selectedMadrasahId ? ['madrasah_id' => $selectedMadrasahId] : [])); ?>" class="spp-action-tile">
                                <span class="spp-action-icon mb-3"><i class="bx bx-cog"></i></span>
                                <h6 class="mb-1">Pengaturan Pembayaran</h6>
                                
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="spp-desktop-card h-100">
                <div class="card-body p-4">
                    <h5 class="spp-section-title mb-1">Pengaturan aktif</h5>
                    

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activeSetting): ?>
                        <div class="rounded-4 p-3 mb-3" style="background: #f5faf8;">
                            <div class="spp-stat-label mb-2">Tahun Ajaran</div>
                            <div class="h4 mb-2 text-dark"><?php echo e($activeSetting->tahun_ajaran); ?></div>
                            <span class="badge bg-success-subtle text-success"><?php echo e(strtoupper(str_replace('_', ' ', $activeSetting->payment_provider))); ?></span>
                        </div>
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Madrasah</span>
                                <strong class="text-end"><?php echo e($activeSetting->madrasah->name ?? $scopeLabel); ?></strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status</span>
                                <strong class="text-success"><?php echo e($activeSetting->is_active ? 'Aktif' : 'Nonaktif'); ?></strong>
                            </div>
                        </div>
                        <div class="small text-muted"><?php echo e($activeSetting->catatan ?: 'Belum ada catatan tambahan pada pengaturan aktif ini.'); ?></div>
                    <?php else: ?>
                        <div class="spp-empty">
                            <div>
                                <i class="bx bx-cog mb-2" style="font-size: 2rem;"></i>
                                <div class="fw-semibold mb-1">Pengaturan aktif belum tersedia</div>
                                
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-7">
            <div class="spp-desktop-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="spp-section-title">Tagihan terbaru dibuat</h5>
                            
                        </div>
                        <a href="<?php echo e(route('spp-siswa.tagihan', $selectedMadrasahId ? ['madrasah_id' => $selectedMadrasahId] : [])); ?>" class="btn btn-sm btn-outline-primary">Lihat semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table spp-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No Tagihan</th>
                                    <th>Siswa</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentBills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($bill->nomor_tagihan); ?></div>
                                            <small class="text-muted"><?php echo e($bill->created_at?->format('d M Y H:i')); ?></small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($bill->siswa->nama_lengkap ?? '-'); ?></div>
                                            <small class="text-muted"><?php echo e($bill->siswa->nis ?? '-'); ?> | <?php echo e($bill->siswa->kelas ?? '-'); ?></small>
                                        </td>
                                        <td><?php echo e($bill->periode); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($bill->status === 'lunas' ? 'success' : ($bill->status === 'sebagian' ? 'warning text-dark' : 'danger')); ?>">
                                                <?php echo e(\Illuminate\Support\Str::headline($bill->status)); ?>

                                            </span>
                                        </td>
                                        <td>Rp <?php echo e(number_format($bill->total_tagihan, 0, ',', '.')); ?></td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada tagihan yang dibuat.</td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="spp-desktop-card h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="spp-section-title">Tagihan perlu perhatian</h5>
                            
                        </div>
                        <a href="<?php echo e(route('spp-siswa.transaksi', $selectedMadrasahId ? ['madrasah_id' => $selectedMadrasahId] : [])); ?>" class="btn btn-sm btn-outline-secondary">Buka transaksi</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table spp-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Siswa</th>
                                    <th>Jenis</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $attentionBills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($bill->siswa->nama_lengkap ?? '-'); ?></div>
                                            <small class="text-muted"><?php echo e($bill->siswa->kelas ?? '-'); ?></small>
                                        </td>
                                        <td><?php echo e($bill->jenis_tagihan ?? 'SPP'); ?></td>
                                        <td>
                                            <div><?php echo e(optional($bill->jatuh_tempo)->format('d M Y')); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(optional($bill->jatuh_tempo)->isPast()): ?>
                                                <small class="text-danger">Terlambat</small>
                                            <?php else: ?>
                                                <small class="text-muted">Menunggu pembayaran</small>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($bill->status === 'sebagian' ? 'warning text-dark' : 'danger'); ?>">
                                                <?php echo e(\Illuminate\Support\Str::headline($bill->status)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Tidak ada tagihan yang perlu perhatian khusus.</td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/spp-siswa/dashboard.blade.php ENDPATH**/ ?>