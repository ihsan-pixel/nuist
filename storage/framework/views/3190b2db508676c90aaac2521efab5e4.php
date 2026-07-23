<?php $__env->startSection('title', 'Detail Menu Talenta'); ?>

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
    <button onclick="window.location.href='<?php echo e(route('mobile.talenta.index')); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>DETAIL TALENTA</h4>
    <p>Data Peserta Talenta</p>
</div>

<!-- Success Alert -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<!-- Error Alert -->
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="form-container">
    <!-- Status Badge -->
    <div class="mb-3">
        <span class="badge <?php echo e($talenta->status === 'published' ? 'bg-success' : 'bg-warning'); ?> fs-6">
            <?php echo e($talenta->status === 'published' ? 'Dipublikasikan' : 'Draft'); ?>

        </span>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-2 mb-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->status !== 'published'): ?>
            <a href="<?php echo e(route('mobile.talenta.edit', $talenta->id)); ?>" class="btn btn-primary btn-sm">
                <i class="bx bx-edit"></i> Edit
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <!-- Data Display -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-user"></i>
            </div>
            <h6 class="section-title">DATA DIRI</h6>
        </div>

        <div class="section-content">
            <div class="row">
                <div class="col-12">
                    <div class="data-item">
                        <label>Nama Lengkap:</label>
                        <span><?php echo e($talenta->nama_lengkap_gelar); ?></span>
                    </div>
                    <div class="data-item">
                        <label>Nama Panggilan:</label>
                        <span><?php echo e($talenta->nama_panggilan); ?></span>
                    </div>
                    <div class="data-item">
                        <label>Nomor KTP:</label>
                        <span><?php echo e($talenta->nomor_ktp); ?></span>
                    </div>
                    <div class="data-item">
                        <label>NIP Ma'arif:</label>
                        <span><?php echo e($talenta->nip_maarif); ?></span>
                    </div>
                    <div class="data-item">
                        <label>Nomor Talenta:</label>
                        <span><?php echo e($talenta->nomor_talenta); ?></span>
                    </div>
                    <div class="data-item">
                        <label>Tempat Lahir:</label>
                        <span><?php echo e($talenta->tempat_lahir); ?></span>
                    </div>
                    <div class="data-item">
                        <label>Tanggal Lahir:</label>
                        <span><?php echo e($talenta->tanggal_lahir ? $talenta->tanggal_lahir->format('d/m/Y') : '-'); ?></span>
                    </div>
                    <div class="data-item">
                        <label>Email:</label>
                        <span><?php echo e($talenta->email_aktif); ?></span>
                    </div>
                    <div class="data-item">
                        <label>Nomor WA:</label>
                        <span><?php echo e($talenta->nomor_wa); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TPT Section -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->nomor_talenta_1 || $talenta->nomor_talenta_2 || $talenta->nomor_talenta_3 || $talenta->nomor_talenta_4 || $talenta->nomor_talenta_5): ?>
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-trophy"></i>
            </div>
            <h6 class="section-title">DATA TPT</h6>
        </div>

        <div class="section-content">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->nomor_talenta_1): ?>
            <div class="data-item">
                <label>TPT Level 1:</label>
                <span><?php echo e($talenta->nomor_talenta_1); ?> (Skor: <?php echo e($talenta->skor_penilaian_1 ?: '-'); ?>)</span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->nomor_talenta_2): ?>
            <div class="data-item">
                <label>TPT Level 2:</label>
                <span><?php echo e($talenta->nomor_talenta_2); ?> (Skor: <?php echo e($talenta->skor_penilaian_2 ?: '-'); ?>)</span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->nomor_talenta_3): ?>
            <div class="data-item">
                <label>TPT Level 3:</label>
                <span><?php echo e($talenta->nomor_talenta_3); ?> (Skor: <?php echo e($talenta->skor_penilaian_3 ?: '-'); ?>)</span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->nomor_talenta_4): ?>
            <div class="data-item">
                <label>TPT Level 4:</label>
                <span><?php echo e($talenta->nomor_talenta_4); ?> (Skor: <?php echo e($talenta->skor_penilaian_4 ?: '-'); ?>)</span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->nomor_talenta_5): ?>
            <div class="data-item">
                <label>TPT Level 5:</label>
                <span><?php echo e($talenta->nomor_talenta_5); ?> (Skor: <?php echo e($talenta->skor_penilaian_5 ?: '-'); ?>)</span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Pendidikan Kader -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-graduation-cap"></i>
            </div>
            <h6 class="section-title">PENDIDIKAN KADER</h6>
        </div>

        <div class="section-content">
            <div class="data-item">
                <label>PKPNU/PDPKPNU:</label>
                <span><?php echo e($talenta->pkpnu_status === 'sudah' ? 'Sudah' : 'Belum'); ?></span>
            </div>
            <div class="data-item">
                <label>MKNU:</label>
                <span><?php echo e($talenta->mknu_status === 'sudah' ? 'Sudah' : 'Belum'); ?></span>
            </div>
            <div class="data-item">
                <label>PMKNU:</label>
                <span><?php echo e($talenta->pmknu_status === 'sudah' ? 'Sudah' : 'Belum'); ?></span>
            </div>
        </div>
    </div>

    <!-- Proyeksi Diri -->
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-target"></i>
            </div>
            <h6 class="section-title">PROYEKSI DIRI</h6>
        </div>

        <div class="section-content">
            <div class="data-item">
                <label>Jabatan Saat Ini:</label>
                <span><?php echo e($talenta->jabatan_saat_ini ?: '-'); ?></span>
            </div>
            <div class="data-item">
                <label>Proyeksi Akademik:</label>
                <span><?php echo e($talenta->proyeksi_akademik ?: '-'); ?></span>
            </div>
            <div class="data-item">
                <label>Proyeksi Jabatan Level 1:</label>
                <span><?php echo e($talenta->proyeksi_jabatan_level1 ?: '-'); ?></span>
            </div>
        </div>
    </div>

    <!-- File Attachments -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->foto_resmi || $talenta->foto_bebas || $talenta->foto_keluarga || $talenta->ijazah_s1 || $talenta->ijazah_s2 || $talenta->ijazah_s3 || $talenta->sertifikat_tpt_1 || $talenta->sertifikat_tpt_2 || $talenta->sertifikat_tpt_3 || $talenta->sertifikat_tpt_4 || $talenta->sertifikat_tpt_5 || $talenta->produk_unggulan_1 || $talenta->produk_unggulan_2 || $talenta->produk_unggulan_3 || $talenta->produk_unggulan_4 || $talenta->produk_unggulan_5 || $talenta->pkpnu_sertifikat || $talenta->mknu_sertifikat || $talenta->pmknu_sertifikat || $talenta->gtt_ptt_sk || $talenta->gty_sk || $talenta->lampiran_step_1 || $talenta->lampiran_step_2 || $talenta->lampiran_step_3 || $talenta->lampiran_step_4): ?>
    <div class="section-card">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-file"></i>
            </div>
            <h6 class="section-title">FILE TERLAMPIR</h6>
        </div>

        <div class="section-content">
            <!-- TPT Sertifikat Files -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_1): ?>
            <div class="data-item">
                <label>Sertifikat TPT Level 1:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->sertifikat_tpt_1)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_2): ?>
            <div class="data-item">
                <label>Sertifikat TPT Level 2:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->sertifikat_tpt_2)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_3): ?>
            <div class="data-item">
                <label>Sertifikat TPT Level 3:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->sertifikat_tpt_3)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_4): ?>
            <div class="data-item">
                <label>Sertifikat TPT Level 4:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->sertifikat_tpt_4)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->sertifikat_tpt_5): ?>
            <div class="data-item">
                <label>Sertifikat TPT Level 5:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->sertifikat_tpt_5)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- TPT Produk Unggulan Files -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_1): ?>
            <div class="data-item">
                <label>Produk Unggulan Level 1:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->produk_unggulan_1)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_2): ?>
            <div class="data-item">
                <label>Produk Unggulan Level 2:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->produk_unggulan_2)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_3): ?>
            <div class="data-item">
                <label>Produk Unggulan Level 3:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->produk_unggulan_3)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_4): ?>
            <div class="data-item">
                <label>Produk Unggulan Level 4:</label>
                <a href="<?php echo e(asset('storage/' . $talenta->produk_unggulan_4)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->produk_unggulan_5): ?>
            <div class="data-item">
                <label>Produk Unggulan Level 5:</label>
                <a href="<?php echo e(asset('/' . $talenta->produk_unggulan_5)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Pendidikan Kader Files -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->pkpnu_sertifikat): ?>
            <div class="data-item">
                <label>Sertifikat PKPNU/PDPKPNU:</label>
                <a href="<?php echo e(asset('/' . $talenta->pkpnu_sertifikat)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->mknu_sertifikat): ?>
            <div class="data-item">
                <label>Sertifikat MKNU:</label>
                <a href="<?php echo e(asset('/' . $talenta->mknu_sertifikat)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->pmknu_sertifikat): ?>
            <div class="data-item">
                <label>Sertifikat PMKNU:</label>
                <a href="<?php echo e(asset('/' . $talenta->pmknu_sertifikat)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Foto Files -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->foto_resmi): ?>
            <div class="data-item">
                <label>Foto Resmi:</label>
                <a href="<?php echo e(asset('/' . $talenta->foto_resmi)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->foto_bebas): ?>
            <div class="data-item">
                <label>Foto Bebas:</label>
                <a href="<?php echo e(asset('/' . $talenta->foto_bebas)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->foto_keluarga): ?>
            <div class="data-item">
                <label>Foto Keluarga:</label>
                <a href="<?php echo e(asset('/' . $talenta->foto_keluarga)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Ijazah Files -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->ijazah_s1): ?>
            <div class="data-item">
                <label>Ijazah S1:</label>
                <a href="<?php echo e(asset('/' . $talenta->ijazah_s1)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->ijazah_s2): ?>
            <div class="data-item">
                <label>Ijazah S2:</label>
                <a href="<?php echo e(asset('/' . $talenta->ijazah_s2)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->ijazah_s3): ?>
            <div class="data-item">
                <label>Ijazah S3:</label>
                <a href="<?php echo e(asset('/' . $talenta->ijazah_s3)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- SK Files -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->gtt_ptt_sk): ?>
            <div class="data-item">
                <label>SK GTT-PTT:</label>
                <a href="<?php echo e(asset('/' . $talenta->gtt_ptt_sk)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->gty_sk): ?>
            <div class="data-item">
                <label>SK GTY:</label>
                <a href="<?php echo e(asset('/' . $talenta->gty_sk)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Lampiran Files -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->lampiran_step_1): ?>
            <div class="data-item">
                <label>Lampiran Step 1:</label>
                <a href="<?php echo e(asset('/' . $talenta->lampiran_step_1)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->lampiran_step_2): ?>
            <div class="data-item">
                <label>Lampiran Step 2:</label>
                <a href="<?php echo e(asset('/' . $talenta->lampiran_step_2)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->lampiran_step_3): ?>
            <div class="data-item">
                <label>Lampiran Step 3:</label>
                <a href="<?php echo e(asset('/' . $talenta->lampiran_step_3)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($talenta->lampiran_step_4): ?>
            <div class="data-item">
                <label>Lampiran Step 4:</label>
                <a href="<?php echo e(asset('/' . $talenta->lampiran_step_4)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<script>
function printData() {
    window.print();
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/talenta/show.blade.php ENDPATH**/ ?>