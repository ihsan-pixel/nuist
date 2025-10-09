@extends('layouts.master')

@section('title') Jadwal Mengajar @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Jadwal Mengajar @endslot
@endcomponent

@if($madrasahName)
<div class="alert alert-info">
    Madrasah terpilih: <strong>{{ $madrasahName }}</strong>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Jadwal Mengajar</h4>
                <p class="card-title-desc">
                    Halaman untuk mengelola jadwal mengajar tenaga pendidik.
                </p>

                <div class="row">
                    <!-- Left column: Manual input form -->
                    <div class="col-md-6">
                        <h5>Input Manual Jadwal Mengajar</h5>
                        <form action="{{ route('jadwal-mengajar.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="tenaga_pendidik_id" class="form-label">Tenaga Pendidik</label>
                                <select name="tenaga_pendidik_id" id="tenaga_pendidik_id" class="form-select" required>
                                    <option value="">Pilih Tenaga Pendidik</option>
                                    @foreach($tenagaPendidiks as $tp)
                                        <option value="{{ $tp->id }}">{{ $tp->nama }} ({{ $tp->madrasah->nama ?? 'Madrasah tidak diketahui' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            @if(auth()->user()->role === 'super_admin')
                            <div class="mb-3">
                                <label for="madrasah_id" class="form-label">Madrasah</label>
                                <select name="madrasah_id" id="madrasah_id" class="form-select" required>
                                    <option value="">Pilih Madrasah</option>
                                    @foreach($madrasahs as $madrasah)
                                        <option value="{{ $madrasah->id }}" {{ (isset($madrasahId) && $madrasahId == $madrasah->id) ? 'selected' : '' }}>{{ $madrasah->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else
                            <input type="hidden" name="madrasah_id" value="{{ $madrasahId }}">
                            @endif

                            <div class="mb-3">
                                <label for="hari" class="form-label">Hari</label>
                                <input type="text" name="hari" id="hari" class="form-control" placeholder="Senin, Selasa, dll." required>
                            </div>

                            <div class="mb-3">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
                                <input type="text" name="mata_pelajaran" id="mata_pelajaran" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                        </form>
                    </div>

                    <!-- Right column: Import file form -->
                    <div class="col-md-6">
                        <h5>Import Jadwal Mengajar dari File</h5>
                        <form action="{{ route('jadwal-mengajar.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="import_file" class="form-label">Pilih File (CSV, XLSX)</label>
                                <input type="file" name="import_file" id="import_file" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                            </div>
                            <button type="submit" class="btn btn-success">Import File</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
