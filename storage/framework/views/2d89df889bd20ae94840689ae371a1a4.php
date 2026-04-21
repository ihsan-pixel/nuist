<?php $__env->startSection('title', 'Presensi Mengajar'); ?>
<?php $__env->startSection('subtitle', 'Presensi Mengajar Saya'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-3" style="max-width: 600px; margin: auto;">
    <div class="sticky-header">
        <div class="text-center mb-4">
            <h5 class="fw-bold text-dark mb-1" style="font-size: 18px;">Presensi Mengajar</h5>
            <small class="text-muted" style="font-size: 12px;"><?php echo e(\Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY')); ?></small>
        </div>
        <div class="realtime-clock-card mb-3">
            
            <div class="clock-time" id="realtimeClock">--:--:--</div>
            
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div class="alert alert-success border-0 rounded-3 mb-3" style="background: rgba(25, 135, 84, 0.1); color: #198754; border-radius: 12px; padding: 10px;">
            <i class="bx bx-check-circle me-1"></i><?php echo e(session('success')); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($approvedIzinPresensi)): ?>
            <div class="alert alert-info border-0 rounded-3 mb-3" style="background: rgba(13, 202, 240, 0.12); color: #055160; border-radius: 12px; padding: 10px;">
                <i class="bx bx-info-circle me-1"></i>
                Anda tercatat <strong>izin (disetujui)</strong> hari ini, sehingga presensi mengajar ditandai sebagai izin.
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($approvedIzinPresensi->keterangan)): ?>
                    <div class="small mt-1"><?php echo e(Str::limit((string) $approvedIzinPresensi->keterangan, 140)); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            background-color: #f8f9fb;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 200px;
            background: linear-gradient(to bottom, rgba(248,249,251,0), #f8f9fb);
            z-index: -1;
        }

        .mobile-header,
        .mobile-header .container-fluid {
            background: transparent !important;
        }

        .mobile-header {
            box-shadow: none !important;
            border: none !important;
        }

        body {
            background-color: transparent !important;
        }



        .schedule-item {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s ease;
            width: 100%;
            margin-bottom: 8px;
        }

        .schedule-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .schedule-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #0e8549, #0f9d58);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(14, 133, 73, 0.3);
        }

        .schedule-icon i {
            color: #fff;
            font-size: 14px;
        }

        .schedule-info {
            flex: 1;
            min-width: 0;
        }

        .schedule-info strong {
            font-size: 14px;
            color: #2d3748;
            display: block;
            margin-bottom: 3px;
            font-weight: 600;
            line-height: 1.2;
        }

        .schedule-info small {
            font-size: 12px;
            color: #718096;
            display: block;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .schedule-time {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 0;
            font-weight: 500;
        }

        .school-badge {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .presensi-btn { background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%); border: none; border-radius: 8px; padding: 10px; color: #fff; font-weight: 600; font-size: 14px; width: 100%; }
        .presensi-btn.outline { background: transparent; border:1px solid #e9ecef; color:#333; }
        .small-muted { font-size: 12px; color: #6c757d; }

        .day-card {
            max-width: 520px;
            margin: 0 auto;
            background: linear-gradient(135deg, #fdbd57 0%, #f89a3c 50%, #e67e22 100%);
            border-radius: 0;
            min-height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            padding: 16px;
            box-shadow: none;
            border: none;
        }

        .schedule-list {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .no-schedule {
            text-align: center;
            padding: 20px;
            color: #999;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .no-schedule i {
            font-size: 32px;
            margin-bottom: 8px;
            opacity: 0.7;
        }

        .no-schedule p {
            font-size: 12px;
            margin: 0;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #ffffff;
            padding-bottom: 16px;
        }

        .realtime-clock-card {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border-radius: 14px;
            padding: 12px 14px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(14, 133, 73, 0.18);
        }

        .clock-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            opacity: 0.85;
            margin-bottom: 4px;
        }

        .clock-time {
            font-size: 28px;
            line-height: 1;
            font-weight: 700;
            letter-spacing: 0.06em;
            font-variant-numeric: tabular-nums;
            margin-bottom: 4px;
        }

        .clock-caption {
            font-size: 11px;
            opacity: 0.8;
        }

        /* Modal font sizes */
        .modal-body .fw-bold {
            font-size: 14px !important;
        }

        .modal-body .text-muted {
            font-size: 11px !important;
        }

        .modal-body .fw-medium {
            font-size: 11px !important;
        }

        .modal-body .alert {
            font-size: 11px !important;
        }

        .modal-body .alert ul li {
            font-size: 10px !important;
        }

        .user-location-map-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            height: 220px;
        }

        .map-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 1;
        }

        .map-placeholder i {
            font-size: 32px;
            color: #adb5bd;
            margin-bottom: 8px;
        }

        .map-placeholder span {
            font-size: 11px;
            color: #6c757d;
            text-align: center;
        }

        .map-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 2;
        }

        .map-loading i {
            font-size: 32px;
            color: #adb5bd;
            margin-bottom: 8px;
            animation: spin 1s linear infinite;
        }

        .map-loading span {
            font-size: 11px;
            color: #6c757d;
            text-align: center;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #map-placeholder {
            pointer-events: none;
        }

        .modal-footer {
            display: flex !important;
            justify-content: space-between !important;
            flex-direction: row !important;
        }

        .modal-footer button {
            flex: 0 0 auto !important;
            width: auto !important;
        }


    </style>

    <!-- Header -->
    

    <!-- Date below header (smaller font) -->
    

    <div class="day-card">
        <div class="schedule-list">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedules->isEmpty()): ?>
                <div class="no-schedule">
                    <i class="bx bx-calendar-x"></i>
                    <p>Tidak ada jadwal mengajar hari ini</p>
                </div>
            <?php else: ?>
                <?php
                    $isIzinApprovedToday = !empty($approvedIzinPresensi);
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div
                        class="schedule-item"
                        data-schedule-id="<?php echo e($schedule->id); ?>"
                        data-subject="<?php echo e(e($schedule->subject)); ?>"
                        data-class-name="<?php echo e(e($schedule->class_name)); ?>"
                        data-school-name="<?php echo e(e($schedule->school->name ?? 'N/A')); ?>"
                        data-start-time="<?php echo e($schedule->start_time); ?>"
                        data-end-time="<?php echo e($schedule->end_time); ?>"
                        data-day-marker="<?php echo e($schedule->day_marker ?? 'normal'); ?>"
                    >
                        <div class="schedule-icon">
                            <i class="bx bx-book"></i>
                        </div>
                        <div class="schedule-info">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <strong><?php echo e($schedule->subject); ?></strong>
                                    <small><?php echo e($schedule->class_name); ?></small>
                                    <div class="schedule-time">
                                        <i class="bx bx-time-five"></i> <?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?>

                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($schedule->day_marker) && $schedule->day_marker !== 'normal'): ?>
                                        <div class="mt-1">
                                            <span class="badge bg-info text-dark"><?php echo e($schedule->day_marker_label ?? 'Keterangan Hari'); ?></span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="school-badge">
                                        <?php echo e(Str::limit($schedule->school->name ?? 'N/A', 100)); ?>

                                    </div>
                                </div>
                                <div class="text-end">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedule->attendance): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($schedule->attendance->status ?? 'hadir') === 'izin'): ?>
                                            <div class="badge bg-info text-dark">Izin</div>
                                        <?php else: ?>
                                            <div class="badge bg-success">Hadir</div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isIzinApprovedToday): ?>
                                            <div class="badge bg-info text-dark">Izin</div>
                                        <?php else: ?>
                                            <div class="badge bg-warning text-dark">Belum</div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <div class="mt-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedule->attendance): ?>
                                    <div class="alert <?php echo e((($schedule->attendance->status ?? 'hadir') === 'izin') ? 'alert-info' : 'alert-success'); ?> mb-0">
                                        <div class="d-flex align-items-center">
                                            <i class="bx <?php echo e((($schedule->attendance->status ?? 'hadir') === 'izin') ? 'bx-info-circle' : 'bx-check-circle'); ?> fs-4 me-2"></i>
                                            <div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($schedule->attendance->status ?? 'hadir') === 'izin'): ?>
                                                    <div class="fw-semibold">Izin</div>
                                                <?php else: ?>
                                                    <div class="fw-semibold">Presensi Berhasil</div>
                                                    <small class="small-muted">Waktu: <?php echo e($schedule->attendance->waktu); ?></small>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedule->attendance->materi): ?>
                                                    <div class="small-muted mt-1">
                                                        <i class="bx bx-note me-1"></i>Materi: <?php echo e($schedule->attendance->materi); ?>

                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!is_null($schedule->attendance->present_students) && !is_null($schedule->attendance->class_total_students)): ?>
                                                    <div class="small-muted mt-1">
                                                        <i class="bx bx-user-check me-1"></i>Siswa hadir:
                                                        <?php echo e($schedule->attendance->present_students); ?>/<?php echo e($schedule->attendance->class_total_students); ?>

                                                        (<?php echo e(number_format($schedule->attendance->student_attendance_percentage, 1)); ?>%)
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <?php if(($schedule->attendance->status ?? 'hadir') !== 'izin'): ?>
                                                <div class="ms-auto">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-outline-primary edit-attendance-btn"
                                                        data-attendance='<?php echo json_encode([
                                                            "id" => $schedule->attendance->id, "schedule_id" => $schedule->id, "subject" => $schedule->subject) ?>'
                                                        title="Edit presensi"
                                                    >
                                                        <i class="bx bx-edit-alt"></i>
                                                    </button>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isIzinApprovedToday): ?>
                                        <div class="alert alert-info mb-0">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-info-circle fs-4 me-2"></i>
                                                <div>
                                                    <div class="fw-semibold">Izin (Disetujui)</div>
                                                    <small class="small-muted">Anda tidak dapat melakukan presensi mengajar hari ini.</small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($schedule->day_marker ?? 'normal') === 'libur'): ?>
                                        <div class="alert alert-info mb-0">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-calendar-x fs-4 me-2"></i>
                                                <div>
                                                    <div class="fw-semibold"><?php echo e($schedule->day_marker_label ?? 'Hari Libur'); ?></div>
                                                    <small class="small-muted">Presensi mengajar dinonaktifkan untuk kelas ini hari ini.</small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                    <div
                                        class="time-status-container"
                                        data-schedule-id="<?php echo e($schedule->id); ?>"
                                        data-subject="<?php echo e(e($schedule->subject)); ?>"
                                        data-class-name="<?php echo e(e($schedule->class_name)); ?>"
                                        data-school-name="<?php echo e(e($schedule->school->name ?? 'N/A')); ?>"
                                        data-start-time="<?php echo e($schedule->start_time); ?>"
                                        data-end-time="<?php echo e($schedule->end_time); ?>"
                                        data-class-total-students="<?php echo e($schedule->class_student_count->total_students ?? ''); ?>"
                                        data-day-marker="<?php echo e($schedule->day_marker ?? 'normal'); ?>"
                                    >
                                        <?php
                                            $currentTime = \Carbon\Carbon::now('Asia/Jakarta');
                                            $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time, 'Asia/Jakarta');
                                            $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time, 'Asia/Jakarta');
                                            $isWithinTime = $currentTime->between($startTime, $endTime);
                                            $isBeforeStart = $currentTime->lt($startTime);
                                            $isAfterEnd = $currentTime->gt($endTime);
                                        ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isWithinTime): ?>
                                            <button
                                                class="presensi-btn attendance-btn"
                                                data-schedule-id="<?php echo e($schedule->id); ?>"
                                                data-subject="<?php echo e(e($schedule->subject)); ?>"
                                                data-class-name="<?php echo e(e($schedule->class_name)); ?>"
                                                data-school-name="<?php echo e(e($schedule->school->name ?? 'N/A')); ?>"
                                                data-start-time="<?php echo e($schedule->start_time); ?>"
                                                data-end-time="<?php echo e($schedule->end_time); ?>"
                                                data-class-total-students="<?php echo e($schedule->class_student_count->total_students ?? ''); ?>"
                                                data-day-marker="<?php echo e($schedule->day_marker ?? 'normal'); ?>"
                                            >
                                                <i class="bx bx-check-circle me-1"></i> Lakukan Presensi
                                            </button>
                                        <?php elseif($isBeforeStart): ?>
                                            <button class="presensi-btn outline countdown-btn" disabled data-schedule-id="<?php echo e($schedule->id); ?>">
                                                <i class="bx bx-time me-1"></i> <span class="countdown-text">Menunggu Waktu Mengajar</span>
                                            </button>
                                            <div class="text-center mt-2">
                                                <small class="small-muted bg-light px-2 py-1 rounded-pill countdown-info" data-schedule-id="<?php echo e($schedule->id); ?>">
                                                    <i class="bx bx-info-circle me-1"></i>Waktu mengajar: <?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?>

                                                </small>
                                            </div>
                                            <button
                                                class="presensi-btn outline manual-attendance-btn mt-2"
                                                data-force-reason="outside_time"
                                                data-schedule-id="<?php echo e($schedule->id); ?>"
                                                data-subject="<?php echo e(e($schedule->subject)); ?>"
                                                data-class-name="<?php echo e(e($schedule->class_name)); ?>"
                                                data-school-name="<?php echo e(e($schedule->school->name ?? 'N/A')); ?>"
                                                data-start-time="<?php echo e($schedule->start_time); ?>"
                                                data-end-time="<?php echo e($schedule->end_time); ?>"
                                                data-class-total-students="<?php echo e($schedule->class_student_count->total_students ?? ''); ?>"
                                                data-day-marker="<?php echo e($schedule->day_marker ?? 'normal'); ?>"
                                            >
                                                <i class="bx bx-error-circle me-1"></i> Input Manual
                                            </button>
                                        <?php else: ?>
                                            <button class="presensi-btn outline" disabled>
                                                <i class="bx bx-time me-1"></i> Waktu Mengajar Berakhir
                                            </button>
                                            <div class="text-center mt-2">
                                                <small class="small-muted bg-light px-2 py-1 rounded-pill">
                                                    <i class="bx bx-info-circle me-1"></i>Waktu mengajar: <?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?>

                                                </small>
                                            </div>
                                            <button
                                                class="presensi-btn outline manual-attendance-btn mt-2"
                                                data-force-reason="outside_time"
                                                data-schedule-id="<?php echo e($schedule->id); ?>"
                                                data-subject="<?php echo e(e($schedule->subject)); ?>"
                                                data-class-name="<?php echo e(e($schedule->class_name)); ?>"
                                                data-school-name="<?php echo e(e($schedule->school->name ?? 'N/A')); ?>"
                                                data-start-time="<?php echo e($schedule->start_time); ?>"
                                                data-end-time="<?php echo e($schedule->end_time); ?>"
                                                data-class-total-students="<?php echo e($schedule->class_student_count->total_students ?? ''); ?>"
                                                data-day-marker="<?php echo e($schedule->day_marker ?? 'normal'); ?>"
                                            >
                                                <i class="bx bx-error-circle me-1"></i> Input Manual
                                            </button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0 fw-bold">
                        <i class="bx bx-check-circle me-2"></i>Konfirmasi Presensi Mengajar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-0 bg-white shadow-sm mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="schedule-icon me-3">
                                    <i class="bx bx-book"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-6" id="modal-subject"></div>
                                    <div class="text-muted small" id="modal-class"></div>
                                </div>
                            </div>
                            <div class="row g-2 mt-2">
                                <div class="col-6">
                                    <div class="text-muted small mb-1">
                                        <i class="bx bx-building me-1"></i>Sekolah
                                    </div>
                                    <div class="fw-medium small" id="modal-school"></div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small mb-1">
                                        <i class="bx bx-time-five me-1"></i>Waktu
                                    </div>
                                    <div class="fw-medium small" id="modal-time"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="user-location-map-container" style="height: 220px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 2px solid rgba(14, 133, 73, 0.1);">
                        <div id="map-loading" class="map-loading">
                            <i class="bx bx-loader-alt"></i>
                            <span>Membaca lokasi Anda...<br>Mohon tunggu sebentar</span>
                        </div>
                        <div id="map-placeholder" class="map-placeholder">
                            <i class="bx bx-map"></i>
                            <span>Menunggu data lokasi...<br>Peta akan muncul setelah GPS aktif</span>
                        </div>
                        <div id="locationMap" style="height: 100%; width: 100%;"></div>
                    </div>

                    <div id="locationStatus" class="alert alert-info mb-3">
                        <i class="bx bx-loader-alt bx-spin me-2"></i> Mendapatkan lokasi Anda...
                    </div>

                    

                    

                    <div class="mb-3">
                        <label class="form-label fw-semibold mb-1">
                            <i class="bx bx-group me-1"></i>Kehadiran Siswa
                        </label>
                        <div id="classTotalInfo" class="alert alert-light border mb-2" style="font-size: 11px;"></div>
                        <div class="mb-2" id="classTotalInputGroup">
                            <label for="classTotalStudents" class="form-label mb-1" style="font-size: 11px;">Jumlah siswa di kelas</label>
                            <input
                                type="number"
                                class="form-control"
                                id="classTotalStudents"
                                min="1"
                                max="10000"
                                inputmode="numeric"
                                placeholder="Contoh: 32"
                            >
                        </div>
                        <div class="mb-2">
                            <label for="presentStudents" class="form-label mb-1" style="font-size: 11px;">Jumlah siswa hadir</label>
                            <input
                                type="number"
                                class="form-control"
                                id="presentStudents"
                                min="0"
                                max="10000"
                                inputmode="numeric"
                                placeholder="Contoh: 30"
                                required
                            >
                        </div>
                        <div id="studentAttendancePreview" class="alert alert-info mb-0" style="font-size: 11px;">
                            Isi jumlah siswa hadir untuk melihat persentase.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="attendanceMateri" class="form-label fw-semibold mb-1">
                            <i class="bx bx-note me-1"></i>Materi atau Topik yang Disampaikan
                        </label>
                        <textarea
                            class="form-control"
                            id="attendanceMateri"
                            rows="3"
                            maxlength="1000"
                            placeholder="Contoh: Persamaan linear satu variabel"
                            required
                        ></textarea>
                        <div class="form-text" style="font-size: 10px;">Wajib diisi sebelum presensi dikirim.</div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary px-4" id="confirmAttendanceBtn" disabled>
                        <i class="bx bx-check-circle me-1"></i>Presensi
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Real-time time-based attendance functionality
let timeCheckInterval;
let scheduleData = {};
let map;
let marker;
let realtimeClockInterval;
const confirmAttendanceBtnLabel = '<i class="bx bx-check-circle me-1"></i>Presensi';
const confirmAttendanceBtnLoadingLabel = '<i class="bx bx-loader-alt bx-spin me-1"></i> Memproses...';

// Initialize Leaflet Map
function initializeMap() {
    const mapElement = document.getElementById('locationMap');
    if (!mapElement) return;

    // Defensive: avoid initializing the same Leaflet container more than once.
    if (mapElement._leaflet_id) {
        // Remove existing map instance if it exists
        if (map) {
            map.remove();
            map = null;
        }
    }
    if (map) return; // Already initialized

    // Default location (Indonesia center)
    const defaultLocation = [-6.2088, 106.8456];

    map = L.map('locationMap').setView(defaultLocation, 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // No default marker - will be added when user location is obtained
}

// Update map with user location
function updateMapLocation(lat, lng) {
    if (!map) {
        initializeMap();
    }

    if (!map) return; // Still not initialized

    const location = [lat, lng];

    // Remove existing marker
    if (marker) {
        map.removeLayer(marker);
    }

    // Add new marker
    marker = L.marker(location).addTo(map)
        .bindPopup('Lokasi saat ini')
        .openPopup();

    // Hide placeholder when map is ready
    $('#map-placeholder').fadeOut(200);

    // Center map on location
    map.setView(location, 16);
}

// Initialize schedule data from DOM
function initializeScheduleData() {
    document.querySelectorAll('.time-status-container').forEach(container => {
        const scheduleId = container.dataset.scheduleId;

        scheduleData[scheduleId] = {
            scheduleId: scheduleId,
            subject: container.dataset.subject,
            className: container.dataset.className,
            schoolName: container.dataset.schoolName,
            startTime: container.dataset.startTime,
            endTime: container.dataset.endTime,
            classTotalStudents: container.dataset.classTotalStudents,
            dayMarker: container.dataset.dayMarker || 'normal',
            container: container
        };
    });
}

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function renderScheduleAction(data, state, minutesUntilStart = 0) {
    const container = data.container;
    const escapedSubject = escapeHtml(data.subject);
    const escapedClassName = escapeHtml(data.className);
    const escapedSchoolName = escapeHtml(data.schoolName);
    const escapedStartTime = escapeHtml(data.startTime);
    const escapedEndTime = escapeHtml(data.endTime);
    const escapedClassTotalStudents = escapeHtml(data.classTotalStudents);
    const escapedDayMarker = escapeHtml(data.dayMarker || 'normal');

    if (state === 'within') {
        container.innerHTML = `
            <button
                class="presensi-btn attendance-btn"
                data-schedule-id="${data.scheduleId}"
                data-subject="${escapedSubject}"
                data-class-name="${escapedClassName}"
                data-school-name="${escapedSchoolName}"
                data-start-time="${escapedStartTime}"
                data-end-time="${escapedEndTime}"
                data-class-total-students="${escapedClassTotalStudents}"
                data-day-marker="${escapedDayMarker}"
            >
                <i class="bx bx-check-circle me-1"></i> Lakukan Presensi
            </button>
        `;
        return;
    }

    if (state === 'before') {
        container.innerHTML = `
            <button class="presensi-btn outline countdown-btn" disabled data-schedule-id="${data.scheduleId}">
                <i class="bx bx-time me-1"></i> <span class="countdown-text">Mulai dalam ${escapeHtml(formatTimeDifference(minutesUntilStart))}</span>
            </button>
            <div class="text-center mt-2">
                <small class="small-muted bg-light px-2 py-1 rounded-pill countdown-info" data-schedule-id="${data.scheduleId}">
                    <i class="bx bx-info-circle me-1"></i>Waktu mengajar: ${escapedStartTime} - ${escapedEndTime}
                </small>
            </div>
            <button
                class="presensi-btn outline manual-attendance-btn mt-2"
                data-force-reason="outside_time"
                data-schedule-id="${data.scheduleId}"
                data-subject="${escapedSubject}"
                data-class-name="${escapedClassName}"
                data-school-name="${escapedSchoolName}"
                data-start-time="${escapedStartTime}"
                data-end-time="${escapedEndTime}"
                data-class-total-students="${escapedClassTotalStudents}"
                data-day-marker="${escapedDayMarker}"
            >
                <i class="bx bx-error-circle me-1"></i> Input Manual
            </button>
        `;
        return;
    }

    container.innerHTML = `
        <button class="presensi-btn outline ended-btn" disabled>
            <i class="bx bx-time me-1"></i> Waktu Mengajar Berakhir
        </button>
        <div class="text-center mt-2">
            <small class="small-muted bg-light px-2 py-1 rounded-pill">
                <i class="bx bx-info-circle me-1"></i>Waktu mengajar: ${escapedStartTime} - ${escapedEndTime}
            </small>
        </div>
        <button
            class="presensi-btn outline manual-attendance-btn mt-2"
            data-force-reason="outside_time"
            data-schedule-id="${data.scheduleId}"
            data-subject="${escapedSubject}"
            data-class-name="${escapedClassName}"
            data-school-name="${escapedSchoolName}"
            data-start-time="${escapedStartTime}"
            data-end-time="${escapedEndTime}"
            data-class-total-students="${escapedClassTotalStudents}"
            data-day-marker="${escapedDayMarker}"
        >
            <i class="bx bx-error-circle me-1"></i> Input Manual
        </button>
    `;
}

// Format time difference to readable format
function formatTimeDifference(minutes) {
    if (minutes < 1) {
        return 'Kurang dari 1 menit';
    } else if (minutes < 60) {
        return `${Math.floor(minutes)} menit lagi`;
    } else {
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = Math.floor(minutes % 60);
        if (remainingMinutes === 0) {
            return `${hours} jam lagi`;
        } else {
            return `${hours} jam ${remainingMinutes} menit lagi`;
        }
    }
}

function updateRealtimeClock() {
    const realtimeClock = document.getElementById('realtimeClock');
    if (!realtimeClock) return;

    const jakartaNow = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));
    const hours = String(jakartaNow.getHours()).padStart(2, '0');
    const minutes = String(jakartaNow.getMinutes()).padStart(2, '0');
    const seconds = String(jakartaNow.getSeconds()).padStart(2, '0');

    realtimeClock.textContent = `${hours}:${minutes}:${seconds}`;
}

// Check current time and update UI accordingly
function checkTimeAndUpdateUI() {
    const now = new Date();
    const jakartaTime = new Date(now.toLocaleString("en-US", {timeZone: "Asia/Jakarta"}));
    const currentTime = jakartaTime.getHours() * 60 + jakartaTime.getMinutes(); // minutes since midnight

    Object.keys(scheduleData).forEach(scheduleId => {
        const data = scheduleData[scheduleId];
        const [startHour, startMinute] = data.startTime.split(':').map(Number);
        const [endHour, endMinute] = data.endTime.split(':').map(Number);

        const startMinutes = startHour * 60 + startMinute;
        const endMinutes = endHour * 60 + endMinute;

        if (currentTime >= startMinutes && currentTime <= endMinutes) {
            renderScheduleAction(data, 'within');
        } else if (currentTime < startMinutes) {
            const minutesUntilStart = startMinutes - currentTime;
            renderScheduleAction(data, 'before', minutesUntilStart);
        } else {
            renderScheduleAction(data, 'after');
        }
    });
}

// Start real-time time checking
function startTimeChecking() {
    // Check immediately
    updateRealtimeClock();
    checkTimeAndUpdateUI();

    realtimeClockInterval = setInterval(updateRealtimeClock, 1000);

    // Then check every 30 seconds
    timeCheckInterval = setInterval(checkTimeAndUpdateUI, 30000);
}

// Stop time checking
function stopTimeChecking() {
    if (timeCheckInterval) {
        clearInterval(timeCheckInterval);
    }
    if (realtimeClockInterval) {
        clearInterval(realtimeClockInterval);
    }
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeScheduleData();
    buildScheduleMeta();
    startTimeChecking();
});

// Clean up when page unloads
window.addEventListener('beforeunload', function() {
    stopTimeChecking();
});

let currentScheduleId = null;
let currentAttendanceId = null;
let userLocation = null;
let isLocationValid = false;
let currentClassTotalStudents = null;
let isEditMode = false;
let requiresLocationValidation = true;
let currentForce = false;
let currentForceReason = '';
const confirmAttendanceBtn = document.getElementById('confirmAttendanceBtn');
const attendanceMateriInput = document.getElementById('attendanceMateri');
const classTotalStudentsInput = document.getElementById('classTotalStudents');
const presentStudentsInput = document.getElementById('presentStudents');
const classTotalInputGroup = document.getElementById('classTotalInputGroup');
const classTotalInfo = document.getElementById('classTotalInfo');
const studentAttendancePreview = document.getElementById('studentAttendancePreview');
const scheduleMeta = {};

function parseTimeToMinutes(value) {
    const parts = String(value ?? '').split(':').map(v => Number(v));
    const hours = Number.isFinite(parts[0]) ? parts[0] : 0;
    const minutes = Number.isFinite(parts[1]) ? parts[1] : 0;
    return (hours * 60) + minutes;
}

function buildScheduleMeta() {
    document.querySelectorAll('.schedule-item[data-schedule-id]').forEach(el => {
        const scheduleId = String(el.dataset.scheduleId);
        scheduleMeta[scheduleId] = {
            scheduleId,
            subject: el.dataset.subject || '',
            className: el.dataset.className || '',
            schoolName: el.dataset.schoolName || '',
            startTime: el.dataset.startTime || '',
            endTime: el.dataset.endTime || '',
            dayMarker: el.dataset.dayMarker || 'normal',
            startMinutes: parseTimeToMinutes(el.dataset.startTime),
            endMinutes: parseTimeToMinutes(el.dataset.endTime),
        };
    });
}

function getOverlappingSchedules(scheduleId) {
    const base = scheduleMeta[String(scheduleId)];
    if (!base) return [];
    return Object.values(scheduleMeta).filter(other => {
        if (other.scheduleId === base.scheduleId) return false;
        return base.startMinutes < other.endMinutes && base.endMinutes > other.startMinutes;
    });
}

function getStudentAttendanceNumbers() {
    const totalRaw = currentClassTotalStudents || Number(classTotalStudentsInput?.value || 0);
    const presentRaw = Number(presentStudentsInput?.value || -1);

    return {
        total: Number.isInteger(totalRaw) ? totalRaw : Math.floor(totalRaw),
        present: Number.isInteger(presentRaw) ? presentRaw : Math.floor(presentRaw),
    };
}

function updateStudentAttendancePreview() {
    if (!studentAttendancePreview) return;

    const { total, present } = getStudentAttendanceNumbers();

    if (!total || total < 1 || present < 0 || !presentStudentsInput?.value) {
        studentAttendancePreview.className = 'alert alert-info mb-0';
        studentAttendancePreview.textContent = 'Isi jumlah siswa hadir untuk melihat persentase.';
        return;
    }

    if (present > total) {
        studentAttendancePreview.className = 'alert alert-warning mb-0';
        studentAttendancePreview.textContent = 'Jumlah siswa hadir tidak boleh melebihi jumlah siswa di kelas.';
        return;
    }

    const percentage = ((present / total) * 100).toFixed(1);
    studentAttendancePreview.className = 'alert alert-success mb-0';
    studentAttendancePreview.textContent = `Kehadiran siswa: ${present}/${total} (${percentage}%)`;
}

function refreshConfirmAttendanceButton() {
    const hasMateri = attendanceMateriInput && attendanceMateriInput.value.trim().length > 0;
    const { total, present } = getStudentAttendanceNumbers();
    const hasValidStudentAttendance = total > 0 && present >= 0 && present <= total && !!presentStudentsInput?.value;
    const locationOk = requiresLocationValidation ? isLocationValid : true;
    confirmAttendanceBtn.disabled = !(locationOk && hasMateri && hasValidStudentAttendance);
    updateStudentAttendancePreview();
}

function openAttendanceModal(scheduleId, subject, className, schoolName, startTime, endTime, classTotalStudents, options = {}) {
    currentScheduleId = scheduleId;
    userLocation = null;
    isLocationValid = false;
    isEditMode = options.mode === 'edit';
    currentAttendanceId = options.attendanceId || null;
    requiresLocationValidation = !isEditMode;
    currentForce = !!options.force;
    currentForceReason = String(options.forceReason || '');

    currentClassTotalStudents = classTotalStudents ? Number(classTotalStudents) : null;
    confirmAttendanceBtn.disabled = true;
    confirmAttendanceBtn.innerHTML = isEditMode
        ? '<i class="bx bx-save me-1"></i>Simpan'
        : confirmAttendanceBtnLabel;
    if (attendanceMateriInput) {
        attendanceMateriInput.value = options.materi ? String(options.materi) : '';
    }
    if (presentStudentsInput) {
        presentStudentsInput.value = (options.presentStudents ?? '') === null ? '' : String(options.presentStudents ?? '');
        presentStudentsInput.removeAttribute('max');
    }
    if (classTotalStudentsInput) {
        classTotalStudentsInput.value = (options.classTotalStudents ?? '') === null ? '' : String(options.classTotalStudents ?? '');
    }

    if (!currentClassTotalStudents && options.classTotalStudents) {
        currentClassTotalStudents = Number(options.classTotalStudents);
    }

    if (currentClassTotalStudents) {
        classTotalInputGroup.style.display = 'none';
        classTotalInfo.className = 'alert alert-success border mb-2';
        classTotalInfo.innerHTML = `Jumlah siswa kelas sudah tersimpan: <strong>${currentClassTotalStudents} siswa</strong>.`;
        presentStudentsInput?.setAttribute('max', currentClassTotalStudents);
    } else {
        classTotalInputGroup.style.display = '';
        classTotalInfo.className = 'alert alert-warning border mb-2';
        classTotalInfo.textContent = 'Jumlah siswa kelas belum tersimpan. Isi sekali untuk kelas ini.';
    }
    updateStudentAttendancePreview();

    document.getElementById('modal-subject').innerText = subject;
    document.getElementById('modal-class').innerText = className;
    document.getElementById('modal-school').innerText = schoolName;
    document.getElementById('modal-time').innerText = startTime + ' - ' + endTime;

    const modalEl = document.getElementById('attendanceModal');
    const modal = new bootstrap.Modal(modalEl);

    // Map will be initialized when location is obtained
    modalEl.addEventListener('shown.bs.modal', function onModalShown() {
        modalEl.removeEventListener('shown.bs.modal', onModalShown);
    });

    modal.show();
    updateLocationStatus('loading', 'Mendapatkan lokasi Anda...');

    // Show loading initially
    $('#map-loading').show();
    $('#map-placeholder').hide();
    $('#locationMap').hide();

    if (isEditMode) {
        $('#map-loading').hide();
        $('#map-placeholder').hide();
        $('#locationMap').hide();
        updateLocationStatus('success', 'Mode edit: lokasi tidak perlu divalidasi.', true);
        refreshConfirmAttendanceButton();
        return;
    }

    // Initialize map first, then get location
    initializeMap();

    // get two readings like presensi page
    getReadingAndVerify().then(() => {
        // Location obtained successfully - hide loading, show map
        $('#map-loading').fadeOut(200, function() {
            $('#locationMap').fadeIn(200, function() {
                // Force map to resize properly after it's visible
                setTimeout(() => {
                    if (map) {
                        map.invalidateSize();
                    }
                }, 100);
            });
        });
    }).catch(err => {
        // Error getting location - hide loading, show placeholder
        $('#map-loading').fadeOut(200);
        $('#map-placeholder').fadeIn(200);
        updateLocationStatus('error', err);
    });
}

function getReadingAndVerify() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject('Browser tidak mendukung geolokasi.');
            return;
        }

        // first reading: quick
        navigator.geolocation.getCurrentPosition((pos1) => {
            // store reading1
            sessionStorage.setItem('reading1_latitude', pos1.coords.latitude);
            sessionStorage.setItem('reading1_longitude', pos1.coords.longitude);
            sessionStorage.setItem('reading1_timestamp', Date.now());

            // second reading for verification
            navigator.geolocation.getCurrentPosition((pos2) => {
                userLocation = { latitude: pos2.coords.latitude, longitude: pos2.coords.longitude };
                // Update map with user location immediately
                updateMapLocation(userLocation.latitude, userLocation.longitude);
                // check location in polygon
                checkLocationInPolygon(userLocation.latitude, userLocation.longitude, currentScheduleId).then(isValid => {
                    if (isValid) {
                        updateLocationStatus('success', 'Lokasi berada dalam area sekolah.', true);
                    } else {
                        updateLocationStatus('warning', 'Lokasi Anda berada di luar area sekolah.');
                    }
                    resolve();
                }).catch(err => reject('Gagal memverifikasi lokasi: ' + err));
            }, (err2) => {
                reject('Gagal mendapatkan lokasi kedua: ' + err2.message);
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });

        }, (err1) => {
            reject('Gagal mendapatkan lokasi awal: ' + err1.message);
        }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
    });
}

function updateLocationStatus(status, message, isSuccess = false) {
    const el = document.getElementById('locationStatus');
    el.className = 'alert';
    if (isSuccess) {
        isLocationValid = true;
        el.classList.add('alert-success');
        el.innerHTML = '<i class="bx bx-check-circle me-2"></i> ' + message;
        document.getElementById('confirmAttendanceBtn').disabled = false;
    } else if (status === 'loading') {
        isLocationValid = false;
        el.classList.add('alert-info');
        el.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i> ' + message;
        document.getElementById('confirmAttendanceBtn').disabled = true;
    } else if (status === 'warning') {
        isLocationValid = false;
        el.classList.add('alert-warning');
        el.innerHTML = '<i class="bx bx-error-circle me-2"></i> ' + message;
        document.getElementById('confirmAttendanceBtn').disabled = true;
    } else {
        isLocationValid = false;
        el.classList.add('alert-danger');
        el.innerHTML = '<i class="bx bx-error me-2"></i> ' + message;
        document.getElementById('confirmAttendanceBtn').disabled = true;
    }
    refreshConfirmAttendanceButton();
}

function checkLocationInPolygon(lat, lng, scheduleId) {
    return new Promise((resolve, reject) => {
        fetch('<?php echo e(route('teaching-attendances.check-location')); ?>', {
            method: 'POST', headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
            body: JSON.stringify({ latitude: lat, longitude: lng, teaching_schedule_id: scheduleId })
        }).then(res => res.json()).then(json => {
            if (json.success) resolve(json.is_within_polygon);
            else reject(json.message || 'Gagal verifikasi');
        }).catch(err => reject(err));
    });
}

document.addEventListener('click', function (e) {
    const editTrigger = e.target.closest('.edit-attendance-btn');
    if (editTrigger) {
        let payload = null;
        try { payload = JSON.parse(editTrigger.dataset.attendance || 'null'); } catch (err) {}
        if (!payload || !payload.id) return;

        openAttendanceModal(
            payload.schedule_id,
            payload.subject,
            payload.class_name,
            payload.school_name,
            payload.start_time,
            payload.end_time,
            payload.class_total_students,
            {
                mode: 'edit',
                attendanceId: payload.id,
                materi: payload.materi,
                presentStudents: payload.present_students,
                classTotalStudents: payload.class_total_students,
            }
        );
        return;
    }

    const actionTrigger = e.target.closest('.attendance-btn, .manual-attendance-btn');
    if (actionTrigger) {
        const scheduleId = actionTrigger.dataset.scheduleId;
        const overlaps = getOverlappingSchedules(scheduleId);
        const warnings = [];
        const isManual = actionTrigger.classList.contains('manual-attendance-btn');
        const dayMarker = actionTrigger.dataset.dayMarker || scheduleMeta[String(scheduleId)]?.dayMarker || 'normal';
        const isKegiatanKhusus = dayMarker === 'kegiatan_khusus';
        const isLibur = dayMarker === 'libur';

        if (isLibur) {
            Swal.fire({ icon: 'info', title: 'Hari Libur', text: 'Presensi mengajar dinonaktifkan untuk kelas ini hari ini.' });
            return;
        }

        if (overlaps.length > 0) {
            const list = overlaps.slice(0, 3).map(o => `${escapeHtml(o.subject)} (${escapeHtml(o.startTime)}-${escapeHtml(o.endTime)})`).join('<br>');
            warnings.push(`<div class="text-start"><b>Jadwal bentrok</b><br>${list}${overlaps.length > 3 ? '<br>...' : ''}</div>`);
        }

        if (isManual) {
            warnings.push('<div class="text-start"><b>Input manual</b><br>Anda akan menginput presensi di luar jam mengajar. Lanjutkan?</div>');
        }

        if (isKegiatanKhusus) {
            warnings.push('<div class="text-start"><b>Kegiatan Khusus</b><br>Hari ini ditandai sebagai kegiatan khusus (PKL/Study Tour/dll). Presensi diperbolehkan meski di luar jam/lokasi sekolah.</div>');
        }

        const shouldForce = isManual || isKegiatanKhusus;
        const forceReason = isKegiatanKhusus
            ? 'kegiatan_khusus'
            : (isManual ? (actionTrigger.dataset.forceReason || 'outside_time') : '');

        const proceed = () => openAttendanceModal(
            actionTrigger.dataset.scheduleId,
            actionTrigger.dataset.subject,
            actionTrigger.dataset.className,
            actionTrigger.dataset.schoolName,
            actionTrigger.dataset.startTime,
            actionTrigger.dataset.endTime,
            actionTrigger.dataset.classTotalStudents,
            {
                mode: 'create',
                force: shouldForce,
                forceReason: forceReason,
            }
        );

        if (warnings.length === 0) {
            proceed();
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi',
            html: warnings.join('<hr class="my-2">'),
            showCancelButton: true,
            confirmButtonText: 'Ya, lanjutkan',
            cancelButtonText: 'Batal',
        }).then(res => {
            if (res.isConfirmed) proceed();
        });
        return;
    }

    if (e.target.closest('#confirmAttendanceBtn')) {
        const materi = attendanceMateriInput ? attendanceMateriInput.value.trim() : '';
        if (!materi) {
            Swal.fire({ icon: 'warning', title: 'Materi Wajib Diisi', text: 'Tuliskan materi atau topik yang disampaikan sebelum mengirim presensi.' });
            return;
        }

        const { total, present } = getStudentAttendanceNumbers();
        if (!total || total < 1) {
            Swal.fire({ icon: 'warning', title: 'Jumlah Siswa Wajib Diisi', text: 'Isi jumlah siswa yang ada di kelas ini terlebih dahulu.' });
            return;
        }

        if (present < 0 || !presentStudentsInput?.value) {
            Swal.fire({ icon: 'warning', title: 'Jumlah Hadir Wajib Diisi', text: 'Isi jumlah siswa yang hadir pada jam mengajar ini.' });
            return;
        }

        if (present > total) {
            Swal.fire({ icon: 'warning', title: 'Jumlah Tidak Valid', text: 'Jumlah siswa hadir tidak boleh melebihi jumlah siswa di kelas.' });
            return;
        }

        confirmAttendanceBtn.disabled = true;
        confirmAttendanceBtn.innerHTML = confirmAttendanceBtnLoadingLabel;

        if (isEditMode) {
            const updateUrl = `<?php echo e(url('/teaching-attendances')); ?>/${currentAttendanceId}`;
            fetch(updateUrl, {
                method: 'PUT',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                body: JSON.stringify({
                    materi: materi,
                    class_total_students: currentClassTotalStudents ? currentClassTotalStudents : total,
                    present_students: present,
                })
            }).then(async res => {
                const json = await res.json();
                return { ok: res.ok, json };
            }).then(({ ok, json }) => {
                confirmAttendanceBtn.disabled = false;
                confirmAttendanceBtn.innerHTML = '<i class="bx bx-save me-1"></i>Simpan';
                if (ok && json.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: json.message, timer: 2000 }).then(() => location.reload());
                    return;
                }
                Swal.fire({ icon: 'error', title: 'Gagal', text: json.message || 'Terjadi kesalahan' });
            }).catch(err => {
                confirmAttendanceBtn.disabled = false;
                confirmAttendanceBtn.innerHTML = '<i class="bx bx-save me-1"></i>Simpan';
                Swal.fire({ icon: 'error', title: 'Error', text: err?.message || String(err) });
            });
            return;
        }

        if (!userLocation || !currentScheduleId) {
            confirmAttendanceBtn.disabled = false;
            confirmAttendanceBtn.innerHTML = confirmAttendanceBtnLabel;
            Swal.fire({ icon: 'error', title: 'Kesalahan', text: 'Lokasi belum didapatkan atau jadwal tidak valid.' });
            return;
        }

        checkLocationInPolygon(userLocation.latitude, userLocation.longitude, currentScheduleId).then(isValid => {
            if (!isValid) {
                confirmAttendanceBtn.disabled = false;
                confirmAttendanceBtn.innerHTML = confirmAttendanceBtnLabel;
                Swal.fire({ icon: 'warning', title: 'Diluar Area', text: 'Lokasi Anda berada di luar area sekolah.' });
                return;
            }

            fetch('<?php echo e(route('teaching-attendances.store')); ?>', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                body: JSON.stringify({
                    teaching_schedule_id: currentScheduleId,
                    latitude: userLocation.latitude,
                    longitude: userLocation.longitude,
                    lokasi: 'Presensi Mengajar',
                    materi: materi,
                    class_total_students: currentClassTotalStudents ? null : total,
                    present_students: present,
                    force: currentForce ? 1 : 0,
                    force_reason: currentForceReason || null,
                })
            }).then(async res => {
                const json = await res.json();
                return { ok: res.ok, json };
            }).then(({ ok, json }) => {
                confirmAttendanceBtn.disabled = false;
                confirmAttendanceBtn.innerHTML = confirmAttendanceBtnLabel;

                if (ok && json.success) {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: json.message, timer: 2000 }).then(() => location.reload());
                    return;
                }

                Swal.fire({ icon: 'error', title: 'Gagal', text: json.message || 'Terjadi kesalahan' });
            }).catch(err => {
                confirmAttendanceBtn.disabled = false;
                confirmAttendanceBtn.innerHTML = confirmAttendanceBtnLabel;
                Swal.fire({ icon: 'error', title: 'Error', text: err?.message || String(err) });
            });
        }).catch(err => {
            confirmAttendanceBtn.disabled = false;
            confirmAttendanceBtn.innerHTML = confirmAttendanceBtnLabel;
            Swal.fire({ icon: 'error', title: 'Error', text: err });
        });
    }
});

if (attendanceMateriInput) {
    attendanceMateriInput.addEventListener('input', refreshConfirmAttendanceButton);
}
if (classTotalStudentsInput) {
    classTotalStudentsInput.addEventListener('input', refreshConfirmAttendanceButton);
}
if (presentStudentsInput) {
    presentStudentsInput.addEventListener('input', refreshConfirmAttendanceButton);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/teaching-attendances.blade.php ENDPATH**/ ?>