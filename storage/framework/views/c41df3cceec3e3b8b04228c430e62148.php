<?php $__env->startSection('title', 'Mode Kiosk 2 - Daftar Wajah'); ?>

<?php $__env->startSection('body'); ?>
<body class="kiosk-face-enrollment-page">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>">
<style>
    body.kiosk-face-enrollment-page {
        margin: 0;
        min-height: 100vh;
        background: radial-gradient(circle at top left, rgba(34, 197, 94, 0.16), transparent 28%), linear-gradient(180deg, #07111f 0%, #0b1727 100%);
        color: #e2e8f0;
    }
    .kiosk-shell { min-height: 100vh; padding: 20px; }
    .panel { border-radius: 28px; background: rgba(15, 23, 42, 0.86); border: 1px solid rgba(148, 163, 184, 0.18); box-shadow: 0 20px 70px rgba(0, 0, 0, 0.35); }
    .phase-chip, .teacher-chip { display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 999px; background: rgba(255,255,255,0.06); font-size: 12px; font-weight: 700; }
    .phase-list { display: grid; gap: 10px; }
    .phase-item { padding: 12px 14px; border-radius: 16px; background: rgba(255,255,255,0.04); border: 1px solid rgba(148,163,184,0.12); }
    .phase-item.done { border-color: rgba(74,222,128,0.35); background: rgba(22,163,74,0.12); }
    .phase-item.active { border-color: rgba(56,189,248,0.5); background: rgba(14,165,233,0.14); }
    .video-box {
        border-radius: 24px;
        overflow: hidden;
        background: #000;
        position: relative;
        aspect-ratio: 4 / 3;
        max-height: 62vh;
        min-height: 360px;
    }
    video, canvas { width: 100%; height: 100%; object-fit: cover; }
    #video {
        transform: scaleX(-1) !important;
    }
    .hidden { display: none !important; }
    .left-panel { max-width: 100%; }
    .camera-panel { max-width: 100%; }
    .status-mini {
        margin-top: 16px;
        padding: 10px 12px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(148, 163, 184, 0.12);
        color: rgba(226, 232, 240, 0.9);
        font-size: 12px;
        line-height: 1.45;
    }
    .status-mini-label {
        display: block;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(148, 163, 184, 0.9);
        margin-bottom: 4px;
    }
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 8px;
    }
    .gallery-item {
        border-radius: 14px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(148, 163, 184, 0.14);
        min-height: 96px;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .gallery-item img {
        width: 100%;
        aspect-ratio: 4 / 3;
        object-fit: cover;
        display: block;
    }
    .gallery-state {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        background: rgba(15, 23, 42, 0.82);
        border: 1px solid rgba(255, 255, 255, 0.18);
        z-index: 2;
    }
    .gallery-state.is-ok {
        background: rgba(22, 163, 74, 0.9);
        border-color: rgba(134, 239, 172, 0.7);
    }
    .gallery-state.is-missing {
        background: rgba(220, 38, 38, 0.9);
        border-color: rgba(248, 113, 113, 0.7);
    }
    .gallery-caption {
        padding: 5px 4px 6px;
        font-size: 10px;
        font-weight: 400;
        line-height: 1.35;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: rgba(226, 232, 240, 0.88);
        border-top: 1px solid rgba(148, 163, 184, 0.12);
        background: rgba(15, 23, 42, 0.45);
        text-align: center;
    }
    @media (max-width: 1199px) {
        .gallery-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 575px) {
        .gallery-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    .gallery-empty {
        grid-column: 1 / -1;
        padding: 14px;
        border-radius: 14px;
        border: 1px dashed rgba(148, 163, 184, 0.28);
        color: rgba(226, 232, 240, 0.7);
        font-size: 12px;
        text-align: center;
    }
    .phase-list .fw-bold {
        font-weight: 400 !important;
        font-size: 12px;
        line-height: 1.35;
    }
    .phase-list .small.text-secondary {
        font-size: 11px !important;
        font-weight: 400;
        line-height: 1.35;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="kiosk-shell">
    <div class="row g-4">
        <div class="col-lg-3">
            <div class="panel p-4 h-100 left-panel">
                <div class="phase-chip mb-3">Mode Kiosk 2</div>
                <h2 class="h4 mb-2">Daftar wajah admin-only</h2>
        <p class="text-secondary mb-4">Fokus ke 6 template capture wajah. Tidak ada presensi di layar ini.</p>
                <div class="mb-3">
                    <label class="form-label text-light">Pilih Sekolah</label>
                    <form method="GET" action="<?php echo e(route('kiosk.face-enrollment.index')); ?>">
                        <select name="madrasah_id" class="form-select mb-2">
                            <option value="">Semua Sekolah</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <option value="<?php echo e($school->id); ?>" <?php echo e((int) $selectedMadrasahId === (int) $school->id ? 'selected' : ''); ?>>
                                    <?php echo e($school->name); ?>

                                </option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </select>
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">Terapkan Sekolah</button>
                    </form>
                </div>
                <div class="phase-list" id="phaseList"></div>
                <div class="status-mini" id="statusMini">
                    <span class="status-mini-label">Status</span>
                    Menunggu sesi dimulai.
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel p-4 h-100 camera-panel">
                <div class="teacher-chip mb-3">Pilih guru dari semua sekolah</div>
                <select id="teacherSelect" class="form-select mb-3">
                    <option value="">Pilih guru</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?><?php echo e($teacher->madrasah?->nama ? ' - '.$teacher->madrasah->nama : ''); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
                <div class="video-box mb-3">
                    <video id="video" autoplay playsinline muted></video>
                    <canvas id="canvas" class="hidden"></canvas>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-success" id="startBtn">Mulai sesi</button>
                    <button class="btn btn-primary" id="finishBtn" disabled>Simpan final</button>
                    <button class="btn btn-outline-warning" id="resetBtn" disabled>Mulai dari awal</button>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="panel p-4 h-100">
                <h3 class="h6 mb-3">Capture Fase</h3>
                <div id="captureGallery" class="gallery-grid"></div>
            </div>
        </div>
    </div>
</div>
<script>
window.__teachers = <?php echo json_encode($teachersPayload, 15, 512) ?>;
window.__phases = <?php echo json_encode($phases, 15, 512) ?>;
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('build/libs/sweetalert2/sweetalert2.all.min.js')); ?>"></script>
<script src="<?php echo e(asset('models/face-api.js')); ?>"></script>
<script src="<?php echo e(asset('js/face-recognition.js')); ?>"></script>
<script src="<?php echo e(asset('js/face-recognition-v2.js')); ?>"></script>
<script>
(function () {
    const teachers = window.__teachers || [];
    const phases = window.__phases || [];
    const teacherSelect = document.getElementById('teacherSelect');
    const phaseList = document.getElementById('phaseList');
    const video = document.getElementById('video');
    const startBtn = document.getElementById('startBtn');
    const finishBtn = document.getElementById('finishBtn');
    const resetBtn = document.getElementById('resetBtn');
    const captureGallery = document.getElementById('captureGallery');
    const statusMini = document.getElementById('statusMini');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const faceRecognition = new window.FaceRecognition();
    const selectedMadrasahId = <?php echo json_encode($selectedMadrasahId, 15, 512) ?>;

    let session = null;
    let currentPhaseIndex = 0;
    let busy = false;
    let streamReady = false;
    let phaseResults = [];
    let autoRunning = false;

    function renderPhases() {
        phaseList.innerHTML = phases.map((phase, index) => {
            const state = index < currentPhaseIndex ? 'done' : index === currentPhaseIndex ? 'active' : '';
            return `<div class="phase-item ${state}" data-index="${index}">
                <div class="fw-bold">${index + 1}. ${phase.label}</div>
                <div class="small text-secondary">${state === 'done' ? 'Tersimpan' : state === 'active' ? 'Fase aktif' : 'Menunggu giliran'}</div>
            </div>`;
        }).join('');
    }

    function renderGallery() {
        if (!phaseResults.length) {
            captureGallery.innerHTML = '<div class="gallery-empty">Hasil capture fase akan tampil di sini.</div>';
            return;
        }

        const phaseByKey = new Map(phases.map((phase) => [phase.key, phase]));
        const captured = phaseResults
            .filter(Boolean)
            .slice()
            .sort((left, right) => (right.capture_index || 0) - (left.capture_index || 0));
        const missing = phases.filter((phase) => !phaseResults.some((result) => result?.phase_key === phase.key));
        const ordered = [
            ...captured.map((item) => ({ phase: phaseByKey.get(item.phase_key), item })),
            ...missing.map((phase) => ({ phase, item: null })),
        ];

        captureGallery.innerHTML = ordered.map(({ phase, item }, index) => {
            const phaseLabel = {
                front: 'Depan',
                front_2: 'Depan 2',
                left: 'Kiri',
                right: 'Kanan',
                up: 'Atas',
                down: 'Bawah',
            }[phase.key] || phase.label;
            const stateIcon = item ? 'bx-check' : 'bx-x';
            const stateClass = item ? 'is-ok' : 'is-missing';

            return `
                <div class="gallery-item">
                    <span class="gallery-state ${stateClass}" aria-hidden="true">
                        <i class="bx ${stateIcon}"></i>
                    </span>
                    ${item ? `<img src="${item.captured_image}" alt="${phaseLabel}">` : `<div class="gallery-empty" style="grid-column:auto; margin:0; min-height: 96px; display:flex; align-items:center; justify-content:center;">Belum</div>`}
                    <div class="gallery-caption">${item ? `#${item.capture_index} ${phaseLabel}` : `${index + 1}. ${phaseLabel}`}</div>
                </div>
            `;
        }).join('');
    }

    function setStatus(message) {
        if (statusMini) {
            statusMini.innerHTML = `<span class="status-mini-label">Status</span>${message}`;
        }
    }

    async function initCamera() {
        if (streamReady) return;
        await faceRecognition.initializeCamera(video);
        streamReady = true;
    }

    async function startSession() {
        const userId = teacherSelect.value;
        if (!userId) {
            await Swal.fire({
                icon: 'warning',
                title: 'Pilih guru terlebih dahulu',
                text: 'Silakan pilih user/guru sebelum memulai pendaftaran wajah.',
                confirmButtonText: 'Mengerti',
            });
            return;
        }

        // Keep the current server session after a failed phase so completed
        // captures are not repeated from the beginning.
        if (session) {
            await runAutoEnrollment();
            return;
        }

        busy = true;
        startBtn.disabled = true;
        setStatus('Memuat model wajah...');
        await faceRecognition.loadModels();
        await initCamera();
        const response = await fetch(<?php echo json_encode(route('kiosk.face-enrollment.sessions.start'), 15, 512) ?>, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                user_id: Number(userId),
                metadata: { madrasah_id: selectedMadrasahId },
            }),
        });

        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Gagal membuat sesi.');
        }

        session = data.session;
        teacherSelect.disabled = true;
        currentPhaseIndex = 0;
        phaseResults = [];
        finishBtn.disabled = true;
        renderPhases();
        renderGallery();
        setStatus('Sesi dimulai. Menunggu wajah terdeteksi.');
        await runAutoEnrollment();
        busy = false;
        startBtn.disabled = false;
    }

    async function detectPhaseCapture(phase) {
        const detectorOptions = {
            onStatus: (message) => setStatus(message),
            onGuideState: () => {},
            onDiagnostic: () => {},
        };

        const waitMs = (phase.key === 'up' || phase.key === 'down') ? 5000 : 7000;

        if (phase.key === 'front' || phase.key === 'front_2') {
            setStatus('Arahkan wajah ke kamera.');
            await faceRecognition.waitForStableSingleFace(video, detectorOptions, waitMs, 2, false);
            const burst = await faceRecognition.captureBurstFrames(video, { count: 4, intervalMs: 150, warmupMs: 180 });

            return {
                frames: burst.frames,
                captured_image: burst.best_frame || faceRecognition.captureFrame(video, { mirror: false }),
                detail: phase.key,
            };
        }

        if (phase.key === 'left' || phase.key === 'right') {
            setStatus(phase.key === 'left' ? 'Tengok ke kiri.' : 'Tengok ke kanan.');
            await faceRecognition.loadRecognitionModel();
            const result = await faceRecognition.waitForHeadTurnChallenge(
                video,
                phase.key === 'left' ? 'turn_left' : 'turn_right',
                detectorOptions,
                waitMs,
            );
            const burst = await faceRecognition.captureBurstFrames(video, { count: 4, intervalMs: 150, warmupMs: 180 });

            return {
                frames: burst.frames,
                captured_image: burst.best_frame || faceRecognition.captureFrame(video, { mirror: false }),
                detail: result.detail,
            };
        }

        if (phase.key === 'up' || phase.key === 'down') {
            setStatus(phase.key === 'up' ? 'Tengok ke atas.' : 'Tengok ke bawah.');
            await faceRecognition.loadRecognitionModel();
            const result = await detectVerticalCapture(phase.key, detectorOptions, waitMs);
            const burst = await faceRecognition.captureBurstFrames(video, { count: 4, intervalMs: 150, warmupMs: 180 });

            return {
                frames: burst.frames,
                captured_image: burst.best_frame || faceRecognition.captureFrame(video, { mirror: false }),
                detail: result.detail,
            };
        }

        throw new Error('Fase tidak dikenali.');
    }

    async function detectVerticalCapture(direction, callbacks, timeoutMs) {
        const isUp = direction === 'up';
        const startedAt = Date.now();
        let stableHits = 0;
        let baseline = null;
        let bestScore = 0;

        const verticalLookRatio = (landmarks) => {
            const noseTip = landmarks.getNose()[3];
            const leftEye = landmarks.getLeftEye()[0];
            const rightEye = landmarks.getRightEye()[3];
            const mouth = landmarks.getMouth();

            if (!noseTip || !leftEye || !rightEye || !Array.isArray(mouth) || mouth.length === 0) {
                return null;
            }

            const eyeCenterY = (leftEye.y + rightEye.y) / 2;
            const mouthCenterY = mouth.reduce((total, point) => total + point.y, 0) / mouth.length;
            const span = mouthCenterY - eyeCenterY;

            if (span <= 0) {
                return null;
            }

            return (noseTip.y - eyeCenterY) / span;
        };

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await faceRecognition.detectSingleFaceGeometry(video, callbacks, {
                strict: false,
                profile: 'enrollment',
                allowFallback: false,
            });

            if (!detection?.landmarks) {
                stableHits = 0;
                await new Promise((resolve) => window.setTimeout(resolve, 45));
                continue;
            }

            const ratio = verticalLookRatio(detection.landmarks);
            if (ratio === null) {
                stableHits = 0;
                await new Promise((resolve) => window.setTimeout(resolve, 45));
                continue;
            }

            if (baseline === null) {
                baseline = ratio;
            }

            const delta = ratio - baseline;
            const directionalDelta = isUp ? -delta : delta;
            const threshold = isUp ? 0.010 : 0.007;

            if (directionalDelta >= threshold) {
                stableHits += 1;
                bestScore = Math.max(bestScore, directionalDelta);

                if (stableHits >= 2) {
                    return {
                        score: Math.min(0.97, 0.66 + (bestScore * (isUp ? 14 : 18))),
                        detail: direction,
                    };
                }

                await new Promise((resolve) => window.setTimeout(resolve, 45));
                continue;
            }

            stableHits = 0;
            await new Promise((resolve) => window.setTimeout(resolve, 45));
        }

        throw new Error(isUp
            ? 'Arah ke atas belum cukup terbaca. Angkat dagu sedikit lebih jelas.'
            : 'Arah ke bawah belum cukup terbaca. Turunkan pandangan sedikit lebih jelas.');
    }

    async function capturePhase(phase, phaseIndex) {
        const scan = await detectPhaseCapture(phase);

        const payload = {
            phase_key: phase.key,
            phase_label: phase.label,
            capture_index: phaseIndex + 1,
            captured_image: scan.captured_image,
            frames: scan.frames,
            metadata: {
                phase_order: phaseIndex + 1,
                auto_captured: true,
                detail: scan.detail || null,
            },
        };

        const response = await fetch(<?php echo json_encode(url('/kiosk-face-enrollment/sessions'), 15, 512) ?> + `/${session.id}/captures`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();
        if (!data.success) {
            const reason = data.code || data.reason || data.notes;
            const score = data.liveness_score !== undefined
                ? ` (liveness ${data.liveness_score})`
                : data.quality_score !== undefined
                    ? ` (quality ${data.quality_score})`
                    : '';
            throw new Error(`${data.message || 'Capture gagal disimpan.'}${reason ? ` [${reason}]` : ''}${score}`);
        }

        phaseResults[phaseIndex] = payload;
        currentPhaseIndex = phaseIndex + 1;
        renderPhases();
        renderGallery();
    }

    async function runAutoEnrollment() {
        if (!session || autoRunning) return;
        autoRunning = true;
        busy = true;
        startBtn.disabled = true;
        finishBtn.disabled = true;
        resetBtn.disabled = true;

        try {
            for (let index = currentPhaseIndex; index < phases.length; index += 1) {
                const phase = phases[index];
                await capturePhase(phase, index);
            }

            finishBtn.disabled = false;
            startBtn.textContent = 'Semua fase selesai';
            setStatus('Semua fase selesai. Tekan simpan final.');
        } finally {
            busy = false;
            autoRunning = false;
            startBtn.disabled = currentPhaseIndex >= phases.length;
            resetBtn.disabled = !session;
            if (currentPhaseIndex < phases.length) {
                startBtn.textContent = 'Lanjutkan sesi';
            }
        }
    }

    async function finishSession() {
        if (!session || busy) return;
        busy = true;
        finishBtn.disabled = true;

        const response = await fetch(<?php echo json_encode(url('/kiosk-face-enrollment/sessions'), 15, 512) ?> + `/${session.id}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                metadata: {
                    phase_results: phaseResults.map((result) => ({
                        phase_key: result.phase_key,
                        phase_label: result.phase_label,
                        capture_index: result.capture_index,
                        detail: result.metadata?.detail || null,
                    })),
                },
            }),
        });

        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || 'Gagal menyimpan data final.');
        }

        const teacherName = data.teacher?.name || teacherSelect.options[teacherSelect.selectedIndex]?.textContent?.trim() || 'Guru';
        await Swal.fire({
            icon: 'success',
            title: 'Berhasil disimpan',
            text: `Data biometrik wajah ${teacherName} berhasil disimpan. Capture terbaru ditampilkan paling atas.`,
            confirmButtonText: 'Selesai',
        });

        setStatus(`Data wajah ${teacherName} tersimpan. Capture terbaru berada di urutan paling atas.`);
        session = null;
        currentPhaseIndex = 0;
        renderPhases();
        renderGallery();
        teacherSelect.disabled = false;
        startBtn.textContent = 'Mulai sesi';
        startBtn.disabled = false;
        resetBtn.disabled = true;
        busy = false;
    }

    async function resetSession() {
        if (!session || busy) return;

        const confirmation = await Swal.fire({
            icon: 'warning',
            title: 'Mulai dari awal?',
            text: 'Semua capture pada sesi draft ini akan dihapus dan enam fase akan dimulai ulang.',
            showCancelButton: true,
            confirmButtonText: 'Ya, mulai ulang',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        });

        if (!confirmation.isConfirmed) return;

        busy = true;
        resetBtn.disabled = true;
        try {
            const response = await fetch(<?php echo json_encode(url('/kiosk-face-enrollment/sessions'), 15, 512) ?> + `/${session.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });
            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Sesi gagal direset.');
            }

            faceRecognition.stopCamera(video);
            streamReady = false;
            session = null;
            currentPhaseIndex = 0;
            phaseResults = [];
            teacherSelect.disabled = false;
            startBtn.textContent = 'Mulai sesi';
            startBtn.disabled = false;
            finishBtn.disabled = true;
            renderPhases();
            renderGallery();
            setStatus('Sesi direset. Pilih guru lalu mulai dari fase pertama.');

            await Swal.fire({
                icon: 'success',
                title: 'Sesi direset',
                text: 'Pendaftaran siap dimulai dari fase pertama.',
                timer: 1800,
                showConfirmButton: false,
            });
        } catch (error) {
            console.error('Reset enrollment gagal:', error);
            await Swal.fire({
                icon: 'error',
                title: 'Reset gagal',
                text: error.message || 'Sesi belum dapat direset.',
                confirmButtonText: 'Mengerti',
            });
        } finally {
            busy = false;
            resetBtn.disabled = !session;
        }
    }

    startBtn.addEventListener('click', () => startSession().catch((error) => {
        console.error('Enrollment gagal:', error);
        setStatus(error.message || 'Enrollment gagal.');
        void Swal.fire({
            icon: 'error',
            title: 'Enrollment gagal',
            text: error.message || 'Pendaftaran wajah gagal diproses.',
            confirmButtonText: 'Coba lagi',
        });
        busy = false;
        startBtn.disabled = false;
        resetBtn.disabled = !session;
    }));

    finishBtn.addEventListener('click', () => finishSession().catch((error) => {
        console.error('Penyimpanan enrollment gagal:', error);
        setStatus(error.message || 'Gagal menyimpan data final.');
        void Swal.fire({
            icon: 'error',
            title: 'Gagal menyimpan',
            text: error.message || 'Data wajah belum berhasil disimpan.',
            confirmButtonText: 'Coba lagi',
        });
        busy = false;
        finishBtn.disabled = false;
        resetBtn.disabled = !session;
    }));

    resetBtn.addEventListener('click', () => resetSession());

    teacherSelect.addEventListener('change', () => {
        void teacherSelect.value;
    });

    renderPhases();
    renderGallery();
    initCamera().catch(() => {});
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master-without-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/lpmnudiymacpro/Documents/Project Nuist/nuist/resources/views/kiosk/face-enrollment-kiosk.blade.php ENDPATH**/ ?>