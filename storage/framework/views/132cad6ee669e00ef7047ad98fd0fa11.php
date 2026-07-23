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

    .sky-edit-cell {
        min-width: 130px;
    }

    .sky-edit-cell .form-control,
    .sky-edit-cell .form-select {
        min-width: 130px;
        padding: .35rem .55rem;
    }

    .sky-edit-cell-sm {
        min-width: 88px;
    }

    .sky-cell-error {
        background: #fff1f1 !important;
    }

    .sky-cell-error .form-control,
    .sky-cell-error .form-select {
        background: #fff7f7;
        border-color: #dc3545 !important;
        color: #842029;
    }

    .sky-cell-error-readonly {
        background: #fff1f1 !important;
        color: #842029 !important;
    }

    .sky-page {
        --sky-ink: #182a25;
        --sky-muted: #6a7b74;
        --sky-line: #dfe8e3;
        --sky-soft: #f5f8f6;
        --sky-soft-strong: #eef4f0;
        --sky-brand: #0f6b4f;
        --sky-brand-dark: #0a4d3f;
        --sky-warn: #9a6a00;
    }

    .sky-page .card,
    .sky-page .accordion-item,
    .sky-page .modal-content {
        background: #fff;
        border: 1px solid var(--sky-line) !important;
        border-radius: 18px !important;
        box-shadow: 0 10px 24px rgba(18, 40, 33, 0.04) !important;
    }

    .sky-page .modal-header,
    .sky-page .card-header,
    .sky-page .card-footer {
        background: #fff;
        border-color: var(--sky-line);
    }

    .sky-page .card-header:first-child {
        border-radius: 18px 18px 0 0 !important;
    }

    .sky-page h4,
    .sky-page h5,
    .sky-page h6,
    .sky-page .card-title,
    .sky-page .modal-title {
        color: var(--sky-ink);
    }

    .sky-page .btn {
        border-radius: 12px;
        font-weight: 600;
    }

    .sky-page .btn-primary,
    .sky-page .btn-success {
        background: var(--sky-brand-dark);
        border-color: var(--sky-brand-dark);
    }

    .sky-page .btn-primary:hover,
    .sky-page .btn-success:hover {
        background: var(--sky-brand);
        border-color: var(--sky-brand);
    }

    .sky-page .btn-light {
        background: #fff;
        border: 1px solid var(--sky-line);
        color: var(--sky-ink);
    }

    .sky-page .btn-outline-primary {
        border-color: rgba(15, 107, 79, .24);
        color: var(--sky-brand);
    }

    .sky-page .btn-outline-primary:hover {
        background: var(--sky-brand);
        border-color: var(--sky-brand);
        color: #fff;
    }

    .sky-page .form-control,
    .sky-page .form-select {
        border-color: #d7e2dc;
        border-radius: 12px;
        min-height: 44px;
    }

    .sky-page textarea.form-control {
        min-height: auto;
    }

    .sky-page .table {
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 0;
    }

    .sky-page .table thead th {
        background: #f7faf8 !important;
        border-bottom: 1px solid var(--sky-line) !important;
        color: var(--sky-ink);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .sky-page .table tbody td {
        border-bottom: 1px solid #edf3ef;
        color: #2f463e;
        vertical-align: middle;
    }

    .sky-page .badge {
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        padding: 7px 10px;
    }

    .sky-header {
        background: linear-gradient(180deg, #ffffff 0%, #fbfcfb 100%);
        border: 1px solid var(--sky-line);
        border-radius: 22px;
        padding: 1.5rem;
    }

    .sky-section-kicker {
        color: var(--sky-brand);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .sky-page-title {
        color: var(--sky-ink);
        font-size: 1.65rem;
        font-weight: 700;
        margin-bottom: .35rem;
    }

    .sky-page-subtitle {
        color: var(--sky-muted);
        margin-bottom: 0;
        max-width: 760px;
    }

    .sky-summary-grid {
        display: grid;
        gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    }

    .sky-summary-card {
        background: #fff;
        border: 1px solid var(--sky-line);
        border-radius: 18px;
        padding: 1rem 1.1rem;
    }

    .sky-summary-value {
        color: var(--sky-ink);
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
    }

    .sky-summary-label {
        color: var(--sky-muted);
        font-size: .8rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .sky-summary-note {
        color: var(--sky-muted);
        display: block;
        font-size: 12px;
        line-height: 1.5;
        margin-top: .65rem;
    }

    .sky-keterangan-grid {
        display: grid;
        gap: .85rem;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    }

    .sky-keterangan-card {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 16px;
        min-width: 0;
        padding: .8rem .85rem;
    }

    .sky-keterangan-card .sky-summary-label {
        font-size: .68rem;
        letter-spacing: .03em;
    }

    .sky-keterangan-value {
        color: var(--sky-brand-dark);
        font-size: 1.1rem;
        font-weight: 800;
        line-height: 1;
    }

    .sky-panel-label {
        color: var(--sky-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .05em;
        text-transform: uppercase;
    }

    .sky-chip {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 999px;
        color: var(--sky-brand-dark);
        display: inline-flex;
        font-size: 12px;
        font-weight: 700;
        padding: 5px 10px;
    }

    .sky-filter-shell {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 16px;
        padding: 1rem;
    }

    .sky-filter-shell .form-control,
    .sky-filter-shell .form-select {
        background: #fff;
    }

    .sky-empty-state {
        align-items: center;
        color: var(--sky-muted);
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 32px 12px;
        text-align: center;
    }

    .sky-empty-state i {
        color: rgba(15, 107, 79, .35);
        font-size: 42px;
    }

    .sky-soft-card {
        background: linear-gradient(180deg, #ffffff 0%, #fafcfb 100%);
        border: 1px solid var(--sky-line);
        border-radius: 18px;
    }

    .sky-inline-note {
        border: 1px solid var(--sky-line);
        border-radius: 14px;
        padding: 12px 14px;
    }

    .sky-inline-note-info {
        background: #eef7ff;
        border-color: #cfe6ff;
        color: #174a7c;
    }

    .sky-inline-note-success {
        background: #edf9f2;
        border-color: #cdeed9;
        color: #18633e;
    }

    .sky-inline-note-danger {
        background: #fff2f2;
        border-color: #f3c9c9;
        color: #8a1f1f;
    }

    .sky-inline-note-secondary {
        background: #f6f7f9;
        border-color: #e2e6ea;
        color: #475569;
    }

    .sky-inline-note-warning {
        background: #fff8e8;
        border-color: #f5dfac;
        color: #815e00;
    }

    .sky-compact-table thead th,
    .sky-compact-table tbody td {
        font-size: 12px;
        padding: 10px 12px;
        white-space: nowrap;
    }

    .sky-compact-table tbody td.wrap {
        white-space: normal;
    }

    .sky-modal-table-wrap {
        border: 1px solid var(--sky-line);
        border-radius: 16px;
        max-height: 420px;
        overflow: auto;
    }

    .sky-mini-stat {
        background: var(--sky-soft);
        border: 1px solid var(--sky-line);
        border-radius: 14px;
        padding: 12px 14px;
    }

    .sky-mini-stat .value {
        color: var(--sky-ink);
        font-size: 18px;
        font-weight: 700;
        line-height: 1.1;
    }

    .sky-mini-stat .label {
        color: var(--sky-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .sky-file-meta {
        color: var(--sky-muted);
        font-size: 12px;
    }

    .sky-data-primary {
        color: var(--sky-ink);
        font-weight: 600;
    }

    .sky-data-secondary {
        color: var(--sky-muted);
        display: block;
        font-size: 12px;
        line-height: 1.5;
        margin-top: .2rem;
    }

    .sky-row-select-col {
        min-width: 42px;
        text-align: center;
        width: 42px;
    }

    .sky-table-actions {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        justify-content: space-between;
        margin-bottom: .75rem;
    }

    .sky-section-divider {
        border-top: 1px solid var(--sky-line);
        margin: 1.5rem 0;
    }

    .sky-collapse-trigger {
        align-items: center;
        background: transparent;
        border: 0;
        border-radius: 18px;
        color: inherit;
        display: flex;
        justify-content: space-between;
        padding: 1.2rem 1.25rem;
        text-align: left;
        width: 100%;
    }

    .sky-collapse-trigger:focus {
        box-shadow: none;
        outline: none;
    }

    .sky-collapse-trigger .sky-collapse-icon {
        color: var(--sky-muted);
        font-size: 1.2rem;
        transition: transform .2s ease;
    }

    .sky-collapse-trigger[aria-expanded="true"] .sky-collapse-icon {
        transform: rotate(180deg);
    }

    .sky-collapse-shell {
        border-top: 1px solid var(--sky-line);
    }

    @media (max-width: 768px) {
        .sky-header {
            padding: 1.1rem;
        }

        .sky-page-title {
            font-size: 1.35rem;
        }
    }
</style>

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

    $importBatchModalItems = $pendingImportBatches->getCollection()
        ->merge($syncedImportBatches->getCollection())
        ->unique('id');

    $statusOptions = [
        'submitted' => 'Diajukan',
        'reviewed' => 'Direview',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'published' => 'Terbit',
    ];

    $statusBadgeMap = [
        'submitted' => ['color' => 'warning', 'label' => 'Diajukan'],
        'reviewed' => ['color' => 'info', 'label' => 'Direview'],
        'approved' => ['color' => 'primary', 'label' => 'Disetujui'],
        'published' => ['color' => 'success', 'label' => 'Terbit'],
        'rejected' => ['color' => 'danger', 'label' => 'Ditolak'],
    ];

    $batchStatusBadgeMap = [
        'pending_review' => ['color' => 'warning', 'label' => 'Pending Review'],
        'synced' => ['color' => 'success', 'label' => 'Tersinkron'],
        'rejected' => ['color' => 'danger', 'label' => 'Ditolak'],
    ];

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

        if ($errors->contains(fn ($error) => str_contains($error, 'Tahun Lulus harus 4 digit'))) {
            $fields[] = 'source_tahun_lulus';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Penilaian Kinerja harus berupa angka'))) {
            $fields[] = 'source_penilaian_kinerja';
        }

        if ($errors->contains(fn ($error) => str_contains($error, 'Keterangan wajib diisi'))) {
            $fields[] = 'source_keterangan';
        }

        return array_values(array_unique($fields));
    };
?>

<div class="sky-page">
    <div class="sky-header mb-4">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <div class="sky-section-kicker mb-2">SK Yayasan</div>
                <h4 class="sky-page-title">Pengajuan perpanjangan SK</h4>
                
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo e(route('sk-yayasan.template.index')); ?>" class="btn btn-light">
                    <i class="mdi mdi-text-box-edit-outline me-1"></i>Template
                </a>
                <a href="<?php echo e(route('sk-yayasan.generate.index')); ?>" class="btn btn-light">
                    <i class="mdi mdi-file-document-multiple-outline me-1"></i>Generate
                </a>
                <a href="<?php echo e(route('sk-yayasan.pengajuan.export-school-summary')); ?>" class="btn btn-primary">
                    <i class="mdi mdi-microsoft-excel me-1"></i>Rekap Sekolah
                </a>
            </div>
        </div>
    </div>

    <div class="sky-summary-grid mb-4">
        <div class="sky-summary-card">
            <div class="sky-summary-label">Sekolah Sudah Mengajukan</div>
            <div class="sky-summary-value mt-2"><?php echo e(number_format($schoolSubmissionSummaryCards['submitted_schools'] ?? 0)); ?></div>
            
        </div>
        <div class="sky-summary-card">
            <div class="sky-summary-label">Sekolah Belum Mengajukan</div>
            <div class="sky-summary-value mt-2"><?php echo e(number_format($schoolSubmissionSummaryCards['not_submitted_schools'] ?? 0)); ?></div>
            
        </div>
        <div class="sky-summary-card">
            <div class="sky-summary-label">Total Pengajuan Aktif</div>
            <div class="sky-summary-value mt-2"><?php echo e(number_format($schoolSubmissionSummaryCards['total_requests'] ?? 0)); ?></div>
            
        </div>
        <div class="sky-summary-card">
            <div class="sky-summary-label">Belum Match Akun NUist</div>
            <div class="sky-summary-value mt-2"><?php echo e(number_format($schoolSubmissionSummaryCards['requests_without_nuist_account'] ?? 0)); ?></div>
            
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Komposisi Keterangan</div>
                    <h6 class="mb-0">Jenis pengajuan yang sedang berjalan</h6>
                </div>
                
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($keteranganSummaryCounts)): ?>
                <div class="sky-keterangan-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $keteranganSummaryCounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="sky-keterangan-card">
                            <div class="sky-summary-label mb-2"><?php echo e($label); ?></div>
                            <div class="sky-keterangan-value"><?php echo e(number_format($count)); ?></div>
                        </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="sky-empty-state py-4">
                    <i class="bx bx-receipt"></i>
                    <strong>Belum ada kategori pengajuan yang terpetakan</strong>
                    
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="card h-100">
                <button class="sky-collapse-trigger" type="button" data-bs-toggle="collapse" data-bs-target="#pendingBatchPanel" aria-expanded="true" aria-controls="pendingBatchPanel">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 grow me-3">
                        <div>
                            <div class="sky-panel-label mb-1">Batch Pending</div>
                            <h6 class="mb-0">Import yang masih menunggu review</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($pendingImportBatches->total()); ?> batch</span>
                    </div>
                    <i class="mdi mdi-chevron-down sky-collapse-icon"></i>
                </button>

                <div id="pendingBatchPanel" class="collapse show sky-collapse-shell">
                    <div class="card-body">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingImportBatches->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Sekolah</th>
                                            <th>Upload</th>
                                            <th>Validasi</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pendingImportBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <tr>
                                                <td>
                                                    <span class="sky-data-primary"><?php echo e($batch->madrasah?->name ?? '-'); ?></span>
                                                </td>
                                                <td>
                                                    <span class="sky-data-primary"><?php echo e($batch->uploader?->name ?? '-'); ?></span>
                                                    <span class="sky-data-secondary"><?php echo e(optional($batch->uploaded_at)->format('d/m/Y H:i')); ?></span>
                                                </td>
                                                <td>
                                                    <span class="sky-data-primary"><?php echo e($batch->valid_rows); ?> valid / <?php echo e($batch->invalid_rows); ?> salah</span>
                                                    <span class="sky-data-secondary"><?php echo e($batch->headings_valid ? 'Kolom sesuai template' : 'Kolom belum sesuai template'); ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal<?php echo e($batch->id); ?>">
                                                            Review
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
                                
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingImportBatches->hasPages()): ?>
                        <div class="card-footer">
                            <div class="sky-pagination-wrap">
                                <?php echo e($pendingImportBatches->links('pagination::bootstrap-5')); ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card h-100">
                <button class="sky-collapse-trigger" type="button" data-bs-toggle="collapse" data-bs-target="#syncedBatchPanel" aria-expanded="true" aria-controls="syncedBatchPanel">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 grow me-3">
                        <div>
                            <div class="sky-panel-label mb-1">Batch Tersinkron</div>
                            <h6 class="mb-0">Import yang sudah masuk ke aplikasi</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($syncedImportBatches->total()); ?> batch • <?php echo e($syncedImportBatchSchoolCount); ?> sekolah</span>
                    </div>
                    <i class="mdi mdi-chevron-down sky-collapse-icon"></i>
                </button>

                <div id="syncedBatchPanel" class="collapse show sky-collapse-shell">
                    <div class="card-body">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($syncedImportBatches->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Sekolah</th>
                                            <th>Tersinkron</th>
                                            <th>Data Sinkron</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $syncedImportBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <?php
                                                $matchedValidRowsCount = $batch->rows
                                                    ->filter(fn ($row) => $row->is_valid && $row->matched_user_id)
                                                    ->unique('matched_user_id')
                                                    ->count();
                                                $displaySubmissionCount = $batch->requests_count > 0
                                                    ? $batch->requests_count
                                                    : $matchedValidRowsCount;
                                            ?>
                                            <tr>
                                                <td>
                                                    <span class="sky-data-primary"><?php echo e($batch->madrasah?->name ?? '-'); ?></span>
                                                </td>
                                                <td>
                                                    <span class="sky-data-primary"><?php echo e($batch->reviewer?->name ?? '-'); ?></span>
                                                    <span class="sky-data-secondary"><?php echo e(optional($batch->synced_at)->format('d/m/Y H:i') ?? '-'); ?></span>
                                                </td>
                                                <td>
                                                    <span class="sky-data-primary"><?php echo e(number_format($displaySubmissionCount)); ?> pengajuan</span>
                                                    <span class="sky-data-secondary"><?php echo e(number_format($batch->valid_rows)); ?> dari <?php echo e(number_format($batch->total_rows)); ?> baris valid</span>
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#importBatchModal<?php echo e($batch->id); ?>">
                                                        Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="sky-empty-state py-5">
                                <i class="bx bx-check-shield"></i>
                                <strong>Belum ada batch tersinkron</strong>
                                <small>Batch yang sudah berhasil disinkronkan ke aplikasi akan tampil di sini.</small>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($syncedImportBatches->hasPages()): ?>
                        <div class="card-footer">
                            <div class="sky-pagination-wrap">
                                <?php echo e($syncedImportBatches->links('pagination::bootstrap-5')); ?>

                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <button class="sky-collapse-trigger" type="button" data-bs-toggle="collapse" data-bs-target="#submissionListPanel" aria-expanded="false" aria-controls="submissionListPanel">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 grow me-3">
                <div>
                    <div class="sky-panel-label mb-1">Daftar Pengajuan</div>
                    <h6 class="mb-0">Semua pengajuan yang masuk ke yayasan, termasuk yang ditolak</h6>
                </div>
                <span class="sky-chip"><?php echo e(number_format($submissions->total())); ?> pengajuan</span>
            </div>
            <i class="mdi mdi-chevron-down sky-collapse-icon"></i>
        </button>

        <div id="submissionListPanel" class="collapse sky-collapse-shell">
            <div class="card-body">
                <form method="GET" class="sky-filter-shell mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-5">
                            <label class="form-label mb-1">Cari sekolah, pegawai, atau nomor surat</label>
                            <input
                                type="text"
                                name="q"
                                value="<?php echo e(request('q')); ?>"
                                class="form-control"
                                placeholder="Contoh: SMA Ma'arif, Ahmad, atau nomor surat">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label mb-1">Sekolah</label>
                            <select name="madrasah_id" class="form-select">
                                <option value="">Semua sekolah</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($madrasah->id); ?>" <?php if((string) request('madrasah_id') === (string) $madrasah->id): echo 'selected'; endif; ?>><?php echo e($madrasah->name); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label mb-1">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua status</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($value); ?>" <?php if(request('status') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                                <a href="<?php echo e(route('sk-yayasan.pengajuan.index')); ?>" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submissions->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Pengajuan</th>
                                    <th>Pegawai</th>
                                    <th>Sekolah</th>
                                    <th>Surat Pengajuan</th>
                                    <th>Status</th>
                                    <th>Template</th>
                                    <th>Catatan Review</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <?php
                                        $submissionBadge = $statusBadgeMap[$submission->current_status] ?? ['color' => 'secondary', 'label' => ucfirst(str_replace('_', ' ', (string) $submission->current_status))];
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="sky-data-primary"><?php echo e($submission->request_number); ?></span>
                                            <span class="sky-data-secondary">Masuk <?php echo e(optional($submission->submitted_at)->format('d/m/Y H:i') ?? '-'); ?></span>
                                        </td>
                                        <td>
                                            <span class="sky-data-primary"><?php echo e($submission->employee?->name ?? '-'); ?></span>
                                            <span class="sky-data-secondary"><?php echo e($submission->employee?->statusKepegawaian?->name ?? ($submission->employee?->ketugasan ?? 'Status belum tersedia')); ?></span>
                                        </td>
                                        <td>
                                            <span class="sky-data-primary"><?php echo e($submission->madrasah?->name ?? '-'); ?></span>
                                            <span class="sky-data-secondary">Dikirim oleh <?php echo e($submission->submitter?->name ?? '-'); ?></span>
                                        </td>
                                        <td>
                                            <span class="sky-data-primary"><?php echo e($submission->submission_letter_number ?? '-'); ?></span>
                                            <span class="sky-data-secondary"><?php echo e(optional($submission->submission_letter_date)->translatedFormat('d M Y') ?? 'Tanggal belum diisi'); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($submissionBadge['color']); ?>-subtle text-<?php echo e($submissionBadge['color']); ?>"><?php echo e($submissionBadge['label']); ?></span>
                                            <span class="sky-data-secondary">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->reviewer): ?>
                                                    Review oleh <?php echo e($submission->reviewer->name); ?><?php echo e($submission->reviewed_at ? ' • ' . $submission->reviewed_at->format('d/m/Y H:i') : ''); ?>

                                                <?php else: ?>
                                                    Menunggu review yayasan
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="sky-data-primary"><?php echo e($submission->template?->name ?? 'Belum dipilih'); ?></span>
                                            <span class="sky-data-secondary"><?php echo e($submission->document ? 'Draft/PDF tersedia' : 'Belum ada dokumen'); ?></span>
                                        </td>
                                        <td>
                                            <span class="sky-data-secondary mt-0"><?php echo e(\Illuminate\Support\Str::limit($submission->review_notes ?: 'Belum ada catatan review.', 110)); ?></span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->document): ?>
                                                    <a href="<?php echo e(route('sk-yayasan.documents.download', $submission->document)); ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">
                                                        PDF
                                                    </a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal<?php echo e($submission->id); ?>">
                                                    Review
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="sky-empty-state py-5">
                        <i class="bx bx-search-alt"></i>
                        <strong>Tidak ada pengajuan yang sesuai filter</strong>
                        <small>Coba ubah kata kunci, sekolah, atau status termasuk filter ditolak untuk melihat data lain.</small>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submissions->hasPages()): ?>
                <div class="card-footer">
                    <div class="sky-pagination-wrap">
                        <?php echo e($submissions->links('pagination::bootstrap-5')); ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="card mt-4">
        <button class="sky-collapse-trigger" type="button" data-bs-toggle="collapse" data-bs-target="#schoolMonitoringPanel" aria-expanded="false" aria-controls="schoolMonitoringPanel">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 grow me-3">
                <div>
                    <div class="sky-panel-label mb-1">Monitoring Sekolah</div>
                    <h6 class="mb-0">Status ringkas pengajuan per sekolah</h6>
                </div>
                <span class="sky-chip"><?php echo e(count($schoolSubmissionSummaryRows)); ?> sekolah</span>
            </div>
            <i class="mdi mdi-chevron-down sky-collapse-icon"></i>
        </button>

        <div id="schoolMonitoringPanel" class="collapse sky-collapse-shell">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle sky-compact-table">
                        <thead>
                            <tr>
                                <th>SCOD</th>
                                <th>Sekolah</th>
                                <th>Status</th>
                                <th>Pengajuan</th>
                                <th>Batch Aktif</th>
                                <th>Batch Terakhir</th>
                                <th>Ditolak</th>
                                <th>Belum Match</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $schoolSubmissionSummaryRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php
                                    $schoolStatusBadge = match ($row['submission_status_label']) {
                                        'Sudah Mengajukan' => ['color' => 'success', 'label' => 'Sudah Mengajukan'],
                                        'Ditolak' => ['color' => 'danger', 'label' => 'Ditolak'],
                                        default => ['color' => 'secondary', 'label' => 'Belum Mengajukan'],
                                    };
                                    $latestBatchBadge = $batchStatusBadgeMap[$row['latest_batch_status'] ?? ''] ?? ['color' => 'secondary', 'label' => 'Belum ada batch'];
                                    $hasRejectedHistory = ($row['rejected_requests_count'] ?? 0) > 0 || ($row['rejected_batch_count'] ?? 0) > 0;
                                ?>
                                <tr>
                                    <td><?php echo e($row['scod'] ?: '-'); ?></td>
                                    <td>
                                        <span class="sky-data-primary"><?php echo e($row['school_name'] ?: '-'); ?></span>
                                        <span class="sky-data-secondary"><?php echo e($row['kabupaten'] ?: '-'); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($schoolStatusBadge['color']); ?>-subtle text-<?php echo e($schoolStatusBadge['color']); ?>"><?php echo e($schoolStatusBadge['label']); ?></span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasRejectedHistory && $row['submission_status_label'] !== 'Ditolak'): ?>
                                            <span class="badge bg-danger-subtle text-danger mt-1">Ada Riwayat Ditolak</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td><?php echo e(number_format($row['total_requests'])); ?></td>
                                    <td><?php echo e(number_format($row['active_batch_count'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($latestBatchBadge['color']); ?>-subtle text-<?php echo e($latestBatchBadge['color']); ?>"><?php echo e($latestBatchBadge['label']); ?></span>
                                        <span class="sky-data-secondary"><?php echo e(optional($row['latest_batch_uploaded_at'])->format('d/m/Y H:i') ?: 'Belum ada upload'); ?></span>
                                    </td>
                                    <td>
                                        <span class="sky-data-primary"><?php echo e(number_format($row['rejected_requests_count'] ?? 0)); ?> pengajuan</span>
                                        <span class="sky-data-secondary"><?php echo e(number_format($row['rejected_batch_count'] ?? 0)); ?> batch ditolak</span>
                                    </td>
                                    <td><?php echo e(number_format($row['latest_batch_unmatched_count'])); ?></td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="sky-empty-state py-4">
                                            <i class="bx bx-buildings"></i>
                                            <strong>Belum ada data sekolah</strong>
                                            <small>Ringkasan sekolah akan tampil otomatis saat data pengajuan tersedia.</small>
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

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($batch->invalid_rows > 0): ?>
                            <div class="sky-inline-note sky-inline-note-danger mb-3">
                                Kolom dengan warna merah menandakan data itu masih perlu diperbaiki.
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <form method="POST" action="<?php echo e(route('sk-yayasan.import-batches.rows.update', $batch)); ?>" id="editImportBatchForm<?php echo e($batch->id); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <input type="hidden" name="action" value="save" data-sync-action>
                            <div class="sky-table-actions">
                                <div class="text-muted small">
                                    Pilih satu atau beberapa baris untuk dihapus dari batch ini sebelum disimpan.
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-delete-selected-rows>
                                    Hapus Baris Terpilih
                                </button>
                            </div>
                            <div class="sky-modal-table-wrap">
                                <table class="table table-sm align-middle sky-compact-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="sky-row-select-col">
                                                <input type="checkbox" class="form-check-input" data-select-all-rows>
                                            </th>
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
                                                <td class="sky-row-select-col">
                                                    <input type="checkbox" class="form-check-input" data-row-select>
                                                </td>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($batch->status === 'pending_review'): ?>
                            <div class="w-100">
                                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                                    <div class="text-muted small">
                                        Data pada tabel bisa diedit langsung. Simpan dulu agar status validasi dan match user diperbarui.
                                    </div>
                                    <button type="submit"
                                            form="editImportBatchForm<?php echo e($batch->id); ?>"
                                            class="btn btn-outline-primary"
                                            onclick="this.form.querySelector('[data-sync-action]').value='save'">
                                        Simpan Perubahan Tabel
                                    </button>
                                </div>

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
                            </div>
                        <?php elseif($batch->status === 'rejected'): ?>
                            <div class="d-flex flex-wrap justify-content-between gap-2 w-100">
                                <button type="submit"
                                        form="editImportBatchForm<?php echo e($batch->id); ?>"
                                        class="btn btn-outline-primary"
                                        onclick="this.form.querySelector('[data-sync-action]').value='save'">
                                    Simpan & Kembalikan ke Pending Review
                                </button>
                                <div class="d-flex flex-wrap gap-2 ms-auto">
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
                            </div>
                        <?php elseif($batch->status === 'synced'): ?>
                            <div class="w-100">
                                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                                    <div class="text-muted small">
                                        Data yang sudah tersinkron tetap bisa diedit. Simpan perubahan lalu lakukan sinkronisasi ulang agar perubahan diterapkan kembali ke aplikasi.
                                    </div>
                                    <button type="submit"
                                            form="editImportBatchForm<?php echo e($batch->id); ?>"
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
                                              form="editImportBatchForm<?php echo e($batch->id); ?>"
                                              placeholder="Catatan opsional untuk sinkronisasi ulang batch ini."></textarea>
                                </div>
                                <div class="d-flex flex-wrap justify-content-end gap-2">
                                    <button type="submit"
                                            form="editImportBatchForm<?php echo e($batch->id); ?>"
                                            class="btn btn-primary"
                                            onclick="this.form.querySelector('[data-sync-action]').value='sync'"
                                            <?php if(!$batch->headings_valid || $batch->invalid_rows > 0): echo 'disabled'; endif; ?>>
                                        <i class="bx bx-refresh me-1"></i>Sinkronisasi Ulang
                                    </button>
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="d-flex justify-content-end w-100">
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

<?php $__env->startSection('script'); ?>
<script>
    document.addEventListener('change', function (event) {
        if (event.target.matches('[data-select-all-rows]')) {
            const table = event.target.closest('table');

            if (!table) {
                return;
            }

            table.querySelectorAll('[data-row-select]').forEach(function (checkbox) {
                checkbox.checked = event.target.checked;
            });
        }

        if (event.target.matches('[data-row-select]')) {
            const table = event.target.closest('table');

            if (!table) {
                return;
            }

            const rowCheckboxes = Array.from(table.querySelectorAll('[data-row-select]'));
            const checkedCount = rowCheckboxes.filter(function (checkbox) {
                return checkbox.checked;
            }).length;
            const selectAllCheckbox = table.querySelector('[data-select-all-rows]');

            if (!selectAllCheckbox) {
                return;
            }

            selectAllCheckbox.checked = rowCheckboxes.length > 0 && checkedCount === rowCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < rowCheckboxes.length;
        }
    });

    document.addEventListener('click', function (event) {
        const deleteButton = event.target.closest('[data-delete-selected-rows]');

        if (!deleteButton) {
            return;
        }

        const modalContent = deleteButton.closest('.modal-content');
        const table = modalContent ? modalContent.querySelector('table') : null;

        if (!table) {
            return;
        }

        const selectedRows = Array.from(table.querySelectorAll('[data-row-select]:checked'));
        const totalRows = table.querySelectorAll('[data-row-select]').length;

        if (selectedRows.length === 0) {
            alert('Pilih minimal satu baris yang ingin dihapus.');
            return;
        }

        if (selectedRows.length === totalRows) {
            alert('Minimal satu baris harus tetap tersisa di dalam batch.');
            return;
        }

        if (!window.confirm('Hapus semua baris yang dipilih dari batch ini?')) {
            return;
        }

        selectedRows.forEach(function (checkbox) {
            const row = checkbox.closest('tr');

            if (row) {
                row.remove();
            }
        });

        const selectAllCheckbox = table.querySelector('[data-select-all-rows]');

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/pengajuan-index.blade.php ENDPATH**/ ?>