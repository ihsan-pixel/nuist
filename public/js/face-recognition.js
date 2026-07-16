class FaceRecognition {
    constructor() {
        this.modelsLoaded = false;
        this.detectionModelsLoaded = false;
        this.recognitionModelsLoaded = false;
        this.activeStream = null;
        this.modelBaseUri = '/models';
        this.recentDetectionMemoryMs = 450;
        this.lastGeometryDetection = null;
        this.lastGeometryDetectedAt = 0;
        this.detectorOptions = {
            inputSize: 224,
            scoreThreshold: 0.4,
        };
        this.minimumFaceWidthRatio = 0.16;
        this.maximumEyeTiltDegrees = 22;
        this.enrollmentSharpnessThreshold = 0.11;
        this.enrollmentMotionThreshold = 0.075;
        this.enrollmentHoldMs = 320;
        this.challengeBlinkLeadMs = 850;
        this.challengeActionLeadMs = 1050;
        this.attendanceChallengePool = ['turn_left', 'turn_right', 'look_up', 'look_down', 'mouth_open'];
    }

    async loadModels() {
        if (this.recognitionModelsLoaded) {
            return true;
        }

        await this.loadDetectionModels();

        try {
            await faceapi.nets.faceRecognitionNet.loadFromUri(this.modelBaseUri);
        } catch (error) {
            const rawMessage = String(error?.message || error || '');

            if (
                rawMessage.includes('Based on the provided shape')
                || rawMessage.includes('tensor should have')
                || rawMessage.includes('Failed to fetch')
                || rawMessage.includes('404')
            ) {
                throw new Error('File model scan wajah di server tidak lengkap atau rusak. Hubungi admin untuk memperbarui model wajah.');
            }

            throw error;
        }

        this.recognitionModelsLoaded = true;
        this.modelsLoaded = true;
        return true;
    }

    async loadDetectionModels() {
        if (this.detectionModelsLoaded) {
            this.modelsLoaded = this.recognitionModelsLoaded;
            return true;
        }

        if (typeof faceapi === 'undefined') {
            throw new Error('Library scan wajah belum tersedia.');
        }

        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(this.modelBaseUri),
                faceapi.nets.faceLandmark68Net.loadFromUri(this.modelBaseUri),
            ]);
        } catch (error) {
            const rawMessage = String(error?.message || error || '');

            if (
                rawMessage.includes('Based on the provided shape')
                || rawMessage.includes('tensor should have')
                || rawMessage.includes('Failed to fetch')
                || rawMessage.includes('404')
            ) {
                throw new Error('File model scan wajah di server tidak lengkap atau rusak. Hubungi admin untuk memperbarui model wajah.');
            }

            throw error;
        }

        this.detectionModelsLoaded = true;
        this.modelsLoaded = this.recognitionModelsLoaded;
        return true;
    }

    async initializeCamera(videoElement) {
        if (!videoElement) {
            throw new Error('Elemen video tidak tersedia.');
        }

        this.stopCamera(videoElement);

        videoElement.autoplay = true;
        videoElement.muted = true;
        videoElement.playsInline = true;
        videoElement.setAttribute('autoplay', 'autoplay');
        videoElement.setAttribute('muted', 'muted');
        videoElement.setAttribute('playsinline', 'playsinline');
        videoElement.setAttribute('webkit-playsinline', 'webkit-playsinline');

        const stream = await navigator.mediaDevices.getUserMedia({
            audio: false,
            video: {
                facingMode: 'user',
                width: { ideal: 640 },
                height: { ideal: 840 },
                frameRate: { ideal: 24, max: 30 },
            },
        });

        this.activeStream = stream;
        videoElement.srcObject = stream;

        await new Promise((resolve) => {
            if (videoElement.readyState >= HTMLMediaElement.HAVE_CURRENT_DATA) {
                resolve(true);
                return;
            }

            const markReady = () => {
                videoElement.removeEventListener('loadedmetadata', markReady);
                videoElement.removeEventListener('loadeddata', markReady);
                videoElement.removeEventListener('canplay', markReady);
                resolve(true);
            };

            videoElement.addEventListener('loadedmetadata', markReady, { once: true });
            videoElement.addEventListener('loadeddata', markReady, { once: true });
            videoElement.addEventListener('canplay', markReady, { once: true });
        });

        try {
            await videoElement.play();
        } catch (error) {
            throw new Error('Kamera sudah diizinkan tetapi browser belum bisa menampilkan stream video. Muat ulang halaman lalu coba lagi.');
        }

        await new Promise((resolve) => window.setTimeout(resolve, 60));

        return true;
    }

    stopCamera(videoElement = null) {
        if (videoElement) {
            videoElement.pause();
            videoElement.srcObject = null;
        }

        if (this.activeStream) {
            this.activeStream.getTracks().forEach((track) => track.stop());
            this.activeStream = null;
        }

        this.lastGeometryDetection = null;
        this.lastGeometryDetectedAt = 0;
    }

    async waitForQuickAlignedFace(videoElement, callbacks = {}, timeoutMs = 4200, stableHitsRequired = 2) {
        await this.loadDetectionModels();

        const startedAt = Date.now();
        let stableHits = 0;

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks, {
                strict: true,
                allowFallback: false,
            });

            if (detection) {
                stableHits += 1;
                this.emit(callbacks.onStatus, stableHits >= stableHitsRequired
                    ? 'Wajah sudah sesuai. Mengambil gambar untuk mencocokkan data.'
                    : 'Wajah terdeteksi. Menstabilkan posisi sebentar.');
                this.emit(callbacks.onGuideState, {
                    state: stableHits >= stableHitsRequired ? 'success' : 'aligned',
                    message: stableHits >= stableHitsRequired
                        ? 'Wajah sesuai. Gambar diambil.'
                        : 'Posisi wajah sudah sesuai. Tahan sebentar.',
                });

                if (stableHits >= stableHitsRequired) {
                    return detection;
                }

                await this.delay(45);
                continue;
            }

            stableHits = 0;
            this.emit(callbacks.onStatus, 'Arahkan satu wajah ke tengah oval agar sistem langsung mencocokkan data.');
            await this.delay(55);
        }

        throw new Error('Wajah belum terbaca dengan stabil. Pastikan satu wajah saja terlihat dan posisinya tepat di dalam oval.');
    }

    async performEnrollmentScan(videoElement, callbacks = {}) {
        await this.loadModels();

        const livenessChallenges = await this.runEnrollmentScanSequence(videoElement, callbacks);
        const descriptor = await this.captureFaceDescriptor(videoElement, {
            strict: true,
            profile: 'enrollment',
        });

        return {
            face_descriptor: Array.from(descriptor),
            liveness_score: 1,
            liveness_challenges: livenessChallenges,
            captured_image: this.captureFrame(videoElement),
        };
    }

    async performAttendanceScan(videoElement, callbacks = {}) {
        await this.loadModels();

        const initialDescriptor = await this.verifyFaceMatchBeforeChallenges(videoElement, callbacks);
        const scanResult = await this.runAttendanceRiskScanSequence(videoElement, callbacks);
        const descriptor = await this.captureFaceDescriptor(videoElement);

        return {
            face_descriptor: Array.from(descriptor),
            liveness_score: scanResult.liveness_score,
            liveness_challenges: scanResult.challenges,
            captured_image: this.captureFrame(videoElement),
            initial_face_descriptor: Array.from(initialDescriptor),
        };
    }

    async verifyFaceMatchBeforeChallenges(videoElement, callbacks = {}) {
        this.emit(callbacks.onInstruction, 'Memeriksa kecocokan wajah.');
        this.emit(callbacks.onStatus, 'Memeriksa kecocokan wajah dengan data terdaftar.');
        this.emit(callbacks.onGuideState, {
            state: 'processing',
            message: 'Memeriksa kecocokan wajah dengan data terdaftar.',
        });

        await this.waitForStableSingleFace(videoElement, callbacks, 8000);
        const descriptor = await this.captureFaceDescriptor(videoElement, {
            strict: true,
            allowFallback: false,
        });

        if (typeof callbacks.onFaceMatchCheck === 'function') {
            const verificationResult = await callbacks.onFaceMatchCheck(Array.from(descriptor));

            if (!verificationResult?.face_verified) {
                throw new Error(verificationResult?.message || 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.');
            }

            this.emit(callbacks.onStatus, 'Wajah cocok dengan data terdaftar. Lanjut ke challenge.');
            this.emit(callbacks.onGuideState, {
                state: 'aligned',
                message: 'Wajah cocok. Lanjut ke challenge berikutnya.',
            });
        }

        return descriptor;
    }

    async runEnrollmentScanSequence(videoElement, callbacks = {}) {
        const results = [];

        this.emit(callbacks.onInstruction, 'Posisikan wajah tepat di dalam oval.');
        this.emit(callbacks.onChallengeState, 'align', 'active');
        await this.waitForPreciseEnrollmentAlignment(videoElement, callbacks);
        results.push({
            type: 'face_aligned',
            passed: true,
            timestamp: Date.now(),
        });
        this.emit(callbacks.onChallengeState, 'align', 'done');

        this.emit(callbacks.onInstruction, 'Posisi sudah pas. Gambar akan diambil otomatis.');
        this.emit(callbacks.onChallengeState, 'steady', 'active');
        await this.waitForEnrollmentAutoCapture(videoElement, callbacks);
        results.push({
            type: 'face_stable',
            passed: true,
            timestamp: Date.now(),
        });
        this.emit(callbacks.onChallengeState, 'steady', 'done');

        this.emit(callbacks.onInstruction, 'Wajah terbaca. Menyelesaikan scan.');
        this.emit(callbacks.onStatus, 'Scan wajah sedang diselesaikan.');
        this.emit(callbacks.onChallengeState, 'done', 'active');
        await this.delay(240);
        this.emit(callbacks.onChallengeState, 'done', 'done');
        results.push({
            type: 'face_captured',
            passed: true,
            timestamp: Date.now(),
        });

        return results;
    }

    async waitForPreciseEnrollmentAlignment(videoElement, callbacks = {}, timeoutMs = 10000) {
        const startedAt = Date.now();

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks, {
                strict: true,
                profile: 'enrollment',
            });

            if (detection) {
                this.emit(callbacks.onStatus, 'Wajah sudah tepat di oval. Tahan posisi sebentar.');
                this.emit(callbacks.onGuideState, {
                    state: 'aligned',
                    message: 'Posisi wajah sudah tepat di oval.',
                });
                return detection;
            }

            this.emit(callbacks.onStatus, 'Pusatkan wajah tepat di dalam oval dan sesuaikan jaraknya.');
            await this.delay(45);
        }

        throw new Error('Wajah belum tepat di dalam oval. Dekatkan atau geser posisi wajah hingga pas pada bingkai.');
    }

    async waitForEnrollmentAutoCapture(videoElement, callbacks = {}, holdMs = this.enrollmentHoldMs, timeoutMs = 4200) {
        const startedAt = Date.now();
        let heldSince = null;
        let previousSignature = null;
        let stableFrames = 0;

        this.emit(callbacks.onCaptureProgress, 0);
        this.emit(callbacks.onEnrollmentQuality, {
            progress: 0,
            ready: false,
            sharpEnough: false,
            stableEnough: false,
            sharpness: 0,
            motion: null,
        });

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks, {
                strict: true,
                profile: 'enrollment',
                allowFallback: false,
            });

            if (!detection) {
                heldSince = null;
                stableFrames = 0;
                previousSignature = null;
                this.emit(callbacks.onCaptureProgress, 0);
                this.emit(callbacks.onEnrollmentQuality, {
                    progress: 0,
                    ready: false,
                    sharpEnough: false,
                    stableEnough: false,
                    sharpness: 0,
                    motion: null,
                });
                this.emit(callbacks.onStatus, 'Posisi wajah berubah. Kembalikan wajah tepat ke oval.');
                await this.delay(45);
                continue;
            }

            const readiness = this.evaluateEnrollmentCaptureReadiness(videoElement, detection, previousSignature);
            previousSignature = readiness.signature;
            this.emit(callbacks.onEnrollmentQuality, {
                progress: readiness.ready ? (heldSince === null ? 0 : this.clamp((Date.now() - heldSince) / holdMs, 0, 1)) : 0,
                ready: readiness.ready,
                sharpEnough: readiness.sharpEnough,
                stableEnough: readiness.stableEnough,
                sharpness: readiness.sharpness,
                motion: readiness.motion,
            });

            if (!readiness.ready) {
                heldSince = null;
                stableFrames = 0;
                this.emit(callbacks.onCaptureProgress, 0);

                if (!readiness.sharpEnough) {
                    this.emit(callbacks.onStatus, 'Wajah sudah pas. Tahan lebih tenang sebentar agar tidak blur.');
                } else if (!readiness.stableEnough) {
                    this.emit(callbacks.onStatus, 'Wajah sudah di tengah. Jangan bergerak agar scan cepat penuh.');
                } else {
                    this.emit(callbacks.onStatus, 'Menstabilkan pembacaan wajah.');
                }

                await this.delay(40);
                continue;
            }

            stableFrames += 1;

            if (heldSince === null && stableFrames >= 1) {
                heldSince = Date.now();
                this.emit(callbacks.onStatus, 'Posisi tepat, stabil, dan tajam. Mengambil gambar otomatis.');
            }

            const holdElapsed = heldSince === null ? 0 : Date.now() - heldSince;
            const holdProgress = this.clamp(holdElapsed / holdMs, 0, 1);
            this.emit(callbacks.onCaptureProgress, holdProgress);
            this.emit(callbacks.onEnrollmentQuality, {
                progress: holdProgress,
                ready: readiness.ready,
                sharpEnough: readiness.sharpEnough,
                stableEnough: readiness.stableEnough,
                sharpness: readiness.sharpness,
                motion: readiness.motion,
            });

            if (heldSince !== null && holdElapsed >= holdMs) {
                this.emit(callbacks.onCaptureProgress, 1);
                this.emit(callbacks.onEnrollmentQuality, {
                    progress: 1,
                    ready: true,
                    sharpEnough: readiness.sharpEnough,
                    stableEnough: readiness.stableEnough,
                    sharpness: readiness.sharpness,
                    motion: readiness.motion,
                });
                this.emit(callbacks.onGuideState, {
                    state: 'success',
                    message: 'Wajah berhasil diambil otomatis.',
                });

                return true;
            }

            await this.delay(35);
        }

        throw new Error('Wajah belum cukup stabil di dalam oval. Tahan posisi wajah hingga sistem mengambil gambar otomatis.');
    }

    async runAttendanceRiskScanSequence(videoElement, callbacks = {}) {
        const results = [];

        this.emit(callbacks.onInstruction, 'Posisikan wajah di dalam oval.');
        this.emit(callbacks.onChallengeState, 'align', 'active');
        await this.waitForStableSingleFace(videoElement, callbacks);
        results.push({
            type: 'face_aligned',
            passed: true,
            timestamp: Date.now(),
        });
        this.emit(callbacks.onChallengeState, 'align', 'done');

        const passiveSignals = await this.collectPassiveSignals(videoElement, callbacks, 850);
        results.push({
            type: 'lighting',
            passed: passiveSignals.lighting_passed,
            score: passiveSignals.lighting_score,
            detail: passiveSignals.lighting_label,
            timestamp: Date.now(),
        });
        results.push({
            type: 'motion_consistency',
            passed: passiveSignals.motion_score >= 0.12,
            score: passiveSignals.motion_score,
            detail: passiveSignals.motion_label,
            timestamp: Date.now(),
        });

        if (!passiveSignals.quality_ready) {
            throw new Error('Kualitas kamera belum cukup baik. Pastikan cahaya cukup dan wajah tetap jelas di bingkai.');
        }

        this.emit(callbacks.onChallengeState, 'blink', 'active');
        let blinkResult = passiveSignals.blink_result || null;

        if (!blinkResult) {
            this.emit(callbacks.onInstruction, 'Tahan wajah lurus. Sistem menyiapkan deteksi kedip.');
            this.emit(callbacks.onStatus, 'Menyiapkan pembacaan kedip.');
            blinkResult = await this.waitForBlinkChallenge(videoElement, callbacks);
        } else {
            this.emit(callbacks.onInstruction, 'Kedipan sudah terbaca. Lanjut ke verifikasi berikutnya.');
            this.emit(callbacks.onStatus, 'Kedipan sudah terbaca otomatis.');
            this.emit(callbacks.onGuideState, {
                state: 'success',
                message: 'Kedipan berhasil terbaca.',
            });
        }

        results.push({
            type: 'blink',
            passed: true,
            score: blinkResult.score,
            detail: 'blink_detected',
            timestamp: Date.now(),
        });
        this.emit(callbacks.onChallengeState, 'blink', 'done');

        const randomChallenge = this.pickRandomAttendanceChallenge();
        this.emit(callbacks.onInstruction, 'Tahan wajah lurus. Sistem menyiapkan instruksi berikutnya.');
        this.emit(callbacks.onStatus, 'Menyiapkan challenge berikutnya.');
        this.emit(callbacks.onChallengeState, 'challenge', 'active');
        const randomChallengeResult = await this.runRandomChallenge(videoElement, randomChallenge, callbacks);
        results.push({
            type: randomChallenge,
            passed: true,
            score: randomChallengeResult.score,
            detail: randomChallengeResult.detail,
            timestamp: Date.now(),
        });
        this.emit(callbacks.onChallengeState, 'challenge', 'done');

        const riskScore = this.calculateAttendanceRiskScore({
            passiveSignals,
            blinkScore: blinkResult.score,
            randomChallengeScore: randomChallengeResult.score,
        });

        results.push({
            type: 'screen_replay_risk',
            passed: riskScore.screen_replay_risk <= 0.55,
            score: riskScore.screen_replay_risk,
            detail: riskScore.risk_label,
            timestamp: Date.now(),
        });
        results.push({
            type: 'risk_score',
            passed: riskScore.overall_score >= 0.78,
            score: riskScore.overall_score,
            detail: 'overall_attendance_risk',
            timestamp: Date.now(),
        });

        if (riskScore.screen_replay_risk > 0.55) {
            throw new Error('Scan terdeteksi berisiko seperti layar atau replay video. Gunakan wajah asli di depan kamera.');
        }

        if (riskScore.overall_score < 0.78) {
            throw new Error('Verifikasi wajah belum cukup aman. Ulangi scan dengan wajah asli dan ikuti arahan kamera.');
        }

        this.emit(callbacks.onInstruction, 'Challenge selesai. Menyelesaikan verifikasi.');
        this.emit(callbacks.onStatus, 'Challenge wajah selesai. Menyelesaikan verifikasi.');
        this.emit(callbacks.onChallengeState, 'done', 'active');
        await this.waitForSteadyFaceHold(videoElement, callbacks, 260, 1800);
        this.emit(callbacks.onChallengeState, 'done', 'done');
        results.push({
            type: 'face_captured',
            passed: true,
            timestamp: Date.now(),
        });

        return {
            liveness_score: riskScore.overall_score,
            challenges: results,
        };
    }

    async waitForStableSingleFace(videoElement, callbacks = {}, timeoutMs = 9000) {
        const startedAt = Date.now();

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks, {
                strict: true,
                allowFallback: false,
            });

            if (detection) {
                this.emit(callbacks.onStatus, 'Wajah terdeteksi. Pertahankan posisi di tengah oval.');
                this.emit(callbacks.onGuideState, {
                    state: 'aligned',
                    message: 'Posisi wajah sudah pas. Tahan sebentar.',
                });
                return detection;
            }

            this.emit(callbacks.onStatus, 'Arahkan satu wajah ke tengah oval dan dekatkan sedikit ke kamera.');
            await this.delay(65);
        }

        throw new Error('Wajah tidak terdeteksi dengan stabil. Pastikan pencahayaan cukup dan hanya satu wajah di layar.');
    }

    async waitForSteadyFaceHold(videoElement, callbacks = {}, holdMs = 420, timeoutMs = 5200) {
        const startedAt = Date.now();
        let heldSince = null;

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);

            if (!detection) {
                heldSince = null;
                this.emit(callbacks.onStatus, 'Menstabilkan pembacaan wajah. Jangan terlalu banyak bergerak.');
                await this.delay(85);
                continue;
            }

            if (heldSince === null) {
                heldSince = Date.now();
                this.emit(callbacks.onStatus, 'Wajah sudah pas. Tahan posisi sebentar.');
            }

            if (Date.now() - heldSince >= holdMs) {
                this.emit(callbacks.onGuideState, {
                    state: 'success',
                    message: 'Wajah berhasil dibaca.',
                });

                return true;
            }

            await this.delay(60);
        }

        throw new Error('Wajah belum terbaca dengan stabil. Coba tahan posisi wajah lebih tenang.');
    }

    async waitForBlinkChallenge(videoElement, callbacks = {}, timeoutMs = 8000) {
        const calibrationDeadline = Date.now() + 1800;
        const baselineSamples = [];
        const recentEars = [];
        let missedFrames = 0;

        this.emit(callbacks.onStatus, 'Posisi sudah sesuai. Kedip akan terbaca otomatis.');
        this.emit(callbacks.onGuideState, {
            state: 'aligned',
            message: 'Wajah sudah pas. Kedip akan terbaca otomatis.',
        });

        while (Date.now() < calibrationDeadline && baselineSamples.length < 6) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);

            if (!detection?.landmarks) {
                missedFrames += 1;
                if (missedFrames >= 3) {
                    this.emit(callbacks.onStatus, 'Wajah belum stabil. Hadapkan wajah lurus ke kamera.');
                }
                await this.delay(80);
                continue;
            }

            missedFrames = 0;
            const ear = this.averageEyeAspectRatio(detection.landmarks);
            if (ear > 0.16) {
                baselineSamples.push(ear);
            }

            await this.delay(28);
        }

        if (baselineSamples.length < 4) {
            throw new Error('Mata terbuka belum terbaca dengan jelas. Dekatkan wajah dan pastikan pencahayaan cukup.');
        }

        const baselineEar = this.median(baselineSamples) || 0.24;
        const closedThreshold = Math.max(0.156, baselineEar - 0.032);
        const reopenThreshold = Math.max(0.17, baselineEar - 0.004);
        const minDrop = Math.max(0.016, baselineEar * 0.075);
        const suddenDropThreshold = Math.max(0.011, baselineEar * 0.048);
        let blinkClosedSeen = false;
        let blinkReopenedSeen = false;
        let reopenedFrames = 0;
        let bestDrop = 0;
        let blinkCandidateAt = null;
        let previousEar = null;
        let previousMinEyeEar = null;

        await this.presentTimedChallengeInstruction(
            callbacks,
            'Kedip satu kali untuk verifikasi.',
            this.challengeBlinkLeadMs,
        );
        this.emit(callbacks.onStatus, 'Deteksi kedip aktif. Cukup kedip satu kali.');
        const startedAt = Date.now();

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);

            if (!detection?.landmarks) {
                blinkClosedSeen = false;
                blinkCandidateAt = null;
                reopenedFrames = 0;
                previousEar = null;
                previousMinEyeEar = null;
                recentEars.length = 0;
                await this.delay(90);
                continue;
            }

            const leftEar = this.eyeAspectRatio(detection.landmarks.getLeftEye());
            const rightEar = this.eyeAspectRatio(detection.landmarks.getRightEye());
            const ear = (leftEar + rightEar) / 2;
            const minEyeEar = Math.min(leftEar, rightEar);
            recentEars.push(ear);
            if (recentEars.length > 3) {
                recentEars.shift();
            }

            const smoothedEar = this.average(recentEars) || ear;
            const instantDrop = baselineEar - ear;
            const smoothedDrop = baselineEar - smoothedEar;
            const suddenDrop = previousEar !== null ? (previousEar - ear) : 0;
            const suddenMinEyeDrop = previousMinEyeEar !== null ? (previousMinEyeEar - minEyeEar) : 0;
            bestDrop = Math.max(bestDrop, instantDrop, smoothedDrop, suddenDrop, suddenMinEyeDrop);

            if (!blinkClosedSeen) {
                const quickBlinkSignal =
                    (suddenDrop >= suddenDropThreshold || suddenMinEyeDrop >= suddenDropThreshold)
                    && (instantDrop >= (minDrop * 0.72) || minEyeEar <= (closedThreshold + 0.012));
                const blinkClosing =
                    minEyeEar <= (closedThreshold - 0.005)
                    || ear <= closedThreshold
                    || smoothedEar <= (closedThreshold + 0.008)
                    || instantDrop >= minDrop
                    || smoothedDrop >= (minDrop * 0.85)
                    || quickBlinkSignal;

                if (blinkClosing) {
                    blinkClosedSeen = true;
                    blinkCandidateAt = Date.now();
                    this.emit(callbacks.onStatus, 'Kedipan terdeteksi. Buka mata kembali.');
                }

                previousEar = ear;
                previousMinEyeEar = minEyeEar;
                await this.delay(28);
                continue;
            }

            if (!blinkReopenedSeen) {
                const reopenedNaturally =
                    ear >= reopenThreshold
                    || smoothedEar >= (reopenThreshold - 0.004);
                const reopenedFromQuickBlink =
                    blinkCandidateAt !== null
                    && (Date.now() - blinkCandidateAt) <= 650
                    && (
                        suddenDrop < 0
                        || (instantDrop <= (minDrop * 0.45) && ear >= (baselineEar - (minDrop * 0.42)))
                    );

                if (reopenedNaturally || reopenedFromQuickBlink) {
                    reopenedFrames += 1;
                    if (reopenedFrames >= 1) {
                        blinkReopenedSeen = true;
                        this.emit(callbacks.onGuideState, {
                            state: 'success',
                            message: 'Kedipan berhasil terbaca.',
                        });
                        return {
                            score: this.clamp(0.66 + Math.min(0.28, bestDrop * 4.2), 0.66, 0.96),
                        };
                    }
                } else {
                    reopenedFrames = 0;

                    if (blinkCandidateAt !== null && (Date.now() - blinkCandidateAt) > 900) {
                        blinkClosedSeen = false;
                        blinkCandidateAt = null;
                        this.emit(callbacks.onStatus, 'Kedipan belum lengkap. Kedip satu kali lagi.');
                    }
                }
            }

            previousEar = ear;
            previousMinEyeEar = minEyeEar;
            await this.delay(28);
        }

        throw new Error('Kedipan belum terbaca. Ulangi scan dan kedip satu kali dengan jelas.');
    }

    async runRandomChallenge(videoElement, challengeType, callbacks = {}) {
        if (challengeType === 'turn_left' || challengeType === 'turn_right') {
            return this.waitForHeadTurnChallenge(videoElement, challengeType, callbacks);
        }

        if (challengeType === 'look_up' || challengeType === 'look_down') {
            return this.waitForVerticalLookChallenge(videoElement, challengeType, callbacks);
        }

        if (challengeType === 'mouth_open') {
            return this.waitForMouthOpenChallenge(videoElement, callbacks);
        }

        throw new Error('Challenge wajah tidak dikenal.');
    }

    challengeInstruction(challengeType) {
        const map = {
            turn_left: 'Menengok sedikit ke kiri.',
            turn_right: 'Menengok sedikit ke kanan.',
            look_up: 'Menengok sedikit ke atas.',
            look_down: 'Menengok sedikit ke bawah.',
            mouth_open: 'Buka mulut sedikit sebentar.',
        };

        return map[challengeType] || 'Ikuti instruksi verifikasi wajah.';
    }

    pickRandomAttendanceChallenge() {
        const index = Math.floor(Math.random() * this.attendanceChallengePool.length);
        return this.attendanceChallengePool[index];
    }

    async presentTimedChallengeInstruction(callbacks = {}, instruction, leadMs = this.challengeActionLeadMs) {
        const safeInstruction = instruction || 'Ikuti instruksi verifikasi wajah.';

        this.emit(callbacks.onInstruction, safeInstruction);
        this.emit(callbacks.onStatus, 'Ikuti instruksi di layar. Sistem memberi jeda sebentar.');
        this.emit(callbacks.onGuideState, {
            state: 'steady',
            message: safeInstruction,
        });

        if (leadMs > 0) {
            await this.delay(leadMs);
        }
    }

    async collectPassiveSignals(videoElement, callbacks = {}, sampleMs = 1200) {
        const startedAt = Date.now();
        const brightnessSamples = [];
        const contrastSamples = [];
        const noseOffsets = [];
        const baselineEarSamples = [];
        const recentEars = [];
        let lastNosePoint = null;
        let motionAccumulator = 0;
        let motionTransitions = 0;
        let baselineEar = null;
        let closedThreshold = null;
        let reopenThreshold = null;
        let minDrop = null;
        let suddenDropThreshold = null;
        let previousEar = null;
        let previousMinEyeEar = null;
        let blinkClosedSeen = false;
        let blinkCandidateAt = null;
        let reopenedFrames = 0;
        let bestBlinkDrop = 0;
        let blinkResult = null;

        this.emit(callbacks.onStatus, 'Wajah sudah sesuai. Kedip bisa dilakukan kapan saja.');

        while (Date.now() - startedAt < sampleMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);

            if (detection?.landmarks) {
                const stats = this.sampleFrameStats(videoElement);
                brightnessSamples.push(stats.brightness);
                contrastSamples.push(stats.contrast);

                const noseTip = detection.landmarks.getNose()[3];
                const leftEye = detection.landmarks.getLeftEye()[0];
                const rightEye = detection.landmarks.getRightEye()[3];
                const eyeDistance = this.distance(leftEye, rightEye) || 1;
                const normalizedPoint = {
                    x: noseTip.x / eyeDistance,
                    y: noseTip.y / eyeDistance,
                };

                noseOffsets.push(normalizedPoint);

                if (lastNosePoint) {
                    motionAccumulator += this.distance(lastNosePoint, normalizedPoint);
                    motionTransitions += 1;
                }

                lastNosePoint = normalizedPoint;

                if (!blinkResult) {
                    const leftEar = this.eyeAspectRatio(detection.landmarks.getLeftEye());
                    const rightEar = this.eyeAspectRatio(detection.landmarks.getRightEye());
                    const ear = (leftEar + rightEar) / 2;
                    const minEyeEar = Math.min(leftEar, rightEar);

                    if (baselineEar === null) {
                        if (ear > 0.16) {
                            baselineEarSamples.push(ear);
                        }

                        if (baselineEarSamples.length >= 4) {
                            baselineEar = this.median(baselineEarSamples) || 0.24;
                            closedThreshold = Math.max(0.156, baselineEar - 0.032);
                            reopenThreshold = Math.max(0.17, baselineEar - 0.004);
                            minDrop = Math.max(0.016, baselineEar * 0.075);
                            suddenDropThreshold = Math.max(0.011, baselineEar * 0.048);
                        }
                    } else {
                        recentEars.push(ear);
                        if (recentEars.length > 3) {
                            recentEars.shift();
                        }

                        const smoothedEar = this.average(recentEars) || ear;
                        const instantDrop = baselineEar - ear;
                        const smoothedDrop = baselineEar - smoothedEar;
                        const suddenDrop = previousEar !== null ? (previousEar - ear) : 0;
                        const suddenMinEyeDrop = previousMinEyeEar !== null ? (previousMinEyeEar - minEyeEar) : 0;
                        bestBlinkDrop = Math.max(bestBlinkDrop, instantDrop, smoothedDrop, suddenDrop, suddenMinEyeDrop);

                        if (!blinkClosedSeen) {
                            const quickBlinkSignal =
                                (suddenDrop >= suddenDropThreshold || suddenMinEyeDrop >= suddenDropThreshold)
                                && (instantDrop >= (minDrop * 0.72) || minEyeEar <= (closedThreshold + 0.012));
                            const blinkClosing =
                                minEyeEar <= (closedThreshold - 0.005)
                                || ear <= closedThreshold
                                || smoothedEar <= (closedThreshold + 0.008)
                                || instantDrop >= minDrop
                                || smoothedDrop >= (minDrop * 0.85)
                                || quickBlinkSignal;

                            if (blinkClosing) {
                                blinkClosedSeen = true;
                                blinkCandidateAt = Date.now();
                            }
                        } else {
                            const reopenedNaturally =
                                ear >= reopenThreshold
                                || smoothedEar >= (reopenThreshold - 0.004);
                            const reopenedFromQuickBlink =
                                blinkCandidateAt !== null
                                && (Date.now() - blinkCandidateAt) <= 650
                                && (
                                    suddenDrop < 0
                                    || (instantDrop <= (minDrop * 0.45) && ear >= (baselineEar - (minDrop * 0.42)))
                                );

                            if (reopenedNaturally || reopenedFromQuickBlink) {
                                reopenedFrames += 1;
                                if (reopenedFrames >= 1) {
                                    blinkResult = {
                                        score: this.clamp(0.66 + Math.min(0.28, bestBlinkDrop * 4.2), 0.66, 0.96),
                                    };
                                }
                            } else {
                                reopenedFrames = 0;

                                if (blinkCandidateAt !== null && (Date.now() - blinkCandidateAt) > 900) {
                                    blinkClosedSeen = false;
                                    blinkCandidateAt = null;
                                }
                            }
                        }

                        previousEar = ear;
                        previousMinEyeEar = minEyeEar;
                    }
                }
            } else {
                previousEar = null;
                previousMinEyeEar = null;
                recentEars.length = 0;
            }

            await this.delay(55);
        }

        const brightness = this.average(brightnessSamples) ?? 0;
        const contrast = this.average(contrastSamples) ?? 0;
        const meanMotion = motionTransitions > 0 ? motionAccumulator / motionTransitions : 0;

        const lightingScore = this.estimateLightingScore(brightness, contrast);
        const motionScore = this.clamp((meanMotion - 0.006) / 0.03, 0, 1);
        const qualityReady = lightingScore >= 0.26;

        return {
            brightness,
            contrast,
            lighting_score: lightingScore,
            lighting_label: lightingScore >= 0.65 ? 'good' : lightingScore >= 0.4 ? 'fair' : 'poor',
            lighting_passed: lightingScore >= 0.4,
            motion_score: motionScore,
            motion_label: motionScore >= 0.45 ? 'natural' : motionScore >= 0.2 ? 'limited' : 'flat',
            quality_ready: qualityReady,
            blink_result: blinkResult,
        };
    }

    calculateAttendanceRiskScore({ passiveSignals, blinkScore, randomChallengeScore }) {
        const overallScore = this.clamp(
            0.22
            + (blinkScore * 0.26)
            + (randomChallengeScore * 0.24)
            + (passiveSignals.lighting_score * 0.12)
            + (this.clamp(passiveSignals.contrast / 42, 0, 1) * 0.08)
            + (passiveSignals.motion_score * 0.08),
            0,
            0.99,
        );

        const screenReplayRisk = this.clamp(
            0.68
            - (randomChallengeScore * 0.22)
            - (blinkScore * 0.16)
            - (passiveSignals.motion_score * 0.10)
            - (this.clamp(passiveSignals.contrast / 42, 0, 1) * 0.08)
            - (passiveSignals.lighting_score * 0.06),
            0.05,
            0.95,
        );

        return {
            overall_score: overallScore,
            screen_replay_risk: screenReplayRisk,
            risk_label: screenReplayRisk <= 0.22 ? 'low' : screenReplayRisk <= 0.45 ? 'moderate' : 'high',
        };
    }

    async waitForHeadTurnChallenge(videoElement, direction, callbacks = {}, timeoutMs = 6500) {
        const baselineSamples = [];
        const baselineVerticalSamples = [];
        const baselineMouthSamples = [];
        const calibrationStartedAt = Date.now();

        while (Date.now() - calibrationStartedAt < 1800 && baselineSamples.length < 8) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);
            if (detection?.landmarks) {
                baselineSamples.push(this.userFacingTurnRatio(detection.landmarks));
                baselineVerticalSamples.push(this.verticalLookRatio(detection.landmarks));
                baselineMouthSamples.push(this.mouthOpenRatio(detection.landmarks));
            }
            await this.delay(75);
        }

        const baseline = this.median(baselineSamples) || 0;
        const baselineVertical = this.median(baselineVerticalSamples) || 0.94;
        const baselineMouth = this.median(baselineMouthSamples) || 0.28;
        const expectedDirection = direction === 'turn_left' ? -1 : 1;
        let bestTurn = 0;
        const instruction = direction === 'turn_left'
            ? 'Menengok sedikit ke kiri.'
            : 'Menengok sedikit ke kanan.';

        await this.presentTimedChallengeInstruction(callbacks, instruction);
        this.emit(callbacks.onStatus, 'Membaca gerakan kepala sesuai instruksi.');
        const startedAt = Date.now();

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);
            if (!detection?.landmarks) {
                await this.delay(85);
                continue;
            }

            const ratio = this.userFacingTurnRatio(detection.landmarks) - baseline;
            const directionalTurn = ratio * expectedDirection;
            const oppositeTurn = ratio * -expectedDirection;
            const verticalDelta = Math.abs(this.verticalLookRatio(detection.landmarks) - baselineVertical);
            const mouthDelta = this.mouthOpenRatio(detection.landmarks) - baselineMouth;
            bestTurn = Math.max(bestTurn, directionalTurn);

            if (oppositeTurn >= 0.075) {
                throw new Error('Gerakan tidak sesuai instruksi. Ulangi scan.');
            }

            if (verticalDelta >= 0.035 || mouthDelta >= 0.08) {
                throw new Error('Gerakan tidak sesuai instruksi. Ulangi scan.');
            }

            if (directionalTurn >= 0.105 && verticalDelta < 0.03 && mouthDelta < 0.06) {
                return {
                    score: this.clamp(0.66 + ((directionalTurn - 0.105) * 1.9), 0.66, 0.95),
                    detail: direction,
                };
            }

            await this.delay(65);
        }

        throw new Error('Gerakan belum sesuai instruksi. Ulangi scan.');
    }

    async waitForVerticalLookChallenge(videoElement, direction, callbacks = {}, timeoutMs = 6500) {
        const baselineSamples = [];
        const baselineTurnSamples = [];
        const baselineMouthSamples = [];
        const calibrationStartedAt = Date.now();

        while (Date.now() - calibrationStartedAt < 1800 && baselineSamples.length < 8) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);
            if (detection?.landmarks) {
                baselineSamples.push(this.verticalLookRatio(detection.landmarks));
                baselineTurnSamples.push(this.faceTurnRatio(detection.landmarks));
                baselineMouthSamples.push(this.mouthOpenRatio(detection.landmarks));
            }
            await this.delay(75);
        }

        const baseline = this.median(baselineSamples) || 0.94;
        const baselineTurn = this.median(baselineTurnSamples) || 0;
        const baselineMouth = this.median(baselineMouthSamples) || 0.28;
        let bestMove = 0;
        const expectedDirection = direction === 'look_up' ? -1 : 1;
        const instruction = direction === 'look_up'
            ? 'Menengok sedikit ke atas.'
            : 'Menengok sedikit ke bawah.';

        await this.presentTimedChallengeInstruction(callbacks, instruction);
        this.emit(callbacks.onStatus, 'Membaca arah pandangan sesuai instruksi.');
        const startedAt = Date.now();

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);
            if (!detection?.landmarks) {
                await this.delay(85);
                continue;
            }

            const ratio = this.verticalLookRatio(detection.landmarks) - baseline;
            const directionalMove = ratio * expectedDirection;
            const oppositeMove = ratio * -expectedDirection;
            const horizontalDelta = Math.abs(this.faceTurnRatio(detection.landmarks) - baselineTurn);
            const mouthDelta = this.mouthOpenRatio(detection.landmarks) - baselineMouth;
            bestMove = Math.max(bestMove, directionalMove);

            if (oppositeMove >= 0.055) {
                throw new Error('Gerakan tidak sesuai instruksi. Ulangi scan.');
            }

            if (horizontalDelta >= 0.045 || mouthDelta >= 0.08) {
                throw new Error('Gerakan tidak sesuai instruksi. Ulangi scan.');
            }

            if (directionalMove >= 0.07 && horizontalDelta < 0.035 && mouthDelta < 0.06) {
                return {
                    score: this.clamp(0.66 + (directionalMove * 1.7), 0.66, 0.94),
                    detail: direction,
                };
            }

            await this.delay(65);
        }

        throw new Error('Gerakan belum sesuai instruksi. Ulangi scan.');
    }

    async waitForMouthOpenChallenge(videoElement, callbacks = {}, timeoutMs = 6500) {
        const baselineSamples = [];
        const baselineTurnSamples = [];
        const baselineVerticalSamples = [];
        const calibrationStartedAt = Date.now();

        while (Date.now() - calibrationStartedAt < 1800 && baselineSamples.length < 8) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);
            if (detection?.landmarks) {
                baselineSamples.push(this.mouthOpenRatio(detection.landmarks));
                baselineTurnSamples.push(this.faceTurnRatio(detection.landmarks));
                baselineVerticalSamples.push(this.verticalLookRatio(detection.landmarks));
            }
            await this.delay(75);
        }

        const baseline = this.median(baselineSamples) || 0.28;
        const baselineTurn = this.median(baselineTurnSamples) || 0;
        const baselineVertical = this.median(baselineVerticalSamples) || 0.94;
        let bestOpen = baseline;

        await this.presentTimedChallengeInstruction(callbacks, 'Buka mulut sedikit sebentar.');
        this.emit(callbacks.onStatus, 'Membaca gerakan mulut sesuai instruksi.');
        const startedAt = Date.now();

        while (Date.now() - startedAt < timeoutMs) {
            const detection = await this.detectSingleFaceGeometry(videoElement, callbacks);
            if (!detection?.landmarks) {
                await this.delay(85);
                continue;
            }

            const ratio = this.mouthOpenRatio(detection.landmarks);
            bestOpen = Math.max(bestOpen, ratio);
            const delta = ratio - baseline;
            const horizontalDelta = Math.abs(this.faceTurnRatio(detection.landmarks) - baselineTurn);
            const verticalDelta = Math.abs(this.verticalLookRatio(detection.landmarks) - baselineVertical);

            if (horizontalDelta >= 0.045 || verticalDelta >= 0.035) {
                throw new Error('Gerakan tidak sesuai instruksi. Ulangi scan.');
            }

            if (delta >= 0.11 && horizontalDelta < 0.035 && verticalDelta < 0.03) {
                return {
                    score: this.clamp(0.66 + (delta * 1.35), 0.66, 0.95),
                    detail: 'mouth_open',
                };
            }

            await this.delay(65);
        }

        throw new Error('Gerakan belum sesuai instruksi. Ulangi scan.');
    }

    async captureFaceDescriptor(videoElement, options = {}) {
        const detection = await this.detectSingleFace(videoElement, options);

        if (!detection) {
            throw new Error('Descriptor wajah tidak dapat diambil. Ulangi scan wajah.');
        }

        return detection.descriptor;
    }

    captureFrame(videoElement) {
        const canvas = document.createElement('canvas');
        canvas.width = videoElement.videoWidth || 480;
        canvas.height = videoElement.videoHeight || 640;

        const context = canvas.getContext('2d');
        context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

        return canvas.toDataURL('image/jpeg', 0.85);
    }

    async captureBurstFrames(videoElement, options = {}) {
        const totalFrames = Number.isFinite(options.count) ? Math.max(1, Math.floor(options.count)) : 6;
        const intervalMs = Number.isFinite(options.intervalMs) ? Math.max(40, Math.floor(options.intervalMs)) : 160;
        const warmupMs = Number.isFinite(options.warmupMs) ? Math.max(0, Math.floor(options.warmupMs)) : 260;
        const frames = [];

        if (warmupMs > 0) {
            await this.delay(warmupMs);
        }

        for (let index = 0; index < totalFrames; index += 1) {
            const frame = this.captureFrame(videoElement);
            const stats = this.sampleFrameStats(videoElement);
            const quality = this.estimateLightingScore(stats.brightness, stats.contrast);

            frames.push({
                data: frame,
                quality,
                brightness: stats.brightness,
                contrast: stats.contrast,
            });

            this.emit(options.onProgress, index + 1, totalFrames, {
                quality,
                brightness: stats.brightness,
                contrast: stats.contrast,
            });

            if (index < totalFrames - 1) {
                await this.delay(intervalMs);
            }
        }

        const sorted = frames
            .slice()
            .sort((first, second) => second.quality - first.quality);
        const best = sorted[0] || frames[0] || null;

        return {
            frames: frames.map((item) => item.data),
            best_frame: best?.data || null,
            best_quality: best?.quality ?? null,
        };
    }

    async detectSingleFace(videoElement, options = {}) {
        if (!this.recognitionModelsLoaded) {
            throw new Error('Model scan wajah belum dimuat.');
        }

        const detection = await faceapi
            .detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions(this.detectorOptions))
            .withFaceLandmarks()
            .withFaceDescriptor();

        const quality = this.evaluateDetectionQuality(detection, videoElement, options);

        if (!quality.usable) {
            return null;
        }

        return detection;
    }

    async detectSingleFaceGeometry(videoElement, callbacks = {}, options = {}) {
        if (!this.detectionModelsLoaded) {
            throw new Error('Model scan wajah belum dimuat.');
        }

        const detection = await faceapi
            .detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions(this.detectorOptions))
            .withFaceLandmarks();

        const quality = this.evaluateDetectionQuality(detection, videoElement, options);
        this.emit(callbacks.onGuideState, this.buildGuideStatePayload(quality));

        if (quality.usable) {
            this.lastGeometryDetection = detection;
            this.lastGeometryDetectedAt = Date.now();

            return detection;
        }

        const fallback = options.allowFallback === false
            ? null
            : this.getRecentGeometryFallback();
        if (fallback) {
            this.emit(callbacks.onGuideState, {
                state: 'warning',
                message: 'Tahan posisi wajah. Sistem menstabilkan pembacaan.',
            });

            return fallback;
        }

        return null;
    }

    eyeTiltDegrees(landmarks) {
        const leftEye = landmarks.getLeftEye()[0];
        const rightEye = landmarks.getRightEye()[3];
        const angleRadians = Math.atan2(rightEye.y - leftEye.y, rightEye.x - leftEye.x);

        return Math.abs(angleRadians * (180 / Math.PI));
    }

    averageEyeAspectRatio(landmarks) {
        const leftEye = landmarks.getLeftEye();
        const rightEye = landmarks.getRightEye();

        return (
            this.eyeAspectRatio(leftEye) +
            this.eyeAspectRatio(rightEye)
        ) / 2;
    }

    eyeAspectRatio(eye) {
        const verticalOne = this.distance(eye[1], eye[5]);
        const verticalTwo = this.distance(eye[2], eye[4]);
        const horizontal = this.distance(eye[0], eye[3]);

        if (horizontal === 0) {
            return 0;
        }

        return (verticalOne + verticalTwo) / (2 * horizontal);
    }

    faceTurnRatio(landmarks) {
        const noseTip = landmarks.getNose()[3];
        const leftEye = landmarks.getLeftEye()[0];
        const rightEye = landmarks.getRightEye()[3];
        const eyeCenterX = (leftEye.x + rightEye.x) / 2;
        const eyeDistance = this.distance(leftEye, rightEye);

        if (eyeDistance === 0) {
            return 0;
        }

        return (noseTip.x - eyeCenterX) / eyeDistance;
    }

    userFacingTurnRatio(landmarks) {
        // Video selfie ditampilkan mirrored di UI, jadi rasio horizontal
        // perlu dibalik agar kanan/kiri mengikuti sudut pandang user.
        return -this.faceTurnRatio(landmarks);
    }

    mouthOpenRatio(landmarks) {
        const mouth = landmarks.getMouth();
        if (!Array.isArray(mouth) || mouth.length === 0) {
            return 0;
        }

        const xs = mouth.map((point) => point.x);
        const ys = mouth.map((point) => point.y);
        const width = Math.max(...xs) - Math.min(...xs);
        const height = Math.max(...ys) - Math.min(...ys);

        if (width <= 0) {
            return 0;
        }

        return height / width;
    }

    verticalLookRatio(landmarks) {
        const noseTip = landmarks.getNose()[3];
        const leftEye = landmarks.getLeftEye()[0];
        const rightEye = landmarks.getRightEye()[3];
        const mouth = landmarks.getMouth();

        if (!noseTip || !leftEye || !rightEye || !Array.isArray(mouth) || mouth.length === 0) {
            return 0;
        }

        const eyeCenterY = (leftEye.y + rightEye.y) / 2;
        const mouthCenterY = mouth.reduce((total, point) => total + point.y, 0) / mouth.length;
        const span = mouthCenterY - eyeCenterY;

        if (span <= 0) {
            return 0;
        }

        return (noseTip.y - eyeCenterY) / span;
    }

    smileRatio(landmarks) {
        const mouth = landmarks.getMouth();
        const leftEye = landmarks.getLeftEye()[0];
        const rightEye = landmarks.getRightEye()[3];

        if (!Array.isArray(mouth) || mouth.length === 0 || !leftEye || !rightEye) {
            return 0;
        }

        const mouthLeft = mouth[0];
        const mouthRight = mouth[6];
        const mouthTop = mouth[3];
        const mouthBottom = mouth[9];
        const eyeDistance = this.distance(leftEye, rightEye) || 1;
        const mouthWidth = this.distance(mouthLeft, mouthRight);
        const mouthHeight = this.distance(mouthTop, mouthBottom);
        const cornerLift = ((mouthTop.y - mouthLeft.y) + (mouthTop.y - mouthRight.y)) / eyeDistance;

        return (mouthWidth / eyeDistance) + Math.max(0, cornerLift * 0.45) - (mouthHeight / eyeDistance * 0.12);
    }

    evaluateDetectionQuality(detection, videoElement, options = {}) {
        const strict = Boolean(options.strict);
        const profile = options.profile || 'default';
        const box = detection?.detection?.box;
        const landmarks = detection?.landmarks;

        if (!box || !landmarks) {
            return {
                usable: false,
                state: 'searching',
                message: 'Arahkan wajah ke bingkai panduan.',
            };
        }

        const videoHeight = videoElement?.videoHeight || 0;
        const videoWidth = videoElement?.videoWidth || 0;
        const boxCenterX = box.x + (box.width / 2);
        const boxCenterY = box.y + (box.height / 2);
        const horizontalOffsetRatio = videoWidth > 0
            ? Math.abs(boxCenterX - (videoWidth / 2)) / (videoWidth / 2)
            : 0;
        const verticalOffsetRatio = videoHeight > 0
            ? Math.abs(boxCenterY - (videoHeight / 2)) / (videoHeight / 2)
            : 0;
        const faceWidthRatio = videoWidth > 0 ? box.width / videoWidth : 0;
        const faceHeightRatio = videoHeight > 0 ? box.height / videoHeight : 0;
        const eyeTilt = this.eyeTiltDegrees(landmarks);
        const tooSmall = videoWidth > 0 && faceWidthRatio < this.minimumFaceWidthRatio;
        const tooTilted = eyeTilt > this.maximumEyeTiltDegrees;
        const tooOffCenter = horizontalOffsetRatio > 0.34 || verticalOffsetRatio > 0.36;
        const enrollmentTooWide = profile === 'enrollment' && faceWidthRatio > 0.46;
        const enrollmentTooTall = profile === 'enrollment' && faceHeightRatio > 0.72;
        const enrollmentTooLow = profile === 'enrollment' && verticalOffsetRatio > 0.28;
        const enrollmentTooFarSide = profile === 'enrollment' && horizontalOffsetRatio > 0.24;
        const enrollmentTooSmall = profile === 'enrollment' && (faceWidthRatio < 0.18 || faceHeightRatio < 0.3);
        const usable = !tooSmall
            && !tooTilted
            && !(strict && tooOffCenter)
            && !enrollmentTooWide
            && !enrollmentTooTall
            && !enrollmentTooLow
            && !enrollmentTooFarSide
            && !enrollmentTooSmall;

        if (!usable) {
            if (enrollmentTooSmall || tooSmall) {
                return {
                    usable: false,
                    state: 'too-far',
                    message: 'Dekatkan wajah sedikit ke kamera.',
                };
            }

            if (enrollmentTooWide || enrollmentTooTall) {
                return {
                    usable: false,
                    state: 'too-close',
                    message: 'Jauhkan wajah sedikit agar pas dengan oval.',
                };
            }

            if (tooTilted) {
                return {
                    usable: false,
                    state: 'tilted',
                    message: 'Luruskan kepala agar sejajar dengan bingkai.',
                };
            }

            if (profile === 'enrollment' && enrollmentTooLow) {
                return {
                    usable: false,
                    state: 'off-center',
                    message: 'Naikkan wajah sedikit agar tepat di tengah oval.',
                };
            }

            if (profile === 'enrollment' && enrollmentTooFarSide) {
                return {
                    usable: false,
                    state: 'off-center',
                    message: 'Geser wajah tepat ke tengah oval.',
                };
            }

            return {
                usable: false,
                state: 'off-center',
                message: 'Geser wajah ke tengah bingkai panduan.',
            };
        }

        if (tooOffCenter) {
            return {
                usable: true,
                state: 'warning',
                message: 'Posisi hampir pas. Geser sedikit ke tengah.',
            };
        }

        return {
            usable: true,
            state: 'aligned',
            message: 'Wajah berada pada posisi yang baik.',
        };
    }

    evaluateEnrollmentCaptureReadiness(videoElement, detection, previousSignature = null) {
        const signature = this.buildFaceSignature(detection);
        const sharpness = this.sampleFaceSharpness(videoElement, detection?.detection?.box);
        const motion = previousSignature ? this.measureFaceMotion(signature, previousSignature) : 0;
        const sharpEnough = sharpness >= this.enrollmentSharpnessThreshold;
        const stableEnough = motion <= this.enrollmentMotionThreshold;

        return {
            ready: sharpEnough && stableEnough,
            sharpEnough,
            stableEnough,
            sharpness,
            motion,
            signature,
        };
    }

    buildGuideStatePayload(quality) {
        return {
            state: quality?.state || 'searching',
            message: quality?.message || 'Arahkan wajah ke bingkai panduan.',
        };
    }

    buildFaceSignature(detection) {
        const box = detection?.detection?.box;
        const landmarks = detection?.landmarks;
        const noseTip = landmarks?.getNose?.()[3];
        const leftEye = landmarks?.getLeftEye?.()[0];
        const rightEye = landmarks?.getRightEye?.()[3];

        if (!box || !noseTip || !leftEye || !rightEye) {
            return null;
        }

        const eyeDistance = this.distance(leftEye, rightEye) || box.width || 1;

        return {
            centerX: box.x + (box.width / 2),
            centerY: box.y + (box.height / 2),
            width: box.width,
            height: box.height,
            noseX: noseTip.x,
            noseY: noseTip.y,
            eyeDistance,
        };
    }

    measureFaceMotion(currentSignature, previousSignature) {
        if (!currentSignature || !previousSignature) {
            return 0;
        }

        const normalizer = Math.max(
            currentSignature.eyeDistance,
            previousSignature.eyeDistance,
            currentSignature.width,
            previousSignature.width,
            1,
        );

        const centerMotion = this.distance(
            { x: currentSignature.centerX, y: currentSignature.centerY },
            { x: previousSignature.centerX, y: previousSignature.centerY },
        ) / normalizer;

        const noseMotion = this.distance(
            { x: currentSignature.noseX, y: currentSignature.noseY },
            { x: previousSignature.noseX, y: previousSignature.noseY },
        ) / normalizer;

        const scaleMotion = Math.abs(currentSignature.width - previousSignature.width) / normalizer;

        return Math.max(centerMotion, noseMotion, scaleMotion);
    }

    getRecentGeometryFallback() {
        if (!this.lastGeometryDetection || !this.lastGeometryDetectedAt) {
            return null;
        }

        return Date.now() - this.lastGeometryDetectedAt <= this.recentDetectionMemoryMs
            ? this.lastGeometryDetection
            : null;
    }

    distance(firstPoint, secondPoint) {
        return Math.sqrt(
            Math.pow(firstPoint.x - secondPoint.x, 2) +
            Math.pow(firstPoint.y - secondPoint.y, 2)
        );
    }

    median(values) {
        if (!Array.isArray(values) || values.length === 0) {
            return null;
        }

        const sorted = [...values].sort((first, second) => first - second);
        const middleIndex = Math.floor(sorted.length / 2);

        if (sorted.length % 2 === 0) {
            return (sorted[middleIndex - 1] + sorted[middleIndex]) / 2;
        }

        return sorted[middleIndex];
    }

    average(values) {
        if (!Array.isArray(values) || values.length === 0) {
            return null;
        }

        return values.reduce((total, value) => total + value, 0) / values.length;
    }

    clamp(value, min = 0, max = 1) {
        return Math.max(min, Math.min(value, max));
    }

    sampleFrameStats(videoElement) {
        const canvas = document.createElement('canvas');
        canvas.width = 32;
        canvas.height = 32;
        const context = canvas.getContext('2d', { willReadFrequently: true });
        context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

        const { data } = context.getImageData(0, 0, canvas.width, canvas.height);
        let totalBrightness = 0;
        const luminances = [];

        for (let index = 0; index < data.length; index += 4) {
            const brightness = (data[index] * 0.299) + (data[index + 1] * 0.587) + (data[index + 2] * 0.114);
            totalBrightness += brightness;
            luminances.push(brightness);
        }

        const averageBrightness = luminances.length > 0 ? totalBrightness / luminances.length : 0;
        const variance = luminances.length > 0
            ? luminances.reduce((total, value) => total + Math.pow(value - averageBrightness, 2), 0) / luminances.length
            : 0;

        return {
            brightness: averageBrightness,
            contrast: Math.sqrt(Math.max(variance, 0)),
        };
    }

    sampleFaceSharpness(videoElement, box) {
        if (!videoElement || !box) {
            return 0;
        }

        const sourceWidth = videoElement.videoWidth || 0;
        const sourceHeight = videoElement.videoHeight || 0;
        if (!sourceWidth || !sourceHeight) {
            return 0;
        }

        const paddingX = box.width * 0.12;
        const paddingY = box.height * 0.12;
        const cropX = Math.max(0, Math.floor(box.x - paddingX));
        const cropY = Math.max(0, Math.floor(box.y - paddingY));
        const cropWidth = Math.min(sourceWidth - cropX, Math.ceil(box.width + (paddingX * 2)));
        const cropHeight = Math.min(sourceHeight - cropY, Math.ceil(box.height + (paddingY * 2)));

        if (cropWidth < 12 || cropHeight < 12) {
            return 0;
        }

        const canvas = document.createElement('canvas');
        canvas.width = 56;
        canvas.height = 56;
        const context = canvas.getContext('2d', { willReadFrequently: true });
        context.drawImage(videoElement, cropX, cropY, cropWidth, cropHeight, 0, 0, canvas.width, canvas.height);

        const { data } = context.getImageData(0, 0, canvas.width, canvas.height);
        const grayscale = new Float32Array(canvas.width * canvas.height);

        for (let index = 0, pixel = 0; index < data.length; index += 4, pixel += 1) {
            grayscale[pixel] = (data[index] * 0.299) + (data[index + 1] * 0.587) + (data[index + 2] * 0.114);
        }

        let edgeEnergy = 0;
        let samples = 0;

        for (let y = 1; y < canvas.height - 1; y += 1) {
            for (let x = 1; x < canvas.width - 1; x += 1) {
                const index = (y * canvas.width) + x;
                const horizontal = Math.abs(grayscale[index + 1] - grayscale[index - 1]);
                const vertical = Math.abs(grayscale[index + canvas.width] - grayscale[index - canvas.width]);
                edgeEnergy += horizontal + vertical;
                samples += 1;
            }
        }

        if (!samples) {
            return 0;
        }

        return this.clamp((edgeEnergy / samples) / 120, 0, 1);
    }

    estimateLightingScore(brightness, contrast) {
        const brightnessScore = brightness < 72
            ? brightness / 72
            : brightness > 190
                ? this.clamp((255 - brightness) / 65, 0, 1)
                : 1;
        const contrastScore = this.clamp(contrast / 32, 0, 1);

        return this.clamp((brightnessScore * 0.68) + (contrastScore * 0.32), 0, 1);
    }

    emit(callback, ...args) {
        if (typeof callback === 'function') {
            callback(...args);
        }
    }

    delay(ms) {
        return new Promise((resolve) => window.setTimeout(resolve, ms));
    }
}

window.FaceRecognition = FaceRecognition;
