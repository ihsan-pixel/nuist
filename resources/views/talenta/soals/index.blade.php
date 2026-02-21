@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-8">
                <h3>Manajemen Soal</h3>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Materi</th>
                                    <th>Jenis</th>
                                    <th>Pertanyaan</th>
                                    <th style="width:200px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach($soals->flatten(1) ?? collect() as $soal)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $soal->materi_slug }}</td>
                                        <td>{{ $soal->jenis }}</td>
                                        <td>{!! nl2br(e(
                                            Str::limit($soal->pertanyaan, 180)
                                        )) !!}</td>
                                        <td>
                                            <a href="{{ route('talenta.soals.edit', $soal->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('talenta.soals.destroy', $soal->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Hapus soal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if(($soals->flatten(1) ?? collect())->isEmpty())
                                    <tr><td colspan="5" class="text-center">Belum ada soal</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-4">
                <h5>@isset($editing) Edit Soal @else Tambah Soal Baru @endisset</h5>
                <div class="card">
                    <div class="card-body">
                        <form action="@isset($editing) {{ route('talenta.soals.update', $editing->id) }} @else {{ route('talenta.soals.store') }} @endisset" method="POST">
                            @csrf
                            @isset($editing)
                                @method('PUT')
                            @endisset

                            <div class="mb-2">
                                <label class="form-label">Materi</label>
                                <select name="materi_slug" class="form-control" required>
                                    @foreach($materis as $m)
                                        <option value="{{ $m->slug }}" @if(isset($editing) && $editing->materi_slug == $m->slug) selected @endif>{{ $m->judul_materi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Jenis</label>
                                <select name="jenis" class="form-control" required>
                                    <option value="on_site" @if(isset($editing) && $editing->jenis=='on_site') selected @endif>On Site</option>
                                    <option value="terstruktur" @if(isset($editing) && $editing->jenis=='terstruktur') selected @endif>Terstruktur</option>
                                    <option value="kelompok" @if(isset($editing) && $editing->jenis=='kelompok') selected @endif>Kelompok</option>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Pertanyaan</label>
                                <textarea name="pertanyaan" class="form-control" rows="4" required>@if(isset($editing)){{ $editing->pertanyaan }}@endif</textarea>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Instruksi (opsional)</label>
                                <textarea name="instruksi" class="form-control" rows="2">@if(isset($editing)){{ $editing->instruksi }}@endif</textarea>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary">@isset($editing) Simpan Perubahan @else Tambah Soal @endisset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
