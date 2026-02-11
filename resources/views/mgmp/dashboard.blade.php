{{-- resources/views/mgmp/dashboard.blade.php --}}
@extends('layouts.master')

@section('title') Dashboard MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') MGMP @endslot
    @slot('title') Dashboard MGMP @endslot
@endcomponent

<div class="row">
    <div class="col-lg-4 col-12">
        <!-- Welcome Card - Mobile Optimized -->
        <div class="card border-0 shadow-sm hover-lift overflow-hidden mb-3" style="border-radius: 15px;">
            <div style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="text-white p-3">
                            <h5 class="text-white">Selamat Datang!</h5>
                            <p class="mb-0 text-white-50">MGMP NUIST</p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        {{-- <img src="{{ asset('build/images/logo 1.png') }}" alt="" class="img-fluid" style="max-height: 60px;"> --}}
                    </div>
                </div>
            </div>
            {{-- <div class="card-body pt-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg profile-user-wid mb-3 mb-md-0">
                            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="font-size-16 mb-1">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                        <p class="text-muted mb-2">Nuist ID : {{ Auth::user()->nuist_id ?? '-' }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <small class="badge bg-primary-subtle text-primary">{{ Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-' }}</small>
                            <small class="badge bg-info-subtle text-info">{{ Auth::user()->ketugasan ?? '-' }}</small>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        {{-- MGMP Statistics Card --}}
        {{-- <div class="card border-0 shadow-sm hover-lift mb-3" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">
                            <i class="mdi mdi-account-group text-primary me-2"></i>
                            Statistik MGMP
                        </h5>
                        <p class="text-muted mb-0 small">Informasi anggota dan kegiatan</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="mdi mdi-chart-bar fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="mdi mdi-account-check fs-5"></i>
                                </div>
                            </div>
                            <h6 class="mb-1">{{ $totalAnggota ?? 0 }}</h6>
                            <small class="text-muted">Total Anggota</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                    <i class="mdi mdi-calendar-check fs-5"></i>
                                </div>
                            </div>
                            <h6 class="mb-1">{{ $totalKegiatan ?? 0 }}</h6>
                            <small class="text-muted">Kegiatan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    {{-- MGMP Main Content - Right side --}}
    <div class="col-xl-8 col-12">
        <!-- MGMP Overview Header -->
        {{-- <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-white mb-1">Dashboard MGMP</h4>
                        <p class="text-white-50 mb-0">Kelola Musyawarah Guru Mata Pelajaran</p>
                    </div>
                    <div class="avatar-lg">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-school fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Quick Actions Row -->
        {{-- <div class="row g-3 mb-4">
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                <i class="mdi mdi-account-group fs-3"></i>
                            </div>
                        </div>
                        <h5 class="text-white mb-2">Data Anggota</h5>
                        <p class="text-white-75 mb-3 small">Kelola anggota MGMP</p>
                        <a href="{{ route('mgmp.data-anggota') }}" class="btn btn-light btn-sm">
                            <i class="mdi mdi-eye me-1"></i>
                            Lihat Data
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                <i class="mdi mdi-file-document fs-3"></i>
                            </div>
                        </div>
                        <h5 class="text-white mb-2">Laporan Kegiatan</h5>
                        <p class="text-white-75 mb-3 small">Buat dan kelola laporan</p>
                        <a href="{{ route('mgmp.laporan') }}" class="btn btn-light btn-sm">
                            <i class="mdi mdi-plus me-1"></i>
                            Buat Laporan
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                <i class="mdi mdi-information fs-3"></i>
                            </div>
                        </div>
                        <h5 class="text-white mb-2">Data MGMP</h5>
                        <p class="text-white-75 mb-3 small">Informasi MGMP</p>
                        <a href="{{ route('mgmp.index') }}" class="btn btn-light btn-sm">
                            <i class="mdi mdi-eye me-1"></i>
                            Lihat Info
                        </a>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- Recent Activities --}}
        {{-- <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">
                            <i class="mdi mdi-timeline-text text-primary me-2"></i>
                            Aktivitas Terbaru
                        </h5>
                        <p class="text-muted mb-0 small">Kegiatan MGMP terkini</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="mdi mdi-clock fs-5"></i>
                        </div>
                    </div>
                </div>

                @if(isset($recentActivities) && $recentActivities->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentActivities as $activity)
                        <div class="list-group-item px-0 py-3 border-0">
                            <div class="d-flex align-items-start">
                                <div class="avatar-sm me-3">
                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                        <i class="mdi mdi-calendar-check fs-5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $activity->title }}</h6>
                                    <p class="text-muted mb-1 small">{{ $activity->description }}</p>
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle">
                                <i class="mdi mdi-information-outline fs-1"></i>
                            </div>
                        </div>
                        <h6 class="text-muted mb-2">Belum ada aktivitas</h6>
                        <p class="text-muted small">Aktivitas MGMP akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div> --}}
    </div>
</div>

@endsection

@section('script')
<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
@endsection
