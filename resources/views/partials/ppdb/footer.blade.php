<footer class="bg-gradient-to-br from-gray-900 to-gray-800 py-12 mt-16">
    <div class="container px-4 mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Logo & Description -->
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start space-x-2 mb-4">
                    <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo" class="h-10">
                    <span class="text-xl font-semibold text-white">NUIST</span>
                </div>
                <p class="text-gray-300 text-sm">Sistem Penerimaan Peserta Didik Baru yang modern dan terpercaya untuk semua madrasah di bawah naungan NUIST.</p>
            </div>

            <!-- Quick Links -->
            <div class="text-center">
                <h4 class="text-white font-semibold mb-4">Tautan Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="#home" class="text-gray-300 hover:text-white transition">Beranda</a></li>
                    <li><a href="#sekolah" class="text-gray-300 hover:text-white transition">Daftar Sekolah</a></li>
                    <li><a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition">Daftar Akun</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="text-center md:text-right">
                <h4 class="text-white font-semibold mb-4">Kontak Kami</h4>
                <div class="space-y-2 text-gray-300 text-sm">
                    <p>📧 <a href="mailto:ppdb@nuist.id" class="hover:text-white transition">ppdb@nuist.id</a></p>
                    <p>📞 <a href="tel:+6281234567890" class="hover:text-white transition">+62 812 3456 7890</a></p>
                    <p>💬 <a href="https://wa.me/+6281234567890" target="_blank" class="hover:text-white transition">WhatsApp</a></p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-700 pt-8 text-center">
            <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} NUIST — Semua hak cipta dilindungi.</p>
            <p class="text-gray-500 text-xs mt-2">Dikembangkan dengan ❤️ untuk pendidikan yang lebih baik</p>
        </div>
    </div>
</footer>
