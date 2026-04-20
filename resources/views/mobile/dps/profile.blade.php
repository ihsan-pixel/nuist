@extends('layouts.mobile')

@section('title', 'Profile DPS')

@section('content')
<header class="mobile-header d-md-none mb-3">
    <div class="d-flex align-items-center justify-content-between px-2 py-2">
        <div>
            <div class="fw-semibold">Profile DPS</div>
            <div class="text-muted small">{{ $madrasah->name }} (SCOD: {{ $madrasah->scod ?? '-' }})</div>
        </div>
        <a class="btn btn-sm btn-outline-secondary" href="{{ route('mobile.dps.dashboard') }}">
            <i class="bx bx-home"></i>
        </a>
    </div>
</header>

<div class="card mb-3">
    <div class="card-body">
        <div class="fw-semibold">{{ $user->name }}</div>
        <div class="text-muted small">{{ $user->email }}</div>
        <div class="mt-2">
            <span class="badge bg-primary-subtle text-primary">role: dps</span>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="fw-semibold mb-2">Sekolah</div>
        <div class="text-muted small">SCOD</div>
        <div class="fw-semibold mb-2">{{ $madrasah->scod ?? '-' }}</div>

        <div class="text-muted small">Nama Sekolah</div>
        <div class="fw-semibold mb-2">{{ $madrasah->name ?? '-' }}</div>

        <div class="text-muted small">Alamat</div>
        <div class="small">{{ $madrasah->alamat ?? '-' }}</div>
    </div>
</div>

<div class="d-grid gap-2">
    <a class="btn btn-outline-secondary" href="{{ route('dashboard') }}">
        <i class="bx bx-desktop me-1"></i> Masuk Mode Desktop
    </a>
    <a class="btn btn-outline-danger" href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bx bx-log-out me-1"></i> Logout
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</div>
@endsection

