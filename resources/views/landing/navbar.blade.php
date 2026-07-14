<style>
/* NAVBAR Styles */
.navbar {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(18px);
    position: fixed;
    top: 8px;
    width: min(1400px, calc(100% - 48px));
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    z-index: 1000;
    border-radius: 999px;
    border: 1px solid rgba(25, 43, 38, 0.08);
    box-shadow: none;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar.transparent {
    background: rgba(255, 255, 255, 0.84);
    backdrop-filter: blur(22px);
}

.navbar.full-width {
    width: 100%;
    top: 0;
    border-radius: 0;
    position: fixed;
    left: 0;
    right: 0;
    border-left: none;
    border-right: none;
    border-top: none;
    box-shadow: 0 1px 0 rgba(25, 43, 38, 0.08), 0 12px 30px rgba(25, 43, 38, 0.08);
}

.navbar.scrolled {
    background: rgba(255, 255, 255, 0.84);
    backdrop-filter: blur(22px);
}

.navbar.full-width .nav-flex {
    max-width: 1600px;
    width: 100%;
    margin: 0 auto;
    padding: 0 20px;
    height: 56px;
}

.navbar.full-width .nav-left {
    width: 100%;
    justify-content: space-between;
    gap: 24px;
}

.navbar.full-width .brand-mark {
    padding-left: 6px;
}

.navbar.full-width .brand-mark img {
    height: 28px;
}

.navbar.full-width .nav-menu {
    padding: 4px 8px;
    background: #fcfcfa;
    border: 1px solid rgba(25, 43, 38, 0.08);
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
}

.navbar.full-width .nav-menu a {
    padding: 5px 16px;
}

.navbar.full-width .desktop-login {
    display: none;
}

.nav-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 50px;
    transition: justify-content 0.3s ease;
}

.nav-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.brand-mark {
    display: flex;
    align-items: center;
    flex-shrink: 0;
    padding-left: 10px;
}

.brand-mark img {
    height: 30px;
    width: auto;
    display: block;
}

.nav-menu {
    list-style: none;
    display: flex;
    gap: 4px;
    align-items: center;
    margin: 0;
    padding: 3px;
    border-radius: 999px;
}

.nav-menu a {
    text-decoration: none;
    color: #657381;
    font-weight: 400;
    font-size: 14px;
    padding: 4px 12px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0);
    transition: background 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.nav-menu a:hover {
    color: #1a8b57;
    background: rgba(37, 166, 91, 0.08);
}

.nav-menu a.active {
    color: #1a8b57;
    background: #f3f8f2;
    box-shadow: inset 0 0 0 1px rgba(37, 166, 91, 0.06);
}

.nav-menu .mobile-only {
    display: none;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 4px 10px;
    border-radius: 999px;
    font-weight: 400;
    font-size: 14px;
    color: #157347;
    background: #ffffff;
    border: 1px solid rgba(21, 115, 71, 0.14);
    box-shadow: 0 8px 22px rgba(25, 43, 38, 0.08);
    text-decoration: none;
    margin-right: 18px;
    transition: transform 0.25s ease, box-shadow 0.25s ease, color 0.25s ease, border-color 0.25s ease;
}

.btn-primary:hover {
    color: #0f5c38;
    border-color: rgba(21, 115, 71, 0.22);
    box-shadow: 0 12px 26px rgba(25, 43, 38, 0.12);
    transform: translateY(-1px);
}

.btn-primary i {
    font-size: 17px;
}

/* DROPDOWN SUBMENU */
.dropdown {
    position: relative;
}

.dropdown:hover .submenu,
.dropdown.open .submenu {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.arrow {
    display: inline-block;
    transition: transform 0.3s;
    transform: rotate(0deg);
    font-size: 18px;
    vertical-align: middle;
    margin-left: 4px;
}

.dropdown:hover .arrow,
.dropdown.open .arrow {
    transform: rotate(-180deg);
}

.submenu {
    position: absolute;
    top: 110%;
    left: 0;
    min-width: 240px;
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid rgba(25, 43, 38, 0.08);
    box-shadow: 0 16px 38px rgba(25, 43, 38, 0.1);
    padding: 12px;
    display: none;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.3s ease;
    z-index: 999;
}

.dropdown:hover .submenu,
.dropdown.open .submenu {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.submenu li {
    list-style: none;
}

.submenu li a {
    display: block;
    padding: 12px 14px;
    border-radius: 14px;
    font-size: 14px;
    color: #475467;
    text-decoration: none;
    transition: all 0.25s ease;
}

.submenu li a:hover {
    background: #f3f8f2;
    color: #1a8b57;
}

/* Hamburger Menu */
.hamburger {
    display: none;
    flex-direction: column;
    cursor: pointer;
    gap: 4px;
}

.hamburger span {
    width: 25px;
    height: 2px;
    background: #475467;
    border-radius: 999px;
    transition: 0.3s;
}

.hamburger.open span:nth-child(1) {
    transform: rotate(-45deg) translate(-5px, 6px);
}

.hamburger.open span:nth-child(2) {
    opacity: 0;
}

.hamburger.open span:nth-child(3) {
    transform: rotate(45deg) translate(-5px, -6px);
}

/* Responsive */
@media (max-width: 768px) {
    .navbar {
        width: 95%;
        margin: 10px auto;
        padding: 0 15px;
        left: 0;
        right: 0;
        border-radius: 32px;
    }

    .navbar.full-width {
        width: 95%;
        margin: 10px auto;
        border-radius: 32px;
        border: 1px solid rgba(25, 43, 38, 0.08);
        left: 0;
        right: 0;
        box-shadow: 0 16px 36px rgba(25, 43, 38, 0.12);
    }

    .navbar.full-width .nav-flex {
        padding: 0;
        height: 42px;
    }

    .navbar.full-width .nav-left {
        width: auto;
        justify-content: flex-start;
    }

    .nav-left {
        gap: 14px;
    }

    .brand-mark {
        padding-left: 0;
    }

    .brand-mark img {
        height: 26px;
    }

    .nav-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 12px;
        right: 12px;
        width: auto;
        background: #ffffff;
        flex-direction: column;
        gap: 0;
        padding: 14px;
        border-radius: 28px;
        border: 1px solid rgba(25, 43, 38, 0.08);
        box-shadow: 0 16px 36px rgba(25, 43, 38, 0.12);
        z-index: 1000;
    }

    .nav-menu.show {
        display: flex;
    }

    .nav-menu a {
        width: 100%;
        padding: 8px 12px;
        border-radius: 18px;
        text-align: center;
    }

    .nav-menu .mobile-only {
        display: block;
    }

    .hamburger {
        display: flex;
    }

    .nav-flex {
        height: 42px;
    }

    .btn-primary.desktop-login {
        display: none;
    }
}

@media (max-width: 480px) {
    .navbar {
        width: 98%;
        margin: 5px auto;
        height: 60px;
        left: 0;
        right: 0;
    }
}
</style>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="container nav-flex">
        <div class="nav-left">
            <a href="{{ route('landing') }}" class="brand-mark" aria-label="NUIST">
                <img src="{{ asset('images/logo1.png') }}" alt="NUIST">
            </a>
            <ul class="nav-menu" id="nav-menu">
                <li><a href="{{ route('landing') }}" class="{{ request()->routeIs('landing') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('landing.sekolah') }}" class="{{ request()->routeIs('landing.sekolah') ? 'active' : '' }}">Sekolah</a></li>
                <li><a href="{{ route('talenta.login') }}" class="{{ request()->routeIs('talenta.login') ? 'active' : '' }}">Talenta</a></li>
                <li><a href="{{ route('mgmp.public') }}" class="{{ request()->routeIs('mgmp.public') ? 'active' : '' }}">MGMP</a></li>
                <li class="mobile-only"><a href="{{ route('login') }}">Login</a></li>
                {{-- <li class="dropdown">
                    <a href="#" onclick="toggleSubmenu(event)">Fitur <i class='bx bx-chevron-down arrow'></i></a>
                    <ul class="submenu">
                        <li><a href="{{ route('talenta.login')}}">TALENTA</a></li>
                        <li><a href="{{ route('mgmp.public')}}">MGMP</a></li>
                    </ul>
                </li>
                {{-- <li><a href="{{ route('landing.tentang') }}" class="{{ request()->routeIs('landing.tentang') ? 'active' : '' }}">Tentang</a></li>
                <li><a href="{{ route('landing.kontak') }}" class="{{ request()->routeIs('landing.kontak') ? 'active' : '' }}">Kontak</a></li> --}}
            </ul>
            <div class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <a href="{{ route('login') }}" class="btn-primary desktop-login">Login<i class='bx bx-arrow-back bx-rotate-180'></i></a>
    </div>
</nav>

<script>
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function smoothScrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

function toggleSubmenu(e) {
    e.preventDefault();
    e.stopPropagation();

    const dropdown = e.target.closest('.dropdown');
    const isOpen = dropdown.classList.contains('open');

    document.querySelectorAll('.dropdown').forEach(drop => {
        drop.classList.remove('open');
    });

    if (!isOpen) {
        dropdown.classList.add('open');
    }
}

function toggleMobileMenu() {
    const navMenu = document.getElementById('nav-menu');
    const hamburger = document.getElementById('hamburger');
    navMenu.classList.toggle('show');
    hamburger.classList.toggle('open');
}

// Close submenu when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown').forEach(drop => {
            drop.classList.remove('open');
        });
    }

    if (!e.target.closest('.nav-left') && !e.target.closest('.hamburger')) {
        const navMenu = document.getElementById('nav-menu');
        const hamburger = document.getElementById('hamburger');
        if (navMenu && hamburger) {
            navMenu.classList.remove('show');
            hamburger.classList.remove('open');
        }
    }
});

// Navbar scroll effect
let ticking = false;
window.addEventListener('scroll', function() {
    if (!ticking) {
        requestAnimationFrame(function() {
            const navbar = document.querySelector('.navbar');
            if (navbar) {
                if (window.scrollY > 50) {
                    navbar.classList.add('transparent');
                    navbar.classList.add('full-width');
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('transparent');
                    navbar.classList.remove('full-width');
                    navbar.classList.remove('scrolled');
                }
            }
            ticking = false;
        });
        ticking = true;
    }
});
</script>
