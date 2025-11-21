<?php $__env->startSection('title', 'Cek Status PPDB - Ma\'arif NU IST'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/ppdb-custom.css')); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .status-hero {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        color: white;
        padding: 60px 0;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .status-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('<?php echo e(asset("images/bg_ppdb2.png")); ?>') center/cover;
        opacity: 0.1;
        z-index: 1;
    }

    .status-hero-content {
        position: relative;
        z-index: 2;
    }

    .status-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-top: -50px;
        position: relative;
        z-index: 3;
    }

    .status-form-section {
        padding: 40px;
    }

    .status-result-section {
        padding: 40px;
        background: #f8f9fa;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #004b4c;
        box-shadow: 0 0 0 0.2rem rgba(0, 75, 76, 0.25);
    }

    .btn-check-status {
        background: linear-gradient(135deg, #004b4c 0%, #00695c 100%);
        border: none;
        color: white;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-check-status:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 75, 76, 0.3);
        color: white;
    }

    .status-timeline {
        position: relative;
        padding-left: 30px;
    }

    .status-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-left: 45px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -22px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6c757d;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .timeline-item.active::before {
        background: #004b4c;
    }

    .timeline-item.completed::before {
        background: #28a745;
    }

    .timeline-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .timeline-title {
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 5px;
    }

    .timeline-date {
        color: #6c757d;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .timeline-description {
        color: #666;
        margin-bottom: 0;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-verifikasi {
        background: #cce5ff;
        color: #004085;
    }

    .status-lulus {
        background: #d4edda;
        color: #155724;
    }

    .status-tidak_lulus {
        background: #f8d7da;
        color: #721c24;
    }

    .info-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-left: 4px solid #004b4c;
    }

    .info-title {
        color: #004b4c;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .info-value {
        color: #666;
        margin-bottom: 0;
    }

    .alert-custom {
        border-radius: 15px;
        border: none;
        padding: 20px;
        margin-bottom: 30px;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
    }

    .btn-back {
        background: #6c757d;
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .status-hero {
            padding: 40px 0;
        }

        .status-container {
            margin-top: -30px;
            border-radius: 15px;
        }

        .status-form-section,
        .status-result-section {
            padding: 20px;
        }

        .timeline-item {
            padding-left: 35px;
        }

        .timeline-item::before {
            left: -18px;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="status-hero">
    <div class="container status-hero-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-search me-3"></i>Cek Status Pendaftaran
                </h1>
                <p class="lead mb-0">Masukkan NISN Anda untuk melihat status pendaftaran PPDB</p>
            </div>
        </div>
    </div>
</section>

<!-- Status Container -->
<div class="container status-container">
    <!-- Alert Messages -->
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-custom animate-fade-in-up">
            <h5 class="alert-heading">
                <i class="fas fa-exclamation-triangle me-2"></i>Data Tidak Ditemukan
            </h5>
            <p class="mb-0"><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?>

    <?php if(!isset($pendaftar)): ?>
        <!-- Form Section -->
        <div class="status-form-section">
            <div class="text-center mb-4">
                <div class="section-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <h4 class="section-title">Masukkan NISN Anda</h4>
                <p class="section-description">NISN digunakan untuk mencari data pendaftaran Anda</p>
            </div>

            <form action="<?php echo e(route('ppdb.cek-status.post')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nisn" class="form-label">
                                <i class="fas fa-hashtag me-2"></i>Nomor Induk Siswa Nasional (NISN)
                            </label>
                            <input type="text" class="form-control <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="nisn" name="nisn" value="<?php echo e(old('nisn')); ?>"
                                   placeholder="Masukkan NISN (10-20 digit)" required
                                   pattern="[0-9]{10,20}" maxlength="20">
                            <?php $__errorArgs = ['nisn'];
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

                        <button type="submit" class="btn btn-check-status">
                            <i class="fas fa-search me-2"></i>Cek Status Pendaftaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    <?php else: ?>
        <!-- Result Section -->
        <div class="status-result-section">
            <div class="text-center mb-4">
                <h4 class="section-title">Status Pendaftaran Anda</h4>
                <p class="section-description">Berikut adalah detail status pendaftaran PPDB Anda</p>
            </div>

            <!-- Basic Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-user me-2"></i>Nama Lengkap
                        </h6>
                        <p class="info-value"><?php echo e($pendaftar->nama_lengkap); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-hashtag me-2"></i>NISN
                        </h6>
                        <p class="info-value"><?php echo e($pendaftar->nisn); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-school me-2"></i>Asal Sekolah
                        </h6>
                        <p class="info-value"><?php echo e($pendaftar->asal_sekolah); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-graduation-cap me-2"></i>Jurusan Pilihan
                        </h6>
                        <p class="info-value"><?php echo e($pendaftar->jurusan_pilihan); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-building me-2"></i>Sekolah Tujuan
                        </h6>
                        <p class="info-value"><?php echo e($pendaftar->ppdbSetting->sekolah->name ?? 'N/A'); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-route me-2"></i>Jalur Pendaftaran
                        </h6>
                        <p class="info-value"><?php echo e($pendaftar->jalur->nama_jalur ?? 'N/A'); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-calendar-check me-2"></i>Nomor Pendaftaran
                        </h6>
                        <p class="info-value"><?php echo e($pendaftar->nomor_pendaftaran); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="info-title">
                            <i class="fas fa-info-circle me-2"></i>Status Saat Ini
                        </h6>
                        <p class="info-value">
                            <span class="status-badge status-<?php echo e($pendaftar->status); ?>">
                                <?php echo e(ucfirst(str_replace('_', ' ', $pendaftar->status))); ?>

                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="info-card">
                <h6 class="info-title mb-4">
                    <i class="fas fa-history me-2"></i>Timeline Pendaftaran
                </h6>

                <div class="status-timeline">
                    <!-- Pendaftaran -->
                    <div class="timeline-item completed">
                        <div class="timeline-content">
                            <div class="timeline-title">Pendaftaran Diterima</div>
                            <div class="timeline-date"><?php echo e($pendaftar->created_at->format('d M Y, H:i')); ?></div>
                            <div class="timeline-description">
                                Pendaftaran Anda telah berhasil diterima dengan nomor pendaftaran <strong><?php echo e($pendaftar->nomor_pendaftaran); ?></strong>
                            </div>
                        </div>
                    </div>

                    <!-- Verifikasi -->
                    <div class="timeline-item <?php echo e(in_array($pendaftar->status, ['verifikasi', 'lulus', 'tidak_lulus']) ? 'completed' : 'active'); ?>">
                        <div class="timeline-content">
                            <div class="timeline-title">Verifikasi Berkas</div>
                            <div class="timeline-date">
                                <?php if($pendaftar->diverifikasi_tanggal): ?>
                                    <?php echo e($pendaftar->diverifikasi_tanggal->format('d M Y, H:i')); ?>

                                <?php else: ?>
                                    Dalam Proses
                                <?php endif; ?>
                            </div>
                            <div class="timeline-description">
                                <?php if($pendaftar->status === 'pending'): ?>
                                    Berkas Anda sedang dalam proses verifikasi oleh admin sekolah
                                <?php elseif(in_array($pendaftar->status, ['verifikasi', 'lulus', 'tidak_lulus'])): ?>
                                    Berkas Anda telah diverifikasi
                                    <?php if($pendaftar->catatan_verifikasi): ?>
                                        <br><small><em>Catatan: <?php echo e($pendaftar->catatan_verifikasi); ?></em></small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Seleksi -->
                    <div class="timeline-item <?php echo e(in_array($pendaftar->status, ['lulus', 'tidak_lulus']) ? 'completed' : ($pendaftar->status === 'verifikasi' ? 'active' : '')); ?>">
                        <div class="timeline-content">
                            <div class="timeline-title">Seleksi Akhir</div>
                            <div class="timeline-date">
                                <?php if($pendaftar->diseleksi_tanggal): ?>
                                    <?php echo e($pendaftar->diseleksi_tanggal->format('d M Y, H:i')); ?>

                                <?php else: ?>
                                    Dalam Proses
                                <?php endif; ?>
                            </div>
                            <div class="timeline-description">
                                <?php if(in_array($pendaftar->status, ['pending', 'verifikasi'])): ?>
                                    Proses seleksi akhir sedang berlangsung
                                <?php elseif($pendaftar->status === 'lulus'): ?>
                                    <strong class="text-success">Selamat! Anda dinyatakan LULUS seleksi PPDB</strong>
                                <?php elseif($pendaftar->status === 'tidak_lulus'): ?>
                                    <strong class="text-danger">Maaf, Anda dinyatakan TIDAK LULUS seleksi PPDB</strong>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Ulang -->
                    <?php if($pendaftar->status === 'lulus'): ?>
                        <div class="timeline-item active">
                            <div class="timeline-content">
                                <div class="timeline-title">Daftar Ulang</div>
                                <div class="timeline-date">Akan diinformasikan</div>
                                <div class="timeline-description">
                                    Informasi daftar ulang akan segera diumumkan. Pastikan untuk memantau pengumuman dari sekolah.
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Next Steps -->
            <?php if($pendaftar->status === 'lulus'): ?>
                <div class="info-card border-success">
                    <h6 class="info-title text-success mb-3">
                        <i class="fas fa-check-circle me-2"></i>Langkah Selanjutnya
                    </h6>
                    <ul class="mb-0">
                        <li>Tunggu pengumuman jadwal daftar ulang dari sekolah</li>
                        <li>Siapkan berkas asli untuk verifikasi</li>
                        <li>Ikuti semua instruksi yang diberikan sekolah</li>
                        <li>Segera lakukan daftar ulang sesuai jadwal yang ditentukan</li>
                    </ul>
                </div>
            <?php elseif($pendaftar->status === 'tidak_lulus'): ?>
                <div class="info-card border-warning">
                    <h6 class="info-title text-warning mb-3">
                        <i class="fas fa-info-circle me-2"></i>Informasi Penting
                    </h6>
                    <p class="mb-0">
                        Anda dapat mendaftar ke sekolah lain atau mencoba lagi di tahun depan.
                        Tetap semangat dan jangan menyerah dalam mengejar cita-cita!
                    </p>
                </div>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="<?php echo e(route('ppdb.cek-status')); ?>" class="btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Cek NISN Lain
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/ppdb/cek-status.blade.php ENDPATH**/ ?>