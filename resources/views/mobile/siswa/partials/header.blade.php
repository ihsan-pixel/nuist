<div class="siswa-topbar">
    <div class="siswa-user">
        <div class="siswa-avatar">
            {{ strtoupper(substr($studentUser->name ?? 'S', 0, 1)) }}
        </div>
        <div>
            <small>{{ $subtitle ?? 'Portal siswa digital' }}</small>
            <strong>{{ $title ?? 'Menu Siswa' }}</strong>
        </div>
    </div>
    <a href="{{ route('mobile.siswa.notifikasi') }}" class="ghost-btn" style="width:auto; padding:10px 12px;">
        <i class="bx bx-bell"></i>
        @if(($notifications ?? collect())->where('is_read', false)->count() > 0)
            {{ ($notifications ?? collect())->where('is_read', false)->count() }}
        @endif
    </a>
</div>
