@extends('layouts.master')

@section('title') Kegiatan Kelas (Non Mengajar) @endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Presensi Mengajar @endslot
    @slot('title') Kegiatan Kelas (Non Mengajar) @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="mb-1">Kegiatan Kelas</h5>
                        <div class="text-muted small">Jika ada kegiatan (ujian/PKL/libur), guru tidak perlu presensi mengajar untuk kelas tersebut pada rentang tanggal.</div>
                    </div>
                    <a href="{{ route('teaching-class-activities.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Tambah Kegiatan
                    </a>
                </div>

                @if(auth()->user()->role === 'super_admin')
                    <form method="GET" class="row g-2 align-items-end mb-3">
                        <div class="col-md-6">
                            <label class="form-label mb-1">Filter Madrasah</label>
                            <select class="form-select" name="school_id">
                                <option value="">Semua</option>
                                @foreach($schools as $s)
                                    <option value="{{ $s->id }}" @selected((string) request('school_id') === (string) $s->id)>{{ ($s->scod ?? '-') . ' - ' . ($s->name ?? '-') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-secondary w-100" type="submit">Terapkan</button>
                        </div>
                    </form>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Madrasah</th>
                                <th>Kelas</th>
                                <th>Rentang Tanggal</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activities as $a)
                                <tr>
                                    <td>{{ $a->school->name ?? '-' }}</td>
                                    <td class="fw-semibold">{{ $a->class_name }}</td>
                                    <td>{{ optional($a->start_date)->format('d M Y') }} - {{ optional($a->end_date)->format('d M Y') }}</td>
                                    <td><span class="badge bg-info text-dark">{{ $a->activity_type }}</span></td>
                                    <td class="text-muted small">{{ \Illuminate\Support\Str::limit((string) $a->description, 80) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('teaching-class-activities.edit', $a->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('teaching-class-activities.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kegiatan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Belum ada data kegiatan kelas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

