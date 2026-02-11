@extends('layouts.master-without-nav')

@section('title', 'Sekolah/Madrasah - NUIST')
@section('description', 'Daftar Sekolah/Madrasah Dibawah Naungan LPMNU PWNU DIY')

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

<!-- MAP SECTION -->
<section id="map-section" class="map-section">
    <div class="container">
        <h2 class="section-title animate fade-up" style="margin-bottom:50px; font-size:24px;">Peta Lokasi Madrasah di Yogyakarta</h2>
        <div id="map"></div>
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
                        <a href="{{ route('landing.sekolah.detail', $madrasah->id) }}" class="school-card-link">
                            <div class="school-card">
                                <div class="school-logo">
                                    <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}">
                                </div>
                                <div class="school-info">
                                    <h3>{{ $madrasah->name }}</h3>
                                    <p>{{ $madrasah->kabupaten }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</section>

@include('landing.footer')

@endsection

@include('landing._sekolah_styles')

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

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

    // Initialize Leaflet Map
    const map = L.map('map').setView([-7.7956, 110.3695], 10); // Yogyakarta coordinates

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add markers for each madrasah
    @foreach($madrasahs as $madrasah)
        @if($madrasah->latitude && $madrasah->longitude)
            L.marker([{{ $madrasah->latitude }}, {{ $madrasah->longitude }}])
                .addTo(map)
                .bindPopup('<b>{{ $madrasah->name }}</b><br>{{ $madrasah->kabupaten }}<br><a href="{{ route("landing.sekolah.detail", $madrasah->id) }}">Lihat Detail</a>');
        @endif
    @endforeach
});
</script>

