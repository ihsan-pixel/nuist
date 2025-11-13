<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <!-- Logo dan Nama Aplikasi -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('ppdb.index') }}">
            <img src="{{ asset('images/logo1.png') }}" alt="Logo NUIST" class="me-2" style="height: 40px; width: auto;">
            <span class="fw-bold text-primary">PPDB </span>
        </a>

        <!-- Toggle Button untuk Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ppdb.index') ? 'active' : '' }}" href="{{ route('ppdb.index') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sekolah">Sekolah</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#kontak">Kontak</a>
                </li>
            </ul>

            <!-- Button Daftar di sebelah kanan -->
            <div class="d-flex">
                <a href="{{ route('ppdb.daftar', 'demo') }}" class="btn btn-primary">Daftar</a>
            </div>
        </div>
    </div>
</nav>
