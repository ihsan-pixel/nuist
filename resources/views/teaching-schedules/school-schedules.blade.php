@extends('layouts.master')

@section('title') Jadwal Mengajar - {{ $school->name }} @endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />

<style>
.schedule-page {
    display: grid;
    gap: 1.5rem;
}

.page-hero {
    border: 1px solid rgba(13, 110, 253, 0.12);
    border-radius: 1rem;
    background: linear-gradient(135deg, #ffffff 0%, #f4f8ff 100%);
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
}

.hero-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0d6efd 0%, #3b82f6 100%);
    color: #fff;
    font-size: 1.75rem;
    box-shadow: 0 12px 24px rgba(13, 110, 253, 0.22);
}

.summary-card,
.day-card,
.empty-state {
    border: 1px solid #e9edf5;
    border-radius: 1rem;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
}

.summary-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.day-card {
    overflow: hidden;
    background: #fff;
}

.day-card-header {
    padding: 1rem 1.25rem;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    border-bottom: 1px solid #eef2f7;
}

.teacher-block + .teacher-block {
    border-top: 1px solid #eef2f7;
    margin-top: 1rem;
    padding-top: 1rem;
}

.teacher-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #e8f1ff;
    color: #0d6efd;
    font-weight: 700;
}

.schedule-item {
    border: 1px solid #edf1f7;
    border-radius: 0.9rem;
    padding: 0.9rem 1rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}

.schedule-item:hover {
    border-color: rgba(13, 110, 253, 0.2);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.06);
    transform: translateY(-1px);
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.meta-badge-time {
    color: #9a3412;
    background: #fff1e6;
}

.subject-title {
    font-size: 0.98rem;
    font-weight: 600;
    color: #0f172a;
}

.info-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.45rem 0.75rem;
    border-radius: 0.75rem;
    background: #f8fafc;
    color: #475569;
    border: 1px solid #edf2f7;
    font-size: 0.85rem;
}

.empty-state {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
}

.btn-action {
    border-radius: 999px;
    padding: 0.45rem 0.9rem;
    font-size: 0.75rem;
    white-space: nowrap;
}
</style>
@endsection

@section('content')
@php
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $totalTeachers = $grouped->count();
    $totalSchedules = $grouped->flatten()->count();
@endphp

<div class="schedule-page">
    <div class="card page-hero mb-0">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-auto">
                    <div class="hero-icon">
                        <i class="bx bx-building-house"></i>
                    </div>
                </div>
                <div class="col">
                    <h4 class="card-title mb-1">Jadwal Mengajar {{ $school->name }}</h4>
                    <p class="text-muted mb-2">Kabupaten: {{ $school->kabupaten }} | SCOD: {{ $school->scod }}</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark border">Total guru: {{ $totalTeachers }}</span>
                        <span class="badge bg-light text-dark border">Total jadwal: {{ $totalSchedules }}</span>
                    </div>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        @if((Auth::user()->role !== 'tenaga_pendidik' || Auth::user()->ketugasan !== 'kepala madrasah/sekolah') && Auth::user()->role !== 'admin' && Auth::user()->role !== 'super_admin')
                        <a href="{{ route('teaching-schedules.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                            <i class="bx bx-arrow-back me-1"></i>Kembali
                        </a>
                        @endif

                        @if(Auth::user()->role !== 'tenaga_pendidik')
                        <a href="{{ route('teaching-schedules.create') }}" class="btn btn-primary rounded-pill px-3">
                            <i class="bx bx-plus me-1"></i>Tambah Jadwal
                        </a>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="bx bx-upload me-1"></i>Import Jadwal
                        </button>
                        @endif
                        @endif

                        @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin' || (Auth::user()->role === 'tenaga_pendidik' && Auth::user()->ketugasan === 'kepala madrasah/sekolah'))
                        <a href="{{ route('teaching-schedules.school-classes', $school->id) }}" class="btn btn-info rounded-pill px-3">
                            <i class="bx bx-group me-1"></i>Lihat Kelas
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-primary-subtle text-primary">
                        <i class="bx bx-group"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Tenaga Pendidik</div>
                        <h4 class="mb-0">{{ $totalTeachers }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-success-subtle text-success">
                        <i class="bx bx-book-content"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Jadwal</div>
                        <h4 class="mb-0">{{ $totalSchedules }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-warning-subtle text-warning">
                        <i class="bx bx-calendar-week"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Hari Aktif</div>
                        <h4 class="mb-0">{{ collect($days)->filter(fn ($day) => $grouped->flatten()->where('day', $day)->isNotEmpty())->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($grouped->flatten()->isEmpty())
    <div class="card empty-state mb-0">
        <div class="card-body text-center py-5">
            <div class="avatar-md mx-auto mb-3">
                <div class="avatar-title bg-light rounded-circle">
                    <i class="bx bx-calendar font-size-24 text-muted"></i>
                </div>
            </div>
            <h5 class="text-muted">Belum ada jadwal mengajar</h5>
            <p class="text-muted mb-0">Belum ada jadwal mengajar untuk madrasah ini.</p>
        </div>
    </div>
    @else
    <div class="row g-4">
        @foreach($days as $day)
            @php
                $daySchedules = collect();
                foreach ($grouped as $teacherName => $schedules) {
                    $teacherDaySchedules = $schedules->where('day', $day);
                    if ($teacherDaySchedules->isNotEmpty()) {
                        $daySchedules[$teacherName] = $teacherDaySchedules;
                    }
                }
            @endphp

            @if($daySchedules->isNotEmpty())
            <div class="col-lg-6 col-xl-4">
                <div class="card day-card h-100 mb-0">
                    <div class="day-card-header d-flex align-items-center justify-content-between gap-2">
                        <div>
                            <h6 class="mb-1 text-primary">
                                <i class="bx bx-calendar-week me-2"></i>{{ $day }}
                            </h6>
                            <small class="text-muted">{{ $daySchedules->flatten()->count() }} jadwal</small>
                        </div>
                        <span class="badge bg-light text-dark border">{{ $daySchedules->count() }} guru</span>
                    </div>
                    <div class="card-body">
                        @foreach($daySchedules as $teacherName => $schedules)
                        <div class="teacher-block">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="teacher-avatar">
                                    {{ strtoupper(substr($teacherName, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-primary">{{ $teacherName }}</div>
                                    <small class="text-muted">{{ count($schedules) }} jadwal</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                @foreach($schedules as $schedule)
                                <div class="schedule-item">
                                    <div class="d-flex flex-column gap-3">
                                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                                            <div class="subject-title">{{ $schedule->subject }}</div>
                                            <span class="meta-badge meta-badge-time">
                                                <i class="bx bx-time-five"></i>{{ $schedule->start_time }} - {{ $schedule->end_time }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="info-chip">
                                                <i class="bx bx-group"></i>{{ $schedule->class_name }}
                                            </span>
                                        </div>
                                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('teaching-schedules.edit', $schedule->id) }}" class="btn btn-outline-primary btn-action" title="Edit Jadwal">
                                                <i class="bx bx-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-outline-danger btn-action delete-btn" data-id="{{ $schedule->id }}" data-name="{{ $schedule->subject }} - {{ $schedule->class_name }}" title="Hapus Jadwal">
                                                <i class="bx bx-trash"></i> Hapus
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif
</div>

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
