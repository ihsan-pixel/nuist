@extends('layouts.master')

@section('title', 'Progres Mengajar')

@section('css')
<style>
.welcome-section {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    border-radius: 15px !important;
    padding: 2rem !important;
    margin-bottom: 2rem !important;
    color: white !important;
    position: relative !important;
    overflow: hidden !important;
    box-shadow: 0 4px 15px rgba(0, 75, 76, 0.2) !important;
}

.welcome-section::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    right: 0 !important;
    width: 200px !important;
    height: 200px !important;
    background: rgba(255, 255, 255, 0.1) !important;
    border-radius: 50% !important;
    transform: translate(50px, -50px) !important;
}

.welcome-content {
    position: relative !important;
    z-index: 1 !important;
}

.stat-card {
    background: white !important;
    border-radius: 15px !important;
    padding: 1.5rem !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    border: none !important;
    transition: all 0.3s ease !important;
    position: relative !important;
    overflow: hidden !important;
}

.stat-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
}

.stat-card.total-madrasah {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
}

.stat-card.schedule-input {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    color: white !important;
}

.stat-card.attendance {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
    color: white !important;
}

.stat-card.pending {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%) !important;
    color: white !important;
}

.stat-number {
    font-size: 2.5rem !important;
    font-weight: bold !important;
    margin-bottom: 0.5rem !important;
    position: relative !important;
    z-index: 1 !important;
}

.stat-label {
    font-size: 0.9rem !important;
    opacity: 0.9 !important;
    margin-bottom: 0 !important;
    position: relative !important;
    z-index: 1 !important;
}

.kabupaten-group {
    background: white !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 15px !important;
    margin-bottom: 1.5rem !important;
    overflow: hidden !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.kabupaten-header {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    color: white !important;
    padding: 1rem 1.5rem !important;
    font-weight: 600 !important;
    font-size: 1.1rem !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}

.kabupaten-header i {
    margin-right: 0.5rem !important;
    opacity: 0.9 !important;
}

.kabupaten-table {
    background: white !important;
}

.kabupaten-table .table {
    margin-bottom: 0 !important;
    border-radius: 0 !important;
}

.kabupaten-table .table thead th {
    /* background: linear-gradient(135deg, #0e8549 0%, #0e8549 100%) !important; */
    color: black !important;
    border: none !important;
    font-weight: 600 !important;
    padding: 1rem !important;
    border-bottom: 2px solid #dee2e6 !important;
}

.kabupaten-table .table tbody tr {
    transition: background-color 0.3s ease !important;
    border-bottom: 1px solid #f1f3f4 !important;
}

.kabupaten-table .table tbody tr:hover {
    background-color: rgba(0, 75, 76, 0.05) !important;
}

.school-name {
    font-weight: 600 !important;
    color: #004b4c !important;
    margin-bottom: 0.25rem !important;
}

.action-btn {
    background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%) !important;
    border: 1px solid #004b4c !important;
    border-radius: 8px !important;
    padding: 0.5rem 1rem !important;
    color: white !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    position: relative !important;
    overflow: hidden !important;
    font-size: 0.9rem !important;
}

.action-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 15px rgba(0, 75, 76, 0.3) !important;
    color: white !important;
    background: linear-gradient(135deg, #0e8549 0%, #004b4c 100%) !important;
}

.badge {
    font-weight: 600 !important;
}

.text-dark {
    color: #004b4c !important;
}

.text-muted {
    color: #6c757d !important;
}

.fw-semibold {
    font-weight: 600 !important;
}

.fw-medium {
    font-weight: 500 !important;
}
</style>
@endsection

@section('content')
@if(in_array(Auth::user()->role, ['super_admin']))
<div class="welcome-section">
    <div class="welcome-content container">
        <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-2">
                        <i class="mdi mdi-view-dashboard me-2"></i>
                        Data Presensi Mengajar Tenaga Pendidik
                    </h2>
                    <p class="mb-0 opacity-75">Pantau dan kelola presensi mengajar tenaga pendidik di seluruh madrasah Ma'arif</p>
                </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card total-madrasah">
            <div class="stat-number">{{ $madrasahs->flatten()->count() }}</div>
            <div class="stat-label">Total Madrasah</div>
        </div>
    </div>

    @php
    $overallScheduleInput = $madrasahs->flatten()->avg('schedule_input_percentage') ?? 0;
    $overallAttendance = $madrasahs->flatten()->avg('attendance_percentage') ?? 0;
    $overallPending = $madrasahs->flatten()->avg('attendance_pending_percentage') ?? 0;
    @endphp

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card schedule-input">
            <div class="stat-number">{{ round($overallScheduleInput) }}%</div>
            <div class="stat-label">Sudah Input Jadwal</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card attendance">
            <div class="stat-number">{{ round($overallAttendance) }}%</div>
            <div class="stat-label">Sudah Presensi Mengajar</div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card pending">
            <div class="stat-number">{{ round($overallPending) }}%</div>
            <div class="stat-label">Guru Belum Presensi</div>
        </div>
    </div>
</div>
@endif

@foreach($kabupatenOrder as $kabupaten)
<div class="kabupaten-group">
    <div class="kabupaten-header">
        <i class="mdi mdi-map-marker-radius"></i> Kabupaten: {{ $kabupaten }}
    </div>
    <div class="kabupaten-table p-3">
        <table id="datatable-{{ \Illuminate\Support\Str::slug($kabupaten) }}" class="table table-bordered dt-responsive nowrap w-100">
<thead>
    <tr>
        <th>SCOD</th>
        <th>Nama Sekolah</th>
        <th>Sudah Input Jadwal (%)</th>
        <th>Rincian Jadwal</th>
        <th>Sudah Presensi Mengajar (%)</th>
        <th>Rincian Presensi</th>
        <th>Guru Belum Presensi (%)</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @foreach($madrasahs[$kabupaten] ?? [] as $index => $madrasah)
    <tr>
        <td>{{ $madrasah->scod }}</td>
        <td class="school-name">{{ $madrasah->name }}</td>
        <td style="text-align: center; font-weight: bold;">
            <span class="badge bg-{{ $madrasah->schedule_input_percentage >= 80 ? 'success' : ($madrasah->schedule_input_percentage >= 50 ? 'warning' : 'danger') }}">
                {{ $madrasah->schedule_input_percentage }}%
            </span>
        </td>
        <td style="text-align: center; font-size: 12px;">
            <div>Total: {{ $madrasah->total_teachers ?? 0 }}</div>
            <div>Sudah: {{ $madrasah->teachers_with_schedule ?? 0 }}</div>
            <div>Belum: {{ $madrasah->teachers_without_schedule ?? 0 }}</div>
        </td>
        <td style="text-align: center; font-weight: bold;">
            <span class="badge bg-{{ $madrasah->attendance_percentage >= 80 ? 'success' : ($madrasah->attendance_percentage >= 50 ? 'warning' : 'danger') }}">
                {{ $madrasah->attendance_percentage }}%
            </span>
        </td>
        <td style="text-align: center; font-size: 12px;">
            <div>Total: {{ $madrasah->total_teachers ?? 0 }}</div>
            <div>Sudah: {{ $madrasah->teachers_with_attendance ?? 0 }}</div>
            <div>Belum: {{ $madrasah->teachers_without_attendance ?? 0 }}</div>
        </td>
        <td style="text-align: center; font-weight: bold;">
            <span class="badge bg-{{ $madrasah->attendance_pending_percentage <= 20 ? 'success' : ($madrasah->attendance_pending_percentage <= 50 ? 'warning' : 'danger') }}">
                {{ $madrasah->attendance_pending_percentage }}%
            </span>
        </td>
            <td style="text-align: center;">
                <button
                    class="btn btn-primary btn-sm btn-detail"
                    data-madrasah-id="{{ $madrasah->id }}"
                    data-schedule-input="{{ $madrasah->schedule_input_percentage }}"
                    data-attendance="{{ $madrasah->attendance_percentage }}"
                    data-pending="{{ $madrasah->attendance_pending_percentage }}"
                >
                    Lihat Detail
                </button>
            </td>
    </tr>
    @endforeach
</tbody>
        </table>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    $(document).ready(function() {
        @foreach($kabupatenOrder as $kabupaten)
        $('#datatable-{{ \Illuminate\Support\Str::slug($kabupaten) }}').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'pdf', 'print', 'colvis'
            ],
            responsive: true,
            order: [[0, 'asc']]
        });
        @endforeach
    });
</script>
@endpush

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Presensi Mengajar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col text-center">
                        <h6>Sudah Input Jadwal (%)</h6>
                        <p id="modalScheduleInput" class="fs-4 fw-bold text-primary mb-0"></p>
                    </div>
                    <div class="col text-center">
                        <h6>Sudah Presensi Mengajar (%)</h6>
                        <p id="modalAttendance" class="fs-4 fw-bold text-success mb-0"></p>
                    </div>
                    <div class="col text-center">
                        <h6>Belum Presensi Mengajar (%)</h6>
                        <p id="modalPending" class="fs-4 fw-bold text-danger mb-0"></p>
                    </div>
                </div>
                <hr>
                <h6>Daftar Tenaga Pendidik</h6>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Status Kepegawaian</th>
                            <th>Status Presensi</th>
                        </tr>
                    </thead>
                    <tbody id="modalTeacherList">
                        <tr>
                            <td colspan="3" class="text-center">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        // Handler for Lihat Detail button click
        $('table').on('click', '.btn-detail', function (e) {
            e.preventDefault();

            var button = $(this);
            var madrasahId = button.data('madrasah-id');
            var scheduleInput = button.data('schedule-input');
            var attendance = button.data('attendance');
            var pending = button.data('pending');

            // Set percentage info in modal
            $('#modalScheduleInput').text(scheduleInput + '%');
            $('#modalAttendance').text(attendance + '%');
            $('#modalPending').text(pending + '%');

            // Load teacher list via AJAX
            $('#modalTeacherList').html('<tr><td colspan="3" class="text-center">Memuat data...</td></tr>');
            $.ajax({
                url: '/teaching-progress/madrasah/' + madrasahId + '/teachers',
                method: 'GET',
                success: function (response) {
                    if (response.teachers.length === 0) {
                        $('#modalTeacherList').html('<tr><td colspan="3" class="text-center">Tidak ada tenaga pendidik</td></tr>');
                        return;
                    }

                    var rows = '';
                    response.teachers.forEach(function (teacher) {
                        rows += '<tr>' +
                            '<td>' + teacher.name + '</td>' +
                            '<td>' + teacher.status_kepegawaian + '</td>' +
                            '<td>' + teacher.presensi_status + '</td>' +
                            '</tr>';
                    });
                    $('#modalTeacherList').html(rows);
                },
                error: function () {
                    $('#modalTeacherList').html('<tr><td colspan="3" class="text-center text-danger">Gagal memuat data tenaga pendidik</td></tr>');
                }
            });

            // Show modal
            var modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        });
    });
</script>
@endpush

@endsection
