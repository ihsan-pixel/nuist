@extends('layouts.master-without-nav')

@section('title', 'PPDB NUIST 2025')

@section('body')
<body class="scroll-smooth bg-gray-50">
@endsection

{{-- Tambahkan CSS di sini --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('src/css/tailwind.css') }}" />
@endsection

{{-- Mulai konten halaman --}}
@section('content')

    {{-- Navbar --}}
    @include('partials.ppdb.navbar')

    <!-- ====== Hero Section Start ====== -->
    <div id="home" class="relative overflow-hidden bg-gradient-to-br from-primary via-blue-600 to-green-500 pt-[120px] md:pt-[130px] lg:pt-[160px] min-h-screen flex items-center">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center text-white animate__animated animate__fadeInUp">
                <h1 class="text-4xl md:text-6xl font-extrabold mb-6 drop-shadow-lg">PPDB NUIST 2025</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-[700px] mx-auto opacity-90">
                    Penerimaan Peserta Didik Baru — Mudah, Cepat, dan Aman
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#sekolah" class="bg-white text-primary px-8 py-4 rounded-full font-semibold shadow-lg hover:bg-gray-100 transition transform hover:scale-105">Pilih Sekolah</a>
                    <a href="{{ url('/') }}" class="bg-white/20 text-white px-8 py-4 rounded-full font-semibold border border-white hover:bg-white hover:text-primary transition transform hover:scale-105">Kembali ke Beranda</a>
                </div>
            </div>
            <div class="mt-16 flex justify-center">
                <img src="{{ asset('assets/images/hero/hero-image.jpg') }}" alt="hero" class="max-w-full mx-auto rounded-2xl shadow-2xl animate__animated animate__zoomIn" />
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-gray-50 to-transparent"></div>
    </div>
    <!-- ====== Hero Section End ====== -->

<!-- ====== Features Section Start ====== -->
<section class="pb-16 pt-20 bg-white lg:pb-[100px] lg:pt-[120px]">
    <div class="container px-4 mx-auto">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4">
                <div class="mx-auto mb-16 max-w-[600px] text-center">
                    <span class="block mb-4 text-lg font-semibold text-primary">Keunggulan PPDB NUIST</span>
                    <h2 class="mb-4 text-3xl font-bold text-gray-900 sm:text-4xl">Mengapa Memilih Kami?</h2>
                    <p class="text-lg text-gray-600">Sistem pendaftaran online yang modern, aman, dan transparan untuk semua madrasah di bawah naungan NUIST.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center group hover:scale-105 transition transform duration-300">
                <div class="relative z-10 mb-8 flex h-[80px] w-[80px] items-center justify-center rounded-full bg-gradient-to-br from-primary to-blue-600 mx-auto shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <h4 class="mb-4 text-xl font-bold text-gray-900">Pendaftaran Mudah</h4>
                <p class="text-gray-600">Proses pendaftaran yang cepat dan sederhana dalam hitungan menit.</p>
            </div>

            <div class="text-center group hover:scale-105 transition transform duration-300">
                <div class="relative z-10 mb-8 flex h-[80px] w-[80px] items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-teal-600 mx-auto shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                </div>
                <h4 class="mb-4 text-xl font-bold text-gray-900">Data Terjamin Aman</h4>
                <p class="text-gray-600">Keamanan data terjamin dengan enkripsi tingkat tinggi.</p>
            </div>

            <div class="text-center group hover:scale-105 transition transform duration-300">
                <div class="relative z-10 mb-8 flex h-[80px] w-[80px] items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-pink-600 mx-auto shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                </div>
                <h4 class="mb-4 text-xl font-bold text-gray-900">Hasil Cepat</h4>
                <p class="text-gray-600">Pengumuman hasil seleksi transparan dan tepat waktu.</p>
            </div>

            <div class="text-center group hover:scale-105 transition transform duration-300">
                <div class="relative z-10 mb-8 flex h-[80px] w-[80px] items-center justify-center rounded-full bg-gradient-to-br from-orange-500 to-red-600 mx-auto shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="mb-4 text-xl font-bold text-gray-900">Fitur Lengkap</h4>
                <p class="text-gray-600">Semua fitur penting untuk madrasah dan calon siswa.</p>
            </div>
        </div>
    </div>
</section>
<!-- ====== Features Section End ====== -->

<!-- ====== Sekolah (dynamic) ====== -->
<section id="sekolah" class="py-16 bg-gradient-to-br from-gray-50 to-white">
    <div class="container px-4 mx-auto">
        <div class="mb-12 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Pilih Sekolah/Madrasah</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Temukan madrasah impian Anda dari daftar sekolah yang membuka PPDB NUIST 2025</p>
        </div>

        @if($sekolah->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($sekolah as $item)
                    <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="block bg-white rounded-2xl shadow-lg hover:shadow-2xl overflow-hidden transform hover:-translate-y-2 transition-all duration-300 group">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary transition">{{ $item->nama_sekolah }}</h3>
                                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Tahun {{ $item->tahun }}</span>
                            </div>
                            @php
                                $isPembukaan = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                            @endphp
                            <div class="mb-6">
                                <div class="inline-block px-4 py-2 rounded-full text-sm font-semibold {{ $isPembukaan ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $isPembukaan ? '🟢 Pendaftaran Dibuka' : '🟡 Menunggu Dibuka' }}
                                </div>
                            </div>
                            <div class="space-y-2 text-sm text-gray-600 mb-6">
                                <div class="flex items-center">
                                    <span class="mr-2">📅</span>
                                    <span>Buka: <strong>{{ $item->jadwal_buka->format('d M Y') }}</strong></span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-2">⏱️</span>
                                    <span>Tutup: <strong>{{ $item->jadwal_tutup->format('d M Y') }}</strong></span>
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="w-full bg-gradient-to-r from-primary to-blue-600 text-white hover:from-blue-600 hover:to-primary rounded-xl py-3 font-semibold shadow-lg transform hover:scale-105 transition-all duration-300">
                                    Pelajari & Daftar →
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center bg-white rounded-2xl p-12 shadow-lg">
                <div class="text-6xl mb-4">🏫</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Sekolah yang Membuka PPDB</h3>
                <p class="text-gray-600">Silakan kembali lagi nanti untuk melihat daftar sekolah yang tersedia.</p>
            </div>
        @endif
    </div>
</section>
<!-- ====== Sekolah End ====== -->

<!-- ====== CTA Section ====== -->
<section class="relative z-10 overflow-hidden bg-gradient-to-r from-primary via-blue-600 to-purple-600 py-20 lg:py-[120px]">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="container px-4 mx-auto text-center relative z-10">
        <h3 class="text-4xl font-bold text-white mb-6">Butuh Bantuan?</h3>
        <p class="text-white/90 text-xl max-w-3xl mx-auto mb-8">Tim support PPDB NUIST siap membantu Anda dengan segala pertanyaan. Jangan ragu untuk menghubungi kami!</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="tel:+6281234567890" class="inline-flex items-center bg-white text-primary px-8 py-4 rounded-full font-semibold shadow-lg hover:bg-gray-100 transition transform hover:scale-105">
                <span class="mr-2">📞</span> Hubungi Kami
            </a>
            <a href="mailto:ppdb@nuist.id" class="inline-flex items-center bg-white/20 text-white px-8 py-4 rounded-full font-semibold border border-white hover:bg-white hover:text-primary transition transform hover:scale-105">
                <span class="mr-2">📧</span> Email Support
            </a>
            <a href="https://wa.me/+6281234567890" target="_blank" class="inline-flex items-center bg-green-500 text-white px-8 py-4 rounded-full font-semibold hover:bg-green-600 transition transform hover:scale-105">
                <span class="mr-2">💬</span> WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- ====== FAQ Section ====== -->
<section class="py-16 bg-gradient-to-br from-white to-gray-50">
    <div class="container px-4 mx-auto">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Pertanyaan Umum</h2>
            <p class="text-lg text-gray-600">Temukan jawaban untuk pertanyaan yang sering ditanyakan tentang PPDB NUIST</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-lg">?</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-3">Syarat pendaftaran apa saja?</h4>
                        <p class="text-gray-600">Lengkapi dokumen seperti Kartu Keluarga (KK), Ijazah, rapor, dan dokumen pendukung lainnya sesuai ketentuan masing-masing madrasah.</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-lg">💰</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-3">Berapa biaya pendaftaran?</h4>
                        <p class="text-gray-600">Pendaftaran online PPDB NUIST sepenuhnya GRATIS. Tidak ada biaya administrasi apapun.</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-lg">📢</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-3">Kapan hasil pengumuman?</h4>
                        <p class="text-gray-600">Hasil seleksi akan diumumkan sesuai dengan jadwal yang telah ditentukan oleh masing-masing madrasah.</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-lg">🏫</span>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-3">Bisa daftar di multiple sekolah?</h4>
                        <p class="text-gray-600">Ya, calon peserta didik dapat mendaftar ke beberapa sekolah sekaligus sesuai keinginan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Info -->
<section class="py-12 bg-white">
    <div class="container px-4 mx-auto">
        <div class="max-w-4xl mx-auto">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-2xl border-l-4 border-primary">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-16 h-16 bg-primary rounded-full flex items-center justify-center mr-6">
                        <span class="text-white text-2xl">ℹ️</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Informasi Penting</h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <span class="inline-block w-2 h-2 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <span>Pastikan semua dokumen Anda sudah lengkap sebelum mulai mendaftar</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block w-2 h-2 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <span>Isi data dengan benar dan lengkap untuk menghindari kesalahan</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block w-2 h-2 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <span>Simpan nomor pendaftaran Anda untuk melacak status pendaftaran</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block w-2 h-2 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                <span>Pastikan koneksi internet stabil saat mengupload dokumen</span>
                            </li>
                            <li class="flex items-start">
                                <span class="inline-block w-2 h-2 bg-primary rounded-full mt-2 mr-3 flex-shrink-0"></span>
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
