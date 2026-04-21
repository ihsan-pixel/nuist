@extends('layouts.mobile')

@section('title', $isEditing ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar')

@section('content')
<div class="container py-3" style="max-width: 600px; margin: auto;">
    <header class="mobile-header d-md-none mb-3">
        <div class="d-flex align-items-center justify-content-between px-2 py-2">
            <div>
                <div class="fw-semibold">{{ $isEditing ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar' }}</div>
                <div class="text-muted small">Pilih kelas dari daftar atau ketik kelas baru</div>
            </div>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('mobile.jadwal') }}">
                <i class="bx bx-arrow-back"></i>
            </a>
        </div>
    </header>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Periksa kembali input Anda:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $isEditing ? route('mobile.jadwal.update', $schedule->id) : route('mobile.jadwal.store') }}">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label mb-1">Hari</label>
                    <select class="form-select" name="day" required>
                        @php
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $selectedDay = old('day', optional($schedule)->day ?? '');
                        @endphp
                        <option value="" @selected($selectedDay === '')>Pilih hari</option>
                        @foreach($days as $d)
                            <option value="{{ $d }}" @selected($selectedDay === $d)>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label mb-1">Mata Pelajaran</label>
                    <input
                        type="text"
                        class="form-control"
                        name="subject"
                        list="subjectList"
                        value="{{ old('subject', optional($schedule)->subject ?? '') }}"
                        placeholder="Contoh: Matematika"
                        required
                    />
                    <datalist id="subjectList">
                        @foreach($subjects as $s)
                            <option value="{{ $s }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="mb-3">
                    <label class="form-label mb-1">Kelas</label>
                    <input
                        type="text"
                        class="form-control"
                        name="class_name"
                        list="classList"
                        value="{{ old('class_name', $schedule ? substr((string) $schedule->class_name, 0, 255) : '') }}"
                        placeholder="Contoh: VII A"
                        required
                    />
                    <datalist id="classList">
                        @foreach($classes as $c)
                            <option value="{{ $c }}"></option>
                        @endforeach
                    </datalist>
                    <div class="form-text">Ketik nama kelas jika belum ada di daftar.</div>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label mb-1">Jam Mulai</label>
                        <input
                            type="time"
                            class="form-control"
                            name="start_time"
                            value="{{ old('start_time', $schedule ? substr((string) $schedule->start_time, 0, 5) : '') }}"
                            required
                        />
                    </div>
                    <div class="col-6">
                        <label class="form-label mb-1">Jam Selesai</label>
                        <input
                            type="time"
                            class="form-control"
                            name="end_time"
                            value="{{ old('end_time', $schedule ? substr((string) $schedule->end_time, 0, 5) : '') }}"
                            required
                        />
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-success w-100" type="submit">
            <i class="bx bx-save me-1"></i> {{ $isEditing ? 'Simpan Perubahan' : 'Simpan Jadwal' }}
        </button>
    </form>

    @if($isEditing)
        <form method="POST" action="{{ route('mobile.jadwal.destroy', $schedule->id) }}" class="mt-2" onsubmit="return confirm('Hapus jadwal ini?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger w-100" type="submit">
                <i class="bx bx-trash me-1"></i> Hapus Jadwal
            </button>
        </form>
    @endif
</div>
@endsection
