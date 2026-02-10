<?php $__env->startSection('title', 'Tugas Talenta Level I - NUIST'); ?>
<?php $__env->startSection('description', 'Platform penyelesaian tugas TPT Level I LP. Ma\'arif NU PWNU DIY'); ?>

<?php $__env->startSection('content'); ?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #f8fafc;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HERO */
    .hero {
        position: relative;
        padding: 80px 40px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white;
        text-align: center;
        max-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 25px 25px;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        position: absolute;
        left: 0;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }

    .hero-title {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        color: white;
    }

    .hero p {
        font-size: 20px;
        opacity: 0.9;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Tab Navigation */
    .tabs-container {
        position: relative;
        margin-top: -60px;
        z-index: 10;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 30px;
        padding: 0 20px;
    }

    .tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
        background: #ffffff;
        padding: 15px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .tab-btn {
        padding: 14px 28px;
        border: none;
        background: transparent;
        color: #666;
        font-size: 15px;
        font-weight: 600;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tab-btn:hover {
        background: rgba(0, 75, 76, 0.1);
        color: #004b4c;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
    }

    .tab-btn i {
        font-size: 20px;
    }

    /* CONTENT */
    .talenta-data {
        padding: 50px 0 80px;
        background: #f8fafc;
        margin-top: -30px;
    }

    .data-section {
        margin-bottom: 0;
    }

    /* Tab Content */
    .tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .tab-content.active {
        display: block;
    }

    /* Area Content */
    .area-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .area-content.active {
        display: block;
    }

    /* Sub Tabs */
    .sub-tabs {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        justify-content: center;
        background: #f8f9fa;
        padding: 10px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
    }

    .sub-tab-btn {
        padding: 10px 20px;
        border: none;
        background: transparent;
        color: #6c757d;
        font-size: 14px;
        font-weight: 500;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .sub-tab-btn:hover {
        background: rgba(0, 75, 76, 0.05);
        color: #004b4c;
    }

    .sub-tab-btn.active {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
        border-color: #004b4c;
    }

    /* Sub Tab Content */
    .sub-tab-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .sub-tab-content.active {
        display: block;
    }

    /* ANIMATION */
    .animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-up {
        transform: translateY(30px);
    }

    .fade-up.delay-1 {
        transition-delay: 0.2s;
    }

    .fade-up.delay-2 {
        transition-delay: 0.4s;
    }

    .fade-up.delay-3 {
        transition-delay: 0.6s;
    }

    .fade-up.delay-4 {
        transition-delay: 0.8s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Cards */
    .card {
        background: #ffffff;
        border-radius: 20px;
        padding: 35px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .card-title {
        font-size: 20px;
        font-weight: 700;
        color: #004b4c;
    }

    .card-subtitle {
        font-size: 14px;
        color: #888;
        margin-top: 4px;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 22px;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .form-label .required {
        color: #dc3545;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    .form-control:focus {
        outline: none;
        border-color: #004b4c;
        box-shadow: 0 0 0 4px rgba(0, 75, 76, 0.1);
    }

    textarea.form-control {
        min-height: 140px;
        resize: vertical;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23004b4c' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 15px center;
        padding-right: 45px;
    }

    /* Checkbox & Radio */
    .form-check {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: #f9f9f9;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-check:hover {
        background: #f0f0f0;
    }

    .form-check input[type="radio"],
    .form-check input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #004b4c;
    }

    .form-check-label {
        font-size: 14px;
        color: #444;
        cursor: pointer;
    }

    /* Info Box */
    .info-box {
        padding: 20px 25px;
        background: linear-gradient(135deg, rgba(0, 75, 76, 0.05), rgba(0, 102, 102, 0.05));
        border-radius: 14px;
        border-left: 4px solid #004b4c;
        margin-bottom: 25px;
    }

    .info-box p {
        font-size: 14px;
        color: #555;
        line-height: 1.7;
    }

    .info-box ul {
        margin-top: 12px;
        padding-left: 20px;
    }

    .info-box ul li {
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        line-height: 1.6;
    }

    /* Group Selection */
    .group-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .group-card {
        padding: 20px;
        background: #f9f9f9;
        border-radius: 12px;
        text-align: center;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .group-card:hover {
        background: #f0f0f0;
    }

    .group-card.selected {
        border-color: #004b4c;
        background: rgba(0, 75, 76, 0.05);
    }

    .group-card i {
        font-size: 32px;
        color: #004b4c;
        margin-bottom: 10px;
    }

    .group-card h4 {
        font-size: 15px;
        color: #333;
        margin-bottom: 5px;
    }

    .group-card p {
        font-size: 12px;
        color: #888;
    }

    /* Buttons */
    .btn {
        padding: 14px 32px;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 75, 76, 0.4);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #555;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .btn-save {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    /* Section Title */
    .section-title {
        font-size: 22px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title::before {
        content: '';
        width: 5px;
        height: 28px;
        background: linear-gradient(135deg, #004b4c, #006666);
        border-radius: 3px;
    }

    /* Deadline Badge */
    .deadline-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #fff3cd;
        color: #856404;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .deadline-badge i {
        font-size: 16px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            margin-top: 80px;
            padding: 35px 20px;
            border-radius: 20px;
        }

        .page-header h1 {
            font-size: 26px;
        }

        .tabs {
            gap: 8px;
            padding: 10px;
        }

        .tab-btn {
            padding: 12px 18px;
            font-size: 13px;
        }

        .tab-btn i {
            font-size: 18px;
        }

        .card {
            padding: 25px 20px;
        }

        .card-title {
            font-size: 18px;
        }

        .group-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .page-header h1 {
            font-size: 22px;
        }

        .group-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <a href="<?php echo e(route('talenta.dashboard')); ?>" class="back-btn">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h1 class="hero-title">Tugas Talenta Level I</h1>
        <p>Platform penyelesaian tugas TPT Level I LP. Ma'arif NU PWNU DIY</p>
    </div>
</section>

<!-- TAB NAVIGATION - OVERLAP HERO -->
<div class="tabs-container">
    <div class="tabs">
        <button class="tab-btn active" onclick="openAreaTab(event, 'ideologi-organisasi')">
            <i class='bx bx-heart'></i>
            Ideologi & Organisasi
        </button>
        <button class="tab-btn" onclick="openAreaTab(event, 'tata-kelola')">
            <i class='bx bx-cog'></i>
            Tata Kelola
        </button>
        <button class="tab-btn" onclick="openAreaTab(event, 'layanan-pendidikan')">
            <i class='bx bx-book'></i>
            Layanan Pendidikan
        </button>
        <button class="tab-btn" onclick="openAreaTab(event, 'kepemimpinan')">
            <i class='bx bx-crown'></i>
            Kepemimpinan
        </button>
    </div>
</div>

<!-- CONTENT -->
<section class="talenta-data">
    <div class="container">
    <!-- TAB 1: IDEOLOGI & ORGANISASI -->
    <div id="ideologi-organisasi" class="area-content active">
        <!-- Sub Tabs for Ideologi & Organisasi -->
        <div class="sub-tabs" style="margin-top: -30px;">
            <button class="sub-tab-btn active" onclick="openSubTab(event, 'ideologi-organisasi-on-site')">On Site</button>
            <button class="sub-tab-btn" onclick="openSubTab(event, 'ideologi-organisasi-terstruktur')">Terstruktur</button>
            <button class="sub-tab-btn" onclick="openSubTab(event, 'ideologi-organisasi-kelompok')">Kelompok</button>
        </div>

        <!-- Ideologi & Organisasi On Site -->
        <div id="ideologi-organisasi-on-site" class="sub-tab-content active">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #004b4c, #006666);">
                        <i class='bx bx-search-alt'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Identifikasi Permasalahan Ideologi & Organisasi</h3>
                        <p class="card-subtitle">Analisis permasalahan nyata di satuan pendidikan</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-time-five'></i>
                    Batas Waktu: 30 Menit
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Mengidentifikasi 1 permasalahan nyata di satuan pendidikan (satpen)</li>
                        <li>Menganalisis dengan mengaitkan nilai ideologi NU dan dinamika NU kini & mendatang</li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="ideologi_organisasi">
                    <input type="hidden" name="jenis_tugas" value="on_site">

                    <div class="form-group">
                        <label class="form-label">Nama Satuan Pendidikan <span class="required">*</span></label>
                        <input type="text" class="form-control" name="nama_satpen" placeholder="Contoh: MTsN 1 Sleman / MAN 2 Yogyakarta" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Permasalahan yang Diidentifikasi <span class="required">*</span></label>
                        <textarea class="form-control" name="permasalahan" rows="5" placeholder="Jelaskan 1 permasalahan nyata yang Anda identifikasi..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kaitan dengan Nilai Ideologi NU</label>
                        <textarea class="form-control" name="nilai_ideologi" rows="4" placeholder="Jelaskan kaitan permasalahan dengan nilai ideologi NU..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dinamika NU Kini & Mendatang</label>
                        <textarea class="form-control" name="dinamika_nu" rows="4" placeholder="Analisis kaitan dengan dinamika NU saat ini dan masa depan..."></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('ideologi-organisasi-on-site')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> Simpan Tugas On Site
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ideologi & Organisasi Terstruktur -->
        <div id="ideologi-organisasi-terstruktur" class="sub-tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #eda711, #f5c842);">
                        <i class='bx bx-file-blank'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Analisis Ideologi & Organisasi Terstruktur</h3>
                        <p class="card-subtitle">Tugas terstruktur setara 8 JPL - Dikirim Hari Ke-3</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-calendar-check'></i>
                    Batas Pengumpulan: Hari Ke-3
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Analisis permasalahan satpen berbasis ideologi & organisasi</li>
                        <li>Kaitkan dengan nilai: Tawasuth, Tawazun, Tasamuh, I'tidal</li>
                        <li>Identifikasi bagian yang tidak berjalan: Ideologi, Organisasi, Kepemimpinan, Layanan</li>
                        <li>Refleksi talenta: Solusi, Peran pribadi, Perbaikan diri</li>
                        <li><strong>Maksimal 2 halaman</strong></li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="ideologi_organisasi">
                    <input type="hidden" name="jenis_tugas" value="terstruktur">

                    <div class="form-group">
                        <label class="form-label">Judul Analisis <span class="required">*</span></label>
                        <input type="text" class="form-control" name="judul_analisis" placeholder="Contoh: Analisis Permasalahan Ideologi di MTsN..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nilai Aswaja yang Berkaitan <span class="required">*</span></label>
                        <div class="group-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                            <label class="form-check">
                                <input type="checkbox" name="nilai_aswaja[]" value="tawasuth">
                                <span class="form-check-label">Tawasuth</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="nilai_aswaja[]" value="tawazun">
                                <span class="form-check-label">Tawazun</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="nilai_aswaja[]" value="tasamuh">
                                <span class="form-check-label">Tasamuh</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="nilai_aswaja[]" value="itidal">
                                <span class="form-check-label">I'tidal</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bagian yang Tidak Berjalan <span class="required">*</span></label>
                        <div class="group-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                            <label class="form-check">
                                <input type="checkbox" name="bagian_tidak_berjalan[]" value="ideologi">
                                <span class="form-check-label">Ideologi</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="bagian_tidak_berjalan[]" value="organisasi">
                                <span class="form-check-label">Organisasi</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="bagian_tidak_berjalan[]" value="kepemimpinan">
                                <span class="form-check-label">Kepemimpinan</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="bagian_tidak_berjalan[]" value="layanan">
                                <span class="form-check-label">Layanan</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Refleksi Talenta <span class="required">*</span></label>
                        <textarea class="form-control" name="refleksi_talenta" rows="6" placeholder="Solusi yang diusulkan, peran pribadi, dan langkah perbaikan diri..." required></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('ideologi-organisasi-terstruktur')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> Simpan Tugas Terstruktur
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ideologi & Organisasi Kelompok -->
        <div id="ideologi-organisasi-kelompok" class="sub-tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #9333ea, #7c3aed);">
                        <i class='bx bx-group'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Tugas Kelompok Ideologi & Organisasi</h3>
                        <p class="card-subtitle">Tugas berbasis produk setara 8 JPL - Dikirim Hari Ke-3</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-calendar-check'></i>
                    Batas Pengumpulan: Hari Ke-3
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Mendesain produk ideologisasi & penguatan organisasi berbasis nilai Aswaja</li>
                        <li>Memuat: Analisis masalah ideologi & organisasi, Perumusan nilai relevan, Desain kebijakan/program</li>
                        <li>Rencana implementasi, Sistem kontrol</li>
                        <li><strong>Output: Dokumen operasional aplikatif</strong></li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="ideologi_organisasi">
                    <input type="hidden" name="jenis_tugas" value="kelompok">

                    <div class="form-group">
                        <label class="form-label">Nomor Kelompok <span class="required">*</span></label>
                        <select class="form-control" name="nomor_kelompok" required>
                            <option value="">Pilih Nomor Kelompok</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo e($i); ?>">Kelompok <?php echo e($i); ?></option>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Produk Ideologisasi <span class="required">*</span></label>
                        <input type="text" class="form-control" name="nama_produk" placeholder="Contoh: Program Penguatan Nilai Aswaja di Madrasah" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Analisis Masalah Ideologi & Organisasi <span class="required">*</span></label>
                        <textarea class="form-control" name="analisis_masalah" rows="5" placeholder="Analisis masalah yang ditemukan terkait ideologi dan organisasi..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Perumusan Nilai Relevan <span class="required">*</span></label>
                        <textarea class="form-control" name="perumusan_nilai" rows="4" placeholder="Rumusan nilai-nilai Aswaja yang relevan dengan masalah..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Desain Kebijakan/Program <span class="required">*</span></label>
                        <textarea class="form-control" name="desain_kebijakan" rows="6" placeholder="Desain kebijakan atau program yang akan diimplementasikan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rencana Implementasi <span class="required">*</span></label>
                        <textarea class="form-control" name="rencana_implementasi" rows="5" placeholder="Rencana langkah-langkah implementasi..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Sistem Kontrol <span class="required">*</span></label>
                        <textarea class="form-control" name="sistem_kontrol" rows="4" placeholder="Sistem monitoring dan evaluasi..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lampiran File (opsional)</label>
                        <input type="file" class="form-control" name="lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <small style="color: #888; margin-top: 5px; display: block;">Format yang diterima: PDF, DOC, DOCX, XLS, XLSX</small>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('ideologi-organisasi-kelompok')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-save">
                            <i class='bx bx-upload'></i> Kirim Tugas Kelompok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 2: TATA KELOLA -->
    <div id="tata-kelola" class="area-content animate fade-up delay-1">
        <!-- Sub Tabs for Tata Kelola -->
        <div class="sub-tabs">
            <button class="sub-tab-btn active" onclick="openSubTab(event, 'tata-kelola-on-site')">On Site</button>
            <button class="sub-tab-btn" onclick="openSubTab(event, 'tata-kelola-terstruktur')">Terstruktur</button>
            <button class="sub-tab-btn" onclick="openSubTab(event, 'tata-kelola-kelompok')">Kelompok</button>
        </div>

        <!-- Tata Kelola On Site -->
        <div id="tata-kelola-on-site" class="sub-tab-content active">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #004b4c, #006666);">
                        <i class='bx bx-search-alt'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Identifikasi Permasalahan Tata Kelola</h3>
                        <p class="card-subtitle">Analisis permasalahan nyata di satuan pendidikan</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-time-five'></i>
                    Batas Waktu: 30 Menit
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Identifikasi 1 permasalahan nyata tata kelola</li>
                        <li>Tentukan aspek yang bermasalah: Keuangan & Aset, SDM, Organisasi & Sistem, Layanan Pendidikan</li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="tata_kelola">
                    <input type="hidden" name="jenis_tugas" value="on_site">

                    <div class="form-group">
                        <label class="form-label">Nama Satuan Pendidikan <span class="required">*</span></label>
                        <input type="text" class="form-control" name="nama_satpen" placeholder="Contoh: MTsN 1 Sleman / MAN 2 Yogyakarta" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Aspek Tata Kelola yang Bermasalah <span class="required">*</span></label>
                        <div class="group-grid">
                            <label class="group-card">
                                <input type="radio" name="aspek_tata_kelola" value="keuangan_aset" style="display: none;">
                                <i class='bx bx-money'></i>
                                <h4>Keuangan & Aset</h4>
                                <p>Pengelolaan anggaran, keuangan, dan aset sekolah</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="aspek_tata_kelola" value="sdm" style="display: none;">
                                <i class='bx bx-user-voice'></i>
                                <h4>SDM</h4>
                                <p>Pengelolaan sumber daya manusia tenaga pendidik</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="aspek_tata_kelola" value="organisasi_sistem" style="display: none;">
                                <i class='bx bx-cog'></i>
                                <h4>Organisasi & Sistem</h4>
                                <p>Struktur organisasi dan sistem kerja</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="aspek_tata_kelola" value="layanan_pendidikan" style="display: none;">
                                <i class='bx bx-book'></i>
                                <h4>Layanan Pendidikan</h4>
                                <p>Kualitas dan akses layanan pendidikan</p>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Uraikan Permasalahan yang Ditemukan <span class="required">*</span></label>
                        <textarea class="form-control" name="uraian_permasalahan" rows="5" placeholder="Jelaskan permasalahan tata kelola yang Anda identifikasi..." required></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('tata-kelola-on-site')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> Simpan Tugas On Site
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tata Kelola Terstruktur -->
        <div id="tata-kelola-terstruktur" class="sub-tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #eda711, #f5c842);">
                        <i class='bx bx-file-blank'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Analisis Tata Kelola Terstruktur</h3>
                        <p class="card-subtitle">Tugas terstruktur setara 6 JPL - Dikirim Hari Ke-3</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-calendar-check'></i>
                    Batas Pengumpulan: Hari Ke-3
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Analisis bagian tata kelola yang tidak berjalan</li>
                        <li>Penyebab utama: Sistem, Aturan, Kebijakan, Kepemimpinan</li>
                        <li>Refleksi peran pribadi dan perbaikan diri</li>
                        <li><strong>Maksimal 2 halaman</strong></li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="tata_kelola">
                    <input type="hidden" name="jenis_tugas" value="terstruktur">

                    <div class="form-group">
                        <label class="form-label">Judul Analisis <span class="required">*</span></label>
                        <input type="text" class="form-control" name="judul_analisis" placeholder="Contoh: Analisis Permasalahan Pengelolaan Kepegawaian..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bagian yang Tidak Berjalan <span class="required">*</span></label>
                        <textarea class="form-control" name="bagian_tidak_berjalan" rows="4" placeholder="Deskripsikan bagian tata kelola yang tidak berjalan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Analisis Penyebab Utama <span class="required">*</span></label>
                        <div class="group-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));">
                            <label class="form-check">
                                <input type="checkbox" name="penyebab[]" value="sistem">
                                <span class="form-check-label">Sistem</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="penyebab[]" value="aturan">
                                <span class="form-check-label">Aturan</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="penyebab[]" value="kebijakan">
                                <span class="form-check-label">Kebijakan</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="penyebab[]" value="kepemimpinan">
                                <span class="form-check-label">Kepemimpinan</span>
                            </label>
                        </div>
                        <textarea class="form-control" name="keterangan_penyebab" rows="3" placeholder="Jelaskan analisis penyebab utama..." style="margin-top: 15px;"></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Refleksi Peran dan Perbaikan Diri <span class="required">*</span></label>
                        <textarea class="form-control" name="refleksi_peran" rows="5" placeholder="Refleksi peran pribadi dan perbaikan diri..." required></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('tata-kelola-terstruktur')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> Simpan Tugas Terstruktur
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tata Kelola Kelompok -->
        <div id="tata-kelola-kelompok" class="sub-tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #9333ea, #7c3aed);">
                        <i class='bx bx-group'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Tugas Kelompok Tata Kelola</h3>
                        <p class="card-subtitle">Tugas berbasis produk setara 10 JPL - Dikirim Hari Ke-3</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-calendar-check'></i>
                    Batas Pengumpulan: Hari Ke-3
                </div>

                <div class="info-box">
                    <p><strong>Informasi Kelompok:</strong></p>
                    <ul>
                        <li><strong>Kelompok 1-5:</strong> Menyusun draft aturan/kebijakan internal</li>
                        <li><strong>Kelompok 6-10:</strong> Menyusun mekanisme operasional & implementasi</li>
                        <li>Fokus per kelompok: SDM, Organisasi & Sistem, Kepemimpinan, Keuangan & Aset, Layanan Pendidikan</li>
                        <li><strong>Output: Draft tata kelola terbatas & realistis</strong></li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="tata_kelola">
                    <input type="hidden" name="jenis_tugas" value="kelompok">

                    <div class="form-group">
                        <label class="form-label">Nomor Kelompok <span class="required">*</span></label>
                        <select class="form-control" name="nomor_kelompok" required>
                            <option value="">Pilih Nomor Kelompok</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo e($i); ?>">Kelompok <?php echo e($i); ?></option>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Tugas <span class="required">*</span></label>
                        <div class="group-grid">
                            <label class="form-check">
                                <input type="radio" name="jenis_tugas_kelompok" value="draft_aturan">
                                <span class="form-check-label">Draft Aturan/Kebijakan Internal (Kel. 1-5)</span>
                            </label>
                            <label class="form-check">
                                <input type="radio" name="jenis_tugas_kelompok" value="mekanisme_operasional">
                                <span class="form-check-label">Mekanisme Operasional & Implementasi (Kel. 6-10)</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Aspek Tata Kelola yang Dikerjakan <span class="required">*</span></label>
                        <div class="group-grid">
                            <label class="group-card">
                                <input type="radio" name="aspek_kelompok" value="sdm" style="display: none;">
                                <i class='bx bx-user-voice'></i>
                                <h4>SDM</h4>
                                <p>Sumber Daya Manusia</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="aspek_kelompok" value="organisasi_sistem" style="display: none;">
                                <i class='bx bx-cog'></i>
                                <h4>Organisasi & Sistem</h4>
                                <p>Struktur & Prosedur</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="aspek_kelompok" value="kepemimpinan" style="display: none;">
                                <i class='bx bx-crown'></i>
                                <h4>Kepemimpinan</h4>
                                <p>Kepemimpinan Sekolah</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="aspek_kelompok" value="keuangan_aset" style="display: none;">
                                <i class='bx bx-money'></i>
                                <h4>Keuangan & Aset</h4>
                                <p>Pengelolaan Keuangan</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="aspek_kelompok" value="layanan_pendidikan" style="display: none;">
                                <i class='bx bx-book'></i>
                                <h4>Layanan Pendidikan</h4>
                                <p>Kualitas Layanan</p>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama/Judul Draft Tata Kelola <span class="required">*</span></label>
                        <input type="text" class="form-control" name="nama_draft" placeholder="Contoh: Draft SOP Pengelolaan Kepegawaian Madrasah" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dasar Hukum/Kebijakan Acuan</label>
                        <textarea class="form-control" name="dasar_hukum" rows="3" placeholder="Sebutkan dasar hukum atau kebijakan yang menjadi acuan..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Draft Konten Utama <span class="required">*</span></label>
                        <textarea class="form-control" name="draft_konten" rows="8" placeholder="Masukkan draft aturan/kebijakan atau mekanisme operasional..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lampiran File (opsional)</label>
                        <input type="file" class="form-control" name="lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <small style="color: #888; margin-top: 5px; display: block;">Format yang diterima: PDF, DOC, DOCX, XLS, XLSX</small>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('tata-kelola-kelompok')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-save">
                            <i class='bx bx-upload'></i> Kirim Tugas Kelompok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 3: LAYANAN PENDIDIKAN -->
    <div id="layanan-pendidikan" class="area-content animate fade-up delay-2">
        <!-- Sub Tabs for Layanan Pendidikan -->
        <div class="sub-tabs">
            <button class="sub-tab-btn active" onclick="openSubTab(event, 'layanan-pendidikan-on-site')">On Site</button>
            <button class="sub-tab-btn" onclick="openSubTab(event, 'layanan-pendidikan-terstruktur')">Terstruktur</button>
            <button class="sub-tab-btn" onclick="openSubTab(event, 'layanan-pendidikan-kelompok')">Kelompok</button>
        </div>

        <!-- Layanan Pendidikan On Site -->
        <div id="layanan-pendidikan-on-site" class="sub-tab-content active">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #004b4c, #006666);">
                        <i class='bx bx-search-alt'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Refleksi Layanan Pendidikan</h3>
                        <p class="card-subtitle">Evaluasi layanan nyata di satuan pendidikan</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-time-five'></i>
                    Batas Waktu: 30 Menit
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Merefleksi 1 layanan nyata: Akademik, Administrasi, Kesiswaan, Layanan Orang Tua</li>
                        <li>Diserahkan ke fasilitator</li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="layanan_pendidikan">
                    <input type="hidden" name="jenis_tugas" value="on_site">

                    <div class="form-group">
                        <label class="form-label">Nama Satuan Pendidikan <span class="required">*</span></label>
                        <input type="text" class="form-control" name="nama_satpen" placeholder="Contoh: MTsN 1 Sleman / MAN 2 Yogyakarta" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Layanan yang Direfleksikan <span class="required">*</span></label>
                        <div class="group-grid">
                            <label class="group-card">
                                <input type="radio" name="jenis_layanan" value="akademik" style="display: none;">
                                <i class='bx bx-book-open'></i>
                                <h4>Akademik</h4>
                                <p>Proses pembelajaran dan kurikulum</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="jenis_layanan" value="administrasi" style="display: none;">
                                <i class='bx bx-file'></i>
                                <h4>Administrasi</h4>
                                <p>Administrasi sekolah dan akademik</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="jenis_layanan" value="kesiswaan" style="display: none;">
                                <i class='bx bx-group'></i>
                                <h4>Kesiswaan</h4>
                                <p>Layanan bimbingan siswa</p>
                            </label>
                            <label class="group-card">
                                <input type="radio" name="jenis_layanan" value="layanan_orang_tua" style="display: none;">
                                <i class='bx bx-heart'></i>
                                <h4>Layanan Orang Tua</h4>
                                <p>Komunikasi dengan orang tua/wali</p>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Refleksi Layanan <span class="required">*</span></label>
                        <textarea class="form-control" name="refleksi_layanan" rows="6" placeholder="Refleksikan layanan yang Anda amati, kekuatan, kelemahan, dan saran perbaikan..." required></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('layanan-pendidikan-on-site')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> Simpan Refleksi On Site
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Layanan Pendidikan Terstruktur -->
        <div id="layanan-pendidikan-terstruktur" class="sub-tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #eda711, #f5c842);">
                        <i class='bx bx-file-blank'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Analisis Layanan Pendidikan Terstruktur</h3>
                        <p class="card-subtitle">Tugas terstruktur setara 6 JPL - Dikirim Hari Ke-3</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-calendar-check'></i>
                    Batas Pengumpulan: Hari Ke-3
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Memilih 1 layanan di lembaga</li>
                        <li>Analisis sebagai studi kasus: Masalah utama, Penyebab mutu rendah, Dampak bagi siswa/orang tua</li>
                        <li>Menggunakan kerangka PRIMA TERRA</li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="layanan_pendidikan">
                    <input type="hidden" name="jenis_tugas" value="terstruktur">

                    <div class="form-group">
                        <label class="form-label">Judul Analisis <span class="required">*</span></label>
                        <input type="text" class="form-control" name="judul_analisis" placeholder="Contoh: Analisis Layanan Akademik di MTsN..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Layanan yang Dianalisis <span class="required">*</span></label>
                        <select class="form-control" name="layanan_dipilih" required>
                            <option value="">Pilih Layanan</option>
                            <option value="akademik">Akademik</option>
                            <option value="administrasi">Administrasi</option>
                            <option value="kesiswaan">Kesiswaan</option>
                            <option value="layanan_orang_tua">Layanan Orang Tua</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Masalah Utama <span class="required">*</span></label>
                        <textarea class="form-control" name="masalah_utama" rows="4" placeholder="Jelaskan masalah utama yang ditemukan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Penyebab Mutu Rendah <span class="required">*</span></label>
                        <textarea class="form-control" name="penyebab_mutu" rows="4" placeholder="Analisis penyebab-penyebab mutu layanan rendah..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Dampak bagi Siswa/Orang Tua <span class="required">*</span></label>
                        <textarea class="form-control" name="dampak" rows="4" placeholder="Jelaskan dampak negatif terhadap siswa dan orang tua..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Analisis dengan Kerangka PRIMA TERRA <span class="required">*</span></label>
                        <textarea class="form-control" name="analisis_prima_terra" rows="6" placeholder="Analisis menggunakan kerangka PRIMA TERRA..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rekomendasi Perbaikan</label>
                        <textarea class="form-control" name="rekomendasi" rows="4" placeholder="Berikan rekomendasi perbaikan layanan..."></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('layanan-pendidikan-terstruktur')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> Simpan Tugas Terstruktur
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Layanan Pendidikan Kelompok -->
        <div id="layanan-pendidikan-kelompok" class="sub-tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #9333ea, #7c3aed);">
                        <i class='bx bx-group'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Tugas Kelompok Layanan Pendidikan</h3>
                        <p class="card-subtitle">Tugas berbasis produk setara 10 JPL - Dikirim Hari Ke-3</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-calendar-check'></i>
                    Batas Pengumpulan: Hari Ke-3
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Kelompok memilih 1 layanan prioritas</li>
                        <li>Analisis: Masalah, Penyebab, Risiko kegagalan</li>
                        <li>Menyusun: Rancangan perbaikan, Pemulihan layanan berbasis PRIMA TERRA</li>
                        <li>Indikator keberhasilan, Kelayakan implementasi</li>
                        <li><strong>5-7 halaman</strong></li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="layanan_pendidikan">
                    <input type="hidden" name="jenis_tugas" value="kelompok">

                    <div class="form-group">
                        <label class="form-label">Nomor Kelompok <span class="required">*</span></label>
                        <select class="form-control" name="nomor_kelompok" required>
                            <option value="">Pilih Nomor Kelompok</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo e($i); ?>">Kelompok <?php echo e($i); ?></option>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Layanan Prioritas yang Dipilih <span class="required">*</span></label>
                        <select class="form-control" name="layanan_prioritas" required>
                            <option value="">Pilih Layanan Prioritas</option>
                            <option value="akademik">Akademik</option>
                            <option value="administrasi">Administrasi</option>
                            <option value="kesiswaan">Kesiswaan</option>
                            <option value="layanan_orang_tua">Layanan Orang Tua</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Analisis Masalah <span class="required">*</span></label>
                        <textarea class="form-control" name="analisis_masalah" rows="5" placeholder="Analisis mendalam masalah pada layanan yang dipilih..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Penyebab Masalah <span class="required">*</span></label>
                        <textarea class="form-control" name="penyebab_masalah" rows="4" placeholder="Identifikasi penyebab-penyebab masalah..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Risiko Kegagalan <span class="required">*</span></label>
                        <textarea class="form-control" name="risiko_kegagalan" rows="4" placeholder="Analisis risiko-risiko yang mungkin terjadi jika tidak diperbaiki..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rancangan Perbaikan <span class="required">*</span></label>
                        <textarea class="form-control" name="rancangan_perbaikan" rows="6" placeholder="Rancangan solusi perbaikan layanan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pemulihan Layanan (PRIMA TERRA) <span class="required">*</span></label>
                        <textarea class="form-control" name="pemulihan_prima_terra" rows="6" placeholder="Strategi pemulihan berbasis kerangka PRIMA TERRA..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Indikator Keberhasilan <span class="required">*</span></label>
                        <textarea class="form-control" name="indikator_keberhasilan" rows="4" placeholder="Indikator-indikator yang menunjukkan keberhasilan perbaikan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kelayakan Implementasi <span class="required">*</span></label>
                        <textarea class="form-control" name="kelayakan_implementasi" rows="4" placeholder="Analisis kelayakan implementasi rancangan perbaikan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lampiran File (opsional)</label>
                        <input type="file" class="form-control" name="lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <small style="color: #888; margin-top: 5px; display: block;">Format yang diterima: PDF, DOC, DOCX, XLS, XLSX</small>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('layanan-pendidikan-kelompok')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-save">
                            <i class='bx bx-upload'></i> Kirim Tugas Kelompok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 4: KEPEMIMPINAN -->
    <div id="kepemimpinan" class="area-content animate fade-up delay-3">
        <!-- Sub Tabs for Kepemimpinan -->
        <div class="sub-tabs">
            <button class="sub-tab-btn active" onclick="openSubTab(event, 'kepemimpinan-on-site')">On Site</button>
            <button class="sub-tab-btn" onclick="openSubTab(event, 'kepemimpinan-terstruktur')">Terstruktur</button>
            <button class="sub-tab-btn" onclick="openSubTab(event, 'kepemimpinan-kelompok')">Kelompok</button>
        </div>

        <!-- Kepemimpinan On Site -->
        <div id="kepemimpinan-on-site" class="sub-tab-content active">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #004b4c, #006666);">
                        <i class='bx bx-search-alt'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Refleksi Pengalaman Kepemimpinan</h3>
                        <p class="card-subtitle">Evaluasi praktik kepemimpinan pendidikan</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-time-five'></i>
                    Batas Waktu: 30 Menit
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Merefleksi pengalaman kepemimpinan pendidikan</li>
                        <li>Menuliskan: Konteks, Peran, Nilai kepemimpinan yang muncul, 1 masalah kepemimpinan, 1 pelajaran penting</li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="kepemimpinan">
                    <input type="hidden" name="jenis_tugas" value="on_site">

                    <div class="form-group">
                        <label class="form-label">Konteks Kepemimpinan <span class="required">*</span></label>
                        <textarea class="form-control" name="konteks" rows="4" placeholder="Jelaskan konteks situasi kepemimpinan yang Anda alami..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Peran Kepemimpinan <span class="required">*</span></label>
                        <textarea class="form-control" name="peran" rows="4" placeholder="Jelaskan peran kepemimpinan yang Anda jalankan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nilai Kepemimpinan yang Muncul <span class="required">*</span></label>
                        <textarea class="form-control" name="nilai_kepemimpinan" rows="4" placeholder="Nilai-nilai kepemimpinan apa yang muncul dalam pengalaman tersebut..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">1 Masalah Kepemimpinan <span class="required">*</span></label>
                        <textarea class="form-control" name="masalah_kepemimpinan" rows="4" placeholder="Sebutkan satu masalah kepemimpinan yang Anda hadapi..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">1 Pelajaran Penting <span class="required">*</span></label>
                        <textarea class="form-control" name="pelajaran_penting" rows="4" placeholder="Pelajaran penting apa yang Anda dapatkan dari pengalaman tersebut..." required></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('kepemimpinan-on-site')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> Simpan Refleksi On Site
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kepemimpinan Terstruktur -->
        <div id="kepemimpinan-terstruktur" class="sub-tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #eda711, #f5c842);">
                        <i class='bx bx-file-blank'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Analisis Praktik Kepemimpinan Terstruktur</h3>
                        <p class="card-subtitle">Tugas terstruktur setara 6 JPL - Dikirim Hari Ke-3</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-calendar-check'></i>
                    Batas Pengumpulan: Hari Ke-3
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Menganalisis praktik kepemimpinan di lembaga</li>
                        <li>Menjelaskan: Pola kepemimpinan dominan, Kesesuaian dengan nilai Islam & NU</li>
                        <li>Masalah utama, Refleksi kritis pribadi</li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="kepemimpinan">
                    <input type="hidden" name="jenis_tugas" value="terstruktur">

                    <div class="form-group">
                        <label class="form-label">Judul Analisis <span class="required">*</span></label>
                        <input type="text" class="form-control" name="judul_analisis" placeholder="Contoh: Analisis Kepemimpinan di MTsN..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Pola Kepemimpinan Dominan <span class="required">*</span></label>
                        <textarea class="form-control" name="pola_kepemimpinan" rows="4" placeholder="Jelaskan pola kepemimpinan yang dominan di lembaga..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kesesuaian dengan Nilai Islam & NU <span class="required">*</span></label>
                        <textarea class="form-control" name="kesesuaian_nilai" rows="5" placeholder="Analisis kesesuaian pola kepemimpinan dengan nilai Islam dan NU..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Masalah Utama Kepemimpinan <span class="required">*</span></label>
                        <textarea class="form-control" name="masalah_utama" rows="4" placeholder="Identifikasi masalah utama dalam praktik kepemimpinan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Refleksi Kritis Pribadi <span class="required">*</span></label>
                        <textarea class="form-control" name="refleksi_kritis" rows="5" placeholder="Refleksi kritis Anda terhadap praktik kepemimpinan di lembaga..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rekomendasi Perbaikan</label>
                        <textarea class="form-control" name="rekomendasi" rows="4" placeholder="Berikan rekomendasi untuk perbaikan kepemimpinan..."></textarea>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('kepemimpinan-terstruktur')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-save'></i> Simpan Tugas Terstruktur
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Kepemimpinan Kelompok -->
        <div id="kepemimpinan-kelompok" class="sub-tab-content">
            <div class="card">
                <div class="card-header">
                    <div class="card-icon" style="background: linear-gradient(135deg, #9333ea, #7c3aed);">
                        <i class='bx bx-group'></i>
                    </div>
                    <div>
                        <h3 class="card-title">Tugas Kelompok Penguatan Kepemimpinan</h3>
                        <p class="card-subtitle">Tugas berbasis produk setara 12 JPL - Dikirim Hari Ke-3</p>
                    </div>
                </div>

                <div class="deadline-badge">
                    <i class='bx bx-calendar-check'></i>
                    Batas Pengumpulan: Hari Ke-3
                </div>

                <div class="info-box">
                    <p><strong>Petunjuk Pengisian:</strong></p>
                    <ul>
                        <li>Analisis masalah kepemimpinan di LP Ma'arif NU</li>
                        <li>Merumuskan strategi penguatan berbasis nilai Islam & NU</li>
                        <li>Menyusun: Indikator keberhasilan, Refleksi kelayakan implementasi</li>
                    </ul>
                </div>

                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="area" value="kepemimpinan">
                    <input type="hidden" name="jenis_tugas" value="kelompok">

                    <div class="form-group">
                        <label class="form-label">Nomor Kelompok <span class="required">*</span></label>
                        <select class="form-control" name="nomor_kelompok" required>
                            <option value="">Pilih Nomor Kelompok</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo e($i); ?>">Kelompok <?php echo e($i); ?></option>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Analisis Masalah Kepemimpinan <span class="required">*</span></label>
                        <textarea class="form-control" name="analisis_masalah" rows="6" placeholder="Analisis mendalam masalah kepemimpinan di LP Ma'arif NU..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Strategi Penguatan (Nilai Islam) <span class="required">*</span></label>
                        <textarea class="form-control" name="strategi_islam" rows="5" placeholder="Strategi penguatan kepemimpinan berbasis nilai-nilai Islam..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Strategi Penguatan (Nilai NU) <span class="required">*</span></label>
                        <textarea class="form-control" name="strategi_nu" rows="5" placeholder="Strategi penguatan kepemimpinan berbasis nilai-nilai NU..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Indikator Keberhasilan <span class="required">*</span></label>
                        <textarea class="form-control" name="indikator_keberhasilan" rows="5" placeholder="Indikator-indikator yang menunjukkan keberhasilan strategi penguatan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Refleksi Kelayakan Implementasi <span class="required">*</span></label>
                        <textarea class="form-control" name="refleksi_kelayakan" rows="5" placeholder="Refleksi kritis terhadap kelayakan implementasi strategi yang dirumuskan..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Rencana Aksi Jangka Pendek</label>
                        <textarea class="form-control" name="rencana_aksi" rows="4" placeholder="Rencana aksi yang dapat diimplementasikan dalam jangka pendek..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lampiran File (opsional)</label>
                        <input type="file" class="form-control" name="lampiran" accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <small style="color: #888; margin-top: 5px; display: block;">Format yang diterima: PDF, DOC, DOCX, XLS, XLSX</small>
                    </div>

                    <div style="text-align: right; margin-top: 30px;">
                        <button type="button" class="btn btn-secondary" onclick="resetForm('kepemimpinan-kelompok')">
                            <i class='bx bx-reset'></i> Reset
                        </button>
                        <button type="submit" class="btn btn-save">
                            <i class='bx bx-upload'></i> Kirim Tugas Kelompok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TAB 3: Tugas Kelompok -->
    <div id="tugas-kelompok" class="tab-content">
        <div class="card">
            <div class="card-header">
                <div class="card-icon" style="background: linear-gradient(135deg, #9333ea, #7c3aed);">
                    <i class='bx bx-group'></i>
                </div>
                <div>
                    <h3 class="card-title">Tugas Kelompok - Produk Tata Kelola</h3>
                    <p class="card-subtitle">Tugas berbasis produk setara 10 JPL - Dikirim Hari Ke-3</p>
                </div>
            </div>

            <div class="deadline-badge">
                <i class='bx bx-calendar-check'></i>
                Batas Pengumpulan: Hari Ke-3
            </div>

            <div class="info-box">
                <p><strong>Informasi Kelompok:</strong></p>
                <ul>
                    <li><strong>Kelompok 1 - 5:</strong> Menyusun draft aturan/kebijakan internal</li>
                    <li><strong>Kelompok 6 - 10:</strong> Menyusun mekanisme operasional dan implementasi</li>
                    <li>Hasil yang diharapkan adalah produk tata kelola yang utuh dan aplikatif</li>
                    <li>Fokus pada satu aspek tata kelola dengan hasil draft terbatas yang realistis</li>
                </ul>
            </div>

            <form action="#" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label">Nomor Kelompok <span class="required">*</span></label>
                    <select class="form-control" required>
                        <option value="">Pilih Nomor Kelompok</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo e($i); ?>">Kelompok <?php echo e($i); ?></option>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Tugas <span class="required">*</span></label>
                    <div class="group-grid">
                        <label class="form-check">
                            <input type="radio" name="jenis_tugas" value="draft_aturan">
                            <span class="form-check-label">Draft Aturan/Kebijakan Internal (Kel. 1-5)</span>
                        </label>
                        <label class="form-check">
                            <input type="radio" name="jenis_tugas" value="mekanisme_operasional">
                            <span class="form-check-label">Mekanisme Operasional & Implementasi (Kel. 6-10)</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Aspek Tata Kelola yang Dikerjakan <span class="required">*</span></label>
                    <div class="group-grid">
                        <label class="group-card">
                            <input type="radio" name="aspek_kelompok" value="sdm" style="display: none;">
                            <i class='bx bx-user-voice'></i>
                            <h4>SDM</h4>
                            <p>Sumber Daya Manusia</p>
                        </label>
                        <label class="group-card">
                            <input type="radio" name="aspek_kelompok" value="organisasi_sistem" style="display: none;">
                            <i class='bx bx-cog'></i>
                            <h4>Organisasi & Sistem</h4>
                            <p>Struktur & Prosedur</p>
                        </label>
                        <label class="group-card">
                            <input type="radio" name="aspek_kelompok" value="kepemimpinan" style="display: none;">
                            <i class='bx bx-crown'></i>
                            <h4>Kepemimpinan</h4>
                            <p>Kepemimpinan Sekolah</p>
                        </label>
                        <label class="group-card">
                            <input type="radio" name="aspek_kelompok" value="keuangan_aset" style="display: none;">
                            <i class='bx bx-money'></i>
                            <h4>Keuangan & Aset</h4>
                            <p>Pengelolaan Keuangan</p>
                        </label>
                        <label class="group-card">
                            <input type="radio" name="aspek_kelompok" value="layanan_pendidikan" style="display: none;">
                            <i class='bx bx-book'></i>
                            <h4>Layanan Pendidikan</h4>
                            <p>Kualitas Layanan</p>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama/Judul Draft Produk Tata Kelola <span class="required">*</span></label>
                    <input type="text" class="form-control" placeholder="Contoh: Draft SOP Pengelolaan Kepegawaian Madrasah" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Dasar Hukum/Kebijakan Acuan</label>
                    <textarea class="form-control" rows="3" placeholder="Sebutkan dasar hukum atau kebijakan yang menjadi acuan..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Tujuan Draft Tata Kelola <span class="required">*</span></label>
                    <textarea class="form-control" rows="3" placeholder="Jelaskan tujuan dari draft tata kelola yang disusun..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Ruang Lingkup <span class="required">*</span></label>
                    <textarea class="form-control" rows="3" placeholder="Jelaskan ruang lingkup dari draft ini..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Draft Konten Utama <span class="required">*</span></label>
                    <textarea class="form-control" rows="8" placeholder="Masukkan draft aturan/kebijakan atau mekanisme operasional yang diusulkan..." required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan/Keterangan Tambahan</label>
                    <textarea class="form-control" rows="3" placeholder="Tambahkan catatan atau keterangan lain jika diperlukan..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Lampiran File (opsional)</label>
                    <input type="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    <small style="color: #888; margin-top: 5px; display: block;">Format yang diterima: PDF, DOC, DOCX, XLS, XLSX</small>
                </div>

                <div style="text-align: right; margin-top: 30px;">
                    <button type="button" class="btn btn-secondary" onclick="resetForm('tugas-kelompok')">
                        <i class='bx bx-reset'></i> Reset
                    </button>
                    <button type="submit" class="btn btn-save">
                        <i class='bx bx-upload'></i> Kirim Tugas Kelompok
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    function openAreaTab(evt, areaName) {
        // Hide all area content
        document.querySelectorAll('.area-content').forEach(content => {
            content.classList.remove('active');
        });

        // Remove active class from all area tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show the current area and add active class to the clicked button
        document.getElementById(areaName).classList.add('active');
        evt.currentTarget.classList.add('active');
    }

    function openSubTab(evt, subTabName) {
        // Get the parent area
        const area = evt.target.closest('.area-content');

        // Hide all sub tab content in this area
        area.querySelectorAll('.sub-tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Remove active class from all sub tab buttons in this area
        area.querySelectorAll('.sub-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show the current sub tab and add active class to the clicked button
        document.getElementById(subTabName).classList.add('active');
        evt.currentTarget.classList.add('active');
    }

    function resetForm(tabId) {
        if(confirm('Apakah Anda yakin ingin mereset form ini?')) {
            const tab = document.getElementById(tabId);
            if (tab) {
                tab.querySelectorAll('input[type="text"], textarea, select').forEach(input => {
                    input.value = '';
                });
                tab.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
                    input.checked = false;
                });
                tab.querySelectorAll('.group-card').forEach(card => {
                    card.classList.remove('selected');
                });
            }
        }
    }

    // Handle group card selection
    document.querySelectorAll('.group-card').forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                const parent = this.closest('.group-grid');
                parent.querySelectorAll('.group-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                radio.checked = true;
            }
        });
    });

    // Handle form-check label click
    document.querySelectorAll('.form-check').forEach(check => {
        check.addEventListener('click', function() {
            const input = this.querySelector('input[type="checkbox"], input[type="radio"]');
            if (input) {
                input.checked = !input.checked;
            }
        });
    });

    // Initialize first area and sub-tab as active
    document.addEventListener('DOMContentLoaded', function() {
        // Show first area
        const firstArea = document.querySelector('.area-content');
        if (firstArea) {
            firstArea.classList.add('active');
        }

        // Show first sub-tab in each area
        document.querySelectorAll('.area-content').forEach(area => {
            const firstSubTab = area.querySelector('.sub-tab-content');
            if (firstSubTab) {
                firstSubTab.classList.add('active');
            }
        });
    });
</script>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/tugas-tata-kelola.blade.php ENDPATH**/ ?>