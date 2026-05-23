<?php $__env->startSection('title'); ?>Data Sekolah UPPM <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
/* Modern Card Grid Design */
.setting-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.setting-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.setting-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.setting-card.active::before { background: linear-gradient(90deg, #28a745, #20c997); }
.setting-card.inactive::before { background: linear-gradient(90deg, #6c757d, #495057); }

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.card-icon.active { background: linear-gradient(135deg, #28a745, #20c997); }
.card-icon.inactive { background: linear-gradient(135deg, #6c757d, #495057); }

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.card-date {
    color: #718096;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.badge-modern {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-modern.bg-primary { background: linear-gradient(135deg, #667eea, #764ba2) !important; }
.badge-modern.bg-success { background: linear-gradient(135deg, #48bb78, #38a169) !important; }
.badge-modern.bg-info { background: linear-gradient(135deg, #4299e1, #3182ce) !important; }
.badge-modern.bg-warning { background: linear-gradient(135deg, #ed8936, #dd6b20) !important; }
.badge-modern.bg-secondary { background: linear-gradient(135deg, #a0aec0, #718096) !important; }

.card-description {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 15px;
}

.card-details {
    background: #f7fafc;
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #667eea;
    margin-bottom: 15px;
}

.card-details small {
    color: #718096;
    font-size: 0.8rem;
}

.nominal-info {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
}

.nominal-info .nominal-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.nominal-info .nominal-item:last-child {
    margin-bottom: 0;
}

.nominal-info .nominal-label {
    font-size: 0.8rem;
    opacity: 0.9;
}

.nominal-info .nominal-value {
    font-weight: 600;
    font-size: 0.9rem;
}

/* Statistics Cards */
.stats-card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    background: white;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.stats-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

/* Filter Card */
.filter-card {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* Action Buttons */
.action-buttons {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 8px 30px rgba(72, 187, 120, 0.3);
}

.action-buttons h5 {
    color: white;
    margin-bottom: 1rem;
}

.btn-group-custom .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 25px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-group-custom .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(5px);
}

.loading-content {
    background: white;
    padding: 2.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
}

/* Responsive Design */
@media (max-width: 768px) {
    .btn-group-custom .btn {
        width: 100%;
        margin-right: 0;
    }

    .stats-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .card-description {
        font-size: 0.9rem;
    }

    .card-title {
        font-size: 1.1rem;
    }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    border-radius: 15px;
    border: 2px dashed #cbd5e0;
}

.empty-state .empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: white;
    font-size: 2rem;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

/* Modern Form Select Styling */
.form-select {
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    padding: 12px 16px;
    font-size: 0.95rem;
    font-weight: 500;
    color: #2d3748;
    background: white;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    outline: none;
    transform: translateY(-1px);
}

.form-select:hover {
    border-color: #cbd5e0;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-label {
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 8px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Filter Card Modern Styling */
.card {
    border-radius: 15px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.card-body {
    padding: 2rem;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> UPPM <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Data Sekolah <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-buildings"></i>
                    Data Sekolah UPPM
                </h4>
                <p class="text-white-50 mb-0">
                    Kelola dan pantau data sekolah beserta informasi pembayaran iuran UPPM untuk tahun <?php echo e(request('tahun', date('Y'))); ?>

                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-buildings"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Sekolah</p>
                        <h5 class="mb-0"><?php echo e(count($data)); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bx bx-check-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Lunas</p>
                        <h5 class="mb-0"><?php echo e(collect($data)->where('status_pembayaran', 'lunas')->count()); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Lunas</p>
                        <h5 class="mb-0">Rp <?php echo e(number_format($totalNominalLunas)); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger">
                        <i class="bx bx-x-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Belum Bayar</p>
                        <h5 class="mb-0"><?php echo e(collect($data)->where('status_pembayaran', 'belum')->count()); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-info">
                        <i class="bx bx-group"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Siswa</p>
                        <h5 class="mb-0"><?php echo e(collect($data)->sum('jumlah_siswa')); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary">
                        <i class="bx bx-money"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Nominal</p>
                        <h5 class="mb-0">Rp <?php echo e(number_format($totalNominalBelumLunas)); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('uppm.data-sekolah')); ?>">
                    <div class="row g-3 align-items-end">
                        <!-- Tahun Anggaran -->
                        <div class="col-md-4">
                            <label for="tahun" class="form-label">Tahun Anggaran</label>
                            <select class="form-select" id="tahun" name="tahun">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $activeYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($year); ?>" <?php echo e(request('tahun', date('Y')) == $year ? 'selected' : ''); ?>>
                                        <?php echo e($year); ?>

                                    </option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>

                        <!-- Tombol -->
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="<?php echo e(route('uppm.data-sekolah')); ?>" class="btn btn-secondary px-4">
                                    <i class="bx bx-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($data) > 0): ?>
                    <div class="table-responsive">
                        <table class="table-modern table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Sekolah</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Jumlah PNS Sertifikasi</th>
                                    <th>Jumlah PNS Non Sertifikasi</th>
                                    <th>Jumlah GTY Sertifikasi</th>
                                    <th>Jumlah GTY Sertifikasi Inpassing</th>
                                    <th>Jumlah GTY Non Sertifikasi</th>
                                    <th>Jumlah GTT</th>
                                    <th>Jumlah PTY</th>
                                    <th>Jumlah PTT</th>
                                    <th>Total Nominal UPPM per Tahun</th>
                                    <th>Status Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <?php echo e($item->madrasah->name); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e(number_format($item->jumlah_siswa)); ?></td>
                                    <td><?php echo e(number_format($item->jumlah_pns_sertifikasi ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item->jumlah_pns_non_sertifikasi ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item->jumlah_gty_sertifikasi ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item->jumlah_gty_sertifikasi_inpassing ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item->jumlah_gty_non_sertifikasi ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item->jumlah_gtt ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item->jumlah_pty ?? 0)); ?></td>
                                    <td><?php echo e(number_format($item->jumlah_ptt ?? 0)); ?></td>
                                    <td>
                                        <strong class="text-success">Rp <?php echo e(number_format($item->total_nominal)); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-modern bg-<?php echo e($item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'sebagian' ? 'warning' : 'danger')); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $item->status_pembayaran))); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($item->id)): ?>
                                            <a href="<?php echo e(route('uppm.invoice', $item->id)); ?>" class="btn-modern btn-sm">
                                                <i class="bx bx-receipt me-1"></i> Invoice
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">Belum ada data</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bx bx-school"></i>
                        </div>
                        <h5>Tidak Ada Data Sekolah</h5>
                        <p class="text-muted">Belum ada data sekolah untuk tahun <?php echo e(request('tahun', date('Y'))); ?>.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php if(session('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?php echo e(session('success')); ?>',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
<?php endif; ?>

<?php if(session('error')): ?>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?php echo e(session('error')); ?>',
        timer: 3000,
        showConfirmButton: false,
        timerProgressBar: true
    });
<?php endif; ?>
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/data-sekolah.blade.php ENDPATH**/ ?>