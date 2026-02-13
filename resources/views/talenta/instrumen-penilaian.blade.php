
@extends('layouts.master-without-nav')

@section('title', 'Instrumen Penilaian - NUIST')
@section('description', 'Instrumen Penilaian Talenta LPMNU PWNU DIY')

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

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* HERO */
    .hero {
        position: relative;
        padding: 80px 40px;
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white;
        text-align: center;
        min-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
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
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
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
        position: absolute;
        left: 0;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateX(-5px);
    }

    .hero-title {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        color: white;
    }

    .hero p {
        font-size: 20px;
        opacity: 0.9;
        max-width: 720px;
        margin-left: auto;
        margin-right: auto;
    }

    /* TAB NAVIGATION - OVERLAP HERO */
    .tab-navigation-wrapper {
        position: relative;
        margin-top: -50px;
        margin-bottom: -20px;
        z-index: 10;
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
        padding: 0 20px;
    }

    .tab-navigation {
        background: white;
        border-radius: 24px;
        padding: 10px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .tab-buttons {
        display: flex;
        gap: 8px;
        width: 100%;
        flex-wrap: wrap;
    }

    .tab-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex: 1;
        min-width: 150px;
        padding: 16px 20px;
        background: transparent;
        border: none;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .tab-btn:hover {
        background: #f1f5f9;
        color: #00393a;
    }

    .tab-btn.active {
        background: linear-gradient(135deg, #00393a, #005555);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 57, 58, 0.3);
    }

    .tab-btn i {
        font-size: 20px;
    }

    /* CONTENT */
    .content-section {
        padding: 50px 0 80px;
        background: #f8fafc;
    }

    .instrumen-section {
        margin-bottom: 0;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 20px;
    }

    /* TABLE STYLES */
    .table-container {
        background: white;
        border-radius: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        margin: 0 auto;
        max-width: 1400px;
    }

    .instrumen-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .instrumen-table thead {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
    }

    .instrumen-table th {
        padding: 20px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 16px;
    }

    .instrumen-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
        vertical-align: top;
    }

    .instrumen-table tbody tr:hover {
        background: #f9fafb;
        transition: background 0.3s ease;
    }

    .instrumen-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* RATING SCALE */
    .rating-scale {
        display: flex;
        gap: 1px;
        justify-content: center;
        flex-wrap: nowrap;
    }

    .rating-option {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 14px;
        height: 14px;
        min-width: 14px;
        border: 1px solid #004b4c;
        border-radius: 2px;
        font-weight: 400;
        color: #004b4c;
        background: white;
        cursor: pointer;
        transition: all 0.1s ease;
        font-size: 8px;
        position: relative;
        line-height: 1;
    }

    .rating-option:hover {
        background: #004b4c;
        color: white;
    }

    .rating-option.selected {
        background: #eda711;
        border-color: #eda711;
        color: white;
    }

    /* TABLE SPECIFIC STYLES */
    .peserta-table,
    .fasilitator-table,
    .trainer-table {
        font-size: 13px;
    }

    .peserta-table thead th,
    .fasilitator-table thead th,
    .trainer-table thead th,
    .teknis-table thead th {
        background: #004b4c;
        color: white;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        border: none;
    }

    .peserta-table .aspek-header,
    .fasilitator-table .aspek-header,
    .trainer-table .aspek-header,
    .teknis-table .aspek-header {
        background: #005555;
        font-size: 16px;
        font-weight: 700;
        padding: 16px 12px;
    }

    .peserta-table .aspek-subheader,
    .fasilitator-table .aspek-subheader,
    .trainer-table .aspek-subheader,
    .teknis-table .aspek-subheader {
        background: #006666;
    }

    .peserta-table .aspek-subheader th,
    .fasilitator-table .aspek-subheader th,
    .trainer-table .aspek-subheader th {
        padding: 12px 8px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-left: 1px solid rgba(255,255,255,0.2);
    }

    .peserta-table .aspek-subheader th:first-child,
    .fasilitator-table .aspek-subheader th:first-child,
    .trainer-table .aspek-subheader th:first-child,
    .teknis-table .aspek-subheader th:first-child {
        border-left: none;
    }

    .peserta-table .aspek-col,
    .fasilitator-table .aspek-col,
    .trainer-table .aspek-col {
        min-width: 80px;
        max-width: 100px;
    }

    .peserta-table tbody td,
    .fasilitator-table tbody td,
    .trainer-table tbody td,
    .teknis-table tbody td {
        padding: 12px 8px;
        vertical-align: middle;
        text-align: center;
        border-bottom: 1px solid #e5e7eb;
    }

    .peserta-table tbody td:first-child,
    .peserta-table tbody td:nth-child(2),
    .peserta-table tbody td:nth-child(3),
    .peserta-table tbody td:nth-child(4),
    .fasilitator-table tbody td:first-child,
    .fasilitator-table tbody td:nth-child(2),
    .fasilitator-table tbody td:nth-child(3),
    .fasilitator-table tbody td:nth-child(4),
    .trainer-table tbody td:first-child,
    .trainer-table tbody td:nth-child(2),
    .trainer-table tbody td:nth-child(3),
    .trainer-table tbody td:nth-child(4),
    .teknis-table tbody td:first-child,
    .teknis-table tbody td:nth-child(2),
    .teknis-table tbody td:nth-child(3),
    .teknis-table tbody td:nth-child(4) {
        text-align: left;
        font-weight: 500;
    }

    .peserta-table .peserta-name,
    .fasilitator-table .fasilitator-name,
    .trainer-table .trainer-name,
    .teknis-table .teknis-name {
        font-weight: 600;
        color: #004b4c;
        font-size: 14px;
    }

    .peserta-table .rating-cell,
    .fasilitator-table .rating-cell,
    .trainer-table .rating-cell,
    .teknis-table .rating-cell {
        padding: 8px 4px;
        min-width: 120px;
    }

    .peserta-table tbody tr:hover,
    .fasilitator-table tbody tr:hover,
    .trainer-table tbody tr:hover,
    .teknis-table tbody tr:hover {
        background: #f0f4f8;
        transition: background 0.2s ease;
    }

    /* TEKNIK SECTION TABLE */
    .teknis-table {
        width: 100%;
        border-collapse: collapse;
    }

    .teknis-table thead th {
        /* background: linear-gradient(135deg, #004b4c, #006666); */
        color: white;
        padding: 20px 15px;
        text-align: left;
        font-weight: 600;
    }

    .teknis-table tbody td {
        padding: 18px 15px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: top;
    }

    .teknis-table tbody tr:hover {
        background: #f9fafb;
    }

    /* SCALE LEGEND */
    .scale-legend {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 30px 40px;
        margin: 60px auto 0;
        max-width: 1400px;
        text-align: center;
    }

    .legend-header {
        margin-bottom: 30px;
    }

    .scale-legend h3 {
        font-size: 24px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 8px;
    }

    .legend-subtitle {
        font-size: 16px;
        color: #6b7280;
        margin: 0;
    }

    .legend-grid {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        max-width: 100%;
        margin: 0 auto;
        flex-wrap: nowrap;
    }

    .legend-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 20px 15px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        flex: 1;
    }

    .legend-item:hover {
        background: #f0f4f8;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .legend-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border: 3px solid #004b4c;
        border-radius: 50%;
        font-weight: 700;
        color: #004b4c;
        background: white;
        font-size: 18px;
        box-shadow: 0 2px 8px rgba(0,75,76,0.2);
    }

    .legend-label {
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        text-align: center;
        line-height: 1.3;
    }

    /* ANIMATION */
    .animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .animate.show {
        opacity: 1;
        transform: translateY(0);
    }

    .fade-up {
        transform: translateY(30px);
    }

    .fade-up.delay-1 {
        transition-delay: 0.2s;
    }

    .fade-up.delay-2 {
        transition-delay: 0.4s;
    }

    .fade-up.delay-3 {
        transition-delay: 0.6s;
    }

    /* FOOTER */
    .footer {
        background: linear-gradient(135deg, #1f2937, #374151);
        color: white;
        padding: 40px;
        margin-top: 80px;
    }

    .footer-content {
        max-width: 1400px;
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

    /* SAVE BUTTON */
    .save-btn {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
        border: none;
        padding: 16px 32px;
        border-radius: 25px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,75,76,0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        min-width: 200px;
    }

    .save-btn:hover {
        background: linear-gradient(135deg, #006666, #007777);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,75,76,0.4);
    }

    /* NO DATA */
    .no-data {
        text-align: center;
        color: #6b7280;
        font-style: italic;
        padding: 40px !important;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .tab-btn {
            min-width: 120px;
            padding: 14px 16px;
            font-size: 13px;
        }

        .tab-btn i {
            font-size: 18px;
        }
    }

    @media (max-width: 768px) {
        .hero {
            padding: 60px 20px;
            min-height: auto;
        }

        .hero-title {
            font-size: 32px;
        }

        .hero p {
            font-size: 16px;
        }

        .back-btn {
            position: static;
            margin-bottom: 16px;
        }

        .tab-navigation-wrapper {
            margin-top: -60px;
            padding: 0 10px;
        }

        .tab-navigation {
            padding: 8px;
        }

        .tab-buttons {
            gap: 6px;
        }

        .tab-btn {
            flex: 1 1 calc(50% - 6px);
            min-width: unset;
            padding: 12px 10px;
            font-size: 11px;
            gap: 6px;
        }

        .tab-btn i {
            font-size: 16px;
        }

        .table-container {
            margin: 0 10px;
            overflow-x: auto;
        }

        .instrumen-table,
        .teknis-table {
            min-width: 800px;
        }

        .section-header h2 {
            font-size: 20px;
        }

        .scale-legend {
            padding: 25px 15px;
            margin: 40px 10px 0;
        }

        .legend-header {
            margin-bottom: 25px;
        }

        .scale-legend h3 {
            font-size: 20px;
        }

        .legend-subtitle {
            font-size: 14px;
        }

        .legend-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
        }

        .legend-item {
            padding: 15px 8px;
            gap: 8px;
        }

        .legend-number {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .legend-label {
            font-size: 12px;
        }

        .footer-content {
            flex-direction: column;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .tab-btn {
            flex: 1 1 100%;
        }
    }
</style>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <a href="{{ route('talenta.dashboard') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h1 class="hero-title">Instrumen Penilaian</h1>
        <p>Instrumen penilaian untuk mengukur kualitas pelaksanaan program talenta dalam ekosistem pendidikan kami.</p>
    </div>
</section>

<!-- TAB NAVIGATION - OVERLAP HERO -->
@if(Auth::user()->role !== 'fasilitator')
<div class="tab-navigation-wrapper">
    <div class="tab-navigation">
        <div class="tab-buttons">
            <button class="tab-btn active" data-target="trainer-section">
                <i class="bi bi-person-badge"></i> Trainer
            </button>
            <button class="tab-btn" data-target="fasilitator-section">
                <i class="bi bi-person-check"></i> Fasilitator
            </button>
            <button class="tab-btn" data-target="teknis-section">
                <i class="bi bi-tools"></i> Tim Teknis
            </button>
            @if(Auth::user()->role !== 'tenaga_pendidik')
            <button class="tab-btn" data-target="peserta-section">
                <i class="bi bi-people"></i> Peserta
            </button>
            @endif
        </div>
    </div>
</div>
@endif

<!-- CONTENT -->
<section class="content-section">
    <div class="container">

        @if(Auth::user()->role !== 'fasilitator')
        <!-- TRAINER SECTION -->
        <div id="trainer-section" class="instrumen-section animate fade-up tab-content active">
            <div class="table-container">
                <table class="instrumen-table trainer-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 60px;">No</th>
                            <th rowspan="2" style="width: 180px;">Nama Trainer</th>
                            <th rowspan="2" style="width: 120px;">Kode Trainer</th>
                            <th rowspan="2" style="width: 160px;">Materi</th>
                            <th colspan="6" class="aspek-header">Aspek Penilaian</th>
                        </tr>
                        <tr class="aspek-subheader">
                            <th class="aspek-col"><small>Kualitas Materi</small></th>
                            <th class="aspek-col"><small>Penyampaian</small></th>
                            <th class="aspek-col"><small>Integrasi Kasus</small></th>
                            <th class="aspek-col"><small>Penjelasan</small></th>
                            <th class="aspek-col"><small>Umpan Balik</small></th>
                            <th class="aspek-col"><small>Waktu</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemateriTalenta ?? [] as $index => $trainer)
                        <tr data-trainer-id="{{ $trainer->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="trainer-name">{{ $trainer->nama ?? 'N/A' }}</td>
                            <td>{{ $trainer->kode_pemateri ?? 'N/A' }}</td>
                            <td>{{ $trainer->materi->nama_materi ?? 'N/A' }}</td>
                            @for($aspek = 1; $aspek <= 6; $aspek++)
                            <td class="rating-cell">
                                <div class="rating-scale">
                                    @for($nilai = 1; $nilai <= 5; $nilai++)
                                    <span class="rating-option" data-trainer="{{ $trainer->id }}" data-aspek="{{ $aspek }}" data-nilai="{{ $nilai }}">{{ $nilai }}</span>
                                    @endfor
                                </div>
                            </td>
                            @endfor
                        </tr>
                        @empty
                        <tr><td colspan="10" class="no-data">Belum ada data trainer</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <button id="save-trainer-ratings" class="save-btn">Simpan Penilaian</button>
            </div>
        </div>

        <!-- FASILITATOR SECTION -->
        <div id="fasilitator-section" class="instrumen-section animate fade-up delay-1 tab-content" style="display: none;">
            <div class="table-container">
                <table class="instrumen-table fasilitator-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 60px;">No</th>
                            <th rowspan="2" style="width: 180px;">Nama Fasilitator</th>
                            <th rowspan="2" style="width: 120px;">Kode</th>
                            <th rowspan="2" style="width: 160px;">Materi</th>
                            <th colspan="6" class="aspek-header">Aspek Penilaian</th>
                        </tr>
                        <tr class="aspek-subheader">
                            <th class="aspek-col"><small>Fasilitasi</small></th>
                            <th class="aspek-col"><small>Pendampingan</small></th>
                            <th class="aspek-col"><small>Respons</small></th>
                            <th class="aspek-col"><small>Koordinasi</small></th>
                            <th class="aspek-col"><small>Monitoring</small></th>
                            <th class="aspek-col"><small>Waktu</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fasilitatorTalenta ?? [] as $index => $fasilitator)
                        <tr data-fasilitator-id="{{ $fasilitator->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="fasilitator-name">{{ $fasilitator->nama ?? 'N/A' }}</td>
                            <td>{{ $fasilitator->kode_fasilitator ?? 'N/A' }}</td>
                            <td>{{ $fasilitator->materi->nama_materi ?? 'N/A' }}</td>
                            @for($aspek = 1; $aspek <= 6; $aspek++)
                            <td class="rating-cell">
                                <div class="rating-scale">
                                    @for($nilai = 1; $nilai <= 5; $nilai++)
                                    <span class="rating-option" data-fasilitator="{{ $fasilitator->id }}" data-aspek="{{ $aspek }}" data-nilai="{{ $nilai }}">{{ $nilai }}</span>
                                    @endfor
                                </div>
                            </td>
                            @endfor
                        </tr>
                        @empty
                        <tr><td colspan="10" class="no-data">Belum ada data fasilitator</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <button id="save-fasilitator-ratings" class="save-btn">Simpan Penilaian</button>
            </div>
        </div>
        @endif

        @if(Auth::user()->role !== 'fasilitator')
        <!-- TEKNIS SECTION -->
        <div id="teknis-section" class="instrumen-section animate fade-up delay-2 tab-content" style="display: none;">
            <div class="table-container">
                <table class="instrumen-table teknis-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 60px;">No</th>
                            <th rowspan="2" style="width: 180px;">Nama Layanan Teknis</th>
                            <th rowspan="2" style="width: 120px;">Kode</th>
                            <th rowspan="2" style="width: 200px;">Tugas</th>
                            <th colspan="6" class="aspek-header">Aspek Penilaian</th>
                        </tr>
                        <tr class="aspek-subheader">
                            <th class="aspek-col"><small>Kehadiran</small></th>
                            <th class="aspek-col"><small>Partisipasi</small></th>
                            <th class="aspek-col"><small>Disiplin</small></th>
                            <th class="aspek-col"><small>Kualitas Tugas</small></th>
                            <th class="aspek-col"><small>Pemahaman Materi</small></th>
                            <th class="aspek-col"><small>Implementasi Praktik</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($layananTeknisTalenta ?? [] as $index => $teknis)
                        <tr data-teknis-id="{{ $teknis->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="teknis-name">{{ $teknis->nama_layanan_teknis ?? 'N/A' }}</td>
                            <td>{{ $teknis->kode_layanan_teknis ?? 'N/A' }}</td>
                            <td>{{ Str::limit($teknis->tugas_layanan_teknis ?? 'N/A', 50) }}</td>
                            @for($aspek = 1; $aspek <= 6; $aspek++)
                            <td class="rating-cell">
                                <div class="rating-scale">
                                    @for($nilai = 1; $nilai <= 5; $nilai++)
                                    <span class="rating-option" data-teknis="{{ $teknis->id }}" data-aspek="{{ $aspek }}" data-nilai="{{ $nilai }}">{{ $nilai }}</span>
                                    @endfor
                                </div>
                            </td>
                            @endfor
                        </tr>
                        @empty
                        <tr><td colspan="10" class="no-data">Belum ada data layanan teknis</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <button id="save-teknis-ratings" class="save-btn">Simpan Penilaian</button>
            </div>
        </div>
        @endif

        @if(Auth::user()->role !== 'fasilitator')
        <!-- PESERTA SECTION -->
        <div id="peserta-section" class="instrumen-section animate fade-up delay-3 tab-content" style="display: none;">
            <div class="table-container">
                <table class="instrumen-table peserta-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 60px;">No</th>
                            <th rowspan="2" style="width: 180px;">Nama Peserta</th>
                            <th rowspan="2" style="width: 120px;">Kode</th>
                            <th rowspan="2" style="width: 160px;">Sekolah</th>
                            <th colspan="7" class="aspek-header">Aspek Penilaian</th>
                        </tr>
                        <tr class="aspek-subheader">
                            <th class="aspek-col"><small>Kehadiran</small></th>
                            <th class="aspek-col"><small>Partisipasi</small></th>
                            <th class="aspek-col"><small>Disiplin</small></th>
                            <th class="aspek-col"><small>Tugas</small></th>
                            <th class="aspek-col"><small>Pemahaman</small></th>
                            <th class="aspek-col"><small>Praktik</small></th>
                            <th class="aspek-col"><small>Sikap</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesertaTalenta ?? [] as $index => $peserta)
                        <tr data-peserta-id="{{ $peserta->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="peserta-name">{{ $peserta->nama ?? 'N/A' }}</td>
                            <td>{{ $peserta->kode_peserta ?? 'N/A' }}</td>
                            <td>{{ $peserta->nama_madrasah ?? 'N/A' }}</td>
                            @for($aspek = 1; $aspek <= 7; $aspek++)
                            <td class="rating-cell">
                                <div class="rating-scale">
                                    @for($nilai = 1; $nilai <= 5; $nilai++)
                                    <span class="rating-option" data-peserta="{{ $peserta->id }}" data-aspek="{{ $aspek }}" data-nilai="{{ $nilai }}">{{ $nilai }}</span>
                                    @endfor
                                </div>
                            </td>
                            @endfor
                        </tr>
                        @empty
                        <tr><td colspan="11" class="no-data">Belum ada data peserta</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <button id="save-peserta-ratings" class="save-btn">Simpan Penilaian</button>
            </div>
        </div>
        @endif

        @if(Auth::user()->role === 'fasilitator')
        <!-- PESERTA SECTION FOR FASILITATOR -->
        <div id="peserta-section" class="instrumen-section animate fade-up tab-content active">
            <div class="table-container">
                <table class="instrumen-table peserta-table">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 60px;">No</th>
                            <th rowspan="2" style="width: 180px;">Nama Peserta</th>
                            <th rowspan="2" style="width: 120px;">Kode</th>
                            <th rowspan="2" style="width: 160px;">Sekolah</th>
                            <th colspan="7" class="aspek-header">Aspek Penilaian</th>
                        </tr>
                        <tr class="aspek-subheader">
                            <th class="aspek-col"><small>Kehadiran</small></th>
                            <th class="aspek-col"><small>Partisipasi</small></th>
                            <th class="aspek-col"><small>Disiplin</small></th>
                            <th class="aspek-col"><small>Tugas</small></th>
                            <th class="aspek-col"><small>Pemahaman</small></th>
                            <th class="aspek-col"><small>Praktik</small></th>
                            <th class="aspek-col"><small>Sikap</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesertaTalenta ?? [] as $index => $peserta)
                        <tr data-peserta-id="{{ $peserta->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td class="peserta-name">{{ $peserta->nama ?? 'N/A' }}</td>
                            <td>{{ $peserta->kode_peserta ?? 'N/A' }}</td>
                            <td>{{ $peserta->nama_madrasah ?? 'N/A' }}</td>
                            @for($aspek = 1; $aspek <= 7; $aspek++)
                            <td class="rating-cell">
                                <div class="rating-scale">
                                    @for($nilai = 1; $nilai <= 5; $nilai++)
                                    <span class="rating-option" data-peserta="{{ $peserta->id }}" data-aspek="{{ $aspek }}" data-nilai="{{ $nilai }}">{{ $nilai }}</span>
                                    @endfor
                                </div>
                            </td>
                            @endfor
                        </tr>
                        @empty
                        <tr><td colspan="11" class="no-data">Belum ada data peserta</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <button id="save-peserta-ratings" class="save-btn">Simpan Penilaian</button>
            </div>
        </div>
        @endif

        <!-- LEGEND -->
        <div class="scale-legend">
            <div class="legend-header">
                <h3>Skala Penilaian</h3>
                <p class="legend-subtitle">Panduan penilaian untuk setiap aspek</p>
            </div>
            <div class="legend-grid">
                <div class="legend-item">
                    <div class="legend-number">1</div>
                    <div class="legend-label">Sangat Kurang</div>
                </div>
                <div class="legend-item">
                    <div class="legend-number">2</div>
                    <div class="legend-label">Kurang</div>
                </div>
                <div class="legend-item">
                    <div class="legend-number">3</div>
                    <div class="legend-label">Cukup</div>
                </div>
                <div class="legend-item">
                    <div class="legend-number">4</div>
                    <div class="legend-label">Baik</div>
                </div>
                <div class="legend-item">
                    <div class="legend-number">5</div>
                    <div class="legend-label">Sangat Baik</div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- FOOTER -->
@include('landing.footer')

@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Animation observer
    const animateElements = document.querySelectorAll('.animate');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('show');
        });
    }, { threshold: 0.15 });
    animateElements.forEach(el => observer.observe(el));

    // Rating selection
    document.querySelectorAll('.rating-option').forEach(opt => {
        opt.addEventListener('click', function() {
            const parent = this.parentElement;
            parent.querySelectorAll('.rating-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
        });
    });

    // Tab navigation
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.target;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-content').forEach(c => {
                c.style.display = 'none';
                c.classList.remove('active');
            });
            const targetEl = document.getElementById(target);
            if (targetEl) {
                targetEl.style.display = 'block';
                setTimeout(() => targetEl.classList.add('show'), 10);
            }
        });
    });

    // Save handlers
    function getRoute(type){
    const routes = {
        trainer: "{{ route('talenta.penilaian-trainer.simpan') }}",
        fasilitator: "{{ route('talenta.penilaian-fasilitator.simpan') }}",
        teknis: "{{ route('talenta.penilaian-teknis.simpan') }}",
        peserta: "{{ route('talenta.penilaian-peserta.simpan') }}"
    };

    return routes[type];
}

    document.getElementById('save-trainer-ratings')?.addEventListener('click', () => saveRatings('trainer', 'trainer'));
    document.getElementById('save-fasilitator-ratings')?.addEventListener('click', () => saveRatings('fasilitator', 'fasilitator'));
    document.getElementById('save-teknis-ratings')?.addEventListener('click', () => saveRatings('teknis', 'teknis'));
    document.getElementById('save-peserta-ratings')?.addEventListener('click', () => saveRatings('peserta', 'peserta'));
});
</script>

