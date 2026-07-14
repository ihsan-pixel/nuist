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

    .produk-group-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .produk-intro {
        max-width: 980px;
        margin: 0 auto 48px;
        text-align: center;
    }

    .produk-intro-copy {
        margin-bottom: 28px;
    }

    .produk-intro-copy p {
        max-width: 780px;
        margin: 16px auto 0;
        color: #6b7280;
        font-size: 18px;
        line-height: 1.8;
    }

    .produk-metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .produk-metric {
        background: white;
        border: 1px solid rgba(0, 75, 76, 0.08);
        border-radius: 999px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
        padding: 16px 22px;
    }

    .produk-metric strong {
        display: block;
        margin-bottom: 4px;
        color: #004b4c;
        font-size: 28px;
        line-height: 1;
    }

    .produk-metric span {
        color: #6b7280;
        font-size: 14px;
        line-height: 1.4;
    }

    .produk-anchor-wrap {
        display: flex;
        justify-content: center;
        overflow-x: auto;
        padding-bottom: 6px;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .produk-anchor-wrap::-webkit-scrollbar {
        display: none;
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

    .produk-anchor-list {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        margin: 0;
        padding: 0;
        list-style: none;
        white-space: nowrap;
    }

    .produk-anchor-list a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 0 18px;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid rgba(0, 75, 76, 0.12);
        color: #166534;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .hero .produk-intro {
        max-width: 980px;
        margin: 0 auto;
    }

    .hero .section-title {
        margin-bottom: 0;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero .produk-intro-copy p {
        color: rgba(255, 255, 255, 0.92);
    }

    .hero .produk-metric {
        background: rgba(255, 255, 255, 0.96);
    }

    .hero .produk-anchor-list a {
        background: rgba(255, 255, 255, 0.96);
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
        grid-template-columns: repeat(3, minmax(0, 1fr));
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

        .produk-metrics,
        .produk-group-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .produk-list {
            grid-template-columns: 1fr;
        }

        .produk-intro {
            margin-bottom: 36px;
        }

        .produk-intro-copy p {
            font-size: 16px;
        }

        .content-card,
        .produk-group-card,
        .produk-card {
            margin: 0 10px;
            padding: 24px;
        }

        .produk-metric {
            border-radius: 20px;
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 40px;
        }
    }

    @media (max-width: 1200px) {
        .produk-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endsection

@section('content')
<div class="landing-page landing-product-page" data-landing-page="produk">
    <section id="hero" class="hero">
        <div class="container">
            <div class="produk-intro">
                <div class="produk-intro-copy animate fade-up">
                    <h1 class="section-title">Mengenal Produk NUIST</h1>
                    <p>Temukan layanan digital NUIST yang dirancang untuk mendukung tata kelola, operasional sekolah, dan pengembangan ekosistem pendidikan dalam satu tampilan yang lebih ringkas.</p>
                </div>

                <div class="produk-metrics animate fade-up delay-1">
                    <div class="produk-metric">
                        <strong>{{ collect($productGroups)->sum(fn ($group) => count($group['products'])) }}</strong>
                        <span>Total produk dan layanan</span>
                    </div>
                    <div class="produk-metric">
                        <strong>{{ collect($productGroups)->sum(fn ($group) => collect($group['products'])->where('status', 'LIVE')->count()) }}</strong>
                        <span>Produk live dan siap digunakan</span>
                    </div>
                    <div class="produk-metric">
                        <strong>{{ count($productGroups) }}</strong>
                        <span>Kelompok kategori produk</span>
                    </div>
                </div>

                <div class="produk-anchor-wrap animate fade-up delay-2">
                    <ul class="produk-anchor-list">
                        <li><a href="{{ route('landing.produk') }}#group-1">Platform Utama</a></li>
                        <li><a href="{{ route('landing.produk') }}#group-2">Layanan Pendidikan</a></li>
                        <li><a href="{{ route('landing.produk') }}#group-3">Operasional &amp; Pengembangan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="produk-content" class="produk-content">
        <div class="container">
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
