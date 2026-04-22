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

    <div class="row">
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Total Kelas</p>
                    <h4 class="mb-0">{{ number_format($stats['total_classes']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Jumlah Siswa Total</p>
                    <h4 class="mb-0 text-info">{{ number_format($stats['total_students']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Sudah Tersimpan</p>
                    <h4 class="mb-0 text-success">{{ number_format($stats['saved_counts']) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Belum Diinput</p>
                    <h4 class="mb-0 text-warning">{{ number_format($stats['missing_counts']) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h4 class="card-title mb-0">
                <i class="bx bx-group me-2"></i>Jumlah Siswa per Kelas
            </h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJumlahSiswa">
                <i class="bx bx-plus"></i> Tambah Data
            </button>
        </div>
        <div class="card-body">
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

            @if(!$isAdmin)
                <form method="GET" action="{{ route('class-student-counts.index') }}" class="row g-2 align-items-end mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Filter Madrasah/Sekolah</label>
                        <select name="school_id" class="form-select">
                            <option value="">Semua Madrasah/Sekolah</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" @selected((int) $selectedSchoolId === (int) $school->id)>
                                    {{ $school->name }}
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
            @endif

            <div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100 align-middle">
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
                                    <button type="button" class="btn btn-sm {{ $hasSavedCount ? 'btn-warning' : 'btn-primary' }}" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                                        {{ $hasSavedCount ? 'Edit' : 'Input' }}
                                    </button>
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
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center mb-0" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>Belum ada data kelas</strong><br>
                                        <small>Tambahkan data jumlah siswa atau lengkapi jadwal mengajar terlebih dahulu.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                buttons: ["copy", "excel", "pdf", "print", "colvis"],
                order: [[1, "asc"], [2, "asc"]]
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
