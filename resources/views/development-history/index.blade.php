@extends('layouts.master')

@section('title') Riwayat Pengembangan @endsection

@section('css')
<style>
.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline:before {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 40px;
    width: 2px;
    margin-left: -1.5px;
    content: '';
    background-color: #e9ecef;
}

.timeline > li {
    position: relative;
    margin-bottom: 50px;
    min-height: 50px;
}

.timeline > li:before,
.timeline > li:after {
    content: ' ';
    display: table;
}

.timeline > li:after {
    clear: both;
}

.timeline > li .timeline-panel {
    position: relative;
    float: right;
    width: calc(100% - 90px);
    padding: 20px;
    border: 1px solid #d4edda;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
}

.timeline > li .timeline-panel:before {
    position: absolute;
    top: 26px;
    right: -15px;
    display: inline-block;
    border-top: 15px solid transparent;
    border-left: 15px solid #ccc;
    border-right: 0 solid #ccc;
    border-bottom: 15px solid transparent;
    content: ' ';
}

.timeline > li .timeline-panel:after {
    position: absolute;
    top: 27px;
    right: -14px;
    display: inline-block;
    border-top: 14px solid transparent;
    border-left: 14px solid #fff;
    border-right: 0 solid #fff;
    border-bottom: 14px solid transparent;
    content: ' ';
}

.timeline > li .timeline-badge {
    position: absolute;
    top: 16px;
    left: 28px;
    z-index: 100;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    text-align: center;
    font-size: 1.4em;
    line-height: 25px;
    color: #fff;
}

.timeline-title {
    margin-top: 0;
    color: inherit;
    font-size: 1.1em;
    font-weight: 600;
}

.timeline-body p {
    margin-bottom: 0;
}

.timeline-body p + p {
    margin-top: 5px;
}

.stats-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.filter-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
}

.filter-card .card-body {
    padding: 2rem;
}

@media (max-width: 767px) {
    .timeline > li .timeline-panel {
        width: calc(100% - 70px);
    }

    .timeline:before {
        left: 30px;
    }

    .timeline > li .timeline-badge {
        left: 18px;
    }
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Riwayat Pengembangan @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-history me-2"></i>
                    Riwayat Pengembangan Aplikasi
                </h4>
                <p class="text-white-50 mb-0">
                    Timeline lengkap perkembangan dan update aplikasi dari awal instalasi hingga sekarang
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary rounded-circle fs-3">
                            <i class="bx bx-data"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total</p>
                        <h5 class="mb-0">{{ $stats['total'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded-circle fs-3">
                            <i class="bx bx-plus-circle"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Fitur</p>
                        <h5 class="mb-0">{{ $stats['features'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-info rounded-circle fs-3">
                            <i class="bx bx-refresh"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Update</p>
                        <h5 class="mb-0">{{ $stats['updates'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-warning rounded-circle fs-3">
                            <i class="bx bx-wrench"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Bug Fix</p>
                        <h5 class="mb-0">{{ $stats['bugfixes'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-secondary rounded-circle fs-3">
                            <i class="bx bx-trending-up"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Enhancement</p>
                        <h5 class="mb-0">{{ $stats['enhancements'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary rounded-circle fs-3">
                            <i class="bx bx-database"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Migration</p>
                        <h5 class="mb-0">{{ $stats['migrations'] ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('development-history.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="type" class="form-label">Tipe</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Semua Tipe</option>
                                @foreach($types as $key => $label)
                                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('development-history.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sync Button -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-end">
            <a href="{{ route('development-history.sync') }}" class="btn btn-success" onclick="return confirm('Sinkronisasi file migration dengan riwayat pengembangan?')">
                <i class="bx bx-sync me-1"></i> Sinkronisasi Migration
            </a>
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($histories->count() > 0)
                    <ul class="timeline">
                        @foreach($histories as $history)
                        <li>
                            <div class="timeline-badge {{ $history->getTypeBadgeClass() }}">
                                <i class="bx {{ $history->getTypeIcon() }}"></i>
                            </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4 class="timeline-title">{{ $history->title }}</h4>
                                    <p class="text-muted">
                                        <small>
                                            <i class="bx bx-calendar me-1"></i>
                                            {{ $history->formatted_date }}
                                            <span class="text-primary ms-2">({{ $history->relative_date }})</span>
                                            @if($history->version)
                                                <span class="badge bg-info ms-2">v{{ $history->version }}</span>
                                            @endif
                                            <span class="badge {{ $history->getTypeBadgeClass() }} ms-2">
                                                {{ $types[$history->type] ?? $history->type }}
                                            </span>
                                        </small>
                                    </p>
                                </div>
                                <div class="timeline-body">
                                    <p>{{ $history->description }}</p>
                                    @if($history->migration_file)
                                        <p class="text-muted mb-0">
                                            <small>
                                                <i class="bx bx-file me-1"></i>
                                                Migration: {{ $history->migration_file }}
                                            </small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $histories->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-light text-primary rounded-circle fs-1">
                                <i class="bx bx-history"></i>
                            </div>
                        </div>
                        <h5>Belum Ada Riwayat Pengembangan</h5>
                        <p class="text-muted">Klik tombol "Sinkronisasi Migration" untuk memuat riwayat dari file migration.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    // Auto-submit form when filter changes
    document.getElementById('type').addEventListener('change', function() {
        this.form.submit();
    });
</script>
@endsection

