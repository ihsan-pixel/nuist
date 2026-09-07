<?php $__env->startSection('title', 'Detail GTK'); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Master Data <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Detail GTK <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('css'); ?>
<style>
    .school-hero, .gtk-table-card, .wizard-step {
        border: 1px solid #e9edf4;
        border-radius: 1rem;
        background: #fff;
    }
    .wizard-progress { height: 8px; }
    .wizard-step { display:none; }
    .wizard-step.active { display:block; }
    .wizard-nav .step-dot {
        width: 34px; height: 34px; border-radius: 50%;
        display:flex; align-items:center; justify-content:center;
        background:#e2e8f0; color:#475569; font-weight:700;
    }
    .wizard-nav .step-dot.active { background:#0d6efd; color:#fff; }
    .gtk-table-card { overflow:hidden; }
    .gtk-table-card .table thead th { white-space:nowrap; font-size:.72rem; text-transform:uppercase; letter-spacing:.04em; color:#64748b; }
    .gtk-table-card .table tbody td { vertical-align:middle; }
    .gtk-data-table { min-width:3200px; font-size:.78rem; }
    .gtk-data-table th, .gtk-data-table td { padding:.7rem .8rem; }
    .gtk-data-table thead th[rowspan="2"] { background:#f8fafc; }
    .gtk-data-table .group-identity { background:#eff6ff; color:#1d4ed8; }
    .gtk-data-table .group-employment { background:#f0fdf4; color:#15803d; }
    .gtk-data-table .group-mgmp { background:#fff7ed; color:#c2410c; }
    .gtk-data-table tbody tr:first-child td { border-top-color:#cbd5e1; }
    .gtk-avatar { width:38px; height:38px; display:flex; align-items:center; justify-content:center; border-radius:12px; background:#eff6ff; color:#2563eb; font-weight:700; }
    .gtk-meta { color:#64748b; font-size:.78rem; }
    .gtk-completion { min-width:120px; }
    .gtk-completion .progress { height:5px; }
    .wizard-section-title { color:#0f172a; font-size:.95rem; font-weight:700; margin-bottom:1rem; }
    .wizard-section-title span { color:#64748b; font-size:.78rem; font-weight:400; }
    .form-label { font-size:.82rem; font-weight:600; color:#334155; }
    .required-mark { color:#dc3545; }
    .gtk-wizard-modal .modal-dialog {
        max-width: min(1140px, calc(100vw - 1rem));
        height: calc(100vh - 1rem);
        margin: .5rem auto;
    }
    .gtk-wizard-modal .modal-content {
        height: 100%;
        max-height: calc(100vh - 1rem);
    }
    .gtk-wizard-modal .modal-body {
        min-height: 0;
        overflow-y: auto !important;
    }
    @media (max-width: 576px) {
        .gtk-wizard-modal .modal-dialog {
            max-width: calc(100vw - .5rem);
            height: calc(100vh - .5rem);
            margin: .25rem auto;
        }
        .gtk-wizard-modal .modal-content {
            max-height: calc(100vh - .5rem);
        }
    }
</style>
<?php $__env->stopSection(); ?>

<div class="row mb-3">
    <div class="col-12">
        <div class="school-hero p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                <div class="d-flex gap-3">
                    <img src="<?php echo e($madrasah->logo && \Illuminate\Support\Facades\Storage::disk('public')->exists($madrasah->logo) ? asset('storage/' . $madrasah->logo) : asset('build/images/logo-light.png')); ?>" alt="<?php echo e($madrasah->name); ?>" class="rounded-3 border" style="width:72px;height:72px;object-fit:cover;">
                    <div>
                        <h4 class="mb-1"><?php echo e($madrasah->name); ?></h4>
                        <div class="text-muted"><?php echo e($madrasah->kabupaten ?: '-'); ?> · SCOD <?php echo e($madrasah->scod ?: '-'); ?></div>
                        <div class="text-muted small mt-1"><?php echo e($madrasah->alamat ?: 'Alamat belum tersedia'); ?></div>
                    </div>
                </div>
                <div class="text-md-end">
                    <div class="fs-3 fw-bold"><?php echo e($gtk->count()); ?></div>
                    <div class="text-muted">GTK terdaftar</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="gtk-table-card p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Daftar GTK</h5>
                    <div class="text-muted small">Kepala sekolah ditampilkan paling atas. Kolom action membuka modal pendataan.</div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>GTK</th>
                            <th>Kepegawaian</th>
                            <th>Kontak</th>
                            <th>Kelengkapan</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $gtk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <?php
                                $gtkPendataan = $user->gtkPendataan;
                                $mgmpNames = $user->mgmpMemberships
                                    ->map(fn ($membership) => $membership->mgmpGroup?->name)
                                    ->filter()
                                    ->implode(', ');
                                $gtkPayload = [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                    'gelar' => $user->gelar,
                                    'nuist_id' => $user->nuist_id,
                                    'madrasah_id' => $user->madrasah_id,
                                    'nik' => $gtkPendataan?->nik,
                                    'gol_darah' => $gtkPendataan?->gol_darah,
                                    'status_pernikahan' => $user->simfoni?->status_pernikahan,
                                    'email_aktif' => $gtkPendataan?->email_aktif ?: $user->email,
                                    'status_kepegawaian_id' => $user->status_kepegawaian_id,
                                    'tempat_lahir' => $user->tempat_lahir,
                                    'tanggal_lahir' => optional($user->tanggal_lahir)->format('Y-m-d'),
                                    'nuptk' => $user->nuptk,
                                    'nip' => $user->simfoni?->nipm ?: $user->nip,
                                    'kartanu' => $user->kartanu,
                                    'tmt' => optional($user->tmt)->format('Y-m-d'),
                                    'tmt_sk_pertama' => optional($gtkPendataan?->tmt_sk_pertama)->format('Y-m-d'),
                                    'tmt_sk_terakhir' => optional($gtkPendataan?->tmt_sk_terakhir)->format('Y-m-d') ?: optional($user->tmt)->format('Y-m-d'),
                                    'nomor_sk_pertama' => $gtkPendataan?->nomor_sk_pertama,
                                    'tahun_sk_pertama' => $gtkPendataan?->tahun_sk_pertama,
                                    'gaji_satpen' => $gtkPendataan?->gaji_satpen,
                                    'nomor_sertifikasi_pendidik' => $gtkPendataan?->nomor_sertifikasi_pendidik,
                                    'gaji_sertifikasi' => $gtkPendataan?->gaji_sertifikasi,
                                    'tunjangan_rerata_bulanan' => $gtkPendataan?->tunjangan_rerata_bulanan,
                                    'pendidikan_terakhir' => $user->pendidikan_terakhir,
                                    'tahun_lulus' => $user->tahun_lulus,
                                    'program_studi' => $user->program_studi,
                                    'ketugasan' => $user->ketugasan,
                                    'alamat' => $user->alamat,
                                    'no_hp' => $user->no_hp,
                                    'is_active' => (int) ($user->is_active ?? 1),
                                    'masa_kerja' => $user->masa_kerja,
                                    'jabatan' => $user->jabatan,
                                    'nama_mgmp' => $gtkPendataan?->nama_mgmp ?: $mgmpNames,
                                    'produk_kerja_kolaboratif' => $gtkPendataan?->produk_kerja_kolaboratif,
                                    'catatan_step_1' => $gtkPendataan?->catatan_step_1,
                                    'catatan_step_2' => $gtkPendataan?->catatan_step_2,
                                    'catatan_step_3' => $gtkPendataan?->catatan_step_3,
                                    'catatan_step_4' => $gtkPendataan?->catatan_step_4,
                                    'catatan_step_5' => $gtkPendataan?->catatan_step_5,
                                    'mengajar' => $user->mengajar,
                                ];
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="gtk-avatar"><?php echo e(strtoupper(substr($user->name, 0, 1))); ?></div>
                                        <div>
                                            <div class="fw-semibold"><?php echo e($user->name); ?> <?php echo e($user->gelar ? ', ' . $user->gelar : ''); ?></div>
                                            <div class="gtk-meta"><?php echo e($user->jabatan ?: ($user->ketugasan ?: 'Tenaga pendidik')); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><div><?php echo e($user->statusKepegawaian?->name ?: '-'); ?></div><div class="gtk-meta"><?php echo e($user->ketugasan ?: 'Belum diisi'); ?></div></td>
                                <td><div><?php echo e($user->no_hp ?: '-'); ?></div><div class="gtk-meta text-truncate" style="max-width:180px"><?php echo e($user->email ?: 'Email belum diisi'); ?></div></td>
                                <td class="gtk-completion">
                                    <?php
                                        // Catatan hanya sebagai informasi dan tidak dihitung sebagai kelengkapan.
                                        $completionFields = [$user->nuist_id, $user->name, $user->status_kepegawaian_id, $user->no_hp, $user->email, $user->pendidikan_terakhir, $gtkPendataan?->nik, $gtkPendataan?->tmt_sk_pertama];
                                        $completion = (int) round(collect($completionFields)->filter(fn ($value) => filled($value))->count() / count($completionFields) * 100);
                                    ?>
                                    <div class="d-flex justify-content-between gtk-meta mb-1"><span>Kelengkapan</span><strong><?php echo e($completion); ?>%</strong></div>
                                    <div class="progress"><div class="progress-bar <?php echo e($completion === 100 ? 'bg-success' : 'bg-primary'); ?>" style="width:<?php echo e($completion); ?>%"></div></div>
                                </td>
                                <td class="text-end">
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary open-gtk-wizard"
                                        data-user='<?php echo e(json_encode($gtkPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)); ?>'
                                        data-action="<?php echo e(route('pendataan-gtk.update', $user->id)); ?>"
                                    >
                                        <i class="bx bx-edit-alt me-1"></i> Lengkapi
                                    </button>
                                </td>
                            </tr>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade gtk-wizard-modal" id="gtkWizardModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <form id="gtkWizardForm" class="modal-content" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1">Pendataan GTK</h5>
                        <small class="text-muted" id="wizardSubtitle">Lengkapi data GTK secara bertahap.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span id="wizardStepLabel">Step 1 dari 5</span>
                            <span id="wizardStepPercent">20%</span>
                        </div>
                        <div class="progress wizard-progress">
                            <div class="progress-bar" id="wizardProgressBar" style="width:20%"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center wizard-nav mb-4">
                        <div class="text-center flex-fill"><div class="step-dot active" data-step-dot="1">1</div><small>Data GTK</small></div>
                        <div class="text-center flex-fill"><div class="step-dot" data-step-dot="2">2</div><small>Identitas</small></div>
                        <div class="text-center flex-fill"><div class="step-dot" data-step-dot="3">3</div><small>Kepegawaian</small></div>
                        <div class="text-center flex-fill"><div class="step-dot" data-step-dot="4">4</div><small>MGMP</small></div>
                        <div class="text-center flex-fill"><div class="step-dot" data-step-dot="5">5</div><small>Review</small></div>
                    </div>

                    <div class="wizard-step active" data-step="1">
                        <div class="wizard-section-title">Data GTK Utama <span>Data utama GTK sesuai urutan pendataan</span></div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label">SCOD</label><input class="form-control" value="<?php echo e($madrasah->scod ?: '-'); ?>" disabled></div>
                            <input type="hidden" name="madrasah_id" id="input_madrasah_id" value="<?php echo e($madrasah->id); ?>">
                            <div class="col-md-4"><label class="form-label">NUIST ID</label><input class="form-control" name="nuist_id" id="input_nuist_id"></div>
                            <div class="col-md-4"><label class="form-label">Nama dan Gelar <span class="required-mark">*</span></label><input class="form-control" name="name" id="input_name" required></div>
                            <div class="col-md-4"><label class="form-label">Gelar</label><input class="form-control" name="gelar" id="input_gelar"></div>
                            <div class="col-md-4"><label class="form-label">Asal Sekolah</label><input class="form-control" value="<?php echo e($madrasah->name); ?>" disabled></div>
                            <div class="col-md-4"><label class="form-label">Status Kepegawaian</label><select class="form-select" name="status_kepegawaian_id" id="input_status_kepegawaian_id"><option value="">- pilih -</option><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $statusKepegawaian; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?><option value="<?php echo e($status->id); ?>"><?php echo e($status->name); ?></option><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?></select></div>
                            <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input class="form-control" name="tempat_lahir" id="input_tempat_lahir"></div>
                            <div class="col-md-4"><label class="form-label">Tanggal Lahir</label><input type="date" class="form-control" name="tanggal_lahir" id="input_tanggal_lahir"></div>
                            <div class="col-md-4"><label class="form-label">NUPTK</label><input class="form-control" name="nuptk" id="input_nuptk"></div>
                            <div class="col-md-4"><label class="form-label">NIPM</label><input class="form-control" name="nip" id="input_nip"></div>
                            <div class="col-md-4"><label class="form-label">Kartanu</label><input class="form-control" name="kartanu" id="input_kartanu"></div>
                            <div class="col-md-4"><label class="form-label">TMT</label><input type="date" class="form-control" name="tmt" id="input_tmt"></div>
                            <div class="col-md-4"><label class="form-label">Pendidikan Terakhir</label><input class="form-control" name="pendidikan_terakhir" id="input_pendidikan_terakhir"></div>
                            <div class="col-md-4"><label class="form-label">Tahun Lulus</label><input class="form-control" name="tahun_lulus" id="input_tahun_lulus" maxlength="4"></div>
                            <div class="col-md-6"><label class="form-label">Program Studi</label><input class="form-control" name="program_studi" id="input_program_studi"></div>
                            <div class="col-12"><label class="form-label">Catatan Step 1</label><textarea class="form-control" rows="3" name="catatan_step_1" id="input_catatan_step_1" placeholder="Tambahkan catatan untuk data GTK utama"></textarea></div>
                        </div>
                    </div>

                    <div class="wizard-step" data-step="2">
                        <div class="wizard-section-title">Data Identitas GTK <span>Data identitas tambahan dan kontak aktif</span></div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label">NIK</label><input class="form-control" name="nik" id="input_nik" inputmode="numeric"></div>
                            <div class="col-md-4"><label class="form-label">Golongan Darah</label><select class="form-select" name="gol_darah" id="input_gol_darah"><option value="">- pilih -</option><option value="A">A</option><option value="B">B</option><option value="AB">AB</option><option value="O">O</option></select></div>
                            <div class="col-md-4"><label class="form-label">Status Perkawinan</label><select class="form-select" name="status_pernikahan" id="input_status_pernikahan"><option value="">- pilih -</option><option value="Belum Kawin">Belum Kawin</option><option value="Kawin">Kawin</option><option value="Cerai Hidup">Cerai Hidup</option><option value="Cerai Mati">Cerai Mati</option></select></div>
                            <div class="col-md-4"><label class="form-label">No HP</label><input class="form-control" name="no_hp" id="input_no_hp"></div>
                            <div class="col-md-6"><label class="form-label">Email Aktif</label><input type="email" class="form-control" name="email_aktif" id="input_email_aktif"></div>
                            <div class="col-12"><label class="form-label">Alamat</label><textarea class="form-control" rows="3" name="alamat" id="input_alamat"></textarea></div>
                            <div class="col-12"><label class="form-label">Catatan Step 2</label><textarea class="form-control" rows="3" name="catatan_step_2" id="input_catatan_step_2" placeholder="Tambahkan catatan untuk data identitas"></textarea></div>
                        </div>
                    </div>

                    <div class="wizard-step" data-step="3">
                        <div class="wizard-section-title">Data Kepegawaian <span>Riwayat SK, masa kerja, jabatan, dan pendapatan</span></div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label">TMT SK 1</label><input type="date" class="form-control" name="tmt_sk_pertama" id="input_tmt_sk_pertama"></div>
                            <div class="col-md-4"><label class="form-label">TMT SK Terakhir</label><input type="date" class="form-control" name="tmt_sk_terakhir" id="input_tmt_sk_terakhir"></div>
                            <div class="col-md-4"><label class="form-label">Nomor SK Pertama</label><input class="form-control" name="nomor_sk_pertama" id="input_nomor_sk_pertama"></div>
                            <div class="col-md-4"><label class="form-label">Tahun SK Pertama</label><input type="number" class="form-control" name="tahun_sk_pertama" id="input_tahun_sk_pertama" min="1900" max="2100"></div>
                            <div class="col-md-4"><label class="form-label">No. Sertifikasi Pendidik</label><input class="form-control" name="nomor_sertifikasi_pendidik" id="input_nomor_sertifikasi_pendidik"></div>
                            <div class="col-12"><label class="form-label">Keterangan SK</label><textarea class="form-control" rows="3" name="keterangan_sk" id="input_keterangan_sk" placeholder="Tuliskan keterangan atau catatan terkait SK"></textarea></div>
                            <div class="col-md-4"><label class="form-label">Masa Kerja</label><input class="form-control" name="masa_kerja" id="input_masa_kerja"></div>
                            <div class="col-md-4"><label class="form-label">Jabatan</label><input class="form-control" name="jabatan" id="input_jabatan"></div>
                            <div class="col-md-4"><label class="form-label">Gaji dari Satpen (Rp)</label><input type="number" class="form-control" name="gaji_satpen" id="input_gaji_satpen" min="0"></div>
                            <div class="col-md-4"><label class="form-label">Ketugasan</label><input class="form-control" name="ketugasan" id="input_ketugasan"></div>
                            <div class="col-12"><label class="form-label">Catatan Step 3</label><textarea class="form-control" rows="3" name="catatan_step_3" id="input_catatan_step_3" placeholder="Tambahkan catatan untuk data kepegawaian"></textarea></div>
                        </div>
                    </div>

                    <div class="wizard-step" data-step="4">
                        <div class="wizard-section-title">MGMP <span>Sertifikasi, MGMP, dan produk kerja kolaboratif</span></div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label">Sertifikasi (Rp)</label><input type="number" class="form-control" name="gaji_sertifikasi" id="input_gaji_sertifikasi" min="0"></div>
                            <div class="col-md-4"><label class="form-label">Tambahan Penghasilan / Bulan (Rp)</label><input type="number" class="form-control" name="tunjangan_rerata_bulanan" id="input_tunjangan_rerata_bulanan" min="0"></div>
                            <div class="col-md-6"><label class="form-label">Nama MGMP</label><input class="form-control" name="nama_mgmp" id="input_nama_mgmp"></div>
                            <div class="col-12"><label class="form-label">Produk Kerja Kolaboratif</label><textarea class="form-control" rows="3" name="produk_kerja_kolaboratif" id="input_produk_kerja_kolaboratif" placeholder="Contoh: modul ajar, perangkat pembelajaran, penelitian, atau karya bersama"></textarea></div>
                            <div class="col-12"><div class="form-text">Nama MGMP yang sudah terhubung melalui relasi aplikasi akan ditampilkan sebagai nilai awal dan masih dapat dilengkapi.</div></div>
                            <div class="col-md-6">
                                <div class="row g-3">
                                    <div class="col-12"><label class="form-label">Status Keaktifan</label><select class="form-select" name="is_active" id="input_is_active"><option value="1">Aktif</option><option value="0">Tidak aktif</option></select></div>
                                    <div class="col-12"><label class="form-label">Mengajar</label><input class="form-control" name="mengajar" id="input_mengajar"></div>
                                </div>
                            </div>
                            <div class="col-md-6"><div class="alert alert-light border h-100 mb-0"><div class="fw-semibold mb-2">Catatan pencocokan</div><div class="small text-muted">Gunakan NUIST ID, NIK, NUPTK, NIPM/NIP, dan email aktif untuk mencocokkan data GTK dengan data yang sudah ada di aplikasi.</div></div></div>
                            <div class="col-12"><label class="form-label">Catatan Step 4</label><textarea class="form-control" rows="3" name="catatan_step_4" id="input_catatan_step_4" placeholder="Tambahkan catatan untuk MGMP"></textarea></div>
                        </div>
                    </div>

                    <div class="wizard-step" data-step="5">
                        <div class="alert alert-info">Periksa kembali data sebelum disimpan. Jika ada field yang belum lengkap, silakan kembali ke step terkait.</div>
                        <div class="row g-3 small">
                            <div class="col-md-6"><strong>Nama:</strong> <span id="review_name"></span></div>
                            <div class="col-md-6"><strong>NUIST ID:</strong> <span id="review_nuist_id"></span></div>
                            <div class="col-md-6"><strong>NIK:</strong> <span id="review_nik"></span></div>
                            <div class="col-md-6"><strong>Email Aktif:</strong> <span id="review_email"></span></div>
                            <div class="col-md-6"><strong>Ketugasan:</strong> <span id="review_ketugasan"></span></div>
                            <div class="col-md-6"><strong>Status Kepegawaian:</strong> <span id="review_status"></span></div>
                            <div class="col-md-6"><strong>Pendidikan Terakhir:</strong> <span id="review_pendidikan"></span></div>
                            <div class="col-md-6"><strong>Program Studi:</strong> <span id="review_program_studi"></span></div>
                            <div class="col-md-6"><strong>Nama MGMP:</strong> <span id="review_mgmp"></span></div>
                            <div class="col-12"><strong>Produk Kerja Kolaboratif:</strong><div id="review_produk" class="text-muted mt-1"></div></div>
                            <div class="col-12"><hr class="my-1"><label class="form-label">Catatan Step 5</label><textarea class="form-control" rows="3" name="catatan_step_5" id="input_catatan_step_5" placeholder="Tambahkan catatan akhir sebelum menyimpan"></textarea></div>
                            <div class="col-12"><strong>Catatan per Step:</strong><div id="review_catatan" class="text-muted mt-1"></div></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" id="wizardBackBtn">Kembali</button>
                    <div class="ms-auto d-flex gap-2">
                        <button type="button" class="btn btn-primary" id="wizardNextBtn">Lanjut</button>
                        <button type="submit" class="btn btn-success d-none" id="wizardSaveBtn">Simpan</button>
                    </div>
                </div>
            </form>
    </div>
</div>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.all.min.js')); ?>"></script>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: <?php echo json_encode(session('success'), 15, 512) ?>,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#0d6efd',
                timer: 3200,
                timerProgressBar: true,
            });
        });
    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Data belum tersimpan',
                html: <?php echo json_encode(implode('<br>', $errors->all()), 512) ?>,
                confirmButtonText: 'Periksa kembali',
                confirmButtonColor: '#dc3545',
            });
        });
    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<script>
(() => {
    const modal = document.getElementById('gtkWizardModal');
    const form = document.getElementById('gtkWizardForm');
    const steps = [...document.querySelectorAll('.wizard-step')];
    const dots = [...document.querySelectorAll('[data-step-dot]')];
    const nextBtn = document.getElementById('wizardNextBtn');
    const backBtn = document.getElementById('wizardBackBtn');
    const saveBtn = document.getElementById('wizardSaveBtn');
    const label = document.getElementById('wizardStepLabel');
    const percent = document.getElementById('wizardStepPercent');
    const progress = document.getElementById('wizardProgressBar');
    let step = 1;

    const fields = ['name','gelar','nuist_id','nik','gol_darah','status_pernikahan','email_aktif','tempat_lahir','tanggal_lahir','status_kepegawaian_id','ketugasan','jabatan','tmt','tmt_sk_pertama','tmt_sk_terakhir','nomor_sk_pertama','tahun_sk_pertama','keterangan_sk','masa_kerja','nuptk','nip','kartanu','is_active','gaji_satpen','nomor_sertifikasi_pendidik','gaji_sertifikasi','tunjangan_rerata_bulanan','pendidikan_terakhir','tahun_lulus','program_studi','alamat','no_hp','nama_mgmp','produk_kerja_kolaboratif','catatan_step_1','catatan_step_2','catatan_step_3','catatan_step_4','catatan_step_5','mengajar'];

    function sync(stepValue) {
        step = stepValue;
        steps.forEach(el => el.classList.toggle('active', Number(el.dataset.step) === step));
        dots.forEach(el => el.classList.toggle('active', Number(el.dataset.stepDot) <= step));
        const pct = Math.round((step / 5) * 100);
        progress.style.width = pct + '%';
        percent.textContent = pct + '%';
        label.textContent = `Step ${step} dari 5`;
        backBtn.disabled = step === 1;
        nextBtn.classList.toggle('d-none', step === 5);
        saveBtn.classList.toggle('d-none', step !== 5);
        if (step === 5) fillReview();
    }

    function fillReview() {
        document.getElementById('review_name').textContent = document.getElementById('input_name').value || '-';
        document.getElementById('review_nuist_id').textContent = document.getElementById('input_nuist_id').value || '-';
        document.getElementById('review_ketugasan').textContent = document.getElementById('input_ketugasan').value || '-';
        document.getElementById('review_status').textContent = document.getElementById('input_status_kepegawaian_id').selectedOptions[0]?.text || '-';
        document.getElementById('review_pendidikan').textContent = document.getElementById('input_pendidikan_terakhir').value || '-';
        document.getElementById('review_program_studi').textContent = document.getElementById('input_program_studi').value || '-';
        document.getElementById('review_nik').textContent = document.getElementById('input_nik').value || '-';
        document.getElementById('review_email').textContent = document.getElementById('input_email_aktif').value || '-';
        document.getElementById('review_mgmp').textContent = document.getElementById('input_nama_mgmp').value || '-';
        document.getElementById('review_produk').textContent = document.getElementById('input_produk_kerja_kolaboratif').value || '-';
        document.getElementById('review_catatan').innerHTML = [1, 2, 3, 4, 5]
            .map(index => `<div><strong>Step ${index}:</strong> ${escapeHtml(document.getElementById('input_catatan_step_' + index).value || '-')}</div>`)
            .join('');
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }

    document.querySelectorAll('.open-gtk-wizard').forEach(btn => {
        btn.addEventListener('click', () => {
            const user = JSON.parse(btn.dataset.user);
            form.action = btn.dataset.action;
            fields.forEach(field => {
                const el = document.getElementById('input_' + field);
                if (!el) return;
                el.value = user[field] ?? '';
            });
            sync(1);
            new bootstrap.Modal(modal).show();
        });
    });

    nextBtn.addEventListener('click', () => {
        if (step < 5) sync(step + 1);
    });
    backBtn.addEventListener('click', () => {
        if (step > 1) sync(step - 1);
    });
    modal.addEventListener('hidden.bs.modal', () => sync(1));
})();
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/masterdata/pendataan-gtk/show.blade.php ENDPATH**/ ?>