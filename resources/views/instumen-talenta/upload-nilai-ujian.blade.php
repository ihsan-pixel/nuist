@extends('layouts.master')

@section('title', 'Upload Nilai Ujian')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Upload Nilai Ujian</h4>
            <p class="text-muted">Unggah file CSV dengan kolom <code>kode_peserta</code> dan <code>nilai_ujian</code>. Sistem akan menyimpan nilai dan menandai <strong>keterangan</strong> sebagai Lulus/Tidak Lulus (threshold: 70).</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('instumen-talenta.import-nilai-ujian') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label">File CSV</label>
                            <input type="file" name="file" id="file" class="form-control" accept=".csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Import CSV</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Daftar Nilai Ujian</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Peserta</th>
                                    <th>Nama Peserta</th>
                                    <th>Nilai Ujian</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($entries as $entry)
                                    <tr>
                                        <td>{{ $entry->id }}</td>
                                        <td>{{ $entry->peserta->kode_peserta ?? '-' }}</td>
                                        <td>{{ $entry->peserta->user->name ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('instumen-talenta.update-nilai-ujian', $entry->id) }}" method="post" class="d-flex">
                                                @csrf
                                                <input type="number" name="nilai_ujian" value="{{ $entry->nilai_ujian ?? '' }}" min="0" max="100" class="form-control form-control-sm me-2" style="width:110px">
                                                <button class="btn btn-sm btn-success">Simpan</button>
                                            </form>
                                        </td>
                                        <td>{{ $entry->keterangan ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('instumen-talenta.delete-nilai-ujian', $entry->id) }}" method="post" onsubmit="return confirm('Hapus entry ini?');">
                                                @csrf
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $entries->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
