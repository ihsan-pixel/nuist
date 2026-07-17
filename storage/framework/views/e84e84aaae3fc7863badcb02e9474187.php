<?php $__env->startSection('title'); ?>
    Kelas Berjalan - <?php echo e($school->name); ?> (<?php echo e($selectedDay); ?>)
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />

<style>
.classes-shell {
    display: grid;
    gap: 1.5rem;
}

.hero-card,
.filter-card,
.summary-card,
.classes-card,
.empty-card {
    border: 1px solid #e7edf5;
    border-radius: 1.25rem;
    box-shadow: 0 18px 42px rgba(15, 23, 42, 0.06);
    background: #fff;
}

.hero-card {
    background:
        radial-gradient(circle at top right, rgba(13, 110, 253, 0.16), transparent 28%),
        linear-gradient(135deg, #ffffff 0%, #f6f9ff 52%, #eef4ff 100%);
}

.hero-mark {
    width: 72px;
    height: 72px;
    border-radius: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #0d6efd 0%, #3f8cff 100%);
    color: #fff;
    font-size: 2rem;
    box-shadow: 0 18px 35px rgba(13, 110, 253, 0.24);
}

.soft-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.48rem 0.82rem;
    border-radius: 999px;
    border: 1px solid #e7edf5;
    background: rgba(255, 255, 255, 0.88);
    color: #475569;
    font-size: 0.82rem;
    font-weight: 600;
}

.field-label {
    font-weight: 600;
    color: #334155;
}

.field-control {
    min-height: 48px;
    border-radius: 0.95rem;
    border: 1px solid #dbe6f3;
}

.summary-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0f172a;
}

.summary-meta {
    color: #64748b;
    font-size: 0.9rem;
}

.summary-icon {
    width: 48px;
    height: 48px;
    border-radius: 15px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.summary-value {
    font-size: 1.65rem;
    font-weight: 700;
    color: #0f172a;
}

.summary-label {
    color: #64748b;
    font-size: 0.84rem;
}

.classes-card {
    overflow: hidden;
}

.classes-card-header {
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #edf2f7;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
}

.class-section + .class-section {
    border-top: 1px solid #edf2f7;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
}

.class-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
}

.schedule-item {
    height: 100%;
    border: 1px solid #ebf1f7;
    border-radius: 1rem;
    padding: 1rem;
    background: #fff;
    transition: 0.2s ease;
}

.schedule-item:hover {
    border-color: rgba(13, 110, 253, 0.2);
    box-shadow: 0 12px 26px rgba(15, 23, 42, 0.05);
}

.status-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.42rem 0.72rem;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 700;
}

.time-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.42rem 0.72rem;
    border-radius: 999px;
    background: #fff2e8;
    color: #9a3412;
    font-size: 0.76rem;
    font-weight: 700;
}

.subject-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.42rem 0.72rem;
    border-radius: 0.8rem;
    background: #eef5ff;
    color: #0d6efd;
    font-size: 0.82rem;
    font-weight: 700;
}

.schedule-meta {
    color: #64748b;
    font-size: 0.85rem;
}

.empty-icon {
    width: 84px;
    height: 84px;
    border-radius: 28px;
    margin: 0 auto 1.25rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e8f1ff 0%, #d8e7ff 100%);
    color: #0d6efd;
    font-size: 2.3rem;
}

.btn-pill {
    border-radius: 999px;
}

@media (max-width: 767.98px) {
    .hero-mark {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        font-size: 1.6rem;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $dayClasses = $classesByDay->get($selectedDay, collect());
    $allSchedules = $dayClasses->flatten(1);
    $totalClasses = $dayClasses->count();
    $totalSchedules = $allSchedules->count();
    $filledSchedules = $allSchedules->filter(fn ($schedule) => !empty($schedule->teacher_id))->count();
    $attendedSchedules = $allSchedules->filter(fn ($schedule) => $schedule->has_attendance_today)->count();
?>

<div class="classes-shell">
    <div class="card hero-card mb-0">
        <div class="card-body p-4 p-lg-4">
            <div class="row align-items-start g-3">
                <div class="col-auto">
                    <div class="hero-mark">
                        <i class="bx bx-group"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="soft-chip">
                            <i class="bx bx-building-house"></i><?php echo e($school->name); ?>

                        </span>
                        <span class="soft-chip">
                            <i class="bx bx-map"></i><?php echo e($school->kabupaten); ?>

                        </span>
                        <span class="soft-chip">
                            <i class="bx bx-hash"></i>SCOD <?php echo e($school->scod); ?>

                        </span>
                    </div>
                    <h3 class="mb-2">Kelas Berjalan</h3>
                    <p class="text-muted mb-0">
                        Pantau kelas yang berjalan per periode dan tanggal dengan tampilan yang lebih rapi, cepat dipindai, dan konsisten dengan halaman jadwal mengajar.
                    </p>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <a href="<?php echo e(route('teaching-schedules.school-schedules', ['schoolId' => $school->id, 'period_id' => optional($selectedPeriod)->id])); ?>" class="btn btn-outline-secondary btn-pill px-4">
                            <i class="bx bx-arrow-back me-1"></i>Kembali ke Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card filter-card mb-0">
        <div class="card-body p-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-5">
                    <div class="summary-title">
                        <?php echo e($selectedPeriod ? $selectedPeriod->summary_label : 'Pilih periode jadwal mengajar'); ?>

                    </div>
                    <div class="summary-meta mt-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedPeriod): ?>
                            Berlaku <?php echo e($selectedPeriod->date_range_label); ?>

                        <?php else: ?>
                            Belum ada periode jadwal mengajar yang dipilih untuk madrasah ini.
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-7">
                    <form method="GET" action="<?php echo e(route('teaching-schedules.school-classes', $school->id)); ?>" class="row g-3 align-items-end" id="date-form">
                        <div class="col-md-6">
                            <label for="period-picker" class="form-label field-label">Periode</label>
                            <select id="period-picker" name="period_id" class="form-select field-control">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($period->id); ?>" <?php if(optional($selectedPeriod)->id === $period->id): echo 'selected'; endif; ?>>
                                    <?php echo e($period->summary_label); ?> | <?php echo e($period->date_range_label); ?>

                                </option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="date-picker" class="form-label field-label">Tanggal</label>
                            <input
                                type="date"
                                id="date-picker"
                                name="date"
                                class="form-control field-control"
                                value="<?php echo e($selectedDate->format('Y-m-d')); ?>">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary btn-pill px-3">
                                <i class="bx bx-filter-alt me-1"></i>Lihat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$selectedPeriod): ?>
    <div class="card empty-card mb-0">
        <div class="card-body text-center py-5 px-4">
            <div class="empty-icon">
                <i class="bx bx-calendar-star"></i>
            </div>
            <h4 class="mb-2">Pilih Periode Jadwal</h4>
            <p class="text-muted mb-0">
                Pilih periode terlebih dahulu untuk melihat daftar kelas berjalan sesuai tanggal yang dipilih.
            </p>
        </div>
    </div>
    <?php else: ?>
    <div class="row g-3">
        <div class="col-md-6 col-xl-3">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-primary-subtle text-primary">
                        <i class="bx bx-grid-alt"></i>
                    </div>
                    <div>
                        <div class="summary-label">Jumlah Kelas</div>
                        <div class="summary-value"><?php echo e($totalClasses); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-success-subtle text-success">
                        <i class="bx bx-book-content"></i>
                    </div>
                    <div>
                        <div class="summary-label">Total Jadwal</div>
                        <div class="summary-value"><?php echo e($totalSchedules); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-info-subtle text-info">
                        <i class="bx bx-user-check"></i>
                    </div>
                    <div>
                        <div class="summary-label">Jadwal Terisi</div>
                        <div class="summary-value"><?php echo e($filledSchedules); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card summary-card h-100 mb-0">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="summary-icon bg-warning-subtle text-warning">
                        <i class="bx bx-check-square"></i>
                    </div>
                    <div>
                        <div class="summary-label">Sudah Presensi</div>
                        <div class="summary-value"><?php echo e($attendedSchedules); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card classes-card mb-0">
        <div class="classes-card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h5 class="mb-1 text-primary">
                    <i class="bx bx-calendar-week me-2"></i><?php echo e($selectedDay); ?>

                </h5>
                <small class="text-muted">
                    <?php echo e($selectedDate->translatedFormat('d F Y')); ?> · <?php echo e($totalClasses); ?> kelas · <?php echo e($totalSchedules); ?> jadwal
                </small>
            </div>
            <span class="soft-chip">
                <i class="bx bx-calendar-event"></i><?php echo e($selectedPeriod->semester_label); ?> <?php echo e($selectedPeriod->school_year); ?>

            </span>
        </div>
        <div class="card-body p-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dayClasses->isNotEmpty()): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dayClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $className => $schedules): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <div class="class-section">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <div class="class-title"><?php echo e($className); ?></div>
                            <div class="summary-meta mt-1"><?php echo e($schedules->count()); ?> jadwal pada kelas ini</div>
                        </div>
                        <span class="soft-chip">
                            <i class="bx bx-book"></i><?php echo e($schedules->pluck('subject')->filter()->count()); ?> mapel
                        </span>
                    </div>
                    <div class="row g-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <div class="col-lg-6 col-xl-4">
                            <div class="schedule-item">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2">
                                        <div class="subject-chip">
                                            <i class="bx bx-book"></i><?php echo e($schedule->subject); ?>

                                        </div>
                                        <span class="time-chip">
                                            <i class="bx bx-time-five"></i><?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?>

                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedule->teacher): ?>
                                        <span class="status-chip bg-success-subtle text-success">
                                            <i class="bx bx-user-check"></i>Terisi
                                        </span>
                                        <?php else: ?>
                                        <span class="status-chip bg-warning-subtle text-warning">
                                            <i class="bx bx-user-x"></i>Kosong
                                        </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schedule->has_attendance_today): ?>
                                        <span class="status-chip bg-info-subtle text-info">
                                            <i class="bx bx-check"></i>Sudah Presensi
                                        </span>
                                        <?php else: ?>
                                        <span class="status-chip bg-secondary-subtle text-secondary">
                                            <i class="bx bx-time"></i>Belum Presensi
                                        </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="schedule-meta">
                                        <i class="bx bx-user me-1"></i><?php echo e($schedule->teacher ? $schedule->teacher->name : 'Belum ada guru'); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <?php else: ?>
            <div class="text-center py-5">
                <div class="empty-icon mb-3">
                    <i class="bx bx-calendar-x"></i>
                </div>
                <h5 class="mb-2">Tidak ada kelas pada hari <?php echo e($selectedDay); ?></h5>
                <p class="text-muted mb-0">
                    Belum ada kelas berjalan untuk tanggal <?php echo e($selectedDate->translatedFormat('d F Y')); ?> pada periode ini.
                </p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    $('#date-picker, #period-picker').on('change', function(e) {
        e.preventDefault();
        $('#date-form').submit();
        return false;
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/teaching-schedules/school-classes.blade.php ENDPATH**/ ?>