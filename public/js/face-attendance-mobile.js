(() => {
    if (typeof window === 'undefined' || !window.FaceRecognition) {
        return;
    }

    class MobileFaceRecognition extends window.FaceRecognition {
        constructor() {
            super();

            // Mobile presensi gets its own tuning so kiosk/enrollment keep the shared defaults.
            this.detectorOptions = {
                inputSize: 192,
                scoreThreshold: 0.18,
            };
            this.minimumFaceWidthRatio = 0.075;
            this.maximumEyeTiltDegrees = 30;
            this.enrollmentSharpnessThreshold = 0.07;
            this.enrollmentMotionThreshold = 0.16;
            this.enrollmentHoldMs = 100;
            this.recentDetectionMemoryMs = 550;
        }

        async detectSingleFaceGeometry(videoElement, callbacks = {}, options = {}) {
            if (!this.detectionModelsLoaded) {
                throw new Error('Model scan wajah belum dimuat.');
            }

            const detection = await window.faceapi
                .detectSingleFace(videoElement, new window.faceapi.TinyFaceDetectorOptions(this.detectorOptions))
                .withFaceLandmarks();

            const quality = this.evaluateDetectionQuality(detection, videoElement, options);
            this.emit(callbacks.onGuideState, this.buildGuideStatePayload(quality));
            this.emit(callbacks.onDiagnostic, {
                stage: options.profile || 'mobile',
                detection: detection ? {
                    box: detection.detection?.box || null,
                    score: detection.detection?.score ?? null,
                } : null,
                quality: {
                    usable: quality.usable,
                    state: quality.state,
                    message: quality.message,
                },
            });

            if (detection) {
                this.lastGeometryDetection = detection;
                this.lastGeometryDetectedAt = Date.now();
                return detection;
            }

            const fallback = options.allowFallback === false
                ? null
                : this.getRecentGeometryFallback();

            return fallback || null;
        }

        async waitForStableSingleFace(videoElement, callbacks = {}, timeoutMs = 5200, stableHitsRequired = 1) {
            const startedAt = Date.now();
            let stableHits = 0;
            let hadFaceVisible = false;

            while (Date.now() - startedAt < timeoutMs) {
                const detection = await this.detectSingleFaceGeometry(videoElement, callbacks, {
                    strict: false,
                    allowFallback: true,
                    profile: 'mobile',
                });

                if (detection) {
                    hadFaceVisible = true;
                    stableHits += 1;
                    this.emit(callbacks.onStatus, stableHits >= stableHitsRequired
                        ? 'Wajah terdeteksi. Mengambil data wajah.'
                        : 'Wajah terdeteksi. Menstabilkan posisi sebentar.');
                    this.emit(callbacks.onGuideState, {
                        state: stableHits >= stableHitsRequired ? 'aligned' : 'warning',
                        message: stableHits >= stableHitsRequired
                            ? 'Wajah sudah masuk frame. Sistem melanjutkan scan.'
                            : 'Posisi wajah sudah terbaca. Tahan sebentar.',
                    });

                    if (stableHits >= stableHitsRequired) {
                        return detection;
                    }

                    await this.delay(80);
                    continue;
                }

                stableHits = 0;
                this.emit(callbacks.onStatus, hadFaceVisible
                    ? 'Wajah sempat hilang. Kembalikan ke tengah frame.'
                    : 'Belum ada wajah di frame. Sistem tetap menunggu.');
                this.emit(callbacks.onGuideState, {
                    state: hadFaceVisible ? 'warning' : 'searching',
                    message: hadFaceVisible
                        ? 'Wajah sempat hilang. Kembalikan ke tengah frame.'
                        : 'Belum ada wajah di frame. Sistem tetap menunggu.',
                });
                await this.delay(120);
            }

            return null;
        }

        async performAttendanceScan(videoElement, callbacks = {}) {
            await this.loadDetectionModels();

            this.emit(callbacks.onStatus, 'Kamera aktif. Pusatkan wajah di dalam oval.');
            this.emit(callbacks.onInstruction, 'Arahkan wajah ke dalam oval. Jika wajah belum masuk, sistem akan menunggu.');
            this.emit(callbacks.onGuideState, {
                state: 'searching',
                message: 'Menunggu wajah masuk ke dalam oval.',
            });

            let alignedFace = null;
            while (!alignedFace) {
                alignedFace = await this.waitForStableSingleFace(videoElement, callbacks, 5200, 1);

                if (!alignedFace) {
                    this.emit(callbacks.onStatus, 'Belum ada wajah di frame. Sistem tetap menunggu.');
                    this.emit(callbacks.onGuideState, {
                        state: 'searching',
                        message: 'Belum ada wajah di frame. Sistem tetap menunggu.',
                    });
                    await this.delay(180);
                }
            }

            await this.loadRecognitionModel();

            const initialDescriptor = await this.captureFaceDescriptor(videoElement, {
                strict: true,
                allowFallback: false,
            });

            if (typeof callbacks.onFaceMatchCheck === 'function' && callbacks.skipPreMatchCheck !== true) {
                const verificationResult = await callbacks.onFaceMatchCheck(Array.from(initialDescriptor));

                if (!verificationResult?.face_verified) {
                    throw new Error(verificationResult?.message || 'Presensi ditolak karena wajah tidak cocok dengan data yang terdaftar.');
                }

                this.emit(callbacks.onStatus, 'Wajah cocok dengan data terdaftar. Presensi dilanjutkan.');
                this.emit(callbacks.onGuideState, {
                    state: 'success',
                    message: 'Wajah cocok. Presensi dilanjutkan.',
                });
            }

            return {
                face_descriptor: Array.from(initialDescriptor),
                liveness_score: 1,
                captured_image: this.captureFrame(videoElement),
                initial_face_descriptor: Array.from(initialDescriptor),
                liveness_challenges: [],
            };
        }
    }

    window.MobileFaceRecognition = MobileFaceRecognition;
})();
