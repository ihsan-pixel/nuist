<?php $__env->startSection('title', 'Buat Laporan Akhir Tahun'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/mobile/laporan-akhir-tahun-create.css')); ?>">

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
    <button onclick="window.location.href='<?php echo e(route('mobile.laporan-akhir-tahun.index')); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>LAPORAN AKHIR TAHUN</h4>
    <p>Kepala Madrasah/Sekolah</p>
</div>

<!-- Form Container -->
<div class="form-container">
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

    <!-- Step Indicators -->
    <div class="step-indicators">
        <div class="step-indicator active" data-step="1"><i class="bx bx-file"></i></div>
        <div class="step-indicator" data-step="2">1</div>
        <div class="step-indicator" data-step="3">2</div>
        <div class="step-indicator" data-step="4">3</div>
        <div class="step-indicator" data-step="5">4</div>
        <div class="step-indicator" data-step="6">5</div>
        <div class="step-indicator" data-step="7">6</div>
        <div class="step-indicator" data-step="8">7</div>
    </div>

    <form action="<?php echo e(route('mobile.laporan-akhir-tahun.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <!-- Step 1: DATA POKOK LAPORAN -->
        <div class="step-content active" data-step="1">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-file"></i>
                    </div>
                    <h6 class="section-title">DATA POKOK LAPORAN</h6>
                </div>

                <div class="section-content">
                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Nama Kepala Sekolah</label>
                            <input type="text" name="nama_kepala_sekolah" value="<?php echo e(old('nama_kepala_sekolah', $data['nama_kepala_sekolah'] ?? '')); ?>" placeholder="Nama Lengkap" required>
                            <?php $__errorArgs = ['nama_kepala_sekolah'];
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
                            <label>Gelar</label>
                            <input type="text" name="gelar" value="<?php echo e(old('gelar')); ?>" placeholder="S.Pd., M.Pd., dll">
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
                    </div>

                    <div class="row-2col">
                        <div class="form-group required">
                            <label>NIPM</label>
                            <input type="text" name="nip" value="<?php echo e(old('nip', $data['nip'] ?? '')); ?>" placeholder="NIPM" required>
                            <?php $__errorArgs = ['nip'];
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
                            <label>Tanggal TMT Kepala Sekolah</label>
                            <input type="date" name="tanggal_tmt_kepala_sekolah" value="<?php echo e(old('tanggal_tmt_kepala_sekolah')); ?>" required>
                            <?php $__errorArgs = ['tanggal_tmt_kepala_sekolah'];
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
                        <label>Tahun Laporan</label>
                        <input type="number" name="tahun_pelaporan" value="<?php echo e(old('tahun_pelaporan', $data['tahun_pelaporan'] ?? date('Y'))); ?>" min="2020" max="<?php echo e(date('Y') + 1); ?>" placeholder="2024" required>
                        <?php $__errorArgs = ['tahun_pelaporan'];
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

        <!-- Step 2: 1. TARGET UTAMA -->
        <div class="step-content" data-step="2">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-target"></i>
                    </div>
                    <h6 class="section-title">1. TARGET UTAMA</h6>
                </div>

                <div class="divider">
                    <span>Siswa</span>
                </div>

                <div class="section-content">
                    <h6 class="mb-3">1-A. Capaian dan Target Utama</h6>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Jumlah Siswa</label>
                            <input type="number" name="target_jumlah_siswa" id="target_jumlah_siswa" value="<?php echo e(old('target_jumlah_siswa')); ?>" min="0" placeholder="0">
                            <?php $__errorArgs = ['target_jumlah_siswa'];
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
                            <label>Capaian Jumlah Siswa</label>
                            <input type="number" name="capaian_jumlah_siswa" id="capaian_jumlah_siswa" value="<?php echo e(old('capaian_jumlah_siswa')); ?>" min="0" placeholder="0">
                            <?php $__errorArgs = ['capaian_jumlah_siswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div id="capaian_siswa_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Siswa Tahun Berikutnya</label>
                        <input type="number" name="target_tahun_berikutnya" value="<?php echo e(old('target_tahun_berikutnya')); ?>" min="0" placeholder="0">
                        <?php $__errorArgs = ['target_tahun_berikutnya'];
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

                    <div class="info-note">
                        <strong>*</strong> Jumlah siswa dihitung keseluruhan tapi pada lampiran tetap dirinci kelas X, XI, XII. Pada SLB disebutkan seluruh jenjang<br>
                    </div>

                    <div class="divider">
                        <span>Dana</span>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Dana</label>
                            <input type="text" name="target_dana" id="target_dana" value="<?php echo e(old('target_dana')); ?>" placeholder="Rp 0">
                            <?php $__errorArgs = ['target_dana'];
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
                            <label>Capaian Dana</label>
                            <input type="text" name="capaian_dana" id="capaian_dana" value="<?php echo e(old('capaian_dana')); ?>" placeholder="Rp 0">
                            <?php $__errorArgs = ['capaian_dana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div id="capaian_dana_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Dana Tahun Berikutnya</label>
                        <input type="text" name="target_dana_tahun_berikutnya" value="<?php echo e(old('target_dana_tahun_berikutnya')); ?>" placeholder="Rp 0">
                        <?php $__errorArgs = ['target_dana_tahun_berikutnya'];
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
                    <div class="info-note">
                        <strong>**</strong> Jumlah dana adalah jumlah gabungan dari BOSNAS, BOSDA, SPP, BP3 dll. Di atas ditulis global, pada lampiran didetailkan
                    </div>

                    <div class="divider">
                        <span>Alumni</span>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Target Alumni</label>
                            <div class="input-with-symbol">
                                <input type="text" name="target_alumni" id="target_alumni" value="<?php echo e(old('target_alumni')); ?>" placeholder="0">
                                <span class="input-symbol">%</span>
                            </div>
                            <?php $__errorArgs = ['target_alumni'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div id="target_alumni_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label>Capaian Alumni</label>
                            <div class="input-with-symbol">
                                <input type="text" name="capaian_alumni" id="capaian_alumni" value="<?php echo e(old('capaian_alumni')); ?>" placeholder="0">
                                <span class="input-symbol">%</span>
                            </div>
                            <?php $__errorArgs = ['capaian_alumni'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div id="capaian_alumni_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Target Alumni Berikutnya</label>
                        <div class="input-with-symbol">
                            <input type="text" name="target_alumni_berikutnya" value="<?php echo e(old('target_alumni_berikutnya')); ?>" placeholder="0">
                            <span class="input-symbol">%</span>
                        </div>
                        <?php $__errorArgs = ['target_alumni_berikutnya'];
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

                    <div class="divider">
                        <span>Akreditasi</span>
                    </div>

                    <div class="row-2col">
                        <div class="form-group">
                            <label>Akreditasi</label>
                            <select name="akreditasi" id="akreditasi">
                                <option value="">Pilih Akreditasi</option>
                                <option value="Belum" <?php echo e(old('akreditasi') == 'Belum' ? 'selected' : ''); ?>>Belum</option>
                                <option value="C" <?php echo e(old('akreditasi') == 'C' ? 'selected' : ''); ?>>C</option>
                                <option value="B" <?php echo e(old('akreditasi') == 'B' ? 'selected' : ''); ?>>B</option>
                                <option value="A" <?php echo e(old('akreditasi') == 'A' ? 'selected' : ''); ?>>A</option>
                            </select>
                            <?php $__errorArgs = ['akreditasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="form-error"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div id="akreditasi_info" class="dynamic-info" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label>Tahun Akreditasi</label>
                            <input type="number" name="tahun_akreditasi" value="<?php echo e(old('tahun_akreditasi')); ?>" min="2000" max="<?php echo e(date('Y') + 10); ?>" placeholder="2024">
                            <?php $__errorArgs = ['tahun_akreditasi'];
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
                        <label>Nilai Akreditasi</label>
                        <input type="number" name="nilai_akreditasi" value="<?php echo e(old('nilai_akreditasi')); ?>" min="0" max="100" step="0.01" placeholder="0.00">
                        <?php $__errorArgs = ['nilai_akreditasi'];
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
                            <!-- Total Skor Field -->
                            <div class="form-group" style="margin-bottom: 12px; padding: 8px; background: #fff; border-radius: 6px; border: 2px solid #004b4c;">
                                <label style="font-weight: 600; color: #004b4c; margin-bottom: 4px; display: block;">Total Skor</label>
                                <input type="text" id="total_skor" value="0" readonly style="width: 100%; padding: 8px; border: none; background: transparent; font-weight: bold; font-size: 14px; color: #004b4c;">
                                <div class="form-hint">Total skor otomatis dari capaian siswa, dana, alumni, dan akreditasi</div>
                                <div id="total_skor_info" class="dynamic-info" style="display: none;"></div>
                                <!-- Rincian Skor -->
                                <div id="skor_breakdown" style="margin-top: 8px; font-size: 11px; color: #004b4c;">
                                    <div><strong>Rincian Skor:</strong></div>
                                    <div id="skor_siswa_kategori">Skor Kategori Siswa: 0</div>
                                    <div id="skor_siswa_prestasi">Skor Prestasi Siswa: 0</div>
                                    <div id="skor_dana_kategori">Skor Kategori Dana: 0</div>
                                    <div id="skor_dana_prestasi">Skor Prestasi Dana: 0</div>
                                    <div id="skor_alumni_kategori">Skor Kategori Alumni: 0</div>
                                    <div id="skor_alumni_prestasi">Skor Prestasi Alumni: 0</div>
                                    <div id="skor_akreditasi">Skor Akreditasi: 0</div>
                                    <div style="border-top: 1px solid #004b4c; margin-top: 4px; padding-top: 4px;"><strong id="total_breakdown">Total: 0</strong></div>
                                </div>
                            </div>

                    <!-- Penjelasan -->
                    <div class="form-group">
                        <button type="button" id="toggle_penjelasan" class="btn btn-link text-decoration-none p-0 mb-2" style="color: #004b4c; font-size: 12px; font-weight: 600;">
                            <i class="bx bx-info-circle" style="margin-right: 4px;"></i>
                            Lihat Penjelasan
                        </button>
                        <div id="penjelasan_content" style="background: #f7e0e0; padding: 12px; border-radius: 8px; font-size: 11px; line-height: 1.4; color: #004b4c; display: none;">

                            <div class="info-section">
                                <h5>Skor Prestasi Target SDA</h5>
                                <table class="info-table">
                                    <tr><td>+0 = turun</td><td>+1 = tetap</td><td>+2 = naik</td></tr>
                                </table>
                            </div>

                            <div class="info-section">
                                <h5>Prestasi Akreditasi</h5>
                                <table class="info-table">
                                    <tr><td>Belum = +1</td><td>C = +4</td><td>B = +7</td><td>A = +10</td></tr>
                                </table>
                            </div>

                            <div class="info-section">
                                <h5>Kategori Berdasarkan Jumlah Siswa</h5>
                                <table class="info-table">
                                    <thead>
                                        <tr><th>Skor</th><th>Kategori</th><th>Jumlah Siswa</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>9</td><td>Unggulan A</td><td>>1001</td></tr>
                                        <tr><td>8</td><td>Unggulan B</td><td>751 - 1000</td></tr>
                                        <tr><td>7</td><td>Mandiri A</td><td>501 - 750</td></tr>
                                        <tr><td>6</td><td>Mandiri B</td><td>251 - 500</td></tr>
                                        <tr><td>5</td><td>Pramandiri A</td><td>151 - 250</td></tr>
                                        <tr><td>4</td><td>Pramandiri B</td><td>101 - 150</td></tr>
                                        <tr><td>3</td><td>Rintisan A</td><td>61 - 100</td></tr>
                                        <tr><td>2</td><td>Rintisan B</td><td>20 - 60</td></tr>
                                        <tr><td>1</td><td>Posisi Zero</td><td>0 - 19</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="info-section">
                                <h5>Kategori Berdasarkan Jumlah Dana (Juta)</h5>
                                <table class="info-table">
                                    <thead>
                                        <tr><th>Skor</th><th>Kategori</th><th>Jumlah Dana</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>9</td><td>Unggulan A</td><td>>5001</td></tr>
                                        <tr><td>8</td><td>Unggulan B</td><td>3001-5000</td></tr>
                                        <tr><td>7</td><td>Mandiri A</td><td>2000 - 3000</td></tr>
                                        <tr><td>6</td><td>Mandiri B</td><td>1251 - 1999</td></tr>
                                        <tr><td>5</td><td>Pramandiri A</td><td>751 - 1250</td></tr>
                                        <tr><td>4</td><td>Pramandiri B</td><td>351 - 750</td></tr>
                                        <tr><td>3</td><td>Rintisan A</td><td>151 - 350</td></tr>
                                        <tr><td>2</td><td>Rintisan B</td><td>30 - 150</td></tr>
                                        <tr><td>1</td><td>Posisi Zero</td><td>0 - 29</td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="info-section">
                                <h5>Kategori Berdasarkan Keterserapan Lulusan</h5>
                                <table class="info-table">
                                    <thead>
                                        <tr><th>Skor</th><th>Kategori</th><th>Persentase</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr><td>9</td><td>Unggulan A</td><td>81 - 100%</td></tr>
                                        <tr><td>8</td><td>Unggulan B</td><td>66 - 80%</td></tr>
                                        <tr><td>7</td><td>Mandiri A</td><td>51 - 65%</td></tr>
                                        <tr><td>6</td><td>Mandiri B</td><td>35 - 50%</td></tr>
                                        <tr><td>5</td><td>Pramandiri A</td><td>20 - 34%</td></tr>
                                        <tr><td>4</td><td>Pramandiri B</td><td>10 - 19%</td></tr>
                                        <tr><td>3</td><td>Rintisan A</td><td>3 - 9%</td></tr>
                                        <tr><td>2</td><td>Rintisan B</td><td>1 - 2%</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">1-B. UPAYA Satpen Meraih Target Utama di Atas? (Skor maksimal 20)</h6>

                    <!-- Untuk Capaian Siswa -->
                    <div class="form-group">
                        <label>Untuk Capaian Siswa</label>
                        <div class="dynamic-inputs" data-category="siswa">
                            <div class="input-row">
                                <input type="text" name="upaya_capaian_siswa[]" placeholder="Upaya untuk mencapai target siswa" value="<?php echo e(old('upaya_capaian_siswa.0')); ?>">
                                <button type="button" class="add-input-btn" onclick="addInputField('siswa')">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            <?php if(old('upaya_capaian_siswa')): ?>
                                <?php $__currentLoopData = old('upaya_capaian_siswa'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($index > 0 && !empty($value)): ?>
                                        <div class="input-row">
                                            <input type="text" name="upaya_capaian_siswa[]" placeholder="Upaya untuk mencapai target siswa" value="<?php echo e($value); ?>">
                                            <button type="button" class="remove-input-btn" onclick="removeInputField(this)">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                        <?php $__errorArgs = ['upaya_capaian_siswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['upaya_capaian_siswa.*'];
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

                    <!-- Untuk Capaian Dana -->
                    <div class="form-group">
                        <label>Untuk Capaian Dana</label>
                        <div class="dynamic-inputs" data-category="dana">
                            <div class="input-row">
                                <input type="text" name="upaya_capaian_dana[]" placeholder="Upaya untuk mencapai target dana" value="<?php echo e(old('upaya_capaian_dana.0')); ?>">
                                <button type="button" class="add-input-btn" onclick="addInputField('dana')">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                        </div>
                        <?php $__errorArgs = ['upaya_capaian_dana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['upaya_capaian_dana.*'];
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

                    <!-- Untuk Alumni BMWA -->
                    <div class="form-group">
                        <label>Untuk Alumni BMWA</label>
                        <div class="dynamic-inputs" data-category="alumni">
                            <div class="input-row">
                                <input type="text" name="upaya_alumni_bmwa[]" placeholder="Upaya untuk alumni BMWA" value="<?php echo e(old('upaya_alumni_bmwa.0')); ?>">
                                <button type="button" class="add-input-btn" onclick="addInputField('alumni')">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            <?php if(old('upaya_alumni_bmwa')): ?>
                                <?php $__currentLoopData = old('upaya_alumni_bmwa'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($index > 0 && !empty($value)): ?>
                                        <div class="input-row">
                                            <input type="text" name="upaya_alumni_bmwa[]" placeholder="Upaya untuk alumni BMWA" value="<?php echo e($value); ?>">
                                            <button type="button" class="remove-input-btn" onclick="removeInputField(this)">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                        <?php $__errorArgs = ['upaya_alumni_bmwa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['upaya_alumni_bmwa.*'];
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

                    <!-- Untuk Akreditasi -->
                    <div class="form-group">
                        <label>Untuk Akreditasi</label>
                        <div class="dynamic-inputs" data-category="akreditasi">
                            <div class="input-row">
                                <input type="text" name="upaya_akreditasi[]" placeholder="Upaya untuk akreditasi" value="<?php echo e(old('upaya_akreditasi.0')); ?>">
                                <button type="button" class="add-input-btn" onclick="addInputField('akreditasi')">
                                    <i class="bx bx-plus"></i>
                                </button>
                            </div>
                            <?php if(old('upaya_akreditasi')): ?>
                                <?php $__currentLoopData = old('upaya_akreditasi'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($index > 0 && !empty($value)): ?>
                                        <div class="input-row">
                                            <input type="text" name="upaya_akreditasi[]" placeholder="Upaya untuk akreditasi" value="<?php echo e($value); ?>">
                                            <button type="button" class="remove-input-btn" onclick="removeInputField(this)">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                        <?php $__errorArgs = ['upaya_akreditasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="form-error"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['upaya_akreditasi.*'];
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

        <!-- Step 3: B. DATA MADRASAH -->
        <div class="step-content" data-step="3">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-building"></i>
                    </div>
                    <h6 class="section-title">B. DATA MADRASAH</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Nama Madrasah</label>
                        <input type="text" name="nama_madrasah" value="<?php echo e(old('nama_madrasah', $data['nama_madrasah'] ?? '')); ?>" placeholder="Nama Madrasah" required>
                        <?php $__errorArgs = ['nama_madrasah'];
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
                        <label>Alamat Madrasah</label>
                        <textarea name="alamat_madrasah" placeholder="Alamat lengkap madrasah" required><?php echo e(old('alamat_madrasah', $data['alamat_madrasah'] ?? '')); ?></textarea>
                        <?php $__errorArgs = ['alamat_madrasah'];
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

        <!-- Step 4: C. DATA STATISTIK -->
        <div class="step-content" data-step="4">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-bar-chart"></i>
                    </div>
                    <h6 class="section-title">C. DATA STATISTIK</h6>
                </div>

                <div class="section-content">
                    <div class="row-2col">
                        <div class="form-group required">
                            <label>Jumlah Guru</label>
                            <input type="number" name="jumlah_guru" value="<?php echo e(old('jumlah_guru')); ?>" min="0" placeholder="0" required>
                            <?php $__errorArgs = ['jumlah_guru'];
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
                            <label>Jumlah Siswa</label>
                            <input type="number" name="jumlah_siswa" id="jumlah_siswa" value="<?php echo e(old('jumlah_siswa')); ?>" min="0" placeholder="0" required>
                            <?php $__errorArgs = ['jumlah_siswa'];
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
                        <label>Jumlah Kelas</label>
                        <input type="number" name="jumlah_kelas" value="<?php echo e(old('jumlah_kelas')); ?>" min="0" placeholder="0" required>
                        <?php $__errorArgs = ['jumlah_kelas'];
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

        <!-- Step 5: D. INFORMASI LAPORAN -->
        <div class="step-content" data-step="5">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-calendar"></i>
                    </div>
                    <h6 class="section-title">D. INFORMASI LAPORAN</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Tanggal Laporan</label>
                        <input type="date" name="tanggal_laporan" value="<?php echo e(old('tanggal_laporan', date('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['tanggal_laporan'];
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

        <!-- Step 6: E. PRESTASI MADRASAH -->
        <div class="step-content" data-step="6">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-trophy"></i>
                    </div>
                    <h6 class="section-title">E. PRESTASI MADRASAH</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Prestasi Madrasah</label>
                        <textarea name="prestasi_madrasah" placeholder="Jelaskan prestasi-prestasi yang telah dicapai oleh madrasah selama tahun ini..." required><?php echo e(old('prestasi_madrasah')); ?></textarea>
                        <?php $__errorArgs = ['prestasi_madrasah'];
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

        <!-- Step 7: F. KENDALA UTAMA -->
        <div class="step-content" data-step="7">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-error"></i>
                    </div>
                    <h6 class="section-title">F. KENDALA UTAMA</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Kendala Utama</label>
                        <textarea name="kendala_utama" placeholder="Jelaskan kendala-kendala utama yang dihadapi selama tahun ini..." required><?php echo e(old('kendala_utama')); ?></textarea>
                        <?php $__errorArgs = ['kendala_utama'];
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

        <!-- Step 8: G. PROGRAM KERJA TAHUN DEPAN -->
        <div class="step-content" data-step="8">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="bx bx-target"></i>
                    </div>
                    <h6 class="section-title">G. PROGRAM KERJA TAHUN DEPAN</h6>
                </div>

                <div class="section-content">
                    <div class="form-group required">
                        <label>Program Kerja Tahun Depan</label>
                        <textarea name="program_kerja_tahun_depan" placeholder="Jelaskan program-program kerja yang akan dilaksanakan pada tahun depan..." required><?php echo e(old('program_kerja_tahun_depan')); ?></textarea>
                        <?php $__errorArgs = ['program_kerja_tahun_depan'];
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
            </div>

            <!-- Step Navigation -->
            <div class="step-navigation">
                <button type="button" class="step-btn secondary" onclick="prevStep()">
                    <i class="bx bx-chevron-left"></i>
                    Sebelumnya
                </button>
                <button type="submit" class="step-btn">
                    <i class="bx bx-save"></i>
                    Simpan Laporan
                </button>
            </div>
        </div>

    </form>
</div>
<?php $__env->stopSection(); ?>

<script src="<?php echo e(asset('js/mobile/laporan-akhir-tahun-create.js')); ?>"></script>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/laporan-akhir-tahun/create.blade.php ENDPATH**/ ?>