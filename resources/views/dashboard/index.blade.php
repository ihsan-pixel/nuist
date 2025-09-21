{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.master')

@section('title') Dasbor @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Dashboards @endslot
    @slot('title') Dashboard @endslot
@endcomponent

<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-success-subtle">
                <div class="row">
                    <div class="col-7">
                        <div class="text-success p-3">
                            <h5 class="text-success">Selamat Datang!</h5>
                            <p>Aplikasi NUIST</p>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        {{-- <img src="{{ URL::asset('build/images/logo 1.png') }}" alt="" class="img-fluid"> --}}
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
<img src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                        <p class="text-muted mb-0 text-truncate">Nuist ID : {{ Auth::user()->nuist_id ?? '-' }}</p>
                    </div>
                    <div class="col-sm-8">
                        {{-- <div class="pt-4"> --}}
                            {{-- <div class="row">
                                <div class="col-6">
                                    <h5 class="font-size-15">{{ Auth::user()->status_kepegawaian ?? '-' }}</h5>
                                    <p class="text-muted mb-0">Status Kepegawaian</p>
                                </div>
                                <div class="col-6">
                                    <h5 class="font-size-15">{{ Auth::user()->masa_kerja ?? '-' }}</h5>
                                    <p class="text-muted mb-0">Masa Kerja</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="" class="btn btn-success waves-effect waves-light btn-sm">Lihat Profil <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div> --}}
                        {{-- </div> --}}
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

        @if(Auth::user()->role === 'tenaga_pendidik')
        {{-- Keaktifan --}}
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Keaktifan</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="text-muted">Bulan ini</p>
                        <h3>{{ round($attendanceData['kehadiran'] ?? 0) }}%</h3>
                        <p class="text-muted">
                            <span class="text-success me-2"> {{ round($attendanceData['kehadiran'] ?? 0) }}% <i class="mdi mdi-arrow-up"></i> </span> Kehadiran
                        </p>
                        <div class="row mt-3">
                            {{-- <div class="col-6">
                                <small class="text-muted">Hari Kerja</small>
                                <h6>{{ $attendanceData['total_hari_kerja'] ?? 0 }}</h6>
                            </div> --}}
                            <div class="col-6">
                                <small class="text-muted">Total Presensi</small>
                                <h6>{{ $attendanceData['total_presensi'] ?? 0 }}</h6>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('presensi.index') }}" class="btn btn-success waves-effect waves-light btn-sm">Lihat Detail <i class="mdi mdi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mt-4 mt-sm-0">
                            <div id="donut-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts"></div>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-0">Persentase kehadiran berdasarkan hari kerja (Senin-Sabtu, exclude hari libur).</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Admin Statistics Section - Right side --}}
    @if(Auth::user()->role === 'admin' && isset($adminStats))
    <div class="col-xl-8">
        <div class="row">
            {{-- Total Teachers Card --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="mdi mdi-account-school fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $adminStats['total_teachers'] }}</h5>
                                <p class="text-muted mb-0">Total Tenaga Pendidik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Madrasah Info Card --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                    <i class="mdi mdi-school fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Auth::user()->madrasah ? Auth::user()->madrasah->name : 'N/A' }}</h6>
                                <p class="text-muted mb-0">Madrasah Saat Ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Employment Status Breakdown --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Ringkasan Berdasarkan Status Kepegawaian</h5>
                <div class="row">
                    @if($adminStats['total_by_status']->count() > 0)
                        @foreach($adminStats['total_by_status'] as $status)
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="mdi mdi-account-tie fs-5"></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-2">{{ $status['count'] }}</h6>
                                    <p class="text-muted mb-0">{{ $status['status_name'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="mdi mdi-information-outline text-muted fs-1"></i>
                                <p class="text-muted mt-2">Belum ada data status kepegawaian</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detailed Statistics Table --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Detail Statistik Tenaga Pendidik</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Status Kepegawaian</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($adminStats['total_by_status']->count() > 0)
                                @foreach($adminStats['total_by_status'] as $status)
                                <tr>
                                    <td>{{ $status['status_name'] }}</td>
                                    <td>{{ $status['count'] }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100) : 0 }}%"
                                                 aria-valuenow="{{ $status['count'] }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="{{ $adminStats['total_teachers'] }}">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            {{ $adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100, 1) : 0 }}%
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                                <tr class="table-info">
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{ $adminStats['total_teachers'] }}</strong></td>
                                    <td><strong>100%</strong></td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="mdi mdi-information-outline text-muted fs-4"></i>
                                        <p class="text-muted mt-2">Belum ada data untuk ditampilkan</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!in_array(Auth::user()->role, ['admin', 'super_admin']))
    <div class="col-xl-8">
        {{-- Tambah kartu info detail user di sebelah kanan --}}
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi User</h5>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <small class="text-muted">Asal Madrasah/Sekolah :</small>
                        <h6>{{ Auth::user()->madrasah ? Auth::user()->madrasah->name : '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tempat Lahir</small>
                        <h6>{{ Auth::user()->tempat_lahir ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tanggal Lahir</small>
                        <h6>{{ Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('d F Y') : '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">TMT</small>
                        <h6>{{ Auth::user()->tmt ? \Carbon\Carbon::parse(Auth::user()->tmt)->format('d F Y') : '-' }}</h6>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <small class="text-muted">NUPTK</small>
                        <h6>{{ Auth::user()->nuptk ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">NPK</small>
                        <h6>{{ Auth::user()->npk ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Kartanu</small>
                        <h6>{{ Auth::user()->kartanu ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">NIP Ma'arif</small>
                        <h6>{{ Auth::user()->nip_maarif ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-success">Status Kepegawaian</small>
                        <h6>{{ Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-success">Ketugasan</small>
                        <h6>{{ Auth::user()->ketugasan ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-info">Pendidikan Terakhir, Tahun Lulus</small>
                        <h6>{{ Auth::user()->pendidikan_terakhir ?? '-' }}</h6>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted text-warning">Program Studi</small>
                        <h6>{{ Auth::user()->program_studi ?? '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel daftar users --}}
        @if($showUsers)
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Rekan Guru/Pegawai Se-Madrasah/Sekolah</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Ketugasan</th>
                                <th>Status Kepegawaian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>
<img src="{{ isset($user->avatar) ? asset('storage/' . $user->avatar) : asset('build/images/users/avatar-1.jpg') }}" alt="Foto {{ $user->name }}" class="rounded-circle" width="40" height="40">
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->ketugasan ?? '-' }}</td>
                                <td>{{ $user->statusKepegawaian ? $user->statusKepegawaian->name : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
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
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- Leaflet CSS and JS for map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var attendanceData = @json($attendanceData ?? ['kehadiran' => 0, 'izin_sakit' => 0, 'alpha' => 0]);

        var options = {
            chart: {
                height: 350,
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
    });
</script>
@endsection
