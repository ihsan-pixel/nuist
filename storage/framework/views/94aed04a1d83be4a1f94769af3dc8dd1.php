<?php $__env->startSection('title'); ?>
    Monitoring Reset MGMP
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
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Admin <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Monitoring Reset MGMP <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('mgmp.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="mgmp-page">
    <div class="mgmp-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="mgmp-kicker mb-2">Super Admin</div>
                <h4 class="mb-1">Monitoring Upload Reset MGMP</h4>
                <p class="mb-0 text-white-50">Pantau progres reset yang sudah diunggah MGMP, lengkap dengan proposal utama dan lampiran pendukung.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('admin.mgmp_dashboard')); ?>" class="btn btn-light">
                    <i class="mdi mdi-arrow-left me-1"></i> Dashboard MGMP
                </a>
                <span class="mgmp-chip bg-white text-success">
                    <?php echo e($monitorSummary['total_updates'] ?? 0); ?> update
                </span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="mdi mdi-progress-upload fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total Update Reset</div>
                        <div class="h5 mb-0"><?php echo e($monitorSummary['total_updates'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                            <i class="mdi mdi-google-circles-communities fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">MGMP Sudah Upload</div>
                        <div class="h5 mb-0"><?php echo e($monitorSummary['mgmp_with_updates'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                            <i class="mdi mdi-paperclip fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Total Lampiran</div>
                        <div class="h5 mb-0"><?php echo e($monitorSummary['total_attachments'] ?? 0); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mgmp-stat-card p-3 h-100">
                <div class="d-flex align-items-center">
                    <div class="avatar-md me-3">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                            <i class="mdi mdi-chart-donut fs-4"></i>
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small">Rata-rata Progres</div>
                        <div class="h5 mb-0"><?php echo e($monitorSummary['average_progress'] ?? '0'); ?>%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Ringkasan Cepat</h6>
                        <span class="mgmp-chip">Admin Scope</span>
                    </div>
                    <div class="mgmp-summary-stack">
                        <div class="mgmp-summary-row">
                            <span>Update selesai 100%</span>
                            <strong><?php echo e($monitorSummary['completed_updates'] ?? 0); ?></strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>Update terbaru</span>
                            <strong><?php echo e(optional($monitorSummary['latest_uploaded_at'])->format('d M Y H:i') ?? '-'); ?></strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>MGMP tanpa grup valid</span>
                            <strong><?php echo e($resetInsights->whereNull('mgmp_group_id')->count()); ?></strong>
                        </div>
                        <div class="mgmp-summary-row">
                            <span>Update tanpa lampiran</span>
                            <strong><?php echo e($resetInsights->where('files_count', 0)->count()); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="mb-1">MGMP Terbaru Upload Reset</h6>
                            <p class="text-muted small mb-0">Satu update terakhir dari setiap grup MGMP yang sudah mengunggah progres reset.</p>
                        </div>
                        <span class="badge bg-success-subtle text-success"><?php echo e($latestGroupUpdates->count()); ?></span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($latestGroupUpdates->isNotEmpty()): ?>
                        <div class="row g-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $latestGroupUpdates->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="col-md-6">
                                    <div class="mgmp-spotlight-card h-100">
                                        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
                                            <div>
                                                <h6 class="mb-1"><?php echo e($update->mgmp_group_name); ?></h6>
                                                <small class="text-muted d-block"><?php echo e($update->proposal_owner_name); ?></small>
                                                <small class="text-muted"><?php echo e($update->created_at?->format('d M Y H:i') ?? '-'); ?></small>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary"><?php echo e($update->progress_percent); ?>%</span>
                                        </div>
                                        <div class="academica-progress-track mb-3">
                                            <div class="academica-progress-bar" style="width: <?php echo e(max(0, min(100, (int) $update->progress_percent))); ?>%;"></div>
                                        </div>
                                        <div class="fw-semibold mb-1"><?php echo e(\Illuminate\Support\Str::limit($update->title, 55)); ?></div>
                                        <p class="text-muted small mb-3"><?php echo e(\Illuminate\Support\Str::limit($update->progress_note, 110)); ?></p>
                                        <div class="d-flex flex-wrap gap-2 small text-muted mb-3">
                                            <span><?php echo e($update->members_count); ?> anggota</span>
                                            <span><?php echo e($update->reports_count); ?> kegiatan</span>
                                            <span><?php echo e($update->files_count); ?> lampiran</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->proposal_path): ?>
                                                <a href="<?php echo e(url('/uploads/' . $update->proposal_path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="mdi mdi-file-document-outline me-1"></i> Proposal
                                                </a>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->files_count > 0): ?>
                                                <a href="<?php echo e('#update-files-' . $update->id); ?>" class="btn btn-sm btn-outline-secondary">
                                                    <i class="mdi mdi-paperclip me-1"></i> Lampiran
                                                </a>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="mgmp-empty-state py-5">
                            <i class="bx bx-cloud-upload"></i>
                            <strong>Belum ada upload reset MGMP</strong>
                            <small>Data monitoring akan muncul setelah MGMP mengunggah progres reset.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h5 class="mb-1">Daftar Detail Upload Reset</h5>
                    <p class="text-muted mb-0">Berisi semua progres reset yang sudah diunggah MGMP, termasuk proposal utama dan file pendukung.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table id="datatable-mgmp-reset-uploads" class="table table-bordered align-middle dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Waktu Upload</th>
                            <th>MGMP</th>
                            <th>Pemilik / Pengupload</th>
                            <th>Judul Update</th>
                            <th>Progres</th>
                            <th>Lampiran</th>
                            <th>Proposal Utama</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $resetInsights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td data-order="<?php echo e($update->created_at?->timestamp ?? 0); ?>">
                                    <div class="fw-semibold"><?php echo e($update->created_at?->format('d M Y') ?? '-'); ?></div>
                                    <small class="text-muted"><?php echo e($update->created_at?->format('H:i') ?? '-'); ?></small>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo e($update->mgmp_group_name); ?></div>
                                    <small class="text-muted"><?php echo e($update->members_count); ?> anggota • <?php echo e($update->reports_count); ?> kegiatan</small>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo e($update->proposal_owner_name); ?></div>
                                    <small class="text-muted d-block"><?php echo e($update->proposal_owner_email); ?></small>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->uploader_email !== $update->proposal_owner_email): ?>
                                        <small class="text-muted">Diupload oleh <?php echo e($update->uploader_name); ?></small>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold"><?php echo e($update->title); ?></div>
                                    <small class="text-muted">Update ID <?php echo e($update->id); ?></small>
                                </td>
                                <td style="min-width: 160px;">
                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                        <span class="badge bg-primary-subtle text-primary"><?php echo e($update->progress_percent); ?>%</span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->progress_percent >= 100): ?>
                                            <small class="text-success">Selesai</small>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="academica-progress-track">
                                        <div class="academica-progress-bar" style="width: <?php echo e(max(0, min(100, (int) $update->progress_percent))); ?>%;"></div>
                                    </div>
                                </td>
                                <td id="<?php echo e('update-files-' . $update->id); ?>" style="min-width: 220px;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->files->isNotEmpty()): ?>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $update->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <a href="<?php echo e(url('/uploads/' . $file->path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-paperclip me-1"></i><?php echo e(\Illuminate\Support\Str::limit($file->original_name, 24)); ?>

                                                </a>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-muted">Tidak ada lampiran</small>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td style="min-width: 180px;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->proposal_path): ?>
                                        <div class="fw-semibold mb-2"><?php echo e(\Illuminate\Support\Str::limit($update->proposal_filename, 28)); ?></div>
                                        <a href="<?php echo e(url('/uploads/' . $update->proposal_path)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="mdi mdi-file-document-outline me-1"></i> Lihat Proposal
                                        </a>
                                    <?php else: ?>
                                        <small class="text-muted">Proposal tidak tersedia</small>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td style="min-width: 280px;">
                                    <div class="text-wrap"><?php echo e($update->progress_note); ?></div>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="9">
                                    <div class="mgmp-empty-state py-5">
                                        <i class="bx bx-data"></i>
                                        <strong>Belum ada data upload reset</strong>
                                        <small>Belum ada progres reset MGMP yang bisa dimonitor.</small>
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
    if ($.fn.DataTable.isDataTable('#datatable-mgmp-reset-uploads')) {
        $('#datatable-mgmp-reset-uploads').DataTable().destroy();
    }

    let table = $('#datatable-mgmp-reset-uploads').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        order: [[1, 'desc']],
        destroy: true,
        buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
    });

    table.buttons().container().appendTo('#datatable-mgmp-reset-uploads_wrapper .col-md-6:eq(0)');
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
        text-align: right;
    }

    .mgmp-spotlight-card {
        background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
        border: 1px solid #e5eee9;
        border-radius: 18px;
        padding: 18px;
    }

    .academica-progress-track {
        background: #e9f2ed;
        border-radius: 999px;
        height: 10px;
        overflow: hidden;
    }

    .academica-progress-bar {
        background: linear-gradient(135deg, #004b4c, #0e8549);
        border-radius: 999px;
        height: 100%;
    }

    .table td {
        vertical-align: top;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/mgmp_reset_uploads.blade.php ENDPATH**/ ?>