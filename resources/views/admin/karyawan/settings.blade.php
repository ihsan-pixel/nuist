@extends('layouts.master')
@section('title', 'Pengaturan Presensi Karyawan')
@section('content')
<div class="container-fluid py-3 mgmp-page">
    <div class="mgmp-hero-strip mb-4"><div class="mgmp-kicker mb-2">Admin · Karyawan Yayasan</div><h4 class="mb-1 text-white">Pengaturan Presensi Karyawan</h4><p class="mb-0 text-white-50">Atur Wi-Fi kantor dan jam presensi tanpa mengubah konfigurasi hosting.</p></div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="card"><div class="card-body">
        <form method="POST" action="{{ route('admin.karyawan.settings.update') }}">@csrf @method('PUT')
            <div class="form-check form-switch mb-4"><input class="form-check-input" type="checkbox" name="employee_attendance_enabled" value="1" id="employee_attendance_enabled" {{ old('employee_attendance_enabled', $settings->employee_attendance_enabled) ? 'checked' : '' }}><label class="form-check-label fw-semibold" for="employee_attendance_enabled">Aktifkan presensi karyawan</label></div>
            <div class="mb-4"><label class="form-label fw-semibold">IP Wi-Fi kantor yang diizinkan</label><textarea name="employee_wifi_allowed_ips" class="form-control" rows="4" required placeholder="Satu IP per baris atau pisahkan dengan koma">{{ old('employee_wifi_allowed_ips', str_replace(',', "\n", (string) $settings->employee_wifi_allowed_ips)) }}</textarea><small class="text-muted">Masukkan IP publik jaringan kantor. Bukan SSID atau IP lokal 192.168.x.x.</small></div>
            <div class="row g-3"><div class="col-12 col-md-6"><label class="form-label fw-semibold">Jam masuk mulai</label><input type="time" name="employee_entry_start" class="form-control" value="{{ old('employee_entry_start', $settings->employee_entry_start) }}" required></div><div class="col-12 col-md-6"><label class="form-label fw-semibold">Jam masuk berakhir</label><input type="time" name="employee_entry_end" class="form-control" value="{{ old('employee_entry_end', $settings->employee_entry_end) }}" required></div><div class="col-12 col-md-6"><label class="form-label fw-semibold">Jam pulang mulai</label><input type="time" name="employee_exit_start" class="form-control" value="{{ old('employee_exit_start', $settings->employee_exit_start) }}" required></div><div class="col-12 col-md-6"><label class="form-label fw-semibold">Jam pulang berakhir</label><input type="time" name="employee_exit_end" class="form-control" value="{{ old('employee_exit_end', $settings->employee_exit_end) }}" required></div></div>
            <button class="btn btn-primary mt-4"><i class="bx bx-save me-1"></i>Simpan Pengaturan</button>
        </form>
    </div></div>
</div>
@endsection
