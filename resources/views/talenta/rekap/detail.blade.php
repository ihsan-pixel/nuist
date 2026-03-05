@extends('layouts.master-without-nav')

@section('title', 'Detail Nilai Sekolah')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')
@include('talenta.navbar')

<section class="section-clean">
<div class="container">
    <h2>Detail Nilai Sekolah</h2>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-1">Skor per Dimensi</h6>
                    <ul class="mb-0 small">
                        <li>Struktur: <strong>{{ $score->struktur ?? $score->layanan ?? 0 }}</strong></li>
                        <li>Kompetensi: <strong>{{ $score->kompetensi ?? $score->tata_kelola ?? 0 }}</strong></li>
                        <li>Perilaku: <strong>{{ $score->perilaku ?? $score->jumlah_siswa ?? 0 }}</strong></li>
                        <li>Keterpaduan: <strong>{{ $score->keterpaduan ?? $score->jumlah_penghasilan ?? 0 }}</strong></li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <p class="mb-0">Total Skor: <strong>{{ $score->total_skor }}</strong></p>
                    <p class="mb-0">Level Sekolah: <strong>{{ $score->level_sekolah }}</strong></p>
                    <p class="small text-muted mb-0">Pengirim: {{ optional($score->submittedBy)->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6>Informasi Sekolah</h6>
                    <p class="mb-0"><strong>{{ optional($score->school)->nama ?? 'N/A' }}</strong></p>
                    <p class="small text-muted mb-0">ID Sekolah: {{ $score->school_id ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mt-3">Jawaban per Pertanyaan</h5>
    @if(isset($answers) && $answers->count())
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width:60px">No</th>
                                <th>Pertanyaan</th>
                                <th style="width:90px">Jawaban</th>
                                <th style="width:120px">Teks Pilihan</th>
                                <th style="width:90px">Skor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($answers as $ans)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="max-width:440px">{{ optional($ans->question)->pertanyaan ?? '-' }}</td>
                                    <td>{{ strtoupper($ans->jawaban ?? '-') }}</td>
                                    <td class="small text-muted">{{ $ans->choice_text ?? (optional($ans->question)->choice_texts[$ans->jawaban ?? ''] ?? '-') }}</td>
                                    <td>{{ $ans->skor ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">Tidak ada jawaban tersimpan untuk sekolah ini.</div>
    @endif
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
