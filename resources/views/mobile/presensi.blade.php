@extends('layouts.mobile')

@section('title', 'Presensi')
@section('subtitle', 'Catat Kehadiran')

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
        <script>
        $(document).ready(function() {
            function handleFormSubmit(formSelector, type) {
                $(formSelector).on('submit', function(e) {
                    e.preventDefault();

                    let $form = $(this);
                    let formData = new FormData(this);
                    formData.append('type', type);

                    // Basic client validation per type
                    if (type === 'terlambat' || type === 'tidak_masuk') {
                        let alasan = $form.find('textarea[name="alasan"]').val();
                        let file = $form.find('input[name="file_izin"]')[0];
                        if (!alasan || !file || !file.files || file.files.length === 0) {
                            Swal.fire({icon:'warning', title:'Data Tidak Lengkap', text:'Lengkapi alasan dan upload file izin.'});
                            return;
                        }
                        if (type === 'terlambat') {
                            let waktu = $form.find('input[name="waktu_masuk"]').val();
                            if (!waktu) {
                                Swal.fire({icon:'warning', title:'Data Tidak Lengkap', text:'Waktu masuk yang diminta harus diisi.'});
                                return;
                            }
                        }
                    }

                    if (type === 'tugas_luar') {
                        let deskripsi = $form.find('textarea[name="deskripsi_tugas"]').val();
                        let lokasi = $form.find('input[name="lokasi_tugas"]').val();
                        let waktuKeluar = $form.find('input[name="waktu_keluar"]').val();
                        let file = $form.find('input[name="file_tugas"]')[0];
                        if (!deskripsi || !lokasi || !waktuKeluar || !file || !file.files || file.files.length === 0) {
                            Swal.fire({icon:'warning', title:'Data Tidak Lengkap', text:'Lengkapi semua field untuk izin tugas diluar.'});
                            return;
                        }
                    }

                    let submitBtn = $form.find('button[type="submit"]');
                    let originalText = submitBtn.html();
                    submitBtn.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Mengirim...');

                    $.ajax({
                        url: '{{ route("mobile.izin.store") }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        timeout: 30000,
                        success: function(res) {
                            if (res.success) {
                                // hide modal
                                $($form.closest('.modal')).modal('hide');
                                Swal.fire({icon:'success', title:'Berhasil', text: res.message, timer:2000, timerProgressBar:true}).then(()=>{
                                    // reload presensi page to reflect changes
                                    location.reload();
                                });
                            } else {
                                Swal.fire({icon:'error', title:'Gagal', text: res.message || 'Terjadi kesalahan.'});
                                submitBtn.prop('disabled', false).html(originalText);
                            }
                        },
                        error: function(xhr) {
                            let msg = 'Terjadi kesalahan.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire({icon:'error', title:'Kesalahan', text: msg});
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    });
                });
            }

            handleFormSubmit('#izinTidakMasukForm', 'tidak_masuk');
            handleFormSubmit('#izinTerlambatForm', 'terlambat');
            handleFormSubmit('#izinTugasLuarForm', 'tugas_luar');
        });
        </script>
        }

        .alert-custom {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 10px;
        }

        .alert-custom.warning {
            border-left: 4px solid #ffc107;
        }

        .alert-custom.danger {
            border-left: 4px solid #dc3545;
        }

        .alert-custom.info {
            border-left: 4px solid #0dcaf0;
        }
    </style>

    <!-- Header -->
    <div class="presensi-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1">Presensi Digital</h6>
                <h5 class="fw-bold mb-0">{{ Auth::user()->madrasah?->name ?? 'Madrasah' }}</h5>
            </div>
            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                 class="rounded-circle border border-white" width="32" height="32" alt="User">
        </div>
    </div>

    <!-- Status Card -->
    @if($isHoliday)
    <div class="alert-custom warning">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-calendar-x"></i>
            </div>
            <div>
                <h6 class="mb-0">Hari Libur</h6>
                <p class="mb-0">{{ $holiday->name ?? 'Hari ini libur' }}</p>
            </div>
        </div>
    </div>
    @elseif($presensiHariIni)
    <div class="status-card success">
        <div class="d-flex align-items-center">
            <div class="status-icon">
                <i class="bx bx-check-circle"></i>
            </div>
            <div>
                <h6 class="mb-1">Presensi Sudah Dicatat</h6>
                <p class="mb-1">Masuk: <strong>{{ $presensiHariIni->waktu_masuk->format('H:i') }}</strong></p>
                @if($presensiHariIni->waktu_keluar)
                <p class="mb-0">Keluar: <strong>{{ $presensiHariIni->waktu_keluar->format('H:i') }}</strong></p>
                <div class="alert-custom success" style="margin-top: 6px; padding: 4px;">
                    <small><i class="bx bx-check me-1"></i> Presensi hari ini lengkap!</small>
                </div>
                @else
                <p class="mb-0 text-muted">Lakukan presensi keluar jika sudah selesai.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Presensi Form -->
    <div class="presensi-form">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-{{ $presensiHariIni ? 'log-out-circle' : 'log-in-circle' }}"></i>
            </div>
            <h6 class="section-title mb-0">{{ $presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk' }}</h6>
        </div>

        <!-- Location Status -->
        <div class="form-section">
            <div id="location-info" class="location-info info">
                <div class="d-flex align-items-center">
                    <i class="bx bx-loader-alt bx-spin me-1"></i>
                    <div>
                        <strong>Mendapatkan lokasi...</strong>
                        <br><small class="text-muted">Pastikan GPS aktif</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coordinates -->
        <div class="form-section">
            <div class="d-flex align-items-center mb-1">
                <i class="bx bx-target-lock text-success me-1"></i>
                <label class="section-title mb-0">Koordinat Lokasi</label>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px;">
                <input type="text" id="latitude" class="coordinate-input" placeholder="Latitude" readonly>
                <input type="text" id="longitude" class="coordinate-input" placeholder="Longitude" readonly>
            </div>
        </div>

        <!-- Address -->
        <div class="form-section">
            <div class="d-flex align-items-center mb-1">
                <i class="bx bx-home text-info me-1"></i>
                <label class="section-title mb-0">Alamat Lokasi</label>
            </div>
            <input type="text" id="lokasi" class="address-input" placeholder="Alamat akan muncul otomatis" readonly>
        </div>

        <!-- Presensi Button -->
        <button type="button" id="btn-presensi"
                class="presensi-btn"
                {{ ($presensiHariIni && $presensiHariIni->waktu_keluar) || $isHoliday ? 'disabled' : '' }}>
            <i class="bx bx-{{ $isHoliday ? 'calendar-x' : 'check-circle' }} me-1"></i>
            {{ $isHoliday ? 'Hari Libur - Presensi Ditutup' : ($presensiHariIni ? 'Presensi Keluar' : 'Presensi Masuk') }}
        </button>
    </div>

    <!-- Time Information -->
    @if(isset($timeRanges) && $timeRanges)
    <div class="schedule-section">
        <div class="d-flex align-items-center mb-2">
            <div class="status-icon">
                <i class="bx bx-calendar-check"></i>
            </div>
            <h6 class="section-title mb-0">Jadwal Presensi</h6>
        </div>
        <div class="schedule-grid">
            <div class="schedule-item masuk">
                <i class="bx bx-log-in-circle text-primary"></i>
                <h6 class="text-primary">Masuk</h6>
                <p>{{ $timeRanges['masuk_start'] }} - {{ $timeRanges['masuk_end'] }}</p>
            </div>
            <div class="schedule-item pulang">
                <i class="bx bx-log-out-circle text-success"></i>
                <h6 class="text-success">Pulang</h6>
                <p>{{ $timeRanges['pulang_start'] }} - {{ $timeRanges['pulang_end'] }}</p>
            </div>
        </div>
        @if(auth()->user()->madrasah && auth()->user()->madrasah->hari_kbm == '6')
        <div class="alert-custom info" style="margin-top: 6px;">
            <small>
                <i class="bx bx-info-circle me-1"></i>
                <strong>Catatan:</strong> Sabtu pulang mulai 12:00, hari lain 13:00.
            </small>
        </div>
        @endif
    </div>
    @else
    <div class="alert-custom warning">
        <i class="bx bx-info-circle me-1"></i>
        <strong>Pengaturan Presensi:</strong> Hari KBM belum diatur. Hubungi admin.
    </div>
    @endif

    <!-- Important Notice -->
    <div class="alert-custom danger">
        <div class="d-flex">
            <i class="bx bx-error-circle text-danger me-1"></i>
            <div>
                <strong class="text-danger">Penting!</strong>
                <p class="mb-0 text-muted">Pastikan berada di lingkungan madrasah untuk presensi.</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Presensi Button -->
    <div class="presensi-form">
        <a href="{{ route('mobile.riwayat-presensi') }}" class="presensi-btn" style="display: block; text-decoration: none; color: #fff; text-align: center;">
            <i class="bx bx-history me-1"></i>
            Riwayat Presensi
        </a>
    </div>

    <!-- Izin Buttons -->
    <div class="izin-section">
        <div class="izin-buttons">
            <button type="button" class="izin-btn" data-bs-toggle="modal" data-bs-target="#izinTidakMasukModal" aria-label="Izin Tidak Masuk">
                <i class="bx bx-user-x"></i>
                Izin Tidak Masuk
            </button>

            <button type="button" class="izin-btn izin-terlambat" data-bs-toggle="modal" data-bs-target="#izinTerlambatModal" aria-label="Izin Terlambat">
                <i class="bx bx-time-five"></i>
                Izin Terlambat
            </button>

            <button type="button" class="izin-btn izin-tugas-luar" data-bs-toggle="modal" data-bs-target="#izinTugasLuarModal" aria-label="Izin Tugas Diluar">
                <i class="bx bx-briefcase"></i>
                Izin Tugas Diluar
            </button>
        </div>
    </div>

    <!-- Izin Tidak Masuk Modal -->
    <div class="modal fade" id="izinTidakMasukModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Izin Tidak Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="izinTidakMasukForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Alasan</label>
                            <textarea class="form-control" name="alasan" rows="3" placeholder="Jelaskan alasan Anda..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Surat Izin / Foto</label>
                            <input type="file" class="form-control" name="file_izin" accept="image/*,.pdf,.doc,.docx" required>
                            <small class="text-muted">Format: JPG, PNG, PDF, DOC. Maks 5MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Izin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Izin Terlambat Modal -->
    <div class="modal fade" id="izinTerlambatModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Izin Terlambat Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="izinTerlambatForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Alasan Terlambat</label>
                            <textarea class="form-control" name="alasan" rows="3" placeholder="Jelaskan alasan keterlambatan Anda..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Surat Izin/Foto</label>
                            <input type="file" class="form-control" name="file_izin" accept="image/*,.pdf,.doc,.docx" required>
                            <small class="text-muted">Format: JPG, PNG, PDF, DOC. Maks 5MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Waktu Masuk yang Diminta</label>
                            <input type="time" class="form-control" name="waktu_masuk" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Izin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Izin Tugas Diluar Modal -->
    <div class="modal fade" id="izinTugasLuarModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Izin Tugas Diluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="izinTugasLuarForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Tugas</label>
                            <textarea class="form-control" name="deskripsi_tugas" rows="3" placeholder="Jelaskan tugas yang akan dilakukan diluar..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lokasi Tugas</label>
                            <input type="text" class="form-control" name="lokasi_tugas" placeholder="Masukkan lokasi tugas" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Surat Tugas/Foto</label>
                            <input type="file" class="form-control" name="file_tugas" accept="image/*,.pdf,.doc,.docx" required>
                            <small class="text-muted">Format: JPG, PNG, PDF, DOC. Maks 5MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Estimasi Waktu Keluar</label>
                            <input type="time" class="form-control" name="waktu_keluar" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Izin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals for inline izin removed — uses dedicated halaman izin instead -->
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('load', function() {
    let latitude, longitude, lokasi;

    // Get location when page loads (reading1)
    if (navigator.geolocation) {
            $('#location-info').html(`
                <div class="location-info info">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-loader-alt bx-spin me-2"></i>
                        <div>
                            <strong class="small">Mendapatkan lokasi Anda...</strong>
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
                <div class="location-info success">
                    <div class="d-flex align-items-center">
                        <i class="bx bx-check-circle me-2"></i>
                        <div>
                            <strong class="small">Lokasi berhasil didapatkan!</strong>
                        </div>
                    </div>
                </div>
            `);



        }, function(error) {
        $('#location-info').html(`
            <div class="location-info error">
                <div class="d-flex align-items-center">
                    <i class="bx bx-error-circle me-2"></i>
                    <div>
                        <strong class="small">Gagal mendapatkan lokasi</strong>
                        <br><small class="text-muted">${error.message}</small>
                    </div>
                </div>
            </div>
        `);


        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 30000
        });
    } else {
    $('#location-info').html(`
        <div class="location-info error">
            <div class="d-flex align-items-center">
                <i class="bx bx-error-circle me-2"></i>
                <div>
                    <strong class="small">Browser tidak mendukung GPS</strong>
                    <br><small class="text-muted">Silakan gunakan browser modern dengan dukungan GPS</small>
                </div>
            </div>
        </div>
    `);


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
                    <div class="location-info success">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle me-2"></i>
                            <div>
                                <strong class="small">Lokasi berhasil didapatkan!</strong>
                            </div>
                        </div>
                    </div>
                `);

                $.ajax({
                    url: '{{ route("mobile.presensi.store") }}',
                    method: 'POST',
                    data: postData,
                    timeout: 30000, // 30 detik timeout
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                confirmButtonText: 'Oke',
                                timer: 3000,
                                timerProgressBar: true
                            }).then(() => {
                                location.reload();
                            });
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
                    error: function(xhr, status, error) {
                        let errorMessage = 'Terjadi kesalahan tidak diketahui';

                        if (status === 'timeout') {
                            errorMessage = 'Waktu koneksi habis. Silakan coba lagi.';
                        } else if (xhr.status === 0) {
                            errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: errorMessage,
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


</script>
@endsection
