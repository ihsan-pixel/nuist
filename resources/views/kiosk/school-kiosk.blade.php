@extends('layouts.master')

@section('title', 'Mode Kiosk Presensi')

@section('css')
<style>
    .kiosk-status-card {
        border: 0;
        border-radius: 22px;
        box-shadow: 0 14px 36px rgba(15, 23, 42, 0.08);
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pill.success {
        background: rgba(34, 197, 94, 0.12);
        color: #15803d;
    }

    .status-pill.danger {
        background: rgba(239, 68, 68, 0.12);
        color: #b91c1c;
    }

    .meta-box,
    .panel-box {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        background: #fff;
        height: 100%;
    }

    .meta-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .meta-value {
        color: #0f172a;
        font-weight: 600;
    }

    .camera-shell {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        background: #0f172a;
        min-height: 420px;
    }

    .camera-shell video,
    .camera-shell img {
        width: 100%;
        height: 420px;
        object-fit: cover;
        display: block;
        transform: scaleX(-1);
    }

    .camera-shell canvas {
        display: none;
    }

    .camera-overlay {
        position: absolute;
        inset: 0;
        pointer-events: none;
        border: 2px solid rgba(255, 255, 255, 0.25);
        border-radius: 18px;
    }

    .camera-preview {
        display: none;
    }

    .camera-preview.show {
        display: block;
    }

    .camera-video.hide {
        display: none;
    }

    .teacher-meta {
        color: #64748b;
        font-size: 12px;
    }

    .teacher-search-note {
        color: #64748b;
        font-size: 11px;
        margin-top: 6px;
    }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi Admin @endslot
    @slot('title') Mode Kiosk Presensi @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card kiosk-status-card">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                    <div>
                        <h4 class="mb-1 fw-semibold">
                            <i class="bx bx-desktop me-2"></i>Mode Kiosk Presensi Sekolah
                        </h4>
                        <p class="text-muted mb-0">
                            Presensi guru dari komputer sekolah terdaftar dengan validasi IP, lokasi, dan {{ strtolower($verificationLabel) }}.
                        </p>
                    </div>
                    <span class="status-pill {{ $accessGranted ? 'success' : 'danger' }}">
                        <i class="bx {{ $accessGranted ? 'bx-check-circle' : 'bx-x-circle' }}"></i>
                        {{ $accessGranted ? 'Perangkat Tervalidasi' : 'Akses Ditolak' }}
                    </span>
                </div>

                <div class="alert {{ $accessGranted ? 'alert-success' : 'alert-danger' }} mb-4">
                    <i class="bx {{ $accessGranted ? 'bx-check-shield' : 'bx-error-circle' }} me-2"></i>{{ $accessMessage }}
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6 col-xl-3">
                        <div class="meta-box">
                            <div class="meta-label">Madrasah</div>
                            <div class="meta-value">{{ $device?->madrasah?->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="meta-box">
                            <div class="meta-label">Perangkat</div>
                            <div class="meta-value">{{ $device?->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="meta-box">
                            <div class="meta-label">Guru Tersedia</div>
                            <div class="meta-value">{{ $teacherCount }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="meta-box">
                            <div class="meta-label">Metode Verifikasi</div>
                            <div class="meta-value">{{ $verificationLabel }}</div>
                        </div>
                    </div>
                </div>

                @if($accessGranted)
                    <form id="kioskAttendanceForm" class="row g-4">
                        @csrf
                        <div class="col-xl-4">
                            <div class="panel-box">
                                <div class="fw-semibold mb-3">Data Presensi</div>

                                <div class="mb-3">
                                    <label class="form-label">Pilih Guru</label>
                                    <input type="text" class="form-control mb-2" id="teacherSearchInput" placeholder="Cari nama, NIP, atau NUPTK">
                                    <select class="form-select" id="teacherSelect" name="teacher_id" required>
                                        <option value="">Pilih guru</option>
                                        @foreach($teachers as $teacher)
                                            <option
                                                value="{{ $teacher->id }}"
                                                data-name="{{ $teacher->name }}"
                                                data-nip="{{ $teacher->nip }}"
                                                data-nuptk="{{ $teacher->nuptk }}"
                                                data-face="{{ $teacher->face_registered_at ? '1' : '0' }}"
                                            >
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="teacher-search-note">Pencarian memfilter daftar guru secara langsung di komputer kiosk ini.</div>
                                    <div id="teacherMeta" class="teacher-meta mt-2">Pilih guru terlebih dahulu.</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Mode Presensi</label>
                                    <select class="form-select" id="presensiModeSelect" name="presensi_mode">
                                        <option value="masuk">Presensi Masuk</option>
                                        <option value="keluar">Presensi Keluar</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Lokasi Perangkat</label>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-primary" id="captureLocationButton">
                                            <i class="bx bx-current-location me-1"></i>Ambil Lokasi
                                        </button>
                                    </div>
                                    <div id="locationStatus" class="small text-muted mt-2">Lokasi belum diambil.</div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label class="form-label mb-0">Verifikasi Kamera</label>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $verificationLabel }}</span>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-secondary" id="startCameraButton">
                                            <i class="bx bx-camera me-1"></i>Aktifkan Kamera
                                        </button>
                                        <button type="button" class="btn btn-primary" id="captureVerificationButton" disabled>
                                            <i class="bx bx-face me-1"></i>{{ $verificationMode === 'face_scan' ? 'Scan Wajah' : 'Ambil Selfie' }}
                                        </button>
                                    </div>
                                    <div id="captureStatus" class="small text-muted mt-2">
                                        {{ $verificationMode === 'face_scan' ? 'Scan wajah belum dilakukan.' : 'Selfie belum diambil.' }}
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success" id="submitAttendanceButton" disabled>
                                        <i class="bx bx-check-circle me-1"></i>Simpan Presensi
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-8">
                            <div class="panel-box">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="fw-semibold">Kamera Kiosk</div>
                                    <div class="small text-muted">
                                        {{ $verificationMode === 'face_scan' ? 'Gunakan satu wajah, cahaya cukup, lalu ikuti arahan scan wajah yang muncul.' : 'Gunakan kamera depan dengan wajah terlihat jelas.' }}
                                    </div>
                                </div>

                                <div class="camera-shell">
                                    <video id="cameraVideo" class="camera-video" autoplay playsinline muted></video>
                                    <img id="cameraPreview" class="camera-preview" alt="Preview verifikasi">
                                    <canvas id="cameraCanvas"></canvas>
                                    <div class="camera-overlay"></div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="latitude" id="latitudeInput">
                        <input type="hidden" name="longitude" id="longitudeInput">
                        <input type="hidden" name="lokasi" id="lokasiInput">
                        <input type="hidden" name="accuracy" id="accuracyInput">
                        <input type="hidden" name="altitude" id="altitudeInput">
                        <input type="hidden" name="speed" id="speedInput">
                        <input type="hidden" name="device_info" id="deviceInfoInput">
                        <input type="hidden" name="location_readings" id="locationReadingsInput">
                        <input type="hidden" name="selfie_data" id="selfieDataInput">
                        <input type="hidden" name="face_descriptor" id="faceDescriptorInput">
                        <input type="hidden" name="liveness_score" id="livenessScoreInput">
                        <input type="hidden" name="liveness_challenges" id="livenessChallengesInput">
                    </form>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="bx bx-info-circle me-2"></i>
                        Validasi perangkat gagal. Selesaikan registrasi komputer sekolah lebih dulu dari menu admin.
                    </div>

                    @if($device && str_contains($accessMessage, 'Fingerprint browser'))
                        <form method="POST" action="{{ route('presensi_admin.kiosk_devices.sync_fingerprint', $device) }}" class="mt-3">
                            @csrf
                            <input type="hidden" name="browser_fingerprint" id="kioskFingerprintSyncInput">
                            <button type="submit" class="btn btn-primary" id="kioskFingerprintSyncButton" disabled>
                                Sinkronkan fingerprint browser ini
                            </button>
                            <div class="form-text mt-2">
                                Gunakan tombol ini dari komputer kiosk yang benar jika browser atau cookie sebelumnya berubah.
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    (function () {
        const buildFingerprint = () => {
            const parts = [
                navigator.userAgent || '',
                navigator.language || '',
                window.screen?.width || '',
                window.screen?.height || '',
                window.screen?.colorDepth || '',
                Intl.DateTimeFormat().resolvedOptions().timeZone || '',
                navigator.platform || '',
            ];

            return btoa(unescape(encodeURIComponent(parts.join('|')))).slice(0, 500);
        };

        const fingerprint = buildFingerprint();
        const syncInput = document.getElementById('kioskFingerprintSyncInput');
        const syncButton = document.getElementById('kioskFingerprintSyncButton');

        document.cookie = `nuist_kiosk_fingerprint=${encodeURIComponent(fingerprint)}; path=/; max-age=${60 * 60 * 24 * 365}; samesite=lax`;

        if (syncInput) {
            syncInput.value = fingerprint;
        }

        if (syncButton) {
            syncButton.disabled = false;
        }
    })();
</script>
@if($accessGranted)
<script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
<script src="{{ asset('js/face-recognition.js') }}"></script>
<script>
    (function () {
        const verificationMode = @json($verificationMode);
        const video = document.getElementById('cameraVideo');
        const preview = document.getElementById('cameraPreview');
        const canvas = document.getElementById('cameraCanvas');
        const teacherSelect = document.getElementById('teacherSelect');
        const teacherSearchInput = document.getElementById('teacherSearchInput');
        const teacherMeta = document.getElementById('teacherMeta');
        const captureLocationButton = document.getElementById('captureLocationButton');
        const locationStatus = document.getElementById('locationStatus');
        const startCameraButton = document.getElementById('startCameraButton');
        const captureVerificationButton = document.getElementById('captureVerificationButton');
        const captureStatus = document.getElementById('captureStatus');
        const submitAttendanceButton = document.getElementById('submitAttendanceButton');
        const form = document.getElementById('kioskAttendanceForm');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const faceRecognition = verificationMode === 'face_scan' ? new window.FaceRecognition() : null;

        const latitudeInput = document.getElementById('latitudeInput');
        const longitudeInput = document.getElementById('longitudeInput');
        const lokasiInput = document.getElementById('lokasiInput');
        const accuracyInput = document.getElementById('accuracyInput');
        const altitudeInput = document.getElementById('altitudeInput');
        const speedInput = document.getElementById('speedInput');
        const deviceInfoInput = document.getElementById('deviceInfoInput');
        const locationReadingsInput = document.getElementById('locationReadingsInput');
        const selfieDataInput = document.getElementById('selfieDataInput');
        const faceDescriptorInput = document.getElementById('faceDescriptorInput');
        const livenessScoreInput = document.getElementById('livenessScoreInput');
        const livenessChallengesInput = document.getElementById('livenessChallengesInput');

        let rawStream = null;
        let locationReadings = [];

        deviceInfoInput.value = navigator.userAgent || '';

        const stopRawCamera = () => {
            if (rawStream) {
                rawStream.getTracks().forEach((track) => track.stop());
                rawStream = null;
            }
        };

        const stopAllCamera = () => {
            stopRawCamera();
            if (faceRecognition) {
                faceRecognition.stopCamera(video);
            }
        };

        const updateSubmitState = () => {
            const teacherReady = !!teacherSelect.value;
            const locationReady = !!latitudeInput.value && !!longitudeInput.value;
            const captureReady = !!selfieDataInput.value;
            submitAttendanceButton.disabled = !(teacherReady && locationReady && captureReady);
        };

        teacherSelect.addEventListener('change', function () {
            const option = this.options[this.selectedIndex];

            if (!option || !option.value) {
                teacherMeta.textContent = 'Pilih guru terlebih dahulu.';
                updateSubmitState();
                return;
            }

            const nip = option.dataset.nip || '-';
            const nuptk = option.dataset.nuptk || '-';
            const face = option.dataset.face === '1' ? 'Wajah terdaftar' : 'Wajah belum terdaftar';
            teacherMeta.textContent = `NIP: ${nip} • NUPTK: ${nuptk} • ${face}`;
            updateSubmitState();
        });

        teacherSearchInput.addEventListener('input', function () {
            const keyword = this.value.trim().toLowerCase();
            const options = Array.from(teacherSelect.options);

            options.forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const haystack = [
                    option.textContent || '',
                    option.dataset.nip || '',
                    option.dataset.nuptk || '',
                ].join(' ').toLowerCase();

                option.hidden = keyword !== '' && !haystack.includes(keyword);
            });
        });

        captureLocationButton.addEventListener('click', async function () {
            if (!navigator.geolocation) {
                locationStatus.innerHTML = '<span class="text-danger">Browser ini tidak mendukung geolocation.</span>';
                return;
            }

            locationStatus.innerHTML = '<span class="text-muted">Mengambil lokasi...</span>';

            const readings = [];

            const readLocation = () => new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 12000,
                    maximumAge: 0,
                });
            });

            try {
                const first = await readLocation();
                const second = await readLocation();
                const active = second || first;

                [first, second].forEach((position) => {
                    if (!position) {
                        return;
                    }

                    readings.push({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy,
                        timestamp: Date.now(),
                    });
                });

                locationReadings = readings;
                latitudeInput.value = active.coords.latitude;
                longitudeInput.value = active.coords.longitude;
                lokasiInput.value = `${active.coords.latitude}, ${active.coords.longitude}`;
                accuracyInput.value = active.coords.accuracy || '';
                altitudeInput.value = active.coords.altitude || '';
                speedInput.value = active.coords.speed || '';
                locationReadingsInput.value = JSON.stringify(locationReadings);

                locationStatus.innerHTML = `<span class="text-success">Lokasi tersimpan. Akurasi ${Math.round(active.coords.accuracy || 0)} m.</span>`;
                updateSubmitState();
            } catch (error) {
                locationStatus.innerHTML = `<span class="text-danger">${error.message || 'Gagal mengambil lokasi.'}</span>`;
            }
        });

        startCameraButton.addEventListener('click', async function () {
            captureStatus.innerHTML = '<span class="text-muted">Mengaktifkan kamera...</span>';

            try {
                preview.classList.remove('show');
                video.classList.remove('hide');

                if (verificationMode === 'face_scan') {
                    await faceRecognition.initializeCamera(video);
                } else {
                    stopAllCamera();
                    rawStream = await navigator.mediaDevices.getUserMedia({
                        audio: false,
                        video: {
                            facingMode: 'user',
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                        },
                    });

                    video.srcObject = rawStream;
                    await video.play();
                }

                captureVerificationButton.disabled = false;
                captureStatus.innerHTML = '<span class="text-success">Kamera aktif dan siap dipakai.</span>';
            } catch (error) {
                captureStatus.innerHTML = `<span class="text-danger">${error.message || 'Kamera tidak dapat diakses.'}</span>`;
            }
        });

        captureVerificationButton.addEventListener('click', async function () {
            if (!teacherSelect.value) {
                captureStatus.innerHTML = '<span class="text-danger">Pilih guru terlebih dahulu.</span>';
                return;
            }

            if (verificationMode === 'face_scan' && teacherSelect.options[teacherSelect.selectedIndex].dataset.face !== '1') {
                captureStatus.innerHTML = '<span class="text-danger">Guru ini belum memiliki data wajah terdaftar.</span>';
                return;
            }

            try {
                if (verificationMode === 'face_scan') {
                    const result = await faceRecognition.performAttendanceScan(video, {
                        onInstruction: (message) => {
                            captureStatus.innerHTML = `<span class="text-muted">${message}</span>`;
                        },
                        onStatus: (message) => {
                            captureStatus.innerHTML = `<span class="text-muted">${message}</span>`;
                        },
                    });

                    selfieDataInput.value = result.captured_image;
                    faceDescriptorInput.value = JSON.stringify(result.face_descriptor || []);
                    livenessScoreInput.value = result.liveness_score ?? 1;
                    livenessChallengesInput.value = JSON.stringify(result.liveness_challenges || []);
                    preview.src = result.captured_image;
                    preview.classList.add('show');
                    video.classList.add('hide');
                    captureStatus.innerHTML = '<span class="text-success">Scan wajah berhasil disimpan.</span>';
                } else {
                    canvas.width = video.videoWidth || 640;
                    canvas.height = video.videoHeight || 480;
                    const context = canvas.getContext('2d');
                    context.translate(canvas.width, 0);
                    context.scale(-1, 1);
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    context.setTransform(1, 0, 0, 1, 0, 0);

                    const imageData = canvas.toDataURL('image/jpeg', 0.9);
                    selfieDataInput.value = imageData;
                    faceDescriptorInput.value = '';
                    livenessScoreInput.value = '';
                    livenessChallengesInput.value = '';
                    preview.src = imageData;
                    preview.classList.add('show');
                    video.classList.add('hide');
                    captureStatus.innerHTML = '<span class="text-success">Selfie berhasil diambil.</span>';
                }

                updateSubmitState();
            } catch (error) {
                captureStatus.innerHTML = `<span class="text-danger">${error.message || 'Verifikasi kamera gagal.'}</span>`;
            }
        });

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            updateSubmitState();

            if (submitAttendanceButton.disabled) {
                return;
            }

            submitAttendanceButton.disabled = true;
            submitAttendanceButton.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Menyimpan...';

            const payload = new FormData(form);
            if (faceDescriptorInput.value) {
                JSON.parse(faceDescriptorInput.value).forEach((value, index) => {
                    payload.append(`face_descriptor[${index}]`, value);
                });
                payload.delete('face_descriptor');
            }

            if (livenessChallengesInput.value) {
                JSON.parse(livenessChallengesInput.value).forEach((challenge, index) => {
                    payload.append(`liveness_challenges[${index}][type]`, challenge.type);
                    payload.append(`liveness_challenges[${index}][passed]`, challenge.passed ? 1 : 0);
                    if (challenge.score !== undefined && challenge.score !== null) {
                        payload.append(`liveness_challenges[${index}][score]`, challenge.score);
                    }
                    if (challenge.detail !== undefined && challenge.detail !== null) {
                        payload.append(`liveness_challenges[${index}][detail]`, challenge.detail);
                    }
                    payload.append(`liveness_challenges[${index}][timestamp]`, challenge.timestamp);
                });
                payload.delete('liveness_challenges');
            }

            try {
                const response = await fetch(@json(route('school-kiosk.submit')), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: payload,
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Submit presensi gagal.');
                }

                captureStatus.innerHTML = `<span class="text-success">${data.message} • ${data.teacher_name}</span>`;
                selfieDataInput.value = '';
                faceDescriptorInput.value = '';
                livenessScoreInput.value = '';
                livenessChallengesInput.value = '';
                preview.classList.remove('show');
                video.classList.remove('hide');
                updateSubmitState();
            } catch (error) {
                captureStatus.innerHTML = `<span class="text-danger">${error.message}</span>`;
            } finally {
                submitAttendanceButton.innerHTML = '<i class="bx bx-check-circle me-1"></i>Simpan Presensi';
                updateSubmitState();
            }
        });

        window.addEventListener('beforeunload', () => {
            stopAllCamera();
        });
    })();
</script>
@endif
@endsection
