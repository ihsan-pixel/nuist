<?php $__env->startSection('title'); ?>Deteksi Fake Location - Sistem Informasi Digital LP. Ma'arif NU PWNU DIY@endsection

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Dashboard <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Deteksi Fake Location <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-map-pin text-danger me-2"></i>
                    Deteksi Presensi dengan Fake Location
                </h4>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('fake-location.index')); ?>" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date"
                               value="<?php echo e($selectedDate->format('Y-m-d')); ?>" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <label for="kabupaten" class="form-label">Kabupaten</label>
                        <select class="form-control" id="kabupaten" name="kabupaten" onchange="this.form.submit()">
                            <option value="">Semua Kabupaten</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $kabupatenList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($kab); ?>" <?php echo e($selectedKabupaten == $kab ? 'selected' : ''); ?>>
                                    <?php echo e($kab); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="madrasah_id" class="form-label">Madrasah</label>
                        <select class="form-control" id="madrasah_id" name="madrasah_id" onchange="this.form.submit()">
                            <option value="">Semua Madrasah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($madrasah->id); ?>" <?php echo e($selectedMadrasah == $madrasah->id ? 'selected' : ''); ?>>
                                    <?php echo e($madrasah->name); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-search me-1"></i>Filter
                        </button>
                        <a href="<?php echo e(route('fake-location.index')); ?>" class="btn btn-secondary">
                            <i class="bx bx-reset me-1"></i>Reset
                        </a>
                    </div>
                </form>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><?php echo e(count($fakeLocationData)); ?></h5>
                                        <p class="mb-0">Presensi Mencurigakan</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-danger rounded-circle">
                                            <i class="bx bx-error font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><?php echo e(count(array_filter($fakeLocationData, fn($item) => $item['analysis']['severity'] >= 3))); ?></h5>
                                        <p class="mb-0">Tingkat Tinggi</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-warning rounded-circle">
                                            <i class="bx bx-error-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><?php echo e(count(array_filter($fakeLocationData, fn($item) => $item['analysis']['severity'] >= 2 && $item['analysis']['severity'] < 3))); ?></h5>
                                        <p class="mb-0">Tingkat Sedang</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-info rounded-circle">
                                            <i class="bx bx-info-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-secondary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><?php echo e(count(array_filter($fakeLocationData, fn($item) => $item['analysis']['severity'] >= 1 && $item['analysis']['severity'] < 2))); ?></h5>
                                        <p class="mb-0">Tingkat Rendah</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-secondary rounded-circle">
                                            <i class="bx bx-low-vision font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><?php echo e(count(array_filter($fakeLocationData, fn($item) => $item['analysis']['fake_location_masuk']))); ?></h5>
                                        <p class="mb-0">Fake Masuk</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-primary rounded-circle">
                                            <i class="bx bx-log-in font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-dark text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1"><?php echo e(count(array_filter($fakeLocationData, fn($item) => $item['analysis']['fake_location_keluar']))); ?></h5>
                                        <p class="mb-0">Fake Keluar</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-white text-dark rounded-circle">
                                            <i class="bx bx-log-out font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table id="fake-location-table" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Guru</th>
                                <th>Madrasah</th>
                                <th>Kabupaten</th>
                                <th>Tanggal</th>
                                <th>Waktu Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Koordinat</th>
                                <th>Lokasi</th>
                                <th>Tingkat Kecurigaan</th>
                                <th>Detail Masalah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $fakeLocationData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['presensi']->user->avatar): ?>
                                                <img src="<?php echo e(asset('storage/app/public/' . $item['presensi']->user->avatar)); ?>"
                                                     alt="Avatar" class="avatar-xs rounded-circle me-2">
                                            <?php else: ?>
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <?php echo e(strtoupper(substr($item['presensi']->user->name, 0, 1))); ?>

                                                    </span>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?php echo e($item['presensi']->user->name); ?></h6>
                                                <small class="text-muted"><?php echo e($item['presensi']->statusKepegawaian->name ?? '-'); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e($item['presensi']->user->madrasah->name ?? '-'); ?></td>
                                    <td><?php echo e($item['presensi']->user->madrasah->kabupaten ?? '-'); ?></td>
                                    <td><?php echo e($item['presensi']->tanggal->format('d/m/Y')); ?></td>
                                    <td><?php echo e($item['presensi']->waktu_masuk ? $item['presensi']->waktu_masuk->format('H:i') : '-'); ?></td>
                                    <td><?php echo e($item['presensi']->waktu_keluar ? $item['presensi']->waktu_keluar->format('H:i') : '-'); ?></td>
                                    <td>
                                        <small>
                                            <?php echo e(number_format($item['presensi']->latitude, 6)); ?>,
                                            <?php echo e(number_format($item['presensi']->longitude, 6)); ?>

                                        </small>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['presensi']->user->madrasah && $item['presensi']->user->madrasah->latitude): ?>
                                            <br>
                                            <small class="text-muted">
                                                Jarak: <?php echo e(number_format($item['analysis']['distance'] ?? 0, 2)); ?> km
                                            </small>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['presensi']->accuracy): ?>
                                            <br>
                                            <small class="text-muted">
                                                Akurasi: <?php echo e(number_format($item['presensi']->accuracy, 1)); ?> m
                                            </small>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['presensi']->speed): ?>
                                            <br>
                                            <small class="text-muted">
                                                Kecepatan: <?php echo e(number_format($item['presensi']->speed * 3.6, 1)); ?> km/h
                                            </small>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark"><?php echo e($item['presensi']->lokasi ?? '-'); ?></span>
                                    </td>
                                    <td>
                                        <?php
                                            $severityClass = match($item['analysis']['severity_label']) {
                                                'Sangat Tinggi' => 'bg-danger',
                                                'Tinggi' => 'bg-warning',
                                                'Sedang' => 'bg-info',
                                                'Rendah' => 'bg-secondary',
                                                default => 'bg-light'
                                            };
                                        ?>
                                        <span class="badge <?php echo e($severityClass); ?> text-white">
                                            <?php echo e($item['analysis']['severity_label']); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <ul class="list-unstyled mb-0">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $item['analysis']['issues']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                                <li><small class="text-danger">• <?php echo e($issue); ?></small></li>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                                        </ul>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="viewDetails(<?php echo e($item['presensi']->id); ?>)">
                                                <i class="bx bx-show"></i> Detail
                                            </button>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['presensi']->user->madrasah && $item['presensi']->user->madrasah->map_link): ?>
                                                <a href="<?php echo e($item['presensi']->user->madrasah->map_link); ?>"
                                                   target="_blank" class="btn btn-sm btn-outline-info">
                                                    <i class="bx bx-map"></i> Map
                                                </a>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['analysis']['fake_location_masuk'] || $item['analysis']['fake_location_keluar']): ?>
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-warning dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bx bx-error"></i> Status
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['analysis']['fake_location_masuk']): ?>
                                                            <li><span class="dropdown-item-text text-danger"><i class="bx bx-log-in"></i> Fake Location Masuk</span></li>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['analysis']['fake_location_keluar']): ?>
                                                            <li><span class="dropdown-item-text text-danger"><i class="bx bx-log-out"></i> Fake Location Keluar</span></li>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($fakeLocationData) == 0): ?>
                    <div class="text-center py-5">
                        <i class="bx bx-check-circle text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Tidak Ada Presensi Mencurigakan</h4>
                        <p class="text-muted">Semua presensi pada tanggal <?php echo e($selectedDate->format('d/m/Y')); ?> terdeteksi valid.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    $('#fake-location-table').DataTable({
        "pageLength": 25,
        "order": [[ 8, "desc" ], [ 0, "asc" ]], // Sort by severity desc, then by No asc
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
        },
        "columnDefs": [
            { "orderable": false, "targets": [10] } // Disable sorting on action column
        ]
    });
});

function viewDetails(presensiId) {
    // Load presensi details via AJAX
    $.get('<?php echo e(url("/presensi-admin/detail")); ?>/' + presensiId)
        .done(function(data) {
            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Guru</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Nama:</strong></td><td>${data.user.name}</td></tr>
                            <tr><td><strong>Email:</strong></td><td>${data.user.email}</td></tr>
                            <tr><td><strong>Madrasah:</strong></td><td>${data.user.madrasah}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>${data.user.status_kepegawaian}</td></tr>
                            <tr><td><strong>NIP:</strong></td><td>${data.user.nip || '-'}</td></tr>
                            <tr><td><strong>NUPTK:</strong></td><td>${data.user.nuptk || '-'}</td></tr>
                        </table>

                        <h6>Data Lokasi & Perangkat</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Koordinat:</strong></td><td>${data.presensi.latitude}, ${data.presensi.longitude}</td></tr>
                            <tr><td><strong>Akurasi GPS:</strong></td><td>${data.presensi.accuracy ? data.presensi.accuracy + ' m' : '-'}</td></tr>
                            <tr><td><strong>Kecepatan:</strong></td><td>${data.presensi.speed ? (data.presensi.speed * 3.6).toFixed(1) + ' km/h' : '-'}</td></tr>
                            <tr><td><strong>Ketinggian:</strong></td><td>${data.presensi.altitude ? data.presensi.altitude + ' m' : '-'}</td></tr>
                            <tr><td><strong>Perangkat:</strong></td><td>${data.presensi.device_info || '-'}</td></tr>
                            <tr><td><strong>Status Fake Location:</strong></td><td><span class="badge bg-${data.presensi.is_fake_location ? 'danger' : 'success'}">${data.presensi.is_fake_location ? 'Terdeteksi' : 'Valid'}</span></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Data Lokasi & Perangkat (Keluar)</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Koordinat Keluar:</strong></td><td>${data.presensi.latitude_keluar ? data.presensi.latitude_keluar + ', ' + data.presensi.longitude_keluar : '-'}</td></tr>
                            <tr><td><strong>Akurasi GPS Keluar:</strong></td><td>${data.presensi.accuracy_keluar ? data.presensi.accuracy_keluar + ' m' : '-'}</td></tr>
                            <tr><td><strong>Kecepatan Keluar:</strong></td><td>${data.presensi.speed_keluar ? (data.presensi.speed_keluar * 3.6).toFixed(1) + ' km/h' : '-'}</td></tr>
                            <tr><td><strong>Ketinggian Keluar:</strong></td><td>${data.presensi.altitude_keluar ? data.presensi.altitude_keluar + ' m' : '-'}</td></tr>
                            <tr><td><strong>Status Fake Location Keluar:</strong></td><td><span class="badge bg-${data.presensi.is_fake_location_keluar ? 'danger' : 'success'}">${data.presensi.is_fake_location_keluar ? 'Terdeteksi' : 'Valid'}</span></td></tr>
                        </table>

                        <h6>Riwayat Presensi (10 terakhir)</h6>
                        <div style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
            `;

            data.presensi_history.forEach(function(presensi) {
                html += `
                    <tr>
                        <td>${presensi.tanggal}</td>
                        <td>${presensi.waktu_masuk || '-'}</td>
                        <td>${presensi.waktu_keluar || '-'}</td>
                        <td><span class="badge bg-${presensi.status === 'hadir' ? 'success' : 'warning'}">${presensi.status}</span></td>
                    </tr>
                `;
            });

            html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            $('#detailModalBody').html(html);
            $('#detailModal').modal('show');
        })
        .fail(function() {
            Swal.fire('Error', 'Gagal memuat detail presensi', 'error');
        });
}

// Helper function for distance calculation (same as in controller)
function calculateDistance(lat1, lon1, lat2, lon2) {
    const earthRadius = 6371;
    const latDelta = (lat2 - lat1) * Math.PI / 180;
    const lonDelta = (lon2 - lon1) * Math.PI / 180;

    const a = Math.sin(latDelta/2) * Math.sin(latDelta/2) +
              Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
              Math.sin(lonDelta/2) * Math.sin(lonDelta/2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return earthRadius * c;
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/fake-location/index.blade.php ENDPATH**/ ?>