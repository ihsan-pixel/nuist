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
                    Pilih nama sekolah untuk melihat daftar pengajuan SK Yayasan yang siap dibuat draft PDF sesuai template masing-masing.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($schools->total()); ?> sekolah</span>
                <span class="sky-chip bg-white bg-opacity-10 border-0 text-white"><?php echo e($totalRequestsCount); ?> pengajuan</span>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <div class="sky-panel-label mb-1">Data Pokok SK</div>
                    <h6 class="mb-0">Ringkasan data pokok SK per sekolah dalam antrean generate</h6>
                </div>
                <span class="sky-chip"><?php echo e($schools->count()); ?> sekolah di halaman ini</span>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schools->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Sekolah</th>
                                <th>Tahun SK</th>
                                <th>Nomor SK Mulai</th>
                                <th>Ketua Yayasan</th>
                                <th>Ditetapkan</th>
                                <th>Tembusan 1</th>
                                <th>Tembusan 2</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <?php ($coreData = $school->core_data ?? []); ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            <a href="<?php echo e(route('sk-yayasan.generate.school', $school)); ?>" class="text-decoration-none">
                                                <?php echo e($school->name); ?>

                                            </a>
                                        </div>
                                        <small class="text-muted"><?php echo e(number_format($school->generate_requests_count)); ?> pengajuan</small>
                                    </td>
                                    <td><?php echo e($coreData['school_year'] ?? '-'); ?></td>
                                    <td><?php echo e($coreData['document_number_start'] ?: '-'); ?></td>
                                    <td><?php echo e($coreData['signer_name'] ?: '-'); ?></td>
                                    <td>
                                        <div><?php echo e($coreData['established_at'] ?? '-'); ?></div>
                                        <small class="text-muted">
                                            <?php echo e(!empty($coreData['issued_date']) ? \Illuminate\Support\Carbon::parse($coreData['issued_date'])->translatedFormat('d F Y') : '-'); ?>

                                        </small>
                                    </td>
                                    <td class="small"><?php echo e($coreData['copy_recipient_1'] ?? '-'); ?></td>
                                    <td class="small"><?php echo e($coreData['copy_recipient_2'] ?? '-'); ?></td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="sky-empty-state py-4">
                    <i class="bx bx-detail"></i>
                    <strong>Belum ada data pokok SK</strong>
                    <small>Data pokok SK akan muncul setelah ada sekolah yang masuk antrean generate.</small>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <div class="sky-panel-label mb-1">Antrean Sekolah</div>
                            <h6 class="mb-0">Klik sekolah untuk membuka daftar pengajuan</h6>
                        </div>
                        <span class="sky-chip"><?php echo e($schools->total()); ?> sekolah</span>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schools->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Nama Sekolah</th>
                                        <th>SCOD</th>
                                        <th>Antrean</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
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
                                            <td>
                                                <a href="<?php echo e(route('sk-yayasan.generate.school', $school)); ?>" class="btn btn-sm btn-primary">
                                                    Lihat Pengajuan
                                                </a>
                                            </td>
                                        </tr>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="sky-empty-state py-5">
                            <i class="bx bx-buildings"></i>
                            <strong>Belum ada sekolah dalam antrean generate</strong>
                            <small>Sekolah akan muncul di sini setelah memiliki pengajuan yang disetujui atau batch yang sudah tersinkronisasi.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schools->hasPages()): ?>
                    <div class="card-footer bg-white">
                        <?php echo e($schools->links()); ?>

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