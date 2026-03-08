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
        <div class="col-lg-12">
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
                                    <th style="width:30px">No</th>
                                    <th>Nama Peserta</th>
                                    <th>Asal Sekolah</th>
                                    <th>Kode Peserta</th>
                                    <th style="width:100px">Ujian</th>
                                    <th style="width:100px">On site</th>
                                    <th style="width:100px">Terstruktur</th>
                                    <th style="width:100px">Kelompok</th>
                                    <th style="width:100px">Kehadiran</th>
                                    <th style="width:100px">Kedisiplinan</th>
                                    <th style="width:100px">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesertaList as $peserta)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $peserta->nama ?? ($peserta->user->name ?? '—') }}</td>
                                    <td>{{ $peserta->namaMadrasah ?? $peserta->asal_sekolah ?? '—' }}</td>
                                    <td>{{ $peserta->kode_peserta ?? '—' }}</td>
                                    <td>{{ number_format($peserta->avg_ujian ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_onsite ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_terstruktur ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_kelompok ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_kehadiran ?? 0, 2) }}</td>
                                    <td>{{ number_format($peserta->avg_kedisiplinan ?? 0, 2) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detail-peserta-{{ $peserta->id }}">
                                            {{ number_format($peserta->total_score ?? 0, 2) }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Render modals for each peserta (raw data details) --}}
        @foreach($pesertaList as $peserta)
            <div class="modal fade" id="detail-peserta-{{ $peserta->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Nilai: {{ $peserta->nama ?? ($peserta->user->name ?? '—') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Penilaian (raw entries)</h6>
                            <div class="table-responsive mb-3">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Materi / Keterangan</th>
                                            <th>Nilai Ujian</th>
                                            <th>Praktik</th>
                                            <th>Tugas</th>
                                            <th>Partisipasi</th>
                                            <th>Kehadiran</th>
                                            <th>Disiplin / Sikap</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($peserta->raw_penilaian ?? collect() as $pen)
                                            <tr>
                                                <td>{{ $pen->materi ?? ($pen->keterangan ?? '—') }}</td>
                                                <td>{{ number_format($pen->nilai_ujian ?? 0, 2) }}</td>
                                                <td>{{ number_format($pen->praktik ?? 0, 2) }}</td>
                                                <td>{{ number_format($pen->tugas ?? 0, 2) }}</td>
                                                <td>{{ number_format($pen->partisipasi ?? 0, 2) }}</td>
                                                <td>{{ number_format($pen->kehadiran ?? 0, 2) }}</td>
                                                <td>{{ number_format($pen->disiplin ?? ($pen->sikap ?? 0), 2) }}</td>
                                                <td>{{ optional($pen->created_at)->format('Y-m-d') ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8" class="text-center">Belum ada penilaian</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <h6>Tugas & Nilai Tugas (raw entries)</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Area</th>
                                            <th>Jenis</th>
                                            <th>Kelompok</th>
                                            <th>Nilai</th>
                                            <th>Penilai</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($peserta->raw_tugas_nilai ?? collect() as $tn)
                                            <tr>
                                                <td>{{ $tn->tugas->area ?? '-' }}</td>
                                                <td>{{ $tn->tugas->jenis_tugas ?? '-' }}</td>
                                                <td>{{ $tn->tugas->kelompok->nama_kelompok ?? ($tn->tugas->user->name ?? '-') }}</td>
                                                <td>{{ number_format($tn->nilai ?? 0, 2) }}</td>
                                                <td>{{ $tn->penilai->name ?? '-' }}</td>
                                                <td>{{ optional($tn->created_at)->format('Y-m-d') ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="6" class="text-center">Belum ada nilai tugas</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

@endsection
