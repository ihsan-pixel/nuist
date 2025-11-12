@extends('layouts.master')

@section('title') PPDB NUIST 2025 @endsection

@section('content')

<!-- Hero Section -->
<div class="bg-gradient-to-r from-green-600 to-blue-600 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">🎓 PPDB NUIST 2025</h1>
            <p class="text-xl md:text-2xl text-white/90 mb-2">Penerimaan Peserta Didik Baru</p>
            <p class="text-lg text-white/80">Selamat Datang di Portal Pendaftaran Online NUIST</p>
        </div>
    </div>
</div>

<!-- Info Section -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <!-- Card 1 -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-green-600">
            <div class="text-3xl mb-3">📋</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Pendaftaran Mudah</h3>
            <p class="text-gray-600">Proses pendaftaran yang simple dan cepat hanya dalam 3 langkah</p>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-blue-600">
            <div class="text-3xl mb-3">🔒</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Data Aman</h3>
            <p class="text-gray-600">Sistem keamanan terjamin untuk melindungi data pribadi Anda</p>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-green-600">
            <div class="text-3xl mb-3">⚡</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Hasil Cepat</h3>
            <p class="text-gray-600">Verifikasi dan hasil seleksi diumumkan dengan transparan dan cepat</p>
        </div>
    </div>
</div>

<!-- Schools Section -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-2 text-center">Pilih Sekolah/Madrasah</h2>
        <p class="text-center text-gray-600 mb-10">Daftar Madrasah/Sekolah yang membuka PPDB NUIST 2025</p>
        
        @if($sekolah->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($sekolah as $item)
                <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="group">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border-b-4 border-green-600 h-full">
                        <!-- Header with gradient -->
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 h-20 flex items-center px-6">
                            <h3 class="text-white text-lg font-bold group-hover:text-yellow-200 transition-colors">
                                {{ $item->nama_sekolah }}
                            </h3>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <span class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                                    {{ $item->tahun }}
                                </span>
                            </div>
                            
                            <!-- Status -->
                            <div class="mb-4 pb-4 border-b border-gray-200">
                                @php
                                    $isPembukaan = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                                    $statusColor = $isPembukaan ? 'text-green-600' : 'text-yellow-600';
                                    $statusText = $isPembukaan ? '✓ Pendaftaran Dibuka' : '⏰ Menunggu Dibuka';
                                @endphp
                                <p class="text-sm {{ $statusColor }} font-semibold">{{ $statusText }}</p>
                            </div>
                            
                            <!-- Jadwal Info -->
                            <div class="space-y-2 mb-4 text-sm text-gray-600">
                                <div class="flex items-start">
                                    <span class="mr-2">📅</span>
                                    <span>Buka: <strong>{{ $item->jadwal_buka->format('d M Y') }}</strong></span>
                                </div>
                                <div class="flex items-start">
                                    <span class="mr-2">⏱️</span>
                                    <span>Tutup: <strong>{{ $item->jadwal_tutup->format('d M Y') }}</strong></span>
                                </div>
                            </div>
                            
                            <!-- Button -->
                            <button class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                Pelajari & Daftar →
                            </button>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <div class="text-5xl mb-4">📭</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak ada sekolah yang membuka PPDB saat ini</h3>
                <p class="text-gray-600">Silakan kembali lagi nanti untuk melihat jadwal pendaftaran terbaru</p>
            </div>
        @endif
    </div>
</div>

<!-- CTA Section -->
<div class="container mx-auto px-4 py-12">
    <div class="bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-lg shadow-lg p-8 text-center">
        <h3 class="text-2xl font-bold mb-4">Punya Pertanyaan?</h3>
        <p class="text-lg mb-6">Tim kami siap membantu Anda. Hubungi kami melalui:</p>
        <div class="flex flex-col md:flex-row justify-center gap-4">
            <a href="tel:+6281234567890" class="bg-white text-green-600 hover:bg-green-50 font-bold py-3 px-6 rounded-lg transition-colors">
                📞 Hubungi Kami
            </a>
            <a href="mailto:ppdb@nuist.id" class="bg-white text-green-600 hover:bg-green-50 font-bold py-3 px-6 rounded-lg transition-colors">
                📧 Email
            </a>
            <a href="https://wa.me/+6281234567890" target="_blank" class="bg-white text-green-600 hover:bg-green-50 font-bold py-3 px-6 rounded-lg transition-colors">
                💬 WhatsApp
            </a>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Pertanyaan Umum</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
            <!-- FAQ 1 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-2">❓ Syarat pendaftaran apa saja?</h4>
                <p class="text-gray-600">Siapa saja yang ingin melanjutkan sekolah dapat mendaftar dengan melengkapi dokumen yang diminta (KK dan Ijazah)</p>
            </div>
            
            <!-- FAQ 2 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-2">❓ Berapa biaya pendaftaran?</h4>
                <p class="text-gray-600">Pendaftaran online PPDB NUIST sepenuhnya GRATIS, tanpa ada biaya apapun</p>
            </div>
            
            <!-- FAQ 3 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-2">❓ Kapan hasil pengumuman?</h4>
                <p class="text-gray-600">Hasil seleksi akan diumumkan sesuai jadwal yang telah ditetapkan oleh masing-masing sekolah</p>
            </div>
            
            <!-- FAQ 4 -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-2">❓ Bisa daftar di multiple sekolah?</h4>
                <p class="text-gray-600">Ya, Anda dapat mendaftar di beberapa sekolah sesuai keinginan Anda</p>
            </div>
        </div>
    </div>
</div>

<!-- Footer Info -->
<div class="container mx-auto px-4 py-12">
    <div class="bg-white rounded-lg shadow-md p-8 border-l-4 border-green-600">
        <h3 class="text-xl font-bold text-gray-800 mb-4">ℹ️ Informasi Penting</h3>
        <ul class="space-y-2 text-gray-700">
            <li>✅ Pastikan dokumen Anda sudah siap sebelum mendaftar</li>
            <li>✅ Isi data dengan benar dan lengkap</li>
            <li>✅ Simpan nomor pendaftaran Anda untuk tracking status</li>
            <li>✅ Pastikan koneksi internet stabil saat upload dokumen</li>
            <li>✅ Maximal ukuran file dokumen 2MB</li>
        </ul>
    </div>
</div>

@endsection
