<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid px-4">
        <!-- Logo dan Nama Aplikasi -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('ppdb.index') }}">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo NUIST" class="me-2" style="height: 40px; width: auto;">
            {{-- <span class="fw-bold text-primary">PPDB </span> --}}
        </a>

        <!-- Toggle Button untuk Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>

    <!-- Menu Items (Always Visible on Desktop, Collapsible on Mobile) -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('ppdb.index') ? 'active' : '' }}" href="{{ route('ppdb.index') }}">Beranda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#sekolah">Sekolah</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#about">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#kontak">Kontak</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay" style="display: none;">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-lg-none">
        <div class="container-fluid px-4">
            <!-- Close Button -->
            <button class="btn btn-link text-dark ms-auto" onclick="closeMobileMenu()">
                <i class="bi bi-x-lg fs-2"></i>
            </button>
        </div>

        <div class="w-100">
            <ul class="navbar-nav flex-column text-center py-4">
                <li class="nav-item mb-3">
                    <a class="nav-link {{ request()->routeIs('ppdb.index') ? 'active' : '' }}" href="{{ route('ppdb.index') }}" onclick="closeMobileMenu()">Beranda</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#sekolah" onclick="closeMobileMenu()">Sekolah</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#about" onclick="closeMobileMenu()">About</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link" href="#kontak" onclick="closeMobileMenu()">Kontak</a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<script>
    // Mobile menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        const toggler = document.querySelector('.navbar-toggler');
        const overlay = document.getElementById('mobileMenuOverlay');

        if (toggler && overlay) {
            toggler.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';

                if (isExpanded) {
                    overlay.style.display = 'block';
                    setTimeout(() => {
                        overlay.style.opacity = '1';
                        overlay.classList.add('show');
                    }, 10);
                } else {
                    overlay.style.opacity = '0';
                    overlay.classList.remove('show');
                    setTimeout(() => {
                        overlay.style.display = 'none';
                    }, 300);
                }
            });
        }
    });

    function closeMobileMenu() {
        const overlay = document.getElementById('mobileMenuOverlay');
        const toggler = document.querySelector('.navbar-toggler');

        overlay.style.opacity = '0';
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);

        // Reset Bootstrap collapse state
        const bsCollapse = new bootstrap.Collapse(document.getElementById('navbarNav'), {
            hide: true
        });
    }

    // Close mobile menu when clicking outside
    document.getElementById('mobileMenuOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeMobileMenu();
        }
    });
</script>

<style>
    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .mobile-menu-overlay .navbar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        max-width: 300px;
        height: 100%;
        background: white;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .mobile-menu-overlay.show .navbar {
        transform: translateX(0);
    }

    /* Ensure navbar stays visible when scrolling */
    .navbar {
        position: sticky !important;
        top: 0 !important;
        z-index: 1030 !important;
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.95) !important;
        transition: all 0.3s ease;
    }

    .mobile-menu-overlay .nav-link {
        font-size: 1.1rem;
        padding: 1rem 2rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .mobile-menu-overlay .nav-link:hover {
        background-color: #f8f9fa;
    }
</style>
