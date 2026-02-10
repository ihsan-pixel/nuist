@extends('layouts.master-without-nav')

@section('title', 'Data Talenta - NUIST')
@section('description', 'Data Peserta Talenta, Pemateri Talenta, Fasilitator Talenta, dan Materi Talenta')

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

    /* DATA TALENTA CONTENT */
    .talenta-data {
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

        .data-table {
            min-width: 800px;
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
        <h1 class="hero-title">Data Talenta</h1>
        <p>Data lengkap peserta talenta, pemateri, fasilitator, dan materi talenta dalam ekosistem pendidikan kami.</p>
    </div>
</section>

<!-- TAB NAVIGATION - OVERLAP HERO -->
<div class="tab-navigation-wrapper">
    <div class="tab-navigation">
        <div class="tab-buttons">
            <button class="tab-btn active" data-target="peserta-section">
                <i class="bi bi-people"></i> Peserta Talenta
            </button>
            <button class="tab-btn" data-target="pemateri-section">
                <i class="bi bi-person-badge"></i> Pemateri Talenta
            </button>
            <button class="tab-btn" data-target="fasilitator-section">
                <i class="bi bi-person-check"></i> Fasilitator Talenta
            </button>
            <button class="tab-btn" data-target="materi-section">
                <i class="bi bi-book"></i> Materi Talenta
            </button>
        </div>
    </div>
</div>

<!-- CONTENT -->
<section class="talenta-data">
    <div class="container">

        <!-- PESERTA TALENTA -->
        <div id="peserta-section" class="data-section animate fade-up tab-content active">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Email</th>
                            <th>Sekolah/Madrasah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesertaTalenta ?? [] as $index => $peserta)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $peserta->nama ?? 'N/A' }}</td>
                            <td>{{ $peserta->kode_peserta ?? 'N/A' }}</td>
                            <td>{{ $peserta->email ?? 'N/A' }}</td>
                            <td>{{ $peserta->nama_madrasah ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="no-data">Belum ada data peserta talenta</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PEMATERI TALENTA -->
        <div id="pemateri-section" class="data-section animate fade-up delay-1 tab-content" style="display: none;">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Materi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemateriTalenta ?? [] as $index => $pemateri)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $pemateri->nama ?? 'N/A' }}</td>
                            <td>{{ $pemateri->kode_pemateri ?? 'N/A' }}</td>
                            <td>{{ $pemateri->materi->judul_materi ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="no-data">Belum ada data pemateri talenta</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FASILITATOR TALENTA -->
        <div id="fasilitator-section" class="data-section animate fade-up delay-2 tab-content" style="display: none;">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kode</th>
                            <th>Materi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fasilitatorTalenta ?? [] as $index => $fasilitator)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $fasilitator->nama ?? 'N/A' }}</td>
                            <td>{{ $fasilitator->kode_fasilitator ?? 'N/A' }}</td>
                            <td>{{ $fasilitator->materi->judul_materi ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="no-data">Belum ada data fasilitator talenta</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MATERI TALENTA -->
        <div id="materi-section" class="data-section animate fade-up delay-3 tab-content" style="display: none;">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Materi</th>
                            <th>Judul Materi</th>
                            <th>Level</th>
                            <th>Tanggal Materi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materiTalenta ?? [] as $index => $materi)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $materi->kode_materi ?? 'N/A' }}</td>
                            <td>{{ $materi->judul_materi ?? 'N/A' }}</td>
                            <td>{{ $materi->level_materi ? 'Level ' . $materi->level_materi : 'N/A' }}</td>
                            <td>{{ $materi->tanggal_materi ? $materi->tanggal_materi->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="no-data">Belum ada data materi talenta</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>

<!-- FOOTER -->
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

    // Navigation Tab Functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');

            // Remove active class from all buttons
            tabButtons.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            // Hide all contents
            tabContents.forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });

            // Show target content
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.style.display = 'block';
                setTimeout(() => {
                    targetContent.classList.add('show');
                }, 10);
            }
        });
    });
});
</script>

