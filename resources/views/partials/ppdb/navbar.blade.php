<header class="navbar">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="navbar-logo">
            <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo" style="height: 2rem; width: auto;">
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
            <a href="{{ route('login') }}" style="padding: 0.5rem 1rem; background: none; border: none; color: #4b5563; font-weight: 500; cursor: pointer; transition: color 0.3s ease;">Masuk</a>
            <a href="{{ route('register') }}" style="padding: 0.5rem 1.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 0.5rem; font-weight: 500; text-decoration: none; transition: all 0.3s ease;">Daftar</a>
        </div>

        <!-- Mobile Toggle -->
        <button id="navbarToggler" style="display: none; background: none; border: none; color: #4b5563; cursor: pointer; padding: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-top: 1px solid rgba(0, 0, 0, 0.1);">
        <nav style="display: flex; flex-direction: column; padding: 1rem;">
            <a href="#home" style="padding: 0.75rem 1rem; color: #1f2937; text-decoration: none; font-weight: 500; transition: color 0.3s ease;">Beranda</a>
            <a href="#sekolah" style="padding: 0.75rem 1rem; color: #1f2937; text-decoration: none; font-weight: 500; transition: color 0.3s ease;">Sekolah</a>
            <a href="#contact" style="padding: 0.75rem 1rem; color: #1f2937; text-decoration: none; font-weight: 500; transition: color 0.3s ease;">Kontak</a>
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
