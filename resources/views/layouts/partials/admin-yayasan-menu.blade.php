<li class="menu-title">YAYASAN</li>
<li><a href="{{ route('dashboard') }}" class="waves-effect"><i class="bx bx-home-circle"></i><span>Dashboard</span></a></li>
<li><a href="{{ route('madrasah.profile') }}" class="waves-effect"><i class="bx bx-building"></i><span>Profil Madrasah/Sekolah</span></a></li>
<li><a href="{{ route('pendataan-gtk.index') }}" class="waves-effect"><i class="bx bx-id-card"></i><span>Pendataan GTK</span></a></li>
<li><a href="{{ route('admin.teaching_progress') }}" class="waves-effect"><i class="bx bx-trending-up"></i><span>Progres Mengajar</span></a></li>

<li class="menu-title">PROGRAM & KEGIATAN</li>
<li>
    <a href="#foundationAdminCalendar" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('academic-calendar-events.*', 'picket-schedule-periods.*', 'admin.agenda.*') ? 'true' : 'false' }}">
        <i class="bx bx-calendar-event"></i><span>Kalender & Agenda</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('academic-calendar-events.*', 'picket-schedule-periods.*', 'admin.agenda.*') ? 'show' : '' }}" id="foundationAdminCalendar">
        <li><a href="{{ route('academic-calendar-events.index') }}">Event Akademik</a></li>
        <li><a href="{{ route('picket-schedule-periods.index') }}">Jadwal Piket</a></li>
        <li><a href="{{ route('admin.agenda.index') }}">Agenda BPPPMNU</a></li>
    </ul>
</li>
<li>
    <a href="#foundationAdminSk" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('sk-yayasan.*') ? 'true' : 'false' }}">
        <i class="bx bx-certification"></i><span>SK Yayasan</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('sk-yayasan.*') ? 'show' : '' }}" id="foundationAdminSk">
        <li><a href="{{ route('sk-yayasan.dashboard') }}">Dashboard SK Yayasan</a></li>
        <li><a href="{{ route('sk-yayasan.numbers.index') }}">Nomor SK</a></li>
        <li><a href="{{ route('sk-yayasan.pengajuan.index') }}">Pengajuan</a></li>
        <li><a href="{{ route('sk-yayasan.template.index') }}">Template</a></li>
        <li><a href="{{ route('sk-yayasan.generate.index') }}">Generate SK</a></li>
        <li><a href="{{ route('sk-yayasan.scan-upload.index') }}">Upload Scan SK</a></li>
    </ul>
</li>
<li><a href="{{ route('admin.mgmp_reset_uploads') }}" class="waves-effect"><i class="bx bx-search-alt"></i><span>Monitoring Unggahan MGMP</span></a></li>

<li class="menu-title">MONITORING</li>
<li>
    <a href="#foundationAdminAttendance" data-bs-toggle="collapse" class="has-arrow" aria-expanded="{{ request()->routeIs('presensi_admin.*') ? 'true' : 'false' }}">
        <i class="bx bx-check-square"></i><span>Presensi</span>
    </a>
    <ul class="sub-menu collapse {{ request()->routeIs('presensi_admin.*') ? 'show' : '' }}" id="foundationAdminAttendance">
        <li><a href="{{ route('presensi_admin.index') }}">Monitoring Presensi</a></li>
        <li><a href="{{ route('presensi_admin.settings') }}">Pengaturan Presensi</a></li>
        <li><a href="{{ route('presensi_admin.laporan_mingguan') }}">Laporan Mingguan</a></li>
    </ul>
</li>
