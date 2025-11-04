@extends('layouts.mobile')

@section('title', 'Monitor Map Presensi')
@section('subtitle', 'Peta Lokasi Presensi')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .header-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .header-card h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .header-card h5 {
            font-size: 14px;
        }

        .map-container {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        #presensi-map {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            font-size: 10px;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 4px;
        }

        .stats-card {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .stat-item {
            text-align: center;
            padding: 8px;
            border-radius: 8px;
        }

        .stat-present {
            background: rgba(14, 133, 73, 0.1);
            color: #0e8549;
        }

        .stat-absent {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .stat-number {
            font-weight: 600;
            font-size: 16px;
            display: block;
        }

        .stat-label {
            font-size: 10px;
        }

        .date-selector {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .date-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            font-size: 12px;
        }

        .menu-button {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 8px;
            padding: 10px;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
            width: 100%;
            margin-bottom: 8px;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .menu-button:hover {
            color: #fff;
            text-decoration: none;
        }
    </style>

    <!-- Header -->
    <div class="header-card">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Monitor Presensi</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- Date Selector -->
    <div class="date-selector">
        <form method="GET" action="{{ route('mobile.monitor-map') }}">
            <label class="form-label" style="font-weight: 600; font-size: 12px;">Pilih Tanggal:</label>
            <input type="date" name="date" class="date-input"
                   value="{{ $selectedDate->format('Y-m-d') }}"
                   max="{{ now()->format('Y-m-d') }}">
            <button type="submit" class="menu-button" style="margin-top: 8px;">Tampilkan</button>
        </form>
    </div>

    <!-- Map Container -->
    <div class="map-container">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-map"></i>
            </div>
            <h6 class="section-title mb-0">Peta Lokasi Presensi</h6>
        </div>

        <!-- Map -->
        <div id="presensi-map"></div>

        <!-- Legend -->
        <div class="legend">
            <div class="legend-item">
                <div class="legend-dot" style="background: #0e8549;"></div>
                <span>Sudah Presensi</span>
            </div>
            <div class="legend-item">
                <div class="legend-dot" style="background: #dc3545;"></div>
                <span>Belum Presensi</span>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-card">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-bar-chart"></i>
            </div>
            <h6 class="section-title mb-0">Statistik Hari Ini</h6>
        </div>

        <div class="stats-grid">
            <div class="stat-item stat-present">
                <span class="stat-number">{{ $presensis->count() }}</span>
                <span class="stat-label">Sudah Presensi</span>
            </div>
            <div class="stat-item stat-absent">
                <span class="stat-number">{{ $belumPresensi->count() }}</span>
                <span class="stat-label">Belum Presensi</span>
            </div>
        </div>
    </div>

    <!-- Menu Buttons -->
    <a href="{{ route('mobile.monitor-presensi') }}" class="menu-button">
        <i class="bx bx-list-ul me-1"></i>
        Lihat Daftar Presensi
    </a>

    <a href="{{ route('mobile.presensi') }}" class="menu-button">
        <i class="bx bx-log-in-circle me-1"></i>
        Kembali ke Presensi
    </a>

    <a href="{{ route('mobile.dashboard') }}" class="menu-button">
        <i class="bx bx-home me-1"></i>
        Dashboard
    </a>
</div>
@endsection

@section('script')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('presensi-map').setView([-6.2088, 106.8456], 13); // Default Jakarta

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Custom icons
    const presensiIcon = L.divIcon({
        html: '<div style="background: #0e8549; width: 14px; height: 14px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 6px rgba(0,0,0,0.3);"></div>',
        className: 'custom-marker',
        iconSize: [14, 14],
        iconAnchor: [7, 7]
    });

    const belumPresensiIcon = L.divIcon({
        html: '<div style="background: #dc3545; width: 14px; height: 14px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 6px rgba(0,0,0,0.3);"></div>',
        className: 'custom-marker',
        iconSize: [14, 14],
        iconAnchor: [7, 7]
    });

    // Add markers
    const mapData = @json($mapData);
    let bounds = [];

    mapData.forEach(function(user) {
        const lat = parseFloat(user.latitude);
        const lng = parseFloat(user.longitude);

        if (!isNaN(lat) && !isNaN(lng)) {
            bounds.push([lat, lng]);

            const icon = user.marker_type === 'presensi' ? presensiIcon : belumPresensiIcon;

            const marker = L.marker([lat, lng], { icon: icon }).addTo(map);

            // Create popup content
            let popupContent = `
                <div style="font-family: 'Poppins', sans-serif; font-size: 12px; max-width: 220px;">
                    <strong style="color: #004b4c;">${user.name}</strong><br>
                    <small style="color: #666;">${user.status_kepegawaian}</small><br>
                    <small><strong>Status:</strong> ${user.marker_type === 'presensi' ? '<span style="color: #0e8549;">Sudah Presensi</span>' : '<span style="color: #dc3545;">Belum Presensi</span>'}</small><br>
            `;

            if (user.marker_type === 'presensi') {
                popupContent += `
                    <small><strong>Masuk:</strong> ${user.waktu_masuk || '-'}</small><br>
                    <small><strong>Keluar:</strong> ${user.waktu_keluar || '-'}</small><br>
                `;
            }

            popupContent += `
                    <small><strong>Lokasi:</strong> ${user.lokasi}</small>
                </div>
            `;

            marker.bindPopup(popupContent);
        }
    });

    // Fit map to show all markers
    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [30, 30] });
    }

    // Set zoom limits
    map.setMinZoom(8);
    map.setMaxZoom(18);
});
</script>
@endsection
