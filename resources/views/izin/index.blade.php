@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Daftar Pengajuan Izin</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Surat Izin</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($izinRequests as $presensi)
                <tr>
                    <td>{{ $presensi->user->name }}</td>
                    <td>{{ $presensi->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $presensi->keterangan }}</td>
                    <td>
                        <a href="{{ asset('storage/app/public/'.$presensi->surat_izin_path) }}" target="_blank">Lihat Surat</a>
                    </td>
                    <td>{{ $presensi->status_izin }}</td>
                    <td>
                        @if($presensi->status_izin === 'pending')
                            @can('approve', $presensi)
                                <form action="{{ route('izin.approve', $presensi) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Setujui</button>
                                </form>
                            @endcan
                            @can('reject', $presensi)
                                <form action="{{ route('izin.reject', $presensi) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                </form>
                            @endcan
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

