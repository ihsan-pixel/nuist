@extends('layouts.master')

@section('title', 'Penanda Hari')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Pengaturan @endslot
    @slot('title') Penanda Hari @endslot
@endcomponent

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Tambah / Ubah Penanda</h6>

                <form method="POST" action="{{ route('day-markers.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Bulan</label>
                        <input type="month" name="month" class="form-control" value="{{ $month }}">
                        <div class="form-text">Untuk menampilkan daftar di kanan.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Scope</label>
                        <select name="scope_type" class="form-select" required>
                            <option value="global">Global</option>
                            <option value="school">Sekolah</option>
                            <option value="class">Kelas</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Sekolah (untuk scope sekolah/kelas)</label>
                        <select name="madrasah_id" class="form-select">
                            <option value="">-</option>
                            @foreach($madrasahs as $m)
                                <option value="{{ $m->id }}" @selected($m->id === $selectedMadrasahId)>
                                    {{ $m->scod ?? '-' }} - {{ $m->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Kelas (untuk scope kelas)</label>
                        <input name="class_name" class="form-control" list="classNames" placeholder="Contoh: X-A">
                        <datalist id="classNames">
                            @foreach($classNames as $className)
                                <option value="{{ $className }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Penanda</label>
                        <select name="marker" class="form-select" required>
                            <option value="normal">Hari Normal</option>
                            <option value="libur">Hari Libur</option>
                            <option value="ujian">Hari Ujian</option>
                            <option value="kegiatan_khusus">Hari Kegiatan Khusus (PKL, Study Tour, dll)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Opsional"></textarea>
                    </div>

                    <button class="btn btn-primary w-100" type="submit">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-semibold mb-0">Daftar Penanda ({{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('id')->isoFormat('MMMM YYYY') }})</h6>
                    <form method="GET" class="d-flex gap-2">
                        <input type="month" name="month" class="form-control form-control-sm" value="{{ $month }}">
                        <select name="madrasah_id" class="form-select form-select-sm">
                            @foreach($madrasahs as $m)
                                <option value="{{ $m->id }}" @selected($m->id === $selectedMadrasahId)>
                                    {{ $m->scod ?? '-' }} - {{ $m->name }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-outline-secondary" type="submit">Terapkan</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Scope</th>
                                <th>Penanda</th>
                                <th>Catatan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($markers as $m)
                                <tr>
                                    <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($m->date)->format('Y-m-d') }}</td>
                                    <td style="max-width:220px;">
                                        <div class="fw-semibold">{{ $m->scope_key }}</div>
                                        @if($m->class_name)
                                            <div class="text-muted small">Kelas: {{ $m->class_name }}</div>
                                        @endif
                                    </td>
                                    <td style="min-width:170px;">
                                        <form method="POST" action="{{ route('day-markers.update', $m) }}" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="marker" class="form-select form-select-sm" style="min-width:170px;">
                                                <option value="normal" @selected($m->marker === 'normal')>Hari Normal</option>
                                                <option value="libur" @selected($m->marker === 'libur')>Hari Libur</option>
                                                <option value="ujian" @selected($m->marker === 'ujian')>Hari Ujian</option>
                                                <option value="kegiatan_khusus" @selected($m->marker === 'kegiatan_khusus')>Kegiatan Khusus</option>
                                            </select>
                                    </td>
                                    <td style="min-width:220px;">
                                            <input name="notes" class="form-control form-control-sm" value="{{ $m->notes }}">
                                    </td>
                                    <td class="text-center" style="white-space:nowrap;">
                                            <button class="btn btn-sm btn-success" type="submit">Update</button>
                                        </form>
                                        <form method="POST" action="{{ route('day-markers.destroy', $m) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Hapus penanda ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Belum ada penanda untuk bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="text-muted small mt-2">
                    Prioritas penanda: <span class="fw-semibold">Kelas</span> &gt; <span class="fw-semibold">Sekolah</span> &gt; <span class="fw-semibold">Global</span>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

