@extends('layouts.master')

@section('title', 'Pengaturan Presensi')

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi Admin @endslot
    @slot('title') Pengaturan Presensi @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card shadow border-0">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-cog me-2"></i>Pengaturan Presensi
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

                <div class="alert alert-info">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Informasi:</strong> Sistem presensi sekarang menggunakan jadwal waktu tetap berdasarkan Hari KBM madrasah. Pengaturan waktu tidak lagi dapat diubah secara manual.
                </div>

                <div class="row gx-0">
                    @foreach($hariKbmOptions as $key => $label)
                        <div class="col-md-6 mb-3 px-1">
                            <div class="card border-primary shadow-sm h-100">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="card-title mb-0">
                                        <i class="bx bx-calendar me-2"></i>{{ $label }}
                                    </h6>
                                </div>
                                <div class="card-body py-3">
                                    @php
                                        $timeRanges = ($key == '5') ? $timeRanges5 : $timeRanges6;
                                    @endphp

                                    <div class="row">
                                        <!-- Presensi Masuk Settings -->
                                        <div class="col-6">
                                            <h6 class="text-primary mb-2">
                                                <i class="bx bx-log-in-circle me-1"></i>Masuk
                                            </h6>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label small">
                                                        Mulai
                                                    </label>
                                                    <input type="time" class="form-control form-control-sm" value="{{ $timeRanges['masuk_start'] }}" readonly>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">
                                                        Akhir
                                                    </label>
                                                    <input type="time" class="form-control form-control-sm" value="{{ $timeRanges['masuk_end'] }}" readonly>
                                                </div>
                                            </div>
                                            <small class="text-muted small">Terlambat setelah akhir</small>
                                        </div>

                                        <!-- Presensi Pulang Settings -->
                                        <div class="col-6">
                                            <h6 class="text-success mb-2">
                                                <i class="bx bx-log-out-circle me-1"></i>Pulang
                                            </h6>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="form-label small">
                                                        Mulai
                                                    </label>
                                                    <input type="time" class="form-control form-control-sm" value="{{ $timeRanges['pulang_start'] }}" readonly>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">
                                                        Akhir
                                                    </label>
                                                    <input type="time" class="form-control form-control-sm" value="{{ $timeRanges['pulang_end'] }}" readonly>
                                                </div>
                                            </div>
                                            <small class="text-muted small">Tidak boleh setelah akhir</small>
                                        </div>
                                    </div>

                                    @if($key == '5')
                                    <div class="mt-3">
                                        <small class="text-warning">
                                            <i class="bx bx-info-circle me-1"></i>
                                            <strong>Catatan:</strong> Untuk hari Jumat, waktu mulai presensi pulang adalah 15:00.
                                        </small>
                                    </div>
                                    @elseif($key == '6')
                                    <div class="mt-3">
                                        <small class="text-info">
                                            <i class="bx bx-info-circle me-1"></i>
                                            <strong>Catatan:</strong> Untuk hari Sabtu, waktu mulai presensi pulang adalah 15:00. Hari lainnya mulai pukul 15:00.
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if ($loop->iteration % 2 == 0 && !$loop->last)
                            </div><div class="row gx-0">
                        @endif
                    @endforeach
                </div>

                <div class="alert alert-warning mt-3">
                    <i class="bx bx-error-circle me-2"></i>
                    <strong>Pengecualian:</strong> Pengguna dengan "Pemenuhan Beban Kerja Lain" = Ya dapat melakukan presensi masuk dan keluar tanpa batasan waktu dan dapat presensi di madrasah tambahan.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

