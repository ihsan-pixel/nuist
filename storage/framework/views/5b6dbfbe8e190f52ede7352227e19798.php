<?php $__env->startSection('title', 'Edit Profil Madrasah - ' . $madrasah->name); ?>

<?php $__env->startPush('css'); ?>
<style>
    .form-section {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: none;
    }

    .section-header {
        border-bottom: 2px solid #004b4c;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        color: #004b4c;
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #004b4c;
        box-shadow: 0 0 0 0.2rem rgba(0, 75, 76, 0.25);
    }

    .array-input-container {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }

    .array-input-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .array-input-item input {
        flex: 1;
        margin-right: 0.5rem;
    }

    .btn-add-array {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .btn-add-array:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        color: white;
    }

    .btn-remove-array {
        background: #dc3545;
        border: none;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .file-upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        background: #f8f9fa;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-upload-area:hover {
        border-color: #004b4c;
        background: rgba(0, 75, 76, 0.05);
    }

    .file-upload-area.dragover {
        border-color: #004b4c;
        background: rgba(0, 75, 76, 0.1);
    }

    .btn-submit {
        background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
        color: white;
    }

    .btn-cancel {
        background: #6c757d;
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        margin-right: 1rem;
    }

    .btn-cancel:hover {
        background: #5a6268;
        color: white;
    }

    .required-field::after {
        content: ' *';
        color: #dc3545;
        font-weight: bold;
    }

    .help-text {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }

    .image-preview {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
        border-radius: 4px;
        margin: 0.25rem;
    }

    .image-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .form-section {
            padding: 1rem;
        }

        .btn-submit, .btn-cancel {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .btn-cancel {
            margin-right: 0;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="mdi mdi-pencil me-2 text-primary"></i>
                Edit Profil Madrasah
            </h2>
            <p class="text-muted mb-0">Lengkapi informasi profil <?php echo e($madrasah->name); ?></p>
        </div>
        <a href="<?php echo e(route('ppdb.lp.dashboard')); ?>" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left me-1"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('ppdb.lp.update', $madrasah->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Informasi Dasar -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-information-outline me-2"></i>Informasi Dasar
                </h3>
                <p class="section-subtitle">Informasi pokok tentang madrasah</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label required-field">Nama Madrasah</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="name" name="name" value="<?php echo e(old('name', $madrasah->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kabupaten" class="form-label required-field">Kabupaten</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['kabupaten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="kabupaten" name="kabupaten" value="<?php echo e(old('kabupaten', $madrasah->kabupaten)); ?>" required>
                        <?php $__errorArgs = ['kabupaten'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="alamat" class="form-label required-field">Alamat Lengkap</label>
                <textarea class="form-control <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="alamat" name="alamat" rows="3" required><?php echo e(old('alamat', $madrasah->alamat)); ?></textarea>
                <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tagline" class="form-label">Tagline</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="tagline" name="tagline" value="<?php echo e(old('tagline', $madrasah->tagline)); ?>"
                               placeholder="Contoh: Madrasah Unggul di Bidang Teknologi">
                        <?php $__errorArgs = ['tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="akreditasi" class="form-label">Akreditasi</label>
                        <select class="form-select <?php $__errorArgs = ['akreditasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="akreditasi" name="akreditasi">
                            <option value="">Pilih Akreditasi</option>
                            <option value="A" <?php echo e(old('akreditasi', $madrasah->akreditasi) == 'A' ? 'selected' : ''); ?>>A (Unggul)</option>
                            <option value="B" <?php echo e(old('akreditasi', $madrasah->akreditasi) == 'B' ? 'selected' : ''); ?>>B (Baik)</option>
                            <option value="C" <?php echo e(old('akreditasi', $madrasah->akreditasi) == 'C' ? 'selected' : ''); ?>>C (Cukup)</option>
                        </select>
                        <?php $__errorArgs = ['akreditasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tahun_berdiri" class="form-label">Tahun Berdiri</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['tahun_berdiri'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="tahun_berdiri" name="tahun_berdiri" value="<?php echo e(old('tahun_berdiri', $madrasah->tahun_berdiri)); ?>"
                               min="1800" max="<?php echo e(date('Y') + 1); ?>">
                        <?php $__errorArgs = ['tahun_berdiri'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="telepon" class="form-label">Telepon</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="telepon" name="telepon" value="<?php echo e(old('telepon', $madrasah->telepon)); ?>"
                               placeholder="Contoh: (021) 1234567">
                        <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="email" name="email" value="<?php echo e(old('email', $madrasah->email)); ?>"
                               placeholder="info@madrasah.com">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="website" name="website" value="<?php echo e(old('website', $madrasah->website)); ?>"
                               placeholder="https://www.madrasah.com">
                        <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profil Madrasah -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-school me-2"></i>Profil Madrasah
                </h3>
                <p class="section-subtitle">Deskripsi dan informasi detail tentang madrasah</p>
            </div>

            <div class="form-group">
                <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                <textarea class="form-control <?php $__errorArgs = ['deskripsi_singkat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="deskripsi_singkat" name="deskripsi_singkat" rows="3"
                          placeholder="Deskripsi singkat tentang madrasah dalam 1-2 paragraf"><?php echo e(old('deskripsi_singkat', $madrasah->deskripsi_singkat)); ?></textarea>
                <?php $__errorArgs = ['deskripsi_singkat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="sejarah" class="form-label">Sejarah Madrasah</label>
                <textarea class="form-control <?php $__errorArgs = ['sejarah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="sejarah" name="sejarah" rows="4"
                          placeholder="Ceritakan sejarah berdirinya madrasah"><?php echo e(old('sejarah', $madrasah->sejarah)); ?></textarea>
                <?php $__errorArgs = ['sejarah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="nilai_nilai" class="form-label">Nilai-Nilai</label>
                <textarea class="form-control <?php $__errorArgs = ['nilai_nilai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="nilai_nilai" name="nilai_nilai" rows="3"
                          placeholder="Nilai-nilai yang dianut oleh madrasah"><?php echo e(old('nilai_nilai', $madrasah->nilai_nilai)); ?></textarea>
                <?php $__errorArgs = ['nilai_nilai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="visi" class="form-label">Visi</label>
                <textarea class="form-control <?php $__errorArgs = ['visi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="visi" name="visi" rows="3"
                          placeholder="Visi madrasah"><?php echo e(old('visi', $madrasah->visi)); ?></textarea>
                <?php $__errorArgs = ['visi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Misi -->
            <div class="form-group">
                <label class="form-label">Misi</label>
                <div id="misi-container" class="array-input-container">
                    <?php $misiArray = old('misi', $madrasah->misi ?? []); ?>
                    <?php if(is_array($misiArray) && count($misiArray) > 0): ?>
                        <?php $__currentLoopData = $misiArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $misi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="array-input-item">
                                <input type="text" class="form-control <?php $__errorArgs = ['misi.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="misi[]" value="<?php echo e($misi); ?>" placeholder="Poin misi">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="misi[]" placeholder="Poin misi">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="misi-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Misi
                </button>
                <?php $__errorArgs = ['misi.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>


        </div>

        

        <!-- Fasilitas dan Keunggulan -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-star me-2"></i>Fasilitas & Keunggulan
                </h3>
                <p class="section-subtitle">Fasilitas yang tersedia dan keunggulan madrasah</p>
            </div>

            <!-- Fasilitas -->
            <div class="form-group">
                <label class="form-label">Fasilitas</label>
                <div id="fasilitas-container" class="array-input-container">
                    <?php $fasilitasArray = old('fasilitas', $madrasah->fasilitas ?? []); ?>
                    <?php if(is_array($fasilitasArray) && count($fasilitasArray) > 0): ?>
                        <?php $__currentLoopData = $fasilitasArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $fasilitas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="array-input-item">
                                <input type="text" class="form-control <?php $__errorArgs = ['fasilitas.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="fasilitas[]" value="<?php echo e($fasilitas); ?>" placeholder="Contoh: Laboratorium Komputer">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="fasilitas[]" placeholder="Contoh: Laboratorium Komputer">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="fasilitas-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Fasilitas
                </button>
                <?php $__errorArgs = ['fasilitas.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Keunggulan -->
            <div class="form-group">
                <label class="form-label">Keunggulan</label>
                <div id="keunggulan-container" class="array-input-container">
                    <?php $keunggulanArray = old('keunggulan', $madrasah->keunggulan ?? []); ?>
                    <?php if(is_array($keunggulanArray) && count($keunggulanArray) > 0): ?>
                        <?php $__currentLoopData = $keunggulanArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $keunggulan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="array-input-item">
                                <input type="text" class="form-control <?php $__errorArgs = ['keunggulan.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="keunggulan[]" value="<?php echo e($keunggulan); ?>" placeholder="Keunggulan madrasah">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="keunggulan[]" placeholder="Keunggulan madrasah">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="keunggulan-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Keunggulan
                </button>
                <?php $__errorArgs = ['keunggulan.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Jurusan -->
            <div class="form-group">
                <label class="form-label">Jurusan</label>
                <div id="jurusan-container" class="array-input-container">
                    <?php $jurusanArray = old('jurusan', $madrasah->jurusan ?? []); ?>
                    <?php if(is_array($jurusanArray) && count($jurusanArray) > 0): ?>
                        <?php $__currentLoopData = $jurusanArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $jurusan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="array-input-item">
                                <input type="text" class="form-control <?php $__errorArgs = ['jurusan.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="jurusan[]" value="<?php echo e($jurusan); ?>" placeholder="Contoh: Teknik Informatika">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="jurusan[]" placeholder="Contoh: Teknik Informatika">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="jurusan-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Jurusan
                </button>
                <?php $__errorArgs = ['jurusan.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Prestasi -->
            <div class="form-group">
                <label class="form-label">Prestasi</label>
                <div id="prestasi-container" class="array-input-container">
                    <?php $prestasiArray = old('prestasi', $madrasah->prestasi ?? []); ?>
                    <?php if(is_array($prestasiArray) && count($prestasiArray) > 0): ?>
                        <?php $__currentLoopData = $prestasiArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $prestasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="array-input-item">
                                <input type="text" class="form-control <?php $__errorArgs = ['prestasi.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="prestasi[]" value="<?php echo e($prestasi); ?>" placeholder="Contoh: Juara 1 Lomba Matematika Tingkat Nasional">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="prestasi[]" placeholder="Contoh: Juara 1 Lomba Matematika Tingkat Nasional">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="prestasi-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Prestasi
                </button>
                <?php $__errorArgs = ['prestasi.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Program Unggulan -->
            <div class="form-group">
                <label class="form-label">Program Unggulan</label>
                <div id="program_unggulan-container" class="array-input-container">
                    <?php $programUnggulanArray = old('program_unggulan', $madrasah->program_unggulan ?? []); ?>
                    <?php if(is_array($programUnggulanArray) && count($programUnggulanArray) > 0): ?>
                        <?php $__currentLoopData = $programUnggulanArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="array-input-item">
                                <input type="text" class="form-control <?php $__errorArgs = ['program_unggulan.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="program_unggulan[]" value="<?php echo e($program); ?>" placeholder="Contoh: Program Tahfidz Al-Quran">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="program_unggulan[]" placeholder="Contoh: Program Tahfidz Al-Quran">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="program_unggulan-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Program Unggulan
                </button>
                <?php $__errorArgs = ['program_unggulan.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Ekstrakurikuler -->
            <div class="form-group">
                <label class="form-label">Ekstrakurikuler</label>
                <div id="ekstrakurikuler-container" class="array-input-container">
                    <?php $ekstrakurikulerArray = old('ekstrakurikuler', $madrasah->ekstrakurikuler ?? []); ?>
                    <?php if(is_array($ekstrakurikulerArray) && count($ekstrakurikulerArray) > 0): ?>
                        <?php $__currentLoopData = $ekstrakurikulerArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ekstra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="array-input-item">
                                <input type="text" class="form-control <?php $__errorArgs = ['ekstrakurikuler.' . $index];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       name="ekstrakurikuler[]" value="<?php echo e($ekstra); ?>" placeholder="Contoh: Pramuka, Futsal, Basket">
                                <button type="button" class="btn btn-remove-array remove-array-item">
                                    <i class="mdi mdi-minus"></i>
                                </button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <div class="array-input-item">
                        <input type="text" class="form-control" name="ekstrakurikuler[]" placeholder="Contoh: Pramuka, Futsal, Basket">
                        <button type="button" class="btn btn-remove-array remove-array-item">
                            <i class="mdi mdi-minus"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-add-array add-array-item text-white" data-target="ekstrakurikuler-container">
                    <i class="mdi mdi-plus me-1"></i>Tambah Ekstrakurikuler
                </button>
                <?php $__errorArgs = ['ekstrakurikuler.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Kepala Sekolah -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-account-tie me-2"></i>Kepala Sekolah
                </h3>
                <p class="section-subtitle">Informasi tentang kepala sekolah</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kepala_sekolah_nama" class="form-label">Nama Kepala Sekolah</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['kepala_sekolah_nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="kepala_sekolah_nama" name="kepala_sekolah_nama"
                               value="<?php echo e(old('kepala_sekolah_nama', $madrasah->kepala_sekolah_nama)); ?>"
                               placeholder="Dr. H. Ahmad Susanto, M.Pd.">
                        <?php $__errorArgs = ['kepala_sekolah_nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="kepala_sekolah_gelar" class="form-label">Gelar</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['kepala_sekolah_gelar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="kepala_sekolah_gelar" name="kepala_sekolah_gelar"
                               value="<?php echo e(old('kepala_sekolah_gelar', $madrasah->kepala_sekolah_gelar)); ?>"
                               placeholder="M.Pd., S.Pd., dll">
                        <?php $__errorArgs = ['kepala_sekolah_gelar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="kepala_sekolah_sambutan" class="form-label">Sambutan Kepala Sekolah</label>
                <textarea class="form-control <?php $__errorArgs = ['kepala_sekolah_sambutan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          id="kepala_sekolah_sambutan" name="kepala_sekolah_sambutan" rows="4"
                          placeholder="Sambutan dari kepala sekolah"><?php echo e(old('kepala_sekolah_sambutan', $madrasah->kepala_sekolah_sambutan)); ?></textarea>
                <?php $__errorArgs = ['kepala_sekolah_sambutan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Statistik -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-chart-bar me-2"></i>Statistik Madrasah
                </h3>
                <p class="section-subtitle">Data jumlah siswa, guru, dan sarana prasarana</p>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_siswa" class="form-label">Jumlah Siswa</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['jumlah_siswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="jumlah_siswa" name="jumlah_siswa" value="<?php echo e(old('jumlah_siswa', $madrasah->jumlah_siswa)); ?>" min="0">
                        <?php $__errorArgs = ['jumlah_siswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_guru" class="form-label">Jumlah Guru</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['jumlah_guru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="jumlah_guru" name="jumlah_guru" value="<?php echo e(old('jumlah_guru', $jumlahGuru ?? $madrasah->jumlah_guru ?? '')); ?>" min="0" readonly>
                        <small class="text-muted">Jumlah guru dihitung otomatis dari data tenaga pendidik</small>
                        <?php $__errorArgs = ['jumlah_guru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_jurusan" class="form-label">Jumlah Jurusan</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['jumlah_jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="jumlah_jurusan" name="jumlah_jurusan" value="<?php echo e(old('jumlah_jurusan', $madrasah->jumlah_jurusan)); ?>" min="0" readonly>
                        <div class="help-text">Otomatis dihitung dari jumlah jurusan yang diisi</div>
                        <?php $__errorArgs = ['jumlah_jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="jumlah_sarana" class="form-label">Jumlah Sarana</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['jumlah_sarana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="jumlah_sarana" name="jumlah_sarana" value="<?php echo e(old('jumlah_sarana', $madrasah->jumlah_sarana)); ?>" min="0">
                        <?php $__errorArgs = ['jumlah_sarana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Media dan Dokumen -->
        <div class="form-section">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="mdi mdi-camera me-2"></i>Media & Dokumen
                </h3>
                <p class="section-subtitle">Upload gambar galeri dan dokumen pendukung</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="video_profile" class="form-label">Link Video Profile</label>
                        <input type="url" class="form-control <?php $__errorArgs = ['video_profile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="video_profile" name="video_profile" value="<?php echo e(old('video_profile', $madrasah->video_profile)); ?>"
                               placeholder="https://youtube.com/watch?v=...">
                        <div class="help-text">Link YouTube atau video lainnya</div>
                        <?php $__errorArgs = ['video_profile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="brosur_pdf" class="form-label">Upload Brosur (PDF)</label>
                        <input type="file" class="form-control <?php $__errorArgs = ['brosur_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="brosur_pdf" name="brosur_pdf" accept=".pdf">
                        <div class="help-text">Maksimal 5MB, format PDF</div>
                        <?php $__errorArgs = ['brosur_pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Galeri Foto</label>
                <div class="file-upload-area" id="galeri-upload-area">
                    <i class="mdi mdi-cloud-upload fs-1 text-muted mb-2"></i>
                    <p class="mb-1">Klik untuk upload gambar galeri</p>
                    <small class="text-muted">atau drag & drop file gambar (JPG, PNG, GIF) - Maksimal 2MB per file</small>
                    <input type="file" id="galeri_foto" name="galeri_foto[]" multiple accept="image/*" style="display: none;">
                </div>
                <?php $__errorArgs = ['galeri_foto.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <!-- Preview existing images -->
                <?php if($madrasah->galeri_foto && is_array($madrasah->galeri_foto)): ?>
                    <div class="image-gallery" id="existing-gallery">
                        <?php $__currentLoopData = $madrasah->galeri_foto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img src="<?php echo e(asset('images/madrasah/galeri/' . $image)); ?>" alt="Galeri" class="image-preview">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <!-- Preview new images -->
                <div class="image-gallery" id="new-gallery"></div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="form-section">
            <div class="d-flex justify-content-end">
                <a href="<?php echo e(route('ppdb.lp.dashboard')); ?>" class="btn btn-cancel">
                    <i class="mdi mdi-close me-1"></i>Batal
                </a>
                <button type="submit" class="btn btn-submit text-white">
                    <i class="mdi mdi-content-save me-1"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Array input management
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-array-item') || e.target.closest('.add-array-item')) {
            const button = e.target.classList.contains('add-array-item') ? e.target : e.target.closest('.add-array-item');
            const targetId = button.getAttribute('data-target');
            const container = document.getElementById(targetId);

            const newItem = document.createElement('div');
            newItem.className = 'array-input-item';
            newItem.innerHTML = `
                <input type="text" class="form-control" name="${targetId.replace('-container', '[]')}" placeholder="${getPlaceholderText(targetId)}">
                <button type="button" class="btn btn-remove-array remove-array-item">
                    <i class="mdi mdi-minus"></i>
                </button>
            `;

            // Insert before the last item (which should be empty)
            const items = container.querySelectorAll('.array-input-item');
            if (items.length > 0) {
                container.insertBefore(newItem, items[items.length - 1]);
            } else {
                container.appendChild(newItem);
            }
        }

        if (e.target.classList.contains('remove-array-item') || e.target.closest('.remove-array-item')) {
            const item = e.target.closest('.array-input-item');
            if (item) {
                item.remove();
            }
        }
    });

    function getPlaceholderText(containerId) {
        const placeholders = {
            'misi-container': 'Poin misi',
            'fasilitas-container': 'Contoh: Laboratorium Komputer',
            'keunggulan-container': 'Keunggulan madrasah',
            'jurusan-container': 'Contoh: Teknik Informatika',
            'prestasi-container': 'Contoh: Juara 1 Lomba Matematika Tingkat Nasional',
            'program_unggulan-container': 'Contoh: Program Tahfidz Al-Quran',
            'ekstrakurikuler-container': 'Contoh: Pramuka, Futsal, Basket',
            'ppdb-quota-jurusan-container': 'Nama Jurusan',
            'ppdb-jalur-container': 'Contoh: Jalur Prestasi, Jalur Reguler'
        };
        return placeholders[containerId] || 'Masukkan teks';
    }



    // File upload handling
    const uploadArea = document.getElementById('galeri-upload-area');
    const fileInput = document.getElementById('galeri_foto');
    const newGallery = document.getElementById('new-gallery');

    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview';
                    newGallery.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    }

    // Auto-update jumlah jurusan
    function updateJumlahJurusan() {
        const jurusanInputs = document.querySelectorAll('input[name="jurusan[]"]');
        let count = 0;
        jurusanInputs.forEach(input => {
            if (input.value.trim() !== '') {
                count++;
            }
        });
        document.getElementById('jumlah_jurusan').value = count;
    }

    // Listen for changes in jurusan inputs
    document.addEventListener('input', function(e) {
        if (e.target.name === 'jurusan[]') {
            updateJumlahJurusan();
        }
    });

    // Also listen for array item additions/removals
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-array-item') || e.target.classList.contains('remove-array-item') ||
            e.target.closest('.add-array-item') || e.target.closest('.remove-array-item')) {
            setTimeout(updateJumlahJurusan, 100); // Small delay to ensure DOM updates
        }
    });

    // Initialize on page load
    updateJumlahJurusan();

    // Copy to clipboard function
    window.copyToClipboard = function(event, text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show success message
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="mdi mdi-check me-1"></i>Berhasil Disalin!';
            button.classList.remove('btn-outline-secondary');
            button.classList.add('btn-success');

            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
        });
    }

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Clean up empty array inputs
        const arrayInputs = form.querySelectorAll('input[name$="[]"]');
        arrayInputs.forEach(input => {
            if (!input.value.trim()) {
                input.remove();
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/ppdb/dashboard/lp-edit.blade.php ENDPATH**/ ?>