@extends('layouts.master-without-nav')

@section('title', 'Rekap Kelulusan')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')

<style>
    .clickable-row { cursor: pointer; }
    .clickable-row:hover { background-color: #f4f7fb; }
    .modal-dialog.modal-wide {
        max-width: 1400px;
        width: 100%;
    }
    @media (max-width: 1400px) {
        .modal-dialog.modal-wide { max-width: 95%; }
    }
    .summary-card {
        border: 0;
        overflow: hidden;
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
        color: #fff;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
    }
    .summary-card .card-body {
        padding: 1.5rem !important;
    }
    .summary-card .text-muted {
        color: rgba(255, 255, 255, 0.72) !important;
    }
    .summary-stat {
        height: 100%;
        border-radius: 14px;
        padding: 0.85rem 1rem;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(8px);
    }
    .summary-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.72);
        margin-bottom: 0.35rem;
    }
    .summary-value {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1;
    }
    .formula-card {
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.98);
        color: #0f172a;
        padding: 1rem 1.1rem;
        height: 100%;
        box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.18);
    }
    .weight-chip {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 0.82rem;
        font-weight: 600;
        color: #0f172a;
    }
    .weight-chip span {
        color: #1d4ed8;
    }
    .rule-item {
        padding: 0.8rem 0.9rem;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        height: 100%;
    }
    .rule-item-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #1d4ed8;
        margin-bottom: 0.35rem;
    }
    .rule-item-text {
        font-size: 0.82rem;
        color: #475569;
        line-height: 1.45;
        margin-bottom: 0;
    }
    .table-card {
        border: 0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
    }
    .table-card .card-header {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.25rem 1.5rem;
    }
    .table-card .table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .table-card .table tbody td {
        vertical-align: middle;
        border-color: #eef2f7;
    }
    .table-score {
        font-weight: 700;
        color: #0f172a;
    }
    .participant-name {
        font-weight: 600;
        color: #0f172a;
    }
    .participant-school {
        color: #64748b;
        font-size: 0.875rem;
    }
    @media (max-width: 991.98px) {
        .summary-card .card-body {
            padding: 1.1rem !important;
        }
    }
</style>

<!-- Back button (no navbar) -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('talenta.dashboard') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #0f172a; margin-top: 20px;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #0f172a; font-size: 14px; margin-top: 20px">Kembali</span>
</div>

<section class="section-clean">
<div class="container">
    @php
        $avgUjian = $pesertaList->avg('avg_ujian') ?: 0;
        $avgTotal = $pesertaList->avg('total_score') ?: 0;
        $topPeserta = $pesertaList->sortByDesc('total_score')->first();
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="mb-1">Hasil Nilai Peserta Talenta</h2>
                    <p class="text-muted mb-0">Rekap akhir penilaian peserta berdasarkan komponen ujian, tugas, dan instrumen fasilitator.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card summary-card mb-4">
                <div class="card-body p-4 p-lg-4">
                    <div class="row g-3 align-items-stretch">
                        <div class="col-lg-4">
                            <div class="h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="text-uppercase small fw-semibold mb-2" style="letter-spacing: 0.08em;">Perhitungan Kelulusan</div>
                                    <h4 class="mb-2">Komposisi nilai akhir peserta</h4>
                                    <p class="text-muted mb-0">Ringkasan singkat bobot dan dasar perhitungan nilai akhir peserta talenta.</p>
                                </div>
                                <div class="row g-2 mt-3">
                                    <div class="col-6">
                                        <div class="summary-stat">
                                            <div class="summary-label">Peserta</div>
                                            <div class="summary-value">{{ $pesertaList->count() }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="summary-stat">
                                            <div class="summary-label">Rata-rata Total</div>
                                            <div class="summary-value">{{ number_format($avgTotal, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="summary-stat">
                                            <div class="summary-label">Rata-rata Ujian</div>
                                            <div class="summary-value">{{ number_format($avgUjian, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="summary-stat">
                                            <div class="summary-label">Skor Tertinggi</div>
                                            <div class="summary-value">{{ number_format($topPeserta->total_score ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="formula-card">
                                <div class="row g-3">
                                    <div class="col-lg-5">
                                        <div class="small fw-semibold text-uppercase text-primary mb-2" style="letter-spacing: 0.08em;">Bobot Penilaian</div>
                                        <div class="d-grid gap-2">
                                            <div class="weight-chip">Ujian <span>50%</span></div>
                                            <div class="weight-chip">On Site <span>10%</span></div>
                                            <div class="weight-chip">Terstruktur <span>10%</span></div>
                                            <div class="weight-chip">Kelompok <span>10%</span></div>
                                            <div class="weight-chip">Kehadiran <span>10%</span></div>
                                            <div class="weight-chip">Kedisiplinan <span>10%</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="small fw-semibold text-uppercase text-primary mb-2" style="letter-spacing: 0.08em;">Aturan Perhitungan</div>
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <div class="rule-item">
                                                    <div class="rule-item-title">Rumus</div>
                                                    <p class="rule-item-text">Total = Ujian x 0.5 + lima komponen lain x 0.1.</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="rule-item">
                                                    <div class="rule-item-title">Skala</div>
                                                    <p class="rule-item-text">Ujian 0-100. Instrumen 1-5 dikonversi ke skala 0-100.</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="rule-item">
                                                    <div class="rule-item-title">Sumber Data</div>
                                                    <p class="rule-item-text">Ujian akhir, penilaian tugas pemateri, data kehadiran dan fasilitator.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h5 class="mb-1">Rekapitulasi Peserta</h5>
                        <div class="small text-muted">Klik baris peserta untuk melihat detail penilaian lengkap.</div>
                    </div>
                    <div class="small text-muted">Total peserta: <span class="fw-semibold text-dark">{{ $pesertaList->count() }}</span></div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width:30px">No</th>
                                    <th>Asal Sekolah</th>
                                    <th>Nama Peserta</th>
                                    <th style="width: 100px">Kode</th>
                                    <th style="width:100px">On site</th>
                                    <th style="width:100px">Terstruktur</th>
                                    <th style="width:100px">Kelompok</th>

                                    <th style="width:100px">Ujian</th>
                                    <th style="width:100px">Kehadiran</th>
                                    <th style="width:100px">Kedisiplinan</th>
                                    <th style="width:100px">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesertaList as $peserta)
                                <tr data-bs-toggle="modal" data-bs-target="#detail-peserta-{{ $peserta->id }}" class="clickable-row" tabindex="0">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $peserta->namaMadrasah ?? $peserta->asal_sekolah ?? '—' }}</td>
                                    <td>
                                        <div class="participant-name">{{ $peserta->nama ?? ($peserta->user->name ?? '—') }}</div>
                                        <div class="participant-school">Klik untuk melihat detail penilaian</div>
                                    </td>
                                    <td>{{ $peserta->kode_peserta ?? '—' }}</td>
                                    <td>{{ number_format($peserta->avg_onsite ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_terstruktur ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_kelompok ?? 0, 2) }}</td>

                                    <td>{{ number_format($peserta->avg_ujian ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_kehadiran ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_kedisiplinan ?? 0, 2) }}</td>
                                    <td>
                                        <span class="table-score">{{ number_format($peserta->total_score ?? 0, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                                @php
                                    $avgUjian = $pesertaList->avg('avg_ujian') ?: 0;
                                    $avgOnsite = $pesertaList->avg('avg_onsite') ?: 0;
                                    $avgTerstruktur = $pesertaList->avg('avg_terstruktur') ?: 0;
                                    $avgKelompok = $pesertaList->avg('avg_kelompok') ?: 0;
                                    $avgKehadiran = $pesertaList->avg('avg_kehadiran') ?: 0;
                                    $avgKedisiplinan = $pesertaList->avg('avg_kedisiplinan') ?: 0;
                                    $avgTotal = $pesertaList->avg('total_score') ?: 0;
                                @endphp
                                <tr class="table-secondary">
                                    <td class="fw-semibold" colspan="4">Rata-rata</td>
                                    <td>{{ number_format($avgUjian,2) }}</td>
                                    <td>{{ number_format($avgOnsite,2) }}</td>
                                    <td>{{ number_format($avgTerstruktur,2) }}</td>
                                    <td>{{ number_format($avgKelompok,2) }}</td>
                                    <td>{{ number_format($avgKehadiran,2) }}</td>
                                    <td>{{ number_format($avgKedisiplinan,2) }}</td>
                                    <td>{{ number_format($avgTotal,2) }}</td>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                </div>
        </div>

        @foreach($pesertaList as $peserta)
        <div class="modal fade" id="detail-peserta-{{ $peserta->id }}" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-wide">
                <div class="modal-content border-0 shadow-sm">

                    <!-- HEADER -->
                    <div class="modal-header border-bottom">
                        <div>
                            <h5 class="modal-title fw-bold">
                                Detail Penilaian Peserta
                            </h5>
                            <div class="small text-muted">
                                Sistem Penilaian Talent Pool Training
                            </div>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>


                    <div class="modal-body" style="max-height:70vh; overflow:auto;">

                        <!-- PROFIL PESERTA -->
                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-4" style="text-align: center">
                                        <div class="small text-muted">Nama Peserta</div>
                                        <div class="fw-semibold">
                                            {{ $peserta->nama ?? ($peserta->user->name ?? '-') }}
                                        </div>
                                    </div>

                                    <div class="col-md-4" style="text-align: center">
                                        <div class="small text-muted">Asal Sekolah</div>
                                        <div class="fw-semibold">
                                            {{ $peserta->namaMadrasah ?? $peserta->asal_sekolah ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="col-md-4" style="text-align: center">
                                        <div class="small text-muted" style="text-align: center">Kode</div>
                                        <div class="fw-semibold">
                                            {{ $peserta->kode_peserta ?? '-' }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- RINGKASAN NILAI -->
                        <div class="row g-3 mb-4">

                            <div class="col-md-2">
                                <div class="card border text-center">
                                    <div class="card-body p-2">
                                        <div class="small text-muted">Ujian</div>
                                        <div class="fw-bold fs-5">
                                            {{ number_format($peserta->avg_ujian ?? 0,2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card border text-center">
                                    <div class="card-body p-2">
                                        <div class="small text-muted">On Site</div>
                                        <div class="fw-bold fs-5">
                                            {{ number_format($peserta->avg_onsite ?? 0,2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card border text-center">
                                    <div class="card-body p-2">
                                        <div class="small text-muted">Terstruktur</div>
                                        <div class="fw-bold fs-5">
                                            {{ number_format($peserta->avg_terstruktur ?? 0,2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card border text-center">
                                    <div class="card-body p-2">
                                        <div class="small text-muted">Kelompok</div>
                                        <div class="fw-bold fs-5">
                                            {{ number_format($peserta->avg_kelompok ?? 0,2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card border text-center">
                                    <div class="card-body p-2">
                                        <div class="small text-muted">Kehadiran</div>
                                        <div class="fw-bold fs-5">
                                            {{ number_format($peserta->avg_kehadiran ?? 0,2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="card border text-center">
                                    <div class="card-body p-2">
                                        <div class="small text-muted">Kedisiplinan</div>
                                        <div class="fw-bold fs-5">
                                            {{ number_format($peserta->avg_kedisiplinan ?? 0,2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- TOTAL NILAI -->
                        <div class="alert alert-light border text-center mb-4">
                            <strong>Total Nilai Akhir :</strong>
                            <span class="fs-5 fw-bold text-dark">
                                {{ number_format($peserta->total_score ?? 0,2) }}
                            </span>
                        </div>



                        <!-- TABEL TUGAS -->
                        <div class="card border">
                            <div class="card-header bg-light fw-semibold">
                                Data Tugas Peserta
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm align-middle mb-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th>Area</th>
                                            <th>Jenis Tugas</th>
                                            <th>Pengupload</th>
                                            <th>File</th>
                                            <th>Nilai</th>
                                            <th>Rincian</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse(($peserta->raw_tugas_uploads ?? collect()) as $t)

                                        <tr>

                                            <td>{{ $t->area ?? '-' }}</td>

                                            <td>{{ ucfirst($t->jenis_tugas ?? '-') }}</td>

                                            <td class="small">
                                                {{ $t->kelompok->nama_kelompok ?? ($t->user->name ?? '-') }}
                                            </td>

                                            <td>
                                                @if(!empty($t->file_path))
                                                <a href="{{ asset($t->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    Lihat File
                                                </a>
                                                @else
                                                -
                                                @endif
                                            </td>

                                            <td>

                                                @php
                                                $nilaiRows = ($peserta->raw_tugas_nilai ?? collect())->where('tugas_talenta_level1_id', $t->id);
                                                @endphp

                                                @if($nilaiRows->isNotEmpty())
                                                    {{ number_format($nilaiRows->avg('nilai'),0) }}
                                                @else
                                                    <span class="text-muted small">Belum dinilai</span>
                                                @endif

                                            </td>

                                            <td>
                                                <button class="btn btn-sm btn-outline-secondary"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#detail-{{ $peserta->id }}-{{ $t->id }}">
                                                Detail
                                                </button>
                                            </td>

                                        </tr>

                                        <tr class="collapse" id="detail-{{ $peserta->id }}-{{ $t->id }}">
                                            <td colspan="6" class="bg-light">

                                                @if($nilaiRows->isEmpty())
                                                <div class="text-muted small">
                                                    Belum ada penilaian untuk tugas ini
                                                </div>
                                                @else

                                                <table class="table table-sm mb-0">

                                                    <thead>
                                                        <tr>
                                                            <th>Penilai</th>
                                                            <th>Nilai</th>
                                                            <th>Tanggal</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach($nilaiRows as $nr)
                                                        <tr>
                                                            <td>{{ $nr->penilai->name ?? '-' }}</td>
                                                            <td>{{ number_format($nr->nilai ?? 0,0) }}</td>
                                                            <td class="small text-muted">
                                                                {{ optional($nr->created_at)->format('d M Y H:i') }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>

                                                </table>

                                                @endif

                                            </td>
                                        </tr>

                                        @empty

                                        <tr>
                                            <td colspan="6" class="text-center text-muted">
                                                Belum ada tugas yang diupload
                                            </td>
                                        </tr>

                                        @endforelse

                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <!-- INSTRUMEN PENILAIAN TERHADAP PESERTA -->
                        <div class="card border mt-4">
                            <div class="card-header bg-light fw-semibold">Instrumen Penilaian terhadap Peserta</div>
                            <div class="card-body p-3">
                                @php $penilaian = $peserta->raw_penilaian ?? collect(); @endphp
                                @if($penilaian->isEmpty())
                                    <div class="text-muted small">Belum ada instrumen penilaian untuk peserta ini.</div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Fasilitator</th>
                                                    <th>Materi</th>
                                                    <th>Praktik</th>
                                                    <th>Tugas</th>
                                                    <th>Partisipasi</th>
                                                    <th>Kehadiran</th>
                                                    <th>Disiplin</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($penilaian as $pi)
                                                    <tr>
                                                        <td class="small">{{ $pi->user->name ?? '-' }}</td>
                                                        <td class="small">{{ optional($pi->materi)->judul_materi ?? ($pi->keterangan ?? '-') }}</td>
                                                        <td>{{ $pi->praktik ?? '-' }}</td>
                                                        <td>{{ $pi->tugas ?? '-' }}</td>
                                                        <td>{{ $pi->partisipasi ?? '-' }}</td>
                                                        <td>{{ $pi->kehadiran ?? '-' }}</td>
                                                        <td>{{ $pi->disiplin ?? ($pi->sikap ?? '-') }}</td>
                                                        <td class="small text-muted">{{ optional($pi->created_at)->format('d M Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                @php
                                                    $avgPraktik = $penilaian->avg('praktik') ?: 0;
                                                    $avgTugas = $penilaian->avg('tugas') ?: 0;
                                                    $avgPart = $penilaian->avg('partisipasi') ?: 0;
                                                    $avgKehadiran = $penilaian->avg('kehadiran') ?: 0;
                                                    $avgDisiplin = $penilaian->map(function($x){ return $x->disiplin ?? $x->sikap ?? null; })->filter()->avg() ?: 0;
                                                @endphp
                                                <tr class="table-secondary">
                                                    <td class="fw-semibold" colspan="2">Rata-rata</td>
                                                    <td>{{ number_format($avgPraktik,2) }}</td>
                                                    <td>{{ number_format($avgTugas,2) }}</td>
                                                    <td>{{ number_format($avgPart,2) }}</td>
                                                    <td>{{ number_format($avgKehadiran,2) }}</td>
                                                    <td>{{ number_format($avgDisiplin,2) }}</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- PENILAIAN OLEH PESERTA (KE PEMATERI & KE FASILITATOR) -->
                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light fw-semibold">Penilaian oleh Peserta - Pemateri</div>
                                    <div class="card-body p-3">
                                                @php
                                                    $pemateriRatings = $peserta->penilaian_by_peserta_pemateri ?? collect();
                                                    $pemateriFirst = $pemateriRatings->first();
                                                    // exclude foreign keys that are not useful to display
                                                    $pemateriCols = $pemateriFirst ? array_filter($pemateriFirst->getFillable(), function($c){ return !in_array($c, ['talenta_pemateri_id','user_id']); }) : [];
                                                @endphp
                                                @if($pemateriRatings->isEmpty())
                                                    <div class="text-muted small">Belum ada penilaian pemateri oleh peserta ini.</div>
                                                @else
                                                    <div class="table-responsive">
                                                        <table class="table table-sm mb-0 align-middle">
                                                            <thead>
                                                                <tr>
                                                                    <th>Pemateri</th>
                                                                    @foreach($pemateriCols as $col)
                                                                        <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                                                                    @endforeach
                                                                    <th>Tanggal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($pemateriRatings as $pr)
                                                                    <tr>
                                                                        <td class="small">{{ optional($pr->pemateri)->nama ?? '-' }}</td>
                                                                        @foreach($pemateriCols as $col)
                                                                            <td>{{ $pr->{$col} ?? '-' }}</td>
                                                                        @endforeach
                                                                        <td class="small text-muted">{{ optional($pr->created_at)->format('d M Y') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-header bg-light fw-semibold">Penilaian oleh Peserta - Fasilitator</div>
                                    <div class="card-body p-3">
                                            @php
                                                $fasilitatorRatings = $peserta->penilaian_by_peserta_fasilitator ?? collect();
                                                $fasilitatorFirst = $fasilitatorRatings->first();
                                                $fasilitatorCols = $fasilitatorFirst ? array_filter($fasilitatorFirst->getFillable(), function($c){ return !in_array($c, ['talenta_fasilitator_id','user_id']); }) : [];
                                            @endphp
                                            @if($fasilitatorRatings->isEmpty())
                                                <div class="text-muted small">Belum ada penilaian fasilitator oleh peserta ini.</div>
                                            @else
                                                <div class="table-responsive">
                                                    <table class="table table-sm mb-0 align-middle">
                                                        <thead>
                                                            <tr>
                                                                <th>Fasilitator</th>
                                                                @foreach($fasilitatorCols as $col)
                                                                    <th>{{ ucwords(str_replace('_', ' ', $col)) }}</th>
                                                                @endforeach
                                                                <th>Tanggal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($fasilitatorRatings as $fr)
                                                                <tr>
                                                                    <td class="small">{{ optional($fr->fasilitator)->nama ?? '-' }}</td>
                                                                    @foreach($fasilitatorCols as $col)
                                                                        <td>{{ $fr->{$col} ?? '-' }}</td>
                                                                    @endforeach
                                                                    <td class="small text-muted">{{ optional($fr->created_at)->format('d M Y') }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

        {{-- <div class="col-lg-3">
            <div class="card shadow-sm border-0 sticky-top" style="top:90px;">
                <div class="card-body">
                    <h6 class="mb-2">Petunjuk</h6>
                    <p class="small text-muted mb-2">Tampilan ini menunjukkan komponen penilaian yang digunakan untuk menentukan kelulusan.</p>
                    <ul class="list-unstyled small">
                        <li>Nilai Ujian: bobot 50%</li>
                        <li>Nilai On site: bobot 10%</li>
                        <li>Nilai Terstruktur: bobot 10%</li>
                        <li>Nilai Kelompok: bobot 10%</li>
                        <li>Nilai Kehadiran: bobot 10%</li>
                        <li>Nilai Kedisiplinan: bobot 10%</li>
                    </ul>
                </div>
            </div>
        </div> --}}
    </div>
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

<script>
    // Allow opening modal via keyboard (Enter) for accessible rows
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.clickable-row').forEach(function (row) {
            row.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    row.click();
                }
            });
        });

        // Prevent accidental modal opening when clicking interactive controls inside a row
        document.querySelectorAll('.clickable-row a, .clickable-row button').forEach(function (el) {
            el.addEventListener('click', function (ev) { ev.stopPropagation(); });
        });
    });
</script>

@endsection
