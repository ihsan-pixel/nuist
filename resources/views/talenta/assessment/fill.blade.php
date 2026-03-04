@extends('layouts.master-without-nav')

@section('title', 'Isi Assessment - School Level')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')
@include('talenta.navbar')

<section class="section-clean">
    <div class="container">
        <h2 class="section-title">Isi Assessment - School Level</h2>
        <p class="section-subtitle">Jawab pertanyaan berikut untuk menghitung skor School Level sekolah Anda.</p>

        <form method="POST" action="{{ route('talenta.assessment.store') }}">
            @csrf

            @foreach($questions->groupBy('kategori') as $kategori => $qs)
                <div class="card" style="margin-bottom:18px;">
                    <h3 style="margin-bottom:12px; text-transform: capitalize;">{{ str_replace('_',' ', $kategori) }}</h3>
                    @foreach($qs as $q)
                        <div style="margin-bottom:12px;">
                            <label style="display:block; font-weight:600; color:#0f172a;">{{ $q->pertanyaan }}</label>
                            <div style="margin-top:6px; display:flex; gap:16px; align-items:center;">
                                <label style="display:flex; align-items:center; gap:8px;"><input type="radio" name="answers[{{ $q->id }}]" value="Ya"> <span>Ya</span></label>
                                <label style="display:flex; align-items:center; gap:8px;"><input type="radio" name="answers[{{ $q->id }}]" value="Tidak"> <span>Tidak</span></label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div style="text-align:center; margin-top:18px;">
                <button class="btn btn-primary">Simpan Jawaban</button>
            </div>
        </form>
    </div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
