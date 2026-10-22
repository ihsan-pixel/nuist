<li class="menu-title">UTAMA</li>
<li>
    <a href="{{ route('dashboard') }}" class="waves-effect {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bx bx-home-circle"></i><span>Dashboard</span>
    </a>
</li>

<li class="menu-title">ORGANISASI & PENGGUNA</li>
<li>
    <a href="#superAdminMasterData" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('yayasan.*', 'pengurus.*', 'admin.*', 'operator-spp.*', 'madrasah.*', 'tenaga-pendidik.*', 'class-student-counts.*', 'dps.*', 'status-kepegawaian.*', 'tahun-pelajaran.*', 'admin_masterdata.broadcast-numbers.*') ? 'true' : 'false' }}">
        <i class="bx bx-data"></i><span>Master Data</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('yayasan.*', 'pengurus.*', 'admin.*', 'operator-spp.*', 'madrasah.*', 'tenaga-pendidik.*', 'class-student-counts.*', 'dps.*', 'status-kepegawaian.*', 'tahun-pelajaran.*', 'admin_masterdata.broadcast-numbers.*') ? 'show' : '' }}" id="superAdminMasterData">
        <li><a href="{{ route('yayasan.index') }}">Data Yayasan</a></li>
        <li><a href="{{ route('pengurus.index') }}">Data Pengurus</a></li>
        <li><a href="{{ route('admin.index') }}">Data Admin</a></li>
        <li><a href="{{ route('operator-spp.index') }}">Operator SPP</a></li>
        <li><a href="{{ route('madrasah.index') }}">Madrasah/Sekolah</a></li>
        <li><a href="{{ route('tenaga-pendidik.index') }}">Tenaga Pendidik</a></li>
        <li><a href="{{ route('class-student-counts.index') }}">Jumlah Siswa per Kelas</a></li>
        <li><a href="{{ route('dps.index') }}">Dana Pendidikan Sekolah</a></li>
        <li><a href="{{ route('status-kepegawaian.index') }}">Status Kepegawaian</a></li>
        <li><a href="{{ route('tahun-pelajaran.index') }}">Tahun Pelajaran</a></li>
        <li><a href="{{ route('admin_masterdata.broadcast-numbers.index') }}">Nomor Broadcast</a></li>
    </ul>
</li>
<li><a href="{{ route('admin.pending-registrations.index') }}" class="waves-effect"><i class="bx bx-user-plus"></i><span>Registrasi Menunggu</span></a></li>
<li><a href="{{ route('active-users.index') }}" class="waves-effect"><i class="bx bx-user-check"></i><span>Pengguna Aktif</span></a></li>

<li class="menu-title">MONITORING SEKOLAH</li>
<li>
    <a href="#superAdminMonitoring" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('admin.data_madrasah*', 'admin.teaching_progress', 'madrasah.profile', 'teaching-schedules.*', 'presensi_admin.*', 'admin.monitor-jurnal-mengajar') ? 'true' : 'false' }}">
        <i class="bx bx-line-chart"></i><span>Monitoring</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('admin.data_madrasah*', 'admin.teaching_progress', 'madrasah.profile', 'teaching-schedules.*', 'presensi_admin.*', 'admin.monitor-jurnal-mengajar') ? 'show' : '' }}" id="superAdminMonitoring">
        <li><a href="{{ route('admin.data_madrasah') }}">Kelengkapan Data</a></li>
        <li><a href="{{ route('admin.teaching_progress') }}">Progres Mengajar</a></li>
        <li><a href="{{ route('madrasah.profile') }}">Profil Madrasah/Sekolah</a></li>
        <li><a href="{{ route('teaching-schedules.index') }}">Jadwal Mengajar</a></li>
        <li><a href="{{ route('admin.monitor-jurnal-mengajar') }}">Jurnal Mengajar</a></li>
        <li><a href="{{ route('presensi_admin.index') }}">Data Presensi</a></li>
        <li><a href="{{ route('presensi_admin.settings') }}">Pengaturan Presensi</a></li>
        <li><a href="{{ route('presensi_admin.kiosk_devices') }}">Perangkat Kiosk</a></li>
        <li><a href="{{ route('presensi_admin.laporan_mingguan') }}">Laporan Presensi</a></li>
    </ul>
</li>
<li>
    <a href="#superAdminAcademic" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('academic-calendar-events.*', 'picket-schedule-periods.*') ? 'true' : 'false' }}">
        <i class="bx bx-calendar-check"></i><span>Kalender Akademik</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('academic-calendar-events.*', 'picket-schedule-periods.*') ? 'show' : '' }}" id="superAdminAcademic">
        <li><a href="{{ route('academic-calendar-events.index') }}">Event Akademik</a></li>
        <li><a href="{{ route('picket-schedule-periods.index') }}">Jadwal Piket</a></li>
    </ul>
</li>
<li>
    <a href="#superAdminSchoolData" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('data-sekolah.*') ? 'true' : 'false' }}">
        <i class="bx bx-buildings"></i><span>Data Sekolah</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('data-sekolah.*') ? 'show' : '' }}" id="superAdminSchoolData">
        <li><a href="{{ route('data-sekolah.data-siswa.index') }}">Data Siswa</a></li>
        <li><a href="{{ route('data-sekolah.siswa') }}">Rekap Siswa per Tahun</a></li>
        <li><a href="{{ route('data-sekolah.guru') }}">Rekap Guru per Tahun</a></li>
    </ul>
</li>

<li class="menu-title">LAYANAN</li>
<li>
    <a href="#superAdminSpmb" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('ppdb.*') ? 'true' : 'false' }}">
        <i class="bx bx-file"></i><span>SPMB / PPDB</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('ppdb.*') ? 'show' : '' }}" id="superAdminSpmb">
        <li><a href="{{ route('ppdb.index') }}">Halaman Publik</a></li>
        <li><a href="{{ route('ppdb.lp.dashboard') }}">Dashboard Pengelola</a></li>
    </ul>
</li>
<li>
    <a href="#superAdminMgmp" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('admin.mgmp_*') ? 'true' : 'false' }}">
        <i class="bx bx-group"></i><span>MGMP</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('admin.mgmp_*') ? 'show' : '' }}" id="superAdminMgmp">
        <li><a href="{{ route('admin.mgmp_dashboard') }}">Dashboard MGMP</a></li>
        <li><a href="{{ route('admin.create_mgmp_user') }}">Buat Akun MGMP</a></li>
        <li><a href="{{ route('admin.mgmp_reset_uploads') }}">Monitoring Unggahan</a></li>
    </ul>
</li>
<li>
    <a href="#superAdminUppm" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('uppm.*') || request()->routeIs('pembayaran.*') ? 'true' : 'false' }}">
        <i class="bx bx-money"></i><span>Keuangan</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('uppm.*') || request()->routeIs('pembayaran.*') ? 'show' : '' }}" id="superAdminUppm">
        <li><a href="{{ route('uppm.index') }}">Dashboard UPPM</a></li>
        <li><a href="{{ route('uppm.data-sekolah') }}">Data Sekolah</a></li>
        <li><a href="{{ route('uppm.perhitungan-iuran') }}">Perhitungan Iuran</a></li>
        <li><a href="{{ route('uppm.pembayaran') }}">Pembayaran UPPM</a></li>
        <li><a href="{{ route('uppm.update-uppm') }}">Pembaruan UPPM</a></li>
        <li><a href="{{ route('uppm.pengaturan') }}">Pengaturan UPPM</a></li>
    </ul>
</li>
<li>
    <a href="#superAdminSpp" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('spp-siswa.*') ? 'true' : 'false' }}">
        <i class="bx bx-wallet-alt"></i><span>SPP Siswa</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('spp-siswa.*') ? 'show' : '' }}" id="superAdminSpp">
        <li><a href="{{ route('spp-siswa.dashboard') }}">Dashboard SPP</a></li>
        <li><a href="{{ route('spp-siswa.tagihan') }}">Tagihan</a></li>
        <li><a href="{{ route('spp-siswa.transaksi') }}">Transaksi</a></li>
        <li><a href="{{ route('spp-siswa.laporan') }}">Laporan</a></li>
        <li><a href="{{ route('spp-siswa.pengaturan') }}">Pengaturan</a></li>
    </ul>
</li>
<li><a href="{{ route('sk-yayasan.dashboard') }}" class="waves-effect"><i class="bx bx-certification"></i><span>SK Yayasan</span></a></li>
<li><a href="{{ route('instumen-talenta.index') }}" class="waves-effect"><i class="bx bx-brain"></i><span>Talenta</span></a></li>
<li><a href="{{ route('admin.simfoni.index') }}" class="waves-effect"><i class="bx bx-sync"></i><span>Simfoni</span></a></li>
<li><a href="{{ route('admin.laporan-akhir-tahun.index') }}" class="waves-effect"><i class="bx bx-file-blank"></i><span>Laporan Akhir Tahun</span></a></li>

<li class="menu-title">SISTEM</li>
<li><a href="{{ route('chat.index') }}" class="waves-effect"><i class="bx bx-chat"></i><span>Chat Admin</span></a></li>
<li><a href="{{ route('fake-location.index') }}" class="waves-effect"><i class="bx bx-error-circle"></i><span>Deteksi Lokasi Palsu</span></a></li>
<li><a href="{{ route('development-history.index') }}" class="waves-effect"><i class="bx bx-history"></i><span>Riwayat Pengembangan</span></a></li>
<li><a href="{{ route('app-settings.index') }}" class="waves-effect"><i class="bx bx-cog"></i><span>Pengaturan Aplikasi</span></a></li>
