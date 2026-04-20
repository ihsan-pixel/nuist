{{-- resources/views/mgmp/laporan.blade.php --}}
@extends('layouts.master')

@section('title') Kegiatan dan Presensi MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') MGMP @endslot
    @slot('li_2') Kegiatan @endslot
    @slot('title') Kegiatan dan Presensi MGMP @endslot
@endcomponent

@php
    $canCreateActivity = in_array($user->role, ['super_admin', 'admin', 'pengurus']) || !empty($mgmpGroup);
    $now = \Carbon\Carbon::now('Asia/Jakarta');
@endphp

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Data belum valid.</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h4 class="card-title mb-1 text-dark">
                            <i class="mdi mdi-calendar-check text-primary me-2"></i>
                            Kegiatan dan Presensi MGMP
                        </h4>
                        <p class="text-muted mb-0">Buat kegiatan MGMP dan pantau presensi anggota berbasis GPS dan selfie.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKegiatanModal" @disabled(!$canCreateActivity)>
                        <i class="mdi mdi-plus me-1"></i>
                        Tambah Kegiatan
                    </button>
                </div>
                @if(!$canCreateActivity)
                    <div class="alert alert-warning mt-3 mb-0">
                        Anda belum memiliki data MGMP. Buat data MGMP terlebih dahulu sebelum membuat kegiatan.
                    </div>
                @endif
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                <i class="mdi mdi-calendar-multiple fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $totalLaporan }}</h5>
                        <small class="text-muted">Total Kegiatan</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                <i class="mdi mdi-calendar-month fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $laporanBulanIni }}</h5>
                        <small class="text-muted">Bulan Ini</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                <i class="mdi mdi-account-check fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $totalPeserta }}</h5>
                        <small class="text-muted">Total Presensi</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-body p-3 text-center">
                        <div class="avatar-sm mx-auto mb-2">
                            <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                <i class="mdi mdi-clock-outline fs-4"></i>
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $rataRataDurasi }}</h5>
                        <small class="text-muted">Jam Rata-rata</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-dark py-3 ps-4">No</th>
                                <th class="border-0 fw-semibold text-dark py-3">Kegiatan</th>
                                <th class="border-0 fw-semibold text-dark py-3">Jadwal</th>
                                <th class="border-0 fw-semibold text-dark py-3">Lokasi Presensi</th>
                                <th class="border-0 fw-semibold text-dark py-3">Presensi</th>
                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan as $report)
                                @php
                                    $start = $report->tanggal && $report->waktu_mulai
                                        ? \Carbon\Carbon::parse($report->tanggal->format('Y-m-d') . ' ' . $report->waktu_mulai, 'Asia/Jakarta')
                                        : null;
                                    $end = $report->tanggal && $report->waktu_selesai
                                        ? \Carbon\Carbon::parse($report->tanggal->format('Y-m-d') . ' ' . $report->waktu_selesai, 'Asia/Jakarta')
                                        : null;
                                    $isOngoing = $start && $end && $now->betweenIncluded($start, $end);
                                @endphp
                                <tr class="border-bottom border-light">
                                    <td class="py-3 ps-4">{{ $loop->iteration }}</td>
                                    <td class="py-3">
                                        <h6 class="mb-1">{{ $report->judul }}</h6>
                                        <div class="text-muted small">{{ $report->mgmpGroup->name ?? 'MGMP tidak tersedia' }}</div>
                                        @if($report->deskripsi)
                                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit($report->deskripsi, 90) }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @if($start && $end)
                                            <div class="fw-medium">{{ $start->format('d M Y') }}</div>
                                            <small class="text-muted">{{ $start->format('H:i') }} - {{ $end->format('H:i') }} WIB</small>
                                            <div class="mt-1">
                                                @if($isOngoing)
                                                    <span class="badge bg-success">Sedang Berlangsung</span>
                                                @elseif($now->lt($start))
                                                    <span class="badge bg-info">Akan Datang</span>
                                                @else
                                                    <span class="badge bg-secondary">Selesai</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">Jadwal belum lengkap</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="small">
                                            <div>{{ $report->lokasi ?: 'Tanpa nama lokasi' }}</div>
                                            <div class="text-muted">{{ $report->latitude }}, {{ $report->longitude }}</div>
                                            <div class="text-muted">Radius {{ $report->radius_meters ?? 100 }} meter</div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#pesertaModal{{ $report->id }}">
                                            {{ $report->attendances_count }} hadir
                                        </button>
                                    </td>
                                    <td class="py-3 pe-4">
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('mgmp.kegiatan.presensi', $report) }}" target="_blank" class="btn btn-sm btn-success">
                                                <i class="mdi mdi-cellphone-check me-1"></i> Form Presensi
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-copy-presensi" data-url="{{ route('mgmp.kegiatan.presensi', $report) }}">
                                                <i class="mdi mdi-content-copy me-1"></i> Salin Link
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="mdi mdi-calendar-remove fs-1"></i>
                                            </div>
                                        </div>
                                        <h6 class="text-muted">Belum ada kegiatan MGMP</h6>
                                        <p class="text-muted small mb-0">Klik tombol Tambah Kegiatan untuk membuat jadwal presensi MGMP.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($laporan as $report)
    <div class="modal fade" id="pesertaModal{{ $report->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Presensi: {{ $report->judul }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Waktu</th>
                                    <th>Jarak</th>
                                    <th>Selfie</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($report->attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->user->name ?? 'User tidak tersedia' }}</td>
                                        <td>{{ $attendance->attended_at ? $attendance->attended_at->format('d M Y H:i') : '-' }}</td>
                                        <td>{{ $attendance->distance_meters ?? '-' }} m</td>
                                        <td>
                                            @if($attendance->selfie_path)
                                                <a href="{{ route('foto.mgmp_attendance', $attendance) }}" target="_blank">Lihat</a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada anggota yang presensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="tambahKegiatanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kegiatan MGMP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('mgmp.laporan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        @if(in_array($user->role, ['super_admin', 'admin', 'pengurus']))
                            <div class="col-12">
                                <label class="form-label">Grup MGMP</label>
                                <select class="form-select" name="mgmp_group_id" required>
                                    <option value="">Pilih Grup MGMP</option>
                                    @foreach($mgmpGroups as $group)
                                        <option value="{{ $group->id }}" @selected(old('mgmp_group_id') == $group->id)>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="col-12">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" class="form-control" name="judul" value="{{ old('judul') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" value="{{ old('tanggal') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="time" class="form-control" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Waktu Selesai</label>
                            <input type="time" class="form-control" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Lokasi</label>
                            <input type="text" class="form-control" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Aula LP Ma'arif NU DIY">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Metode Penentuan Lokasi</label>
                            <div class="location-method-grid">
                                <label class="location-method-card">
                                    <input type="radio" name="location_method" value="link" class="location-method-input" checked>
                                    <span class="location-method-icon"><i class="mdi mdi-link-variant"></i></span>
                                    <span class="location-method-title">Gunakan Link</span>
                                    <small>Tempel link Google Maps / OpenStreetMap</small>
                                </label>
                                <label class="location-method-card">
                                    <input type="radio" name="location_method" value="current" class="location-method-input">
                                    <span class="location-method-icon"><i class="mdi mdi-crosshairs-gps"></i></span>
                                    <span class="location-method-title">Lokasi Saat Ini</span>
                                    <small>Ambil titik dari GPS perangkat</small>
                                </label>
                                <label class="location-method-card">
                                    <input type="radio" name="location_method" value="map" class="location-method-input">
                                    <span class="location-method-icon"><i class="mdi mdi-map-marker-radius"></i></span>
                                    <span class="location-method-title">Pilih di Map</span>
                                    <small>Klik peta atau geser marker</small>
                                </label>
                            </div>
                            <small class="text-muted">Pilih salah satu metode. Semua metode akan mengisi latitude dan longitude kegiatan.</small>
                        </div>

                        <div class="col-12 location-method-panel" data-location-panel="link">
                            <label class="form-label">Link Lokasi</label>
                            <div class="input-group">
                                <input type="url" class="form-control" id="locationLinkInput" placeholder="Tempel link Google Maps / OpenStreetMap di sini">
                                <button type="button" class="btn btn-outline-primary" id="btnApplyLocationLink">
                                    Gunakan Link
                                </button>
                            </div>
                            <small class="text-muted">Contoh: link Google Maps yang mengandung koordinat `@lat,lng` atau `q=lat,lng`.</small>
                        </div>

                        <div class="col-12 location-method-panel d-none" data-location-panel="current">
                            <div class="location-method-helper">
                                <div>
                                    <h6 class="mb-1">Gunakan Lokasi Saat Ini</h6>
                                    <small class="text-muted">Pastikan Anda sedang berada di lokasi kegiatan dan browser mengizinkan akses lokasi.</small>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btnUseCurrentLocation">
                                    <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi Saat Ini
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="0.00000001" class="form-control" id="latitudeInput" name="latitude" value="{{ old('latitude') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="0.00000001" class="form-control" id="longitudeInput" name="longitude" value="{{ old('longitude') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Radius Presensi (meter)</label>
                            <input type="number" min="10" max="1000" class="form-control" name="radius_meters" value="{{ old('radius_meters', 100) }}" required>
                        </div>
                        <div class="col-12 location-method-panel d-none" data-location-panel="map">
                            <div id="mgmpLocationMap" style="height: 320px; border-radius: 14px; overflow: hidden; border: 1px solid #dee2e6;"></div>
                            <small class="text-muted d-block mt-2">
                                Klik peta untuk menentukan titik lokasi atau geser marker yang muncul.
                            </small>
                        </div>
                        <div class="col-12">
                            <div id="locationPickerStatus" class="small text-muted">
                                Belum ada titik lokasi dipilih.
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi (opsional)</label>
                            <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" @disabled(!$canCreateActivity)>Simpan Kegiatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const locationButton = document.getElementById('btnUseCurrentLocation');
    const applyLocationLinkButton = document.getElementById('btnApplyLocationLink');
    const locationLinkInput = document.getElementById('locationLinkInput');
    const latitudeInput = document.getElementById('latitudeInput');
    const longitudeInput = document.getElementById('longitudeInput');
    const locationStatus = document.getElementById('locationPickerStatus');
    const modalElement = document.getElementById('tambahKegiatanModal');
    const methodInputs = document.querySelectorAll('.location-method-input');
    const methodPanels = document.querySelectorAll('.location-method-panel');
    const methodCards = document.querySelectorAll('.location-method-card');
    const defaultLat = -7.80119450;
    const defaultLng = 110.36491730;
    let locationMap = null;
    let locationMarker = null;

    function setStatus(message, type = 'muted') {
        locationStatus.className = 'small mt-1 text-' + type;
        locationStatus.textContent = message;
    }

    function selectedLocationMethod() {
        const selected = document.querySelector('.location-method-input:checked');
        return selected ? selected.value : 'link';
    }

    function applyLocationMethod(method) {
        methodPanels.forEach(function (panel) {
            panel.classList.toggle('d-none', panel.dataset.locationPanel !== method);
        });

        methodCards.forEach(function (card) {
            const input = card.querySelector('.location-method-input');
            card.classList.toggle('active', input && input.checked);
        });

        if (method === 'map') {
            initializeLocationMap();
            setTimeout(function () {
                if (locationMap) {
                    locationMap.invalidateSize();
                }
            }, 120);
        }

        const methodMessages = {
            link: 'Tempel link lokasi lalu klik Gunakan Link.',
            current: 'Klik Ambil Lokasi Saat Ini untuk memakai GPS perangkat.',
            map: 'Klik peta untuk menentukan titik lokasi kegiatan.'
        };

        if (!latitudeInput.value || !longitudeInput.value) {
            setStatus(methodMessages[method] || 'Pilih metode penentuan lokasi.');
        }
    }

    function updateCoordinateInputs(lat, lng, message = null) {
        latitudeInput.value = Number(lat).toFixed(8);
        longitudeInput.value = Number(lng).toFixed(8);

        if (locationMarker) {
            locationMarker.setLatLng([lat, lng]);
        } else if (locationMap) {
            locationMarker = L.marker([lat, lng], { draggable: true }).addTo(locationMap);
            locationMarker.on('dragend', function (event) {
                const position = event.target.getLatLng();
                updateCoordinateInputs(position.lat, position.lng, 'Marker digeser. Koordinat diperbarui.');
                locationMap.panTo(position);
            });
        }

        if (locationMap) {
            locationMap.setView([lat, lng], Math.max(locationMap.getZoom(), 16));
        }

        setStatus(message || ('Titik dipilih: ' + Number(lat).toFixed(8) + ', ' + Number(lng).toFixed(8)), 'success');
    }

    function extractCoordinatesFromLink(url) {
        const patterns = [
            /@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/,
            /[?&]q=(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/,
            /[?&]ll=(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/,
            /!3d(-?\d+(?:\.\d+)?)!4d(-?\d+(?:\.\d+)?)/,
            /#map=\d+\/(-?\d+(?:\.\d+)?)\/(-?\d+(?:\.\d+)?)/,
            /(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)/
        ];

        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match) {
                return {
                    lat: parseFloat(match[1]),
                    lng: parseFloat(match[2])
                };
            }
        }

        return null;
    }

    function initializeLocationMap() {
        if (locationMap) {
            locationMap.invalidateSize();
            return;
        }

        locationMap = L.map('mgmpLocationMap').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(locationMap);

        locationMap.on('click', function (event) {
            updateCoordinateInputs(event.latlng.lat, event.latlng.lng, 'Titik lokasi dipilih dari peta.');
        });

        const initialLat = parseFloat(latitudeInput.value);
        const initialLng = parseFloat(longitudeInput.value);
        if (!Number.isNaN(initialLat) && !Number.isNaN(initialLng)) {
            updateCoordinateInputs(initialLat, initialLng, 'Koordinat awal dimuat ke peta.');
        } else {
            setStatus('Belum ada titik lokasi dipilih.');
        }
    }

    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function () {
            applyLocationMethod(selectedLocationMethod());
            if (selectedLocationMethod() === 'map' && locationMap) {
                setTimeout(function () {
                    locationMap.invalidateSize();
                }, 100);
            }
        });
    }

    methodInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            applyLocationMethod(input.value);
        });
    });

    applyLocationMethod(selectedLocationMethod());

    if (locationButton) {
        locationButton.addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung GPS.');
                return;
            }

            locationButton.disabled = true;
            locationButton.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Mengambil lokasi...';

            navigator.geolocation.getCurrentPosition(function (position) {
                updateCoordinateInputs(
                    position.coords.latitude,
                    position.coords.longitude,
                    'Lokasi saat ini berhasil digunakan.'
                );
                locationButton.disabled = false;
                locationButton.innerHTML = '<i class="mdi mdi-crosshairs-gps me-1"></i> Gunakan Lokasi Saat Ini';
            }, function () {
                alert('Gagal mengambil lokasi. Pastikan izin lokasi aktif.');
                locationButton.disabled = false;
                locationButton.innerHTML = '<i class="mdi mdi-crosshairs-gps me-1"></i> Gunakan Lokasi Saat Ini';
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        });
    }

    if (applyLocationLinkButton) {
        applyLocationLinkButton.addEventListener('click', function () {
            const rawLink = (locationLinkInput.value || '').trim();
            if (!rawLink) {
                setStatus('Tempel link lokasi terlebih dahulu.', 'danger');
                return;
            }

            const coordinates = extractCoordinatesFromLink(rawLink);
            if (!coordinates || Number.isNaN(coordinates.lat) || Number.isNaN(coordinates.lng)) {
                setStatus('Koordinat tidak ditemukan dari link lokasi tersebut.', 'danger');
                return;
            }

            updateCoordinateInputs(coordinates.lat, coordinates.lng, 'Koordinat berhasil diambil dari link lokasi.');
        });
    }

    [latitudeInput, longitudeInput].forEach(function (input) {
        input.addEventListener('change', function () {
            const lat = parseFloat(latitudeInput.value);
            const lng = parseFloat(longitudeInput.value);

            if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                updateCoordinateInputs(lat, lng, 'Koordinat manual diterapkan ke peta.');
            }
        });
    });

    document.querySelectorAll('.btn-copy-presensi').forEach(function (button) {
        button.addEventListener('click', async function () {
            const url = button.dataset.url;
            try {
                await navigator.clipboard.writeText(url);
                button.innerHTML = '<i class="mdi mdi-check me-1"></i> Tersalin';
                setTimeout(function () {
                    button.innerHTML = '<i class="mdi mdi-content-copy me-1"></i> Salin Link';
                }, 1500);
            } catch (e) {
                prompt('Salin link presensi berikut:', url);
            }
        });
    });
});
</script>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.location-method-grid {
    display: grid;
    gap: 10px;
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.location-method-card {
    border: 1px solid #dee2e6;
    border-radius: 14px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px;
    transition: all 0.2s ease;
}

.location-method-card.active {
    background: rgba(13, 110, 253, 0.08);
    border-color: #0d6efd;
    box-shadow: 0 8px 22px rgba(13, 110, 253, 0.12);
}

.location-method-card input {
    display: none;
}

.location-method-icon {
    align-items: center;
    background: #f1f5f9;
    border-radius: 10px;
    display: inline-flex;
    height: 34px;
    justify-content: center;
    width: 34px;
}

.location-method-icon i {
    color: #0d6efd;
    font-size: 20px;
}

.location-method-title {
    color: #1f2937;
    font-weight: 700;
}

.location-method-card small {
    color: #6c757d;
    line-height: 1.35;
}

.location-method-helper {
    align-items: center;
    background: #f8fbff;
    border: 1px solid #dbeafe;
    border-radius: 14px;
    display: flex;
    gap: 12px;
    justify-content: space-between;
    padding: 14px;
}

#mgmpLocationMap .leaflet-control-attribution {
    font-size: 10px;
}

@media (max-width: 768px) {
    .location-method-grid {
        grid-template-columns: 1fr;
    }

    .location-method-helper {
        align-items: stretch;
        flex-direction: column;
    }
}
</style>
@endsection
