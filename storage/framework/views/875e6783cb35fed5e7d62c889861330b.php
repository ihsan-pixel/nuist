<?php $__env->startSection('title'); ?> Dashboard - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboards <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Dashboard <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-lg-4 col-12">
        <!-- Welcome Card - Mobile Optimized -->
        <div class="card border-0 shadow-sm hover-lift overflow-hidden mb-3" style="border-radius: 15px;">
            <div style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);">
                <div class="row align-items-center">
                    <div class="col-8">
                        <div class="text-white p-3">
                            <h5 class="text-white">Selamat Datang!</h5>
                            <p class="mb-0 text-white-50">Aplikasi NUIST</p>
                        </div>
                    </div>s
                    <div class="col-4 text-end">
                        
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar-lg profile-user-wid mb-3 mb-md-0">
                            <img src="<?php echo e(isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/avatar-1.jpg')); ?>" alt="" class="img-thumbnail rounded-circle">
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

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'admin' && isset($madrasahData)): ?>
        <div class="row">
            
            <div class="col-12">
                <div class="card border-0 shadow-sm hover-lift" style="border-radius: 15px;">
                    <div class="card-body p-4">
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
            </div>

            
            <div class="col-12">
                <div class="card border-0 shadow-sm hover-lift" style="border-radius: 15px;">
                    <div class="card-body p-4">
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
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(Auth::user()->role, ['super_admin', 'pengurus']) && isset($foundationData)): ?>
        <div class="row">
            
            <div class="col-12">
                <div class="card border-0 shadow-sm hover-lift" style="border-radius: 15px;">
                    <div class="card-body p-4">
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
            </div>

            
            <div class="col-12">
                <div class="card border-0 shadow-sm hover-lift" style="border-radius: 15px;">
                    <div class="card-body p-4">
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(Auth::user()->role === 'admin' && isset($adminStats)): ?>
    <div class="col-xl-8 col-12">
        <!-- Statistics Overview Header -->
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="text-white mb-1">Dashboard Admin</h4>
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
        <div class="row g-3 mb-4">
            
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px;">
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

            
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px;">
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

            
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px;">
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
        </div>

        
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
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
                            <div class="card border-0 shadow-sm h-100 hover-lift" style="border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
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

        
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
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
                                            <div class="flex-grow-1 me-3">
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
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(in_array(Auth::user()->role, ['super_admin', 'pengurus']) && isset($superAdminStats)): ?>
    <div class="col-xl-8 col-12">
        <!-- Statistics Overview Header -->
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border-radius: 15px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <?php if(Auth::user()->role === 'super_admin'): ?>
                            <h4 class="text-white mb-1">Dashboard Super Admin</h4>
                        <?php elseif(Auth::user()->role === 'pengurus'): ?>
                            <h4 class="text-white mb-1">Dashboard Pengurus</h4>
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
        <div class="row g-3 mb-4">
            
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px;">
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

            
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px;">
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

            
            <div class="col-lg-6 col-xl-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 12px;">
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
            
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 12px;">
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

            
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); border-radius: 12px;">
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

            
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm hover-lift" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border-radius: 12px;">
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

        
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
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
                            <div class="card border-0 shadow-sm h-100 hover-lift" style="border-radius: 12px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
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

        
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
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
                                            <div class="flex-grow-1 me-3">
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
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array(Auth::user()->role, ['admin', 'super_admin', 'pengurus'])): ?>
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










<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- apexcharts -->
<script src="<?php echo e(asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>



<style>
.hover-lift {
    transition: all 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.gradient-pink {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.gradient-blue {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.gradient-orange {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.gradient-mint {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.gradient-peach {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
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

        var chartElement = document.querySelector("#donut-chart");
        if (chartElement) {
            var chart = new ApexCharts(
                chartElement,
                options
            );

            chart.render();
        }


    });
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/dashboard/index.blade.php ENDPATH**/ ?>