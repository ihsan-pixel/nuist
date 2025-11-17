@extends('layouts.master-without-nav')

@section('title', 'Dashboard PPDB Sekolah')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Dashboard PPDB — {{ $ppdb->nama_sekolah }}</h4>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6>Total Pendaftar: {{ $pendaftar->count() }}</h6>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Asal Sekolah</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendaftar as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $p->nama_lengkap }}</td>
                        <td>{{ $p->asal_sekolah }}</td>
                        <td>{{ $p->jurusan_pilihan }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status == 'pending' ? 'secondary' : ($p->status == 'verifikasi' ? 'info' : ($p->status == 'lulus' ? 'success' : 'danger')) }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('ppdb.sekolah.verifikasi', $p->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-primary">Verifikasi</button>
                            </form>

                            <form action="{{ route('ppdb.sekolah.seleksi', $p->id) }}" method="POST" class="d-inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm d-inline w-auto">
                                    <option value="">Seleksi...</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="tidak_lulus">Tidak Lulus</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
