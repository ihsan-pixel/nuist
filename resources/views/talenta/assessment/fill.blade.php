@extends('layouts.master-without-nav')

@section('title', 'Isi Assessment - School Level')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
{{-- @include('talenta.partials.hero') --}}

<!-- Back button (no navbar) -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('talenta.tugas-level-1') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #0f172a;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #0f172a; font-size: 14px;">Kembali</span>
</div>

<section class="section-clean">
    <div class="container">
        <h2 class="section-title">Isi Assessment - School Level</h2>
        <p class="section-subtitle">Jawab pertanyaan berikut untuk menghitung skor School Level sekolah Anda.</p>

        <form id="assessmentForm" method="POST" action="{{ route('talenta.assessment.store') }}">
            @csrf

            <div class="row">
                <div class="col-12 col-lg-9">

                    @foreach($questions->groupBy('kategori') as $kategori => $qs)
                        <div class="card shadow-sm mb-4 border-0">
                            <div class="card-header bg-white">
                                <h5 class="mb-0" style="text-transform: capitalize;">{{ str_replace('_',' ', $kategori) }}</h5>
                            </div>
                            <div class="card-body">
                                @foreach($qs as $q)
                                    <div class="mb-3 question-block" data-qid="{{ $q->id }}">
                                        <label class="form-label fw-semibold">{{ $q->pertanyaan }}</label>
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]" id="answer_{{ $q->id }}_ya" value="Ya" {{ $loop->first ? 'required' : '' }}>
                                                <label class="form-check-label" for="answer_{{ $q->id }}_ya">Ya</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]" id="answer_{{ $q->id }}_tidak" value="Tidak">
                                                <label class="form-check-label" for="answer_{{ $q->id }}_tidak">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-center mt-3">
                        <button id="submitBtn" type="submit" class="btn btn-primary btn-lg px-5">Simpan Jawaban</button>
                    </div>

                </div>

                <div class="col-12 col-lg-3">
                    <div class="card shadow-sm mb-4 border-0 sticky-top" style="top: 90px;">
                        <div class="card-body">
                            <h6 class="mb-2">Ringkasan Jawaban</h6>
                            <p class="text-muted small mb-1">Terjawab: <span id="answeredCount">0</span> / <span id="totalCount">0</span></p>
                            <div class="progress mb-2" style="height:10px;">
                                <div id="answeredProgress" class="progress-bar bg-primary" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <p class="small text-muted">Pastikan semua pertanyaan dijawab sebelum menyimpan untuk hasil yang akurat.</p>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
