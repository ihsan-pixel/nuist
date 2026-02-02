@extends('layouts.mobile-pengurus')

@section('title', 'Dashboard Pengurus')
@section('subtitle', 'Ringkasan Aktivitas')

@section('content')
<?php

date_default_timezone_set('Asia/Jakarta');

$b = time();
$hour = date('G', $b);

if ($hour >= 0 && $hour <= 11) {
    $congrat = 'Selamat Pagi';
} elseif ($hour >= 12 && $hour <= 14) {
    $congrat = 'Selamat Siang ';
} elseif ($hour >= 15 && $hour <= 17) {
    $congrat = 'Selamat Sore ';
} elseif ($hour >= 17 && $hour <= 18) {
    $congrat = 'Selamat Petang ';
} elseif ($hour >= 19 && $hour <= 23) {
    $congrat = 'Selamat Malam ';
}

?>
<header class="mobile-header d-md-none" style="position: sticky; top: 0; z-index: 1050;">
    <div class="container-fluid px-0 py-0" style="background: transparent;">
        <div class="d-flex align-items-center justify-content-between">
            <!-- User Avatar (Left) -->
            <div class="avatar-sm me-3 ms-3">
                <img
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                    class="avatar-img rounded-circle"
                    alt="User"
                >
            </div>

            <!-- Welcome Text (Right-aligned) -->
            <div class="text-start grow">
                <small class="text-dark fw-medium" style="font-size: 11px;">{{ $congrat }}</small>
                <h6 class="mb-0 fw-semibold text-dark" style="font-size: 14px;">{{ Auth::user()->name }}</h6>
            </div>

            <!-- Notification and Menu Buttons (Right) -->
            <div class="d-flex align-items-center">
                <!-- Notification Bell -->
                <a href="{{ route('mobile.notifications') }}" class="btn btn-link text-decoration-none p-0 me-2 position-relative">
                    <i class="bx bx-bell" style="font-size: 22px; color: #db3434;"></i>
                    <span id="notificationBadge" class="badge bg-danger rounded-pill position-absolute" style="font-size: 9px; padding: 2px 5px; top: -4px; right: -4px; display: none;">0</span>
                </a>

                <!-- Dropdown Menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-dots-vertical-rounded" style="font-size: 22px; color: #000000;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item py-2" href="{{ route('mobile.notifications') }}"><i class="bx bx-bell me-2"></i>Notifikasi</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2" href="{{ route('dashboard') }}"><i class="bx bx-home me-2"></i>Dashboard</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bx bx-log-out me-2"></i>Logout
                            </a>
                        </li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container py-3" style="max-width: 520px; margin: auto;">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(to bottom, rgba(248,249,251,0), #f8f9fb);
            z-index: -1;
        }

        .dashboard-header {
            background: #f8f9fb url('{{ asset("images/qwe1.png") }}') no-repeat center center;
            background-size: cover;
            border-radius: 14px;
            padding: 12px;
            color: #004b4c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
        }

        .stats-form {
            background: #fff;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176);
            margin-bottom: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        .stat-item {
            text-align: center;
            padding: 6px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .stat-item i {
            font-size: 18px;
        }

        .stat-item h6 {
            font-size: 12px;
            margin-bottom: 0;
            font-weight: 600;
        }

        .stat-item small {
            font-size: 9px;
            color: #6c757d;
        }

        .services-form {
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            min-height: 50px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            text-align: center;
        }

        .service-wrapper {
            text-align: center;
        }

        .service-item {
            position: relative;
            border-radius: 8px;
            padding: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
            height: 64px;
            width: 100%;
            box-sizing: border-box;
            border: 0px solid rgba(0,75,76,0.2);
            box-shadow: #000000;
        }

        .service-label {
            font-size: 10px;
            font-weight: 600;
            margin-top: 6px;
            color: #333;
        }

        .service-item i {
            font-size: 28px;
            color: #003d3d;
        }

        .service-item h6 {
            font-size: 10px;
            margin-bottom: 0;
            font-weight: 600;
            color: #ffffff;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        .performance-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 14px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,.15);
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .performance-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.2);
        }

        .performance-level {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .level-badge {
            font-size: 9px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 2px 6px;
            border-radius: 6px;
            font-weight: 600;
        }

        .performance-level strong {
            font-size: 10px;
            color: white;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: white;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 75%;
            background: #0e8549;
            border-radius: 999px;
            transition: width .4s ease;
        }

        .progress-text {
            text-align: center;
        }

        .progress-text strong {
            font-size: 14px;
            color: #fcffff;
        }

        .progress-text small {
            font-size: 9px;
            color: #ffffff;
        }

        .performance-progress {
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 8px;
        }

        .timeline-accordion {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding-left: 20px;
        }

        .timeline-accordion::before {
            content: '';
            position: absolute;
            left: 16px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.8), rgba(255,255,255,0.4));
            border-radius: 1px;
        }

        .timeline-item-accordion {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 6px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .timeline-item-accordion:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .timeline-item-accordion.done {
            background: #d4edda;
            border-color: #c3e6cb;
            border-left: 4px solid #28a745;
        }

        .timeline-item-accordion .timeline-icon {
            position: absolute;
            left: -20px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1;
            font-size: 12px;
        }

        .timeline-item-accordion.done .timeline-icon {
            background: #28a745;
            color: white;
        }

        .timeline-item-accordion .timeline-content {
            flex: 1;
        }

        .timeline-item-accordion .timeline-content strong {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 4px;
        }

        .timeline-item-accordion .timeline-content small {
            font-size: 11px;
            color: #6c757d;
        }

        .timeline-item-accordion.done .timeline-content strong {
            color: #155724;
        }

        .timeline-item-accordion.done .timeline-content small {
            color: #155724;
        }
    </style>

    <!-- Show banner modal on page load -->
    @if($showBanner)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var welcomeModal = new bootstrap.Modal(document.getElementById('welcomeBannerModal'), {
                backdrop: 'static',
                keyboard: false
            });
            welcomeModal.show();

            // Auto hide after 3 seconds
            setTimeout(function() {
                welcomeModal.hide();
            }, 3000);
        });
    </script>
    @endif

    <!-- Banner Modal -->
    @if($bannerImage)
    <div class="modal fade" id="welcomeBannerModal" tabindex="-1" aria-labelledby="welcomeBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="background: transparent;">
                <div class="modal-body p-0">
                    <div class="text-center">
                        <img src="{{ $bannerImage }}" alt="Welcome Banner" class="img-fluid rounded" style="max-height: 60vh; width: auto;">
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 bg-transparent">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Info Cards Section -->
    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="info-card text-center h-100" style="background: linear-gradient(135deg, #004b4c 0%, #006666 100%); border-radius: 10px; padding: 10px 6px; color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                <i class="bx bx-building fs-4 mb-1"></i>
                <div class="fw-bold" style="font-size: 18px;">{{ number_format($jumlahSekolah) }}</div>
                <small style="font-size: 9px;">Sekolah</small>
            </div>
        </div>
        <div class="col-4">
            <div class="info-card text-center h-100" style="background: linear-gradient(135deg, #0e8549 0%, #28a745 100%); border-radius: 10px; padding: 10px 6px; color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                <i class="bx bx-user-voice fs-4 mb-1"></i>
                <div class="fw-bold" style="font-size: 18px;">{{ number_format($jumlahTenagaPendidik) }}</div>
                <small style="font-size: 9px;">Tenaga Pendidik</small>
            </div>
        </div>
        <div class="col-4">
            <div class="info-card text-center h-100" style="background: linear-gradient(135deg, #6c5ce7 0%, #8c7ae6 100%); border-radius: 10px; padding: 10px 6px; color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                <i class="bx bx-group fs-4 mb-1"></i>
                <div class="fw-bold" style="font-size: 18px;">{{ number_format($jumlahSiswa) }}</div>
                <small style="font-size: 9px;">Siswa</small>
            </div>
        </div>
    </div>

    <!-- Chart Section: Tenaga Pendidik by Status Kepegawaian -->
    <div class="mb-3">
        {{-- <small>Distribusi Tenaga Pendidik</small> --}}
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: #fff;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="mb-0 text-dark" style="font-size: 13px; font-weight: 600;">
                        <i class="bx bx-bar-chart me-1" style="color: #004b4c;"></i>
                        Berdasarkan Status Kepegawaian
                    </h6>
                </div>
                @if($tenagaPendidikByStatus->count() > 0)
                <div id="tenaga-pendidik-chart" data-colors='["#004b4c", "#0e8549", "#6c5ce7", "#f5576c", "#fa709a", "#4facfe", "#ffecd2"]' class="apex-charts" style="height: 220px;"></div>
                @else
                <div class="text-center py-4">
                    <div class="avatar-md mx-auto mb-2">
                        <div class="avatar-title bg-light text-muted rounded-circle">
                            <i class="bx bx-bar-chart-alt-2 fs-4"></i>
                        </div>
                    </div>
                    <p class="text-muted mb-0" style="font-size: 12px;">Belum ada data tenaga pendidik</p>
                </div>
                @endif
            </div>
        </div>
    </div>


    <small>Menu Pengurus</small>

    <!-- Services Form -->
    <div class="services-form">
        <div class="services-grid" id="servicesGrid">
            <div class="service-wrapper">
                <a href="{{ route('mobile.pengurus.sekolah') }}" class="service-item" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="bx bx-building" style="color: white; font-size: 28px; position: relative; z-index: 1;"></i>
                </a>
                <div class="service-label">Data Sekolah</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('tenaga-pendidik.index') }}" class="service-item" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="bx bx-user-voice" style="color: white; font-size: 28px; position: relative; z-index: 1;"></i>
                </a>
                <div class="service-label">Tenaga Pendidik</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('admin.teaching_progress') }}" class="service-item" style="background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);">
                    <i class="bx bx-chalkboard" style="color: white; font-size: 28px; position: relative; z-index: 1;"></i>
                </a>
                <div class="service-label">Progres Mengajar</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('admin.data_madrasah') }}" class="service-item" style="background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);">
                    <i class="bx bx-data" style="color: white; font-size: 28px; position: relative; z-index: 1;"></i>
                </a>
                <div class="service-label">Kelengkapan Data</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('uppm.index') }}" class="service-item" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                    <i class="bx bx-certification" style="color: white; font-size: 28px; position: relative; z-index: 1;"></i>
                </a>
                <div class="service-label">UPPM</div>
            </div>
            <div class="service-wrapper">
                <a href="{{ route('ppdb.index') }}" class="service-item" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                    <i class="bx bx-user-plus" style="color: white; font-size: 28px; position: relative; z-index: 1;"></i>
                </a>
                <div class="service-label">PPDB</div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tenagaPendidikData = @json($tenagaPendidikByStatus);

    if (tenagaPendidikData && tenagaPendidikData.length > 0) {
        var chartElement = document.querySelector("#tenaga-pendidik-chart");

        if (chartElement) {
            var colors = JSON.parse(chartElement.getAttribute('data-colors')) || ["#004b4c"];
            var labels = tenagaPendidikData.map(function(item) { return item.status_name; });
            var series = tenagaPendidikData.map(function(item) { return item.count; });

            var options = {
                chart: {
                    type: 'bar',
                    height: 220,
                    fontFamily: "'Poppins', sans-serif",
                    toolbar: {
                        show: false
                    },
                    stacked: false
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 4,
                        distributed: true,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                colors: colors,
                series: [{
                    name: 'Jumlah',
                    data: series
                }],
                xaxis: {
                    categories: labels,
                    labels: {
                        show: false,
                        style: {
                            fontSize: '10px',
                            fontFamily: "'Poppins', sans-serif",
                            colors: '#495057'
                        },
                        rotate: -45,
                        rotateAlways: false
                    }
                },
                yaxis: {
                    show: false,
                    labels: {
                        show: false
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: true,
                    textAnchor: 'end',
                    offsetY: -20,
                    offsetX: 8,
                    style: {
                        fontSize: '11px',
                        fontWeight: 600,
                        fontFamily: "'Poppins', sans-serif",
                        colors: ['#495057']
                    },
                    formatter: function(val, opts) {
                        return val;
                    }
                },
                stroke: {
                    width: 0
                },
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function(val) {
                            return val + " tenaga pendidik";
                        }
                    }
                },
                grid: {
                    show: false,
                    padding: {
                        top: 10,
                        bottom: 10,
                        left: 0,
                        right: 0
                    }
                },
                fill: {
                    opacity: 1
                }
            };

            var chart = new ApexCharts(chartElement, options);
            chart.render();
        }
    }
});
</script>
@endsection
