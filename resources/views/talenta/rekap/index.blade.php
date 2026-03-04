@extends('layouts.master-without-nav')

@section('title', 'Rekap School Level')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

@include('talenta.partials.styles')
{{-- @include('talenta.partials.hero') --}}

<!-- Back button (no navbar) -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='{{ route('talenta.dashboard') }}'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #0f172a;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #0f172a; font-size: 14px;">Kembali</span>
</div>

<section class="section-clean">
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <h2 class="mb-3">Rekap School Level</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Sekolah</th>
                                    <th>Layanan</th>
                                    <th>Tata Kelola</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Jumlah Penghasilan</th>
                                    <th>Jumlah Prestasi</th>
                                    <th>Jumlah Talenta</th>
                                    <th>Total Nilai</th>
                                    <th>Level</th>
                                    <th style="width:120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scores as $s)
                                <tr>
                                    <td>
                                        {{ optional($s->school)->nama ?? 'N/A' }}
                                        <div class="small text-muted">Pengirim: {{ optional($s->submittedBy)->name ?? '-' }}</div>
                                    </td>
                                    <td>{{ $s->layanan }}</td>
                                    <td>{{ $s->tata_kelola }}</td>
                                    <td>{{ $s->jumlah_siswa }}</td>
                                    <td>{{ $s->jumlah_penghasilan }}</td>
                                    <td>{{ $s->jumlah_prestasi }}</td>
                                    <td>{{ $s->jumlah_talenta }}</td>
                                    <td>{{ $s->total_skor }}</td>
                                    <td>{{ $s->level_sekolah }}</td>
                                    <td>
                                        <a href="{{ route('talenta.rekap.detail', $s->id) }}" class="btn btn-sm btn-info">Detail</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-3">{{ $scores->links() }}</div>
        </div>

        <div class="col-lg-3">
            <div class="card shadow-sm border-0 sticky-top" style="top:90px;">
                <div class="card-body">
                    <h6 class="mb-2">Ringkasan</h6>
                    <p class="small text-muted mb-2">Total Sekolah: <strong>{{ $scores->total() ?? $scores->count() }}</strong></p>
                    <p class="small text-muted mb-1">Distribusi Level:</p>
                    <ul class="small list-unstyled">
                        <li class="mb-1">A: <strong>{{ \App\Models\SchoolScore::where('level_sekolah','A')->count() }}</strong></li>
                        <li class="mb-1">B: <strong>{{ \App\Models\SchoolScore::where('level_sekolah','B')->count() }}</strong></li>
                        <li class="mb-1">C: <strong>{{ \App\Models\SchoolScore::where('level_sekolah','C')->count() }}</strong></li>
                        <li class="mb-1">D: <strong>{{ \App\Models\SchoolScore::where('level_sekolah','D')->count() }}</strong></li>
                    </ul>
                    <p class="small text-muted">Klik Detail untuk melihat rekap lengkap per sekolah.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@include('talenta.partials.scripts')
@include('landing.footer')

@endsection
