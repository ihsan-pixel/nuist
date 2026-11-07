@extends('layouts.master')

@section('title')
    Jumlah Siswa per Kelas
@endsection

@section('css')
    <link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <style>
        .student-count-page{--sc-green:#08745a;--sc-ink:#192d37;--sc-muted:#6c7c86;position:relative;isolation:isolate;padding:8px 0 24px}.student-count-page:before,.student-count-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:sc-float 10s ease-in-out infinite}.student-count-page:before{width:280px;height:280px;right:-100px;top:-90px;background:rgba(42,190,153,.15)}.student-count-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.11);animation-direction:reverse;animation-duration:12s}body:has(.student-count-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(.student-count-page) .main-content{background:transparent!important}.student-count-page .sc-shell{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden;animation:sc-in .72s both}.student-count-page .sc-toolbar{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 24px;border-bottom:1px solid #e5edef}.student-count-page .sc-eyebrow{margin:0 0 4px;color:var(--sc-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.student-count-page .sc-title{margin:0;color:var(--sc-ink);font-size:22px;font-weight:700}.student-count-page .sc-subtitle{margin:5px 0 0;color:var(--sc-muted);font-size:12px}.student-count-page .sc-content{padding:20px 24px 24px}.student-count-page .sc-stat{border:1px solid rgba(255,255,255,.86);border-radius:16px;background:rgba(255,255,255,.68);box-shadow:0 8px 22px rgba(32,70,78,.07)}.student-count-page .sc-stat .card-body{padding:16px 18px}.student-count-page .table-panel{border:1px solid #e5edef;border-radius:16px;background:rgba(255,255,255,.58);overflow-x:auto}.student-count-page table.dataTable{width:100%!important;table-layout:auto;min-width:1000px}.student-count-page table.dataTable thead th{border:0;border-bottom:1px solid #e5edef;background:#f1f6f6;color:var(--sc-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.student-count-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle;white-space:normal!important;overflow-wrap:anywhere;word-break:break-word}.student-count-page table.dataTable th:nth-child(1),.student-count-page table.dataTable td:nth-child(1){width:54px}.student-count-page table.dataTable th:nth-child(2),.student-count-page table.dataTable td:nth-child(2){min-width:220px}.student-count-page table.dataTable th:nth-child(3),.student-count-page table.dataTable td:nth-child(3){min-width:110px}.student-count-page table.dataTable th:nth-child(4),.student-count-page table.dataTable td:nth-child(4),.student-count-page table.dataTable th:nth-child(5),.student-count-page table.dataTable td:nth-child(5){min-width:145px}.student-count-page table.dataTable th:nth-child(6),.student-count-page table.dataTable td:nth-child(6){width:100px}.student-count-page table.dataTable th:nth-child(7),.student-count-page table.dataTable td:nth-child(7){min-width:145px}.student-count-page table.dataTable th:last-child,.student-count-page table.dataTable td:last-child{width:135px}.student-count-page .dataTables_wrapper{padding:16px}.student-count-page .dataTables_wrapper .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}.student-count-page .dataTables_wrapper .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem}.student-count-page .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}.student-count-page .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}.student-count-page .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}.student-count-page .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}.student-count-page .buttons-colvis,.student-count-page .buttons-columnVisibility{background:linear-gradient(90deg,#db2777,#f472b6)!important}.student-count-page div.dt-button-collection{width:245px!important;padding:10px!important;border:1px solid #e3eaec!important;border-radius:16px!important;background:#f8fafb!important;box-shadow:0 12px 28px rgba(32,70,78,.14)!important}.student-count-page div.dt-button-collection button.dt-button{display:block!important;width:100%!important;margin:0 0 6px!important;padding:13px 18px!important;border:0!important;border-radius:14px!important;background:#fff!important;background-image:none!important;color:#192d37!important;box-shadow:0 4px 10px rgba(32,70,78,.07)!important;text-align:left!important;font-size:15px!important}.student-count-page .modal{z-index:2000!important;pointer-events:none}.student-count-page .modal.show{pointer-events:auto}.student-count-page .modal.show .modal-dialog{position:relative;z-index:2001;pointer-events:auto}.student-count-page .modal-content{border:1px solid rgba(255,255,255,.9);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}.student-count-page .modal-header{border-bottom:1px solid #e5edef;background:#f6faf9}.student-count-page .modal-footer{border-top:1px solid #e5edef}.student-count-page .form-control,.student-count-page .form-select{border:1px solid #dce7e9;border-radius:9px}.sc-swal{width:min(420px,calc(100% - 32px))!important;border-radius:22px!important;background:rgba(255,255,255,.92)!important;backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(32,70,78,.2)!important}.sc-swal-confirm{border:0!important;border-radius:10px!important;background:#08745a!important;color:#fff!important;padding:10px 16px!important}.sc-swal-cancel{border:1px solid #dce7e9!important;border-radius:10px!important;background:#f5f8f9!important;color:#52656d!important;padding:10px 16px!important}@keyframes sc-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@keyframes sc-float{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(0,14px,0)}}@media(max-width:767.98px){.student-count-page .sc-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.student-count-page .sc-content{padding:14px}.student-count-page .dataTables_wrapper{padding:10px}.student-count-page .modal-dialog{margin:.75rem}}@media(max-width:390px){.student-count-page .dataTables_wrapper .dt-buttons{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}.student-count-page .dataTables_wrapper .dt-button{width:100%;text-align:center}}
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Master Data @endslot
        @slot('title') Jumlah Siswa per Kelas @endslot
    @endcomponent

    @php
        $user = auth()->user();
        $isAdmin = $user && $user->role === 'admin';
        $selectedSchool = $selectedSchoolId ? $schools->firstWhere('id', $selectedSchoolId) : null;
        $defaultSchool = $isAdmin ? $schools->first() : $selectedSchool;
    @endphp

    <div class="student-count-page">
    <div class="row">
        <div class="col-md-3">
            <div class="card mini-stats-wid sc-stat">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Total Kelas</p>
                    <h4 class="mb-0">{{ number_format($stats['total_classes']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid sc-stat">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Jumlah Siswa Total</p>
                    <h4 class="mb-0 text-info">{{ number_format($stats['total_students']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid sc-stat">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Sudah Tersimpan</p>
                    <h4 class="mb-0 text-success">{{ number_format($stats['saved_counts']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid sc-stat">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Belum Diinput</p>
                    <h4 class="mb-0 text-warning">{{ number_format($stats['missing_counts']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card sc-shell">
        <div class="card-header sc-toolbar">
            <div><p class="sc-eyebrow">MASTER DATA</p><h4 class="sc-title">Jumlah Siswa per Kelas</h4><p class="sc-subtitle">Kelola jumlah siswa berdasarkan kelas dan periode pembelajaran.</p></div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJumlahSiswa">
                <i class="bx bx-plus"></i> Tambah Data
            </button>
        </div>
        <div class="card-body sc-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="GET" action="{{ route('class-student-counts.index') }}" class="row g-2 align-items-end mb-3">
                <div class="col-md-5">
                    <label class="form-label">Filter Madrasah/Sekolah</label>
                    @if($isAdmin && $defaultSchool)
                        <input type="hidden" name="school_id" value="{{ $defaultSchool->id }}">
                        <input type="text" class="form-control" value="{{ $defaultSchool->name }}" disabled>
                    @else
                        <select name="school_id" class="form-select">
                            <option value="">Semua Madrasah/Sekolah</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" @selected((int) $selectedSchoolId === (int) $school->id)>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Periode</label>
                    <select name="period_id" class="form-select">
                        <option value="">Pilih periode</option>
                        @foreach($periods as $period)
                            <option value="{{ $period->id }}" @selected(optional($selectedPeriod)->id === $period->id)>
                                {{ $period->semester_label }} {{ $period->school_year }} | {{ $period->date_range_label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter-alt"></i> Filter
                    </button>
                </div>
                <div class="col-md-auto">
                    <a href="{{ route('class-student-counts.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>

            @if($selectedPeriod)
                <div class="alert alert-info">
                    <strong>{{ $selectedPeriod->title }}</strong><br>
                    <small>{{ $selectedPeriod->semester_label }} | {{ $selectedPeriod->school_year }} | Berlaku {{ $selectedPeriod->date_range_label }}</small>
                </div>
            @elseif($selectedSchoolId)
                <div class="alert alert-warning">
                    Pilih atau buat periode jadwal mengajar terlebih dahulu agar data jumlah siswa per kelas dapat dikelola per tahun ajaran.
                </div>
            @endif

            <div class="table-panel"><div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered dt-responsive w-100 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Madrasah/Sekolah</th>
                            <th>Kelas</th>
                            <th>Jumlah Siswa</th>
                            <th>Referensi Presensi</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            @php
                                $modalId = 'modalJumlahSiswa' . $loop->iteration;
                                $deleteFormId = 'deleteJumlahSiswa' . $loop->iteration;
                                $hasSavedCount = !is_null($row['count_id']);
                                $inputValue = old('total_students', $row['total_students'] ?? $row['latest_attendance_total']);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $row['school_name'] }}</div>
                                    <small class="text-muted">{{ $row['school_kabupaten'] }}</small>
                                </td>
                                <td>{{ $row['class_name'] }}</td>
                                <td>
                                    @if($hasSavedCount)
                                        <span class="fw-semibold">{{ number_format($row['total_students']) }}</span>
                                        <small class="text-muted d-block">
                                            @if($row['updated_at'])
                                                Update: {{ $row['updated_at']->format('d/m/Y H:i') }}
                                            @endif
                                            @if($row['updated_by'])
                                                oleh {{ $row['updated_by'] }}
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">Belum diinput</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!is_null($row['latest_attendance_total']))
                                        <span class="fw-semibold">{{ number_format($row['latest_attendance_total']) }}</span>
                                        <small class="text-muted d-block">
                                            {{ \Carbon\Carbon::parse($row['latest_attendance_date'])->format('d/m/Y') }}
                                            @if($row['latest_attendance_time'])
                                                {{ substr($row['latest_attendance_time'], 0, 5) }}
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                                <td>{{ number_format($row['schedule_count']) }} jadwal</td>
                                <td>
                                    @if($hasSavedCount)
                                        @if(!is_null($row['latest_attendance_total']) && (int) $row['total_students'] !== (int) $row['latest_attendance_total'])
                                            <span class="badge bg-warning text-dark">Berbeda dari presensi</span>
                                        @else
                                            <span class="badge bg-success">Tersimpan</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Perlu input</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm {{ $hasSavedCount ? 'btn-warning' : 'btn-primary' }}" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                            {{ $hasSavedCount ? 'Edit' : 'Input' }}
                                        </button>
                                        @if($hasSavedCount)
                                            <form action="{{ route('class-student-counts.destroy', $row['count_id']) }}" method="POST" onsubmit="return confirm('Hapus data jumlah siswa untuk kelas {{ addslashes($row['class_name']) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form
                                        action="{{ $hasSavedCount ? route('class-student-counts.update', $row['count_id']) : route('class-student-counts.store') }}"
                                        method="POST">
                                        @csrf
                                        @if($hasSavedCount)
                                            @method('PUT')
                                        @else
                                            <input type="hidden" name="school_id" value="{{ $row['school_id'] }}">
                                            <input type="hidden" name="teaching_schedule_period_id" value="{{ optional($selectedPeriod)->id }}">
                                        @endif

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ $hasSavedCount ? 'Edit' : 'Input' }} Jumlah Siswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Madrasah/Sekolah</label>
                                                    <input type="text" class="form-control" value="{{ $row['school_name'] }}" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kelas</label>
                                                    <input type="text" name="class_name" class="form-control" value="{{ old('class_name', $row['class_name']) }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jumlah Siswa</label>
                                                    <input type="number" name="total_students" class="form-control" min="1" max="10000" value="{{ $inputValue }}" required>
                                                    @if(!is_null($row['latest_attendance_total']))
                                                        <div class="form-text">
                                                            Referensi presensi terakhir: {{ number_format($row['latest_attendance_total']) }} siswa.
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                @if($hasSavedCount)
                                                    <button type="submit"
                                                            form="{{ $deleteFormId }}"
                                                            class="btn btn-outline-danger me-auto"
                                                            onclick="return confirm('Hapus data jumlah siswa untuk kelas {{ addslashes($row['class_name']) }}?');">
                                                        Hapus
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                    @if($hasSavedCount)
                                        <form id="{{ $deleteFormId }}" action="{{ route('class-student-counts.destroy', $row['count_id']) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            {{-- Empty state ditangani oleh konfigurasi DataTables agar jumlah kolom tetap valid. --}}
                        @endforelse
                    </tbody>
                </table>
            </div></div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modalTambahJumlahSiswa" tabindex="-1" aria-labelledby="modalTambahJumlahSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('class-student-counts.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahJumlahSiswaLabel">Tambah Jumlah Siswa per Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        @if($isAdmin && $defaultSchool)
                            <input type="hidden" name="school_id" value="{{ $defaultSchool->id }}">
                            <input type="hidden" name="teaching_schedule_period_id" value="{{ optional($selectedPeriod)->id }}">
                            <div class="mb-3">
                                <label class="form-label">Madrasah/Sekolah</label>
                                <input type="text" class="form-control" value="{{ $defaultSchool->name }}" disabled>
                            </div>
                        @else
                            <div class="mb-3">
                                <label class="form-label">Madrasah/Sekolah</label>
                                <select name="school_id" class="form-select" required>
                                    <option value="">Pilih Madrasah/Sekolah</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" @selected((int) old('school_id', $defaultSchool->id ?? null) === (int) $school->id)>
                                            {{ $school->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Periode Jadwal</label>
                                <select name="teaching_schedule_period_id" class="form-select" required>
                                    <option value="">Pilih periode</option>
                                    @foreach($periods as $period)
                                        <option value="{{ $period->id }}" @selected((int) old('teaching_schedule_period_id', optional($selectedPeriod)->id) === (int) $period->id)>
                                            {{ $period->semester_label }} {{ $period->school_year }} | {{ $period->date_range_label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="class_name" class="form-control" value="{{ old('class_name') }}" placeholder="Contoh: VII A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Siswa</label>
                            <input type="number" name="total_students" class="form-control" min="1" max="10000" value="{{ old('total_students') }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
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
            $('.modal').appendTo(document.body);
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"],
                order: [[1, "asc"], [2, "asc"]],
                language: {
                    emptyTable: '<div class="empty-state"><i class="bx bx-group"></i><strong>Belum ada data kelas</strong><small>Tambahkan data jumlah siswa atau lengkapi jadwal mengajar terlebih dahulu.</small></div>',
                    zeroRecords: 'Data kelas tidak ditemukan.'
                }
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

            const normalizeColumnMenu = () => document.querySelectorAll('div.dt-button-collection, div.dt-button-collection *').forEach((item) => {
                item.style.setProperty('background', '#fff', 'important');
                item.style.setProperty('background-image', 'none', 'important');
                item.style.setProperty('color', '#192d37', 'important');
            });
            new MutationObserver(normalizeColumnMenu).observe(document.body, {childList:true, subtree:true});
            $(document).on('click', '.buttons-colvis,.buttons-columnVisibility', () => setTimeout(normalizeColumnMenu, 20));

            @if(session('success'))
                Swal.fire({customClass:{popup:'sc-swal'},buttonsStyling:false,icon:'success',title:'Berhasil',text:@json(session('success')),timer:2200,showConfirmButton:false});
            @endif
            @if($errors->any())
                Swal.fire({customClass:{popup:'sc-swal',confirmButton:'sc-swal-confirm'},buttonsStyling:false,icon:'error',title:'Periksa data',text:@json($errors->first()),confirmButtonText:'Tutup'});
            @endif
            $(document).on('submit', 'form[action*="/jumlah-siswa-kelas/"]', function(event){
                if (!this.querySelector('input[name="_method"][value="DELETE"]')) return;
                event.preventDefault(); const form=this;
                Swal.fire({customClass:{popup:'sc-swal',confirmButton:'sc-swal-confirm',cancelButton:'sc-swal-cancel'},buttonsStyling:false,icon:'warning',title:'Hapus data?',text:'Data jumlah siswa tidak dapat dikembalikan.',showCancelButton:true,confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(result=>{if(result.isConfirmed) form.submit()});
            });
        });
    </script>
@endsection
