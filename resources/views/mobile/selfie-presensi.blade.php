@extends('layouts.mobile')

@section('title', 'Selfie Presensi')
@section('subtitle', 'Ambil Foto Selfie')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .presensi-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 12px;
            padding: 12px 10px;
            box-shadow: 0 4px 10px rgba(0, 75, 76, 0.3);
            margin-bottom: 10px;
        }

        .presensi-header h6 {
            font-weight: 600;
            font-size: 12px;
        }

        .presensi-header h5 {
            font-size: 14px;
        }

        .selfie-section {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .selfie-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: #f8f9fa;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .selfie-video, .selfie-canvas, .selfie-preview {
            width: 100%;
            max-width: 280px;
            height: 280px;
            border-radius: 8px;
            object-fit: cover;
        }

        .selfie-video, .selfie-canvas {
            display: none;
        }

        .selfie-placeholder {
            text-align: center;
            color: #6c757d;
        }

        .selfie-placeholder i {
            font-size: 48px;
            margin-bottom: 8px;
            display: block;
        }

        .selfie-btn {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 6px;
            padding: 10px 16px;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
            width: 100%;
            margin-top: 8px;
        }

        .selfie-btn:disabled {
            background: #6c757d;
        }

        .selfie-btn.retake {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }

        .selfie-status {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 8px;
            margin-top: 8px;
            text-align: center;
        }

        .selfie-status.success {
            background: rgba(14, 133, 73, 0.1);
            border: 1px solid rgba(14, 133, 73, 0.2);
        }

        .selfie-status.error {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .location-info {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 6px;
            margin-bottom: 6px;
            word-wrap: break-word;
        }

        .location-info.success {
            background: rgba(14, 133, 73, 0.1);
            border: 1px solid rgba(14, 133, 73, 0.2);
        }

        .location-info.error {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .location-info.info {
            background: rgba(0, 123, 255, 0.1);
            border: 1px solid rgba(0, 123, 255, 0.2);
        }

        .coordinate-input {
            background: #fff;
            border-radius: 4px;
            padding: 4px 6px;
            border: 1px solid #e9ecef;
            font-size: 11px;
            width: 100%;
        }

        .address-input {
            background: #fff;
            border-radius: 4px;
            padding: 4px 6px;
            border: 1px solid #e9ecef;
            font-size: 11px;
            width: 100%;
            word-wrap: break-word;
        }

        .presensi-info {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .presensi-info.success {
            border-left: 4px solid #0e8549;
        }

        .presensi-info.warning {
            border-left: 4px solid #ffc107;
        }

        .btn-back {
            background: #6c757d;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-bottom: 10px;
        }

        .btn-back:hover {
            color: #fff;
            background: #5a6268;
        }
    </style>

    <!-- Header -->
    <div class="presensi-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Selfie Presensi</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- Back Button -->
    <a href="{{ route('mobile.presensi') }}" class="btn-back">
        <i class="bx bx-arrow-back me-1"></i>Kembali ke Presensi
    </a>

    <!-- Presensi Status Info -->
    @if($presensiHariIni && $presensiHariIni->waktu_masuk)
    <div class="presensi-info success">
        <div class="d-flex align-items-center">
            <div class="status-icon" style="width: 24px; height: 24px; background: rgba(14, 133, 73, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 6px;">
                <i class="bx bx-check-circle" style="color: #0e8549; font-size: 12px;"></i>
            </div>
            <div>
                <h6 class="mb-0" style="font-size: 12px; font-weight: 600;">Presensi Masuk Sudah Dicatat</h6>
                <p class="mb-0" style="font-size: 11px; color: #6c757d;">{{ $presensiHariIni->waktu_masuk ? $presensiHariIni->waktu_masuk->format('H:i') : '-' }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Location Status -->
    <div class="presensi-info">
        <div class="d-flex align-items-center mb-2">
            <i class="bx bx-target-lock text-success me-1"></i>
            <label style="font-weight: 600; font-size: 12px; margin-bottom: 0;">Koordinat Lokasi</label>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
            <input type="text" id="latitude" class="coordinate-input" placeholder="Latitude" readonly>
            <input type="text" id="longitude" class="coordinate-input" placeholder="Longitude" readonly>
        </div>
    </div>

    <!-- Address -->
    <div class="presensi-info">
        <div class="d-flex align-items-center mb-2">
            <i class="bx bx-home text-info me-1"></i>
            <label style="font-weight: 600; font-size: 12px; margin-bottom: 0;">Alamat Lokasi</label>
        </div>
        <input type="text" id="lokasi" class="address-input" placeholder="Alamat akan muncul otomatis" readonly>
    </div>

    <!-- Selfie Section -->
    <div class="selfie-section">
        <div class="d-flex align-items-center mb-2">
            <i class="bx bx-camera text-primary me-1"></i>
            <label style="font-weight: 600; font-size: 12px; margin-bottom: 0;">Foto Selfie</label>
        </div>

        <div class="alert-custom info" style="margin-bottom: 8px; padding: 6px; font-size: 11px;">
            <small><i class="bx bx-info-circle me-1"></i><strong>Wajib:</strong> Pastikan selfie diambil di lingkungan madrasah/sekolah.</small>
        </div>

        <div class="selfie-container">
            <div id="selfie-placeholder" class="selfie-placeholder">
                <i class="bx bx-camera"></i>
                <span>Klik tombol "Ambil Foto" untuk memulai</span>
            </div>
            <video id="selfie-video" autoplay playsinline></video>
            <canvas id="selfie-canvas"></canvas>
            <img id="selfie-preview" alt="Selfie Preview">
        </div>

        <button type="button" id="btn-take-selfie" class="selfie-btn">
            <i class="bx bx-camera me-1"></i>Ambil Foto
        </button>

        <button type="button" id="btn-retake-selfie" class="selfie-btn retake" style="display: none;">
            <i class="bx bx-refresh me-1"></i>Ulang Foto
        </button>

        <div id="selfie-status" class="selfie-status" style="display: none;">
            <div class="d-flex align-items-center justify-content-center">
                <i class="bx bx-check-circle me-1" style="color: #0e8549;"></i>
                <div>
                    <strong>Foto berhasil diambil</strong>
                    <br><small class="text-muted">Klik "Kirim Presensi" untuk menyelesaikan</small>
                </div>
            </div>
        </div>

        <input type="hidden" id="selfie-data" name="selfie_data">
    </div>

    <!-- Submit Button -->
    <button type="button" id="btn-submit-selfie-presensi" class="selfie-btn" style="display: none;">
        <i class="bx bx-send me-1"></i>Kirim Presensi
    </button>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('load', function() {
    let latitude, longitude, lokasi;
    let selfieCaptured = false;

    // Get location first
    if (navigator.geolocation) {
        $('#location-info').html(`
            <div class="location-info info">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-1"></i>
                    <div>
                        <strong>Mengumpulkan data lokasi...</strong>
                        <br><small class="text-muted">Pastikan GPS aktif</small>
                    </div>
                </div>
            </div>
        `);

        navigator.geolocation.getCurrentPosition(
            function(position) {
                latitude = position.coords.latitude;
                longitude = position.coords.longitude;

                $('#latitude').val(latitude.toFixed(6));
                $('#longitude').val(longitude.toFixed(6));

                // Get address
                getAddressFromCoordinates(latitude, longitude);

                $('#location-info').html(`
                    <div class="location-info success">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle me-1"></i>
                            <div>
                                <strong>Lokasi berhasil didapatkan</strong>
                                <br><small class="text-muted">Akurasi: ${Math.round(position.coords.accuracy)}m</small>
                            </div>
                        </div>
                    </div>
                `);

                // Enable selfie button
                $('#btn-take-selfie').prop('disabled', false);
            },
            function(error) {
                const errorMessage = error.code === 1 ? 'Izin lokasi ditolak' :
                                   error.code === 2 ? 'Sinyal GPS lemah' :
                                   error.code === 3 ? 'Waktu habis' : 'Error tidak diketahui';

                $('#location-info').html(`
                    <div class="location-info error">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error-circle me-1"></i>
                            <div>
                                <strong>GPS Error</strong>
                                <br><small class="text-muted">${errorMessage}</small>
                            </div>
                        </div>
                    </div>
                `);

                $('#btn-take-selfie').prop('disabled', true).html('<i class="bx bx-error me-1"></i>GPS Error');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 30000
            }
        );
    } else {
        $('#location-info').html(`
            <div class="location-info error">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle me-1"></i>
                    <div>
                        <strong>Browser tidak mendukung GPS</strong>
                    </div>
                </div>
            </div>
        `);
        $('#btn-take-selfie').prop('disabled', true).html('<i class="bx bx-error me-1"></i>GPS Tidak Didukung');
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

    // Selfie functionality
    $('#btn-take-selfie').click(async function() {
        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Lokasi Belum Didapatkan',
                text: 'Harap tunggu hingga lokasi GPS berhasil didapatkan.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: 640, height: 480 }
            });

            const video = document.getElementById('selfie-video');
            const placeholder = document.getElementById('selfie-placeholder');

            video.srcObject = stream;
            video.style.display = 'block';
            placeholder.style.display = 'none';

            // Auto-capture after 3 seconds
            $('#selfie-status').html(`
                <div class="selfie-status success">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-camera me-1"></i>
                        <div>
                            <strong>Kamera aktif</strong>
                            <br><small class="text-muted">Foto akan diambil otomatis dalam 3 detik...</small>
                        </div>
                    </div>
                </div>
            `).show();

            setTimeout(() => {
                if (!selfieCaptured) {
                    captureSelfie();
                }
            }, 3000);

        } catch (error) {
            console.error('Error accessing camera:', error);
            $('#selfie-status').html(`
                <div class="selfie-status error">
                    <div class="d-flex align-items-center justify-content-center">
                        <i class="bx bx-error-circle me-1"></i>
                        <div>
                            <strong>Kamera tidak dapat diakses</strong>
                            <br><small class="text-muted">Pastikan memberikan izin kamera</small>
                        </div>
                    </div>
                </div>
            `).show();
        }
    });

    // Capture selfie
    function captureSelfie() {
        const video = document.getElementById('selfie-video');
        const canvas = document.getElementById('selfie-canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/jpeg', 0.8);
        document.getElementById('selfie-data').value = imageData;
        document.getElementById('selfie-preview').src = imageData;
        document.getElementById('selfie-preview').style.display = 'block';
        document.getElementById('selfie-video').style.display = 'none';

        // Stop camera stream
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
        }

        selfieCaptured = true;

        $('#selfie-status').html(`
            <div class="selfie-status success">
                <div class="d-flex align-items-center justify-content-center">
                    <i class="bx bx-check-circle me-1"></i>
                    <div>
                        <strong>Foto berhasil diambil</strong>
                        <br><small class="text-muted">Klik "Kirim Presensi" untuk menyelesaikan</small>
                    </div>
                </div>
            </div>
        `);

        // Hide take button, show retake and submit buttons
        $('#btn-take-selfie').hide();
        $('#btn-retake-selfie').show();
        $('#btn-submit-selfie-presensi').show();
    }

    // Retake selfie
    $('#btn-retake-selfie').click(function() {
        document.getElementById('selfie-preview').style.display = 'none';
        document.getElementById('selfie-data').value = '';
        selfieCaptured = false;

        // Hide buttons
        $('#btn-retake-selfie').hide();
        $('#btn-submit-selfie-presensi').hide();
        $('#btn-take-selfie').show();
        $('#selfie-status').hide();
    });

    // Submit selfie presensi
    $('#btn-submit-selfie-presensi').click(function() {
        if (!latitude || !longitude) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Data lokasi belum lengkap.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        const selfieData = document.getElementById('selfie-data').value;
        if (!selfieData || selfieData.length < 100) {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan',
                text: 'Foto selfie belum diambil.',
                confirmButtonText: 'Oke'
            });
            return;
        }

        $(this).prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Memproses...');

        const postData = {
            _token: '{{ csrf_token() }}',
            latitude: latitude,
            longitude: longitude,
            lokasi: lokasi,
            accuracy: 10, // Default accuracy
            altitude: null,
            speed: null,
            device_info: navigator.userAgent,
            location_readings: JSON.stringify([{
                latitude: latitude,
                longitude: longitude,
                timestamp: Date.now(),
                accuracy: 10
            }]),
            selfie_data: selfieData
        };

        $.ajax({
            url: '{{ route("mobile.selfie-presensi.store") }}',
            method: 'POST',
            data: postData,
            timeout: 30000,
            success: function(resp) {
                if (resp && resp.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: resp.message || 'Presensi berhasil dicatat',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.href = '{{ route("mobile.presensi") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: resp.message || 'Gagal melakukan presensi. Coba lagi.',
                    });
                    $('#btn-submit-selfie-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
                }
            },
            error: function(xhr, status, err) {
                let message = 'Gagal menghubungi server.';
                if (xhr && xhr.responseJSON && xhr.responseJSON.message) message = xhr.responseJSON.message;
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: message
                });
                $('#btn-submit-selfie-presensi').prop('disabled', false).html('<i class="bx bx-send me-1"></i>Kirim Presensi');
            }
        });
    });
});
</script>
@endsection
