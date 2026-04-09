<nav class="bottom-nav-siswa">
    <a href="{{ route('mobile.siswa.dashboard') }}" class="{{ request()->routeIs('mobile.siswa.dashboard') ? 'active' : '' }}">
        <i class="bx bx-home-alt-2"></i>Dashboard
    </a>
    <a href="{{ route('mobile.siswa.tagihan') }}" class="{{ request()->routeIs('mobile.siswa.tagihan') || request()->routeIs('mobile.siswa.detail') ? 'active' : '' }}">
        <i class="bx bx-receipt"></i>Tagihan
    </a>
    <a href="{{ route('mobile.siswa.riwayat') }}" class="{{ request()->routeIs('mobile.siswa.riwayat') || request()->routeIs('mobile.siswa.bukti') ? 'active' : '' }}">
        <i class="bx bx-history"></i>Riwayat
    </a>
    <a href="{{ route('mobile.siswa.chat') }}" class="{{ request()->routeIs('mobile.siswa.chat') ? 'active' : '' }}">
        <i class="bx bx-message-dots"></i>Chat
    </a>
    <a href="{{ route('mobile.siswa.profile') }}" class="{{ request()->routeIs('mobile.siswa.profile') ? 'active' : '' }}">
        <i class="bx bx-user-circle"></i>Profil
    </a>
</nav>
