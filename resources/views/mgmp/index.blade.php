{{-- resources/views/mgmp/index.blade.php --}}
@extends('layouts.master-without-nav')

@section('title') MGMP - Musyawarah Guru Mata Pelajaran @endsection

@section('content')

<div class="row">
    <div class="col-12">
        <!-- MGMP Header -->
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border-radius: 15px;">
            <div class="card-body p-5 text-center">
                <div class="avatar-xl mx-auto mb-4">
                    <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                        <i class="mdi mdi-school fs-1"></i>
                    </div>
                </div>
                <h2 class="text-white mb-3">MGMP NUIST</h2>
                <p class="text-white-50 mb-4 fs-5">
                    Musyawarah Guru Mata Pelajaran<br>
                    Sistem Informasi Digital LP. Ma'arif NU PWNU DIY
                </p>
                @if(Auth::check() && Auth::user()->role === 'mgmp')
                    <a href="{{ route('mgmp.dashboard') }}" class="btn btn-light btn-lg">
                        <i class="mdi mdi-view-dashboard me-2"></i>
                        Masuk Dashboard MGMP
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg">
                        <i class="mdi mdi-login me-2"></i>
                        Login MGMP
                    </a>
                @endif
            </div>
        </div>

        <!-- MGMP Information -->
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                <i class="mdi mdi-account-group fs-2"></i>
                            </div>
                        </div>
                        <h5 class="card-title mb-3">Anggota MGMP</h5>
                        <p class="text-muted mb-3">
                            Komunitas guru yang berkolaborasi untuk meningkatkan kualitas pembelajaran
                        </p>
                        <div class="text-primary fw-bold fs-4">{{ $totalAnggota ?? 0 }}</div>
                        <small class="text-muted">Total Anggota</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                <i class="mdi mdi-calendar-check fs-2"></i>
                            </div>
                        </div>
                        <h5 class="card-title mb-3">Kegiatan Rutin</h5>
                        <p class="text-muted mb-3">
                            Workshop, seminar, dan kegiatan pengembangan profesi secara berkala
                        </p>
                        <div class="text-success fw-bold fs-4">{{ $totalKegiatan ?? 0 }}</div>
                        <small class="text-muted">Kegiatan</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                <i class="mdi mdi-book-open fs-2"></i>
                            </div>
                        </div>
                        <h5 class="card-title mb-3">Materi Pembelajaran</h5>
                        <p class="text-muted mb-3">
                            Berbagi materi, metode, dan inovasi pembelajaran antar guru
                        </p>
                        <div class="text-info fw-bold fs-4">{{ $totalMateri ?? '0' }}</div>
                        <small class="text-muted">Materi Tersedia</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- MGMP Objectives -->
        <div class="card border-0 shadow-sm mt-4" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="card-title mb-1 text-dark">
                            <i class="mdi mdi-target text-primary me-2"></i>
                            Tujuan MGMP
                        </h4>
                        <p class="text-muted mb-0">Meningkatkan kompetensi dan profesionalisme guru</p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                    <i class="mdi mdi-school fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-2">Peningkatan Kompetensi</h6>
                                <p class="text-muted mb-0 small">
                                    Mengembangkan kompetensi pedagogik, profesional, kepribadian, dan sosial guru
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                    <i class="mdi mdi-share-variant fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-2">Berbagi Pengalaman</h6>
                                <p class="text-muted mb-0 small">
                                    Berbagi pengalaman, metode, dan inovasi pembelajaran antar sesama guru
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-info bg-opacity-10 text-info rounded-circle">
                                    <i class="mdi mdi-lightbulb fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-2">Inovasi Pembelajaran</h6>
                                <p class="text-muted mb-0 small">
                                    Mengembangkan inovasi dan kreativitas dalam proses pembelajaran
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex align-items-start">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title bg-warning bg-opacity-10 text-warning rounded-circle">
                                    <i class="mdi mdi-account-network fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-2">Jaringan Profesional</h6>
                                <p class="text-muted mb-0 small">
                                    Membangun jaringan kerja sama dan kolaborasi antar guru dan sekolah
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
