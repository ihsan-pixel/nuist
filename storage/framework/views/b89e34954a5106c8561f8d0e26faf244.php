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
                <span class="d-block mt-1"><?php echo e(number_format($uppmBlockedSchoolCount)); ?> sekolah tersinkron belum muncul di antrean karena status UPPM-nya belum lunas.</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Data Pokok SK</div>
                    <h6 class="mb-0">Metadata global untuk semua sekolah yang sudah tersinkronisasi</h6>
                </div>
                <span class="sky-chip">Global untuk seluruh antrean generate</span>
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
                        <small class="text-muted">Contoh `1565` akan menghasilkan `1565/SK.02/LPM.DIY/VI/2026`.</small>
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
                        <small class="text-muted">Bagian depan nomor akan diisi otomatis dari `Nomor SK Mulai` dan berlanjut global untuk semua guru.</small>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                    <div class="small text-muted">
                        Tembusan 1 dan 2 tetap dihitung otomatis per sekolah berdasarkan ID madrasah dan kabupaten. Gunakan antrean sekolah sesuai urutan SCOD agar penomoran global berjalan berurutan.
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Data Pokok SK Global</button>
                </div>
            </form>
            <div class="d-flex justify-content-end flex-wrap gap-2 mt-2">
                <form method="POST" action="<?php echo e(route('sk-yayasan.generate.lock-all')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit"
                            class="btn btn-outline-dark"
                            <?php if(!$numberLockSupported || $schools->isEmpty()): echo 'disabled'; endif; ?>
                            onclick="return confirm('Kunci semua nomor SK yang sudah tergenerate pada seluruh antrean sekolah? Nomor yang sudah dikunci tidak akan bisa berubah saat generate ulang.')">
                        Kunci All
                    </button>
                </form>
                <form method="POST" action="<?php echo e(route('sk-yayasan.generate.regenerate-all')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="btn btn-outline-primary"
                            <?php if($schools->isEmpty()): echo 'disabled'; endif; ?>
                            onclick="return confirm('Generate ulang semua sekolah akan menyusun ulang nomor SK sesuai urutan SCOD. Nomor yang sudah dikunci tidak akan diubah. Lanjutkan?')">
                        Generate Ulang All
                    </button>
                </form>
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
                                        <th>Tembusan Otomatis</th>
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
                                            <td class="small">
                                                <div><?php echo e($coreData['copy_recipient_1'] ?? '-'); ?></div>
                                                <div class="text-muted mt-1"><?php echo e($coreData['copy_recipient_2'] ?? '-'); ?></div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="<?php echo e(route('sk-yayasan.generate.school', $school)); ?>" class="btn btn-sm btn-primary">
                                                        Lihat Pengajuan
                                                    </a>
                                                    <form method="POST" action="<?php echo e(route('sk-yayasan.generate.school.lock-number', $school)); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-dark"
                                                                <?php if(!$numberLockSupported || $generatedDocumentsCount === 0 || $allGeneratedLocked): echo 'disabled'; endif; ?>
                                                                onclick="return confirm('Kunci semua nomor SK yang sudah tergenerate untuk sekolah ini? Nomor yang sudah dikunci akan tetap dipakai dan tidak akan diubah saat generate ulang.')">
                                                            Kunci Nomor SK
                                                        </button>
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
                            <i class="bx bx-buildings"></i>
                            <strong>Belum ada sekolah dengan pengajuan tersinkronisasi</strong>
                            <small>Sekolah akan muncul di sini setelah pengajuan SK Yayasannya berhasil melalui proses sinkronisasi batch.</small>
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
                            <h6 class="mb-0">Daftar pengajuan dengan keterangan Pengangkatan PTY dan Pengangkatan GTY dengan TMT 2 tahun ke atas</h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="sky-chip"><?php echo e($appointmentRequests->count()); ?> pengajuan</span>
                            <span class="sky-chip"><?php echo e($appointmentRequests->where('nipm_synced', false)->count()); ?> belum sinkron</span>
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
                                        <th>SCOD</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>Keterangan</th>
                                        <th>NIPM Otomatis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $appointmentRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointmentData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php ($teacherId = data_get($appointmentData, 'teacher_id')); ?>
                                        <?php ($nipmSynced = (bool) data_get($appointmentData, 'nipm_synced', false)); ?>
                                        <?php ($selectedMode = $nipmSynced ? 'system' : old('rows.' . $teacherId . '.nipm_mode', data_get($appointmentData, 'default_nipm_mode', 'system'))); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'submission_year', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'school_scod', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'school_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'teacher_name', '-')); ?></td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info"><?php echo e(data_get($appointmentData, 'keterangan', '-')); ?></span>
                                            </td>
                                            <td style="min-width: 280px;">
                                                <form id="appointment-nipm-sync-<?php echo e($teacherId); ?>" method="POST" action="<?php echo e(route('sk-yayasan.generate.appointment-nipm-sync')); ?>" class="d-none">
                                                    <?php echo csrf_field(); ?>
                                                </form>
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
                                                       class="form-control form-control-sm js-nipm-input"
                                                       value="<?php echo e(old('rows.' . $teacherId . '.nipm', data_get($appointmentData, 'nipm_value', ''))); ?>"
                                                       placeholder="NIPM otomatis"
                                                       inputmode="numeric"
                                                       data-existing-nipm="<?php echo e(data_get($appointmentData, 'existing_nipm_value', '')); ?>"
                                                       data-system-nipm="<?php echo e(data_get($appointmentData, 'system_nipm_value', '')); ?>"
                                                       <?php if($nipmSynced || $selectedMode === 'existing'): echo 'readonly'; endif; ?>>
                                            </td>
                                            <td style="width: 140px;">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nipmSynced): ?>
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                            class="btn btn-sm btn-outline-primary w-100">
                                                        Sinkron Ulang
                                                    </button>
                                                <?php else: ?>
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-<?php echo e($teacherId); ?>"
                                                            class="btn btn-sm btn-primary w-100">
                                                        Sinkron
                                                    </button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                            <span class="sky-chip"><?php echo e($appointmentRequestsUnderTwoYears->where('nipm_synced', false)->count()); ?> belum sinkron</span>
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
                                        <th>SCOD</th>
                                        <th>Nama Sekolah</th>
                                        <th>Nama Guru</th>
                                        <th>Keterangan</th>
                                        <th>NIPM Otomatis</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $appointmentRowsUnderTwoYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointmentData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <?php ($teacherId = data_get($appointmentData, 'teacher_id')); ?>
                                        <?php ($nipmSynced = (bool) data_get($appointmentData, 'nipm_synced', false)); ?>
                                        <?php ($selectedMode = $nipmSynced ? 'system' : old('rows.' . $teacherId . '.nipm_mode', data_get($appointmentData, 'default_nipm_mode', 'system'))); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'submission_year', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'school_scod', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'school_name', '-')); ?></td>
                                            <td><?php echo e(data_get($appointmentData, 'teacher_name', '-')); ?></td>
                                            <td>
                                                <span class="badge bg-warning-subtle text-warning"><?php echo e(data_get($appointmentData, 'keterangan', '-')); ?></span>
                                            </td>
                                            <td style="min-width: 280px;">
                                                <form id="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>" method="POST" action="<?php echo e(route('sk-yayasan.generate.appointment-nipm-sync')); ?>" class="d-none">
                                                    <?php echo csrf_field(); ?>
                                                </form>
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
                                                       class="form-control form-control-sm js-nipm-input"
                                                       value="<?php echo e(old('rows.' . $teacherId . '.nipm', data_get($appointmentData, 'nipm_value', ''))); ?>"
                                                       placeholder="NIPM otomatis"
                                                       inputmode="numeric"
                                                       data-existing-nipm="<?php echo e(data_get($appointmentData, 'existing_nipm_value', '')); ?>"
                                                       data-system-nipm="<?php echo e(data_get($appointmentData, 'system_nipm_value', '')); ?>"
                                                       <?php if($nipmSynced || $selectedMode === 'existing'): echo 'readonly'; endif; ?>>
                                            </td>
                                            <td style="width: 140px;">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nipmSynced): ?>
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                            class="btn btn-sm btn-outline-primary w-100">
                                                        Sinkron Ulang
                                                    </button>
                                                <?php else: ?>
                                                    <button type="submit"
                                                            form="appointment-nipm-sync-under-two-years-<?php echo e($teacherId); ?>"
                                                            class="btn btn-sm btn-primary w-100">
                                                        Sinkron
                                                    </button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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