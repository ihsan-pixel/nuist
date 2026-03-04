@extends('layouts.master-without-nav')

@section('title', 'Hasil Assessment (Admin)')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
@include('talenta.partials.hero')

<section class="section-clean">
<div class="container">
    <h2>Hasil Assessment (Admin)</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Sekolah</th>
                <th>Layanan</th>
                <th>Tata Kelola</th>
                <th>Jumlah Siswa</th>
                <th>Jumlah Penghasilan</th>
                <th>Jumlah Prestasi</th>
                <th>Jumlah Talenta</th>
                <th>Total Skor</th>
                <th>Level Sekolah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scores as $s)
            <tr>
                <td>{{ optional($s->school)->nama ?? 'N/A' }}</td>
                <td>{{ $s->layanan }}</td>
                <td>{{ $s->tata_kelola }}</td>
                <td>{{ $s->jumlah_siswa }}</td>
                <td>{{ $s->jumlah_penghasilan }}</td>
                <td>{{ $s->jumlah_prestasi }}</td>
                <td>{{ $s->jumlah_talenta }}</td>
                <td>{{ $s->total_skor }}</td>
                <td>{{ $s->level_sekolah }}</td>
                <td><a href="#" class="btn btn-sm btn-info">Detail</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $scores->links() }}
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
