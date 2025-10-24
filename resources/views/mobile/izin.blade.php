@extends('layouts.mobile')

@section('title', 'Izin')
@section('subtitle', 'Pengajuan Izin')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        .izin-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .izin-action { background: #fff; border-radius: 10px; padding: 12px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.04); text-decoration: none; color: #333; }
        .izin-action i { font-size: 20px; color: #0e8549; display:block; margin-bottom:6px; }
        .izin-action .label { font-weight: 600; font-size: 13px; }
    </style>

    <h6 class="mb-3">Pengajuan Izin</h6>

    <div class="izin-grid">
        <a href="{{ route('mobile.izin', ['type' => 'tidak_masuk']) }}" class="izin-action">
            <i class="bx bx-user-x"></i>
            <div class="label">Izin Tidak Masuk</div>
        </a>

        <a href="{{ route('mobile.izin', ['type' => 'sakit']) }}" class="izin-action">
            <i class="bx bx-medical"></i>
            <div class="label">Izin Sakit</div>
        </a>

        <a href="{{ route('mobile.izin', ['type' => 'terlambat']) }}" class="izin-action">
            <i class="bx bx-time-five"></i>
            <div class="label">Izin Terlambat</div>
        </a>

        <a href="{{ route('mobile.izin', ['type' => 'tugas_luar']) }}" class="izin-action">
            <i class="bx bx-briefcase"></i>
            <div class="label">Izin Tugas Diluar</div>
        </a>
    </div>
</div>
@endsection
