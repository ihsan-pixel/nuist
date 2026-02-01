@extends('layouts.mobile')

@section('title', 'Data Presensi Mengajar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Data Presensi Mengajar</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Guru</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachingAttendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $attendance->teachingSchedule->teacher->name ?? '-' }}</td>
                                    <td>{{ $attendance->teachingSchedule->subject ?? '-' }}</td>
                                    <td>{{ $attendance->teachingSchedule->start_time ?? '-' }} - {{ $attendance->teachingSchedule->end_time ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-success">Hadir</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data presensi mengajar.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $teachingAttendances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
