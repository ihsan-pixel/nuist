@extends('layouts.master-without-nav')

@section('title', 'PPDB NUIST 2025')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-green-600 via-blue-600 to-green-700 text-white py-20 overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('/images/pattern.svg')] bg-cover"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold mb-4 tracking-tight">🎓 PPDB NUIST 2025</h1>
        <p class="text-2xl font-medium mb-2">Penerimaan Peserta Didik Baru</p>
        <p class="text-lg opacity-90 mb-6">Selamat datang di portal pendaftaran online Madrasah dan Sekolah NUIST</p>
        <a href="#sekolah" class="inline-block bg-white text-green-700 font-semibold px-8 py-3 rounded-full shadow hover:bg-green-50 transition-all duration-200">
            Mulai Pendaftaran
        </a>
    </div>
</section>

<!-- Info Section -->
<section class="container mx-auto px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach([
            ['icon' => '📋', 'title' => 'Pendaftaran Mudah', 'desc' => 'Proses pendaftaran cepat dan mudah hanya dalam 3 langkah.'],
            ['icon' => '🔒', 'title' => 'Data Aman', 'desc' => 'Keamanan data Anda dijamin dengan sistem enkripsi terbaik.'],
            ['icon' => '⚡', 'title' => 'Hasil Cepat', 'desc' => 'Pengumuman hasil seleksi diumumkan secara transparan.']
        ] as $info)
        <div class="bg-white shadow-lg hover:shadow-2xl rounded-xl p-8 text-center border-t-4 border-green-600 transition-transform hover:-translate-y-1 duration-300">
            <div class="text-4xl mb-4">{{ $info['icon'] }}</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $info['title'] }}</h3>
            <p class="text-gray-600">{{ $info['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

<!-- Schools Section -->
<section id="sekolah" class="bg-gray-50 py-16">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-extrabold text-gray-800 mb-2">Pilih Sekolah / Madrasah</h2>
            <p class="text-gray-600">Daftar sekolah di bawah NUIST yang membuka PPDB tahun 2025</p>
        </div>

        @if($sekolah->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($sekolah as $item)
                <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="group block">
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl overflow-hidden transition-all duration-300 border border-gray-100 hover:border-green-500">
                        <div class="bg-gradient-to-r from-green-600 to-blue-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white group-hover:text-yellow-200 transition">
                                {{ $item->nama_sekolah }}
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                                    {{ $item->tahun }}
                                </span>
                                @php
                                    $isOpen = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                                @endphp
                                <span class="text-sm font-bold {{ $isOpen ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $isOpen ? '✓ Dibuka' : '⏰ Belum Dibuka' }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-700 space-y-1 mb-5">
                                <p>📅 Buka: <strong>{{ $item->jadwal_buka->format('d M Y') }}</strong></p>
                                <p>⏱️ Tutup: <strong>{{ $item->jadwal_tutup->format('d M Y') }}</strong></p>
                            </div>
                            <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                                Pelajari & Daftar →
                            </button>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="text-center bg-white rounded-xl shadow p-12">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Sekolah yang Membuka PPDB</h3>
                <p class="text-gray-600">Silakan cek kembali nanti untuk jadwal terbaru.</p>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="container mx-auto px-6 py-16">
    <div class="bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-2xl shadow-lg p-10 text-center">
        <h3 class="text-3xl font-bold mb-4">Butuh Bantuan?</h3>
        <p class="text-lg mb-6 opacity-90">Tim support kami siap membantu Anda setiap saat.</p>
        <div class="flex flex-col md:flex-row justify-center gap-4">
            <a href="tel:+6281234567890" class="bg-white text-green-700 hover:bg-green-50 font-bold py-3 px-8 rounded-lg shadow transition">
                📞 Telepon
            </a>
            <a href="mailto:ppdb@nuist.id" class="bg-white text-green-700 hover:bg-green-50 font-bold py-3 px-8 rounded-lg shadow transition">
                📧 Email
            </a>
            <a href="https://wa.me/+6281234567890" target="_blank" class="bg-white text-green-700 hover:bg-green-50 font-bold py-3 px-8 rounded-lg shadow transition">
                💬 WhatsApp
            </a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-extrabold text-gray-800 mb-10 text-center">Pertanyaan Umum (FAQ)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
            @foreach([
                ['q' => 'Syarat pendaftaran apa saja?', 'a' => 'Cukup siapkan dokumen seperti Kartu Keluarga (KK) dan Ijazah terakhir.'],
                ['q' => 'Berapa biaya pendaftaran?', 'a' => 'Pendaftaran online PPDB NUIST GRATIS tanpa biaya apapun.'],
                ['q' => 'Kapan hasil pengumuman?', 'a' => 'Hasil seleksi diumumkan sesuai jadwal tiap sekolah.'],
                ['q' => 'Bisa daftar di beberapa sekolah?', 'a' => 'Ya, Anda bisa mendaftar di beberapa sekolah selama jadwal masih terbuka.']
            ] as $faq)
            <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-2">❓ {{ $faq['q'] }}</h4>
                <p class="text-gray-600">{{ $faq['a'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Footer Info -->
<footer class="container mx-auto px-6 py-16">
    <div class="bg-white rounded-xl shadow p-8 border-l-4 border-green-600">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">ℹ️ Informasi Penting</h3>
        <ul class="text-gray-700 space-y-2">
            <li>✅ Pastikan semua dokumen siap sebelum mendaftar.</li>
            <li>✅ Isi data dengan benar dan lengkap.</li>
            <li>✅ Simpan nomor pendaftaran Anda untuk memantau status.</li>
            <li>✅ Gunakan koneksi internet stabil saat upload dokumen.</li>
            <li>✅ Ukuran maksimal file dokumen: 2MB.</li>
        </ul>
    </div>
</footer>
@endsection
