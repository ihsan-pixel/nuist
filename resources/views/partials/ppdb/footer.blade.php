<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <!-- Logo & Description -->
            <div class="footer-section">
                <div class="footer-logo">
                    <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo">
                    <span class="footer-logo-text">NUIST</span>
                </div>
                <p class="footer-description">Sistem Penerimaan Peserta Didik Baru yang modern dan terpercaya untuk semua madrasah di bawah naungan NUIST.</p>
            </div>

            <!-- Quick Links -->
            <div class="footer-section">
                <h4 class="footer-title">Tautan Cepat</h4>
                <ul class="footer-links">
                    <li><a href="#home" class="footer-link">Beranda</a></li>
                    <li><a href="#sekolah" class="footer-link">Daftar Sekolah</a></li>
                    <li><a href="{{ route('login') }}" class="footer-link">Masuk</a></li>
                    <li><a href="{{ route('register') }}" class="footer-link">Daftar Akun</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-section">
                <h4 class="footer-title">Kontak Kami</h4>
                <div class="footer-contact">
                    <p>📧 <a href="mailto:ppdb@nuist.id">ppdb@nuist.id</a></p>
                    <p>📞 <a href="tel:+6281234567890">+62 812 3456 7890</a></p>
                    <p>💬 <a href="https://wa.me/+6281234567890" target="_blank">WhatsApp</a></p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <p class="footer-copyright">&copy; {{ date('Y') }} NUIST — Semua hak cipta dilindungi.</p>
            <p class="footer-tagline">Dikembangkan dengan ❤️ untuk pendidikan yang lebih baik</p>
        </div>
    </div>
</footer>
