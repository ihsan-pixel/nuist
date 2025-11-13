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
                <a href="#sekolah" class="navbar-link">
                    <span>Sekolah</span>
                </a>
                <a href="#contact" class="navbar-link">
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
    <div id="mobileMenu" class="navbar-mobile-menu">
        <nav class="navbar-mobile-nav">
            <a href="#home" class="navbar-mobile-link">
                <span class="navbar-mobile-link-icon">🏠</span>
                <span>Beranda</span>
            </a>
            <a href="#sekolah" class="navbar-mobile-link">
                <span class="navbar-mobile-link-icon">🏫</span>
                <span>Sekolah</span>
            </a>
            <a href="#contact" class="navbar-mobile-link">
                <span class="navbar-mobile-link-icon">📞</span>
                <span>Kontak</span>
            </a>
            <div class="navbar-mobile-divider"></div>
            <a href="{{ route('login') }}" class="navbar-mobile-link navbar-mobile-link-secondary">
                <span class="navbar-mobile-link-icon">🔑</span>
                <span>Masuk</span>
            </a>
            <a href="{{ route('register') }}" class="navbar-mobile-link navbar-mobile-link-primary">
                <span class="navbar-mobile-link-icon">✨</span>
                <span>Daftar</span>
            </a>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.getElementById('mainNavbar');
    const navbarLinks = document.querySelectorAll('.navbar-link');
    const mobileLinks = document.querySelectorAll('.navbar-mobile-link');
    let lastScrollTop = 0;

    // Scroll effect for navbar
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (scrollTop > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        lastScrollTop = scrollTop;
    });

    // Active link switching
    function setActiveLink(targetId) {
        // Remove active class from all links
        navbarLinks.forEach(link => link.classList.remove('active'));
        mobileLinks.forEach(link => link.classList.remove('active'));

        // Add active class to clicked link
        const desktopLink = document.querySelector(`.navbar-link[href="${targetId}"]`);
        const mobileLink = document.querySelector(`.navbar-mobile-link[href="${targetId}"]`);

        if (desktopLink) desktopLink.classList.add('active');
        if (mobileLink) mobileLink.classList.add('active');
    }

    // Smooth scroll and active link update
    navbarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                setActiveLink(targetId);
            }
        });
    });

    mobileLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                setActiveLink(targetId);
                // Close mobile menu
                document.getElementById('mobileMenu').style.display = 'none';
            }
        });
    });

    // Mobile menu toggle
    const navbarToggler = document.getElementById('navbarToggler');
    const mobileMenu = document.getElementById('mobileMenu');

    navbarToggler.addEventListener('click', function() {
        if (mobileMenu.style.display === 'block') {
            mobileMenu.style.display = 'none';
        } else {
            mobileMenu.style.display = 'block';
        }
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!navbar.contains(e.target)) {
            mobileMenu.style.display = 'none';
        }
    });
});
</script>

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
            navbar.classList.add('bg-white/95', 'shadow-lg', 'backdrop-blur-lg', 'border-gray-200');
            navbar.classList.remove('bg-white/10', 'border-white/10');
            navbar.querySelectorAll('.navbar-link').forEach(el => {
                el.classList.add('text-black');
                el.classList.remove('text-white');
            });
            navbar.querySelectorAll('.navbar-btn-secondary').forEach(el => {
                el.classList.add('text-black');
                el.classList.remove('text-white');
            });
            navbar.querySelectorAll('.navbar-toggle').forEach(el => {
                el.classList.add('text-black');
                el.classList.remove('text-white');
            });
        } else {
            navbar.classList.remove('bg-white/95', 'shadow-lg', 'backdrop-blur-lg', 'border-gray-200');
            navbar.classList.add('bg-white/10', 'border-white/10');
            navbar.querySelectorAll('.navbar-link').forEach(el => {
                el.classList.remove('text-black');
                el.classList.add('text-white');
            });
            navbar.querySelectorAll('.navbar-btn-secondary').forEach(el => {
                el.classList.remove('text-black');
                el.classList.add('text-white');
            });
            navbar.querySelectorAll('.navbar-toggle').forEach(el => {
                el.classList.remove('text-black');
                el.classList.add('text-white');
            });
        }
    });
</script>
