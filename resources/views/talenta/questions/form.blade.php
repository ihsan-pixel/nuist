@extends('layouts.master-without-nav')

@section('title', (isset($question) && $question->id) ? 'Edit Soal' : 'Tambah Soal')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
{{-- @include('talenta.partials.hero') --}}
{{-- @include('talenta.navbar') --}}

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
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select">
                                @foreach(['layanan','tata_kelola','jumlah_siswa','jumlah_penghasilan','jumlah_prestasi','jumlah_talenta'] as $cat)
                                    <option value="{{ $cat }}" {{ (old('kategori', $question->kategori ?? '') == $cat) ? 'selected' : '' }}>{{ str_replace('_',' ', $cat) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pertanyaan</label>
                            <textarea name="pertanyaan" class="form-control" rows="4">{{ old('pertanyaan', $question->pertanyaan ?? '') }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Skor Ya</label>
                                <input type="number" name="skor_ya" class="form-control" value="{{ old('skor_ya', $question->skor_ya ?? 1) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Skor Tidak</label>
                                <input type="number" name="skor_tidak" class="form-control" value="{{ old('skor_tidak', $question->skor_tidak ?? 0) }}">
                            </div>
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
