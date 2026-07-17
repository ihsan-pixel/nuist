@extends('layouts.master')

@section('title') Jadwal Mengajar - {{ $school->name }} @endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />

<style>
.schedule-shell {
    display: grid;
    gap: 1.5rem;
}

.hero-card,
.period-card,
.stats-card,
.day-card,
.empty-card,
.selection-card {
    border: 1px solid #e7edf5;
    border-radius: 1.25rem;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
    background: #fff;
}

.hero-card {
    background:
        radial-gradient(circle at top right, rgba(13, 110, 253, 0.16), transparent 28%),
        linear-gradient(135deg, #ffffff 0%, #f6f9ff 52%, #eef4ff 100%);
}

.hero-mark {
    width: 72px;
    height: 72px;
    border-radius: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0d6efd 0%, #3f8cff 100%);
    color: #fff;
    font-size: 2rem;
    box-shadow: 0 18px 35px rgba(13, 110, 253, 0.24);
}

.soft-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.48rem 0.82rem;
    border-radius: 999px;
    border: 1px solid #e7edf5;
    background: rgba(255, 255, 255, 0.88);
    color: #475569;
    font-size: 0.82rem;
    font-weight: 600;
}

.action-toggle {
    border-radius: 999px;
    padding: 0.7rem 1rem;
    border: 1px solid #d8e2f1;
    background: #fff;
    font-weight: 600;
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
}

.dropdown-menu-modern {
    min-width: 260px;
    border: 1px solid #e4ebf5;
    border-radius: 1rem;
    padding: 0.55rem;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
}

.dropdown-menu-modern .dropdown-item,
.dropdown-menu-modern .dropdown-item-text {
    border-radius: 0.8rem;
    padding: 0.72rem 0.85rem;
}

.dropdown-menu-modern .dropdown-item i,
.dropdown-menu-modern .dropdown-item-text i {
    width: 18px;
}

.period-card {
    background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
}

.period-picker {
    border-radius: 0.95rem;
    border: 1px solid #dbe6f3;
    min-height: 50px;
}

.period-grid {
    display: grid;
    gap: 0.85rem;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}

.period-tile {
    width: 100%;
    text-align: left;
    border: 1px solid #dfe8f3;
    border-radius: 1rem;
    padding: 1rem;
    background: #fff;
    transition: 0.2s ease;
}

.period-tile:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    border-color: rgba(13, 110, 253, 0.24);
}

.period-tile.active {
    border-color: rgba(13, 110, 253, 0.34);
    background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
    box-shadow: 0 16px 30px rgba(13, 110, 253, 0.09);
}

.period-tile-title {
    font-weight: 700;
    color: #0f172a;
}

.period-tile-meta {
    color: #64748b;
    font-size: 0.82rem;
}

.selection-card {
    background:
        radial-gradient(circle at top center, rgba(13, 110, 253, 0.12), transparent 35%),
        linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
}

.selection-icon {
    width: 84px;
    height: 84px;
    border-radius: 28px;
    margin: 0 auto 1.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e8f1ff 0%, #d8e7ff 100%);
    color: #0d6efd;
    font-size: 2.3rem;
}

.stats-card {
    overflow: hidden;
}

.stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 15px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
}

.stats-value {
    font-size: 1.65rem;
    font-weight: 700;
    color: #0f172a;
}

.stats-label {
    color: #64748b;
    font-size: 0.84rem;
}

.summary-panel {
    border: 1px solid #e8eef7;
    border-radius: 1rem;
    background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
}

.summary-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
}

.summary-meta {
    color: #64748b;
    font-size: 0.9rem;
}

.day-card {
    overflow: hidden;
}

.day-card-header {
    padding: 1rem 1.15rem;
    border-bottom: 1px solid #edf2f7;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
}

.teacher-block + .teacher-block {
    border-top: 1px solid #edf2f7;
    margin-top: 1rem;
    padding-top: 1rem;
}

.teacher-avatar {
    width: 44px;
    height: 44px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #eaf2ff;
    color: #0d6efd;
    font-weight: 700;
}

.schedule-item {
    border: 1px solid #ebf1f7;
    border-radius: 1rem;
    padding: 0.95rem 1rem;
    background: #fff;
    transition: 0.2s ease;
}

.schedule-item:hover {
    border-color: rgba(13, 110, 253, 0.2);
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.05);
}

.schedule-subject {
    font-weight: 700;
    color: #0f172a;
}

.schedule-meta {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.42rem 0.72rem;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 700;
}

.schedule-meta-time {
    color: #9a3412;
    background: #fff2e8;
}

.schedule-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.42rem 0.72rem;
    border-radius: 0.8rem;
    border: 1px solid #e8eef7;
    background: #f8fafc;
    font-size: 0.82rem;
    color: #475569;
}

.btn-pill {
    border-radius: 999px;
}

@media (max-width: 767.98px) {
    .hero-mark {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        font-size: 1.6rem;
    }

    .period-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
@php
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $user = Auth::user();
    $allSchedules = $grouped->flatten(1);
    $totalSchedules = $allSchedules->count();
    $totalTeachers = $allSchedules->pluck('teacher_id')->filter()->unique()->count();
    $totalSubjects = $allSchedules->pluck('subject')->map(fn ($item) => trim((string) $item))->filter()->unique()->count();
    $totalClasses = $allSchedules->pluck('class_name')->map(fn ($item) => trim((string) $item))->filter()->unique()->count();
    $baseScheduleUrl = route('teaching-schedules.school-schedules', ['schoolId' => $school->id]);
    $canCreateSchedules = in_array($user->role, ['admin', 'super_admin'], true);
    $canManagePeriods = in_array($user->role, ['admin', 'super_admin'], true);
    $canImportSchedules = in_array($user->role, ['admin', 'super_admin'], true);
    $canSeeSchoolClasses = in_array($user->role, ['super_admin', 'admin'], true)
        || ($user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah');
@endphp

<div class="schedule-shell">
    @if($errors->any())
    <div class="alert alert-danger mb-0">
        <div class="fw-semibold mb-1">Terdapat data yang perlu diperiksa.</div>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card hero-card mb-0">
        <div class="card-body p-4 p-lg-4">
            <div class="row align-items-start g-3">
                <div class="col-auto">
                    <div class="hero-mark">
                        <i class="bx bx-calendar-alt"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="soft-chip">
                            <i class="bx bx-building-house"></i>{{ $school->name }}
                        </span>
                        <span class="soft-chip">
                            <i class="bx bx-map"></i>{{ $school->kabupaten }}
                        </span>
                        <span class="soft-chip">
                            <i class="bx bx-hash"></i>SCOD {{ $school->scod }}
                        </span>
                    </div>
                    <h3 class="mb-2">Jadwal Mengajar</h3>
                    <p class="text-muted mb-0">
                        Pilih periode akademik terlebih dahulu untuk melihat, mengelola, dan memantau distribusi jadwal mengajar secara lebih terstruktur.
                    </p>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        @if(($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') && $user->role !== 'admin' && $user->role !== 'super_admin')
                        <a href="{{ route('teaching-schedules.index') }}" class="btn btn-outline-secondary btn-pill px-3">
                            <i class="bx bx-arrow-back me-1"></i>Kembali
                        </a>
                        @endif

                        <div class="dropdown">
                            <button class="btn action-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-grid-alt me-1"></i>Aksi Jadwal
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-modern">
                                @if($canManagePeriods)
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#createPeriodModal">
                                        <i class="bx bx-calendar-plus me-2 text-primary"></i>Tambah Periode
                                    </button>
                                </li>
                                @endif
                                @if($canCreateSchedules)
                                <li>
                                    <a class="dropdown-item {{ $selectedPeriod ? '' : 'disabled' }}" href="{{ $selectedPeriod ? route('teaching-schedules.create', ['school_id' => $school->id, 'period_id' => $selectedPeriod->id]) : '#' }}">
                                        <i class="bx bx-plus-circle me-2 text-success"></i>Tambah Jadwal Mengajar
                                    </a>
                                </li>
                                @endif
                                @if($canImportSchedules)
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#importModal" @disabled(!$selectedPeriod)>
                                        <i class="bx bx-upload me-2 text-secondary"></i>Import Jadwal
                                    </button>
                                </li>
                                @endif
                                @if($canSeeSchoolClasses)
                                <li>
                                    <a class="dropdown-item {{ $selectedPeriod ? '' : 'disabled' }}" href="{{ $selectedPeriod ? route('teaching-schedules.school-classes', ['schoolId' => $school->id, 'period_id' => $selectedPeriod->id]) : '#' }}">
                                        <i class="bx bx-bar-chart-alt-2 me-2 text-info"></i>Lihat Rekap / Statistik Jadwal
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card period-card mb-0">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-6">
                    <div class="text-muted small mb-1">Periode Akademik</div>
                    <div class="summary-title">
                        {{ $selectedPeriod ? $selectedPeriod->summary_label : 'Pilih periode akademik untuk memulai' }}
                    </div>
                    <div class="summary-meta mt-1">
                        {{ $selectedPeriod ? 'Masa berlaku ' . $selectedPeriod->date_range_label : 'Data jadwal tidak langsung ditampilkan agar halaman tetap ringan dan fokus.' }}
                    </div>
                </div>
                <div class="col-lg-6">
                    <label for="periodSwitcher" class="form-label text-muted small mb-1">Pilih Periode</label>
                    <select id="periodSwitcher" class="form-select period-picker">
                        <option value="">Pilih periode akademik</option>
                        @foreach($periods as $period)
                        <option value="{{ $period->id }}" @selected(optional($selectedPeriod)->id === $period->id)>
                            {{ $period->summary_label }} | {{ $period->date_range_label }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="period-grid mt-4">
                @forelse($periods as $period)
                <button
                    type="button"
                    class="period-tile period-tile-button {{ optional($selectedPeriod)->id === $period->id ? 'active' : '' }}"
                    data-period-id="{{ $period->id }}"
                >
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="period-tile-title">{{ $period->title }}</div>
                            <div class="period-tile-meta mt-1">{{ $period->semester_label }} | {{ $period->school_year }}</div>
                        </div>
                        @if(optional($selectedPeriod)->id === $period->id)
                        <span class="badge bg-primary">Aktif</span>
                        @endif
                    </div>
                    <div class="period-tile-meta mt-3">
                        <i class="bx bx-calendar-event me-1"></i>{{ $period->date_range_label }}
                    </div>
                </button>
                @empty
                <div class="text-muted small">
                    Belum ada periode jadwal mengajar untuk sekolah ini. Gunakan menu aksi untuk menambahkan periode terlebih dahulu.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    @if(!$selectedPeriod)
    <div class="card selection-card mb-0">
        <div class="card-body text-center py-5 px-4">
            <div class="selection-icon">
                <i class="bx bx-calendar-star"></i>
            </div>
            <h4 class="mb-2">Pilih Periode Akademik</h4>
            <p class="text-muted mx-auto mb-4" style="max-width: 640px;">
                Pilih periode untuk melihat atau mengelola jadwal mengajar. Setelah periode dipilih, halaman akan menampilkan seluruh jadwal, ringkasan guru, mata pelajaran, kelas, dan total jadwal pada periode tersebut.
            </p>

            @if($periods->isNotEmpty())
            <div class="period-grid text-start">
                @foreach($periods as $period)
                <button
                    type="button"
                    class="period-tile period-tile-button"
                    data-period-id="{{ $period->id }}"
                >
                    <div class="period-tile-title">{{ $period->title }}</div>
                    <div class="period-tile-meta mt-1">{{ $period->semester_label }} | {{ $period->school_year }}</div>
                    <div class="period-tile-meta mt-3">
                        <i class="bx bx-time-five me-1"></i>{{ $period->date_range_label }}
                    </div>
                </button>
                @endforeach
            </div>
            @else
            <div class="empty-card border-0 shadow-none bg-transparent">
                <div class="card-body p-0">
                    <p class="text-muted mb-3">Belum ada periode yang tersedia.</p>
                    @if($canManagePeriods)
                    <button type="button" class="btn btn-primary btn-pill px-4" data-bs-toggle="modal" data-bs-target="#createPeriodModal">
                        <i class="bx bx-calendar-plus me-1"></i>Tambah Periode Pertama
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="card summary-panel mb-0">
        <div class="card-body p-4">
            <div class="row g-3 align-items-start">
                <div class="col-lg-8">
                    <div class="text-muted small mb-1">Periode Terpilih</div>
                    <div class="summary-title">{{ $selectedPeriod->summary_label }}</div>
                    <div class="summary-meta mt-1">
                        Berlaku {{ $selectedPeriod->date_range_label }}
                    </div>
                </div>
                @if($canManagePeriods)
                <div class="col-lg-4 text-lg-end">
                    <button type="button" class="btn btn-outline-warning btn-pill px-3" data-bs-toggle="modal" data-bs-target="#editPeriodModal">
                        <i class="bx bx-edit me-1"></i>Edit Periode
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6 col-xl-3">
            <div class="card stats-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon bg-primary-subtle text-primary">
                        <i class="bx bx-group"></i>
                    </div>
                    <div>
                        <div class="stats-label">Jumlah Guru</div>
                        <div class="stats-value">{{ $totalTeachers }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stats-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon bg-success-subtle text-success">
                        <i class="bx bx-book"></i>
                    </div>
                    <div>
                        <div class="stats-label">Jumlah Mata Pelajaran</div>
                        <div class="stats-value">{{ $totalSubjects }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stats-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon bg-info-subtle text-info">
                        <i class="bx bx-home-circle"></i>
                    </div>
                    <div>
                        <div class="stats-label">Jumlah Kelas</div>
                        <div class="stats-value">{{ $totalClasses }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card stats-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stats-icon bg-warning-subtle text-warning">
                        <i class="bx bx-calendar-week"></i>
                    </div>
                    <div>
                        <div class="stats-label">Total Jadwal</div>
                        <div class="stats-value">{{ $totalSchedules }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($allSchedules->isEmpty())
    <div class="card empty-card mb-0">
        <div class="card-body text-center py-5">
            <div class="selection-icon mb-3">
                <i class="bx bx-notepad"></i>
            </div>
            <h5 class="mb-2">Belum ada jadwal pada periode ini</h5>
            <p class="text-muted mb-0">Periode sudah dipilih, tetapi jadwal mengajar belum tersedia. Gunakan menu aksi untuk menambahkan atau mengimpor jadwal.</p>
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
                                            <div class="schedule-subject">{{ $schedule->subject }}</div>
                                            <span class="schedule-meta schedule-meta-time">
                                                <i class="bx bx-time-five"></i>{{ $schedule->start_time }} - {{ $schedule->end_time }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="schedule-chip">
                                                <i class="bx bx-group"></i>{{ $schedule->class_name }}
                                            </span>
                                        </div>
                                        @if($canCreateSchedules)
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('teaching-schedules.edit', $schedule->id) }}" class="btn btn-outline-primary btn-sm btn-pill px-3">
                                                <i class="bx bx-edit me-1"></i>Edit
                                            </a>
                                            <button class="btn btn-outline-danger btn-sm btn-pill px-3 delete-btn" data-id="{{ $schedule->id }}" data-name="{{ $schedule->subject }} - {{ $schedule->class_name }}">
                                                <i class="bx bx-trash me-1"></i>Hapus
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
    @endif
</div>

@if($canManagePeriods)
<div class="modal fade" id="createPeriodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('teaching-schedule-periods.store') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="school_id" value="{{ $school->id }}">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Periode Jadwal Mengajar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', 'Jadwal Mengajar') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="ganjil" @selected(old('semester') === 'ganjil')>Semester Ganjil</option>
                            <option value="genap" @selected(old('semester') === 'genap')>Semester Genap</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Pelajaran</label>
                        <input type="text" name="school_year" class="form-control" placeholder="2026/2027" value="{{ old('school_year') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mulai Berlaku</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Selesai Berlaku</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Periode</button>
            </div>
        </form>
    </div>
</div>

@if($selectedPeriod)
<div class="modal fade" id="editPeriodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('teaching-schedule-periods.update', $selectedPeriod->id) }}" method="POST" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Periode Jadwal Mengajar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Judul</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $selectedPeriod->title) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="ganjil" @selected(old('semester', $selectedPeriod->semester) === 'ganjil')>Semester Ganjil</option>
                            <option value="genap" @selected(old('semester', $selectedPeriod->semester) === 'genap')>Semester Genap</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Pelajaran</label>
                        <input type="text" name="school_year" class="form-control" value="{{ old('school_year', $selectedPeriod->school_year) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mulai Berlaku</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($selectedPeriod->start_date)->toDateString()) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Selesai Berlaku</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($selectedPeriod->end_date)->toDateString()) }}" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Update Periode</button>
            </div>
        </form>
    </div>
</div>
@endif
@endif

@if($canImportSchedules)
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
                    <ul class="mb-0">
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
                                <li>Unduh template file Excel dengan mengklik tombol "Unduh Template".</li>
                                <li>Isi data jadwal sesuai format pada template.</li>
                                <li>Simpan kembali file dalam format Excel.</li>
                                <li>Upload file ke periode yang sedang dipilih.</li>
                            </ol>
                        </div>

                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Kolom</th>
                                    <th>Tipe</th>
                                    <th>Keterangan</th>
                                    <th>Wajib</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>school_scod</code></td>
                                    <td>Angka</td>
                                    <td>Kode SCOD madrasah</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>teacher_nuist_id</code></td>
                                    <td>Angka</td>
                                    <td>NUist ID guru</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>day</code></td>
                                    <td>Teks</td>
                                    <td>Senin sampai Sabtu</td>
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
                                    <td>Nama kelas</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>start_time</code></td>
                                    <td>Jam</td>
                                    <td>Format HH:MM</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                                <tr>
                                    <td><code>end_time</code></td>
                                    <td>Jam</td>
                                    <td>Format HH:MM</td>
                                    <td><span class="badge bg-danger">Ya</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="bx bx-upload"></i> Upload File</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('teaching-schedules.process-import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                                    <input type="hidden" name="teaching_schedule_period_id" value="{{ optional($selectedPeriod)->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">Periode Tujuan</label>
                                        <input type="text" class="form-control" value="{{ $selectedPeriod ? $selectedPeriod->summary_label . ' | ' . $selectedPeriod->date_range_label : 'Pilih periode terlebih dahulu' }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label for="file" class="form-label">Pilih File Import</label>
                                        <input type="file" class="form-control" id="file" name="file" accept=".csv,.xlsx,.xls" required>
                                        <div class="form-text">Format: CSV, Excel (.xlsx, .xls) - Maksimal 10MB</div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary" @disabled(!$selectedPeriod)>
                                            <i class="bx bx-upload"></i> Import Data
                                        </button>
                                        <a href="{{ asset('template/teaching_schedule_import_template.xlsx') }}" class="btn btn-outline-secondary" download>
                                            <i class="bx bx-download"></i> Unduh Template
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('script-bottom')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<script>
$(document).ready(function() {
    const baseUrl = @json($baseScheduleUrl);

    function goToPeriod(periodId) {
        if (!periodId) {
            window.location.href = baseUrl;
            return;
        }

        const targetUrl = new URL(baseUrl, window.location.origin);
        targetUrl.searchParams.set('period_id', periodId);
        window.location.href = targetUrl.toString();
    }

    $('#periodSwitcher').on('change', function() {
        goToPeriod(this.value);
    });

    $('.period-tile-button').on('click', function() {
        goToPeriod($(this).data('period-id'));
    });

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: @json(session('error')),
        confirmButtonText: 'Tutup'
    });
    @endif

    @if(session('import_errors'))
    Swal.fire({
        icon: 'error',
        title: 'Import Gagal',
        html: '<div style="text-align:left;">' + @json(collect(session('import_errors'))->implode('<br>')) + '</div>',
        confirmButtonText: 'Tutup'
    });
    @endif

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: @json(session('success')),
        confirmButtonText: 'OK'
    });
    @endif

    $('.delete-btn').on('click', function() {
        var scheduleId = $(this).data('id');
        var scheduleName = $(this).data('name');

        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Jadwal "' + scheduleName + '" akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
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
