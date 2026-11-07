<?php $__env->startSection('title'); ?>
    Jumlah Siswa per Kelas
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" />
    <style>
        .student-count-page{--sc-green:#08745a;--sc-ink:#192d37;--sc-muted:#6c7c86;position:relative;isolation:isolate;padding:8px 0 24px}.student-count-page:before,.student-count-page:after{content:"";position:absolute;z-index:-1;border-radius:50%;filter:blur(2px);pointer-events:none;animation:sc-float 10s ease-in-out infinite}.student-count-page:before{width:280px;height:280px;right:-100px;top:-90px;background:rgba(42,190,153,.15)}.student-count-page:after{width:230px;height:230px;left:-100px;bottom:-100px;background:rgba(98,158,255,.11);animation-direction:reverse;animation-duration:12s}body:has(.student-count-page){background:linear-gradient(135deg,rgba(222,227,230,.92),rgba(238,241,243,.88) 52%,rgba(215,222,225,.92))}body:has(.student-count-page) .main-content{background:transparent!important}.student-count-page .sc-shell{border:1px solid rgba(255,255,255,.86);border-radius:22px;background:rgba(255,255,255,.68);backdrop-filter:blur(18px);box-shadow:0 8px 26px rgba(32,70,78,.08);overflow:hidden;animation:sc-in .72s both}.student-count-page .sc-toolbar{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:22px 24px;border-bottom:1px solid #e5edef}.student-count-page .sc-eyebrow{margin:0 0 4px;color:var(--sc-green);font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}.student-count-page .sc-title{margin:0;color:var(--sc-ink);font-size:22px;font-weight:700}.student-count-page .sc-subtitle{margin:5px 0 0;color:var(--sc-muted);font-size:12px}.student-count-page .sc-content{padding:20px 24px 24px}.student-count-page .sc-stat{border:1px solid rgba(255,255,255,.86);border-radius:16px;background:rgba(255,255,255,.68);box-shadow:0 8px 22px rgba(32,70,78,.07)}.student-count-page .sc-stat .card-body{padding:16px 18px}.student-count-page .table-panel{border:1px solid #e5edef;border-radius:16px;background:rgba(255,255,255,.58);overflow-x:auto}.student-count-page table.dataTable{width:100%!important;table-layout:auto;min-width:1000px}.student-count-page table.dataTable thead th{border:0;border-bottom:1px solid #e5edef;background:#f1f6f6;color:var(--sc-ink);font-size:11px;padding:13px 14px;white-space:nowrap}.student-count-page table.dataTable tbody td{border:0;border-bottom:1px solid #edf2f3;color:#465a64;font-size:12px;padding:14px;vertical-align:middle;white-space:normal!important;overflow-wrap:anywhere;word-break:break-word}.student-count-page table.dataTable th:nth-child(1),.student-count-page table.dataTable td:nth-child(1){width:54px}.student-count-page table.dataTable th:nth-child(2),.student-count-page table.dataTable td:nth-child(2){min-width:220px}.student-count-page table.dataTable th:nth-child(3),.student-count-page table.dataTable td:nth-child(3){min-width:110px}.student-count-page table.dataTable th:nth-child(4),.student-count-page table.dataTable td:nth-child(4),.student-count-page table.dataTable th:nth-child(5),.student-count-page table.dataTable td:nth-child(5){min-width:145px}.student-count-page table.dataTable th:nth-child(6),.student-count-page table.dataTable td:nth-child(6){width:100px}.student-count-page table.dataTable th:nth-child(7),.student-count-page table.dataTable td:nth-child(7){min-width:145px}.student-count-page table.dataTable th:last-child,.student-count-page table.dataTable td:last-child{width:135px}.student-count-page .dataTables_wrapper{padding:16px}.student-count-page .dataTables_wrapper .dt-buttons{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:14px}.student-count-page .dataTables_wrapper .dt-button{border:0!important;border-radius:8px!important;color:#fff!important;font-size:11px;font-weight:600;padding:.46rem .8rem}.student-count-page .buttons-copy{background:linear-gradient(90deg,#0f766e,#14b8a6)!important}.student-count-page .buttons-excel{background:linear-gradient(90deg,#2563eb,#60a5fa)!important}.student-count-page .buttons-pdf{background:linear-gradient(90deg,#7c3aed,#a78bfa)!important}.student-count-page .buttons-print{background:linear-gradient(90deg,#d97706,#fbbf24)!important}.student-count-page .buttons-colvis,.student-count-page .buttons-columnVisibility{background:linear-gradient(90deg,#db2777,#f472b6)!important}.student-count-page div.dt-button-collection{width:245px!important;padding:10px!important;border:1px solid #e3eaec!important;border-radius:16px!important;background:#f8fafb!important;box-shadow:0 12px 28px rgba(32,70,78,.14)!important}.student-count-page div.dt-button-collection button.dt-button{display:block!important;width:100%!important;margin:0 0 6px!important;padding:13px 18px!important;border:0!important;border-radius:14px!important;background:#fff!important;background-image:none!important;color:#192d37!important;box-shadow:0 4px 10px rgba(32,70,78,.07)!important;text-align:left!important;font-size:15px!important}.student-count-page .modal{z-index:2000!important;pointer-events:none}.student-count-page .modal.show{pointer-events:auto}.student-count-page .modal.show .modal-dialog{position:relative;z-index:2001;pointer-events:auto}.student-count-page .modal-content{border:1px solid rgba(255,255,255,.9);border-radius:18px;box-shadow:0 18px 50px rgba(22,53,61,.18);overflow:hidden}.student-count-page .modal-header{border-bottom:1px solid #e5edef;background:#f6faf9}.student-count-page .modal-footer{border-top:1px solid #e5edef}.student-count-page .form-control,.student-count-page .form-select{border:1px solid #dce7e9;border-radius:9px}.sc-swal{width:min(420px,calc(100% - 32px))!important;border-radius:22px!important;background:rgba(255,255,255,.92)!important;backdrop-filter:blur(18px);box-shadow:0 18px 50px rgba(32,70,78,.2)!important}.sc-swal-confirm{border:0!important;border-radius:10px!important;background:#08745a!important;color:#fff!important;padding:10px 16px!important}.sc-swal-cancel{border:1px solid #dce7e9!important;border-radius:10px!important;background:#f5f8f9!important;color:#52656d!important;padding:10px 16px!important}@keyframes sc-in{from{opacity:0;transform:translateY(18px) scale(.99);filter:blur(3px)}to{opacity:1;transform:none;filter:none}}@keyframes sc-float{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(0,14px,0)}}@media(max-width:767.98px){.student-count-page .sc-toolbar{align-items:flex-start;flex-direction:column;padding:18px}.student-count-page .sc-content{padding:14px}.student-count-page .dataTables_wrapper{padding:10px}.student-count-page .modal-dialog{margin:.75rem}}@media(max-width:390px){.student-count-page .dataTables_wrapper .dt-buttons{display:grid;grid-template-columns:repeat(2,minmax(0,1fr))}.student-count-page .dataTables_wrapper .dt-button{width:100%;text-align:center}}
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> Jumlah Siswa per Kelas <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php
        $user = auth()->user();
        $isAdmin = $user && $user->role === 'admin';
        $selectedSchool = $selectedSchoolId ? $schools->firstWhere('id', $selectedSchoolId) : null;
        $defaultSchool = $isAdmin ? $schools->first() : $selectedSchool;
    ?>

    <div class="student-count-page">
    <div class="row">
        <div class="col-md-3">
            <div class="card mini-stats-wid sc-stat">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Total Kelas</p>
                    <h4 class="mb-0"><?php echo e(number_format($stats['total_classes'])); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid sc-stat">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Jumlah Siswa Total</p>
                    <h4 class="mb-0 text-info"><?php echo e(number_format($stats['total_students'])); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid sc-stat">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Sudah Tersimpan</p>
                    <h4 class="mb-0 text-success"><?php echo e(number_format($stats['saved_counts'])); ?></h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mini-stats-wid sc-stat">
                <div class="card-body">
                    <p class="text-muted fw-medium mb-1">Belum Diinput</p>
                    <h4 class="mb-0 text-warning"><?php echo e(number_format($stats['missing_counts'])); ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card sc-shell">
        <div class="card-header sc-toolbar">
            <div><p class="sc-eyebrow">MASTER DATA</p><h4 class="sc-title">Jumlah Siswa per Kelas</h4><p class="sc-subtitle">Kelola jumlah siswa berdasarkan kelas dan periode pembelajaran.</p></div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahJumlahSiswa">
                <i class="bx bx-plus"></i> Tambah Data
            </button>
        </div>
        <div class="card-body sc-content">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i><?php echo e($errors->first()); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="GET" action="<?php echo e(route('class-student-counts.index')); ?>" class="row g-2 align-items-end mb-3">
                <div class="col-md-5">
                    <label class="form-label">Filter Madrasah/Sekolah</label>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAdmin && $defaultSchool): ?>
                        <input type="hidden" name="school_id" value="<?php echo e($defaultSchool->id); ?>">
                        <input type="text" class="form-control" value="<?php echo e($defaultSchool->name); ?>" disabled>
                    <?php else: ?>
                        <select name="school_id" class="form-select">
                            <option value="">Semua Madrasah/Sekolah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($school->id); ?>" <?php if((int) $selectedSchoolId === (int) $school->id): echo 'selected'; endif; ?>>
                                    <?php echo e($school->name); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Periode</label>
                    <select name="period_id" class="form-select">
                        <option value="">Pilih periode</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($period->id); ?>" <?php if(optional($selectedPeriod)->id === $period->id): echo 'selected'; endif; ?>>
                                <?php echo e($period->semester_label); ?> <?php echo e($period->school_year); ?> | <?php echo e($period->date_range_label); ?>

                            </option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter-alt"></i> Filter
                    </button>
                </div>
                <div class="col-md-auto">
                    <a href="<?php echo e(route('class-student-counts.index')); ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedPeriod): ?>
                <div class="alert alert-info">
                    <strong><?php echo e($selectedPeriod->title); ?></strong><br>
                    <small><?php echo e($selectedPeriod->semester_label); ?> | <?php echo e($selectedPeriod->school_year); ?> | Berlaku <?php echo e($selectedPeriod->date_range_label); ?></small>
                </div>
            <?php elseif($selectedSchoolId): ?>
                <div class="alert alert-warning">
                    Pilih atau buat periode jadwal mengajar terlebih dahulu agar data jumlah siswa per kelas dapat dikelola per tahun ajaran.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="table-panel"><div class="table-responsive">
                <table id="datatable-buttons" class="table table-bordered dt-responsive w-100 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Madrasah/Sekolah</th>
                            <th>Kelas</th>
                            <th>Jumlah Siswa</th>
                            <th>Referensi Presensi</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <?php
                                $modalId = 'modalJumlahSiswa' . $loop->iteration;
                                $deleteFormId = 'deleteJumlahSiswa' . $loop->iteration;
                                $hasSavedCount = !is_null($row['count_id']);
                                $inputValue = old('total_students', $row['total_students'] ?? $row['latest_attendance_total']);
                            ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td>
                                    <div class="fw-semibold"><?php echo e($row['school_name']); ?></div>
                                    <small class="text-muted"><?php echo e($row['school_kabupaten']); ?></small>
                                </td>
                                <td><?php echo e($row['class_name']); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSavedCount): ?>
                                        <span class="fw-semibold"><?php echo e(number_format($row['total_students'])); ?></span>
                                        <small class="text-muted d-block">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row['updated_at']): ?>
                                                Update: <?php echo e($row['updated_at']->format('d/m/Y H:i')); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row['updated_by']): ?>
                                                oleh <?php echo e($row['updated_by']); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted">Belum diinput</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!is_null($row['latest_attendance_total'])): ?>
                                        <span class="fw-semibold"><?php echo e(number_format($row['latest_attendance_total'])); ?></span>
                                        <small class="text-muted d-block">
                                            <?php echo e(\Carbon\Carbon::parse($row['latest_attendance_date'])->format('d/m/Y')); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row['latest_attendance_time']): ?>
                                                <?php echo e(substr($row['latest_attendance_time'], 0, 5)); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td><?php echo e(number_format($row['schedule_count'])); ?> jadwal</td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSavedCount): ?>
                                        <?php if(!is_null($row['latest_attendance_total']) && (int) $row['total_students'] !== (int) $row['latest_attendance_total']): ?>
                                            <span class="badge bg-warning text-dark">Berbeda dari presensi</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Tersimpan</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Perlu input</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm <?php echo e($hasSavedCount ? 'btn-warning' : 'btn-primary'); ?>" data-bs-toggle="modal" data-bs-target="#<?php echo e($modalId); ?>">
                                            <?php echo e($hasSavedCount ? 'Edit' : 'Input'); ?>

                                        </button>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSavedCount): ?>
                                            <form action="<?php echo e(route('class-student-counts.destroy', $row['count_id'])); ?>" method="POST" onsubmit="return confirm('Hapus data jumlah siswa untuk kelas <?php echo e(addslashes($row['class_name'])); ?>?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="<?php echo e($modalId); ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form
                                        action="<?php echo e($hasSavedCount ? route('class-student-counts.update', $row['count_id']) : route('class-student-counts.store')); ?>"
                                        method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSavedCount): ?>
                                            <?php echo method_field('PUT'); ?>
                                        <?php else: ?>
                                            <input type="hidden" name="school_id" value="<?php echo e($row['school_id']); ?>">
                                            <input type="hidden" name="teaching_schedule_period_id" value="<?php echo e(optional($selectedPeriod)->id); ?>">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><?php echo e($hasSavedCount ? 'Edit' : 'Input'); ?> Jumlah Siswa</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Madrasah/Sekolah</label>
                                                    <input type="text" class="form-control" value="<?php echo e($row['school_name']); ?>" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kelas</label>
                                                    <input type="text" name="class_name" class="form-control" value="<?php echo e(old('class_name', $row['class_name'])); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jumlah Siswa</label>
                                                    <input type="number" name="total_students" class="form-control" min="1" max="10000" value="<?php echo e($inputValue); ?>" required>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!is_null($row['latest_attendance_total'])): ?>
                                                        <div class="form-text">
                                                            Referensi presensi terakhir: <?php echo e(number_format($row['latest_attendance_total'])); ?> siswa.
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSavedCount): ?>
                                                    <button type="submit"
                                                            form="<?php echo e($deleteFormId); ?>"
                                                            class="btn btn-outline-danger me-auto"
                                                            onclick="return confirm('Hapus data jumlah siswa untuk kelas <?php echo e(addslashes($row['class_name'])); ?>?');">
                                                        Hapus
                                                    </button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasSavedCount): ?>
                                        <form id="<?php echo e($deleteFormId); ?>" action="<?php echo e(route('class-student-counts.destroy', $row['count_id'])); ?>" method="POST" class="d-none">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <tr>
                                <td colspan="8" class="text-center p-4">
                                    <div class="alert alert-info d-inline-block text-center mb-0" role="alert">
                                        <i class="bx bx-info-circle bx-lg me-2"></i>
                                        <strong>Belum ada data kelas</strong><br>
                                        <small>Tambahkan data jumlah siswa atau lengkapi jadwal mengajar terlebih dahulu.</small>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div></div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="modalTambahJumlahSiswa" tabindex="-1" aria-labelledby="modalTambahJumlahSiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?php echo e(route('class-student-counts.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTambahJumlahSiswaLabel">Tambah Jumlah Siswa per Kelas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAdmin && $defaultSchool): ?>
                            <input type="hidden" name="school_id" value="<?php echo e($defaultSchool->id); ?>">
                            <input type="hidden" name="teaching_schedule_period_id" value="<?php echo e(optional($selectedPeriod)->id); ?>">
                            <div class="mb-3">
                                <label class="form-label">Madrasah/Sekolah</label>
                                <input type="text" class="form-control" value="<?php echo e($defaultSchool->name); ?>" disabled>
                            </div>
                        <?php else: ?>
                            <div class="mb-3">
                                <label class="form-label">Madrasah/Sekolah</label>
                                <select name="school_id" class="form-select" required>
                                    <option value="">Pilih Madrasah/Sekolah</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($school->id); ?>" <?php if((int) old('school_id', $defaultSchool->id ?? null) === (int) $school->id): echo 'selected'; endif; ?>>
                                            <?php echo e($school->name); ?>

                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Periode Jadwal</label>
                                <select name="teaching_schedule_period_id" class="form-select" required>
                                    <option value="">Pilih periode</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($period->id); ?>" <?php if((int) old('teaching_schedule_period_id', optional($selectedPeriod)->id) === (int) $period->id): echo 'selected'; endif; ?>>
                                            <?php echo e($period->semester_label); ?> <?php echo e($period->school_year); ?> | <?php echo e($period->date_range_label); ?>

                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="class_name" class="form-control" value="<?php echo e(old('class_name')); ?>" placeholder="Contoh: VII A" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah Siswa</label>
                            <input type="number" name="total_students" class="form-control" min="1" max="10000" value="<?php echo e(old('total_students')); ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/jszip/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/pdfmake/build/pdfmake.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/pdfmake/build/vfs_fonts.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>

    <script>
        $(document).ready(function () {
            $('.modal').appendTo(document.body);
            let table = $("#datatable-buttons").DataTable({
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print", "colvis"],
                order: [[1, "asc"], [2, "asc"]]
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

            const normalizeColumnMenu = () => document.querySelectorAll('div.dt-button-collection, div.dt-button-collection *').forEach((item) => {
                item.style.setProperty('background', '#fff', 'important');
                item.style.setProperty('background-image', 'none', 'important');
                item.style.setProperty('color', '#192d37', 'important');
            });
            new MutationObserver(normalizeColumnMenu).observe(document.body, {childList:true, subtree:true});
            $(document).on('click', '.buttons-colvis,.buttons-columnVisibility', () => setTimeout(normalizeColumnMenu, 20));

            <?php if(session('success')): ?>
                Swal.fire({customClass:{popup:'sc-swal'},buttonsStyling:false,icon:'success',title:'Berhasil',text:<?php echo json_encode(session('success'), 15, 512) ?>,timer:2200,showConfirmButton:false});
            <?php endif; ?>
            <?php if($errors->any()): ?>
                Swal.fire({customClass:{popup:'sc-swal',confirmButton:'sc-swal-confirm'},buttonsStyling:false,icon:'error',title:'Periksa data',text:<?php echo json_encode($errors->first(), 15, 512) ?>,confirmButtonText:'Tutup'});
            <?php endif; ?>
            $(document).on('submit', 'form[action*="/jumlah-siswa-kelas/"]', function(event){
                if (!this.querySelector('input[name="_method"][value="DELETE"]')) return;
                event.preventDefault(); const form=this;
                Swal.fire({customClass:{popup:'sc-swal',confirmButton:'sc-swal-confirm',cancelButton:'sc-swal-cancel'},buttonsStyling:false,icon:'warning',title:'Hapus data?',text:'Data jumlah siswa tidak dapat dikembalikan.',showCancelButton:true,confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'}).then(result=>{if(result.isConfirmed) form.submit()});
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/class-student-counts/index.blade.php ENDPATH**/ ?>