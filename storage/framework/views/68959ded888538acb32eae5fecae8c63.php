<?php $__env->startSection('title'); ?>Generate SK Yayasan - <?php echo e($madrasah->name); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Generate SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo e($madrasah->name); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Generate SK Yayasan</div>
                <h4 class="mb-1"><?php echo e($madrasah->name); ?></h4>
                <p class="mb-0 text-white-50">
                    Daftar pengajuan pada sekolah ini yang siap digenerate menjadi draft PDF. Template akan mengikuti jenis pengajuan dan kategori pegawai.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('sk-yayasan.generate.index')); ?>" class="btn btn-light">
                    <i class="bx bx-arrow-back me-1"></i>Kembali ke Antrean
                </a>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($requests->total()); ?> pengajuan</span>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Data Pokok SK</div>
                    <h6 class="mb-0">Metadata global yang dipakai untuk seluruh sekolah tersinkron</h6>
                </div>
                <a href="<?php echo e(route('sk-yayasan.generate.index')); ?>" class="btn btn-sm btn-outline-primary">Ubah di Halaman Generate</a>
            </div>

            <div class="row g-3">
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Tahun Penerbitan SK</label>
                    <input type="text" class="form-control" value="<?php echo e($coreData['school_year']); ?>" readonly>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Nomor SK Yayasan Mulai</label>
                    <input type="text" class="form-control" value="<?php echo e($coreData['document_number_start']); ?>" readonly>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Nama Ketua Yayasan</label>
                    <input type="text" class="form-control" value="<?php echo e($coreData['signer_name']); ?>" readonly>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Jabatan Penandatangan</label>
                    <input type="text" class="form-control" value="<?php echo e($coreData['signer_position']); ?>" readonly>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Ditetapkan Di</label>
                    <input type="text" class="form-control" value="<?php echo e($coreData['established_at']); ?>" readonly>
                </div>
                <div class="col-lg-4 col-md-6">
                    <label class="form-label">Pada Tanggal Penetapan</label>
                    <input type="date" class="form-control" value="<?php echo e($coreData['issued_date']); ?>" readonly>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Tembusan 1</label>
                    <textarea class="form-control" rows="2" readonly><?php echo e($coreData['copy_recipient_1']); ?></textarea>
                </div>
                <div class="col-lg-6">
                    <label class="form-label">Tembusan 2</label>
                    <textarea class="form-control" rows="2" readonly><?php echo e($coreData['copy_recipient_2']); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Generate</div>
                            <h6 class="mb-0">Data pengajuan SK Yayasan per sekolah</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($requests->total()); ?> data</span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requests->count() > 0): ?>
                        <div class="accordion" id="generateAccordion">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php
                                    $isSyncedBatch = in_array($submission->current_status, ['submitted', 'reviewed'], true)
                                        && $submission->importBatch?->status === 'synced';
                                    $badgeConfig = $submission->current_status === 'published'
                                        ? ['bg' => 'success', 'text' => 'success', 'label' => 'PUBLISHED']
                                        : ($submission->current_status === 'approved'
                                            ? ['bg' => 'primary', 'text' => 'primary', 'label' => 'APPROVED']
                                            : ($isSyncedBatch
                                                ? ['bg' => 'info', 'text' => 'info', 'label' => 'TERSINKRON']
                                                : ['bg' => 'warning', 'text' => 'warning', 'label' => strtoupper($submission->current_status)]));
                                ?>
                                <div class="accordion-item mb-3">
                                    <h2 class="accordion-header" id="generateHeading<?php echo e($submission->id); ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#generateCollapse<?php echo e($submission->id); ?>">
                                            <div class="w-100 me-3 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <div class="fw-semibold"><?php echo e($submission->request_number); ?> - <?php echo e($submission->employee?->name ?? '-'); ?></div>
                                                    <small class="text-muted"><?php echo e($submission->submission_type_label); ?></small>
                                                </div>
                                                <span class="badge bg-<?php echo e($badgeConfig['bg']); ?>-subtle text-<?php echo e($badgeConfig['text']); ?>">
                                                    <?php echo e($badgeConfig['label']); ?>

                                                </span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="generateCollapse<?php echo e($submission->id); ?>" class="accordion-collapse collapse" data-bs-parent="#generateAccordion">
                                        <div class="accordion-body">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isSyncedBatch): ?>
                                                <div class="sky-inline-note mb-3">
                                                    Data pengajuan ini berasal dari batch yang sudah berhasil tersinkronisasi pada <?php echo e(optional($submission->importBatch?->synced_at)->format('d/m/Y H:i') ?? '-'); ?>.
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
                                                        <div class="sky-panel-label mb-1">Jenis Pengajuan</div>
                                                        <div class="fw-semibold"><?php echo e($submission->submission_type_label); ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <form method="POST" action="<?php echo e(route('sk-yayasan.generate.store')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="request_id" value="<?php echo e($submission->id); ?>">
                                                <input type="hidden" name="issued_date" value="<?php echo e($coreData['issued_date']); ?>" data-sk-core-target="issued_date">
                                                <input type="hidden" name="school_year" value="<?php echo e($coreData['school_year']); ?>" data-sk-core-target="school_year">
                                                <input type="hidden" name="document_number_start" value="<?php echo e($coreData['document_number_start']); ?>" data-sk-core-target="document_number_start">
                                                <input type="hidden" name="signer_name" value="<?php echo e($coreData['signer_name']); ?>" data-sk-core-target="signer_name">
                                                <input type="hidden" name="signer_position" value="<?php echo e($coreData['signer_position']); ?>" data-sk-core-target="signer_position">
                                                <input type="hidden" name="established_at" value="<?php echo e($coreData['established_at']); ?>" data-sk-core-target="established_at">
                                                <input type="hidden" name="copy_recipient_1" value="<?php echo e($coreData['copy_recipient_1']); ?>" data-sk-core-target="copy_recipient_1">
                                                <input type="hidden" name="copy_recipient_2" value="<?php echo e($coreData['copy_recipient_2']); ?>" data-sk-core-target="copy_recipient_2">
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Template</label>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->resolved_template): ?>
                                                            <input type="text" class="form-control" value="<?php echo e($submission->resolved_template->name); ?>" readonly>
                                                            <input type="hidden" name="template_id" value="<?php echo e($submission->resolved_template->id); ?>">
                                                            <small class="text-muted">Template otomatis mengikuti jenis pengajuan ini.</small>
                                                        <?php else: ?>
                                                            <select name="template_id" class="form-select" required>
                                                                <option value="">Pilih template</option>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                                    <option value="<?php echo e($template->id); ?>" <?php if($submission->template_id == $template->id): echo 'selected'; endif; ?>><?php echo e($template->name); ?></option>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                            </select>
                                                            <small class="text-muted">Template belum bisa dipetakan otomatis, silakan pilih manual.</small>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Data Pokok SK</label>
                                                        <div class="sky-soft-card p-3 h-100">
                                                            <div class="small text-muted mb-1">Tahun <?php echo e($coreData['school_year']); ?></div>
                                                            <div class="fw-semibold"><?php echo e($coreData['established_at']); ?>, <?php echo e(\Illuminate\Support\Carbon::parse($coreData['issued_date'])->translatedFormat('d F Y')); ?></div>
                                                            <small class="text-muted">Nomor SK mengikuti setting global dari halaman generate utama.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Nomor SK</label>
                                                        <input type="text" name="document_number" class="form-control" value="<?php echo e($submission->document?->document_number); ?>">
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
                            <small>Pengajuan yang sudah disetujui atau batch yang sudah tersinkronisasi akan tampil di halaman ini.</small>
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
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/generate-school-index.blade.php ENDPATH**/ ?>