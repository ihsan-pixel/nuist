@extends('layouts.master-without-nav')

@section('title', 'Penilaian Tugas Talenta - NUIST')
@section('description', 'Penilaian tugas peserta talenta berdasarkan materi yang diajarkan')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

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

    /* CONTENT */
    .talenta-penilaian {
        padding: 50px 0 80px;
        background: #f8fafc;
        margin-top: -30px;
    }

    .data-section {
        margin-bottom: 0;
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

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .data-table thead {
        background: linear-gradient(135deg, #004b4c, #006666);
        color: white;
    }

    .data-table th {
        padding: 20px 15px;
        text-align: left;
        font-weight: 600;
        font-size: 16px;
    }

    .data-table td {
        padding: 18px 15px;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
    }

    .data-table tbody tr:hover {
        background: #f9fafb;
        transition: background 0.3s ease;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* NO DATA */
    .no-data {
        text-align: center;
        color: #6b7280;
        font-style: italic;
        padding: 40px !important;
    }

    /* ACTION BUTTONS */
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view {
        background: #e3f2fd;
        color: #1976d2;
    }

    .btn-view:hover {
        background: #bbdefb;
        color: #0d47a1;
    }

    .btn-download {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .btn-download:hover {
        background: #c8e6c9;
        color: #1b5e20;
    }

    .btn-nilai {
        background: #fff3cd;
        color: #856404;
    }

    .btn-nilai:hover {
        background: #ffeaa7;
        color: #5a4a00;
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

    /* MODAL STYLES */
    .modal-overlay {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 57, 58, 0.8);
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s ease-out;
    }

    .modal-content-custom {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        margin: 5% auto;
        padding: 0;
        border: none;
        width: 90%;
        max-width: 500px;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 57, 58, 0.3);
        animation: slideIn 0.4s ease-out;
        overflow: hidden;
    }

    .modal-header-custom {
        background: linear-gradient(135deg, #00393a 0%, #005555 50%, #00393a 100%);
        color: white;
        padding: 25px 30px;
        position: relative;
    }

    .modal-header-custom h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .close-custom {
        position: absolute;
        right: 20px;
        top: 20px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
    }

    .close-custom:hover {
        color: white;
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .modal-body-custom {
        padding: 30px;
    }

    .form-group-custom {
        margin-bottom: 25px;
    }

    .form-label-custom {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
    }

    .form-display-custom {
        display: block;
        padding: 12px 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border: 1px solid #d1d5db;
        border-radius: 12px;
        color: #374151;
        font-weight: 500;
    }

    .form-input-custom {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #d1d5db;
        border-radius: 12px;
        box-sizing: border-box;
        font-size: 16px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input-custom:focus {
        outline: none;
        border-color: #00393a;
        box-shadow: 0 0 0 3px rgba(0, 57, 58, 0.1);
    }

    .nilai-info {
        font-size: 13px;
        color: #6b7280;
        margin-top: 5px;
        font-style: italic;
    }

    .modal-footer-custom {
        padding: 20px 30px 30px;
        background: #f8fafc;
        border-top: 1px solid #e5e7eb;
        text-align: right;
    }

    .btn-submit-custom {
        background: linear-gradient(135deg, #00393a 0%, #005555 100%);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 57, 58, 0.3);
    }

    .btn-submit-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 57, 58, 0.4);
    }

    .btn-submit-custom:active {
        transform: translateY(0);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .table-container {
            margin: 0 10px;
            overflow-x: auto;
        }

        .data-table {
            min-width: 800px;
        }

        .modal-content-custom {
            margin: 10% auto;
            width: 95%;
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

        .modal-content-custom {
            margin: 15% auto;
        }

        .modal-header-custom {
            padding: 20px;
        }

        .modal-body-custom {
            padding: 20px;
        }

        .modal-footer-custom {
            padding: 15px 20px 20px;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0 15px;
        }

        .hero h1 {
            font-size: 28px;
        }

        .hero p {
            font-size: 15px;
        }

        .modal-content-custom {
            margin: 20% auto;
            width: 98%;
        }

        .modal-header-custom h3 {
            font-size: 20px;
        }

        .close-custom {
            font-size: 28px;
            width: 35px;
            height: 35px;
        }
    }

    /* Section header styles for cleaner titles */
    .section-header {
        padding: 20px 24px 0 24px;
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 12px;
    }

    .section-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #00393a;
        letter-spacing: 0.2px;
    }

    .section-header .subtitle {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
    }
</style>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <a href="{{ route('talenta.dashboard') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        <h1 class="hero-title">Penilaian Tugas</h1>
        <p>Tugas peserta talenta berdasarkan materi yang Anda ajarkan.</p>
    </div>
</section>

<!-- CONTENT -->
<section class="talenta-penilaian">
    <div class="container">

        <!-- PENILAIAN TUGAS -->
        <div id="penilaian-section" class="data-section animate">
            <div class="table-container">
                @php
                    // Areas (slugs) the current user is allowed to assess
                    $allowedAreas = collect($materis ?? [])->pluck('slug')->toArray();
                    $tugasCollection = collect($tugas ?? []);

                    // Separate types: Terstruktur and Onsite (non-terstruktur), and Kelompok
                    $terstruktur = $tugasCollection->filter(function ($t) {
                        if (!isset($t->jenis_tugas)) return false;
                        $j = strtolower(str_replace([' ', '_'], ['_', ''], $t->jenis_tugas));
                        return strpos($j, 'terstruktur') !== false;
                    })->values();

                    $onsite = $tugasCollection->filter(function ($t) {
                        if (!isset($t->jenis_tugas)) return false;
                        $j = strtolower(str_replace([' ', '_'], ['_', ''], $t->jenis_tugas));
                        // onsite but not terstruktur (to avoid duplicates)
                        return strpos($j, 'onsite') !== false && strpos($j, 'terstruktur') === false;
                    })->values();

                    $kelompok = $tugasCollection->filter(function ($t) {
                        if (!isset($t->jenis_tugas)) return false;
                        $j = strtolower($t->jenis_tugas);
                        return strpos($j, 'kelompok') !== false;
                    })->values();

                    // Areas to show on info cards: prefer provided $materis (slugs), fallback to areas present in tugas
                    $areas = collect($materis ?? [])->pluck('slug')->filter()->values();
                    if ($areas->isEmpty()) {
                        $areas = $tugasCollection->pluck('area')->filter()->unique()->values();
                    }

                    // Build counts per area for each jenis
                    $countsByArea = [];
                    foreach ($areas as $area) {
                        $countsByArea[$area] = [
                            'terstruktur' => $terstruktur->where('area', $area)->count(),
                            'onsite' => $onsite->where('area', $area)->count(),
                            'kelompok' => $kelompok->where('area', $area)->count(),
                        ];
                    }
                @endphp

                {{-- Info cards: jumlah per area untuk Onsite / Terstruktur / Kelompok --}}
                <div style="padding:20px 24px;">
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
                        @foreach($countsByArea as $area => $counts)
                            <div class="table-container" style="padding:14px;">
                                <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                                    <div>
                                        <h4 style="margin:0 0 6px 0; font-size:16px;">{{ ucwords(str_replace('-', ' ', $area)) }}</h4>
                                        <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                                            <span class="badge bg-info">Onsite: {{ $counts['onsite'] }}</span>
                                            <span class="badge bg-warning text-dark">Terstruktur: {{ $counts['terstruktur'] }}</span>
                                            <span class="badge bg-secondary">Kelompok: {{ $counts['kelompok'] }}</span>
                                        </div>
                                    </div>
                                    <div style="font-size:22px; font-weight:700; color:#00393a;">
                                        {{ array_sum($counts) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Render Onsite (non-terstruktur) group --}}
                <div class="section-header">
                    <h3>Tugas Onsite</h3>
                    <div class="subtitle">Daftar tugas onsite yang dikumpulkan peserta</div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Sekolah/Madrasah</th>
                            <th>Area Tugas</th>
                            <th>Jenis Tugas</th>
                            <th>Tanggal Submit</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($onsite->isEmpty())
                            <tr>
                                <td colspan="8" class="no-data">Belum ada tugas Onsite yang disubmit untuk materi Anda</td>
                            </tr>
                        @else
                            @foreach($onsite as $index => $tugasItem)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tugasItem->user->name ?? 'N/A' }}</td>
                                    <td>{{ $tugasItem->user->madrasah->name ?? 'N/A' }}</td>
                                    <td>{{ ucwords(str_replace('-', ' ', $tugasItem->area)) }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $tugasItem->jenis_tugas)) }}</td>
                                    <td>{{ $tugasItem->submitted_at ? $tugasItem->submitted_at->format('d M Y H:i') : 'N/A' }}</td>
                                    @php
                                        $nilaiCollection = $tugasItem->nilai ?? collect();
                                        $currentUserNilai = $nilaiCollection->where('penilai_id', Auth::id())->first();
                                        $averageNilai = $nilaiCollection->avg('nilai');
                                    @endphp
                                    <td>
                                        @if($currentUserNilai)
                                            <span class="badge bg-success">{{ $currentUserNilai->nilai }}</span>
                                        @else
                                            @if(Auth::id() === 2472 && $averageNilai)
                                                <span class="text-muted">Rata-rata: {{ number_format($averageNilai, 1) }}</span>
                                            @else
                                                <span class="text-muted">Belum dinilai oleh Anda</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($tugasItem->file_path)
                                            <a href="{{ asset('/' . $tugasItem->file_path) }}" target="_blank" class="action-btn btn-view">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                            <a href="{{ asset('/' . $tugasItem->file_path) }}" download class="action-btn btn-download">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                        @if(Auth::id() === 2472 || in_array($tugasItem->area, $allowedAreas, true))
                                            <button type="button" class="action-btn btn-nilai" onclick="openNilaiModal({{ $tugasItem->id }}, '{{ $tugasItem->user->name }}', '{{ $tugasItem->area }}', {{ $currentUserNilai ? $currentUserNilai->nilai : 'null' }}, {{ (Auth::id() === 2472 && $averageNilai) ? number_format($averageNilai, 1) : 'null' }})">
                                                <i class="bi bi-star"></i> Nilai
                                                @if($currentUserNilai)
                                                    ({{ $currentUserNilai->nilai }})
                                                @endif
                                                @if(Auth::id() === 2472 && $averageNilai)
                                                    <br><small>Rata-rata: {{ number_format($averageNilai, 1) }}</small>
                                                @endif
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{-- Render Terstruktur group --}}
                <div class="section-header">
                    <h3>Tugas Terstruktur</h3>
                    <div class="subtitle">Daftar tugas Terstruktur yang dikumpulkan peserta</div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Sekolah/Madrasah</th>
                            <th>Area Tugas</th>
                            <th>Jenis Tugas</th>
                            <th>Tanggal Submit</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($terstruktur->isEmpty())
                            <tr>
                                <td colspan="8" class="no-data">Belum ada tugas Terstruktur yang disubmit untuk materi Anda</td>
                            </tr>
                        @else
                            @foreach($terstruktur as $index => $tugasItem)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tugasItem->user->name ?? 'N/A' }}</td>
                                    <td>{{ $tugasItem->user->madrasah->name ?? 'N/A' }}</td>
                                    <td>{{ ucwords(str_replace('-', ' ', $tugasItem->area)) }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $tugasItem->jenis_tugas)) }}</td>
                                    <td>{{ $tugasItem->submitted_at ? $tugasItem->submitted_at->format('d M Y H:i') : 'N/A' }}</td>
                                    @php
                                        $nilaiCollection = $tugasItem->nilai ?? collect();
                                        $currentUserNilai = $nilaiCollection->where('penilai_id', Auth::id())->first();
                                        $averageNilai = $nilaiCollection->avg('nilai');
                                    @endphp
                                    <td>
                                        @if($currentUserNilai)
                                            <span class="badge bg-success">{{ $currentUserNilai->nilai }}</span>
                                        @else
                                            @if(Auth::id() === 2472 && $averageNilai)
                                                <span class="text-muted">Rata-rata: {{ number_format($averageNilai, 1) }}</span>
                                            @else
                                                <span class="text-muted">Belum dinilai oleh Anda</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($tugasItem->file_path)
                                            <a href="{{ asset('/' . $tugasItem->file_path) }}" target="_blank" class="action-btn btn-view">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                            <a href="{{ asset('/' . $tugasItem->file_path) }}" download class="action-btn btn-download">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                        @if(Auth::id() === 2472 || in_array($tugasItem->area, $allowedAreas, true))
                                            <button type="button" class="action-btn btn-nilai" onclick="openNilaiModal({{ $tugasItem->id }}, '{{ $tugasItem->user->name }}', '{{ $tugasItem->area }}', {{ $currentUserNilai ? $currentUserNilai->nilai : 'null' }}, {{ (Auth::id() === 2472 && $averageNilai) ? number_format($averageNilai, 1) : 'null' }})">
                                                <i class="bi bi-star"></i> Nilai
                                                @if($currentUserNilai)
                                                    ({{ $currentUserNilai->nilai }})
                                                @endif
                                                @if(Auth::id() === 2472 && $averageNilai)
                                                    <br><small>Rata-rata: {{ number_format($averageNilai, 1) }}</small>
                                                @endif
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                {{-- Render Kelompok group --}}
                <div class="section-header">
                    <h3>Tugas Kelompok</h3>
                    <div class="subtitle">Daftar tugas Kelompok yang dikumpulkan peserta</div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kelompok</th>
                            {{-- <th>Sekolah/Madrasah</th> --}}
                            <th>Area Tugas</th>
                            <th>Jenis Tugas</th>
                            <th>Tanggal Submit</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($kelompok->isEmpty())
                            <tr>
                                <td colspan="8" class="no-data">Belum ada tugas Kelompok yang disubmit untuk materi Anda</td>
                            </tr>
                        @else
                            @foreach($kelompok as $index => $tugasItem)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tugasItem->kelompok->nama_kelompok ?? $tugasItem->user->name ?? 'N/A' }}</td>
                                    {{-- <td>{{ $tugasItem->user->madrasah->name ?? 'N/A' }}</td> --}}
                                    <td>{{ ucwords(str_replace('-', ' ', $tugasItem->area)) }}</td>
                                    <td>{{ ucwords(str_replace('_', ' ', $tugasItem->jenis_tugas)) }}</td>
                                    <td>{{ $tugasItem->submitted_at ? $tugasItem->submitted_at->format('d M Y H:i') : 'N/A' }}</td>
                                    @php
                                        $nilaiCollection = $tugasItem->nilai ?? collect();
                                        $currentUserNilai = $nilaiCollection->where('penilai_id', Auth::id())->first();
                                        $averageNilai = $nilaiCollection->avg('nilai');
                                    @endphp
                                    <td>
                                        @if($currentUserNilai)
                                            <span class="badge bg-success">{{ $currentUserNilai->nilai }}</span>
                                        @else
                                            @if(Auth::id() === 2472 && $averageNilai)
                                                <span class="text-muted">Rata-rata: {{ number_format($averageNilai, 1) }}</span>
                                            @else
                                                <span class="text-muted">Belum dinilai oleh Anda</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($tugasItem->file_path)
                                            <a href="{{ asset('/' . $tugasItem->file_path) }}" target="_blank" class="action-btn btn-view">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                            <a href="{{ asset('/' . $tugasItem->file_path) }}" download class="action-btn btn-download">
                                                <i class="bi bi-download"></i> Download
                                            </a>
                                        @else
                                            <span class="text-muted">Tidak ada file</span>
                                        @endif
                                        @if(Auth::id() === 2472 || in_array($tugasItem->area, $allowedAreas, true))
                                            @php
                                                $displayName = $tugasItem->kelompok->nama_kelompok ?? ($tugasItem->user->name ?? 'N/A');
                                            @endphp
                                            <button type="button" class="action-btn btn-nilai" onclick="openNilaiModal({{ $tugasItem->id }}, '{{ $displayName }}', '{{ $tugasItem->area }}', {{ $currentUserNilai ? $currentUserNilai->nilai : 'null' }}, {{ (Auth::id() === 2472 && $averageNilai) ? number_format($averageNilai, 1) : 'null' }})">
                                                <i class="bi bi-star"></i> Nilai
                                                @if($currentUserNilai)
                                                    ({{ $currentUserNilai->nilai }})
                                                @endif
                                                @if(Auth::id() === 2472 && $averageNilai)
                                                    <br><small>Rata-rata: {{ number_format($averageNilai, 1) }}</small>
                                                @endif
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

@include('landing.footer')

<!-- MODAL NILAI TUGAS -->
<div id="nilaiModal" class="modal-overlay">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h3>Nilai Tugas</h3>
            <span class="close-custom">&times;</span>
        </div>
        <div class="modal-body-custom">
            <form id="nilaiForm">
                <input type="hidden" id="tugasId" name="tugas_id">
                <div class="form-group-custom">
                    <label for="namaPeserta" class="form-label-custom">Nama Peserta:</label>
                    <span id="namaPeserta" class="form-display-custom"></span>
                </div>
                <div class="form-group-custom">
                    <label for="areaTugas" class="form-label-custom">Area Tugas:</label>
                    <span id="areaTugas" class="form-display-custom"></span>
                </div>
                <div class="form-group-custom">
                    <label for="nilaiInput" class="form-label-custom">Nilai (0-100):</label>
                    <input type="number" id="nilaiInput" name="nilai" min="0" max="100" class="form-input-custom" placeholder="Masukkan nilai 0-100">
                    <div class="nilai-info">Masukkan nilai antara 0 hingga 100</div>
                </div>
                <div id="currentNilaiDisplay" class="nilai-info"></div>
                <div id="averageNilaiDisplay" class="nilai-info"></div>
            </form>
        </div>
        <div class="modal-footer-custom">
            <button type="submit" form="nilaiForm" class="btn-submit-custom">Simpan Nilai</button>
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Animation trigger
    const animateElements = document.querySelectorAll('.animate');
    if (animateElements.length > 0) {
        const animateObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('show');
                    }
                });
            },
            {
                threshold: 0.15
            }
        );

        animateElements.forEach(el => {
            animateObserver.observe(el);
            // Show immediately if already visible
            if (el.getBoundingClientRect().top < window.innerHeight) {
                el.classList.add('show');
            }
        });
    }

    // Modal functionality
    const modal = document.getElementById('nilaiModal');
    const closeBtn = document.querySelector('.close-custom');
    const form = document.getElementById('nilaiForm');
    const nilaiInput = document.getElementById('nilaiInput');

    // Close modal when clicking close button (guarded)
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }

    // Close modal when clicking outside
    window.addEventListener('click', function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });

    // helper: show small non-blocking feedback
    function showToast(message) {
        // lightweight: use console and non-blocking alert replacement
        console.info('Toast:', message);
    }

    // Save nilai via AJAX (send JSON payload, return a promise)
    function saveNilai(tugasId, nilai, showAlerts = false) {
        const url = '{{ route("talenta.simpan-nilai-tugas") }}';
        const payload = {
            tugas_id: tugasId,
            nilai: Number(nilai),
        };
        return fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(async response => {
            let body = null;
            try {
                body = await response.json();
            } catch (e) {
                // ignore parse errors; body stays null
            }

            // Success path
            if (response.ok && body && body.success) {
                const successMsg = body.message || 'Nilai berhasil disimpan.';
                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: successMsg,
                        timer: 1500,
                        showConfirmButton: false,
                    });
                } else if (showAlerts) {
                    alert(successMsg);
                } else {
                    showToast(successMsg);
                }
                return body;
            }

            // Error path - prefer server message when present
            const status = response.status;
            let errMsg = (body && body.message) ? body.message : null;

            // Map common statuses to clearer messages when backend doesn't provide details
            if (!errMsg) {
                if (status === 401) errMsg = 'Sesi telah berakhir. Silakan login kembali.';
                else if (status === 403) errMsg = 'Anda tidak memiliki izin untuk melakukan tindakan ini.';
                else if (status === 422) errMsg = 'Data tidak valid. Silakan periksa input Anda.';
                else errMsg = 'Terjadi kesalahan saat menyimpan nilai.';
            }

            // Determine a suitable title/icon for the alert
            let title = 'Gagal';
            let icon = 'error';
            if (status === 401) title = 'Sesi Habis';
            else if (status === 403 || (errMsg && /pemateri|akses/i.test(errMsg))) title = 'Akses Ditolak';
            else if (status === 422) title = 'Validasi';

            if (window.Swal) {
                window.Swal.fire({ icon, title, text: errMsg });
            } else if (showAlerts) {
                alert(errMsg);
            } else {
                console.error('SaveNilai error:', status, errMsg);
            }

            const error = new Error(errMsg || 'Error saving nilai');
            error.status = status;
            throw error;
        })
        .catch(error => {
            // network / parse errors
            console.error('Error saving nilai:', error);
            const fallbackMsg = error && error.message ? error.message : 'Terjadi kesalahan saat menyimpan nilai.';
            if (showAlerts) {
                if (window.Swal) {
                    window.Swal.fire({ icon: 'error', title: 'Gagal', text: fallbackMsg });
                } else {
                    alert(fallbackMsg);
                }
            }
            throw error;
        });
    }

    // Note: Auto-save on input was removed so data is only sent when the user
    // explicitly clicks the "Simpan Nilai" button. This prevents accidental
    // saves while the user is editing.

    // We intentionally do NOT attach a form submit handler so the form will
    // only be submitted when the user clicks the explicit save button below.
    // The footerSaveBtn click handler performs the save using the same
    // saveNilai() function.

    // Fallback: add click handler to the footer save button (in case form submission via form attr doesn't fire)
    const footerSaveBtn = document.querySelector('.btn-submit-custom');
    if (footerSaveBtn) {
        footerSaveBtn.addEventListener('click', function (e) {
            // If the button has form attribute it will trigger form submit; we still handle explicitly as a fallback
            if (form) {
                // Trigger the same logic as form.onsubmit
                e.preventDefault();
                const tugasId = document.getElementById('tugasId').value;
                const nilai = document.getElementById('nilaiInput').value;
                const n = parseInt(nilai, 10);
                if (!tugasId) return alert('Tugas tidak ditemukan.');
                if (isNaN(n) || n < 0 || n > 100) return alert('Nilai harus angka antara 0 - 100.');

                saveNilai(tugasId, n, true)
                    .then(() => {
                        modal.style.display = 'none';
                        location.reload();
                    })
                    .catch(() => {});
            }
        });
    }
});

// Function to open modal
function openNilaiModal(tugasId, namaPeserta, areaTugas, currentNilai, averageNilai) {
    document.getElementById('tugasId').value = tugasId;
    document.getElementById('namaPeserta').textContent = namaPeserta;
    document.getElementById('areaTugas').textContent = areaTugas.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    document.getElementById('nilaiInput').value = currentNilai || '';

    const averageDisplay = document.getElementById('averageNilaiDisplay');

    if (averageNilai !== null && averageNilai !== undefined) {
        averageDisplay.textContent = 'Rata-rata nilai: ' + averageNilai;
    } else {
        averageDisplay.textContent = '';
    }

    document.getElementById('nilaiModal').style.display = 'block';
}
</script>

<!-- Load SweetAlert2 if not already loaded on the page -->
<script>
if (!window.Swal) {
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    s.defer = true;
    document.head.appendChild(s);
}
</script>
