@extends('layouts.master')

@section('title', 'Jadwal Mengajar Saya')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Jadwal Mengajar Saya</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(!empty($selectedPeriod))
                <div class="alert alert-info">
                    <strong>{{ $selectedPeriod->title }}</strong><br>
                    <small>{{ $selectedPeriod->semester_label }} | {{ $selectedPeriod->school_year }} | {{ $selectedPeriod->date_range_label }}</small>
                </div>
                @endif

                @php
                $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                @endphp

                @foreach($days as $day)
                <h5>{{ $day }}</h5>
                @if(isset($grouped[$day]))
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Sekolah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grouped[$day] as $schedule)
                        <tr>
                            <td>{{ $schedule->subject }}</td>
                            <td>{{ $schedule->class_name }}</td>
                            <td>{{ $schedule->start_time }}</td>
                            <td>{{ $schedule->end_time }}</td>
                            <td>{{ $schedule->school->nama ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p>Tidak ada jadwal.</p>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
