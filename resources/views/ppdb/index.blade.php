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
    <section class="hero">
        <div class="container">
            <div class="hero-content fade-in-up">
                <div class="hero-badge">🎓 PPDB NUIST 2025</div>
                <h1 class="hero-title">Penerimaan Peserta Didik Baru Modern & Terpercaya</h1>
                <p class="hero-subtitle">Sistem pendaftaran online yang aman, transparan, dan mudah digunakan untuk semua madrasah di bawah naungan NUIST.</p>
                <div class="hero-buttons">
                    <a href="#sekolah" class="btn btn-primary">Pilih Sekolah</a>
                    <a href="{{ url('/') }}" class="btn btn-outline">Kembali ke Beranda</a>
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

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="features-header fade-in-up">
                <h2 class="features-title">Mengapa Memilih PPDB NUIST?</h2>
                <p class="features-subtitle">Platform pendaftaran modern dengan teknologi terkini untuk memastikan pengalaman terbaik bagi siswa dan sekolah.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card fade-in-up">
                    <div class="feature-icon">⚡</div>
                    <h3 class="feature-title">Proses Cepat & Mudah</h3>
                    <p class="feature-description">Daftar dalam hitungan menit dengan interface yang intuitif dan user-friendly.</p>
                </div>

                <div class="feature-card fade-in-up">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Keamanan Data Terjamin</h3>
                    <p class="feature-description">Enkripsi tingkat enterprise dan protokol keamanan untuk melindungi data pribadi Anda.</p>
                </div>

                <div class="feature-card fade-in-up">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Transparansi Penuh</h3>
                    <p class="feature-description">Pantau status pendaftaran secara real-time dengan sistem tracking yang akurat.</p>
                </div>

                <div class="feature-card fade-in-up">
                    <div class="feature-icon">🎯</div>
                    <h3 class="feature-title">Fitur Lengkap</h3>
                    <p class="feature-description">Semua yang Anda butuhkan dalam satu platform: pendaftaran, pembayaran, dan komunikasi.</p>
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
        </div>
    </section>

    <!-- Schools Section -->
    <section id="sekolah" class="schools">
        <div class="container">
            <div class="schools-header fade-in-up">
                <h2 class="schools-title">Pilih Sekolah Impian Anda</h2>
                <p class="schools-subtitle">Temukan madrasah terbaik yang sesuai dengan visi dan misi pendidikan Anda</p>
            </div>

            @if($sekolah->count())
                <div class="schools-grid">
                    @foreach($sekolah as $item)
                        <div class="school-card fade-in-up">
                            <div class="school-image">
                                <span>{{ substr($item->nama_sekolah, 0, 1) }}</span>
                            </div>
                            <div class="school-content">
                                <h3 class="school-title">{{ $item->nama_sekolah }}</h3>
                                <div class="school-meta">
                                    <span>Tahun {{ $item->tahun }}</span>
                                    @php
                                        $isPembukaan = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                                    @endphp
                                    <span class="school-status {{ $isPembukaan ? 'status-open' : 'status-closed' }}">
                                        {{ $isPembukaan ? '🟢 Dibuka' : '🟡 Ditutup' }}
                                    </span>
                                </div>
                                <div class="school-description">
                                    <p><strong>Pendaftaran:</strong> {{ $item->jadwal_buka->format('d M Y') }} - {{ $item->jadwal_tutup->format('d M Y') }}</p>
                                </div>
                                <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="school-btn">Pelajari & Daftar</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center fade-in-up">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">🏫</div>
                    <h3 style="font-size: 1.5rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">Belum Ada Sekolah yang Membuka PPDB</h3>
                    <p style="color: #6b7280;">Silakan kembali lagi nanti untuk melihat daftar sekolah yang tersedia.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container cta-content">
            <h2 class="cta-title">Siap Memulai Pendaftaran?</h2>
            <p class="cta-subtitle">Bergabunglah dengan ribuan siswa yang telah berhasil mendaftar melalui platform PPDB NUIST. Prosesnya mudah dan cepat!</p>
            <div class="cta-buttons">
                <a href="#sekolah" class="btn btn-primary">Mulai Pendaftaran</a>
                <a href="tel:+6281234567890" class="btn btn-outline">Hubungi Support</a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq">
        <div class="container">
            <div class="faq-header fade-in-up">
                <h2 class="faq-title">Pertanyaan yang Sering Diajukan</h2>
                <p class="faq-subtitle">Temukan jawaban cepat untuk pertanyaan umum seputar PPDB NUIST</p>
            </div>
            <div class="faq-grid">
                <div class="faq-item fade-in-up">
                    <h4 class="faq-question">📋 Syarat pendaftaran apa saja?</h4>
                    <p class="faq-answer">Kartu Keluarga (KK), Ijazah/Rapor terakhir, akta kelahiran, foto berwarna, dan dokumen pendukung lainnya sesuai persyaratan sekolah tujuan.</p>
                </div>

                <div class="faq-item fade-in-up">
                    <h4 class="faq-question">💰 Berapa biaya pendaftaran?</h4>
                    <p class="faq-answer">Pendaftaran sepenuhnya GRATIS! PPDB NUIST tidak memungut biaya administrasi apapun untuk proses pendaftaran online.</p>
                </div>

                <div class="faq-item fade-in-up">
                    <h4 class="faq-question">⏰ Kapan batas waktu pendaftaran?</h4>
                    <p class="faq-answer">Setiap sekolah memiliki jadwal pendaftaran yang berbeda. Pastikan untuk memeriksa tanggal pembukaan dan penutupan di halaman detail sekolah.</p>
                </div>

                <div class="faq-item fade-in-up">
                    <h4 class="faq-question">📱 Bagaimana cara mendaftar?</h4>
                    <p class="faq-answer">Pilih sekolah tujuan, lengkapi formulir pendaftaran, upload dokumen yang diperlukan, dan lakukan verifikasi data.</p>
                </div>

                <div class="faq-item fade-in-up">
                    <h4 class="faq-question">🏫 Bisa daftar ke beberapa sekolah?</h4>
                    <p class="faq-answer">Ya, Anda dapat mendaftar ke beberapa sekolah sekaligus. Sistem akan melacak semua pendaftaran Anda.</p>
                </div>

                <div class="faq-item fade-in-up">
                    <h4 class="faq-question">🔒 Apakah data saya aman?</h4>
                    <p class="faq-answer">Ya, kami menggunakan enkripsi tingkat enterprise dan protokol keamanan untuk melindungi semua data pribadi Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section style="padding: 5rem 0; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto; background: white; border-radius: 1rem; padding: 3rem; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);">
                <div style="text-align: center; margin-bottom: 2rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">💡</div>
                    <h3 style="font-size: 1.75rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">Tips Sukses Pendaftaran</h3>
                    <p style="color: #6b7280;">Panduan penting untuk memastikan pendaftaran Anda berjalan lancar</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 1rem;">📋</div>
                        <h4 style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">Persiapkan Dokumen</h4>
                        <p style="color: #6b7280; font-size: 0.875rem;">Pastikan semua dokumen lengkap sebelum mulai mendaftar</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 1rem;">📱</div>
                        <h4 style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">Isi Data dengan Benar</h4>
                        <p style="color: #6b7280; font-size: 0.875rem;">Periksa kembali data yang diisi untuk menghindari kesalahan</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 1rem;">💾</div>
                        <h4 style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">Simpan Nomor Pendaftaran</h4>
                        <p style="color: #6b7280; font-size: 0.875rem;">Catat nomor pendaftaran untuk tracking status</p>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 1rem;">📶</div>
                        <h4 style="font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">Koneksi Stabil</h4>
                        <p style="color: #6b7280; font-size: 0.875rem;">Pastikan internet stabil saat upload dokumen</p>
                    </div>
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
