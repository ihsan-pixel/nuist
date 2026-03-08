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
        <div class="modal fade" id="detail-peserta-{{ $peserta->id }}" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">

                    <div class="modal-header bg-light">
                        <div>
                            <h5 class="modal-title fw-bold">
                                Detail Nilai Peserta
                            </h5>
                            <small class="text-muted">
                                {{ $peserta->nama ?? ($peserta->user->name ?? '-') }}
                                • {{ $peserta->kode_peserta ?? '-' }}
                            </small>
                        </div>

                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>


                    <div class="modal-body" style="max-height:70vh; overflow:auto;">

                        <!-- INFO PESERTA -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row text-sm">

                                    <div class="col-md-4 mb-2">
                                        <strong>Nama Peserta</strong><br>
                                        {{ $peserta->nama ?? ($peserta->user->name ?? '-') }}
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <strong>Asal Sekolah</strong><br>
                                        {{ $peserta->namaMadrasah ?? $peserta->asal_sekolah ?? '-' }}
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <strong>Kode Peserta</strong><br>
                                        {{ $peserta->kode_peserta ?? '-' }}
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- RINGKASAN PENILAIAN -->
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body small">
                                <div class="row">
                                    <div class="col-md-4 mb-2"><strong>Ujian:</strong> {{ number_format($peserta->avg_ujian ?? 0,2) }}</div>
                                    <div class="col-md-4 mb-2"><strong>On site:</strong> {{ number_format($peserta->avg_onsite ?? 0,2) }}</div>
                                    <div class="col-md-4 mb-2"><strong>Terstruktur:</strong> {{ number_format($peserta->avg_terstruktur ?? 0,2) }}</div>
                                    <div class="col-md-4 mb-2"><strong>Kelompok:</strong> {{ number_format($peserta->avg_kelompok ?? 0,2) }}</div>
                                    <div class="col-md-4 mb-2"><strong>Kehadiran:</strong> {{ number_format($peserta->avg_kehadiran ?? 0,2) }}</div>
                                    <div class="col-md-4 mb-2"><strong>Kedisiplinan:</strong> {{ number_format($peserta->avg_kedisiplinan ?? 0,2) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- TUGAS UPLOAD PER MATERI -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white fw-semibold">Tugas yang diupload (per materi/area)</div>
                            <div class="card-body p-0">
                                @php $tasksByArea = ($peserta->raw_tugas_uploads ?? collect())->groupBy('area'); @endphp
                                @if($tasksByArea->isEmpty())
                                    <div class="p-3 text-muted">Belum ada tugas yang diupload.</div>
                                @else
                                    @foreach($tasksByArea as $area => $tasks)
                                        <div class="p-3 border-bottom">
                                            <h6 class="mb-2">{{ $area }}</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Jenis</th>
                                                            <th>Kelompok / Pengupload</th>
                                                            <th>File</th>
                                                            <th>Nilai (avg)</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($tasks as $t)
                                                            <tr>
                                                                <td>{{ ucfirst($t->jenis_tugas ?? '-') }}</td>
                                                                <td>{{ $t->kelompok->nama_kelompok ?? ($t->user->name ?? '-') }}</td>
                                                                <td>
                                                                    @if(!empty($t->file_path))
                                                                        <a href="{{ asset($t->file_path) }}" target="_blank">Lihat file</a>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        // prefer using the controller-loaded raw_tugas_nilai collection
                                                                        // because some nilai rows may not be eager-loaded on the tugas model
                                                                        $nilaiRows = ($peserta->raw_tugas_nilai ?? collect())->where('tugas_talenta_level1_id', $t->id);
                                                                    @endphp

                                                                    @if($nilaiRows->isNotEmpty())
                                                                        @php $avg = $nilaiRows->avg('nilai'); @endphp
                                                                        {{ number_format($avg,2) }}
                                                                    @else
                                                                        <span class="text-muted">Belum dinilai</span>
                                                                    @endif
                                                                </td>
                                                                <td>{{ ucfirst($t->status ?? '—') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>


                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
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

@endsection
