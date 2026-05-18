<?php $__env->startSection('title'); ?>
    Academica - Proposal
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus', 'mgmp']) && auth()->user()->password_changed;
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAllowed): ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> MGMP <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Academica <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php echo $__env->make('mgmp.partials.ui-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="mgmp-page">
<div class="mgmp-hero-strip mb-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <div class="mgmp-kicker mb-2">Academica</div>
            <h4 class="mb-1">Proposal Akademik MGMP</h4>
            <p class="mb-0 text-white-50">Unggah dan pantau proposal akademik anggota MGMP.</p>
        </div>
        <span class="mgmp-chip bg-white text-success"><?php echo e($proposals->count()); ?> proposal</span>
    </div>
</div>

<div class="card mgmp-panel mb-4">
    <div class="card-body">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <h5 class="mb-2"><?php echo e($userHasUploaded ? 'Proposal Anda' : 'Upload Proposal PDF'); ?></h5>
                <p class="text-muted mb-3">
                    <?php echo e($userHasUploaded ? 'File yang sudah diunggah masih bisa diperbarui dengan file PDF baru.' : 'Unggah proposal akademik dalam format PDF.'); ?>

                </p>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userHasUploaded && $userProposal): ?>
                    <div class="p-3 rounded-3 border bg-light">
                        <div class="d-flex align-items-start gap-3">
                            <div class="mgmp-icon-bubble">
                                <i class="bx bx-file"></i>
                            </div>
                            <div class="grow">
                                <div class="fw-semibold text-dark"><?php echo e($userProposal->filename); ?></div>
                                <small class="text-muted">Terakhir diperbarui <?php echo e($userProposal->updated_at->format('d M Y H:i')); ?></small>
                                <div class="mt-2">
                                    <a href="<?php echo e(url('/uploads/' . $userProposal->path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show"></i> Lihat File
                                    </a>
                                    <button type="button" class="btn btn-sm btn-primary ms-2" id="toggleReplaceProposal">
                                        <i class="bx bx-edit-alt"></i> Edit / Ganti File
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-3 rounded-3 border bg-light">
                        <div class="d-flex align-items-start gap-3">
                            <div class="mgmp-icon-bubble">
                                <i class="bx bx-upload"></i>
                            </div>
                            <div class="grow">
                                <div class="fw-semibold text-dark">Belum ada file proposal</div>
                                <small class="text-muted">Silakan upload proposal pertama Anda dalam format PDF.</small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="col-lg-7">
                <div class="academica-form-panel <?php echo e($userHasUploaded ? 'is-collapsed' : ''); ?>" id="academicaReplacePanel">
                    <h5 class="mb-2"><?php echo e($userHasUploaded ? 'Edit / Ganti Proposal' : 'Form Upload Proposal'); ?></h5>
                    <p class="text-muted mb-3">
                        <?php echo e($userHasUploaded ? 'Pilih file PDF baru untuk mengganti file proposal lama. File lama akan otomatis diperbarui.' : 'File maksimal 10 MB dan wajib berformat PDF.'); ?>

                    </p>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userHasUploaded && $userProposal): ?>
                        <div class="alert alert-info border-0">
                            <div class="fw-semibold mb-1">File saat ini</div>
                            <div><?php echo e($userProposal->filename); ?></div>
                            <small class="text-muted">Saat Anda simpan file baru, file lama akan digantikan.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <form method="POST" action="<?php echo e(route('mgmp.academica.upload')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="proposal" class="form-label">Pilih file PDF proposal</label>
                            <input type="file" name="proposal" id="proposal" accept="application/pdf" class="form-control" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proposal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary">
                                <i class="bx <?php echo e($userHasUploaded ? 'bx-refresh' : 'bx-upload'); ?>"></i>
                                <?php echo e($userHasUploaded ? 'Simpan File Baru' : 'Upload Proposal'); ?>

                            </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userHasUploaded): ?>
                                <button type="button" class="btn btn-outline-secondary" id="cancelReplaceProposal">
                                    <i class="bx bx-x"></i> Batal
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </form>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userHasUploaded): ?>
                    <div class="academica-placeholder-panel" id="academicaReplacePlaceholder">
                        <div class="p-4 rounded-3 border bg-light h-100 d-flex flex-column justify-content-center">
                            <h5 class="mb-2">Edit / Ganti Proposal</h5>
                            <p class="text-muted mb-3">Klik tombol <strong>Edit / Ganti File</strong> di sebelah kiri untuk mengganti file proposal yang sudah diupload.</p>
                            <div>
                                <button type="button" class="btn btn-primary" id="openReplaceProposalFromPlaceholder">
                                    <i class="bx bx-refresh"></i> Ganti File Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

    </div>
</div>

<div class="card mgmp-panel mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h5 class="mb-1">Update Reset</h5>
                <p class="text-muted mb-0">Catat progres pengerjaan reset dan unggah beberapa file pendukung dalam satu laporan.</p>
            </div>
            <span class="mgmp-chip"><?php echo e(isset($resetUpdates) ? $resetUpdates->count() : 0); ?> update</span>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userHasUploaded && $userProposal): ?>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <h6 class="mb-3">Form Update Reset</h6>
                        <form method="POST" action="<?php echo e(route('mgmp.academica.reset-update.store')); ?>" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="mb-3">
                                <label for="reset_title" class="form-label">Judul update</label>
                                <input
                                    type="text"
                                    name="title"
                                    id="reset_title"
                                    class="form-control"
                                    value="<?php echo e(old('title')); ?>"
                                    placeholder="Contoh: Progress revisi bab 2"
                                    required
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="progress_percent" class="form-label">Progres pengerjaan (%)</label>
                                <input
                                    type="number"
                                    min="0"
                                    max="100"
                                    name="progress_percent"
                                    id="progress_percent"
                                    class="form-control"
                                    value="<?php echo e(old('progress_percent', 0)); ?>"
                                    required
                                >
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['progress_percent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="progress_note" class="form-label">Keterangan progres</label>
                                <textarea
                                    name="progress_note"
                                    id="progress_note"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Jelaskan sudah sampai tahap mana reset dikerjakan, kendala, atau target berikutnya."
                                    required
                                ><?php echo e(old('progress_note')); ?></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['progress_note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="reset_attachments" class="form-label">Lampiran pendukung</label>
                                <input
                                    type="file"
                                    name="attachments[]"
                                    id="reset_attachments"
                                    class="form-control"
                                    multiple
                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip"
                                >
                                <small class="text-muted d-block mt-1">
                                    Boleh upload lebih dari satu file. Format umum dokumen/gambar, maksimal 10 MB per file.
                                </small>
                                <small class="text-muted d-block mt-1" id="resetAttachmentInfo"></small>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['attachments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <button class="btn btn-primary">
                                <i class="bx bx-save"></i> Simpan Update Reset
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Riwayat Progres Reset</h6>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($resetUpdates) && $resetUpdates->isNotEmpty()): ?>
                            <small class="text-muted">Terbaru: <?php echo e($resetUpdates->first()->progress_percent); ?>%</small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($resetUpdates) && $resetUpdates->isNotEmpty()): ?>
                        <div class="academica-reset-list">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $resetUpdates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="academica-reset-card">
                                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-2">
                                        <div>
                                            <h6 class="mb-1"><?php echo e($update->title); ?></h6>
                                            <small class="text-muted">
                                                <?php echo e($update->created_at->format('d M Y H:i')); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->updated_at && $update->updated_at->ne($update->created_at)): ?>
                                                    • diperbarui <?php echo e($update->updated_at->format('d M Y H:i')); ?>

                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </small>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary"><?php echo e($update->progress_percent); ?>%</span>
                                    </div>

                                    <div class="academica-progress-track mb-3">
                                        <div class="academica-progress-bar" style="width: <?php echo e(max(0, min(100, (int) $update->progress_percent))); ?>%;"></div>
                                    </div>

                                    <p class="text-muted mb-3"><?php echo e($update->progress_note); ?></p>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->files->isNotEmpty()): ?>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $update->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <a href="<?php echo e(url('/uploads/' . $file->path)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-paperclip me-1"></i><?php echo e(\Illuminate\Support\Str::limit($file->original_name, 28)); ?>

                                                </a>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-muted">Tidak ada lampiran pada update ini.</small>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="mgmp-empty-state py-5">
                            <i class="bx bx-timer"></i>
                            <strong>Belum ada update reset</strong>
                            <small>Tambahkan progres reset pertama Anda agar riwayat pengerjaan mulai tercatat.</small>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning mb-0">
                <i class="bx bx-info-circle me-2"></i>Upload proposal utama terlebih dahulu. Setelah itu barulah Anda bisa menambahkan update reset dan lampiran progres.
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>


</div>

<?php else: ?>
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    $('#reset_attachments').on('change', function () {
        const count = this.files ? this.files.length : 0;
        $('#resetAttachmentInfo').text(count > 0 ? count + ' file dipilih.' : '');
    });

    function openReplacePanel() {
        $('#academicaReplacePanel').removeClass('is-collapsed');
        $('#academicaReplacePlaceholder').hide();
        $('#proposal').trigger('focus');
    }

    function closeReplacePanel() {
        $('#academicaReplacePanel').addClass('is-collapsed');
        $('#academicaReplacePlaceholder').show();
        $('#proposal').val('');
    }

    $('#toggleReplaceProposal, #openReplaceProposalFromPlaceholder').on('click', function () {
        openReplacePanel();
    });

    $('#cancelReplaceProposal').on('click', function () {
        closeReplacePanel();
    });

    if ($.fn.DataTable.isDataTable('#datatable-academica')) {
        $('#datatable-academica').DataTable().destroy();
    }

    let table = $("#datatable-academica").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        destroy: true,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-academica_wrapper .col-md-6:eq(0)');
});
</script>

<style>
    .academica-form-panel.is-collapsed {
        display: none;
    }

    .academica-placeholder-panel {
        display: block;
    }

    .academica-reset-list {
        display: grid;
        gap: 14px;
    }

    .academica-reset-card {
        background: linear-gradient(180deg, #ffffff 0%, #f7fbf8 100%);
        border: 1px solid #e5eee9;
        border-radius: 16px;
        padding: 16px;
    }

    .academica-progress-track {
        background: #e8f1ec;
        border-radius: 999px;
        height: 10px;
        overflow: hidden;
    }

    .academica-progress-bar {
        background: linear-gradient(90deg, #004b4c, #0e8549);
        border-radius: 999px;
        height: 100%;
    }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mgmp/academica.blade.php ENDPATH**/ ?>