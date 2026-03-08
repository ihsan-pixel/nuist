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

                    <div class="modal-header border-0 bg-primary text-white">
                        <div class="d-flex align-items-center w-100">
                            <div class="me-3">
                                <span class="avatar rounded-circle bg-white text-primary d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;font-weight:700;">
                                    {{ strtoupper(substr(($peserta->nama ?? ($peserta->user->name ?? '-')),0,1)) }}
                                </span>
                            </div>
                            <div class="grow">
                                <h5 class="modal-title mb-0 text-white">Detail Nilai Peserta</h5>
                                <div class="small text-white-50">{{ $peserta->nama ?? ($peserta->user->name ?? '-') }} • {{ $peserta->kode_peserta ?? '-' }}</div>
                            </div>
                            <div class="text-end">
                                <div class="small text-white-50">Total: <span class="badge bg-light text-dark">{{ number_format($peserta->total_score ?? 0,2) }}</span></div>
                                <button type="button" class="btn btn-sm btn-light mt-2" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>


                    <div class="modal-body" style="max-height:72vh; overflow:auto;">

                        <div class="row g-3">
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h6 class="mb-3">Ringkasan Penilaian</h6>
                                        <ul class="list-group list-group-flush small">
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>Ujian</div>
                                                <div><span class="fw-semibold">{{ number_format($peserta->avg_ujian ?? 0,2) }}</span></div>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>On site</div>
                                                <div>{{ number_format($peserta->avg_onsite ?? 0,2) }}</div>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>Terstruktur</div>
                                                <div>{{ number_format($peserta->avg_terstruktur ?? 0,2) }}</div>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>Kelompok</div>
                                                <div>{{ number_format($peserta->avg_kelompok ?? 0,2) }}</div>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>Kehadiran</div>
                                                <div>{{ number_format($peserta->avg_kehadiran ?? 0,2) }}</div>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                <div>Kedisiplinan</div>
                                                <div>{{ number_format($peserta->avg_kedisiplinan ?? 0,2) }}</div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="text-center small text-muted">Data diambil dari penilaian dan tugas terkait.</div>
                            </div>

                            <div class="col-lg-8">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-0">
                                        <h6 class="px-3 pt-3">Tugas yang diupload (per materi)</h6>
                                        <div class="p-3">
                                            @php $tasksByArea = ($peserta->raw_tugas_uploads ?? collect())->groupBy('area'); @endphp
                                            @if($tasksByArea->isEmpty())
                                                <div class="text-muted">Belum ada tugas yang diupload.</div>
                                            @else
                                                @foreach($tasksByArea as $area => $tasks)
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <strong>{{ $area }}</strong>
                                                            <span class="small text-muted">{{ $tasks->count() }} tugas</span>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm align-middle mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width:30%">Tipe</th>
                                                                        <th style="width:30%">Pengupload</th>
                                                                        <th style="width:20%">File</th>
                                                                        <th style="width:10%">Nilai</th>
                                                                        <th style="width:10%">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($tasks as $t)
                                                                        <tr>
                                                                            <td>{{ ucfirst($t->jenis_tugas ?? '-') }}</td>
                                                                            <td class="small text-muted">{{ $t->kelompok->nama_kelompok ?? ($t->user->name ?? '-') }}</td>
                                                                            <td>
                                                                                @if(!empty($t->file_path))
                                                                                    <a class="btn btn-sm btn-outline-primary" href="{{ asset($t->file_path) }}" target="_blank">Lihat</a>
                                                                                @else
                                                                                    <span class="text-muted">-</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @php $nilaiRows = ($peserta->raw_tugas_nilai ?? collect())->where('tugas_talenta_level1_id', $t->id); @endphp
                                                                                @if($nilaiRows->isNotEmpty())
                                                                                    @php $avg = $nilaiRows->avg('nilai'); @endphp
                                                                                    <span class="badge bg-success">{{ number_format($avg,0) }}</span>
                                                                                @else
                                                                                    <span class="text-muted small">Belum</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                @php $rId = 'rincian-'.$peserta->id.'-'.$t->id; @endphp
                                                                                <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $rId }}" aria-expanded="false" aria-controls="{{ $rId }}">Rincian</button>
                                                                            </td>
                                                                        </tr>

                                                                        <tr class="collapse" id="{{ $rId }}">
                                                                            <td colspan="5" class="bg-light">
                                                                                @php $nilaiRowsForThis = ($peserta->raw_tugas_nilai ?? collect())->where('tugas_talenta_level1_id', $t->id); @endphp
                                                                                @if($nilaiRowsForThis->isEmpty())
                                                                                    <div class="small text-muted p-2">Belum ada nilai untuk tugas ini.</div>
                                                                                @else
                                                                                    <div class="p-2">
                                                                                        <table class="table table-borderless table-sm mb-0">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th>Penilai</th>
                                                                                                    <th>Nilai</th>
                                                                                                    <th>Tanggal</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @foreach($nilaiRowsForThis as $nr)
                                                                                                    <tr>
                                                                                                        <td class="small">{{ $nr->penilai->name ?? '—' }}</td>
                                                                                                        <td><span class="fw-semibold">{{ number_format($nr->nilai ?? 0,0) }}</span></td>
                                                                                                        <td class="small text-muted">{{ optional($nr->created_at)->format('d M Y H:i') ?? '-' }}</td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                @endif
                                                                            </td>
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
