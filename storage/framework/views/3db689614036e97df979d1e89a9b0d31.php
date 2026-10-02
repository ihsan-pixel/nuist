<?php $__env->startSection('title'); ?>Verifikasi SK Yayasan - <?php echo e($madrasah->name); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Generate SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Verifikasi SK <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Verifikasi SK Yayasan</div>
                <h4 class="mb-1"><?php echo e($madrasah->name); ?></h4>
                <p class="mb-0 text-white-50">Periksa PDF setiap guru, lalu tentukan apakah SK sudah sesuai atau masih perlu penyesuaian.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('sk-yayasan.generate.index')); ?>" class="btn btn-light">
                    <i class="bx bx-arrow-back me-1"></i>Kembali
                </a>
                <a href="<?php echo e(route('sk-yayasan.generate.school', $madrasah)); ?>" class="btn btn-outline-light">
                    Halaman Generate
                </a>
            </div>
        </div>
    </div>

    <div class="alert <?php echo e($uppmIsPaid ? 'alert-success' : 'alert-warning'); ?> border-0 mb-4">
        <div class="fw-semibold">Status UPPPM <?php echo e($uppmYear); ?> — <?php echo e($uppmPeriodLabel); ?></div>
        <div><?php echo e($uppmStatusLabel); ?>. SK yang sudah sesuai <?php echo e($uppmIsPaid ? 'akan langsung berstatus Siap Diambil.' : 'akan berstatus Menunggu Pembayaran UPPPM.'); ?></div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Daftar Verifikasi</div>
                    <h6 class="mb-0"><?php echo e($requests->count()); ?> pengajuan tersinkron</h6>
                </div>
                <span class="sky-chip">Belum dichecklist = Proses</span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requests->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Request</th>
                                <th>Guru/Pegawai</th>
                                <th>Nomor SK</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php
                                    $status = match ($submission->current_status) {
                                        'ready_for_pickup' => ['success', 'Siap Diambil'],
                                        'waiting_uppm_payment' => ['warning', 'Menunggu UPPPM'],
                                        'revision_required' => ['danger', 'Perlu Penyesuaian'],
                                        default => ['info', 'Proses'],
                                    };
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($submission->request_number); ?></div>
                                        <small class="text-muted"><?php echo e($submission->submission_type_label ?? ucfirst($submission->request_type)); ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($submission->employee?->name ?? '-'); ?></div>
                                        <small class="text-muted"><?php echo e($submission->employee?->statusKepegawaian?->name ?? '-'); ?></small>
                                    </td>
                                    <td><?php echo e($submission->document?->document_number ?? 'Belum digenerate'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($status[0]); ?>-subtle text-<?php echo e($status[0]); ?>"><?php echo e($status[1]); ?></span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->skVerifier): ?>
                                            <small class="text-muted d-block mt-1">
                                                <?php echo e($submission->skVerifier->name); ?> · <?php echo e(optional($submission->sk_verified_at)->format('d/m/Y H:i')); ?>

                                            </small>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td style="min-width: 220px">
                                        <form id="verify-sk-<?php echo e($submission->id); ?>" method="POST" action="<?php echo e(route('sk-yayasan.generate.verify.update', $submission)); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Catatan penyesuaian (opsional)"><?php echo e($submission->sk_verification_notes); ?></textarea>
                                        </form>
                                    </td>
                                    <td style="min-width: 260px">
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->document): ?>
                                                <a href="<?php echo e(route('sk-yayasan.documents.download', $submission->document)); ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-show me-1"></i>View SK
                                                </a>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" disabled>View SK</button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <button form="verify-sk-<?php echo e($submission->id); ?>" name="decision" value="correct" class="btn btn-sm btn-success" <?php if(!$submission->document): echo 'disabled'; endif; ?>>
                                                <i class="bx bx-check me-1"></i>Sesuai
                                            </button>
                                            <button form="verify-sk-<?php echo e($submission->id); ?>" name="decision" value="needs_revision" class="btn btn-sm btn-outline-danger">
                                                <i class="bx bx-x me-1"></i>Belum Sesuai
                                            </button>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->sk_verification_status): ?>
                                                <button form="verify-sk-<?php echo e($submission->id); ?>" name="decision" value="processing" class="btn btn-sm btn-light">
                                                    Reset Checklist
                                                </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="sky-empty-state py-5">
                    <i class="bx bx-file-find"></i>
                    <strong>Belum ada pengajuan untuk diverifikasi</strong>
                    <small>Pengajuan akan tampil setelah batch sekolah berhasil disinkronkan.</small>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/sk-yayasan/verify-school-index.blade.php ENDPATH**/ ?>