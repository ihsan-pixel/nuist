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
        <div class="card">
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

                    @foreach($statuses as $status)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-user me-2"></i>Pengaturan Presensi untuk Status: {{ $status->name }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @php
                                $setting = $settings->get($status->id);
                                $prefix = "status_{$status->id}_";
                            @endphp

                            <form action="{{ route('presensi_admin.updateSettings') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                <input type="hidden" name="status_id" value="{{ $status->id }}">

                                <!-- Presensi Masuk Settings -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="bx bx-log-in-circle me-2"></i>Pengaturan Presensi Masuk
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="{{ $prefix }}waktu_mulai_presensi_masuk" class="form-label">
                                                    <i class="bx bx-time-five me-1"></i>Waktu Mulai Presensi Masuk
                                                </label>
                                                <input type="time" class="form-control" id="{{ $prefix }}waktu_mulai_presensi_masuk" name="{{ $prefix }}waktu_mulai_presensi_masuk" value="{{ old($prefix . 'waktu_mulai_presensi_masuk', isset($setting->waktu_mulai_presensi_masuk) ? \Carbon\Carbon::parse($setting->waktu_mulai_presensi_masuk)->format('H:i') : '06:30') }}" required>
                                                @error($prefix . 'waktu_mulai_presensi_masuk')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="{{ $prefix }}waktu_akhir_presensi_masuk" class="form-label">
                                                    <i class="bx bx-time-five me-1"></i>Waktu Akhir Presensi Masuk
                                                </label>
                                                <input type="time" class="form-control" id="{{ $prefix }}waktu_akhir_presensi_masuk" name="{{ $prefix }}waktu_akhir_presensi_masuk" value="{{ old($prefix . 'waktu_akhir_presensi_masuk', isset($setting->waktu_akhir_presensi_masuk) ? \Carbon\Carbon::parse($setting->waktu_akhir_presensi_masuk)->format('H:i') : '07:00') }}" required>
                                                @error($prefix . 'waktu_akhir_presensi_masuk')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Setelah waktu ini, presensi masih diperbolehkan namun terhitung terlambat</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Presensi Pulang Settings -->
                                <div class="mb-4">
                                    <h6 class="text-success mb-3">
                                        <i class="bx bx-log-out-circle me-2"></i>Pengaturan Presensi Pulang
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="{{ $prefix }}waktu_mulai_presensi_pulang" class="form-label">
                                                    <i class="bx bx-time-five me-1"></i>Waktu Mulai Presensi Pulang
                                                </label>
                                                <input type="time" class="form-control" id="{{ $prefix }}waktu_mulai_presensi_pulang" name="{{ $prefix }}waktu_mulai_presensi_pulang" value="{{ old($prefix . 'waktu_mulai_presensi_pulang', isset($setting->waktu_mulai_presensi_pulang) ? \Carbon\Carbon::parse($setting->waktu_mulai_presensi_pulang)->format('H:i') : '13:00') }}" required>
                                                @error($prefix . 'waktu_mulai_presensi_pulang')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="{{ $prefix }}waktu_akhir_presensi_pulang" class="form-label">
                                                    <i class="bx bx-time-five me-1"></i>Waktu Akhir Presensi Pulang
                                                </label>
                                                <input type="time" class="form-control" id="{{ $prefix }}waktu_akhir_presensi_pulang" name="{{ $prefix }}waktu_akhir_presensi_pulang" value="{{ old($prefix . 'waktu_akhir_presensi_pulang', isset($setting->waktu_akhir_presensi_pulang) ? \Carbon\Carbon::parse($setting->waktu_akhir_presensi_pulang)->format('H:i') : '15:00') }}" required>
                                                @error($prefix . 'waktu_akhir_presensi_pulang')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Setelah waktu ini, presensi pulang tidak diperbolehkan</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i>Simpan Pengaturan {{ $status->name }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

