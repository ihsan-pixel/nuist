@extends('layouts.mobile')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="siswa-shell">
    @include('mobile.siswa.partials.styles')
    @include('mobile.siswa.partials.header', ['title' => 'Dashboard Siswa', 'subtitle' => $studentSchool->name ?? 'Akses pembayaran sekolah'])

    <section class="hero-card">
        <span class="hero-eyebrow"><i class="bx bx-shield-quarter"></i>Ringkasan semester ini</span>
        <h4>{{ $paymentCompletionRate }}% selesai</h4>
        <p class="mb-0">Pantau tagihan, pembayaran, reminder jatuh tempo, dan komunikasi dengan admin sekolah dari satu tampilan.</p>

        <div class="hero-meta">
            <span class="hero-chip"><i class="bx bx-buildings"></i>{{ $studentSchool->name ?? 'Sekolah aktif' }}</span>
            <span class="hero-chip"><i class="bx bx-wallet"></i>{{ $activeTagihan ? 'Ada tagihan berjalan' : 'Belum ada tagihan aktif' }}</span>
        </div>

        <div class="hero-stat-grid">
            <div class="hero-stat">
                <strong>Rp {{ number_format($totalTagihanNominal, 0, ',', '.') }}</strong>
                <small>Total tagihan</small>
            </div>
            <div class="hero-stat">
                <strong>Rp {{ number_format($totalTerbayarNominal, 0, ',', '.') }}</strong>
                <small>Total terbayar</small>
            </div>
            <div class="hero-stat">
                <strong>{{ $notifications->count() }}</strong>
                <small>Update baru</small>
            </div>
        </div>
    </section>

    <div class="summary-grid">
        <div class="mini-card">
            <small>Status tagihan aktif</small>
            <h4>{{ $activeTagihan ? '1' : '0' }}</h4>
            <div class="text-soft">{{ $activeTagihan?->nomor_tagihan ?? 'Tidak ada tagihan aktif' }}</div>
        </div>
        <div class="mini-card">
            <small>Riwayat pembayaran</small>
            <h4>{{ $payments->count() }}</h4>
            <div class="text-soft">Transaksi terdokumentasi</div>
        </div>
    </div>

    <div class="menu-grid">
        <a class="menu-item" href="{{ route('mobile.siswa.tagihan') }}"><i class="bx bx-wallet-alt"></i><span>Tagihan</span><small>Cek invoice aktif</small></a>
        <a class="menu-item" href="{{ route('mobile.siswa.pembayaran') }}"><i class="bx bx-credit-card-front"></i><span>Pembayaran</span><small>Lihat kanal bayar</small></a>
        <a class="menu-item" href="{{ route('mobile.siswa.riwayat') }}"><i class="bx bx-filter-alt"></i><span>Riwayat</span><small>Transaksi terdahulu</small></a>
        <a class="menu-item" href="{{ route('mobile.siswa.notifikasi') }}"><i class="bx bx-bell-ring"></i><span>Notifikasi</span><small>Update terbaru</small></a>
        <a class="menu-item" href="{{ route('mobile.siswa.chat') }}"><i class="bx bx-conversation"></i><span>Chat Admin</span><small>Tanya pembayaran</small></a>
        <a class="menu-item" href="{{ route('mobile.siswa.profile') }}"><i class="bx bx-id-card"></i><span>Profil</span><small>Data akun siswa</small></a>
    </div>

    <section class="section-card">
        <div class="section-title">
            <div>
                <h5>Grafik pembayaran</h5>
                <p class="section-subtitle">Perbandingan tagihan lunas dan belum lunas</p>
            </div>
            <span class="pill pill-success">{{ $chartSummary['lunas'] }} lunas</span>
        </div>
        <div class="bar-row">
            <div class="bar-label">Lunas</div>
            <div class="bar-track">
                <div class="bar-fill-success" style="width: {{ max(8, $chartSummary['lunas'] === 0 ? 0 : round(($chartSummary['lunas'] / max(1, $tagihans->count())) * 100)) }}%"></div>
            </div>
            <strong>{{ $chartSummary['lunas'] }}</strong>
        </div>
        <div class="bar-row">
            <div class="bar-label">Belum</div>
            <div class="bar-track">
                <div class="bar-fill-warning" style="width: {{ max(8, $chartSummary['belum'] === 0 ? 0 : round(($chartSummary['belum'] / max(1, $tagihans->count())) * 100)) }}%"></div>
            </div>
            <strong>{{ $chartSummary['belum'] }}</strong>
        </div>
    </section>

    @if($upcomingReminder)
    <section class="section-card">
        <div class="section-title">
            <div>
                <h5>Reminder otomatis H-3</h5>
                <p class="section-subtitle">Pengingat tagihan yang mendekati jatuh tempo</p>
            </div>
            <span class="pill pill-warning">Aktif</span>
        </div>
        <div class="list-item">
            <div class="list-kicker"><i class="bx bx-time-five"></i>Pengingat</div>
            <h6>{{ $upcomingReminder->title }}</h6>
            <p>{{ $upcomingReminder->message }}</p>
        </div>
    </section>
    @endif

    <section class="list-card">
        <div class="section-title">
            <div>
                <h5>Tagihan terbaru</h5>
                <p class="section-subtitle">Dua invoice terakhir yang perlu diperhatikan</p>
            </div>
            <a href="{{ route('mobile.siswa.tagihan') }}" class="text-soft">Lihat semua</a>
        </div>
        @forelse($tagihans->take(2) as $tagihan)
            <div class="list-item">
                <div class="list-kicker"><i class="bx bx-receipt"></i>{{ $tagihan->jenis_tagihan ?? 'SPP' }}</div>
                <h6>{{ $tagihan->nomor_tagihan }}</h6>
                <p>Periode {{ \Carbon\Carbon::createFromFormat('Y-m', $tagihan->periode)->translatedFormat('F Y') }}</p>
                <p>Jatuh tempo {{ optional($tagihan->jatuh_tempo)->translatedFormat('d M Y') }}</p>
                <div class="meta-row">
                    <span class="pill {{ $tagihan->status === 'lunas' ? 'pill-success' : ($tagihan->status === 'sebagian' ? 'pill-warning' : 'pill-danger') }}">{{ ucfirst(str_replace('_', ' ', $tagihan->status)) }}</span>
                    <strong>Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</strong>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bx bx-receipt"></i>
                <h6>Belum ada data tagihan</h6>
                <p>Masukkan data tagihan agar dashboard siswa menampilkan status pembayaran.</p>
            </div>
        @endforelse
    </section>
</div>

@include('mobile.siswa.partials.nav')
@endsection
