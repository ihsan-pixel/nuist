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

    <!-- ====== Hero Section Start ====== -->
    <div id="home" class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">PPDB NUIST 2025</h1>
                <p class="hero-subtitle">
                    Penerimaan Peserta Didik Baru — Mudah, Cepat, dan Aman
                </p>
                <div class="hero-buttons">
                    <a href="#sekolah" class="hero-btn-primary">Pilih Sekolah</a>
                    <a href="{{ url('/') }}" class="hero-btn-secondary">Kembali ke Beranda</a>
                </div>
            </div>
            <div class="hero-image-container">
                <img src="{{ asset('assets/images/hero/hero-image.jpg') }}" alt="hero" class="hero-image" />
            </div>
            <div class="hero-bottom-gradient"></div>
        </div>
    </div>
    <!-- ====== Hero Section End ====== -->

<!-- ====== Features Section Start ====== -->
<section class="features-section">
    <div class="container">
        <div class="features-header">
            <span class="features-subtitle">Keunggulan PPDB NUIST</span>
            <h2 class="features-title">Mengapa Memilih Kami?</h2>
            <p class="features-description">Sistem pendaftaran online yang modern, aman, dan transparan untuk semua madrasah di bawah naungan NUIST.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon feature-icon-primary">
                    <svg class="feature-icon-text" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <h4 class="feature-title">Pendaftaran Mudah</h4>
                <p class="feature-description">Proses pendaftaran yang cepat dan sederhana dalam hitungan menit.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon feature-icon-green">
                    <svg class="feature-icon-text" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                </div>
                <h4 class="feature-title">Data Terjamin Aman</h4>
                <p class="feature-description">Keamanan data terjamin dengan enkripsi tingkat tinggi.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon feature-icon-purple">
                    <svg class="feature-icon-text" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                </div>
                <h4 class="feature-title">Hasil Cepat</h4>
                <p class="feature-description">Pengumuman hasil seleksi transparan dan tepat waktu.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon feature-icon-orange">
                    <svg class="feature-icon-text" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="feature-title">Fitur Lengkap</h4>
                <p class="feature-description">Semua fitur penting untuk madrasah dan calon siswa.</p>
            </div>
        </div>
    </div>
</section>
<!-- ====== Features Section End ====== -->

<!-- ====== Sekolah (dynamic) ====== -->
<section id="sekolah" class="schools-section">
    <div class="container">
        <div class="schools-header">
            <h2 class="schools-title">Pilih Sekolah/Madrasah</h2>
            <p class="schools-subtitle">Temukan madrasah impian Anda dari daftar sekolah yang membuka PPDB NUIST 2025</p>
        </div>

        @if($sekolah->count())
            <div class="schools-grid">
                @foreach($sekolah as $item)
                    <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="school-card">
                        <div class="school-content">
                            <div class="school-header">
                                <h3 class="school-title">{{ $item->nama_sekolah }}</h3>
                                <span class="school-year">Tahun {{ $item->tahun }}</span>
                            </div>
                            @php
                                $isPembukaan = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                            @endphp
                            <div class="school-status">
                                <div class="{{ $isPembukaan ? 'status-open' : 'status-closed' }}">
                                    {{ $isPembukaan ? '🟢 Pendaftaran Dibuka' : '🟡 Menunggu Dibuka' }}
                                </div>
                            </div>
                            <div class="school-details">
                                <div class="detail-item">
                                    <span class="detail-label">📅</span>
                                    <span>Buka: <strong>{{ $item->jadwal_buka->format('d M Y') }}</strong></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">⏱️</span>
                                    <span>Tutup: <strong>{{ $item->jadwal_tutup->format('d M Y') }}</strong></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="school-btn">Pelajari & Daftar →</button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="no-schools">
                <div class="no-schools-icon">🏫</div>
                <h3 class="no-schools-title">Belum Ada Sekolah yang Membuka PPDB</h3>
                <p class="no-schools-text">Silakan kembali lagi nanti untuk melihat daftar sekolah yang tersedia.</p>
            </div>
        @endif
    </div>
</section>
<!-- ====== Sekolah End ====== -->

<!-- ====== CTA Section ====== -->
<section class="cta-section">
    <div class="cta-overlay"></div>
    <div class="container cta-content">
        <h3 class="cta-title">Butuh Bantuan?</h3>
        <p class="cta-subtitle">Tim support PPDB NUIST siap membantu Anda dengan segala pertanyaan. Jangan ragu untuk menghubungi kami!</p>
        <div class="cta-buttons">
            <a href="tel:+6281234567890" class="cta-btn cta-btn-primary">
                <span class="cta-btn-icon">📞</span> Hubungi Kami
            </a>
            <a href="mailto:ppdb@nuist.id" class="cta-btn cta-btn-secondary">
                <span class="cta-btn-icon">📧</span> Email Support
            </a>
            <a href="https://wa.me/+6281234567890" target="_blank" class="cta-btn cta-btn-whatsapp">
                <span class="cta-btn-icon">💬</span> WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- ====== FAQ Section ====== -->
<section class="faq-section">
    <div class="container">
        <div class="faq-header">
            <h2 class="faq-title">Pertanyaan Umum</h2>
            <p class="faq-subtitle">Temukan jawaban untuk pertanyaan yang sering ditanyakan tentang PPDB NUIST</p>
        </div>
        <div class="faq-grid">
            <div class="faq-card">
                <div class="faq-content">
                    <div class="faq-icon faq-icon-primary">
                        <span class="faq-icon-text">?</span>
                    </div>
                    <div>
                        <h4 class="faq-question">Syarat pendaftaran apa saja?</h4>
                        <p class="faq-answer">Lengkapi dokumen seperti Kartu Keluarga (KK), Ijazah, rapor, dan dokumen pendukung lainnya sesuai ketentuan masing-masing madrasah.</p>
                    </div>
                </div>
            </div>
            <div class="faq-card">
                <div class="faq-content">
                    <div class="faq-icon faq-icon-green">
                        <span class="faq-icon-text">💰</span>
                    </div>
                    <div>
                        <h4 class="faq-question">Berapa biaya pendaftaran?</h4>
                        <p class="faq-answer">Pendaftaran online PPDB NUIST sepenuhnya GRATIS. Tidak ada biaya administrasi apapun.</p>
                    </div>
                </div>
            </div>
            <div class="faq-card">
                <div class="faq-content">
                    <div class="faq-icon faq-icon-blue">
                        <span class="faq-icon-text">📢</span>
                    </div>
                    <div>
                        <h4 class="faq-question">Kapan hasil pengumuman?</h4>
                        <p class="faq-answer">Hasil seleksi akan diumumkan sesuai dengan jadwal yang telah ditentukan oleh masing-masing madrasah.</p>
                    </div>
                </div>
            </div>
            <div class="faq-card">
                <div class="faq-content">
                    <div class="faq-icon faq-icon-purple">
                        <span class="faq-icon-text">🏫</span>
                    </div>
                    <div>
                        <h4 class="faq-question">Bisa daftar di multiple sekolah?</h4>
                        <p class="faq-answer">Ya, calon peserta didik dapat mendaftar ke beberapa sekolah sekaligus sesuai keinginan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Info -->
<section class="info-section">
    <div class="container">
        <div class="info-container">
            <div class="info-card">
                <div class="info-header">
                    <div class="info-icon">
                        <span class="info-icon-text">ℹ️</span>
                    </div>
                    <div>
                        <h3 class="info-title">Informasi Penting</h3>
                        <ul class="info-list">
                            <li class="info-item">
                                <span class="info-bullet"></span>
                                <span>Pastikan semua dokumen Anda sudah lengkap sebelum mulai mendaftar</span>
                            </li>
                            <li class="info-item">
                                <span class="info-bullet"></span>
                                <span>Isi data dengan benar dan lengkap untuk menghindari kesalahan</span>
                            </li>
                            <li class="info-item">
                                <span class="info-bullet"></span>
                                <span>Simpan nomor pendaftaran Anda untuk melacak status pendaftaran</span>
                            </li>
                            <li class="info-item">
                                <span class="info-bullet"></span>
                                <span>Pastikan koneksi internet stabil saat mengupload dokumen</span>
                            </li>
                            <li class="info-item">
                                <span class="info-bullet"></span>
                                <span>Maksimal ukuran file dokumen adalah 2MB per file</span>
                            </li>
                        </ul>
                    </div>
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
