<header id="mainNavbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 bg-transparent">
    <div class="navbar-container flex items-center justify-between px-6 py-3 max-w-7xl mx-auto">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo" class="h-8 w-auto transition-all duration-300" id="navbarLogo">
            <span class="font-semibold text-white text-lg tracking-wide transition-all duration-300" id="navbarText">NUIST</span>
        </a>

        <!-- Desktop Menu -->
        <nav class="hidden md:flex space-x-8">
            <a href="#home" class="nav-link text-white hover:text-blue-600 font-medium transition">Beranda</a>
            <a href="#sekolah" class="nav-link text-white hover:text-blue-600 font-medium transition">Sekolah</a>
            <a href="#contact" class="nav-link text-white hover:text-blue-600 font-medium transition">Kontak</a>
        </nav>

        <!-- Auth Buttons -->
        <div class="hidden md:flex space-x-3">
            <a href="{{ route('login') }}" class="border border-white text-white px-4 py-2 rounded-lg hover:bg-white hover:text-blue-600 transition">Masuk</a>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Daftar</a>
        </div>

        <!-- Mobile Toggle -->
        <button id="navbarToggler" class="md:hidden text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden flex-col items-center bg-white/95 backdrop-blur-md border-t border-gray-200 py-4 md:hidden shadow-md">
        <a href="#home" class="navbar-mobile-link py-2 text-gray-700 hover:text-blue-600">Beranda</a>
        <a href="#sekolah" class="navbar-mobile-link py-2 text-gray-700 hover:text-blue-600">Sekolah</a>
        <a href="#contact" class="navbar-mobile-link py-2 text-gray-700 hover:text-blue-600">Kontak</a>
        <div class="flex space-x-3 mt-3">
            <a href="{{ route('login') }}" class="border border-blue-600 text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-600 hover:text-white transition">Masuk</a>
            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Daftar</a>
        </div>
    </div>
</header>

<script>
    const toggler = document.getElementById('navbarToggler');
    const mobileMenu = document.getElementById('mobileMenu');
    const navbar = document.getElementById('mainNavbar');
    const logo = document.getElementById('navbarLogo');
    const text = document.getElementById('navbarText');
    const links = document.querySelectorAll('.nav-link');

    toggler.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('bg-white/90', 'shadow-md', 'backdrop-blur-md');
            navbar.classList.remove('bg-transparent');
            text.classList.add('text-gray-800');
            text.classList.remove('text-white');
            logo.src = "{{ asset('assets/images/logo/logo-blue.svg') }}"; // gunakan logo gelap
            links.forEach(link => {
                link.classList.remove('text-white');
                link.classList.add('text-gray-800');
            });
        } else {
            navbar.classList.remove('bg-white/90', 'shadow-md', 'backdrop-blur-md');
            navbar.classList.add('bg-transparent');
            text.classList.remove('text-gray-800');
            text.classList.add('text-white');
            logo.src = "{{ asset('assets/images/logo/logo-white.svg') }}";
            links.forEach(link => {
                link.classList.add('text-white');
                link.classList.remove('text-gray-800');
            });
        }
    });
</script>
