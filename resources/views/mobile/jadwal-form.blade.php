@extends('layouts.mobile')

@section('title', $isEditing ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar')

@section('content')
<div class="container py-3" style="max-width: 600px; margin: auto;">
    <header class="mobile-header d-md-none mb-3">
        <div class="d-flex align-items-center justify-content-between px-2 py-2">
            <div>
                <div class="fw-semibold">{{ $isEditing ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar' }}</div>
                <div class="text-muted small">Input mandiri hanya untuk periode aktif saat ini</div>
            </div>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('mobile.jadwal', ['period_id' => optional($selectedPeriod)->id]) }}">
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

        @if($selectedPeriod)
            <input type="hidden" name="period_id" value="{{ $selectedPeriod->id }}">
        @endif

        <div class="card mb-3">
            <div class="card-body">
                @if($selectedPeriod)
                    <div class="alert alert-info mb-3">
                        <div class="fw-semibold">{{ $selectedPeriod->title }}</div>
                        <div class="small">{{ $selectedPeriod->semester_label }} | {{ $selectedPeriod->school_year }}</div>
                        <div class="small">Berlaku {{ $selectedPeriod->date_range_label }}</div>
                        <div class="small mt-1">Jadwal guru akan disimpan pada periode aktif ini dan bentrok jadwal akan ditolak otomatis.</div>
                    </div>
                @endif

                @php
                    $subjectValue = old('subject', optional($schedule)->subject ?? '');
                    $selectedClassNames = collect(old('class_names', $schedule ? $schedule->resolvedClassNames() : []))
                        ->map(fn ($item) => trim((string) $item))
                        ->filter()
                        ->values();
                    $additionalClassNames = old('class_name_new', '');
                    $subjectIsKnown = $subjectValue !== '' && $subjects->contains($subjectValue);
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
                    <input type="hidden" name="class_name" value="">
                    @if($classes->isNotEmpty())
                        <div class="border rounded-3 p-3 bg-light">
                            <div class="small text-muted mb-2">Pilih satu atau beberapa kelas yang digabung dalam jam mengajar ini.</div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($classes as $c)
                                    @php $checked = $selectedClassNames->contains((string) $c); @endphp
                                    <label class="btn btn-sm {{ $checked ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3 class-chip">
                                        <input type="checkbox" name="class_names[]" value="{{ $c }}" class="d-none class-checkbox" @checked($checked)>
                                        {{ $c }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <textarea
                        class="form-control mt-2"
                        name="class_name_new"
                        rows="2"
                        placeholder="Tambahkan kelas lain jika perlu, pisahkan dengan koma atau baris baru"
                    >{{ $additionalClassNames }}</textarea>
                    <div class="form-text">Daftar kelas diambil dari jadwal madrasah Anda. Anda bisa memilih lebih dari satu kelas sekaligus.</div>
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
            document.addEventListener('DOMContentLoaded', function () {
                const subjectSelect = document.getElementById('subjectSelect');
                const subjectNew = document.getElementById('subjectNew');
                if (subjectSelect && subjectNew) {
                    const toggleSubject = () => {
                        if (subjectSelect.value === '__new__') {
                            subjectNew.style.display = '';
                        } else {
                            subjectNew.style.display = 'none';
                            subjectNew.value = '';
                        }
                    };
                    subjectSelect.addEventListener('change', toggleSubject);
                    toggleSubject();
                }

                document.querySelectorAll('.class-chip').forEach((chip) => {
                    const checkbox = chip.querySelector('.class-checkbox');
                    if (!checkbox) return;

                    const syncChip = () => {
                        chip.classList.toggle('btn-success', checkbox.checked);
                        chip.classList.toggle('btn-outline-secondary', !checkbox.checked);
                    };

                    chip.addEventListener('click', function (event) {
                        if (event.target.tagName === 'INPUT') return;
                        event.preventDefault();
                        checkbox.checked = !checkbox.checked;
                        syncChip();
                    });

                    checkbox.addEventListener('change', syncChip);
                    syncChip();
                });
            });
        })();
    </script>

    @if($errors->has('overlap'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Swal === 'undefined') return;
                Swal.fire({
                    icon: 'warning',
                    title: 'Jadwal Bentrok',
                    text: @json($errors->first('overlap')),
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

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
