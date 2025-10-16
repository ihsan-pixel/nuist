@extends('layouts.master')

@section('title') Kelas Berjalan - {{ $school->name }} @endsection

@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('li_2') <a href="{{ route('teaching-schedules.index') }}">Jadwal Mengajar</a> @endslot
    @slot('title') Kelas Berjalan {{ $school->name }} @endslot
@endcomponent

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />

<style>
/* === Layout Fix === */
.card-body {
    overflow-y: auto;
    max-height: 480px; /* agar jadwal panjang bisa di-scroll */
}

@media (max-width: 768px) {
    .card-body {
        max-height: unset; /* bebas tinggi di HP */
    }
}

/* Jarak antar card lebih longgar */
.row-cols-1 > * {
    margin-bottom: 1.5rem;
}

/* Tampilan jadwal dalam card */
.badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.schedule-item {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #e9ecef;
}

.schedule-item:hover {
    background-color: #eef6f9;
    transition: background-color 0.2s;
}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="card-title mb-0">
                        <i class="bx bx-group me-2"></i>Kelas Berjalan - {{ $school->name }}
                    </h4>
                    <p class="mb-0 text-muted">
                        Kabupaten: {{ $school->kabupaten }} | SCOD: {{ $school->scod }}
                    </p>
                </div>
                <div>
                    <form method="GET" action="{{ route('teaching-schedules.school-classes', $school->id) }}" id="date-form">
                        <input type="date" id="date-picker" name="date"
                            class="form-control form-control-sm"
                            value="{{ $selectedDate->format('Y-m-d') }}">
                    </form>
                </div>
            </div>

            <div class="card-body" style="overflow-x: hidden;">
                <div class="mb-3">
                    <a href="{{ route('teaching-schedules.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i> Kembali ke Daftar Madrasah
                    </a>
                </div>

                <!-- === GRID RESPONSIF === -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                        @php
                            $dayClasses = $classesByDay[$day] ?? collect();
                        @endphp

                        <div class="col">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white py-2 rounded-top">
                                    <h6 class="mb-0" style="font-size: 0.9rem;">
                                        <i class="bx bx-calendar-week me-2"></i>{{ $day }}
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    @if($dayClasses->isNotEmpty())
                                        @foreach($dayClasses as $className => $schedules)
                                            <div class="mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="bx bx-group me-2 text-muted" style="font-size: 0.9rem;"></i>
                                                    <strong class="text-success" style="font-size: 0.9rem;">{{ $className }}</strong>
                                                </div>

                                                @foreach($schedules as $schedule)
                                                <div class="schedule-item mb-2 d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1 me-2">
                                                        <div class="d-flex align-items-center flex-wrap mb-1">
                                                            @if($schedule->teacher)
                                                                <span class="badge bg-success me-1">Terisi</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark me-1">Kosong</span>
                                                            @endif
                                                            <span class="badge bg-primary me-1">{{ $schedule->subject }}</span>
                                                            @if($schedule->has_attendance_today)
                                                                <span class="badge bg-info me-1">
                                                                    <i class="bx bx-check me-1"></i>Hadir
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary me-1">
                                                                    <i class="bx bx-time me-1"></i>Belum Presensi
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                                <i class="bx bx-user me-1"></i>
                                                                {{ $schedule->teacher ? $schedule->teacher->name : 'Belum ada guru' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <small class="text-muted" style="font-size: 0.75rem;">
                                                            {{ $schedule->start_time }}<br>{{ $schedule->end_time }}
                                                        </small>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bx bx-calendar-x text-muted" style="font-size: 1.5rem;"></i>
                                            <p class="text-muted mb-0 mt-2" style="font-size: 0.8rem;">Tidak ada kelas</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($classesByDay->isEmpty())
                <div class="text-center py-5">
                    <div class="avatar-md mx-auto mb-3">
                        <div class="avatar-title bg-light rounded-circle">
                            <i class="bx bx-group font-size-24 text-muted"></i>
                        </div>
                    </div>
                    <h5 class="text-muted">Belum ada kelas berjalan</h5>
                    <p class="text-muted">Belum ada kelas berjalan untuk madrasah ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
$(document).ready(function() {
    // Ganti tanggal otomatis submit
    $('#date-picker').on('change', function(e) {
        e.preventDefault();
        $('#date-form').submit();
        return false;
    });
});
</script>
@endsection
