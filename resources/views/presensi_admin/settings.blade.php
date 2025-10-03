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

                    <div class="row gx-0">
                    @foreach($statuses as $status)
                        <div class="col-md-6 mb-3 px-1">
                            <div class="card border-primary shadow-sm h-100">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="card-title mb-0">
                                        <i class="bx bx-user me-2"></i>{{ $status->name }}
                                    </h6>
                                </div>
                                <div class="card-body py-3">
                                    @php
                                        $setting = $settings->get($status->id);
                                        $prefix = "status_{$status->id}_";
                                    @endphp

                                    <form action="{{ route('presensi_admin.updateSettings') }}" method="POST" class="needs-validation" novalidate>
                                        @csrf
                                        <input type="hidden" name="status_id" value="{{ $status->id }}">

                                        <div class="row">
                                            <!-- Presensi Masuk Settings -->
                                            <div class="col-6">
                                                <h6 class="text-white bg-primary p-1 rounded small mb-2">
                                                    <i class="bx bx-log-in-circle me-1"></i>Masuk
                                                </h6>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label for="{{ $prefix }}waktu_mulai_presensi_masuk" class="form-label small text-white">
                                                            Mulai
                                                        </label>
                                                        <input type="time" class="form-control form-control-sm" id="{{ $prefix }}waktu_mulai_presensi_masuk" name="{{ $prefix }}waktu_mulai_presensi_masuk" value="{{ old($prefix . 'waktu_mulai_presensi_masuk', isset($setting->waktu_mulai_presensi_masuk) ? \Carbon\Carbon::parse($setting->waktu_mulai_presensi_masuk)->format('H:i') : '06:30') }}" required>
                                                        @error($prefix . 'waktu_mulai_presensi_masuk')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="{{ $prefix }}waktu_akhir_presensi_masuk" class="form-label small text-white">
                                                            Akhir
                                                        </label>
                                                        <input type="time" class="form-control form-control-sm" id="{{ $prefix }}waktu_akhir_presensi_masuk" name="{{ $prefix }}waktu_akhir_presensi_masuk" value="{{ old($prefix . 'waktu_akhir_presensi_masuk', isset($setting->waktu_akhir_presensi_masuk) ? \Carbon\Carbon::parse($setting->waktu_akhir_presensi_masuk)->format('H:i') : '07:00') }}" required>
                                                        @error($prefix . 'waktu_akhir_presensi_masuk')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <small class="text-white small">Terlambat setelah akhir</small>
                                            </div>

                                            <!-- Presensi Pulang Settings -->
                                            <div class="col-6">
                                                <h6 class="text-white bg-success p-1 rounded small mb-2">
                                                    <i class="bx bx-log-out-circle me-1"></i>Pulang
                                                </h6>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <label for="{{ $prefix }}waktu_mulai_presensi_pulang" class="form-label small text-white">
                                                            Mulai
                                                        </label>
                                                        <input type="time" class="form-control form-control-sm" id="{{ $prefix }}waktu_mulai_presensi_pulang" name="{{ $prefix }}waktu_mulai_presensi_pulang" value="{{ old($prefix . 'waktu_mulai_presensi_pulang', isset($setting->waktu_mulai_presensi_pulang) ? \Carbon\Carbon::parse($setting->waktu_mulai_presensi_pulang)->format('H:i') : '13:00') }}" required>
                                                        @error($prefix . 'waktu_mulai_presensi_pulang')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="{{ $prefix }}waktu_akhir_presensi_pulang" class="form-label small text-white">
                                                            Akhir
                                                        </label>
                                                        <input type="time" class="form-control form-control-sm" id="{{ $prefix }}waktu_akhir_presensi_pulang" name="{{ $prefix }}waktu_akhir_presensi_pulang" value="{{ old($prefix . 'waktu_akhir_presensi_pulang', isset($setting->waktu_akhir_presensi_pulang) ? \Carbon\Carbon::parse($setting->waktu_akhir_presensi_pulang)->format('H:i') : '15:00') }}" required>
                                                        @error($prefix . 'waktu_akhir_presensi_pulang')
                                                            <div class="text-danger small">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <small class="text-white small">Tidak boleh setelah akhir</small>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center mt-3">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bx bx-save me-1"></i>Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if ($loop->iteration % 2 == 0 && !$loop->last)
                            </div><div class="row gx-0">
                        @endif
                    @endforeach
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

