@extends('layouts.master')

@section('title')
Pending Registrations - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pending Registrations</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Jabatan</th>
                                <th>Asal Sekolah</th>
                                <th>Submitted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingRegistrations as $registration)
                                <tr>
                                    <td>{{ $registration->name }}</td>
                                    <td>{{ $registration->email }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($registration->role) }}</span>
                                    </td>
                                    <td>{{ $registration->jabatan ?: '-' }}</td>
                                    <td>{{ $registration->madrasah ? $registration->madrasah->name : '-' }}</td>
                                    <td>{{ $registration->submitted_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.pending-registrations.approve', $registration->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to approve this registration?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.pending-registrations.reject', $registration->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject this registration?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No pending registrations found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $pendingRegistrations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
