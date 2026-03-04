@extends('layouts.master-without-nav')

@section('title', (isset($question) && $question->id) ? 'Edit Soal' : 'Tambah Soal')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')
@include('talenta.navbar')

<section class="section-clean">
<div class="container">
    <h2>{{ isset($question) && $question->id ? 'Edit Soal' : 'Tambah Soal' }}</h2>
    <form method="POST" action="{{ isset($question) && $question->id ? route('talenta.questions.update', $question) : route('talenta.questions.store') }}">
        @csrf
        @if(isset($question) && $question->id) @method('PUT') @endif
        <div class="mb-3">
            <label>Kategori</label>
            <select name="kategori" class="form-control">
                @foreach(['layanan','tata_kelola','jumlah_siswa','jumlah_penghasilan','jumlah_prestasi','jumlah_talenta'] as $cat)
                    <option value="{{ $cat }}" {{ (old('kategori', $question->kategori ?? '') == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Pertanyaan</label>
            <textarea name="pertanyaan" class="form-control">{{ old('pertanyaan', $question->pertanyaan ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label>Skor Ya</label>
            <input type="number" name="skor_ya" class="form-control" value="{{ old('skor_ya', $question->skor_ya ?? 1) }}">
        </div>
        <div class="mb-3">
            <label>Skor Tidak</label>
            <input type="number" name="skor_tidak" class="form-control" value="{{ old('skor_tidak', $question->skor_tidak ?? 0) }}">
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
