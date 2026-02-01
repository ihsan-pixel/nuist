@extends('layouts.mobile')

@section('title', 'Pengguna Aktif')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Pengguna Aktif</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Madrasah</th>
                                    <th>Terakhir Login</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role == 'pengurus' ? 'info' : ($user->role == 'tenaga_pendidik' ? 'success' : 'primary') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->madrasah->name ?? '-' }}</td>
                                    <td>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d M Y H:i') : '-' }}</td>
                                    <td>
                                        @if($user->last_login_at && $user->last_login_at->isAfter(now()->subDays(7)))
                                            <span class="badge bg-success">Aktif</span>
                                        @elseif($user->last_login_at && $user->last_login_at->isAfter(now()->subDays(30)))
                                            <span class="badge bg-warning">Cukup Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pengguna aktif.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $activeUsers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
