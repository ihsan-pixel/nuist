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

    /* Tentang Kami Section */
    .tentang-kami-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 80px 0;
    }

    .stats-card {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .benefit-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        border-left: 5px solid #004b4c;
    }

    .benefit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .feature-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .stats-container {
        background: linear-gradient(135deg, #004b4c 0%, #004b4c 100%);
        color: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .stats-container:hover {
        transform: translateY(-5px);
    }

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
                <img src="{{ asset('images/flayer1.png') }}" class="img-fluid rounded animate-bounce-in mb-10" alt="Flayer PPDB">
            </div>
        </div>
    </div>

    <!-- Bagian Text + Image Hero -->
    <div class="container">
        <div class="row align-items-center">

            <!-- Text Kiri -->
            <div class="col-lg-6 animate-slide-in-left">
                <h1 class="display-4 fw-bold mb-4">Transformasi Sistem PPDB <span style="color: #efaa0c;">Online</span></h1>
                <p class="lead">Sistem Penerimaan Peserta Didik Baru Sekolah/Madrasah</p>
                <p class="lead"> Bawah Naungan LP. Ma'arif NU PWNU D.I. Yogyakarta</p>
                <p class="lead fw-bold">Tahun Pelajaran 2026/2027</p>
                <a href="#sekolah" class="btn btn-orange btn-lg me-3 animate-pulse">Lihat Sekolah</a>
                <a href="{{ route('ppdb.cek-status') }}" class="btn btn-outline-light btn-lg animate-pulse">Cek Status Pendaftaran</a>
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
                        <label for="search" class="form-label">Cari Nama Madrasah/Sekolah</label>
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
                    <i class="fas fa-chevron-down toggle-icon" style="transition: transform 0.3s; font-size: 1.2rem;"></i>
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
                                        <img src="{{ $madrasah->logo ? asset('storage/' . $madrasah->logo) : asset('images/madrasah-default.jpg') }}"
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
                                            @if($ppdb)
                                                <a href="{{ route('ppdb.sekolah', $ppdb->slug) }}" class="btn btn-outline-primary btn-sm w-100 mb-1" style="font-size: 0.7rem; padding: 3px 6px;">Lihat Halaman Sekolah</a>
                                                <a href="{{ route('ppdb.daftar', $ppdb->slug) }}" class="btn btn-primary btn-sm w-100" style="font-size: 0.75rem; padding: 4px 8px;">Daftar</a>
                                            @else
                                                <a href="{{ route('ppdb.sekolah', $madrasah->id) }}" class="btn btn-outline-primary btn-sm w-100 mb-1" style="font-size: 0.7rem; padding: 3px 6px;">Lihat Halaman Sekolah</a>
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
        <!-- Section Header -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-4 fw-bold mb-3" style="color: #004b4c;">Tentang <span style="color: #efaa0c;">PPDB Online</span></h2>
                <div class="mx-auto" style="max-width: 700px;">
                    <p class="lead mb-0" style="color: #666; font-size: 1.1rem; line-height: 1.6;">
                        Platform terdepan dalam transformasi digital sistem penerimaan peserta didik baru di LP. Ma'arif NU D.I. Yogyakarta
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Introduction -->
        <div class="row align-items-center mb-5">
            <div class="col-lg-8">
                <div class="content-wrapper pe-lg-4">
                    <h3 class="h2 fw-bold mb-4" style="color: #004b4c;">Sistem Inovatif Terpadu</h3>
                    <p class="mb-4" style="color: #555; font-size: 1rem; line-height: 1.7;">
                        PPDB Online adalah platform terdepan dalam transformasi digital sistem penerimaan peserta didik baru di LP. Ma'arif NU D.I. Yogyakarta, kami menyediakan solusi lengkap untuk kemudahan akses pendidikan berkualitas.
                    </p>
                    <p class="mb-4" style="color: #555; font-size: 1rem; line-height: 1.7;">
                        Platform ini tidak hanya memfasilitasi proses administrasi, tetapi juga memastikan transparansi, efisiensi,
                        dan kemudahan akses bagi semua pihak yang terlibat dalam proses penerimaan murid baru.
                    </p>
                    <div class="key-features d-flex flex-wrap gap-3">
                        <div class="feature-badge px-3 py-2 rounded-pill" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); color: #1976d2; font-weight: 500;">
                            <i class="fas fa-shield-alt me-2"></i>Keamanan Data
                        </div>
                        <div class="feature-badge px-3 py-2 rounded-pill" style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%); color: #7b1fa2; font-weight: 500;">
                            <i class="fas fa-clock me-2"></i>Real-time Processing
                        </div>
                        <div class="feature-badge px-3 py-2 rounded-pill" style="background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%); color: #388e3c; font-weight: 500;">
                            <i class="fas fa-globe me-2"></i>Multi-Platform
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="highlight-card p-4 rounded-4 shadow-lg text-center h-100" style="background: linear-gradient(135deg, #004b4c 0%, #00695c 100%); color: white; position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -30px; right: -30px; width: 80px; height: 80px; background: rgba(239, 170, 12, 0.1); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -20px; left: -20px; width: 60px; height: 60px; background: rgba(239, 170, 12, 0.1); border-radius: 50%;"></div>
                    <div style="position: relative; z-index: 2;">
                        <i class="fas fa-award display-4 mb-3" style="color: #efaa0c;"></i>
                        <h4 class="fw-bold mb-2">PPDB Online</h4>
                        <p class="mb-3 opacity-75">Platform Terpercaya</p>
                        <p class="small opacity-75 mb-0">Sistem layanan inovatif terpadu untuk pendidikan masa depan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefits Section -->
        <div class="benefits-section mb-5">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h3 class="h1 fw-bold mb-3" style="color: #004b4c;">Manfaat & Keuntungan</h3>
                    <p class="lead" style="color: #666; max-width: 600px; margin: 0 auto;">
                        Memberikan nilai tambah signifikan bagi semua stakeholder dalam ekosistem pendidikan
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <!-- For Education Departments & Schools -->
                <div class="col-lg-6">
                    <div class="benefit-card p-4 rounded-4 shadow-sm h-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-left: 5px solid #004b4c; transition: all 0.3s ease;">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-container me-3" style="background: linear-gradient(135deg, #004b4c 0%, #00695c 100%); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-school text-white fs-5"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0" style="color: #004b4c;">Dinas Pendidikan & Sekolah</h4>
                                <p class="small text-muted mb-0">Institusi Pendidikan</p>
                            </div>
                        </div>
                        <div class="benefits-list">
                            <div class="benefit-item mb-3 pb-3 border-bottom border-light">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #333;">Efisiensi Pembiayaan</h6>
                                        <p class="small mb-0" style="color: #666;">Mengurangi biaya operasional</p>
                                    </div>
                                </div>
                            </div>
                            <div class="benefit-item mb-3 pb-3 border-bottom border-light">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #333;">Reputasi Terjaga</h6>
                                        <p class="small mb-0" style="color: #666;">Meningkatkan kredibilitas dan transparansi sekolah</p>
                                    </div>
                                </div>
                            </div>
                            <div class="benefit-item mb-3 pb-3 border-bottom border-light">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #333;">Akses Universal</h6>
                                        <p class="small mb-0" style="color: #666;">Memberikan akses yang luas kepada masyarakat</p>
                                    </div>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #333;">Data Terintegrasi</h6>
                                        <p class="small mb-0" style="color: #666;">Basis data terpadu untuk perencanaan pendidikan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- For Students & Parents -->
                <div class="col-lg-6">
                    <div class="benefit-card p-4 rounded-4 shadow-sm h-100" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); border-left: 5px solid #efaa0c; transition: all 0.3s ease;">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-container me-3" style="background: linear-gradient(135deg, #efaa0c 0%, #ff8f00 100%); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-users text-white fs-5"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0" style="color: #004b4c;">Murid & Orang Tua</h4>
                                <p class="small text-muted mb-0">Calon Peserta Didik</p>
                            </div>
                        </div>
                        <div class="benefits-list">
                            <div class="benefit-item mb-3 pb-3 border-bottom border-light">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #333;">Pendaftaran Mudah</h6>
                                        <p class="small mb-0" style="color: #666;">Proses pendaftaran yang sederhana dan cepat</p>
                                    </div>
                                </div>
                            </div>
                            <div class="benefit-item mb-3 pb-3 border-bottom border-light">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #333;">Informasi Real-time</h6>
                                        <p class="small mb-0" style="color: #666;">Update informasi penerimaan secara langsung</p>
                                    </div>
                                </div>
                            </div>
                            <div class="benefit-item mb-3 pb-3 border-bottom border-light">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #333;">Monitoring Terpadu</h6>
                                        <p class="small mb-0" style="color: #666;">Pantau status pendaftaran dengan mudah</p>
                                    </div>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1 flex-shrink-0"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1" style="color: #333;">Pelayanan Prima</h6>
                                        <p class="small mb-0" style="color: #666;">Fasilitas dan pelayanan yang memuaskan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Experience & Stats -->
        <div class="experience-section mb-5">
            <div class="experience-card p-5 rounded-4 shadow-lg text-center" style="background: linear-gradient(135deg, #004b4c 0%, #00695c 100%); color: white; position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.03)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.02)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg></div>
                <div style="position: relative; z-index: 2;">
                    <div class="mb-4">
                        <i class="fas fa-trophy display-4 mb-3" style="color: #efaa0c;"></i>
                        <h3 class="fw-bold mb-3">Madrasah/Sekolah yang Kita Naungi</h3>
                        <p class="lead opacity-90 mb-4">Madrasah/Sekolah Jenjang SMA/SMK/MA</p>
                    </div>
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-3">
                            <div class="stat-item">
                                <h2 class="display-3 fw-bold mb-2" style="color: #efaa0c;">36</h2>
                                <p class="mb-0 opacity-75">Madrasah/Sekolah</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <h2 class="display-3 fw-bold mb-2" style="color: #efaa0c;">750+</h2>
                                <p class="mb-0 opacity-75">Users</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <h2 class="display-3 fw-bold mb-2" style="color: #efaa0c;">6000+</h2>
                                <p class="mb-0 opacity-75">Peserta Didik</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-item">
                                <h2 class="display-3 fw-bold mb-2" style="color: #efaa0c;">100%</h2>
                                <p class="mb-0 opacity-75">Meningkatkan Kualitas Pelayanan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="features-section">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h3 class="h1 fw-bold mb-3" style="color: #004b4c;">Keunggulan Teknologi</h3>
                    <p class="lead" style="color: #666; max-width: 600px; margin: 0 auto;">
                        Fitur-fitur canggih yang menjadikan PPDB Online sebagai solusi terdepan
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 rounded-4 shadow-sm text-center h-100" style="background: white; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        <div class="feature-icon mb-3" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-cloud fs-2" style="color: #1976d2;"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #004b4c;">Cloud-Based System</h5>
                        <p style="color: #555; line-height: 1.6; font-size: 0.95rem;">Tidak memerlukan instalasi perangkat lunak khusus, hanya perlu koneksi internet untuk akses penuh.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 rounded-4 shadow-sm text-center h-100" style="background: white; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        <div class="feature-icon mb-3" style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-mobile-alt fs-2" style="color: #9c27b0;"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #004b4c;">Multi-Platform Access</h5>
                        <p style="color: #555; line-height: 1.6; font-size: 0.95rem;">Dapat diakses melalui berbagai perangkat: desktop, tablet, smartphone dengan interface yang responsif.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 rounded-4 shadow-sm text-center h-100" style="background: white; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        <div class="feature-icon mb-3" style="background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-database fs-2" style="color: #388e3c;"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #004b4c;">Data Integration</h5>
                        <p style="color: #555; line-height: 1.6; font-size: 0.95rem;">Terintegrasi penuh dengan sistem Dapodik nasional untuk validasi dan sinkronisasi data.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 rounded-4 shadow-sm text-center h-100" style="background: white; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        <div class="feature-icon mb-3" style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-mobile-alt fs-2" style="color: #f57c00;"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #004b4c;">Responsive Design</h5>
                        <p style="color: #555; line-height: 1.6; font-size: 0.95rem;">Tampilan yang menyesuaikan dengan ukuran layar perangkat untuk pengalaman optimal.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 rounded-4 shadow-sm text-center h-100" style="background: white; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        <div class="feature-icon mb-3" style="background: linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-sitemap fs-2" style="color: #e91e63;"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #004b4c;">Multi-Model Registration</h5>
                        <p style="color: #555; line-height: 1.6; font-size: 0.95rem;">Berbagai model alur pendaftaran sesuai dengan kebutuhan dan regulasi masing-masing daerah.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card p-4 rounded-4 shadow-sm text-center h-100" style="background: white; border: 1px solid #e9ecef; transition: all 0.3s ease;">
                        <div class="feature-icon mb-3" style="background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-route fs-2" style="color: #009688;"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #004b4c;">Multi-Path Selection</h5>
                        <p style="color: #555; line-height: 1.6; font-size: 0.95rem;">Mendukung berbagai jalur seleksi sesuai dengan Permendikbud dan kebijakan daerah.</p>
                    </div>
                </div>
                <div class="col-12">
                    <div class="feature-card p-4 rounded-4 shadow-lg text-center" style="background: linear-gradient(135deg, #004b4c 0%, #00695c 100%); color: white; border: none;">
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <div class="feature-icon me-3" style="background: rgba(239, 170, 12, 0.2); width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock fs-2" style="color: #efaa0c;"></i>
                            </div>
                            <div class="text-start">
                                <h4 class="fw-bold mb-1">Real-Time Processing</h4>
                                <p class="mb-0 opacity-75">Proses pendaftaran hingga pengumuman hasil secara real-time</p>
                            </div>
                        </div>
                        <p class="lead opacity-90 mb-0">Memastikan transparansi dan kecepatan dalam setiap tahap proses PPDB</p>
                    </div>
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
                    <i class="fas fa-phone"></i>
                    <h5>Telepon</h5>
                    <p>(021) 1234-5678</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-envelope"></i>
                    <h5>Email</h5>
                    <p>ppdb@nuist.id</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100 animate-fade-in-up">
                    <i class="fas fa-map-marker-alt"></i>
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

    // Scroll-triggered animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
            }
        });
    }, observerOptions);

    // Observe all animate-on-scroll elements
    document.querySelectorAll('.animate-on-scroll, .animate-slide-left-on-scroll, .animate-slide-right-on-scroll, .animate-bounce-on-scroll').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endsection
