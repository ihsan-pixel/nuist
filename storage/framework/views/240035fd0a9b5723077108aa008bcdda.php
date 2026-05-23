<div class="row g-3">
    <div class="col-12">
        <div class="alert alert-light border mb-0">
            Wajib isi minimal NIS, nama peserta didik, kelas, dan madrasah. Email siswa opsional dan akan dibuat otomatis jika dikosongkan.
        </div>
    </div>

    <div class="col-12">
        <h6 class="mb-1">Identitas Siswa</h6>
        <hr class="mt-0">
    </div>
    <div class="col-md-4">
        <label class="form-label">NIS</label>
        <input type="text" name="nis" class="form-control" value="<?php echo e(old('nis', $siswa->nis ?? '')); ?>" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">NISN</label>
        <input type="text" name="nisn" class="form-control" value="<?php echo e(old('nisn', $siswa->nisn ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">NIK</label>
        <input type="text" name="nik" class="form-control" value="<?php echo e(old('nik', $siswa->nik ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">No. KK</label>
        <input type="text" name="no_kk" class="form-control" value="<?php echo e(old('no_kk', $siswa->no_kk ?? '')); ?>">
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Peserta Didik</label>
        <input type="text" name="nama_lengkap" class="form-control" value="<?php echo e(old('nama_lengkap', $siswa->nama_lengkap ?? '')); ?>" placeholder="Akan disimpan kapital" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select">
            <option value="">Pilih</option>
            <option value="L" <?php echo e(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'L' ? 'selected' : ''); ?>>Laki-laki</option>
            <option value="P" <?php echo e(old('jenis_kelamin', $siswa->jenis_kelamin ?? '') === 'P' ? 'selected' : ''); ?>>Perempuan</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Tempat Lahir</label>
        <input type="text" name="tempat_lahir" class="form-control" value="<?php echo e(old('tempat_lahir', $siswa->tempat_lahir ?? '')); ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" value="<?php echo e(old('tanggal_lahir', isset($siswa) && $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '')); ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Agama</label>
        <input type="text" name="agama" class="form-control" value="<?php echo e(old('agama', $siswa->agama ?? '')); ?>">
    </div>

    <div class="col-12 pt-2">
        <h6 class="mb-1">Akademik</h6>
        <hr class="mt-0">
    </div>
    <div class="col-md-4">
        <label class="form-label">Kelas</label>
        <input type="text" name="kelas" class="form-control" value="<?php echo e(old('kelas', $siswa->kelas ?? '')); ?>" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Jurusan</label>
        <input type="text" name="jurusan" class="form-control" value="<?php echo e(old('jurusan', $siswa->jurusan ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Tahun Masuk</label>
        <input type="text" name="tahun_masuk" class="form-control" value="<?php echo e(old('tahun_masuk', $siswa->tahun_masuk ?? '')); ?>" placeholder="Contoh: 2025">
    </div>
    <div class="col-md-4">
        <label class="form-label">Madrasah/Sekolah</label>
        <select name="madrasah_id" class="form-select" required <?php echo e(in_array($userRole, ['admin', 'admin_spp']) ? 'disabled' : ''); ?>>
            <option value="">Pilih Madrasah</option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <option value="<?php echo e($madrasah->id); ?>" <?php echo e((string) old('madrasah_id', $siswa->madrasah_id ?? $selectedMadrasahId) === (string) $madrasah->id ? 'selected' : ''); ?>>
                    <?php echo e($madrasah->name); ?>

                </option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </select>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($userRole, ['admin', 'admin_spp'])): ?>
            <input type="hidden" name="madrasah_id" value="<?php echo e(old('madrasah_id', $siswa->madrasah_id ?? $selectedMadrasahId)); ?>">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="col-md-4">
        <label class="form-label">Jenis Tinggal</label>
        <input type="text" name="jenis_tinggal" class="form-control" value="<?php echo e(old('jenis_tinggal', $siswa->jenis_tinggal ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Alat Transportasi</label>
        <input type="text" name="alat_transportasi" class="form-control" value="<?php echo e(old('alat_transportasi', $siswa->alat_transportasi ?? '')); ?>">
    </div>

    <div class="col-12 pt-2">
        <h6 class="mb-1">Kontak dan Alamat</h6>
        <hr class="mt-0">
    </div>
    <div class="col-md-4">
        <label class="form-label">No HP Siswa</label>
        <input type="text" name="no_hp" class="form-control" value="<?php echo e(old('no_hp', $siswa->no_hp ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Email Siswa</label>
        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $siswa->email ?? '')); ?>" placeholder="Opsional">
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', isset($siswa) ? $siswa->is_active : true) ? 'checked' : ''); ?>>
            <label class="form-check-label">Akun aktif</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="3"><?php echo e(old('alamat', $siswa->alamat ?? '')); ?></textarea>
    </div>
    <div class="col-md-3">
        <label class="form-label">Dusun</label>
        <input type="text" name="dusun" class="form-control" value="<?php echo e(old('dusun', $siswa->dusun ?? '')); ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Kelurahan</label>
        <input type="text" name="kelurahan" class="form-control" value="<?php echo e(old('kelurahan', $siswa->kelurahan ?? '')); ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Kecamatan</label>
        <input type="text" name="kecamatan" class="form-control" value="<?php echo e(old('kecamatan', $siswa->kecamatan ?? '')); ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Kode Pos</label>
        <input type="text" name="kode_pos" class="form-control" value="<?php echo e(old('kode_pos', $siswa->kode_pos ?? '')); ?>">
    </div>

    <div class="col-12 pt-2">
        <h6 class="mb-1">Data Orang Tua dan Wali</h6>
        <hr class="mt-0">
    </div>
    <div class="col-md-4">
        <label class="form-label">Nama Ayah</label>
        <input type="text" name="nama_ayah" class="form-control" value="<?php echo e(old('nama_ayah', $siswa->nama_ayah ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Pendidikan Ayah</label>
        <input type="text" name="pendidikan_ayah" class="form-control" value="<?php echo e(old('pendidikan_ayah', $siswa->pendidikan_ayah ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Pekerjaan Ayah</label>
        <input type="text" name="pekerjaan_ayah" class="form-control" value="<?php echo e(old('pekerjaan_ayah', $siswa->pekerjaan_ayah ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Penghasilan Ayah</label>
        <input type="text" name="penghasilan_ayah" class="form-control" value="<?php echo e(old('penghasilan_ayah', $siswa->penghasilan_ayah ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Nama Ibu</label>
        <input type="text" name="nama_ibu" class="form-control" value="<?php echo e(old('nama_ibu', $siswa->nama_ibu ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Pendidikan Ibu</label>
        <input type="text" name="pendidikan_ibu" class="form-control" value="<?php echo e(old('pendidikan_ibu', $siswa->pendidikan_ibu ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Pekerjaan Ibu</label>
        <input type="text" name="pekerjaan_ibu" class="form-control" value="<?php echo e(old('pekerjaan_ibu', $siswa->pekerjaan_ibu ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Penghasilan Ibu</label>
        <input type="text" name="penghasilan_ibu" class="form-control" value="<?php echo e(old('penghasilan_ibu', $siswa->penghasilan_ibu ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Nama Wali</label>
        <input type="text" name="nama_wali" class="form-control" value="<?php echo e(old('nama_wali', $siswa->nama_wali ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Pendidikan Wali</label>
        <input type="text" name="pendidikan_wali" class="form-control" value="<?php echo e(old('pendidikan_wali', $siswa->pendidikan_wali ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Pekerjaan Wali</label>
        <input type="text" name="pekerjaan_wali" class="form-control" value="<?php echo e(old('pekerjaan_wali', $siswa->pekerjaan_wali ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Penghasilan Wali</label>
        <input type="text" name="penghasilan_wali" class="form-control" value="<?php echo e(old('penghasilan_wali', $siswa->penghasilan_wali ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Nama Orang Tua/Wali</label>
        <input type="text" name="nama_orang_tua_wali" class="form-control" value="<?php echo e(old('nama_orang_tua_wali', $siswa->nama_orang_tua_wali ?? '')); ?>" placeholder="Opsional, akan diambil dari ayah/ibu/wali jika kosong">
    </div>
    <div class="col-md-4">
        <label class="form-label">No HP Orang Tua/Wali</label>
        <input type="text" name="no_hp_orang_tua_wali" class="form-control" value="<?php echo e(old('no_hp_orang_tua_wali', $siswa->no_hp_orang_tua_wali ?? '')); ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Email Orang Tua/Wali</label>
        <input type="email" name="email_orang_tua_wali" class="form-control" value="<?php echo e(old('email_orang_tua_wali', $siswa->email_orang_tua_wali ?? '')); ?>">
    </div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/data-sekolah/partials/siswa-form.blade.php ENDPATH**/ ?>