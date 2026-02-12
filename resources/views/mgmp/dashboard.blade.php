{{-- resources/views/mgmp/dashboard.blade.php --}}
@extends('layouts.master')

@section('title') Dashboard MGMP - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') MGMP @endslot
    @slot('title') Dashboard MGMP @endslot
@endcomponent
<div class="row">
    <div class="col-lg-4 col-12">
        <!-- Profile / Welcome Card -->
        <div class="card border-0 shadow-sm hover-lift mb-3" style="border-radius: 12px; overflow: hidden;">
            <div class="p-4" style="background: linear-gradient(135deg, #0e8549 0%, #0b6b4d 100%); color: #fff;">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="avatar-lg profile-user-wid mb-0">
                            <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle" style="margin-top:20px;">
                                <i class="mdi mdi-school fs-2"></i>
                            </div>
                        </div>
                    </div>
                    <div class="grow">
                        <h5 class="mb-1 text-white">{{ Str::title(Auth::user()->name ?? 'MGMP User') }}</h5>
                        <p class="mb-1 text-white-50 small">MGMP NUIST • {{ Auth::user()->email ?? '-' }}</p>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <small class="badge bg-white bg-opacity-10 text-white">Role: {{ Str::ucfirst(Auth::user()->role ?? 'mgmp') }}</small>
                            <small class="badge bg-white bg-opacity-10 text-white">ID: {{ Auth::user()->nuist_id ?? '-' }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <p class="text-muted small mb-2">Ringkasan cepat MGMP Anda</p>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded text-center">
                            <div class="text-muted small">Anggota</div>
                            <div class="h5 mb-0">{{ $totalAnggota ?? 0 }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 bg-light rounded text-center">
                            <div class="text-muted small">Kegiatan</div>
                            <div class="h5 mb-0">{{ $totalKegiatan ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-12">
        <!-- Top statistic cards -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                <i class="mdi mdi-account-group fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Total Anggota</div>
                            <div class="h5 mb-0">{{ $totalAnggota ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                <i class="mdi mdi-calendar-check fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Kegiatan</div>
                            <div class="h5 mb-0">{{ $totalKegiatan ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <div class="d-flex align-items-center">
                        <div class="avatar-md me-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="mdi mdi-file-document fs-4"></i>
                            </div>
                        </div>
                        <div>
                            <div class="text-muted small">Laporan</div>
                            <div class="h5 mb-0">{{ $totalLaporan ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Aktivitas Terbaru</h6>
                            <small class="text-muted">{{ isset($recentActivities) ? $recentActivities->count() : 0 }} items</small>
                        </div>
                        @if(isset($recentActivities) && $recentActivities->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentActivities as $activity)
                                <div class="list-group-item px-0 py-2 border-0">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-light text-primary rounded-circle">
                                                    <i class="mdi mdi-calendar-check text-primary fs-5"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grow">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-1">{{ $activity->title }}</h6>
                                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="text-muted small mb-0">{{ Str::limit($activity->description, 120) }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-2">
                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                        <i class="mdi mdi-information-outline fs-1"></i>
                                    </div>
                                </div>
                                <p class="text-muted small mb-0">Belum ada aktivitas. Buat laporan atau kegiatan untuk mengisi daftar ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0">Laporan Terbaru</h6>
                            <a href="{{ route('mgmp.laporan') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        </div>
                        @if(isset($recentReports) && $recentReports->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Kegiatan</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentReports as $i => $r)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ Str::limit($r->name ?? $r->title ?? '-', 40) }}</td>
                                            <td>{{ isset($r->tanggal) ? \Carbon\Carbon::parse($r->tanggal)->format('d M Y') : '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted small mb-0">Belum ada laporan terbaru.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
@endsection
