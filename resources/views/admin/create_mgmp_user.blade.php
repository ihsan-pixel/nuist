@extends('layouts.master')

@section('title')
    Buat Akun MGMP
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Admin @endslot
    @slot('title') Buat Akun MGMP @endslot
@endcomponent

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Form Buat Akun MGMP</h5>

                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.store_mgmp_user') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <!-- Sekolah field removed as requested -->

                    <button class="btn btn-primary">Buat Akun MGMP</button>
                </form>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Data Akun MGMP</h5>
                @if(isset($mgmpUsers) && $mgmpUsers->count())
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mgmpUsers as $u)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->created_at ? $u->created_at->format('Y-m-d H:i') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-muted">Belum ada akun MGMP.</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
