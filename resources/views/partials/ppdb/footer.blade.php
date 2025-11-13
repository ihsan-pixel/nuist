<footer class="bg-gradient-to-b from-gray-900 to-black text-gray-100 pt-16 pb-8 mt-20">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Footer Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <!-- Brand Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-green-400 to-blue-500 rounded-lg p-2">
                        <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo" class="h-8 w-8">
                    </div>
                    <h3 class="text-xl font-bold bg-gradient-to-r from-green-400 to-blue-500 bg-clip-text text-transparent">NUIST PPDB</h3>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Platform pendaftaran peserta didik baru yang aman, cepat, dan transparan untuk madrasah dan sekolah.
                </p>
                <div class="flex gap-4 pt-2">
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-green-600 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-green-600 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20v-7.21H5.93V9.25h2.36V7.37c0-2.33 1.43-3.61 3.48-3.61 1 0 1.82.07 2.06.11v2.38h-1.44c-1.11 0-1.32.53-1.32 1.3v1.7h2.62l-.65 3.54h-1.97V20z"/></svg>
                    </a>
                    <a href="#" class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-800 hover:bg-green-600 transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-lg">Menu Utama</h4>
                <ul class="space-y-3">
                    <li><a href="{{ route('ppdb.index') }}" class="text-gray-400 hover:text-green-400 transition-colors duration-300">← Beranda</a></li>
                    <li><a href="{{ route('ppdb.index') }}#features" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Keunggulan</a></li>
                    <li><a href="{{ route('ppdb.index') }}#schools" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Daftar Sekolah</a></li>
                    <li><a href="{{ route('ppdb.index') }}#faq" class="text-gray-400 hover:text-green-400 transition-colors duration-300">FAQ</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Masuk Sistem</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-lg">Dukungan</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Panduan Pendaftaran</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Syarat & Ketentuan</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Kebijakan Privasi</a></li>
                    <li><a href="#contact" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Hubungi Kami</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Status Sistem</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-white font-semibold mb-4 text-lg">Hubungi Kami</h4>
                <ul class="space-y-4">
                    <li class="flex gap-3">
                        <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:ppdb@nuist.id" class="text-gray-400 hover:text-green-400 transition-colors duration-300 break-all">ppdb@nuist.id</a>
                    </li>
                    <li class="flex gap-3">
                        <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:+6281234567890" class="text-gray-400 hover:text-green-400 transition-colors duration-300">+62 812-3456-7890</a>
                    </li>
                    <li class="flex gap-3">
                        <svg class="w-5 h-5 text-green-400 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371 0-.57 0-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004c-1.022 0-2.031.193-3.008.566-.93.361-1.764.87-2.456 1.563-.691.692-1.201 1.526-1.562 2.457-.375.98-.568 1.987-.568 3.008 0 1.019.193 2.031.568 3.008.361.931.871 1.765 1.562 2.457s1.526 1.201 2.456 1.562c.977.375 1.986.568 3.008.568 1.022 0 2.031-.193 3.008-.568.93-.361 1.764-.87 2.456-1.562.691-.692 1.201-1.526 1.562-2.457.375-.977.568-1.986.568-3.008 0-1.022-.193-2.031-.568-3.008-.361-.931-.871-1.765-1.562-2.457-.691-.692-1.526-1.201-2.456-1.562-.977-.375-1.986-.568-3.008-.568z"/></svg>
                        <a href="https://wa.me/6281234567890" target="_blank" class="text-gray-400 hover:text-green-400 transition-colors duration-300">WhatsApp</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-800 my-8"></div>

        <!-- Footer Bottom -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-gray-400 text-sm text-center md:text-left">
                &copy; {{ date('Y') }} <strong class="text-white">NUIST PPDB</strong> — Semua hak cipta dilindungi. Dikembangkan dengan ❤️ untuk madrasah Indonesia.
            </p>
            <div class="flex gap-6 text-sm">
                <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Kebijakan Privasi</a>
                <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Syarat Layanan</a>
                <a href="#" class="text-gray-400 hover:text-green-400 transition-colors duration-300">Sitemap</a>
            </div>
        </div>
    </div>

    <!-- Gradient Background -->
    <div class="absolute inset-0 -z-10 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-green-500/10 to-transparent rounded-full blur-3xl"></div>
    </div>
</footer>
