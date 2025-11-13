<header class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="navbar-logo">
            <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo">
            <span class="navbar-logo-text">NUIST</span>
        </a>

        <!-- Desktop Menu -->
        <nav class="navbar-menu">
            <a href="#home" class="navbar-link">Beranda</a>
            <a href="#sekolah" class="navbar-link">Sekolah</a>
            <a href="#contact" class="navbar-link">Kontak</a>
        </nav>

        <!-- Auth Buttons -->
        <div class="navbar-auth">
            <a href="{{ route('login') }}" class="navbar-btn-secondary">Masuk</a>
            <a href="{{ route('register') }}" class="navbar-btn-primary">Daftar</a>
        </div>

        <!-- Mobile Toggle -->
        <button id="navbarToggler" class="navbar-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="navbar-mobile-menu">
        <nav class="navbar-mobile-nav">
            <a href="#home" class="navbar-mobile-link">Beranda</a>
            <a href="#sekolah" class="navbar-mobile-link">Sekolah</a>
            <a href="#contact" class="navbar-mobile-link">Kontak</a>
        </nav>
    </div>
</header>

<script>
    const toggler = document.getElementById('navbarToggler');
    const mobileMenu = document.getElementById('mobileMenu');
    const navbar = document.getElementById('mainNavbar');

    toggler.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Ubah warna navbar saat scroll
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('bg-white/90', 'shadow-md', 'backdrop-blur-md', 'border-transparent');
            navbar.classList.remove('bg-white/10', 'border-white/10');
            navbar.querySelectorAll('.nav-link').forEach(el => {
                el.classList.add('text-dark');
                el.classList.remove('text-white');
            });
        } else {
            navbar.classList.remove('bg-white/90', 'shadow-md', 'backdrop-blur-md', 'border-transparent');
            navbar.classList.add('bg-white/10', 'border-white/10');
            navbar.querySelectorAll('.nav-link').forEach(el => {
                el.classList.remove('text-dark');
                el.classList.add('text-white');
            });
        }
    });
</script>
