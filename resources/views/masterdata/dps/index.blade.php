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

            <a href="{{ route('dps.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah DPS
            </a>
        </div>

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
@endsection

