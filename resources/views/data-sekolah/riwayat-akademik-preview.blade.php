@extends('layouts.master')

@section('title')Pratinjau Jenjang Siswa @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Data Sekolah @endslot
    @slot('li_2') Data Siswa @endslot
    @slot('title') Inisialisasi Jenjang @endslot
@endcomponent

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="alert alert-info">
    <strong>Proses aman:</strong> halaman ini hanya membaca data siswa. Saat dikonfirmasi, sistem membuat snapshot
    riwayat akademik baru dan tidak mengubah kelas, jurusan, status, maupun identitas pada tabel siswa.
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('data-sekolah.data-siswa.academic-history.preview') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Tahun Ajaran</label>
                    <input name="tahun_ajaran" class="form-control" value="{{ $tahunAjaran }}" pattern="\d{4}/\d{4}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select">
                        <option value="1" {{ $semester === 1 ? 'selected' : '' }}>1 (Ganjil)</option>
                        <option value="2" {{ $semester === 2 ? 'selected' : '' }}>2 (Genap)</option>
                    </select>
                </div>
                @if($userRole === 'super_admin')
                    <div class="col-md-5">
                        <label class="form-label">Madrasah</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">Semua Madrasah</option>
                            @foreach($madrasahOptions as $madrasah)
                                <option value="{{ $madrasah->id }}" {{ (int) $selectedMadrasahId === (int) $madrasah->id ? 'selected' : '' }}>
                                    {{ $madrasah->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary"><i class="bx bx-search me-1"></i>Pratinjau</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="text-muted">Siap Dibuat</div><h3 class="mb-0">{{ number_format($rows->count()) }}</h3></div></div></div>
    <div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="text-muted">Dikenali Otomatis</div><h3 class="mb-0 text-success">{{ number_format($recognizedCount) }}</h3></div></div></div>
    <div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="text-muted">Perlu Verifikasi</div><h3 class="mb-0 text-warning">{{ number_format($reviewCount) }}</h3></div></div></div>
    <div class="col-md-3"><div class="card h-100"><div class="card-body"><div class="text-muted">Sudah Memiliki Riwayat</div><h3 class="mb-0">{{ number_format($alreadyRecorded) }}</h3></div></div></div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h5 class="mb-1">Hasil Pembacaan Kelas</h5>
                <div class="text-muted">{{ $tahunAjaran }} · Semester {{ $semester }}</div>
            </div>
            <a href="{{ route('data-sekolah.data-siswa.index') }}" class="btn btn-outline-secondary">Kembali ke Data Siswa</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light"><tr><th>No</th><th>Sekolah</th><th>Siswa</th><th>Kelas Asli</th><th>Jurusan</th><th>Jenjang</th><th>Tingkat</th><th>Hasil</th><th>Catatan</th></tr></thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row['siswa']->madrasah->name ?? $row['siswa']->nama_madrasah ?? '-' }}</td>
                            <td><strong>{{ $row['siswa']->nama_lengkap ?: '-' }}</strong><br><small class="text-muted">{{ $row['siswa']->nis ?: $row['siswa']->nisn }}</small></td>
                            <td>{{ $row['siswa']->kelas ?: '-' }}</td>
                            <td>{{ $row['siswa']->jurusan ?: '-' }}</td>
                            <td>{{ str_replace('_', '/', $row['jenjang_sekolah'] ?? '-') }}</td>
                            <td>{{ $row['tingkat'] ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $row['status'] === 'perlu_verifikasi' ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ str_replace('_', ' ', ucfirst($row['status'])) }}
                                </span>
                            </td>
                            <td>{{ $row['catatan'] ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada siswa baru untuk periode ini. Semua data pada cakupan terpilih sudah memiliki riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($userRole === 'super_admin' && $rows->isNotEmpty())
            <form method="POST" action="{{ route('data-sekolah.data-siswa.academic-history.store') }}" class="mt-3" onsubmit="return confirm('Buat snapshot riwayat akademik sesuai pratinjau? Data siswa lama tidak akan diubah.');">
                @csrf
                <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">
                <input type="hidden" name="semester" value="{{ $semester }}">
                @if($selectedMadrasahId)<input type="hidden" name="madrasah_id" value="{{ $selectedMadrasahId }}">@endif
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="confirmation" id="confirmation" required>
                    <label class="form-check-label" for="confirmation">Saya sudah memeriksa pratinjau dan memahami bahwa data ambigu diberi status perlu verifikasi.</label>
                </div>
                <button class="btn btn-success"><i class="bx bx-check-shield me-1"></i>Buat {{ number_format($rows->count()) }} Riwayat Akademik</button>
            </form>
        @elseif($userRole === 'admin')
            <div class="alert alert-secondary mb-0">Admin sekolah dapat memeriksa hasil. Pembuatan snapshot awal hanya dapat dikonfirmasi oleh super admin.</div>
        @endif
    </div>
</div>
@endsection
