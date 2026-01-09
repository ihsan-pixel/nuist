<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu"><?php echo app('translator')->get('translation.Menu'); ?></li>

                <li>
                    <a href="<?php echo e(url('dashboard')); ?>" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <?php
                    $userRole = auth()->user() ? trim(strtolower(auth()->user()->role)) : '';
                    $allowedRoles = ['super_admin', 'admin'];
                    $isAllowed = in_array($userRole, $allowedRoles);
                    \Log::info('Sidebar MasterData userRole: [' . $userRole . '], isAllowed: ' . ($isAllowed ? 'true' : 'false'));
                ?>
                <?php if($isAllowed): ?>
                <li>
                    <a href="#masterDataSubmenu" data-bs-toggle="collapse" class="has-arrow" aria-expanded="false">
                        <i class="bx bx-data"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="sub-menu collapse" id="masterDataSubmenu">
                        <?php if(in_array($userRole, ['super_admin', 'pengurus'])): ?>
                        <li><a href="<?php echo e(route('yayasan.index')); ?>">Data Yayasan</a></li>
                        <li><a href="<?php echo e(route('pengurus.index')); ?>">Data Pengurus</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo e(route('admin.index')); ?>">Data Admin</a></li>
                        <li><a href="<?php echo e(route('madrasah.index')); ?>">Data Madrasah/Sekolah</a></li>
                        <li><a href="<?php echo e(route('tenaga-pendidik.index')); ?>">Data Tenaga Pendidik</a></li>
                        <?php if(in_array($userRole, ['super_admin', 'pengurus'])): ?>
                        <li><a href="<?php echo e(route('status-kepegawaian.index')); ?>">Data Status Kepegawaian</a></li>
                        <li><a href="<?php echo e(route('tahun-pelajaran.index')); ?>">Data Tahun Pelajaran</a></li>
                        <li><a href="<?php echo e(route('admin_masterdata.broadcast-numbers.index')); ?>">Data Broadcast</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <li class="menu-title">INFORMATION</li>

                <?php if(in_array($userRole, ['super_admin', 'pengurus'])): ?>
                <li>
                    <a href="<?php echo e(route('admin.data_madrasah')); ?>" class="waves-effect">
                        <i class="bx bx-bar-chart"></i>
                        <span>Kelengkapan Data</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($userRole === 'super_admin'): ?>
                <li>
                    <a href="<?php echo e(route('admin.teaching_progress')); ?>" class="waves-effect">
                        <i class="bx bx-trending-up"></i>
                        <span>Progres Mengajar</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($userRole, ['super_admin', 'pengurus'])): ?>
                <li>
                    <a href="<?php echo e(route('madrasah.profile')); ?>" class="waves-effect">
                        <i class="bx bx-building"></i>
                        <span>Profile Madrasah/Sekolah</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($userRole, ['super_admin', 'admin', 'pengurus']) || ($userRole === 'tenaga_pendidik' && auth()->user()->ketugasan === 'kepala madrasah/sekolah')): ?>
                <li>
                    <a href="<?php echo e(route('teaching-schedules.index')); ?>" class="waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span>Jadwal Mengajar</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php
                    $isAdminOnly = $userRole === 'admin';
                ?>
                



                <li class="menu-title">PRESENSI</li>

                <?php
                    $presensiAllowed = in_array($userRole, ['tenaga_pendidik']) && auth()->user()->password_changed;
                    \Log::info('Sidebar Presensi userRole: [' . $userRole . '], password_changed: ' . (auth()->user()->password_changed ? 'true' : 'false') . ', presensiAllowed: ' . ($presensiAllowed ? 'true' : 'false'));
                ?>
                <?php if($presensiAllowed): ?>

                <li>
                    <a href="<?php echo e(route('mobile.presensi')); ?>" class="waves-effect">
                        <i class="bx bx-check-square"></i>
                        <span>Presensi</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('teaching-attendances.index')); ?>" class="waves-effect">
                        <i class="bx bx-calendar-check"></i>
                        <span>Presensi Mengajar</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($userRole, ['super_admin', 'pengurus'])): ?>
                <li>
                    <a href="#presensiAdminSubmenu" data-bs-toggle="collapse" class="has-arrow">
                        <i class="bx bx-check-square"></i>
                        <span>Presensi Admin</span>
                    </a>
                    <ul class="sub-menu collapse" id="presensiAdminSubmenu">
                        <li><a href="<?php echo e(route('presensi_admin.settings')); ?>">Pengaturan Presensi</a></li>
                        <li><a href="<?php echo e(route('presensi_admin.index')); ?>">Data Presensi</a></li>
                        <li><a href="<?php echo e(route('presensi_admin.laporan_mingguan')); ?>">Laporan</a></li>
                    </ul>
                </li>
                <?php elseif($userRole === 'admin'): ?>
                <li>
                    <a href="<?php echo e(route('presensi_admin.show_detail', auth()->user()->madrasah_id)); ?>" class="waves-effect">
                        <i class="bx bx-check-square"></i>
                        <span>Data Presensi</span>
                    </a>
                </li>
                <?php elseif($userRole === 'tenaga_pendidik' && auth()->user()->ketugasan === 'kepala madrasah/sekolah'): ?>
                <li>
                    <a href="<?php echo e(route('presensi_admin.index')); ?>" class="waves-effect">
                        <i class="bx bx-check-square"></i>
                        <span>Data Presensi</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php
                    \Log::info('Sidebar PresensiAdmin isAllowed: ' . ($isAllowed ? 'true' : 'false'));
                ?>

                <?php if($userRole === 'admin'): ?>
                <li class="menu-title">PPDB</li>

                <li>
                    <a href="#ppdbSubmenu" data-bs-toggle="collapse" class="has-arrow" aria-expanded="false">
                        <i class="bx bx-file"></i>
                        <span>PPDB</span>
                    </a>
                    <ul class="sub-menu collapse" id="ppdbSubmenu">
                        <?php
                            $tahun = now()->year;
                            $ppdbSetting = \App\Models\PPDBSetting::where('sekolah_id', auth()->user()->madrasah_id)
                                ->where('tahun', $tahun)
                                ->first();

                            if ($ppdbSetting) {
                                $slug = $ppdbSetting->slug;
                            } else {
                                $madrasah = \App\Models\Madrasah::find(auth()->user()->madrasah_id);
                                $madrasahName = $madrasah ? $madrasah->name : 'madrasah-' . auth()->user()->madrasah_id;
                                $slug = \Illuminate\Support\Str::slug($madrasahName . '-' . auth()->user()->madrasah_id . '-' . $tahun);
                            }
                        ?>
                        <li><a href="<?php echo e(route('ppdb.lp.pendaftar', $slug)); ?>">Pendaftar</a></li>
                        <li><a href="<?php echo e(route('ppdb.lp.ppdb-settings', auth()->user()->madrasah_id)); ?>">Pengaturan</a></li>
                        <li><a href="<?php echo e(route('ppdb.lp.edit', auth()->user()->madrasah_id)); ?>">Edit Profile PPDB</a></li>
                        
                    </ul>
                </li>
                <?php endif; ?>

                <?php if(in_array($userRole, ['super_admin', 'pengurus'])): ?>

                <li class="menu-title">PPDB</li>

                
                
                <!-- Super Admin / Pengurus PPDB Menu -->
                

                <li>
                    <a href="<?php echo e(route('ppdb.index')); ?>" class="waves-effect">
                        <i class="bx bx-file"></i>
                        <span>Halaman PPDB</span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('ppdb.lp.dashboard')); ?>" class="waves-effect">
                        <i class="bx bx-bar-chart-alt-2"></i>
                        <span>Dashboard PPDB</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(in_array($userRole, ['super_admin', 'pengurus'])): ?>
                <li class="menu-title">ABOUT</li>

                <li>
                    <a href="<?php echo e(route('development-history.index')); ?>" class="waves-effect">
                        <i class="bx bx-history"></i>
                        <span>Riwayat Pengembangan</span>
                    </a>
                </li>

                

                <li>
                    <a href="<?php echo e(route('active-users.index')); ?>" class="waves-effect">
                        <i class="bx bx-user-check"></i>
                        <span>Pengguna Aktif</span>
                    </a>
                </li>

                <?php if($userRole === 'super_admin'): ?>
                <li>
                    <a href="<?php echo e(route('admin.simfoni.index')); ?>" class="waves-effect">
                        <i class="bx bx-data"></i>
                        <span>Simfoni</span>
                    </a>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                <?php if($userRole === 'super_admin'): ?>
                <li>
                    <a href="<?php echo e(route('fake-location.index')); ?>" class="waves-effect">
                        <i class="bx bx-error-circle"></i>
                        <span>Fake Location</span>
                    </a>
                </li>

                <li class="menu-title">CHAT</li>

                <?php if(in_array($userRole, ['super_admin', 'admin'])): ?>
                <li>
                    <a href="<?php echo e(route('chat.index')); ?>" class="waves-effect">
                        <i class="bx bx-chat"></i>
                        <span>Chat Admin</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="menu-title">SETTING</li>

                <li>
                    <a href="<?php echo e(route('app-settings.index')); ?>" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Pengaturan Aplikasi</span>
                    </a>
                </li>
                <?php endif; ?>
                

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/layouts/sidebar.blade.php ENDPATH**/ ?>