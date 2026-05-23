<?php $__env->startSection('title', $isEditing ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 600px; margin: auto;">
    <header class="mobile-header d-md-none mb-3">
        <div class="d-flex align-items-center justify-content-between px-2 py-2">
            <div>
                <div class="fw-semibold"><?php echo e($isEditing ? 'Edit Jadwal Mengajar' : 'Tambah Jadwal Mengajar'); ?></div>
                <div class="text-muted small">Pilih kelas dari daftar atau ketik kelas baru</div>
            </div>
            <a class="btn btn-sm btn-outline-secondary" href="<?php echo e(route('mobile.jadwal')); ?>">
                <i class="bx bx-arrow-back"></i>
            </a>
        </div>
    </header>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Periksa kembali input Anda:</div>
            <ul class="mb-0 ps-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <li><?php echo e($err); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <form method="POST" action="<?php echo e($isEditing ? route('mobile.jadwal.update', $schedule->id) : route('mobile.jadwal.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditing): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="card mb-3">
            <div class="card-body">
                <?php
                    $subjectValue = old('subject', optional($schedule)->subject ?? '');
                    $classValue = old('class_name', optional($schedule)->class_name ?? '');
                    $subjectIsKnown = $subjectValue !== '' && $subjects->contains($subjectValue);
                    $classIsKnown = $classValue !== '' && $classes->contains($classValue);
                ?>

                <div class="mb-3">
                    <label class="form-label mb-1">Hari</label>
                    <select class="form-select" name="day" required>
                        <?php
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $selectedDay = old('day', optional($schedule)->day ?? '');
                        ?>
                        <option value="" <?php if($selectedDay === ''): echo 'selected'; endif; ?>>Pilih hari</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($d); ?>" <?php if($selectedDay === $d): echo 'selected'; endif; ?>><?php echo e($d); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label mb-1">Mata Pelajaran</label>
                    <select class="form-select" name="subject" id="subjectSelect" required>
                        <option value="" <?php if($subjectValue === ''): echo 'selected'; endif; ?>>Pilih mata pelajaran</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($s); ?>" <?php if($subjectValue === (string) $s): echo 'selected'; endif; ?>><?php echo e($s); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <option value="__new__" <?php if(!$subjectIsKnown && $subjectValue !== ''): echo 'selected'; endif; ?>>Tambah mata pelajaran baru...</option>
                    </select>
                    <input
                        type="text"
                        class="form-control mt-2"
                        name="subject_new"
                        id="subjectNew"
                        value="<?php echo e(old('subject_new', $subjectIsKnown ? '' : $subjectValue)); ?>"
                        placeholder="Tulis mata pelajaran baru"
                        style="<?php echo e(($subjectValue === '' || $subjectIsKnown) ? 'display:none;' : ''); ?>"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label mb-1">Kelas</label>
                    <select class="form-select" name="class_name" id="classSelect" required>
                        <option value="" <?php if($classValue === ''): echo 'selected'; endif; ?>>Pilih kelas</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option value="<?php echo e($c); ?>" <?php if($classValue === (string) $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        <option value="__new__" <?php if(!$classIsKnown && $classValue !== ''): echo 'selected'; endif; ?>>Tambah kelas baru...</option>
                    </select>
                    <input
                        type="text"
                        class="form-control mt-2"
                        name="class_name_new"
                        id="classNew"
                        value="<?php echo e(old('class_name_new', $classIsKnown ? '' : $classValue)); ?>"
                        placeholder="Tulis nama kelas baru"
                        style="<?php echo e(($classValue === '' || $classIsKnown) ? 'display:none;' : ''); ?>"
                    />
                    <div class="form-text">Daftar kelas & mapel diambil dari jadwal madrasah Anda. Jika belum ada, pilih "Tambah ..." lalu ketik.</div>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label mb-1">Jam Mulai</label>
                        <input
                            type="time"
                            class="form-control"
                            name="start_time"
                            value="<?php echo e(old('start_time', $schedule ? substr((string) $schedule->start_time, 0, 5) : '')); ?>"
                            required
                        />
                    </div>
                    <div class="col-6">
                        <label class="form-label mb-1">Jam Selesai</label>
                        <input
                            type="time"
                            class="form-control"
                            name="end_time"
                            value="<?php echo e(old('end_time', $schedule ? substr((string) $schedule->end_time, 0, 5) : '')); ?>"
                            required
                        />
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-success w-100" type="submit">
            <i class="bx bx-save me-1"></i> <?php echo e($isEditing ? 'Simpan Perubahan' : 'Simpan Jadwal'); ?>

        </button>
    </form>

    <script>
        (function () {
            function bindToggle(selectId, newInputId) {
                const selectEl = document.getElementById(selectId);
                const newEl = document.getElementById(newInputId);
                if (!selectEl || !newEl) return;

                const toggle = () => {
                    if (selectEl.value === '__new__') {
                        newEl.style.display = '';
                    } else {
                        newEl.style.display = 'none';
                        newEl.value = '';
                    }
                };

                selectEl.addEventListener('change', toggle);
                toggle();
            }

            document.addEventListener('DOMContentLoaded', function () {
                bindToggle('subjectSelect', 'subjectNew');
                bindToggle('classSelect', 'classNew');
            });
        })();
    </script>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('overlap')): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Swal === 'undefined') return;
                Swal.fire({
                    icon: 'warning',
                    title: 'Jadwal Bentrok',
                    text: <?php echo json_encode($errors->first('overlap'), 15, 512) ?>,
                    confirmButtonText: 'OK'
                });
            });
        </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditing): ?>
        <form method="POST" action="<?php echo e(route('mobile.jadwal.destroy', $schedule->id)); ?>" class="mt-2" onsubmit="return confirm('Hapus jadwal ini?');">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button class="btn btn-outline-danger w-100" type="submit">
                <i class="bx bx-trash me-1"></i> Hapus Jadwal
            </button>
        </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/jadwal-form.blade.php ENDPATH**/ ?>