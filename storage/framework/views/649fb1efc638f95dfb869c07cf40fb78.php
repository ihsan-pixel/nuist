<?php $__env->startSection('title'); ?>Perpanjangan SK Yayasan <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/libs/select2/css/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
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
        min-height: 36px;
        padding: .35rem .55rem;
    }

    .sky-edit-cell-sm {
        min-width: 88px;
    }

    .sky-upload-actions {
        position: relative;
        z-index: 2;
    }

    .sky-upload-actions .btn {
        cursor: pointer;
        pointer-events: auto;
    }

    .sky-admin-import-modal {
        background: rgba(15, 23, 42, 0.48);
    }

    .sky-admin-import-modal .modal-dialog {
        margin: 1.75rem auto;
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
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Perpanjangan SK <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
    $keteranganOptions = \App\Support\SkYayasanImportSynchronizer::allowedKeteranganOptions();
    $importPreviewColumns = \App\Support\SkYayasanImportSynchronizer::expectedHeadings();
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
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                
                <h4 class="mb-1">Ajukan dan pantau SK guru atau pegawai</h4>
                
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($submissions->total()); ?> total pengajuan</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($publishedDocuments->count()); ?> SK terbaru</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Diajukan</div>
                <div class="h4 mb-0"><?php echo e($statusCounts['submitted'] ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Direview</div>
                <div class="h4 mb-0"><?php echo e($statusCounts['reviewed'] ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Disetujui</div>
                <div class="h4 mb-0"><?php echo e($statusCounts['approved'] ?? 0); ?></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card sky-stat-card p-3 h-100">
                <div class="text-muted small">Terbit</div>
                <div class="h4 mb-0"><?php echo e($statusCounts['published'] ?? 0); ?></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$hasExistingSchoolSubmission): ?>
                        <div class="sky-panel-label mb-1">Form Pengajuan</div>
                        <h6 class="mb-3">Pilih guru/pegawai dan lengkapi berkas pengajuan</h6>
                        <p class="text-muted small mb-3">
                            Pengajuan perpanjangan SK harus menyertakan file Excel data tenaga pendidik, file Pakta integritas, dan file form penilaian perilaku kinerja pegawai.
                        </p>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($latestSyncedImport): ?>
                            <div class="sky-inline-note sky-inline-note-success py-2 px-3 small mb-3">
                                Sinkronisasi terakhir sudah berhasil. Nama guru pada form pengajuan di bawah dipilih otomatis dari data import ini.
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <form action="<?php echo e(route('sk-yayasan.sekolah.store')); ?>" method="POST" enctype="multipart/form-data" class="mb-3">
                            <?php echo csrf_field(); ?>
                            <div class="row g-3 mb-3">
                                <div class="col-md-7">
                                    <label class="form-label">Nomor Surat Pengajuan</label>
                                    <input type="text" name="submission_letter_number" class="form-control" value="<?php echo e(old('submission_letter_number')); ?>" placeholder="Contoh: 421.5/SMK-PD/VI/2026" required>
                                    <small class="text-muted">Nomor surat dari sekolah yang menjadi dasar pengajuan ke Yayasan.</small>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['submission_letter_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger d-block"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Tanggal Surat Pengajuan</label>
                                    <input type="date" name="submission_letter_date" class="form-control" value="<?php echo e(old('submission_letter_date')); ?>" required>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['submission_letter_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger d-block"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <label class="form-label mb-0">Guru/Pegawai</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="select-all-employees">
                                            Pilih Semua
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-all-employees">
                                            Kosongkan
                                        </button>
                                    </div>
                                </div>
                                <select name="employee_ids[]" class="form-select select2-pegawai" multiple required data-placeholder="Pilih satu atau lebih pegawai">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($employee->id); ?>" <?php if(in_array($employee->id, $autoSelectedEmployeeIds ?? [])): echo 'selected'; endif; ?>>
                                            <?php echo e($employee->name); ?> - <?php echo e($employee->statusKepegawaian?->name ?? ($employee->ketugasan ?? '-')); ?>

                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                                <small class="text-muted">
                                    Bisa pilih lebih dari satu pegawai sekaligus.
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($autoSelectedEmployeeIds)): ?>
                                        Data hasil import terakhir sudah dipilih otomatis.
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </small>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['employee_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger d-block"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['employee_ids.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <label class="form-label mb-0">File Excel Data Tenaga Pendidik</label>
                                    <a href="<?php echo e(route('sk-yayasan.sekolah.template-import')); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-file-excel-outline me-1"></i> Template Import
                                    </a>
                                </div>
                                <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                <small class="text-muted">Format: XLSX, XLS, atau CSV.</small>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['excel_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger d-block"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <label class="form-label mb-0">File Pakta Integritas</label>
                                    <a href="<?php echo e(asset('templates/sk-yayasan/contoh-template-pakta-integritas.pdf')); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="mdi mdi-file-download-outline me-1"></i> Contoh File
                                    </a>
                                </div>
                                <input type="file" name="fakta_integritas_file" class="form-control" accept=".pdf,application/pdf" required>
                                <small class="text-muted">Format: PDF.</small>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['fakta_integritas_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger d-block"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <label class="form-label mb-0">File Form Penilaian Perilaku Kinerja Pegawai</label>
                                    <a href="<?php echo e(asset('templates/sk-yayasan/contoh-template-form-penilaian-kinerja.pdf')); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="mdi mdi-file-download-outline me-1"></i> Contoh File
                                    </a>
                                </div>
                                <input type="file" name="penilaian_perilaku_file" class="form-control" accept=".pdf,application/pdf" required>
                                <small class="text-muted">Format: PDF.</small>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['penilaian_perilaku_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger d-block"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Kirim Pengajuan</button>
                        </form>
                    <?php else: ?>
                        <div class="sky-panel-label mb-1">Form Pengajuan</div>
                        <h6 class="mb-3">Pengajuan baru dinonaktifkan</h6>
                        <div class="sky-inline-note sky-inline-note-warning mb-3">
                            Sekolah ini sudah memiliki pengajuan SK Yayasan. Form pengajuan baru disembunyikan agar setiap sekolah hanya dapat upload satu kali.
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($latestSchoolSubmissionBatch): ?>
                            <div class="sky-inline-note sky-inline-note-secondary small mb-3">
                                Batch terakhir:
                                <strong><?php echo e($latestSchoolSubmissionBatch->original_filename); ?></strong>
                                dengan status
                                <strong><?php echo e(str_replace('_', ' ', $latestSchoolSubmissionBatch->status)); ?></strong>.
                                Gunakan bagian riwayat upload di bawah untuk meninjau atau memperbarui data.
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($importBatches->isNotEmpty()): ?>
                        <div class="border-top pt-3">
                            <div class="sky-panel-label mb-1">Riwayat Upload</div>
                            <h6 class="mb-3">Status review dan sinkronisasi file</h6>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php
                                    $batchColor = $batch->status === 'synced' ? 'success' : ($batch->status === 'rejected' ? 'danger' : 'warning');
                                ?>
                                <div class="sky-document-card mb-3">
                                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
                                        <div>
                                            <div class="fw-semibold"><?php echo e($batch->original_filename); ?></div>
                                            <div class="sky-document-meta">
                                                Upload <?php echo e(optional($batch->uploaded_at)->format('d/m/Y H:i')); ?> |
                                                <?php echo e($batch->valid_rows); ?> valid / <?php echo e($batch->invalid_rows); ?> perlu cek
                                            </div>
                                        </div>
                                        <span class="badge bg-<?php echo e($batchColor); ?>-subtle text-<?php echo e($batchColor); ?> text-uppercase">
                                            <?php echo e(str_replace('_', ' ', $batch->status)); ?>

                                        </span>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'excel'])); ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat Excel</a>
                                        <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'fakta_integritas'])); ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat Pakta Integritas</a>
                                        <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'penilaian_perilaku'])); ?>" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">Lihat Penilaian Perilaku</a>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$batch->headings_valid): ?>
                                        <div class="small text-danger mb-2">
                                            Format kolom tidak sesuai template.
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($batch->missing_headings)): ?>
                                                Kolom kurang: <?php echo e(implode(', ', $batch->missing_headings)); ?>.
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($batch->review_notes): ?>
                                        <div class="small mb-2"><strong>Catatan review:</strong> <?php echo e($batch->review_notes); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($batch->status, ['pending_review', 'rejected'])): ?>
                                        <div class="d-flex flex-wrap gap-2 mb-2 sky-upload-actions">
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    onclick="return window.skyOpenModal('#editImportBatchRowsModal<?php echo e($batch->id); ?>')"
                                                    data-sky-open-modal="#editImportBatchRowsModal<?php echo e($batch->id); ?>">
                                                Edit Data Import
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return window.skyOpenModal('#updateRejectedBatchModal<?php echo e($batch->id); ?>')"
                                                    data-sky-open-modal="#updateRejectedBatchModal<?php echo e($batch->id); ?>">
                                                Perbarui Berkas
                                            </button>
                                            <small class="text-muted align-self-center">Edit isi data Excel atau perbarui file/lampiran sebelum atau sesudah review Yayasan.</small>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($batch->reviewer): ?>
                                        <div class="small text-muted">Direview oleh <?php echo e($batch->reviewer->name); ?> pada <?php echo e(optional($batch->reviewed_at)->format('d/m/Y H:i')); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Riwayat Pengajuan</div>
                            <h6 class="mb-0">Status pengajuan berdasarkan batch upload</h6>
                        </div>
                        <form method="GET" class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="">Semua status</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = ['submitted' => 'Diajukan', 'reviewed' => 'Direview', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'published' => 'Terbit']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($value); ?>" <?php if(request('status') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                        </form>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submissionHistoryBatches->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>File Upload</th>
                                        <th>Surat Pengajuan</th>
                                        <th>Data Diupload</th>
                                        <th>Status</th>
                                        <th>Catatan Review</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $submissionHistoryBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php
                                            $batchRequests = $batch->requests;
                                            $firstRequest = $batchRequests->first();
                                            $publishedRequests = $batchRequests->filter(fn ($requestItem) => $requestItem->document);
                                            $requestStatusCounts = $batchRequests
                                                ->pluck('current_status')
                                                ->filter()
                                                ->countBy();

                                            if ($batch->status === 'rejected' || ($requestStatusCounts['rejected'] ?? 0) > 0) {
                                                $historyBadge = ['color' => 'danger', 'label' => 'Ditolak'];
                                            } elseif (($requestStatusCounts['published'] ?? 0) > 0) {
                                                $historyBadge = ['color' => 'success', 'label' => ($requestStatusCounts->count() === 1 ? 'Terbit' : 'Terbit Sebagian')];
                                            } elseif (($requestStatusCounts['approved'] ?? 0) > 0) {
                                                $historyBadge = ['color' => 'primary', 'label' => 'Disetujui'];
                                            } elseif (($requestStatusCounts['reviewed'] ?? 0) > 0) {
                                                $historyBadge = ['color' => 'info', 'label' => 'Direview'];
                                            } elseif ($batch->status === 'synced') {
                                                $historyBadge = ['color' => 'success', 'label' => 'Tersinkron'];
                                            } else {
                                                $historyBadge = ['color' => 'warning', 'label' => 'Diajukan'];
                                            }

                                            $employeeNames = $batchRequests
                                                ->pluck('employee.name')
                                                ->filter()
                                                ->values();
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($batch->original_filename); ?></div>
                                                <small class="text-muted"><?php echo e(optional($batch->uploaded_at)->format('d/m/Y H:i') ?? '-'); ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($firstRequest?->submission_letter_number ?? '-'); ?></div>
                                                <small class="text-muted"><?php echo e(optional($firstRequest?->submission_letter_date)->translatedFormat('d M Y') ?? '-'); ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($batchRequests->count()); ?> pegawai</div>
                                                <small class="text-muted">
                                                    <?php echo e($employeeNames->take(2)->implode(', ') ?: '-'); ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employeeNames->count() > 2): ?>
                                                        +<?php echo e($employeeNames->count() - 2); ?> lainnya
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <span class="badge bg-<?php echo e($historyBadge['color']); ?>-subtle text-<?php echo e($historyBadge['color']); ?> text-uppercase align-self-start">
                                                        <?php echo e($historyBadge['label']); ?>

                                                    </span>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requestStatusCounts->isNotEmpty()): ?>
                                                        <small class="text-muted">
                                                            <?php echo e(collect([
                                                                ($requestStatusCounts['submitted'] ?? 0) ? (($requestStatusCounts['submitted'] ?? 0) . ' diajukan') : null,
                                                                ($requestStatusCounts['reviewed'] ?? 0) ? (($requestStatusCounts['reviewed'] ?? 0) . ' direview') : null,
                                                                ($requestStatusCounts['approved'] ?? 0) ? (($requestStatusCounts['approved'] ?? 0) . ' disetujui') : null,
                                                                ($requestStatusCounts['published'] ?? 0) ? (($requestStatusCounts['published'] ?? 0) . ' terbit') : null,
                                                            ])->filter()->implode(' • ')); ?>

                                                        </small>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?php echo e($batch->review_notes ?? $firstRequest?->review_notes ?? '-'); ?></td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($publishedRequests->isNotEmpty()): ?>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $publishedRequests->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $publishedRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                            <a href="<?php echo e(route('sk-yayasan.documents.download', $publishedRequest->document)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                                SK <?php echo e(\Illuminate\Support\Str::limit($publishedRequest->employee?->name ?? 'Pegawai', 14)); ?>

                                                            </a>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($publishedRequests->count() > 2): ?>
                                                            <span class="text-muted small align-self-center">+<?php echo e($publishedRequests->count() - 2); ?> SK lainnya</span>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small">Menunggu proses Yayasan</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-task-x"></i>
                            <strong>Belum ada pengajuan perpanjangan SK</strong>
                            <small>Silakan kirim pengajuan pertama Anda melalui form di sebelah kiri.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submissionHistoryBatches->hasPages()): ?>
                    <div class="card-footer bg-white">
                        <div class="sky-pagination-wrap">
                            <?php echo e($submissionHistoryBatches->links('pagination::bootstrap-5')); ?>

                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importBatches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $batch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
    <?php
        $batchSubmission = $batch->requests->first();
    ?>

    <?php if(in_array($batch->status, ['pending_review', 'rejected']) && $batchSubmission): ?>
        <div class="modal fade sky-admin-import-modal" id="editImportBatchRowsModal<?php echo e($batch->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-xl-down modal-xl">
                <form action="<?php echo e(route('sk-yayasan.sekolah.import-batches.rows.update', $batch)); ?>" method="POST" class="modal-content">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Edit Data Import</h5>
                            <div class="sky-file-meta"><?php echo e($batch->original_filename); ?> - upload <?php echo e(optional($batch->uploaded_at)->format('d/m/Y H:i') ?? '-'); ?></div>
                        </div>
                        <button type="button" class="btn-close" data-sky-close-modal></button>
                    </div>
                    <div class="modal-body">
                        <div class="sky-inline-note sky-inline-note-warning mb-3">
                            Perubahan pada tabel ini akan mengganti data hasil upload Excel untuk batch ini. Setelah disimpan, batch kembali ke antrean review Yayasan.
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($batch->invalid_rows > 0): ?>
                            <div class="sky-inline-note sky-inline-note-danger mb-3">
                                Kolom dengan warna merah menandakan data itu masih perlu diperbaiki.
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($batch->review_notes): ?>
                            <div class="sky-inline-note sky-inline-note-secondary mb-3">
                                <strong>Catatan review terakhir:</strong> <?php echo e($batch->review_notes); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="row g-2 mb-3">
                            <div class="col-md-3 col-6">
                                <div class="sky-mini-stat">
                                    <div class="label">Status</div>
                                    <div class="value text-capitalize"><?php echo e(str_replace('_', ' ', $batch->status)); ?></div>
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
                            <div class="col-md-3 col-6">
                                <div class="sky-mini-stat">
                                    <div class="label">Data Pegawai</div>
                                    <div class="value"><?php echo e($batch->requests->count()); ?></div>
                                </div>
                            </div>
                        </div>

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
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $importPreviewColumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <?php
                                                    $field = $importPreviewFieldMap[$column] ?? null;
                                                    $value = $field ? data_get($row, $field, '') : '';
                                                    $value = $value === '-' ? '' : $value;
                                                    $hasFieldError = $field && in_array($field, $rowErrorFields, true);
                                                ?>
                                                <td class="sky-edit-cell <?php echo e($column === 'No' ? 'sky-edit-cell-sm' : ''); ?> <?php echo e($hasFieldError ? 'sky-cell-error' : ''); ?>">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loop->first): ?>
                                                        <input type="hidden" name="rows[<?php echo e($loop->parent->index); ?>][row_number]" value="<?php echo e($row->row_number); ?>">
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                            <td class="wrap"><?php echo e(!empty($row->validation_errors) ? implode(' ', $row->validation_errors) : 'Data siap direview.'); ?></td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-sky-close-modal>Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Data Import</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade sky-admin-import-modal" id="updateRejectedBatchModal<?php echo e($batch->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <form action="<?php echo e(route('sk-yayasan.sekolah.import-batches.update', $batch)); ?>" method="POST" enctype="multipart/form-data" class="modal-content">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Perbarui Berkas Pengajuan</h5>
                        <button type="button" class="btn-close" data-sky-close-modal></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning py-2 px-3 small">
                            Perbarui file yang perlu diganti. Kosongkan file yang tidak ingin diubah. Setelah disimpan, batch akan masuk atau tetap berada di antrean review Yayasan.
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label class="form-label">Nomor Surat Pengajuan</label>
                                <input type="text" name="submission_letter_number" class="form-control" value="<?php echo e(old('submission_letter_number', $batchSubmission->submission_letter_number)); ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Tanggal Surat Pengajuan</label>
                                <input type="date" name="submission_letter_date" class="form-control" value="<?php echo e(old('submission_letter_date', optional($batchSubmission->submission_letter_date)->format('Y-m-d'))); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">File Excel Data Tenaga Pendidik</label>
                            <input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls,.csv">
                            <small class="text-muted">File saat ini: <?php echo e($batch->original_filename); ?>. Upload file baru jika data Excel direvisi.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">File Pakta Integritas</label>
                            <input type="file" name="fakta_integritas_file" class="form-control" accept=".pdf,application/pdf">
                            <small class="text-muted">File saat ini: <?php echo e($batch->fakta_integritas_filename ?? '-'); ?>.</small>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">File Form Penilaian Perilaku Kinerja Pegawai</label>
                            <input type="file" name="penilaian_perilaku_file" class="form-control" accept=".pdf,application/pdf">
                            <small class="text-muted">File saat ini: <?php echo e($batch->penilaian_perilaku_filename ?? '-'); ?>.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-sky-close-modal>Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan & Kirim Ulang</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/select2/js/select2.min.js')); ?>"></script>
<script>
    window.skyOpenModal = function (target) {
        const modalElement = target ? document.querySelector(target) : null;

        if (!modalElement) {
            return false;
        }

        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        modalElement.removeAttribute('aria-hidden');
        modalElement.setAttribute('aria-modal', 'true');
        document.body.classList.add('modal-open');

        return false;
    };

    window.skyCloseModal = function (target) {
        const modalElement = typeof target === 'string'
            ? document.querySelector(target)
            : target;

        if (!modalElement) {
            return false;
        }

        modalElement.classList.remove('show');
        modalElement.setAttribute('aria-hidden', 'true');
        modalElement.removeAttribute('aria-modal');
        modalElement.style.display = 'none';
        document.body.classList.remove('modal-open');

        return false;
    };

    $(document).ready(function () {
        const $employeeSelect = $('.select2-pegawai');

        document.querySelectorAll('.sky-admin-import-modal').forEach(function (modalElement) {
            if (modalElement.parentElement !== document.body) {
                document.body.appendChild(modalElement);
            }
        });

        $employeeSelect.select2({
            width: '100%',
            placeholder: $employeeSelect.data('placeholder'),
            closeOnSelect: false
        });

        $('#select-all-employees').on('click', function () {
            const allValues = $employeeSelect.find('option').map(function () {
                return $(this).val();
            }).get();

            $employeeSelect.val(allValues).trigger('change');
        });

        $('#clear-all-employees').on('click', function () {
            $employeeSelect.val(null).trigger('change');
        });

        $(document).on('click', '[data-sky-open-modal]', function (event) {
            event.preventDefault();

            const target = $(this).data('sky-open-modal');
            window.skyOpenModal(target);
        });

        $(document).on('click', '[data-sky-close-modal]', function (event) {
            event.preventDefault();
            window.skyCloseModal($(this).closest('.sky-admin-import-modal').get(0));
        });

        $(document).on('click', '.sky-admin-import-modal', function (event) {
            if (event.target === this) {
                window.skyCloseModal(this);
            }
        });

        $(document).on('keydown', function (event) {
            if (event.key === 'Escape') {
                const activeModal = document.querySelector('.sky-admin-import-modal.show');

                if (activeModal) {
                    window.skyCloseModal(activeModal);
                }
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/sekolah-index.blade.php ENDPATH**/ ?>