@extends('layouts.master-without-nav')

@section('landing_shell', '1')
@section('title', 'Produk - NUIST')
@section('description', 'Ekosistem produk digital NUIST untuk sekolah, tenaga pendidik, dan pengurus.')

@section('css')
<style data-landing-page-style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        background: #ffffff;
        color: #333;
        line-height: 1.6;
    }

    .container {
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .hero {
        position: relative;
        overflow: hidden;
        padding: 100px 20px;
        color: white;
        text-align: center;
        background: linear-gradient(135deg, #00393a, #005555, #00393a);
        border-radius: 48px;
        max-width: 1600px;
        margin: 65px auto 0;
        min-height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .hero > * {
        position: relative;
        z-index: 1;
    }

    .hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        color: white;
    }

    .hero-subtitle {
        font-size: 36px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #eda711;
    }

    .hero p {
        font-size: 20px;
        opacity: 0.9;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }

    .produk-content {
        padding: 80px 0;
        background: #f8fafc;
    }

    .section-title {
        text-align: center;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 50px;
        color: #004b4c;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        width: 0;
        height: 3px;
        background-color: #eda711;
        transition: width 0.3s ease, left 0.3s ease;
    }

    .section-title.active::after {
        width: 50%;
        left: 25%;
    }

    section:hover .section-title::after {
        width: 100%;
        left: 0;
    }

    .produk-overview-grid,
    .produk-group-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .produk-overview-grid {
        margin-bottom: 40px;
    }

    .content-card,
    .produk-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .content-card:hover,
    .produk-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .content-card h3,
    .produk-group-card h3 {
        font-size: 24px;
        font-weight: 700;
        color: #004b4c;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 3px solid #eda711;
    }

    .content-card p,
    .content-card ul,
    .produk-group-card p {
        color: #6b7280;
        font-size: 16px;
        line-height: 1.7;
    }

    .content-card ul {
        padding-left: 20px;
    }

    .content-card li {
        margin-bottom: 10px;
    }

    .produk-stat-value {
        display: block;
        margin-bottom: 8px;
        font-size: 36px;
        font-weight: 700;
        color: #004b4c;
        line-height: 1;
    }

    .produk-anchor-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .produk-anchor-list a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 0 14px;
        border-radius: 999px;
        background: rgba(22, 163, 74, 0.08);
        color: #166534;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .produk-group-grid {
        align-items: start;
    }

    .produk-group-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .produk-group-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .produk-group-kicker {
        display: inline-block;
        margin-bottom: 12px;
        color: #15803d;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .produk-group-description {
        margin-bottom: 24px;
        color: #6b7280;
        font-size: 16px;
        line-height: 1.7;
    }

    .produk-list {
        display: grid;
        gap: 18px;
    }

    .produk-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .produk-badge {
        display: inline-flex;
        align-items: center;
        min-height: 32px;
        padding: 0 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .produk-badge.live {
        background: rgba(22, 163, 74, 0.1);
        color: #166534;
    }

    .produk-badge.development {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
    }

    .produk-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border-radius: 16px;
        font-size: 24px;
        background: #f3f4f6;
        color: #004b4c;
    }

    .produk-card h4 {
        margin-bottom: 10px;
        font-size: 22px;
        line-height: 1.2;
        color: #004b4c;
    }

    .produk-card p {
        margin-bottom: 18px;
        color: #6b7280;
        font-size: 15px;
        line-height: 1.7;
    }

    .produk-card-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .produk-cta,
    .produk-cta-disabled {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
    }

    .produk-cta {
        background: rgba(22, 163, 74, 0.08);
        color: #166534;
    }

    .produk-cta-disabled {
        background: #f4f4f5;
        color: #71717a;
    }

    .produk-meta {
        font-size: 13px;
        color: #98a2b3;
    }

    .animate {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
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

    @media (max-width: 768px) {
        .hero {
            padding: 80px 20px;
            margin-top: 80px;
            min-height: auto;
        }

        .hero h1 {
            font-size: 32px;
        }

        .hero-subtitle {
            font-size: 28px;
        }

        .hero p {
            font-size: 16px;
        }

        .produk-overview-grid,
        .produk-group-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .content-card,
        .produk-group-card,
        .produk-card {
            margin: 0 10px;
            padding: 24px;
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 40px;
        }
    }
</style>
@endsection

@section('content')
<div class="landing-page landing-product-page" data-landing-page="produk">
    <section id="hero" class="hero">
        <div class="container">
            <h1 class="hero-title">Produk Kami</h1>
            <h2 class="hero-subtitle">Ekosistem Digital NUIST</h2>
            <p>Kumpulan aplikasi dan layanan yang dirancang untuk membantu sekolah, tenaga pendidik, operator, dan pengurus bekerja lebih rapi, cepat, dan terhubung dalam satu ekosistem.</p>
        </div>
    </section>

    <section id="produk-content" class="produk-content">
        <div class="container">
            <h2 class="section-title animate fade-up">Mengenal Produk NUIST</h2>

            <div class="produk-overview-grid">
                <div class="content-card animate fade-up">
                    <h3>Katalog Produk</h3>
                    <p>Halaman ini memuat seluruh produk dan layanan digital NUIST dalam satu tampilan yang rapi agar mudah dijelajahi tanpa perpindahan halaman yang berat.</p>
                </div>

                <div class="content-card animate fade-up delay-1">
                    <h3>Statistik Produk</h3>
                    <p><span class="produk-stat-value">{{ collect($productGroups)->sum(fn ($group) => count($group['products'])) }}</span>Total produk dan layanan yang tersedia dalam ekosistem NUIST.</p>
                    <p style="margin-top: 14px;"><span class="produk-stat-value">{{ collect($productGroups)->sum(fn ($group) => collect($group['products'])->where('status', 'LIVE')->count()) }}</span>Produk yang sudah live dan siap digunakan.</p>
                </div>

                <div class="content-card animate fade-up delay-2">
                    <h3>Kelompok Produk</h3>
                    <ul class="produk-anchor-list">
                        @foreach($productGroups as $index => $group)
                            <li><a href="#group-{{ $index + 1 }}">{{ $group['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="produk-group-grid">
                @foreach($productGroups as $index => $group)
                    <section class="produk-group-card animate fade-up" id="group-{{ $index + 1 }}">
                        <span class="produk-group-kicker">Kategori {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $group['title'] }}</h3>
                        <p class="produk-group-description">{{ $group['description'] }}</p>

                        <div class="produk-list">
                            @foreach($group['products'] as $product)
                                <article class="produk-card">
                                    <div class="produk-card-head">
                                        <span class="produk-badge {{ $product['status'] === 'LIVE' ? 'live' : 'development' }}">
                                            {{ $product['status'] }}
                                        </span>
                                        <span class="produk-icon">
                                            <i class='bx {{ $product['icon'] }}'></i>
                                        </span>
                                    </div>

                                    <h4>{{ $product['name'] }}</h4>
                                    <p>{{ $product['description'] }}</p>

                                    <div class="produk-card-foot">
                                        @if($product['link'])
                                            <a href="{{ $product['link'] }}" class="produk-cta">
                                                Lihat Selengkapnya
                                                <i class='bx bx-right-arrow-alt'></i>
                                            </a>
                                        @else
                                            <span class="produk-cta-disabled">Segera Hadir</span>
                                        @endif

                                        <span class="produk-meta">{{ $product['status'] === 'LIVE' ? 'Siap digunakan' : 'Roadmap berikutnya' }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
