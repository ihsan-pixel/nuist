@extends('layouts.master')

@section('title') Presensi @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Presensi @endslot
@endcomponent

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-check-square me-2"></i>Data Presensi
                </h4>
            </div>
            <div class="card-body">



                @if(auth()->user()->role === 'tenaga_pendidik')
                <!-- Mobile-optimized action buttons -->
                <div class="mb-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('presensi.create') }}" class="btn btn-primary btn-lg w-100 py-3">
                                <i class="bx bx-plus me-2"></i>Presensi Hari Ini
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('izin.create') }}" class="btn btn-warning btn-lg w-100 py-3">
                                <i class="bx bx-upload me-2"></i>Upload Surat Izin
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Mobile-friendly card layout for tenaga_pendidik, table for admin roles -->
                @if(auth()->user()->role === 'tenaga_pendidik')
                <div class="row g-3">
                    @forelse($presensis as $index => $presensi)
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-{{ $presensi->status === 'hadir' ? 'success' : ($presensi->status === 'izin' ? 'warning' : ($presensi->status === 'sakit' ? 'info' : 'danger')) }} rounded-circle">
                                                <i class="bx bx-{{ $presensi->status === 'hadir' ? 'check' : ($presensi->status === 'izin' ? 'calendar-x' : ($presensi->status === 'sakit' ? 'medical' : 'x')) }} fs-5"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h6 class="mb-1">{{ $presensi->tanggal->format('d F Y') }}</h6>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <small class="badge bg-{{ $presensi->status === 'hadir' ? 'success' : ($presensi->status === 'izin' ? 'warning' : ($presensi->status === 'sakit' ? 'info' : 'danger')) }}">
                                                {{ ucfirst($presensi->status) }}
                                            </small>
                                            @if($presensi->status === 'izin' && $presensi->status_izin)
                                            <small class="badge bg-{{ $presensi->status_izin === 'approved' ? 'success' : ($presensi->status_izin === 'rejected' ? 'danger' : 'secondary') }}">
                                                {{ ucfirst($presensi->status_izin) }}
                                            </small>
                                            @endif
                                        </div>
                                        <div class="row g-2 text-muted small">
                                            <div class="col-6">
                                                <i class="bx bx-log-in me-1"></i>Masuk: {{ $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : '-' }}
                                            </div>
                                            <div class="col-6">
                                                <i class="bx bx-log-out me-1"></i>Keluar: {{ $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : '-' }}
                                            </div>
                                            @if($presensi->lokasi)
                                            <div class="col-12">
                                                <i class="bx bx-map me-1"></i>{{ $presensi->lokasi }}
                                            </div>
                                            @endif
                                            @if($presensi->keterangan)
                                            <div class="col-12">
                                                <i class="bx bx-note me-1"></i>{{ $presensi->keterangan }}
                                            </div>
                                            @endif
                                            @if($presensi->status === 'izin' && $presensi->surat_izin_path)
                                            <div class="col-12">
                                                <a href="{{ asset('storage/app/public/'.$presensi->surat_izin_path) }}" target="_blank" class="text-info">
                                                    <i class="bx bx-file me-1"></i>Lihat Surat Izin
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card border">
                            <div class="card-body text-center py-5">
                                <i class="bx bx-info-circle bx-lg text-muted mb-3"></i>
                                <h6 class="text-muted">Belum ada data presensi</h6>
                                <p class="text-muted small">Silakan lakukan presensi terlebih dahulu.</p>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
                @else
                <!-- Table layout for admin roles -->
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable-buttons">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Tenaga Pendidik</th>
                                <th>Madrasah</th>
                                <th>Status Kepegawaian</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status</th>
                                <th>Detail Izin</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensis as $index => $presensi)
                                <tr>
                                    <td>
                                        @if($presensis instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                                            {{ $presensis->firstItem() + $index }}
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>{{ $presensi->user->name }}</td>
                                    <td>{{ $presensi->user->madrasah?->name ?? '-' }}</td>
                                    <td>{{ $presensi->statusKepegawaian->name ?? '-' }}</td>
                                    <td>{{ $presensi->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : '-' }}</td>
                                    <td>{{ $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $presensi->status === 'hadir' ? 'success' : ($presensi->status === 'izin' ? 'warning' : ($presensi->status === 'sakit' ? 'info' : 'danger')) }}">
                                            {{ ucfirst($presensi->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($presensi->status === 'izin')
                                            @if($presensi->status_izin)
                                                <span class="badge bg-{{ $presensi->status_izin === 'approved' ? 'success' : ($presensi->status_izin === 'rejected' ? 'danger' : 'secondary') }}">
                                                    {{ ucfirst($presensi->status_izin) }}
                                                </span>
                                            @endif
                                            @if($presensi->surat_izin_path)
                                                <br>
                                                <a href="{{ asset('storage/app/public/'.$presensi->surat_izin_path) }}" target="_blank" class="text-info"><small>Lihat Surat</small></a>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $presensi->lokasi ?? '-' }}</td>
                                    <td>{{ $presensi->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block text-center" role="alert">
                                            <i class="bx bx-info-circle bx-lg me-2"></i>
                                            <strong>Belum ada data presensi</strong><br>
                                            <small>Silakan lakukan presensi terlebih dahulu.</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                @if($presensis instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $presensis->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $presensis->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
<script>
$(document).ready(function() {
    var isSuperAdmin = "{{ auth()->user()->role }}" === "super_admin";
    $('#datatable-buttons').DataTable({
        pageLength: isSuperAdmin ? -1 : 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        responsive: true,
        buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
    });

    // Get initial location when page loads
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            // Store first location reading in sessionStorage
            sessionStorage.setItem('first_latitude', position.coords.latitude);
            sessionStorage.setItem('first_longitude', position.coords.longitude);
            sessionStorage.setItem('first_timestamp', Date.now());
            console.log('First location stored:', position.coords.latitude, position.coords.longitude);
        }, function(error) {
            console.log('Error getting first location:', error.message);
        }, {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        });
    }
});
</script>
@endsection

