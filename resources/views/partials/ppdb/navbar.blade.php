<header id="mainNavbar" class="fixed top-0 left-0 w-full z-[9999] transition-all duration-300 bg-white/10 backdrop-blur-md border-b border-white/10">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo" class="h-10">
            <span class="text-xl font-semibold text-white">NUIST</span>
        </a>

        <!-- Desktop Menu -->
        <nav class="hidden lg:flex items-center space-x-8">
            <a href="#home" class="nav-link text-white hover:text-green-200 transition">Home</a>
            <a href="#about" class="nav-link text-white hover:text-green-200 transition">Tentang</a>
            <a href="#pricing" class="nav-link text-white hover:text-green-200 transition">Fitur</a>
            <a href="#contact" class="nav-link text-white hover:text-green-200 transition">Kontak</a>
        </nav>

        <!-- Auth Buttons -->
        <div class="hidden sm:flex items-center space-x-3">
            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white hover:text-green-200 transition">Sign In</a>
            <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-medium text-dark bg-white rounded-lg hover:bg-green-600 hover:text-white transition">Sign Up</a>
        </div>

        <!-- Mobile Toggle -->
        <button id="navbarToggler" class="block lg:hidden text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden absolute top-full left-0 w-full bg-white/95 shadow-lg backdrop-blur-lg lg:hidden">
        <nav class="flex flex-col space-y-2 px-6 py-4">
            <a href="#home" class="block py-2 text-dark hover:text-primary transition">Home</a>
            <a href="#about" class="block py-2 text-dark hover:text-primary transition">Tentang</a>
            <a href="#pricing" class="block py-2 text-dark hover:text-primary transition">Fitur</a>
            <a href="#contact" class="block py-2 text-dark hover:text-primary transition">Kontak</a>
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
