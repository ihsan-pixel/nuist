<footer style="background: #1f2937; color: white; padding: 4rem 0 2rem;">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 3rem; margin-bottom: 3rem;">
            <!-- Logo & Description -->
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                    <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo" style="height: 2rem;">
                    <span style="font-size: 1.25rem; font-weight: 700; color: #667eea;">NUIST</span>
                </div>
                <p style="color: #9ca3af; line-height: 1.6; margin-bottom: 1.5rem;">Sistem Penerimaan Peserta Didik Baru yang modern dan terpercaya untuk semua madrasah di bawah naungan NUIST.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: white;">Tautan Cepat</h4>
                <ul style="list-style: none;">
                    <li style="margin-bottom: 0.5rem;"><a href="#home" style="color: #9ca3af; text-decoration: none; transition: color 0.3s ease;">Beranda</a></li>
                    <li style="margin-bottom: 0.5rem;"><a href="#sekolah" style="color: #9ca3af; text-decoration: none; transition: color 0.3s ease;">Daftar Sekolah</a></li>
                    <li style="margin-bottom: 0.5rem;"><a href="{{ route('login') }}" style="color: #9ca3af; text-decoration: none; transition: color 0.3s ease;">Masuk</a></li>
                    <li style="margin-bottom: 0.5rem;"><a href="{{ route('register') }}" style="color: #9ca3af; text-decoration: none; transition: color 0.3s ease;">Daftar Akun</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: white;">Kontak Kami</h4>
                <div style="color: #9ca3af;">
                    <p style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">📧 <a href="mailto:ppdb@nuist.id" style="color: #9ca3af; text-decoration: none; transition: color 0.3s ease;">ppdb@nuist.id</a></p>
                    <p style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">📞 <a href="tel:+6281234567890" style="color: #9ca3af; text-decoration: none; transition: color 0.3s ease;">+62 812 3456 7890</a></p>
                    <p style="margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">💬 <a href="https://wa.me/+6281234567890" target="_blank" style="color: #9ca3af; text-decoration: none; transition: color 0.3s ease;">WhatsApp</a></p>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div style="border-top: 1px solid #374151; padding-top: 2rem; text-align: center;">
            <p style="color: #9ca3af; font-size: 0.875rem;">&copy; {{ date('Y') }} NUIST — Semua hak cipta dilindungi.</p>
            <p style="color: #6b7280; font-size: 0.75rem; margin-top: 0.5rem;">Dikembangkan dengan ❤️ untuk pendidikan yang lebih baik</p>
        </div>
    </div>
</footer>
