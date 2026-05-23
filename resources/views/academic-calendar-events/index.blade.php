@extends('layouts.master')

@section('title', 'Kalender Akademik')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Akademik @endslot
    @slot('title') Kalender Akademik & Kegiatan @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <h4 class="mb-1">Kalender Akademik & Kegiatan</h4>
                    <p class="text-muted mb-0">
                        Kelola kegiatan resmi untuk <strong>{{ $school->name }}</strong>. Event di sini terpisah dari sistem holiday lama dan khusus mempengaruhi presensi mengajar.
                    </p>
                </div>
                <div>
                    <a href="{{ route('academic-calendar-events.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Tambah Kegiatan
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->has('conflict'))
                    <div class="alert alert-danger">{{ $errors->first('conflict') }}</div>
                @endif

                @if($events->isEmpty())
                    <div class="text-center py-5">
                        <i class="bx bx-calendar-x display-5 text-muted"></i>
                        <h5 class="mt-3 mb-2">Belum ada kegiatan akademik</h5>
                        <p class="text-muted mb-0">Tambahkan event agar presensi mengajar bisa disesuaikan otomatis saat ada kegiatan resmi sekolah.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Kegiatan</th>
                                    <th>Jenis Status</th>
                                    <th>Tanggal / Hari</th>
                                    <th>Jam</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $event->name }}</div>
                                            @if($event->description)
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($event->description, 110) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                {{ $event->resolved_type_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $event->date_range_label }}</div>
                                        </td>
                                        <td>{{ $event->time_range_label }}</td>
                                        <td>
                                            @if($event->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('academic-calendar-events.edit', $event) }}" class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>
                                            <form action="{{ route('academic-calendar-events.destroy', $event) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Hapus kegiatan ini? Presensi otomatis yang dibuat dari event ini juga akan dihapus.')"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
