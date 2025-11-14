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
</style>
@endsection

@section('content')
@include('partials.ppdb.navbar')

<section class="hero-section">

    <!-- Flayer di atas -->
    <div class="container hero-flayer">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/flayer1.png') }}" class="img-fluid rounded" alt="Flayer PPDB">
            </div>
        </div>
    </div>

    <!-- Bagian Text + Image Hero -->
    <div class="container">
        <div class="row align-items-center">

            <!-- Text Kiri -->
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Transformasi Sistem PPDB <span style="color: #efaa0c;">Online</span></h1>
                <p class="lead">Sistem Penerimaan Peserta Didik Baru Sekolah/Madrasah </p>
                <p class="lead"> Bawah Naungan LP. Ma'arif NU PWNU D.I. Yogyakarta</p>
                <p class="lead fw-bold">Tahun Pelajaran 2026/2027</p>
                <a href="#sekolah" class="btn btn-orange btn-lg me-3">Lihat Sekolah</a>
            </div>

            <!-- Image Kanan -->
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/hero1.png') }}" alt="PPDB Image" class="img-fluid rounded">
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
            <div class="col-lg-6">
                <img src="{{ asset('images/flayer4.png') }}" alt="About PPDB" class="img-fluid rounded shadow" style="max-width: 120%; height: auto; transform: scale(1.2);">
            </div>
            <div class="col-lg-6">
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

<!-- Contact Section -->
<section id="kontak" class="contact-section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold" style="color: #004b4c;">Hubungi Kami</h2>
                <p class="lead" style="color: #666;">Butuh bantuan? Tim kami siap membantu Anda</p>
            </div>
        </div>

        {{-- <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100">
                    <i class="bi bi-telephone-fill"></i>
                    <h5>Telepon</h5>
                    <p>(021) 1234-5678</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100">
                    <i class="bi bi-envelope-fill"></i>
                    <h5>Email</h5>
                    <p>ppdb@nuist.id</p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="contact-card h-100">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h5>Alamat</h5>
                    <p>Jl. KH. Wahid Hasyim No. 123<br>Jakarta Pusat, 10250</p>
                </div>
            </div> --}}
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
