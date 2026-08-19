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
                alignedFace = await this.waitForStableSingleFace(videoElement, callbacks, 1400, 1, false);

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
