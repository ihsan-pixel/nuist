@extends('ami.layout')
@section('content')
<div class="card">
    <h1 class="title">Dashboard AMI</h1>
    <div class="muted">Role: {{ $role }}</div>
</div>
<div class="stats">
    <div class="stat"><strong>{{ $stats['periods'] }}</strong><div class="muted">Periode</div></div>
    <div class="stat"><strong>{{ $stats['schools'] }}</strong><div class="muted">Sekolah</div></div>
    <div class="stat"><strong>{{ $stats['assignments'] }}</strong><div class="muted">Penugasan</div></div>
    <div class="stat"><strong>{{ $stats['tracked_schools'] }}</strong><div class="muted">Tercatat</div></div>
</div>
<div class="card">
    <h3>Periode Aktif</h3>
    @forelse($periods as $period)
        <div style="padding:10px 0;border-bottom:1px solid var(--border)">
            <strong>{{ $period->name }}</strong>
            <div class="muted">{{ $period->year }} - {{ $period->status }}</div>
        </div>
    @empty
        <div class="muted">Belum ada periode.</div>
    @endforelse
</div>
@endsection
