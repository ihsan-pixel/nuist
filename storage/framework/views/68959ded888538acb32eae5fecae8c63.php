<?php $__env->startSection('title'); ?>Generate SK Yayasan - <?php echo e($madrasah->name); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_2'); ?> Generate SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo e($madrasah->name); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
    $keteranganOptions = \App\Support\SkYayasanImportSynchronizer::allowedKeteranganOptions();
    $importPreviewFieldMap = [
        'No' => 'excel_no',
        'NUIST ID' => 'source_nuist_id',
        'Nama' => 'source_nama',
        'Gelar' => 'source_gelar',
        'Tempat Lahir' => 'source_tempat_lahir',
        'Tanggal Lahir' => 'source_tanggal_lahir',
        "NIP Ma'arif" => 'source_nip_maarif',
        'NUPTK' => 'source_nuptk',
        'Nomor Kartanu' => 'source_nomor_kartanu',
        'TMT Pertama' => 'source_tmt_pertama',
        'Masa Kerja' => 'source_masa_kerja',
        'Pendidikan Terakhir' => 'source_pendidikan_terakhir',
        'Tahun Lulus' => 'source_tahun_lulus',
        'Program Studi' => 'source_program_studi',
        'Mapel/Tugas yang Diampu' => 'source_mapel_tugas',
        'Penilaian Kinerja' => 'source_penilaian_kinerja',
        'Keterangan' => 'source_keterangan',
    ];

    $importBatchModalItems = $requests->getCollection()
        ->pluck('importBatch')
        ->filter()
        ->unique('id')
        ->values();

    $resolveImportErrorFields = function ($row) {
        $errors = collect($row->validation_errors ?? [])->map(fn ($error) => (string) $error);
        $fields = [];
        $identifierFields = ['source_nuist_id', 'source_nama', 'source_nip_maarif', 'source_nuptk'];

        if ($errors->contains(fn ($error) => str_contains($error, 'Isi minimal salah satu data pencocokan'))) {
            $fields = array_merge($fields, $identifierFields);
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'User tidak ditemukan'))) {
            $fields = array_merge($fields, $identifierFields, ['matched_name']);
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Tanggal Lahir tidak valid'))) {
            $fields[] = 'source_tanggal_lahir';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'TMT Pertama tidak valid'))) {
            $fields[] = 'source_tmt_pertama';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Penilaian Kinerja wajib diisi'))) {
            $fields[] = 'source_penilaian_kinerja';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Keterangan wajib diisi'))) {
            $fields[] = 'source_keterangan';
        }

        return array_values(array_unique($fields));
    };
?>

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

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Generate</div>
                            <h6 class="mb-0">Generate otomatis satu sekolah atau tetap per guru</h6>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="sky-chip"><?php echo e($requests->total()); ?> data</span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requests->count() > 0): ?>
                                <form method="POST"
                                      action="<?php echo e(route('sk-yayasan.generate.school.pdf', $madrasah)); ?>"
                                      target="_blank">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-printer me-1"></i>Generate Semua Guru Sekolah Ini
                                    </button>
                                </form>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importBatchModalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <button type="button"
                                            class="btn btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#generateImportBatchModal<?php echo e($batch->id); ?>">
                                        <i class="bx bx-detail me-1"></i>Lihat Data Detail<?php echo e($importBatchModalItems->count() > 1 ? ' Batch ' . $loop->iteration : ''); ?>

                                    </button>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requests->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Request</th>
                                        <th>Guru/Pegawai</th>
                                        <th>Jenis Pengajuan</th>
                                        <th>Template</th>
                                        <th>Status</th>
                                        <th>Nomor SK</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                        <tr>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($submission->request_number); ?></div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->importBatch): ?>
                                                    <small class="text-muted"><?php echo e($submission->importBatch->original_filename); ?></small>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td><?php echo e($submission->employee?->name ?? '-'); ?></td>
                                            <td><?php echo e($submission->submission_type_label); ?></td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->resolved_template): ?>
                                                    <div class="fw-semibold"><?php echo e($submission->resolved_template->name); ?></div>
                                                <?php else: ?>
                                                    <span class="text-muted">Pilih manual saat generate</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo e($badgeConfig['bg']); ?>-subtle text-<?php echo e($badgeConfig['text']); ?>">
                                                    <?php echo e($badgeConfig['label']); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($submission->document?->document_number ?? '-'); ?></td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <form method="POST" action="<?php echo e(route('sk-yayasan.generate.store')); ?>" target="_blank">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="request_id" value="<?php echo e($submission->id); ?>">
                                                        <input type="hidden" name="preview_pdf" value="1">
                                                        <input type="hidden" name="issued_date" value="<?php echo e($coreData['issued_date']); ?>">
                                                        <input type="hidden" name="school_year" value="<?php echo e($coreData['school_year']); ?>">
                                                        <input type="hidden" name="document_number_start" value="<?php echo e($coreData['document_number_start']); ?>">
                                                        <input type="hidden" name="signer_name" value="<?php echo e($coreData['signer_name']); ?>">
                                                        <input type="hidden" name="signer_position" value="<?php echo e($coreData['signer_position']); ?>">
                                                        <input type="hidden" name="established_at" value="<?php echo e($coreData['established_at']); ?>">
                                                        <input type="hidden" name="copy_recipient_1" value="<?php echo e($coreData['copy_recipient_1']); ?>">
                                                        <input type="hidden" name="copy_recipient_2" value="<?php echo e($coreData['copy_recipient_2']); ?>">
                                                        <input type="hidden" name="publication_notes" value="<?php echo e($submission->document?->publication_notes); ?>">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->resolved_template): ?>
                                                            <input type="hidden" name="template_id" value="<?php echo e($submission->resolved_template->id); ?>">
                                                        <?php else: ?>
                                                            <input type="hidden" name="template_id" value="<?php echo e($submission->template_id); ?>">
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" <?php if(!$submission->resolved_template && !$submission->template_id): echo 'disabled'; endif; ?>>
                                                            Generate 1 Guru
                                                        </button>
                                                    </form>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->document): ?>
                                                        <a href="<?php echo e(route('sk-yayasan.documents.download', $submission->document)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">Preview PDF</a>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->document && $submission->document->status !== 'published'): ?>
                                                    <form method="POST" action="<?php echo e(route('sk-yayasan.generate.publish', $submission->document)); ?>" class="mt-2 d-inline-block" data-sk-swal-confirm data-sk-swal-title="Terbitkan dokumen?" data-sk-swal-text="Dokumen akan dipublikasikan sebagai SK Yayasan." data-sk-swal-confirm-text="Ya, terbitkan" data-sk-swal-icon="question">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-sm btn-success">Terbitkan</button>
                                                    </form>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
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

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importBatchModalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <?php
        $batchBadge = $batch->status === 'synced'
            ? ['bg' => 'success', 'label' => 'TERSINKRON']
            : ($batch->status === 'rejected'
                ? ['bg' => 'danger', 'label' => 'DITOLAK']
                : ['bg' => 'warning', 'label' => 'PENDING REVIEW']);
    ?>
    <div class="modal fade" id="generateImportBatchModal<?php echo e($batch->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1">Detail Data Sinkronisasi</h5>
                        <div class="sky-file-meta"><?php echo e($batch->original_filename); ?> - <?php echo e($batch->madrasah?->name ?? '-'); ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-3 col-6">
                            <div class="sky-mini-stat">
                                <div class="label">Uploader</div>
                                <div class="value"><?php echo e($batch->uploader?->name ?? '-'); ?></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="sky-mini-stat">
                                <div class="label">Upload</div>
                                <div class="value"><?php echo e(optional($batch->uploaded_at)->format('d/m/Y') ?? '-'); ?></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="sky-mini-stat">
                                <div class="label">Valid</div>
                                <div class="value"><?php echo e($batch->valid_rows); ?></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="sky-mini-stat">
                                <div class="label">Perlu Cek</div>
                                <div class="value"><?php echo e($batch->invalid_rows); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge bg-<?php echo e($batchBadge['bg']); ?>-subtle text-<?php echo e($batchBadge['bg']); ?>"><?php echo e($batchBadge['label']); ?></span>
                        <span class="sky-chip"><?php echo e($batch->headings_valid ? 'Kolom sesuai template' : 'Kolom belum sesuai template'); ?></span>
                    </div>

                    <form method="POST" action="<?php echo e(route('sk-yayasan.import-batches.rows.update', $batch)); ?>" id="generateEditImportBatchForm<?php echo e($batch->id); ?>">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="action" value="save" data-sync-action>
                        <div class="sky-modal-table-wrap">
                            <table class="table table-sm align-middle sky-compact-table mb-0">
                                <thead>
                                    <tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importPreviewColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <th><?php echo e($column); ?></th>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        <th>Match User</th>
                                        <th>Status</th>
                                        <th class="wrap">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $batch->rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php
                                            $rowErrorFields = $resolveImportErrorFields($row);
                                        ?>
                                        <tr>
                                            <input type="hidden" name="rows[<?php echo e($loop->index); ?>][row_number]" value="<?php echo e($row->row_number); ?>">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importPreviewColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <?php
                                                    $field = $importPreviewFieldMap[$column] ?? null;
                                                    $value = $field ? data_get($row, $field, '') : '';
                                                    $value = $value === '-' ? '' : $value;
                                                    $hasFieldError = $field && in_array($field, $rowErrorFields, true);
                                                ?>
                                                <td class="sky-edit-cell <?php echo e($column === 'No' ? 'sky-edit-cell-sm' : ''); ?> <?php echo e($hasFieldError ? 'sky-cell-error' : ''); ?>">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($column === 'Keterangan'): ?>
                                                        <select name="rows[<?php echo e($loop->parent->index); ?>][<?php echo e($field); ?>]" class="form-select form-select-sm">
                                                            <option value="">Pilih</option>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $keteranganOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                                <option value="<?php echo e($option); ?>" <?php if($value === $option): echo 'selected'; endif; ?>><?php echo e($option); ?></option>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                        </select>
                                                    <?php else: ?>
                                                        <input type="text"
                                                               name="rows[<?php echo e($loop->parent->index); ?>][<?php echo e($field); ?>]"
                                                               value="<?php echo e($value); ?>"
                                                               class="form-control form-control-sm">
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            <td class="<?php echo e(in_array('matched_name', $rowErrorFields, true) ? 'sky-cell-error-readonly' : ''); ?>"><?php echo e($row->matched_name ?? '-'); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo e($row->is_valid ? 'success' : 'danger'); ?>-subtle text-<?php echo e($row->is_valid ? 'success' : 'danger'); ?>">
                                                    <?php echo e($row->status_label ?? ($row->is_valid ? 'Siap sync' : 'Perlu perbaikan')); ?>

                                                </span>
                                            </td>
                                            <td class="wrap"><?php echo e(!empty($row->validation_errors) ? implode(' ', $row->validation_errors) : 'Data siap disinkronkan.'); ?></td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                            <div class="text-muted small">
                                Data di sini tetap bisa diedit. Setelah diperbarui, lakukan sinkronisasi ulang agar hasilnya dipakai kembali saat generate SK.
                            </div>
                            <button type="submit"
                                    form="generateEditImportBatchForm<?php echo e($batch->id); ?>"
                                    class="btn btn-outline-primary"
                                    onclick="this.form.querySelector('[data-sync-action]').value='save'">
                                <i class="bx bx-save me-1"></i>Simpan Perubahan Tabel
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan Sinkronisasi Ulang</label>
                            <textarea name="review_notes"
                                      rows="3"
                                      class="form-control"
                                      form="generateEditImportBatchForm<?php echo e($batch->id); ?>"
                                      placeholder="Catatan opsional untuk sinkronisasi ulang batch ini."></textarea>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <button type="submit"
                                    form="generateEditImportBatchForm<?php echo e($batch->id); ?>"
                                    class="btn btn-primary"
                                    onclick="this.form.querySelector('[data-sync-action]').value='sync'"
                                    <?php if(!$batch->headings_valid || $batch->invalid_rows > 0): echo 'disabled'; endif; ?>>
                                <i class="bx bx-refresh me-1"></i>Sinkronisasi Ulang
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/generate-school-index.blade.php ENDPATH**/ ?>