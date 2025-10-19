@extends('layouts.master')

@section('title') Jadwal Mengajar @endsection

@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Jadwal Mengajar @endslot
@endcomponent

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.schedule-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.schedule-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}
.teacher-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    border-left: 4px solid #0d6efd;
}
.teacher-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 18px;
}
.schedule-item {
    background: white;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}
.schedule-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-color: #0d6efd;
}
.day-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}
.time-badge {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}
.subject-badge {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}
.school-info {
    background: rgba(0,123,255,0.1);
    border: 1px solid rgba(0,123,255,0.2);
    border-radius: 8px;
    padding: 8px 12px;
}
.empty-state {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
}
.btn-action {
    border-radius: 20px;
    padding: 6px 12px;
    font-size: 0.75rem;
}
</style>
@endsection

@section('content')
<!-- Header Section - Mobile Optimized -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg">
                            <div class="avatar-title bg-gradient-primary rounded-circle">
                                <i class="bx bx-calendar fs-1"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="card-title mb-1">Daftar Jadwal Mengajar</h4>
                        <p class="text-muted mb-0">Kelola jadwal mengajar tenaga pendidik</p>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            @if(Auth::user()->role !== 'tenaga_pendidik')
                            <a href="{{ route('teaching-schedules.create') }}" class="btn btn-primary rounded-pill px-3">
                                <i class="bx bx-plus me-1"></i>Tambah
                            </a>
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin' || Auth::user()->role === 'pengurus')
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="bx bx-upload me-1"></i>Import
                            </button>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 rounded-3" role="alert">
    <div class="d-flex align-items-center">
        <i class="bx bx-check-circle fs-4 me-3"></i>
        <div>{{ session('success') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show border-0 rounded-3" role="alert">
    <div class="d-flex align-items-center">
        <i class="bx bx-error-circle fs-4 me-3"></i>
        <div>{{ session('error') }}</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Schedule Content -->
@if($grouped->isEmpty())
    <!-- Empty State -->
    <div class="card empty-state shadow-sm border-0">
        <div class="card-body text-center py-5">
            <div class="avatar-xl mx-auto mb-4">
                <div class="avatar-title bg-light rounded-circle">
                    <i class="bx bx-calendar-x fs-1 text-muted"></i>
                </div>
            </div>
            <h5 class="text-muted mb-2">Belum ada jadwal mengajar</h5>
            <p class="text-muted mb-0">Belum ada data jadwal mengajar yang terdaftar dalam sistem.</p>
        </div>
    </div>
@else
    <!-- Teacher Schedule Sections - Mobile Optimized -->
    @foreach($grouped as $teacherName => $schedules)
    <div class="teacher-section">
        <div class="d-flex align-items-center mb-4">
            <div class="teacher-avatar me-3">
                {{ strtoupper(substr($teacherName, 0, 1)) }}
            </div>
            <div>
                <h5 class="mb-1 text-primary">{{ $teacherName }}</h5>
                <small class="text-muted">{{ count($schedules) }} jadwal mengajar</small>
            </div>
        </div>

        <div class="row g-3">
            @foreach($schedules as $schedule)
            <div class="col-12">
                <div class="schedule-item">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <div class="d-flex flex-column gap-2">
                                <span class="day-badge">{{ $schedule->day }}</span>
                                <span class="time-badge">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex flex-column flex-md-row gap-2 mb-2">
                                <span class="subject-badge">{{ $schedule->subject }}</span>
                                <span class="badge bg-light text-dark">{{ $schedule->class_name }}</span>
                            </div>
                            <div class="school-info">
                                <i class="bx bx-building me-1"></i>
                                <strong>{{ $schedule->school->name ?? 'N/A' }}</strong>
                            </div>
                        </div>
                        @if(Auth::user()->role !== 'tenaga_pendidik')
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <a href="{{ route('teaching-schedules.edit', $schedule->id) }}" class="btn btn-warning btn-action">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-action delete-btn"
                                        data-id="{{ $schedule->id }}"
                                        data-name="{{ $schedule->subject }} - {{ $schedule->class_name }}">
                                    <i class="bx bx-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
@endif

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Jadwal Mengajar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if(session('import_errors'))
                <div class="alert alert-danger">
                    <strong>Import gagal dengan {{ count(session('import_errors')) }} error(s):</strong>
                    <ul class="mt-2 mb-0">
                        @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <h5>Panduan Import Jadwal Mengajar</h5>
                        <div class="alert alert-info">
                            <h6><i class="bx bx-info-circle"></i> Instruksi:</h6>
                            <ol>
                                <li>Unduh template file Excel dengan mengklik tombol "Unduh Template" di bawah ini.</li>
                                <li>Buka file template menggunakan Microsoft Excel atau aplikasi spreadsheet lainnya.</li>
                                <li>Isi data jadwal mengajar sesuai dengan format yang telah ditentukan.</li>
                                <li>Simpan file dalam format Excel (.xlsx/.xls).</li>
                                <li>Upload file yang telah diisi menggunakan form di bawah ini.</li>
                            </ol>
                        </div>

                        <h6>Format Data yang Diperlukan:</h6>
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Tipe Data</th>
                                    <th>Keterangan</th>
                                    <th>Wajib</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>school_scod</code></td>
                                    <td>Angka</td>
                                    <td>Kode SCOD Madrasah (lihat di master data madrasah)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>teacher_nuist_id</code></td>
                                    <td>Angka</td>
                                    <td>NUist ID guru (lihat di master data tenaga pendidik)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>day</code></td>
                                    <td>Teks</td>
                                    <td>Hari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>subject</code></td>
                                    <td>Teks</td>
                                    <td>Mata pelajaran</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>class_name</code></td>
                                    <td>Teks</td>
                                    <td>Nama kelas (contoh: Kelas 1A, Kelas 2B)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>start_time</code></td>
                                    <td>Jam (HH:MM)</td>
                                    <td>Jam mulai mengajar (contoh: 08:00)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>end_time</code></td>
                                    <td>Jam (HH:MM)</td>
                                    <td>Jam selesai mengajar (contoh: 09:00)</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="alert alert-warning">
                            <h6><i class="bx bx-error"></i> Catatan Penting:</h6>
                            <ul>
                                <li>Pastikan Kode SCOD Madrasah dan NUist ID Guru sudah terdaftar dalam sistem.</li>
                                <li>Jam selesai harus lebih besar dari jam mulai.</li>
                                <li>Sistem akan mengecek konflik jadwal otomatis (guru yang sama pada hari dan jam yang sama).</li>
                                <li>Data yang tidak valid akan dilewati dengan pesan error.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bx bx-upload"></i> Upload File</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('teaching-schedules.process-import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Pilih File Import</label>
                                        <input type="file" class="form-control" id="file" name="file" accept=".csv,.xlsx,.xls" required>
                                        <div class="form-text">Format: CSV, Excel (.xlsx, .xls) - Maksimal 10MB</div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-upload"></i> Import Data
                                        </button>
                                        <a href="{{ asset('template/teaching_schedule_import_template.xlsx') }}" class="btn btn-outline-secondary" download>
                                            <i class="bx bx-download"></i> Unduh Template
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bx bx-link"></i> Link Penting</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('madrasah.index') }}" target="_blank">Lihat Data Madrasah</a></li>
                                    <li><a href="{{ route('tenaga-pendidik.index') }}" target="_blank">Lihat Data Tenaga Pendidik</a></li>
                                    <li><a href="{{ route('teaching-schedules.index') }}">Kembali ke Daftar Jadwal</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script-bottom')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Import Gagal',
            text: '{{ session("error") }}',
            confirmButtonText: 'Tutup'
        });
    @endif

    @if(session('import_errors'))
        var errorList = '';
        @foreach(session('import_errors') as $error)
            errorList += '{{ $error }}\n';
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Import Gagal',
            html: '<div style="text-align: left; white-space: pre-line;">' + errorList + '</div>',
            confirmButtonText: 'Tutup'
        });
    @endif

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session("success") }}',
            confirmButtonText: 'OK'
        });
    @endif

    // SweetAlert for delete confirmation
    $('.delete-btn').on('click', function() {
        var scheduleId = $(this).data('id');
        var scheduleName = $(this).data('name');

        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Jadwal "' + scheduleName + '" akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create and submit form
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ route("teaching-schedules.destroy", ":id") }}'.replace(':id', scheduleId)
                });
                form.append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
                form.append('<input type="hidden" name="_method" value="DELETE">');
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endsection
