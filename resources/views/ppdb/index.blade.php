@extends('layouts.master-without-nav')

@section('title') PPDB NUIST 2025 @endsection

@section('content')

<style>
    * {
        margin: 0;
@include('partials.ppdb.navbar')

        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #16a34a 0%, #059669 50%, #0884d8 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.05);
        z-index: 0;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 1000px;
        margin: 0 auto;
    }

    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        animation: slideDown 0.8s ease;
    }

    .hero-section p {
        font-size: 1.3rem;
        margin-bottom: 8px;
        opacity: 0.95;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Info Cards */
    .info-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin: 50px auto;
        max-width: 1200px;
        padding: 0 20px;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-top: 5px solid;
        transition: all 0.3s ease;
        text-align: center;
    }

    .info-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
    }

    .info-card.card-1 {
        border-top-color: #16a34a;
    }

    .info-card.card-2 {
        border-top-color: #0884d8;
    }

    .info-card.card-3 {
        border-top-color: #16a34a;
    }

    .info-card-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .info-card h3 {
        font-size: 1.5rem;
        color: #1f2937;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .info-card p {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    /* Schools Section */
    .schools-section {
        background: #f9fafb;
        padding: 50px 20px;
    }

    .schools-header {
        text-align: center;
        margin-bottom: 40px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .schools-header h2 {
        font-size: 2.5rem;
        color: #1f2937;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .schools-header p {
        font-size: 1.1rem;
        color: #6b7280;
    }

    .schools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* School Card */
    .school-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        border-bottom: 4px solid #16a34a;
    }

    .school-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .school-card-header {
        background: linear-gradient(135deg, #16a34a 0%, #0884d8 100%);
        color: white;
        padding: 25px;
        min-height: 80px;
        display: flex;
        align-items: center;
    }

    .school-card-header h3 {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.4;
    }

    .school-card-body {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .school-year-badge {
        display: inline-block;
        background: #dcfce7;
        color: #166534;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 15px;
        width: fit-content;
    }

    .school-status {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-weight: 600;
        text-align: center;
        font-size: 0.9rem;
    }

    .status-open {
        background: #dcfce7;
        color: #166534;
        border-left: 4px solid #16a34a;
    }

    .status-waiting {
        background: #fef3c7;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    .schedule-info {
        margin: 15px 0;
        padding: 12px;
        background: #f3f4f6;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #374151;
    }

    .schedule-info strong {
        color: #1f2937;
    }

    .school-card-button {
        margin-top: auto;
        background: linear-gradient(135deg, #16a34a 0%, #059669 100%);
        color: white;
        border: none;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .school-card-button:hover {
        background: linear-gradient(135deg, #15803d 0%, #047857 100%);
        transform: scale(1.02);
    }

    /* Empty State */
    .empty-state {
        background: white;
        border-radius: 12px;
        padding: 60px 30px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 1.8rem;
        color: #1f2937;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .empty-state p {
        color: #6b7280;
        font-size: 1rem;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #16a34a 0%, #0884d8 100%);
        color: white;
        padding: 50px 20px;
        margin: 40px 0;
    }

    .cta-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
    }

    .cta-section h3 {
        font-size: 2rem;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .cta-section > div > p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.95;
    }

    .cta-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }

    .cta-button {
        background: white;
        color: #16a34a;
        padding: 14px 28px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        display: inline-block;
    }

    .cta-button:hover {
        background: #f0fdf4;
        transform: scale(1.05);
    }

    /* FAQ Section */
    .faq-section {
        background: white;
        padding: 50px 20px;
    }

    .faq-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .faq-header h2 {
        font-size: 2.5rem;
        color: #1f2937;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .faq-item {
        background: #f9fafb;
        border-radius: 8px;
        padding: 25px;
        border-left: 4px solid #16a34a;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        background: #f3f4f6;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .faq-item h4 {
        font-size: 1.1rem;
        color: #1f2937;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .faq-item p {
        color: #6b7280;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Info Important Section */
    .info-important {
        background: white;
        border-radius: 12px;
        border-left: 5px solid #16a34a;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 40px auto;
    }

    .info-important h3 {
        font-size: 1.5rem;
        color: #1f2937;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .info-important ul {
        list-style: none;
        padding: 0;
    }

    .info-important li {
        padding: 10px 0;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-important li:last-child {
        border-bottom: none;
    }

    .info-important li::before {
        content: '✓ ';
        color: #16a34a;
        font-weight: 700;
        margin-right: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2.2rem;
        }

        .hero-section p:first-of-type {
            font-size: 1.1rem;
        }

        .schools-header h2 {
            font-size: 1.8rem;
        }

        .info-cards {
            grid-template-columns: 1fr;
        }

        .cta-buttons {
            flex-direction: column;
        }

        .cta-button {
            width: 100%;
        }

        .schools-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .hero-section {
            padding: 40px 15px;
        }

        .hero-section h1 {
            font-size: 1.8rem;
        }

        .school-card-header h3 {
            font-size: 1.1rem;
        }
    }
</style>

<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <h1>🎓 PPDB NUIST 2025</h1>
        <p>Penerimaan Peserta Didik Baru</p>
        <p>Selamat Datang di Portal Pendaftaran Online NUIST</p>
    </div>
</div>

<!-- Info Cards -->
<div class="info-cards">
    <!-- Card 1 -->
    <div class="info-card card-1">
        <div class="info-card-icon">📋</div>
        <h3>Pendaftaran Mudah</h3>
        <p>Proses pendaftaran yang simple dan cepat hanya dalam 3 langkah</p>
    </div>

    <!-- Card 2 -->
    <div class="info-card card-2">
        <div class="info-card-icon">🔒</div>
        <h3>Data Aman</h3>
        <p>Sistem keamanan terjamin untuk melindungi data pribadi Anda</p>
    </div>

    <!-- Card 3 -->
    <div class="info-card card-3">
        <div class="info-card-icon">⚡</div>
        <h3>Hasil Cepat</h3>
        <p>Verifikasi dan hasil seleksi diumumkan dengan transparan dan cepat</p>
    </div>
</div>

<!-- Schools Section -->
<div class="schools-section">
    <div class="schools-header">
        <h2>Pilih Sekolah/Madrasah</h2>
        <p>Daftar Madrasah/Sekolah yang membuka PPDB NUIST 2025</p>
    </div>

    @if($sekolah->count() > 0)
        <div class="schools-grid">
            @foreach($sekolah as $item)
            <a href="{{ route('ppdb.sekolah', $item->slug) }}" class="school-card">
                <!-- Header with gradient -->
                <div class="school-card-header">
                    <h3>{{ $item->nama_sekolah }}</h3>
                </div>

                <!-- Body -->
                <div class="school-card-body">
                    <span class="school-year-badge">Tahun {{ $item->tahun }}</span>

                    <!-- Status -->
                    @php
                        $isPembukaan = $item->jadwal_buka <= now() && $item->jadwal_tutup > now();
                        $statusClass = $isPembukaan ? 'status-open' : 'status-waiting';
                        $statusText = $isPembukaan ? '✓ Pendaftaran Dibuka' : '⏰ Menunggu Dibuka';
                    @endphp
                    <div class="school-status {{ $statusClass }}">
                        {{ $statusText }}
                    </div>

                    <!-- Jadwal Info -->
                    <div class="schedule-info">
                        📅 Buka: <strong>{{ $item->jadwal_buka->format('d M Y') }}</strong>
                    </div>
                    <div class="schedule-info">
                        ⏱️ Tutup: <strong>{{ $item->jadwal_tutup->format('d M Y') }}</strong>
                    </div>

                    <!-- Button -->
                    <button class="school-card-button">
                        Pelajari & Daftar →
                    </button>
                </div>
            </a>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>Tidak ada sekolah yang membuka PPDB saat ini</h3>
            <p>Silakan kembali lagi nanti untuk melihat jadwal pendaftaran terbaru</p>
        </div>
    @endif
</div>

<!-- CTA Section -->
<div class="cta-section">
    <div class="cta-content">
        <h3>Punya Pertanyaan?</h3>
        <p>Tim kami siap membantu Anda. Hubungi kami melalui:</p>
        <div class="cta-buttons">
            <a href="tel:+6281234567890" class="cta-button">📞 Hubungi Kami</a>
            <a href="mailto:ppdb@nuist.id" class="cta-button">📧 Email</a>
            <a href="https://wa.me/+6281234567890" target="_blank" class="cta-button">💬 WhatsApp</a>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="faq-section">
    <div class="faq-header">
        <h2>Pertanyaan Umum</h2>
    </div>

    <div class="faq-grid">
        <!-- FAQ 1 -->
        <div class="faq-item">
            <h4>❓ Syarat pendaftaran apa saja?</h4>
            <p>Siapa saja yang ingin melanjutkan sekolah dapat mendaftar dengan melengkapi dokumen yang diminta (KK dan Ijazah)</p>
        </div>

        <!-- FAQ 2 -->
        <div class="faq-item">
            <h4>❓ Berapa biaya pendaftaran?</h4>
            <p>Pendaftaran online PPDB NUIST sepenuhnya GRATIS, tanpa ada biaya apapun</p>
        </div>

        <!-- FAQ 3 -->
        <div class="faq-item">
            <h4>❓ Kapan hasil pengumuman?</h4>
            <p>Hasil seleksi akan diumumkan sesuai jadwal yang telah ditetapkan oleh masing-masing sekolah</p>
        </div>

        <!-- FAQ 4 -->
        <div class="faq-item">
            <h4>❓ Bisa daftar di multiple sekolah?</h4>
            <p>Ya, Anda dapat mendaftar di beberapa sekolah sesuai keinginan Anda</p>
        </div>
    </div>
</div>

<!-- Footer Info -->
<div style="max-width: 900px; margin: 40px auto 0; padding: 0 20px;">
    <div class="info-important">
        <h3>ℹ️ Informasi Penting</h3>
        <ul>
            <li>Pastikan dokumen Anda sudah siap sebelum mendaftar</li>
            <li>Isi data dengan benar dan lengkap</li>
            <li>Simpan nomor pendaftaran Anda untuk tracking status</li>
            <li>Pastikan koneksi internet stabil saat upload dokumen</li>
            <li>Maximal ukuran file dokumen 2MB</li>
        </ul>
    </div>
</div>

@include('partials.ppdb.footer')

<div style="height: 40px;"></div>

@endsection
