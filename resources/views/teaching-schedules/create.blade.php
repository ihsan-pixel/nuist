@extends('layouts.master')

@section('title', 'Tambah Jadwal Mengajar')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tambah Jadwal Mengajar</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('teaching-schedules.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="school_id">Sekolah</label>
                                <select name="school_id" id="school_id" class="form-control" required>
                                    <option value="">Pilih Sekolah</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ Auth::user()->role === 'admin' && Auth::user()->madrasah_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="teacher_id">Guru</label>
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    <option value="">Pilih Guru</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    @endphp

                    @foreach($days as $index => $day)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ $day }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="subject_{{ $index }}">Mata Pelajaran</label>
                                        <input type="text" name="schedules[{{ $index }}][subject]" id="subject_{{ $index }}" class="form-control" placeholder="Contoh: Matematika">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="class_name_{{ $index }}">Kelas</label>
                                        <input type="text" name="schedules[{{ $index }}][class_name]" id="class_name_{{ $index }}" class="form-control" placeholder="Contoh: VII A">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="start_time_{{ $index }}">Jam Mulai</label>
                                        <input type="time" name="schedules[{{ $index }}][start_time]" id="start_time_{{ $index }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="end_time_{{ $index }}">Jam Selesai</label>
                                        <input type="time" name="schedules[{{ $index }}][end_time]" id="end_time_{{ $index }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="schedules[{{ $index }}][day]" value="{{ $day }}">
                    @endforeach

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('teaching-schedules.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
