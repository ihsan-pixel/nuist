<!-- Header/Navbar -->
<header class="fixed top-0 left-0 z-50 w-full transition-all duration-300 bg-transparent backdrop-blur-sm" id="mainNavbar">
    <div class="container mx-auto flex items-center justify-between px-4 py-4">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo" class="h-10">
            <span class="text-xl font-semibold text-white">NUIST</span>
        </a>

        <!-- Desktop Menu -->
        <nav class="hidden lg:flex items-center space-x-8">
            <a href="#home" class="nav-link text-white hover:text-primary transition">Home</a>
            <a href="#about" class="nav-link text-white hover:text-primary transition">About</a>
            <a href="#pricing" class="nav-link text-white hover:text-primary transition">Pricing</a>
            <a href="#team" class="nav-link text-white hover:text-primary transition">Team</a>
            <a href="#contact" class="nav-link text-white hover:text-primary transition">Contact</a>
        </nav>

        <!-- Auth Buttons -->
        <div class="hidden sm:flex items-center space-x-3">
            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-white hover:text-primary transition">Sign In</a>
            <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-medium text-dark bg-white rounded-lg hover:bg-primary hover:text-white transition">Sign Up</a>
        </div>

        <!-- Mobile Toggle -->
        <button id="navbarToggler" class="block lg:hidden text-white focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden absolute top-full left-0 w-full bg-white/95 shadow-lg backdrop-blur-lg lg:hidden">
        <nav class="flex flex-col space-y-2 px-6 py-4">
            <a href="#home" class="block py-2 text-dark hover:text-primary transition">Home</a>
            <a href="#about" class="block py-2 text-dark hover:text-primary transition">About</a>
            <a href="#pricing" class="block py-2 text-dark hover:text-primary transition">Pricing</a>
            <a href="#team" class="block py-2 text-dark hover:text-primary transition">Team</a>
            <a href="#contact" class="block py-2 text-dark hover:text-primary transition">Contact</a>
            <hr class="my-2 border-gray-200">
            <a href="{{ route('login') }}" class="block py-2 text-dark hover:text-primary transition">Sign In</a>
            <a href="{{ route('register') }}" class="block py-2 text-dark font-medium hover:text-primary transition">Sign Up</a>
        </nav>
    </div>
</header>

<script>
    // Navbar toggle untuk mobile
    const toggler = document.getElementById('navbarToggler');
    const mobileMenu = document.getElementById('mobileMenu');
    const mainNavbar = document.getElementById('mainNavbar');

    toggler.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Tambahkan efek background saat scroll
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            mainNavbar.classList.add('bg-white/80', 'backdrop-blur-md', 'shadow-md');
            mainNavbar.classList.remove('bg-transparent');
            mainNavbar.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('text-white');
                link.classList.add('text-dark');
            });
        } else {
            mainNavbar.classList.remove('bg-white/80', 'backdrop-blur-md', 'shadow-md');
            mainNavbar.classList.add('bg-transparent');
            mainNavbar.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('text-dark');
                link.classList.add('text-white');
            });
        }
    });
</script>
