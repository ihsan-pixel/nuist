@extends('layouts.mobile')

@section('title', 'Presensi MGMP')
@section('subtitle', 'Presensi Kegiatan')

@section('content')
<div class="container py-3" style="max-width: 460px; margin: auto;">
    @php
        $start = $schedule['start'] ?? null;
        $end = $schedule['end'] ?? null;
        $disabledReason = null;

        if (!$canAttend) {
            $disabledReason = 'Akun Anda tidak terdaftar sebagai anggota pada grup MGMP kegiatan ini.';
        } elseif ($existingAttendance) {
            $disabledReason = 'Presensi kegiatan ini sudah tercatat.';
        } elseif (!$isOngoing) {
            $disabledReason = 'Presensi hanya dapat dilakukan saat kegiatan sedang berlangsung.';
        }
    @endphp

    <style>
        body {
            background: #f6f8f5;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
        }

        .mgmp-hero {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 18px;
            color: #fff;
            padding: 18px;
            box-shadow: 0 14px 28px rgba(0, 75, 76, 0.25);
        }

        .mgmp-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 8px 24px rgba(24, 39, 75, 0.08);
            margin-top: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-box {
            border: 1px solid #e9f0ec;
            border-radius: 12px;
            padding: 10px;
            background: #fbfdfb;
        }

        .info-label {
            color: #7a8b83;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .info-value {
            color: #1f332b;
            font-weight: 600;
            line-height: 1.3;
        }

        .camera-frame {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: #eef3f0;
            min-height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .camera-frame video,
        .camera-frame canvas,
        .camera-frame img {
            width: 100%;
            max-height: 360px;
            object-fit: cover;
            display: none;
        }

        .camera-frame video.selfie-mirror {
            transform: scaleX(-1);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pill.success {
            background: #e8f7ef;
            color: #0e8549;
        }

        .status-pill.warning {
            background: #fff4d8;
            color: #946200;
        }

        .status-pill.danger {
            background: #ffe8e8;
            color: #c52828;
        }

        .btn-mgmp {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            color: #fff;
            width: 100%;
            font-weight: 700;
        }

        .btn-mgmp:disabled {
            background: #a9b8b2;
        }
    </style>

    <div class="mgmp-hero">
        <div class="d-flex justify-content-between align-items-start gap-3">
            <div>
                <div class="text-white-50 small mb-1">{{ $report->mgmpGroup->name ?? 'MGMP' }}</div>
                <h5 class="mb-2 text-white">{{ $report->judul }}</h5>
                <div class="small">
                    {{ $start ? $start->format('d M Y') : '-' }}
                    <span class="mx-1">.</span>
                    {{ $start ? $start->format('H:i') : '-' }} - {{ $end ? $end->format('H:i') : '-' }} WIB
                </div>
            </div>
            @if($existingAttendance)
                <span class="status-pill success"><i class="bx bx-check-circle"></i> Hadir</span>
            @elseif($isOngoing)
                <span class="status-pill success"><i class="bx bx-radio-circle-marked"></i> Aktif</span>
            @else
                <span class="status-pill warning"><i class="bx bx-time"></i> Tidak Aktif</span>
            @endif
        </div>
    </div>

    <div class="mgmp-card">
        <div class="info-grid">
            <div class="info-box">
                <div class="info-label">Lokasi Kegiatan</div>
                <div class="info-value">{{ $report->lokasi ?: 'Tanpa nama lokasi' }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">Radius Presensi</div>
                <div class="info-value">{{ $report->radius_meters ?? 100 }} meter</div>
            </div>
            <div class="info-box">
                <div class="info-label">Latitude</div>
                <div class="info-value">{{ $report->latitude ?: '-' }}</div>
            </div>
            <div class="info-box">
                <div class="info-label">Longitude</div>
                <div class="info-value">{{ $report->longitude ?: '-' }}</div>
            </div>
        </div>

        @if($report->deskripsi)
            <div class="mt-3 text-muted">{{ $report->deskripsi }}</div>
        @endif
    </div>

    @if($disabledReason)
        <div class="mgmp-card">
            <div class="status-pill {{ $existingAttendance ? 'success' : 'danger' }} mb-2">
                <i class="bx {{ $existingAttendance ? 'bx-check-circle' : 'bx-error-circle' }}"></i>
                {{ $existingAttendance ? 'Presensi Tercatat' : 'Presensi Tidak Tersedia' }}
            </div>
            <p class="mb-0 text-muted">{{ $disabledReason }}</p>
            @if($existingAttendance)
                <div class="mt-3 small">
                    <div><strong>Waktu:</strong> {{ $existingAttendance->attended_at->format('d M Y H:i') }} WIB</div>
                    <div><strong>Jarak:</strong> {{ $existingAttendance->distance_meters }} meter dari titik kegiatan</div>
                    @if($existingAttendance->selfie_path)
                        <a href="{{ route('foto.mgmp_attendance', $existingAttendance) }}" target="_blank" class="btn btn-outline-success btn-sm mt-3">Lihat Selfie</a>
                    @endif
                </div>
            @endif
        </div>
    @else
        <div class="mgmp-card">
            <h6 class="mb-2">1. Validasi GPS</h6>
            <p class="text-muted mb-3">Lokasi presensi wajib berada di dalam radius kegiatan yang ditentukan MGMP.</p>
            <button type="button" class="btn btn-outline-success w-100" id="btnGetLocation">
                <i class="bx bx-current-location me-1"></i> Ambil Lokasi GPS
            </button>
            <div id="locationStatus" class="mt-3 text-muted small">Lokasi belum diambil.</div>
        </div>

        <div class="mgmp-card">
            <h6 class="mb-2">2. Foto Selfie</h6>
            <p class="text-muted mb-3">Selfie wajib diambil langsung dari kamera sebagai bukti kehadiran.</p>

            <div class="camera-frame">
                <div id="cameraPlaceholder" class="text-center text-muted p-4">
                    <i class="bx bx-camera" style="font-size: 42px;"></i>
                    <div class="mt-2">Kamera belum aktif</div>
                </div>
                <video id="selfieVideo" autoplay playsinline></video>
                <canvas id="selfieCanvas"></canvas>
                <img id="selfiePreview" alt="Preview selfie">
            </div>

            <div class="row g-2 mt-3">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-primary w-100" id="btnStartCamera">
                        <i class="bx bx-camera me-1"></i> Buka Kamera
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-outline-success w-100" id="btnCaptureSelfie" disabled>
                        <i class="bx bx-check me-1"></i> Ambil Foto
                    </button>
                </div>
            </div>
            <div id="selfieStatus" class="mt-3 text-muted small">Selfie belum diambil.</div>
        </div>

        <div class="mgmp-card mb-4">
            <button type="button" class="btn btn-mgmp" id="btnSubmitAttendance">
                <i class="bx bx-send me-1"></i> Kirim Presensi MGMP
            </button>
            <div class="text-muted small mt-3">
                Server akan memeriksa ulang jadwal kegiatan, radius GPS, dan validitas foto selfie sebelum presensi disimpan.
            </div>
        </div>
    @endif
</div>
@endsection

@section('script')
@if(!$disabledReason)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const targetLat = parseFloat(@json($report->latitude));
    const targetLng = parseFloat(@json($report->longitude));
    const radiusMeters = parseInt(@json($report->radius_meters ?? 100), 10);
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const storeUrl = @json(route('mgmp.kegiatan.presensi.store', $report));

    const btnGetLocation = document.getElementById('btnGetLocation');
    const btnStartCamera = document.getElementById('btnStartCamera');
    const btnCaptureSelfie = document.getElementById('btnCaptureSelfie');
    const btnSubmitAttendance = document.getElementById('btnSubmitAttendance');
    const locationStatus = document.getElementById('locationStatus');
    const selfieStatus = document.getElementById('selfieStatus');
    const video = document.getElementById('selfieVideo');
    const canvas = document.getElementById('selfieCanvas');
    const preview = document.getElementById('selfiePreview');
    const placeholder = document.getElementById('cameraPlaceholder');

    let latestPosition = null;
    let selfieData = '';
    let locationReadings = [];
    let stream = null;

    function distanceMeters(lat1, lon1, lat2, lon2) {
        const earthRadius = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2)
            + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
            * Math.sin(dLon / 2) * Math.sin(dLon / 2);
        return earthRadius * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
    }

    function getCurrentPosition() {
        return new Promise(function (resolve, reject) {
            if (!navigator.geolocation) {
                reject(new Error('Browser tidak mendukung GPS.'));
                return;
            }

            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 12000,
                maximumAge: 0
            });
        });
    }

    function recordPosition(position) {
        const reading = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            accuracy: position.coords.accuracy,
            timestamp: Date.now()
        };
        latestPosition = position;
        locationReadings.push(reading);

        const distance = Math.round(distanceMeters(reading.latitude, reading.longitude, targetLat, targetLng));
        const statusClass = distance <= radiusMeters ? 'text-success' : 'text-danger';
        locationStatus.innerHTML = `
            <div class="${statusClass}">
                <strong>Lokasi terbaca.</strong> Jarak dari titik kegiatan: ${distance} meter.
            </div>
            <div>Lat: ${reading.latitude.toFixed(8)}, Lng: ${reading.longitude.toFixed(8)}</div>
            <div>Akurasi GPS: ${Math.round(reading.accuracy || 0)} meter</div>
        `;
    }

    btnGetLocation.addEventListener('click', async function () {
        btnGetLocation.disabled = true;
        btnGetLocation.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Mengambil GPS...';

        try {
            const position = await getCurrentPosition();
            recordPosition(position);
        } catch (error) {
            locationStatus.innerHTML = `<span class="text-danger">${error.message || 'Gagal mengambil lokasi.'}</span>`;
        } finally {
            btnGetLocation.disabled = false;
            btnGetLocation.innerHTML = '<i class="bx bx-current-location me-1"></i> Ambil Lokasi GPS';
        }
    });

    btnStartCamera.addEventListener('click', async function () {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' },
                audio: false
            });
            video.srcObject = stream;
            video.classList.add('selfie-mirror');
            placeholder.style.display = 'none';
            preview.style.display = 'none';
            video.style.display = 'block';
            btnCaptureSelfie.disabled = false;
            selfieStatus.innerHTML = '<span class="text-success">Kamera aktif. Ambil foto selfie.</span>';
        } catch (error) {
            selfieStatus.innerHTML = '<span class="text-danger">Kamera tidak dapat diakses. Pastikan izin kamera aktif.</span>';
        }
    });

    btnCaptureSelfie.addEventListener('click', function () {
        const size = 480;
        canvas.width = size;
        canvas.height = size;
        const context = canvas.getContext('2d');
        const videoRatio = video.videoWidth / video.videoHeight;
        let sourceWidth = video.videoWidth;
        let sourceHeight = video.videoHeight;
        let sourceX = 0;
        let sourceY = 0;

        if (videoRatio > 1) {
            sourceWidth = video.videoHeight;
            sourceX = (video.videoWidth - sourceWidth) / 2;
        } else {
            sourceHeight = video.videoWidth;
            sourceY = (video.videoHeight - sourceHeight) / 2;
        }

        context.translate(size, 0);
        context.scale(-1, 1);
        context.drawImage(video, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, size, size);
        context.setTransform(1, 0, 0, 1, 0, 0);
        selfieData = canvas.toDataURL('image/jpeg', 0.9);
        preview.src = selfieData;
        preview.style.display = 'block';
        video.style.display = 'none';
        placeholder.style.display = 'none';
        selfieStatus.innerHTML = '<span class="text-success">Selfie berhasil diambil.</span>';

        if (stream) {
            stream.getTracks().forEach(function (track) {
                track.stop();
            });
            stream = null;
        }
    });

    btnSubmitAttendance.addEventListener('click', async function () {
        if (!selfieData || selfieData.length < 100) {
            Swal.fire('Selfie belum lengkap', 'Silakan ambil foto selfie terlebih dahulu.', 'warning');
            return;
        }

        btnSubmitAttendance.disabled = true;
        btnSubmitAttendance.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Memproses...';

        try {
            const position = await getCurrentPosition();
            recordPosition(position);

            const payload = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy,
                device_info: navigator.userAgent,
                location_readings: JSON.stringify(locationReadings),
                selfie_data: selfieData
            };

            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Presensi gagal disimpan.');
            }

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: result.message,
                timer: 1800,
                showConfirmButton: false
            }).then(function () {
                window.location.reload();
            });
        } catch (error) {
            Swal.fire('Gagal', error.message || 'Presensi gagal disimpan.', 'error');
            btnSubmitAttendance.disabled = false;
            btnSubmitAttendance.innerHTML = '<i class="bx bx-send me-1"></i> Kirim Presensi MGMP';
        }
    });
});
</script>
@endif
@endsection
