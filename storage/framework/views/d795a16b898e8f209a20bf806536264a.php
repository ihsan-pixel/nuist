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
                        <li><a href="<?php echo e(route('admin.index')); ?>">Data Admin</a></li>
                        <li><a href="<?php echo e(route('madrasah.index')); ?>">Data Madrasah/Sekolah</a></li>
                        <li><a href="<?php echo e(route('tenaga-pendidik.index')); ?>">Data Tenaga Pendidik</a></li>
                        <?php if($userRole === 'super_admin'): ?>
                        <li><a href="<?php echo e(route('status-kepegawaian.index')); ?>">Data Status Kepegawaian</a></li>
                        <li><a href="<?php echo e(route('tahun-pelajaran.index')); ?>">Data Tahun Pelajaran</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <?php
                    $isAdminOnly = $userRole === 'admin';
                ?>
                

                <?php
                    $presensiAllowed = in_array($userRole, ['tenaga_pendidik']);
                    \Log::info('Sidebar Presensi userRole: [' . $userRole . '], presensiAllowed: ' . ($presensiAllowed ? 'true' : 'false'));
                ?>
                <?php if($presensiAllowed): ?>
                <li>
                    <a href="<?php echo e(route('presensi.index')); ?>" class="waves-effect">
                        <i class="bx bx-check-square"></i>
                        <span>Presensi</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if($userRole === 'super_admin'): ?>
                <li>
                    <a href="#presensiAdminSubmenu" data-bs-toggle="collapse" class="has-arrow">
                        <i class="bx bx-check-square"></i>
                        <span>Presensi Admin</span>
                    </a>
                    <ul class="sub-menu collapse" id="presensiAdminSubmenu">
                        <li><a href="<?php echo e(route('presensi_admin.settings')); ?>">Pengaturan Presensi</a></li>
                        <li><a href="<?php echo e(route('presensi_admin.index')); ?>">Data Presensi</a></li>
                    </ul>
                </li>
                <?php elseif($userRole === 'admin'): ?>
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

                <?php if($userRole === 'super_admin'): ?>
                <li>
                    <a href="<?php echo e(route('development-history.index')); ?>" class="waves-effect">
                        <i class="bx bx-history"></i>
                        <span>Riwayat Pengembangan</span>
                    </a>
                </li>

                <li>
                    <a href="<?php echo e(route('panduan.index')); ?>" class="waves-effect">
                        <i class="bx bx-help-circle"></i>
                        <span>Panduan</span>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<?php /**PATH C:\laragon\www\apk_nuist\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>