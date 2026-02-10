<style>
/* NAVBAR Styles */
.navbar {
    background: rgb(255, 255, 255);
    backdrop-filter: blur(10px);
    position: fixed;
    top: 20px;
    width: 1400px;
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    z-index: 1000;
    border-radius: 50px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar.transparent {
    background: rgb(255, 255, 255);
    backdrop-filter: blur(20px);
}

.navbar.full-width {
    width: 100%;
    top: 0;
    border-radius: 0 0 28px 28px;
    position: fixed;
    left: 0;
    right: 0;
}

.navbar.scrolled {
    top: 0px;
}

.nav-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 80px;
    transition: justify-content 0.3s ease;
}

.nav-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.nav-menu {
    list-style: none;
    display: flex;
    gap: 20px;
    align-items: center;
    margin-top: 20px;
}

.nav-menu a {
    text-decoration: none;
    color: #004b4c;
    font-weight: 500;
    font-size: 18px;
    padding: 8px 16px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0);
    transition: transform 0.3s ease, box-shadow 0.3s ease, background 0.3s ease, color 0.3s ease;
    transform: translateY(0) scale(1);
    box-shadow: 0 0 0 rgba(0, 75, 76, 0);
}

.nav-menu a:hover, .nav-menu a.active {
    color: #fefefe;
    background: linear-gradient(135deg, #004b4c, #006666);
}

.btn-primary {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 26px;
    border-radius: 999px;
    font-weight: 600;
    color: #fff;
    background: linear-gradient(135deg, #004b4c, #006666);
    overflow: hidden;
    z-index: 1;
}

.btn-primary::before {
    content: '';
    position: absolute;
    width: 0;
    height: 0;
    min-width: 0;
    min-height: 0;
    background: rgba(2, 2, 2, 0.976);
    border-radius: 50%;
    bottom: -60%;
    right: -60%;
    transition: width 0.55s ease-out, height 0.55s ease-out;
    z-index: -1;
}

.btn-primary:hover::before {
    width: 380%;
    height: 380%;
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
    font-size: 20px;
    vertical-align: middle;
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
    border-radius: 14px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.12);
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
    border-radius: 10px;
    font-size: 14px;
    color: #004b4c;
    text-decoration: none;
    transition: all 0.25s ease;
}

.submenu li a:hover {
    background: #f1f5ff;
    color: #eda711;
    padding-left: 18px;
}

/* USER PROFILE */
.user-profile {
    position: relative;
}

.profile-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid #004b4c;
    transition: all 0.3s ease;
    overflow: hidden;
}

.profile-avatar:hover {
    border-color: #006666;
    transform: scale(1.05);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.default-avatar {
    background: linear-gradient(135deg, #004b4c, #006666);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
}

.profile-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    min-width: 250px;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    display: none;
    z-index: 1000;
    margin-top: 10px;
}

.profile-menu.show {
    opacity: 1;
    transform: translateY(0);
    display: block;
}

.profile-info {
    padding: 20px;
    border-bottom: 1px solid rgba(0, 75, 76, 0.1);
    text-align: center;
}

.profile-name {
    font-weight: 600;
    color: #004b4c;
    margin: 0 0 5px 0;
    font-size: 16px;
}

.profile-email {
    color: #666;
    margin: 0;
    font-size: 14px;
}

.profile-actions {
    padding: 10px 0;
}

.profile-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #004b4c;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
}

.profile-link:hover {
    background: rgba(0, 75, 76, 0.1);
    color: #006666;
}

.logout-link:hover {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
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
    height: 3px;
    background: #004b4c;
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
    }

    .navbar.full-width {
        width: 100%;
        margin: 0;
        border-radius: 0;
        left: 0;
        right: 0;
    }

    .nav-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #ffffff;
        flex-direction: column;
        gap: 0;
        padding: 20px 0;
        border-radius: 0 0 28px 28px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        z-index: 1000;
    }

    .nav-menu.show {
        display: flex;
    }

    .nav-menu a {
        padding: 15px 20px;
        border-radius: 0;
        text-align: center;
    }

    .hamburger {
        display: flex;
    }

    .nav-flex {
        height: 60px;
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
            <img src="<?php echo e(asset('images/tpt logo.png')); ?>" alt="Logo" style="height: 50px; margin-left: 20px;">
            <ul class="nav-menu" id="nav-menu">
                <li><a href="<?php echo e(route('talenta.dashboard')); ?>" class="<?php echo e(request()->routeIs('talenta.dashboard') ? 'active' : ''); ?>">Dashboard</a></li>
                <li><a href="<?php echo e(route('talenta.data')); ?>" class="<?php echo e(request()->routeIs('talenta.data') ? 'active' : ''); ?>">Data Talenta</a></li>
                <li><a href="<?php echo e(route('talenta.instrumen-penilaian')); ?>" class="<?php echo e(request()->routeIs('talenta.instrumen-penilaian') ? 'active' : ''); ?>">Instrumen Penilaian</a></li>
                <li><a href="<?php echo e(route('talenta.tugas-level-1')); ?>" class="<?php echo e(request()->routeIs('talenta.tugas-level-1') ? 'active' : ''); ?>">Tugas</a></li>
            </ul>
            <div class="hamburger" id="hamburger" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="user-profile">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::check()): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->avatar): ?>
                    <div class="profile-avatar" onclick="toggleProfileMenu()">
                        <img src="<?php echo e(asset('storage/' . Auth::user()->avatar)); ?>" alt="Profile">
                    </div>
                <?php else: ?>
                    <div class="profile-avatar default-avatar" onclick="toggleProfileMenu()">
                        <i class='bx bx-user'></i>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="profile-menu" id="profileMenu">
                    <div class="profile-info">
                        <p class="profile-name"><?php echo e(Auth::user()->name); ?></p>
                        <p class="profile-email"><?php echo e(Auth::user()->email); ?></p>
                    </div>
                    <div class="profile-actions">
                        <a href="<?php echo e(route('mobile.profile')); ?>" class="profile-link">
                            <i class='bx bx-user'></i>
                            Profile
                        </a>
                        <a href="<?php echo e(route('mobile.talenta.index')); ?>" class="profile-link">
                            <i class='bx bx-list-ul'></i>
                            Data Talenta
                        </a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="profile-link logout-link" style="border: none; background: none; width: 100%; text-align: left; cursor: pointer;">
                                <i class='bx bx-log-out'></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('talenta.login')); ?>" class="btn-primary desktop-login" style="margin-right: 20px;">Login<i class='bx bx-arrow-back bx-rotate-180'></i></a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
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

function toggleProfileMenu() {
    const profileMenu = document.getElementById('profileMenu');
    profileMenu.classList.toggle('show');
}

// Close submenu when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown').forEach(drop => {
            drop.classList.remove('open');
        });
    }

    if (!e.target.closest('.user-profile')) {
        const profileMenu = document.getElementById('profileMenu');
        if (profileMenu) {
            profileMenu.classList.remove('show');
        }
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

<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/navbar.blade.php ENDPATH**/ ?>