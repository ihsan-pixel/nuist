@extends('layouts.master')

@section('title') Jadwal Mengajar - Pengurus @endsection

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

                <!-- Search and Filter Section -->
                <form method="GET" action="{{ route('teaching-schedules.index') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama madrasah..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="kabupaten" class="form-select">
                                <option value="">Semua Kabupaten</option>
                                @foreach($kabupatens as $kabupaten)
                                <option value="{{ $kabupaten }}" {{ request('kabupaten') == $kabupaten ? 'selected' : '' }}>{{ $kabupaten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Cari</button>
                        </div>
                    </div>
                </form>

                @if($schoolsByKabupaten->isEmpty())
                <div class="text-center p-4">
                    <div class="alert alert-info d-inline-block" role="alert">
                        <i class="bx bx-info-circle bx-lg me-2"></i>
                        <strong>Belum ada data madrasah</strong><br>
                        <small>Silakan tambahkan data madrasah terlebih dahulu melalui menu Master Data.</small>
                    </div>
                </div>
                @else
                <div class="row">
                    @forelse($schoolsByKabupaten as $kabupaten => $schools)
                    @foreach($schools as $school)
                    <div class="col-xxl-3 col-md-6 mb-4">
                        <div class="card project-card" style="border: none; box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,.03); border-radius: 0.75rem; overflow: hidden;">
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 120px;">
                                <i class="bx bx-building-house bx-lg text-muted"></i>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold mb-2">{{ $school->name }}</h5>
                                <p class="card-text text-muted small mb-3">{{ $school->kabupaten }}</p>
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="text-center">
                                            <p class="text-muted mb-1 small">SCOD</p>
                                            <h6 class="mb-0">{{ $school->scod }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center">
                                            <p class="text-muted mb-1 small">Kabupaten</p>
                                            <h6 class="mb-0">{{ $school->kabupaten }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Status Jadwal:</small>
                                        @if($school->has_schedules)
                                            <span class="badge bg-success">Sudah Input</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Input</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Status Presensi:</small>
                                        @if($school->has_attendances)
                                            <span class="badge bg-success">Sudah Presensi</span>
                                        @else
                                            <span class="badge bg-danger">Belum Presensi</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('teaching-schedules.school-schedules', $school->id) }}" class="btn btn-warning btn-sm rounded-pill px-3">
                                        <i class="bx bx-calendar me-1"></i> Lihat Jadwal
                                    </a>
                                    <a href="{{ route('teaching-schedules.school-classes', $school->id) }}" class="btn btn-info btn-sm rounded-pill px-3">
                                        <i class="bx bx-group me-1"></i> Lihat Kelas
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @empty
                    @endforelse
                </div>
                @endif
            </div>
        </div>
    </div>
</div>


@endsection
