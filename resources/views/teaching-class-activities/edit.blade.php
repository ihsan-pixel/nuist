@extends('layouts.master')

@section('title') Edit Kegiatan Kelas @endsection

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Kegiatan Kelas @endslot
    @slot('title') Edit Kegiatan @endslot
@endcomponent

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
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

                <form method="POST" action="{{ route('teaching-class-activities.update', $activity->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Madrasah</label>
                            <select class="form-select" name="school_id" id="schoolSelect" {{ auth()->user()->role === 'admin' ? 'disabled' : '' }} required>
                                <option value="">Pilih madrasah</option>
                                @foreach($schools as $s)
                                    <option value="{{ $s->id }}" @selected((string) old('school_id', $schoolId) === (string) $s->id)>
                                        {{ ($s->scod ?? '-') . ' - ' . ($s->name ?? '-') }}
                                    </option>
                                @endforeach
                            </select>
                            @if(auth()->user()->role === 'admin')
                                <input type="hidden" name="school_id" value="{{ old('school_id', $schoolId) }}">
                            @endif
                        </div>

                        <div class="col-md-6">
                            @php
                                $classValue = old('class_name', $activity->class_name);
                                $classIsKnown = $classValue !== '' && $classes->contains($classValue);
                            @endphp
                            <label class="form-label">Kelas</label>
                            <select class="form-select" name="class_name" id="classSelect" required>
                                <option value="">Pilih kelas</option>
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
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="start_date" value="{{ old('start_date', optional($activity->start_date)->toDateString()) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="end_date" value="{{ old('end_date', optional($activity->end_date)->toDateString()) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jenis Kegiatan</label>
                            <select class="form-select" name="activity_type" required>
                                <option value="">Pilih jenis</option>
                                @foreach($types as $t)
                                    <option value="{{ $t }}" @selected(old('activity_type', $activity->activity_type) === $t)>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Keterangan (opsional)</label>
                            <input type="text" class="form-control" name="description" value="{{ old('description', $activity->description) }}" placeholder="Contoh: UTS kelas VII A">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('teaching-class-activities.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const schoolSelect = document.getElementById('schoolSelect');
        const classSelect = document.getElementById('classSelect');
        const classNew = document.getElementById('classNew');

        function toggleNewClass() {
            if (!classSelect || !classNew) return;
            if (classSelect.value === '__new__') {
                classNew.style.display = '';
            } else {
                classNew.style.display = 'none';
                classNew.value = '';
            }
        }

        async function loadClassesForSchool(schoolId) {
            if (!classSelect) return;
            classSelect.innerHTML = '<option value=\"\">Memuat kelas...</option>';

            try {
                const url = @json(route('teaching-class-activities.get-classes', ['schoolId' => '__ID__']));
                const res = await fetch(url.replace('__ID__', String(schoolId)));
                const data = await res.json();

                classSelect.innerHTML = '<option value=\"\">Pilih kelas</option>';
                (data || []).forEach((c) => {
                    const opt = document.createElement('option');
                    opt.value = c;
                    opt.textContent = c;
                    classSelect.appendChild(opt);
                });

                const optNew = document.createElement('option');
                optNew.value = '__new__';
                optNew.textContent = 'Tambah kelas baru...';
                classSelect.appendChild(optNew);

                toggleNewClass();
            } catch (e) {
                classSelect.innerHTML = '<option value=\"\">Gagal memuat kelas</option>';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            if (schoolSelect && !schoolSelect.disabled) {
                schoolSelect.addEventListener('change', function () {
                    if (this.value) loadClassesForSchool(this.value);
                });
            }

            if (classSelect) classSelect.addEventListener('change', toggleNewClass);
            toggleNewClass();
        });
    })();
</script>
@endsection

