@extends('layouts.mobile-pengurus')

@section('title', 'Detail Sekolah')
@section('subtitle', $madrasah->name)

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
            <!-- Back Button -->
            <a href="{{ route('mobile.pengurus.sekolah') }}" class="btn btn-link text-decoration-none p-0 me-2">
                <i class="bx bx-arrow-back" style="font-size: 22px; color: #000000;"></i>
            </a>

            <!-- Welcome Text -->
            <div class="text-start grow">
                <small class="text-dark fw-medium" style="font-size: 11px;">{{ $congrat }}</small>
                <h6 class="mb-0 fw-semibold text-dark" style="font-size: 14px;">{{ Auth::user()->name }}</h6>
            </div>

            <!-- Notification and Menu Buttons -->
            <div class="d-flex align-items-center">
                <a href="{{ route('mobile.notifications') }}" class="btn btn-link text-decoration-none p-0 me-2 position-relative">
                    <i class="bx bx-bell" style="font-size: 22px; color: #db3434;"></i>
                    <span id="notificationBadge" class="badge bg-danger rounded-pill position-absolute" style="font-size: 9px; padding: 2px 5px; top: -4px; right: -4px; display: none;">0</span>
                </a>

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

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        .profile-header {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border-radius: 16px;
            padding: 20px;
            color: white;
            margin-bottom: 16px;
            box-shadow: 0 4px 16px rgba(0, 75, 76, 0.3);
        }

        .profile-logo {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            object-fit: cover;
            background: rgba(255, 255, 255, 0.2);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .profile-school-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .profile-school-code {
            font-size: 12px;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }

        .info-card {
            background: #fff;
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 12px;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-card-header i {
            font-size: 20px;
            color: #004b4c;
            margin-right: 10px;
        }

        .info-card-header h6 {
            font-size: 14px;
            font-weight: 600;
            color: #004b4c;
            margin: 0;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            width: 110px;
            flex-shrink: 0;
        }

        .info-value {
            font-size: 12px;
            color: #212529;
            font-weight: 500;
            word-break: break-word;
        }

        .info-value a {
            color: #004b4c;
            text-decoration: none;
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .stat-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }

        .stat-item h4 {
            font-size: 20px;
            font-weight: 700;
            color: #004b4c;
            margin-bottom: 2px;
        }

        .stat-item small {
            font-size: 10px;
            color: #6c757d;
        }

        .badge-info {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .badge-green {
            background: #d4edda;
            color: #155724;
        }

        .badge-blue {
            background: #cce5ff;
            color: #004085;
        }

        .empty-value {
            color: #adb5bd;
            font-style: italic;
        }

        .section-divider {
            height: 1px;
            background: #e9ecef;
            margin: 16px 0;
        }

        .list-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-bullet {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #004b4c;
            margin-right: 10px;
            flex-shrink: 0;
        }
    </style>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="d-flex align-items-start">
            <div class="me-3">
                @if($madrasah->logo)
                <img
                    src="{{ asset('storage/' . $madrasah->logo) }}"
                    alt="{{ $madrasah->name }}"
                    class="profile-logo"
                    onerror="this.src='{{ asset('build/images/logo-light.png') }}'"
                >
                @else
                <img
                    src="{{ asset('build/images/logo-light.png') }}"
                    alt="{{ $madrasah->name }}"
                    class="profile-logo"
                >
                @endif
            </div>
            <div class="grow">
                <h4 class="profile-school-name mb-2">{{ $madrasah->name }}</h4>
                @if($madrasah->scod)
                <span class="profile-school-code">{{ $madrasah->scod }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="info-card">
        <div class="stat-grid">
            <div class="stat-item">
                <h4>{{ number_format($madrasah->jumlah_siswa ?? 0) }}</h4>
                <small>Siswa</small>
            </div>
            <div class="stat-item">
                <h4>{{ number_format($madrasah->jumlah_guru ?? 0) }}</h4>
                <small>Guru</small>
            </div>
            <div class="stat-item">
                <h4>{{ number_format($madrasah->jumlah_jurusan ?? 0) }}</h4>
                <small>Jurusan</small>
            </div>
            <div class="stat-item">
                <h4>{{ number_format($madrasah->jumlah_sarana ?? 0) }}</h4>
                <small>Sarana</small>
            </div>
        </div>
    </div>

    <!-- Basic Info -->
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-info-circle"></i>
            <h6>Informasi Dasar</h6>
        </div>

        <div class="info-row">
            <span class="info-label">Kabupaten</span>
            <span class="info-value">{{ $madrasah->kabupaten ?: '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Alamat</span>
            <span class="info-value">{{ $madrasah->alamat ?: '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tahun Berdiri</span>
            <span class="info-value">{{ $madrasah->tahun_berdiri ?: '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Akreditasi</span>
            <span class="info-value">
                @if($madrasah->akreditasi)
                <span class="badge badge-blue">{{ $madrasah->akreditasi }}</span>
                @else
                <span class="empty-value">-</span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">SCOD</span>
            <span class="info-value">{{ $madrasah->scod ?: '-' }}</span>
        </div>
    </div>

    <!-- Contact Info -->
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-phone"></i>
            <h6>Informasi Kontak</h6>
        </div>

        <div class="info-row">
            <span class="info-label">Telepon</span>
            <span class="info-value">
                @if($madrasah->telepon)
                <a href="tel:{{ $madrasah->telepon }}">{{ $madrasah->telepon }}</a>
                @else
                <span class="empty-value">-</span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">
                @if($madrasah->email)
                <a href="mailto:{{ $madrasah->email }}">{{ $madrasah->email }}</a>
                @else
                <span class="empty-value">-</span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Website</span>
            <span class="info-value">
                @if($madrasah->website)
                <a href="{{ $madrasah->website }}" target="_blank">{{ $madrasah->website }}</a>
                @else
                <span class="empty-value">-</span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Jam Operasi</span>
            <span class="info-value">
                @if($madrasah->jam_operasional_buka && $madrasah->jam_operasional_tutup)
                {{ \Carbon\Carbon::parse($madrasah->jam_operasional_buka)->format('H:i') }} - {{ \Carbon\Carbon::parse($madrasah->jam_operasional_tutup)->format('H:i') }}
                @else
                <span class="empty-value">-</span>
                @endif
            </span>
        </div>
    </div>

    <!-- Kepala Sekolah -->
    @if($madrasah->kepala_sekolah_nama)
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-user"></i>
            <h6>Kepala Sekolah</h6>
        </div>

        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value">
                {{ $madrasah->kepala_sekolah_nama }}
                @if($madrasah->kepala_sekolah_gelar)
                <small class="text-muted">, {{ $madrasah->kepala_sekolah_gelar }}</small>
                @endif
            </span>
        </div>
    </div>
    @endif

    <!-- Tagline & Deskripsi -->
    @if($madrasah->tagline || $madrasah->deskripsi_singkat)
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-text"></i>
            <h6>Tentang</h6>
        </div>

        @if($madrasah->tagline)
        <div class="info-row">
            <span class="info-label">Tagline</span>
            <span class="info-value">{{ $madrasah->tagline }}</span>
        </div>
        @endif

        @if($madrasah->deskripsi_singkat)
        <div class="info-row" style="flex-direction: column;">
            <span class="info-label" style="width: 100%; margin-bottom: 6px;">Deskripsi</span>
            <span class="info-value" style="width: 100%;">{{ $madrasah->deskripsi_singkat }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Visi Misi -->
    @if($madrasah->visi || $madrasah->misi)
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-bullseye"></i>
            <h6>Visi & Misi</h6>
        </div>

        @if($madrasah->visi)
        <div class="info-row" style="flex-direction: column;">
            <span class="info-label" style="width: 100%; margin-bottom: 6px;">Visi</span>
            <span class="info-value" style="width: 100%;">{{ $madrasah->visi }}</span>
        </div>
        @endif

        @if($madrasah->misi)
        <div class="info-row" style="flex-direction: column;">
            <span class="info-label" style="width: 100%; margin-bottom: 6px;">Misi</span>
            <span class="info-value" style="width: 100%;">
                @if(is_array($madrasah->misi))
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($madrasah->misi as $misi)
                    <li style="margin-bottom: 4px;">{{ $misi }}</li>
                    @endforeach
                </ul>
                @else
                {{ $madrasah->misi }}
                @endif
            </span>
        </div>
        @endif
    </div>
    @endif

    <!-- Jurusan -->
    @if($madrasah->jurusan && (is_array($madrasah->jurusan) && count($madrasah->jurusan) > 0))
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-book"></i>
            <h6>Jurusan</h6>
        </div>

        @if(is_array($madrasah->jurusan))
        @foreach($madrasah->jurusan as $jurusan)
        <div class="list-item">
            <span class="list-bullet"></span>
            <span style="font-size: 12px;">{{ $jurusan }}</span>
        </div>
        @endforeach
        @else
        <span class="info-value">{{ $madrasah->jurusan }}</span>
        @endif
    </div>
    @endif

    <!-- Fasilitas -->
    @if($madrasah->fasilitas && (is_array($madrasah->fasilitas) && count($madrasah->fasilitas) > 0))
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-building"></i>
            <h6>Fasilitas</h6>
        </div>

        @if(is_array($madrasah->fasilitas))
        @foreach($madrasah->fasilitas as $fasilitas)
        <div class="list-item">
            <span class="list-bullet" style="background: #0e8549;"></span>
            <span style="font-size: 12px;">{{ $fasilitas }}</span>
        </div>
        @endforeach
        @else
        <span class="info-value">{{ $madrasah->fasilitas }}</span>
        @endif
    </div>
    @endif

    <!-- Keunggulan -->
    @if($madrasah->keunggulan && (is_array($madrasah->keunggulan) && count($madrasah->keunggulan) > 0))
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-star"></i>
            <h6>Keunggulan</h6>
        </div>

        @if(is_array($madrasah->keunggulan))
        @foreach($madrasah->keunggulan as $keunggulan)
        <div class="list-item">
            <span class="list-bullet" style="background: #f5576c;"></span>
            <span style="font-size: 12px;">{{ $keunggulan }}</span>
        </div>
        @endforeach
        @else
        <span class="info-value">{{ $madrasah->keunggulan }}</span>
        @endif
    </div>
    @endif

    <!-- PPDB Info -->
    @if($madrasah->ppdb_status)
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-user-plus"></i>
            <h6>Informasi PPDB</h6>
        </div>

        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value">
                @if($madrasah->ppdb_status == 'buka')
                <span class="badge badge-green">Pendaftaran Dibuka</span>
                @else
                <span class="badge badge-info" style="background: #f8d7da; color: #721c24;">Pendaftaran Ditutup</span>
                @endif
            </span>
        </div>

        @if($madrasah->ppdb_jadwal_buka && $madrasah->ppdb_jadwal_tutup)
        <div class="info-row">
            <span class="info-label">Periode</span>
            <span class="info-value">
                {{ \Carbon\Carbon::parse($madrasah->ppdb_jadwal_buka)->format('d M Y') }} - {{ \Carbon\Carbon::parse($madrasah->ppdb_jadwal_tutup)->format('d M Y') }}
            </span>
        </div>
        @endif

        @if($madrasah->ppdb_kuota_total)
        <div class="info-row">
            <span class="info-label">Kuota</span>
            <span class="info-value">{{ number_format($madrasah->ppdb_kuota_total) }} Siswa</span>
        </div>
        @endif

        @if($madrasah->ppdb_biaya_pendaftaran)
        <div class="info-row">
            <span class="info-label">Biaya Daftar</span>
            <span class="info-value">Rp {{ number_format($madrasah->ppdb_biaya_pendaftaran, 0, ',', '.') }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Lokasi -->
    @if($madrasah->latitude && $madrasah->longitude)
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-map"></i>
            <h6>Lokasi</h6>
        </div>

        <div class="info-row">
            <span class="info-label">Koordinat</span>
            <span class="info-value">
                {{ $madrasah->latitude }}, {{ $madrasah->longitude }}
            </span>
        </div>

        @if($madrasah->map_link)
        <div class="info-row">
            <span class="info-label">Google Maps</span>
            <span class="info-value">
                <a href="{{ $madrasah->map_link }}" target="_blank">Buka di Google Maps</a>
            </span>
        </div>
        @endif
    </div>
    @endif

    <!-- Hari KBM -->
    @if($madrasah->hari_kbm)
    <div class="info-card">
        <div class="info-card-header">
            <i class="bx bx-calendar"></i>
            <h6>Jadwal KBM</h6>
        </div>

        <div class="info-row">
            <span class="info-value">{{ $madrasah->hari_kbm }}</span>
        </div>
    </div>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification badge
    fetchUnreadNotifications();

    async function fetchUnreadNotifications() {
        try {
            const response = await fetch('{{ route("mobile.notifications.unread-count") }}');
            const data = await response.json();
            const badge = document.getElementById('notificationBadge');
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.style.display = 'block';
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }
});
</script>
@endsection

