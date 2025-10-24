@extends('layouts.mobile')

@section('title', 'Laporan Mengajar')
@section('subtitle', 'Riwayat Presensi Mengajar')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body d-flex align-items-center">
        <div class="avatar-lg me-3">
            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                <i class="bx bx-history fs-2"></i>
            </div>
        </div>
        <div>
            <h6 class="mb-0">Riwayat Presensi Mengajar</h6>
            <small class="text-muted">Riwayat presensi mengajar untuk bulan ini</small>
        </div>
    </div>
</div>

@if($history->isEmpty())
    <div class="card empty-state shadow-sm border-0">
        <div class="card-body text-center py-5">
            <div class="avatar-xl mx-auto mb-4">
                <div class="avatar-title bg-light rounded-circle">
                    <i class="bx bx-list-ul fs-1 text-muted"></i>
                </div>
            </div>
            <h5 class="text-muted mb-2">Belum ada presensi mengajar</h5>
            <p class="text-muted mb-0">Belum ada catatan presensi mengajar pada periode ini.</p>
        </div>
    </div>
@else
    <div class="list-group">
        @foreach($history as $item)
            <div class="list-group-item d-flex justify-content-between align-items-start">
                <div class="me-3">
                    <div class="fw-semibold">{{ optional($item->teachingSchedule)->subject ?? 'Mata Pelajaran' }}</div>
                    <div class="text-muted small">{{ optional($item->teachingSchedule->school)->name ?? '-' }}</div>
                    <div class="text-muted small">Kelas: {{ optional($item->teachingSchedule)->class_name ?? '-' }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                    <div class="text-muted small">Waktu: {{ $item->waktu }}</div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
