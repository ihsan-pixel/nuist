@extends('layouts.master')

@section('title') Jadwal Mengajar - {{ $school->name }} @endsection

@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('li_2') <a href="{{ route('teaching-schedules.index') }}">Jadwal Mengajar</a> @endslot
    @slot('title') Jadwal {{ $school->name }} @endslot
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
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-calendar me-2"></i>Jadwal Mengajar - {{ $school->name }}
                </h4>
                <p class="mb-0 text-muted">Kabupaten: {{ $school->kabupaten }} | SCOD: {{ $school->scod }}</p>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('teaching-schedules.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Kembali ke Daftar Madrasah
                    </a>
                </div>

                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp

                @forelse($days as $day)
                    @php
                        $daySchedules = collect();
                        foreach ($grouped as $teacherName => $schedules) {
                            $teacherDaySchedules = $schedules->where('day', $day);
                            if ($teacherDaySchedules->isNotEmpty()) {
                                $daySchedules[$teacherName] = $teacherDaySchedules;
                            }
                        }
                    @endphp

                    @if($daySchedules->isNotEmpty())
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="bx bx-calendar-week me-2"></i>{{ $day }}
                        </h5>

                        @foreach($daySchedules as $teacherName => $schedules)
                        <div class="card border mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="bx bx-user me-2"></i>{{ $teacherName }}
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0">Mata Pelajaran</th>
                                                <th class="border-0">Kelas</th>
                                                <th class="border-0">Jam Mulai</th>
                                                <th class="border-0">Jam Selesai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($schedules as $schedule)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">{{ $schedule->subject }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $schedule->class_name }}</span>
                                                </td>
                                                <td>{{ $schedule->start_time }}</td>
                                                <td>{{ $schedule->end_time }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                @endforelse

                @if($grouped->flatten()->isEmpty())
                <div class="text-center py-5">
                    <div class="avatar-md mx-auto mb-3">
                        <div class="avatar-title bg-light rounded-circle">
                            <i class="bx bx-calendar font-size-24 text-muted"></i>
                        </div>
                    </div>
                    <h5 class="text-muted">Belum ada jadwal mengajar</h5>
                    <p class="text-muted">Belum ada jadwal mengajar untuk madrasah ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
