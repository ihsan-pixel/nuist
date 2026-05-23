@extends('layouts.master')

@section('title', $isEdit ? 'Edit Kalender Akademik' : 'Tambah Kalender Akademik')

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Akademik @endslot
    @slot('title') {{ $isEdit ? 'Edit Kalender Akademik' : 'Tambah Kalender Akademik' }} @endslot
@endcomponent

<div class="row">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-4">
                    <h4 class="mb-1">{{ $isEdit ? 'Ubah Event Akademik' : 'Tambah Event Akademik' }}</h4>
                    <p class="text-muted mb-0">
                        Sekolah: <strong>{{ $school->name }}</strong>. Event di halaman ini tidak memakai tabel `holidays` dan baru akan mengubah presensi mengajar setelah disetujui kepala sekolah.
                    </p>
                </div>

                <form action="{{ $isEdit ? route('academic-calendar-events.update', $event) : route('academic-calendar-events.store') }}" method="POST">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $event->name) }}" placeholder="Contoh: Ujian Semester / Class Meeting">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jenis Kegiatan</label>
                            <select name="event_type" id="event_type" class="form-select @error('event_type') is-invalid @enderror">
                                @foreach($typeOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('event_type', $event->event_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('event_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6" id="customTypeWrapper">
                            <label class="form-label">Label Jenis Kegiatan Khusus</label>
                            <input type="text" name="custom_type_label" class="form-control @error('custom_type_label') is-invalid @enderror" value="{{ old('custom_type_label', $event->custom_type_label) }}" placeholder="Contoh: Asesmen Internal / Peringatan Hari Besar">
                            @error('custom_type_label')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', optional($event->start_date)->format('Y-m-d') ?: $event->start_date) }}">
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', optional($event->end_date)->format('Y-m-d') ?: $event->end_date) }}">
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_all_day" name="is_all_day" value="1" @checked(old('is_all_day', $event->is_all_day ?? true))>
                                <label class="form-check-label" for="is_all_day">Berlaku seharian penuh</label>
                            </div>
                        </div>

                        <div class="col-md-3 time-field">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $event->start_time ? substr($event->start_time, 0, 5) : null) }}">
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Disimpan sebagai jam agenda event.</small>
                        </div>

                        <div class="col-md-3 time-field">
                            <label class="form-label">Jam Selesai</label>
                            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $event->end_time ? substr($event->end_time, 0, 5) : null) }}">
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Disimpan sebagai jam agenda event.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="Catatan tambahan untuk admin dan monitoring presensi">{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $event->is_active ?? true))>
                                <label class="form-check-label" for="is_active">Event aktif dan siap diajukan ke kepala sekolah</label>
                            </div>
                        </div>
                    </div>

                    @if($errors->has('conflict'))
                        <div class="alert alert-danger mt-4 mb-0">{{ $errors->first('conflict') }}</div>
                    @endif

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Event' }}
                        </button>
                        <a href="{{ route('academic-calendar-events.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-3">Catatan Integrasi</h5>
                <ul class="text-muted ps-3 mb-0">
                    <li>Event ini hanya tambahan baru dan tidak menggantikan sistem holiday lama.</li>
                    <li>Semua jadwal mengajar pada tanggal event akan menjadi izin setelah kepala sekolah menyetujui event.</li>
                    <li>Sebelum disetujui, event belum mempengaruhi presensi mengajar guru.</li>
                    <li>Status laporan tetap aman karena izin dari kalender akademik ikut dihitung valid.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function toggleAcademicCalendarFields() {
    const eventType = document.getElementById('event_type').value;
    const isAllDay = document.getElementById('is_all_day').checked;
    const customWrapper = document.getElementById('customTypeWrapper');

    customWrapper.style.display = eventType === 'custom' ? '' : 'none';
    document.querySelectorAll('.time-field').forEach((field) => {
        field.style.display = isAllDay ? 'none' : '';
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('event_type').addEventListener('change', toggleAcademicCalendarFields);
    document.getElementById('is_all_day').addEventListener('change', toggleAcademicCalendarFields);
    toggleAcademicCalendarFields();
});
</script>
@endsection
