<?php $__env->startSection('title'); ?>Generate SK Yayasan <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Generate SK Yayasan <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Generate SK Yayasan</div>
                <h4 class="mb-1">Susun draft, preview, lalu terbitkan dokumen</h4>
                <p class="mb-0 text-white-50">
                    Pilih template, isi metadata penerbitan, lalu generate SK berdasarkan pengajuan yang sudah disetujui.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($requests->total()); ?> antrean</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($publishedDocuments->count()); ?> dokumen terbaru</span>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Generate</div>
                            <h6 class="mb-0">Pengajuan siap diproses menjadi SK</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($requests->total()); ?> data</span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requests->count() > 0): ?>
                        <div class="accordion" id="generateAccordion">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="generateHeading<?php echo e($submission->id); ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#generateCollapse<?php echo e($submission->id); ?>">
                                            <div class="w-100 me-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-semibold"><?php echo e($submission->request_number); ?> - <?php echo e($submission->employee?->name ?? '-'); ?></div>
                                                    <small class="text-muted"><?php echo e($submission->madrasah?->name ?? '-'); ?></small>
                                                </div>
                                                <span class="badge bg-<?php echo e($submission->current_status === 'published' ? 'success' : 'warning'); ?>-subtle text-<?php echo e($submission->current_status === 'published' ? 'success' : 'warning'); ?>">
                                                    <?php echo e(strtoupper($submission->current_status)); ?>

                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="generateCollapse<?php echo e($submission->id); ?>" class="accordion-collapse collapse" data-bs-parent="#generateAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <div class="sky-soft-card p-3 h-100">
                                                        <div class="sky-panel-label mb-1">Sekolah</div>
                                                        <div class="fw-semibold"><?php echo e($submission->madrasah?->name ?? '-'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="sky-soft-card p-3 h-100">
                                                        <div class="sky-panel-label mb-1">Pegawai/Guru</div>
                                                        <div class="fw-semibold"><?php echo e($submission->employee?->name ?? '-'); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="sky-soft-card p-3 h-100">
                                                        <div class="sky-panel-label mb-1">Status Kepegawaian</div>
                                                        <div class="fw-semibold"><?php echo e($submission->employee?->statusKepegawaian?->name ?? ($submission->employee?->ketugasan ?? '-')); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" action="<?php echo e(route('sk-yayasan.generate.store')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="request_id" value="<?php echo e($submission->id); ?>">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Template</label>
                                                        <select name="template_id" class="form-select" required>
                                                            <option value="">Pilih template</option>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                                <option value="<?php echo e($template->id); ?>" <?php if($submission->template_id == $template->id): echo 'selected'; endif; ?>><?php echo e($template->name); ?></option>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Tanggal Terbit</label>
                                                        <input type="date" name="issued_date" class="form-control" value="<?php echo e(optional($submission->document?->issued_date)->format('Y-m-d') ?? now()->format('Y-m-d')); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nomor SK</label>
                                                        <input type="text" name="document_number" class="form-control" value="<?php echo e($submission->document?->document_number); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Penandatangan</label>
                                                        <input type="text" name="signer_name" class="form-control" value="<?php echo e($submission->document?->signer_name ?? 'Ketua Yayasan'); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Jabatan Penandatangan</label>
                                                        <input type="text" name="signer_position" class="form-control" value="<?php echo e($submission->document?->signer_position ?? 'Ketua Yayasan'); ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Catatan Penerbitan</label>
                                                        <input type="text" name="publication_notes" class="form-control" value="<?php echo e($submission->document?->publication_notes); ?>">
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="submit" class="btn btn-primary">Generate Draft</button>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->document): ?>
                                                        <a href="<?php echo e(route('sk-yayasan.documents.download', $submission->document)); ?>" class="btn btn-outline-primary" target="_blank">Preview PDF</a>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </form>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->document && $submission->document->status !== 'published'): ?>
                                                <form method="POST" action="<?php echo e(route('sk-yayasan.generate.publish', $submission->document)); ?>" class="mt-3" data-sk-swal-confirm data-sk-swal-title="Terbitkan dokumen?" data-sk-swal-text="Dokumen akan dipublikasikan sebagai SK Yayasan." data-sk-swal-confirm-text="Ya, terbitkan" data-sk-swal-icon="question">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" class="btn btn-success">Terbitkan SK Yayasan</button>
                                                </form>
                                            <?php elseif($submission->document && $submission->document->status === 'published'): ?>
                                                <div class="sky-inline-note sky-inline-note-success mt-3 mb-0">
                                                    Dokumen ini sudah diterbitkan pada <?php echo e(optional($submission->document->published_at)->format('d/m/Y H:i')); ?>.
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-file-find"></i>
                            <strong>Belum ada pengajuan yang siap digenerate</strong>
                            <small>Setelah pengajuan disetujui, antreannya akan muncul di halaman ini.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requests->hasPages()): ?>
                    <div class="card-footer bg-white">
                        <?php echo e($requests->links()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="sky-panel-label mb-1">Dokumen Terbit</div>
                    <h6 class="mb-3">Publikasi terbaru</h6>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $publishedDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="sky-document-card mb-3">
                            <div class="fw-semibold"><?php echo e($document->document_number); ?></div>
                            <div class="sky-document-meta"><?php echo e($document->request?->employee?->name ?? '-'); ?> - <?php echo e($document->request?->madrasah?->name ?? '-'); ?></div>
                            <div class="small mb-3 mt-2">Terbit <?php echo e(optional($document->published_at)->format('d/m/Y H:i')); ?></div>
                            <a href="<?php echo e(route('sk-yayasan.documents.download', $document)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">Lihat PDF</a>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="sky-empty-state">
                            <i class="bx bx-printer"></i>
                            <strong>Belum ada dokumen terbit</strong>
                            <small>Dokumen yang berhasil dipublish akan tampil di panel ini.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/generate-index.blade.php ENDPATH**/ ?>