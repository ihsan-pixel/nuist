{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.master')

@section('title') Dashboard - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Dashboards @endslot
    @slot('title') Dashboard @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <!-- Welcome Card - Mobile Optimized -->
        <div class="card overflow-hidden mb-3">
            <div class="bg-success-subtle">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="text-success p-3">
                            <h5 class="text-success">Selamat Datang!</h5>
                            <p class="mb-0">Aplikasi NUIST</p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        {{-- <img src="{{ asset('build/images/logo 1.png') }}" alt="" class="img-fluid" style="max-height: 60px;"> --}}
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg profile-user-wid mb-3 mb-md-0">
                            <img src="{{ isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="font-size-16 mb-1">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                        <p class="text-muted mb-2">Nuist ID : {{ Auth::user()->nuist_id ?? '-' }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <small class="badge bg-primary-subtle text-primary">{{ Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-' }}</small>
                            <small class="badge bg-info-subtle text-info">{{ Auth::user()->ketugasan ?? '-' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Madrasah Location and Map - Positioned below welcome card on left side --}}
        @if(Auth::user()->role === 'admin' && isset($madrasahData))
        <div class="row">
            {{-- Address Information --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map-marker text-primary me-2"></i>
                            Alamat Madrasah
                        </h5>
                        <div class="mb-3">
                            <h6 class="mb-2">{{ $madrasahData->name }}</h6>
                            <p class="text-muted mb-2">
                                <i class="mdi mdi-map-marker-outline me-1"></i>
                                {{ $madrasahData->alamat ?? 'Alamat belum diisi' }}
                            </p>
                            @if($madrasahData->map_link)
                            <a href="{{ $madrasahData->map_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Map Display --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map text-success me-2"></i>
                            Lokasi Madrasah
                        </h5>
                        <div id="map-container" style="height: 300px; border-radius: 8px; overflow: hidden;">
                            @if($madrasahData->latitude && $madrasahData->longitude)
                                <div id="map" style="height: 100%; width: 100%;"></div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light">
                                    <i class="mdi mdi-map-marker-off text-muted fs-1 mb-3"></i>
                                    <h6 class="text-muted">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small">
                                        Koordinat latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Foundation Location and Map for Super Admin and Pengurus --}}
        @if(in_array(Auth::user()->role, ['super_admin', 'pengurus']) && isset($foundationData))
        <div class="row">
            {{-- Address Information --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map-marker text-primary me-2"></i>
                            Alamat Yayasan
                        </h5>
                        <div class="mb-3">
                            <h6 class="mb-2">{{ $foundationData->name }}</h6>
                            <p class="text-muted mb-2">
                                <i class="mdi mdi-map-marker-outline me-1"></i>
                                {{ $foundationData->alamat ?? 'Alamat belum diisi' }}
                            </p>
                            @if($foundationData->map_link)
                            <a href="{{ $foundationData->map_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Map Display --}}
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map text-success me-2"></i>
                            Lokasi Yayasan
                        </h5>
                        <div id="foundation-map-container" style="height: 300px; border-radius: 8px; overflow: hidden;">
                            @if($foundationData->latitude && $foundationData->longitude)
                                <div id="foundation-map" style="height: 100%; width: 100%;"></div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light">
                                    <i class="mdi mdi-map-marker-off text-muted fs-1 mb-3"></i>
                                    <h6 class="text-muted">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small">
                                        Koordinat latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- User Information Card - Mobile Optimized -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="mdi mdi-account-details text-primary me-2"></i>
                    Informasi Personal
                </h5>

                <!-- Basic Info -->
                <div class="mb-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Asal Madrasah/Sekolah</small>
                                <strong class="text-truncate d-block">{{ Auth::user()->madrasah ? Auth::user()->madrasah->name : '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Tempat Lahir</small>
                                <strong>{{ Auth::user()->tempat_lahir ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Tanggal Lahir</small>
                                <strong>{{ Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('d F Y') : '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">TMT</small>
                                <strong>{{ Auth::user()->tmt ? \Carbon\Carbon::parse(Auth::user()->tmt)->format('d F Y') : '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="mb-3">
                    <h6 class="text-muted mb-3">Informasi Kepegawaian</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-primary-subtle rounded">
                                <small class="text-muted d-block">NUPTK</small>
                                <strong>{{ Auth::user()->nuptk ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-primary-subtle rounded">
                                <small class="text-muted d-block">NPK</small>
                                <strong>{{ Auth::user()->npk ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success-subtle rounded">
                                <small class="text-muted d-block">Kartanu</small>
                                <strong>{{ Auth::user()->kartanu ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success-subtle rounded">
                                <small class="text-muted d-block">NIP Ma'arif</small>
                                <strong>{{ Auth::user()->nip ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-info-subtle rounded">
                                <small class="text-muted d-block">Status Kepegawaian</small>
                                <strong>{{ Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-info-subtle rounded">
                                <small class="text-muted d-block">Ketugasan</small>
                                <strong>{{ Auth::user()->ketugasan ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-warning-subtle rounded">
                                <small class="text-muted d-block">Pendidikan Terakhir</small>
                                <strong>{{ Auth::user()->pendidikan_terakhir ?? '-' }}</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-warning-subtle rounded">
                                <small class="text-muted d-block">Program Studi</small>
                                <strong>{{ Auth::user()->program_studi ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Colleagues List - Mobile Optimized --}}
        @if($showUsers)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="mdi mdi-account-group text-info me-2"></i>
                    Rekan Guru/Pegawai Se-Madrasah/Sekolah
                </h5>

                <!-- Mobile-friendly list view -->
                <div class="list-group list-group-flush">
                    @foreach($users as $index => $user)
                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                                     alt="Foto {{ $user->name }}"
                                     class="rounded-circle"
                                     width="50"
                                     height="50">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $user->name }}</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <small class="badge bg-primary-subtle text-primary">{{ $user->ketugasan ?? '-' }}</small>
                                    <small class="badge bg-info-subtle text-info">{{ $user->statusKepegawaian ? $user->statusKepegawaian->name : '-' }}</small>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <small class="text-muted">{{ $users->firstItem() + $index }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Social Source, Activity, Top Cities --}}
{{-- <div class="row">
    <div class="col-xl-4">@include('dashboard.partials.social')</div>
    <div class="col-xl-4">@include('dashboard.partials.activity')</div>
    <div class="col-xl-4">@include('dashboard.partials.cities')</div>
</div> --}}

{{-- Latest Transaction --}}
{{-- <div class="row">
    <div class="col-lg-12">@include('dashboard.partials.transactions')</div>
</div> --}}

{{-- Modals --}}
{{-- @include('dashboard.partials.modals') --}}

@endsection

@section('script')
<!-- apexcharts -->
<script src="{{ asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- Leaflet CSS and JS for map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var attendanceData = @json($attendanceData ?? ['kehadiran' => 0, 'izin_sakit' => 0, 'alpha' => 0]);

        var options = {
            chart: {
                height: 200,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '16px',
                        },
                        value: {
                            fontSize: '14px',
                            formatter: function (val) {
                                return val + "%";
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function () {
                                return 100 + "%";
                            }
                        }
                    }
                }
            },
            colors: ['#198754', '#ffc107', '#dc3545'],
            series: [
                attendanceData.kehadiran,
                attendanceData.izin_sakit,
                attendanceData.alpha
            ],
            labels: ['Kehadiran', 'Izin/Sakit', 'Tidak Hadir'],
            legend: {
                position: 'bottom',
                formatter: function (val, opts) {
                    return val + " - " + opts.w.globals.series[opts.seriesIndex] + "%";
                }
            }
        };

        var chartElement = document.querySelector("#donut-chart");
        if (chartElement) {
            var chart = new ApexCharts(
                chartElement,
                options
            );

            chart.render();
        }

        // Initialize map if coordinates are available
        @if(isset($madrasahData) && $madrasahData->latitude && $madrasahData->longitude)
            var map = L.map('map').setView([{{ $madrasahData->latitude }}, {{ $madrasahData->longitude }}], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([{{ $madrasahData->latitude }}, {{ $madrasahData->longitude }}])
                .addTo(map)
                .bindPopup('<b>{{ $madrasahData->name }}</b><br>{{ $madrasahData->alamat ?? "Alamat tidak tersedia" }}')
                .openPopup();
        @endif

        // Initialize foundation map if coordinates are available
        @if(isset($foundationData) && $foundationData->latitude && $foundationData->longitude)
            var foundationMap = L.map('foundation-map').setView([{{ $foundationData->latitude }}, {{ $foundationData->longitude }}], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(foundationMap);

            var foundationMarker = L.marker([{{ $foundationData->latitude }}, {{ $foundationData->longitude }}])
                .addTo(foundationMap)
                .bindPopup('<b>{{ $foundationData->name }}</b><br>{{ $foundationData->alamat ?? "Alamat tidak tersedia" }}')
                .openPopup();
        @endif
    });
</script>
@endsection

