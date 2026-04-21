@extends('layouts.master')

@section('title', 'Tambah Jadwal Mengajar')

@section('css')
<link href="{{ asset('build/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/icons.min.css') }}" rel="stylesheet" />
<link href="{{ asset('build/css/app.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-plus me-2"></i>Tambah Jadwal Mengajar
                </h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('teaching-schedules.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Sekolah</label>
                                <input type="text" class="form-control" value="{{ $school->name ?? 'N/A' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Guru</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name ?? 'N/A' }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="day" class="form-label">Hari <span class="text-danger">*</span></label>
                                <select name="day" id="day" class="form-control" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin" {{ old('day') == 'Senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="Selasa" {{ old('day') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="Rabu" {{ old('day') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="Kamis" {{ old('day') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="Jumat" {{ old('day') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="Sabtu" {{ old('day') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label for="subject" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject') }}" placeholder="Contoh: Matematika" required>
                            </div>
                        </div>
                    </div>

                    <datalist id="available-classes">
                        @foreach(($availableClasses ?? []) as $className)
                            <option value="{{ $className }}"></option>
                        @endforeach
                    </datalist>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="class_name" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <input type="text" name="class_name" id="class_name" class="form-control" value="{{ old('class_name') }}" list="available-classes" placeholder="Pilih dari daftar atau ketik baru" required>
                                <div class="form-text">Jika kelas belum ada di daftar, Anda bisa mengetik sendiri.</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Simpan
                        </button>
                        <a href="{{ route('teaching-schedules.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

