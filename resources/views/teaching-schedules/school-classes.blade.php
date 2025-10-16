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

                @forelse($classesByDay as $day => $classes)
                <h5>{{ $day }}</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classes as $className => $schedules)
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $className }}</td>
                            <td>
                                @if($schedule->teacher)
                                <span class="badge bg-success">Terisi</span>
                                @else
                                <span class="badge bg-warning">Kosong</span>
                                @endif
                            </td>
                            <td>{{ $schedule->teacher ? $schedule->teacher->name : '-' }}</td>
                            <td>{{ $schedule->subject }}</td>
                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
                @empty
                <div class="alert alert-info">
                    <i class="bx bx-info-circle"></i> Belum ada kelas berjalan untuk madrasah ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
