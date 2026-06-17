<?php $__env->startSection('title'); ?>Pengajuan Perpanjangan SK <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Pengajuan Perpanjangan SK <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
    .sky-pagination-wrap {
        display: flex;
        justify-content: flex-end;
    }

    .sky-pagination-wrap nav {
        width: auto;
    }

    .sky-pagination-wrap .pagination {
        gap: .35rem;
        justify-content: flex-end;
        margin-bottom: 0;
    }

    .sky-pagination-wrap .page-item .page-link {
        align-items: center;
        border: 1px solid #dbe7e1;
        border-radius: 10px;
        color: #34524a;
        display: inline-flex;
        font-size: 12px;
        font-weight: 600;
        height: 34px;
        justify-content: center;
        min-width: 34px;
        padding: .35rem .65rem;
    }

    .sky-pagination-wrap .page-item.active .page-link {
        background: linear-gradient(135deg, #004b4c, #0e8549);
        border-color: transparent;
        color: #fff;
    }

    .sky-pagination-wrap .page-item.disabled .page-link {
        background: #f4f8f6;
        border-color: #e6efea;
        color: #9aa9a3;
    }

    .sky-pagination-wrap .page-link:hover {
        background: #eef7f2;
        border-color: #bfd7cb;
        color: #0e8549;
    }
</style>

<?php
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

    $importBatchModalItems = $pendingImportBatches->getCollection()
        ->merge($rejectedImportBatches->getCollection())
        ->unique('id');
?>

<div class="sky-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Verifikasi Yayasan</div>
                <h4 class="mb-1">Review pengajuan perpanjangan dari sekolah</h4>
                <p class="mb-0 text-white-50">
                    Telaah pengajuan, beri catatan review, pilih template, lalu lanjutkan ke proses generate SK Yayasan.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('sk-yayasan.template.index')); ?>" class="btn btn-light">
                    <i class="mdi mdi-text-box-edit-outline me-1"></i> Template
                </a>
                <a href="<?php echo e(route('sk-yayasan.generate.index')); ?>" class="btn btn-light">
                    <i class="mdi mdi-file-document-multiple-outline me-1"></i> Generate
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Review Import Data</div>
                            <h6 class="mb-0">Batch dengan status pending review</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($pendingImportBatches->total()); ?> pending review</span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingImportBatches->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>File Import</th>
                                        <th>Sekolah</th>
                                        <th>Upload</th>
                                        <th>Validasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingImportBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td><div class="fw-semibold"><?php echo e($batch->original_filename); ?></div></td>
                                            <td><?php echo e($batch->madrasah?->name ?? '-'); ?></td>
                                            <td>
                                                <div><?php echo e($batch->uploader?->name ?? '-'); ?></div>
                                                <small class="text-muted"><?php echo e(optional($batch->uploaded_at)->format('d/m/Y H:i')); ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($batch->valid_rows); ?> valid / <?php echo e($batch->invalid_rows); ?> salah</div>
                                                <small class="text-muted"><?php echo e($batch->headings_valid ? 'Kolom sesuai template' : 'Kolom belum sesuai template'); ?></small>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal<?php echo e($batch->id); ?>">
                                                        Lihat Review
                                                    </button>
                                                    <form method="POST"
                                                          action="<?php echo e(route('sk-yayasan.import-batches.destroy', $batch)); ?>"
                                                          data-sk-swal-confirm
                                                          data-sk-swal-title="Hapus pengajuan ini?"
                                                          data-sk-swal-text="Semua request, dokumen, dan lampiran pada batch ini akan dihapus permanen."
                                                          data-sk-swal-confirm-text="Ya, hapus"
                                                          data-sk-swal-icon="warning">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-spreadsheet"></i>
                            <strong>Tidak ada batch pending review</strong>
                            <small>Batch baru dari sekolah akan muncul di sini sebelum disinkronkan.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingImportBatches->hasPages()): ?>
                    <div class="card-footer bg-white">
                        <div class="sky-pagination-wrap">
                            <?php echo e($pendingImportBatches->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Batch Ditolak</div>
                            <h6 class="mb-0">Riwayat batch yang dikembalikan ke sekolah</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($rejectedImportBatches->total()); ?> ditolak</span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rejectedImportBatches->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>File Import</th>
                                        <th>Sekolah</th>
                                        <th>Direview</th>
                                        <th>Catatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rejectedImportBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td><div class="fw-semibold"><?php echo e($batch->original_filename); ?></div></td>
                                            <td><?php echo e($batch->madrasah?->name ?? '-'); ?></td>
                                            <td>
                                                <div><?php echo e($batch->reviewer?->name ?? '-'); ?></div>
                                                <small class="text-muted"><?php echo e(optional($batch->reviewed_at)->format('d/m/Y H:i') ?? '-'); ?></small>
                                            </td>
                                            <td>
                                                <div class="small"><?php echo e(\Illuminate\Support\Str::limit($batch->review_notes ?: 'Tidak ada catatan.', 90)); ?></div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal<?php echo e($batch->id); ?>">
                                                        Lihat Review
                                                    </button>
                                                    <form method="POST"
                                                          action="<?php echo e(route('sk-yayasan.import-batches.destroy', $batch)); ?>"
                                                          data-sk-swal-confirm
                                                          data-sk-swal-title="Hapus pengajuan ini?"
                                                          data-sk-swal-text="Semua request, dokumen, dan lampiran pada batch ini akan dihapus permanen."
                                                          data-sk-swal-confirm-text="Ya, hapus"
                                                          data-sk-swal-icon="warning">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-check-shield"></i>
                            <strong>Tidak ada batch ditolak</strong>
                            <small>Batch yang pernah ditolak oleh Yayasan akan tampil terpisah di sini.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rejectedImportBatches->hasPages()): ?>
                    <div class="card-footer bg-white">
                        <div class="sky-pagination-wrap">
                            <?php echo e($rejectedImportBatches->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <div class="modal fade" id="reviewModal<?php echo e($submission->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form method="POST" action="<?php echo e(route('sk-yayasan.pengajuan.update-status', $submission)); ?>" class="modal-content">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Review Pengajuan <?php echo e($submission->request_number); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
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
                            <div class="col-md-6">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-1">Nomor Surat Pengajuan</div>
                                    <div class="fw-semibold"><?php echo e($submission->submission_letter_number ?? '-'); ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-1">Tanggal Surat Pengajuan</div>
                                    <div class="fw-semibold"><?php echo e(optional($submission->submission_letter_date)->translatedFormat('d F Y') ?? '-'); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->importBatch): ?>
                            <div class="sky-inline-note sky-inline-note-info">
                                Berkas terkait:
                                <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$submission->importBatch, 'excel'])); ?>" class="ms-2" target="_blank" rel="noopener">Excel</a>
                                <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$submission->importBatch, 'fakta_integritas'])); ?>" class="ms-2" target="_blank" rel="noopener">Pakta Integritas</a>
                                <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$submission->importBatch, 'penilaian_perilaku'])); ?>" class="ms-2" target="_blank" rel="noopener">Penilaian Perilaku</a>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="current_status" class="form-select" required>
                                <option value="reviewed" <?php if($submission->current_status === 'reviewed'): echo 'selected'; endif; ?>>Direview</option>
                                <option value="approved" <?php if($submission->current_status === 'approved'): echo 'selected'; endif; ?>>Setujui</option>
                                <option value="rejected" <?php if($submission->current_status === 'rejected'): echo 'selected'; endif; ?>>Tolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Template Rekomendasi</label>
                            <select name="template_id" class="form-select">
                                <option value="">Belum dipilih</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($template->id); ?>" <?php if($submission->template_id == $template->id): echo 'selected'; endif; ?>><?php echo e($template->name); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Catatan Review</label>
                            <textarea name="review_notes" rows="4" class="form-control"><?php echo e($submission->review_notes); ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Review</button>
                    </div>
                </form>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importBatchModalItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <?php
            $batchBadge = $batch->status === 'synced'
                ? ['bg' => 'success', 'label' => 'TERSINKRON']
                : ($batch->status === 'rejected'
                    ? ['bg' => 'danger', 'label' => 'DITOLAK']
                    : ['bg' => 'warning', 'label' => 'PENDING REVIEW']);
        ?>
        <div class="modal fade" id="importBatchModal<?php echo e($batch->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Review Import Data</h5>
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

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-2">Lampiran Excel</div>
                                    <div class="small mb-2"><?php echo e($batch->original_filename); ?></div>
                                    <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'excel'])); ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat File</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-2">Pakta Integritas</div>
                                    <div class="small mb-2"><?php echo e($batch->fakta_integritas_filename ?? '-'); ?></div>
                                    <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'fakta_integritas'])); ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat File</a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="sky-soft-card p-3 h-100">
                                    <div class="sky-panel-label mb-2">Penilaian Perilaku</div>
                                    <div class="small mb-2"><?php echo e($batch->penilaian_perilaku_filename ?? '-'); ?></div>
                                    <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'penilaian_perilaku'])); ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat File</a>
                                </div>
                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$batch->headings_valid): ?>
                            <div class="sky-inline-note sky-inline-note-danger">
                                Format kolom file belum sesuai template.
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($batch->missing_headings)): ?>
                                    <div>Kolom kurang: <?php echo e(implode(', ', $batch->missing_headings)); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($batch->unexpected_headings)): ?>
                                    <div>Kolom tidak dikenali: <?php echo e(implode(', ', $batch->unexpected_headings)); ?></div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($batch->review_notes): ?>
                            <div class="sky-inline-note sky-inline-note-secondary">
                                <strong>Catatan Review:</strong> <?php echo e($batch->review_notes); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="sky-modal-table-wrap">
                            <table class="table table-sm align-middle sky-compact-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Baris</th>
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
                                        <tr>
                                            <td><?php echo e($row->row_number ?? '-'); ?></td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importPreviewColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <td><?php echo e(data_get($row, $importPreviewFieldMap[$column] ?? '', '-') ?: '-'); ?></td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                            <td><?php echo e($row->matched_name ?? '-'); ?></td>
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
                    </div>
                    <div class="modal-footer">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($batch->status === 'pending_review'): ?>
                            <form method="POST" action="<?php echo e(route('sk-yayasan.import-batches.review', $batch)); ?>" class="w-100">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <div class="mb-3">
                                    <label class="form-label">Catatan Review</label>
                                    <textarea name="review_notes" rows="3" class="form-control" placeholder="Isi catatan untuk admin sekolah bila perlu."></textarea>
                                </div>
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    <button type="submit" name="action" value="reject" class="btn btn-outline-danger">Tolak Batch</button>
                                    <button type="submit" name="action" value="sync" class="btn btn-primary" <?php if(!$batch->headings_valid || $batch->invalid_rows > 0): echo 'disabled'; endif; ?>>Sinkronkan ke Database</button>
                                </div>
                            </form>
                            <form method="POST"
                                  action="<?php echo e(route('sk-yayasan.import-batches.destroy', $batch)); ?>"
                                  class="w-100 mt-2"
                                  data-sk-swal-confirm
                                  data-sk-swal-title="Hapus pengajuan ini?"
                                  data-sk-swal-text="Semua request, dokumen, dan lampiran pada batch ini akan dihapus permanen."
                                  data-sk-swal-confirm-text="Ya, hapus"
                                  data-sk-swal-icon="warning">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-outline-danger">Hapus Pengajuan</button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="d-flex flex-wrap justify-content-between gap-2 w-100">
                                <form method="POST"
                                      action="<?php echo e(route('sk-yayasan.import-batches.destroy', $batch)); ?>"
                                      data-sk-swal-confirm
                                      data-sk-swal-title="Hapus pengajuan ini?"
                                      data-sk-swal-text="Semua request, dokumen, dan lampiran pada batch ini akan dihapus permanen."
                                      data-sk-swal-confirm-text="Ya, hapus"
                                      data-sk-swal-icon="warning">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger">Hapus Pengajuan</button>
                                </form>
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/pengajuan-index.blade.php ENDPATH**/ ?>