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
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-group me-2"></i>Kelas Berjalan - {{ $school->name }}
                </h4>
                <p class="mb-0 text-muted">Kabupaten: {{ $school->kabupaten }} | SCOD: {{ $school->scod }}</p>
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

                        <div class="col-lg-6 col-xl-4 mb-4">
                            <div class="card h-100 border">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="bx bx-calendar-week me-2"></i>{{ $day }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if($dayClasses->isNotEmpty())
                                        @foreach($dayClasses as $className => $schedules)
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="bx bx-group me-2 text-muted"></i>
                                                <strong class="text-success">{{ $className }}</strong>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tbody>
                                                        @foreach($schedules as $schedule)
                                                        <tr>
                                                            <td class="ps-0">
                                                                <div class="d-flex align-items-center">
                                                                    @if($schedule->teacher)
                                                                        <span class="badge bg-success me-2">Terisi</span>
                                                                    @else
                                                                        <span class="badge bg-warning me-2">Kosong</span>
                                                                    @endif
                                                                    <span class="badge bg-primary">{{ $schedule->subject }}</span>
                                                                </div>
                                                                <div class="mt-1">
                                                                    <small class="text-muted">
                                                                        <i class="bx bx-user me-1"></i>{{ $schedule->teacher ? $schedule->teacher->name : 'Belum ada guru' }}
                                                                    </small>
                                                                </div>
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
                                    @else
                                        <div class="text-center py-3">
                                            <i class="bx bx-calendar-x text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted mb-0 mt-2">Tidak ada kelas</p>
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
@endsection
