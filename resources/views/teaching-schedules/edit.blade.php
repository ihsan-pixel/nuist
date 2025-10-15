@extends('layouts.master')

@section('title', 'Edit Jadwal Mengajar')

@component('components.breadcrumb')
    @slot('li_1') Master Data @endslot
    @slot('title') Edit Jadwal Mengajar @endslot
@endcomponent

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
                    <i class="bx bx-edit me-2"></i>Edit Jadwal Mengajar
                </h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('teaching-schedules.update', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="school_id" class="form-label">Sekolah <span class="text-danger">*</span></label>
                                <select name="school_id" id="school_id" class="form-control" required>
                                    <option value="">Pilih Sekolah</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ $schedule->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="teacher_id" class="form-label">Guru <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    <option value="">Pilih Guru</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $schedule->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="day" class="form-label">Hari <span class="text-danger">*</span></label>
                                <select name="day" id="day" class="form-control" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="Senin" {{ $schedule->day == 'Senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="Selasa" {{ $schedule->day == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="Rabu" {{ $schedule->day == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="Kamis" {{ $schedule->day == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="Jumat" {{ $schedule->day == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="Sabtu" {{ $schedule->day == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="subject" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="subject" class="form-control" value="{{ $schedule->subject }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="class_name" class="form-label">Kelas <span class="text-danger">*</span></label>
                                <input type="text" name="class_name" id="class_name" class="form-control" value="{{ $schedule->class_name }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="start_time" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ $schedule->start_time }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label for="end_time" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ $schedule->end_time }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Update
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
