<li class="menu-title">SEKOLAH</li>
<li><a href="<?php echo e(route('dashboard')); ?>" class="waves-effect"><i class="bx bx-home-circle"></i><span>Dashboard Sekolah</span></a></li>

<li class="menu-title">DATA & PEMBELAJARAN</li>
<li>
    <a href="#schoolAdminMasterData" data-bs-toggle="collapse" class="has-arrow" aria-expanded="<?php echo e(request()->routeIs('admin.index', 'madrasah.index', 'tenaga-pendidik.index', 'class-student-counts.index') ? 'true' : 'false'); ?>">
        <i class="bx bx-data"></i><span>Master Data</span>
    </a>
    <ul class="sub-menu collapse <?php echo e(request()->routeIs('admin.index', 'madrasah.index', 'tenaga-pendidik.index', 'class-student-counts.index') ? 'show' : ''); ?>" id="schoolAdminMasterData">
        <li><a href="<?php echo e(route('admin.index')); ?>">Admin Sekolah</a></li>
        <li><a href="<?php echo e(route('madrasah.index')); ?>">Profil Sekolah</a></li>
        <li><a href="<?php echo e(route('tenaga-pendidik.index')); ?>">Tenaga Pendidik</a></li>
        <li><a href="<?php echo e(route('class-student-counts.index')); ?>">Jumlah Siswa per Kelas</a></li>
    </ul>
</li>
<li><a href="<?php echo e(route('teaching-schedules.index')); ?>" class="waves-effect"><i class="bx bx-calendar"></i><span>Jadwal Mengajar</span></a></li>
<li>
    <a href="#schoolAdminAcademic" data-bs-toggle="collapse" class="has-arrow" aria-expanded="<?php echo e(request()->routeIs('academic-calendar-events.*', 'picket-schedule-periods.*') ? 'true' : 'false'); ?>">
        <i class="bx bx-calendar-check"></i><span>Kalender Akademik</span>
    </a>
    <ul class="sub-menu collapse <?php echo e(request()->routeIs('academic-calendar-events.*', 'picket-schedule-periods.*') ? 'show' : ''); ?>" id="schoolAdminAcademic">
        <li><a href="<?php echo e(route('academic-calendar-events.index')); ?>">Event Akademik</a></li>
        <li><a href="<?php echo e(route('picket-schedule-periods.index')); ?>">Jadwal Piket</a></li>
    </ul>
</li>
<li><a href="<?php echo e(route('sk-yayasan.sekolah.index')); ?>" class="waves-effect"><i class="bx bx-certification"></i><span>Perpanjangan SK</span></a></li>

<li class="menu-title">OPERASIONAL</li>
<li>
    <a href="#schoolAdminAttendance" data-bs-toggle="collapse" class="has-arrow" aria-expanded="<?php echo e(request()->routeIs('presensi_admin.*', 'admin.monitor-jurnal-mengajar') ? 'true' : 'false'); ?>">
        <i class="bx bx-check-square"></i><span>Presensi & Jurnal</span>
    </a>
    <ul class="sub-menu collapse <?php echo e(request()->routeIs('presensi_admin.*', 'admin.monitor-jurnal-mengajar') ? 'show' : ''); ?>" id="schoolAdminAttendance">
        <li><a href="<?php echo e(route('presensi_admin.show_detail', auth()->user()->madrasah_id)); ?>">Data Presensi Sekolah</a></li>
        <li><a href="<?php echo e(route('presensi_admin.kiosk_devices', ['madrasah_id' => auth()->user()->madrasah_id])); ?>">Perangkat Kiosk</a></li>
        <li><a href="<?php echo e(route('admin.monitor-jurnal-mengajar')); ?>">Monitoring Jurnal Mengajar</a></li>
    </ul>
</li>
<li>
    <a href="#schoolAdminPpdb" data-bs-toggle="collapse" class="has-arrow" aria-expanded="<?php echo e(request()->routeIs('ppdb.lp.*') ? 'true' : 'false'); ?>">
        <i class="bx bx-user-plus"></i><span>SPMB / PPDB</span>
    </a>
    <ul class="sub-menu collapse <?php echo e(request()->routeIs('ppdb.lp.*') ? 'show' : ''); ?>" id="schoolAdminPpdb">
        <?php
            $schoolAdminPpdbSetting = \App\Models\PPDBSetting::where('sekolah_id', auth()->user()->madrasah_id)
                ->where('tahun', now()->year)
                ->first();
            $schoolAdminMadrasah = auth()->user()->madrasah;
            $schoolAdminPpdbSlug = $schoolAdminPpdbSetting?->slug
                ?: \Illuminate\Support\Str::slug(($schoolAdminMadrasah?->name ?? 'madrasah') . '-' . auth()->user()->madrasah_id . '-' . now()->year);
        ?>
        <li><a href="<?php echo e(route('ppdb.lp.pendaftar', $schoolAdminPpdbSlug)); ?>">Pendaftar</a></li>
        <li><a href="<?php echo e(route('ppdb.lp.ppdb-settings', auth()->user()->madrasah_id)); ?>">Pengaturan PPDB</a></li>
        <li><a href="<?php echo e(route('ppdb.lp.edit', auth()->user()->madrasah_id)); ?>">Profil PPDB</a></li>
    </ul>
</li>
<li>
    <a href="#schoolAdminSchoolData" data-bs-toggle="collapse" class="has-arrow" aria-expanded="<?php echo e(request()->routeIs('data-sekolah.*') ? 'true' : 'false'); ?>">
        <i class="bx bx-buildings"></i><span>Data Sekolah</span>
    </a>
    <ul class="sub-menu collapse <?php echo e(request()->routeIs('data-sekolah.*') ? 'show' : ''); ?>" id="schoolAdminSchoolData">
        <li><a href="<?php echo e(route('data-sekolah.data-siswa.index')); ?>">Data Siswa</a></li>
        <li><a href="<?php echo e(route('data-sekolah.siswa')); ?>">Rekap Siswa per Tahun</a></li>
        <li><a href="<?php echo e(route('data-sekolah.guru')); ?>">Rekap Guru per Tahun</a></li>
    </ul>
</li>
<?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/layouts/partials/admin-menu.blade.php ENDPATH**/ ?>