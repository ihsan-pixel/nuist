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
                    <h4 class="mb-1">Antrean generate per sekolah</h4>
                    <p class="mb-0 text-white-50">
                    Pilih nama sekolah untuk melihat daftar pengajuan SK Yayasan yang sudah tersinkronisasi dan siap dibuat draft PDF sesuai template masing-masing. Urutan sekolah mengikuti SCOD dari yang terendah ke tertinggi.
                    </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($schools->count()); ?> sekolah</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($syncedBatchCount); ?> batch tersinkron</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($totalRequestsCount); ?> pengajuan</span>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uppmValidationEnabled): ?>
        <div class="alert alert-info border-0 shadow-sm">
            Antrean generate saat ini hanya menampilkan sekolah yang sudah <strong>lunas UPPM periode <?php echo e($uppmValidationPeriodLabel); ?> tahun <?php echo e($uppmValidationYear); ?></strong>.
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uppmBlockedSchoolCount > 0): ?>
                <span class="d-block mt-1"><?php echo e(number_format($uppmBlockedSchoolCount)); ?> sekolah tersinkron yang belum lunas ditampilkan pada div terpisah di bawah dan tetap bisa diproses generate dari sana.</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <div class="sky-panel-label mb-1">Pengaturan Nomor Dipindah</div>
                    <h6 class="mb-1">Manajemen nomor SK sekarang dipusatkan di menu Nomor SK Yayasan</h6>
                    <p class="text-muted mb-0">
                        Setting nomor mulai, format nomor, rapikan nomor, hingga kunci nomor per sekolah sekarang dikelola dari satu halaman agar lebih rapi dan tidak tersebar.
                    </p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo e(route('sk-yayasan.numbers.index')); ?>" class="btn btn-primary">
                        <i class="bx bx-hash me-1"></i>Buka Nomor SK Yayasan
                    </a>
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
                            <div class="sky-panel-label mb-1">Antrean Sekolah</div>
                            <h6 class="mb-0">Klik sekolah untuk membuka daftar pengajuan tersinkronisasi</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($schools->count()); ?> sekolah dari <?php echo e($syncedSchoolCount); ?> sekolah tersinkron</span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schools->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Sekolah</th>
                                        <th>SCOD</th>
                                        <th>Antrean</th>
                                        <th>Status Nomor SK</th>
                                        <th>Nomor Surat Pengajuan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php ($coreData = $school->core_data ?? []); ?>
                                        <?php ($generatedDocumentsCount = (int) ($school->generated_documents_count ?? 0)); ?>
                                        <?php ($lockedDocumentsCount = (int) ($school->locked_documents_count ?? 0)); ?>
                                        <?php ($readyLockCount = (int) ($school->ready_lock_count ?? 0)); ?>
                                        <?php ($readyLockRange = $school->ready_lock_range); ?>
                                        <?php ($storedNumberSummary = $school->stored_number_summary ?? null); ?>
                                        <?php ($allGeneratedLocked = $generatedDocumentsCount > 0 && $generatedDocumentsCount === $lockedDocumentsCount); ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    <a href="<?php echo e(route('sk-yayasan.generate.school', $school)); ?>" class="text-decoration-none">
                                                        <?php echo e($school->name); ?>

                                                    </a>
                                                </div>
                                                <small class="text-muted"><?php echo e($school->kabupaten ?? 'Kabupaten belum diisi'); ?></small>
                                            </td>
                                            <td><?php echo e($school->scod ?? '-'); ?></td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <?php echo e(number_format($school->generate_requests_count)); ?> pengajuan
                                                </span>
                                            </td>
                                            <td class="small">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$numberLockSupported): ?>
                                                    <div class="text-muted">Fitur lock menunggu migration database</div>
                                                <?php elseif($generatedDocumentsCount > 0): ?>
                                                    <div class="fw-semibold text-dark"><?php echo e($lockedDocumentsCount); ?>/<?php echo e($generatedDocumentsCount); ?> nomor terkunci</div>
                                                    <div class="text-muted mt-1">
                                                        <?php echo e($allGeneratedLocked ? 'Semua draft/generate sekolah ini sudah final.' : 'Nomor yang sudah dikunci tidak akan berubah saat generate ulang.'); ?>

                                                    </div>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($storedNumberSummary && $storedNumberSummary['range_label']): ?>
                                                        <div class="mt-1">
                                                            <span class="fw-semibold text-dark">Rentang tersimpan:</span>
                                                            <span class="text-muted"><?php echo e($storedNumberSummary['range_label']); ?>/<?php echo e($storedNumberSummary['status_label']); ?></span>
                                                        </div>
                                                        <div class="mt-1 <?php echo e(($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'text-danger' : 'text-success'); ?>">
                                                            <span class="fw-semibold">Status duplikat:</span>
                                                            <span><?php echo e(($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'ada (' . $storedNumberSummary['duplicate_count'] . ' data)' : 'tidak ada'); ?></span>
                                                        </div>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($storedNumberSummary['validation_note']): ?>
                                                            <div class="mt-1 text-success">
                                                                <span class="fw-semibold">Validasi:</span>
                                                                <span><?php echo e($storedNumberSummary['validation_note']); ?></span>
                                                            </div>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$storedNumberSummary['is_sequential'] && $storedNumberSummary['missing_preview']): ?>
                                                            <div class="mt-1 text-danger">
                                                                <span class="fw-semibold">Nomor loncat:</span>
                                                                <span><?php echo e($storedNumberSummary['missing_preview']); ?></span>
                                                            </div>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($readyLockCount > 0 && $readyLockRange): ?>
                                                        <div class="mt-1">
                                                            <span class="fw-semibold text-dark">Rentang siap dikunci (urut SCOD):</span>
                                                            <span class="text-muted"><?php echo e($readyLockRange); ?></span>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php else: ?>
                                                    <div class="text-muted">Belum ada dokumen yang digenerate</div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td class="small"><?php echo e($school->submission_letter_reference['submission_letter_number'] ?? '-'); ?></td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="<?php echo e(route('sk-yayasan.generate.school', $school)); ?>" class="btn btn-sm btn-primary">
                                                        Lihat Pengajuan
                                                    </a>
                                                    <a href="<?php echo e(route('sk-yayasan.numbers.index', ['madrasah_id' => $school->id])); ?>#document-list" class="btn btn-sm btn-outline-primary">
                                                        Kelola Nomor
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-buildings"></i>
                            <strong>Belum ada sekolah dengan pengajuan tersinkronisasi</strong>
                            <small>Sekolah akan muncul di sini setelah pengajuan SK Yayasannya berhasil melalui proses sinkronisasi batch.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </div>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($uppmValidationEnabled): ?>
            <div class="col-12">
                <div class="card border-warning-subtle">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <div class="sky-panel-label mb-1">Antrean Generate Belum Lunas</div>
                                <h6 class="mb-0">Sekolah sudah mengajukan, tetapi status UPPM periode <?php echo e($uppmValidationPeriodLabel); ?> belum lunas</h6>
                            </div>
                            <span class="sky-chip"><?php echo e($blockedSchools->count()); ?> sekolah</span>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($blockedSchools->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nama Sekolah</th>
                                            <th>SCOD</th>
                                            <th>Antrean</th>
                                            <th>Status Nomor SK</th>
                                            <th>Nomor Surat Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $blockedSchools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <?php ($generatedDocumentsCount = (int) ($school->generated_documents_count ?? 0)); ?>
                                            <?php ($lockedDocumentsCount = (int) ($school->locked_documents_count ?? 0)); ?>
                                            <?php ($readyLockCount = (int) ($school->ready_lock_count ?? 0)); ?>
                                            <?php ($readyLockRange = $school->ready_lock_range); ?>
                                            <?php ($storedNumberSummary = $school->stored_number_summary ?? null); ?>
                                            <?php ($allGeneratedLocked = $generatedDocumentsCount > 0 && $generatedDocumentsCount === $lockedDocumentsCount); ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">
                                                        <a href="<?php echo e(route('sk-yayasan.generate.school', $school)); ?>" class="text-decoration-none">
                                                            <?php echo e($school->name); ?>

                                                        </a>
                                                    </div>
                                                    <small class="text-muted"><?php echo e($school->kabupaten ?? 'Kabupaten belum diisi'); ?></small>
                                                </td>
                                                <td><?php echo e($school->scod ?? '-'); ?></td>
                                                <td>
                                                    <span class="badge bg-warning-subtle text-warning">
                                                        <?php echo e(number_format($school->generate_requests_count)); ?> pengajuan
                                                    </span>
                                                </td>
                                                <td class="small">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$numberLockSupported): ?>
                                                        <div class="text-muted">Fitur lock menunggu migration database</div>
                                                    <?php elseif($generatedDocumentsCount > 0): ?>
                                                        <div class="fw-semibold text-dark"><?php echo e($lockedDocumentsCount); ?>/<?php echo e($generatedDocumentsCount); ?> nomor terkunci</div>
                                                        <div class="text-muted mt-1">
                                                            <?php echo e($allGeneratedLocked ? 'Semua draft/generate sekolah ini sudah final.' : 'Nomor yang sudah dikunci tidak akan berubah saat generate ulang.'); ?>

                                                        </div>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($storedNumberSummary && $storedNumberSummary['range_label']): ?>
                                                            <div class="mt-1">
                                                                <span class="fw-semibold text-dark">Rentang tersimpan:</span>
                                                                <span class="text-muted"><?php echo e($storedNumberSummary['range_label']); ?>/<?php echo e($storedNumberSummary['status_label']); ?></span>
                                                            </div>
                                                            <div class="mt-1 <?php echo e(($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'text-danger' : 'text-success'); ?>">
                                                                <span class="fw-semibold">Status duplikat:</span>
                                                                <span><?php echo e(($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'ada (' . $storedNumberSummary['duplicate_count'] . ' data)' : 'tidak ada'); ?></span>
                                                            </div>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($storedNumberSummary['validation_note']): ?>
                                                                <div class="mt-1 text-success">
                                                                    <span class="fw-semibold">Validasi:</span>
                                                                    <span><?php echo e($storedNumberSummary['validation_note']); ?></span>
                                                                </div>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$storedNumberSummary['is_sequential'] && $storedNumberSummary['missing_preview']): ?>
                                                                <div class="mt-1 text-danger">
                                                                    <span class="fw-semibold">Nomor loncat:</span>
                                                                    <span><?php echo e($storedNumberSummary['missing_preview']); ?></span>
                                                                </div>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($readyLockCount > 0 && $readyLockRange): ?>
                                                            <div class="mt-1">
                                                                <span class="fw-semibold text-dark">Rentang siap dikunci (urut SCOD):</span>
                                                                <span class="text-muted"><?php echo e($readyLockRange); ?></span>
                                                            </div>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php else: ?>
                                                        <div class="text-muted">Belum ada dokumen yang digenerate</div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                                <td class="small"><?php echo e($school->submission_letter_reference['submission_letter_number'] ?? '-'); ?></td>
                                                <td>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <a href="<?php echo e(route('sk-yayasan.generate.school', $school)); ?>" class="btn btn-sm btn-warning">
                                                            Lihat Pengajuan
                                                        </a>
                                                        <a href="<?php echo e(route('sk-yayasan.numbers.index', ['madrasah_id' => $school->id])); ?>#document-list" class="btn btn-sm btn-outline-primary">
                                                            Kelola Nomor
                                                        </a>
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
                                <strong>Semua sekolah tersinkron pada periode ini sudah lunas</strong>
                                <small>Tidak ada antrean generate terpisah untuk sekolah belum lunas.</small>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Data Guru Pengangkatan</div>
                            <h6 class="mb-0">Daftar pengajuan dengan keterangan Pengangkatan PTY dan Pengangkatan GTY dengan TMT 2 tahun ke atas</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip"><?php echo e($appointmentRequests->count()); ?> pengajuan</span>
                            <span class="sky-chip"><?php echo e($appointmentRequests->where('nipm_validated', false)->count()); ?> belum tervalidasi</span>
                        </div>
                    </div>

                    <?php ($appointmentRows = $appointmentRequests->values()); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointmentRequests->isNotEmpty()): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Pengajuan SK</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>TMT Diajukan</th>
                                        <th>Masa Kerja</th>
                                        <th>Keterangan</th>
                                        <th>NIPM Otomatis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $appointmentRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointmentData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php ($teacherId = data_get($appointmentData, 'teacher_id')); ?>
                                        <?php ($nipmSynced = (bool) data_get($appointmentData, 'nipm_synced', false)); ?>
                                        <?php ($nipmValidated = (bool) data_get($appointmentData, 'nipm_validated', false)); ?>
                                        <?php ($selectedMode = $nipmSynced ? 'system' : old('rows.' . $teacherId . '.nipm_mode', data_get($appointmentData, 'default_nipm_mode', 'system'))); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'submission_year', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'school_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'teacher_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'tmt_label', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'tenure_label', '-')); ?></td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info"><?php echo e(data_get($appointmentData, 'keterangan', '-')); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($appointmentData, 'rejection_keterangan')): ?>
                                                    <small class="text-muted d-block mt-1">
                                                        Jika ditolak: <strong><?php echo e(data_get($appointmentData, 'rejection_keterangan')); ?></strong>
                                                    </small>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td style="min-width: 280px;">
                                                <form id="appointment-nipm-sync-<?php echo e($teacherId); ?>" method="POST" action="<?php echo e(route('sk-yayasan.generate.appointment-nipm-sync')); ?>" class="d-none">
                                                    <?php echo csrf_field(); ?>
                                                </form>
                                                <input type="hidden"
                                                       id="appointment-decision-<?php echo e($teacherId); ?>"
                                                       form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                       name="rows[<?php echo e($teacherId); ?>][decision]"
                                                       value="approve">
                                                <input type="hidden"
                                                       form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                       name="rows[<?php echo e($teacherId); ?>][teacher_id]"
                                                       value="<?php echo e($teacherId); ?>">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$nipmSynced && data_get($appointmentData, 'has_nipm_source_choice', false)): ?>
                                                    <select name="rows[<?php echo e(data_get($appointmentData, 'teacher_id')); ?>][nipm_mode]"
                                                            form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                            class="form-select form-select-sm mb-2 js-nipm-mode"
                                                            data-existing-nipm="<?php echo e(data_get($appointmentData, 'existing_nipm_value', '')); ?>"
                                                            data-system-nipm="<?php echo e(data_get($appointmentData, 'system_nipm_value', '')); ?>">
                                                        <option value="existing" <?php if($selectedMode === 'existing'): echo 'selected'; endif; ?>>Gunakan NIPM yang ada</option>
                                                        <option value="system" <?php if($selectedMode === 'system'): echo 'selected'; endif; ?>>Gunakan NIPM sistem</option>
                                                    </select>
                                                <?php else: ?>
                                                    <input type="hidden"
                                                           form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                           name="rows[<?php echo e($teacherId); ?>][nipm_mode]"
                                                           value="<?php echo e($nipmSynced ? 'system' : $selectedMode); ?>">
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <input type="text"
                                                       form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                       name="rows[<?php echo e($teacherId); ?>][nipm]"
                                                       class="form-control form-control-sm js-nipm-input <?php echo e($nipmValidated ? 'border-success bg-success-subtle text-success-emphasis' : ''); ?>"
                                                       value="<?php echo e(old('rows.' . $teacherId . '.nipm', data_get($appointmentData, 'nipm_value', ''))); ?>"
                                                       placeholder="NIPM otomatis"
                                                       inputmode="numeric"
                                                       data-existing-nipm="<?php echo e(data_get($appointmentData, 'existing_nipm_value', '')); ?>"
                                                       data-system-nipm="<?php echo e(data_get($appointmentData, 'system_nipm_value', '')); ?>"
                                                       <?php if($nipmSynced || $selectedMode === 'existing'): echo 'readonly'; endif; ?>>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nipmValidated): ?>
                                                    <small class="text-success d-block mt-1 fw-semibold">NIPM tervalidasi</small>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td style="width: 180px;">
                                                <div class="d-grid gap-2">
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                            class="btn btn-sm btn-outline-danger w-100"
                                                            onclick="document.getElementById('appointment-decision-<?php echo e($teacherId); ?>').value='reject'">
                                                        Tolak
                                                    </button>
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                            class="btn btn-sm <?php echo e($nipmValidated ? 'btn-outline-success' : 'btn-primary'); ?> w-100"
                                                            onclick="document.getElementById('appointment-decision-<?php echo e($teacherId); ?>').value='approve'">
                                                        <?php echo e($nipmValidated ? 'Setujui Ulang' : 'Setujui'); ?>

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
                            <i class="bx bx-table"></i>
                            <strong>Belum ada data pengangkatan PTY/GTY</strong>
                            <small>Data akan muncul di sini jika ada pengajuan tersinkronisasi dengan keterangan Pengangkatan PTY atau Pengangkatan GTY.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Data Guru Pengangkatan</div>
                            <h6 class="mb-0">Daftar pengajuan dengan TMT di bawah 2 tahun</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip"><?php echo e($appointmentRequestsUnderTwoYears->count()); ?> pengajuan</span>
                            <span class="sky-chip"><?php echo e($appointmentRequestsUnderTwoYears->where('nipm_validated', false)->count()); ?> belum tervalidasi</span>
                        </div>
                    </div>

                    <?php ($appointmentRowsUnderTwoYears = $appointmentRequestsUnderTwoYears->values()); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointmentRequestsUnderTwoYears->isNotEmpty()): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Pengajuan SK</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>TMT Diajukan</th>
                                        <th>Masa Kerja</th>
                                        <th>Keterangan</th>
                                        <th>NIPM Otomatis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $appointmentRowsUnderTwoYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointmentData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php ($teacherId = data_get($appointmentData, 'teacher_id')); ?>
                                        <?php ($nipmSynced = (bool) data_get($appointmentData, 'nipm_synced', false)); ?>
                                        <?php ($nipmValidated = (bool) data_get($appointmentData, 'nipm_validated', false)); ?>
                                        <?php ($selectedMode = $nipmSynced ? 'system' : old('rows.' . $teacherId . '.nipm_mode', data_get($appointmentData, 'default_nipm_mode', 'system'))); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'submission_year', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'school_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'teacher_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'tmt_label', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'tenure_label', '-')); ?></td>
                                            <td>
                                                <span class="badge bg-warning-subtle text-warning"><?php echo e(data_get($appointmentData, 'keterangan', '-')); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(data_get($appointmentData, 'rejection_keterangan')): ?>
                                                    <small class="text-muted d-block mt-1">
                                                        Jika ditolak: <strong><?php echo e(data_get($appointmentData, 'rejection_keterangan')); ?></strong>
                                                    </small>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td style="min-width: 280px;">
                                                <form id="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>" method="POST" action="<?php echo e(route('sk-yayasan.generate.appointment-nipm-sync')); ?>" class="d-none">
                                                    <?php echo csrf_field(); ?>
                                                </form>
                                                <input type="hidden"
                                                       id="appointment-decision-under-two-years-<?php echo e($teacherId); ?>"
                                                       form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                       name="rows[<?php echo e($teacherId); ?>][decision]"
                                                       value="approve">
                                                <input type="hidden"
                                                       form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                       name="rows[<?php echo e($teacherId); ?>][teacher_id]"
                                                       value="<?php echo e($teacherId); ?>">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$nipmSynced && data_get($appointmentData, 'has_nipm_source_choice', false)): ?>
                                                    <select name="rows[<?php echo e(data_get($appointmentData, 'teacher_id')); ?>][nipm_mode]"
                                                            form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                            class="form-select form-select-sm mb-2 js-nipm-mode"
                                                            data-existing-nipm="<?php echo e(data_get($appointmentData, 'existing_nipm_value', '')); ?>"
                                                            data-system-nipm="<?php echo e(data_get($appointmentData, 'system_nipm_value', '')); ?>">
                                                        <option value="existing" <?php if($selectedMode === 'existing'): echo 'selected'; endif; ?>>Gunakan NIPM yang ada</option>
                                                        <option value="system" <?php if($selectedMode === 'system'): echo 'selected'; endif; ?>>Gunakan NIPM sistem</option>
                                                    </select>
                                                <?php else: ?>
                                                    <input type="hidden"
                                                           form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                           name="rows[<?php echo e($teacherId); ?>][nipm_mode]"
                                                           value="<?php echo e($nipmSynced ? 'system' : $selectedMode); ?>">
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <input type="text"
                                                       form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                       name="rows[<?php echo e($teacherId); ?>][nipm]"
                                                       class="form-control form-control-sm js-nipm-input <?php echo e($nipmValidated ? 'border-success bg-success-subtle text-success-emphasis' : ''); ?>"
                                                       value="<?php echo e(old('rows.' . $teacherId . '.nipm', data_get($appointmentData, 'nipm_value', ''))); ?>"
                                                       placeholder="NIPM otomatis"
                                                       inputmode="numeric"
                                                       data-existing-nipm="<?php echo e(data_get($appointmentData, 'existing_nipm_value', '')); ?>"
                                                       data-system-nipm="<?php echo e(data_get($appointmentData, 'system_nipm_value', '')); ?>"
                                                       <?php if($nipmSynced || $selectedMode === 'existing'): echo 'readonly'; endif; ?>>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nipmValidated): ?>
                                                    <small class="text-success d-block mt-1 fw-semibold">NIPM tervalidasi</small>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td style="width: 180px;">
                                                <div class="d-grid gap-2">
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                            class="btn btn-sm btn-outline-danger w-100"
                                                            onclick="document.getElementById('appointment-decision-under-two-years-<?php echo e($teacherId); ?>').value='reject'">
                                                        Tolak
                                                    </button>
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                            class="btn btn-sm <?php echo e($nipmValidated ? 'btn-outline-success' : 'btn-primary'); ?> w-100"
                                                            onclick="document.getElementById('appointment-decision-under-two-years-<?php echo e($teacherId); ?>').value='approve'">
                                                        <?php echo e($nipmValidated ? 'Setujui Ulang' : 'Setujui'); ?>

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
                            <i class="bx bx-table"></i>
                            <strong>Belum ada data guru pengangkatan dengan TMT di bawah 2 tahun</strong>
                            <small>Jika ada pengajuan Pengangkatan PTY atau GTY dengan TMT kurang dari 2 tahun, datanya akan tampil di sini.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Data Sudah Disetujui</div>
                            <h6 class="mb-0">Daftar guru pengangkatan yang NIPM-nya sudah divalidasi</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip"><?php echo e($approvedAppointmentRequests->count()); ?> data</span>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($approvedAppointmentRequests->isNotEmpty()): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Pengajuan SK</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>TMT Diajukan</th>
                                        <th>Masa Kerja</th>
                                        <th>Keterangan</th>
                                        <th>NIPM</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $approvedAppointmentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointmentData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'submission_year', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'school_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'teacher_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'tmt_label', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'tenure_label', '-')); ?></td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info"><?php echo e(data_get($appointmentData, 'keterangan', '-')); ?></span>
                                            </td>
                                            <td>
                                                <span class="text-success fw-semibold"><?php echo e(data_get($appointmentData, 'nipm_value', '-')); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success">Disetujui</span>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-check-circle"></i>
                            <strong>Belum ada data yang disetujui</strong>
                            <small>Guru yang sudah disetujui akan tetap tampil di sini sebagai riwayat validasi NIPM.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Data Sudah Ditolak</div>
                            <h6 class="mb-0">Daftar guru pengangkatan yang dialihkan ke keterangan GTT/PTT</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip"><?php echo e($rejectedAppointmentRequests->count()); ?> data</span>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rejectedAppointmentRequests->isNotEmpty()): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tahun Pengajuan SK</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>TMT Diajukan</th>
                                        <th>Masa Kerja</th>
                                        <th>Keterangan Pengajuan</th>
                                        <th>Keterangan Setelah Ditolak</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $rejectedAppointmentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointmentData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'submission_year', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'school_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'teacher_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'tmt_label', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'tenure_label', '-')); ?></td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info"><?php echo e(data_get($appointmentData, 'proposal_keterangan', data_get($appointmentData, 'keterangan', '-'))); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger-subtle text-danger"><?php echo e(data_get($appointmentData, 'rejection_keterangan', '-')); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-x-circle"></i>
                            <strong>Belum ada data yang ditolak</strong>
                            <small>Guru yang ditolak dari antrean pengangkatan akan tetap tampil di sini dengan hasil keterangan GTT/PTT.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.js-nipm-mode').forEach(function (select) {
        var input = select.parentElement.querySelector('.js-nipm-input');
        if (!input) {
            return;
        }

        var applyMode = function () {
            var useExisting = select.value === 'existing';
            input.value = useExisting
                ? (select.dataset.existingNipm || '')
                : (select.dataset.systemNipm || '');
            input.readOnly = useExisting;
        };

        select.addEventListener('change', applyMode);
        applyMode();
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/generate-index.blade.php ENDPATH**/ ?>