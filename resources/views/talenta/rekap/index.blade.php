@extends('layouts.master-without-nav')

@section('title', 'Rekap School Level')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
{{-- @include('talenta.partials.hero') --}}

<!-- Back button (no navbar) -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('talenta.dashboard') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #0f172a; margin-top: 20px;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #0f172a; font-size: 14px; margin-top:20px">Kembali</span>
</div>

<section class="section-clean">
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <h2 class="mb-3">Rekap School Level</h2>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">Rata-rata Struktur</h6>
                            <h3 class="mb-0">{{ $avgStruktur ?? 0 }}</h3>
                            <p class="small text-muted mb-0">dari {{ $totalSchools ?? 0 }} sekolah</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">Rata-rata Kompetensi</h6>
                            <h3 class="mb-0">{{ $avgKompetensi ?? 0 }}</h3>
                            <p class="small text-muted mb-0">dari {{ $totalSchools ?? 0 }} sekolah</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">Rata-rata Perilaku</h6>
                            <h3 class="mb-0">{{ $avgPerilaku ?? 0 }}</h3>
                            <p class="small text-muted mb-0">dari {{ $totalSchools ?? 0 }} sekolah</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-1">Rata-rata Keterpaduan</h6>
                            <h3 class="mb-0">{{ $avgKeterpaduan ?? 0 }}</h3>
                            <p class="small text-muted mb-0">dari {{ $totalSchools ?? 0 }} sekolah</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1">Distribusi Level</h6>
                                <div class="small text-muted">A: <strong>{{ $levelCounts['A'] ?? 0 }}</strong> &nbsp; B: <strong>{{ $levelCounts['B'] ?? 0 }}</strong> &nbsp; C: <strong>{{ $levelCounts['C'] ?? 0 }}</strong> &nbsp; D: <strong>{{ $levelCounts['D'] ?? 0 }}</strong></div>
                            </div>
                            <div>
                                <a href="#" class="btn btn-outline-secondary btn-sm">Export CSV</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Sekolah</th>
                                    <th>Struktur</th>
                                    <th>Kompetensi</th>
                                    <th>Perilaku</th>
                                    <th>Keterpaduan</th>
                                    <th>Total Nilai</th>
                                    <th>Level</th>
                                    <th style="width:120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scores as $s)
                                <tr>
                                    <td>
                                        {{ optional($s->school)->nama ?? 'N/A' }}
                                        <div class="small text-muted">Pengirim: {{ optional($s->submittedBy)->name ?? '-' }}</div>
                                    </td>
                                    <td>{{ $s->struktur ?? $s->layanan ?? 0 }}</td>
                                    <td>{{ $s->kompetensi ?? $s->tata_kelola ?? 0 }}</td>
                                    <td>{{ $s->perilaku ?? $s->jumlah_siswa ?? 0 }}</td>
                                    <td>{{ $s->keterpaduan ?? $s->jumlah_penghasilan ?? 0 }}</td>
                                    <td>{{ $s->total_skor }}</td>
                                    <td>{{ $s->level_sekolah }}</td>
                                    <td>
                                        <a href="{{ route('talenta.rekap.detail', $s->id) }}" class="btn btn-sm btn-info">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">{{ $scores->links() }}</div>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm border-0 sticky-top" style="top:90px;">
                <div class="card-body">
                    <h6 class="mb-2">Ringkasan</h6>
                    <p class="small text-muted mb-2">Total Sekolah: <strong>{{ $scores->total() ?? $scores->count() }}</strong></p>
                    <p class="small text-muted mb-1">Distribusi Level:</p>
                    <ul class="small list-unstyled">
                        <li class="mb-1">A: <strong>{{ \App\Models\SchoolScore::where('level_sekolah','A')->count() }}</strong></li>
                        <li class="mb-1">B: <strong>{{ \App\Models\SchoolScore::where('level_sekolah','B')->count() }}</strong></li>
                        <li class="mb-1">C: <strong>{{ \App\Models\SchoolScore::where('level_sekolah','C')->count() }}</strong></li>
                        <li class="mb-1">D: <strong>{{ \App\Models\SchoolScore::where('level_sekolah','D')->count() }}</strong></li>
                    </ul>
                    <p class="small text-muted">Klik Detail untuk melihat rekap lengkap per sekolah.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
