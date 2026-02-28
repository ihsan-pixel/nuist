<?php $__env->startSection('title', 'Upload Tugas Peserta'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h4>Upload Tugas Peserta</h4>
            <p class="text-muted">Pantau pengumpulan tugas berdasarkan Materi ID. Filter berdasarkan materi untuk melihat status pengumpulan.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#downloadTugasModal">
                            <i class="fas fa-file-download me-1"></i> Download Semua File
                        </button>
                    </div>
                    
                    <div class="mb-3">
                        <div class="btn-group" role="group" aria-label="Area filter">
                            <a href="<?php echo e(route('instumen-talenta.upload-tugas')); ?>" class="btn btn-sm <?php echo e(empty($selectedArea) ? 'btn-primary' : 'btn-outline-primary'); ?>">Semua Area</a>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($areas) && $areas->isNotEmpty()): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <a href="<?php echo e(route('instumen-talenta.upload-tugas', ['area' => $area])); ?>" class="btn btn-sm <?php echo e(($selectedArea === $area) ? 'btn-primary' : 'btn-outline-primary'); ?>"><?php echo e($area); ?></a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($tugas) && $tugas->isNotEmpty()): ?>
                        <?php
                            // Group tasks by jenis_tugas, default label 'Umum' when empty
                            $groups = $tugas->groupBy(function($item) {
                                return $item->jenis_tugas ?? 'Umum';
                            });
                        ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="mb-4">
                                <h5 class="mb-2">Jenis Tugas: <?php echo e($jenis); ?> <small class="text-muted">(<?php echo e($items->count()); ?>)</small></h5>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Peserta (User)</th>
                                                <th>Kelompok</th>
                                                <th>Area</th>
                                                <th>File / Data</th>
                                                <th>Submitted At</th>
                                                <th>Nilai</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <tr>
                                                    <td><?php echo e($item->id); ?></td>
                                                    <td>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->user): ?>
                                                            <?php echo e($item->user->name); ?><br>
                                                            <small class="text-muted"><?php echo e($item->user->email); ?></small>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($item->kelompok->nama_kelompok ?? '-'); ?></td>
                                                    <td><?php echo e($item->area ?? '-'); ?></td>
                                                    <td>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($item->file_path)): ?>
                                                            <a href="<?php echo e(asset($item->file_path)); ?>" target="_blank">Lihat File</a>
                                                        <?php elseif(!empty($item->data)): ?>
                                                            <pre style="white-space:pre-wrap;max-width:350px"><?php echo e(json_encode($item->data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)); ?></pre>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </td>
                                                    <td><?php echo e(optional($item->submitted_at)->format('Y-m-d H:i') ?? '-'); ?></td>
                                                    <td>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->nilai && $item->nilai->isNotEmpty()): ?>
                                                            <?php echo e($item->nilai->pluck('nilai')->filter()->first() ?? '-'); ?>

                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </td>
                                                    
                                                </tr>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info">Belum ada data pengumpulan tugas.</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Pilihan Download Semua File -->
<div class="modal fade" id="downloadTugasModal" tabindex="-1" aria-labelledby="downloadTugasModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
                    <form id="downloadTugasForm" method="POST" action="<?php echo e(route('instumen-talenta.download-tugas')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="downloadTugasModalLabel">Download Semua File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="areaSelect" class="form-label">Pilih Materi / Area (opsional)</label>
                        <select id="areaSelect" name="area" class="form-select">
                            <option value="">-- Semua Area --</option>
                            <?php if(isset($areas)): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                    <option value="<?php echo e($area); ?>"><?php echo e($area); ?></option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jenisSelect" class="form-label">Pilih Jenis Tugas</label>
                        <select id="jenisSelect" name="jenis_tugas" class="form-select" required>
                            <option value="on_site">Tugas Onsite</option>
                            <option value="terstruktur">Tugas Terstruktur</option>
                            <option value="kelompok">Tugas Kelompok</option>
                        </select>
                    </div>
                </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Download</button>
                        </div>
            </form>
        </div>
    </div>
</div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('downloadTugasForm');
            const modalEl = document.getElementById('downloadTugasModal');
            const bootstrapModal = modalEl ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const submitBtn = form.querySelector('button[type=submit]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Memproses...';

                const fd = new FormData(form);

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });

                    const contentType = res.headers.get('content-type') || '';

                    if (res.ok && contentType.indexOf('application/pdf') === 0) {
                        const blob = await res.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        // try to get filename from disposition
                        const disp = res.headers.get('content-disposition') || '';
                        let filename = 'gabungan_tugas.pdf';
                        const m = /filename\*=UTF-8''([^;\n]+)/i.exec(disp) || /filename="?([^";\n]+)"?/i.exec(disp);
                        if (m && m[1]) filename = decodeURIComponent(m[1]);
                        a.download = filename;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                        if (bootstrapModal) bootstrapModal.hide();
                    } else {
                        // read text/html or error message
                        const text = await res.text();
                        // show error inside modal or alert
                        let msg = text;
                        // try to extract plain text message if server returned plain text
                        if (contentType.indexOf('text/plain') === 0) {
                            msg = text;
                        } else {
                            // attempt to parse common flash structure
                            const tmp = document.createElement('div');
                            tmp.innerHTML = text;
                            msg = tmp.innerText || text;
                        }
                        alert('Terjadi kesalahan:\n' + msg);
                    }
                } catch (err) {
                    alert('Gagal menghubungi server: ' + err.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Download';
                }
            });
        });
        </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/instumen-talenta/upload-tugas.blade.php ENDPATH**/ ?>