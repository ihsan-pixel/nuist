<?php $__env->startSection('title', 'Dashboard Talenta'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('talenta.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #ffffff;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HERO */
    .hero {
        position: relative;
        margin-top: 65px;
        margin-bottom: 30px;
        padding: 50px 20px 120px;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin-left: auto;
        margin-right: auto;
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .hero h1 {
        font-size: 56px;
        font-weight: 800;
        line-height: 1.15;
        color: white;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #eda711;
    }

    .hero p {
        font-size: 18px;
        max-width: 720px;
        margin: 0 auto 40px;
        opacity: 0.9;
        color: white;
    }

    .hero-image {
        transition: transform 0.3s ease, filter 0.3s ease;
        cursor: pointer;
    }

    .hero-image:hover {
        transform: scale(1.05);
        filter: brightness(1.1);
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px 30px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        text-decoration: none;
        color: #004b4c;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        min-width: 120px;
    }

    .action-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        color: #006666;
    }

    .action-btn i {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .action-btn span {
        font-size: 14px;
        font-weight: 600;
        text-align: center;
    }

    /* SECTION BACKGROUNDS */
    .section-clean {
        padding: 100px 0;
        background: #ffffff;
    }

    .section-soft {
        padding: 100px 0;
        background: #f8fafc;
    }

    .section-description {
        text-align: center;
        font-size: 18px;
        color: #6b7280;
        max-width: 800px;
        margin: 0 auto 80px;
        line-height: 1.6;
        opacity: 0.9;
    }

    .section-title {
        text-align: center;
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 20px;
        color: #0f172a;
    }

    .section-subtitle {
        text-align: center;
        font-size: 16px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto 60px;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: auto;
    }

    .card {
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        transition: all 0.3s ease;
        text-align: left;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }

    .card h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #0f172a;
    }

    .card p {
        font-size: 15px;
        color: #64748b;
        line-height: 1.7;
    }

    /* TARGET LIST */
    .target-list {
        display: flex;
        flex-direction: row;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 0 auto;
    }

    .target-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 15px;
        padding: 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
        flex: 1;
        min-width: 300px;
        max-width: 500px;
    }

    .target-item:hover {
        transform: translateY(-3px);
    }

    .target-item h3 {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 8px;
        color: #004b4c;
    }

    .target-item p {
        color: #6b7280;
        line-height: 1.6;
    }

    /* TIMELINE */
    .karakteristik-timeline {
        background: #ffffff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 40px;
        padding-left: 40px;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -25px;
        top: 20px;
        width: 2px;
        height: calc(100% + 20px);
        background: linear-gradient(to bottom, #eda711, #004b4c);
    }

    .timeline-dot {
        position: absolute;
        left: -35px;
        top: 10px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #eda711;
        border: 3px solid #fff;
        box-shadow: 0 0 0 3px #004b4c;
    }

    .timeline-content h4 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #004b4c;
    }

    .timeline-content p {
        color: #6b7280;
        line-height: 1.5;
    }

    /* STEPS */
    .kelulusan-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .step:hover {
        transform: translateY(-3px);
    }

    .step-number {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eda711, #004b4c);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }

    .step-content h4 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #000000;
    }

    .step-content p {
        color: #1f2937;
        line-height: 1.4;
        font-size: 14px;
    }

    .kelulusan-note {
        text-align: center;
        font-style: italic;
        color: #6b7280;
        margin-top: 20px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    /* PENILAIAN CONTAINER */
    .penilaian-container {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 0 auto;
    }

    .penilaian-subjek, .penilaian-skala {
        flex: 1;
        min-width: 250px;
        padding: 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .penilaian-subjek h3, .penilaian-skala h3 {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #004b4c;
    }

    .subjek-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
    }

    .subjek-item {
        padding: 6px 12px;
        background: linear-gradient(135deg, #eda711, #004b4c);
        color: white;
        border-radius: 20px;
        font-weight: 600;
        font-size: 12px;
    }

    .skala-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 10px;
    }

    .skala-item {
        padding: 8px;
        background: #f8f9fa;
        border-radius: 8px;
        font-weight: 600;
        color: #004b4c;
        text-align: center;
        border: 1px solid #e9ecef;
        font-size: 14px;
    }

    .card.active-feature {
        border-color: #10b981;
        background: linear-gradient(135deg, #ffffff, #f0fdf4);
    }

    .card-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #9333ea, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 28px;
        color: white;
    }

    .card h3 {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #111827;
    }

    .card p {
        font-size: 16px;
        line-height: 1.6;
        color: #6b7280;
        max-width: 320px;
        margin: 0 auto;
    }

    /* TABLE MODERN */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
        border-radius: 16px;
        overflow: hidden;
    }

    .table-modern th {
        background: #0f172a;
        color: white;
        padding: 14px;
        font-weight: 600;
    }

    .table-modern td {
        padding: 14px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-modern tr:hover {
        background: #f1f5f9;
    }

    /* SECTION DIVIDER */
    .section-divider {
        width: 80px;
        height: 4px;
        background: #eda711;
        margin: 0 auto 40px;
        border-radius: 4px;
    }

    .profile-flex {
        display: flex;
        align-items: center;
        gap: 60px;
    }

    .profile-content h2 {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #004b4c;
    }

    .profile-content p {
        font-size: 18px;
        color: #6d6b7b;
        margin-bottom: 30px;
        margin-top: 60px;
    }

    .title-with-dot {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 36px;
        font-weight: 800;
        color: #004b4c;
        letter-spacing: 1px;
    }

    .title-with-dot .dot {
        width: 18px;
        height: 18px;
        background: linear-gradient(135deg, #004b4c, #006666);
        border-radius: 50%;
        flex-shrink: 0;
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

    .fade-left {
        transform: translateX(-40px);
    }

    .fade-right {
        transform: translateX(40px);
    }

    .zoom-soft {
        transform: scale(0.9);
    }

    .zoom-soft.show {
        transform: scale(1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 40px 20px 120px;
            min-height: auto;
            margin-top: -10px;
        }

        .hero-title {
            font-size: 28px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 24px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .hero-image {
            height: 80px !important;
            margin-bottom: 30px;
        }

        .hero .hero-image:nth-child(2) {
            height: 400px !important;
            margin-bottom: -300px;
        }

        .quick-actions {
            flex-direction: column;
            align-items: center;
        }

        .action-btn {
            width: 100%;
            max-width: 250px;
        }

        .profile {
            padding: 60px 0;
            margin-top: -80px;
        }

        .profile-flex {
            flex-direction: column;
            gap: 40px;
        }

        .profile-content h2 {
            font-size: 28px;
        }

        .profile-content p {
            font-size: 16px;
        }

        .title-with-dot {
            font-size: 28px;
        }

        .features {
            padding: 80px 0;
        }

        .section-title {
            font-size: 20px;
            margin-bottom: 40px;
        }

        .section-description {
            font-size: 16px;
            margin-bottom: 60px;
        }

        .grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .card {
            padding: 30px 20px;
        }

        .card h3 {
            font-size: 20px;
        }

        .card p {
            font-size: 15px;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0 15px;
        }

        .hero h1 {
            font-size: 28px;
        }

        .hero p {
            font-size: 15px;
        }

        .hero-image {
            height: 60px !important;
        }

        .hero .hero-image:nth-child(2) {
            height: 300px !important;
            margin-bottom: -250px;
        }

        .profile-content h1 {
            font-size: 36px;
        }

        .title-with-dot {
            font-size: 24px;
        }

        .card {
            padding: 25px 15px;
        }

        .action-btn {
            padding: 15px 20px;
            min-width: 100px;
        }

        .action-btn i {
            font-size: 24px;
        }

        .action-btn span {
            font-size: 12px;
        }
    }
</style>

<!-- HERO -->
<section id="home" class="hero">
    <div class="container">
        <img src="<?php echo e(asset('images/image 1.png')); ?>" alt="Hero Image 1" class="hero-image animate zoom-soft" style="height: 100px; margin-top: 0px; margin-bottom: 50px;">
        <h1 class="hero-title animate fade-up">
            Dashboard Talenta
        </h1>
        <h1 class="hero-subtitle animate fade-up delay-1" style="color: #eda711">Talent Pool Training (TPT)</h1>
        <p class="animate fade-up delay-2">LP Ma'arif NU DIY memerlukan sistem kaderisasi terstruktur untuk memastikan SDM kompeten, ideologis, tertib, berorientasi mutu, dan memiliki kepemimpinan selaras dengan nilai organisasi.</p>

    </div>
</section>

<!-- PENDAHULUAN -->
<section id="pendahuluan" class="section-clean">
    <div class="container">
        <h2 class="section-title animate fade-up">Pendahuluan</h2>
        <p class="section-subtitle">Talent Pool Training dirancang sebagai sistem kaderisasi terstruktur dan berkelanjutan.</p>
        <div class="grid animate fade-up delay-1">
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-target-lock"></i>
                </div>
                <h3>Kebutuhan Kaderisasi Terstruktur</h3>
                <p>LP Ma'arif NU DIY memerlukan sistem kaderisasi untuk SDM yang kompeten teknis, memiliki kesiapan ideologi-organisasi, mampu kelola tata tertib, berorientasi mutu layanan, dan kepemimpinan selaras nilai organisasi.</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-error-circle"></i>
                </div>
                <h3>Risiko Pendekatan Saat Ini</h3>
                <p>Kebutuhan kader muncul mendadak, diisi berdasarkan ketersediaan, berisiko lahirkan "pemimpin instan" kuat operasional tapi lemah ideologi dan sistem organisasi, kurang tertib, tidak konsisten mutu.</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-bulb"></i>
                </div>
                <h3>Solusi TPT</h3>
                <p>Dirancang Talent Pool Training (TPT) sistematis, berjenjang, berkelanjutan Level I-V. GTY sebagai basis kader, wajib TPT I untuk standar minimal kader melek ideologi-organisasi, paham tata kelola, orientasi layanan, bangun karakter kepemimpinan.</p>
            </div>
        </div>
    </div>
</section>

<!-- TUJUAN -->
<section id="tujuan" class="section-soft">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Tujuan</h2>
        <p class="section-subtitle">Membentuk SDM yang kompeten, ideologis, dan siap memimpin.</p>
        <div class="grid animate fade-up delay-1">
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-flag"></i>
                </div>
                <h3>Tujuan Umum</h3>
                <p>Siapkan dan kembangkan SDM LP Ma'arif NU DIY sistematis, berjenjang, berkelanjutan untuk kesiapan ideologis, kapasitas kepemimpinan, kemampuan pengelolaan organisasi selaras nilai dan kebijakan lembaga sesuai peran dan tingkat.</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-layer"></i>
                </div>
                <h3>TPT Level I</h3>
                <p>Siapkan GTY sebagai kader dasar: Internalisasi ideologi, disiplin organisasi, tata kelola dasar, etika layanan, pengetahuan kepemimpinan dasar. Fokus: Internalisasi dan kedisiplinan ideologis.</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-cog"></i>
                </div>
                <h3>TPT Level II</h3>
                <p>Siapkan pimpinan manajerial menengah (Waka, Kaprodi, dll.) untuk implementasi ideologi organisasi, kelola tata kelola satuan pendidikan, pimpin operasional sesuai tugas. Fokus: Pelaksana Manajerial.</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-network-chart"></i>
                </div>
                <h3>TPT Level III</h3>
                <p>Siapkan pengelola dan pengendali operasional lintas fungsi untuk kelola kompleksitas organisasi, selesaikan masalah strategis berbasis data, kendalikan kinerja unit. Fokus: Institutional Leadership.</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-school"></i>
                </div>
                <h3>TPT Level IV</h3>
                <p>Siapkan KS/Madrasah dan calon pengawas untuk kembangkan mutu satuan pendidikan, bangun budaya organisasi, ambil keputusan strategis berbasis nilai. Fokus: Institutional Leadership.</p>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-star"></i>
                </div>
                <h3>TPT Level V</h3>
                <p>Siapkan pemimpin sistem untuk desain replikasi mutu lintas satuan, lakukan pendampingan strategis, wujudkan dampak sistemik. Fokus: System Leader & Architect.</p>
            </div>
        </div>
    </div>
</section>

<!-- TARGET PROGRAM -->
<section id="target" class="section-clean">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Target Program</h2>
        <p class="section-subtitle">Target pencapaian yang ambisius dan terukur.</p>
        <div class="target-list animate fade-up delay-1">
            <div class="target-item">
                <i class="bx bx-target" style="color: #eda711; font-size: 24px;"></i>
                <div>
                    <h3>Target Umum</h3>
                    <p>Minimal 75% peserta TPT Level I-V terbentuk sebagai kader sesuai level dan layak diproyeksikan dalam sistem kaderisasi LP Ma'arif NU DIY.</p>
                </div>
            </div>
            <div class="target-item">
                <i class="bx bx-check-circle" style="color: #eda711; font-size: 24px;"></i>
                <div>
                    <h3>Target Khusus</h3>
                    <p>Minimal 75% peserta tiap level memenuhi kompetensi dan capaian produk sesuai standar kelulusan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SIFAT DAN KARAKTERISTIK TRAINING -->
<section id="karakteristik" class="section-soft">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Sifat dan Karakteristik Training</h2>
        <p class="section-subtitle">Pendekatan yang disiplin, reflektif, dan berbasis organisasi.</p>
        <div class="karakteristik-timeline animate fade-up delay-1">
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Wajib dan Berbasis Kaderisasi Struktural</h4>
                    <p>Training wajib dengan fokus pada pembentukan kader organisasi.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Reflektif-Organisasi sebagai Pendekatan Utama</h4>
                    <p>Pendekatan refleksi dan organisasi sebagai dasar pembelajaran.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Disiplin dan Tegas dalam Sistem dan Proses</h4>
                    <p>Menerapkan disiplin ketat dalam seluruh sistem dan proses.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Pembinaan Bertahap dan Berkelanjutan</h4>
                    <p>Pembinaan dilakukan secara bertahap dan berkelanjutan.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Kompetitif-Terkontrol dan Berkeadilan</h4>
                    <p>Kompetisi yang terkendali dan adil bagi semua peserta.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Peserta Diposisikan sebagai Kader sesuai Level</h4>
                    <p>Peserta diperlakukan sebagai kader sesuai tingkatannya.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Berbasis Data dan Terintegrasi dengan NUIST</h4>
                    <p>Berbasis data dan terintegrasi dengan sistem NUIST.</p>
                </div>
            </div>
            <div class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-content">
                    <h4>Siap Ditugaskan dengan Menjunjung Nilai Kemanusiaan</h4>
                    <p>Siap tugas dengan menjunjung tinggi nilai kemanusiaan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PERSYARATAN PESERTA -->
<section id="persyaratan" class="section-clean">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Persyaratan Peserta</h2>
        <p class="section-subtitle">Kriteria ketat untuk memastikan kualitas kader.</p>
        <div class="grid animate fade-up delay-1">
            <div class="card">
                <h3>Level I</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Guru GTY LPMNU</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Minimal 2 tahun pengabdian</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Wajib Kartanu</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Lulus PDPKPNU</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Rekomendasi Kepala Sekolah</li>
                </ul>
            </div>
            <div class="card">
                <h3>Level II</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Lulus Level I</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Minimal 5 tahun pengabdian</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> GTY Sertifikasi</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Rekomendasi KS/Kamad</li>
                </ul>
            </div>
            <div class="card">
                <h3>Level III</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Lulus Level II</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Minimal 5 tahun</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Rekomendasi DPS</li>
                </ul>
            </div>
            <div class="card">
                <h3>Level IV</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Lulus Level III</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Minimal 10 tahun</li>
                </ul>
            </div>
            <div class="card">
                <h3>Level V</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Lulus Level IV</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Minimal 15 tahun</li>
                </ul>
            </div>
            <div class="card">
                <h3>Ketentuan Tambahan</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Kehadiran 100%</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Tidak memiliki catatan kriminal/SP3</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Menandatangani Pakta Integritas</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- KETENTUAN KELULUSAN -->
<section id="kelulusan" class="section-soft">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Ketentuan Kelulusan</h2>
        <p class="section-subtitle">Standar kelulusan yang ketat dan terukur.</p>
        <div class="kelulusan-steps animate fade-up delay-1">
            <div class="step">
                <div class="step-number">1</div>
                <div>
                    <h4>Kehadiran 100%</h4>
                    <p>Wajib hadir penuh selama program berlangsung.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div>
                    <h4>Produk Minimal 90%</h4>
                    <p>Capaian produk kerja minimal 90% dari standar.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div>
                    <h4>Nilai Minimal 75</h4>
                    <p>Nilai rata-rata minimal 75 pada semua mata training.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">4</div>
                <div>
                    <h4>Disiplin Baik</h4>
                    <p>Kategori disiplin dinilai baik oleh tim trainer.</p>
                </div>
            </div>
            <div class="step">
                <div class="step-number">5</div>
                <div>
                    <h4>Bersedia Ditugaskan</h4>
                    <p>Bersedia menerima penugasan di seluruh wilayah LPMNU DIY.</p>
                </div>
            </div>
        </div>
        <p class="kelulusan-note animate fade-up delay-2">Kelulusan ditetapkan melalui rapat tim trainer, fasilitator dan penanggung jawab.</p>
    </div>
</section>

<!-- HAK DAN KEWAJIBAN LULUSAN -->
<section id="hak-kewajiban" class="section-clean">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Hak dan Kewajiban Lulusan</h2>
        <p class="section-subtitle">Hak dan kewajiban yang jelas untuk lulusan talenta.</p>
        <div class="grid animate fade-up delay-1">
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-gift"></i>
                </div>
                <h3>Hak</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Pengakuan sebagai kader strategis</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Prioritas penugasan</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Pendampingan lanjutan</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Keterlibatan dalam pengembangan kebijakan</li>
                </ul>
            </div>
            <div class="card">
                <div class="card-icon">
                    <i class="bx bx-task"></i>
                </div>
                <h3>Kewajiban</h3>
                <ul style="list-style: none; padding: 0;">
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Jaga integritas dan nilai ke-NU-an</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Implementasikan hasil training</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Bersedia ditugaskan</li>
                    <li><i class="bx bx-check" style="color: #10b981;"></i> Siap dievaluasi kinerjanya</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- GELAR TALENTA -->
<section id="gelar" class="section-soft">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Gelar Talenta</h2>
        <p class="section-subtitle">Hierarki gelar talenta berdasarkan level kompetensi.</p>
        <div class="card animate fade-up delay-1">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Gelar</th>
                        <th>Penugasan Prioritas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Level I</td>
                        <td>Talenta Pelaksana</td>
                        <td>MGMP, Pendamping Siswa</td>
                    </tr>
                    <tr>
                        <td>Level II</td>
                        <td>Talenta Penggerak</td>
                        <td>Pimpinan Level II</td>
                    </tr>
                    <tr>
                        <td>Level III</td>
                        <td>Talenta Pengembang</td>
                        <td>Waka Utama</td>
                    </tr>
                    <tr>
                        <td>Level IV</td>
                        <td>Talenta Strategis</td>
                        <td>KS Senior</td>
                    </tr>
                    <tr>
                        <td>Level V</td>
                        <td>Talenta Utama</td>
                        <td>Pembina Utama</td>
                    </tr>
                </tbody>
            </table>
            <p style="margin-top: 20px;">Target pengembangan bertahap: 50% naik level dalam 5 tahun; 50% Talenta Strategis menjadi Talenta Utama dalam 5-10 tahun.</p>
        </div>
    </div>
</section>

<!-- SISTEM PENILAIAN -->
<section id="penilaian" class="section-clean">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Sistem Penilaian</h2>
        <p class="section-subtitle">Sistem penilaian komprehensif untuk semua pihak terkait.</p>
        <div class="penilaian-container animate fade-up delay-1">
            <div class="penilaian-subjek">
                <h3>Subjek Penilaian</h3>
                <div class="subjek-list">
                    <span class="subjek-item">Trainer</span>
                    <span class="subjek-item">Fasilitator</span>
                    <span class="subjek-item">Tim Layanan Teknis</span>
                    <span class="subjek-item">Peserta</span>
                </div>
            </div>
            <div class="penilaian-skala">
                <h3>Skala Penilaian</h3>
                <div class="skala-grid">
                    <div class="skala-item">1 = Sangat Kurang</div>
                    <div class="skala-item">2 = Kurang</div>
                    <div class="skala-item">3 = Cukup</div>
                    <div class="skala-item">4 = Baik</div>
                    <div class="skala-item">5 = Sangat Baik</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DURASI DAN STRUKTUR WAKTU -->
<section id="durasi" class="section-soft">
    <div class="container">
        <div class="section-divider"></div>
        <h2 class="section-title animate fade-up">Durasi dan Struktur Waktu</h2>
        <p class="section-subtitle">Jadwal training yang terstruktur dan efektif.</p>
        <div class="card animate fade-up delay-1">
            <div class="card-icon">
                <i class="bx bx-time"></i>
            </div>
            <h3>Detail Durasi</h3>
            <ul style="list-style: none; padding: 0;">
                <li><i class="bx bx-check" style="color: #10b981;"></i> Total: 100 JPL (3-4 bulan)</li>
                <li><i class="bx bx-check" style="color: #10b981;"></i> Durasi: 7 minggu efektif</li>
                <li><i class="bx bx-check" style="color: #10b981;"></i> Tatap muka: 8-12 JPL per sesi</li>
                <li><i class="bx bx-check" style="color: #10b981;"></i> On The Job Assignment: 48 JPL</li>
                <li><i class="bx bx-check" style="color: #10b981;"></i> Presentasi & Tes: 14 JPL</li>
                <li><i class="bx bx-check" style="color: #10b981;"></i> TPT I: 08 Februari - 31 Maret 2026</li>
            </ul>
        </div>
    </div>
</section>

<!-- MATERI POKOK -->
<section id="materi" class="features">
    <div class="container">
        <h2 class="section-title animate fade-up">Materi Pokok</h2>
        <div class="card animate fade-up delay-1">
            <div class="card-icon">
                <i class="bx bx-book"></i>
            </div>
            <h3>Materi Utama</h3>
            <ul style="list-style: none; padding: 0;">
                <li><i class="bx bx-check" style="color: #10b981;"></i> Ideologi dan Organisasi</li>
                <li><i class="bx bx-check" style="color: #10b981;"></i> Tata Kelola</li>
                <li><i class="bx bx-check" style="color: #10b981;"></i> Layanan</li>
                <li><i class="bx bx-check" style="color: #10b981;"></i> SDM dan Kepemimpinan</li>
            </ul>
        </div>
    </div>
</section>

<?php echo $__env->make('landing.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const animatedElements = document.querySelectorAll(".animate");

    animatedElements.forEach(el => {
        el.classList.add("show");
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/dashboard.blade.php ENDPATH**/ ?>