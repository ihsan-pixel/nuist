<?php $__env->startSection('title', 'Penilaian Pemateri - Instrument Talenta'); ?>

<?php $__env->startSection('content'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/mobile/laporan-akhir-tahun-create.css')); ?>">

<style>
    body {
        background: #f8f9fb url('/images/bg.png') no-repeat center center;
        background-size: cover;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
    }

    .evaluation-form {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 16px;
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 16px;
        text-align: center;
    }

    .form-title {
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .form-content {
        padding: 16px;
    }

    .aspect-item {
        margin-bottom: 20px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .aspect-number {
        display: inline-block;
        width: 24px;
        height: 24px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 24px;
        font-size: 12px;
        font-weight: bold;
        margin-right: 8px;
    }

    .aspect-name {
        font-weight: 600;
        color: #212529;
        margin-bottom: 4px;
    }

    .aspect-indicator {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 8px;
        font-style: italic;
    }

    .rating-group {
        display: flex;
        justify-content: space-between;
        gap: 4px;
    }

    .rating-option {
        flex: 1;
        text-align: center;
    }

    .rating-input {
        display: none;
    }

    .rating-label {
        display: block;
        width: 100%;
        padding: 8px 4px;
        border: 2px solid #dee2e6;
        border-radius: 6px;
        background: white;
        color: #6c757d;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .rating-input:checked + .rating-label {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .scale-info {
        margin-top: 16px;
        padding: 12px;
        background: #e9ecef;
        border-radius: 8px;
        font-size: 11px;
        text-align: center;
    }

    .submit-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        margin-top: 20px;
    }
</style>

<!-- Header -->
<div class="d-flex align-items-center mb-3" style="margin-top: -10px;">
    <button onclick="window.location.href='<?php echo e(route('instumen-talenta.peserta')); ?>'" class="btn btn-link text-decoration-none p-0 me-2" style="color: #ffffff;">
        <i class="bx bx-arrow-back" style="font-size: 20px;"></i>
    </button>
    <span class="fw-bold" style="color: #ffffff; font-size: 12px;">Kembali</span>
</div>

<div class="simfoni-header" style="margin-top: -10px;">
    <h4>PENILAIAN PEMATERI</h4>
    <p>Instrument Talenta</p>
</div>

<!-- Main Container -->
<div class="form-container">
    <!-- Selection Form -->
    <div class="section-card" id="selectionForm">
        <div class="section-header">
            <div class="section-icon">
                <i class="bx bx-select-multiple"></i>
            </div>
            <h6 class="section-title">PILIH PEMATERI</h6>
        </div>
        <div class="section-content">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <label for="pemateri_id" class="form-label">Pilih Pemateri <span class="text-danger">*</span></label>
                        <select class="form-select" id="pemateri_id" required>
                            <option value="">Pilih Pemateri</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $pemateris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pemateri): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($pemateri->id); ?>"
                                        data-materi="<?php echo e($pemateri->materi->judul_materi ?? 'N/A'); ?>"
                                        data-tanggal="<?php echo e($pemateri->materi->tanggal_materi ? $pemateri->materi->tanggal_materi->format('d/m/Y') : 'N/A'); ?>">
                                    <?php echo e($pemateri->nama); ?> (<?php echo e($pemateri->kode_pemateri); ?>)
                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Materi</label>
                        <input type="text" class="form-control" id="materi_info" readonly>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control" id="tanggal_info" readonly>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary w-100" id="proceedBtn" disabled>
                <i class="bx bx-right-arrow-alt me-2"></i>
                Lanjutkan ke Penilaian
            </button>
        </div>
    </div>

    <!-- Evaluation Form -->
    <div id="evaluationContainer" style="display: none;">
        <div class="section-card">
            <div class="section-header">
                <div class="section-icon">
                    <i class="bx bx-star"></i>
                </div>
                <h6 class="section-title">PENILAIAN PEMATERI</h6>
            </div>
            <div class="section-content">
                <div class="alert alert-info">
                    <strong>Pemateri:</strong> <span id="selectedPemateri">-</span><br>
                    <strong>Materi:</strong> <span id="selectedMateri">-</span><br>
                    <strong>Tanggal:</strong> <span id="selectedTanggal">-</span>
                </div>
            </div>
        </div>

        <form action="#" method="POST">
            <?php echo csrf_field(); ?>

        <div class="evaluation-form">
            <div class="form-header">
                <h5 class="form-title">Instrumen Penilaian terhadap Trainer/Pemateri</h5>
            </div>
            <div class="form-content">
                <!-- Aspect 1 -->
                <div class="aspect-item">
                    <div class="aspect-name">
                        <span class="aspect-number">1</span>
                        Kualitas Materi
                    </div>
                    <div class="aspect-indicator">Penyampaian jelas, runtut, dan mudah dipahami</div>
                    <div class="rating-group">
                        <div class="rating-option">
                            <input type="radio" name="aspect1" value="1" id="aspect1-1" class="rating-input" required>
                            <label for="aspect1-1" class="rating-label">1</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect1" value="2" id="aspect1-2" class="rating-input">
                            <label for="aspect1-2" class="rating-label">2</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect1" value="3" id="aspect1-3" class="rating-input">
                            <label for="aspect1-3" class="rating-label">3</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect1" value="4" id="aspect1-4" class="rating-input">
                            <label for="aspect1-4" class="rating-label">4</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect1" value="5" id="aspect1-5" class="rating-input">
                            <label for="aspect1-5" class="rating-label">5</label>
                        </div>
                    </div>
                </div>

                <!-- Aspect 2 -->
                <div class="aspect-item">
                    <div class="aspect-name">
                        <span class="aspect-number">2</span>
                        Penyampaian Materi
                    </div>
                    <div class="aspect-indicator">Penyampaian jelas, runtut, dan mudah dipahami</div>
                    <div class="rating-group">
                        <div class="rating-option">
                            <input type="radio" name="aspect2" value="1" id="aspect2-1" class="rating-input" required>
                            <label for="aspect2-1" class="rating-label">1</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect2" value="2" id="aspect2-2" class="rating-input">
                            <label for="aspect2-2" class="rating-label">2</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect2" value="3" id="aspect2-3" class="rating-input">
                            <label for="aspect2-3" class="rating-label">3</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect2" value="4" id="aspect2-4" class="rating-input">
                            <label for="aspect2-4" class="rating-label">4</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect2" value="5" id="aspect2-5" class="rating-input">
                            <label for="aspect2-5" class="rating-label">5</label>
                        </div>
                    </div>
                </div>

                <!-- Aspect 3 -->
                <div class="aspect-item">
                    <div class="aspect-name">
                        <span class="aspect-number">3</span>
                        Integrasi Kasus
                    </div>
                    <div class="aspect-indicator">Mampu mengaitkan materi dengan kasus nyata di satuan pendidikan</div>
                    <div class="rating-group">
                        <div class="rating-option">
                            <input type="radio" name="aspect3" value="1" id="aspect3-1" class="rating-input" required>
                            <label for="aspect3-1" class="rating-label">1</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect3" value="2" id="aspect3-2" class="rating-input">
                            <label for="aspect3-2" class="rating-label">2</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect3" value="3" id="aspect3-3" class="rating-input">
                            <label for="aspect3-3" class="rating-label">3</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect3" value="4" id="aspect3-4" class="rating-input">
                            <label for="aspect3-4" class="rating-label">4</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect3" value="5" id="aspect3-5" class="rating-input">
                            <label for="aspect3-5" class="rating-label">5</label>
                        </div>
                    </div>
                </div>

                <!-- Aspect 4 -->
                <div class="aspect-item">
                    <div class="aspect-name">
                        <span class="aspect-number">4</span>
                        Penjelasan Contoh
                    </div>
                    <div class="aspect-indicator">Contoh konkret, aplikatif, dan memperjelas konsep</div>
                    <div class="rating-group">
                        <div class="rating-option">
                            <input type="radio" name="aspect4" value="1" id="aspect4-1" class="rating-input" required>
                            <label for="aspect4-1" class="rating-label">1</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect4" value="2" id="aspect4-2" class="rating-input">
                            <label for="aspect4-2" class="rating-label">2</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect4" value="3" id="aspect4-3" class="rating-input">
                            <label for="aspect4-3" class="rating-label">3</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect4" value="4" id="aspect4-4" class="rating-input">
                            <label for="aspect4-4" class="rating-label">4</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect4" value="5" id="aspect4-5" class="rating-input">
                            <label for="aspect4-5" class="rating-label">5</label>
                        </div>
                    </div>
                </div>

                <!-- Aspect 5 -->
                <div class="aspect-item">
                    <div class="aspect-name">
                        <span class="aspect-number">5</span>
                        Umpan Balik Tugas
                    </div>
                    <div class="aspect-indicator">Memberikan umpan balik yang jelas, membangun, dan tepat sasaran</div>
                    <div class="rating-group">
                        <div class="rating-option">
                            <input type="radio" name="aspect5" value="1" id="aspect5-1" class="rating-input" required>
                            <label for="aspect5-1" class="rating-label">1</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect5" value="2" id="aspect5-2" class="rating-input">
                            <label for="aspect5-2" class="rating-label">2</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect5" value="3" id="aspect5-3" class="rating-input">
                            <label for="aspect5-3" class="rating-label">3</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect5" value="4" id="aspect5-4" class="rating-input">
                            <label for="aspect5-4" class="rating-label">4</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect5" value="5" id="aspect5-5" class="rating-input">
                            <label for="aspect5-5" class="rating-label">5</label>
                        </div>
                    </div>
                </div>

                <!-- Aspect 6 -->
                <div class="aspect-item">
                    <div class="aspect-name">
                        <span class="aspect-number">6</span>
                        Penggunaan Waktu
                    </div>
                    <div class="aspect-indicator">Waktu dikelola efektif sesuai alokasi sesi</div>
                    <div class="rating-group">
                        <div class="rating-option">
                            <input type="radio" name="aspect6" value="1" id="aspect6-1" class="rating-input" required>
                            <label for="aspect6-1" class="rating-label">1</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect6" value="2" id="aspect6-2" class="rating-input">
                            <label for="aspect6-2" class="rating-label">2</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect6" value="3" id="aspect6-3" class="rating-input">
                            <label for="aspect6-3" class="rating-label">3</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect6" value="4" id="aspect6-4" class="rating-input">
                            <label for="aspect6-4" class="rating-label">4</label>
                        </div>
                        <div class="rating-option">
                            <input type="radio" name="aspect6" value="5" id="aspect6-5" class="rating-input">
                            <label for="aspect6-5" class="rating-label">5</label>
                        </div>
                    </div>
                </div>

                <div class="scale-info">
                    <strong>Skala Penilaian:</strong><br>
                    1 = Sangat Kurang | 2 = Kurang | 3 = Cukup | 4 = Baik | 5 = Sangat Baik
                </div>

                <button type="submit" class="submit-btn">
                    <i class="bx bx-send me-2"></i>
                    Kirim Penilaian
                </button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pemateriSelect = document.getElementById('pemateri_id');
    const materiInput = document.getElementById('materi_info');
    const tanggalInput = document.getElementById('tanggal_info');
    const proceedBtn = document.getElementById('proceedBtn');
    const selectionForm = document.getElementById('selectionForm');
    const evaluationContainer = document.getElementById('evaluationContainer');
    const selectedPemateri = document.getElementById('selectedPemateri');
    const selectedMateri = document.getElementById('selectedMateri');
    const selectedTanggal = document.getElementById('selectedTanggal');

    // Handle pemateri selection
    pemateriSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const materi = selectedOption.getAttribute('data-materi');
            const tanggal = selectedOption.getAttribute('data-tanggal');

            materiInput.value = materi;
            tanggalInput.value = tanggal;
            proceedBtn.disabled = false;
        } else {
            materiInput.value = '';
            tanggalInput.value = '';
            proceedBtn.disabled = true;
        }
    });

    // Handle proceed button
    proceedBtn.addEventListener('click', function() {
        const selectedOption = pemateriSelect.options[pemateriSelect.selectedIndex];
        if (selectedOption.value) {
            const pemateriName = selectedOption.text;
            const materi = selectedOption.getAttribute('data-materi');
            const tanggal = selectedOption.getAttribute('data-tanggal');

            // Update evaluation form header
            selectedPemateri.textContent = pemateriName;
            selectedMateri.textContent = materi;
            selectedTanggal.textContent = tanggal;

            // Hide selection form and show evaluation form
            selectionForm.closest('.section-card').style.display = 'none';
            evaluationContainer.style.display = 'block';

            // Scroll to evaluation form
            evaluationContainer.scrollIntoView({ behavior: 'smooth' });
        }
    });

    // SweetAlert for success message
    <?php if(session('success')): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: '<?php echo e(session('success')); ?>',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mobile', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/instumen-talenta/penilaian-pemateri.blade.php ENDPATH**/ ?>