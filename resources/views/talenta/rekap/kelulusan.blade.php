@extends('layouts.master-without-nav')

@section('title', 'Rekap Kelulusan')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')

<!-- Back button (no navbar) -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('talenta.admin.dashboard') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #0f172a; margin-top: 20px;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #0f172a; font-size: 14px; margin-top: 20px">Kembali</span>
</div>

<section class="section-clean">
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">Rekap Kelulusan</h2>
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
                                    <th style="width:60px">No</th>
                                    <th>Nama Peserta</th>
                                    <th>Asal Sekolah</th>
                                    <th>Kode Peserta</th>
                                    <th style="width:110px">Nilai Ujian<br><small class="text-muted">(50%)</small></th>
                                    <th style="width:110px">Nilai On site<br><small class="text-muted">(10%)</small></th>
                                    <th style="width:110px">Nilai Terstruktur<br><small class="text-muted">(10%)</small></th>
                                    <th style="width:110px">Nilai Kelompok<br><small class="text-muted">(10%)</small></th>
                                    <th style="width:110px">Nilai Kehadiran<br><small class="text-muted">(10%)</small></th>
                                    <th style="width:110px">Nilai Kedisiplinan<br><small class="text-muted">(10%)</small></th>
                                    <th style="width:120px">Total (Weighted)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesertaList as $peserta)
                                @php
                                    // aggregate penilaian (may have multiple materi). Take average where applicable.
                                    $pen = $peserta->penilaian ?? collect();
                                    $ujian = $pen->avg('nilai_ujian') ?? 0;
                                    // mapping: praktik -> onsite, tugas -> terstruktur, partisipasi -> kelompok, kehadiran -> kehadiran, disiplin -> kedisiplinan
                                    $onsite = $pen->avg('praktik') ?? 0;
                                    $terstruktur = $pen->avg('tugas') ?? 0;
                                    $kelompok = $pen->avg('partisipasi') ?? 0;
                                    $kehadiran = $pen->avg('kehadiran') ?? 0;
                                    $kedisiplinan = $pen->avg('disiplin') ?? $pen->avg('sikap') ?? 0;
                                    $total = ($ujian * 0.5) + ($onsite * 0.1) + ($terstruktur * 0.1) + ($kelompok * 0.1) + ($kehadiran * 0.1) + ($kedisiplinan * 0.1);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration + (($pesertaList->currentPage() - 1) * $pesertaList->perPage()) }}</td>
                                    <td>{{ $peserta->nama ?? ($peserta->user->name ?? '—') }}</td>
                                    <td>{{ $peserta->namaMadrasah ?? $peserta->asal_sekolah ?? '—' }}</td>
                                    <td>{{ $peserta->kode_peserta ?? '—' }}</td>
                                    <td>{{ number_format(floatval($ujian), 2) }}%</td>
                                    <td>{{ number_format(floatval($onsite), 2) }}%</td>
                                    <td>{{ number_format(floatval($terstruktur), 2) }}%</td>
                                    <td>{{ number_format(floatval($kelompok), 2) }}%</td>
                                    <td>{{ number_format(floatval($kehadiran), 2) }}%</td>
                                    <td>{{ number_format(floatval($kedisiplinan), 2) }}%</td>
                                    <td><strong>{{ number_format($total, 2) }}%</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                {{ $scores->links() }}
            </div>

        </div>

        <div class="col-lg-3">
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
        </div>
    </div>
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
