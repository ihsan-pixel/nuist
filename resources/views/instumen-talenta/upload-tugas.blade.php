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
                    @if(isset($tugas) && $tugas->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Peserta (User)</th>
                                        <th>Kelompok</th>
                                        <th>Area</th>
                                        <th>Jenis Tugas</th>
                                        <th>File / Data</th>
                                        <th>Submitted At</th>
                                        <th>Nilai</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tugas as $item)
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
                                            <td>{{ $item->jenis_tugas ?? '-' }}</td>
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
                                            <td>
                                                <a href="#" class="btn btn-sm btn-outline-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">Belum ada data pengumpulan tugas.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
