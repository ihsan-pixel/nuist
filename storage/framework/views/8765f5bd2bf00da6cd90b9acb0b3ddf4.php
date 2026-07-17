<?php $__env->startSection('title', 'Tambah Jadwal Mengajar'); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />

<style>
.schedule-create-shell {
    display: grid;
    gap: 1.5rem;
}

.hero-card,
.form-card,
.day-card,
.summary-card {
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

.section-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0f172a;
}

.section-meta {
    color: #64748b;
    font-size: 0.9rem;
}

.form-card {
    background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
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

.field-note {
    color: #64748b;
    font-size: 0.82rem;
}

.summary-card {
    background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
}

.summary-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 0.8rem;
    border-radius: 999px;
    border: 1px solid #e7edf5;
    background: #fff;
    color: #475569;
    font-size: 0.82rem;
    font-weight: 600;
}

.day-card {
    overflow: hidden;
}

.day-card-header {
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #edf2f7;
    background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
}

.day-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.38rem 0.7rem;
    border-radius: 999px;
    background: #eef5ff;
    color: #0d6efd;
    font-size: 0.76rem;
    font-weight: 700;
}

.add-row-btn,
.btn-pill {
    border-radius: 999px;
}

.schedule-row {
    border: 1px solid #e9eff7;
    border-radius: 1rem;
    padding: 1rem;
    background: #fff;
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.04);
}

.schedule-row + .schedule-row {
    margin-top: 1rem;
}

.remove-row-btn {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.sticky-footer {
    position: sticky;
    bottom: 1rem;
    z-index: 10;
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
    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $user = Auth::user();
    $defaultSchoolId = (int) old('school_id', $selectedSchoolId ?? ($user->role === 'admin' ? $user->madrasah_id : 0));
    $selectedSchool = $schools->firstWhere('id', $defaultSchoolId);
    $selectedTeacherId = (string) old('teacher_id', '');
    $selectedPeriodId = (string) old('teaching_schedule_period_id', optional($selectedPeriod)->id);
    $backUrl = $selectedSchool
        ? route('teaching-schedules.school-schedules', ['schoolId' => $selectedSchool->id, 'period_id' => $selectedPeriodId ?: null])
        : route('teaching-schedules.index');
?>

<div class="schedule-create-shell">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="alert alert-success mb-0">
        <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
    <div class="alert alert-danger mb-0">
        <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
    <div class="alert alert-danger mb-0">
        <div class="fw-semibold mb-1">Terdapat data yang perlu diperiksa.</div>
        <ul class="mb-0">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <li><?php echo e($error); ?></li>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </ul>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="card hero-card mb-0">
        <div class="card-body p-4 p-lg-4">
            <div class="row align-items-start g-3">
                <div class="col-auto">
                    <div class="hero-mark">
                        <i class="bx bx-calendar-plus"></i>
                    </div>
                </div>
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedSchool): ?>
                        <span class="soft-chip">
                            <i class="bx bx-building-house"></i><?php echo e($selectedSchool->name); ?>

                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedPeriod): ?>
                        <span class="soft-chip">
                            <i class="bx bx-calendar-event"></i><?php echo e($selectedPeriod->semester_label); ?> <?php echo e($selectedPeriod->school_year); ?>

                        </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <h3 class="mb-2">Tambah Jadwal Mengajar</h3>
                    <p class="text-muted mb-0">
                        Lengkapi informasi sekolah, guru, dan periode terlebih dahulu, lalu susun jam pelajaran per hari dengan tampilan yang konsisten dengan halaman jadwal utama.
                    </p>
                </div>
                <div class="col-12 col-lg-auto">
                    <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                        <button type="submit" form="scheduleCreateForm" class="btn btn-primary btn-pill px-4">
                            <i class="bx bx-save me-1"></i>Simpan Jadwal
                        </button>
                        <a href="<?php echo e($backUrl); ?>" class="btn btn-outline-secondary btn-pill px-4">
                            <i class="bx bx-arrow-back me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card form-card mb-0">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
                <div>
                    <div class="section-title">Informasi Dasar Jadwal</div>
                    <div class="section-meta">Tentukan sekolah, guru pengampu, dan periode akademik untuk jadwal yang akan disimpan.</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="summary-pill"><i class="bx bx-calendar-week"></i><?php echo e(count($days)); ?> hari tersedia</span>
                    <span class="summary-pill"><i class="bx bx-copy-alt"></i>Histori periode tetap tersimpan</span>
                </div>
            </div>

            <form action="<?php echo e(route('teaching-schedules.store')); ?>" method="POST" id="scheduleCreateForm">
                <?php echo csrf_field(); ?>
                <div class="row g-3">
                    <div class="col-lg-4">
                        <label for="school_id" class="form-label field-label">Sekolah <span class="text-danger">*</span></label>
                        <select name="school_id" id="school_id" class="form-select field-control" required>
                            <option value="">Pilih Sekolah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($school->id); ?>" <?php if($defaultSchoolId === (int) $school->id): echo 'selected'; endif; ?>><?php echo e($school->name); ?></option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>

                    <div class="col-lg-4">
                        <label for="teacher_id" class="form-label field-label">Guru <span class="text-danger">*</span></label>
                        <div class="d-flex gap-2">
                            <select name="teacher_id" id="teacher_id" class="form-select field-control" required data-selected="<?php echo e($selectedTeacherId); ?>">
                                <option value="">Pilih Guru</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'admin'): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($teacher->id); ?>" <?php if($selectedTeacherId === (string) $teacher->id): echo 'selected'; endif; ?>><?php echo e($teacher->name); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role !== 'admin'): ?>
                            <button type="button" id="loadTeachersBtn" class="btn btn-outline-info btn-pill px-3">
                                <i class="bx bx-search"></i>
                            </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <label for="teaching_schedule_period_id" class="form-label field-label">Periode Jadwal <span class="text-danger">*</span></label>
                        <select
                            name="teaching_schedule_period_id"
                            id="teaching_schedule_period_id"
                            class="form-select field-control"
                            required
                            data-selected="<?php echo e($selectedPeriodId); ?>"
                        >
                            <option value="">Pilih periode jadwal</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($period->id); ?>" <?php if($selectedPeriodId === (string) $period->id): echo 'selected'; endif; ?>>
                                <?php echo e($period->title); ?> | <?php echo e($period->semester_label); ?> | <?php echo e($period->school_year); ?> | <?php echo e($period->date_range_label); ?>

                            </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="card summary-card mt-4 mb-0">
                    <div class="card-body p-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-8">
                                <div class="section-title mb-1">Ringkasan Periode</div>
                                <div class="section-meta" id="periodSummaryText">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedPeriod): ?>
                                        <?php echo e($selectedPeriod->summary_label); ?> | Berlaku <?php echo e($selectedPeriod->date_range_label); ?>

                                    <?php else: ?>
                                        Pilih periode untuk mengaitkan jadwal mengajar ke tahun pelajaran yang benar.
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <span class="summary-pill"><i class="bx bx-history"></i>Periode lama tidak tertimpa</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-3 mt-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <div class="card day-card mb-0">
                        <div class="day-card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <div class="section-title mb-1"><?php echo e($day); ?></div>
                                <div class="section-meta">Tambahkan mata pelajaran, kelas, dan rentang jam mengajar untuk hari <?php echo e(strtolower($day)); ?>.</div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm add-row-btn px-3" onclick="addSchedule(<?php echo e($index); ?>)">
                                <i class="bx bx-plus me-1"></i>Tambah Jam Pelajaran
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div id="schedules-container-<?php echo e($index); ?>">
                                <div class="schedule-row">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-lg-4">
                                            <label for="subject_<?php echo e($index); ?>_0" class="form-label field-label">Mata Pelajaran</label>
                                            <input type="text" name="schedules[<?php echo e($index); ?>][0][subject]" id="subject_<?php echo e($index); ?>_0" class="form-control field-control" placeholder="Contoh: Matematika" value="<?php echo e(old("schedules.$index.0.subject")); ?>">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="class_name_<?php echo e($index); ?>_0" class="form-label field-label">Kelas</label>
                                            <input type="text" name="schedules[<?php echo e($index); ?>][0][class_name]" id="class_name_<?php echo e($index); ?>_0" class="form-control field-control" placeholder="Contoh: VII A" value="<?php echo e(old("schedules.$index.0.class_name")); ?>">
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="start_time_<?php echo e($index); ?>_0" class="form-label field-label">Jam Mulai</label>
                                            <input type="time" name="schedules[<?php echo e($index); ?>][0][start_time]" id="start_time_<?php echo e($index); ?>_0" class="form-control field-control" value="<?php echo e(old("schedules.$index.0.start_time")); ?>">
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="end_time_<?php echo e($index); ?>_0" class="form-label field-label">Jam Selesai</label>
                                            <input type="time" name="schedules[<?php echo e($index); ?>][0][end_time]" id="end_time_<?php echo e($index); ?>_0" class="form-control field-control" value="<?php echo e(old("schedules.$index.0.end_time")); ?>">
                                        </div>
                                        <div class="col-lg-1 d-flex justify-content-lg-end">
                                            <button type="button" class="btn btn-outline-danger remove-row-btn" onclick="removeSchedule(this)" style="display: none;">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="schedules[<?php echo e($index); ?>][0][day]" value="<?php echo e($day); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>

                <div class="sticky-footer mt-4">
                    <div class="card form-card mb-0">
                        <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="section-meta">
                                Periksa kembali guru, periode, kelas, dan rentang jam sebelum menyimpan.
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary btn-pill px-4">
                                    <i class="bx bx-save me-1"></i>Simpan Jadwal
                                </button>
                                <a href="<?php echo e($backUrl); ?>" class="btn btn-outline-secondary btn-pill px-4">
                                    <i class="bx bx-arrow-back me-1"></i>Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
let scheduleCounters = {};
<?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
scheduleCounters[<?php echo e($index); ?>] = document.querySelectorAll('#schedules-container-<?php echo e($index); ?> .schedule-row').length;
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

function createFieldLabel(text) {
    const label = document.createElement('label');
    label.className = 'form-label field-label';
    label.textContent = text;
    return label;
}

function createInput(type, name, id, placeholder = '') {
    const input = document.createElement('input');
    input.type = type;
    input.name = name;
    input.id = id;
    input.className = 'form-control field-control';
    if (placeholder) {
        input.placeholder = placeholder;
    }
    return input;
}

function createRemoveButton() {
    const wrapper = document.createElement('div');
    wrapper.className = 'col-lg-1 d-flex justify-content-lg-end';

    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'btn btn-outline-danger remove-row-btn';
    button.innerHTML = '<i class="bx bx-trash"></i>';
    button.onclick = function() {
        removeSchedule(button);
    };

    wrapper.appendChild(button);
    return wrapper;
}

function addSchedule(dayIndex) {
    const container = document.getElementById(`schedules-container-${dayIndex}`);
    const counter = scheduleCounters[dayIndex]++;
    const dayName = days[dayIndex];

    const scheduleRow = document.createElement('div');
    scheduleRow.className = 'schedule-row';

    const row = document.createElement('div');
    row.className = 'row g-3 align-items-end';

    const subjectCol = document.createElement('div');
    subjectCol.className = 'col-lg-4';
    subjectCol.appendChild(createFieldLabel('Mata Pelajaran'));
    subjectCol.appendChild(createInput('text', `schedules[${dayIndex}][${counter}][subject]`, `subject_${dayIndex}_${counter}`, 'Contoh: Matematika'));

    const classCol = document.createElement('div');
    classCol.className = 'col-lg-3';
    classCol.appendChild(createFieldLabel('Kelas'));
    classCol.appendChild(createInput('text', `schedules[${dayIndex}][${counter}][class_name]`, `class_name_${dayIndex}_${counter}`, 'Contoh: VII A'));

    const startCol = document.createElement('div');
    startCol.className = 'col-lg-2';
    startCol.appendChild(createFieldLabel('Jam Mulai'));
    startCol.appendChild(createInput('time', `schedules[${dayIndex}][${counter}][start_time]`, `start_time_${dayIndex}_${counter}`));

    const endCol = document.createElement('div');
    endCol.className = 'col-lg-2';
    endCol.appendChild(createFieldLabel('Jam Selesai'));
    endCol.appendChild(createInput('time', `schedules[${dayIndex}][${counter}][end_time]`, `end_time_${dayIndex}_${counter}`));

    row.appendChild(subjectCol);
    row.appendChild(classCol);
    row.appendChild(startCol);
    row.appendChild(endCol);
    row.appendChild(createRemoveButton());

    scheduleRow.appendChild(row);

    const hiddenDay = document.createElement('input');
    hiddenDay.type = 'hidden';
    hiddenDay.name = `schedules[${dayIndex}][${counter}][day]`;
    hiddenDay.value = dayName;
    scheduleRow.appendChild(hiddenDay);

    container.appendChild(scheduleRow);
    syncRemoveButtons(container);
}

function removeSchedule(button) {
    const scheduleRow = button.closest('.schedule-row');
    const container = scheduleRow.parentElement;
    scheduleRow.remove();
    syncRemoveButtons(container);
}

function syncRemoveButtons(container) {
    const rows = container.querySelectorAll('.schedule-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-row-btn');
        if (!removeBtn) {
            return;
        }

        removeBtn.style.display = rows.length > 1 ? 'inline-flex' : 'none';
    });
}

function setTeacherOptions(data, selectedTeacherId = '') {
    const teacherSelect = document.getElementById('teacher_id');
    teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
    data.forEach(teacher => {
        const option = document.createElement('option');
        option.value = teacher.id;
        option.textContent = teacher.name;
        if (String(teacher.id) === String(selectedTeacherId)) {
            option.selected = true;
        }
        teacherSelect.appendChild(option);
    });
}

function loadTeachersBySchool(schoolId, selectedTeacherId = '') {
    const teacherSelect = document.getElementById('teacher_id');

    if (!schoolId) {
        teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
        return Promise.resolve();
    }

    return fetch('<?php echo e(route("teaching-schedules.get-teachers", ":schoolId")); ?>'.replace(':schoolId', schoolId))
        .then(response => response.json())
        .then(data => {
            setTeacherOptions(data, selectedTeacherId);
        })
        .catch(() => {
            teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
        });
}

function loadPeriodsBySchool(schoolId, selectedValue = '') {
    const periodSelect = document.getElementById('teaching_schedule_period_id');
    const periodSummary = document.getElementById('periodSummaryText');

    if (!schoolId) {
        periodSelect.innerHTML = '<option value="">Pilih periode jadwal</option>';
        periodSummary.textContent = 'Pilih periode untuk mengaitkan jadwal mengajar ke tahun pelajaran yang benar.';
        return Promise.resolve();
    }

    return fetch('<?php echo e(route("teaching-schedules.get-periods", ":schoolId")); ?>'.replace(':schoolId', schoolId))
        .then(response => response.json())
        .then(data => {
            periodSelect.innerHTML = '<option value="">Pilih periode jadwal</option>';
            let matched = null;

            data.forEach(period => {
                const option = document.createElement('option');
                option.value = period.id;
                option.textContent = period.summary_label + ' | ' + period.date_range_label;
                if (String(period.id) === String(selectedValue)) {
                    option.selected = true;
                    matched = period;
                }
                periodSelect.appendChild(option);
            });

            if (!matched && data.length === 1) {
                periodSelect.value = data[0].id;
                matched = data[0];
            }

            periodSummary.textContent = matched
                ? `${matched.summary_label} | Berlaku ${matched.date_range_label}`
                : 'Pilih periode untuk mengaitkan jadwal mengajar ke tahun pelajaran yang benar.';
        })
        .catch(() => {
            periodSelect.innerHTML = '<option value="">Pilih periode jadwal</option>';
            periodSummary.textContent = 'Pilih periode untuk mengaitkan jadwal mengajar ke tahun pelajaran yang benar.';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    const schoolSelect = document.getElementById('school_id');
    const teacherSelect = document.getElementById('teacher_id');
    const periodSelect = document.getElementById('teaching_schedule_period_id');
    const loadTeachersBtn = document.getElementById('loadTeachersBtn');

    document.querySelectorAll('[id^="schedules-container-"]').forEach(syncRemoveButtons);

    const initialSchoolId = schoolSelect.value;
    const selectedTeacherId = teacherSelect.dataset.selected || '';
    const selectedPeriodId = periodSelect.dataset.selected || '';

    loadPeriodsBySchool(initialSchoolId, selectedPeriodId);

    <?php if($user->role !== 'admin'): ?>
    loadTeachersBySchool(initialSchoolId, selectedTeacherId);
    <?php endif; ?>

    schoolSelect.addEventListener('change', function() {
        const schoolId = this.value;
        loadPeriodsBySchool(schoolId, '');
        loadTeachersBySchool(schoolId, '');
    });

    if (loadTeachersBtn) {
        loadTeachersBtn.addEventListener('click', function() {
            loadTeachersBySchool(schoolSelect.value, teacherSelect.value);
        });
    }

    periodSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('periodSummaryText').textContent = this.value
            ? selectedOption.textContent
            : 'Pilih periode untuk mengaitkan jadwal mengajar ke tahun pelajaran yang benar.';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/teaching-schedules/create.blade.php ENDPATH**/ ?>