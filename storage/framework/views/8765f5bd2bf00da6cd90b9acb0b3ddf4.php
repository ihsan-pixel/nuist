<?php $__env->startSection('title', 'Tambah Jadwal Mengajar'); ?>

<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Tambah Jadwal Mengajar <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-plus me-2"></i>Tambah Jadwal Mengajar
                </h4>
            </div>
            <div class="card-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <form action="<?php echo e(route('teaching-schedules.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label for="school_id" class="form-label">Sekolah <span class="text-danger">*</span></label>
                                <select name="school_id" id="school_id" class="form-control" required>
                                    <option value="">Pilih Sekolah</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>" <?php echo e(Auth::user()->role === 'admin' && Auth::user()->madrasah_id == $school->id ? 'selected' : ''); ?>><?php echo e($school->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role !== 'admin'): ?>
                        <div class="col-md-2 text-center">
                            <button type="button" id="loadTeachersBtn" class="btn btn-info mt-4">
                                <i class="bx bx-search"></i> Cari Guru
                            </button>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label for="teacher_id" class="form-label">Guru <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    <option value="">Pilih Guru</option>
                                    <?php if(Auth::user()->role === 'admin'): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?php echo e($day); ?></h5>
                            <button type="button" class="btn btn-sm btn-success" onclick="addSchedule(<?php echo e($index); ?>)">
                                <i class="bx bx-plus"></i> Tambah Jam Pelajaran
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="schedules-container-<?php echo e($index); ?>">
                                <!-- Default schedule row -->
                                    <div class="schedule-row mb-3 border-bottom pb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="subject_<?php echo e($index); ?>_0" class="form-label">Mata Pelajaran</label>
                                                <input type="text" name="schedules[<?php echo e($index); ?>][0][subject]" id="subject_<?php echo e($index); ?>_0" class="form-control" placeholder="Contoh: Matematika">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-3">
                                                <label for="class_name_<?php echo e($index); ?>_0" class="form-label">Kelas</label>
                                                <input type="text" name="schedules[<?php echo e($index); ?>][0][class_name]" id="class_name_<?php echo e($index); ?>_0" class="form-control" placeholder="Contoh: VII A">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="start_time_<?php echo e($index); ?>_0" class="form-label">Jam Mulai</label>
                                                <input type="time" name="schedules[<?php echo e($index); ?>][0][start_time]" id="start_time_<?php echo e($index); ?>_0" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="end_time_<?php echo e($index); ?>_0" class="form-label">Jam Selesai</label>
                                                <input type="time" name="schedules[<?php echo e($index); ?>][0][end_time]" id="end_time_<?php echo e($index); ?>_0" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeSchedule(this)" style="display: none;">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="schedules[<?php echo e($index); ?>][0][day]" value="<?php echo e($day); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Simpan
                        </button>
                        <a href="<?php echo e(route('teaching-schedules.index')); ?>" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let scheduleCounters = {};
<?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
scheduleCounters[<?php echo e($index); ?>] = 1;
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

function addSchedule(dayIndex) {
    const container = document.getElementById(`schedules-container-${dayIndex}`);
    const counter = scheduleCounters[dayIndex]++;
    const dayName = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][dayIndex];

    const scheduleRow = document.createElement('div');
    scheduleRow.className = 'schedule-row mb-3 border-bottom pb-3';
    scheduleRow.innerHTML = `
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="subject_${dayIndex}_${counter}" class="form-label">Mata Pelajaran</label>
                    <input type="text" name="schedules[${dayIndex}][${counter}][subject]" id="subject_${dayIndex}_${counter}" class="form-control" placeholder="Contoh: Matematika">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-3">
                    <label for="class_name_${dayIndex}_${counter}" class="form-label">Kelas</label>
                    <input type="text" name="schedules[${dayIndex}][${counter}][class_name]" id="class_name_${dayIndex}_${counter}" class="form-control" placeholder="Contoh: VII A">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="start_time_${dayIndex}_${counter}" class="form-label">Jam Mulai</label>
                    <input type="time" name="schedules[${dayIndex}][${counter}][start_time]" id="start_time_${dayIndex}_${counter}" class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <label for="end_time_${dayIndex}_${counter}" class="form-label">Jam Selesai</label>
                    <input type="time" name="schedules[${dayIndex}][${counter}][end_time]" id="end_time_${dayIndex}_${counter}" class="form-control">
                </div>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeSchedule(this)">
                    <i class="bx bx-trash"></i>
                </button>
            </div>
        </div>
        <input type="hidden" name="schedules[${dayIndex}][${counter}][day]" value="${dayName}">
    `;

    container.appendChild(scheduleRow);

    // Show remove button for first row if there are multiple rows
    const rows = container.querySelectorAll('.schedule-row');
    if (rows.length > 1) {
        rows[0].querySelector('.btn-danger').style.display = 'block';
    }
}

function removeSchedule(button) {
    const scheduleRow = button.closest('.schedule-row');
    const container = scheduleRow.parentElement;
    const dayIndex = container.id.split('-')[2];

    scheduleRow.remove();

    // Hide remove button for first row if only one row remains
    const rows = container.querySelectorAll('.schedule-row');
    if (rows.length === 1) {
        rows[0].querySelector('.btn-danger').style.display = 'none';
    }
}

document.getElementById('loadTeachersBtn').addEventListener('click', function() {
    var schoolSelect = document.getElementById('school_id');
    var schoolId = schoolSelect.value;
    var teacherSelect = document.getElementById('teacher_id');

    if (schoolId) {
        fetch('<?php echo e(route("teaching-schedules.get-teachers", ":schoolId")); ?>'.replace(':schoolId', schoolId))
            .then(response => response.json())
            .then(data => {
                teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
                data.forEach(teacher => {
                    teacherSelect.innerHTML += '<option value="' + teacher.id + '">' + teacher.name + '</option>';
                });
            })
            .catch(error => {
                console.error('Error:', error);
                teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
            });
    } else {
        teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/teaching-schedules/create.blade.php ENDPATH**/ ?>