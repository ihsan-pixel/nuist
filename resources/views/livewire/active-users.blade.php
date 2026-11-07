<div wire:poll.15s="refreshData">
    <div class="active-users-heading d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
        <div>
            <h4 class="mb-1"><i class="bx bx-user-check text-success me-2"></i>Pengguna Aktif</h4>
            <p class="text-muted mb-0">Pantau pengguna yang sedang aktif dalam sistem secara realtime.</p>
        </div>
        <div class="active-users-search">
            <i class="bx bx-search"></i>
            <input type="search" id="activeUserSearch" placeholder="Cari nama, email, atau sekolah..." autocomplete="off">
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm shrink-0">
                            <span class="avatar-title bg-primary rounded-circle fs-3">
                                <i class="bx bx-user-check"></i>
                            </span>
                        </div>
                        <div class="grow ms-3">
                            <p class="text-muted mb-2">Total Aktif</p>
                            <h5 class="mb-0">{{ $totalActive }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach($roleLabels as $role => $label)
        <div class="col-xl-3 col-md-6">
            <div class="card stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm shrink-0">
                            <span class="avatar-title bg-success rounded-circle fs-3">
                                <i class="bx bx-group"></i>
                            </span>
                        </div>
                        <div class="grow ms-3">
                            <p class="text-muted mb-2">{{ $label }}</p>
                            <h5 class="mb-0">{{ isset($activeUsersByRole[$role]) ? $activeUsersByRole[$role]->count() : 0 }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Active Users by Role --}}
    <div class="row">
        @foreach($activeUsersByRole as $role => $users)
        <div class="col-12 mb-4 active-role-section" data-role-section="{{ $role }}">
            <div class="card user-card">
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
                        <div class="user-item" data-user-search="{{ strtolower($user['name'].' '.$user['email'].' '.($user['madrasah'] ?? '').' '.$user['nuist_id']) }}">
                            <div class="d-flex align-items-center">
                                <img src="{{ $user['avatar'] }}"
                                     alt="Avatar" class="user-avatar me-3">
                                <div class="grow">
                                    <h6 class="mb-1">{{ $user['name'] }}</h6>
                                    <p class="text-muted mb-0 small">
                                        <i class="bx bx-envelope me-1"></i>{{ $user['email'] }}
                                    </p>
                                    @if($user['madrasah'])
                                        <p class="text-muted mb-0 small">
                                            <i class="bx bx-building me-1"></i>{{ $user['madrasah'] }}
                                        </p>
                                    @endif
                                    <p class="text-muted mb-0 small">
                                        <i class="bx bx-time me-1"></i>Terakhir aktif: {{ $user['last_seen'] }}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">{{ $user['nuist_id'] }}</small>
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

    <style>
    .user-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 .5rem 1.5rem rgba(15, 23, 42, .07);
        transition: transform 0.2s;
    }

    .user-card:hover {
        transform: translateY(-2px);
    }

    .role-header {
        background: linear-gradient(135deg, #075e4d 0%, #0b8b68 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: .9rem 1.15rem;
    }

    .user-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(285px, 1fr));
        gap: .85rem;
        max-height: 560px;
        padding: 1rem;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .user-item {
        min-width: 0;
        padding: .9rem;
        border: 1px solid #e7edef;
        border-radius: .8rem;
        background: #fff;
        transition: background-color 0.2s;
        overflow: hidden;
    }

    .active-users-heading h4 { font-weight: 700; color: #192d37; }
    .active-users-search { position: relative; width: min(100%, 360px); }
    .active-users-search i { position: absolute; left: .8rem; top: 50%; transform: translateY(-50%); color: #7b8790; }
    .active-users-search input { width: 100%; border: 1px solid #dfe8eb; border-radius: 999px; padding: .65rem 1rem .65rem 2.35rem; outline: none; }
    .active-users-search input:focus { border-color: #0b8b68; box-shadow: 0 0 0 .2rem rgba(11, 139, 104, .12); }

    .user-item:hover {
        background-color: #f7fbfa;
        border-color: #9ed7c5;
        box-shadow: 0 .35rem .8rem rgba(8, 116, 90, .08);
    }

    .user-item > .d-flex {
        min-width: 0;
        align-items: flex-start !important;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        flex: 0 0 40px;
    }

    .stats-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 .5rem 1.5rem rgba(15, 23, 42, .07);
    }

    @media (max-width: 575.98px) {
        .user-list { grid-template-columns: 1fr; padding: .75rem; }
        .active-users-search { width: 100%; }
    }

    .user-item .grow {
        min-width: 0;
        overflow: hidden;
    }

    .user-item .text-end {
        flex: 0 0 auto;
        margin-left: .5rem;
    }

    .user-item .text-end small {
        display: inline-block;
        padding: .2rem .4rem;
        border-radius: .35rem;
        background: #f0f5f6;
        color: #62727a !important;
        font-size: .68rem;
    }

    .user-item h6,
    .user-item p {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    :host{display:block}.active-users-heading{position:relative;padding:22px 24px;border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);animation:active-card-in .7s both}.active-users-heading h4{color:#192d37!important;font-size:22px}.active-users-search input{background:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.95);box-shadow:inset 0 1px 0 rgba(255,255,255,.9),0 5px 15px rgba(32,70,78,.06)}.stats-card{border:1px solid rgba(255,255,255,.92)!important;background:linear-gradient(135deg,rgba(255,255,255,.82),rgba(245,250,249,.58))!important;backdrop-filter:blur(16px);box-shadow:0 12px 30px rgba(32,70,78,.1),inset 0 1px 0 rgba(255,255,255,.9)!important;transition:transform .2s ease,box-shadow .2s ease;animation:active-card-in .7s both}.stats-card:hover{transform:translateY(-4px);box-shadow:0 16px 34px rgba(32,70,78,.14)!important}.stats-card .avatar-title{border:1px solid rgba(255,255,255,.9);background:linear-gradient(135deg,#e1f6ec,#bde8d1)!important;color:#08745a!important;box-shadow:0 7px 16px rgba(8,116,90,.12),inset 0 1px 0 rgba(255,255,255,.95)}.stats-card .text-muted{color:#6c7c86!important}.stats-card h5{background:linear-gradient(90deg,#005b58,#0e8549);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;font-size:26px;font-weight:800}.user-card{border:1px solid rgba(255,255,255,.9)!important;border-radius:22px!important;background:rgba(255,255,255,.68)!important;backdrop-filter:blur(18px);box-shadow:0 12px 32px rgba(32,70,78,.1)!important;overflow:hidden;animation:active-card-in .75s both}.role-header{background:linear-gradient(135deg,rgba(0,91,88,.96),rgba(8,116,90,.9));border-radius:0;padding:16px 20px;box-shadow:inset 0 1px 0 rgba(255,255,255,.35)}.role-header .badge{border:1px solid rgba(255,255,255,.7);background:rgba(255,255,255,.78)!important;color:#08745a!important;border-radius:999px}.user-item{border:1px solid rgba(255,255,255,.95);border-radius:15px;background:linear-gradient(135deg,rgba(255,255,255,.84),rgba(248,252,251,.6));box-shadow:0 5px 14px rgba(32,70,78,.06),inset 0 1px 0 rgba(255,255,255,.9);transition:transform .18s ease,box-shadow .18s ease,background .18s ease}.user-item:hover{background:rgba(255,255,255,.94);border-color:#9ed7c5;transform:translateY(-2px);box-shadow:0 10px 22px rgba(8,116,90,.12)}.user-avatar{border:3px solid #e4f5ee;box-shadow:0 5px 12px rgba(8,116,90,.12)}.user-item .text-end small{border:1px solid #dce7e9;background:rgba(255,255,255,.75);color:#52656d!important}@keyframes active-card-in{from{opacity:0;transform:translateY(16px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@media(prefers-reduced-motion:reduce){.active-users-heading,.stats-card,.user-card{animation:none!important}}
    </style>
    <script>
    document.addEventListener('input', function (event) {
        if (event.target.id !== 'activeUserSearch') return;
        const query = event.target.value.toLowerCase().trim();
        document.querySelectorAll('.active-role-section').forEach(section => {
            let visible = 0;
            section.querySelectorAll('.user-item[data-user-search]').forEach(item => {
                const match = !query || item.dataset.userSearch.includes(query);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            section.style.display = visible || !query ? '' : 'none';
        });
    });
    </script>
</div>
