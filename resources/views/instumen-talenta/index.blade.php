@extends('layouts.master')

@section('title', 'Instrument Talenta - Dashboard')

@section('content')
<div class="container-fluid">

    <div class="row mb-3">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard Instrument Talenta</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Instrument Talenta</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="card-title">Selamat Datang di Instrument Talenta</h4>
                            <p class="text-muted">Platform pengembangan talenta profesional untuk meningkatkan kompetensi dan keterampilan tenaga pendidik di lingkungan LP. Ma'arif NU DIY</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('images/tpt logo.png') }}" alt="Logo Instrument Talenta" class="img-fluid" style="max-height:80px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-3">
        <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary rounded">
                                <i class="fas fa-users font-size-20"></i>
                            </span>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-1">Total Peserta</p>
                            <h4 class="mb-0">{{ number_format($totalPeserta) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success rounded">
                                <i class="fas fa-chalkboard-teacher font-size-20"></i>
                            </span>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-1">Pemateri</p>
                            <h4 class="mb-0">{{ number_format($totalPemateri) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info rounded">
                                <i class="fas fa-book-open font-size-20"></i>
                            </span>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-1">Materi</p>
                            <h4 class="mb-0">{{ number_format($totalMateri) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-md-6 col-lg-3 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning rounded">
                                <i class="fas fa-star font-size-20"></i>
                            </span>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-1">Fasilitator</p>
                            <h4 class="mb-0">{{ number_format($totalFasilitator) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Input Data -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Input Data</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-primary rounded-circle">
                                        <i class="fas fa-user-plus font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Input Peserta</h5>
                                {{-- <p class="text-muted mb-3 font-size-12">Tambah data peserta baru ke dalam sistem</p> --}}
                                <a href="{{ route('instumen-talenta.input-peserta') }}" class="btn btn-primary btn-sm w-100" aria-label="Input Peserta">
                                    <i class="fas fa-plus me-1"></i> Input Peserta
                                </a>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-success rounded-circle">
                                        <i class="fas fa-chalkboard-teacher font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Input Pemateri</h5>
                                {{-- <p class="text-muted mb-3 font-size-12">Tambah data pemateri/instruktur baru</p> --}}
                                <a href="{{ route('instumen-talenta.input-pemateri') }}" class="btn btn-success btn-sm w-100" aria-label="Input Pemateri">
                                    <i class="fas fa-plus me-1"></i> Input Pemateri
                                </a>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-info rounded-circle">
                                        <i class="fas fa-user-cog font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Input Fasilitator</h5>
                                {{-- <p class="text-muted mb-3 font-size-12">Tambah data fasilitator/trainer baru</p> --}}
                                <a href="{{ route('instumen-talenta.input-fasilitator') }}" class="btn btn-info btn-sm w-100" aria-label="Input Fasilitator">
                                    <i class="fas fa-plus me-1"></i> Input Fasilitator
                                </a>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-warning rounded-circle">
                                        <i class="fas fa-book-open font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Input Materi</h5>
                                {{-- <p class="text-muted mb-3 font-size-12">Tambah data materi pembelajaran baru</p> --}}
                                <a href="{{ route('instumen-talenta.input-materi') }}" class="btn btn-warning btn-sm w-100" aria-label="Input Materi">
                                    <i class="fas fa-plus me-1"></i> Input Materi
                                </a>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-info rounded-circle">
                                        <i class="fas fa-tools font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Input Layanan Teknis</h5>
                                {{-- <p class="text-muted mb-3 font-size-12">Tambah data layanan teknis baru</p> --}}
                                <a href="{{ route('instumen-talenta.input-layanan-teknis') }}" class="btn btn-info btn-sm w-100" aria-label="Input Layanan Teknis">
                                    <i class="fas fa-plus me-1"></i> Input Layanan Teknis
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access + Features -->
    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Akses Cepat</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-primary rounded-circle">
                                        <i class="fas fa-users font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Dashboard Peserta</h5>
                                <p class="text-muted mb-3 font-size-12">Akses materi dan tracking progress</p>
                                <a href="{{ route('instumen-talenta.peserta') }}" class="btn btn-primary btn-sm w-100" aria-label="Dashboard Peserta">
                                    <i class="fas fa-sign-in-alt me-1"></i> Masuk
                                </a>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-success rounded-circle">
                                        <i class="fas fa-chalkboard-teacher font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Dashboard Pemateri</h5>
                                <p class="text-muted mb-3 font-size-12">Kelola konten dan materi kursus</p>
                                <a href="{{ route('instumen-talenta.pemateri') }}" class="btn btn-success btn-sm w-100" aria-label="Dashboard Pemateri">
                                    <i class="fas fa-sign-in-alt me-1"></i> Masuk
                                </a>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-info rounded-circle">
                                        <i class="fas fa-user-cog font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Dashboard Fasilitator</h5>
                                <p class="text-muted mb-3 font-size-12">Pantau dan fasilitasi pembelajaran</p>
                                <a href="{{ route('instumen-talenta.fasilitator') }}" class="btn btn-info btn-sm w-100" aria-label="Dashboard Fasilitator">
                                    <i class="fas fa-sign-in-alt me-1"></i> Masuk
                                </a>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="text-center">
                                <div class="avatar-lg mx-auto mb-3">
                                    <span class="avatar-title bg-danger rounded-circle">
                                        <i class="fas fa-cog font-size-24"></i>
                                    </span>
                                </div>
                                <h5 class="font-size-15 mb-2">Dashboard Admin</h5>
                                <p class="text-muted mb-3 font-size-12">Kelola sistem dan pengaturan</p>
                                <a href="{{ route('instumen-talenta.admin') }}" class="btn btn-danger btn-sm w-100" aria-label="Dashboard Admin">
                                    <i class="fas fa-sign-in-alt me-1"></i> Masuk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Fitur Platform</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-primary rounded-circle">
                                <i class="fas fa-book-open font-size-12"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Materi Pembelajaran</h6>
                            <p class="text-muted mb-0 small">Akses materi interaktif</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-success rounded-circle">
                                <i class="fas fa-chart-line font-size-12"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Tracking Progress</h6>
                            <p class="text-muted mb-0 small">Monitor perkembangan</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-info rounded-circle">
                                <i class="fas fa-certificate font-size-12"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Sertifikasi</h6>
                            <p class="text-muted mb-0 small">Sertifikat kompetensi</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="avatar-xs me-3">
                            <span class="avatar-title bg-warning rounded-circle">
                                <i class="fas fa-users font-size-12"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-0">Kolaborasi</h6>
                            <p class="text-muted mb-0 small">Forum diskusi & grup</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/instumen-talenta.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection
