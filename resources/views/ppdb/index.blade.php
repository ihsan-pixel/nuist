@extends('layouts.master-without-nav')

@section('title', 'PPDB NUIST 2025')

@section('body')
<body class="scroll-smooth bg-gray-50">
@endsection

{{-- Tambahkan CSS di sini --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/ppdb-custom.css') }}" />
@endsection

{{-- Mulai konten halaman --}}
@section('content')

    {{-- Navbar --}}
    @include('partials.ppdb.navbar')

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content fade-in-up">
                <div class="hero-badge">🎓 PPDB NUIST 2025</div>
                <h1 class="hero-title">Sistem Informasi Digital LP. Ma'arif NU PWNU DIY</h1>
                <p class="hero-subtitle">Transformasi Digital Pendidikan untuk Madrasah yang Lebih Maju.</p>
                <div class="hero-buttons">
                    <a href="#tentang" class="btn btn-primary">Mulai Sekarang</a>
                    <a href="#demo" class="btn btn-outline">Lihat Demo</a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Sekolah Terdaftar</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">Siswa Terdaftar</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">99%</span>
                        <span class="stat-label">Kepuasan Pengguna</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Support Online</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-image fade-in-up">
                    <div class="about-image-placeholder">
                        <span>🏫</span>
                    </div>
                </div>
                <div class="about-text fade-in-up">
                    <h2 class="about-title">Tentang Kami</h2>
                    <p class="about-subtitle">LP. Ma'arif NU PWNU DIY berkomitmen untuk memberikan pendidikan berkualitas melalui transformasi digital yang inovatif.</p>
                    <div class="about-features">
                        <div class="about-feature">
                            <div class="about-feature-icon">✅</div>
                            <div class="about-feature-content">
                                <h4>Terintegrasi dengan Dapodik</h4>
                                <p>Sistem yang terhubung langsung dengan database pendidikan nasional untuk kemudahan pengelolaan data.</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <div class="about-feature-icon">✅</div>
                            <div class="about-feature-content">
                                <h4>Layanan Support 24 Jam</h4>
                                <p>Tim teknis profesional siap membantu Anda kapan saja dengan berbagai kanal komunikasi.</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <div class="about-feature-icon">✅</div>
                            <div class="about-feature-content">
                                <h4>Sistem Aman dan Teruji</h4>
                                <p>Enkripsi tingkat enterprise dan protokol keamanan untuk melindungi semua data pendidikan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="features">
        <div class="container">
            <div class="features-header fade-in-up">
                <h2 class="features-title">Fitur Unggulan</h2>
                <p class="features-subtitle">Platform pendaftaran modern dengan teknologi terkini untuk memastikan pengalaman terbaik bagi siswa dan sekolah.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card fade-in-up">
                    <div class="feature-icon">🧩</div>
                    <h3 class="feature-title">Integrasi Dapodik</h3>
                    <p class="feature-description">Sistem terintegrasi dengan database pendidikan nasional untuk kemudahan pengelolaan data siswa.</p>
                </div>

                <div class="feature-card fade-in-up">
                    <div class="feature-icon">⚙️</div>
                    <h3 class="feature-title">Multi Jalur Pendaftaran</h3>
                    <p class="feature-description">Berbagai jalur pendaftaran tersedia: reguler, prestasi, dan afirmasi sesuai kebutuhan.</p>
                </div>

                <div class="feature-card fade-in-up">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Manajemen Hak Akses</h3>
                    <p class="feature-description">Sistem kontrol akses yang ketat untuk melindungi data sensitif dan memastikan keamanan.</p>
                </div>

                <div class="feature-card fade-in-up">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Layanan ASVRI Data Analitik</h3>
                    <p class="feature-description">Analisis data canggih untuk membantu pengambilan keputusan strategis dalam pendidikan.</p>
                </div>

                <div class="feature-card fade-in-up">
                    <div class="feature-icon">📱</div>
                    <h3 class="feature-title">Mobile Responsive</h3>
                    <p class="feature-description">Akses dari mana saja dengan desain yang optimal untuk semua perangkat.</p>
                </div>

                <div class="feature-card fade-in-up">
                    <div class="feature-icon">💬</div>
                    <h3 class="feature-title">Support 24/7</h3>
                    <p class="feature-description">Tim support siap membantu Anda kapan saja dengan berbagai kanal komunikasi.</p>
                </div>
            </div>
            <div class="features-cta fade-in-up">
                <a href="#galeri" class="btn btn-primary">Lihat Semua Fitur</a>
            </div>
        </div>
    </section>

    <!-- Galeri Section -->
    <section id="galeri" class="galeri">
        <div class="container">
            <div class="galeri-header fade-in-up">
                <h2 class="galeri-title">Galeri & Tampilan Demo</h2>
                <p class="galeri-subtitle">Temukan berbagai fitur dan hasil visual dari sistem PPDB NUIST yang telah membantu ribuan siswa</p>
            </div>

            <div class="galeri-grid">
                <div class="galeri-item fade-in-up">
                    <div class="galeri-image">
                        <span>📊</span>
                    </div>
                    <div class="galeri-overlay">
                        <h4>Dashboard Admin</h4>
                        <p>Interface modern untuk pengelolaan data siswa</p>
                    </div>
                </div>

                <div class="galeri-item fade-in-up">
                    <div class="galeri-image">
                        <span>📱</span>
                    </div>
                    <div class="galeri-overlay">
                        <h4>Mobile Responsive</h4>
                        <p>Akses mudah dari smartphone dan tablet</p>
                    </div>
                </div>

                <div class="galeri-item fade-in-up">
                    <div class="galeri-image">
                        <span>📋</span>
                    </div>
                    <div class="galeri-overlay">
                        <h4>Form Pendaftaran</h4>
                        <p>Proses pendaftaran yang intuitif dan cepat</p>
                    </div>
                </div>

                <div class="galeri-item fade-in-up">
                    <div class="galeri-image">
                        <span>📈</span>
                    </div>
                    <div class="galeri-overlay">
                        <h4>Analitik Data</h4>
                        <p>Laporan dan statistik real-time</p>
                    </div>
                </div>

                <div class="galeri-item fade-in-up">
                    <div class="galeri-image">
                        <span>🔒</span>
                    </div>
                    <div class="galeri-overlay">
                        <h4>Keamanan Data</h4>
                        <p>Enkripsi tingkat enterprise</p>
                    </div>
                </div>

                <div class="galeri-item fade-in-up">
                    <div class="galeri-image">
                        <span>💬</span>
                    </div>
                    <div class="galeri-overlay">
                        <h4>Support Center</h4>
                        <p>Bantuan 24/7 untuk semua pengguna</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Section -->
    <section id="berita" class="berita">
        <div class="container">
            <div class="berita-header fade-in-up">
                <h2 class="berita-title">Berita & Informasi Terbaru</h2>
                <p class="berita-subtitle">Update terkini seputar PPDB NUIST dan dunia pendidikan</p>
            </div>

            <div class="berita-grid">
                <div class="berita-card fade-in-up">
                    <div class="berita-image">
                        <span>📢</span>
                    </div>
                    <div class="berita-content">
                        <div class="berita-meta">
                            <span class="berita-date">15 Jan 2025</span>
                            <span class="berita-category">Pengumuman</span>
                        </div>
                        <h3 class="berita-title-card">PPDB NUIST 2025 Resmi Dibuka</h3>
                        <p class="berita-excerpt">Penerimaan Peserta Didik Baru tahun ajaran 2025/2026 telah resmi dibuka untuk semua madrasah di bawah naungan LP. Ma'arif NU PWNU DIY.</p>
                        <a href="#" class="berita-link">Baca Selengkapnya →</a>
                    </div>
                </div>

                <div class="berita-card fade-in-up">
                    <div class="berita-image">
                        <span>🎓</span>
                    </div>
                    <div class="berita-content">
                        <div class="berita-meta">
                            <span class="berita-date">12 Jan 2025</span>
                            <span class="berita-category">Berita</span>
                        </div>
                        <h3 class="berita-title-card">Transformasi Digital Pendidikan</h3>
                        <p class="berita-excerpt">LP. Ma'arif NU PWNU DIY terus berkomitmen untuk memberikan pendidikan berkualitas melalui inovasi teknologi digital.</p>
                        <a href="#" class="berita-link">Baca Selengkapnya →</a>
                    </div>
                </div>

                <div class="berita-card fade-in-up">
                    <div class="berita-image">
                        <span>🏆</span>
                    </div>
                    <div class="berita-content">
                        <div class="berita-meta">
                            <span class="berita-date">10 Jan 2025</span>
                            <span class="berita-category">Prestasi</span>
                        </div>
                        <h3 class="berita-title-card">Madrasah Unggulan NUIST Raih Penghargaan</h3>
                        <p class="berita-excerpt">Beberapa madrasah binaan NUIST berhasil meraih penghargaan nasional dalam bidang pendidikan dan inovasi teknologi.</p>
                        <a href="#" class="berita-link">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>

            <div class="berita-cta fade-in-up">
                <a href="#" class="btn btn-outline">Lihat Semua Berita</a>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="kontak">
        <div class="container">
            <div class="kontak-content">
                <div class="kontak-info fade-in-up">
                    <h2 class="kontak-title">Hubungi Kami</h2>
                    <p class="kontak-subtitle">Kami siap membantu Anda dengan segala pertanyaan seputar PPDB NUIST</p>

                    <div class="kontak-details">
                        <div class="kontak-item">
                            <div class="kontak-icon">📧</div>
                            <div class="kontak-text">
                                <h4>Email</h4>
                                <p>ppdb@nuist.id</p>
                            </div>
                        </div>

                        <div class="kontak-item">
                            <div class="kontak-icon">📞</div>
                            <div class="kontak-text">
                                <h4>Telepon</h4>
                                <p>+62 812 3456 7890</p>
                            </div>
                        </div>

                        <div class="kontak-item">
                            <div class="kontak-icon">💬</div>
                            <div class="kontak-text">
                                <h4>WhatsApp</h4>
                                <p>+62 812 3456 7890</p>
                            </div>
                        </div>

                        <div class="kontak-item">
                            <div class="kontak-icon">📍</div>
                            <div class="kontak-text">
                                <h4>Alamat</h4>
                                <p>LP. Ma'arif NU PWNU DIY<br>Yogyakarta, Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kontak-form fade-in-up">
                    <form class="contact-form">
                        <div class="form-group">
                            <input type="text" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Subjek" required>
                        </div>
                        <div class="form-group">
                            <textarea placeholder="Pesan Anda" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

@include('partials.ppdb.footer')

@section('script-bottom')
    <!-- Play template scripts -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        if (typeof WOW !== 'undefined') { new WOW().init(); }
    </script>
@endsection

@endsection
