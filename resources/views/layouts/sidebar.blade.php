<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">@lang('translation.Menu')</li>

                <li>
                    <a href="{{ url('dashboard') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span key="t-dashboard">Dashboard</span>
                    </a>
                </li>


                    @php
                        $userRole = auth()->user() ? trim(strtolower(auth()->user()->role)) : '';
                        $allowedRoles = ['super_admin', 'admin', 'pengurus'];
                        $isAllowed = in_array($userRole, $allowedRoles);
                        \Log::info('Sidebar MasterData userRole: [' . $userRole . '], isAllowed: ' . ($isAllowed ? 'true' : 'false'));
                    @endphp
                    @if($isAllowed)
                    <li>
                        <a href="#masterDataSubmenu" data-bs-toggle="collapse" class="has-arrow" aria-expanded="false">
                            <i class="bx bx-data"></i>
                            <span>Master Data</span>
                        </a>
                        <ul class="sub-menu collapse" id="masterDataSubmenu">
                            @if(in_array($userRole, ['super_admin', 'pengurus']))
                            <li><a href="{{ route('yayasan.index') }}">Data Yayasan</a></li>
                            <li><a href="{{ route('pengurus.index') }}">Data Pengurus</a></li>
                            @endif
                            <li><a href="{{ route('admin.index') }}">Data Admin</a></li>
                            <li><a href="{{ route('madrasah.index') }}">Data Madrasah/Sekolah</a></li>
                            <li><a href="{{ route('tenaga-pendidik.index') }}">Data Tenaga Pendidik</a></li>
                            @if(in_array($userRole, ['super_admin', 'pengurus']))
                            <li><a href="{{ route('status-kepegawaian.index') }}">Data Status Kepegawaian</a></li>
                            <li><a href="{{ route('tahun-pelajaran.index') }}">Data Tahun Pelajaran</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if(in_array($userRole, ['super_admin', 'pengurus']))
                    <li>
                        <a href="{{ route('madrasah.profile') }}" class="waves-effect">
                            <i class="bx bx-building"></i>
                            <span>Profile Madrasah/Sekolah</span>
                        </a>
                    </li>
                    @endif

                    @php
                        $isAdminOnly = $userRole === 'admin';
                    @endphp
                {{-- @if($isAdminOnly)
                <li>
                    <a href="#adminMasterDataSubmenu" data-bs-toggle="collapse" class="has-arrow" aria-expanded="false">
                        <i class="bx bx-data"></i>
                        <span>Master Data (Admin)</span>
                    </a>
                    <ul class="sub-menu collapse" id="adminMasterDataSubmenu">
                        <li><a href="{{ route('admin_masterdata.admin.index') }}">Data Admin</a></li>
                        <li><a href="{{ route('admin_masterdata.madrasah.index') }}">Data Madrasah/Sekolah</a></li>
                        <li><a href="{{ route('admin_masterdata.tenaga-pendidik.index') }}">Data Tenaga Pendidik</a></li>
                        <li><a href="{{ route('admin_masterdata.status-kepegawaian.index') }}">Data Status Kepegawaian</a></li>
                        <li><a href="{{ route('admin_masterdata.tahun-pelajaran.index') }}">Data Tahun Pelajaran</a></li>
                    </ul>
                </li>
                @endif --}}

                @php
                    $presensiAllowed = in_array($userRole, ['tenaga_pendidik']) && auth()->user()->password_changed;
                    \Log::info('Sidebar Presensi userRole: [' . $userRole . '], password_changed: ' . (auth()->user()->password_changed ? 'true' : 'false') . ', presensiAllowed: ' . ($presensiAllowed ? 'true' : 'false'));
                @endphp
                @if($presensiAllowed)
                <li>
                    <a href="{{ route('presensi.index') }}" class="waves-effect">
                        <i class="bx bx-check-square"></i>
                        <span>Presensi</span>
                    </a>
                </li>
                @endif

                @if(in_array($userRole, ['super_admin', 'pengurus']))
                <li>
                    <a href="#presensiAdminSubmenu" data-bs-toggle="collapse" class="has-arrow">
                        <i class="bx bx-check-square"></i>
                        <span>Presensi Admin</span>
                    </a>
                    <ul class="sub-menu collapse" id="presensiAdminSubmenu">
                        <li><a href="{{ route('presensi_admin.settings') }}">Pengaturan Presensi</a></li>
                        <li><a href="{{ route('presensi_admin.index') }}">Data Presensi</a></li>
                    </ul>
                </li>
                @elseif($userRole === 'admin')
                <li>
                    <a href="{{ route('presensi_admin.index') }}" class="waves-effect">
                        <i class="bx bx-check-square"></i>
                        <span>Data Presensi</span>
                    </a>
                </li>
                @endif
                @php
                    \Log::info('Sidebar PresensiAdmin isAllowed: ' . ($isAllowed ? 'true' : 'false'));
                @endphp

                @if(in_array($userRole, ['super_admin', 'pengurus']))
                <li>
                    <a href="{{ route('development-history.index') }}" class="waves-effect">
                        <i class="bx bx-history"></i>
                        <span>Riwayat Pengembangan</span>
                    </a>
                </li>

                {{-- <li>
                    <a href="{{ route('panduan.index') }}" class="waves-effect">
                        <i class="bx bx-help-circle"></i>
                        <span>Panduan</span>
                    </a>
                </li> --}}

                <li>
                    <a href="{{ route('active-users.index') }}" class="waves-effect">
                        <i class="bx bx-user-check"></i>
                        <span>Pengguna Aktif</span>
                    </a>
                </li>
                @if($userRole === 'super_admin')
                <li>
                    <a href="{{ route('jadwal-mengajar.index') }}" class="waves-effect">
                        <i class="bx bx-calendar"></i>
                        <span>Jadwal Mengajar</span>
                    </a>
                </li>
                @endif
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->

