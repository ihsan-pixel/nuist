@extends('layouts.mobile')

@section('title', 'Menu Layanan')
@section('subtitle', 'Semua fitur dalam satu tempat')

@section('content')
<div class="services-page">
    <style>
        body {
            background: #f4f7f5;
            font-family: 'Poppins', sans-serif;
        }

        .services-page {
            max-width: 420px;
            margin: 0 auto;
            padding: 6px 2px 0;
        }

        .services-topbar {
            display: grid;
            grid-template-columns: 40px 1fr 40px;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            padding: 0 2px;
        }

        .services-back {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid rgba(4, 63, 49, 0.08);
            background: #fff;
            color: #043F31;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(4, 63, 49, 0.04);
        }

        .services-back i {
            font-size: 16px;
        }

        .services-title {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.2;
            color: #17312c;
            text-align: center;
        }

        .services-topbar-spacer {
            width: 34px;
            height: 34px;
        }

        .service-group {
            background: #fff;
            border: 1px solid rgba(4, 63, 49, 0.05);
            border-radius: 12px;
            padding: 8px;
            margin-bottom: 7px;
            box-shadow: 0 3px 10px rgba(4, 63, 49, 0.04);
        }

        .service-group-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .service-group-title h6 {
            margin: 0;
            font-size: 0.76rem;
            font-weight: 700;
            color: #17312c;
        }

        .service-group-title small {
            font-size: 0.64rem;
            color: #7a8a8f;
        }

        .service-group-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 6px;
        }

        .service-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            color: inherit;
            min-width: 0;
        }

        .service-icon {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(251, 181, 36, 0.18), rgba(251, 181, 36, 0.26));
            color: #FBB524;
        }

        .service-icon i {
            font-size: 20px;
        }

        .service-label {
            font-size: 0.58rem;
            line-height: 1.25;
            text-align: center;
            color: #334;
            font-weight: 600;
        }

        .service-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 8px;
        }

        .service-footer a {
            font-size: 0.66rem;
            font-weight: 700;
            color: #FBB524;
            text-decoration: none;
        }

        @media (max-width: 420px) {
            .services-page {
                padding-inline: 2px;
            }

            .service-group {
                padding: 8px;
            }

            .service-group-grid {
                gap: 6px;
            }
        }
    </style>

    <div class="services-topbar">
        <a href="javascript:void(0)" onclick="window.history.back()" class="services-back" aria-label="Kembali">
            <i class="bx bx-arrow-back"></i>
        </a>
        <h1 class="services-title">Menu Layanan</h1>
        <div class="services-topbar-spacer" aria-hidden="true"></div>
    </div>

    <div class="service-group">
        <div class="service-group-title">
            <h6>Menu Utama</h6>
            <small>Akses cepat</small>
        </div>
        <div class="service-group-grid">
            <a href="{{ route('mobile.presensi') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-qr-scan"></i></div>
                <div class="service-label">Presensi</div>
            </a>
            <a href="{{ route('mobile.teaching-attendances') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-chalkboard"></i></div>
                <div class="service-label">Presensi Mengajar</div>
            </a>
            <a href="{{ route('mobile.jadwal') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-calendar"></i></div>
                <div class="service-label">Jadwal Mengajar</div>
            </a>
            <a href="{{ route('mobile.menu-layanan') }}" class="service-card" data-no-loader="true">
                <div class="service-icon"><i class="bx bx-grid-alt"></i></div>
                <div class="service-label">Semua Menu</div>
            </a>
        </div>
    </div>

    <div class="service-group">
        <div class="service-group-title">
            <h6>Administrasi</h6>
            <small>Perizinan</small>
        </div>
        <div class="service-group-grid">
            <a href="{{ route('mobile.izin', ['type' => 'tidak_masuk']) }}" class="service-card">
                <div class="service-icon"><i class="bx bx-user-x"></i></div>
                <div class="service-label">Izin Tidak Masuk</div>
            </a>
            <a href="{{ route('mobile.izin', ['type' => 'cuti']) }}" class="service-card">
                <div class="service-icon"><i class="bx bx-calendar-star"></i></div>
                <div class="service-label">Izin Cuti</div>
            </a>
            <a href="{{ route('mobile.izin', ['type' => 'terlambat']) }}" class="service-card">
                <div class="service-icon"><i class="bx bx-time-five"></i></div>
                <div class="service-label">Izin Terlambat</div>
            </a>
            <a href="{{ route('mobile.izin', ['type' => 'sakit']) }}" class="service-card">
                <div class="service-icon"><i class="bx bx-plus-medical"></i></div>
                <div class="service-label">Izin Sakit</div>
            </a>
            <a href="{{ route('mobile.izin', ['type' => 'tugas_luar']) }}" class="service-card">
                <div class="service-icon"><i class="bx bx-briefcase"></i></div>
                <div class="service-label">Dinas Luar</div>
            </a>
            @if(Auth::user()->pemenuhan_beban_kerja_lain)
            <a href="{{ route('mobile.izin', ['type' => 'mengajar_sekolah_lain']) }}" class="service-card">
                <div class="service-icon"><i class="bx bx-buildings"></i></div>
                <div class="service-label">Sekolah Lain</div>
            </a>
            @endif
        </div>
    </div>

    <div class="service-group">
        <div class="service-group-title">
            <h6>Akun</h6>
            <small>Profil & laporan</small>
        </div>
        <div class="service-group-grid">
            <a href="{{ route('mobile.profile') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-user"></i></div>
                <div class="service-label">Profil</div>
            </a>
            <a href="{{ route('mobile.laporan') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-file"></i></div>
                <div class="service-label">Laporan</div>
            </a>
            <a href="{{ route('mobile.ubah-akun') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-cog"></i></div>
                <div class="service-label">Pengaturan</div>
            </a>
        </div>
    </div>

    @if(Auth::user()->role === 'tenaga_pendidik' && Auth::user()->ketugasan === 'kepala madrasah/sekolah')
    <div class="service-group">
        <div class="service-group-title">
            <h6>Pengelolaan</h6>
            <small>Untuk kepala madrasah</small>
        </div>
        <div class="service-group-grid">
            <a href="{{ route('mobile.kelola-izin') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-edit"></i></div>
                <div class="service-label">Kelola Izin</div>
            </a>
            <a href="{{ route('mobile.monitor-presensi') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-calendar-check"></i></div>
                <div class="service-label">Data Presensi</div>
            </a>
            <a href="{{ route('mobile.monitor-jurnal-mengajar') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-notepad"></i></div>
                <div class="service-label">Jurnal Mengajar</div>
            </a>
            <a href="{{ route('mobile.academic-calendar-approvals') }}" class="service-card">
                <div class="service-icon"><i class="bx bx-task"></i></div>
                <div class="service-label">Approval Event</div>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
