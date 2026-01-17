<?php $__env->startSection('title', 'Izin Tugas Luar'); ?>
<?php $__env->startSection('subtitle', 'Pengajuan Izin Tugas Luar'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <!-- Back Button -->
    <div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
        <button onclick="history.back()" class="btn btn-link text-decoration-none p-0 me-2" style="color: #004b4c;">
            <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
        </button>
        <span class="fw-bold" style="color: #004b4c; font-size: 12px;">Kembali</span>
    </div>
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
        }

        .izin-form {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            font-size: 12px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-submit:hover {
            opacity: 0.9;
        }
    </style>

    <form id="form-izin-tugas-luar" action="<?php echo e(route('mobile.izin.store')); ?>" method="POST" enctype="multipart/form-data" class="izin-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="tugas_luar">

        <div class="form-group">
            <label for="tanggal" class="form-label">Tanggal Tugas</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="form-group">
            <label for="deskripsi_tugas" class="form-label">Deskripsi Tugas</label>
            <textarea class="form-control" id="deskripsi_tugas" name="deskripsi_tugas" rows="3" placeholder="Jelaskan tugas yang akan dilakukan..." required></textarea>
        </div>

        <div class="form-group">
            <label for="lokasi_tugas" class="form-label">Lokasi Tugas</label>
            <input type="text" class="form-control" id="lokasi_tugas" name="lokasi_tugas" placeholder="Masukkan lokasi tugas" required>
        </div>

        <div class="form-group">
            <label for="waktu_masuk" class="form-label">Waktu Mulai</label>
            <input type="time" class="form-control" id="waktu_masuk" name="waktu_masuk" required>
        </div>

        <div class="form-group">
            <label for="waktu_keluar" class="form-label">Waktu Selesai</label>
            <input type="time" class="form-control" id="waktu_keluar" name="waktu_keluar" required>
        </div>

        <div class="form-group">
            <label for="file_tugas" class="form-label">Upload Surat Tugas (PDF/JPG/PNG)</label>
            <input type="file" class="form-control" id="file_tugas" name="file_tugas" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>

        <button type="submit" class="btn-submit">Kirim Izin Tugas Luar</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#form-izin-tugas-luar').on('submit', function(e){
        e.preventDefault();
        var fd = new FormData(this);

        $.ajax({
            url: '<?php echo e(route("mobile.izin.store")); ?>',
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(res){
                if(res.success){
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message || 'Izin tugas luar berhasil diajukan.' }).then(function(){
                        window.location.href = '<?php echo e(route("mobile.riwayat-presensi")); ?>';
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: res.message || 'Surat gagal terkirim.' });
                }
            },
            error: function(xhr){
                var msg = 'Surat gagal terkirim.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/izin-tugas-luar.blade.php ENDPATH**/ ?>