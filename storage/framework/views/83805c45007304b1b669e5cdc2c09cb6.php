<?php $__env->startSection('title'); ?> Dashboard - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboards <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="dashboard-page">
<div class="row g-4">
    <div class="col-lg-4 col-12 dashboard-page__profile-column">
        <!-- Welcome Card - Mobile Optimized -->
        <div class="card border-0 shadow-sm hover-lift overflow-hidden mb-4 dashboard-welcome-card">
            <div class="dashboard-welcome-card__banner">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="text-white p-3">
                            <p class="dashboard-eyebrow mb-1">Sistem Informasi Digital</p>
                            <h5 class="text-white mb-0">Selamat datang, <?php echo e(Str::before(trim(Auth::user()->name), ' ')); ?>!</h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <span class="dashboard-welcome-card__mark" aria-hidden="true"><i class="mdi mdi-view-dashboard-outline"></i></span>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg profile-user-wid mb-3 mb-md-0 dashboard-avatar">
                            <img src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg')); ?>" alt="Foto profil <?php echo e(Auth::user()->name); ?>" class="img-thumbnail rounded-circle">
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="font-size-16 mb-1"><?php echo e(Str::ucfirst(Auth::user()->name)); ?></h5>
                        <p class="text-muted mb-2">NUIST ID: <?php echo e(Auth::user()->nuist_id ?? '-'); ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <small class="badge bg-primary-subtle text-primary"><?php echo e(Auth::user()->statusKepegawaian?->name ?? 'Pengguna NUIST'); ?></small>
                            <small class="badge bg-info-subtle text-info"><?php echo e(Auth::user()->ketugasan ?? Str::headline(Auth::user()->role)); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'admin' && isset($madrasahData)): ?>
        <div class="dashboard-location-card card border-0 shadow-sm hover-lift">
            
            <div class="dashboard-location-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map-marker text-primary me-2"></i>
                                    Alamat Madrasah
                                </h5>
                                <p class="text-muted mb-0 small">Informasi lokasi dan kontak madrasah</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                    <i class="mdi mdi-school fs-5"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="mb-3 text-primary fw-semibold"><?php echo e($madrasahData->name); ?></h6>
                            <div class="d-flex align-items-start mb-3">
                                <i class="mdi mdi-map-marker-outline text-success me-2 mt-1"></i>
                                <div>
                                    <p class="text-muted mb-1 small">Alamat Lengkap</p>
                                    <p class="mb-0 fw-medium"><?php echo e($madrasahData->alamat ?? 'Alamat belum diisi'); ?></p>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahData->map_link): ?>
                            <a href="<?php echo e($madrasahData->map_link); ?>" target="_blank" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
            </div>
            </div>

            
            <div class="dashboard-location-map">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map text-success me-2"></i>
                                    Lokasi Madrasah
                                </h5>
                                <p class="text-muted mb-0 small">Peta lokasi madrasah saat ini</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                    <i class="mdi mdi-map-marker fs-5"></i>
                                </div>
                            </div>
                        </div>
                        <div id="map-container" style="height: 300px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahData->latitude && $madrasahData->longitude): ?>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168.639!2d<?php echo e($madrasahData->longitude); ?>!3d<?php echo e($madrasahData->latitude); ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s<?php echo e(urlencode($madrasahData->name)); ?>!2z<?php echo e($madrasahData->latitude); ?>,<?php echo e($madrasahData->longitude); ?>!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid&q=<?php echo e($madrasahData->latitude); ?>,<?php echo e($madrasahData->longitude); ?>"
                                        style="height: 100%; width: 100%; border-radius: 12px; border: none;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                                
                            <?php else: ?>
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light rounded">
                                    <div class="avatar-lg mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="mdi mdi-map-marker-off fs-1"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-2">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small px-3">
                                        Latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($madrasahData->map_link): ?>
                                    <a href="<?php echo e($madrasahData->map_link); ?>" target="_blank" class="btn btn-primary btn-sm mt-2">
                                        <i class="mdi mdi-google-maps me-1"></i>
                                        Lihat di Google Maps
                                    </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
            </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']) && isset($foundationData)): ?>
        <div class="dashboard-location-card card border-0 shadow-sm hover-lift">
            
            <div class="dashboard-location-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map-marker text-primary me-2"></i>
                                    Alamat Yayasan
                                </h5>
                                <p class="text-muted mb-0 small">Informasi lokasi dan kontak yayasan</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                    <i class="mdi mdi-office-building fs-5"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h6 class="mb-3 text-primary fw-semibold"><?php echo e($foundationData->name); ?></h6>
                            <div class="d-flex align-items-start mb-3">
                                <i class="mdi mdi-map-marker-outline text-success me-2 mt-1"></i>
                                <div>
                                    <p class="text-muted mb-1 small">Alamat Lengkap</p>
                                    <p class="mb-0 fw-medium"><?php echo e($foundationData->alamat ?? 'Alamat belum diisi'); ?></p>
                                </div>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationData->map_link): ?>
                            <a href="<?php echo e($foundationData->map_link); ?>" target="_blank" class="btn btn-primary btn-sm d-inline-flex align-items-center">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
            </div>
            </div>

            
            <div class="dashboard-location-map">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <h5 class="card-title mb-1 text-dark">
                                    <i class="mdi mdi-map text-success me-2"></i>
                                    Lokasi Yayasan
                                </h5>
                                <p class="text-muted mb-0 small">Peta lokasi yayasan saat ini</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                                    <i class="mdi mdi-map-marker fs-5"></i>
                                </div>
                            </div>
                        </div>
                        <div id="foundation-map-container" style="height: 300px; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: relative;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationData->latitude && $foundationData->longitude): ?>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3168.639!2d<?php echo e($foundationData->longitude); ?>!3d<?php echo e($foundationData->latitude); ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s<?php echo e(urlencode($foundationData->name)); ?>!2z<?php echo e($foundationData->latitude); ?>,<?php echo e($foundationData->longitude); ?>!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid&q=<?php echo e($foundationData->latitude); ?>,<?php echo e($foundationData->longitude); ?>"
                                        style="height: 100%; width: 100%; border-radius: 12px; border: none;"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationData->map_link): ?>
                                <div class="position-absolute top-0 end-0 p-2">
                                    <a href="<?php echo e($foundationData->map_link); ?>" target="_blank" class="btn btn-success btn-sm shadow-sm">
                                        <i class="mdi mdi-google-maps me-1"></i>
                                        Buka Peta Lengkap
                                    </a>
                                </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light rounded">
                                    <div class="avatar-lg mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                            <i class="mdi mdi-map-marker-off fs-1"></i>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-2">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small px-3">
                                        Latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($foundationData->map_link): ?>
                                    <a href="<?php echo e($foundationData->map_link); ?>" target="_blank" class="btn btn-primary btn-sm mt-2">
                                        <i class="mdi mdi-google-maps me-1"></i>
                                        Lihat di Google Maps
                                    </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
            </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(Auth::user()->role === 'tenaga_pendidik'): ?>
        <!-- Attendance Card - Mobile Optimized -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-calendar-check text-success me-2"></i>
                        Keaktifan Bulan Ini
                    </h5>
                    <div class="text-end">
                        <h3 class="text-success mb-0"><?php echo e(round($attendanceData['kehadiran'] ?? 0)); ?>%</h3>
                        <small class="text-muted">Kehadiran</small>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="mb-3">
                    <div class="nuist-attendance-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts" style="height: 200px;"></div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="mdi mdi-check-circle fs-5"></i>
                                </div>
                            </div>
                            <h6 class="mb-1"><?php echo e(round($attendanceData['kehadiran'] ?? 0)); ?>%</h6>
                            <small class="text-muted">Hadir</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light rounded p-3 text-center">
                            <div class="avatar-sm mx-auto mb-2">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="mdi mdi-medical-bag fs-5"></i>
                                </div>
                            </div>
                            <h6 class="mb-1"><?php echo e(round($attendanceData['izin_sakit'] ?? 0)); ?>%</h6>
                            <small class="text-muted">Izin/Sakit</small>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-calendar-text text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Total Presensi</small>
                                <strong><?php echo e($attendanceData['total_presensi'] ?? 0); ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex align-items-center">
                            <i class="mdi mdi-close-circle text-danger me-2"></i>
                            <div>
                                <small class="text-muted d-block">Tidak Hadir</small>
                                <strong><?php echo e(round($attendanceData['alpha'] ?? 0)); ?>%</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <div class="text-center">
                    <a href="<?php echo e(route('mobile.presensi')); ?>" class="btn btn-success btn-lg w-100">
                        <i class="mdi mdi-eye me-2"></i>
                        Lihat Detail Presensi
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']) && isset($superAdminStats)): ?>
    <div class="col-12 dashboard-teacher-status-card">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark"><i class="mdi mdi-account-group text-success me-2"></i>Statistik Tenaga Pendidik</h5>
                        <p class="text-muted mb-0 small">Pengelompokan berdasarkan status kepegawaian</p>
                    </div>
                    <span class="badge bg-success-subtle text-success"><?php echo e($superAdminStats['total_teachers']); ?> Orang</span>
                </div>
                <div class="dashboard-teacher-summary mb-4">
                    <div class="dashboard-teacher-summary-item">
                        <div class="avatar-sm"><div class="avatar-title rounded-circle"><i class="mdi mdi-school"></i></div></div>
                        <div><small>Total Sekolah</small><strong><?php echo e($superAdminStats['total_madrasah']); ?></strong></div>
                    </div>
                    <div class="dashboard-teacher-summary-item">
                        <div class="avatar-sm"><div class="avatar-title rounded-circle"><i class="mdi mdi-account-group"></i></div></div>
                        <div><small>Total Tenaga Pendidik</small><strong><?php echo e($superAdminStats['total_teachers']); ?></strong></div>
                    </div>
                    <div class="dashboard-teacher-summary-item">
                        <div class="avatar-sm"><div class="avatar-title rounded-circle"><i class="mdi mdi-account-tie"></i></div></div>
                        <div><small>Total Kepala Sekolah</small><strong><?php echo e($superAdminStats['total_school_principals']); ?></strong></div>
                    </div>
                    <div class="dashboard-teacher-summary-item">
                        <div class="avatar-sm"><div class="avatar-title rounded-circle"><i class="mdi mdi-account-cog"></i></div></div>
                        <div><small>Total Admin Sekolah</small><strong><?php echo e($superAdminStats['total_admin']); ?></strong></div>
                    </div>
                </div>
                <div class="table-responsive dashboard-teacher-status-table">
                    <?php
                        $statusColors = ['#20b96b', '#4596ee', '#ffb62e', '#9362e7', '#8798a6'];
                        $sortedEmploymentStatuses = $superAdminStats['total_by_status']->sortBy(function ($status) {
                            return strtolower(trim($status['status_name'])) === 'tidak diketahui' ? 1 : 0;
                        })->values();
                    ?>
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Status Kepegawaian</th>
                                <th class="text-center">Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $sortedEmploymentStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <?php ($percentage = $superAdminStats['total_teachers'] > 0 ? round($status['count'] / $superAdminStats['total_teachers'] * 100, 1) : 0); ?>
                            <tr>
                                <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                <td class="fw-medium"><?php echo e($status['status_name']); ?></td>
                                <td class="text-center"><?php echo e($status['count']); ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="progress flex-grow-1"><div class="progress-bar" style="width:<?php echo e($percentage); ?>%;background:<?php echo e($statusColors[$loop->index % count($statusColors)]); ?>"></div></div>
                                        <span class="dashboard-status-percentage"><?php echo e(number_format($percentage, 1, ',', '.')); ?>%</span>
                                    </div>
                                </td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data tenaga pendidik.</td></tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 dashboard-employment-donut-card">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark"><i class="mdi mdi-calendar-check text-success me-2"></i>Presensi Hari Ini</h5>
                        <small class="text-muted" id="attendance-live-date"><?php echo e($todayAttendance['date']); ?></small>
                    </div>
                    <span class="dashboard-live-indicator"><i></i> Live</span>
                </div>
                <div class="dashboard-live-attendance">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [
                        ['key' => 'present', 'percentage' => 'present_percentage', 'label' => 'Hadir', 'color' => '#20b96b'],
                        ['key' => 'permission', 'percentage' => 'permission_percentage', 'label' => 'Izin', 'color' => '#ffb62e'],
                        ['key' => 'absent', 'percentage' => 'absent_percentage', 'label' => 'Tidak Masuk', 'color' => '#ee6868']
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendanceItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="dashboard-live-attendance-item" data-attendance-key="<?php echo e($attendanceItem['key']); ?>" style="--attendance-color:<?php echo e($attendanceItem['color']); ?>;--attendance-value:<?php echo e($todayAttendance[$attendanceItem['percentage']]); ?>%">
                        <div class="dashboard-live-donut"><span class="attendance-percentage"><?php echo e(number_format($todayAttendance[$attendanceItem['percentage']], 1, ',', '.')); ?>%</span></div>
                        <strong><?php echo e($attendanceItem['label']); ?></strong>
                        <small><span class="attendance-count"><?php echo e($todayAttendance[$attendanceItem['key']]); ?></span> Orang</small>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <div class="dashboard-live-updated">Diperbarui <span id="attendance-live-updated"><?php echo e($todayAttendance['updated_at']); ?></span></div>
                <div class="dashboard-top-schools">
                    <h6><i class="mdi mdi-trophy-outline me-2"></i>5 Kehadiran Sekolah Tertinggi</h6>
                    <div id="attendance-top-schools">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $todayAttendance['top_schools']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="dashboard-top-school-item">
                            <span class="dashboard-top-school-rank"><?php echo e($loop->iteration); ?></span>
                            <div class="dashboard-top-school-info">
                                <strong><?php echo e($school['name']); ?></strong>
                                <div class="progress"><div class="progress-bar" style="width:<?php echo e($school['percentage']); ?>%"></div></div>
                            </div>
                            <span class="dashboard-top-school-value"><?php echo e(number_format($school['percentage'], 1, ',', '.')); ?>%</span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <div class="text-muted small text-center py-3">Belum ada data kehadiran sekolah hari ini.</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(Auth::user()->role === 'admin' && isset($adminStats)): ?>
    <div class="col-xl-8 col-12 dashboard-page__main-column dashboard-admin-stats">
        <!-- Statistics Overview Header -->
        <div class="card border-0 shadow-sm mb-4 dashboard-section-heading">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-white mb-1">Statistik Admin Sekolah</h4>
                        <p class="text-white-50 mb-0">Ringkasan Sistem Informasi NUIST</p>
                    </div>
                    <div class="avatar-lg">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-view-dashboard fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary Statistics Row -->
        <div class="row g-3 mb-4 dashboard-admin-primary-stats">
            
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--teachers">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1"><?php echo e($adminStats['total_teachers']); ?></h3>
                                <p class="text-white-75 mb-0 fs-6">Total Tenaga Pendidik</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-tie fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--principal">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schoolPrincipal): ?>
                                    <h4 class="text-white mb-1"><?php echo e($schoolPrincipal->name); ?></h4>
                                    <p class="text-white-75 mb-0 small">Kepala Sekolah</p>
                                <?php else: ?>
                                    <h4 class="text-white mb-1">-</h4>
                                    <p class="text-white-75 mb-0 small">Kepala Sekolah</p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--school">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1"><?php echo e(Auth::user()->madrasah ? Auth::user()->madrasah->name : 'N/A'); ?></h4>
                                <p class="text-white-75 mb-0 small">Madrasah Saat Ini</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-school fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--admins">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1"><?php echo e($adminStats['total_by_status']->count()); ?></h3>
                                <p class="text-white-75 mb-0 fs-6">Status Kepegawaian</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-group fs-3"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm dashboard-status-summary" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">Ringkasan Status Kepegawaian</h5>
                        <p class="text-muted mb-0 small">Distribusi tenaga pendidik berdasarkan status</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="mdi mdi-chart-pie fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($adminStats['total_by_status']->count() > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $adminStats['total_by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card border-0 shadow-sm h-100 hover-lift dashboard-status-card">
                                <div class="card-body p-3 text-center">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                            <i class="mdi mdi-account-tie fs-4"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2 text-dark"><?php echo e($status['count']); ?></h5>
                                    <p class="text-muted mb-2 small"><?php echo e($status['status_name']); ?></p>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             style="width: <?php echo e($adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100) : 0); ?>%"
                                             aria-valuenow="<?php echo e($status['count']); ?>"
                                             aria-valuemin="0"
                                             aria-valuemax="<?php echo e($adminStats['total_teachers']); ?>">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <?php echo e($adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100, 1) : 0); ?>%
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                        <i class="mdi mdi-information-outline fs-1"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted">Belum ada data status kepegawaian</h6>
                                <p class="text-muted small">Data akan muncul setelah ada tenaga pendidik terdaftar</p>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm dashboard-detail-table" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">Detail Statistik Tenaga Pendidik</h5>
                        <p class="text-muted mb-0 small">Tabel lengkap distribusi status kepegawaian</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                            <i class="mdi mdi-table fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-dark py-3 ps-4">Status Kepegawaian</th>
                                <th class="border-0 fw-semibold text-dark py-3 text-center">Jumlah</th>
                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($adminStats['total_by_status']->count() > 0): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $adminStats['total_by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr class="border-bottom border-light">
                                    <td class="py-3 ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                                    <i class="mdi mdi-account-tie fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="fw-medium"><?php echo e($status['status_name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary fs-6 px-3 py-2"><?php echo e($status['count']); ?></span>
                                    </td>
                                    <td class="py-3 pe-4">
                                        <div class="d-flex align-items-center">
                                            <div class="grow me-3">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                         style="width: <?php echo e($adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100) : 0); ?>%"
                                                         aria-valuenow="<?php echo e($status['count']); ?>"
                                                         aria-valuemin="0"
                                                         aria-valuemax="<?php echo e($adminStats['total_teachers']); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-medium small">
                                                <?php echo e($adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100, 1) : 0); ?>%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr class="table-primary bg-primary bg-opacity-10">
                                    <td class="py-3 ps-4 fw-bold text-primary">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-primary text-white rounded-circle">
                                                    <i class="mdi mdi-sigma fs-6"></i>
                                                </div>
                                            </div>
                                            Total Keseluruhan
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary text-white fs-6 px-3 py-2"><?php echo e($adminStats['total_teachers']); ?></span>
                                    </td>
                                    <td class="py-3 pe-4 fw-bold text-primary">100%</td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="mdi mdi-table-off fs-1"></i>
                                            </div>
                                        </div>
                                        <h6 class="text-muted">Belum ada data untuk ditampilkan</h6>
                                        <p class="text-muted small">Data statistik akan muncul setelah ada tenaga pendidik terdaftar</p>
                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm dashboard-attendance-card" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1 text-dark"><i class="mdi mdi-calendar-check text-success me-2"></i>Grafik Presensi</h5>
                        <p class="text-muted mb-0 small">Ringkasan presensi bulan ini</p>
                    </div>
                    <span class="badge bg-success-subtle text-success">Bulan ini</span>
                </div>
                <div class="nuist-attendance-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts" style="height: 220px;"></div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(false && in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']) && isset($superAdminStats)): ?>
    <div class="col-xl-8 col-12 dashboard-page__main-column dashboard-super-admin-stats">
        <!-- Statistics Overview Header -->
        <div class="card border-0 shadow-sm mb-4 dashboard-section-heading">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <?php if(Auth::user()->role === 'super_admin'): ?>
                            <h4 class="text-white mb-1">Dashboard Super Admin</h4>
                        <?php elseif(Auth::user()->role === 'pengurus'): ?>
                            <h4 class="text-white mb-1">Dashboard Pengurus</h4>
                        <?php elseif(Auth::user()->role === 'admin_yayasan'): ?>
                            <h4 class="text-white mb-1">Dashboard Admin Yayasan</h4>
                        <?php elseif(Auth::user()->role === 'admin'): ?>
                            <h4 class="text-white mb-1">Dashboard Admin</h4>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-white-50 mb-0">Ringkasan Sistem Informasi NUIST</p>
                    </div>
                    <div class="avatar-lg">
                        <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                            <i class="mdi mdi-view-dashboard fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary Statistics Row -->
        <div class="row g-3 mb-4 dashboard-super-primary-stats">
            
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--schools">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1"><?php echo e($superAdminStats['total_madrasah']); ?></h3>
                                <p class="text-white-75 mb-0 fs-6">Total Madrasah</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-school fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--teachers">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1"><?php echo e($superAdminStats['total_teachers']); ?></h3>
                                <p class="text-white-75 mb-0 fs-6">Total Tenaga Pendidik</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-tie fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--admins">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="text-white mb-1"><?php echo e($superAdminStats['total_admin']); ?></h3>
                                <p class="text-white-75 mb-0 fs-6">Total Admin</p>
                            </div>
                            <div class="avatar-md">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-cog fs-3"></i>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                                <div class="progress-bar bg-white" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Statistics Row -->
        <div class="row g-3 mb-4">
            
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--admins">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1"><?php echo e($superAdminStats['total_super_admin']); ?></h4>
                                <p class="text-white-75 mb-0 small">Super Admin</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-shield-account fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--leaders">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1"><?php echo e($superAdminStats['total_pengurus']); ?></h4>
                                <p class="text-white-75 mb-0 small">Pengurus</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-group fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-sm-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift dashboard-stat-card dashboard-stat-card--principals">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="text-white mb-1"><?php echo e($superAdminStats['total_school_principals']); ?></h4>
                                <p class="text-white-75 mb-0 small">Kepala Sekolah</p>
                            </div>
                            <div class="avatar-sm">
                                <div class="avatar-title bg-white bg-opacity-25 text-white rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm dashboard-super-status-summary" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">Ringkasan Status Kepegawaian</h5>
                        <p class="text-muted mb-0 small">Distribusi tenaga pendidik berdasarkan status</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="mdi mdi-chart-pie fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($superAdminStats['total_by_status']->count() > 0): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $superAdminStats['total_by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="card border-0 shadow-sm h-100 hover-lift dashboard-status-card">
                                <div class="card-body p-3 text-center">
                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                            <i class="mdi mdi-account-tie fs-4"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2 text-dark"><?php echo e($status['count']); ?></h5>
                                    <p class="text-muted mb-2 small"><?php echo e($status['status_name']); ?></p>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             style="width: <?php echo e($superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100) : 0); ?>%"
                                             aria-valuenow="<?php echo e($status['count']); ?>"
                                             aria-valuemin="0"
                                             aria-valuemax="<?php echo e($superAdminStats['total_teachers']); ?>">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <?php echo e($superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100, 1) : 0); ?>%
                                    </small>
                                </div>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-title bg-light text-muted rounded-circle">
                                        <i class="mdi mdi-information-outline fs-1"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted">Belum ada data status kepegawaian</h6>
                                <p class="text-muted small">Data akan muncul setelah ada tenaga pendidik terdaftar</p>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card border-0 shadow-sm dashboard-super-detail-table" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h5 class="card-title mb-1 text-dark">Detail Statistik Tenaga Pendidik</h5>
                        <p class="text-muted mb-0 small">Tabel lengkap distribusi status kepegawaian</p>
                    </div>
                    <div class="avatar-sm">
                        <div class="avatar-title bg-success bg-opacity-10 text-success rounded-circle">
                            <i class="mdi mdi-table fs-5"></i>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="border-radius: 10px; overflow: hidden;">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 fw-semibold text-dark py-3 ps-4">Status Kepegawaian</th>
                                <th class="border-0 fw-semibold text-dark py-3 text-center">Jumlah</th>
                                <th class="border-0 fw-semibold text-dark py-3 pe-4">Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($superAdminStats['total_by_status']->count() > 0): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $superAdminStats['total_by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr class="border-bottom border-light">
                                    <td class="py-3 ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle">
                                                    <i class="mdi mdi-account-tie fs-6"></i>
                                                </div>
                                            </div>
                                            <span class="fw-medium"><?php echo e($status['status_name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary bg-opacity-10 text-primary fs-6 px-3 py-2"><?php echo e($status['count']); ?></span>
                                    </td>
                                    <td class="py-3 pe-4">
                                        <div class="d-flex align-items-center">
                                            <div class="grow me-3">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                         style="width: <?php echo e($superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100) : 0); ?>%"
                                                         aria-valuenow="<?php echo e($status['count']); ?>"
                                                         aria-valuemin="0"
                                                         aria-valuemax="<?php echo e($superAdminStats['total_teachers']); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-muted fw-medium small">
                                                <?php echo e($superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100, 1) : 0); ?>%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <tr class="table-primary bg-primary bg-opacity-10">
                                    <td class="py-3 ps-4 fw-bold text-primary">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-3">
                                                <div class="avatar-title bg-primary text-white rounded-circle">
                                                    <i class="mdi mdi-sigma fs-6"></i>
                                                </div>
                                            </div>
                                            Total Keseluruhan
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-primary text-white fs-6 px-3 py-2"><?php echo e($superAdminStats['total_teachers']); ?></span>
                                    </td>
                                    <td class="py-3 pe-4 fw-bold text-primary">100%</td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle">
                                                <i class="mdi mdi-table-off fs-1"></i>
                                            </div>
                                        </div>
                                        <h6 class="text-muted">Belum ada data untuk ditampilkan</h6>
                                        <p class="text-muted small">Data statistik akan muncul setelah ada tenaga pendidik terdaftar</p>
                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm dashboard-super-attendance" style="border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h5 class="card-title mb-1 text-dark"><i class="mdi mdi-calendar-check text-success me-2"></i>Grafik Presensi</h5>
                        <p class="text-muted mb-0 small">Ringkasan presensi</p>
                    </div>
                    <span class="badge bg-light text-muted">Bulan ini</span>
                </div>
                <div class="dashboard-attendance-rings">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['label' => 'Hadir', 'value' => round($attendanceData['kehadiran'] ?? 0), 'color' => '#20a464'], ['label' => 'Izin / Sakit', 'value' => round($attendanceData['izin_sakit'] ?? 0), 'color' => '#f4b52b'], ['label' => 'Tidak Hadir', 'value' => round($attendanceData['alpha'] ?? 0), 'color' => '#e66a6a']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="text-center">
                        <div class="dashboard-attendance-ring" style="--ring-color: <?php echo e($attendance['color']); ?>; --ring-value: <?php echo e($attendance['value']); ?>%;"><span><?php echo e($attendance['value']); ?>%</span></div>
                        <small class="text-muted fw-semibold"><?php echo e($attendance['label']); ?></small>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array(Auth::user()->role, ['admin', 'super_admin', 'pengurus', 'admin_yayasan'])): ?>
    <div class="col-12">
        <!-- User Information Card - Mobile Optimized -->
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="mdi mdi-account-details text-primary me-2"></i>
                    Informasi Personal
                </h5>

                <!-- Basic Info -->
                <div class="mb-3">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Asal Madrasah/Sekolah</small>
                                <strong class="text-truncate d-block"><?php echo e(Auth::user()->madrasah ? Auth::user()->madrasah->name : '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Tempat Lahir</small>
                                <strong><?php echo e(Auth::user()->tempat_lahir ?? '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">Tanggal Lahir</small>
                                <strong><?php echo e(Auth::user()->tanggal_lahir ? \Carbon\Carbon::parse(Auth::user()->tanggal_lahir)->format('d F Y') : '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-light rounded">
                                <small class="text-muted d-block">TMT</small>
                                <strong><?php echo e(Auth::user()->tmt ? \Carbon\Carbon::parse(Auth::user()->tmt)->format('d F Y') : '-'); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="mb-3">
                    <h6 class="text-muted mb-3">Informasi Kepegawaian</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-primary-subtle rounded">
                                <small class="text-muted d-block">NUPTK</small>
                                <strong><?php echo e(Auth::user()->nuptk ?? '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-primary-subtle rounded">
                                <small class="text-muted d-block">NPK</small>
                                <strong><?php echo e(Auth::user()->npk ?? '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success-subtle rounded">
                                <small class="text-muted d-block">Kartanu</small>
                                <strong><?php echo e(Auth::user()->kartanu ?? '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-success-subtle rounded">
                                <small class="text-muted d-block">NIP Ma'arif</small>
                                <strong><?php echo e(Auth::user()->nip ?? '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-info-subtle rounded">
                                <small class="text-muted d-block">Status Kepegawaian</small>
                                <strong><?php echo e(Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-info-subtle rounded">
                                <small class="text-muted d-block">Ketugasan</small>
                                <strong><?php echo e(Auth::user()->ketugasan ?? '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-warning-subtle rounded">
                                <small class="text-muted d-block">Pendidikan Terakhir</small>
                                <strong><?php echo e(Auth::user()->pendidikan_terakhir ?? '-'); ?></strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 bg-warning-subtle rounded">
                                <small class="text-muted d-block">Program Studi</small>
                                <strong><?php echo e(Auth::user()->program_studi ?? '-'); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showUsers): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="mdi mdi-account-group text-info me-2"></i>
                    Rekan Guru/Pegawai Se-Madrasah/Sekolah
                </h5>

                <!-- Mobile-friendly list view -->
                <div class="list-group list-group-flush">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="shrink-0 me-3">
                                <img src="<?php echo e(isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg')); ?>"
                                     alt="Foto <?php echo e($user->name); ?>"
                                     class="rounded-circle"
                                     width="50"
                                     height="50">
                            </div>
                            <div class="grow">
                                <h6 class="mb-1"><?php echo e($user->name); ?></h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <small class="badge bg-primary-subtle text-primary"><?php echo e($user->ketugasan ?? '-'); ?></small>
                                    <small class="badge bg-info-subtle text-info"><?php echo e($user->statusKepegawaian ? $user->statusKepegawaian->name : '-'); ?></small>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <small class="text-muted"><?php echo e($users->firstItem() + $index); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($users->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($users->links()); ?>

                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
</div>










<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- apexcharts -->
<script src="<?php echo e(asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>



<style>
/* NUIST Dashboard — desktop-first, responsive, scoped to this page */
.dashboard-page{--nuist-green:#08745a;--nuist-dark:#064b3c;--nuist-ink:#192d37;--nuist-muted:#6c7c86;--nuist-border:#e6edf0;background:#f5f8fa;padding:18px;border-radius:16px;margin-bottom:24px}
.dashboard-page,.dashboard-page *{box-sizing:border-box}
.dashboard-page>.row{display:flex;flex-wrap:wrap;gap:18px;margin:0}
.dashboard-page>.row>.dashboard-page__profile-column,.dashboard-page>.row>.dashboard-page__main-column{padding:0;max-width:none;width:100%;flex:0 0 100%;min-width:0}
.dashboard-page__profile-column{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1.2fr);gap:18px;align-items:stretch}
.dashboard-page__profile-column>.card{margin:0!important;min-width:0;height:100%}
.dashboard-page>.row>.dashboard-teacher-status-card{grid-column:1;min-width:0;width:calc(66.667% - 6px);max-width:calc(66.667% - 6px);flex:0 0 calc(66.667% - 6px);padding:0;margin:0;}
.dashboard-page>.row>.dashboard-employment-donut-card{min-width:0;width:calc(33.333% - 12px);max-width:calc(33.333% - 12px);flex:0 0 calc(33.333% - 12px);padding:0;margin:0;}
.dashboard-employment-donut{height:230px}
.dashboard-employment-legend{display:grid;gap:9px;margin-top:8px}
.dashboard-employment-legend-item{display:grid;grid-template-columns:10px minmax(0,1fr) auto;gap:8px;align-items:center;font-size:12px;color:var(--nuist-muted)}
.dashboard-employment-dot{width:10px;height:10px;border-radius:50%}
.dashboard-employment-name{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.dashboard-employment-legend-item strong{color:var(--nuist-ink);font-size:12px;white-space:nowrap}
.dashboard-live-indicator{display:inline-flex;align-items:center;gap:6px;padding:5px 9px;border-radius:999px;background:#e7f7ef;color:#168653;font-size:11px;font-weight:700;text-transform:uppercase}
.dashboard-live-indicator i{width:7px;height:7px;border-radius:50%;background:#20b96b;box-shadow:0 0 0 4px rgba(32,185,107,.14)}
.dashboard-live-attendance{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.dashboard-live-attendance-item{text-align:center;min-width:0}
.dashboard-live-donut{width:88px;height:88px;margin:0 auto 10px;display:grid;place-items:center;position:relative;border-radius:50%;background:conic-gradient(var(--attendance-color) var(--attendance-value),color-mix(in srgb,var(--attendance-color) 16%,white) 0)}
.dashboard-live-donut:after{content:'';position:absolute;inset:10px;border-radius:50%;background:#fff}
.dashboard-live-donut span{position:relative;z-index:1;color:var(--nuist-ink);font-size:15px;font-weight:700}
.dashboard-live-attendance-item>strong{display:block;color:var(--nuist-ink);font-size:13px}
.dashboard-live-attendance-item>small{display:block;margin-top:3px;color:var(--nuist-muted);font-size:11px}
.dashboard-live-updated{margin-top:18px;padding-top:12px;border-top:1px solid var(--nuist-border);color:var(--nuist-muted);font-size:11px;text-align:center}
.dashboard-top-schools{margin-top:16px;padding-top:16px;border-top:1px solid var(--nuist-border)}
.dashboard-top-schools h6{margin-bottom:12px;font-size:13px;color:var(--nuist-ink)}
.dashboard-top-school-item{display:grid;grid-template-columns:24px minmax(0,1fr) 48px;gap:10px;align-items:center;padding:11px 0}
.dashboard-top-school-item+.dashboard-top-school-item{border-top:1px solid #edf1f2}
.dashboard-top-school-rank{display:grid;place-items:center;width:24px;height:24px;border-radius:50%;background:#e6f5ee;color:var(--nuist-green);font-size:11px;font-weight:700}
.dashboard-top-school-info{min-width:0}
.dashboard-top-school-info strong{display:block;margin-bottom:5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--nuist-ink);font-size:11px}
.dashboard-top-school-info .progress{height:5px;background:#e5ecee;border-radius:999px}
.dashboard-top-school-info .progress-bar{background:var(--nuist-green)!important;border-radius:999px}
.dashboard-top-school-value{text-align:right;color:var(--nuist-green);font-size:11px;font-weight:700}
.dashboard-teacher-status-card>.card{width:100%;height:100%;}
.dashboard-page>.row>.dashboard-teacher-status-card,.dashboard-page>.row>.dashboard-employment-donut-card{align-self:stretch}
.dashboard-teacher-summary{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}
.dashboard-teacher-summary-item{display:flex;align-items:center;gap:12px;min-width:0;padding:14px;border:1px solid var(--nuist-border);border-radius:10px;background:#fff}
.dashboard-teacher-summary-item .avatar-title{background:#e6f5ee;color:var(--nuist-green)}
.dashboard-teacher-summary-item small{display:block;color:var(--nuist-muted);font-size:12px;line-height:1.3}
.dashboard-teacher-summary-item strong{display:block;margin-top:3px;color:var(--nuist-ink);font-size:22px;line-height:1.2}
.dashboard-teacher-status-table{border:1px solid var(--nuist-border);border-radius:10px;overflow:hidden}
.dashboard-teacher-status-table .table{min-width:560px}
.dashboard-teacher-status-table thead th{padding:14px 18px;background:#f2f6f8;color:var(--nuist-ink);font-weight:700;border-bottom:1px solid var(--nuist-border)}
.dashboard-teacher-status-table tbody td{padding:13px 18px;border-bottom:1px solid var(--nuist-border);color:#465a64}
.dashboard-teacher-status-table tbody tr:last-child td{border-bottom:0}
.dashboard-teacher-status-table .progress{height:14px;max-width:230px;background:#e4eaed;border-radius:999px}
.dashboard-teacher-status-table .progress-bar{border-radius:999px}
.dashboard-status-percentage{width:58px;color:#465a64;font-weight:600}
.dashboard-page .card{border:1px solid var(--nuist-border)!important;border-radius:14px!important;box-shadow:0 3px 14px rgba(25,45,55,.045)!important;background:#fff;overflow:hidden}
.dashboard-page .card-body{padding:20px!important}
.dashboard-page h3,.dashboard-page h4,.dashboard-page h5,.dashboard-page h6{color:var(--nuist-ink);overflow-wrap:anywhere}
.dashboard-page .text-muted{color:var(--nuist-muted)!important}
.dashboard-welcome-card__banner{background:linear-gradient(110deg,#08674e,#15946b);min-height:112px;position:relative}
.dashboard-welcome-card__banner .p-3{padding:22px!important}
.dashboard-welcome-card__banner h5{color:#fff!important;font-size:20px}
.dashboard-eyebrow{font-size:12px;letter-spacing:.06em;opacity:.8;text-transform:uppercase}
.dashboard-welcome-card__mark{font-size:48px;color:rgba(255,255,255,.22);margin-right:20px}
.dashboard-avatar img{width:76px;height:76px;object-fit:cover;border:4px solid #fff;box-shadow:0 4px 15px #0002}
.dashboard-welcome-card .profile-user-wid,
.dashboard-welcome-card .dashboard-avatar{position:static!important;top:auto!important;left:auto!important;margin:0!important;flex:0 0 96px;width:96px;height:96px;display:flex;align-items:center;justify-content:center;background:#fff;border-radius:50%;box-shadow:0 3px 12px rgba(25,45,55,.12)}
.dashboard-welcome-card .dashboard-avatar img{width:82px;height:82px;border:0;box-shadow:none}
.dashboard-welcome-card .card-body{padding-top:20px!important}
.dashboard-location-card{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr)}
.dashboard-location-card .card-body{height:100%}
.dashboard-location-info{border-right:1px solid var(--nuist-border)}
.dashboard-location-map #map-container,.dashboard-location-map #foundation-map-container{height:200px!important;box-shadow:none!important;border-radius:10px!important}
.dashboard-page__main-column{display:grid;grid-template-columns:minmax(0,1.65fr) minmax(300px,.9fr);gap:18px;align-items:start}
.dashboard-page__main-column>.card,.dashboard-page__main-column>.row{margin:0!important;min-width:0}
.dashboard-section-heading{grid-column:1/-1;background:#fff!important}
.dashboard-section-heading .card-body{padding:18px 22px!important}
.dashboard-section-heading h4{color:var(--nuist-ink)!important;font-size:19px;margin-bottom:4px!important}
.dashboard-section-heading p{color:var(--nuist-muted)!important}
.dashboard-section-heading .avatar-lg{display:none}
.dashboard-admin-primary-stats,.dashboard-super-admin-stats>.row{grid-column:1/-1;display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin:0!important}
.dashboard-super-admin-stats>.row{grid-template-columns:repeat(3,minmax(0,1fr))}
.dashboard-admin-primary-stats>[class*=col-],.dashboard-super-admin-stats>.row>[class*=col-]{width:100%;max-width:none;min-width:0;padding:0}
.dashboard-page .dashboard-stat-card{background:#fff!important;min-height:122px}
.dashboard-page .dashboard-stat-card .card-body{padding:18px!important}
.dashboard-page .dashboard-stat-card h3,.dashboard-page .dashboard-stat-card h4{color:var(--nuist-ink)!important;font-size:24px!important;font-weight:700;line-height:1.25}
.dashboard-page .dashboard-stat-card p{color:var(--nuist-muted)!important;font-size:13px!important}
.dashboard-page .dashboard-stat-card .avatar-title{background:#e6f5ee!important;color:var(--nuist-green)!important}
.dashboard-page .dashboard-stat-card .progress{display:none}
.dashboard-status-summary,.dashboard-super-status-summary{grid-column:2;grid-row:auto}
.dashboard-detail-table,.dashboard-super-detail-table{grid-column:1;grid-row:auto}
.dashboard-attendance-card{grid-column:2}
.dashboard-super-attendance{grid-column:2;grid-row:auto}
.dashboard-attendance-rings{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;align-items:start}
.dashboard-attendance-ring{width:76px;height:76px;margin:0 auto 8px;display:grid;place-items:center;border-radius:50%;background:conic-gradient(var(--ring-color) var(--ring-value),#edf1f2 0);position:relative;font-weight:700;color:var(--nuist-ink)}
.dashboard-attendance-ring:after{content:'';position:absolute;inset:8px;border-radius:50%;background:#fff}
.dashboard-attendance-ring span{position:relative;z-index:1}
.dashboard-page .dashboard-status-card{box-shadow:none!important;background:#f8fafb!important}
.dashboard-page .dashboard-status-summary .row,.dashboard-page .dashboard-super-status-summary .row{gap:10px}
.dashboard-page .dashboard-status-summary .row>[class*=col-],.dashboard-page .dashboard-super-status-summary .row>[class*=col-]{width:100%;flex:0 0 100%}
.dashboard-page .dashboard-status-card .card-body{display:grid;grid-template-columns:40px 1fr auto;align-items:center;text-align:left!important;gap:8px;padding:10px 12px!important}
.dashboard-page .dashboard-status-card .avatar-md{grid-column:1;grid-row:1/3;margin:0!important}
.dashboard-page .dashboard-status-card h5{grid-column:3;grid-row:1/3;margin:0!important}
.dashboard-page .dashboard-status-card p{grid-column:2;grid-row:1/3;margin:0!important}
.dashboard-page .dashboard-status-card .progress,.dashboard-page .dashboard-status-card small{display:none!important}
.dashboard-page .table{font-size:13px}
.dashboard-page .table td,.dashboard-page .table th{padding-top:12px!important;padding-bottom:12px!important}
.dashboard-page .progress{background:#e9eff1}
.dashboard-page .progress-bar{background:var(--nuist-green)!important}
.dashboard-page .btn-primary,.dashboard-page .btn-success{background:var(--nuist-green);border-color:var(--nuist-green)}
.dashboard-page .badge{font-weight:500}
.dashboard-page .hover-lift{transition:transform .18s ease,box-shadow .18s ease}
.dashboard-page .hover-lift:hover{transform:translateY(-2px);box-shadow:0 8px 24px #193b3415!important}
@media(max-width:1199.98px){.dashboard-page__main-column{grid-template-columns:minmax(0,1fr) minmax(260px,.85fr)}.dashboard-admin-primary-stats{grid-template-columns:repeat(2,minmax(0,1fr))}.dashboard-teacher-summary{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:991.98px){.dashboard-page__profile-column{grid-template-columns:1fr}.dashboard-location-card{grid-template-columns:1fr 1fr}.dashboard-page__main-column{grid-template-columns:1fr}.dashboard-section-heading,.dashboard-admin-primary-stats,.dashboard-super-admin-stats>.row,.dashboard-status-summary,.dashboard-super-status-summary,.dashboard-detail-table,.dashboard-super-detail-table,.dashboard-attendance-card,.dashboard-super-attendance{grid-column:1;grid-row:auto}.dashboard-super-admin-stats>.row{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:991.98px){.dashboard-page>.row>.dashboard-teacher-status-card,.dashboard-page>.row>.dashboard-employment-donut-card{width:100%;max-width:none;flex-basis:100%}}
@media(max-width:575.98px){.dashboard-page>.row>.dashboard-teacher-status-card{width:100%;max-width:none;flex-basis:100%;margin-left:0}.dashboard-teacher-summary{grid-template-columns:1fr}.dashboard-page{padding:10px;margin:0 -10px 16px;border-radius:0}.dashboard-page>.row,.dashboard-page__profile-column,.dashboard-page__main-column{gap:12px}.dashboard-location-card{grid-template-columns:1fr}.dashboard-location-info{border-right:0;border-bottom:1px solid var(--nuist-border)}.dashboard-admin-primary-stats,.dashboard-super-admin-stats>.row{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.dashboard-page .card-body{padding:15px!important}.dashboard-page .dashboard-stat-card h3,.dashboard-page .dashboard-stat-card h4{font-size:19px!important}.dashboard-page .dashboard-stat-card .card-body{padding:13px!important}.dashboard-page .table{min-width:460px}}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var attendanceData = <?php echo json_encode($attendanceData ?? ['kehadiran' => 0, 'izin_sakit' => 0, 'alpha' => 0]) ?>;

        var options = {
            chart: {
                height: 200,
                type: 'radialBar',
            },
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            fontSize: '16px',
                        },
                        value: {
                            fontSize: '14px',
                            formatter: function (val) {
                                return val + "%";
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            formatter: function () {
                                return 100 + "%";
                            }
                        }
                    }
                }
            },
            colors: ['#198754', '#ffc107', '#dc3545'],
            series: [
                attendanceData.kehadiran,
                attendanceData.izin_sakit,
                attendanceData.alpha
            ],
            labels: ['Kehadiran', 'Izin/Sakit', 'Tidak Hadir'],
            legend: {
                position: 'bottom',
                formatter: function (val, opts) {
                    return val + " - " + opts.w.globals.series[opts.seriesIndex] + "%";
                }
            }
        };

        document.querySelectorAll(".nuist-attendance-chart").forEach(function (chartElement) {
            new ApexCharts(chartElement, options).render();
        });

        <?php if(in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']) && isset($superAdminStats)): ?>
        var attendanceKeys = {
            present: 'present_percentage',
            permission: 'permission_percentage',
            absent: 'absent_percentage'
        };

        function updateLiveAttendance(data) {
            Object.keys(attendanceKeys).forEach(function (key) {
                var item = document.querySelector('[data-attendance-key="' + key + '"]');
                if (!item) return;
                var percentage = Number(data[attendanceKeys[key]] || 0);
                item.style.setProperty('--attendance-value', percentage + '%');
                item.querySelector('.attendance-percentage').textContent = percentage.toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + '%';
                item.querySelector('.attendance-count').textContent = data[key] || 0;
            });
            document.querySelector('#attendance-live-date').textContent = data.date;
            document.querySelector('#attendance-live-updated').textContent = data.updated_at;
            updateTopSchools(data.top_schools || []);
        }

        function updateTopSchools(schools) {
            var container = document.querySelector('#attendance-top-schools');
            if (!container) return;
            container.replaceChildren();

            if (!schools.length) {
                var empty = document.createElement('div');
                empty.className = 'text-muted small text-center py-3';
                empty.textContent = 'Belum ada data kehadiran sekolah hari ini.';
                container.appendChild(empty);
                return;
            }

            schools.slice(0, 5).forEach(function (school, index) {
                var item = document.createElement('div');
                item.className = 'dashboard-top-school-item';

                var rank = document.createElement('span');
                rank.className = 'dashboard-top-school-rank';
                rank.textContent = index + 1;

                var info = document.createElement('div');
                info.className = 'dashboard-top-school-info';
                var name = document.createElement('strong');
                name.textContent = school.name;
                var progress = document.createElement('div');
                progress.className = 'progress';
                var bar = document.createElement('div');
                bar.className = 'progress-bar';
                bar.style.width = Number(school.percentage || 0) + '%';
                progress.appendChild(bar);
                info.append(name, progress);

                var value = document.createElement('span');
                value.className = 'dashboard-top-school-value';
                value.textContent = Number(school.percentage || 0).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + '%';

                item.append(rank, info, value);
                container.appendChild(item);
            });
        }

        var attendanceRefreshRunning = false;

        function refreshLiveAttendance() {
            if (attendanceRefreshRunning) return;
            attendanceRefreshRunning = true;

            fetch(<?php echo json_encode(route('dashboard.attendance-today'), 15, 512) ?>, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
                .then(function (response) { if (!response.ok) throw new Error('Gagal mengambil presensi'); return response.json(); })
                .then(updateLiveAttendance)
                .catch(function () {})
                .finally(function () { attendanceRefreshRunning = false; });
        }

        refreshLiveAttendance();
        setInterval(refreshLiveAttendance, 5000);

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) refreshLiveAttendance();
        });
        <?php endif; ?>


    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/dashboard/index.blade.php ENDPATH**/ ?>