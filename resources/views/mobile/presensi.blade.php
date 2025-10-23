@extends('layouts.mobile')

@section('title', 'Presensi')
@section('subtitle', 'Catat Kehadiran')

@section('content')
<!-- Status Card -->
@if($isHoliday)
<div class="alert alert-warning border-0 rounded-3 mb-4">
    <div class="d-flex align-items-center">
        <i class="bx bx-calendar-x fs-3 me-3"></i>
        <div>
            <h6 class="mb-1">Hari Libur</h6>
            <p class="mb-0 small">{{ $holiday->name ?? 'Hari ini adalah hari libur' }}</p>
        </div>
    </div>
</div>
@elseif($presensiHariIni)
<div class="card mb-4 shadow-sm border-success">
    <div class="card-body text-center py-4">
        <div class="avatar-lg mx-auto mb-3">
            <div class="avatar-title bg-success text-white rounded-circle">
                <i class="bx bx-check-circle fs-1"></i>
            </div>
        </div>
        <h5 class="text-success mb-2">Presensi Sudah Dicatat</h5>
        <p class="mb-2">Waktu Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i') }}</strong></p>
        @if($presensiHariIni->waktu_keluar)
        <p class="mb-0">Waktu Keluar: <strong>{{ $presensiHariIni->waktu_keluar->format('H:i') }}</strong></p>
        <div class="alert alert-success mt-3">
            <i class="bx bx-check me-1"></i> Presensi hari ini sudah lengkap!
        </div>
        @else
        <p class="text-muted small">Silakan lakukan presensi keluar jika sudah selesai bekerja.</p>
        @endif
    </div>
</div>
@endif

<!-- Presensi Form -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-gradient-primary text-white">
        <div class="d-flex align-items-center">
            <i class="bx bx-{{ $presensiHariIni ? 'log-out-circle' : 'log-in-circle' }} me-2"></i>
            <h6 class="mb-0">{{ $presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk' }}</h6>
        </div>
    </div>
    <div class="card-body">
        <!-- Location Status -->
        <div id="location-info" class="mb-4">
            <div class="alert alert-info border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                    <div>
                        <strong>Mendapatkan lokasi...</strong>
                        <br><small class="text-muted">Pastikan GPS aktif</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="mb-4">
            <label class="form-label fw-semibold mb-2">Lokasi Anda</label>
            <div class="map-container rounded-3 overflow-hidden shadow-sm border">
                <div id="map"></div>
            </div>
        </div>

        <!-- Coordinates -->
        <div class="row g-3 mb-4">
            <div class="col-6">
                <label class="form-label small fw-semibold">Latitude</label>
                <input type="text" id="latitude" class="form-control form-control-lg"
                       placeholder="Latitude" readonly>
            </div>
            <div class="col-6">
                <label class="form-label small fw-semibold">Longitude</label>
                <input type="text" id="longitude" class="form-control form-control-lg"
                       placeholder="Longitude" readonly>
            </div>
        </div>

        <!-- Address -->
        <div class="mb-4">
            <label class="form-label fw-semibold mb-2">Alamat Lokasi</label>
            <input type="text" id="lokasi" class="form-control form-control-lg"
                   placeholder="Alamat akan muncul otomatis" readonly>
        </div>

        <!-- Presensi Button -->
        <button type="button" id="btn-presensi"
                class="btn btn-{{ $isHoliday ? 'secondary' : 'primary' }} btn-lg w-100 py-3 rounded-3"
                {{ ($presensiHariIni && $presensiHariIni->waktu_keluar) || $isHoliday ? 'disabled' : '' }}>
            <i class="bx bx-{{ $isHoliday ? 'calendar-x' : 'check-circle' }} me-2 fs-5"></i>
            {{ $isHoliday ? 'Hari Libur - Presensi Ditutup' : ($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk') }}
        </button>
    </div>
</div>

        <!-- Time Information -->
@if(isset($timeRanges) && $timeRanges)
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h6 class="mb-0"><i class="bx bx-time me-2"></i>Jadwal Presensi</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between p-3 bg-primary bg-opacity-10 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-log-in-circle text-primary me-2 fs-4"></i>
                        <div>
                            <h6 class="mb-0 text-primary">Presensi Masuk</h6>
                            <small class="text-muted">{{ $timeRanges['masuk']['start'] }} - {{ $timeRanges['masuk']['end'] }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Terlambat setelah 07:00</small>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between p-3 bg-success bg-opacity-10 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-log-out-circle text-success me-2 fs-4"></i>
                        <div>
                            <h6 class="mb-0 text-success">Presensi Pulang</h6>
                            <small class="text-muted">{{ $timeRanges['pulang']['start'] }} - {{ $timeRanges['pulang']['end'] }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="text-muted">Waktu pulang normal</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Important Notice -->
<div class="alert alert-danger border-0 rounded-3">
    <div class="d-flex">
        <i class="bx bx-error-circle text-danger me-3 fs-4"></i>
        <div>
            <strong>Penting!</strong>
            <p class="mb-0 small">Pastikan Anda berada dalam lingkungan Madrasah/Sekolah untuk melakukan presensi.</p>
        </div>
    </div>
</div>

@endsection

@section('css')
<style>
.map-container {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 250px !important;
    width: 100% !important;
    background: #f8f9fa;
    position: relative;
    overflow: hidden !important;
    border-radius: 8px;
    margin: 0;
    padding: 0;
    box-sizing: border-box !important;
    display: block !important;
}
#map {
    height: 100% !important;
    width: 100% !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    z-index: 1;
    border-radius: 8px;
    box-sizing: border-box !important;
    max-width: 100% !important;
    max-height: 100% !important;
    display: block !important;
}
.user-location-marker {
    background: transparent !important;
    border: none !important;
}
.leaflet-container {
    background: #f8f9fa !important;
    font-family: inherit;
    font-size: 12px !important;
    height: 100% !important;
    width: 100% !important;
    position: relative !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    box-sizing: border-box !important;
    max-width: 100% !important;
    max-height: 100% !important;
    display: block !important;
}
.leaflet-popup-content-wrapper {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.leaflet-popup-tip {
    background: white;
}
</style>
@endsection

@section('script')
<script src="{{ asset('build/libs/leaflet/leaflet.js') }}"></script>
<script>
window.addEventListener('load', function() {
    let latitude, longitude, lokasi, map;

    // Get location when page loads (reading1)
    if (navigator.geolocation) {
        $('#location-info').html(`
            <div class="alert alert-info border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-3 fs-4"></i>
                    <div>
                        <strong>Mendapatkan lokasi Anda...</strong>
                        <br><small class="text-muted">Proses ini akan selesai dalam beberapa detik</small>
                    </div>
                </div>
            </div>
        `);

        navigator.geolocation.getCurrentPosition(function(position) {
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            // Store reading1 in sessionStorage (menu entry reading)
            sessionStorage.setItem('reading1_latitude', position.coords.latitude);
            sessionStorage.setItem('reading1_longitude', position.coords.longitude);
            sessionStorage.setItem('reading1_timestamp', Date.now());

            $('#latitude').val(latitude.toFixed(6));
            $('#longitude').val(longitude.toFixed(6));

            // Get address
            getAddressFromCoordinates(latitude, longitude);

            $('#location-info').html(`
                <div class="alert alert-success border-0 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle me-3 fs-4"></i>
                        <div>
                            <strong>Lokasi berhasil didapatkan!</strong>
                        </div>
                    </div>
                </div>
            `);

            // Initialize map with user location
            map = L.map('map', {
                center: [latitude, longitude],
                zoom: 16,
                zoomControl: false,
                scrollWheelZoom: false,
                attributionControl: false,
                fadeAnimation: false,
                zoomAnimation: false,
                markerZoomAnimation: false,
                preferCanvas: true,
                renderer: L.canvas(),
                boxZoom: false,
                doubleClickZoom: false,
                keyboard: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: false,
                updateWhenIdle: true,
                updateWhenZooming: false,
                crossOrigin: true,
                tileSize: 256,
                zoomOffset: 0
            }).addTo(map);

            // Add user location marker with custom icon
            var userIcon = L.divIcon({
                className: 'user-location-marker',
                html: '<div style="background-color: #007bff; width: 16px; height: 16px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            var marker = L.marker([latitude, longitude], {icon: userIcon}).addTo(map)
                .bindPopup('<div class="text-center"><strong>Lokasi Anda</strong><br/>' + latitude.toFixed(6) + ', ' + longitude.toFixed(6) + '</div>')
                .openPopup();

            // Add accuracy circle if available
            if (position.coords.accuracy) {
                L.circle([latitude, longitude], {
                    color: '#007bff',
                    fillColor: '#007bff',
                    fillOpacity: 0.1,
                    radius: position.coords.accuracy
                }).addTo(map);
            }

            // Force map to resize and center on location
            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                    map.setView([latitude, longitude], 16);
                    // Force a complete redraw
                    map._onResize();
                }
            }, 100);

            // Additional resize for hidden tabs - multiple calls for reliability
            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                    map._onResize();
                }
            }, 300);

            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                    map._onResize();
                }
            }, 600);

            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                    map._onResize();
                }
            }, 1000);

        }, function(error) {
            $('#location-info').html(`
                <div class="alert alert-danger border-0 rounded-3">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-error-circle me-3 fs-4"></i>
                        <div>
                            <strong>Gagal mendapatkan lokasi</strong>
                            <br><small class="text-muted">${error.message}</small>
                        </div>
                    </div>
                </div>
            `);

            // Initialize map with default location on error
            map = L.map('map', {
                center: [-7.7956, 110.3695],
                zoom: 11,
                zoomControl: false,
                scrollWheelZoom: false,
                attributionControl: false,
                fadeAnimation: false,
                zoomAnimation: false,
                markerZoomAnimation: false,
                preferCanvas: true,
                renderer: L.canvas(),
                boxZoom: false,
                doubleClickZoom: false,
                keyboard: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: false,
                updateWhenIdle: true,
                updateWhenZooming: false,
                crossOrigin: true,
                tileSize: 256,
                zoomOffset: 0
            }).addTo(map);

            var marker = L.marker([-7.7956, 110.3695]).addTo(map)
                .bindPopup('<div class="text-center"><strong>Lokasi Tidak Dapat Didapatkan</strong><br/>Periksa pengaturan GPS Anda</div>')
                .openPopup();

            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                    map._onResize();
                }
            }, 100);

            // Additional resize for hidden tabs - multiple calls for reliability
            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                    map._onResize();
                }
            }, 300);

            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                    map._onResize();
                }
            }, 600);

            setTimeout(function() {
                if (map) {
                    map.invalidateSize();
                    map._onResize();
                }
            }, 1000);
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 30000
        });
    } else {
        $('#location-info').html(`
            <div class="alert alert-danger border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle me-3 fs-4"></i>
                    <div>
                        <strong>Browser tidak mendukung GPS</strong>
                        <br><small class="text-muted">Silakan gunakan browser modern dengan dukungan GPS</small>
                    </div>
                </div>
            </div>
        `);

        // Initialize map with default location
        map = L.map('map', {
            center: [-7.7956, 110.3695],
            zoom: 11,
            zoomControl: false,
            scrollWheelZoom: false,
            attributionControl: false,
            fadeAnimation: false,
            zoomAnimation: false,
            markerZoomAnimation: false,
            preferCanvas: true,
            renderer: L.canvas(),
            boxZoom: false,
            doubleClickZoom: false,
            keyboard: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: false,
            updateWhenIdle: true,
            updateWhenZooming: false,
            crossOrigin: true,
            tileSize: 256,
            zoomOffset: 0
        }).addTo(map);

        var marker = L.marker([-7.7956, 110.3695]).addTo(map)
            .bindPopup('<div class="text-center"><strong>Browser Tidak Mendukung GPS</strong><br/>Gunakan browser modern</div>')
            .openPopup();

        setTimeout(function() {
            if (map) {
                map.invalidateSize();
                map._onResize();
            }
        }, 100);

        // Additional resize for hidden tabs - multiple calls for reliability
        setTimeout(function() {
            if (map) {
                map.invalidateSize();
                map._onResize();
            }
        }, 300);

        setTimeout(function() {
            if (map) {
                map.invalidateSize();
                map._onResize();
            }
        }, 600);

        setTimeout(function() {
            if (map) {
                map.invalidateSize();
                map._onResize();
            }
        }, 1000);
    }

    // Get address from coordinates
    function getAddressFromCoordinates(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data.display_name) {
                    lokasi = data.display_name;
                    $('#lokasi').val(lokasi);
                }
            })
            .catch(error => {
                console.error('Error getting address:', error);
                $('#lokasi').val('Tidak dapat mendapatkan alamat');
            });
    }

    // Handle presensi button
    $('#btn-presensi').click(function() {
        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Lokasi belum didapatkan. Pastikan GPS aktif dan izinkan akses lokasi.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        // Get second location reading (button click)
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let reading2Lat = position.coords.latitude;
                let reading2Lng = position.coords.longitude;

                // Store reading 2 in sessionStorage
                sessionStorage.setItem('reading2_latitude', position.coords.latitude);
                sessionStorage.setItem('reading2_longitude', position.coords.longitude);
                sessionStorage.setItem('reading2_timestamp', Date.now());

                let reading1Lat = sessionStorage.getItem('reading1_latitude');
                let reading1Lng = sessionStorage.getItem('reading1_longitude');
                let reading1Timestamp = sessionStorage.getItem('reading1_timestamp');

                let reading2Timestamp = Date.now();

                let postData = {
                    _token: '{{ csrf_token() }}',
                    latitude: reading2Lat,
                    longitude: reading2Lng,
                    lokasi: lokasi,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed,
                    device_info: navigator.userAgent,
                    location_readings: JSON.stringify([
                        {
                            latitude: parseFloat(reading1Lat),
                            longitude: parseFloat(reading1Lng),
                            timestamp: parseInt(reading1Timestamp)
                        },
                        {
                            latitude: reading2Lat,
                            longitude: reading2Lng,
                            timestamp: reading2Timestamp
                        }
                    ])
                };

                // Update UI with location data
                $('#latitude').val(reading2Lat.toFixed(6));
                $('#longitude').val(reading2Lng.toFixed(6));

                // Get address
                getAddressFromCoordinates(reading2Lat, reading2Lng);

                $('#location-info').html(`
                    <div class="alert alert-success border-0 rounded-3">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle me-3 fs-4"></i>
                            <div>
                                <strong>Lokasi berhasil didapatkan!</strong>
                            </div>
                        </div>
                    </div>
                `);

                $.ajax({
                    url: '{{ route("mobile.presensi.store") }}',
                    method: 'POST',
                    data: postData,
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: response.message,
                                confirmButtonText: 'Oke'
                            });
                            $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan tidak diketahui',
                            confirmButtonText: 'Oke'
                        });
                        $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
                    }
                });
            },
            function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Gagal mendapatkan lokasi: ' + error.message,
                    confirmButtonText: 'Oke'
                });
                $('#btn-presensi').prop('disabled', false).html('<i class="bx bx-check-circle me-2"></i>{{ $presensiHariIni ? "Presensi Keluar" : "Presensi Masuk" }}');
            },
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 10000
            }
        );
    });
});

// Handle tab visibility changes for map resizing
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        setTimeout(function() {
            if (map && typeof map.invalidateSize === 'function') {
                map.invalidateSize();
                if (typeof map._onResize === 'function') map._onResize();
            }
        }, 100);
        // Additional calls for reliability
        setTimeout(function() {
            if (map && typeof map.invalidateSize === 'function') {
                map.invalidateSize();
                if (typeof map._onResize === 'function') map._onResize();
            }
        }, 300);
    }
});

// Handle window resize
window.addEventListener('resize', function() {
    setTimeout(function() {
        if (map && typeof map.invalidateSize === 'function') {
            map.invalidateSize();
            if (typeof map._onResize === 'function') map._onResize();
        }
    }, 100);
    // Additional calls for reliability
    setTimeout(function() {
        if (map && typeof map.invalidateSize === 'function') {
            map.invalidateSize();
            if (typeof map._onResize === 'function') map._onResize();
        }
    }, 300);
});

// Use MutationObserver to detect when map container becomes visible
const mapContainer = document.querySelector('.map-container');
if (mapContainer) {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && (mutation.attributeName === 'style' || mutation.attributeName === 'class')) {
                setTimeout(function() {
                    if (map && typeof map.invalidateSize === 'function') {
                        map.invalidateSize();
                        if (typeof map._onResize === 'function') map._onResize();
                    }
                }, 100);
                // Additional calls for reliability
                setTimeout(function() {
                    if (map && typeof map.invalidateSize === 'function') {
                        map.invalidateSize();
                        if (typeof map._onResize === 'function') map._onResize();
                    }
                }, 300);
            }
        });
    });

    observer.observe(mapContainer, {
        attributes: true,
        attributeFilter: ['style', 'class']
    });
}

// Additional check for when map element becomes visible
const mapElement = document.getElementById('map');
if (mapElement) {
    const mapObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                setTimeout(function() {
                    if (map && typeof map.invalidateSize === 'function') {
                        map.invalidateSize();
                        if (typeof map._onResize === 'function') map._onResize();
                    }
                }, 100);
                // Additional calls for reliability
                setTimeout(function() {
                    if (map && typeof map.invalidateSize === 'function') {
                        map.invalidateSize();
                        if (typeof map._onResize === 'function') map._onResize();
                    }
                }, 300);
            }
        });
    }, { threshold: 0.1 });

    mapObserver.observe(mapElement);
}
</script>
@endsection
