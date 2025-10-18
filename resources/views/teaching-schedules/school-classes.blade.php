@extends('layouts.master')

@section('title')
    Kelas Berjalan - {{ $school->name }} ({{ $selectedDay }})
@endsection

@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('li_2') <a href="{{ route('teaching-schedules.index') }}">Jadwal Mengajar</a> @endslot
    @slot('title') Kelas Berjalan {{ $school->name }} ({{ $selectedDay }}) @endslot
@endcomponent

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />

{{-- ✅ Fix CSS agar layout tidak terpotong dan responsif --}}
<style>
    /* Pastikan konten bisa di-scroll dan tidak terpotong */
    .main-content,
    .page-content,
    body,
    html {
        overflow-y: auto !important;
        overflow-x: hidden !important;
        min-height: 100vh;
    }

    /* Pastikan grid membungkus (wrap) semua kolom hari */
    .row {
        flex-wrap: wrap !important;
    }

    /* Jarak antar kolom agar tidak dempet */
    .row > [class*='col-'] {
        margin-bottom: 1rem;
    }

    /* Sidebar handling: beri margin ke konten */
    .content-page {
        margin-left: 260px;
        transition: all 0.3s ease;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 992px) {
        .content-page {
            margin-left: 0;
        }
    }

    /* Card header styling */
    .card-header.bg-success {
        background-color: #007b5e !important;
    }

    /* Scroll halus */
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="bx bx-group me-2"></i>Kelas Berjalan - {{ $school->name }}
                            </h4>
                            <p class="mb-0 text-muted">
                                Kabupaten: {{ $school->kabupaten }} | SCOD: {{ $school->scod }}
                            </p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form method="GET" action="{{ route('teaching-schedules.school-classes', $school->id) }}" class="d-flex align-items-center" id="date-form">
                                <input
                                    type="date"
                                    id="date-picker"
                                    name="date"
                                    class="form-control form-control-sm"
                                    value="{{ $selectedDate->format('Y-m-d') }}">
                            </form>
                        </div>
                    </div>

                    <!-- Day Selection Buttons -->
                    <div class="d-flex flex-wrap gap-2 mb-0">
                        @php
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $currentDate = $selectedDate->copy();
                            $dayDates = [];

                            // Calculate dates for each day going backwards from current date
                            foreach ($days as $day) {
                                $dayIndex = array_search($day, ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
                                $currentDayIndex = $currentDate->dayOfWeek;
                                $diff = $dayIndex - $currentDayIndex;

                                if ($diff > 0) {
                                    $diff -= 7; // Go to previous week
                                }

                                $dayDates[$day] = $currentDate->copy()->addDays($diff);
                            }
                        @endphp
                        @foreach($days as $day)
                            @php
                                $dayDate = $dayDates[$day];
                                $isSelected = $selectedDay === $day;
                            @endphp
                            <button type="button"
                                    class="btn btn-sm {{ $isSelected ? 'btn-primary' : 'btn-outline-primary' }}"
                                    onclick="selectDate('{{ $dayDate->format('Y-m-d') }}')">
                                {{ $day }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('teaching-schedules.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali ke Daftar Madrasah
                        </a>
                    </div>

                    <div class="row">
                        @if($classesByDay->has($selectedDay))
                            @php
                                $dayClasses = $classesByDay[$selectedDay];
                            @endphp

                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-success text-white py-3">
                                        <h5 class="mb-0">
                                            <i class="bx bx-calendar-week me-2"></i>{{ $selectedDay }}
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        @if($dayClasses->isNotEmpty())
                                            @foreach($dayClasses as $className => $schedules)
                                            <div class="mb-4">
                                                <div class="d-flex align-items-center mb-3">
                                                    <i class="bx bx-group me-2 text-muted" style="font-size: 1.1rem;"></i>
                                                    <strong class="text-success h5 mb-0">{{ $className }}</strong>
                                                </div>
                                                <div class="row">
                                                    @foreach($schedules as $schedule)
                                                    <div class="col-lg-6 col-xl-4 col-md-6 col-sm-12 mb-3">
                                                        <div class="d-flex justify-content-between align-items-start p-3 border rounded bg-light h-100">
                                                            <div class="flex-grow-1 me-3">
                                                                <div class="d-flex align-items-center flex-wrap mb-2">
                                                                    @if($schedule->teacher)
                                                                        <span class="badge bg-success me-1">Terisi</span>
                                                                    @else
                                                                        <span class="badge bg-warning me-1">Kosong</span>
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
                                                                    <small class="text-muted">
                                                                        <i class="bx bx-user me-1"></i>{{ $schedule->teacher ? $schedule->teacher->name : 'Belum ada guru' }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <small class="text-muted">
                                                                    {{ $schedule->start_time }}<br>{{ $schedule->end_time }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-5">
                                                <i class="bx bx-calendar-x text-muted" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0 mt-3" style="font-size: 1rem;">Tidak ada kelas pada hari {{ $selectedDay }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-success text-white py-3">
                                        <h5 class="mb-0">
                                            <i class="bx bx-calendar-week me-2"></i>{{ $selectedDay }}
                                        </h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="text-center py-5">
                                            <i class="bx bx-calendar-x text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0 mt-3" style="font-size: 1rem;">Tidak ada kelas pada hari {{ $selectedDay }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($classesByDay->isEmpty())
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light rounded-circle">
                                <i class="bx bx-group font-size-24 text-muted"></i>
                            </div>
                        </div>
                        <h5 class="text-muted">Belum ada kelas berjalan</h5>
                        <p class="text-muted">Belum ada kelas berjalan untuk madrasah ini pada hari {{ $selectedDay }}.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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

// Function to select specific date
function selectDate(dateStr) {
    $('#date-picker').val(dateStr);
    $('#date-form').submit();
}
</script>
@endsection
