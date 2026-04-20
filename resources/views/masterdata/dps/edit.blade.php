@extends('layouts.master')

@section('title')
    Edit DPS
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Edit DPS @endslot
@endcomponent

<div class="card mb-4">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">Validasi gagal:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <div class="fw-semibold">{{ $member->madrasah->name ?? '-' }}</div>
            <div class="text-muted small">SCOD: {{ $member->madrasah->scod ?? '-' }}</div>
        </div>

        <form method="POST" action="{{ route('dps.update', $member->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-lg-4">
                    <label class="form-label">Nama DPS</label>
                    <input type="text" name="nama" value="{{ old('nama', $member->nama) }}" class="form-control" required>
                </div>
                <div class="col-lg-5">
                    <label class="form-label">Unsur DPS</label>
                    <input type="text" name="unsur" value="{{ old('unsur', $member->unsur) }}" class="form-control" required>
                </div>
                <div class="col-lg-3">
                    <label class="form-label">Periode</label>
                    <input type="text" name="periode" value="{{ old('periode', $member->periode) }}" class="form-control" required>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end mt-4">
                <a href="{{ route('dps.show', $member->madrasah_id) }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

