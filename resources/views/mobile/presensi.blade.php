@extends('layouts.mobile')

@section('title', 'Presensi')
@section('subtitle', 'Catat Kehadiran')

@section('content')
<div class="presensi-container">

    {{-- Status Presensi / Hari Libur --}}
    @if($isHoliday)
    <div class="info-card holiday">
        <div class="icon"><i class="bx bx-calendar-x"></i></div>
        <div class="text">
            <h6>Hari Libur</h6>
            <p>{{ $holiday->name ?? 'Hari ini adalah hari libur nasional.' }}</p>
        </div>
    </div>
    @elseif($presensiHariIni)
    <div class="info-card success">
        <div class="icon"><i class="bx bx-check-circle"></i></div>
        <div class="text">
            <h6>Presensi Tercatat</h6>
            <p>Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i') }}</strong></p>
            @if($presensiHariIni->waktu_keluar)
                <p>Pulang: <strong>{{ $presensiHariIni->waktu_keluar->format('H:i') }}</strong></p>
                <span class="badge badge-success mt-1"><i class="bx bx-check me-1"></i>Lengkap</span>
            @else
                <span class="badge badge-warning mt-1"><i class="bx bx-time-five me-1"></i>Belum Presensi Pulang</span>
            @endif
        </div>
    </div>
    @endif


    {{-- Peta Lokasi --}}
    <div id="map-preview" class="rounded-4 mb-3">
        <div class="map-placeholder text-center">
            <i class="bx bx-map fs-1 text-primary mb-2"></i>
            <p class="text-muted small mb-0">Titik lokasi Anda akan muncul di sini</p>
        </div>
    </div>

    {{-- Form Presensi --}}
    <div class="card presensi-card shadow-sm">
        <div class="card-body">
            <div id="location-info" class="alert alert-info border-0 rounded-3 mb-3 small">
                <i class="bx bx-loader-alt bx-spin me-1"></i> Mendapatkan lokasi... pastikan GPS aktif.
            </div>

            <div class="location-fields mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-semibold small text-muted">Koordinat</span>
                    <i class="bx bx-target-lock text-success"></i>
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="text" id="latitude" class="form-control form-control-sm text-center" placeholder="Latitude" readonly>
                    </div>
                    <div class="col-6">
                        <input type="text" id="longitude" class="form-control form-control-sm text-center" placeholder="Longitude" readonly>
                    </div>
                </div>
            </div>

            <div class="address-field mb-3">
                <label class="small text-muted fw-semibold mb-1"><i class="bx bx-map-pin text-danger me-1"></i>Alamat</label>
                <input type="text" id="lokasi" class="form-control form-control-sm" placeholder="Alamat otomatis muncul" readonly>
            </div>

            <button id="btn-presensi" type="button"
                class="btn btn-gradient w-100 py-2 rounded-3 shadow-sm fw-semibold"
                {{ ($presensiHariIni && $presensiHariIni->waktu_keluar) || $isHoliday ? 'disabled' : '' }}>
                <i class="bx bx-check-circle me-2"></i>
                {{ $isHoliday ? 'Presensi Ditutup (Libur)' : ($presensiHariIni ? 'Presensi Pulang' : 'Presensi Masuk') }}
            </button>
        </div>
    </div>


    {{-- Jadwal Presensi --}}
    @if(isset($timeRanges))
    <div class="card mt-3 shadow-sm">
        <div class="card-body small">
            <h6 class="fw-bold mb-2"><i class="bx bx-time-five text-primary me-1"></i>Jadwal Presensi</h6>
            <div class="row g-2 text-center">
                <div class="col-6">
                    <div class="p-2 bg-light rounded-3 border">
                        <i class="bx bx-log-in-circle text-primary fs-5"></i>
                        <p class="mb-0 fw-semibold">{{ $timeRanges['masuk_start'] }} - {{ $timeRanges['masuk_end'] }}</p>
                        <small class="text-muted">Presensi Masuk</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 bg-light rounded-3 border">
                        <i class="bx bx-log-out-circle text-success fs-5"></i>
                        <p class="mb-0 fw-semibold">{{ $timeRanges['pulang_start'] }} - {{ $timeRanges['pulang_end'] }}</p>
                        <small class="text-muted">Presensi Pulang</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    {{-- Catatan Penting --}}
    <div class="alert alert-warning bg-warning bg-opacity-10 border-0 mt-3 small">
        <i class="bx bx-error-circle me-1 text-warning"></i>
        Pastikan Anda berada di area madrasah saat melakukan presensi.
    </div>
</div>
@endsection


@section('css')
<style>
.presensi-container {
    padding: 10px 12px 70px;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
}

/* Info Cards */
.info-card {
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 12px;
    padding: 12px;
    color: #333;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.info-card .icon {
    background: rgba(0, 123, 255, 0.1);
    color: #007bff;
    border-radius: 50%;
    width: 42px;
    height: 42px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.info-card.success .icon {
    background: rgba(0, 200, 83, 0.1);
    color: #00c853;
}
.info-card.holiday .icon {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}
.info-card .text h6 {
    font-weight: 600;
    margin-bottom: 4px;
}
.info-card .text p {
    font-size: 12px;
    margin: 0;
}
.badge {
    display: inline-block;
    font-size: 11px;
    border-radius: 8px;
    padding: 2px 6px;
}
.badge-success {
    background: rgba(0,200,83,0.1);
    color: #00c853;
}
.badge-warning {
    background: rgba(255,193,7,0.1);
    color: #f57f17;
}

/* Map Placeholder */
#map-preview {
    background: #f8f9fa;
    border: 1px dashed #d6d6d6;
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Button Gradient */
.btn-gradient {
    background: linear-gradient(45deg, #007bff, #ff2d55);
    color: #fff !important;
    border: none;
    transition: 0.3s;
}
.btn-gradient:active {
    transform: scale(0.97);
}

/* Alert Styling */
.alert {
    border-radius: 10px;
}

/* Small Device Spacing */
@media (max-width: 576px) {
    .info-card { padding: 10px; }
    .card-body { padding: 12px; }
}
</style>
@endsection


@section('script')
<script>
document.addEventListener("DOMContentLoaded", () => {
    let currentLocation = null;

    // Function to get single location reading (faster)
    const getCurrentLocation = (callback) => {
        if (!navigator.geolocation) {
            alert('GPS tidak tersedia di perangkat ini.');
            return;
        }

        document.getElementById('location-info').innerHTML = `
            <i class="bx bx-loader-alt bx-spin me-1"></i> Mendapatkan lokasi...
        `;

        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentLocation = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                    altitude: position.coords.altitude,
                    speed: position.coords.speed
                };

                // Update form fields
                document.getElementById('latitude').value = currentLocation.latitude.toFixed(6);
                document.getElementById('longitude').value = currentLocation.longitude.toFixed(6);

                // Reverse geocode
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${currentLocation.latitude}&lon=${currentLocation.longitude}`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('lokasi').value = data.display_name || 'Tidak dapat menentukan alamat';
                        document.getElementById('location-info').innerHTML = `
                            <i class="bx bx-check-circle text-success me-1"></i> Lokasi berhasil didapatkan.
                        `;
                        if (callback) callback();
                    })
                    .catch(() => {
                        document.getElementById('lokasi').value = 'Tidak dapat menentukan alamat';
                        document.getElementById('location-info').innerHTML = `
                            <i class="bx bx-check-circle text-success me-1"></i> Lokasi berhasil didapatkan.
                        `;
                        if (callback) callback();
                    });
            },
            (error) => {
                console.error('Error getting location:', error);
                let errorMessage = 'Gagal mendapatkan lokasi. ';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage += 'Akses lokasi ditolak. Izinkan akses lokasi di browser.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage += 'Lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        errorMessage += 'Waktu habis mendapatkan lokasi.';
                        break;
                    default:
                        errorMessage += 'Error tidak diketahui.';
                        break;
                }
                alert(errorMessage);
                document.getElementById('location-info').innerHTML = `
                    <div class="alert alert-danger small"><i class="bx bx-error-circle me-1"></i>${errorMessage}</div>
                `;
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 30000 // Allow cached location up to 30 seconds
            }
        );
    };

    // Initial location collection
    getCurrentLocation();

    // Handle presensi button click
    document.getElementById('btn-presensi').addEventListener('click', function(e) {
        e.preventDefault();

        // Disable button to prevent multiple clicks
        this.disabled = true;
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Mengirim...';

        // Prepare form data (use existing location data without fetching new one)
        const formData = new FormData();
        formData.append('latitude', document.getElementById('latitude').value || '0');
        formData.append('longitude', document.getElementById('longitude').value || '0');
        formData.append('lokasi', document.getElementById('lokasi').value || 'Lokasi tidak diketahui');
        formData.append('accuracy', currentLocation ? currentLocation.accuracy : '5.0');
        formData.append('altitude', currentLocation ? currentLocation.altitude : null);
        formData.append('speed', currentLocation ? currentLocation.speed : null);
        formData.append('device_info', navigator.userAgent);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Submit presensi directly without fetching new location
        fetch('{{ route("mobile.presensi.store") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success alert
                alert('✅ ' + data.message);

                // Show success message in UI
                document.getElementById('location-info').innerHTML = `
                    <div class="alert alert-success small"><i class="bx bx-check-circle me-1"></i>${data.message}</div>
                `;

                // Reload page after 2 seconds
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                // Show error alert for validation failures
                alert('❌ ' + data.message);

                // Show error message in UI
                document.getElementById('location-info').innerHTML = `
                    <div class="alert alert-danger small"><i class="bx bx-error-circle me-1"></i>${data.message}</div>
                `;

                // Re-enable button
                document.getElementById('btn-presensi').disabled = false;
                document.getElementById('btn-presensi').innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Terjadi kesalahan. Silakan coba lagi.');
            document.getElementById('location-info').innerHTML = `
                <div class="alert alert-danger small"><i class="bx bx-error-circle me-1"></i>Terjadi kesalahan. Silakan coba lagi.</div>
            `;

            // Re-enable button
            document.getElementById('btn-presensi').disabled = false;
            document.getElementById('btn-presensi').innerHTML = originalText;
        });
    });
});
</script>
@endsection
