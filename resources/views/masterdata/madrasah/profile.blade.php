@extends('layouts.master')

@section('title', 'Profile Madrasah/Sekolah')

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" type="text/css" />
<style>
    .profile-summary-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 1rem 2rem rgba(15, 23, 42, 0.08);
    }

    .profile-summary-card .progress {
        height: 0.55rem;
        background: #e9eef6;
    }

    .profile-summary-table th {
        white-space: nowrap;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .profile-summary-table td {
        vertical-align: middle;
    }

    .profile-metric {
        min-width: 160px;
    }

    .profile-rank-pill {
        width: 2rem;
        height: 2rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #1d4ed8;
        color: #fff;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Profile Madrasah/Sekolah @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-school me-2"></i>Profile Madrasah/Sekolah
                </h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form method="GET" action="{{ route('madrasah.profile') }}" class="mb-4">
                    <div class="row g-3">
                        <div class="col-xl-4 col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama madrasah..." value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <select name="yayasan_id" class="form-select">
                                <option value="">Pilih Yayasan</option>
                                @foreach($yayasans as $yayasan)
                                <option value="{{ $yayasan->id }}" {{ (int) ($yayasan_id ?? 0) === (int) $yayasan->id ? 'selected' : '' }}>
                                    {{ $yayasan->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <select name="kabupaten" class="form-select">
                                <option value="">Pilih Kabupaten</option>
                                <option value="Kabupaten Bantul" {{ ($kabupaten ?? '') == 'Kabupaten Bantul' ? 'selected' : '' }}>Kabupaten Bantul</option>
                                <option value="Kabupaten Gunungkidul" {{ ($kabupaten ?? '') == 'Kabupaten Gunungkidul' ? 'selected' : '' }}>Kabupaten Gunungkidul</option>
                                <option value="Kabupaten Kulon Progo" {{ ($kabupaten ?? '') == 'Kabupaten Kulon Progo' ? 'selected' : '' }}>Kabupaten Kulon Progo</option>
                                <option value="Kabupaten Sleman" {{ ($kabupaten ?? '') == 'Kabupaten Sleman' ? 'selected' : '' }}>Kabupaten Sleman</option>
                                <option value="Kota Yogyakarta" {{ ($kabupaten ?? '') == 'Kota Yogyakarta' ? 'selected' : '' }}>Kota Yogyakarta</option>
                            </select>
                        </div>
                        <div class="col-xl-2 col-md-6">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search-alt-2 me-1"></i>Cari
                                </button>
                                <a href="{{ route('madrasah.profile.export', request()->query()) }}" class="btn btn-success">
                                    <i class="bx bx-export me-1"></i>Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                @if($madrasahs->isEmpty())
                <div class="text-center p-4">
                    <div class="alert alert-info d-inline-block" role="alert">
                        <i class="bx bx-info-circle bx-lg me-2"></i>
                        <strong>Belum ada data madrasah</strong><br>
                        <small>Silakan tambahkan data madrasah terlebih dahulu melalui menu Master Data.</small>
                    </div>
                </div>
                @else
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <p class="text-muted text-uppercase fw-semibold small mb-2">Total Sekolah</p>
                                <h3 class="mb-1">{{ number_format($summaryStats['total_schools']) }}</h3>
                                <small class="text-muted">Sekolah sesuai filter aktif</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <p class="text-muted text-uppercase fw-semibold small mb-2">Rata-rata Kelengkapan</p>
                                <h3 class="mb-1">{{ $summaryStats['average_completion_percentage'] }}%</h3>
                                <small class="text-muted">Rerata dari guru, pegawai, total, SK, dan siswa</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <p class="text-muted text-uppercase fw-semibold small mb-2">Sekolah Lengkap</p>
                                <h3 class="mb-1">{{ number_format($summaryStats['fully_complete_schools']) }}</h3>
                                <small class="text-muted">Sudah 100% pada seluruh kolom kelengkapan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <p class="text-muted text-uppercase fw-semibold small mb-2">Sekolah Dengan Data Siswa</p>
                                <h3 class="mb-1">{{ number_format($summaryStats['schools_with_students']) }}</h3>
                                <small class="text-muted">Sudah memiliki data siswa terinput</small>
                            </div>
                        </div>
                    </div>
                </div>

                @if($topCompleteSchools->isNotEmpty())
                <div class="row g-3 mb-4">
                    @foreach($topCompleteSchools as $school)
                    <div class="col-xl-4">
                        <div class="card profile-summary-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge bg-primary-subtle text-primary mb-2">Peringkat #{{ $school['rank'] }}</span>
                                        <h5 class="mb-1">{{ $school['school_name'] }}</h5>
                                        <small class="text-muted">{{ $school['scod'] }} • {{ $school['kabupaten'] }}</small>
                                    </div>
                                    <span class="badge bg-success fs-6">{{ $school['overall_completion_percentage'] }}%</span>
                                </div>

                                <div class="progress mb-3">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $school['overall_completion_percentage'] }}%"></div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-light text-dark">Guru: {{ $school['total_teachers'] }} ({{ $school['total_teachers_percentage'] }}%)</span>
                                    <span class="badge bg-light text-dark">Pegawai: {{ $school['total_employees'] }} ({{ $school['total_employees_percentage'] }}%)</span>
                                    <span class="badge bg-light text-dark">SK: {{ $school['total_sk_submissions'] }} ({{ $school['total_sk_submissions_percentage'] }}%)</span>
                                    <span class="badge bg-light text-dark">Siswa: {{ $school['total_students'] }} ({{ $school['total_students_percentage'] }}%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-0">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                            <div>
                                <h5 class="mb-1">Ringkasan Kelengkapan Data Sekolah</h5>
                                <p class="text-muted mb-0">Diurutkan dari sekolah dengan data paling lengkap berdasarkan guru, pegawai, total guru + pegawai, pengajuan SK, dan data siswa.</p>
                            </div>
                            <span class="badge bg-info-subtle text-info">Setiap kolom angka menampilkan jumlah data dan persentase kelengkapannya</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle profile-summary-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>SCOD</th>
                                        <th>Nama Sekolah/Madrasah</th>
                                        <th>Jumlah Guru</th>
                                        <th>Jumlah Pegawai</th>
                                        <th>Total Guru + Pegawai</th>
                                        <th>Pengajuan SK</th>
                                        <th>Data Siswa</th>
                                        <th>Persentase</th>
                                        <th>Rank</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schoolSummaryRows as $row)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $row['scod'] }}</div>
                                            <small class="text-muted">{{ $row['kabupaten'] }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $row['school_name'] }}</div>
                                            <small class="text-muted">{{ $row['yayasan_name'] }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $row['total_teachers'] }}</div>
                                            <small class="text-muted">{{ $row['total_teachers_percentage'] }}%</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $row['total_employees'] }}</div>
                                            <small class="text-muted">{{ $row['total_employees_percentage'] }}%</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $row['total_teacher_employees'] }}</div>
                                            <small class="text-muted">{{ $row['total_teacher_employees_percentage'] }}%</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $row['total_sk_submissions'] }}</div>
                                            <small class="text-muted">{{ $row['total_sk_submissions_percentage'] }}%</small>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $row['total_students'] }}</div>
                                            <small class="text-muted">{{ $row['total_students_percentage'] }}%</small>
                                        </td>
                                        <td class="profile-metric">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span class="fw-semibold">{{ $row['overall_completion_percentage'] }}%</span>
                                                <span class="text-muted">{{ $row['filled_indicator_count'] }}/5</span>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $row['overall_completion_percentage'] }}%"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="profile-rank-pill">{{ $row['rank'] }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="mb-1">Akses Cepat Profile Sekolah</h5>
                        <p class="text-muted mb-0">Kartu di bawah tetap bisa dipakai untuk membuka detail profile masing-masing sekolah.</p>
                    </div>
                </div>

                <div class="row">
                    @forelse($madrasahs as $madrasah)
                    <div class="col-xxl-3 col-md-6">
                        <div class="card project-card" style="border: none; box-shadow: 0 0.75rem 1.5rem rgba(18,38,63,.03); border-radius: 0.75rem; overflow: hidden;">
                            @if($madrasah->logo)
                            <img src="{{ asset('storage/' . $madrasah->logo) }}" class="card-img-top" alt="{{ $madrasah->name }}" style="height: 200px; object-fit: cover;">
                            @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bx bx-school bx-lg text-muted"></i>
                            </div>
                            @endif
                            <div class="card-body p-4">
                                <h5 class="card-title fw-semibold mb-2">{{ $madrasah->name }}</h5>
                                <p class="card-text text-muted small mb-3">{{ Str::limit($madrasah->alamat ?? 'Alamat tidak tersedia', 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('madrasah.detail', $madrasah->id) }}" class="btn btn-success btn-sm rounded-pill px-3">
                                        <i class="bx bx-user me-1"></i>
                                        Lihat Profile
                                    </a>
                                    <span class="badge bg-light text-dark">{{ $madrasah->tenaga_pendidik_count }} tenaga pendidik</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Sukses',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif
</script>
@endsection
