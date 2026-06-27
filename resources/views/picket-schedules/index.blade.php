@extends('layouts.master')

@section('title', 'Izin Jadwal Piket')

@section('css')
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Akademik @endslot
    @slot('title') Izin Jadwal Piket @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1">Izin Jadwal Piket Semester</h4>
                    <p class="text-muted mb-0">
                        Kelola periode libur semester dan pengajuan jadwal piket untuk
                        <strong>{{ $school?->name ?? 'semua sekolah' }}</strong>.
                    </p>
                </div>
                <div>
                    <a href="{{ route('picket-schedule-periods.create', $selectedSchoolId ? ['school_id' => $selectedSchoolId] : []) }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Tambah Periode
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(Auth::user()->role === 'super_admin')
                    <form method="GET" action="{{ route('picket-schedule-periods.index') }}" class="row g-2 align-items-end mb-3">
                        <div class="col-md-6 col-lg-4">
                            <label class="form-label">Filter Sekolah</label>
                            <select name="school_id" class="form-select">
                                <option value="">Semua sekolah</option>
                                @foreach($schools as $schoolOption)
                                    <option value="{{ $schoolOption->id }}" @selected((string) $selectedSchoolId === (string) $schoolOption->id)>
                                        {{ $schoolOption->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Terapkan</button>
                        </div>
                    </form>
                @endif

                @if($periods->isEmpty())
                    <div class="text-center py-5">
                        <i class="bx bx-calendar-x display-5 text-muted"></i>
                        <h5 class="mt-3 mb-2">Belum ada periode jadwal piket</h5>
                        <p class="text-muted mb-0">Tambahkan periode libur semester agar guru dapat mengajukan hari piketnya.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    @if(Auth::user()->role === 'super_admin')
                                        <th>Sekolah</th>
                                    @endif
                                    <th>Periode</th>
                                    <th>Rentang Tanggal</th>
                                    <th>Pengajuan Guru</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($periods as $index => $period)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        @if(Auth::user()->role === 'super_admin')
                                            <td>{{ $period->school->name ?? '-' }}</td>
                                        @endif
                                        <td>
                                            <div class="fw-semibold">{{ $period->name }}</div>
                                            @if($period->description)
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($period->description, 110) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $period->date_range_label }}</td>
                                        <td>
                                            <div><span class="badge bg-primary">{{ $period->submissions_count }}</span> Total pengajuan</div>
                                            <div class="mt-1 d-flex flex-wrap gap-1">
                                                <span class="badge bg-warning text-dark">Pending: {{ $period->submissions_pending_count }}</span>
                                                <span class="badge bg-success">Approved: {{ $period->submissions_approved_count }}</span>
                                                <span class="badge bg-danger">Rejected: {{ $period->submissions_rejected_count }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($period->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('picket-schedule-periods.edit', $period) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ route('picket-schedule-periods.destroy', $period) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus periode jadwal piket ini? Semua pengajuan guru pada periode ini juga akan ikut terhapus.')"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('build/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function () {
    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        order: [],
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
});
</script>
@endsection
