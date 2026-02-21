@extends('layouts.master')

@section('title', 'Upload Tugas Peserta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Upload Tugas Peserta</h4>
            <p class="text-muted">Pantau pengumpulan tugas berdasarkan Materi ID. Filter berdasarkan materi untuk melihat status pengumpulan.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- Area navigation buttons --}}
                    <div class="mb-3">
                        <div class="btn-group" role="group" aria-label="Area filter">
                            <a href="{{ route('instumen-talenta.upload-tugas') }}" class="btn btn-sm {{ empty($selectedArea) ? 'btn-primary' : 'btn-outline-primary' }}">Semua Area</a>
                            @if(isset($areas) && $areas->isNotEmpty())
                                @foreach($areas as $area)
                                    <a href="{{ route('instumen-talenta.upload-tugas', ['area' => $area]) }}" class="btn btn-sm {{ ($selectedArea === $area) ? 'btn-primary' : 'btn-outline-primary' }}">{{ $area }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    @if(isset($tugas) && $tugas->isNotEmpty())
                        @php
                            // Group tasks by jenis_tugas, default label 'Umum' when empty
                            $groups = $tugas->groupBy(function($item) {
                                return $item->jenis_tugas ?? 'Umum';
                            });
                        @endphp

                        @foreach($groups as $jenis => $items)
                            <div class="mb-4">
                                <h5 class="mb-2">Jenis Tugas: {{ $jenis }} <small class="text-muted">({{ $items->count() }})</small></h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Peserta (User)</th>
                                                <th>Kelompok</th>
                                                <th>Area</th>
                                                <th>File / Data</th>
                                                <th>Submitted At</th>
                                                <th>Nilai</th>
                                                {{-- <th>Actions</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $item)
                                                <tr>
                                                    <td>{{ $item->id }}</td>
                                                    <td>
                                                        @if($item->user)
                                                            {{ $item->user->name }}<br>
                                                            <small class="text-muted">{{ $item->user->email }}</small>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->kelompok->nama_kelompok ?? '-' }}</td>
                                                    <td>{{ $item->area ?? '-' }}</td>
                                                    <td>
                                                        @if(!empty($item->file_path))
                                                            <a href="{{ asset($item->file_path) }}" target="_blank">Lihat File</a>
                                                        @elseif(!empty($item->data))
                                                            <pre style="white-space:pre-wrap;max-width:350px">{{ json_encode($item->data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ optional($item->submitted_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                                    <td>
                                                        @if($item->nilai && $item->nilai->isNotEmpty())
                                                            {{ $item->nilai->pluck('nilai')->filter()->first() ?? '-' }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    {{-- <td>
                                                        <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">Belum ada data pengumpulan tugas.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
