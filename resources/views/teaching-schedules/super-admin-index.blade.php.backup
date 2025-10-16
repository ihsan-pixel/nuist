@extends('layouts.master')

@section('title') Jadwal Mengajar - Super Admin @endsection

@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Jadwal Mengajar @endslot
@endcomponent

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-calendar me-2"></i>Daftar Madrasah
                </h4>
            </div>
            <div class="card-body">
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
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Kabupaten</th>
                                <th>SCOD</th>
                                <th>Nama Madrasah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schools as $school)
                            <tr>
                                <td>{{ $school->kabupaten }}</td>
                                <td>{{ $school->scod }}</td>
                                <td>{{ $school->name }}</td>
                                <td>
                                    <a href="{{ route('teaching-schedules.school-schedules', $school->id) }}" class="btn btn-sm btn-primary">
                                        <i class="bx bx-calendar"></i> Lihat Jadwal
                                    </a>
                                    <a href="{{ route('teaching-schedules.school-classes', $school->id) }}" class="btn btn-sm btn-info">
                                        <i class="bx bx-group"></i> Lihat Kelas Berjalan
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data madrasah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
