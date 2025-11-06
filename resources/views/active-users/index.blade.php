@extends('layouts.master')

@section('title') Pengguna Aktif @endsection

@section('css')
<style>
.user-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.user-card:hover {
    transform: translateY(-2px);
}

.role-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
    padding: 1rem;
}

.user-list {
    max-height: 400px;
    overflow-y: auto;
}

.user-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f1f1;
    transition: background-color 0.2s;
}

.user-item:hover {
    background-color: #f8f9fa;
}

.user-item:last-child {
    border-bottom: none;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.stats-card {
    border-radius: 10px;
    border: none;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}
</style>
@endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Dashboard @endslot
    @slot('title') Pengguna Aktif @endslot
@endcomponent

{{-- <div class="row">
    <div class="col-12">
        <div class="card filter-card mb-4">
            <div class="card-body">
                <h4 class="card-title text-white mb-4">
                    <i class="bx bx-user-check me-2"></i>
                    Pengguna Aktif Berdasarkan Role
                </h4>
                <p class="text-white-50 mb-0">
                    Daftar pengguna yang sedang aktif menggunakan sistem presensi, dikelompokkan berdasarkan role mereka
                </p>
            </div>
        </div>
    </div>
</div> --}}

<!-- Statistics -->
<div class="row mb-4">
    @php
        $totalActive = 0;
        foreach($activeUsersByRole as $role => $users) {
            $totalActive += $users->count();
        }
    @endphp
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card" data-role="total">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary rounded-circle fs-3">
                            <i class="bx bx-user-check"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">Total Aktif</p>
                        <h5 class="mb-0">{{ $totalActive }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($roleLabels as $role => $label)
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card" data-role="{{ $role }}">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-success rounded-circle fs-3">
                            <i class="bx bx-group"></i>
                        </span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <p class="text-muted mb-2">{{ $label }}</p>
                        <h5 class="mb-0">{{ $activeUsersByRole[$role]->count() ?? 0 }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Active Users by Role -->
<div class="row">
    @foreach($activeUsersByRole as $role => $users)
    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card user-card" data-role="{{ $role }}">
            <div class="role-header">
                <h5 class="mb-0">
                    <i class="bx bx-group me-2"></i>
                    {{ $roleLabels[$role] ?? ucfirst($role) }}
                    <span class="badge bg-light text-dark ms-2">{{ $users->count() }}</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="user-list">
                    @forelse($users as $user)
                    <div class="user-item">
                        <div class="d-flex align-items-center">
                            <img src="{{ $user->avatar ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg') }}"
                                 alt="Avatar" class="user-avatar me-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $user->name }}</h6>
                                <p class="text-muted mb-0 small">
                                    <i class="bx bx-envelope me-1"></i>{{ $user->email }}
                                </p>
                                @if($user->madrasah)
                                    <p class="text-muted mb-0 small">
                                        <i class="bx bx-building me-1"></i>{{ $user->madrasah->nama }}
                                    </p>
                                @endif
                                <p class="text-muted mb-0 small">
                                    <i class="bx bx-time me-1"></i>Terakhir aktif: {{ optional($user->last_seen)->diffForHumans() ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">{{ $user->nuist_id }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <p class="text-muted mb-0">Tidak ada pengguna aktif</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateActiveUsers() {
        fetch('/api/active-users', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data); // Debug log

            // Update all cards
            document.querySelectorAll('[data-role]').forEach(card => {
                let role = card.getAttribute('data-role');
                if (role === 'total') {
                    let h5 = card.querySelector('h5');
                    if (h5) h5.textContent = data.totalActive;
                } else {
                    let users = data.activeUsersByRole[role] || [];
                    let count = users.length;
                    let h5 = card.querySelector('h5');
                    if (h5) h5.textContent = count;
                    let badge = card.querySelector('.badge');
                    if (badge) badge.textContent = count;

                    let userList = card.querySelector('.user-list');
                    if (userList) {
                        let userListHtml = '';
                        if (count === 0) {
                            userListHtml = '<div class="text-center py-4"><p class="text-muted mb-0">Tidak ada pengguna aktif</p></div>';
                        } else {
                            users.forEach(user => {
                                userListHtml += `
<div class="user-item">
    <div class="d-flex align-items-center">
        <img src="${user.avatar}" alt="Avatar" class="user-avatar me-3">
        <div class="flex-grow-1">
            <h6 class="mb-1">${user.name}</h6>
            <p class="text-muted mb-0 small"><i class="bx bx-envelope me-1"></i>${user.email}</p>
            ${user.madrasah ? `<p class="text-muted mb-0 small"><i class="bx bx-building me-1"></i>${user.madrasah}</p>` : ''}
            <p class="text-muted mb-0 small"><i class="bx bx-time me-1"></i>Terakhir aktif: ${user.last_seen}</p>
        </div>
        <div class="text-end"><small class="text-muted">${user.nuist_id}</small></div>
    </div>
</div>
                                `;
                            });
                        }
                        userList.innerHTML = userListHtml;
                    }
                }
            });
        })
        .catch(error => {
            console.log('Error fetching active users:', error);
        });
    }

    // Initial load
    updateActiveUsers();

    // Poll every 30 seconds
    setInterval(updateActiveUsers, 30000);
});
</script>

@endsection
