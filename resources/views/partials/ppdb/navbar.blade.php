<header id="mainNavbar" class="fixed top-0 left-0 w-full z-[9999] transition-all duration-500">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-[80px] lg:h-[100px]">
            <!-- Logo & Brand -->
            <a href="{{ route('ppdb.index') }}" class="flex items-center gap-3 group">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg blur opacity-0 group-hover:opacity-75 transition duration-300"></div>
                    <div class="relative bg-white rounded-lg p-2">
                        <img src="{{ asset('assets/images/logo/logo-white.svg') }}" alt="Logo" class="h-8 w-8">
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg lg:text-xl font-bold logo-text">NUIST</span>
                    <span class="text-xs logo-subtext hidden sm:block">PPDB 2025</span>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex items-center gap-1">
                <a href="{{ route('ppdb.index') }}#home" class="nav-link px-4 py-2 rounded-lg transition-all duration-300 text-sm font-medium">Beranda</a>
                <a href="{{ route('ppdb.index') }}#features" class="nav-link px-4 py-2 rounded-lg transition-all duration-300 text-sm font-medium">Keunggulan</a>
                <a href="{{ route('ppdb.index') }}#schools" class="nav-link px-4 py-2 rounded-lg transition-all duration-300 text-sm font-medium">Sekolah</a>
                <a href="{{ route('ppdb.index') }}#faq" class="nav-link px-4 py-2 rounded-lg transition-all duration-300 text-sm font-medium">FAQ</a>
                <a href="{{ route('ppdb.index') }}#contact" class="nav-link px-4 py-2 rounded-lg transition-all duration-300 text-sm font-medium">Kontak</a>
            </nav>

            <!-- CTA Buttons -->
            <div class="hidden md:flex items-center gap-3">
                <a href="{{ route('login') }}" class="cta-login px-4 py-2 rounded-lg font-medium text-sm transition-all duration-300">Masuk</a>
                <a href="{{ route('ppdb.index') }}#schools" class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-2 rounded-lg font-medium text-sm hover:shadow-lg hover:shadow-green-500/50 transition-all duration-300">Daftar Sekarang</a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="mobileToggle" class="lg:hidden relative z-10 w-10 h-10 flex flex-col justify-center items-center gap-1.5 group">
                <span class="toggle-line block w-6 h-0.5 rounded-full transition-all duration-300 origin-center"></span>
                <span class="toggle-line block w-6 h-0.5 rounded-full transition-all duration-300 origin-center"></span>
                <span class="toggle-line block w-6 h-0.5 rounded-full transition-all duration-300 origin-center"></span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden fixed inset-0 top-[80px] lg:hidden backdrop-blur-sm bg-black/30 z-40">
        <nav class="bg-white h-screen overflow-y-auto py-6 px-6 space-y-2">
            <a href="{{ route('ppdb.index') }}#home" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors font-medium text-lg">Beranda</a>
            <a href="{{ route('ppdb.index') }}#features" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors font-medium text-lg">Keunggulan</a>
            <a href="{{ route('ppdb.index') }}#schools" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors font-medium text-lg">Sekolah</a>
            <a href="{{ route('ppdb.index') }}#faq" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors font-medium text-lg">FAQ</a>
            <a href="{{ route('ppdb.index') }}#contact" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors font-medium text-lg">Kontak</a>
            <hr class="my-4">
            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors font-medium text-lg">Masuk</a>
            <a href="{{ route('ppdb.index') }}#schools" class="block px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg text-center font-medium text-lg">Daftar Sekarang</a>
        </nav>
    </div>
</header>

<style>
    #mainNavbar {
        background: rgba(255, 255, 255, 0);
        backdrop-filter: none;
    }

    #mainNavbar.scrolled {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
    }

    .logo-text {
        background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transition: all 0.3s ease;
    }

    #mainNavbar.scrolled .logo-text {
        background: linear-gradient(135deg, #059669 0%, #1d4ed8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .logo-subtext {
        color: #10b981;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.85);
        font-weight: 500;
    }

    #mainNavbar.scrolled .nav-link {
        color: #374151;
    }

    .nav-link:hover {
        color: #10b981;
        background-color: rgba(16, 185, 129, 0.1);
    }

    #mainNavbar.scrolled .nav-link:hover {
        color: #10b981;
        background-color: rgba(16, 185, 129, 0.1);
    }

    .cta-login {
        color: rgba(255, 255, 255, 0.85);
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    #mainNavbar.scrolled .cta-login {
        color: #374151;
        border-color: #e5e7eb;
    }

    .cta-login:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
    }

    #mainNavbar.scrolled .cta-login:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }

    .toggle-line {
        background-color: rgba(255, 255, 255, 0.85);
        transition-property: all;
    }

    #mainNavbar.scrolled .toggle-line {
        background-color: #374151;
    }

    #mobileMenu.active {
        display: block !important;
    }
</style>

<script>
    const navbar = document.getElementById('mainNavbar');
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const toggleLines = document.querySelectorAll('.toggle-line');
    let isMenuOpen = false;

    // Handle mobile menu toggle
    mobileToggle.addEventListener('click', () => {
        isMenuOpen = !isMenuOpen;
        mobileMenu.classList.toggle('hidden');
        mobileMenu.classList.toggle('active');
        
        if (isMenuOpen) {
            toggleLines[0].style.transform = 'rotate(45deg) translate(10px, 10px)';
            toggleLines[1].style.opacity = '0';
            toggleLines[2].style.transform = 'rotate(-45deg) translate(7px, -7px)';
        } else {
            toggleLines.forEach(line => {
                line.style.transform = 'none';
                line.style.opacity = '1';
            });
        }
    });

    // Close mobile menu when clicking on links
    document.querySelectorAll('#mobileMenu a').forEach(link => {
        link.addEventListener('click', () => {
            isMenuOpen = false;
            mobileMenu.classList.add('hidden');
            mobileMenu.classList.remove('active');
            toggleLines.forEach(line => {
                line.style.transform = 'none';
                line.style.opacity = '1';
            });
        });
    });

    // Handle scroll effects
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Prevent body scroll when mobile menu is open
    document.addEventListener('DOMContentLoaded', () => {
        if (isMenuOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    });
</script>
