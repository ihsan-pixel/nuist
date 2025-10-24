<?php $__env->startSection('title'); ?> Dashboard - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboards <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <!-- Welcome Card - Mobile Optimized -->
        <div class="card overflow-hidden mb-3">
            <div class="bg-success-subtle">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="text-success p-3">
                            <h5 class="text-success">Selamat Datang!</h5>
                            <p class="mb-0">Aplikasi NUIST</p>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg profile-user-wid mb-3 mb-md-0">
                            <img src="<?php echo e(isset(Auth::user()->avatar) ? asset('storage/app/public/' . Auth::user()->avatar) : asset('build/images/users/avatar-11.jpg')); ?>" alt="" class="img-thumbnail rounded-circle">
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="font-size-16 mb-1"><?php echo e(Str::ucfirst(Auth::user()->name)); ?></h5>
                        <p class="text-muted mb-2">Nuist ID : <?php echo e(Auth::user()->nuist_id ?? '-'); ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <small class="badge bg-primary-subtle text-primary"><?php echo e(Auth::user()->statusKepegawaian ? Auth::user()->statusKepegawaian->name : '-'); ?></small>
                            <small class="badge bg-info-subtle text-info"><?php echo e(Auth::user()->ketugasan ?? '-'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if(Auth::user()->role === 'admin' && isset($madrasahData)): ?>
        <div class="row">
            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map-marker text-primary me-2"></i>
                            Alamat Madrasah
                        </h5>
                        <div class="mb-3">
                            <h6 class="mb-2"><?php echo e($madrasahData->name); ?></h6>
                            <p class="text-muted mb-2">
                                <i class="mdi mdi-map-marker-outline me-1"></i>
                                <?php echo e($madrasahData->alamat ?? 'Alamat belum diisi'); ?>

                            </p>
                            <?php if($madrasahData->map_link): ?>
                            <a href="<?php echo e($madrasahData->map_link); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map text-success me-2"></i>
                            Lokasi Madrasah
                        </h5>
                        <div id="map-container" style="height: 300px; border-radius: 8px; overflow: hidden;">
                            <?php if($madrasahData->latitude && $madrasahData->longitude): ?>
                                <div id="map" style="height: 100%; width: 100%;"></div>
                            <?php else: ?>
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light">
                                    <i class="mdi mdi-map-marker-off text-muted fs-1 mb-3"></i>
                                    <h6 class="text-muted">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small">
                                        Koordinat latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if(in_array(Auth::user()->role, ['super_admin', 'pengurus']) && isset($foundationData)): ?>
        <div class="row">
            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map-marker text-primary me-2"></i>
                            Alamat Yayasan
                        </h5>
                        <div class="mb-3">
                            <h6 class="mb-2"><?php echo e($foundationData->name); ?></h6>
                            <p class="text-muted mb-2">
                                <i class="mdi mdi-map-marker-outline me-1"></i>
                                <?php echo e($foundationData->alamat ?? 'Alamat belum diisi'); ?>

                            </p>
                            <?php if($foundationData->map_link): ?>
                            <a href="<?php echo e($foundationData->map_link); ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="mdi mdi-google-maps me-1"></i>
                                Lihat di Google Maps
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="mdi mdi-map text-success me-2"></i>
                            Lokasi Yayasan
                        </h5>
                        <div id="foundation-map-container" style="height: 300px; border-radius: 8px; overflow: hidden;">
                            <?php if($foundationData->latitude && $foundationData->longitude): ?>
                                <div id="foundation-map" style="height: 100%; width: 100%;"></div>
                            <?php else: ?>
                                <div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light">
                                    <i class="mdi mdi-map-marker-off text-muted fs-1 mb-3"></i>
                                    <h6 class="text-muted">Koordinat belum tersedia</h6>
                                    <p class="text-muted text-center small">
                                        Koordinat latitude dan longitude belum diisi untuk menampilkan peta
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

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
                    <div id="donut-chart" data-colors='["--bs-success", "--bs-warning", "--bs-danger"]' class="apex-charts" style="height: 200px;"></div>
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
        <?php endif; ?>
    </div>

    
    <?php if(Auth::user()->role === 'admin' && isset($adminStats)): ?>
    <div class="col-xl-8">
        <div class="row">
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo e($adminStats['total_teachers']); ?></h5>
                                <p class="text-muted mb-0">Total Tenaga Pendidik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <?php if($schoolPrincipal): ?>
                                    <h6 class="mb-1"><?php echo e($schoolPrincipal->name); ?></h6>
                                    <p class="text-muted mb-0">Kepala Sekolah</p>
                                <?php else: ?>
                                    <h6 class="mb-1 text-muted">-</h6>
                                    <p class="text-muted mb-0">Kepala Sekolah</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                    <i class="mdi mdi-school fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo e(Auth::user()->madrasah ? Auth::user()->madrasah->name : 'N/A'); ?></h6>
                                <p class="text-muted mb-0">Madrasah Saat Ini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Ringkasan Berdasarkan Status Kepegawaian</h5>
                <div class="row">
                    <?php if($adminStats['total_by_status']->count() > 0): ?>
                        <?php $__currentLoopData = $adminStats['total_by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="mdi mdi-account-tie fs-5"></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-2"><?php echo e($status['count']); ?></h6>
                                    <p class="text-muted mb-0"><?php echo e($status['status_name']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="mdi mdi-information-outline text-muted fs-1"></i>
                                <p class="text-muted mt-2">Belum ada data status kepegawaian</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Detail Statistik Tenaga Pendidik</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Status Kepegawaian</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($adminStats['total_by_status']->count() > 0): ?>
                                <?php $__currentLoopData = $adminStats['total_by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($status['status_name']); ?></td>
                                    <td><?php echo e($status['count']); ?></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: <?php echo e($adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100) : 0); ?>%"
                                                 aria-valuenow="<?php echo e($status['count']); ?>"
                                                 aria-valuemin="0"
                                                 aria-valuemax="<?php echo e($adminStats['total_teachers']); ?>">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo e($adminStats['total_teachers'] > 0 ? round(($status['count'] / $adminStats['total_teachers']) * 100, 1) : 0); ?>%
                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-info">
                                    <td><strong>Total</strong></td>
                                    <td><strong><?php echo e($adminStats['total_teachers']); ?></strong></td>
                                    <td><strong>100%</strong></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="mdi mdi-information-outline text-muted fs-4"></i>
                                        <p class="text-muted mt-2">Belum ada data untuk ditampilkan</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if(in_array(Auth::user()->role, ['super_admin', 'pengurus']) && isset($superAdminStats)): ?>
    <div class="col-xl-8">
        <div class="row">
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="mdi mdi-school fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo e($superAdminStats['total_madrasah']); ?></h5>
                                <p class="text-muted mb-0">Total Madrasah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo e($superAdminStats['total_teachers']); ?></h5>
                                <p class="text-muted mb-0">Total Tenaga Pendidik</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                    <i class="mdi mdi-account-cog fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo e($superAdminStats['total_admin']); ?></h5>
                                <p class="text-muted mb-0">Total Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                    <i class="mdi mdi-shield-account fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo e($superAdminStats['total_super_admin']); ?></h5>
                                <p class="text-muted mb-0">Total Super Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="mdi mdi-account-group fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo e($superAdminStats['total_pengurus']); ?></h5>
                                <p class="text-muted mb-0">Total Pengurus</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0 me-3">
                                <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle">
                                    <i class="mdi mdi-account-tie fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo e($superAdminStats['total_school_principals']); ?></h5>
                                <p class="text-muted mb-0">Total Kepala Sekolah</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Ringkasan Berdasarkan Status Kepegawaian</h5>
                <div class="row">
                    <?php if($superAdminStats['total_by_status']->count() > 0): ?>
                        <?php $__currentLoopData = $superAdminStats['total_by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <div class="avatar-sm mx-auto mb-3">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="mdi mdi-account-tie fs-5"></i>
                                        </div>
                                    </div>
                                    <h6 class="mb-2"><?php echo e($status['count']); ?></h6>
                                    <p class="text-muted mb-0"><?php echo e($status['status_name']); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="mdi mdi-information-outline text-muted fs-1"></i>
                                <p class="text-muted mt-2">Belum ada data status kepegawaian</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Detail Statistik Tenaga Pendidik</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Status Kepegawaian</th>
                                <th>Jumlah</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($superAdminStats['total_by_status']->count() > 0): ?>
                                <?php $__currentLoopData = $superAdminStats['total_by_status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($status['status_name']); ?></td>
                                    <td><?php echo e($status['count']); ?></td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: <?php echo e($superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100) : 0); ?>%"
                                                 aria-valuenow="<?php echo e($status['count']); ?>"
                                                 aria-valuemin="0"
                                                 aria-valuemax="<?php echo e($superAdminStats['total_teachers']); ?>">
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo e($superAdminStats['total_teachers'] > 0 ? round(($status['count'] / $superAdminStats['total_teachers']) * 100, 1) : 0); ?>%
                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr class="table-info">
                                    <td><strong>Total</strong></td>
                                    <td><strong><?php echo e($superAdminStats['total_teachers']); ?></strong></td>
                                    <td><strong>100%</strong></td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="mdi mdi-information-outline text-muted fs-4"></i>
                                        <p class="text-muted mt-2">Belum ada data untuk ditampilkan</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(!in_array(Auth::user()->role, ['admin', 'super_admin', 'pengurus'])): ?>
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

        
        <?php if($showUsers): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="mdi mdi-account-group text-info me-2"></i>
                    Rekan Guru/Pegawai Se-Madrasah/Sekolah
                </h5>

                <!-- Mobile-friendly list view -->
                <div class="list-group list-group-flush">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="list-group-item px-0 py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <img src="<?php echo e(isset($user->avatar) ? asset('storage/app/public/' . $user->avatar) : asset('build/images/users/avatar-11.jpg')); ?>"
                                     alt="Foto <?php echo e($user->name); ?>"
                                     class="rounded-circle"
                                     width="50"
                                     height="50">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?php echo e($user->name); ?></h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <small class="badge bg-primary-subtle text-primary"><?php echo e($user->ketugasan ?? '-'); ?></small>
                                    <small class="badge bg-info-subtle text-info"><?php echo e($user->statusKepegawaian ? $user->statusKepegawaian->name : '-'); ?></small>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <small class="text-muted"><?php echo e($users->firstItem() + $index); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <?php if($users->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($users->links()); ?>

                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>










<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- apexcharts -->
<script src="<?php echo e(asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>

<!-- Leaflet CSS and JS for map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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

        var chartElement = document.querySelector("#donut-chart");
        if (chartElement) {
            var chart = new ApexCharts(
                chartElement,
                options
            );

            chart.render();
        }

        // Initialize map if coordinates are available
        <?php if(isset($madrasahData) && $madrasahData->latitude && $madrasahData->longitude): ?>
            var map = L.map('map').setView([<?php echo e($madrasahData->latitude); ?>, <?php echo e($madrasahData->longitude); ?>], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            var marker = L.marker([<?php echo e($madrasahData->latitude); ?>, <?php echo e($madrasahData->longitude); ?>])
                .addTo(map)
                .bindPopup('<b><?php echo e($madrasahData->name); ?></b><br><?php echo e($madrasahData->alamat ?? "Alamat tidak tersedia"); ?>')
                .openPopup();
        <?php endif; ?>

        // Initialize foundation map if coordinates are available
        <?php if(isset($foundationData) && $foundationData->latitude && $foundationData->longitude): ?>
            var foundationMap = L.map('foundation-map').setView([<?php echo e($foundationData->latitude); ?>, <?php echo e($foundationData->longitude); ?>], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(foundationMap);

            var foundationMarker = L.marker([<?php echo e($foundationData->latitude); ?>, <?php echo e($foundationData->longitude); ?>])
                .addTo(foundationMap)
                .bindPopup('<b><?php echo e($foundationData->name); ?></b><br><?php echo e($foundationData->alamat ?? "Alamat tidak tersedia"); ?>')
                .openPopup();
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/dashboard/index.blade.php ENDPATH**/ ?>