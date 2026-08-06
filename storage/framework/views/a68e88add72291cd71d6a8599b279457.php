<?php $__env->startSection('title'); ?>Nomor SK Yayasan <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> SK Yayasan <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Nomor SK Yayasan <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('sk-yayasan.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('sk-yayasan.partials.sweet-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
    .sky-number-page .sky-section-card {
        border: 1px solid #e4eee8 !important;
    }

    .sky-number-page .sky-toolbar {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        justify-content: space-between;
    }

    .sky-number-page .sky-table-note {
        color: #6b7b75;
        font-size: 12px;
    }

    .sky-number-page .sky-school-card {
        background: linear-gradient(180deg, #ffffff 0%, #f8fcfa 100%);
        border: 1px solid #e5eee9;
        border-radius: 18px;
        padding: 1rem;
    }

    .sky-number-page .sky-school-meta {
        color: #6b7b75;
        display: flex;
        flex-wrap: wrap;
        gap: .35rem .75rem;
        font-size: 12px;
    }

    .sky-number-page .sky-number-cell {
        min-width: 220px;
    }

    .sky-number-page .sky-number-pill {
        background: #f3faf6;
        border: 1px solid #d8e9df;
        border-radius: 14px;
        display: inline-flex;
        font-weight: 700;
        padding: .45rem .7rem;
    }

    .sky-number-page .sky-lock-chip {
        border-radius: 999px;
        display: inline-flex;
        font-size: 11px;
        font-weight: 700;
        padding: .3rem .55rem;
    }

    .sky-number-page .sky-lock-chip.is-locked {
        background: rgba(14, 133, 73, .12);
        color: #0e8549;
    }

    .sky-number-page .sky-lock-chip.is-open {
        background: rgba(148, 163, 184, .14);
        color: #475569;
    }

    .sky-number-page .sky-pager {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: .75rem;
        justify-content: space-between;
        margin-top: 1rem;
    }

    .sky-number-page .sky-pager-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .sky-number-page .sky-pager-actions .btn {
        min-width: 110px;
    }

    .sky-number-page .sky-filter-card .form-label {
        font-weight: 700;
    }

    .sky-number-page .sky-multi-select {
        min-height: 220px;
    }
</style>

<?php
    $selectedBulkSchoolIds = collect(old('madrasah_ids', ($filters['madrasah_id'] ?? null) ? [(int) $filters['madrasah_id']] : []))
        ->map(fn ($id) => (int) $id)
        ->filter(fn (int $id) => $id > 0)
        ->all();
?>

<div class="sky-page sky-number-page">
    <div class="sky-hero-strip mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <div class="sky-kicker mb-2">Pusat Pengaturan Nomor</div>
                <h4 class="mb-1">Nomor SK Yayasan</h4>
                <p class="mb-0 text-white-50">
                    Semua pengaturan nomor SK dipusatkan di halaman ini: setting global, rapikan nomor, kunci nomor, dan edit nomor per dokumen.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e(number_format($numberStats['school_count'] ?? 0)); ?> sekolah</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e(number_format($numberStats['synced_batch_count'] ?? 0)); ?> batch</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e(number_format($numberStats['total_documents'] ?? 0)); ?> nomor</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-3 col-md-6">
            <div class="card sky-stat-card h-100">
                <div class="card-body">
                    <div class="sky-panel-label mb-2">Total Nomor</div>
                    <h4 class="mb-1"><?php echo e(number_format($numberStats['total_documents'] ?? 0)); ?></h4>
                    <div class="text-muted small">Dokumen yang sudah memiliki nomor SK.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card sky-stat-card h-100">
                <div class="card-body">
                    <div class="sky-panel-label mb-2">Rentang Global</div>
                    <h4 class="mb-1"><?php echo e($numberStats['range_label'] ?? '-'); ?></h4>
                    <div class="text-muted small">Rentang urutan nomor yang tersimpan saat ini.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card sky-stat-card h-100">
                <div class="card-body">
                    <div class="sky-panel-label mb-2">Nomor Terkunci</div>
                    <h4 class="mb-1"><?php echo e(number_format($numberStats['locked_documents'] ?? 0)); ?></h4>
                    <div class="text-muted small">Nomor final yang sudah dikunci.</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card sky-stat-card h-100">
                <div class="card-body">
                    <div class="sky-panel-label mb-2">Duplikat</div>
                    <h4 class="mb-1"><?php echo e(number_format($numberStats['duplicate_number_count'] ?? 0)); ?></h4>
                    <div class="text-muted small"><?php echo e(number_format($numberStats['duplicate_row_count'] ?? 0)); ?> baris memakai nomor yang sama.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sky-section-card mb-3">
        <div class="card-body">
            <div class="sky-toolbar mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Data Pokok SK</div>
                    <h6 class="mb-0">Setting global nomor dan metadata penerbitan</h6>
                </div>
                <span class="sky-chip">Berlaku untuk seluruh antrean sekolah</span>
            </div>

            <form method="POST" action="<?php echo e(route('sk-yayasan.generate.settings.update')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Tahun Penerbitan SK</label>
                        <input type="text" name="sk_yayasan_school_year" class="form-control" value="<?php echo e(old('sk_yayasan_school_year', $globalSkSettings['school_year'])); ?>" required>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Nomor SK Mulai</label>
                        <input type="number" name="sk_yayasan_number_start" class="form-control" min="1" value="<?php echo e(old('sk_yayasan_number_start', $globalSkSettings['number_start'])); ?>" required>
                        <small class="text-muted">Nomor awal global, contoh `1565`.</small>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Nama Ketua Yayasan</label>
                        <input type="text" name="sk_yayasan_signer_name" class="form-control" value="<?php echo e(old('sk_yayasan_signer_name', $globalSkSettings['signer_name'])); ?>" required>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Jabatan Penandatangan</label>
                        <input type="text" name="sk_yayasan_signer_position" class="form-control" value="<?php echo e(old('sk_yayasan_signer_position', $globalSkSettings['signer_position'])); ?>">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Ditetapkan Di</label>
                        <input type="text" name="sk_yayasan_established_at" class="form-control" value="<?php echo e(old('sk_yayasan_established_at', $globalSkSettings['established_at'])); ?>" required>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label">Pada Tanggal Penetapan</label>
                        <input type="date" name="sk_yayasan_issued_date" class="form-control" value="<?php echo e(old('sk_yayasan_issued_date', $globalSkSettings['issued_date'])); ?>" required>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label">Format Nomor SK</label>
                        <input type="text" name="sk_yayasan_number_format_suffix" class="form-control" value="<?php echo e(old('sk_yayasan_number_format_suffix', $globalSkSettings['number_format_suffix'])); ?>" required>
                        <small class="text-muted">Bagian depan nomor tetap diambil dari urutan global.</small>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                    <div class="small text-muted">
                        Pengaturan ini dipakai saat generate dan saat sistem merapikan nomor SK.
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Data Pokok SK</button>
                </div>
            </form>

            <div class="d-flex justify-content-end flex-wrap gap-2 mt-3">
                <a href="<?php echo e(route('sk-yayasan.generate.index')); ?>" class="btn btn-light">
                    <i class="bx bx-printer me-1"></i>Buka Halaman Generate
                </a>
                <form method="POST"
                      action="<?php echo e(route('sk-yayasan.generate.lock-all')); ?>"
                      data-sk-swal-confirm
                      data-sk-swal-title="Kunci semua nomor SK?"
                      data-sk-swal-text="Semua nomor yang sudah tergenerate akan dikunci dan tidak berubah saat generate ulang."
                      data-sk-swal-confirm-text="Ya, kunci semua">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-outline-dark" <?php if(!$numberLockSupported || $schools->isEmpty()): echo 'disabled'; endif; ?>>
                        <i class="bx bx-lock-alt me-1"></i>Kunci All
                    </button>
                </form>
                <form method="POST"
                      action="<?php echo e(route('sk-yayasan.generate.regenerate-all')); ?>"
                      data-sk-swal-confirm
                      data-sk-swal-title="Generate ulang semua nomor SK?"
                      data-sk-swal-text="Sistem akan menyusun ulang nomor global sesuai urutan SCOD. Nomor yang sudah dikunci tidak akan diubah."
                      data-sk-swal-confirm-text="Ya, generate ulang">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-outline-primary" <?php if($schools->isEmpty()): echo 'disabled'; endif; ?>>
                        <i class="bx bx-refresh me-1"></i>Generate Ulang All
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="card sky-section-card mb-3">
        <div class="card-body">
            <div class="sky-toolbar mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Atur Ulang Rentang Pilihan</div>
                    <h6 class="mb-0">Pilih beberapa sekolah, lalu susun ulang nomor mereka ke rentang yang Anda tentukan</h6>
                </div>
                <span class="sky-chip">Urutan sekolah mengikuti SCOD</span>
            </div>

            <form method="POST"
                  action="<?php echo e(route('sk-yayasan.numbers.bulk-renumber')); ?>"
                  id="bulkRenumberSelectedSchoolForm"
                  data-sk-swal-confirm
                  data-sk-swal-title="Atur ulang nomor SK sekolah terpilih?"
                  data-sk-swal-text="Nomor SK lama pada sekolah terpilih akan diganti penuh sesuai rentang yang Anda tentukan."
                  data-sk-swal-confirm-text="Ya, atur ulang">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="skip_used_numbers" id="bulkSkipUsedNumbersInput" value="<?php echo e(old('skip_used_numbers', 0) ? 1 : 0); ?>">
                <div class="row g-3">
                    <div class="col-lg-5">
                        <label class="form-label">Pilih Sekolah</label>
                        <select name="madrasah_ids[]" class="form-select sky-multi-select" id="bulkSchoolSelect" multiple required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($school->id); ?>"
                                        data-request-count="<?php echo e((int) ($school->generate_requests_count ?? 0)); ?>"
                                        <?php if(in_array((int) $school->id, $selectedBulkSchoolIds, true)): echo 'selected'; endif; ?>>
                                    <?php echo e($school->scod ? $school->scod . ' - ' : ''); ?><?php echo e($school->name); ?>

                                    - <?php echo e((int) ($school->generate_requests_count ?? 0)); ?> pengajuan
                                    - <?php echo e((int) ($school->generated_documents_count ?? 0) > 0 ? (int) ($school->generated_documents_count ?? 0) . ' nomor tersimpan' : 'belum punya nomor'); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <small class="text-muted">Pilih sekolah yang nomornya ingin dihapus lalu disusun ulang. Sekolah yang sudah dihapus nomornya tetap bisa dipilih lagi untuk diisi rentang baru.</small>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">Nomor Awal Rentang</label>
                        <input type="number" name="range_start" id="bulkRangeStartInput" class="form-control" min="1" value="<?php echo e(old('range_start')); ?>" required>
                        <small class="text-muted">Contoh: `7070`.</small>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">Nomor Akhir Rentang</label>
                        <input type="number" name="range_end" id="bulkRangeEndInput" class="form-control" min="1" value="<?php echo e(old('range_end')); ?>" required>
                        <small class="text-muted" id="bulkRangeHint">Contoh: `7085`.</small>
                    </div>
                    <div class="col-lg-1 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="1" id="bulkLockAfter" name="lock_after" <?php if(old('lock_after')): echo 'checked'; endif; ?>>
                            <label class="form-check-label small" for="bulkLockAfter">Kunci</label>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                    <div class="small text-muted">
                        Sistem akan menghapus nomor lama sekolah terpilih, lalu mengisi ulang sesuai rentang yang Anda masukkan. Jika ada nomor yang sudah dipakai sekolah lain, sistem bisa melanjutkan dengan melewati nomor bentrok tanpa mengubah nomor yang sudah dipakai.
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit"
                                formaction="<?php echo e(route('sk-yayasan.numbers.bulk-clear')); ?>"
                                formnovalidate
                                class="btn btn-outline-danger"
                                data-sk-swal-title="Hapus nomor SK sekolah terpilih?"
                                data-sk-swal-text="Nomor SK pada sekolah terpilih akan dikosongkan dulu dan tidak langsung diisi rentang baru."
                                data-sk-swal-confirm-text="Ya, hapus nomor"
                                <?php if($schools->isEmpty()): echo 'disabled'; endif; ?>>
                            <i class="bx bx-eraser me-1"></i>Hapus Nomor SK
                        </button>
                        <button type="submit" class="btn btn-primary" <?php if($schools->isEmpty()): echo 'disabled'; endif; ?>>
                            <i class="bx bx-slider-alt me-1"></i>Atur Ulang Rentang Pilihan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card sky-section-card mb-3">
        <div class="card-body">
            <div class="sky-toolbar mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Kelola Nomor per Sekolah</div>
                    <h6 class="mb-0">Rapikan, kunci, dan validasi nomor berdasarkan sekolah</h6>
                </div>
                <span class="sky-chip"><?php echo e($schools->count()); ?> sekolah terdata</span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schools->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Sekolah</th>
                                <th>Antrean</th>
                                <th>Status Nomor</th>
                                <th>Nomor Surat</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php ($totalSchoolRequests = (int) ($school->generate_requests_count ?? 0)); ?>
                                <?php ($generatedDocumentsCount = (int) ($school->generated_documents_count ?? 0)); ?>
                                <?php ($lockedDocumentsCount = (int) ($school->locked_documents_count ?? 0)); ?>
                                <?php ($readyLockCount = (int) ($school->ready_lock_count ?? 0)); ?>
                                <?php ($readyLockRange = $school->ready_lock_range); ?>
                                <?php ($storedNumberSummary = $school->stored_number_summary ?? null); ?>
                                <?php ($requestsWithoutNumber = $requestsWithoutNumberBySchool[$school->id] ?? collect()); ?>
                                <?php ($requestsWithoutNumberCount = $requestsWithoutNumber instanceof \Illuminate\Support\Collection ? $requestsWithoutNumber->count() : 0); ?>
                                <?php ($allGeneratedLocked = $totalSchoolRequests > 0 && $generatedDocumentsCount === $totalSchoolRequests && $generatedDocumentsCount === $lockedDocumentsCount); ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($school->name); ?></div>
                                        <small class="text-muted"><?php echo e($school->scod ? 'SCOD ' . $school->scod . ' • ' : ''); ?><?php echo e($school->kabupaten ?? 'Kabupaten belum diisi'); ?></small>
                                    </td>
                                    <td>
                                        <span class="sky-chip"><?php echo e(number_format($school->generate_requests_count ?? 0)); ?> pengajuan</span>
                                    </td>
                                    <td class="small">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$numberLockSupported): ?>
                                            <div class="text-muted">Fitur lock menunggu migration database</div>
                                        <?php elseif($generatedDocumentsCount > 0): ?>
                                            <div class="fw-semibold text-dark"><?php echo e($lockedDocumentsCount); ?>/<?php echo e($totalSchoolRequests); ?> nomor terkunci</div>
                                            <div class="text-muted mt-1">
                                                <?php echo e($generatedDocumentsCount); ?>/<?php echo e($totalSchoolRequests); ?> pengajuan sudah punya nomor SK.
                                            </div>
                                            <div class="text-muted mt-1">
                                                <?php echo e($allGeneratedLocked ? 'Semua draft sekolah ini sudah final.' : 'Nomor yang dikunci tidak akan berubah saat generate ulang.'); ?>

                                            </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requestsWithoutNumberCount > 0): ?>
                                                <div class="mt-1 text-warning">
                                                    <span class="fw-semibold">Belum punya nomor:</span>
                                                    <span><?php echo e($requestsWithoutNumberCount); ?> user</span>
                                                </div>
                                                <div class="mt-2">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#schoolMissingNumberModal<?php echo e($school->id); ?>">
                                                        Lihat <?php echo e($requestsWithoutNumberCount); ?> User
                                                    </button>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($storedNumberSummary && $storedNumberSummary['range_label']): ?>
                                                <div class="mt-1">
                                                    <span class="fw-semibold text-dark">Rentang:</span>
                                                    <span class="text-muted"><?php echo e($storedNumberSummary['range_label']); ?>/<?php echo e($storedNumberSummary['status_label']); ?></span>
                                                </div>
                                                <div class="mt-1 <?php echo e(($storedNumberSummary['duplicate_count'] ?? 0) > 0 ? 'text-danger' : 'text-success'); ?>">
                                                    <span class="fw-semibold">Duplikat:</span>
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
                                                    <span class="fw-semibold text-dark">Siap dikunci:</span>
                                                    <span class="text-muted"><?php echo e($readyLockRange); ?></span>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <div class="fw-semibold text-warning">Nomor SK belum ada</div>
                                            <div class="text-muted mt-1">Nomor sekolah ini kosong atau sudah dihapus. Sekolah tetap bisa dipilih untuk diisi ulang sesuai rentang nomor yang Anda tentukan.</div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requestsWithoutNumberCount > 0): ?>
                                                <div class="mt-1 text-warning">
                                                    <span class="fw-semibold">User siap diisi nomor:</span>
                                                    <span><?php echo e($requestsWithoutNumberCount); ?> user</span>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="small"><?php echo e($school->submission_letter_reference['submission_letter_number'] ?? '-'); ?></td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm action-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-cog me-1"></i>Kelola
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern">
                                                <li>
                                                    <a href="<?php echo e(route('sk-yayasan.generate.school', $school)); ?>" class="dropdown-item">
                                                        <i class="bx bx-printer me-2 text-primary"></i>Buka Halaman Generate
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="<?php echo e(route('sk-yayasan.numbers.index', ['madrasah_id' => $school->id])); ?>#document-list" class="dropdown-item">
                                                        <i class="bx bx-list-ul me-2 text-info"></i>Lihat Nomor Sekolah Ini
                                                    </a>
                                                </li>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requestsWithoutNumberCount > 0): ?>
                                                    <li>
                                                        <button type="button"
                                                                class="dropdown-item"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#schoolMissingNumberModal<?php echo e($school->id); ?>">
                                                            <i class="bx bx-user-plus me-2 text-warning"></i>User Belum Punya Nomor (<?php echo e($requestsWithoutNumberCount); ?>)
                                                        </button>
                                                    </li>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <li><hr class="dropdown-divider my-2"></li>
                                                <li>
                                                    <form method="POST"
                                                          action="<?php echo e(route('sk-yayasan.generate.school.renumber', $school)); ?>"
                                                          data-sk-swal-confirm
                                                          data-sk-swal-title="Rapikan nomor SK sekolah ini?"
                                                          data-sk-swal-text="Nomor sekolah lain tidak akan diubah. Sistem akan menyusun ulang nomor sekolah ini dari rentang terendah yang tersedia."
                                                          data-sk-swal-confirm-text="Ya, rapikan">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item" <?php if($generatedDocumentsCount === 0): echo 'disabled'; endif; ?>>
                                                            <i class="bx bx-sort-alt-2 me-2 text-warning"></i>Rapikan Nomor SK
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST"
                                                          action="<?php echo e(route('sk-yayasan.generate.school.renumber', $school)); ?>"
                                                          data-sk-swal-confirm
                                                          data-sk-swal-title="Gunakan nomor kosong global?"
                                                          data-sk-swal-text="Sistem akan mencoba mengisi gap nomor global yang belum terpakai untuk sekolah ini."
                                                          data-sk-swal-confirm-text="Ya, pakai nomor kosong">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="use_unused_global_numbers" value="1">
                                                        <button type="submit" class="dropdown-item" <?php if($generatedDocumentsCount === 0): echo 'disabled'; endif; ?>>
                                                            <i class="bx bx-transfer-alt me-2 text-warning"></i>Rapikan Pakai Nomor Kosong
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider my-2"></li>
                                                <li>
                                                    <form method="POST"
                                                          action="<?php echo e(route('sk-yayasan.generate.school.renumber', $school)); ?>"
                                                          data-sk-swal-confirm
                                                          data-sk-swal-title="Rapikan dan kunci ulang?"
                                                          data-sk-swal-text="Setelah dirapikan, semua nomor sekolah ini langsung dikunci kembali."
                                                          data-sk-swal-confirm-text="Ya, rapikan & kunci">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="lock_after" value="1">
                                                        <button type="submit" class="dropdown-item" <?php if($generatedDocumentsCount === 0 || !$numberLockSupported): echo 'disabled'; endif; ?>>
                                                            <i class="bx bx-reset me-2 text-dark"></i>Rapikan & Kunci Ulang
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST"
                                                          action="<?php echo e(route('sk-yayasan.generate.school.renumber', $school)); ?>"
                                                          data-sk-swal-confirm
                                                          data-sk-swal-title="Pakai nomor kosong lalu kunci?"
                                                          data-sk-swal-text="Sistem akan memakai nomor kosong global lalu langsung mengunci hasilnya untuk sekolah ini."
                                                          data-sk-swal-confirm-text="Ya, pakai & kunci">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="lock_after" value="1">
                                                        <input type="hidden" name="use_unused_global_numbers" value="1">
                                                        <button type="submit" class="dropdown-item" <?php if($generatedDocumentsCount === 0 || !$numberLockSupported): echo 'disabled'; endif; ?>>
                                                            <i class="bx bx-git-merge me-2 text-dark"></i>Kunci Pakai Nomor Kosong
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider my-2"></li>
                                                <li>
                                                    <form method="POST"
                                                          action="<?php echo e(route('sk-yayasan.generate.school.lock-number', $school)); ?>"
                                                          data-sk-swal-confirm
                                                          data-sk-swal-title="Kunci nomor SK sekolah ini?"
                                                          data-sk-swal-text="Nomor yang terkunci tidak akan berubah saat generate ulang berikutnya."
                                                          data-sk-swal-confirm-text="Ya, kunci">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="dropdown-item" <?php if(!$numberLockSupported || $generatedDocumentsCount === 0 || $allGeneratedLocked): echo 'disabled'; endif; ?>>
                                                            <i class="bx bx-lock-alt me-2 text-secondary"></i>Kunci Nomor SK Sekolah Ini
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
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
                    <strong>Belum ada sekolah yang bisa dikelola</strong>
                    <small>Sekolah akan muncul di sini jika sudah memiliki pengajuan SK Yayasan atau riwayat nomor yang bisa diatur.</small>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="card sky-section-card sky-filter-card mb-3" id="document-list">
        <div class="card-body">
            <div class="sky-toolbar mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Filter Dokumen</div>
                    <h6 class="mb-0">Cari nomor yang sudah terpakai dan edit langsung</h6>
                </div>
                <span class="sky-chip"><?php echo e($documents->total()); ?> data</span>
            </div>

            <form method="GET" action="<?php echo e(route('sk-yayasan.numbers.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-6">
                        <label class="form-label">Cari nomor / guru / sekolah</label>
                        <input type="text" name="q" class="form-control" value="<?php echo e($filters['q'] ?? ''); ?>" placeholder="Contoh: 7095, Agung, SMAPDA">
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">Filter sekolah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua sekolah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schoolOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($schoolOption->id); ?>" <?php if((int) ($filters['madrasah_id'] ?? 0) === (int) $schoolOption->id): echo 'selected'; endif; ?>>
                                    <?php echo e($schoolOption->scod ? $schoolOption->scod . ' - ' : ''); ?><?php echo e($schoolOption->name); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                            <a href="<?php echo e(route('sk-yayasan.numbers.index')); ?>#document-list" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card sky-section-card">
        <div class="card-body">
            <div class="sky-toolbar mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Daftar Nomor Tersimpan</div>
                    <h6 class="mb-0">Urut dari nomor terkecil ke terbesar</h6>
                </div>
                <div class="sky-table-note">
                    Menampilkan <?php echo e(number_format($documents->firstItem() ?? 0)); ?>-<?php echo e(number_format($documents->lastItem() ?? 0)); ?> dari <?php echo e(number_format($documents->total())); ?> nomor
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($documents->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th style="width:72px;">No</th>
                                <th style="width:110px;">Urutan</th>
                                <th>Nomor SK</th>
                                <th>Sekolah</th>
                                <th>Guru/Pegawai</th>
                                <th>Status</th>
                                <th>Terkunci</th>
                                <th class="text-end" style="width:120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php ($requestData = $document->request); ?>
                                <?php ($school = $requestData?->madrasah); ?>
                                <?php ($employee = $requestData?->employee); ?>
                                <tr>
                                    <td><?php echo e($documents->firstItem() + $loop->index); ?></td>
                                    <td><span class="fw-semibold"><?php echo e($document->sequence_number ?? '-'); ?></span></td>
                                    <td class="sky-number-cell">
                                        <div class="sky-number-pill"><?php echo e($document->document_number); ?></div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($document->duplicate_total ?? 0) > 1): ?>
                                            <div class="mt-1">
                                                <span class="badge bg-danger-subtle text-danger">Duplikat <?php echo e($document->duplicate_total); ?> data</span>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($school?->name ?? '-'); ?></div>
                                        <small class="text-muted"><?php echo e($school?->scod ? 'SCOD ' . $school->scod : 'SCOD belum diisi'); ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo e($employee?->name ?? '-'); ?></div>
                                        <small class="text-muted"><?php echo e($requestData?->request_number ?? '-'); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo e($document->status === 'published' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'); ?>">
                                            <?php echo e($document->status === 'published' ? 'Published' : 'Draft'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="sky-lock-chip <?php echo e($document->number_locked_at ? 'is-locked' : 'is-open'); ?>">
                                            <?php echo e($document->number_locked_at ? 'Terkunci' : 'Belum terkunci'); ?>

                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($document->number_locked_at): ?>
                                            <div class="text-muted small mt-1"><?php echo e($document->number_locked_at->format('d/m/Y H:i')); ?></div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDocumentNumberModal<?php echo e($document->id); ?>">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($documents->hasPages()): ?>
                    <div class="sky-pager">
                        <div class="sky-table-note">
                            Halaman <?php echo e(number_format($documents->currentPage())); ?> dari <?php echo e(number_format($documents->lastPage())); ?>

                        </div>
                        <div class="sky-pager-actions">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($documents->onFirstPage()): ?>
                                <button type="button" class="btn btn-light btn-sm" disabled>Sebelumnya</button>
                            <?php else: ?>
                                <a href="<?php echo e($documents->previousPageUrl()); ?>#document-list" class="btn btn-light btn-sm">Sebelumnya</a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($documents->hasMorePages()): ?>
                                <a href="<?php echo e($documents->nextPageUrl()); ?>#document-list" class="btn btn-outline-primary btn-sm">Berikutnya</a>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-primary btn-sm" disabled>Berikutnya</button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <div class="sky-empty-state py-5">
                    <i class="bx bx-hash"></i>
                    <strong>Belum ada nomor SK yang cocok dengan filter</strong>
                    <small>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($filters['madrasah_id'] ?? null)): ?>
                            Sekolah yang dipilih saat ini belum memiliki nomor SK tersimpan atau nomornya sudah dihapus. Anda bisa mengisi ulang dari panel Atur Ulang Rentang Pilihan.
                        <?php else: ?>
                            Ubah pencarian atau filter sekolah untuk melihat data nomor lain.
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </small>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <?php ($requestData = $document->request); ?>
        <?php ($school = $requestData?->madrasah); ?>
        <?php ($employee = $requestData?->employee); ?>
        <div class="modal fade" id="editDocumentNumberModal<?php echo e($document->id); ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1">Edit Nomor SK Yayasan</h5>
                            <small class="text-muted"><?php echo e($employee?->name ?? '-'); ?> - <?php echo e($school?->name ?? '-'); ?></small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST"
                          action="<?php echo e(route('sk-yayasan.numbers.update', $document)); ?>"
                          data-sk-swal-confirm
                          data-sk-swal-title="Perbarui nomor SK ini?"
                          data-sk-swal-text="Nomor lama akan diganti dan status validasi sekolah dihitung ulang dari data tersimpan."
                          data-sk-swal-confirm-text="Ya, simpan">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nomor SK</label>
                                <input type="text"
                                       name="document_number"
                                       class="form-control"
                                       value="<?php echo e(old('document_number', $document->document_number)); ?>"
                                       required>
                                <small class="text-muted">Format harus diawali angka lalu `/`, contoh `7095/SK.02/LPM.DIY/VII/2026`.</small>
                            </div>
                            <div class="sky-summary-stack">
                                <div class="sky-summary-row">
                                    <span class="text-muted">Urutan sekarang</span>
                                    <span class="fw-semibold"><?php echo e($document->sequence_number ?? '-'); ?></span>
                                </div>
                                <div class="sky-summary-row">
                                    <span class="text-muted">Status lock</span>
                                    <span class="fw-semibold"><?php echo e($document->number_locked_at ? 'Terkunci' : 'Belum terkunci'); ?></span>
                                </div>
                                <div class="sky-summary-row">
                                    <span class="text-muted">Status duplikat</span>
                                    <span class="fw-semibold <?php echo e(($document->duplicate_total ?? 0) > 1 ? 'text-danger' : 'text-success'); ?>">
                                        <?php echo e(($document->duplicate_total ?? 0) > 1 ? 'Duplikat ' . $document->duplicate_total . ' data' : 'Tidak ada'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
        <?php ($requestsWithoutNumber = $requestsWithoutNumberBySchool[$school->id] ?? collect()); ?>
        <?php ($requestsWithoutNumberCount = $requestsWithoutNumber instanceof \Illuminate\Support\Collection ? $requestsWithoutNumber->count() : 0); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($requestsWithoutNumberCount > 0): ?>
            <div class="modal fade" id="schoolMissingNumberModal<?php echo e($school->id); ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title mb-1">User Belum Memiliki Nomor SK</h5>
                                <small class="text-muted"><?php echo e($school->name); ?> • <?php echo e($requestsWithoutNumberCount); ?> user siap diisi nomor</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Guru/Pegawai</th>
                                            <th>Request</th>
                                            <th>Jenis Pengajuan</th>
                                            <th>Template</th>
                                            <th>Nomor SK Baru</th>
                                            <th class="text-end">Simpan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $requestsWithoutNumber; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold"><?php echo e($submission->employee?->name ?? '-'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold"><?php echo e($submission->request_number); ?></div>
                                                </td>
                                                <td><?php echo e($submission->submission_type_label ?? '-'); ?></td>
                                                <td>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->resolved_template): ?>
                                                        <div class="fw-semibold"><?php echo e($submission->resolved_template->name); ?></div>
                                                    <?php else: ?>
                                                        <span class="text-danger">Template belum tersedia</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                                <td style="min-width: 260px;">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->resolved_template): ?>
                                                        <form method="POST"
                                                              id="assignMissingNumberForm<?php echo e($submission->id); ?>"
                                                              action="<?php echo e(route('sk-yayasan.numbers.assign-request-number', $submission)); ?>"
                                                              class="d-flex flex-column gap-2"
                                                              data-sk-swal-confirm
                                                              data-sk-swal-title="Tambahkan nomor SK untuk user ini?"
                                                              data-sk-swal-text="Sistem akan membuat dokumen SK untuk user ini dengan nomor yang Anda input."
                                                              data-sk-swal-confirm-text="Ya, tambahkan">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="text"
                                                                   name="document_number"
                                                                   class="form-control"
                                                                   value="<?php echo e(old('document_number')); ?>"
                                                                   placeholder="Contoh: 7095/SK.02/LPM.DIY/VII/2026"
                                                                   required>
                                                            <small class="text-muted">Isi nomor manual untuk user ini.</small>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="text-muted">Template belum ada, nomor belum bisa ditambahkan.</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                                <td class="text-end">
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submission->resolved_template): ?>
                                                        <button type="submit"
                                                                form="assignMissingNumberForm<?php echo e($submission->id); ?>"
                                                                class="btn btn-sm btn-primary">
                                                            Tambah Nomor
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="text-muted small">Belum bisa</span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const schoolSelect = document.getElementById('bulkSchoolSelect');
    const rangeStartInput = document.getElementById('bulkRangeStartInput');
    const rangeEndInput = document.getElementById('bulkRangeEndInput');
    const rangeHint = document.getElementById('bulkRangeHint');
    const bulkRenumberForm = document.getElementById('bulkRenumberSelectedSchoolForm');
    const bulkSkipUsedNumbersInput = document.getElementById('bulkSkipUsedNumbersInput');
    const bulkRenumberConflict = <?php echo json_encode(session('sk_yayasan_bulk_renumber_conflict'), 15, 512) ?>;

    if (!schoolSelect || !rangeStartInput || !rangeEndInput || !rangeHint) {
        return;
    }

    const calculateSelectedRequestCount = () => {
        return Array.from(schoolSelect.selectedOptions).reduce((total, option) => {
            const count = parseInt(option.dataset.requestCount || '0', 10);

            return total + (Number.isNaN(count) ? 0 : count);
        }, 0);
    };

    const syncRangeEnd = () => {
        const selectedRequestCount = calculateSelectedRequestCount();
        const startNumber = parseInt(rangeStartInput.value || '', 10);

        if (selectedRequestCount <= 0) {
            rangeHint.textContent = 'Contoh: `7085`.';
            return;
        }

        rangeHint.textContent = 'Total pengajuan terpilih: ' + selectedRequestCount + ' guru/user.';

        if (Number.isNaN(startNumber) || startNumber <= 0) {
            return;
        }

        rangeEndInput.value = String(startNumber + selectedRequestCount - 1);
    };

    schoolSelect.addEventListener('change', syncRangeEnd);
    rangeStartInput.addEventListener('input', syncRangeEnd);
    syncRangeEnd();

    if (
        bulkRenumberConflict
        && typeof Swal !== 'undefined'
        && bulkRenumberForm
        && bulkSkipUsedNumbersInput
    ) {
        const skippedSequences = Array.isArray(bulkRenumberConflict.skipped_sequences)
            ? bulkRenumberConflict.skipped_sequences
            : [];
        const conflictPreview = Array.isArray(bulkRenumberConflict.conflict_preview)
            ? bulkRenumberConflict.conflict_preview
            : [];
        const skippedLabel = skippedSequences.length
            ? skippedSequences.join(', ')
            : '-';
        const previewItems = conflictPreview.length
            ? '<ul style="text-align:left;padding-left:18px;margin:.5rem 0 0;">' + conflictPreview.map((item) => `<li>${item}</li>`).join('') + '</ul>'
            : '';

        Swal.fire({
            icon: 'warning',
            title: 'Rentang nomor bentrok',
            html:
                '<div style="text-align:left">' +
                    '<p>Rentang <strong>' + (bulkRenumberConflict.range_label || '-') + '</strong> bentrok dengan nomor yang sudah dipakai sekolah lain.</p>' +
                    '<p>Jika dilanjutkan, sistem akan tetap mulai dari nomor <strong>' + (bulkRenumberConflict.requested_start || '-') + '</strong> dan melewati nomor yang sudah terpakai tanpa mengubah nomor sekolah lain.</p>' +
                    '<p class="mb-1">Perkiraan rentang hasil: <strong>' + (bulkRenumberConflict.proposed_range_label || '-') + '</strong></p>' +
                    '<p class="mb-0">Nomor yang dilewati: <strong>' + skippedLabel + '</strong></p>' +
                    previewItems +
                '</div>',
            showCancelButton: true,
            confirmButtonText: 'Ya, lewati nomor bentrok',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#0e8549',
            cancelButtonColor: '#94a3b8',
        }).then((result) => {
            if (!result.isConfirmed) {
                bulkSkipUsedNumbersInput.value = '0';
                return;
            }

            bulkSkipUsedNumbersInput.value = '1';
            bulkRenumberForm.dataset.skSwalSubmitting = '1';
            bulkRenumberForm.submit();
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/sk-yayasan/number-index.blade.php ENDPATH**/ ?>