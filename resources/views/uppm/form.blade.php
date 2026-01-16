<!-- General Settings Section -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bx bx-cog me-2"></i>Pengaturan Umum</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tahun_anggaran" class="form-label"><i class="bx bx-calendar me-1"></i>Tahun Anggaran</label>
                    <input type="number" class="form-control" id="tahun_anggaran" name="tahun_anggaran" value="{{ old('tahun_anggaran', $setting->tahun_anggaran ?? date('Y')) }}" min="2020" max="{{ date('Y') + 1 }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="jatuh_tempo" class="form-label"><i class="bx bx-time me-1"></i>Jatuh Tempo</label>
                    <input type="date" class="form-control" id="jatuh_tempo" name="jatuh_tempo" value="{{ old('jatuh_tempo', $setting->jatuh_tempo ?? '') }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="skema_pembayaran" class="form-label"><i class="bx bx-credit-card me-1"></i>Skema Pembayaran</label>
                    <select class="form-select" id="skema_pembayaran" name="skema_pembayaran" required>
                        <option value="lunas" {{ old('skema_pembayaran', $setting->skema_pembayaran ?? '') == 'lunas' ? 'selected' : '' }}>Lunas Tahunan</option>
                        <option value="cicilan" {{ old('skema_pembayaran', $setting->skema_pembayaran ?? '') == 'cicilan' ? 'selected' : '' }}>Cicilan</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="aktif" class="form-label"><i class="bx bx-toggle-right me-1"></i>Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="aktif" name="aktif" value="1" {{ old('aktif', $setting->aktif ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">
                            Aktif
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nominal Iuran Section -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bx bx-money me-2"></i>Nominal Iuran per Bulan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_siswa" class="form-label"><i class="bx bx-user me-1"></i>Siswa</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_siswa" name="nominal_siswa" value="{{ old('nominal_siswa', $setting->nominal_siswa ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_pns_sertifikasi" class="form-label"><i class="bx bx-id-card me-1"></i>PNS Sertifikasi</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_pns_sertifikasi" name="nominal_pns_sertifikasi" value="{{ old('nominal_pns_sertifikasi', $setting->nominal_pns_sertifikasi ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_pns_non_sertifikasi" class="form-label"><i class="bx bx-id-card me-1"></i>PNS Non Sertifikasi</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_pns_non_sertifikasi" name="nominal_pns_non_sertifikasi" value="{{ old('nominal_pns_non_sertifikasi', $setting->nominal_pns_non_sertifikasi ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_gty_sertifikasi" class="form-label"><i class="bx bx-graduation me-1"></i>GTY Sertifikasi</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_gty_sertifikasi" name="nominal_gty_sertifikasi" value="{{ old('nominal_gty_sertifikasi', $setting->nominal_gty_sertifikasi ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_gty_sertifikasi_inpassing" class="form-label"><i class="bx bx-graduation me-1"></i>GTY Sertifikasi Inpassing</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_gty_sertifikasi_inpassing" name="nominal_gty_sertifikasi_inpassing" value="{{ old('nominal_gty_sertifikasi_inpassing', $setting->nominal_gty_sertifikasi_inpassing ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_gty_non_sertifikasi" class="form-label"><i class="bx bx-graduation me-1"></i>GTY Non Sertifikasi</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_gty_non_sertifikasi" name="nominal_gty_non_sertifikasi" value="{{ old('nominal_gty_non_sertifikasi', $setting->nominal_gty_non_sertifikasi ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_gtt" class="form-label"><i class="bx bx-teacher me-1"></i>GTT</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_gtt" name="nominal_gtt" value="{{ old('nominal_gtt', $setting->nominal_gtt ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_pty" class="form-label"><i class="bx bx-teacher me-1"></i>PTY</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_pty" name="nominal_pty" value="{{ old('nominal_pty', $setting->nominal_pty ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nominal_ptt" class="form-label"><i class="bx bx-teacher me-1"></i>PTT</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_ptt" name="nominal_ptt" value="{{ old('nominal_ptt', $setting->nominal_ptt ?? 0) }}" min="0" step="0.01" required>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Settings Section -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bx bx-file me-2"></i>Pengaturan Tambahan</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="format_invoice" class="form-label"><i class="bx bx-receipt me-1"></i>Format Penomoran Invoice</label>
                    <input type="text" class="form-control" id="format_invoice" name="format_invoice" value="{{ old('format_invoice', $setting->format_invoice ?? 'UPPM-{tahun}-{no}') }}" required>
                    <small class="text-muted">Gunakan {tahun} untuk tahun anggaran dan {no} untuk nomor urut</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="catatan" class="form-label"><i class="bx bx-note me-1"></i>Catatan Tambahan</label>
                    <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan', $setting->catatan ?? '') }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>
