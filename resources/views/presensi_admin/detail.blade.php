@extends('layouts.master')

@section('title', 'Detail Madrasah - ' . $madrasah->name)

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />

<!-- Responsive Table css -->
<link href="{{ asset('build/libs/admin-resources/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi Admin @endslot
    @slot('title') Detail Madrasah @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-school me-2"></i>{{ $madrasah->name }}
                    </h4>
                    <div>
                        <a href="{{ route('presensi_admin.index', ['date' => $selectedDate->format('Y-m-d')]) }}" class="btn btn-light btn-sm">
                            <i class="mdi mdi-arrow-left me-1"></i>Kembali
                        </a>
                        <a href="{{ route('presensi_admin.export_madrasah', $madrasah->id) }}" class="btn btn-success btn-sm">
                            <i class="mdi mdi-download me-1"></i>Export Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Date Selector -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('presensi_admin.show_detail', $madrasah->id) }}" class="d-flex align-items-center">
                            <label for="date" class="form-label me-2 mb-0">Tanggal:</label>
                            <input type="date" name="date" id="date" class="form-control form-control-sm" value="{{ $selectedDate->format('Y-m-d') }}" onchange="this.form.submit()" style="max-width: 200px;">
                        </form>
                    </div>
                </div>

                <!-- Madrasah Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h5 class="card-title mb-0">
                                    <i class="mdi mdi-information-outline me-2"></i>Informasi Madrasah
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="40%"><strong>Nama Madrasah:</strong></td>
                                                <td>{{ $madrasah->name }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>SCOD:</strong></td>
                                                <td>{{ $madrasah->scod ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kabupaten:</strong></td>
                                                <td>{{ $madrasah->kabupaten ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Hari KBM:</strong></td>
                                                <td>{{ $madrasah->hari_kbm ?? '-' }} Hari</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="40%"><strong>Alamat:</strong></td>
                                                <td>{{ $madrasah->alamat ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Latitude:</strong></td>
                                                <td>{{ $madrasah->latitude ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Longitude:</strong></td>
                                                <td>{{ $madrasah->longitude ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Map Link:</strong></td>
                                                <td>
                                                    @if($madrasah->map_link)
                                                        <a href="{{ $madrasah->map_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="mdi mdi-map-marker"></i> Lihat Map
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Staff Attendance Data -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="mdi mdi-account-group me-2"></i>Data Presensi Tenaga Pendidik
                                    <span class="badge bg-primary ms-2">{{ count($tenagaPendidikData) }} Orang</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                @if(count($tenagaPendidikData) > 0)
                                    <div class="table-rep-plugin">
                                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                                            <table id="staff-attendance-table" class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th data-priority="1">Nama</th>
                                                        <th data-priority="2">NIP</th>
                                                        <th data-priority="3">NUPTK</th>
                                                        <th data-priority="4">Status Kepegawaian</th>
                                                        <th data-priority="1">Status Presensi</th>
                                                        <th data-priority="5">Waktu Masuk</th>
                                                        <th data-priority="6">Waktu Keluar</th>
                                                        <th data-priority="7">Lokasi</th>
                                                        <th data-priority="8">Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($tenagaPendidikData as $index => $tp)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $tp['nama'] }}</td>
                                                        <td>{{ $tp['nip'] ?? '-' }}</td>
                                                        <td>{{ $tp['nuptk'] ?? '-' }}</td>
                                                        <td>{{ $tp['status_kepegawaian'] }}</td>
                                                        <td>
                                                            @if($tp['status'] == 'hadir')
                                                                <span class="badge bg-success">Hadir</span>
                                                            @elseif($tp['status'] == 'izin')
                                                                <span class="badge bg-warning">Izin</span>
                                                            @elseif($tp['status'] == 'sakit')
                                                                <span class="badge bg-info">Sakit</span>
                                                            @elseif($tp['status'] == 'terlambat')
                                                                <span class="badge bg-warning text-dark">Terlambat</span>
                                                            @else
                                                                <span class="badge bg-danger">Tidak Hadir</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $tp['waktu_masuk'] ?? '-' }}</td>
                                                        <td>{{ $tp['waktu_keluar'] ?? '-' }}</td>
                                                        <td>
                                                            @if($tp['lokasi'])
                                                                <small class="text-muted">{{ Str::limit($tp['lokasi'], 30) }}</small>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ $tp['keterangan'] ?? '-' }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="mdi mdi-account-off-outline" style="font-size: 3rem; color: #ccc;"></i>
                                        <p class="mt-2 text-muted">Tidak ada data tenaga pendidik untuk madrasah ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/js/app.js') }}"></script>

<!-- Responsive Table js -->
<script src="{{ asset('build/libs/admin-resources/rwd-table/rwd-table.min.js') }}"></script>

<!-- Init js -->
<script src="{{ asset('build/js/pages/table-responsive.init.js') }}"></script>
@endsection
