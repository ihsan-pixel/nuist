<?php $__env->startSection('title'); ?>Dashboard SK Yayasan <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard SK Yayasan <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
    $cards = [
        ['label' => 'Pengajuan Masuk', 'value' => $statusCounts['submitted'] ?? 0, 'icon' => 'bx bx-send', 'class' => 'primary'],
        ['label' => 'Sedang Direview', 'value' => $statusCounts['reviewed'] ?? 0, 'icon' => 'bx bx-time', 'class' => 'warning'],
        ['label' => 'Disetujui', 'value' => $statusCounts['approved'] ?? 0, 'icon' => 'bx bx-check-circle', 'class' => 'success'],
        ['label' => 'SK Terbit', 'value' => $statusCounts['published'] ?? 0, 'icon' => 'bx bx-file', 'class' => 'info'],
    ];
?>

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Dashboard SK Yayasan</div>
                <h4 class="mb-1">Monitoring pengajuan, template, dan penerbitan SK</h4>
                <p class="mb-0 text-white-50">
                    Gunakan halaman ini untuk melihat antrean pengajuan dari sekolah, status review, dan progres penerbitan SK Yayasan.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('sk-yayasan.pengajuan.index')); ?>" class="btn btn-light">
                    <i class="mdi mdi-clipboard-text-outline me-1"></i> Kelola Pengajuan
                </a>
                <a href="<?php echo e(route('sk-yayasan.generate.index')); ?>" class="btn btn-light">
                    <i class="mdi mdi-file-document-edit-outline me-1"></i> Generate SK
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <div class="col-xl-3 col-md-6">
                <div class="card sky-stat-card p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-<?php echo e($card['class']); ?>-subtle text-<?php echo e($card['class']); ?> rounded-circle">
                                <i class="<?php echo e($card['icon']); ?> fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small"><?php echo e($card['label']); ?></div>
                            <div class="h4 mb-0"><?php echo e($card['value']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <div class="sky-inline-note sky-inline-note-info mb-3">
        <strong>Review import data:</strong> <?php echo e($pendingImportBatches); ?> batch menunggu review super admin, <?php echo e($rejectedImportBatches); ?> batch pernah ditolak dan menunggu perbaikan dari admin sekolah.
    </div>

    <div class="row g-3">
        <div class="col-xl-4">
            <div class="card h-100 overflow-hidden">
                <div class="p-4 text-white" style="background: linear-gradient(135deg, #0e8549 0%, #0b6b4d 100%);">
                    <div class="sky-kicker mb-2">Ringkasan Penerbitan</div>
                    <h5 class="mb-1 text-white">Kondisi dokumen saat ini</h5>
                    <p class="mb-0 text-white-50 small">
                        Draft, template aktif, dan jumlah SK yang sudah terbit pada periode berjalan.
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="sky-metric">
                                <div class="value"><?php echo e($documentCounts['draft'] ?? 0); ?></div>
                                <div class="label">Draft Dokumen</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="sky-metric">
                                <div class="value"><?php echo e($publishedThisMonth); ?></div>
                                <div class="label">Terbit Bulan Ini</div>
                            </div>
                        </div>
                    </div>
                    <div class="sky-summary-stack mb-3">
                        <div class="sky-summary-row">
                            <span>Template aktif</span>
                            <strong><?php echo e($activeTemplates); ?></strong>
                        </div>
                        <div class="sky-summary-row">
                            <span>Dokumen publish</span>
                            <strong><?php echo e($documentCounts['published'] ?? 0); ?></strong>
                        </div>
                        <div class="sky-summary-row">
                            <span>Perlu digenerate</span>
                            <strong><?php echo e($statusCounts['approved'] ?? 0); ?></strong>
                        </div>
                    </div>
                    <div class="sky-inline-note sky-inline-note-info mb-0">
                        <div class="fw-semibold mb-1">Placeholder utama template</div>
                        <div class="small">
                            <code><?php echo e('{{nama_pegawai); ?>' }}</code>,
                            <code><?php echo e('{{nama_sekolah); ?>' }}</code>,
                            <code><?php echo e('{{status_kepegawaian); ?>' }}</code>,
                            <code><?php echo e('{{tanggal_terbit); ?>' }}</code>.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Pengajuan Terbaru</div>
                            <h6 class="mb-0">Antrean pengajuan yang baru masuk</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($latestRequests->count()); ?> data</span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($latestRequests->isNotEmpty()): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No Pengajuan</th>
                                        <th>Sekolah</th>
                                        <th>Pegawai/Guru</th>
                                        <th>Status</th>
                                        <th>Template</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $latestRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($submission->request_number); ?></div>
                                                <small class="text-muted"><?php echo e($submission->submission_letter_number ?? '-'); ?></small>
                                            </td>
                                            <td><?php echo e($submission->madrasah?->name ?? '-'); ?></td>
                                            <td><?php echo e($submission->employee?->name ?? '-'); ?></td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary text-uppercase">
                                                    <?php echo e($submission->current_status); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($submission->template?->name ?? '-'); ?></td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-inbox"></i>
                            <strong>Belum ada pengajuan</strong>
                            <small>Pengajuan dari sekolah akan muncul di panel ini.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Rekap Sekolah</div>
                    <h6 class="mb-0">Sekolah dengan pengajuan dan status penerbitan</h6>
                </div>
                <a href="<?php echo e(route('sk-yayasan.template.index')); ?>" class="btn btn-outline-primary btn-sm">
                    <i class="mdi mdi-text-box-edit-outline me-1"></i> Kelola Template
                </a>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schoolSummaries->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Sekolah</th>
                                <th>Total Pengajuan</th>
                                <th>Pending</th>
                                <th>SK Terbit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schoolSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td class="fw-semibold"><?php echo e($school->name); ?></td>
                                    <td><?php echo e($school->total_pengajuan_sk); ?></td>
                                    <td><?php echo e($school->total_pengajuan_pending); ?></td>
                                    <td><?php echo e($school->total_sk_terbit); ?></td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="sky-empty-state">
                    <i class="bx bx-building-house"></i>
                    <strong>Belum ada sekolah yang mengirim pengajuan</strong>
                    <small>Rekap akan muncul setelah admin sekolah mulai mengajukan perpanjangan SK.</small>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/dashboard.blade.php ENDPATH**/ ?>