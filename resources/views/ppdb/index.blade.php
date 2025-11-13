@extends('layouts.master-without-nav')

@section('title', 'PPDB NUIST 2025')

@section('body')
<body class="scroll-smooth">
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
    <div id="home" class="relative overflow-hidden min-h-screen flex items-center">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-blue-600 to-purple-700">
            <div class="absolute top-0 right-0 w-96 h-96 bg-green-400/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl -ml-20 -mb-20"></div>
            <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-purple-400/15 rounded-full blur-3xl"></div>
        </div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center min-h-[calc(100vh-100px)] lg:min-h-screen">
                <!-- Left Content -->
                <div class="text-white space-y-6 py-12 lg:py-0">
                    <div class="inline-block">
                        <span class="px-4 py-2 rounded-full bg-white/20 text-white text-sm font-semibold border border-white/30">🎓 Tahun Ajaran 2025/2026</span>
                    </div>
                    
                    <div class="space-y-3">
                        <h1 class="text-5xl lg:text-6xl font-bold leading-tight">
                            Daftar PPDB <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-yellow-200">NUIST 2025</span>
                        </h1>
                        <p class="text-xl text-white/90 leading-relaxed max-w-xl">
                            Pendaftaran online peserta didik baru yang cepat, aman, dan transparan. Ratusan calon pendaftar telah bergabung dengan kami.
                        </p>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 pt-4">
                        <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 border border-white/20">
                            <div class="text-2xl font-bold">500+</div>
                            <div class="text-white/70 text-sm">Pendaftar</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 border border-white/20">
                            <div class="text-2xl font-bold">30+</div>
                            <div class="text-white/70 text-sm">Sekolah</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-lg rounded-lg p-4 border border-white/20">
                            <div class="text-2xl font-bold">99%</div>
                            <div class="text-white/70 text-sm">Kepuasan</div>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-8">
                        <a href="#schools" class="px-8 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-900 font-bold rounded-lg hover:shadow-2xl hover:shadow-yellow-500/50 transition-all duration-300 text-center text-lg">
                            🚀 Mulai Daftar Sekarang
                        </a>
                        <a href="#faq" class="px-8 py-4 bg-white/20 text-white font-bold rounded-lg border border-white/30 hover:bg-white/30 transition-all duration-300 text-center text-lg backdrop-blur-lg">
                            ❓ Pelajari Selengkapnya
                        </a>
                    </div>
                </div>

                <!-- Right Content - Hero Image/Illustration -->
                <div class="hidden lg:flex items-center justify-center relative">
                    <div class="relative">
                        <!-- Animated card background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-white/5 rounded-3xl blur-2xl"></div>
                        
                        <!-- Card Content -->
                        <div class="relative bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl p-8 space-y-6">
                            <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-2xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-xl font-bold text-white">Pendaftaran Cepat</h3>
                                <p class="text-white/70">Hanya butuh 5 menit untuk melengkapi formulir</p>
                            </div>
                            <div class="h-1 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full" style="width: 70%"></div>
                        </div>

                        <!-- Second Card -->
                        <div class="relative bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl p-8 space-y-6 mt-6 ml-8">
                            <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-400 to-teal-400 rounded-2xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-xl font-bold text-white">Aman & Terpercaya</h3>
                                <p class="text-white/70">Data Anda dijaga dengan enkripsi tingkat tinggi</p>
                            </div>
                            <div class="h-1 bg-gradient-to-r from-green-400 to-teal-400 rounded-full" style="width: 90%"></div>
                        </div>

                        <!-- Third Card -->
                        <div class="relative bg-white/10 backdrop-blur-2xl border border-white/20 rounded-3xl p-8 space-y-6 mt-6">
                            <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-2xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-xl font-bold text-white">Transparan</h3>
                                <p class="text-white/70">Tracking status pendaftaran real-time</p>
                            </div>
                            <div class="h-1 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </div>
    <!-- ====== Hero Section End ====== -->

<!-- ====== Features Section Start ====== -->
<section id="features" class="py-20 lg:py-28 bg-gradient-to-b from-white via-gray-50 to-white relative">
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-100/40 rounded-full blur-3xl -mr-48 -mt-48 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-100/40 rounded-full blur-3xl -ml-48 -mb-48 pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16 space-y-4">
            <div class="inline-block">
                <span class="px-4 py-2 rounded-full bg-gradient-to-r from-green-100 to-blue-100 text-transparent bg-clip-text font-semibold text-sm">✨ FITUR UNGGULAN</span>
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900">
                Mengapa Memilih <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">NUIST PPDB?</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Sistem pendaftaran modern dengan fitur lengkap untuk kebutuhan sekolah dan calon pendaftar
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Feature 1 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-green-400 rounded-2xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative bg-white rounded-2xl p-8 space-y-4 h-full hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Pendaftaran Mudah</h3>
                        <p class="text-gray-600 mt-2">Proses pendaftaran yang intuitif dan cepat, hanya perlu 5 menit untuk menyelesaikan formulir.</p>
                    </div>
                    <div class="flex items-center text-green-600 font-semibold group-hover:translate-x-2 transition-transform">
                        Pelajari <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-400 rounded-2xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative bg-white rounded-2xl p-8 space-y-4 h-full hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Keamanan Data</h3>
                        <p class="text-gray-600 mt-2">Enkripsi tingkat enterprise melindungi data pribadi Anda dengan standar internasional.</p>
                    </div>
                    <div class="flex items-center text-blue-600 font-semibold group-hover:translate-x-2 transition-transform">
                        Pelajari <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-400 rounded-2xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative bg-white rounded-2xl p-8 space-y-4 h-full hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Hasil Cepat</h3>
                        <p class="text-gray-600 mt-2">Pengumuman seleksi transparan dan tepat waktu dengan notifikasi real-time untuk Anda.</p>
                    </div>
                    <div class="flex items-center text-purple-600 font-semibold group-hover:translate-x-2 transition-transform">
                        Pelajari <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </div>

            <!-- Feature 4 -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-orange-400 rounded-2xl blur opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <div class="relative bg-white rounded-2xl p-8 space-y-4 h-full hover:shadow-2xl transition duration-300">
                    <div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Fitur Lengkap</h3>
                        <p class="text-gray-600 mt-2">Semua alat yang Anda butuhkan: upload dokumen, tracking status, hingga komunikasi langsung.</p>
                    </div>
                    <div class="flex items-center text-orange-600 font-semibold group-hover:translate-x-2 transition-transform">
                        Pelajari <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Benefits -->
        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500/20">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Gratis & Transparent</h3>
                    <p class="mt-2 text-gray-600">Tidak ada biaya pendaftaran. Semua proses transparan tanpa biaya tersembunyi.</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500/20">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Dukungan 24/7</h3>
                    <p class="mt-2 text-gray-600">Tim support kami siap membantu Anda kapan saja melalui berbagai channel komunikasi.</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500/20">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Responsif & Cepat</h3>
                    <p class="mt-2 text-gray-600">Akses dari perangkat apa pun dengan performa optimal dan loading time minimal.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ====== Features Section End ====== -->

<!-- ====== Schools Section Start ====== -->
<section id="schools" class="py-20 lg:py-28 bg-white relative">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-16 space-y-4">
            <div class="inline-block">
                <span class="px-4 py-2 rounded-full bg-gradient-to-r from-green-100 to-blue-100 text-transparent bg-clip-text font-semibold text-sm">🏫 DAFTAR SEKOLAH</span>
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900">
                Pilih <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">Sekolah/Madrasah</span> Anda
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Daftar lengkap madrasah dan sekolah yang membuka PPDB tahun ajaran 2025/2026
            </p>
        </div>

        @if($sekolah->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($sekolah as $item)
                    <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="group">
                        <div class="relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-500 h-full hover:-translate-y-2">
                            <!-- Top Accent -->
                            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-green-500 via-blue-500 to-purple-500"></div>
                            
                            <!-- Card Content -->
                            <div class="p-8 flex flex-col h-full">
                                <!-- School Name -->
                                <div class="mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900 group-hover:text-green-600 transition-colors duration-300">
                                        {{ $item->nama_sekolah }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-2">Tahun Akademik: {{ $item->tahun }}</p>
                                </div>

                                <!-- Status Badge -->
                                <div class="mb-6">
                                    @php
                                        $isPembukaan = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                                    @endphp
                                    <div class="inline-flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full {{ $isPembukaan ? 'bg-green-500 animate-pulse' : 'bg-yellow-500' }}"></div>
                                        <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $isPembukaan ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $isPembukaan ? '✓ Pendaftaran Dibuka' : '⏳ Menunggu Dibuka' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Timeline Info -->
                                <div class="space-y-3 mb-8 flex-grow">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <div>
                                            <p class="text-sm text-gray-600">Dibuka</p>
                                            <p class="font-semibold text-gray-900">{{ $item->jadwal_buka->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <div>
                                            <p class="text-sm text-gray-600">Ditutup</p>
                                            <p class="font-semibold text-gray-900">{{ $item->jadwal_tutup->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- CTA Button -->
                                <button class="w-full px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-xl hover:shadow-lg hover:shadow-green-500/40 transition-all duration-300 group-hover:scale-105 flex items-center justify-center gap-2">
                                    Pelajari & Daftar
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-16 shadow-lg">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Tidak Ada Sekolah yang Membuka PPDB</h3>
                <p class="text-gray-600 text-lg">Mohon cek kembali nanti untuk daftar sekolah terbaru. Kami akan segera menampilkan data sekolah yang membuka PPDB.</p>
            </div>
        @endif
    </div>
</section>
<!-- ====== Schools Section End ====== -->

<!-- ====== CTA Section ====== -->
<section id="contact" class="relative py-20 lg:py-28 overflow-hidden">
    <!-- Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-blue-600 to-purple-700"></div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-green-400/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl -ml-20 -mb-20"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="max-w-3xl mx-auto text-center space-y-8">
            <div class="space-y-4">
                <h2 class="text-4xl lg:text-5xl font-bold text-white">
                    Punya Pertanyaan?
                </h2>
                <p class="text-xl text-white/90 leading-relaxed">
                    Tim support kami yang profesional siap membantu Anda 24/7 melalui berbagai saluran komunikasi untuk memastikan pengalaman pendaftaran Anda lancar.
                </p>
            </div>

            <!-- Contact Methods Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-8">
                <!-- Phone -->
                <a href="tel:+6281234567890" class="group bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 hover:shadow-2xl hover:shadow-green-500/20">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-400 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-2">📞 Telepon</h3>
                    <p class="text-white/80 text-sm mb-4">Hubungi kami langsung untuk bantuan cepat</p>
                    <p class="text-white font-semibold">+62 812-3456-7890</p>
                </a>

                <!-- Email -->
                <a href="mailto:ppdb@nuist.id" class="group bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-2">📧 Email</h3>
                    <p class="text-white/80 text-sm mb-4">Kirimkan pertanyaan Anda kepada kami</p>
                    <p class="text-white font-semibold break-all">ppdb@nuist.id</p>
                </a>

                <!-- WhatsApp -->
                <a href="https://wa.me/6281234567890" target="_blank" class="group bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl p-8 hover:bg-white/20 transition-all duration-300 hover:shadow-2xl hover:shadow-green-500/20">
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-teal-400 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371 0-.57 0-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004c-1.022 0-2.031.193-3.008.566-.93.361-1.764.87-2.456 1.563-.691.692-1.201 1.526-1.562 2.457-.375.98-.568 1.987-.568 3.008 0 1.019.193 2.031.568 3.008.361.931.871 1.765 1.562 2.457s1.526 1.201 2.456 1.562c.977.375 1.986.568 3.008.568 1.022 0 2.031-.193 3.008-.568.93-.361 1.764-.87 2.456-1.562.691-.692 1.201-1.526 1.562-2.457.375-.977.568-1.986.568-3.008 0-1.022-.193-2.031-.568-3.008-.361-.931-.871-1.765-1.562-2.457-.691-.692-1.526-1.201-2.456-1.562-.977-.375-1.986-.568-3.008-.568z"/></svg>
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-2">💬 WhatsApp</h3>
                    <p class="text-white/80 text-sm mb-4">Chat langsung dengan tim kami</p>
                    <p class="text-white font-semibold">+62 812-3456-7890</p>
                </a>
            </div>

            <!-- Quick Response Note -->
            <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-xl p-6 mt-8">
                <p class="text-white/90 text-center">
                    <span class="font-semibold">⚡ Respons Cepat:</span> Kami biasanya merespons pertanyaan Anda dalam waktu kurang dari 2 jam pada jam kerja
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ====== FAQ Section ====== -->
<section id="faq" class="py-20 lg:py-28 bg-white relative">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-100/40 rounded-full blur-3xl -ml-48 -mt-48 pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-16 space-y-4">
            <div class="inline-block">
                <span class="px-4 py-2 rounded-full bg-gradient-to-r from-green-100 to-blue-100 text-transparent bg-clip-text font-semibold text-sm">❓ PERTANYAAN UMUM</span>
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900">
                FAQ - Jawaban untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-blue-600">Pertanyaan Anda</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Temukan jawaban atas pertanyaan yang sering diajukan tentang PPDB NUIST
            </p>
        </div>

        <!-- FAQ Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <!-- FAQ Item 1 -->
            <div class="group">
                <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-green-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Syarat pendaftaran apa saja?</h3>
                            <p class="text-gray-600">Lengkapi dokumen seperti KK, Ijazah, Kartu Keluarga, Akta Kelahiran, dan dokumen pendukung lainnya sesuai ketentuan yang ditetapkan oleh masing-masing madrasah.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="group">
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-blue-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Berapa biaya pendaftaran?</h3>
                            <p class="text-gray-600">Pendaftaran online PPDB NUIST <strong>sepenuhnya GRATIS</strong>. Tidak ada biaya tersembunyi atau pembayaran tambahan untuk proses registrasi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="group">
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-purple-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Kapan hasil pengumuman?</h3>
                            <p class="text-gray-600">Hasil pengumuman akan diumumkan sesuai dengan jadwal yang telah ditetapkan oleh masing-masing sekolah/madrasah. Anda akan menerima notifikasi secara langsung.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 4 -->
            <div class="group">
                <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-orange-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Bisa daftar di multiple sekolah?</h3>
                            <p class="text-gray-600">Ya, calon pendaftar dapat mendaftar di beberapa sekolah/madrasah sesuai dengan minat dan preferensi mereka. Tidak ada batasan jumlah pendaftaran.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 5 -->
            <div class="group">
                <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-red-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Bagaimana jika lupa password?</h3>
                            <p class="text-gray-600">Gunakan fitur "Lupa Password" di halaman login. Anda akan menerima email untuk reset password. Hubungi support kami jika tidak menerima email.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 6 -->
            <div class="group">
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl p-8 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 bg-indigo-600 rounded-xl">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Berapa lama proses verifikasi?</h3>
                            <p class="text-gray-600">Proses verifikasi dokumen biasanya memakan waktu 3-5 hari kerja. Status verifikasi dapat Anda pantau melalui dashboard akun Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Help -->
        <div class="mt-16 bg-gradient-to-r from-green-600 to-blue-600 rounded-2xl p-8 text-center text-white shadow-xl">
            <h3 class="text-2xl font-bold mb-2">Pertanyaan Anda Tidak Terjawab?</h3>
            <p class="text-white/90 mb-6">Tim support kami siap membantu Anda kapan saja</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/6281234567890" target="_blank" class="px-8 py-3 bg-white text-green-600 font-semibold rounded-xl hover:shadow-lg transition-all">
                    💬 Chat WhatsApp
                </a>
                <a href="mailto:ppdb@nuist.id" class="px-8 py-3 bg-white/20 text-white font-semibold rounded-xl border border-white/30 hover:bg-white/30 transition-all backdrop-blur-lg">
                    📧 Kirim Email
                </a>
            </div>
        </div>
    </div>
</section>
<!-- ====== FAQ Section End ====== -->

<!-- Information Section -->
<section class="py-12 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-lg p-8 lg:p-12 border-l-4 border-green-600">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 bg-green-100 rounded-full">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">ℹ️ Informasi Penting</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-700"><strong>Pastikan dokumen Anda sudah siap</strong> sebelum memulai proses pendaftaran</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-700"><strong>Isi data dengan benar dan lengkap</strong> sesuai dengan dokumen asli Anda</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-700"><strong>Simpan nomor pendaftaran Anda</strong> untuk tracking status pendaftaran</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-700"><strong>Pastikan koneksi internet stabil</strong> saat melakukan upload dokumen</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-gray-700"><strong>Ukuran file maksimal</strong> untuk setiap dokumen adalah 2MB</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.ppdb.footer')

<style>
    html {
        scroll-behavior: smooth;
    }
</style>

@section('script-bottom')
    <!-- Play template scripts -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        if (typeof WOW !== 'undefined') { new WOW().init(); }
    </script>
@endsection

@endsection
