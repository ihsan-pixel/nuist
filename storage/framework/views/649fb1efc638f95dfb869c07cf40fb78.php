<?php $__env->startSection('title'); ?>Perpanjangan SK Yayasan <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/libs/select2/css/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Perpanjangan SK <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

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
                                        <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'excel'])); ?>" class="btn btn-sm btn-outline-primary">Excel</a>
                                        <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'fakta_integritas'])); ?>" class="btn btn-sm btn-outline-primary">Pakta Integritas</a>
                                        <a href="<?php echo e(route('sk-yayasan.import-batches.attachments.download', [$batch, 'penilaian_perilaku'])); ?>" class="btn btn-sm btn-outline-primary">Penilaian Perilaku</a>
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
                            <h6 class="mb-0">Status dan perkembangan pengajuan</h6>
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

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submissions->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No Pengajuan</th>
                                        <th>Surat Pengajuan</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Catatan Review</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td class="fw-semibold"><?php echo e($submission->request_number); ?></td>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($submission->submission_letter_number ?? '-'); ?></div>
                                                <small class="text-muted"><?php echo e(optional($submission->submission_letter_date)->translatedFormat('d M Y') ?? '-'); ?></small>
                                            </td>
                                            <td>
                                                <div class="fw-semibold"><?php echo e($submission->employee?->name ?? '-'); ?></div>
                                                <small class="text-muted"><?php echo e($submission->employee?->statusKepegawaian?->name ?? ($submission->employee?->ketugasan ?? '-')); ?></small>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->importBatch): ?>
                                                    <div><small class="text-muted">Dari file import</small></div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-secondary text-uppercase"><?php echo e($submission->current_status); ?></span>
                                            </td>
                                            <td><?php echo e($submission->review_notes ?? '-'); ?></td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->document): ?>
                                                    <a href="<?php echo e(route('sk-yayasan.documents.download', $submission->document)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        Unduh SK
                                                    </a>
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

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submissions->hasPages()): ?>
                    <div class="card-footer bg-white">
                        <?php echo e($submissions->links()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/select2/js/select2.min.js')); ?>"></script>
<script>
    $(document).ready(function () {
        const $employeeSelect = $('.select2-pegawai');

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
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/sekolah-index.blade.php ENDPATH**/ ?>