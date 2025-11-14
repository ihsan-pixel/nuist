@extends('layouts.master-without-nav')

@section('title', 'PPDB NUIST - Penerimaan Peserta Didik Baru')

@section('css')
<link rel="stylesheet" href="{{ asset('css/ppdb-custom.css') }}">
<style>
    .hero-section {
        background: url('{{ asset("images/bg_ppdb2.png") }}');
        background-size: cover;
        background-position: center;
        background-attachment: absolute;

        /* FIX TERPENTING */
        display: block; /* buang flex agar elemen tidak ketarik tengah */
        padding-top: 40px; /* beri ruang flayer lebih kecil */
        padding-bottom: 80px;
        min-height: 93vh;
        position: relative;
    }

    .hero-section .container {
        position: relative;
        z-index: 2;
    }

    /* Flayer selalu center dan berada di paling atas */
    .hero-flayer {
        text-align: center;
        margin-bottom: 50px;
    }

    /* Tombol orange */
    .btn-orange {
        background-color: #ff7f00;
        color: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-orange:hover {
        background-color: #e66f00;
        color: #fff;
    }
    .navbar-brand img {
        height: 40px;
        width: auto;
    }
    .btn-primary {
        background-color: #667eea;
        border-color: #667eea;
    }
    .btn-primary:hover {
        background-color: #5a67d8;
        border-color: #5a67d8;
    }
    .school-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 75, 76, 0.1);
        border-radius: 15px;
        overflow: hidden;
        background: #ffffff;
        min-height: 260px;
        display: flex;
        flex-direction: column;
    }
    .school-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 75, 76, 0.2);
    }
    .school-card .card-img-top {
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }
    .school-card:hover .card-img-top {
        transform: scale(1.05);
    }
    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 15px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .school-info {
        background: #ffffff;
        color: #333333;
        padding: 15px;
        border-radius: 0 0 15px 15px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .school-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #004b4c;
    }
    .school-details {
        font-size: 0.85rem;
        color: #666666;
        line-height: 1.4;
        flex-grow: 1;
    }
    .btn-primary {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        border: none;
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 75, 76, 0.3);
    }
    .btn-secondary {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        border: none;
        border-radius: 25px;
        padding: 8px 20px;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 75, 76, 0.3);
    }
    .flyer-section {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        padding: 40px 0;
        color: white;
        text-align: center;
    }
    .flyer-images {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .flyer-img {
        width: 200px;
        height: 280px;
        object-fit: cover;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }
    .flyer-img:hover {
        transform: scale(1.05);
    }
    /* About Section */
    .about-section {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        color: white;
        padding: 80px 0;
    }
    .about-section h2 {
        color: white;
    }
    .about-section p {
        color: rgba(255, 255, 255, 0.9);
    }
    .about-section ul li {
        color: rgba(255, 255, 255, 0.9);
    }
    .about-section .bi-check-circle-fill {
        color: #efaa0c;
    }
    .feature-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: #c2facf;
        border-radius: 50%;
        margin-right: 15px;
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 75, 76, 0.2);
    }
    .feature-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 75, 76, 0.4);
        background-color: #a7f3d0;
    }
    .feature-icon .fas {
        color: #004b4c;
        font-size: 1.2rem;
        transition: color 0.3s ease;
    }
    .feature-icon:hover .fas {
        color: #004b4c;
    }
    .feature-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    .feature-content {
        flex: 1;
    }
    /* Contact Section */
    .contact-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 80px 0;
    }
    .contact-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        padding: 30px;
        text-align: center;
    }
    .contact-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .contact-card .bi {
        font-size: 3rem;
        color: #004b4c;
        margin-bottom: 20px;
    }
    .contact-card h5 {
        color: #004b4c;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .contact-card p {
        color: #666;
        font-size: 1rem;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out;
    }

    .animate-slide-in-left {
        animation: slideInLeft 0.8s ease-out;
    }

    .animate-slide-in-right {
        animation: slideInRight 0.8s ease-out;
    }

    .animate-bounce-in {
        animation: bounceIn 1s ease-out;
    }

    .animate-pulse {
        animation: pulse 2s infinite;
    }

    /* Enhanced hover effects */
    .school-card:hover .card-img-top {
        transform: scale(1.1) rotate(2deg);
    }

    .contact-card:hover .bi {
        animation: bounceIn 0.6s ease-out;
    }

    .feature-icon:hover {
        animation: pulse 0.6s ease-out;
    }

    .btn:hover {
        animation: bounceIn 0.4s ease-out;
    }

    /* Staggered animations for cards */
    .school-card:nth-child(1) { animation-delay: 0.1s; }
    .school-card:nth-child(2) { animation-delay: 0.2s; }
    .school-card:nth-child(3) { animation-delay: 0.3s; }
    .school-card:nth-child(4) { animation-delay: 0.4s; }
    .school-card:nth-child(5) { animation-delay: 0.5s; }
    .school-card:nth-child(6) { animation-delay: 0.6s; }
    .school-card:nth-child(7) { animation-delay: 0.7s; }
    .school-card:nth-child(8) { animation-delay: 0.8s; }

    .contact-card:nth-child(1) { animation-delay: 0.1s; }
    .contact-card:nth-child(2) { animation-delay: 0.2s; }
    .contact-card:nth-child(3) { animation-delay: 0.3s; }

    /* Scroll-triggered animations */
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease-out;
    }

    .animate-on-scroll.animate {
        opacity: 1;
        transform: translateY(0);
    }

    .animate-slide-left-on-scroll {
        opacity: 0;
        transform: translateX(-50px);
        transition: all 0.8s ease-out;
    }

    .animate-slide-left-on-scroll.animate {
        opacity: 1;
        transform: translateX(0);
    }

    .animate-slide-right-on-scroll {
        opacity: 0;
        transform: translateX(50px);
        transition: all 0.8s ease-out;
    }

    .animate-slide-right-on-scroll.animate {
        opacity: 1;
        transform: translateX(0);
    }

    .animate-bounce-on-scroll {
        opacity: 0;
        transform: scale(0.8);
        transition: all 1s ease-out;
    }

    .animate-bounce-on-scroll.animate {
        opacity: 1;
        transform: scale(1);
    }
</style>
@endsection

@section('content')
@include('partials.ppdb.navbar')

<section class="hero-section">

    <!-- Flayer di atas -->
    <div class="container hero-flayer animate-fade-in-up">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/flayer1.png') }}" class="img-fluid rounded animate-bounce-in" alt="Flayer PPDB">
            </div>
        </div>
    </div>

    <!-- Bagian Text + Image Hero -->
    <div class="container">
        <div class="row align-items-center">

            <!-- Text Kiri -->
            <div class="col-lg-6 animate-slide-in-left">
                <h1 class="display-4 fw-bold mb-4">Transformasi Sistem PPDB <span style="color: #efaa0c;">Online</span></h1>
                <p class="lead">Sistem Penerimaan Peserta Didik Baru Sekolah/Madrasah </p>
                <p class="lead"> Bawah Naungan LP. Ma'arif NU PWNU D.I. Yogyakarta</p>
                <p class="lead fw-bold">Tahun Pelajaran 2026/2027</p>
                <a href="#sekolah" class="btn btn-orange btn-lg me-3 animate-pulse">Lihat Sekolah</a>
            </div>

            <!-- Image Kanan -->
            <div class="col-lg-6 text-center animate-slide-in-right">
                <img src="{{ asset('images/hero1.png') }}" alt="PPDB Image" class="img-fluid rounded animate-bounce-in">
            </div>

        </div>
    </div>

</section>

<!-- Daftar Sekolah -->
<section id="sekolah" class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">Daftar <span style="color: #efaa0c;">Sekolah/Madrasah</span></h2>
                <p class="lead text-muted">Pilih madrasah impian Anda untuk melanjutkan pendidikan</p>
            </div>
        </div>

        <!-- Filter dan Search -->
        <div class="row mb-4">
            <div class="col-12">
                <form method="GET" action="{{ route('ppdb.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="kabupaten" class="form-label">Filter Kabupaten</label>
                        <select name="kabupaten" id="kabupaten" class="form-select">
                            <option value="">Semua Kabupaten</option>
                            @foreach($kabupatenList as $kabupaten)
                                <option value="{{ $kabupaten }}" {{ request('kabupaten') == $kabupaten ? 'selected' : '' }}>
                                    {{ $kabupaten }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Cari Nama Sekolah</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Masukkan nama sekolah..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('ppdb.index') }}" class="btn btn-primary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        @forelse($sekolahGrouped as $kabupaten => $sekolahList)
            <div class="kabupaten-section mb-3">
                <h3 class="kabupaten-title" onclick="window.toggleSchools(this)" style="color: #004b4c; font-weight: 600; cursor: pointer; border-bottom: 2px solid #004b4c; padding-bottom: 10px; display: flex; align-items: center; justify-content: space-between;">
                    {{ $kabupaten ?: 'Kabupaten Belum Diisi' }}
                    <i class="bi bi-chevron-down toggle-icon" style="transition: transform 0.3s; font-size: 1.2rem;"></i>
                </h3>
                <div class="kabupaten-schools" style="display: none; margin-top: 15px;">
                    <div class="row g-3">
                        @foreach($sekolahList as $madrasah)
                            @php
                                $ppdb = $madrasah->ppdbSettings->where('tahun', now()->year)->first(); // Ambil PPDB setting untuk tahun ini
                            @endphp
                            <div class="col-xl-2-4 col-lg-2-4 col-md-3 col-sm-6 col-12">
                                <div class="card school-card h-100">
                                    <div class="position-relative">
                                        <img src="{{ $madrasah->logo ? asset('storage/app/public/' . $madrasah->logo) : asset('images/madrasah-default.jpg') }}"
                                             class="card-img-top"
                                             alt="{{ $madrasah->name }}"
                                             style="height: 120px; object-fit: cover;">
                                        @if($ppdb && $ppdb->isPembukaan())
                                            <span class="badge bg-success status-badge">Buka</span>
                                        @elseif($ppdb && $ppdb->isStarted())
                                            <span class="badge bg-warning status-badge">Segera</span>
                                        @else
                                            <span class="badge bg-danger status-badge">Tutup</span>
                                        @endif
                                    </div>
                                    <div class="school-info">
                                        <h6 class="school-title mb-1" style="font-size: 0.9rem; color: #004b4c;">{{ $madrasah->name }}</h6>
                                        <div class="school-details" style="font-size: 0.75rem;">
                                            <small>SCOD: {{ $madrasah->scod ?: '-' }}</small><br>
                                            <small>Pendaftar: {{ $ppdb ? $ppdb->totalPendaftar() : 0 }}</small>
                                        </div>
                                        <div class="mt-1 text-center">
                                            @if($ppdb && $ppdb->isPembukaan())
                                                <a href="{{ route('ppdb.sekolah', $ppdb->slug) }}" class="btn btn-primary btn-sm w-100" style="font-size: 0.75rem; padding: 4px 8px;">Lihat Detail</a>
                                            @else
                                                <button class="btn btn-secondary btn-sm w-100 disabled" style="font-size: 0.75rem; padding: 4px 8px;">Ditutup</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <div class="alert alert-info" style="border-radius: 15px; border: none; background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);">
                    <h5 style="color: #667eea;">Tidak ada sekolah yang ditemukan</h5>
                    <p class="mb-0" style="color: #64748b;">Coba ubah filter atau pencarian Anda.</p>
                </div>
            </div>
        @endforelse
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-slide-in-left">
                <img src="{{ asset('images/flayer4.png') }}" alt="About PPDB" class="img-fluid rounded shadow animate-bounce-in" style="max-width: 120%; height: auto; transform: scale(1.2);">
            </div>
            <div class="col-lg-6 animate-slide-in-right">
                <h2 class="display-5 fw-bold mb-4">Beragam Fitur Unggulan dimiliki <span style="color: #efaa0c;">PPDB Online</span></h2>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="fw-bold mb-2" style="color: #c2facf;">Multi Jalur Seleksi</h5>
                        <p class="mb-0">Penyesuaian jalur seleksi sesuai dengan kondisi</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="fw-bold mb-2" style="color: #c2facf;">Multi Model Pendaftaran</h5>
                        <p class="mb-0">Berbagai macam model pendaftaran yang dapat dipilih untuk mensukseskan implementasi PPDB</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="fw-bold mb-2" style="color: #c2facf;">Manajemen Hak Akses</h5>
                        <p class="mb-0">Peran Admin dapat diatur secara fleksibel</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="feature-content">
                        <h5 class="fw-bold mb-2" style="color: #c2facf;">Integrasi Data Dapodik</h5>
                        <p class="mb-0">Mengakomodir integrasi data Dapodik baik sebagai data awalan untuk pendaftaran PPDB maupun data balikan ke Dapodik dari hasil seleksi akhir.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang Kami Section -->
<section id="tentang-kami" class="tentang-kami-section py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 animate-slide-left-on-scroll">
                <h2 class="display-5 fw-bold mb-4" style="color: #004b4c;">Tentang <span style="color: #efaa0c;">PPDB Online</span></h2>
                <p class="lead mb-4" style="color: #333;">
                    Dirancang untuk mendukung PPDB secara online dan efisien.
                    Dengan pengalaman kami sejak tahun 2003, PPDB Online memiliki kapabilitas untuk memberikan pelayanan optimal baik di tingkat kota/kabupaten maupun provinsi.
                </p>
                <p style="color: #555;">
                    Selain memfasilitasi proses penerimaan murid baru, platform ini juga menyederhanakan langkah-langkah verifikasi dokumen serta menyediakan akses yang mudah bagi calon murid dan orangtua/wali yang terlibat dalam proses tersebut.
                    Dengan demikian, PPDB Online bukan hanya menjadi solusi efektif dalam administrasi pendidikan, tetapi juga mencerminkan komitmen untuk memudahkan akses dan penggunaan teknologi modern dalam penyelenggaraan kegiatan Penerimaan Murid Baru.
                </p>
                <p style="color: #555;">
                    Sistem ini beroperasi sepanjang tahun, menyediakan akses dan layanan kapan pun diperlukan, memfasilitasi selama proses seleksi PPDB, serta menampilkan arsip data dari pelaksanaan tahun-tahun sebelumnya.
                </p>
            </div>
            <div class="col-lg-6 text-center animate-slide-right-on-scroll">
                <div class="stats-card p-4 rounded shadow animate-bounce-on-scroll" style="background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%); color: white;">
                    <h3 class="fw-bold mb-3">PPDB Online</h3>
                    <h4 class="mb-3">Platform Penerimaan</h4>
                    <h4 class="mb-4">Murid Baru Secara Online</h4>
                    <p class="mb-0">Sistem layanan inovatif terpadu.</p>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12 text-center animate-on-scroll">
                <p class="lead" style="color: #333;">
                    Layanan PPDB Online adalah sistem layanan inovatif terpadu yang dirancang untuk memfasilitasi dan mengoptimalkan proses seleksi dalam pelaksanaan Penerimaan Murid Baru (PPDB), mulai dari proses pendaftaran, seleksi, hingga pengumuman hasil seleksi, yang dilakukan secara online dan berbasis waktu nyata (real-time).
                    Melalui PPDB Online, para pihak terkait, seperti calon murid dan orang tua atau wali, dapat mengakses platform dengan mudah untuk melakukan pendaftaran, mengunggah dokumen yang diperlukan, serta terlibat dalam proses verifikasi.
                </p>
                <p style="color: #555;">
                    PPDB Online mudah diakses dari manapun melalui berbagai perangkat yang terkoneksi jaringan internet.
                </p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12 text-center animate-on-scroll">
                <h3 class="fw-bold mb-4" style="color: #004b4c;">Manfaat dan Keuntungan menggunakan <span style="color: #efaa0c;">PPDB Online</span></h3>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="benefit-card p-4 rounded shadow animate-on-scroll" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-left: 5px solid #004b4c;">
                    <h4 class="fw-bold mb-3" style="color: #004b4c;">Bagi Dinas Pendidikan dan Sekolah</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2" style="color: #333;"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Efisiensi Pembiayaan:</strong> Efisiensi pembiayaan dan mengurangi resiko terjadinya KKN</li>
                        <li class="mb-2" style="color: #333;"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Reputasi:</strong> Meningkatkan reputasi sekolah</li>
                        <li class="mb-2" style="color: #333;"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Bebas Akses:</strong> Memberikan akses yang luas kepada masyarakat</li>
                        <li class="mb-0" style="color: #333;"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Integrasi Basis Data:</strong> Tersedianya sebuah basis data terintegrasi</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="benefit-card p-4 rounded shadow animate-on-scroll" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-left: 5px solid #efaa0c;">
                    <h4 class="fw-bold mb-3" style="color: #004b4c;">Bagi Murid dan Orang Tua Murid</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2" style="color: #333;"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Pendaftaran Yang Mudah:</strong> Mempermudah untuk melakukan pendaftaran murid baru</li>
                        <li class="mb-2" style="color: #333;"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Update Informasi:</strong> Mempermudah akses informasi penerimaan murid baru</li>
                        <li class="mb-2" style="color: #333;"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Memantau Pendaftaran:</strong> Pendaftaran menjadi lebih tertib dan mudah dipantau</li>
                        <li class="mb-0" style="color: #333;"><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Pelayanan Maksimal:</strong> Fasilitas dan pelayanan memuaskan</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row text-center mb-5">
            <div class="col-12 animate-on-scroll">
                <div class="stats-container p-4 rounded shadow" style="background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%); color: white;">
                    <h4 class="fw-bold mb-3">Berpengalaman lebih dari 20 Tahun</h4>
                    <p class="mb-3">PPDB Online telah beroperasi sejak tahun 2003 hingga saat ini. Dipercayai melaksanakan PPDB Online mulai dari ujung Barat pulau Sumatera hingga ujung Timur pulau Papua.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="display-4 fw-bold" style="color: #efaa0c;">147</h2>
                            <p style="color: white;">Dinas Pendidikan</p>
                        </div>
                        <div class="col-md-6">
                            <h2 class="display-4 fw-bold" style="color: #efaa0c;">7000+</h2>
                            <p style="color: white;">Sekolah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center mb-4 animate-on-scroll">
                <h3 class="fw-bold" style="color: #004b4c;">Keunggulan <span style="color: #efaa0c;">PPDB Online</span></h3>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 rounded shadow text-center animate-on-scroll" style="background: white; height: 100%;">
                    <i class="bi bi-cloud-fill display-4 mb-3" style="color: #004b4c;"></i>
                    <h5 class="fw-bold mb-3" style="color: #004b4c;">Tanpa Instalasi & Berbasis Cloud</h5>
                    <p style="color: #555;">Tidak perlu menginstal perangkat lunak atau perangkat keras khusus, hanya perlu akses internet.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 rounded shadow text-center animate-on-scroll" style="background: white; height: 100%;">
                    <i class="bi bi-device-fill display-4 mb-3" style="color: #004b4c;"></i>
                    <h5 class="fw-bold mb-3" style="color: #004b4c;">Multi Platform & Multi Akses</h5>
                    <p style="color: #555;">Dapat diakses melalui desktop, laptop, tablet, maupun ponsel pintar baik melalui internet, SMS, maupun aplikasi Android.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 rounded shadow text-center animate-on-scroll" style="background: white; height: 100%;">
                    <i class="bi bi-database-fill display-4 mb-3" style="color: #004b4c;"></i>
                    <h5 class="fw-bold mb-3" style="color: #004b4c;">Integrasi Data Dapodik</h5>
                    <p style="color: #555;">Memastikan seluruh data pendaftaran calon murid selaras dengan data pendidikan nasional.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 rounded shadow text-center animate-on-scroll" style="background: white; height: 100%;">
                    <i class="bi bi-phone-fill display-4 mb-3" style="color: #004b4c;"></i>
                    <h5 class="fw-bold mb-3" style="color: #004b4c;">Responsive Design</h5>
                    <p style="color: #555;">Tampilan dapat disesuaikan dengan perangkat yang digunakan.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 rounded shadow text-center animate-on-scroll" style="background: white; height: 100%;">
                    <i class="bi bi-diagram-3-fill display-4 mb-3" style="color: #004b4c;"></i>
                    <h5 class="fw-bold mb-3" style="color: #004b4c;">Multi Model Alur Pendaftaran</h5>
                    <p style="color: #555;">Menyediakan berbagai macam alur pendaftaran sesuai dengan petunjuk teknis.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card p-4 rounded shadow text-center animate-on-scroll" style="background: white; height: 100%;">
                    <i class="bi bi-signpost-split-fill display-4 mb-3" style="color: #004b4c;"></i>
                    <h5 class="fw-bold mb-3" style="color: #004b4c;">Multi Jalur Seleksi</h5>
                    <p style="color: #555;">Mendukung PPDB Online dengan berbagai jalur sesuai dengan Permendikbud.</p>
                </div>
            </div>
            <div class="col-12">
                <div class="feature-card p-4 rounded shadow text-center animate-on-scroll" style="background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%); color: white;">
                    <i class="bi bi-clock-fill display-4 mb-3"></i>
                    <h5 class="fw-bold mb-3">Real Time Process</h5>
                    <p style="color: white;">Memproses data pendaftaran calon murid secara real time mulai dari pendaftaran hingga pengumuman hasil seleksi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="kontak" class="contact-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="display-5 fw-bold" style="color: #004b4c;">Hubungi <span style="color: #efaa0c;">Kami</span></h2>
                <p class="lead" style="color: #666;">Butuh bantuan? Tim kami siap membantu Anda</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="bi bi-telephone-fill"></i>
                    <h5>Telepon</h5>
                    <p>(021) 1234-5678</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="bi bi-envelope-fill"></i>
                    <h5>Email</h5>
                    <p>ppdb@nuist.id</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h5>Alamat</h5>
                    <p>Jl. KH. Wahid Hasyim No. 123<br>Jakarta Pusat, 10250</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.ppdb.footer')
@endsection

<script>
// Define function globally first
window.toggleSchools = function(element) {
    const kabupatenSection = element.closest('.kabupaten-section');
    const schoolsDiv = kabupatenSection.querySelector('.kabupaten-schools');
    const toggleIcon = kabupatenSection.querySelector('.toggle-icon');

    if (schoolsDiv.style.display === 'none' || schoolsDiv.style.display === '') {
        schoolsDiv.style.display = 'block';
        toggleIcon.style.transform = 'rotate(180deg)';
    } else {
        schoolsDiv.style.display = 'none';
        toggleIcon.style.transform = 'rotate(0deg)';
    }
};
</script>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Smooth scrolling (tetap)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Navbar scroll effect (tetap)
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;
        if (window.scrollY > 50) navbar.classList.add('navbar-scrolled');
        else navbar.classList.remove('navbar-scrolled');
    });
});
</script>
@endsection
