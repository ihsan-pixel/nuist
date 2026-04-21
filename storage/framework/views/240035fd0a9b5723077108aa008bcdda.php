<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">NIS</label>
        <input type="text" name="nis" class="form-control" value="<?php echo e(old('nis', $siswa->nis ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Lengkap Siswa</label>
        <input type="text" name="nama_lengkap" class="form-control" value="<?php echo e(old('nama_lengkap', $siswa->nama_lengkap ?? '')); ?>" placeholder="Akan disimpan kapital" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Orang Tua/Wali</label>
        <input type="text" name="nama_orang_tua_wali" class="form-control" value="<?php echo e(old('nama_orang_tua_wali', $siswa->nama_orang_tua_wali ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Kelas</label>
        <input type="text" name="kelas" class="form-control" value="<?php echo e(old('kelas', $siswa->kelas ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jurusan</label>
        <input type="text" name="jurusan" class="form-control" value="<?php echo e(old('jurusan', $siswa->jurusan ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email Siswa</label>
        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $siswa->email ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email Orang Tua/Wali</label>
        <input type="email" name="email_orang_tua_wali" class="form-control" value="<?php echo e(old('email_orang_tua_wali', $siswa->email_orang_tua_wali ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">No HP Siswa</label>
        <input type="text" name="no_hp" class="form-control" value="<?php echo e(old('no_hp', $siswa->no_hp ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">No HP Orang Tua/Wali</label>
        <input type="text" name="no_hp_orang_tua_wali" class="form-control" value="<?php echo e(old('no_hp_orang_tua_wali', $siswa->no_hp_orang_tua_wali ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Madrasah/Sekolah</label>
        <select name="madrasah_id" class="form-select" required <?php echo e($userRole === 'admin' ? 'disabled' : ''); ?>>
            <option value="">Pilih Madrasah</option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $madrasahOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $madrasah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <option value="<?php echo e($madrasah->id); ?>" <?php echo e((string) old('madrasah_id', $siswa->madrasah_id ?? $selectedMadrasahId) === (string) $madrasah->id ? 'selected' : ''); ?>>
                    <?php echo e($madrasah->name); ?>

                </option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </select>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userRole === 'admin'): ?>
            <input type="hidden" name="madrasah_id" value="<?php echo e(old('madrasah_id', $siswa->madrasah_id ?? $selectedMadrasahId)); ?>">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', isset($siswa) ? $siswa->is_active : true) ? 'checked' : ''); ?>>
            <label class="form-check-label">Akun aktif</label>
        </div>
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="3" required><?php echo e(old('alamat', $siswa->alamat ?? '')); ?></textarea>
    </div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/data-sekolah/partials/siswa-form.blade.php ENDPATH**/ ?>