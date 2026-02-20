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
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Input Data</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('instumen-talenta.input-peserta') }}" class="btn btn-primary btn-sm w-100" aria-label="Input Peserta">
                                    <i class="fas fa-plus me-1"></i> Input Peserta
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('instumen-talenta.input-pemateri') }}" class="btn btn-success btn-sm w-100" aria-label="Input Pemateri">
                                    <i class="fas fa-plus me-1"></i> Input Pemateri
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('instumen-talenta.input-fasilitator') }}" class="btn btn-info btn-sm w-100" aria-label="Input Fasilitator">
                                    <i class="fas fa-plus me-1"></i> Input Fasilitator
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('instumen-talenta.input-materi') }}" class="btn btn-warning btn-sm w-100" aria-label="Input Materi">
                                    <i class="fas fa-plus me-1"></i> Input Materi
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('instumen-talenta.input-layanan-teknis') }}" class="btn btn-danger btn-sm w-100" aria-label="Input Layanan Teknis">
                                    <i class="fas fa-plus me-1"></i> Input Layanan Teknis
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Akses Cepat -->
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Akses Cepat</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('talenta.dashboard') }}" class="btn btn-primary btn-sm w-100" aria-label="Input Peserta">
                                    Dashboard Talenta
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('talenta.data') }}" class="btn btn-success btn-sm w-100" aria-label="Input Pemateri">
                                    Data Talenta
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('talenta.instrumen-penilaian') }}" class="btn btn-info btn-sm w-100" aria-label="Input Fasilitator">
                                    Instrumen Penilaian
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <div class="text-center">
                                <a href="{{ route('talenta.tugas-level-1') }}" class="btn btn-warning btn-sm w-100" aria-label="Input Materi">
                                    Tugas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- fitur  -->
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Fitur Talenta</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <a href="{{ route('instumen-talenta.kelengkapan') }}" class="btn btn-outline-primary w-100" aria-label="Kelengkapan Data Peserta">
                                <i class="fas fa-list-check me-1"></i> Kelengkapan Data Peserta
                            </a>
                        </div>

                        <div class="col-lg-12 mb-2">
                            <a href="{{ route('instumen-talenta.upload-tugas') }}" class="btn btn-outline-success w-100" aria-label="Upload Tugas Peserta">
                                <i class="fas fa-upload me-1"></i> Upload Tugas Peserta
                            </a>
                        </div>

                        <div class="col-lg-12 mb-2">
                            <a href="{{ route('instumen-talenta.instrumen-penilaian') }}" class="btn btn-outline-info w-100" aria-label="Instrumen Penilaian">
                                <i class="fas fa-clipboard-list me-1"></i> Instrumen Penilaian
                            </a>
                        </div>

                        <div class="col-lg-12 mb-2">
                            <a href="{{ route('instumen-talenta.nilai-tugas') }}" class="btn btn-outline-warning w-100" aria-label="Nilai Tugas">
                                <i class="fas fa-table me-1"></i> Nilai Tugas
                            </a>
                        </div>

                        <div class="col-lg-12">
                            <a href="{{ route('instumen-talenta.upload-sertifikat') }}" class="btn btn-outline-danger w-100" aria-label="Upload Sertifikat">
                                <i class="fas fa-certificate me-1"></i> Upload Sertifikat
                            </a>
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
