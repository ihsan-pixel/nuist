@extends('layouts.master')

@section('title', 'Jadwal Mengajar Saya')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">Jadwal Mengajar Saya</h4>
                    <a href="{{ route('teaching-schedules.create') }}" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus"></i> Tambah Jadwal
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
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
                            <th style="width: 170px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grouped[$day] as $schedule)
                        <tr>
                            <td>{{ $schedule->subject }}</td>
                            <td>{{ $schedule->class_name }}</td>
                            <td>{{ $schedule->start_time }}</td>
                            <td>{{ $schedule->end_time }}</td>
                            <td>{{ $schedule->school->name ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('teaching-schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <form action="{{ route('teaching-schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
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
