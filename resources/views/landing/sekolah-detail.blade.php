@extends('layouts.master-without-nav')

@section('title', $madrasah->name . ' - NUIST')
@section('description', 'Profil ' . $madrasah->name . ' Dibawah Naungan LPMNU PWNU DIY')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #f8fafc;
        color: #333;
        line-height: 1.6;
    }

    /* HERO */
    .hero {
        position: relative;
        padding: 80px 40px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white;
        text-align: center;
        min-height: 280px;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: linear-gradient(rgba(255, 255, 255, 0.04) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255, 255, 255, 0.04) 1px, transparent 1px);
        background-size: 25px 25px;
        pointer-events: none;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 1200px;
        margin: 0 auto;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }

    .hero-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .school-logo-large {
        width: 120px;
        height: 120px;
        background: white;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
    }

    .school-logo-large img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .school-title {
        text-align: left;
    }

    .school-title h1 {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }

    .school-title .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.25);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 15px;
        font-weight: 600;
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .school-title .scod {
        display: inline-block;
        margin-top: 10px;
        background: #eda711;
        color: #00393a;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
    }

    /* CONTENT */
    .content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 50px 40px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 3px solid #eda711;
        display: inline-block;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 50px;
    }

    .info-card {
        display: flex;
        align-items: flex-start;
        gap: 18px;
        padding: 24px;
        background: white;
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        border-color: rgba(0, 75, 76, 0.1);
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #004b4c, #006666);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-icon i {
        font-size: 24px;
        color: white;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 16px;
        color: #1e293b;
        font-weight: 700;
        line-height: 1.4;
    }

    /* ACTION BUTTONS */
    .action-section {
        margin-top: 50px;
    }

    .action-buttons {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 18px 36px;
        background: linear-gradient(135deg, #00393a, #005555, #004b4c);
        color: white;
        text-decoration: none;
        border-radius: 16px;
        font-weight: 700;
        font-size: 17px;
        transition: all 0.3s ease;
        box-shadow: 0 6px 25px rgba(0, 75, 76, 0.35);
        border: none;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 40px rgba(0, 75, 76, 0.45);
        background: linear-gradient(135deg, #004b4c, #006666, #005555);
    }

    .btn-action.secondary {
        background: white;
        color: #004b4c;
        border: 2px solid #004b4c;
        box-shadow: none;
    }

    .btn-action.secondary:hover {
        background: #f0fdf4;
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 75, 76, 0.15);
    }

    .btn-action i {
        font-size: 22px;
    }

    /* STATS SECTION */
    .stats-section {
        background: white;
        border-radius: 24px;
        padding: 40px;
        margin-top: 50px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        text-align: center;
    }

    .stat-item {
        padding: 20px;
    }

    .stat-number {
        font-size: 42px;
        font-weight: 800;
        background: linear-gradient(135deg, #00393a, #005555);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* FOOTER */
    .footer {
        background: linear-gradient(135deg, #1f2937, #374151);
        color: white;
        padding: 40px;
        margin-top: 80px;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .footer-logo img {
        height: 45px;
    }

    .footer-logo span {
        font-size: 20px;
        font-weight: 700;
        color: #eda711;
    }

    .footer-text {
        font-size: 14px;
        opacity: 0.8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero {
            padding: 50px 20px;
        }

        .hero-header {
            flex-direction: column;
            text-align: center;
        }

        .school-title {
            text-align: center;
        }

        .school-title h1 {
            font-size: 28px;
        }

        .content {
            padding: 30px 20px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }

        .info-card {
            padding: 18px;
        }

        .footer-content {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <a href="{{ route('landing.sekolah') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Sekolah
        </a>
        <div class="hero-header">
            <div class="school-logo-large">
                <img src="{{ asset('storage/' . $madrasah->logo) }}" alt="{{ $madrasah->name }}">
            </div>
            <div class="school-title">
                <h1>{{ $madrasah->name }}</h1>
                <span class="badge">
                    <i class="bi bi-geo-alt-fill"></i> {{ $madrasah->kabupaten }}
                </span>
                @if($madrasah->scod)
                    <span class="scod">SCOD: {{ $madrasah->scod }}</span>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- CONTENT -->
<section class="content">
    <h2 class="section-title">Informasi Sekolah</h2>

    <div class="info-grid">
        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Alamat</div>
                <div class="info-value">{{ $madrasah->alamat ?? 'Belum ada data alamat' }}</div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-award-fill"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Kode SCOD</div>
                <div class="info-value">{{ $madrasah->scod ?? '-' }}</div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-envelope-fill"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $madrasah->email ?? '-' }}</div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-telephone-fill"></i>
            </div>
            <div class="info-content">
                <div class="info-label">Telepon</div>
                <div class="info-value">{{ $madrasah->telepon ?? '-' }}</div>
            </div>
        </div>
    </div>
{{--
    <div class="action-section">
        <h2 class="section-title">Aksi</h2>
        <div class="action-buttons">
            <a href="{{ route('ppdb.sekolah', $madrasah->slug) }}" class="btn-action">
                <i class="bi bi-pencil-square"></i> PPDB Sekolah Ini
            </a>
            <a href="#" class="btn-action secondary">
                <i class="bi bi-telephone-fill"></i> Hubungi Sekolah
            </a>
        </div>
    </div>

    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\User::where('madrasah_id', $madrasah->id)->where('role', 'tenaga_pendidik')->count() }}</div>
                <div class="stat-label">Tenaga Pendidik</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\User::where('madrasah_id', $madrasah->id)->whereIn('role', ['admin', 'operator'])->count() }}</div>
                <div class="stat-label">Admin Operator</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ \App\Models\PPDBPendent::where('madrasah_id', $madrasah->id)->count() }}</div>
                <div class="stat-label">Calon Siswa</div>
            </div>
        </div>
    </div> --}}
</section>

@include('landing.footer')

@endsection

