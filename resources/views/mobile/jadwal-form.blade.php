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
                @php
                    $subjectValue = old('subject', optional($schedule)->subject ?? '');
                    $classValue = old('class_name', optional($schedule)->class_name ?? '');
                    $subjectIsKnown = $subjectValue !== '' && $subjects->contains($subjectValue);
                    $classIsKnown = $classValue !== '' && $classes->contains($classValue);
                @endphp

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
                    <select class="form-select" name="subject" id="subjectSelect" required>
                        <option value="" @selected($subjectValue === '')>Pilih mata pelajaran</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s }}" @selected($subjectValue === (string) $s)>{{ $s }}</option>
                        @endforeach
                        <option value="__new__" @selected(!$subjectIsKnown && $subjectValue !== '')>Tambah mata pelajaran baru...</option>
                    </select>
                    <input
                        type="text"
                        class="form-control mt-2"
                        name="subject_new"
                        id="subjectNew"
                        value="{{ old('subject_new', $subjectIsKnown ? '' : $subjectValue) }}"
                        placeholder="Tulis mata pelajaran baru"
                        style="{{ ($subjectValue === '' || $subjectIsKnown) ? 'display:none;' : '' }}"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label mb-1">Kelas</label>
                    <select class="form-select" name="class_name" id="classSelect" required>
                        <option value="" @selected($classValue === '')>Pilih kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c }}" @selected($classValue === (string) $c)>{{ $c }}</option>
                        @endforeach
                        <option value="__new__" @selected(!$classIsKnown && $classValue !== '')>Tambah kelas baru...</option>
                    </select>
                    <input
                        type="text"
                        class="form-control mt-2"
                        name="class_name_new"
                        id="classNew"
                        value="{{ old('class_name_new', $classIsKnown ? '' : $classValue) }}"
                        placeholder="Tulis nama kelas baru"
                        style="{{ ($classValue === '' || $classIsKnown) ? 'display:none;' : '' }}"
                    />
                    <div class="form-text">Daftar kelas & mapel diambil dari jadwal madrasah Anda. Jika belum ada, pilih "Tambah ..." lalu ketik.</div>
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

    <script>
        (function () {
            function bindToggle(selectId, newInputId) {
                const selectEl = document.getElementById(selectId);
                const newEl = document.getElementById(newInputId);
                if (!selectEl || !newEl) return;

                const toggle = () => {
                    if (selectEl.value === '__new__') {
                        newEl.style.display = '';
                    } else {
                        newEl.style.display = 'none';
                        newEl.value = '';
                    }
                };

                selectEl.addEventListener('change', toggle);
                toggle();
            }

            document.addEventListener('DOMContentLoaded', function () {
                bindToggle('subjectSelect', 'subjectNew');
                bindToggle('classSelect', 'classNew');
            });
        })();
    </script>

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
