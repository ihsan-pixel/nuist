@extends('layouts.master')

@section('title', $isEdit ? 'Edit Izin Jadwal Piket' : 'Tambah Izin Jadwal Piket')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Akademik @endslot
    @slot('title') {{ $isEdit ? 'Edit Izin Jadwal Piket' : 'Tambah Izin Jadwal Piket' }} @endslot
@endcomponent

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-4">
                    <h4 class="mb-1">{{ $isEdit ? 'Ubah Periode Jadwal Piket' : 'Tambah Periode Jadwal Piket' }}</h4>
                    <p class="text-muted mb-0">
                        Sekolah: <strong>{{ $school?->name ?? 'Pilih sekolah terlebih dahulu' }}</strong>
                    </p>
                </div>

                <form action="{{ $isEdit ? route('picket-schedule-periods.update', $period) : route('picket-schedule-periods.store') }}" method="POST">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        @if(Auth::user()->role === 'super_admin')
                            <div class="col-12">
                                <label class="form-label">Sekolah</label>
                                <select name="school_id" class="form-select @error('school_id') is-invalid @enderror">
                                    <option value="">Pilih sekolah</option>
                                    @foreach($schools as $schoolOption)
                                        <option value="{{ $schoolOption->id }}" @selected((string) old('school_id', $period->school_id) === (string) $schoolOption->id)>
                                            {{ $schoolOption->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('school_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @else
                            <div class="col-12">
                                <label class="form-label">Sekolah</label>
                                <input type="text" class="form-control" value="{{ $school?->name ?? '-' }}" readonly>
                            </div>
                        @endif

                        <div class="col-12">
                            <label class="form-label">Nama Periode</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $period->name) }}" placeholder="Contoh: Piket Libur Semester Ganjil 2026">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', optional($period->start_date)->format('Y-m-d') ?: $period->start_date) }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', optional($period->end_date)->format('Y-m-d') ?: $period->end_date) }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Contoh: Guru memilih hari piket selama libur semester untuk pelayanan sekolah dan administrasi.">{{ old('description', $period->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $period->is_active ?? true))>
                                <label class="form-check-label" for="is_active">Periode aktif dan bisa diajukan oleh tenaga pendidik</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Periode' }}</button>
                        <a href="{{ route('picket-schedule-periods.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h5 class="mb-3">Alur Fitur</h5>
                <ul class="text-muted ps-3 mb-0">
                    <li>Admin membuat periode libur semester.</li>
                    <li>Semua tenaga pendidik di sekolah dapat memilih hari piketnya.</li>
                    <li>Pengajuan masuk ke menu Approval Event milik kepala sekolah.</li>
                    <li>Jika rentang tanggal perlu diubah, sebaiknya dilakukan sebelum ada pengajuan guru.</li>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">Data Tenaga Pendidik</h5>
                    <span class="badge bg-primary">{{ $teachers->count() }}</span>
                </div>
                @if($teachers->isEmpty())
                    <div class="text-muted small">Data guru belum tersedia untuk sekolah ini.</div>
                @else
                    <div class="d-grid gap-2">
                        @foreach($teachers as $teacher)
                            <div class="border rounded p-2">
                                <div class="fw-semibold">{{ $teacher->name }}</div>
                                <small class="text-muted">
                                    {{ $teacher->ketugasan ?: 'Tenaga pendidik' }}
                                    @if($teacher->jabatan)
                                        • {{ $teacher->jabatan }}
                                    @endif
                                </small>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
