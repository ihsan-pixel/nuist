@extends('layouts.master')

@section('title')
    DPS - {{ $madrasah->name ?? '-' }}
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') DPS - {{ $madrasah->scod ?? '-' }} ({{ $madrasah->name ?? '-' }}) @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
            <div>
                <div class="fw-semibold">{{ $madrasah->name }}</div>
                <div class="text-muted small">SCOD: {{ $madrasah->scod ?? '-' }}</div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('dps.create', ['madrasah_id' => $madrasah->id]) }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Anggota DPS
                </a>
                <a href="{{ route('dps.index') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
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
                        <th style="width: 52px;">No</th>
                        <th>Nama DPS</th>
                        <th>Unsur DPS</th>
                        <th style="width: 140px;">Periode</th>
                        <th style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($madrasah->dpsMembers as $idx => $m)
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td class="fw-semibold">{{ $m->nama }}</td>
                            <td>{{ $m->unsur }}</td>
                            <td>{{ $m->periode }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('dps.edit', $m->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('dps.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Yakin hapus anggota DPS ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada anggota DPS untuk sekolah ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

