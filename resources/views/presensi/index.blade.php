@extends('layouts.master')

@section('title') Presensi @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Presensi @endslot
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
                <h4 class="card-title mb-0">
                    <i class="bx bx-check-square me-2"></i>Data Presensi
                </h4>
            </div>
            <div class="card-body">

                @if(auth()->user()->role === 'tenaga_pendidik')
                <div class="mb-3 d-flex justify-content-end gap-2">
                    <a href="{{ route('presensi.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Presensi Hari Ini
                    </a>
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

                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap w-100" id="datatable-buttons">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                <th>Nama Tenaga Pendidik</th>
                                <th>Madrasah</th>
                                @endif
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
                                    @if(in_array(auth()->user()->role, ['super_admin', 'admin']))
                                    <td>{{ $presensi->user->name }}</td>
                                    <td>{{ $presensi->user->madrasah?->name ?? '-' }}</td>
                                    @endif
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
                                    <td colspan="{{ auth()->user()->role === 'tenaga_pendidik' ? '7' : '9' }}" class="text-center p-4">
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
