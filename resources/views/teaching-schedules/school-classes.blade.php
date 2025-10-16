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
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-0">
                        <i class="bx bx-group me-2"></i>Kelas Berjalan - {{ $school->name }}
                    </h4>
                    <p class="mb-0 text-muted">Kabupaten: {{ $school->kabupaten }} | SCOD: {{ $school->scod }}</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <form method="GET" action="{{ route('teaching-schedules.school-classes', $school->id) }}" class="d-flex align-items-center" id="date-form">
                        <input type="date" id="date-picker" name="date" class="form-control form-control-sm" value="{{ $selectedDate->format('Y-m-d') }}">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('teaching-schedules.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Kembali ke Daftar Madrasah
                    </a>
                </div>

                <div class="row">
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                        @php
                            $dayClasses = $classesByDay[$day] ?? collect();
                        @endphp

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-3">
                            <div class="card h-100 border">
                                <div class="card-header bg-success text-white py-2">
                                    <h6 class="mb-0 d-none d-md-block" style="font-size: 0.85rem;">
                                        <i class="bx bx-calendar-week me-1"></i>{{ $day }}
                                    </h6>
                                    <h6 class="mb-0 d-md-none text-center" style="font-size: 0.8rem;">
                                        {{ substr($day, 0, 3) }}
                                    </h6>
                                </div>
                                <div class="card-body p-2">
                                    @if($dayClasses->isNotEmpty())
                                        @foreach($dayClasses as $className => $schedules)
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bx bx-group me-1 text-muted" style="font-size: 0.8rem;"></i>
                                                <strong class="text-success" style="font-size: 0.8rem;">{{ $className }}</strong>
                                            </div>
                                            @foreach($schedules as $schedule)
                                            <div class="mb-2 p-1 border rounded" style="background-color: #f8f9fa; border-color: #e9ecef;">
                                                <div class="d-flex flex-column">
                                                    <div class="d-flex align-items-center flex-wrap mb-1">
                                                        @if($schedule->teacher)
                                                            <span class="badge bg-success me-1" style="font-size: 0.65rem; padding: 0.15rem 0.3rem;">Terisi</span>
                                                        @else
                                                            <span class="badge bg-warning me-1" style="font-size: 0.65rem; padding: 0.15rem 0.3rem;">Kosong</span>
                                                        @endif
                                                        <span class="badge bg-primary me-1" style="font-size: 0.65rem; padding: 0.15rem 0.3rem;">{{ $schedule->subject }}</span>
                                                        @if($schedule->has_attendance_today)
                                                            <span class="badge bg-info me-1" style="font-size: 0.65rem; padding: 0.15rem 0.3rem;">
                                                                <i class="bx bx-check me-1"></i>Hadir
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary me-1" style="font-size: 0.65rem; padding: 0.15rem 0.3rem;">
                                                                <i class="bx bx-time me-1"></i>Belum
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted" style="font-size: 0.7rem;">
                                                            <i class="bx bx-user me-1"></i>{{ $schedule->teacher ? $schedule->teacher->name : 'Belum ada guru' }}
                                                        </small>
                                                        <small class="text-muted text-end" style="font-size: 0.7rem;">
                                                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-3">
                                            <i class="bx bx-calendar-x text-muted" style="font-size: 1.2rem;"></i>
                                            <p class="text-muted mb-0 mt-1" style="font-size: 0.7rem;">Tidak ada kelas</p>
                                        </div>
                                    @endif
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
    // Handle date change
    $('#date-picker').on('change', function(e) {
        e.preventDefault();
        $('#date-form').submit();
        return false;
    });
});
</script>
@endsection
