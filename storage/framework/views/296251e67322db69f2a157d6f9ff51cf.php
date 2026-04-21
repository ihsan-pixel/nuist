<?php $__env->startSection('title'); ?>
    Tambah DPS
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $defaults = \App\Models\DpsMember::DEFAULT_UNSUR_OPTIONS;
    $existing = \App\Models\DpsMember::query()->select('unsur')->distinct()->pluck('unsur')->filter()->all();
    $unsurOptions = array_values(array_unique(array_merge($defaults, $existing)));
?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Tambah DPS <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card mb-4">
    <div class="card-body">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <div class="fw-semibold mb-2">Validasi gagal:</div>
                <ul class="mb-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <li><?php echo e($error); ?></li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </ul>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form method="POST" action="<?php echo e(route('dps.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label">SCOD</label>
                    <select name="madrasah_id" id="madrasah_id" class="form-select" required>
                        <option value="">Pilih sekolah...</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <option
                                value="<?php echo e($m->id); ?>"
                                data-name="<?php echo e($m->name); ?>"
                                data-scod="<?php echo e($m->scod); ?>"
                                <?php if((string)old('madrasah_id', $prefillMadrasahId) === (string)$m->id): echo 'selected'; endif; ?>
                            >
                                <?php echo e($m->scod); ?> - <?php echo e($m->name); ?>

                            </option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </select>
                </div>
                <div class="col-lg-8">
                    <label class="form-label">Nama Sekolah</label>
                    <input type="text" id="madrasah_name" class="form-control" value="" readonly>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Anggota DPS</div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                    <i class="bx bx-plus"></i> Tambah Baris
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="membersTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 34%;">Nama DPS</th>
                            <th style="width: 34%;">Unsur DPS</th>
                            <th style="width: 22%;">Periode</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="members[0][nama]" class="form-control" placeholder="Nama DPS" required>
                            </td>
                            <td>
                                <select name="members[0][unsur]" class="form-select" required>
                                    <option value="">Pilih unsur...</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $unsurOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                        <option value="<?php echo e($opt); ?>" <?php if(old('members.0.unsur') === $opt): echo 'selected'; endif; ?>><?php echo e($opt); ?></option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="members[0][periode]" class="form-control" value="2024-2026" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger removeRowBtn" disabled title="Minimal 1 baris">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="<?php echo e(route('dps.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(() => {
    const madrasahSelect = document.getElementById('madrasah_id');
    const madrasahName = document.getElementById('madrasah_name');

    function syncMadrasahName() {
        const opt = madrasahSelect.options[madrasahSelect.selectedIndex];
        madrasahName.value = opt && opt.dataset ? (opt.dataset.name || '') : '';
    }
    madrasahSelect.addEventListener('change', syncMadrasahName);
    syncMadrasahName();

    const tableBody = document.querySelector('#membersTable tbody');
    const addRowBtn = document.getElementById('addRowBtn');
    const unsurOptionsHtml = <?php echo json_encode(
        collect($unsurOptions)->map(fn($o) => "<option value=\\\"".e($o)."\\\">".e($o)."</option>")->implode('')
    , 15, 512) ?>;

    function updateRemoveButtons() {
        const rows = tableBody.querySelectorAll('tr');
        rows.forEach((row, idx) => {
            const btn = row.querySelector('.removeRowBtn');
            if (!btn) return;
            btn.disabled = rows.length === 1;
            btn.title = (rows.length === 1) ? 'Minimal 1 baris' : 'Hapus baris';
        });
    }

    function renumberNames() {
        const rows = tableBody.querySelectorAll('tr');
        rows.forEach((row, idx) => {
            row.querySelectorAll('input[name^="members["]').forEach((input) => {
                input.name = input.name.replace(/members\\[\\d+\\]/, `members[${idx}]`);
            });
        });
    }

    addRowBtn.addEventListener('click', () => {
        const idx = tableBody.querySelectorAll('tr').length;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="members[${idx}][nama]" class="form-control" placeholder="Nama DPS" required></td>
            <td>
                <select name="members[${idx}][unsur]" class="form-select" required>
                    <option value="">Pilih unsur...</option>
                    ${unsurOptionsHtml}
                </select>
            </td>
            <td><input type="text" name="members[${idx}][periode]" class="form-control" value="2024-2026" required></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger removeRowBtn" title="Hapus baris">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(tr);
        updateRemoveButtons();
    });

    tableBody.addEventListener('click', (e) => {
        const btn = e.target.closest('.removeRowBtn');
        if (!btn) return;
        const row = btn.closest('tr');
        if (!row) return;
        row.remove();
        renumberNames();
        updateRemoveButtons();
    });

    updateRemoveButtons();
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/masterdata/dps/create.blade.php ENDPATH**/ ?>