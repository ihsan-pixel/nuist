<?php $__env->startSection('title'); ?> Riwayat Pengembangan <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<style>
/* Modern Card Grid Design */
.history-card {
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

.history-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.history-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
}

.history-card.migration::before { background: linear-gradient(90deg, #6c757d, #495057); }
.history-card.feature::before { background: linear-gradient(90deg, #28a745, #20c997); }
.history-card.update::before { background: linear-gradient(90deg, #17a2b8, #138496); }
.history-card.bugfix::before { background: linear-gradient(90deg, #ffc107, #e0a800); }
.history-card.enhancement::before { background: linear-gradient(90deg, #6f42c1, #5a32a3); }

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

.card-icon.migration { background: linear-gradient(135deg, #6c757d, #495057); }
.card-icon.feature { background: linear-gradient(135deg, #28a745, #20c997); }
.card-icon.update { background: linear-gradient(135deg, #17a2b8, #138496); }
.card-icon.bugfix { background: linear-gradient(135deg, #ffc107, #e0a800); }
.card-icon.enhancement { background: linear-gradient(135deg, #6f42c1, #5a32a3); }

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

.commit-info {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    border-radius: 8px;
    padding: 12px 15px;
}

.commit-info code {
    background: rgba(255,255,255,0.2);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 0.8rem;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    border: none;
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
}

/* Action Buttons */
.action-buttons {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 8px 30px rgba(245, 87, 108, 0.3);
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

/* Export Section - More Visible */
.export-section {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 30px rgba(40, 167, 69, 0.3);
    border: 2px solid rgba(255,255,255,0.2);
}

.export-section h5 {
    color: white;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.export-section p {
    color: rgba(255,255,255,0.9);
    margin-bottom: 1.5rem;
}

.export-section .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 500;
    background: rgba(255,255,255,0.1);
    color: white;
    transition: all 0.3s ease;
}

.export-section .btn:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.6);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.export-section .btn i {
    margin-right: 8px;
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

/* Custom Pagination Styles */
.custom-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 2rem;
}

.page-item-custom {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: white;
    border: 2px solid #e2e8f0;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.page-item-custom:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.page-item-custom.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.page-item-custom.disabled {
    opacity: 0.5;
    pointer-events: none;
    cursor: not-allowed;
}

.page-item-custom i {
    font-size: 1.1rem;
}

.page-info {
    color: #718096;
    font-size: 0.875rem;
    margin: 0 15px;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .btn-group-custom .btn {
        width: 100%;
        margin-right: 0;
    }

    .export-section .btn {
        width: 100%;
        margin-right: 0;
    }

    .stats-card {
        margin-bottom: 1rem;
    }

    .custom-pagination {
        gap: 8px;
    }

    .page-item-custom {
        width: 40px;
        height: 40px;
    }

    .page-info {
        font-size: 0.8rem;
        margin: 0 10px;
    }
}

@media (max-width: 576px) {
    .card-description {
        font-size: 0.9rem;
    }

    .card-title {
        font-size: 1.1rem;
    }

    .custom-pagination {
        flex-wrap: wrap;
        gap: 5px;
    }

    .page-item-custom {
        width: 35px;
        height: 35px;
    }

    .page-item-custom i {
        font-size: 1rem;
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
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Riwayat Pengembangan <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-history me-2"></i>
                    Riwayat Pengembangan Aplikasi
                </h4>
                <p class="text-white-50 mb-0">
                    Timeline lengkap perkembangan dan update aplikasi dari awal instalasi hingga sekarang
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
                        <i class="bx bx-data"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total</p>
                        <h5 class="mb-0"><?php echo e($stats['total'] ?? 0); ?></h5>
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
                        <i class="bx bx-git-commit"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Git Commits</p>
                        <h5 class="mb-0"><?php echo e($stats['commits'] ?? 0); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-secondary">
                        <i class="bx bx-edit"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Manual Entry</p>
                        <h5 class="mb-0"><?php echo e($stats['manual_entries'] ?? 0); ?></h5>
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
                        <i class="bx bx-plus-circle"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Fitur</p>
                        <h5 class="mb-0"><?php echo e($stats['features'] ?? 0); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning">
                        <i class="bx bx-wrench"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Bug Fix</p>
                        <h5 class="mb-0"><?php echo e($stats['bugfixes'] ?? 0); ?></h5>
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
                        <i class="bx bx-database"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Migration</p>
                        <h5 class="mb-0"><?php echo e($stats['migrations'] ?? 0); ?></h5>
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
                <form method="GET" action="<?php echo e(route('development-history.index')); ?>">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="type" class="form-label">Tipe</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Semua Tipe</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(request('type') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="source" class="form-label">Sumber</label>
                            <select class="form-select" id="source" name="source">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(request('source') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo e(request('date_from')); ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo e(request('date_to')); ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="<?php echo e(route('development-history.index')); ?>" class="btn btn-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset
                                </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'super_admin'): ?>
                                <a href="#" class="btn btn-success" onclick="runCommitTracking()">
                                    <i class="bx bx-git-commit me-1"></i> Track Commits
                                </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="action-buttons">
            <h5 class="mb-3">
                <i class="bx bx-cog me-2"></i>Aksi Pengembangan
            </h5>
            <div class="btn-group-custom">
                <a href="<?php echo e(route('development-history.sync')); ?>" class="btn btn-success" onclick="return confirm('Sinkronisasi file migration dengan riwayat pengembangan?')">
                    <i class="bx bx-sync me-1"></i> Sinkronisasi Migration
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'super_admin'): ?>
                <button type="button" class="btn btn-warning" onclick="regenerateDocumentation()">
                    <i class="bx bx-refresh me-1"></i> Regenerasi Dokumentasi
                </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Export Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="export-section">
            <h5 class="mb-3">
                <i class="bx bx-download me-2"></i>Export Riwayat Pengembangan
            </h5>
            <p class="mb-3 opacity-75">Unduh riwayat pengembangan dalam berbagai format</p>
            <div class="btn-group-custom">
                <a href="<?php echo e(route('development-history.export', ['format' => 'txt']) . '?' . request()->getQueryString()); ?>" class="btn btn-light">
                    <i class="bx bx-file me-1"></i> TXT
                </a>
                <a href="<?php echo e(route('development-history.export', ['format' => 'md']) . '?' . request()->getQueryString()); ?>" class="btn btn-light">
                    <i class="bx bx-file-blank me-1"></i> Markdown
                </a>
                <a href="<?php echo e(route('development-history.export', ['format' => 'pdf']) . '?' . request()->getQueryString()); ?>" class="btn btn-light">
                    <i class="bx bx-file-pdf me-1"></i> PDF
                </a>
                <a href="<?php echo e(route('development-history.export', ['format' => 'excel']) . '?' . request()->getQueryString()); ?>" class="btn btn-light">
                    <i class="bx bx-spreadsheet me-1"></i> Excel
                </a>
            </div>
        </div>
    </div>
</div>

<!-- History Cards Grid -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($histories->count() > 0): ?>
                    <div class="row g-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="history-card <?php echo e($history->type); ?>">
                                <div class="card-icon <?php echo e($history->type); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->details && isset($history->details['commit_hash'])): ?>
                                        <i class="bx bx-git-commit"></i>
                                    <?php else: ?>
                                        <i class="bx <?php echo e($history->getTypeIcon()); ?>"></i>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="card-header">
                                    <h3 class="card-title">
                                        <?php echo e($history->title); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->details && isset($history->details['commit_hash'])): ?>
                                            <i class="bx bx-git-commit text-info ms-2" title="Git Commit"></i>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </h3>
                                    <div class="card-meta">
                                        <div class="card-date">
                                            <i class="bx bx-calendar me-1"></i>
                                            <?php echo e($history->formatted_date); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="card-description">
                                    <?php echo e($history->description); ?>

                                </div>

                                <div class="card-badges mb-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->version): ?>
                                        <span class="badge badge-modern bg-info">v<?php echo e($history->version); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="badge badge-modern <?php echo e($history->getTypeBadgeClass()); ?>">
                                        <?php echo e($types[$history->type] ?? $history->type); ?>

                                    </span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->details && isset($history->details['commit_hash'])): ?>
                                        <span class="badge badge-modern bg-success">
                                            <i class="bx bx-git-commit me-1"></i>Git Commit
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->migration_file): ?>
                                    <div class="card-details">
                                        <small>
                                            <i class="bx bx-file me-1"></i>
                                            <strong>Migration:</strong> <?php echo e($history->migration_file); ?>

                                        </small>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($history->details && isset($history->details['commit_hash'])): ?>
                                    <div class="commit-info">
                                        <small>
                                            <i class="bx bx-hash me-1"></i>
                                            <strong>Commit:</strong> <code><?php echo e(substr($history->details['commit_hash'], 0, 7)); ?></code>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($history->details['commit_author'])): ?>
                                                | <strong>Author:</strong> <?php echo e($history->details['commit_author']); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($history->details['webhook_processed']) && $history->details['webhook_processed']): ?>
                                                <span class="badge bg-light text-dark ms-2">Auto-tracked</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </small>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Custom Pagination -->
                    <div class="custom-pagination">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($histories->hasPages()): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($histories->onFirstPage()): ?>
                                <span class="page-item-custom disabled">
                                    <i class="bx bx-chevron-left"></i>
                                </span>
                            <?php else: ?>
                                <a href="<?php echo e($histories->previousPageUrl()); ?>" class="page-item-custom">
                                    <i class="bx bx-chevron-left"></i>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="page-info">
                                Halaman <?php echo e($histories->currentPage()); ?> dari <?php echo e($histories->lastPage()); ?>

                                <br>
                                <small class="text-muted">Menampilkan <?php echo e($histories->count()); ?> dari <?php echo e($histories->total()); ?> item</small>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($histories->hasMorePages()): ?>
                                <a href="<?php echo e($histories->nextPageUrl()); ?>" class="page-item-custom">
                                    <i class="bx bx-chevron-right"></i>
                                </a>
                            <?php else: ?>
                                <span class="page-item-custom disabled">
                                    <i class="bx bx-chevron-right"></i>
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="bx bx-history"></i>
                        </div>
                        <h5>Belum Ada Riwayat Pengembangan</h5>
                        <p class="text-muted">Klik tombol "Sinkronisasi Migration" untuk memuat riwayat dari file migration.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    // Auto-submit form when filter changes
    document.getElementById('type').addEventListener('change', function() {
        this.form.submit();
    });

    document.getElementById('source').addEventListener('change', function() {
        this.form.submit();
    });

    // Function to run commit tracking
    function runCommitTracking() {
        if (!confirm('Jalankan tracking commit Git? Proses ini akan memakan waktu beberapa saat.')) {
            return;
        }

        // Show loading
        const button = event.target.closest('a');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Processing...';
        button.classList.add('disabled');

        // Make AJAX request to run the command
        fetch('/admin/run-commit-tracking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Commit tracking berhasil! ' + data.message, 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Terjadi kesalahan saat menjalankan commit tracking', 'error');
            console.error(error);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.classList.remove('disabled');
        });
    }

    // Function to regenerate documentation
    function regenerateDocumentation() {
        if (!confirm('Regenerasi file dokumentasi riwayat pengembangan?')) {
            return;
        }

        // Show loading
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Processing...';
        button.disabled = true;

        // Make AJAX request to regenerate documentation
        fetch('/admin/regenerate-documentation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Dokumentasi berhasil diregenerasi!', 'success');
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Terjadi kesalahan saat meregenerasi dokumentasi', 'error');
            console.error(error);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }

    // Function to show notifications
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 10000; min-width: 300px;';
        notification.innerHTML = `
            <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'error-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Add to page
        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Add loading overlay to page
    document.addEventListener('DOMContentLoaded', function() {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'loading-overlay';
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.innerHTML = `
            <div class="loading-content">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mt-3">Memproses...</h5>
                <p class="text-muted">Mohon tunggu sebentar</p>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
    });

    // Show loading overlay
    function showLoading() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    }

    // Hide loading overlay
    function hideLoading() {
        document.getElementById('loadingOverlay').style.display = 'none';
    }
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/development-history/index.blade.php ENDPATH**/ ?>