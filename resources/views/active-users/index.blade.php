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

@livewire('active-users')

@section('js')
<script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.0/dist/echo.iife.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup Echo for Reverb
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config("broadcasting.connections.reverb.key") }}',
        wsHost: '{{ config("broadcasting.connections.reverb.options.host") }}',
        wsPort: {{ config("broadcasting.connections.reverb.options.port") }},
        wssPort: {{ config("broadcasting.connections.reverb.options.port") }},
        forceTLS: {{ config("broadcasting.connections.reverb.options.scheme") === "https" ? 'true' : 'false' }},
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        },
    });

    function updateUI(data) {
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
    }

    // Initial load
    fetch('/api/active-users', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => updateUI(data));

    // Listen for realtime updates
    window.Echo.channel('active-users')
        .listen('.active-users.updated', (e) => {
            console.log('Realtime update:', e);
            updateUI(e);
        });
});
</script>
@endsection

@endsection
