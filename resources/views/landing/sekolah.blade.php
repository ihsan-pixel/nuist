@extends('layouts.master-without-nav')

@section('title', 'Sekolah/Madrasah - NUIST')
@section('description', 'Daftar Sekolah/Madrasah dessous naungan LPMNU PWNU DIY')

@section('content')
@include('landing.navbar')

<!-- HERO -->
<section id="hero" class="hero">
    <div class="container">
        <h1 class="hero-title">Sekolah/Madrasah</h1>
        <h1 class="hero-subtitle" style="color: #eda711">Dibawah Naungan LPMNU PWNU DIY</h1>
        <p>Temukan sekolah dan madrasah yang menjadi bagian dari ekosistem pendidikan kami. Klik pada sekolah untuk melihat profil lengkapnya.</p>
    </div>
</section>

<!-- SEKOLAH LIST -->
<section id="sekolah-list" class="sekolah-list">
    <div class="container">
        <h2 class="section-title animate fade-up" style="margin-bottom:50px; font-size:24px;">Daftar Sekolah/Madrasah</h2>

        @foreach($groupedMadrasahs as $kabupaten => $madrasahList)
            <div class="kabupaten-section animate fade-up">
                <h3 class="kabupaten-header">
                    <span class="kabupaten-icon">📍</span>
                    {{ $kabupaten }}
                    <span class="kabupaten-count">({{ count($madrasahList) }} sekolah)</span>
                </h3>
                <div class="schools-grid">
                    @foreach($madrasahList as $madrasah)
                        <div class="school-card"
                             data-id="{{ $madrasah->id }}"
                             data-name="{{ $madrasah->name }}"
                             data-logo="{{ asset('storage/' . $madrasah->logo) }}"
                             data-kabupaten="{{ $madrasah->kabupaten }}"
                             data-scod="{{ $madrasah->scod ?? '-' }}"
                             data-slug="{{ $madrasah->slug ?? '#' }}"
                             data-alamat="{{ $madrasah->alamat ?? 'Belum ada data alamat' }}"
                             data-email="{{ $madrasah->email ?? '-' }}"
                             data-telepon="{{ $madrasah->telepon ?? '-' }}"
                             onclick="openSchoolModal(this)">
                            <div class="school-logo">
                                <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}">
                            </div>
                            <div class="school-info">
                                <h3>{{ $madrasah->name }}</h3>
                                <p>{{ $madrasah->kabupaten }}</p>
                                @if($madrasah->scod)
                                    <span class="scod-badge">SCOD: {{ $madrasah->scod }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

@include('landing.footer')

<!-- MODAL PROFILE SEKOLAH -->
<div id="schoolModal" class="modal-overlay" onclick="closeModalOutside(event)">
    <div class="modal-container">
        <button class="modal-close" onclick="closeSchoolModal()">&times;</button>
        <div class="modal-header">
            <div class="modal-header-content">
                <div class="modal-logo" style="margin-top: 20px; margin-left: 20px; margin-bottom: 20px;">
                    <img id="modalLogo" src="" alt="Logo Sekolah">
                </div>
                <div class="modal-title-group" style="margin-left:20px;">
                    <h2 id="modalName">Nama Sekolah</h2>
                    <span id="modalKabupaten" class="modal-subtitle">📍 Kabupaten</span>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="modal-info-grid">
                <div class="modal-info-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <div>
                        <span class="modal-label">Alamat</span>
                        <span id="modalAlamat" class="modal-value">Alamat sekolah</span>
                    </div>
                </div>
                <div class="modal-info-item">
                    <i class="bi bi-award-fill"></i>
                    <div>
                        <span class="modal-label">Kode SCOD</span>
                        <span id="modalScod" class="modal-value">SCOD</span>
                    </div>
                </div>
                <div class="modal-info-item">
                    <i class="bi bi-envelope-fill"></i>
                    <div>
                        <span class="modal-label">Email</span>
                        <span id="modalEmail" class="modal-value">email@sekolah.sch.id</span>
                    </div>
                </div>
                <div class="modal-info-item">
                    <i class="bi bi-telephone-fill"></i>
                    <div>
                        <span class="modal-label">Telepon</span>
                        <span id="modalTelepon" class="modal-value">081234567890</span>
                    </div>
                </div>
            </div>
            <div class="modal-actions">
                <a id="modalPpdbLink" href="#" class="btn-modal-primary">
                    <i class="bi bi-pencil-square"></i> PPDB Sekolah Ini
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

<style>
    /* MODAL STYLES */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
    }

    .modal-container {
        background: white;
        border-radius: 32px;
        max-width: 1000px;
        width: 95%;
        position: relative;
        box-shadow: 0 30px 100px rgba(0, 0, 0, 0.4);
        transform: scale(0.85) translateY(20px);
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
        margin: 20px;
    }

    .modal-overlay.active .modal-container {
        transform: scale(1) translateY(0);
    }

    .modal-close {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        font-size: 28px;
        font-weight: 700;
        color: #dc3545;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .modal-close:hover {
        background: #dc3545;
        color: white;
        transform: rotate(90deg) scale(1.1);
    }

    .modal-header {
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        padding: 30px;
        text-align: left;
        position: relative;
    }

    .modal-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 25px 25px;
        pointer-events: none;
    }

    .modal-header-content {
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    .modal-logo {
        width: 90px;
        height: 90px;
        background: white;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
        padding: 10px;
    }

    .modal-logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .modal-title-group {
        flex: 1;
    }

    .modal-header h2 {
        color: white;
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 8px;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
        line-height: 1.3;
    }

    .modal-subtitle {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.25);
        color: white;
        padding: 6px 16px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .modal-body {
        padding: 35px 40px 40px;
    }

    .modal-info-grid {
        display: grid;
        gap: 18px;
    }

    .modal-info-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 18px 20px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 16px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .modal-info-item:hover {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        transform: translateX(8px);
        border-color: rgba(0, 75, 76, 0.1);
    }

    .modal-info-item i {
        font-size: 26px;
        color: #004b4c;
        margin-top: 3px;
        min-width: 26px;
    }

    .modal-info-item > div {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .modal-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .modal-value {
        font-size: 16px;
        color: #1e293b;
        font-weight: 700;
        word-break: break-word;
        line-height: 1.4;
    }

    .modal-actions {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    .btn-modal-primary {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 16px 36px;
        background: linear-gradient(135deg, #00393a, #005555, #004b4c);
        color: white;
        text-decoration: none;
        border-radius: 14px;
        font-weight: 700;
        font-size: 17px;
        transition: all 0.3s ease;
        box-shadow: 0 6px 25px rgba(0, 75, 76, 0.35);
        border: none;
        cursor: pointer;
    }

    .btn-modal-primary:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 40px rgba(0, 75, 76, 0.45);
        background: linear-gradient(135deg, #004b4c, #006666, #005555);
    }

    /* Responsive Modal */
    @media (max-width: 600px) {
        .modal-container {
            max-width: 100%;
            width: 100%;
            border-radius: 24px 24px 0 0;
            margin: 0;
            position: fixed;
            bottom: 0;
            top: auto;
            transform: translateY(100%);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-overlay.active .modal-container {
            transform: translateY(0);
        }

        .modal-header {
            padding: 35px 25px 30px;
            border-radius: 24px 24px 0 0;
        }

        .modal-logo {
            width: 85px;
            height: 85px;
        }

        .modal-header h2 {
            font-size: 22px;
        }

        .modal-subtitle {
            font-size: 14px;
            padding: 6px 16px;
        }

        .modal-body {
            padding: 25px;
            padding-bottom: 40px;
        }

        .modal-info-item {
            padding: 14px 16px;
        }

        .modal-info-item i {
            font-size: 22px;
        }

        .modal-value {
            font-size: 15px;
        }

        .btn-modal-primary {
            width: 100%;
            justify-content: center;
            padding: 14px 24px;
            font-size: 16px;
        }
    }
</style>

<script>
    function openSchoolModal(element) {
        const modal = document.getElementById('schoolModal');

        // Get data from attributes
        const name = element.getAttribute('data-name');
        const logo = element.getAttribute('data-logo');
        const kabupaten = element.getAttribute('data-kabupaten');
        const scod = element.getAttribute('data-scod');
        const slug = element.getAttribute('data-slug');
        const alamat = element.getAttribute('data-alamat');
        const email = element.getAttribute('data-email');
        const telepon = element.getAttribute('data-telepon');

        // Populate modal
        document.getElementById('modalName').textContent = name;
        document.getElementById('modalLogo').src = logo;
        document.getElementById('modalKabupaten').textContent = '📍 ' + kabupaten;
        document.getElementById('modalScod').textContent = scod;
        document.getElementById('modalAlamat').textContent = alamat;
        document.getElementById('modalEmail').textContent = email;
        document.getElementById('modalTelepon').textContent = telepon;

        // Set PPDB link
        document.getElementById('modalPpdbLink').href = '/ppdb/sekolah/' + slug;

        // Show modal with animation
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('active');
        }, 10);

        // Disable body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeSchoolModal() {
        const modal = document.getElementById('schoolModal');
        modal.classList.remove('active');

        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);

        // Enable body scroll
        document.body.style.overflow = 'auto';
    }

    function closeModalOutside(event) {
        const modalContainer = document.querySelector('.modal-container');
        if (event.target === event.currentTarget) {
            closeSchoolModal();
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSchoolModal();
        }
    });

    // Prevent modal close when clicking inside container
    document.querySelector('.modal-container').addEventListener('click', function(e) {
        e.stopPropagation();
    });
</script>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #ffffff;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HERO */
    .hero {
        padding: 100px 20px;
        color: white;
        text-align: center;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin: 65px auto 0;
        min-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        color: white;
    }

    .hero-subtitle {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .hero p {
        font-size: 20px;
        opacity: 0.9;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    /* SEKOLAH LIST */
    .sekolah-list {
        padding: 80px 0;
        background: #f8fafc;
    }

    .kabupaten-section {
        margin-bottom: 60px;
    }

    .kabupaten-header {
        font-size: 24px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #eda711;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kabupaten-icon {
        font-size: 28px;
    }

    .kabupaten-count {
        font-size: 16px;
        font-weight: 400;
        color: #6b7280;
        margin-left: auto;
    }

    .scod-badge {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 12px;
        background: #004b4c;
        color: white;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .schools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .school-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .school-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .school-link {
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .school-logo {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        padding: 20px;
    }

    .school-logo img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .school-info {
        padding: 20px;
        text-align: center;
    }

    .school-info h3 {
        font-size: 20px;
        font-weight: 600;
        color: #004b4c;
        margin-bottom: 8px;
    }

    .school-info p {
        color: #6b7280;
        font-size: 14px;
    }

    .section-title {
        text-align: center;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 60px;
        color: #004b4c;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        width: 0;
        height: 3px;
        background-color: #eda711;
        transition: width 0.3s ease, left 0.3s ease;
    }

    .section-title.active::after {
        width: 50%;
        left: 25%;
    }

    section:hover .section-title::after {
        width: 100%;
        left: 0;
    }

    /* ANIMATION */
    .animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-up {
        transform: translateY(30px);
    }

    .fade-up.delay-1 {
        transition-delay: 0.2s;
    }

    .fade-up.delay-2 {
        transition-delay: 0.4s;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 80px 20px;
            margin-top: 80px;
            min-height: auto;
        }

        .hero h1 {
            font-size: 32px;
        }

        .hero-subtitle {
            font-size: 28px;
        }

        .hero p {
            font-size: 16px;
        }

        .schools-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .school-card {
            margin: 0 10px;
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 40px;
        }
    }

    /* Custom Cursor Effect */
    .cursor-small {
        position: fixed;
        width: 10px;
        height: 10px;
        background-color: #00ff00;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9999;
        transition: transform 0.1s ease;
    }

    .cursor-large {
        position: fixed;
        width: 30px;
        height: 30px;
        background-color: #00ff88;
        border-radius: 50%;
        pointer-events: none;
        z-index: 9998;
        transition: transform 0.15s ease;
        opacity: 0.5;
    }
</style>

<script>
// Section active on scroll and animation trigger
document.addEventListener('DOMContentLoaded', function () {
    const animateElements = document.querySelectorAll('.animate');
    if (animateElements.length > 0) {
        const animateObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                    }
                });
            },
            {
                threshold: 0.15
            }
        );

        animateElements.forEach(el => {
            animateObserver.observe(el);

            // Tampilkan langsung kalau sudah terlihat
            if (el.getBoundingClientRect().top < window.innerHeight) {
                el.classList.add('show');
            }
        });
    }

    // Custom Cursor Effect
    const cursorSmall = document.createElement('div');
    cursorSmall.className = 'cursor-small';
    document.body.appendChild(cursorSmall);

    const cursorLarge = document.createElement('div');
    cursorLarge.className = 'cursor-large';
    document.body.appendChild(cursorLarge);

    let mouseX = 0;
    let mouseY = 0;
    let cursorSmallX = 0;
    let cursorSmallY = 0;
    let cursorLargeX = 0;
    let cursorLargeY = 0;

    // Track mouse movement
    document.addEventListener('mousemove', function(e) {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Animate cursor positions
    function animateCursor() {
        // Smooth follow for small cursor
        cursorSmallX += (mouseX - cursorSmallX) * 0.2;
        cursorSmallY += (mouseY - cursorSmallY) * 0.2;

        // Slower follow for large cursor
        cursorLargeX += (mouseX - cursorLargeX) * 0.1;
        cursorLargeY += (mouseY - cursorLargeY) * 0.1;

        cursorSmall.style.left = cursorSmallX - 5 + 'px';
        cursorSmall.style.top = cursorSmallY - 5 + 'px';

        cursorLarge.style.left = cursorLargeX - 15 + 'px';
        cursorLarge.style.top = cursorLargeY - 15 + 'px';

        requestAnimationFrame(animateCursor);
    }

    animateCursor();

    // Hide cursors on mobile devices
    if ('ontouchstart' in window) {
        cursorSmall.style.display = 'none';
        cursorLarge.style.display = 'none';
    }
});
</script>

