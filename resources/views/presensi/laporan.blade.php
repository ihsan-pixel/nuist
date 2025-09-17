@extends('layouts.master')

@section('title') Laporan Presensi @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi @endslot
    @slot('title') Laporan Presensi @endslot
@endcomponent

@section('css')
<link href="{{ URL::asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/css/app.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Laporan Presensi</h4>
            </div>
            <div class="card-body">

                <!-- Filter Form -->
                <form method="GET" action="{{ route('presensi.laporan') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                        </div>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                        <div class="col-md-3">
                            <label class="form-label">Madrasah</label>
                            <select name="madrasah_id" class="form-control">
                                <option value="">Semua Madrasah</option>
                                @foreach($madrasahs as $madrasah)
                                    <option value="{{ $madrasah->id }}" {{ request('madrasah_id') == $madrasah->id ? 'selected' : '' }}>
                                        {{ $madrasah->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('presensi.laporan') }}" class="btn btn-secondary">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

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

                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable-buttons">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Tenaga Pendidik</th>
                                <th>Madrasah</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presensis as $index => $presensi)
                                <tr>
                                    <td>{{ $presensis->firstItem() + $index }}</td>
                                    <td>{{ $presensi->user->name }}</td>
                                    <td>{{ $presensi->user->madrasah?->name ?? '-' }}</td>
                                    <td>{{ $presensi->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : '-' }}</td>
                                    <td>{{ $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : '-' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $presensi->status === 'hadir' ? 'success' : ($presensi->status === 'izin' ? 'warning' : ($presensi->status === 'sakit' ? 'info' : 'danger')) }}">
                                            {{ ucfirst($presensi->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $presensi->lokasi ?? '-' }}</td>
                                    <td>{{ $presensi->keterangan ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center p-4">
                                        <div class="alert alert-info d-inline-block text-center" role="alert">
                                            <i class="bx bx-info-circle bx-lg me-2"></i>
                                            <strong>Tidak ada data presensi</strong><br>
                                            <small>Silakan ubah filter untuk melihat data presensi lainnya.</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($presensis->hasPages())
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
<script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
@endsection
