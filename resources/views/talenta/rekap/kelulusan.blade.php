@extends('layouts.master-without-nav')

@section('title', 'Rekap Kelulusan')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')

<style>
    /* clickable row styling for professional look */
    .clickable-row { cursor: pointer; }
    .clickable-row:hover { background-color: #f8f9fa; }
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
                                <tr data-bs-toggle="modal" data-bs-target="#detail-peserta-{{ $peserta->id }}" class="clickable-row" tabindex="0">
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
                                        <span class="fw-semibold">{{ number_format($peserta->total_score ?? 0, 2) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
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
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @foreach($pesertaList as $peserta)
        <div class="modal fade" id="detail-peserta-{{ $peserta->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
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

                                    <div class="col-md-4">
                                        <div class="small text-muted">Nama Peserta</div>
                                        <div class="fw-semibold">
                                            {{ $peserta->nama ?? ($peserta->user->name ?? '-') }}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="small text-muted">Asal Sekolah</div>
                                        <div class="fw-semibold">
                                            {{ $peserta->namaMadrasah ?? $peserta->asal_sekolah ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="small text-muted">Kode Peserta</div>
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
                                        @php $pemateriRatings = $peserta->penilaian_by_peserta_pemateri ?? collect(); @endphp
                                        @if($pemateriRatings->isEmpty())
                                            <div class="text-muted small">Belum ada penilaian pemateri oleh peserta ini.</div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Pemateri</th>
                                                            <th>Kualitas Materi</th>
                                                            <th>Penyampaian</th>
                                                            <th>Umpan Balik</th>
                                                            <th>Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($pemateriRatings as $pr)
                                                            <tr>
                                                                <td class="small">{{ optional($pr->pemateri)->nama ?? '-' }}</td>
                                                                <td>{{ $pr->kualitas_materi ?? '-' }}</td>
                                                                <td>{{ $pr->penyampaian ?? '-' }}</td>
                                                                <td>{{ $pr->umpan_balik ?? '-' }}</td>
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
                                        @php $fasilitatorRatings = $peserta->penilaian_by_peserta_fasilitator ?? collect(); @endphp
                                        @if($fasilitatorRatings->isEmpty())
                                            <div class="text-muted small">Belum ada penilaian fasilitator oleh peserta ini.</div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Fasilitator</th>
                                                            <th>Pendampingan</th>
                                                            <th>Respons</th>
                                                            <th>Koordinasi</th>
                                                            <th>Tanggal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($fasilitatorRatings as $fr)
                                                            <tr>
                                                                <td class="small">{{ optional($fr->fasilitator)->nama ?? '-' }}</td>
                                                                <td>{{ $fr->pendampingan ?? '-' }}</td>
                                                                <td>{{ $fr->respons ?? '-' }}</td>
                                                                <td>{{ $fr->koordinasi ?? '-' }}</td>
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
