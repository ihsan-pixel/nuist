@extends('layouts.master-without-nav')

@section('title', 'Data MGMP')

@section('content')
@include('mgmp.navbar')

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
        min-height: 40vh;
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

    /* SECTION BACKGROUNDS */
    .section-clean {
        padding: 100px 0;
        background: #ffffff;
    }

    .section-soft {
        padding: 100px 0;
        background: #f8fafc;
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
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: auto;
    }

    .mgmp-card {
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        transition: all 0.3s ease;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }

    .mgmp-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }

    .mgmp-logo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin: 0 auto 20px;
        object-fit: cover;
        border: 3px solid #004b4c;
    }

    .mgmp-name {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 15px;
        color: #004b4c;
    }

    .mgmp-description {
        font-size: 15px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .participants-section {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid #e5e7eb;
    }

    .participants-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #004b4c;
    }

    .participants-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .participant-item {
        padding: 8px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #64748b;
    }

    .participant-item:last-child {
        border-bottom: none;
    }

    .participant-name {
        font-weight: 500;
        color: #0f172a;
    }

    .participant-role {
        font-size: 12px;
        color: #6b7280;
        margin-left: 8px;
    }

    .no-data {
        text-align: center;
        color: #6b7280;
        font-style: italic;
        padding: 40px 20px;
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
        text-align: left;
    }

    .table-modern td {
        padding: 14px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table-modern tr:hover {
        background: #f1f5f9;
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

        .mgmp-card {
            padding: 30px 20px;
        }

        .mgmp-logo {
            width: 60px;
            height: 60px;
        }

        .mgmp-name {
            font-size: 20px;
        }

        .mgmp-description {
            font-size: 14px;
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

        .mgmp-card {
            padding: 25px 15px;
        }

        .mgmp-logo {
            width: 50px;
            height: 50px;
        }

        .mgmp-name {
            font-size: 18px;
        }
    }
</style>

<!-- HERO -->
<section id="home" class="hero">
    <div class="container">
        <h1 class="hero-title animate fade-up">
            Data MGMP
        </h1>
        <h1 class="hero-subtitle animate fade-up delay-1" style="color: #eda711">Musyawarah Guru Mata Pelajaran</h1>
        <p class="animate fade-up delay-2">Informasi lengkap tentang MGMP dan peserta yang terlibat dalam kegiatan pembelajaran.</p>
    </div>
</section>

<!-- DATA MGMP -->
<section id="data-mgmp" class="section-clean">
    <div class="container">
        <h2 class="section-title animate fade-up">Daftar MGMP</h2>
        <p class="section-subtitle">Informasi lengkap tentang Musyawarah Guru Mata Pelajaran beserta peserta aktifnya.</p>
        <div class="grid animate fade-up delay-1">
            <!-- MGMP Matematika -->
            <div class="mgmp-card">
                <img src="{{ asset('images/mgmp-matematika.png') }}" alt="Logo MGMP Matematika" class="mgmp-logo">
                <h3 class="mgmp-name">MGMP Matematika</h3>
                <p class="mgmp-description">Musyawarah Guru Mata Pelajaran Matematika untuk meningkatkan kualitas pembelajaran matematika di sekolah-sekolah.</p>

                <div class="participants-section">
                    <h4 class="participants-title">Peserta Aktif</h4>
                    <ul class="participants-list">
                        <li class="participant-item">
                            <span class="participant-name">Ahmad Surya</span>
                            <span class="participant-role">Ketua MGMP</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Siti Nurhaliza</span>
                            <span class="participant-role">Sekretaris</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Budi Santoso</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Maya Sari</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Rudi Hartono</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- MGMP Bahasa Indonesia -->
            <div class="mgmp-card">
                <img src="{{ asset('images/mgmp-bindo.png') }}" alt="Logo MGMP Bahasa Indonesia" class="mgmp-logo">
                <h3 class="mgmp-name">MGMP Bahasa Indonesia</h3>
                <p class="mgmp-description">Musyawarah Guru Mata Pelajaran Bahasa Indonesia untuk pengembangan kompetensi mengajar bahasa dan sastra.</p>

                <div class="participants-section">
                    <h4 class="participants-title">Peserta Aktif</h4>
                    <ul class="participants-list">
                        <li class="participant-item">
                            <span class="participant-name">Dewi Lestari</span>
                            <span class="participant-role">Ketua MGMP</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Agus Priyanto</span>
                            <span class="participant-role">Sekretaris</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Rina Wulandari</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Eko Prasetyo</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Lina Marlina</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- MGMP IPA -->
            <div class="mgmp-card">
                <img src="{{ asset('images/mgmp-ipa.png') }}" alt="Logo MGMP IPA" class="mgmp-logo">
                <h3 class="mgmp-name">MGMP IPA</h3>
                <p class="mgmp-description">Musyawarah Guru Mata Pelajaran Ilmu Pengetahuan Alam untuk inovasi pembelajaran sains dan eksperimen.</p>

                <div class="participants-section">
                    <h4 class="participants-title">Peserta Aktif</h4>
                    <ul class="participants-list">
                        <li class="participant-item">
                            <span class="participant-name">Dr. Hendra Wijaya</span>
                            <span class="participant-role">Ketua MGMP</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Sri Wahyuni</span>
                            <span class="participant-role">Sekretaris</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Tono Subagio</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Ani Suryani</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Dedi Kurniawan</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- MGMP IPS -->
            <div class="mgmp-card">
                <img src="{{ asset('images/mgmp-ips.png') }}" alt="Logo MGMP IPS" class="mgmp-logo">
                <h3 class="mgmp-name">MGMP IPS</h3>
                <p class="mgmp-description">Musyawarah Guru Mata Pelajaran Ilmu Pengetahuan Sosial untuk pengembangan pemahaman sosial dan kewarganegaraan.</p>

                <div class="participants-section">
                    <h4 class="participants-title">Peserta Aktif</h4>
                    <ul class="participants-list">
                        <li class="participant-item">
                            <span class="participant-name">Prof. Sumarno</span>
                            <span class="participant-role">Ketua MGMP</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Indah Permata</span>
                            <span class="participant-role">Sekretaris</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Joko Widodo</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Sari Dewanti</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Bambang Sutrisno</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- MGMP Bahasa Inggris -->
            <div class="mgmp-card">
                <img src="{{ asset('images/mgmp-english.png') }}" alt="Logo MGMP Bahasa Inggris" class="mgmp-logo">
                <h3 class="mgmp-name">MGMP Bahasa Inggris</h3>
                <p class="mgmp-description">Musyawarah Guru Mata Pelajaran Bahasa Inggris untuk meningkatkan kemampuan berkomunikasi dalam bahasa internasional.</p>

                <div class="participants-section">
                    <h4 class="participants-title">Peserta Aktif</h4>
                    <ul class="participants-list">
                        <li class="participant-item">
                            <span class="participant-name">John Smith</span>
                            <span class="participant-role">Ketua MGMP</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Maria Rodriguez</span>
                            <span class="participant-role">Sekretaris</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Ahmad Rahman</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Lisa Chen</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">David Wilson</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- MGMP PKn -->
            <div class="mgmp-card">
                <img src="{{ asset('images/mgmp-pkn.png') }}" alt="Logo MGMP PKn" class="mgmp-logo">
                <h3 class="mgmp-name">MGMP PKn</h3>
                <p class="mgmp-description">Musyawarah Guru Mata Pelajaran Pendidikan Kewarganegaraan untuk membentuk karakter dan wawasan kebangsaan.</p>

                <div class="participants-section">
                    <h4 class="participants-title">Peserta Aktif</h4>
                    <ul class="participants-list">
                        <li class="participant-item">
                            <span class="participant-name">Dr. Susilo Bambang</span>
                            <span class="participant-role">Ketua MGMP</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Ratna Sari</span>
                            <span class="participant-role">Sekretaris</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Wahyu Nugroho</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Dina Puspita</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                        <li class="participant-item">
                            <span class="participant-name">Adi Pratama</span>
                            <span class="participant-role">Anggota</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATISTIK PESERTA -->
<section id="statistik" class="section-soft">
    <div class="container">
        <h2 class="section-title animate fade-up">Statistik Peserta MGMP</h2>
        <p class="section-subtitle">Ringkasan data peserta MGMP berdasarkan mata pelajaran.</p>
        <div class="card animate fade-up delay-1">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Jumlah MGMP</th>
                        <th>Total Peserta</th>
                        <th>Ketua MGMP</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Matematika</td>
                        <td>1</td>
                        <td>25</td>
                        <td>Ahmad Surya</td>
                        <td><span style="color: #10b981; font-weight: 600;">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>Bahasa Indonesia</td>
                        <td>1</td>
                        <td>30</td>
                        <td>Dewi Lestari</td>
                        <td><span style="color: #10b981; font-weight: 600;">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>IPA</td>
                        <td>1</td>
                        <td>28</td>
                        <td>Dr. Hendra Wijaya</td>
                        <td><span style="color: #10b981; font-weight: 600;">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>IPS</td>
                        <td>1</td>
                        <td>22</td>
                        <td>Prof. Sumarno</td>
                        <td><span style="color: #10b981; font-weight: 600;">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>Bahasa Inggris</td>
                        <td>1</td>
                        <td>20</td>
                        <td>John Smith</td>
                        <td><span style="color: #10b981; font-weight: 600;">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>PKn</td>
                        <td>1</td>
                        <td>18</td>
                        <td>Dr. Susilo Bambang</td>
                        <td><span style="color: #10b981; font-weight: 600;">Aktif</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

@include('landing.footer')

<script>
document.addEventListener("DOMContentLoaded", function() {
    const animatedElements = document.querySelectorAll(".animate");

    animatedElements.forEach(el => {
        el.classList.add("show");
    });
});
</script>

@endsection
