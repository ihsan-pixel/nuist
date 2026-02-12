@extends('layouts.master-without-nav')

@section('title', 'Penilaian Tugas Talenta - NUIST')
@section('description', 'Penilaian tugas peserta talenta berdasarkan materi yang diajarkan')

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

    /* Responsive */
    @media (max-width: 992px) {
        .table-container {
            margin: 0 10px;
            overflow-x: auto;
        }

        .data-table {
            min-width: 800px;
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
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Peserta</th>
                            <th>Sekolah/Madrasah</th>
                            <th>Area Tugas</th>
                            <th>Jenis Tugas</th>
                            <th>Tanggal Submit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tugas ?? [] as $index => $tugasItem)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $tugasItem->user->name ?? 'N/A' }}</td>
                            <td>{{ $tugasItem->user->madrasah->nama_madrasah ?? 'N/A' }}</td>
                            <td>{{ ucwords(str_replace('-', ' ', $tugasItem->area)) }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $tugasItem->jenis_tugas)) }}</td>
                            <td>{{ $tugasItem->submitted_at ? $tugasItem->submitted_at->format('d M Y H:i') : 'N/A' }}</td>
                            <td>
                                @if($tugasItem->file_path)
                                    <a href="{{ asset('storage/' . $tugasItem->file_path) }}" target="_blank" class="action-btn btn-view">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                    <a href="{{ asset('storage/' . $tugasItem->file_path) }}" download class="action-btn btn-download">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="no-data">Belum ada tugas yang disubmit untuk materi Anda</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

@include('landing.footer')

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
});
</script>
