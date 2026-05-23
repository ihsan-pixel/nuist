@extends('layouts.master')

@section('title', 'Kalender Akademik')

@section('css')
<link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Akademik @endslot
    @slot('title') Kalender Akademik & Kegiatan @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1">Kalender Akademik & Kegiatan</h4>
                    <p class="text-muted mb-0">
                        Kelola kegiatan resmi untuk <strong>{{ $school?->name ?? 'semua sekolah' }}</strong>.
                    </p>
                </div>
                <div>
                    <a href="{{ route('academic-calendar-events.create', $selectedSchoolId ? ['school_id' => $selectedSchoolId] : []) }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Tambah Kegiatan
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->has('conflict'))
                    <div class="alert alert-danger">{{ $errors->first('conflict') }}</div>
                @endif

                @if(Auth::user()->role === 'super_admin')
                    <form method="GET" action="{{ route('academic-calendar-events.index') }}" class="row g-2 align-items-end mb-3">
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

                @if($events->isEmpty())
                    <div class="text-center py-5">
                        <i class="bx bx-calendar-x display-5 text-muted"></i>
                        <h5 class="mt-3 mb-2">Belum ada kegiatan akademik</h5>
                        <p class="text-muted mb-0">Tambahkan event agar status presensi mengajar bisa disesuaikan saat ada kegiatan resmi sekolah.</p>
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
                                    <th>Nama Kegiatan</th>
                                    <th>Jenis Status</th>
                                    <th>Tanggal / Hari</th>
                                    <th>Jam Event</th>
                                    <th>Persetujuan</th>
                                    <th>Status Event</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $index => $event)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        @if(Auth::user()->role === 'super_admin')
                                            <td>{{ $event->school->name ?? '-' }}</td>
                                        @endif
                                        <td>
                                            <div class="fw-semibold">{{ $event->name }}</div>
                                            @if($event->description)
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($event->description, 110) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                {{ $event->resolved_type_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $event->date_range_label }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $event->time_range_label }}</div>
                                            <small class="text-muted">Jam ini menjadi acuan status izin event</small>
                                        </td>
                                        <td>
                                            @php
                                                $approvalClass = $event->approval_status === 'approved'
                                                    ? 'bg-success'
                                                    : ($event->approval_status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark');
                                            @endphp
                                            <span class="badge {{ $approvalClass }}">{{ $event->approval_status_label }}</span>
                                            @if($event->approver)
                                                <div><small class="text-muted">Oleh: {{ $event->approver->name }}</small></div>
                                            @endif
                                            @if($event->approval_status === \App\Models\AcademicCalendarEvent::APPROVAL_REJECTED)
                                                <div><small class="text-danger">Silakan perbarui data lalu ajukan ulang.</small></div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($event->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('academic-calendar-events.edit', $event) }}" class="btn btn-sm btn-outline-primary">
                                                {{ $event->approval_status === \App\Models\AcademicCalendarEvent::APPROVAL_REJECTED ? 'Perbaiki & Ajukan Ulang' : 'Edit' }}
                                            </a>
                                            <form action="{{ route('academic-calendar-events.destroy', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus kegiatan ini? Status izin dari event ini tidak akan lagi dipakai pada presensi mengajar.')"
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
