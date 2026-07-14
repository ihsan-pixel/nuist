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
                                    <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}" loading="lazy" decoding="async">
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

    const mapSection = document.getElementById('map-section');
    let mapInitialized = false;
    const mapLocations = [
        @foreach($madrasahs as $madrasah)
            @if($madrasah->latitude && $madrasah->longitude)
                {
                    lat: {{ $madrasah->latitude }},
                    lng: {{ $madrasah->longitude }},
                    name: @json($madrasah->name),
                    kabupaten: @json($madrasah->kabupaten),
                    detailUrl: @json(route('landing.sekolah.detail', $madrasah->id))
                },
            @endif
        @endforeach
    ];

    function loadStylesheetOnce(href) {
        if (document.querySelector(`link[href="${href}"]`)) {
            return Promise.resolve();
        }

        return new Promise((resolve, reject) => {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = href;
            link.onload = resolve;
            link.onerror = reject;
            document.head.appendChild(link);
        });
    }

    function loadScriptOnce(src) {
        if (document.querySelector(`script[src="${src}"]`)) {
            return Promise.resolve();
        }

        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = src;
            script.async = true;
            script.onload = resolve;
            script.onerror = reject;
            document.body.appendChild(script);
        });
    }

    async function initializeMap() {
        if (mapInitialized || !mapSection) {
            return;
        }

        mapInitialized = true;

        try {
            await loadStylesheetOnce('https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
            await loadScriptOnce('https://unpkg.com/leaflet@1.9.4/dist/leaflet.js');

            const map = L.map('map', {
                preferCanvas: true
            }).setView([-7.7956, 110.3695], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let markerIndex = 0;
            const chunkSize = 20;

            function addMarkerChunk() {
                const end = Math.min(markerIndex + chunkSize, mapLocations.length);

                for (; markerIndex < end; markerIndex++) {
                    const location = mapLocations[markerIndex];
                    L.marker([location.lat, location.lng])
                        .addTo(map)
                        .bindPopup(`<b>${location.name}</b><br>${location.kabupaten}<br><a href="${location.detailUrl}">Lihat Detail</a>`);
                }

                if (markerIndex < mapLocations.length) {
                    requestAnimationFrame(addMarkerChunk);
                }
            }

            addMarkerChunk();
        } catch (error) {
            mapInitialized = false;
        }
    }

    if (mapSection) {
        const mapObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    initializeMap();
                    observer.disconnect();
                }
            });
        }, {
            rootMargin: '200px 0px'
        });

        mapObserver.observe(mapSection);
    }
});
</script>
