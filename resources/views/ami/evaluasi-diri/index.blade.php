@extends('ami.layout')
@section('content')
<div class="card"><h1 class="title">Evaluasi Diri</h1><div class="muted">Struktur progres komponen</div></div>
@foreach($components as $component)
    <div class="card">
        <strong>{{ $component->name }}</strong>
        <div class="muted">Progress akan dihitung dari indikator dan bukti minimal.</div>
    </div>
@endforeach
@endsection
