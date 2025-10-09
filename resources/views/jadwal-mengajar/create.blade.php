@extends('layouts.master')

@section('title') Tambah Jadwal Mengajar @endsection

@section('content')

@component('components.breadcrumb')
    @slot('li_1') Jadwal Mengajar @endslot
    @slot('title') Tambah Jadwal Mengajar @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('jadwal-mengajar.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="tenaga_pendidik_id" class="form-label">Tenaga Pendidik</label>
                        <select name="tenaga_pendidik_id" id="tenaga_pendidik_id" class="form-select" required>
                            <option value="">Pilih Tenaga Pendidik</option>
                            @foreach($tenagaPendidiks as $tp)
                                <option value="{{ $tp->id }}">{{ $tp->nama }}</option>
                            @endforeach
                        </select>
                    </div>

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
                    <a href="{{ route('jadwal-mengajar.index') }}" class="btn btn-secondary">Batal</a>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
