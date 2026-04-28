<div class="siswa-topbar">
    <div class="siswa-user">
        <div class="siswa-avatar">
            {{ strtoupper(substr($studentUser->name ?? 'S', 0, 1)) }}
        </div>
        <div>
            <small>{{ $subtitle ?? 'Portal siswa digital' }}</small>
            <strong>{{ $title ?? 'Menu Siswa' }}</strong>
            <div class="topbar-caption">{{ $studentUser->name ?? 'Siswa' }}</div>
        </div>
    </div>
    <a href="{{ route('mobile.siswa.notifikasi') }}" class="ghost-btn topbar-action" aria-label="Lihat notifikasi">
        <i class="bx bx-bell"></i>
        @if(($notifications ?? collect())->where('is_read', false)->count() > 0)
            <span class="notif-count">{{ ($notifications ?? collect())->where('is_read', false)->count() }}</span>
        @endif
    </a>
</div>
