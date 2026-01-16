<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="tahun_anggaran" class="form-label">Tahun Anggaran</label>
            <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran" value="<?php echo e(old('tahun_anggaran', $setting->tahun_anggaran ?? date('Y'))); ?>" min="2020" max="<?php echo e(date('Y') + 1); ?>" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="jatuh_tempo" class="form-label">Jatuh Tempo</label>
            <input type="date" class="form-control" id="jatuh_tempo" name="jatuh_tempo" value="<?php echo e(old('jatuh_tempo', $setting->jatuh_tempo ?? '')); ?>">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="skema_pembayaran" class="form-label">Skema Pembayaran</label>
            <select class="form-control" id="skema_pembayaran" name="skema_pembayaran" required>
                <option value="lunas" <?php echo e(old('skema_pembayaran', $setting->skema_pembayaran ?? '') == 'lunas' ? 'selected' : ''); ?>>Lunas Tahunan</option>
                <option value="cicilan" <?php echo e(old('skema_pembayaran', $setting->skema_pembayaran ?? '') == 'cicilan' ? 'selected' : ''); ?>>Cicilan</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="aktif" class="form-label">Status</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1" <?php echo e(old('aktif', $setting->aktif ?? false) ? 'checked' : ''); ?>>
                <label class="form-check-label" for="aktif">
                    Aktif
                </label>
            </div>
        </div>
    </div>
</div>

<h5>Nominal Iuran per Bulan</h5>
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_siswa" class="form-label">Siswa</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_siswa" name="nominal_siswa" value="<?php echo e(old('nominal_siswa', $setting->nominal_siswa ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_pns_sertifikasi" class="form-label">PNS Sertifikasi</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_pns_sertifikasi" name="nominal_pns_sertifikasi" value="<?php echo e(old('nominal_pns_sertifikasi', $setting->nominal_pns_sertifikasi ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_pns_non_sertifikasi" class="form-label">PNS Non Sertifikasi</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_pns_non_sertifikasi" name="nominal_pns_non_sertifikasi" value="<?php echo e(old('nominal_pns_non_sertifikasi', $setting->nominal_pns_non_sertifikasi ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_gty_sertifikasi" class="form-label">GTY Sertifikasi</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_gty_sertifikasi" name="nominal_gty_sertifikasi" value="<?php echo e(old('nominal_gty_sertifikasi', $setting->nominal_gty_sertifikasi ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_gty_sertifikasi_inpassing" class="form-label">GTY Sertifikasi Inpassing</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_gty_sertifikasi_inpassing" name="nominal_gty_sertifikasi_inpassing" value="<?php echo e(old('nominal_gty_sertifikasi_inpassing', $setting->nominal_gty_sertifikasi_inpassing ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_gty_non_sertifikasi" class="form-label">GTY Non Sertifikasi</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_gty_non_sertifikasi" name="nominal_gty_non_sertifikasi" value="<?php echo e(old('nominal_gty_non_sertifikasi', $setting->nominal_gty_non_sertifikasi ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_gtt" class="form-label">GTT</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_gtt" name="nominal_gtt" value="<?php echo e(old('nominal_gtt', $setting->nominal_gtt ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_pty" class="form-label">PTY</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_pty" name="nominal_pty" value="<?php echo e(old('nominal_pty', $setting->nominal_pty ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="nominal_ptt" class="form-label">PTT</label>
            <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="number" class="form-control" id="nominal_ptt" name="nominal_ptt" value="<?php echo e(old('nominal_ptt', $setting->nominal_ptt ?? 0)); ?>" min="0" step="0.01" required>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="format_invoice" class="form-label">Format Penomoran Invoice</label>
            <input type="text" class="form-control" id="format_invoice" name="format_invoice" value="<?php echo e(old('format_invoice', $setting->format_invoice ?? 'UPPM-{tahun}-{no}')); ?>" required>
            <small class="text-muted">Gunakan {tahun} untuk tahun anggaran dan {no} untuk nomor urut</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan Tambahan</label>
            <textarea class="form-control" id="catatan" name="catatan" rows="3"><?php echo e(old('catatan', $setting->catatan ?? '')); ?></textarea>
        </div>
    </div>
</div>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/uppm/form.blade.php ENDPATH**/ ?>