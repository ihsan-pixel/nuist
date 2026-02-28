<?php $__env->startSection('title', 'Edit Menu Talenta'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/mobile/talenta.css')); ?>">

<style>
    body {
    background: #f8f9fb url('/images/bg.png') no-repeat center center;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
}
</style>

<!-- Header -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='<?php echo e(route('mobile.talenta.show', $talenta->id)); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>EDIT MENU TALENTA</h4>
    <p>Update Data Peserta</p>
</div>

<!-- Form Container -->
<div class="form-container">
    <!-- Success Alert will be shown via SweetAlert -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div id="success-message" data-message="<?php echo e(session('success')); ?>" style="display: none;"></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Auto-save Indicator -->
    <div id="auto-save-indicator" class="auto-save-indicator" style="display: none;">
        <i class="bx bx-save"></i> Draft tersimpan
    </div>

    <!-- Error Messages -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="error-container">
            <ul class="error-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <li><?php echo e($error); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Progress Bar -->
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill" style="width: 25%;"></div>
        </div>
        <div class="progress-text">
            <span id="progress-percentage">25%</span>
            <span id="progress-step">Step 1 dari 4</span>
        </div>
    </div>

    <form action="<?php echo e(route('mobile.talenta.update', $talenta->id)); ?>" method="POST" enctype="multipart/form-data" id="talenta-form">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Hidden inputs -->
        <input type="hidden" name="tahun_pelaporan" value="<?php echo e($talenta->tahun_pelaporan ?? date('Y')); ?>">
        <input type="hidden" name="status" value="draft" id="form-status">

        <!-- Step 1: B. MENU UPDATE TALENTA - TPT -->
        <div class="step-content active" data-step="1">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-user"></i>
                    </div>
                    <h6 class="section-title">UPDATE TALENTA - TPT</h6>
                </div>

                <div class="section-content">
                    <!-- Disabled Notice -->
                    <div class="info-note" style="background: #fff3cd; border-color: #ffeaa7; color: #856404; margin-bottom: 20px;">
                        <i class="bx bx-info-circle" style="margin-right: 8px;"></i>
                        <strong>Update data level TPT belum bisa dilakukan sesuai jadwal TPT</strong>
                        <br>
                        <small>Semua kolom pada step ini tidak dapat diisi untuk sementara waktu.</small>
                    </div>

                    <!-- TPT Level 1 - Collapsible -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(1)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 1</span>
                        <i class="bx bx-chevron-down" id="icon-1" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-1" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 1</label>
                            <input type="text" name="nomor_talenta_1" value="<?php echo e(old('nomor_talenta_1', $talenta->nomor_talenta_1)); ?>" placeholder="Nomor Talenta 1" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nomor_talenta_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_1" value="<?php echo e(old('skor_penilaian_1', $talenta->skor_penilaian_1)); ?>" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['skor_penilaian_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 1 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_1" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_1): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->sertifikat_tpt_1)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['sertifikat_tpt_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 1 (PDF)</label>
                            <input type="file" name="produk_unggulan_1" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_1): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->produk_unggulan_1)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['produk_unggulan_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- TPT Level 2 -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(2)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 2</span>
                        <i class="bx bx-chevron-down" id="icon-2" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-2" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 2</label>
                            <input type="text" name="nomor_talenta_2" value="<?php echo e(old('nomor_talenta_2', $talenta->nomor_talenta_2)); ?>" placeholder="Nomor Talenta 2" disabled style="background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_2" value="<?php echo e(old('skor_penilaian_2', $talenta->skor_penilaian_2)); ?>" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 2 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_2" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_2): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->sertifikat_tpt_2)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 2 (PDF)</label>
                            <input type="file" name="produk_unggulan_2" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_2): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->produk_unggulan_2)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- TPT Level 3 -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(3)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 3</span>
                        <i class="bx bx-chevron-down" id="icon-3" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-3" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 3</label>
                            <input type="text" name="nomor_talenta_3" value="<?php echo e(old('nomor_talenta_3', $talenta->nomor_talenta_3)); ?>" placeholder="Nomor Talenta 3" disabled style="background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_3" value="<?php echo e(old('skor_penilaian_3', $talenta->skor_penilaian_3)); ?>" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 3 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_3" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_3): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->sertifikat_tpt_3)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 3 (PDF)</label>
                            <input type="file" name="produk_unggulan_3" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_3): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->produk_unggulan_3)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- TPT Level 4 -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(4)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 4 (Diklat Cakep)</span>
                        <i class="bx bx-chevron-down" id="icon-4" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-4" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 4</label>
                            <input type="text" name="nomor_talenta_4" value="<?php echo e(old('nomor_talenta_4', $talenta->nomor_talenta_4)); ?>" placeholder="Nomor Talenta 4" disabled style="background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_4" value="<?php echo e(old('skor_penilaian_4', $talenta->skor_penilaian_4)); ?>" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 4 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_4" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_4): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->sertifikat_tpt_4)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 4 (PDF)</label>
                            <input type="file" name="produk_unggulan_4" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_4): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->produk_unggulan_4)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- TPT Level 5 -->
                    <div class="tpt-level-header" onclick="toggleTptLevel(5)" style="cursor: pointer; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #dee2e6;">
                        <span style="font-weight: 600; color: #004b4c;">TPT LEVEL 5 (Training Lanjut Khusus-Case)</span>
                        <i class="bx bx-chevron-down" id="icon-5" style="transition: transform 0.3s;"></i>
                    </div>
                    <div class="tpt-level-content" id="tpt-level-5" style="display: none; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px; border: 1px solid #dee2e6;">
                        <div class="form-group">
                            <label>Nomor Talenta 5</label>
                            <input type="text" name="nomor_talenta_5" value="<?php echo e(old('nomor_talenta_5', $talenta->nomor_talenta_5)); ?>" placeholder="Nomor Talenta 5" disabled style="background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Input Skor Penilaian</label>
                            <input type="number" name="skor_penilaian_5" value="<?php echo e(old('skor_penilaian_5', $talenta->skor_penilaian_5)); ?>" min="0" max="100" placeholder="0" disabled style="background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label>Upload Sertifikat TPT Level 5 (PDF)</label>
                            <input type="file" name="sertifikat_tpt_5" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_5): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->sertifikat_tpt_5)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label>Upload Produk Unggulan Level 5 (PDF)</label>
                            <input type="file" name="produk_unggulan_5" accept=".pdf" disabled style="background: #f8f9fa; cursor: not-allowed;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_5): ?>
                                <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->produk_unggulan_5)); ?>" target="_blank">Lihat</a></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <div></div>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 2: B.2 MENU PENDIDIKAN KADER -->
        <div class="step-content" data-step="2">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-graduation-cap"></i>
                    </div>
                    <h6 class="section-title">PENDIDIKAN KADER</h6>
                </div>

                <div class="section-content">
                    <!-- PKPNU/PDPKPNU -->
                    <div class="divider">
                        <span>PKPNU/PDPKPNU</span>
                    </div>
                    <div class="form-group">
                        <label>Pilihan</label>
                        <select name="pkpnu_status" required onchange="toggleUploadField('pkpnu')">
                            <option value="">Pilih</option>
                            <option value="sudah" <?php echo e(old('pkpnu_status', $talenta->pkpnu_status) == 'sudah' ? 'selected' : ''); ?>>Sudah</option>
                            <option value="belum" <?php echo e(old('pkpnu_status', $talenta->pkpnu_status) == 'belum' ? 'selected' : ''); ?>>Belum</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pkpnu_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group" id="pkpnu-upload" style="display: <?php echo e(old('pkpnu_status', $talenta->pkpnu_status) == 'sudah' ? 'block' : 'none'); ?>;">
                        <label>Upload Sertifikat (PDF)</label>
                        <input type="file" name="pkpnu_sertifikat" accept=".pdf">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->pkpnu_sertifikat): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->pkpnu_sertifikat)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pkpnu_sertifikat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- MKNU -->
                    <div class="divider">
                        <span>MKNU</span>
                    </div>
                    <div class="form-group">
                        <label>Pilihan</label>
                        <select name="mknu_status" required onchange="toggleUploadField('mknu')">
                            <option value="">Pilih</option>
                            <option value="sudah" <?php echo e(old('mknu_status', $talenta->mknu_status) == 'sudah' ? 'selected' : ''); ?>>Sudah</option>
                            <option value="belum" <?php echo e(old('mknu_status', $talenta->mknu_status) == 'belum' ? 'selected' : ''); ?>>Belum</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mknu_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group" id="mknu-upload" style="display: <?php echo e(old('mknu_status', $talenta->mknu_status) == 'sudah' ? 'block' : 'none'); ?>;">
                        <label>Upload Sertifikat (PDF)</label>
                        <input type="file" name="mknu_sertifikat" accept=".pdf">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->mknu_sertifikat): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->mknu_sertifikat)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mknu_sertifikat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- PMKNU -->
                    <div class="divider">
                        <span>PMKNU</span>
                    </div>
                    <div class="form-group">
                        <label>Pilihan</label>
                        <select name="pmknu_status" required onchange="toggleUploadField('pmknu')">
                            <option value="">Pilih</option>
                            <option value="sudah" <?php echo e(old('pmknu_status', $talenta->pmknu_status) == 'sudah' ? 'selected' : ''); ?>>Sudah</option>
                            <option value="belum" <?php echo e(old('pmknu_status', $talenta->pmknu_status) == 'belum' ? 'selected' : ''); ?>>Belum</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pmknu_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group" id="pmknu-upload" style="display: <?php echo e(old('pmknu_status', $talenta->pmknu_status) == 'sudah' ? 'block' : 'none'); ?>;">
                        <label>Upload Sertifikat (PDF)</label>
                        <input type="file" name="pmknu_sertifikat" accept=".pdf">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->pmknu_sertifikat): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->pmknu_sertifikat)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pmknu_sertifikat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 3: B.3 MENU PROYEKSI DIRI -->
        <div class="step-content" data-step="3">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-user"></i>
                    </div>
                    <h6 class="section-title">PROYEKSI DIRI</h6>
                </div>

                <div class="section-content">
                    <!-- Data Jabatan -->
                    <div class="divider">
                        <span>Data Jabatan</span>
                    </div>
                    <div class="form-group">
                        <label>Jabatan saat ini</label>
                        <select name="jabatan_saat_ini">
                            <option value="">Pilih Jabatan</option>
                            <option value="guru" <?php echo e(old('jabatan_saat_ini', $talenta->jabatan_saat_ini) == 'guru' ? 'selected' : ''); ?>>Guru</option>
                            <option value="kaprodi" <?php echo e(old('jabatan_saat_ini', $talenta->jabatan_saat_ini) == 'kaprodi' ? 'selected' : ''); ?>>Kaprodi</option>
                            <option value="kepala_laboratorium" <?php echo e(old('jabatan_saat_ini', $talenta->jabatan_saat_ini) == 'kepala_laboratorium' ? 'selected' : ''); ?>>Kepala Laboratorium</option>
                            <option value="kepala_perpustakaan" <?php echo e(old('jabatan_saat_ini', $talenta->jabatan_saat_ini) == 'kepala_perpustakaan' ? 'selected' : ''); ?>>Kepala Perpustakaan</option>
                            <option value="kepala_bengkel" <?php echo e(old('jabatan_saat_ini', $talenta->jabatan_saat_ini) == 'kepala_bengkel' ? 'selected' : ''); ?>>Kepala Bengkel</option>
                            <option value="bendahara_i" <?php echo e(old('jabatan_saat_ini', $talenta->jabatan_saat_ini) == 'bendahara_i' ? 'selected' : ''); ?>>Bendahara I</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['jabatan_saat_ini'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Proyeksi Akademik -->
                    <div class="divider">
                        <span>Proyeksi Akademik</span>
                    </div>
                    <div class="form-group">
                        <label>Pilih Proyeksi Akademik</label>
                        <select name="proyeksi_akademik">
                            <option value="">Pilih</option>
                            <option value="guru_terampil" <?php echo e(old('proyeksi_akademik', $talenta->proyeksi_akademik) == 'guru_terampil' ? 'selected' : ''); ?>>Guru Terampil</option>
                            <option value="guru_mahir" <?php echo e(old('proyeksi_akademik', $talenta->proyeksi_akademik) == 'guru_mahir' ? 'selected' : ''); ?>>Guru Mahir</option>
                            <option value="guru_ahli" <?php echo e(old('proyeksi_akademik', $talenta->proyeksi_akademik) == 'guru_ahli' ? 'selected' : ''); ?>>Guru Ahli</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proyeksi_akademik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Proyeksi Jabatan -->
                    <div class="divider">
                        <span>Proyeksi Jabatan</span>
                    </div>
                    <div class="form-group">
                        <label>Level 2 Umum</label>
                        <select name="proyeksi_jabatan_level2_umum">
                            <option value="">Pilih</option>
                            <option value="wakil_kepala_sekolah" <?php echo e(old('proyeksi_jabatan_level2_umum', $talenta->proyeksi_jabatan_level2_umum) == 'wakil_kepala_sekolah' ? 'selected' : ''); ?>>Wakil Kepala Sekolah</option>
                            <option value="kaprodi" <?php echo e(old('proyeksi_jabatan_level2_umum', $talenta->proyeksi_jabatan_level2_umum) == 'kaprodi' ? 'selected' : ''); ?>>Kaprodi</option>
                            <option value="kepala_perpustakaan" <?php echo e(old('proyeksi_jabatan_level2_umum', $talenta->proyeksi_jabatan_level2_umum) == 'kepala_perpustakaan' ? 'selected' : ''); ?>>Kepala Perpustakaan</option>
                            <option value="bendahara_sekolah" <?php echo e(old('proyeksi_jabatan_level2_umum', $talenta->proyeksi_jabatan_level2_umum) == 'bendahara_sekolah' ? 'selected' : ''); ?>>Bendahara Sekolah</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proyeksi_jabatan_level2_umum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Level 2 Khusus</label>
                        <select name="proyeksi_jabatan_level2_khusus">
                            <option value="">Pilih</option>
                            <option value="kepala_unit_usaha" <?php echo e(old('proyeksi_jabatan_level2_khusus', $talenta->proyeksi_jabatan_level2_khusus) == 'kepala_unit_usaha' ? 'selected' : ''); ?>>Kepala Unit Usaha</option>
                            <option value="leader_sister_school" <?php echo e(old('proyeksi_jabatan_level2_khusus', $talenta->proyeksi_jabatan_level2_khusus) == 'leader_sister_school' ? 'selected' : ''); ?>>Leader Sister School</option>
                            <option value="leader_jejaring" <?php echo e(old('proyeksi_jabatan_level2_khusus', $talenta->proyeksi_jabatan_level2_khusus) == 'leader_jejaring' ? 'selected' : ''); ?>>Leader Jejaring</option>
                            <option value="leader_unggulan_sekolah" <?php echo e(old('proyeksi_jabatan_level2_khusus', $talenta->proyeksi_jabatan_level2_khusus) == 'leader_unggulan_sekolah' ? 'selected' : ''); ?>>Leader Unggulan Sekolah</option>
                            <option value="leader_prestasi_sekolah" <?php echo e(old('proyeksi_jabatan_level2_khusus', $talenta->proyeksi_jabatan_level2_khusus) == 'leader_prestasi_sekolah' ? 'selected' : ''); ?>>Leader Prestasi Sekolah</option>
                            <option value="leader_panjaminan_mutu" <?php echo e(old('proyeksi_jabatan_level2_khusus', $talenta->proyeksi_jabatan_level2_khusus) == 'leader_panjaminan_mutu' ? 'selected' : ''); ?>>Leader Panjaminan Mutu</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proyeksi_jabatan_level2_khusus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Level I</label>
                        <select name="proyeksi_jabatan_level1">
                            <option value="">Pilih</option>
                            <option value="kepala_sekolah" <?php echo e(old('proyeksi_jabatan_level1', $talenta->proyeksi_jabatan_level1) == 'kepala_sekolah' ? 'selected' : ''); ?>>Kepala Sekolah</option>
                            <option value="kepala_madrasah" <?php echo e(old('proyeksi_jabatan_level1', $talenta->proyeksi_jabatan_level1) == 'kepala_madrasah' ? 'selected' : ''); ?>>Kepala Madrasah</option>
                            <option value="tidak" <?php echo e(old('proyeksi_jabatan_level1', $talenta->proyeksi_jabatan_level1) == 'tidak' ? 'selected' : ''); ?>>Tidak</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proyeksi_jabatan_level1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Level Top Leader</label>
                        <select name="proyeksi_jabatan_top_leader">
                            <option value="">Pilih</option>
                            <option value="pengawas" <?php echo e(old('proyeksi_jabatan_top_leader', $talenta->proyeksi_jabatan_top_leader) == 'pengawas' ? 'selected' : ''); ?>>Pengawas</option>
                            <option value="pembina_utama" <?php echo e(old('proyeksi_jabatan_top_leader', $talenta->proyeksi_jabatan_top_leader) == 'pembina_utama' ? 'selected' : ''); ?>>Pembina Utama</option>
                            <option value="tidak" <?php echo e(old('proyeksi_jabatan_top_leader', $talenta->proyeksi_jabatan_top_leader) == 'tidak' ? 'selected' : ''); ?>>Tidak</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proyeksi_jabatan_top_leader'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Proyeksi Keahlian -->
                    <div class="divider">
                        <span>Proyeksi Keahlian</span>
                    </div>
                    <div class="form-group">
                        <label>Studi lanjut S2, S3</label>
                        <input type="text" name="studi_lanjut" value="<?php echo e(old('studi_lanjut', $talenta->studi_lanjut)); ?>" placeholder="Jelaskan studi lanjut">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['studi_lanjut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Leader dan Aktif MGMP</label>
                        <input type="text" name="leader_mgmp" value="<?php echo e(old('leader_mgmp', $talenta->leader_mgmp)); ?>" placeholder="Jelaskan kepemimpinan MGMP">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['leader_mgmp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Produk ajar : modul, buku ajar</label>
                        <input type="text" name="produk_ajar" value="<?php echo e(old('produk_ajar', $talenta->produk_ajar)); ?>" placeholder="Jelaskan produk ajar">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['produk_ajar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Prestasi kompetitif</label>
                        <input type="text" name="prestasi_kompetitif" value="<?php echo e(old('prestasi_kompetitif', $talenta->prestasi_kompetitif)); ?>" placeholder="Jelaskan prestasi kompetitif">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['prestasi_kompetitif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn" onclick="nextStep()">
                    Selanjutnya
                    <i class="bx bx-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Step 4: B.4 MENU DATA DIRI -->
        <div class="step-content" data-step="4">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-id-card"></i>
                    </div>
                    <h6 class="section-title">DATA DIRI</h6>
                </div>

                <div class="section-content">
                    <!-- B.4.1 Data Pribadi -->
                    <div class="divider">
                        <span>Data Pribadi</span>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap gelar</label>
                        <input type="text" name="nama_lengkap_gelar" value="<?php echo e(old('nama_lengkap_gelar', $talenta->nama_lengkap_gelar)); ?>" placeholder="Nama Lengkap dengan Gelar">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_lengkap_gelar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Nama Panggilan</label>
                        <input type="text" name="nama_panggilan" value="<?php echo e(old('nama_panggilan', $talenta->nama_panggilan)); ?>" placeholder="Nama Panggilan">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nama_panggilan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Nomor KTP</label>
                        <input type="text" name="nomor_ktp" value="<?php echo e(old('nomor_ktp', $talenta->nomor_ktp)); ?>" placeholder="Nomor KTP">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nomor_ktp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>NIP Ma'arif</label>
                        <input type="text" name="nip_maarif" value="<?php echo e(old('nip_maarif', $talenta->nip_maarif)); ?>" placeholder="NIP Ma'arif">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nip_maarif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Nomor Talenta</label>
                        <input type="text" name="nomor_talenta" value="<?php echo e(old('nomor_talenta', $talenta->nomor_talenta)); ?>" placeholder="Nomor Talenta">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nomor_talenta'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Tempat lahir (Kabupaten atau kota)</label>
                        <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $talenta->tempat_lahir)); ?>" placeholder="Tempat Lahir">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $talenta->tanggal_lahir ? $talenta->tanggal_lahir->format('Y-m-d') : '')); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Email Aktif</label>
                        <input type="email" name="email_aktif" value="<?php echo e(old('email_aktif', $talenta->email_aktif)); ?>" placeholder="Email Aktif">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email_aktif'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Nomor WA aktif</label>
                        <input type="text" name="nomor_wa" value="<?php echo e(old('nomor_wa', $talenta->nomor_wa)); ?>" placeholder="Nomor WA">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['nomor_wa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Alamat KTP</label>
                        <textarea name="alamat_ktp" placeholder="Alamat KTP"><?php echo e(old('alamat_ktp', $talenta->alamat_ktp)); ?></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['alamat_ktp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Alamat yang ditinggali saat ini</label>
                        <textarea name="alamat_tinggal" placeholder="Alamat Tinggal"><?php echo e(old('alamat_tinggal', $talenta->alamat_tinggal)); ?></textarea>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['alamat_tinggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Link FB, link Tik Tok, Link Instagram</label>
                        <input type="text" name="link_fb" value="<?php echo e(old('link_fb', $talenta->link_fb)); ?>" placeholder="Facebook">
                        <input type="text" name="link_tiktok" value="<?php echo e(old('link_tiktok', $talenta->link_tiktok)); ?>" placeholder="TikTok" style="margin-top: 8px;">
                        <input type="text" name="link_instagram" value="<?php echo e(old('link_instagram', $talenta->link_instagram)); ?>" placeholder="Instagram" style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['link_fb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['link_tiktok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['link_instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Upload Foto: 1 Foto Resmi, 1 Foto Bebas, 1 Foto Keluarga</label>
                        <input type="file" name="foto_resmi" accept="image/*">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->foto_resmi): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->foto_resmi)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <input type="file" name="foto_bebas" accept="image/*" style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->foto_bebas): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->foto_bebas)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <input type="file" name="foto_keluarga" accept="image/*" style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->foto_keluarga): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->foto_keluarga)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['foto_resmi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['foto_bebas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['foto_keluarga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- B.4.2 Data Pendidikan -->
                    <div class="divider">
                        <span>Data Pendidikan</span>
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah SD</label>
                        <input type="text" name="asal_sekolah_sd" value="<?php echo e(old('asal_sekolah_sd', $talenta->asal_sekolah_sd)); ?>" placeholder="Nama Sekolah SD">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['asal_sekolah_sd'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah SMP</label>
                        <input type="text" name="asal_sekolah_smp" value="<?php echo e(old('asal_sekolah_smp', $talenta->asal_sekolah_smp)); ?>" placeholder="Nama Sekolah SMP">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['asal_sekolah_smp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah SMA</label>
                        <input type="text" name="asal_sekolah_sma" value="<?php echo e(old('asal_sekolah_sma', $talenta->asal_sekolah_sma)); ?>" placeholder="Nama Sekolah SMA">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['asal_sekolah_sma'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah S1</label>
                        <input type="text" name="asal_sekolah_s1" value="<?php echo e(old('asal_sekolah_s1', $talenta->asal_sekolah_s1)); ?>" placeholder="Nama Perguruan Tinggi S1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['asal_sekolah_s1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah S2</label>
                        <input type="text" name="asal_sekolah_s2" value="<?php echo e(old('asal_sekolah_s2', $talenta->asal_sekolah_s2)); ?>" placeholder="Nama Perguruan Tinggi S2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['asal_sekolah_s2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Asal Sekolah S3</label>
                        <input type="text" name="asal_sekolah_s3" value="<?php echo e(old('asal_sekolah_s3', $talenta->asal_sekolah_s3)); ?>" placeholder="Nama Perguruan Tinggi S3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['asal_sekolah_s3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Upload Ijazah: S1, S2, S3 (PDF/JPG)</label>
                        <input type="file" name="ijazah_s1" accept=".pdf,.jpg,.jpeg">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->ijazah_s1): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->ijazah_s1)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <input type="file" name="ijazah_s2" accept=".pdf,.jpg,.jpeg" style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->ijazah_s2): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->ijazah_s2)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <input type="file" name="ijazah_s3" accept=".pdf,.jpg,.jpeg" style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->ijazah_s3): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->ijazah_s3)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ijazah_s1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ijazah_s2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['ijazah_s3'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- B.4.3 Data Pendapatan -->
                    <div class="divider">
                        <span>Data Pendapatan</span>
                    </div>
                    <div class="form-group">
                        <label>Kategori Level Pendapatan Internal</label>
                        <select name="level_pendapatan_internal">
                            <option value="">Pilih Level</option>
                            <option value="1" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '1' ? 'selected' : ''); ?>>Level 1 : Rp200.000 – Rp500.000</option>
                            <option value="2" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '2' ? 'selected' : ''); ?>>Level 2 : Rp600.000 – Rp1.000.000</option>
                            <option value="3" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '3' ? 'selected' : ''); ?>>Level 3 : Rp1.100.000 – Rp1.500.000</option>
                            <option value="4" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '4' ? 'selected' : ''); ?>>Level 4 : Rp1.600.000 – Rp2.000.000</option>
                            <option value="5" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '5' ? 'selected' : ''); ?>>Level 5 : Rp2.100.000 – Rp3.000.000</option>
                            <option value="6" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '6' ? 'selected' : ''); ?>>Level 6 : Rp3.100.000 – Rp4.000.000</option>
                            <option value="7" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '7' ? 'selected' : ''); ?>>Level 7 : Rp4.100.000 – Rp5.500.000</option>
                            <option value="8" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '8' ? 'selected' : ''); ?>>Level 8 : Rp5.600.000 – Rp7.500.000</option>
                            <option value="9" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '9' ? 'selected' : ''); ?>>Level 9 : Rp7.600.000 – Rp10.000.000</option>
                            <option value="10" <?php echo e(old('level_pendapatan_internal', $talenta->level_pendapatan_internal) == '10' ? 'selected' : ''); ?>>Level 10 : Di atas Rp10.000.000</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['level_pendapatan_internal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Level pendapatan eksternal, rerata</label>
                        <input type="text" name="pekerjaan_eksternal" value="<?php echo e(old('pekerjaan_eksternal', $talenta->pekerjaan_eksternal)); ?>" placeholder="Sebagai .....">
                        <input type="number" name="pendapatan_eksternal" value="<?php echo e(old('pendapatan_eksternal', $talenta->pendapatan_eksternal)); ?>" placeholder="Rupiah ....." style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pekerjaan_eksternal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['pendapatan_eksternal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- B.4.4 Data Riwayat Kerja -->
                    <div class="divider">
                        <span>Data Riwayat Kerja</span>
                    </div>
                    <div class="form-group required">
                        <label>GTT-PTT</label>
                        <input type="date" name="gtt_ptt_tanggal" value="<?php echo e(old('gtt_ptt_tanggal', $talenta->gtt_ptt_tanggal)); ?>" placeholder="Tanggal-bulan-Tahun" required>
                        <input type="file" name="gtt_ptt_sk" accept=".pdf" <?php echo e(!$talenta->gtt_ptt_sk ? 'required' : ''); ?> style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->gtt_ptt_sk): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->gtt_ptt_sk)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['gtt_ptt_tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['gtt_ptt_sk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group required">
                        <label>GTY</label>
                        <input type="date" name="gty_tanggal" value="<?php echo e(old('gty_tanggal', $talenta->gty_tanggal)); ?>" placeholder="Tanggal-bulan-tahun" required>
                        <input type="file" name="gty_sk" accept=".pdf" style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->gty_sk): ?>
                            <small class="text-muted">File saat ini: <a href="<?php echo e(Storage::url($talenta->gty_sk)); ?>" target="_blank">Lihat</a></small>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['gty_tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['gty_sk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group required">
                        <label>Masa Kerja LPMNU DIY (Tahun)</label>
                        <input type="number" name="masa_kerja_lpmnu" value="<?php echo e(old('masa_kerja_lpmnu', $talenta->masa_kerja_lpmnu)); ?>" placeholder="... tahun" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['masa_kerja_lpmnu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="form-group required">
                        <label>Riwayat Jabatan Guru</label>
                        <input type="text" name="riwayat_jabatan_pemula" value="<?php echo e(old('riwayat_jabatan_pemula', $talenta->riwayat_jabatan_pemula)); ?>" placeholder="(1) Pemula diajukan tanggal :" required>
                        <input type="text" name="riwayat_jabatan_terampil" value="<?php echo e(old('riwayat_jabatan_terampil', $talenta->riwayat_jabatan_terampil)); ?>" placeholder="(2) Terampil diajukan tanggal :" required style="margin-top: 8px;">
                        <input type="text" name="riwayat_jabatan_mahir" value="<?php echo e(old('riwayat_jabatan_mahir', $talenta->riwayat_jabatan_mahir)); ?>" placeholder="(3) Mahir diajukan tanggal :" required style="margin-top: 8px;">
                        <input type="text" name="riwayat_jabatan_ahli" value="<?php echo e(old('riwayat_jabatan_ahli', $talenta->riwayat_jabatan_ahli)); ?>" placeholder="(4) Ahli diajukan tanggal :" required style="margin-top: 8px;">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['riwayat_jabatan_pemula'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['riwayat_jabatan_terampil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['riwayat_jabatan_mahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['riwayat_jabatan_ahli'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>


                </div>
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="button" class="step-btn draft-btn" onclick="submitDraft()">
                    <i class="bx bx-edit"></i>
                    Simpan Data
                </button>
                
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?php echo e(session('success')); ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>
<script src="<?php echo e(asset('js/mobile/talenta.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/talenta/edit.blade.php ENDPATH**/ ?>