<div class="pendaftar-detail">
    <div class="row">
        <div class="col-lg-8">
            <!-- Header Info -->
            <div class="card border-primary mb-3">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-account-card-details me-2 fs-5"></i>
                        <div>
                            <h5 class="card-title mb-0"><?php echo e($pendaftar->nama_lengkap); ?></h5>
                            <small>NISN: <?php echo e($pendaftar->nisn ?? '-'); ?> | No. Pendaftaran: <?php echo e($pendaftar->nomor_pendaftaran ?? '-'); ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Timeline Status -->
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="timeline-status d-flex justify-content-center align-items-center flex-wrap mb-2">
                                <!-- Step 1: Data Dikirim -->
                                <div class="timeline-step text-center mx-2">
                                    <div class="timeline-icon bg-primary text-white mb-1"><i class="mdi mdi-upload"></i></div>
                                    <div class="small">Data Dikirim</div>
                                    <div class="timeline-dot <?php echo e($pendaftar->created_at ? 'active' : ''); ?>"></div>
                                </div>
                                <div class="timeline-line"></div>
                                <!-- Step 2: Diverifikasi -->
                                <div class="timeline-step text-center mx-2">
                                    <div class="timeline-icon <?php echo e($pendaftar->status == 'verifikasi' || $pendaftar->status == 'lulus' || $pendaftar->status == 'tidak_lulus' ? 'bg-info text-white' : 'bg-light text-secondary'); ?> mb-1"><i class="mdi mdi-magnify"></i></div>
                                    <div class="small">Diverifikasi</div>
                                    <div class="timeline-dot <?php echo e($pendaftar->status == 'verifikasi' || $pendaftar->status == 'lulus' || $pendaftar->status == 'tidak_lulus' ? 'active' : ''); ?>"></div>
                                </div>
                                <div class="timeline-line"></div>
                                <!-- Step 3: Hasil Seleksi -->
                                <div class="timeline-step text-center mx-2">
                                    <div class="timeline-icon <?php echo e($pendaftar->status == 'lulus' ? 'bg-success text-white' : ($pendaftar->status == 'tidak_lulus' ? 'bg-danger text-white' : 'bg-light text-secondary')); ?> mb-1">
                                        <i class="mdi mdi-<?php echo e($pendaftar->status == 'lulus' ? 'check-circle' : ($pendaftar->status == 'tidak_lulus' ? 'close-circle' : 'clock-outline')); ?>"></i>
                                    </div>
                                    <div class="small"><?php if($pendaftar->status == 'lulus'): ?> Lulus <?php elseif($pendaftar->status == 'tidak_lulus'): ?> Tidak Lulus <?php else: ?> Seleksi <?php endif; ?></div>
                                    <div class="timeline-dot <?php echo e($pendaftar->status == 'lulus' || $pendaftar->status == 'tidak_lulus' ? 'active' : ''); ?>"></div>
                                </div>
                                <div class="timeline-line"></div>
                                <!-- Step 4: Pengumuman Daftar Ulang -->
                                <div class="timeline-step text-center mx-2">
                                    <div class="timeline-icon <?php echo e($pendaftar->status == 'lulus' ? 'bg-warning text-dark' : 'bg-light text-secondary'); ?> mb-1"><i class="mdi mdi-bullhorn"></i></div>
                                    <div class="small">Pengumuman Daftar Ulang</div>
                                    <div class="timeline-dot <?php echo e($pendaftar->status == 'lulus' ? 'active' : ''); ?>"></div>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <span class="badge fs-6 px-3 py-2 bg-<?php echo e($pendaftar->status == 'lulus' ? 'success' : ($pendaftar->status == 'tidak_lulus' ? 'danger' : ($pendaftar->status == 'verifikasi' ? 'info' : 'warning'))); ?>">
                                    <i class="mdi mdi-<?php echo e($pendaftar->status == 'lulus' ? 'check-circle' : ($pendaftar->status == 'tidak_lulus' ? 'close-circle' : ($pendaftar->status == 'verifikasi' ? 'magnify' : 'clock-outline'))); ?> me-1"></i>
                                    <?php if($pendaftar->status == 'lulus'): ?>
                                        Lulus Seleksi
                                    <?php elseif($pendaftar->status == 'tidak_lulus'): ?>
                                        Tidak Lulus
                                    <?php elseif($pendaftar->status == 'verifikasi'): ?>
                                        Dalam Verifikasi
                                    <?php else: ?>
                                        Menunggu Verifikasi
                                    <?php endif; ?>
                                </span>
                                <?php if($pendaftar->ranking): ?>
                                    <span class="badge bg-secondary ms-2">Ranking #<?php echo e($pendaftar->ranking); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Pribadi & Sekolah -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">
                                <i class="mdi mdi-account me-1"></i>Data Pribadi
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-4"><strong>Nama Lengkap</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->nama_lengkap); ?></div>

                                <div class="col-sm-4"><strong>NIK</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->nik ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>NISN</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->nisn ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Tempat Lahir</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->tempat_lahir ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Tanggal Lahir</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->tanggal_lahir ? $pendaftar->tanggal_lahir->format('d/m/Y') : '-'); ?></div>

                                <div class="col-sm-4"><strong>Jenis Kelamin</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : ($pendaftar->jenis_kelamin == 'P' ? 'Perempuan' : '-')); ?></div>

                                <div class="col-sm-4"><strong>Agama</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->agama ?? '-'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">
                                <i class="mdi mdi-school me-1"></i>Data Sekolah
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-4"><strong>Asal Sekolah</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->asal_sekolah ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>NPSN</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->npsn_sekolah_asal ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Tahun Lulus</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->tahun_lulus ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Rata-rata Nilai</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->rata_rata_nilai_raport ?? $pendaftar->nilai ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Jurusan</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->jurusan_pilihan ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Jalur PPDB</strong></div>
                                <div class="col-sm-8">: <span class="badge bg-secondary"><?php echo e($pendaftar->ppdbJalur->nama_jalur ?? '-'); ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Orang Tua & Kontak -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">
                                <i class="mdi mdi-account-group me-1"></i>Data Orang Tua
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-4"><strong>Nama Ayah</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->nama_ayah ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Pekerjaan Ayah</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->pekerjaan_ayah ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>No. HP Ayah</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->nomor_hp_ayah ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Nama Ibu</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->nama_ibu ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Pekerjaan Ibu</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->pekerjaan_ibu ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>No. HP Ibu</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->nomor_hp_ibu ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Status Keluarga</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->status_keluarga ?? '-'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">
                                <i class="mdi mdi-phone me-1"></i>Kontak & Alamat
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-4"><strong>WhatsApp</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->ppdb_nomor_whatsapp_siswa ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Email</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->ppdb_email_siswa ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Alamat</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->alamat_lengkap ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Provinsi</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->provinsi ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Kabupaten</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->kabupaten ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Kecamatan</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->kecamatan ?? '-'); ?></div>

                                <div class="col-sm-4"><strong>Desa</strong></div>
                                <div class="col-sm-8">: <?php echo e($pendaftar->desa ?? '-'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data PPDB & Skor -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">
                                <i class="mdi mdi-clipboard-check me-1"></i>Data PPDB
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-sm-5"><strong>No. Pendaftaran</strong></div>
                                <div class="col-sm-7">: <?php echo e($pendaftar->nomor_pendaftaran ?? '-'); ?></div>

                                <div class="col-sm-5"><strong>Status</strong></div>
                                <div class="col-sm-7">:
                                    <span class="badge bg-<?php echo e($pendaftar->status == 'lulus' ? 'success' : ($pendaftar->status == 'tidak_lulus' ? 'danger' : ($pendaftar->status == 'verifikasi' ? 'info' : 'warning'))); ?>">
                                        <?php if($pendaftar->status == 'lulus'): ?>
                                            Lulus
                                        <?php elseif($pendaftar->status == 'tidak_lulus'): ?>
                                            Tidak Lulus
                                        <?php elseif($pendaftar->status == 'verifikasi'): ?>
                                            Dalam Verifikasi
                                        <?php else: ?>
                                            Menunggu Verifikasi
                                        <?php endif; ?>
                                    </span>
                                </div>

                                <div class="col-sm-5"><strong>Ranking</strong></div>
                                <div class="col-sm-7">: <?php echo e($pendaftar->ranking ?? '-'); ?></div>

                                <div class="col-sm-5"><strong>Skor Total</strong></div>
                                <div class="col-sm-7">: <strong class="text-primary"><?php echo e($pendaftar->skor_total ?? 0); ?></strong></div>

                                <div class="col-sm-5"><strong>Rencana Lulus</strong></div>
                                <div class="col-sm-7">: <?php echo e($pendaftar->rencana_lulus == 'kuliah' ? 'Kuliah' : ($pendaftar->rencana_lulus == 'kerja' ? 'Bekerja' : '-')); ?></div>

                                <div class="col-sm-5"><strong>Tanggal Daftar</strong></div>
                                <div class="col-sm-7">: <?php echo e($pendaftar->created_at->format('d/m/Y H:i')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0 text-primary">
                                <i class="mdi mdi-chart-line me-1"></i>Detail Skor (Otomatis)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <!-- Skor Nilai Akademik -->
                                <div class="col-12">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <i class="mdi mdi-book-outline"></i> <strong>Skor Nilai Akademik</strong>
                                        </small>
                                        <?php
                                            $nilai = $pendaftar->rata_rata_nilai_raport ?? $pendaftar->nilai ?? 0;
                                            $keterangan = '';
                                            if ($nilai >= 90) {
                                                $keterangan = '(≥ 90)';
                                            } elseif ($nilai >= 80) {
                                                $keterangan = '(80-89)';
                                            } elseif ($nilai >= 70) {
                                                $keterangan = '(70-79)';
                                            } else {
                                                $keterangan = '(< 70)';
                                            }
                                        ?>
                                        <div class="mt-1">
                                            <span class="badge bg-info"><?php echo e($pendaftar->skor_nilai ?? 0); ?> poin</span>
                                            <small class="text-muted ms-2">Nilai <?php echo e($nilai); ?> <?php echo e($keterangan); ?></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Skor Prestasi -->
                                <div class="col-12">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <i class="mdi mdi-trophy-outline"></i> <strong>Skor Prestasi</strong>
                                        </small>
                                        <div class="mt-1">
                                            <span class="badge bg-<?php echo e($pendaftar->berkas_sertifikat_prestasi ? 'success' : 'secondary'); ?>">
                                                <?php echo e($pendaftar->skor_prestasi ?? 0); ?> poin
                                            </span>
                                            <small class="text-muted ms-2">
                                                <?php echo e($pendaftar->berkas_sertifikat_prestasi ? '✓ Ada Sertifikat' : '✗ Tidak Ada Sertifikat'); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Skor Domisili -->
                                <div class="col-12">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <i class="mdi mdi-map-marker-outline"></i> <strong>Skor Domisili</strong>
                                        </small>
                                        <div class="mt-1">
                                            <span class="badge bg-secondary"><?php echo e($pendaftar->skor_domisili ?? 0); ?> poin</span>
                                            <small class="text-muted ms-2">(Belum diaktifkan)</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Skor Dokumen -->
                                <div class="col-12">
                                    <div class="p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <i class="mdi mdi-file-document-outline"></i> <strong>Skor Dokumen</strong>
                                        </small>
                                        <div class="mt-1">
                                            <span class="badge bg-secondary"><?php echo e($pendaftar->skor_dokumen ?? 0); ?> poin</span>
                                            <small class="text-muted ms-2">(Belum diaktifkan)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12"><hr class="my-2"></div>

                                <!-- Skor Total -->
                                <div class="col-12">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-primary">
                                                <i class="mdi mdi-plus-circle me-1"></i>Skor Total
                                            </strong>
                                            <strong class="text-primary fs-5 badge bg-primary">
                                                <?php echo e($pendaftar->skor_total ?? 0); ?>

                                            </strong>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            Dihitung otomatis: <?php echo e($pendaftar->skor_nilai ?? 0); ?> + <?php echo e($pendaftar->skor_prestasi ?? 0); ?> + <?php echo e($pendaftar->skor_domisili ?? 0); ?> + <?php echo e($pendaftar->skor_dokumen ?? 0); ?>

                                        </small>
                                    </div>
                                </div>

                                <!-- Info Update -->
                                <div class="col-12">
                                    <div class="alert alert-info alert-sm mb-0" style="font-size: 0.85rem;">
                                        <i class="mdi mdi-information-outline"></i>
                                        <strong>Catatan:</strong> Skor dihitung otomatis berdasarkan data pendaftar dan tersimpan di database.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Berkas -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-file-document-multiple me-2"></i>
                        Berkas & Dokumen
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php if($pendaftar->berkas_kk): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-file-document-outline me-2 text-primary"></i>
                                    <span>Kartu Keluarga</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_kk)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_ijazah): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-certificate me-2 text-success"></i>
                                    <span>Ijazah</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_ijazah)); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_akta_kelahiran): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-baby-face-outline me-2 text-info"></i>
                                    <span>Akta Kelahiran</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_akta_kelahiran)); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_ktp_ayah): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-card-account-details-outline me-2 text-warning"></i>
                                    <span>KTP Ayah</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_ktp_ayah)); ?>" target="_blank" class="btn btn-sm btn-outline-warning">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_ktp_ibu): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-card-account-details-outline me-2 text-warning"></i>
                                    <span>KTP Ibu</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_ktp_ibu)); ?>" target="_blank" class="btn btn-sm btn-outline-warning">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>



                        <?php if($pendaftar->berkas_sertifikat_prestasi): ?>
                            <?php
                                $sertifikatFiles = is_array($pendaftar->berkas_sertifikat_prestasi) ? $pendaftar->berkas_sertifikat_prestasi : json_decode($pendaftar->berkas_sertifikat_prestasi, true);
                                if (!is_array($sertifikatFiles)) {
                                    $sertifikatFiles = [$pendaftar->berkas_sertifikat_prestasi];
                                }
                            ?>
                            <?php $__currentLoopData = $sertifikatFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="mdi mdi-trophy-outline me-2 text-warning"></i>
                                        <span>Sertifikat Prestasi <?php echo e(count($sertifikatFiles) > 1 ? ($index + 1) : ''); ?></span>
                                    </div>
                                    <a href="<?php echo e(asset($file)); ?>" target="_blank" class="btn btn-sm btn-outline-warning">
                                        <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_kip_pkh): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-hand-heart me-2 text-danger"></i>
                                    <span>PIP/PKH</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_kip_pkh)); ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_bukti_domisili): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-map-marker-outline me-2 text-info"></i>
                                    <span>Bukti Domisili</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_bukti_domisili)); ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_surat_mutasi): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-file-move-outline me-2 text-secondary"></i>
                                    <span>Surat Mutasi</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_surat_mutasi)); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_surat_keterangan_lulus): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-school-outline me-2 text-success"></i>
                                    <span>Surat Keterangan Lulus</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_surat_keterangan_lulus)); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if($pendaftar->berkas_skl): ?>
                            <div class="list-group-item d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="mdi mdi-certificate-outline me-2 text-success"></i>
                                    <span>SKL</span>
                                </div>
                                <a href="<?php echo e(asset($pendaftar->berkas_skl)); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                    <i class="mdi mdi-eye me-1"></i>Lihat Dokumen
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if(empty(array_filter([
                            $pendaftar->berkas_kk, $pendaftar->berkas_ijazah, $pendaftar->berkas_akta_kelahiran,
                            $pendaftar->berkas_ktp_ayah, $pendaftar->berkas_ktp_ibu, $pendaftar->berkas_raport,
                            $pendaftar->berkas_sertifikat_prestasi, $pendaftar->berkas_kip_pkh, $pendaftar->berkas_bukti_domisili,
                            $pendaftar->berkas_surat_mutasi, $pendaftar->berkas_surat_keterangan_lulus, $pendaftar->berkas_skl
                        ]))): ?>
                            <div class="text-center text-muted py-3">
                                <i class="mdi mdi-file-document-multiple-outline fs-1"></i>
                                <p class="mb-0">Belum ada berkas yang diupload</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Verifikasi Info -->
            <?php if($pendaftar->diverifikasi_oleh || $pendaftar->diseleksi_oleh): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-check-circle me-2"></i>
                        Info Verifikasi
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($pendaftar->diverifikasi_oleh): ?>
                        <div class="mb-2">
                            <small class="text-muted">Diverifikasi oleh:</small><br>
                            <strong><?php echo e($pendaftar->verifikator->name ?? 'Unknown'); ?></strong><br>
                            <small class="text-muted"><?php echo e($pendaftar->diverifikasi_tanggal ? $pendaftar->diverifikasi_tanggal->format('d/m/Y H:i') : '-'); ?></small>
                        </div>
                        <?php if($pendaftar->catatan_verifikasi): ?>
                            <div class="mb-2">
                                <small class="text-muted">Catatan Verifikasi:</small><br>
                                <p class="mb-0 small"><?php echo e($pendaftar->catatan_verifikasi); ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($pendaftar->diseleksi_oleh): ?>
                        <div>
                            <small class="text-muted">Diseleksi oleh:</small><br>
                            <strong><?php echo e($pendaftar->penyeleksi->name ?? 'Unknown'); ?></strong><br>
                            <small class="text-muted"><?php echo e($pendaftar->diseleksi_tanggal ? $pendaftar->diseleksi_tanggal->format('d/m/Y H:i') : '-'); ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0 text-primary">
                        <i class="mdi mdi-gavel me-1"></i>Aksi Verifikasi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button onclick="window.print()" class="btn btn-secondary">
                            <i class="mdi mdi-printer me-1"></i>Print
                        </button>
                        <?php if($pendaftar->status == 'pending'): ?>
                            <button onclick="verifikasiData(<?php echo e($pendaftar->id); ?>)" class="btn btn-info">
                                <i class="mdi mdi-check-circle-outline me-1"></i>Verifikasi Data
                            </button>
                            <small class="text-muted">Tandai data telah diverifikasi</small>
                        <?php elseif($pendaftar->status == 'verifikasi'): ?>
                            <button onclick="setStatus(<?php echo e($pendaftar->id); ?>, 'lulus')" class="btn btn-success">
                                <i class="mdi mdi-check-circle me-1"></i>Lulus Seleksi
                            </button>
                            <button onclick="setStatus(<?php echo e($pendaftar->id); ?>, 'tidak_lulus')" class="btn btn-danger">
                                <i class="mdi mdi-close-circle me-1"></i>Tidak Lulus
                            </button>
                            <small class="text-muted">Tentukan status kelulusan</small>
                        <?php elseif($pendaftar->status == 'lulus'): ?>
                            <div class="alert alert-success mb-0">
                                <i class="mdi mdi-check-circle me-1"></i>Siswa telah lulus seleksi
                            </div>
                        <?php elseif($pendaftar->status == 'tidak_lulus'): ?>
                            <div class="alert alert-danger mb-0">
                                <i class="mdi mdi-close-circle me-1"></i>Siswa tidak lulus seleksi
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-status {
    gap: 0.5rem;
}
.timeline-step {
    min-width: 80px;
}
.timeline-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 1.25rem;
    margin: 0 auto;
}
.timeline-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #dee2e6;
    margin: 0.25rem auto 0 auto;
}
.timeline-dot.active {
    background: #007bff;
    box-shadow: 0 0 0 2px #007bff33;
}
.timeline-line {
    width: 32px;
    height: 2px;
    background: #dee2e6;
    margin-bottom: 24px;
}
@media (max-width: 600px) {
    .timeline-status { flex-direction: column; align-items: flex-start; }
    .timeline-line { width: 2px; height: 32px; margin: 0 0 0 16px; }
}
</style>

<script>
window.verifikasiData = function(pendaftarId) {
    window.setStatus(pendaftarId, 'verifikasi');
};

window.setStatus = function(pendaftarId, status) {
    if (confirm('Apakah Anda yakin ingin mengubah status menjadi ' + (status === 'verifikasi' ? 'Dalam Verifikasi' : status === 'lulus' ? 'Lulus' : 'Tidak Lulus') + '?')) {
        fetch('/ppdb/lp/pendaftar/' + pendaftarId + '/update-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Status berhasil diupdate');
                location.reload();
            } else {
                alert('Gagal update status: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat update status');
        });
    }
};
</script>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/ppdb/dashboard/pendaftar-detail.blade.php ENDPATH**/ ?>