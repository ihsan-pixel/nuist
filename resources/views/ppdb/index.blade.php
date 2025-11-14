@extends('layouts.master-without-nav')

@section('title', 'PPDB NUIST - Penerimaan Peserta Didik Baru')

@section('css')
<link rel="stylesheet" href="{{ asset('css/ppdb-custom.css') }}">
<style>
    .hero-section {
        background: url('{{ asset("images/bg_ppdb1.png") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: #0f854a;
        min-height: 95vh;
        display: flex;
        align-items: center;
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
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .school-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: bold;
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')
@include('partials.ppdb.navbar')

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-left">
            <div class="col-lg-6 text-left">
                <h1 class="display-4 fw-bold mb-4">PPDB NUIST 2025</h1>
                <p class="lead mb-4">Penerimaan Peserta Didik Baru Madrasah Tahun Pelajaran 2025/2026</p>
                <a href="#sekolah" class="btn btn-light btn-lg me-3">Lihat Sekolah</a>
                <a href="{{ route('ppdb.daftar', 'demo') }}" class="btn btn-outline-light btn-lg">Daftar Sekarang</a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/bg_ppdb1.png') }}" alt="PPDB Image" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Daftar Sekolah -->
<section id="sekolah" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">Sekolah Terdaftar</h2>
                <p class="lead text-muted">Pilih madrasah impian Anda untuk melanjutkan pendidikan</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Contoh Card Sekolah -->
            <div class="col-lg-4 col-md-6">
                <div class="card school-card h-100">
                    <div class="position-relative">
                        <img src="{{ asset('images/madrasah-1.jpg') }}" class="card-img-top" alt="Madrasah 1" style="height: 200px; object-fit: cover;">
                        <span class="badge bg-success status-badge">Buka</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Madrasah Ibtidaiyah Al-Huda</h5>
                        <p class="card-text text-muted">Jl. Pendidikan No. 123, Jakarta</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Kuota: 50 siswa</small>
                            <a href="{{ route('ppdb.sekolah', 'al-huda') }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card school-card h-100">
                    <div class="position-relative">
                        <img src="{{ asset('images/madrasah-2.jpg') }}" class="card-img-top" alt="Madrasah 2" style="height: 200px; object-fit: cover;">
                        <span class="badge bg-warning status-badge">Segera</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Madrasah Tsanawiyah Al-Falah</h5>
                        <p class="card-text text-muted">Jl. Kemajuan No. 456, Bandung</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Kuota: 75 siswa</small>
                            <a href="{{ route('ppdb.sekolah', 'al-falah') }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card school-card h-100">
                    <div class="position-relative">
                        <img src="{{ asset('images/madrasah-3.jpg') }}" class="card-img-top" alt="Madrasah 3" style="height: 200px; object-fit: cover;">
                        <span class="badge bg-danger status-badge">Tutup</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Madrasah Aliyah Al-Amien</h5>
                        <p class="card-text text-muted">Jl. Ilmu No. 789, Surabaya</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Kuota: 100 siswa</small>
                            <a href="{{ route('ppdb.sekolah', 'al-amien') }}" class="btn btn-secondary btn-sm disabled">Pendaftaran Ditutup</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold mb-4">Tentang PPDB NUIST</h2>
                <p class="lead mb-4">PPDB NUIST adalah sistem penerimaan peserta didik baru yang modern dan transparan untuk madrasah di bawah naungan Nahdlatul Ulama.</p>
                <ul class="list-unstyled">
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Proses pendaftaran online yang mudah</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Transparansi dalam seleksi dan pengumuman</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Dukungan teknis 24/7</li>
                    <li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Sistem verifikasi data yang akurat</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('images/about-ppdb.png') }}" alt="About PPDB" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="kontak" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">Hubungi Kami</h2>
                <p class="lead text-muted">Butuh bantuan? Tim kami siap membantu Anda</p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 text-center">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-telephone-fill text-primary fs-1 mb-3"></i>
                        <h5 class="card-title">Telepon</h5>
                        <p class="card-text">(021) 1234-5678</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-center">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-envelope-fill text-primary fs-1 mb-3"></i>
                        <h5 class="card-title">Email</h5>
                        <p class="card-text">ppdb@nuist.id</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-center">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bi bi-geo-alt-fill text-primary fs-1 mb-3"></i>
                        <h5 class="card-title">Alamat</h5>
                        <p class="card-text">Jl. KH. Wahid Hasyim No. 123<br>Jakarta Pusat, 10250</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.ppdb.footer')
@endsection

@section('scripts')
<script>
    // Smooth scrolling untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
</script>
@endsection
