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
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('teaching-schedules.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Kembali ke Daftar Madrasah
                    </a>
                    @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin')
                    <a href="{{ route('teaching-schedules.school-classes', $school->id) }}" class="btn btn-info">
                        <i class="bx bx-group me-1"></i> Lihat Kelas
                    </a>
                    @endif
                </div>

                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp

                <div class="row">
                    @foreach($days as $day)
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
                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="bx bx-calendar-week me-2"></i>{{ $day }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach($daySchedules as $teacherName => $schedules)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bx bx-user me-2 text-muted"></i>
                                            <strong class="text-primary">{{ $teacherName }}</strong>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tbody>
                                                    @foreach($schedules as $schedule)
                                                    <tr>
                                                        <td class="ps-0">
                                                            <span class="badge bg-primary me-2">{{ $schedule->subject }}</span>
                                                            <span class="badge bg-info">{{ $schedule->class_name }}</span>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <small class="text-muted">
                                                                {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

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
