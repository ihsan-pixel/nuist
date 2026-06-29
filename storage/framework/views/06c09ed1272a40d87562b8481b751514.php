<?php $__env->startSection('title', $isEdit ? 'Edit Izin Jadwal Piket' : 'Tambah Izin Jadwal Piket'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Akademik <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo e($isEdit ? 'Edit Izin Jadwal Piket' : 'Tambah Izin Jadwal Piket'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <style>
                    .picket-page-note {
                        border: 1px solid #e6edf0;
                        border-radius: 14px;
                        padding: 14px 16px;
                        background: #f8fbfb;
                        color: #5f6b72;
                        font-size: 13px;
                        line-height: 1.5;
                    }

                    .picket-teacher-list {
                        display: grid;
                        gap: 14px;
                    }

                    .picket-teacher-card {
                        border: 1px solid #e9eef1;
                        border-radius: 16px;
                        padding: 14px;
                        background: #fff;
                    }

                    .picket-teacher-name {
                        font-size: 15px;
                        font-weight: 600;
                        color: #223035;
                        margin-bottom: 2px;
                    }

                    .picket-teacher-role {
                        font-size: 12px;
                        color: #7a8790;
                        margin-bottom: 10px;
                    }

                    .picket-date-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                        gap: 8px;
                    }

                    .picket-date-option {
                        display: flex;
                        align-items: flex-start;
                        gap: 10px;
                        border: 1px solid #e7ecef;
                        border-radius: 12px;
                        padding: 10px 12px;
                        background: #fbfcfc;
                        min-height: 100%;
                    }

                    .picket-date-option.disabled {
                        background: #f4f6f7;
                        color: #95a1a8;
                    }

                    .picket-date-text {
                        font-size: 13px;
                        line-height: 1.4;
                        color: #304047;
                    }

                    .picket-date-option.disabled .picket-date-text {
                        color: #7f8b91;
                    }

                    .picket-date-reason {
                        display: block;
                        margin-top: 2px;
                        font-size: 11px;
                        color: #d14d41;
                    }

                    .picket-inline-alert {
                        border: 1px solid #e9eef1;
                        border-radius: 14px;
                        padding: 14px 16px;
                        background: #fbfcfc;
                        color: #5f6b72;
                        font-size: 13px;
                    }
                </style>

                <div class="mb-4">
                    <h4 class="mb-1"><?php echo e($isEdit ? 'Ubah Periode Jadwal Piket' : 'Tambah Periode Jadwal Piket'); ?></h4>
                    <p class="text-muted mb-0">
                        Sekolah: <strong><?php echo e($school?->name ?? 'Pilih sekolah terlebih dahulu'); ?></strong>
                    </p>
                </div>

                <form action="<?php echo e($isEdit ? route('picket-schedule-periods.update', $period) : route('picket-schedule-periods.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEdit): ?>
                        <?php echo method_field('PUT'); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="row g-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->role === 'super_admin'): ?>
                            <div class="col-12">
                                <label class="form-label">Sekolah</label>
                                <select name="school_id" class="form-select <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Pilih sekolah</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($schoolOption->id); ?>" <?php if((string) old('school_id', $period->school_id) === (string) $schoolOption->id): echo 'selected'; endif; ?>>
                                            <?php echo e($schoolOption->name); ?>

                                        </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="col-12">
                                <label class="form-label">Sekolah</label>
                                <input type="text" class="form-control" value="<?php echo e($school?->name ?? '-'); ?>" readonly>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="col-12">
                            <div class="picket-page-note">
                                Admin sekolah menyusun langsung hari piket tiap tenaga pendidik dalam satu form, lalu seluruh susunan ini dikirim ke approval kepala sekolah.
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nama Periode</label>
                            <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $period->name)); ?>" placeholder="Contoh: Piket Libur Semester Ganjil 2026">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" id="start_date" name="start_date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_date', optional($period->start_date)->format('Y-m-d') ?: $period->start_date)); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" id="end_date" name="end_date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('end_date', optional($period->end_date)->format('Y-m-d') ?: $period->end_date)); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="description" rows="4" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Contoh: Admin sekolah menyusun hari piket guru selama libur semester untuk pelayanan sekolah dan administrasi."><?php echo e(old('description', $period->description)); ?></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <div class="col-12">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" <?php if(old('is_active', $period->is_active ?? true)): echo 'checked'; endif; ?>>
                                <label class="form-check-label" for="is_active">Periode aktif dan pengajuannya disusun oleh admin sekolah</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pt-2">
                                <div>
                                    <h5 class="mb-1">Susun Hari Piket Guru</h5>
                                    <small class="text-muted">Pilih hanya hari kerja yang benar-benar menjadi jadwal piket.</small>
                                </div>
                                <span class="badge bg-primary"><?php echo e($teachers->count()); ?> guru</span>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['teacher_dates'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="alert alert-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div id="picket-teacher-builder"></div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><?php echo e($isEdit ? 'Simpan Perubahan' : 'Simpan Periode'); ?></button>
                        <a href="<?php echo e(route('picket-schedule-periods.index')); ?>" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
    $initialTeacherDates = collect(old('teacher_dates', $existingSelections ?? []))
        ->mapWithKeys(function ($dates, $teacherId) {
            return [(string) $teacherId => collect(is_array($dates) ? $dates : [])->filter()->values()->all()];
        })
        ->all();
?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const container = document.getElementById('picket-teacher-builder');

        if (!startInput || !endInput || !container) {
            return;
        }

        const teachers = <?php echo json_encode($teachers->map(fn ($teacher) => [
            'id' => (string) $teacher->id, 'name' => $teacher->name, 'ketugasan' => $teacher->ketugasan ?: 'Tenaga pendidik') ?>;
        const initialSelections = <?php echo json_encode($initialTeacherDates, 15, 512) ?>;
        const isFiveDaySchool = <?php echo json_encode((string) ($school?->hari_kbm ?? '') === '5', 15, 512) ?>;
        const formatter = new Intl.DateTimeFormat('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });

        let selectionState = JSON.parse(JSON.stringify(initialSelections));

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function syncCurrentSelections() {
            const nextState = {};
            const fieldPrefix = 'teacher_dates[';
            const fieldSuffix = '][]';

            container.querySelectorAll('input[type="checkbox"][name^="teacher_dates["]:checked').forEach((input) => {
                if (!input.name.startsWith(fieldPrefix) || !input.name.endsWith(fieldSuffix)) {
                    return;
                }

                const teacherId = input.name.slice(fieldPrefix.length, -fieldSuffix.length);
                if (!nextState[teacherId]) {
                    nextState[teacherId] = [];
                }

                nextState[teacherId].push(input.value);
            });

            selectionState = nextState;
        }

        function formatDateValue(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function buildActiveDateChoices(startValue, endValue) {
            if (!startValue || !endValue) {
                return [];
            }

            const start = new Date(`${startValue}T00:00:00`);
            const end = new Date(`${endValue}T00:00:00`);

            if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime()) || end < start) {
                return [];
            }

            const choices = [];
            const current = new Date(start);

            while (current <= end) {
                const day = current.getDay();
                const isSunday = day === 0;
                const isSaturday = day === 6;

                if (!isSunday && !(isFiveDaySchool && isSaturday)) {
                    const dateValue = formatDateValue(current);
                    const label = formatter.format(current).replace(/\./g, '');
                    choices.push({
                        date: dateValue,
                        label: label.charAt(0).toUpperCase() + label.slice(1),
                    });
                }

                current.setDate(current.getDate() + 1);
            }

            return choices;
        }

        function renderTeacherBuilder() {
            syncCurrentSelections();

            if (!teachers.length) {
                container.innerHTML = '<div class="picket-inline-alert">Data tenaga pendidik belum tersedia untuk sekolah ini.</div>';
                return;
            }

            const activeDateChoices = buildActiveDateChoices(startInput.value, endInput.value);

            if (!startInput.value || !endInput.value) {
                container.innerHTML = '<div class="picket-inline-alert">Rentang tanggal belum tersedia. Isi tanggal mulai dan tanggal selesai yang valid terlebih dahulu.</div>';
                return;
            }

            if (!activeDateChoices.length) {
                container.innerHTML = '<div class="picket-inline-alert">Tidak ada hari aktif yang bisa dipilih pada rentang tanggal ini.</div>';
                return;
            }

            const teacherCards = teachers.map((teacher) => {
                const selectedDates = new Set(selectionState[teacher.id] || []);
                const options = activeDateChoices.map((choice) => `
                    <label class="picket-date-option">
                        <input
                            type="checkbox"
                            name="teacher_dates[${escapeHtml(teacher.id)}][]"
                            value="${escapeHtml(choice.date)}"
                            class="mt-1"
                            ${selectedDates.has(choice.date) ? 'checked' : ''}
                        >
                        <span class="picket-date-text">${escapeHtml(choice.label)}</span>
                    </label>
                `).join('');

                return `
                    <div class="picket-teacher-card">
                        <div class="picket-teacher-name">${escapeHtml(teacher.name)}</div>
                        <div class="picket-teacher-role">${escapeHtml(teacher.ketugasan)}</div>
                        <div class="picket-date-grid">${options}</div>
                    </div>
                `;
            }).join('');

            container.innerHTML = `<div class="picket-teacher-list">${teacherCards}</div>`;
        }

        startInput.addEventListener('change', renderTeacherBuilder);
        endInput.addEventListener('change', renderTeacherBuilder);
        container.addEventListener('change', (event) => {
            if (event.target.matches('input[type="checkbox"][name^="teacher_dates["]')) {
                syncCurrentSelections();
            }
        });

        renderTeacherBuilder();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/picket-schedules/form.blade.php ENDPATH**/ ?>