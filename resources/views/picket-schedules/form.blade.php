@extends('layouts.master')

@section('title', $isEdit ? 'Edit Izin Jadwal Piket' : 'Tambah Izin Jadwal Piket')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Akademik @endslot
    @slot('title') {{ $isEdit ? 'Edit Izin Jadwal Piket' : 'Tambah Izin Jadwal Piket' }} @endslot
@endcomponent

<div class="row">
    <div class="col-xl-10">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <style>
                    .picket-page-note {
                        border: 1px solid #e6edf0;
                        border-radius: 14px;
                        padding: 14px 16px;
                        background: #f8fbfb;
                        color: #5f6b72;
                        font-size: 13px;
                        line-height: 1.5;
                    }

                    .picket-teacher-list {
                        display: grid;
                        gap: 14px;
                    }

                    .picket-teacher-card {
                        border: 1px solid #e9eef1;
                        border-radius: 16px;
                        padding: 14px;
                        background: #fff;
                    }

                    .picket-teacher-name {
                        font-size: 15px;
                        font-weight: 600;
                        color: #223035;
                        margin-bottom: 2px;
                    }

                    .picket-teacher-role {
                        font-size: 12px;
                        color: #7a8790;
                        margin-bottom: 10px;
                    }

                    .picket-date-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                        gap: 8px;
                    }

                    .picket-date-option {
                        display: flex;
                        align-items: flex-start;
                        gap: 10px;
                        border: 1px solid #e7ecef;
                        border-radius: 12px;
                        padding: 10px 12px;
                        background: #fbfcfc;
                        min-height: 100%;
                    }

                    .picket-date-option.disabled {
                        background: #f4f6f7;
                        color: #95a1a8;
                    }

                    .picket-date-text {
                        font-size: 13px;
                        line-height: 1.4;
                        color: #304047;
                    }

                    .picket-date-option.disabled .picket-date-text {
                        color: #7f8b91;
                    }

                    .picket-date-reason {
                        display: block;
                        margin-top: 2px;
                        font-size: 11px;
                        color: #d14d41;
                    }
                </style>

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
                            <div class="picket-page-note">
                                Admin sekolah menyusun langsung hari piket tiap tenaga pendidik dalam satu form, lalu seluruh susunan ini dikirim ke approval kepala sekolah.
                            </div>
                        </div>

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
                            <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Contoh: Admin sekolah menyusun hari piket guru selama libur semester untuk pelayanan sekolah dan administrasi.">{{ old('description', $period->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $period->is_active ?? true))>
                                <label class="form-check-label" for="is_active">Periode aktif dan pengajuannya disusun oleh admin sekolah</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pt-2">
                                <div>
                                    <h5 class="mb-1">Susun Hari Piket Guru</h5>
                                    <small class="text-muted">Pilih hanya hari kerja yang benar-benar menjadi jadwal piket.</small>
                                </div>
                                <span class="badge bg-primary">{{ $teachers->count() }} guru</span>
                            </div>

                            @error('teacher_dates')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            @if($teachers->isEmpty())
                                <div class="alert alert-light border mb-0">Data tenaga pendidik belum tersedia untuk sekolah ini.</div>
                            @elseif(empty($dateChoices))
                                <div class="alert alert-light border mb-0">Rentang tanggal belum tersedia. Isi tanggal mulai dan tanggal selesai yang valid terlebih dahulu.</div>
                            @else
                                <div class="picket-teacher-list">
                                    @foreach($teachers as $teacher)
                                        @php
                                            $selectedDates = collect(old('teacher_dates.' . $teacher->id, $existingSelections[$teacher->id] ?? []));
                                        @endphp
                                        <div class="picket-teacher-card">
                                            <div class="picket-teacher-name">{{ $teacher->name }}</div>
                                            <div class="picket-teacher-role">{{ $teacher->ketugasan ?: 'Tenaga pendidik' }}</div>

                                            <div class="picket-date-grid">
                                                @foreach($dateChoices as $choice)
                                                    <label class="picket-date-option {{ $choice['is_disabled'] ? 'disabled' : '' }}">
                                                        <input
                                                            type="checkbox"
                                                            name="teacher_dates[{{ $teacher->id }}][]"
                                                            value="{{ $choice['date'] }}"
                                                            class="mt-1"
                                                            @checked($selectedDates->contains($choice['date']))
                                                            @disabled($choice['is_disabled'])
                                                        >
                                                        <span class="picket-date-text">
                                                            {{ $choice['label'] }}
                                                            @if($choice['is_disabled'])
                                                                <small class="picket-date-reason">{{ $choice['disabled_reason'] }}</small>
                                                            @endif
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
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
</div>
@endsection
