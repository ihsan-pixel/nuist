@extends('layouts.mobile')

@section('title', 'Dashboard Pengurus')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Pengurus</h4>
            </div>
        </div>
    </div>

    <!-- Banner -->
    @if($showBanner && $bannerImage)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <img src="{{ asset($bannerImage) }}" alt="Banner" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="text-muted fw-normal mb-1">Total Madrasah</h4>
                            <h2 class="mb-0">{{ $totalMadrasah }}</h2>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-school text-primary display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="text-muted fw-normal mb-1">Tenaga Pendidik</h4>
                            <h2 class="mb-0">{{ $totalTenagaPendidik }}</h2>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-account-group text-success display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="text-muted fw-normal mb-1">Total Pengurus</h4>
                            <h2 class="mb-0">{{ $totalPengurus }}</h2>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-account-tie text-info display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="text-muted fw-normal mb-1">Aktivitas</h4>
                            <h2 class="mb-0">{{ count($recentActivities) }}</h2>
                        </div>
                        <div class="col-6">
                            <div class="text-end">
                                <i class="mdi mdi-chart-line text-warning display-4 opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Aktivitas Terbaru</h4>
                    @if(count($recentActivities) > 0)
                        <div class="list-group">
                            @foreach($recentActivities as $activity)
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">{{ $activity['title'] ?? 'Aktivitas' }}</h5>
                                        <small>{{ $activity['date'] ?? now()->format('d M Y') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $activity['description'] ?? 'Deskripsi aktivitas' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada aktivitas terbaru.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
