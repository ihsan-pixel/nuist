@extends('layouts.master-without-nav')

@section('title') PPDB NUIST 2025 @endsection

@section('body')

    <body class="scroll-smooth">

@endsection

@section('content')

@section('css')
        <!-- Play Tailwind template CSS (from index.html) -->
        <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
        <link rel="stylesheet" href="{{ asset('src/css/tailwind.css') }}" />
@endsection

@include('partials.ppdb.navbar')

<!-- ====== Hero Section Start ====== -->
<div id="home" class="relative overflow-hidden bg-primary pt-[120px] md:pt-[130px] lg:pt-[160px]">
    <div class="container px-4 mx-auto">
        <div class="flex flex-wrap items-center -mx-4">
            <div class="w-full px-4">
                <div class="hero-content wow fadeInUp mx-auto max-w-[780px] text-center" data-wow-delay=".2s">
                    <h1 class="mb-6 text-3xl font-bold leading-snug text-white sm:text-4xl lg:text-5xl">PPDB NUIST 2025 — Penerimaan Peserta Didik Baru</h1>
                    <p class="mx-auto mb-9 max-w-[600px] text-base font-medium text-white sm:text-lg">Selamat datang di portal pendaftaran online NUIST. Pilih madrasah dan daftar sekarang.</p>
                    <ul class="flex flex-wrap items-center justify-center gap-5 mb-10">
                        <li>
                            <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-md bg-white px-7 py-[14px] text-base font-medium text-dark shadow-1 hover:bg-gray-2">Kembali ke Beranda</a>
                        </li>
                        <li>
                            <a href="https://github.com/tailgrids/play-tailwind" target="_blank" class="flex items-center gap-4 rounded-md bg-white/[0.12] px-6 py-[14px] text-base font-medium text-white hover:bg-white hover:text-dark">Star Template</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="w-full px-4">
                <div class="wow fadeInUp relative z-10 mx-auto max-w-[845px]" data-wow-delay=".25s">
                    <div class="mt-16">
                        <img src="{{ asset('assets/images/hero/hero-image.jpg') }}" alt="hero" class="max-w-full mx-auto rounded-t-xl rounded-tr-xl" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ====== Hero Section End ====== -->

<!-- ====== Features Section Start ====== -->
<section class="pb-8 pt-20 dark:bg-dark lg:pb-[70px] lg:pt-[120px]">
    <div class="container px-4 mx-auto">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4">
                <div class="mx-auto mb-12 max-w-[485px] text-center lg:mb-[70px]">
                    <span class="block mb-2 text-lg font-semibold text-primary">Fitur</span>
                    <h2 class="mb-3 text-3xl font-bold text-dark sm:text-4xl">Mengapa memilih PPDB NUIST</h2>
                    <p class="text-base text-body-color">Sistem pendaftaran online yang cepat, aman, dan transparan untuk semua madrasah.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4 md:w-1/2 lg:w-1/4">
                <div class="mb-12 wow fadeInUp group" data-wow-delay=".1s">
                    <div class="relative z-10 mb-10 flex h-[70px] w-[70px] items-center justify-center rounded-[14px] bg-primary">
                        <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M30.5801 8.30514H27.9926..." fill="white"/></svg>
                    </div>
                    <h4 class="mb-3 text-xl font-bold text-dark">Pendaftaran Mudah</h4>
                    <p class="mb-8 text-body-color">Proses pendaftaran yang cepat dan sederhana.</p>
                </div>
            </div>

            <div class="w-full px-4 md:w-1/2 lg:w-1/4">
                <div class="mb-12 wow fadeInUp group" data-wow-delay=".15s">
                    <div class="relative z-10 mb-10 flex h-[70px] w-[70px] items-center justify-center rounded-[14px] bg-primary">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M30.5998 1.01245H5.39981..." fill="white"/></svg>
                    </div>
                    <h4 class="mb-3 text-xl font-bold text-dark">Data Aman</h4>
                    <p class="mb-8 text-body-color">Keamanan data terjamin dengan standar enkripsi.</p>
                </div>
            </div>

            <div class="w-full px-4 md:w-1/2 lg:w-1/4">
                <div class="mb-12 wow fadeInUp group" data-wow-delay=".2s">
                    <div class="relative z-10 mb-10 flex h-[70px] w-[70px] items-center justify-center rounded-[14px] bg-primary">
                        <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M33.5613 21.4677L31.3675..." fill="white"/></svg>
                    </div>
                    <h4 class="mb-3 text-xl font-bold text-dark">Hasil Cepat</h4>
                    <p class="mb-8 text-body-color">Pengumuman seleksi transparan dan tepat waktu.</p>
                </div>
            </div>

            <div class="w-full px-4 md:w-1/2 lg:w-1/4">
                <div class="mb-12 wow fadeInUp group" data-wow-delay=".25s">
                    <div class="relative z-10 mb-10 flex h-[70px] w-[70px] items-center justify-center rounded-[14px] bg-primary">
                        <svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.355 2.0614H5.21129..." fill="white"/></svg>
                    </div>
                    <h4 class="mb-3 text-xl font-bold text-dark">Semua Elemen Penting</h4>
                    <p class="mb-8 text-body-color">Fitur lengkap untuk kebutuhan madrasah dan pendaftar.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ====== Features Section End ====== -->

<!-- ====== Sekolah (dynamic) ====== -->
<section class="py-12 bg-gray-50">
    <div class="container px-4 mx-auto">
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold">Pilih Sekolah/Madrasah</h2>
            <p class="text-gray-600">Daftar Madrasah/Sekolah yang membuka PPDB NUIST 2025</p>
        </div>

        @if($sekolah->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sekolah as $item)
                    <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="block bg-white rounded-lg shadow hover:shadow-lg overflow-hidden">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold">{{ $item->nama_sekolah }}</h3>
                                <span class="text-sm text-gray-500">Tahun {{ $item->tahun }}</span>
                            </div>
                            @php
                                $isPembukaan = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                            @endphp
                            <div class="mt-4">
                                <div class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $isPembukaan ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ $isPembukaan ? 'Pendaftaran Dibuka' : 'Menunggu Dibuka' }}</div>
                            </div>
                            <div class="mt-4 text-sm text-gray-600">
                                <div>📅 Buka: <strong>{{ $item->jadwal_buka->format('d M Y') }}</strong></div>
                                <div>⏱️ Tutup: <strong>{{ $item->jadwal_tutup->format('d M Y') }}</strong></div>
                            </div>
                            <div class="mt-6">
                                <button class="w-full text-white bg-primary hover:bg-green-700 rounded-md py-2 font-semibold">Pelajari & Daftar →</button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center bg-white rounded-lg p-8 shadow">Tidak ada sekolah yang membuka PPDB saat ini.</div>
        @endif
    </div>
</section>
<!-- ====== Sekolah End ====== -->

<!-- ====== CTA Section ====== -->
<section class="relative z-10 overflow-hidden bg-primary py-20 lg:py-[115px]">
    <div class="container px-4 mx-auto text-center">
        <h3 class="text-3xl font-bold text-white mb-4">Punya Pertanyaan?</h3>
        <p class="text-white/90 max-w-2xl mx-auto mb-6">Tim kami siap membantu Anda. Hubungi kami melalui telepon, email, atau WhatsApp.</p>
        <div class="flex items-center justify-center gap-4">
            <a href="tel:+6281234567890" class="inline-block bg-white text-primary px-6 py-3 rounded-md font-semibold">📞 Hubungi</a>
            <a href="mailto:ppdb@nuist.id" class="inline-block bg-white text-primary px-6 py-3 rounded-md font-semibold">📧 Email</a>
            <a href="https://wa.me/+6281234567890" target="_blank" class="inline-block bg-white text-primary px-6 py-3 rounded-md font-semibold">💬 WhatsApp</a>
        </div>
    </div>
</section>

<!-- ====== FAQ Section ====== -->
<section class="py-12 bg-white">
    <div class="container px-4 mx-auto">
        <div class="max-w-4xl mx-auto text-center mb-8">
            <h2 class="text-3xl font-bold">Pertanyaan Umum</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-6 bg-gray-50 rounded-lg">
                <h4 class="font-semibold mb-2">Syarat pendaftaran apa saja?</h4>
                <p class="text-gray-600">Lengkapi dokumen seperti KK, Ijazah, dan dokumen pendukung sesuai ketentuan madrasah.</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-lg">
                <h4 class="font-semibold mb-2">Berapa biaya pendaftaran?</h4>
                <p class="text-gray-600">Pendaftaran online PPDB NUIST sepenuhnya GRATIS.</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-lg">
                <h4 class="font-semibold mb-2">Kapan hasil pengumuman?</h4>
                <p class="text-gray-600">Hasil akan diumumkan sesuai jadwal tiap madrasah.</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-lg">
                <h4 class="font-semibold mb-2">Bisa daftar di multiple sekolah?</h4>
                <p class="text-gray-600">Ya, pendaftar dapat memilih beberapa sekolah.</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer Info -->
<div style="max-width: 900px; margin: 40px auto 0; padding: 0 20px;">
    <div class="info-important">
        <h3>ℹ️ Informasi Penting</h3>
        <ul>
            <li>Pastikan dokumen Anda sudah siap sebelum mendaftar</li>
            <li>Isi data dengan benar dan lengkap</li>
            <li>Simpan nomor pendaftaran Anda untuk tracking status</li>
            <li>Pastikan koneksi internet stabil saat upload dokumen</li>
            <li>Maximal ukuran file dokumen 2MB</li>
        </ul>
    </div>
</div>

@include('partials.ppdb.footer')

<div style="height: 40px;"></div>

@section('script-bottom')
    <!-- Play template scripts -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        if (typeof WOW !== 'undefined') { new WOW().init(); }
    </script>
@endsection

@endsection
