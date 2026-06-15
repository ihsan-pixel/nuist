<?php $__env->startSection('title'); ?>
    Dashboard MGMP - Super Admin
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $topInsights = $mgmpInsights->take(4);
?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Admin <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard MGMP <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('mgmp.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="mgmp-page">
    <div class="mgmp-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="mgmp-kicker mb-2">Super Admin</div>
                <h4 class="mb-1">Dashboard MGMP Terpusat</h4>
                <p class="mb-0 text-white-50">Pantau seluruh MGMP, anggota, kegiatan, dan proposal dari satu halaman admin.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('admin.create_mgmp_user')); ?>" class="btn btn-light">
                    <i class="mdi mdi-account-plus-outline me-1"></i> Buat User MGMP
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'super_admin'): ?>
                    <a href="<?php echo e(route('admin.mgmp_reset_uploads')); ?>" class="btn btn-light">
                        <i class="mdi mdi-cloud-upload-outline me-1"></i> Monitoring Reset
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <a href="<?php echo e(route('mgmp.data-mgmp')); ?>" class="btn btn-light">
                    <i class="mdi mdi-domain me-1"></i> Kelola Data MGMP
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="mdi mdi-google-circles-communities fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total MGMP</div>
                        <div class="h5 mb-0"><?php echo e($dashboardSummary['total_groups'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="mdi mdi-account-group fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total Anggota</div>
                        <div class="h5 mb-0"><?php echo e($dashboardSummary['total_members'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="mdi mdi-calendar-check-outline fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Kegiatan MGMP</div>
                        <div class="h5 mb-0"><?php echo e($dashboardSummary['total_reports'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="mdi mdi-file-document-multiple-outline fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Proposal Academica</div>
                        <div class="h5 mb-0"><?php echo e($dashboardSummary['total_proposals'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="mdi mdi-cloud-check-outline fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">MGMP Sudah Upload</div>
                        <div class="h5 mb-0"><?php echo e($dashboardSummary['uploaded_academica_groups'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                            <i class="mdi mdi-cloud-alert-outline fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">MGMP Belum Upload</div>
                        <div class="h5 mb-0"><?php echo e($dashboardSummary['pending_academica_groups'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Informasi Singkat</h6>
                        <span class="mgmp-chip">Admin Scope</span>
                    </div>
                    <div class="mgmp-summary-stack">
                        <div class="mgmp-summary-row">
                            <span>MGMP dengan anggota</span>
                            <strong><?php echo e($dashboardSummary['groups_with_members'] ?? 0); ?></strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>MGMP dengan kegiatan</span>
                            <strong><?php echo e($dashboardSummary['groups_with_reports'] ?? 0); ?></strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>Madrasah terjangkau</span>
                            <strong><?php echo e($dashboardSummary['total_schools'] ?? 0); ?></strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>Rata-rata anggota per MGMP</span>
                            <strong><?php echo e($dashboardSummary['average_members'] ?? '0'); ?></strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>MGMP perlu perhatian</span>
                            <strong><?php echo e($dashboardSummary['needs_attention'] ?? 0); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">Sorotan MGMP</h6>
                            <p class="text-muted small mb-0">Grup dengan data paling aktif atau paling lengkap.</p>
                        </div>
                        <span class="mgmp-chip"><?php echo e($mgmpInsights->count()); ?> grup</span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($topInsights->isNotEmpty()): ?>
                        <div class="row g-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $topInsights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="col-md-6">
                                    <div class="mgmp-spotlight-card h-100">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group->logo): ?>
                                                    <img src="<?php echo e(url('/uploads/' . $group->logo)); ?>" alt="Logo <?php echo e($group->name); ?>" class="mgmp-mini-logo">
                                                <?php else: ?>
                                                    <div class="mgmp-mini-logo mgmp-mini-logo-placeholder">
                                                        <i class="mdi mdi-school-outline"></i>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e(Str::limit($group->name, 26)); ?></h6>
                                                    <small class="text-muted"><?php echo e($group->owner_name); ?></small>
                                                </div>
                                            </div>
                                            <span class="badge bg-<?php echo e($group->status_class); ?>-subtle text-<?php echo e($group->status_class); ?>"><?php echo e($group->status_label); ?></span>
                                        </div>
                                        <div class="mgmp-spotlight-grid">
                                            <div>
                                                <small>Anggota</small>
                                                <strong><?php echo e($group->members_count); ?></strong>
                                            </div>
                                            <div>
                                                <small>Sekolah</small>
                                                <strong><?php echo e($group->school_count); ?></strong>
                                            </div>
                                            <div>
                                                <small>Kegiatan</small>
                                                <strong><?php echo e($group->reports_count); ?></strong>
                                            </div>
                                            <div>
                                                <small>Proposal</small>
                                                <strong><?php echo e($group->proposal_count); ?></strong>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-top">
                                            <small class="text-muted d-block mb-1">Kegiatan terakhir</small>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group->latest_report_title): ?>
                                                <div class="fw-semibold"><?php echo e(Str::limit($group->latest_report_title, 44)); ?></div>
                                                <small class="text-muted"><?php echo e(optional($group->latest_report_date)->format('d M Y') ?? '-'); ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">Belum ada kegiatan tercatat.</small>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="mgmp-empty-state py-5">
                            <i class="bx bx-data"></i>
                            <strong>Belum ada data MGMP</strong>
                            <small>Tambahkan data MGMP untuk mengisi dashboard admin.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Aktivitas Terbaru</h6>
                        <small class="text-muted"><?php echo e($recentReports->count()); ?> kegiatan</small>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentReports->count() > 0): ?>
                        <div class="mgmp-timeline">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $recentReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="mgmp-timeline-item">
                                    <div class="mgmp-timeline-dot">
                                        <i class="mdi mdi-calendar-check-outline"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold"><?php echo e($activity->judul ?? '-'); ?></div>
                                        <div class="text-muted small mb-1">
                                            <?php echo e($activity->mgmpGroup->name ?? 'MGMP'); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activity->tanggal): ?>
                                                • <?php echo e(\Carbon\Carbon::parse($activity->tanggal)->format('d M Y')); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div class="text-muted small">Peserta tercatat: <?php echo e($activity->jumlah_peserta ?? 0); ?></div>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="mgmp-empty-state py-5">
                            <i class="mdi mdi-information-outline"></i>
                            <strong>Belum ada aktivitas</strong>
                            <small>Kegiatan MGMP terbaru akan tampil pada panel ini.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">Informasi Detail MGMP</h6>
                            <p class="text-muted small mb-0">Status tiap MGMP, pengelola, anggota, proposal, dan kegiatan terakhir.</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>MGMP</th>
                                    <th>Pengelola</th>
                                    <th>Anggota</th>
                                    <th>Sekolah</th>
                                    <th>Kegiatan</th>
                                    <th>Proposal</th>
                                    <th>Kegiatan Terakhir</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $mgmpInsights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group->logo): ?>
                                                    <img src="<?php echo e(url('/uploads/' . $group->logo)); ?>" alt="Logo <?php echo e($group->name); ?>" class="mgmp-table-logo">
                                                <?php else: ?>
                                                    <div class="mgmp-table-logo mgmp-mini-logo-placeholder">
                                                        <i class="mdi mdi-school-outline"></i>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <div>
                                                    <div class="fw-semibold"><?php echo e($group->name); ?></div>
                                                    <small class="text-muted">ID Grup: <?php echo e($group->id); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($group->owner_name); ?></div>
                                            <small class="text-muted"><?php echo e($group->owner_email); ?></small>
                                        </td>
                                        <td><?php echo e($group->members_count); ?></td>
                                        <td><?php echo e($group->school_count); ?></td>
                                        <td>
                                            <div><?php echo e($group->reports_count); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group->latest_participants_count > 0): ?>
                                                <small class="text-muted">Peserta terakhir: <?php echo e($group->latest_participants_count); ?></small>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td><?php echo e($group->proposal_count); ?></td>
                                        <td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group->latest_report_title): ?>
                                                <div class="fw-semibold"><?php echo e(Str::limit($group->latest_report_title, 34)); ?></div>
                                                <small class="text-muted"><?php echo e(optional($group->latest_report_date)->format('d M Y') ?? '-'); ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">Belum ada kegiatan</small>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($group->status_class); ?>-subtle text-<?php echo e($group->status_class); ?>">
                                                <?php echo e($group->status_label); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="9">
                                            <div class="mgmp-empty-state">
                                                <i class="bx bx-data"></i>
                                                <strong>Belum ada detail MGMP</strong>
                                                <small>Data detail MGMP akan tampil di sini.</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">Kelompok MGMP Sudah Upload Academica</h6>
                            <p class="text-muted small mb-0">Owner MGMP yang sudah memiliki file Academica.</p>
                        </div>
                        <span class="badge bg-success-subtle text-success"><?php echo e($mgmpWithAcademicaUpload->count()); ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>MGMP</th>
                                    <th>Owner</th>
                                    <th>File</th>
                                    <th>Upload</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $mgmpWithAcademicaUpload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($group->name); ?></td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($group->owner_name); ?></div>
                                            <small class="text-muted"><?php echo e($group->owner_email); ?></small>
                                        </td>
                                        <td><?php echo e(Str::limit($group->academica_filename ?? '-', 28)); ?></td>
                                        <td><?php echo e(optional($group->academica_uploaded_at)->format('d M Y H:i') ?? '-'); ?></td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="mgmp-empty-state py-4">
                                                <i class="bx bx-upload"></i>
                                                <strong>Belum ada upload Academica</strong>
                                                <small>Daftar MGMP yang sudah upload akan tampil di sini.</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">Kelompok MGMP Belum Upload Academica</h6>
                            <p class="text-muted small mb-0">Owner MGMP yang belum memiliki file Academica.</p>
                        </div>
                        <span class="badge bg-danger-subtle text-danger"><?php echo e($mgmpWithoutAcademicaUpload->count()); ?></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>MGMP</th>
                                    <th>Owner</th>
                                    <th>Anggota</th>
                                    <th>Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $mgmpWithoutAcademicaUpload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($group->name); ?></td>
                                        <td>
                                            <div class="fw-semibold"><?php echo e($group->owner_name); ?></div>
                                            <small class="text-muted"><?php echo e($group->owner_email); ?></small>
                                        </td>
                                        <td><?php echo e($group->members_count); ?></td>
                                        <td><?php echo e($group->reports_count); ?></td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="mgmp-empty-state py-4">
                                                <i class="bx bx-check-shield"></i>
                                                <strong>Semua MGMP sudah upload</strong>
                                                <small>Tidak ada MGMP yang menunggu upload Academica.</small>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="mb-3">Data Anggota MGMP</h5>
            <div class="table-responsive">
                <table id="datatable-anggota" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Grup MGMP</th>
                            <th>Sekolah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($m->name); ?></td>
                            <td><?php echo e($m->email); ?></td>
                            <td><?php echo e($m->mgmpGroup->name ?? '-'); ?></td>
                            <td><?php echo e($m->sekolah ?? '-'); ?></td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr><td colspan="5" class="text-center">Belum ada anggota MGMP.</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Daftar Proposal Academica</h5>
            <div class="table-responsive">
                <table id="datatable-academica-admin" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Pengupload</th>
                            <th>File</th>
                            <th>Diunggah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($p->user->name ?? 'User ID ' . $p->user_id); ?></td>
                            <td><?php echo e($p->filename); ?></td>
                            <td><?php echo e($p->created_at->format('Y-m-d H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(url('/uploads/' . $p->path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat</a>
                            </td>
                        </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <tr><td colspan="5" class="text-center">Belum ada proposal.</td></tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>

<script>
$(document).ready(function () {
    ["#datatable-anggota","#datatable-academica-admin"].forEach(function(sel){
        if ($.fn.DataTable.isDataTable(sel)) { $(sel).DataTable().destroy(); }
        let table = $(sel).DataTable({ responsive: true, lengthChange: true, autoWidth: false, destroy: true, buttons: ["copy","excel","pdf","print","colvis"] });
        table.buttons().container().appendTo(sel + '_wrapper .col-md-6:eq(0)');
    });
});
</script>

<style>
    .mgmp-summary-stack {
        display: grid;
        gap: 10px;
    }

    .mgmp-summary-row {
        align-items: center;
        background: #f7faf8;
        border: 1px solid #e7f0eb;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        padding: 12px 14px;
    }

    .mgmp-summary-row span {
        color: #5d6f67;
        font-size: 13px;
    }

    .mgmp-summary-row strong {
        color: #102d28;
        font-size: 16px;
    }

    .mgmp-spotlight-card {
        background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
        border: 1px solid #e5eee9;
        border-radius: 18px;
        padding: 18px;
    }

    .mgmp-mini-logo,
    .mgmp-table-logo {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .mgmp-table-logo {
        width: 38px;
        height: 38px;
    }

    .mgmp-mini-logo-placeholder {
        align-items: center;
        background: rgba(14, 133, 73, .10);
        color: #0e8549;
        display: flex;
        justify-content: center;
    }

    .mgmp-spotlight-grid {
        display: grid;
        gap: 10px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .mgmp-spotlight-grid div {
        background: #fff;
        border: 1px solid #edf3ef;
        border-radius: 14px;
        padding: 10px 12px;
    }

    .mgmp-spotlight-grid small {
        color: #6d7b75;
        display: block;
        margin-bottom: 4px;
    }

    .mgmp-spotlight-grid strong {
        color: #102d28;
        font-size: 18px;
    }

    .mgmp-timeline {
        display: grid;
        gap: 18px;
    }

    .mgmp-timeline-item {
        display: grid;
        gap: 12px;
        grid-template-columns: 36px minmax(0, 1fr);
    }

    .mgmp-timeline-dot {
        align-items: center;
        background: rgba(14, 133, 73, .12);
        border-radius: 12px;
        color: #0e8549;
        display: flex;
        height: 36px;
        justify-content: center;
        width: 36px;
    }

    @media (max-width: 768px) {
        .mgmp-spotlight-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/super_mgmp_dashboard.blade.php ENDPATH**/ ?>