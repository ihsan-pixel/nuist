@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Isi Assessment - School Level</h2>
    <form method="POST" action="{{ route('talenta.assessment.store') }}">
        @csrf
        @foreach($questions->groupBy('kategori') as $kategori => $qs)
            <h4>{{ ucfirst(str_replace('_',' ', $kategori)) }}</h4>
            @foreach($qs as $q)
                <div class="mb-2">
                    <label>{{ $q->pertanyaan }}</label><br>
                    <label><input type="radio" name="answers[{{ $q->id }}]" value="Ya"> Ya</label>
                    <label style="margin-left:10px"><input type="radio" name="answers[{{ $q->id }}]" value="Tidak"> Tidak</label>
                </div>
            @endforeach
        @endforeach

        <button class="btn btn-success">Simpan Jawaban</button>
    </form>
</div>
@endsection
