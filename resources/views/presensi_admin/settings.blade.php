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

                <form action="{{ route('presensi_admin.updateSettings') }}" method="POST">
                    @csrf

                    <!-- Presensi Masuk Settings -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">
                            <i class="bx bx-log-in-circle me-2"></i>Pengaturan Presensi Masuk
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                    <div class="mb-3">
                        <label for="waktu_mulai_presensi_masuk" class="form-label">
                            <i class="bx bx-time-five me-1"></i>Waktu Mulai Presensi Masuk
                        </label>
@php
    use Carbon\Carbon;
@endphp
<input type="time" class="form-control" id="waktu_mulai_presensi_masuk" name="waktu_mulai_presensi_masuk" value="{{ old('waktu_mulai_presensi_masuk', isset($settings->waktu_mulai_presensi_masuk) ? Carbon::parse($settings->waktu_mulai_presensi_masuk)->format('H:i') : '06:30') }}">
                        @error('waktu_mulai_presensi_masuk')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="waktu_akhir_presensi_masuk" class="form-label">
                                        <i class="bx bx-time-five me-1"></i>Waktu Akhir Presensi Masuk
                                    </label>
<input type="time" class="form-control" id="waktu_akhir_presensi_masuk" name="waktu_akhir_presensi_masuk" value="{{ old('waktu_akhir_presensi_masuk', isset($settings->waktu_akhir_presensi_masuk) ? Carbon::parse($settings->waktu_akhir_presensi_masuk)->format('H:i') : '07:00') }}">
                        @error('waktu_akhir_presensi_masuk')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                                    <small class="text-muted">Setelah waktu ini, presensi masih diperbolehkan namun terhitung terlambat</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Presensi Pulang Settings -->
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="bx bx-log-out-circle me-2"></i>Pengaturan Presensi Pulang
                        </h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="waktu_mulai_presensi_pulang" class="form-label">
                                        <i class="bx bx-time-five me-1"></i>Waktu Mulai Presensi Pulang
                                    </label>
<input type="time" class="form-control" id="waktu_mulai_presensi_pulang" name="waktu_mulai_presensi_pulang" value="{{ old('waktu_mulai_presensi_pulang', isset($settings->waktu_mulai_presensi_pulang) ? Carbon::parse($settings->waktu_mulai_presensi_pulang)->format('H:i') : '13:00') }}">
                                    @error('waktu_mulai_presensi_pulang')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="waktu_akhir_presensi_pulang" class="form-label">
                                        <i class="bx bx-time-five me-1"></i>Waktu Akhir Presensi Pulang
                                    </label>
<input type="time" class="form-control" id="waktu_akhir_presensi_pulang" name="waktu_akhir_presensi_pulang" value="{{ old('waktu_akhir_presensi_pulang', isset($settings->waktu_akhir_presensi_pulang) ? Carbon::parse($settings->waktu_akhir_presensi_pulang)->format('H:i') : '15:00') }}">
                                    @error('waktu_akhir_presensi_pulang')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Setelah waktu ini, presensi pulang tidak diperbolehkan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

