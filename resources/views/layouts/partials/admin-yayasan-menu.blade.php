<li class="menu-title">Admin Yayasan</li>
<li><a href="{{ route('dashboard') }}" class="waves-effect"><i class="bx bx-home-circle"></i><span>Dashboard</span></a></li>
<li><a href="{{ route('madrasah.profile') }}" class="waves-effect"><i class="bx bx-building"></i><span>Profile Madrasah/Sekolah</span></a></li>
<li>
    <a href="#academicCalendarSubmenu" data-bs-toggle="collapse" class="has-arrow" aria-expanded="false"><i class="bx bx-calendar-check"></i><span>Kalender Akademik</span></a>
    <ul class="sub-menu collapse" id="academicCalendarSubmenu">
        <li><a href="{{ route('academic-calendar-events.index') }}">Event Akademik</a></li>
        <li><a href="{{ route('picket-schedule-periods.index') }}">Izin Jadwal Piket</a></li>
    </ul>
</li>
<li>
    <a href="#skYayasanSubmenu" data-bs-toggle="collapse" class="has-arrow" aria-expanded="false"><i class="bx bx-certification"></i><span>SK Yayasan</span></a>
    <ul class="sub-menu collapse" id="skYayasanSubmenu">
        <li><a href="{{ route('sk-yayasan.dashboard') }}">Dashboard SK Yayasan</a></li>
        <li><a href="{{ route('sk-yayasan.numbers.index') }}">Nomor SK Yayasan</a></li>
        <li><a href="{{ route('sk-yayasan.pengajuan.index') }}">Pengajuan SK Yayasan</a></li>
        <li><a href="{{ route('sk-yayasan.template.index') }}">Template SK Yayasan</a></li>
        <li><a href="{{ route('sk-yayasan.generate.index') }}">Generate SK Yayasan</a></li>
    </ul>
</li>
<li><a href="{{ route('admin.teaching_progress') }}" class="waves-effect"><i class="bx bx-trending-up"></i><span>Progress Mengajar</span></a></li>
<li>
    <a href="#presensiAdminSubmenu" data-bs-toggle="collapse" class="has-arrow" aria-expanded="false"><i class="bx bx-check-square"></i><span>Presensi Admin</span></a>
    <ul class="sub-menu collapse" id="presensiAdminSubmenu">
        <li><a href="{{ route('presensi_admin.settings') }}">Pengaturan Presensi</a></li>
        <li><a href="{{ route('presensi_admin.index') }}">Data Presensi</a></li>
        <li><a href="{{ route('presensi_admin.laporan_mingguan') }}">Laporan</a></li>
    </ul>
</li>
<li><a href="{{ route('admin.mgmp_reset_uploads') }}" class="waves-effect"><i class="bx bx-search-alt"></i><span>Monitoring Riset MGMP</span></a></li>
<li><a href="{{ route('pendataan-gtk.index') }}" class="waves-effect"><i class="bx bx-id-card"></i><span>Pendataan GTK</span></a></li>
<li><a href="{{ route('admin.bpppmnu.events.index') }}" class="waves-effect"><i class="bx bx-calendar-event"></i><span>Agenda BPPPMNU</span></a></li>
