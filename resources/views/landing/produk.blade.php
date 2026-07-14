@extends('layouts.master-without-nav')

@section('title', 'Produk - NUIST')
@section('description', 'Ekosistem produk digital NUIST untuk sekolah, tenaga pendidik, dan pengurus.')

@section('css')
<style>
    * {
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body {
        margin: 0;
        background:
            radial-gradient(circle at top left, rgba(22, 163, 74, 0.12), transparent 32%),
            radial-gradient(circle at top right, rgba(13, 148, 136, 0.1), transparent 28%),
            linear-gradient(180deg, #f7fbf8 0%, #ffffff 38%, #f8fafc 100%);
        color: #172033;
    }

    .produk-page {
        padding: 92px 0 0;
    }

    .produk-shell {
        max-width: 1500px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .produk-hero {
        position: relative;
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(340px, 0.8fr);
        gap: 28px;
        padding: 44px;
        margin-bottom: 28px;
        border-radius: 36px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid rgba(22, 43, 36, 0.08);
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(16px);
    }

    .produk-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 16% 20%, rgba(34, 197, 94, 0.16), transparent 28%),
            radial-gradient(circle at 85% 12%, rgba(20, 184, 166, 0.14), transparent 24%);
        pointer-events: none;
    }

    .produk-hero-copy,
    .produk-hero-panel {
        position: relative;
        z-index: 1;
    }

    .produk-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(22, 163, 74, 0.08);
        color: #15803d;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .produk-eyebrow::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }

    .produk-title {
        margin: 0 0 18px;
        font-size: clamp(40px, 5vw, 66px);
        line-height: 0.98;
        letter-spacing: -0.04em;
        color: #172033;
    }

    .produk-title span {
        display: block;
        color: #15803d;
    }

    .produk-lead {
        max-width: 720px;
        margin: 0 0 28px;
        font-size: 18px;
        line-height: 1.8;
        color: #627081;
    }

    .produk-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
    }

    .produk-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 48px;
        padding: 0 22px;
        border-radius: 999px;
        border: 1px solid transparent;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease, color 0.2s ease;
    }

    .produk-button:hover {
        transform: translateY(-1px);
    }

    .produk-button-primary {
        background: linear-gradient(135deg, #15803d, #0f766e);
        color: #ffffff;
        box-shadow: 0 14px 28px rgba(21, 128, 61, 0.18);
    }

    .produk-button-secondary {
        background: rgba(255, 255, 255, 0.8);
        border-color: rgba(22, 43, 36, 0.1);
        color: #1f2937;
    }

    .produk-hero-panel {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .produk-summary-card {
        padding: 22px 24px;
        border-radius: 28px;
        background: linear-gradient(180deg, rgba(249, 250, 251, 0.98), rgba(243, 247, 246, 0.98));
        border: 1px solid rgba(22, 43, 36, 0.08);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
    }

    .produk-summary-card h2,
    .produk-summary-card h3 {
        margin: 0 0 8px;
        color: #172033;
    }

    .produk-summary-card h2 {
        font-size: 18px;
        font-weight: 700;
    }

    .produk-summary-card h3 {
        font-size: 15px;
        font-weight: 700;
    }

    .produk-summary-card p {
        margin: 0;
        font-size: 14px;
        line-height: 1.75;
        color: #667085;
    }

    .produk-stat-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .produk-stat {
        padding: 18px;
        border-radius: 22px;
        background: #ffffff;
        border: 1px solid rgba(22, 43, 36, 0.08);
    }

    .produk-stat strong {
        display: block;
        margin-bottom: 6px;
        font-size: 28px;
        line-height: 1;
        color: #172033;
    }

    .produk-stat span {
        font-size: 13px;
        color: #667085;
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

    .produk-groups {
        display: grid;
        gap: 26px;
        padding-bottom: 32px;
    }

    .produk-group {
        content-visibility: auto;
        contain-intrinsic-size: 600px;
        padding: 30px;
        border-radius: 34px;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(22, 43, 36, 0.08);
        box-shadow: 0 20px 44px rgba(15, 23, 42, 0.06);
    }

    .produk-group-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 24px;
    }

    .produk-group-kicker {
        display: inline-block;
        margin-bottom: 10px;
        color: #0f766e;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }

    .produk-group h2 {
        margin: 0 0 10px;
        font-size: 32px;
        line-height: 1.1;
        color: #172033;
    }

    .produk-group p {
        margin: 0;
        max-width: 760px;
        font-size: 15px;
        line-height: 1.8;
        color: #667085;
    }

    .produk-group-index {
        flex-shrink: 0;
        font-size: 46px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.06em;
        color: rgba(21, 128, 61, 0.18);
    }

    .produk-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 20px;
    }

    .produk-card {
        display: flex;
        flex-direction: column;
        min-height: 100%;
        padding: 22px;
        border-radius: 28px;
        background: linear-gradient(180deg, #ffffff, #f8fbfa);
        border: 1px solid rgba(22, 43, 36, 0.08);
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }

    .produk-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
        border-color: rgba(21, 128, 61, 0.18);
    }

    .produk-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 20px;
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
        width: 54px;
        height: 54px;
        border-radius: 18px;
        font-size: 26px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
    }

    .produk-icon.accent-emerald,
    .produk-card.accent-emerald .produk-cta {
        background: linear-gradient(135deg, rgba(22, 163, 74, 0.16), rgba(22, 163, 74, 0.08));
        color: #166534;
    }

    .produk-icon.accent-teal,
    .produk-card.accent-teal .produk-cta {
        background: linear-gradient(135deg, rgba(13, 148, 136, 0.16), rgba(20, 184, 166, 0.08));
        color: #0f766e;
    }

    .produk-icon.accent-amber,
    .produk-card.accent-amber .produk-cta {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.16), rgba(251, 191, 36, 0.08));
        color: #b45309;
    }

    .produk-icon.accent-sky,
    .produk-card.accent-sky .produk-cta {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.16), rgba(56, 189, 248, 0.08));
        color: #0369a1;
    }

    .produk-icon.accent-violet,
    .produk-card.accent-violet .produk-cta {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.16), rgba(167, 139, 250, 0.08));
        color: #6d28d9;
    }

    .produk-icon.accent-rose,
    .produk-card.accent-rose .produk-cta {
        background: linear-gradient(135deg, rgba(244, 63, 94, 0.16), rgba(251, 113, 133, 0.08));
        color: #be123c;
    }

    .produk-icon.accent-slate,
    .produk-card.accent-slate .produk-cta {
        background: linear-gradient(135deg, rgba(71, 85, 105, 0.16), rgba(148, 163, 184, 0.08));
        color: #334155;
    }

    .produk-icon.accent-orange,
    .produk-card.accent-orange .produk-cta {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.16), rgba(251, 146, 60, 0.08));
        color: #c2410c;
    }

    .produk-icon.accent-indigo,
    .produk-card.accent-indigo .produk-cta {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.16), rgba(129, 140, 248, 0.08));
        color: #4338ca;
    }

    .produk-card h3 {
        margin: 0 0 12px;
        font-size: 24px;
        line-height: 1.2;
        color: #172033;
    }

    .produk-card p {
        margin: 0 0 24px;
        font-size: 15px;
        line-height: 1.8;
        color: #667085;
    }

    .produk-card-foot {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: auto;
    }

    .produk-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 999px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        border: 1px solid transparent;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .produk-cta:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
    }

    .produk-cta-disabled {
        display: inline-flex;
        align-items: center;
        min-height: 42px;
        padding: 0 16px;
        border-radius: 999px;
        background: #f4f4f5;
        color: #71717a;
        font-size: 14px;
        font-weight: 600;
    }

    .produk-meta {
        font-size: 13px;
        color: #98a2b3;
    }

    @media (max-width: 1200px) {
        .produk-hero {
            grid-template-columns: 1fr;
        }

        .produk-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .produk-page {
            padding-top: 72px;
        }

        .produk-shell {
            padding: 0 14px;
        }

        .produk-hero,
        .produk-group {
            padding: 24px;
            border-radius: 28px;
        }

        .produk-title {
            font-size: 38px;
        }

        .produk-lead {
            font-size: 16px;
        }

        .produk-group-head {
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .produk-group h2 {
            font-size: 26px;
        }

        .produk-group-index {
            font-size: 34px;
        }

        .produk-grid,
        .produk-stat-grid {
            grid-template-columns: 1fr;
        }

        .produk-card {
            padding: 20px;
            border-radius: 24px;
        }

        .produk-card h3 {
            font-size: 21px;
        }
    }
</style>
@endsection

@section('content')
@include('landing.navbar')

<main class="produk-page">
    <div class="produk-shell">
        <section class="produk-hero">
            <div class="produk-hero-copy">
                <div class="produk-eyebrow">Ekosistem Solusi Digital</div>
                <h1 class="produk-title">Produk Digital <span>Nuist Untuk Operasional Pendidikan</span></h1>
                <p class="produk-lead">Kumpulan aplikasi dan layanan yang dirancang untuk membantu sekolah, tenaga pendidik, operator, dan pengurus bekerja lebih rapi, cepat, dan terhubung dalam satu ekosistem.</p>
                <div class="produk-hero-actions">
                    <a href="#produk-list" class="produk-button produk-button-primary">Jelajahi Produk</a>
                    <a href="{{ route('landing.sekolah') }}" class="produk-button produk-button-secondary" data-nav-ajax="true">Lihat Data Sekolah</a>
                </div>
            </div>

            <div class="produk-hero-panel">
                <div class="produk-summary-card">
                    <h2>Katalog Produk Nuist</h2>
                    <p>Halaman ini dibuat ringan tanpa galeri gambar besar, sehingga perpindahan dari menu navbar tetap terasa cepat saat memakai navigasi tanpa reload penuh.</p>
                </div>

                <div class="produk-stat-grid">
                    <div class="produk-stat">
                        <strong>{{ collect($productGroups)->sum(fn ($group) => count($group['products'])) }}</strong>
                        <span>Total produk & layanan</span>
                    </div>
                    <div class="produk-stat">
                        <strong>{{ collect($productGroups)->sum(fn ($group) => collect($group['products'])->where('status', 'LIVE')->count()) }}</strong>
                        <span>Produk live</span>
                    </div>
                </div>

                <div class="produk-summary-card">
                    <h3>Kelompok Produk</h3>
                    <ul class="produk-anchor-list">
                        @foreach($productGroups as $index => $group)
                            <li><a href="#group-{{ $index + 1 }}">{{ $group['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <section id="produk-list" class="produk-groups">
            @foreach($productGroups as $index => $group)
                <section class="produk-group" id="group-{{ $index + 1 }}">
                    <div class="produk-group-head">
                        <div>
                            <span class="produk-group-kicker">Kategori {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h2>{{ $group['title'] }}</h2>
                            <p>{{ $group['description'] }}</p>
                        </div>
                        <div class="produk-group-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <div class="produk-grid">
                        @foreach($group['products'] as $product)
                            <article class="produk-card accent-{{ $product['accent'] }}">
                                <div class="produk-card-head">
                                    <span class="produk-badge {{ $product['status'] === 'LIVE' ? 'live' : 'development' }}">
                                        {{ $product['status'] }}
                                    </span>
                                    <span class="produk-icon accent-{{ $product['accent'] }}">
                                        <i class='bx {{ $product['icon'] }}'></i>
                                    </span>
                                </div>

                                <h3>{{ $product['name'] }}</h3>
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
        </section>
    </div>
</main>

@include('landing.footer')
@endsection
