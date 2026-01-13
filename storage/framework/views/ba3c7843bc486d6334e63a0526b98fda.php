<?php $__env->startSection('title'); ?>
    Data Simfoni
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('build/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/css/app.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- DataTables -->
<link href="<?php echo e(asset('build/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" type="text/css" />

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $userRole = trim(strtolower(auth()->user()->role));
    $isAllowed = in_array($userRole, ['super_admin', 'admin', 'pengurus']);
?>
<?php if($isAllowed): ?>
<?php $__env->startComponent('components.breadcrumb'); ?>
    <?php $__env->slot('li_1'); ?> Admin <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> Data Simfoni <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="card mb-4">
    <div class="card-body">

        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Gelar</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>NUPTK</th>
                        <th>KARTANU</th>
                        <th>NIPM</th>
                        <th>NIK</th>
                        <th>TMT</th>
                        <th>Strata Pendidikan</th>
                        <th>PT Asal</th>
                        <th>Tahun Lulus</th>
                        <th>Program Studi</th>
                        <th>Status Kerja</th>
                        <th>Tanggal SK Pertama</th>
                        <th>Nomor SK Pertama</th>
                        <th>Nomor Sertifikasi Pendidik</th>
                        <th>Riwayat Kerja Sebelumnya</th>
                        <th>Keahlian</th>
                        <th>Kedudukan LPM</th>
                        <th>Prestasi</th>
                        <th>Tahun Sertifikasi Impassing</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Status Perkawinan</th>
                        <th>Alamat Lengkap</th>
                        <th>Bank</th>
                        <th>Nomor Rekening</th>
                        <th>Gaji Sertifikasi</th>
                        <th>Gaji Pokok</th>
                        <th>Honor Lain</th>
                        <th>Penghasilan Lain</th>
                        <th>Penghasilan Pasangan</th>
                        <th>Total Penghasilan</th>
                        <th>Masa Kerja</th>
                        <th>Kategori Penghasilan</th>
                        <th>Status Kader Diri</th>
                        <th>Pendidikan Kader</th>
                        <th>Status Kader Ayah</th>
                        <th>Status Kader Ibu</th>
                        <th>Status Kader Pasangan</th>
                        <th>Nama Ayah</th>
                        <th>Nama Ibu</th>
                        <th>Nama Pasangan</th>
                        <th>Jumlah Anak</th>
                        <th>Akan Kuliah S2</th>
                        <th>Akan Daftar PNS</th>
                        <th>Akan Daftar PPPK</th>
                        <th>Akan Ikuti PPG</th>
                        <th>Akan Tulis Buku/Modul/Riset</th>
                        <th>Akan Ikuti Seleksi Diklat CAKEP</th>
                        <th>Akan Bimbing Riset Prestasi Siswa</th>
                        <th>Akan Masuk Tim Unggulan</th>
                        <th>Akan Kompetisi Pimpinan Level II</th>
                        <th>Akan Aktif Ikuti Pelatihan</th>
                        <th>Akan Aktif MGMP/MKK</th>
                        <th>Akan Ikuti Pendidikan Kader NU</th>
                        <th>Akan Aktif Bantu Kegiatan Lembaga</th>
                        <th>Akan Aktif Ikuti Kegiatan NU</th>
                        <th>Akan Aktif Ikut ZIS Kegiatan Sosial</th>
                        <th>Akan Kembangkan Unit Usaha SATPEN</th>
                        <th>Akan Bekerja Disiplin Produktif</th>
                        <th>Akan Loyal NU Aktif Masyarakat</th>
                        <th>Akan Bersedia Dipindah SATPEN Lain</th>
                        <th>Skor Proyeksi</th>
                        <th>Pernyataan Setuju</th>
                        <th>Asal Sekolah</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $simfonis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $simfoni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($simfoni->nama_lengkap_gelar); ?></td>
                        <td><?php echo e($simfoni->gelar); ?></td>
                        <td><?php echo e($simfoni->tempat_lahir); ?></td>
                        <td><?php echo e($simfoni->tanggal_lahir ? \Carbon\Carbon::parse($simfoni->tanggal_lahir)->locale('id')->format('d F Y') : '-'); ?></td>
                        <td><?php echo e($simfoni->nuptk); ?></td>
                        <td><?php echo e($simfoni->kartanu); ?></td>
                        <td><?php echo e($simfoni->nipm); ?></td>
                        <td><?php echo e($simfoni->nik); ?></td>
                        <td><?php echo e($simfoni->tmt ? \Carbon\Carbon::parse($simfoni->tmt)->locale('id')->format('d F Y') : '-'); ?></td>
                        <td><?php echo e($simfoni->strata_pendidikan); ?></td>
                        <td><?php echo e($simfoni->pt_asal); ?></td>
                        <td><?php echo e($simfoni->tahun_lulus); ?></td>
                        <td><?php echo e($simfoni->program_studi); ?></td>
                        <td><?php echo e($simfoni->status_kerja); ?></td>
                        <td><?php echo e($simfoni->tanggal_sk_pertama ? \Carbon\Carbon::parse($simfoni->tanggal_sk_pertama)->locale('id')->format('d F Y') : '-'); ?></td>
                        <td><?php echo e($simfoni->nomor_sk_pertama); ?></td>
                        <td><?php echo e($simfoni->nomor_sertifikasi_pendidik); ?></td>
                        <td><?php echo e($simfoni->riwayat_kerja_sebelumnya); ?></td>
                        <td><?php echo e($simfoni->keahlian); ?></td>
                        <td><?php echo e($simfoni->kedudukan_lpm); ?></td>
                        <td><?php echo e($simfoni->prestasi); ?></td>
                        <td><?php echo e($simfoni->tahun_sertifikasi_impassing); ?></td>
                        <td><?php echo e($simfoni->no_hp); ?></td>
                        <td><?php echo e($simfoni->email); ?></td>
                        <td><?php echo e($simfoni->status_pernikahan); ?></td>
                        <td><?php echo e($simfoni->alamat_lengkap); ?></td>
                        <td><?php echo e($simfoni->bank); ?></td>
                        <td><?php echo e($simfoni->nomor_rekening); ?></td>
                        <td><?php echo e(number_format($simfoni->gaji_sertifikasi, 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($simfoni->gaji_pokok, 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($simfoni->honor_lain, 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($simfoni->penghasilan_lain, 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($simfoni->penghasilan_pasangan, 0, ',', '.')); ?></td>
                        <td><?php echo e(number_format($simfoni->total_penghasilan, 0, ',', '.')); ?></td>
                        <td><?php echo e($simfoni->masa_kerja); ?></td>
                        <td><?php echo e($simfoni->kategori_penghasilan); ?></td>
                        <td><?php echo e($simfoni->status_kader_diri); ?></td>
                        <td><?php echo e($simfoni->pendidikan_kader); ?></td>
                        <td><?php echo e($simfoni->status_kader_ayah); ?></td>
                        <td><?php echo e($simfoni->status_kader_ibu); ?></td>
                        <td><?php echo e($simfoni->status_kader_pasangan); ?></td>
                        <td><?php echo e($simfoni->nama_ayah); ?></td>
                        <td><?php echo e($simfoni->nama_ibu); ?></td>
                        <td><?php echo e($simfoni->nama_pasangan); ?></td>
                        <td><?php echo e($simfoni->jumlah_anak); ?></td>
                        <td><?php echo e($simfoni->akan_kuliah_s2 ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_daftar_pns ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_daftar_pppk ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_ikut_ppg ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_tulis_buku_modul_riset ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_ikut_seleksi_diklat_cakep ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_bimbing_riset_prestasi_siswa ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_masuk_tim_unggulan_sekolah_madrasah ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_kompetisi_pimpinan_level_ii ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_aktif_mengikuti_pelatihan ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_aktif_mgmp_mkk ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_ikut_pendidikan_kader_nu ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_aktif_bantu_kegiatan_lembaga ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_aktif_ikut_kegiatan_nu ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_aktif_ikut_zis_kegiatan_sosial ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_kembangkan_unit_usaha_satpen ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_bekerja_disiplin_produktif ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_loyal_nu_aktif_masyarakat ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->akan_bersedia_dipindah_satpen_lain ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->skor_proyeksi); ?></td>
                        <td><?php echo e($simfoni->pernyataan_setuju ? 'Ya' : 'Tidak'); ?></td>
                        <td><?php echo e($simfoni->user->madrasah->name ?? '-'); ?></td>
                        <td><?php echo e($simfoni->created_at->format('d/m/Y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="71" class="text-center p-4">
                            <div class="alert alert-info d-inline-block text-center" role="alert">
                                <i class="bx bx-info-circle bx-lg me-2"></i>
                                <strong>Belum ada data Simfoni</strong><br>
                                <small>Silakan tambahkan data simfoni terlebih dahulu untuk melanjutkan.</small>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-danger text-center">
    <h4>Akses Ditolak</h4>
    <p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/pdfmake/build/vfs_fonts.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.print.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-buttons/js/buttons.colVis.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive/js/dataTables.responsive.min.js')); ?>"></script>
<script src="<?php echo e(asset('build/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')); ?>"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    let table = $("#datatable-buttons").DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        buttons: ["copy", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container()
        .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');

    <?php if(session('success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?php echo e(session('success')); ?>',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if(session('error')): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?php echo e(session('error')); ?>',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: true
        });
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/admin/simfoni/index.blade.php ENDPATH**/ ?>