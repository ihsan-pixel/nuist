@extends('ami.layout')
@section('content')
<div class="card">
    <h1 class="title">Instrumen AMI</h1>
    <div class="muted">{{ $instrument?->name ?? 'Belum ada instrumen aktif' }}</div>
</div>
@foreach($components as $component)
    <div class="card">
        <strong>{{ $component->code }} - {{ $component->name }}</strong>
    </div>
@endforeach
@endsection
