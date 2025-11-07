@extends('layouts.master')

@section('title', 'Progres Mengajar')

@section('content')
@foreach($kabupatenOrder as $kabupaten)
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 text-white">Kabupaten: {{ $kabupaten }}</h4>
            </div>
            <div class="card-body">
                <table id="datatable-{{ Str::slug($kabupaten) }}" class="table table-bordered dt-responsive nowrap w-100">
                    <thead class="table-light">
                        <tr>
                            <th>SCOD</th>
                            <th>Nama Sekolah</th>
                            <th>Sudah Input Jadwal (%)</th>
                            <th>Sudah Presensi Mengajar (%)</th>
                            <th>Guru Belum Presensi (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($madrasahs[$kabupaten] ?? [] as $index => $madrasah)
                        <tr>
                            <td>{{ $madrasah->scod }}</td>
                            <td>{{ $madrasah->name }}</td>
                            <td style="text-align: center; font-weight: bold;">
                                <span class="badge bg-{{ $madrasah->schedule_input_percentage >= 80 ? 'success' : ($madrasah->schedule_input_percentage >= 50 ? 'warning' : 'danger') }}"
                                      data-bs-toggle="tooltip"
                                      data-bs-placement="top"
                                      title="Total Guru: {{ $madrasah->total_teachers ?? 0 }}, Sudah Input Jadwal: {{ $madrasah->teachers_with_schedule ?? 0 }}, Belum Input Jadwal: {{ $madrasah->teachers_without_schedule ?? 0 }}">
                                    {{ $madrasah->schedule_input_percentage }}%
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: bold;">
                                <span class="badge bg-{{ $madrasah->attendance_percentage >= 80 ? 'success' : ($madrasah->attendance_percentage >= 50 ? 'warning' : 'danger') }}"
                                      data-bs-toggle="tooltip"
                                      data-bs-placement="top"
                                      title="Total Guru: {{ $madrasah->total_teachers ?? 0 }}, Sudah Presensi: {{ $madrasah->teachers_with_attendance ?? 0 }}, Belum Presensi: {{ $madrasah->teachers_without_attendance ?? 0 }}">
                                    {{ $madrasah->attendance_percentage }}%
                                </span>
                            </td>
                            <td style="text-align: center; font-weight: bold;">
                                <span class="badge bg-{{ $madrasah->attendance_pending_percentage <= 20 ? 'success' : ($madrasah->attendance_pending_percentage <= 50 ? 'warning' : 'danger') }}"
                                      data-bs-toggle="tooltip"
                                      data-bs-placement="top"
                                      title="Total Guru: {{ $madrasah->total_teachers ?? 0 }}, Sudah Presensi: {{ $madrasah->teachers_with_attendance ?? 0 }}, Belum Presensi: {{ $madrasah->teachers_without_attendance ?? 0 }}">
                                    {{ $madrasah->attendance_pending_percentage }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    $(document).ready(function() {
        @foreach($kabupatenOrder as $kabupaten)
        $('#datatable-{{ Str::slug($kabupaten) }}').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'pdf', 'print', 'colvis'
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        @endforeach

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush

@endsection
