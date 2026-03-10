@extends('layouts.master')

@section('title', 'Peserta Belum Upload Tugas')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Peserta yang Belum Mengunggah Tugas</h4>
            <p class="text-muted">Daftar peserta (atau anggota kelompok) yang belum mengunggah tugas. Gunakan filter untuk mempersempit hasil.</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <form method="GET" action="{{ route('instumen-talenta.belum-upload-tugas') }}" class="row gy-2 gx-2 align-items-end">
                <div class="col-auto">
                    <label class="form-label">Area</label>
                    <select name="area" class="form-select">
                        <option value="">-- Semua Area --</option>
                        @foreach($areas as $a)
                            <option value="{{ $a }}" {{ ($selectedArea == $a) ? 'selected' : '' }}>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label class="form-label">Jenis Tugas</label>
                    <select name="jenis_tugas" class="form-select">
                        <option value="" {{ empty($selectedJenis) ? 'selected' : '' }}>-- Semua Tugas --</option>
                        <option value="on_site" {{ ($selectedJenis == 'on_site') ? 'selected' : '' }}>Tugas Onsite</option>
                        <option value="terstruktur" {{ ($selectedJenis == 'terstruktur') ? 'selected' : '' }}>Tugas Terstruktur</option>
                        <option value="kelompok" {{ ($selectedJenis == 'kelompok') ? 'selected' : '' }}>Tugas Kelompok</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Filter</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('instumen-talenta.upload-tugas', ['area' => $selectedArea]) }}" class="btn btn-outline-secondary">Kembali ke Upload Tugas</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($rows->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode Peserta</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kelompok</th>
                                        <th>Asal Sekolah / Madrasah</th>
                                        <th>Area</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $idx => $row)
                                        <tr>
                                            <td>{{ ($rows->currentPage() - 1) * $rows->perPage() + $idx + 1 }}</td>
                                            <td>{{ $row->kode_peserta }}</td>
                                            <td>{{ $row->nama }}</td>
                                            <td>{{ $row->email }}</td>
                                            <td>{{ $row->kelompok }}</td>
                                            <td>{{ $row->asal }}</td>
                                            <td>{{ $row->area }}</td>
                                            <td>{{ $row->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end">
                            {{ $rows->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">Tidak ditemukan peserta yang sesuai filter.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
