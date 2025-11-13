<header id="mainNavbar" class="navbar">
    <div class="navbar-container">
        <!-- Logo (Left) -->
        <div class="navbar-left">
            <a href="{{ url('/') }}" class="navbar-logo">
                <div class="navbar-logo-icon">
                    <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo">
                </div>
                <span class="navbar-logo-text">NUIST</span>
            </a>
        </div>

        <!-- Desktop Menu (Center) -->
        <div class="navbar-center">
            <nav class="navbar-menu">
                <a href="#home" class="navbar-link active">
                    <span>Beranda</span>
                </a>
                <a href="#tentang" class="navbar-link">
                    <span>Tentang</span>
                </a>
                <a href="#fitur" class="navbar-link">
                    <span>Fitur</span>
                </a>
                <a href="#galeri" class="navbar-link">
                    <span>Galeri</span>
                </a>
                <a href="#berita" class="navbar-link">
                    <span>Berita</span>
                </a>
                <a href="#kontak" class="navbar-link">
                    <span>Kontak</span>
                </a>
            </nav>
        </div>

        <!-- Auth Buttons (Right) -->
        <div class="navbar-right">
            <a href="{{ route('login') }}" class="navbar-btn-secondary">
                <span>Masuk</span>
            </a>
            <a href="{{ route('register') }}" class="navbar-btn-primary">
                <span>Daftar</span>
            </a>

            <!-- Mobile Toggle -->
            <button id="navbarToggler" class="navbar-toggle">
                <span class="navbar-toggle-line"></span>
                <span class="navbar-toggle-line"></span>
                <span class="navbar-toggle-line"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="navbar-mobile-menu hidden">
        <nav class="navbar-mobile-nav">
            <a href="#home" class="navbar-mobile-link"><span>🏠</span>Beranda</a>
            <a href="#tentang" class="navbar-mobile-link"><span>ℹ️</span>Tentang</a>
            <a href="#fitur" class="navbar-mobile-link"><span>⚡</span>Fitur</a>
            <a href="#galeri" class="navbar-mobile-link"><span>🖼️</span>Galeri</a>
            <a href="#berita" class="navbar-mobile-link"><span>📰</span>Berita</a>
            <a href="#kontak" class="navbar-mobile-link"><span>📞</span>Kontak</a>
            <div class="navbar-mobile-divider"></div>
            <a href="{{ route('login') }}" class="navbar-mobile-link navbar-mobile-link-secondary"><span>🔑</span>Masuk</a>
            <a href="{{ route('register') }}" class="navbar-mobile-link navbar-mobile-link-primary"><span>✨</span>Daftar</a>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('mainNavbar');
    const toggler = document.getElementById('navbarToggler');
    const mobileMenu = document.getElementById('mobileMenu');
    const links = document.querySelectorAll('.navbar-link, .navbar-mobile-link');

    // Scroll effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) navbar.classList.add('scrolled');
        else navbar.classList.remove('scrolled');
    });

    // Mobile toggle
    toggler.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Smooth scroll + active link
    links.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                mobileMenu.classList.add('hidden');
            }
        });
    });
});
</script>
