@extends('layouts.master')

@section('title')Deteksi Fake Location Presensi@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Deteksi Fake Location @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-map-pin text-danger me-2"></i>
                    Deteksi Presensi dengan Fake Location
                </h4>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('fake-location.index') }}" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date"
                               value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <label for="kabupaten" class="form-label">Kabupaten</label>
                        <select class="form-control" id="kabupaten" name="kabupaten" onchange="this.form.submit()">
                            <option value="">Semua Kabupaten</option>
                            @foreach($kabupatenList as $kab)
                                <option value="{{ $kab }}" {{ $selectedKabupaten == $kab ? 'selected' : '' }}>
                                    {{ $kab }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="madrasah_id" class="form-label">Madrasah</label>
                        <select class="form-control" id="madrasah_id" name="madrasah_id" onchange="this.form.submit()">
                            <option value="">Semua Madrasah</option>
                            @foreach($madrasahs as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ $selectedMadrasah == $madrasah->id ? 'selected' : '' }}>
                                    {{ $madrasah->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('fake-location.index') }}" class="btn btn-secondary">
                            <i class="bx bx-reset me-1"></i>Reset
                        </a>
                    </div>
                </form>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ count($fakeLocationData) }}</h5>
                                        <p class="mb-0">Presensi Mencurigakan</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-danger rounded-circle">
                                            <i class="bx bx-error font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ count(array_filter($fakeLocationData, fn($item) => $item['analysis']['severity'] >= 3)) }}</h5>
                                        <p class="mb-0">Tingkat Tinggi</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-warning rounded-circle">
                                            <i class="bx bx-error-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ count(array_filter($fakeLocationData, fn($item) => $item['analysis']['severity'] >= 2 && $item['analysis']['severity'] < 3)) }}</h5>
                                        <p class="mb-0">Tingkat Sedang</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-info rounded-circle">
                                            <i class="bx bx-info-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ count(array_filter($fakeLocationData, fn($item) => $item['analysis']['severity'] >= 1 && $item['analysis']['severity'] < 2)) }}</h5>
                                        <p class="mb-0">Tingkat Rendah</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-secondary rounded-circle">
                                            <i class="bx bx-low-vision font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table id="fake-location-table" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Guru</th>
                                <th>Madrasah</th>
                                <th>Kabupaten</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Koordinat</th>
                                <th>Lokasi</th>
                                <th>Tingkat Kecurigaan</th>
                                <th>Detail Masalah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fakeLocationData as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item['presensi']->user->avatar)
                                                <img src="{{ asset('storage/app/public/' . $item['presensi']->user->avatar) }}"
                                                     alt="Avatar" class="avatar-xs rounded-circle me-2">
                                            @else
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        {{ strtoupper(substr($item['presensi']->user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item['presensi']->user->name }}</h6>
                                                <small class="text-muted">{{ $item['presensi']->statusKepegawaian->name ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item['presensi']->user->madrasah->name ?? '-' }}</td>
                                    <td>{{ $item['presensi']->user->madrasah->kabupaten ?? '-' }}</td>
                                    <td>{{ $item['presensi']->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $item['presensi']->waktu_masuk ? $item['presensi']->waktu_masuk->format('H:i') : '-' }}</td>
                                    <td>
                                        <small>
                                            {{ number_format($item['presensi']->latitude, 6) }},
                                            {{ number_format($item['presensi']->longitude, 6) }}
                                        </small>
                                        @if($item['presensi']->user->madrasah && $item['presensi']->user->madrasah->latitude)
                                            <br>
                                            <small class="text-muted">
                                                Jarak: {{ number_format($item['analysis']['distance'] ?? 0, 2) }} km
                                            </small>
                                        @endif
                                        @if($item['presensi']->accuracy)
                                            <br>
                                            <small class="text-muted">
                                                Akurasi: {{ number_format($item['presensi']->accuracy, 1) }} m
                                            </small>
                                        @endif
                                        @if($item['presensi']->speed)
                                            <br>
                                            <small class="text-muted">
                                                Kecepatan: {{ number_format($item['presensi']->speed * 3.6, 1) }} km/h
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $item['presensi']->lokasi ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $severityClass = match($item['analysis']['severity_label']) {
                                                'Sangat Tinggi' => 'bg-danger',
                                                'Tinggi' => 'bg-warning',
                                                'Sedang' => 'bg-info',
                                                'Rendah' => 'bg-secondary',
                                                default => 'bg-light'
                                            };
                                        @endphp
                                        <span class="badge {{ $severityClass }} text-white">
                                            {{ $item['analysis']['severity_label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($item['analysis']['issues'] as $issue)
                                                <li><small class="text-danger">• {{ $issue }}</small></li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="viewDetails({{ $item['presensi']->id }})">
                                                <i class="bx bx-show"></i> Detail
                                            </button>
                                            @if($item['presensi']->user->madrasah && $item['presensi']->user->madrasah->map_link)
                                                <a href="{{ $item['presensi']->user->madrasah->map_link }}"
                                                   target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="bx bx-map"></i> Map
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(count($fakeLocationData) == 0)
                    <div class="text-center py-5">
                        <i class="bx bx-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Tidak Ada Presensi Mencurigakan</h4>
                        <p class="text-muted">Semua presensi pada tanggal {{ $selectedDate->format('d/m/Y') }} terdeteksi valid.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#fake-location-table').DataTable({
        "pageLength": 25,
        "order": [[ 8, "desc" ], [ 0, "asc" ]], // Sort by severity desc, then by No asc
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [10] } // Disable sorting on action column
        ]
    });
});

function viewDetails(presensiId) {
    // Load presensi details via AJAX
    $.get('{{ url("/presensi-admin/detail") }}/' + presensiId)
        .done(function(data) {
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Guru</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Nama:</strong></td><td>${data.user.name}</td></tr>
                            <tr><td><strong>Email:</strong></td><td>${data.user.email}</td></tr>
                            <tr><td><strong>Madrasah:</strong></td><td>${data.user.madrasah}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>${data.user.status_kepegawaian}</td></tr>
                            <tr><td><strong>NIP:</strong></td><td>${data.user.nip || '-'}</td></tr>
                            <tr><td><strong>NUPTK:</strong></td><td>${data.user.nuptk || '-'}</td></tr>
                        </table>

                        <h6>Data Lokasi & Perangkat</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Koordinat:</strong></td><td>${data.presensi.latitude}, ${data.presensi.longitude}</td></tr>
                            <tr><td><strong>Akurasi GPS:</strong></td><td>${data.presensi.accuracy ? data.presensi.accuracy + ' m' : '-'}</td></tr>
                            <tr><td><strong>Kecepatan:</strong></td><td>${data.presensi.speed ? (data.presensi.speed * 3.6).toFixed(1) + ' km/h' : '-'}</td></tr>
                            <tr><td><strong>Ketinggian:</strong></td><td>${data.presensi.altitude ? data.presensi.altitude + ' m' : '-'}</td></tr>
                            <tr><td><strong>Perangkat:</strong></td><td>${data.presensi.device_info || '-'}</td></tr>
                            <tr><td><strong>Status Fake Location:</strong></td><td><span class="badge bg-${data.presensi.is_fake_location ? 'danger' : 'success'}">${data.presensi.is_fake_location ? 'Terdeteksi' : 'Valid'}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Riwayat Presensi (10 terakhir)</h6>
                        <div style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
            `;

            data.presensi_history.forEach(function(presensi) {
                html += `
                    <tr>
                        <td>${presensi.tanggal}</td>
                        <td>${presensi.waktu_masuk || '-'}</td>
                        <td>${presensi.waktu_keluar || '-'}</td>
                        <td><span class="badge bg-${presensi.status === 'hadir' ? 'success' : 'warning'}">${presensi.status}</span></td>
                    </tr>
                `;
            });

            html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            $('#detailModalBody').html(html);
            $('#detailModal').modal('show');
        })
        .fail(function() {
            Swal.fire('Error', 'Gagal memuat detail presensi', 'error');
        });
}

// Helper function for distance calculation (same as in controller)
function calculateDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371;
    const latDelta = (lat2 - lat1) * Math.PI / 180;
    const lonDelta = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(latDelta/2) * Math.sin(latDelta/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(lonDelta/2) * Math.sin(lonDelta/2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return earthRadius * c;
}
</script>
@endsection
