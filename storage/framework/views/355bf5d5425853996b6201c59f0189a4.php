<?php $__env->startSection('title', 'Simfoni'); ?>
<?php $__env->startSection('subtitle', 'Data SK Tenaga Pendidik'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb url('<?php echo e(asset("images/bg.png")); ?>') no-repeat center center;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .simfoni-header {
            color: white;
            padding: 20px 16px;
            text-align: center;
            position: relative;
        }

        .simfoni-header h4 {
            margin: 0 0 4px 0;
            font-weight: 600;
            font-size: 16px;
        }

        .simfoni-header p {
            margin: 0;
            font-size: 12px;
            opacity: 0.9;
        }

        .form-container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .section-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: #212121 6px 2px 4px -1px;
        }

        .section-card:last-child {
            margin-bottom: 0;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .section-icon {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 14px;
        }

        .section-title {
            font-weight: 600;
            font-size: 12px;
            color: #004b4c;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #004b4c;
            margin-bottom: 6px;
            font-size: 10px;
        }

        .form-group.required label::after {
            content: ' *';
            color: #dc3545;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="number"],
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            transition: all 0.3s ease;
            box-sizing: border-box;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #004b4c;
            box-shadow: 0 0 0 3px rgba(0, 75, 76, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 70px;
        }

        .form-hint {
            font-size: 10px;
            color: #999;
            margin-top: 3px;
        }

        .form-error {
            color: #dc3545;
            font-size: 10px;
            margin-top: 3px;
        }

        .error-container {
            background: #fff5f5;
            border-left: 4px solid #dc3545;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
        }

        .error-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .error-list li {
            color: #dc3545;
            font-size: 11px;
            padding: 2px 0;
        }

        .success-alert {
            background: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .row-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .submit-container {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 0 0 12px 12px;
            border-top: 1px solid #e9ecef;
        }

        .submit-btn {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .navigation-container {
            background: #f8f9fa;
            padding: 16px;
            border-top: 1px solid #e9ecef;
        }

        .nav-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-btn {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .step-indicator {
            font-size: 10px;
            color: #666;
            font-weight: 600;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .currency-prefix {
            position: relative;
        }

        .currency-prefix::before {
            content: 'Rp ';
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #999;
            font-weight: 600;
        }

        .currency-prefix input {
            padding-left: 35px !important;
        }

        .auto-fill {
            background: #f0ebf5;
            font-size: 11px;
            color: #666;
        }

        .step-timeline {
            background: #f8f9fa;
            padding: 16px;
            border-bottom: 1px solid #e9ecef;
        }

        .timeline-steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 400px;
            margin: 0 auto;
        }

        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
        }

        /* Lines removed as requested */

        .timeline-step-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #e9ecef;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .timeline-step.active .timeline-step-circle,
        .timeline-step.completed .timeline-step-circle {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: white;
        }

        .timeline-step-label {
            font-size: 5px;
            color: #666;
            margin-top: 4px;
            text-align: center;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .timeline-step.active .timeline-step-label,
        .timeline-step.completed .timeline-step-label {
            color: #004b4c;
            font-weight: 600;
        }
    </style>

    <!-- Header -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="window.location.href='<?php echo e(route('mobile.profile')); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
    </div>

    <div class="simfoni-header" style="margin-top: -10px;">
        <h4>SIMFONI <span style="color: #efaa0c;">GTK LPMNU DIY</span></h4>
        <p>Tahun 2025</p>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <!-- Step Timeline -->
        <div class="step-timeline">
            
        <div class="timeline-steps">
            <div class="timeline-step active" data-step="1">
                <div class="timeline-step-circle">1</div>
                
            </div>
            <div class="timeline-step" data-step="2">
                <div class="timeline-step-circle">2</div>
                
            </div>
            <div class="timeline-step" data-step="3">
                <div class="timeline-step-circle">3</div>
                
            </div>
            <div class="timeline-step" data-step="4">
                <div class="timeline-step-circle">4</div>
                
            </div>
            <div class="timeline-step" data-step="5">
                <div class="timeline-step-circle">5</div>
                
            </div>
            <div class="timeline-step" data-step="6">
                <div class="timeline-step-circle">6</div>
                
            </div>
            <div class="timeline-step" data-step="7">
                <div class="timeline-step-circle">7</div>
                
            </div>
        </div>
        </div>

        <!-- Success Alert -->
        <?php if(session('success')): ?>
            <div class="success-alert">
                ✓ <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Error Messages -->
        <?php if($errors->any()): ?>
            <div class="error-container">
                <ul class="error-list">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('mobile.simfoni.store')); ?>" method="POST" id="simfoniForm">
            <?php echo csrf_field(); ?>

            <!-- Step 1: A. DATA SK -->
            <div class="step-content active" data-step="1">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-id-card"></i>
                        </div>
                        <h6 class="section-title">A. DATA SK</h6>
                    </div>

                    <div class="section-content">
                    <div class="form-group required">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap_gelar" value="<?php echo e(old('nama_lengkap_gelar', $simfoni->nama_lengkap_gelar ?? '')); ?>" placeholder="Nama Lengkap" required>
                        <?php $__errorArgs = ['nama_lengkap_gelar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label>Gelar (Jika Memiliki)</label>
                        <input type="text" name="gelar" value="<?php echo e(old('gelar', $simfoni->gelar ?? '')); ?>" placeholder="Contoh: S.Pd., M.Pd., dll">
                        <?php $__errorArgs = ['gelar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $simfoni->tempat_lahir ?? '')); ?>" placeholder="Tempat Lahir" required>
                        </div>
                        <div class="form-group required">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $simfoni->tanggal_lahir ?? '')); ?>" required>
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>NUPTK</label>
                            <input type="text" name="nuptk" value="<?php echo e(old('nuptk', $simfoni->nuptk ?? '')); ?>" placeholder="NUPTK (jika ada)">
                        </div>
                        <div class="form-group">
                            <label>Karta-NU</label>
                            <input type="text" name="kartanu" value="<?php echo e(old('kartanu', $simfoni->kartanu ?? '')); ?>" placeholder="Nomor Karta-NU (jika ada)">
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>NIP Ma'arif Baru</label>
                            <input type="text" name="nipm" value="<?php echo e(old('nipm', $simfoni->nipm ?? '')); ?>" placeholder="NIP Ma'arif Baru">
                        </div>
                        <div class="form-group required">
                            <label>NIK</label>
                            <input type="text" name="nik" value="<?php echo e(old('nik', $simfoni->nik ?? '')); ?>" placeholder="Nomor Induk Keluarga" required>
                            <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>TMT Pertama</label>
                            <input type="date" name="tmt" value="<?php echo e(old('tmt', $simfoni->tmt ?? '')); ?>" placeholder="Terhitung Malai Tanggal" required id="tmtInput">
                        </div>
                        <div class="form-group required">
                            <label>Strata Pendidikan</label>
                            <input type="text" name="strata_pendidikan" value="<?php echo e(old('strata_pendidikan', $simfoni->strata_pendidikan ?? '')); ?>" placeholder="SMK, SMA, S1, S2, S3" required>
                        </div>
                    </div>

                        <div class="form-group">
                            <label>Masa Kerja</label>
                            <input type="text" id="masaKerja" name="masa_kerja" readonly style="background: #f8f9fa; color: #666;">
                            <div class="form-hint" style="font-style: italic">Otomatis: Dari TMT Pertama sampai Juni 2025</div>
                        </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Perguruan Tinggi Asal</label>
                            <input type="text" name="pt_asal" value="<?php echo e(old('pt_asal', $simfoni->pt_asal ?? '')); ?>" placeholder="Nama Perguruan Tinggi">
                            <div class="form-hint" style="font-style: italic">Bukan Inisal</div>
                            <?php $__errorArgs = ['pt_asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group required">
                            <label>Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" value="<?php echo e(old('tahun_lulus', $simfoni->tahun_lulus ?? '')); ?>" min="1900" max="2100" placeholder="2024" required>
                            <?php $__errorArgs = ['tahun_lulus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-group required">
                        <label>Program Studi</label>
                        <input type="text" name="program_studi" value="<?php echo e(old('program_studi', $simfoni->program_studi ?? '')); ?>" placeholder="Program Studi" required>
                        <div class="form-hint" style="font-style: italic">Bukan Inisal</div>
                    </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 2: B. RIWAYAT KERJA -->
            <div class="step-content" data-step="2" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-briefcase"></i>
                        </div>
                        <h6 class="section-title">B. RIWAYAT KERJA</h6>
                    </div>

                    <div class="section-content">
                        <div class="form-group">
                            <label>Status Kerja Saat Ini</label>
                            <select name="status_kerja" id="statusKerjaSelect">
                                <option value="">-- Pilih Status --</option>
                                <?php $__currentLoopData = $statusKepegawaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($status->name); ?>" <?php echo e(old('status_kerja', $simfoni->status_kerja ?? '') == $status->name ? 'selected' : ''); ?>><?php echo e($status->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <div class="form-hint" style="font-style: italic">Jika masa kerja belum ada 2 tahun maka hanya tersedia sebagai GTT dan PTT</div>
                            <?php $__errorArgs = ['status_kerja'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row-2col">
                            <div class="form-group">
                                <label>Tanggal SK Pertama</label>
                                <input type="date" name="tanggal_sk_pertama" value="<?php echo e(old('tanggal_sk_pertama', $simfoni->tanggal_sk_pertama ?? '')); ?>">
                                <?php $__errorArgs = ['tanggal_sk_pertama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="form-error"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group">
                                <label>Nomor SK Pertama</label>
                                <input type="text" name="nomor_sk_pertama" value="<?php echo e(old('nomor_sk_pertama', $simfoni->nomor_sk_pertama ?? '')); ?>">
                                <?php $__errorArgs = ['nomor_sk_pertama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="form-error"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nomor Sertifikasi Pendidik</label>
                            <input type="text" name="nomor_sertifikasi_pendidik" value="<?php echo e(old('nomor_sertifikasi_pendidik', $simfoni->nomor_sertifikasi_pendidik ?? '')); ?>" placeholder="Nomor sertifikat pendidik (jika ada)">
                        </div>

                        <div class="form-group">
                            <label>Riwayat Kerja Sebelumnya</label>
                            <textarea name="riwayat_kerja_sebelumnya" placeholder="Ceritakan pengalaman kerja sebelumnya..."><?php echo e(old('riwayat_kerja_sebelumnya', $simfoni->riwayat_kerja_sebelumnya ?? '')); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Pernah Mendaftar PPPK</label>
                            <div style="display: flex; gap: 20px;">
                                <label style="display: flex; align-items: center; gap: 5px;">
                                    <input type="radio" name="pernah_mendaftar_pppk" value="1" <?php echo e(old('pernah_mendaftar_pppk', $simfoni->pernah_mendaftar_pppk ?? '') == '1' ? 'checked' : ''); ?> id="pppk_yes">
                                    Iya
                                </label>
                                <label style="display: flex; align-items: center; gap: 5px;">
                                    <input type="radio" name="pernah_mendaftar_pppk" value="0" <?php echo e(old('pernah_mendaftar_pppk', $simfoni->pernah_mendaftar_pppk ?? '') == '0' ? 'checked' : ''); ?> id="pppk_no">
                                    Tidak
                                </label>
                            </div>
                        </div>

                        <div class="row-2col" id="pppk_details" style="display: <?php echo e(old('pernah_mendaftar_pppk', $simfoni->pernah_mendaftar_pppk ?? '') == '1' ? 'grid' : 'none'); ?>;">
                            <div class="form-group">
                                <label>Tahun Mendaftar PPPK</label>
                                <input type="number" name="tahun_mendaftar_pppk" value="<?php echo e(old('tahun_mendaftar_pppk', $simfoni->tahun_mendaftar_pppk ?? '')); ?>" min="2000" max="2030" placeholder="2024">
                            </div>
                            <div class="form-group">
                                <label>Formasi</label>
                                <input type="text" name="formasi" value="<?php echo e(old('formasi', $simfoni->formasi ?? '')); ?>" placeholder="Formasi PPPK">
                            </div>
                        </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 3: C. KEAHLIAN DAN DATA LAIN -->
            <div class="step-content" data-step="3" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-star"></i>
                        </div>
                        <h6 class="section-title">C. KEAHLIAN DAN DATA LAIN</h6>
                    </div>

                    <div class="section-content">
                        <div class="form-group">
                            <label>Keahlian</label>
                            <textarea name="keahlian" placeholder="Sebutkan keahlian khusus Anda..."><?php echo e(old('keahlian', $simfoni->keahlian ?? '')); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Kedudukan di LPM</label>
                            <input type="text" name="kedudukan_lpm" value="<?php echo e(old('kedudukan_lpm', $simfoni->kedudukan_lpm ?? '')); ?>" placeholder="Posisi di Lembaga Pendidikan">
                        </div>

                        <div class="form-group">
                            <label>Prestasi</label>
                            <textarea name="prestasi" placeholder="Pencapaian atau prestasi yang diraih..."><?php echo e(old('prestasi', $simfoni->prestasi ?? '')); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Tahun Sertifikasi & Impassing</label>
                            <input type="text" name="tahun_sertifikasi_impassing" value="<?php echo e(old('tahun_sertifikasi_impassing', $simfoni->tahun_sertifikasi_impassing ?? '')); ?>" placeholder="Contoh: 2015 & 2018" id="tahunSertifikasiInput" maxlength="100">
                        </div>

                        <div class="row-2col">
                            <div class="form-group required">
                                <label>Nomor HP/WA</label>
                                <input type="tel" name="no_hp" value="<?php echo e(old('no_hp', $simfoni->no_hp ?? '')); ?>">
                            </div>
                            <div class="form-group required">
                                <label>E-mail Aktif</label>
                                <input type="email" name="email" value="<?php echo e(old('email', $simfoni->email ?? '')); ?>" class="auto-fill" readonly>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label>Status Pernikahan</label>
                            <select name="status_pernikahan" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Belum Kawin" <?php echo e(old('status_pernikahan', $simfoni->status_pernikahan ?? '') == 'Belum Kawin' ? 'selected' : ''); ?>>Belum Kawin</option>
                                <option value="Kawin" <?php echo e(old('status_pernikahan', $simfoni->status_pernikahan ?? '') == 'Kawin' ? 'selected' : ''); ?>>Kawin</option>
                                <option value="Cerai Hidup" <?php echo e(old('status_pernikahan', $simfoni->status_pernikahan ?? '') == 'Cerai Hidup' ? 'selected' : ''); ?>>Cerai Hidup</option>
                                <option value="Cerai Mati" <?php echo e(old('status_pernikahan', $simfoni->status_pernikahan ?? '') == 'Cerai Mati' ? 'selected' : ''); ?>>Cerai Mati</option>
                            </select>
                            <?php $__errorArgs = ['status_pernikahan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group required">
                            <label>Alamat Lengkap</label>
                            <textarea name="alamat_lengkap" required><?php echo e(old('alamat_lengkap', $simfoni->alamat_lengkap ?? '')); ?></textarea>
                            <?php $__errorArgs = ['alamat_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 4: D. DATA KEUANGAN/KESEJAHTERAAN -->
            <div class="step-content" data-step="4" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-money"></i>
                        </div>
                        <h6 class="section-title">D. DATA KEUANGAN/KESEJAHTERAAN</h6>
                    </div>

                    <div class="section-content">
                        <div class="row-2col">
                            <div class="form-group">
                                <label>Bank</label>
                                <input type="text" name="bank" value="<?php echo e(old('bank', $simfoni->bank ?? '')); ?>" placeholder="Nama Bank">
                            </div>
                            <div class="form-group">
                                <label>Nomor Rekening</label>
                                <input type="text" name="nomor_rekening" value="<?php echo e(old('nomor_rekening', $simfoni->nomor_rekening ?? '')); ?>" placeholder="No Rekening">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Gaji Sertifikasi</label>
                            <div class="currency-prefix">
                                <input type="text" name="gaji_sertifikasi" value="<?php echo e(old('gaji_sertifikasi', $simfoni->gaji_sertifikasi ?? '')); ?>" placeholder="0" id="gajiSertifikasiInput">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Gaji Pokok Perbulan dari Satpen</label>
                            <div class="currency-prefix">
                                <input type="text" name="gaji_pokok" value="<?php echo e(old('gaji_pokok', $simfoni->gaji_pokok ?? '')); ?>" placeholder="0" id="gajiPokokInput">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Honor Lain (dari satpen)</label>
                            <div class="currency-prefix">
                                <input type="text" name="honor_lain" value="<?php echo e(old('honor_lain', $simfoni->honor_lain ?? '')); ?>" placeholder="0" id="honorLainInput">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Penghasilan Lain</label>
                            <div class="currency-prefix">
                                <input type="text" name="penghasilan_lain" value="<?php echo e(old('penghasilan_lain', $simfoni->penghasilan_lain ?? '')); ?>" placeholder="0" id="penghasilanLainInput">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Penghasilan Pasangan (tidak dihitung)</label>
                            <div class="currency-prefix">
                                <input type="text" name="penghasilan_pasangan" value="<?php echo e(old('penghasilan_pasangan', $simfoni->penghasilan_pasangan ?? '')); ?>" placeholder="0" id="penghasilanPasanganInput">
                            </div>
                            <div class="form-hint" style="font-style: italic">Informasi ini tidak masuk dalam perhitungan total</div>
                        </div>

                        <div class="form-group">
                            <label>Jumlah Total Penghasilan Diri</label>
                            <div class="currency-prefix">
                                <input type="text" name="total_penghasilan" value="<?php echo e(old('total_penghasilan', $simfoni->total_penghasilan ?? '')); ?>" placeholder="0" id="totalPenghasilan" readonly style="background: #f8f9fa; color: #666;">
                            </div>
                            <div class="form-hint" style="font-style: italic">Otomatis: Gaji Sertifikasi + Gaji Pokok + Honor Lain + Penghasilan Lain</div>
                        </div>

                        <div class="form-group">
                            <label>Kategori Penghasilan</label>
                            <input type="text" id="kategoriPenghasilan" name="kategori_penghasilan" readonly style="background: #f8f9fa; color: #666; font-weight: bold;" value="<?php echo e(old('kategori_penghasilan', $simfoni->kategori_penghasilan ?? '')); ?>">
                            <div class="form-hint" style="font-style: italic">
                                A = Bagus (≥ 10 juta)<br>
                                B = Baik (6,0 – 9,9 juta)<br>
                                C = Cukup (4,0 – 5,9 juta)<br>
                                D = Hampir Cukup (2,5 – 3,9 juta)<br>
                                E = Kurang (1,5 – 2,4 juta)<br>
                                F = Sangat Kurang (< 1,5 juta)
                            </div>
                        </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 5: E. STATUS KEKADERAN -->
            <div class="step-content" data-step="5" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-group"></i>
                        </div>
                        <h6 class="section-title">E. STATUS KEKADERAN</h6>
                    </div>

                    <div class="section-content">
                        <div class="form-group">
                            <label>Status Kader Diri</label>
                            <select name="status_kader_diri">
                                <option value="">-- Pilih Status --</option>
                                <option value="Militan" <?php echo e(old('status_kader_diri', $simfoni->status_kader_diri ?? '') == 'Militan' ? 'selected' : ''); ?>>Militan</option>
                                <option value="Aktif" <?php echo e(old('status_kader_diri', $simfoni->status_kader_diri ?? '') == 'Aktif' ? 'selected' : ''); ?>>Aktif</option>
                                <option value="Baru" <?php echo e(old('status_kader_diri', $simfoni->status_kader_diri ?? '') == 'Baru' ? 'selected' : ''); ?>>Baru</option>
                                <option value="Non-NU" <?php echo e(old('status_kader_diri', $simfoni->status_kader_diri ?? '') == 'Non-NU' ? 'selected' : ''); ?>>Non-NU</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Pendidikan Kader yang Diikuti</label>
                            <input type="text" name="pendidikan_kader" value="<?php echo e(old('pendidikan_kader', $simfoni->pendidikan_kader ?? '')); ?>" placeholder="Pendidikan kader yang diikuti">
                        </div>

                        <div class="form-group">
                            <label>Status Kader Ayah</label>
                            <select name="status_kader_ayah">
                                <option value="">-- Pilih Status --</option>
                                <option value="Militan" <?php echo e(old('status_kader_ayah', $simfoni->status_kader_ayah ?? '') == 'Militan' ? 'selected' : ''); ?>>Militan</option>
                                <option value="Aktif" <?php echo e(old('status_kader_ayah', $simfoni->status_kader_ayah ?? '') == 'Aktif' ? 'selected' : ''); ?>>Aktif</option>
                                <option value="Baru" <?php echo e(old('status_kader_ayah', $simfoni->status_kader_ayah ?? '') == 'Baru' ? 'selected' : ''); ?>>Baru</option>
                                <option value="Non-NU" <?php echo e(old('status_kader_ayah', $simfoni->status_kader_ayah ?? '') == 'Non-NU' ? 'selected' : ''); ?>>Non-NU</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status Kader Ibu</label>
                            <select name="status_kader_ibu">
                                <option value="">-- Pilih Status --</option>
                                <option value="Militan" <?php echo e(old('status_kader_ibu', $simfoni->status_kader_ibu ?? '') == 'Militan' ? 'selected' : ''); ?>>Militan</option>
                                <option value="Aktif" <?php echo e(old('status_kader_ibu', $simfoni->status_kader_ibu ?? '') == 'Aktif' ? 'selected' : ''); ?>>Aktif</option>
                                <option value="Baru" <?php echo e(old('status_kader_ibu', $simfoni->status_kader_ibu ?? '') == 'Baru' ? 'selected' : ''); ?>>Baru</option>
                                <option value="Non-NU" <?php echo e(old('status_kader_ibu', $simfoni->status_kader_ibu ?? '') == 'Non-NU' ? 'selected' : ''); ?>>Non-NU</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status Kader Suami/Istri</label>
                            <select name="status_kader_pasangan">
                                <option value="">-- Pilih Status --</option>
                                <option value="Militan" <?php echo e(old('status_kader_pasangan', $simfoni->status_kader_pasangan ?? '') == 'Militan' ? 'selected' : ''); ?>>Militan</option>
                                <option value="Aktif" <?php echo e(old('status_kader_pasangan', $simfoni->status_kader_pasangan ?? '') == 'Aktif' ? 'selected' : ''); ?>>Aktif</option>
                                <option value="Baru" <?php echo e(old('status_kader_pasangan', $simfoni->status_kader_pasangan ?? '') == 'Baru' ? 'selected' : ''); ?>>Baru</option>
                                <option value="Non-NU" <?php echo e(old('status_kader_pasangan', $simfoni->status_kader_pasangan ?? '') == 'Non-NU' ? 'selected' : ''); ?>>Non-NU</option>
                            </select>
                        </div>

                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 6: F. DATA KELUARGA -->
            <div class="step-content" data-step="6" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-family"></i>
                        </div>
                        <h6 class="section-title">F. DATA KELUARGA</h6>
                    </div>

                    <div class="section-content">
                        <div class="form-group">
                            <label>Nama Ayah</label>
                            <input type="text" name="nama_ayah" value="<?php echo e(old('nama_ayah', $simfoni->nama_ayah ?? '')); ?>" placeholder="Nama lengkap ayah">
                        </div>

                        <div class="form-group">
                            <label>Nama Ibu</label>
                            <input type="text" name="nama_ibu" value="<?php echo e(old('nama_ibu', $simfoni->nama_ibu ?? '')); ?>" placeholder="Nama lengkap ibu">
                        </div>

                        <div class="form-group">
                            <label>Nama Suami/Istri</label>
                            <input type="text" name="nama_pasangan" value="<?php echo e(old('nama_pasangan', $simfoni->nama_pasangan ?? '')); ?>" placeholder="Nama lengkap suami/istri">
                        </div>

                        <div class="form-group">
                            <label>Jumlah Anak Tanggungan</label>
                            <input type="number" name="jumlah_anak" value="<?php echo e(old('jumlah_anak', $simfoni->jumlah_anak ?? '')); ?>" min="0" placeholder="0">
                        </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Step 7: G. PROYEKSI KE DEPAN -->
            <div class="step-content" data-step="7" style="display: none;">
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bx bx-trending-up"></i>
                        </div>
                        <h6 class="section-title">G. PROYEKSI KE DEPAN</h6>
                    </div>

                    <div class="section-content">
                        <div class="table-responsive">
                            <table class="table table-sm" style="font-size: 10px;">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">No</th>
                                        <th>Pernyataan</th>
                                        <th style="width: 50px;">iya/sudah</th>
                                        <th style="width: 50px;">tidak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Akan kuliah S2</td>
                                        <td><input type="radio" name="akan_kuliah_s2" value="iya" <?php echo e(old('akan_kuliah_s2', $simfoni->akan_kuliah_s2 ?? '') == 'iya' ? 'checked' : ''); ?> required></td>
                                        <td><input type="radio" name="akan_kuliah_s2" value="tidak" <?php echo e(old('akan_kuliah_s2', $simfoni->akan_kuliah_s2 ?? '') == 'tidak' ? 'checked' : ''); ?> required></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Akan mendaftar PNS</td>
                                        <td><input type="radio" name="akan_mendaftar_pns" value="iya" <?php echo e(old('akan_mendaftar_pns', $simfoni->akan_mendaftar_pns ?? '') == 'iya' ? 'checked' : ''); ?> required></td>
                                        <td><input type="radio" name="akan_mendaftar_pns" value="tidak" <?php echo e(old('akan_mendaftar_pns', $simfoni->akan_mendaftar_pns ?? '') == 'tidak' ? 'checked' : ''); ?> required></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Akan mendaftar PPPK</td>
                                        <td><input type="radio" name="akan_mendaftar_pppk" value="iya" <?php echo e(old('akan_mendaftar_pppk', $simfoni->akan_mendaftar_pppk ?? '') == 'iya' ? 'checked' : ''); ?> required></td>
                                        <td><input type="radio" name="akan_mendaftar_pppk" value="tidak" <?php echo e(old('akan_mendaftar_pppk', $simfoni->akan_mendaftar_pppk ?? '') == 'tidak' ? 'checked' : ''); ?> required></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Akan mengikuti PPG</td>
                                        <td><input type="radio" name="akan_mengikuti_ppg" value="iya" <?php echo e(old('akan_mengikuti_ppg', $simfoni->akan_mengikuti_ppg ?? '') == 'iya' ? 'checked' : ''); ?> required></td>
                                        <td><input type="radio" name="akan_mengikuti_ppg" value="tidak" <?php echo e(old('akan_mengikuti_ppg', $simfoni->akan_mengikuti_ppg ?? '') == 'tidak' ? 'checked' : ''); ?> required></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Akan menulis buku/modul/riset</td>
                                        <td><input type="radio" name="akan_menulis_buku_modul_riset" value="iya" <?php echo e(old('akan_menulis_buku_modul_riset', $simfoni->akan_menulis_buku_modul_riset ?? '') == 'iya' ? 'checked' : ''); ?> required></td>
                                        <td><input type="radio" name="akan_menulis_buku_modul_riset" value="tidak" <?php echo e(old('akan_menulis_buku_modul_riset', $simfoni->akan_menulis_buku_modul_riset ?? '') == 'tidak' ? 'checked' : ''); ?> required></td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Akan mengikuti seleksi diklat CAKEP</td>
                                        <td><input type="radio" name="akan_mengikuti_seleksi_diklat_cakep" value="iya" <?php echo e(old('akan_mengikuti_seleksi_diklat_cakep', $simfoni->akan_mengikuti_seleksi_diklat_cakep ?? '') == 'iya' ? 'checked' : ''); ?> required></td>
                                        <td><input type="radio" name="akan_mengikuti_seleksi_diklat_cakep" value="tidak" <?php echo e(old('akan_mengikuti_seleksi_diklat_cakep', $simfoni->akan_mengikuti_seleksi_diklat_cakep ?? '') == 'tidak' ? 'checked' : ''); ?> required></td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Akan membimbing riset prestasi siswa</td>
                                        <td><input type="radio" name="akan_membimbing_riset_prestasi_siswa" value="iya" <?php echo e(old('akan_membimbing_riset_prestasi_siswa', $simfoni->akan_membimbing_riset_prestasi_siswa ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_membimbing_riset_prestasi_siswa" value="tidak" <?php echo e(old('akan_membimbing_riset_prestasi_siswa', $simfoni->akan_membimbing_riset_prestasi_siswa ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Akan masuk tim unggulan sekolah/madrasah</td>
                                        <td><input type="radio" name="akan_masuk_tim_unggulan_sekolah_madrasah" value="iya" <?php echo e(old('akan_masuk_tim_unggulan_sekolah_madrasah', $simfoni->akan_masuk_tim_unggulan_sekolah_madrasah ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_masuk_tim_unggulan_sekolah_madrasah" value="tidak" <?php echo e(old('akan_masuk_tim_unggulan_sekolah_madrasah', $simfoni->akan_masuk_tim_unggulan_sekolah_madrasah ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Akan kompetisi pimpinan level II</td>
                                        <td><input type="radio" name="akan_kompetisi_pimpinan_level_ii" value="iya" <?php echo e(old('akan_kompetisi_pimpinan_level_ii', $simfoni->akan_kompetisi_pimpinan_level_ii ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_kompetisi_pimpinan_level_ii" value="tidak" <?php echo e(old('akan_kompetisi_pimpinan_level_ii', $simfoni->akan_kompetisi_pimpinan_level_ii ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Akan aktif mengikuti pelatihan</td>
                                        <td><input type="radio" name="akan_aktif_mengikuti_pelatihan" value="iya" <?php echo e(old('akan_aktif_mengikuti_pelatihan', $simfoni->akan_aktif_mengikuti_pelatihan ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_aktif_mengikuti_pelatihan" value="tidak" <?php echo e(old('akan_aktif_mengikuti_pelatihan', $simfoni->akan_aktif_mengikuti_pelatihan ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>Akan aktif MGMP/MKK</td>
                                        <td><input type="radio" name="akan_aktif_mgmp_mkk" value="iya" <?php echo e(old('akan_aktif_mgmp_mkk', $simfoni->akan_aktif_mgmp_mkk ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_aktif_mgmp_mkk" value="tidak" <?php echo e(old('akan_aktif_mgmp_mkk', $simfoni->akan_aktif_mgmp_mkk ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>Akan mengikuti pendidikan kader NU</td>
                                        <td><input type="radio" name="akan_mengikuti_pendidikan_kader_nu" value="iya" <?php echo e(old('akan_mengikuti_pendidikan_kader_nu', $simfoni->akan_mengikuti_pendidikan_kader_nu ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_mengikuti_pendidikan_kader_nu" value="tidak" <?php echo e(old('akan_mengikuti_pendidikan_kader_nu', $simfoni->akan_mengikuti_pendidikan_kader_nu ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>Akan aktif membantu kegiatan lembaga</td>
                                        <td><input type="radio" name="akan_aktif_membantu_kegiatan_lembaga" value="iya" <?php echo e(old('akan_aktif_membantu_kegiatan_lembaga', $simfoni->akan_aktif_membantu_kegiatan_lembaga ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_aktif_membantu_kegiatan_lembaga" value="tidak" <?php echo e(old('akan_aktif_membantu_kegiatan_lembaga', $simfoni->akan_aktif_membantu_kegiatan_lembaga ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>Akan aktif mengikuti kegiatan NU</td>
                                        <td><input type="radio" name="akan_aktif_mengikuti_kegiatan_nu" value="iya" <?php echo e(old('akan_aktif_mengikuti_kegiatan_nu', $simfoni->akan_aktif_mengikuti_kegiatan_nu ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_aktif_mengikuti_kegiatan_nu" value="tidak" <?php echo e(old('akan_aktif_mengikuti_kegiatan_nu', $simfoni->akan_aktif_mengikuti_kegiatan_nu ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>Akan aktif ikut ZIS & kegiatan sosial</td>
                                        <td><input type="radio" name="akan_aktif_ikut_zis_kegiatan_sosial" value="iya" <?php echo e(old('akan_aktif_ikut_zis_kegiatan_sosial', $simfoni->akan_aktif_ikut_zis_kegiatan_sosial ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_aktif_ikut_zis_kegiatan_sosial" value="tidak" <?php echo e(old('akan_aktif_ikut_zis_kegiatan_sosial', $simfoni->akan_aktif_ikut_zis_kegiatan_sosial ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>Akan mengembangkan unit usaha satpen</td>
                                        <td><input type="radio" name="akan_mengembangkan_unit_usaha_satpen" value="iya" <?php echo e(old('akan_mengembangkan_unit_usaha_satpen', $simfoni->akan_mengembangkan_unit_usaha_satpen ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_mengembangkan_unit_usaha_satpen" value="tidak" <?php echo e(old('akan_mengembangkan_unit_usaha_satpen', $simfoni->akan_mengembangkan_unit_usaha_satpen ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>17</td>
                                        <td>Akan bekerja dengan disiplin & produktif</td>
                                        <td><input type="radio" name="akan_bekerja_disiplin_produktif" value="iya" <?php echo e(old('akan_bekerja_disiplin_produktif', $simfoni->akan_bekerja_disiplin_produktif ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_bekerja_disiplin_produktif" value="tidak" <?php echo e(old('akan_bekerja_disiplin_produktif', $simfoni->akan_bekerja_disiplin_produktif ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>18</td>
                                        <td>Akan loyal pada NU & aktif di masyarakat</td>
                                        <td><input type="radio" name="akan_loyal_nu_aktif_masyarakat" value="iya" <?php echo e(old('akan_loyal_nu_aktif_masyarakat', $simfoni->akan_loyal_nu_aktif_masyarakat ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_loyal_nu_aktif_masyarakat" value="tidak" <?php echo e(old('akan_loyal_nu_aktif_masyarakat', $simfoni->akan_loyal_nu_aktif_masyarakat ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                    <tr>
                                        <td>19</td>
                                        <td>Bersedia dipindah ke satpen lain</td>
                                        <td><input type="radio" name="akan_bersedia_dipindah_satpen_lain" value="iya" <?php echo e(old('akan_bersedia_dipindah_satpen_lain', $simfoni->akan_bersedia_dipindah_satpen_lain ?? '') == 'iya' ? 'checked' : ''); ?> requaired></td>
                                        <td><input type="radio" name="akan_bersedia_dipindah_satpen_lain" value="tidak" <?php echo e(old('akan_bersedia_dipindah_satpen_lain', $simfoni->akan_bersedia_dipindah_satpen_lain ?? '') == 'tidak' ? 'checked' : ''); ?> requaired></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" style="text-align: right; font-weight: bold; border: none;">Total Skor Proyeksi:</td>
                                        <td style="text-align: center; font-weight: bold; border: none;"><input type="text" id="totalSkorProyeksi" name="skor_proyeksi" value="<?php echo e(old('skor_proyeksi', $simfoni->skor_proyeksi ?? 0)); ?>" readonly style="width: 50px; text-align: center; font-weight: bold; border: 1px solid #dee2e6;"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>



                        <div class="form-group">
                            <label style="font-weight: bold; color: #dc3545;">PERNYATAAN</label>
                            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #e9ecef; font-size: 11px; line-height: 1.4;">
                                Dengan ini saya menyatakan bahwa semua data yang saya tulis di atas adalah BENAR dan DAPAT DIPERTANGGUNGJAWABKAN. Apabila di kemudian hari ditemukan ketidaksesuaian, saya bersedia menerima konsekuensi dan sanksi yang berlaku.
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" id="pernyataan_setuju" name="pernyataan_setuju" value="1" <?php echo e(old('pernyataan_setuju', $simfoni->pernyataan_setuju ?? '') ? 'checked' : ''); ?> style="margin-right: 8px;">
                                <label for="pernyataan_setuju" style="margin: 0; font-size: 11px; color: #004b4c;">Saya menyetujui dan bertanggung jawab atas kebenaran data yang saya isi</label>
                            </div>
                            <?php $__errorArgs = ['pernyataan_setuju'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div> <!-- /.section-content -->
                </div> <!-- /.section-card -->
            </div> <!-- /.step-content -->

            <!-- Navigation Buttons -->
            <div class="navigation-container">
                <div class="nav-buttons">
                    <button type="button" class="nav-btn nav-prev" id="prevBtn" style="display: none;">
                        <i class="bx bx-chevron-left"></i>
                    </button>

                    <div class="step-indicator">
                        Langkah <span id="currentStep">1</span> dari 7
                    </div>

                    <button type="button" class="nav-btn nav-next" id="nextBtn">
                        <i class="bx bx-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button (Hidden initially) -->
            <div class="submit-container" id="submitContainer" style="display: none;">
                <button type="submit" class="submit-btn">
                    <i class="bx bx-save"></i> SIMPAN DATA
                </button>
            </div>
        </form>
    </div> <!-- /.form-container -->
</div> <!-- /.container -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ===== FORMAT RUPIAH (INDONESIA, TANPA DESIMAL) =====
        function formatRupiah(angka) {
            const number = parseInt(angka, 10);
            if (isNaN(number)) return '';
            return number.toLocaleString('id-ID');
        }

        function unformatRupiah(str) {
            return parseInt(str.replace(/\./g, ''), 10) || 0;
        }

        // Auto-fill readonly fields (if present)
        const mappings = {
            'nama_lengkap_gelar': '<?php echo e($user->name ?? ""); ?>',
            'tempat_lahir': '<?php echo e($user->tempat_lahir ?? ""); ?>',
            'tanggal_lahir': '<?php echo e($user->tanggal_lahir ?? ""); ?>',
            'nuptk': '<?php echo e($user->nuptk ?? ""); ?>',
            'kartanu': '<?php echo e($user->kartanu ?? ""); ?>',
            'nipm': '<?php echo e($user->nipm ?? ""); ?>',
            'tmt': '<?php echo e($user->tmt ?? ""); ?>',
            'strata_pendidikan': '<?php echo e($user->pendidikan_terakhir ?? ""); ?>',
            'program_studi': '<?php echo e($user->program_studi ?? ""); ?>',
            'no_hp': '<?php echo e($user->phone ?? ""); ?>',
            'email': '<?php echo e($user->email ?? ""); ?>'
        };

        Object.keys(mappings).forEach(name => {
            const el = document.querySelector(`input[name="${name}"]`);
            if (el && !el.value) el.value = mappings[name];
        });

        // Multi-step form navigation
        let currentStep = 1;
        const totalSteps = 7;

        // Define required fields for each step
        const stepRequiredFields = {
            1: ['nama_lengkap_gelar', 'tempat_lahir', 'tanggal_lahir', 'nik', 'tmt', 'strata_pendidikan', 'tahun_lulus', 'program_studi'],
            2: ['status_kerja', 'tanggal_sk_pertama', 'nomor_sk_pertama'],
            3: ['no_hp', 'email', 'status_pernikahan', 'alamat_lengkap'],
            4: [], // No required fields in step 4
            5: [], // No required fields in step 5
            6: [], // No required fields in step 6
            7: ['akan_kuliah_s2', 'akan_mendaftar_pns', 'akan_mendaftar_pppk', 'akan_mengikuti_ppg', 'akan_menulis_buku_modul_riset', 'akan_mengikuti_seleksi_diklat_cakep', 'akan_membimbing_riset_prestasi_siswa', 'akan_masuk_tim_unggulan_sekolah_madrasah', 'akan_kompetisi_pimpinan_level_ii', 'akan_aktif_mengikuti_pelatihan', 'akan_aktif_mgmp_mkk', 'akan_mengikuti_pendidikan_kader_nu', 'akan_aktif_membantu_kegiatan_lembaga', 'akan_aktif_mengikuti_kegiatan_nu', 'akan_aktif_ikut_zis_kegiatan_sosial', 'akan_mengembangkan_unit_usaha_satpen', 'akan_bekerja_disiplin_produktif', 'akan_loyal_nu_aktif_masyarakat', 'akan_bersedia_dipindah_satpen_lain', 'pernyataan_setuju']
        };

        function updateRequiredFields(step) {
            // Remove required from all fields first
            document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
                field.removeAttribute('required');
            });

            // Add required to fields in current step
            if (stepRequiredFields[step]) {
                stepRequiredFields[step].forEach(fieldName => {
                    const field = document.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        field.setAttribute('required', 'required');
                    }
                });
            }
        }

        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.step-content').forEach(stepEl => {
                stepEl.classList.remove('active');
                stepEl.style.display = 'none';
            });

            // Show current step
            const currentStepEl = document.querySelector(`.step-content[data-step="${step}"]`);
            if (currentStepEl) {
                currentStepEl.classList.add('active');
                currentStepEl.style.display = 'block';
            }

            // Update required fields
            updateRequiredFields(step);

            // Update timeline
            document.querySelectorAll('.timeline-step').forEach((timelineStep, index) => {
                const stepNumber = index + 1;
                timelineStep.classList.remove('active', 'completed');

                if (stepNumber === step) {
                    timelineStep.classList.add('active');
                } else if (stepNumber < step) {
                    timelineStep.classList.add('completed');
                }
            });

            // Update step indicator
            const currentStepElText = document.getElementById('currentStep');
            if (currentStepElText) currentStepElText.textContent = step;

            // Update navigation buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitContainer = document.getElementById('submitContainer');

            if (prevBtn && nextBtn && submitContainer) {
                if (step === 1) {
                    prevBtn.style.display = 'none';
                    nextBtn.style.display = 'block';
                    submitContainer.style.display = 'none';
                } else if (step === totalSteps) {
                    prevBtn.style.display = 'block';
                    nextBtn.style.display = 'none';
                    submitContainer.style.display = 'block';
                } else {
                    prevBtn.style.display = 'block';
                    nextBtn.style.display = 'block';
                    submitContainer.style.display = 'none';
                }
            }
        }

        // Buttons
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        }

        // ===== AUTO FORMAT & HITUNG PENGHASILAN =====
        const form = document.getElementById('simfoniForm');
        if (form) {
            form.addEventListener('input', function (e) {
                const watched = ['gaji_sertifikasi', 'gaji_pokok', 'honor_lain', 'penghasilan_lain'];

                if (watched.includes(e.target.name)) {
                    // Format angka saat diketik
                    e.target.value = formatRupiah(unformatRupiah(e.target.value));

                    const getValue = (name) => {
                        const el = document.querySelector(`input[name="${name}"]`);
                        return el ? unformatRupiah(el.value) : 0;
                    };

                    const total =
                        getValue('gaji_sertifikasi') +
                        getValue('gaji_pokok') +
                        getValue('honor_lain') +
                        getValue('penghasilan_lain');

                    // Total penghasilan
                    const totalEl = document.getElementById('totalPenghasilan');
                    if (totalEl) {
                        totalEl.value = formatRupiah(total);
                    }

                    // Kategori penghasilan
                    let kategori = '';
                    if (total >= 10000000) {
                        kategori = 'A = Bagus (≥ 10 juta)';
                    } else if (total >= 6000000) {
                        kategori = 'B = Baik (6,0 – 9,9 juta)';
                    } else if (total >= 4000000) {
                        kategori = 'C = Cukup (4,0 – 5,9 juta)';
                    } else if (total >= 2500000) {
                        kategori = 'D = Hampir Cukup (2,5 – 3,9 juta)';
                    } else if (total >= 1500000) {
                        kategori = 'E = Kurang (1,5 – 2,4 juta)';
                    } else {
                        kategori = 'F = Sangat Kurang (< 1,5 juta)';
                    }

                    const kategoriEl = document.getElementById('kategoriPenghasilan');
                    if (kategoriEl) kategoriEl.value = kategori;
                }
            });
        }

        // Calculate masa kerja from TMT to June 2025
        let totalYears = 0; // Global variable to store total years

        function calculateMasaKerja() {
            const tmtInput = document.getElementById('tmtInput');
            const masaKerjaInput = document.getElementById('masaKerja');

            if (!tmtInput || !masaKerjaInput) return;

            const tmtDate = new Date(tmtInput.value);
            if (!tmtInput.value || isNaN(tmtDate.getTime())) {
                masaKerjaInput.value = '';
                totalYears = 0;
                filterStatusKerja();
                return;
            }

            // Target date: July 31, 2025
            const targetDate = new Date(2025, 6, 31); // Month is 0-indexed, so 6 = July

            // Calculate difference
            let years = targetDate.getFullYear() - tmtDate.getFullYear();
            let months = targetDate.getMonth() - tmtDate.getMonth();

            // Adjust if months is negative
            if (months < 0) {
                years--;
                months += 12;
            }

            // Adjust if target day is before TMT day in the same month
            if (targetDate.getDate() < tmtDate.getDate() && months === 0) {
                years--;
                months = 11;
            }

            totalYears = years + (months / 12); // Store as decimal for comparison
            masaKerjaInput.value = `${years} tahun ${months} bulan`;
            filterStatusKerja();
        }

        // Filter status kerja options based on masa kerja
        function filterStatusKerja() {
            const statusKerjaSelect = document.getElementById('statusKerjaSelect');
            if (!statusKerjaSelect) return;

            const options = statusKerjaSelect.querySelectorAll('option');
            options.forEach(option => {
                const value = option.value.toLowerCase();
                if (totalYears < 2 && totalYears > 0) {
                    // Show only GTT and PTT (assuming these are the values for id 6 and 8)
                    if (value.includes('gtt') || value.includes('ptt')) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                } else {
                    // Show all options
                    option.style.display = 'block';
                }
            });

            // Reset selection if current selection is hidden
            if (statusKerjaSelect.selectedOptions[0] && statusKerjaSelect.selectedOptions[0].style.display === 'none') {
                statusKerjaSelect.selectedIndex = 0;
            }

            // Disable select if masa kerja is empty
            if (totalYears === 0) {
                statusKerjaSelect.disabled = true;
                statusKerjaSelect.selectedIndex = 0;
            } else {
                statusKerjaSelect.disabled = false;
            }
        }

        // Add event listener to TMT input
        const tmtInput = document.getElementById('tmtInput');
        if (tmtInput) {
            tmtInput.addEventListener('change', calculateMasaKerja);
            // Calculate on page load if TMT has value
            if (tmtInput.value) {
                calculateMasaKerja();
            }
        }

        // Initialize UI
        showStep(currentStep);

        // Function to calculate skor for proyeksi
        function calculateSkorProyeksi() {
            let totalSkor = 0;
            for (let i = 1; i <= 19; i++) {
                const iyaRadio = document.querySelector(`input[name="akan_${getFieldName(i)}"][value="iya"]`);
                const sudahRadio = document.querySelector(`input[name="akan_${getFieldName(i)}"][value="sudah"]`);
                const skorField = document.getElementById(`skor_${i}`);

                let skor = 0;
                if (iyaRadio && iyaRadio.checked) {
                    skor = 1;
                } else if (sudahRadio && sudahRadio.checked) {
                    skor = 2;
                }

                if (skorField) {
                    skorField.value = skor;
                }
                totalSkor += skor;
            }

            const totalField = document.getElementById('totalSkorProyeksi');
            if (totalField) {
                totalField.value = totalSkor;
            }
        }

        // Helper function to get field name based on index
        function getFieldName(index) {
            const fieldNames = [
                'kuliah_s2',
                'mendaftar_pns',
                'mendaftar_pppk',
                'mengikuti_ppg',
                'menulis_buku_modul_riset',
                'mengikuti_seleksi_diklat_cakep',
                'membimbing_riset_prestasi_siswa',
                'masuk_tim_unggulan_sekolah_madrasah',
                'kompetisi_pimpinan_level_ii',
                'aktif_mengikuti_pelatihan',
                'aktif_mgmp_mkk',
                'mengikuti_pendidikan_kader_nu',
                'aktif_membantu_kegiatan_lembaga',
                'aktif_mengikuti_kegiatan_nu',
                'aktif_ikut_zis_kegiatan_sosial',
                'mengembangkan_unit_usaha_satpen',
                'bekerja_disiplin_produktif',
                'loyal_nu_aktif_masyarakat',
                'bersedia_dipindah_satpen_lain'
            ];
            return fieldNames[index - 1];
        }

        // Add event listeners to radio buttons in step 7
        document.addEventListener('change', function(e) {
            if (e.target.type === 'radio' && e.target.name.startsWith('akan_')) {
                calculateSkorProyeksi();
            }
        });

        // Calculate on page load if values are set
        calculateSkorProyeksi();

        // Title case for Tempat Lahir, Nama Lengkap dengan Gelar, PT Asal, and Nama Program Studi
        function toTitleCase(str) {
            return str.replace(/\w\S*/g, function(txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        }

        const tempatLahirInput = document.querySelector('input[name="tempat_lahir"]');
        if (tempatLahirInput) {
            tempatLahirInput.addEventListener('input', function() {
                this.value = toTitleCase(this.value);
            });
        }

        const namaLengkapInput = document.querySelector('input[name="nama_lengkap_gelar"]');
        if (namaLengkapInput) {
            namaLengkapInput.addEventListener('input', function() {
                this.value = toTitleCase(this.value);
            });
        }

        const ptAsalInput = document.querySelector('input[name="pt_asal"]');
        if (ptAsalInput) {
            ptAsalInput.addEventListener('input', function() {
                this.value = toTitleCase(this.value);
            });
        }

        const programStudiInput = document.querySelector('input[name="program_studi"]');
        if (programStudiInput) {
            programStudiInput.addEventListener('input', function() {
                this.value = toTitleCase(this.value);
            });
        }

        const tahunSertifikasiInput = document.getElementById('tahunSertifikasiInput');
        if (tahunSertifikasiInput) {
            tahunSertifikasiInput.addEventListener('keydown', function(e) {
                if (e.key === ' ' && this.value.match(/\d{4}$/) && !this.value.includes('&')) {
                    e.preventDefault();
                    this.value += ' & ';
                }
            });
            tahunSertifikasiInput.addEventListener('input', function() {
                // Remove invalid characters except digits, &, space
                this.value = this.value.replace(/[^0-9&\s]/g, '');
            });
        }

        // PPPK fields show/hide functionality
        const pppkYes = document.getElementById('pppk_yes');
        const pppkNo = document.getElementById('pppk_no');
        const pppkDetails = document.getElementById('pppk_details');

        function togglePppkDetails() {
            if (pppkYes && pppkYes.checked) {
                pppkDetails.style.display = 'grid';
            } else {
                pppkDetails.style.display = 'none';
            }
        }

        if (pppkYes) {
            pppkYes.addEventListener('change', togglePppkDetails);
        }
        if (pppkNo) {
            pppkNo.addEventListener('change', togglePppkDetails);
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/simfoni.blade.php ENDPATH**/ ?>