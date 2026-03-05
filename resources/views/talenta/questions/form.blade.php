@extends('layouts.master-without-nav')

@section('title', (isset($question) && $question->id) ? 'Edit Soal' : 'Tambah Soal')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
{{-- @include('talenta.partials.hero') --}}
{{-- @include('talenta.navbar') --}}

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
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">{{ isset($question) && $question->id ? 'Edit Soal' : 'Tambah Soal' }}</h2>
                <a href="{{ route('talenta.questions.index') }}" class="btn btn-outline-secondary">Kembali ke Daftar</a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ isset($question) && $question->id ? route('talenta.questions.update', $question) : route('talenta.questions.store') }}">
                        @csrf
                        @if(isset($question) && $question->id) @method('PUT') @endif

                        <div class="mb-3">
                            <label class="form-label">Dimensi / Kategori</label>
                            <select name="kategori" class="form-select">
                                @foreach(['Struktur','Kompetensi','Perilaku','Keterpaduan'] as $cat)
                                    <option value="{{ $cat }}" {{ (old('kategori', $question->kategori ?? '') == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea name="pertanyaan" class="form-control" rows="4">{{ old('pertanyaan', $question->pertanyaan ?? '') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <p class="small text-muted mb-2">Definisikan teks untuk pilihan A..E dan skor numeric untuk masing-masing pilihan. Contoh: A => teks, skor => 3</p>
                            </div>
                            @php
                                $existing_texts = old('choice_texts', $question->choice_texts ?? []);
                                $existing_scores = old('choice_scores', $question->choice_scores ?? []);
                            @endphp
                            @foreach(['A','B','C','D','E'] as $letter)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilihan {{ $letter }} - Teks</label>
                                    <input type="text" name="choice_texts[{{ $letter }}]" class="form-control" value="{{ $existing_texts[$letter] ?? '' }}" placeholder="Teks jawaban untuk {{ $letter }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pilihan {{ $letter }} - Skor</label>
                                    <input type="number" name="choice_scores[{{ $letter }}]" class="form-control" value="{{ $existing_scores[$letter] ?? '' }}" placeholder="Skor numeric untuk {{ $letter }}">
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="top:90px;">
                <div class="card-body">
                    <h6 class="mb-2">Panduan</h6>
                    <p class="small text-muted">Masukkan pertanyaan sesuai kategori. Skor Ya / Tidak akan digunakan untuk perhitungan School Level.</p>
                    <hr>
                    <p class="small text-muted mb-0">Kategori yang tersedia:</p>
                    <ul class="small">
                        <li>Layanan</li>
                        <li>Tata Kelola</li>
                        <li>Jumlah Siswa</li>
                        <li>Jumlah Penghasilan</li>
                        <li>Jumlah Prestasi</li>
                        <li>Jumlah Talenta</li>
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
