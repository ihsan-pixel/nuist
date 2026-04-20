@extends('layouts.master')

@section('title')
    DPS (Dewan Pengawas Sekolah)
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') DPS (Dewan Pengawas Sekolah) @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <form method="GET" action="{{ route('dps.index') }}" class="d-flex gap-2" style="max-width:520px; width:100%;">
                <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari SCOD / Nama Sekolah / Nama DPS / Unsur / Periode">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bx bx-search"></i>
                </button>
            </form>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalImportDps">
                    <i class="bx bx-upload"></i> Import Excel
                </button>
                <a href="{{ route('dps.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah DPS
                </a>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('dps_credentials_token'))
            <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2" role="alert">
                <div>
                    <div class="fw-semibold">File akun DPS hasil import tersedia.</div>
                    <div class="small text-muted">Berisi email dan password untuk baris yang berhasil membuat akun baru.</div>
                </div>
                <a class="btn btn-sm btn-outline-dark" href="{{ route('dps.credentials.download', session('dps_credentials_token')) }}">
                    <i class="bx bx-download"></i> Download CSV Akun
                </a>
            </div>
        @endif

        @if(session('dps_import_errors'))
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">Sebagian baris gagal diproses (maks 30 ditampilkan):</div>
                <ul class="mb-0">
                    @foreach(session('dps_import_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 110px;">SCOD</th>
                        <th>Nama Sekolah</th>
                        <th>Daftar DPS</th>
                        <th>Unsur DPS</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($madrasahs as $madrasah)
                        <tr>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $madrasah->scod ?? '-' }}</span></td>
                            <td class="fw-semibold">{{ $madrasah->name ?? '-' }}</td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($madrasah->dpsMembers as $m)
                                        <li class="mb-2">
                                            <div>{{ $m->nama }}</div>
                                            <div class="text-muted small">Periode: {{ $m->periode }}</div>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0">
                                    @foreach($madrasah->dpsMembers as $m)
                                        <li class="mb-2">{{ $m->unsur }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <a href="{{ route('dps.show', $madrasah->id) }}" class="btn btn-sm btn-outline-primary">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data DPS.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end">
            {{ $madrasahs->links() }}
        </div>
    </div>
</div>

<!-- Modal Import DPS -->
<div class="modal fade" id="modalImportDps" tabindex="-1" aria-labelledby="modalImportDpsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('dps.import') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="modalImportDpsLabel">Import DPS (Excel)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">File Excel (.xlsx / .xls / .csv)</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
                <div class="alert alert-info mb-0">
                    <div class="fw-semibold mb-1">Format kolom (heading):</div>
                    <div class="small">
                        <code>scod</code>, <code>nama_dps</code>, <code>unsur_dps</code>, <code>periode</code>
                    </div>
                    <div class="small mt-2">
                        Nilai <code>unsur_dps</code> wajib salah satu: Pengurus LP. Ma'arif NU PWNU DIY, Akademisi, Pendiri Sekolah, Tokoh Masyarakat.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="bx bx-upload"></i> Import
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
