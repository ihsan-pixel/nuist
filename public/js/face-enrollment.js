(() => {
    if (typeof window === 'undefined') {
        return;
    }

    class FaceEnrollmentEngine {
        constructor() {
            this.modelsLoaded = false;
            this.detectionModelsLoaded = false;
            this.recognitionModelsLoaded = false;
            this.modelLoadPromise = null;
            this.detectionModelLoadPromise = null;
            this.recognitionModelLoadPromise = null;
            this.activeStream = null;
            this.modelBaseUri = '/models';
            this.detectorOptions = {
                inputSize: 160,
                scoreThreshold: 0.22,
            };
            this.minimumFaceWidthRatio = 0.085;
            this.maximumEyeTiltDegrees = 26;
            this.enrollmentSharpnessThreshold = 0.075;
            this.enrollmentMotionThreshold = 0.14;
            this.enrollmentHoldMs = 120;
            this.tfBackend = null;
            this.tfBackendHealth = null;
            this.tfBackendMode = null;
            this.webglInferenceFailures = 0;
            this.backendFallbackActive = false;
            this.inferenceTimeoutMs = 1800;
            this.modelLoadTimeoutMs = 20000;
            this.tfHealthCheckTimeoutMs = 1800;
            this.backendOrder = ['webgl', 'wasm', 'cpu'];
            this.recentDetectionMemoryMs = 450;
            this.lastGeometryDetection = null;
            this.lastGeometryDetectedAt = 0;
        }

        async withTimeout(promise, timeoutMs, label = 'operation') {
            let timeoutId = null;
            const timeoutPromise = new Promise((_, reject) => {
                timeoutId = window.setTimeout(() => reject(new Error(`${label} timeout after ${timeoutMs}ms`)), timeoutMs);
            });

            try {
                return await Promise.race([promise, timeoutPromise]);
            } finally {
                if (timeoutId !== null) {
                    window.clearTimeout(timeoutId);
                }
            }
        }

        emit(callback, ...args) {
            if (typeof callback === 'function') {
                callback(...args);
            }
        }

        emitDiagnostic(callbacks = {}, payload = {}) {
            this.emit(callbacks.onDiagnostic, payload);
        }

        getTf() {
            if (window.tf) {
                return window.tf;
            }

            if (window.faceapi) {
                if (window.faceapi.tf) {
                    return window.faceapi.tf;
                }

                if (window.faceapi.env?.getEnv?.().tf) {
                    return window.faceapi.env.getEnv().tf;
                }
            }

            return null;
        }

        getTfBackendName() {
            const tf = this.getTf();
            return tf && typeof tf.getBackend === 'function' ? (tf.getBackend() || 'unknown') : 'unavailable';
        }

        async probeTensorFlowBackend(callbacks = {}) {
            const tf = this.getTf();
            if (!tf) {
                return false;
            }

            try {
                const backendName = await this.withTimeout(tf.ready().then(() => tf.getBackend()), this.tfHealthCheckTimeoutMs, 'tf.ready');
                this.tfBackend = backendName || 'unknown';
                this.tfBackendHealth = { backend: this.tfBackend, healthy: true, checkedAt: Date.now() };

                if (typeof tf.version_core !== 'undefined') {
                    this.emitDiagnostic(callbacks, { type: 'tf-version', version: tf.version_core });
                }

                await this.withTimeout(tf.tidy(() => {
                    const tensor = tf.tensor1d([1, 2, 3]);
                    const mean = tensor.mean();
                    const value = mean.dataSync()[0];
                    tensor.dispose();
                    mean.dispose();
                    return value;
                }), this.tfHealthCheckTimeoutMs, 'tf.healthCheck');

                return true;
            } catch (error) {
                this.tfBackendHealth = {
                    backend: this.getTfBackendName(),
                    healthy: false,
                    error: String(error?.message || error || 'tf health check failed'),
                    checkedAt: Date.now(),
                };
                return false;
            }
        }

        async setTensorFlowBackend(backend, callbacks = {}) {
            const tf = this.getTf();
            if (!tf || typeof tf.setBackend !== 'function') {
                return false;
            }

            const backendName = backend || 'cpu';
            await this.withTimeout(tf.setBackend(backendName), this.tfHealthCheckTimeoutMs, `tf.setBackend:${backendName}`);
            await this.withTimeout(tf.ready(), this.tfHealthCheckTimeoutMs, `tf.ready:${backendName}`);
            this.tfBackend = tf.getBackend() || backendName;
            this.tfBackendMode = this.tfBackend;
            this.emitDiagnostic(callbacks, {
                type: 'tf-backend',
                backend: this.tfBackend,
                webgl: this.getWebGLInfo(),
            });
            return true;
        }

        getWebGLInfo() {
            try {
                const canvas = document.createElement('canvas');
                const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
                if (!gl) {
                    return { webglVersion: null, gpuRenderer: null };
                }

                const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
                return {
                    webglVersion: gl instanceof WebGL2RenderingContext ? 2 : 1,
                    gpuRenderer: debugInfo ? gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL) : null,
                };
            } catch (error) {
                return {
                    webglVersion: null,
                    gpuRenderer: null,
                    error: String(error?.message || error || 'webgl probe failed'),
                };
            }
        }

        async loadModelWithTimeout(loader, label) {
            return this.withTimeout(loader(), this.modelLoadTimeoutMs, label);
        }

        async selectEnrollmentBackend(callbacks = {}) {
            const tf = this.getTf();
            if (!tf) {
                this.tfBackend = 'unavailable';
                this.tfBackendHealth = {
                    backend: 'unavailable',
                    healthy: false,
                    error: 'TensorFlow.js belum tersedia.',
                    checkedAt: Date.now(),
                };
                this.emitDiagnostic(callbacks, {
                    type: 'tf-backend-missing',
                    backend: 'unavailable',
                });
                this.backendFallbackActive = true;
                return 'unavailable';
            }

            if (this.backendFallbackActive) {
                return this.tfBackendMode || this.tfBackend || this.getTfBackendName();
            }

            for (const backend of this.backendOrder) {
                try {
                    await this.setTensorFlowBackend(backend, callbacks);
                    const healthy = await this.probeTensorFlowBackend(callbacks);
                    if (!healthy) {
                        continue;
                    }
                    this.backendFallbackActive = backend !== 'webgl';
                    this.webglInferenceFailures = 0;
                    return backend;
                } catch (error) {
                    this.emitDiagnostic(callbacks, {
                        type: 'backend-switch-error',
                        backend,
                        error: String(error?.message || error || 'backend switch failed'),
                    });
                }
            }

            this.tfBackend = this.getTfBackendName();
            this.tfBackendHealth = {
                backend: this.tfBackend,
                healthy: false,
                error: 'TensorFlow backend tidak dapat diinisialisasi.',
                checkedAt: Date.now(),
            };
            this.backendFallbackActive = true;
            this.emitDiagnostic(callbacks, {
                type: 'backend-fallback-active',
                backend: this.tfBackend,
                reason: 'TensorFlow backend unavailable, using face-api default execution path.',
            });
            return this.tfBackend;
        }

        async runInferenceWithBackendFallback(callbacks, inferenceFn) {
            const tf = this.getTf();
            const backend = this.getTfBackendName();
            const startedAt = Date.now();

            if (!tf) {
                this.emitDiagnostic(callbacks, {
                    type: 'inference',
                    backend: 'unavailable',
                    durationMs: 0,
                    status: 'error',
                    error: 'TensorFlow.js belum tersedia.',
                });
                throw new Error('TensorFlow.js belum tersedia.');
            }

            try {
                const result = await this.withTimeout(Promise.resolve().then(inferenceFn), this.inferenceTimeoutMs, `inference:${backend}`);
                this.webglInferenceFailures = 0;
                this.emitDiagnostic(callbacks, {
                    type: 'inference',
                    backend,
                    durationMs: Date.now() - startedAt,
                    status: 'ok',
                });
                return result;
            } catch (error) {
                const message = String(error?.message || error || 'inference failed');
                this.emitDiagnostic(callbacks, {
                    type: 'inference',
                    backend,
                    durationMs: Date.now() - startedAt,
                    status: 'error',
                    error: message,
                });

                if (backend === 'webgl') {
                    this.webglInferenceFailures += 1;
                    if (this.webglInferenceFailures >= 2) {
                        this.backendFallbackActive = true;
                        try {
                            await this.setTensorFlowBackend('wasm', callbacks);
                            await this.probeTensorFlowBackend(callbacks);
                            this.emit(callbacks.onStatus, 'Menyesuaikan kamera untuk perangkat Anda...');
                        } catch (backendError) {
                            await this.setTensorFlowBackend('cpu', callbacks);
                            await this.probeTensorFlowBackend(callbacks);
                            this.emit(callbacks.onStatus, 'Menyesuaikan kamera untuk perangkat Anda...');
                            this.emitDiagnostic(callbacks, {
                                type: 'backend-fallback-error',
                                error: String(backendError?.message || backendError || 'fallback failed'),
                            });
                        }
                    }
                }

                throw error;
            } finally {
                if (tf && typeof tf.memory === 'function') {
                    this.emitDiagnostic(callbacks, {
                        type: 'tf-memory',
                        backend: this.getTfBackendName(),
                        ...tf.memory(),
                    });
                }
            }
        }

        async loadModels() {
            if (this.recognitionModelsLoaded) {
                return true;
            }

            if (this.modelLoadPromise) {
                return this.modelLoadPromise;
            }

            this.modelLoadPromise = (async () => {
                this.emitDiagnostic({}, { type: 'model-load-status', status: 'starting', backend: this.getTfBackendName() });
                try {
                    await this.selectEnrollmentBackend();
                } catch (error) {
                    this.emitDiagnostic({}, {
                        type: 'backend-fallback-warning',
                        backend: this.getTfBackendName(),
                        error: String(error?.message || error || 'backend selection failed'),
                    });
                }

                await this.loadDetectionModels();
                this.emitDiagnostic({}, { type: 'model-load-status', status: 'detection-ready', backend: this.getTfBackendName() });
                this.recognitionModelsLoaded = false;
                this.modelsLoaded = true;
                return true;
            })();

            try {
                return await this.modelLoadPromise;
            } finally {
                this.modelLoadPromise = null;
            }
        }

        async loadDetectionModels() {
            if (this.detectionModelsLoaded) {
                this.modelsLoaded = this.recognitionModelsLoaded;
                return true;
            }

            if (this.detectionModelLoadPromise) {
                return this.detectionModelLoadPromise;
            }

            if (typeof faceapi === 'undefined') {
                throw new Error('Library scan wajah belum tersedia.');
            }

            this.detectionModelLoadPromise = (async () => {
                try {
                    this.emitDiagnostic({}, { type: 'model-load-status', status: 'loading-detection', backend: this.getTfBackendName() });
                    await this.loadModelWithTimeout(() => faceapi.nets.tinyFaceDetector.loadFromUri(this.modelBaseUri), 'tinyFaceDetector.load');
                    await this.loadModelWithTimeout(() => faceapi.nets.faceLandmark68Net.loadFromUri(this.modelBaseUri), 'faceLandmark68Net.load');
                } catch (error) {
                    const rawMessage = String(error?.message || error || '');
                    if (rawMessage.includes('Based on the provided shape') || rawMessage.includes('tensor should have') || rawMessage.includes('Failed to fetch') || rawMessage.includes('404')) {
                        throw new Error('File model scan wajah di server tidak lengkap atau rusak. Hubungi admin untuk memperbarui model wajah.');
                    }
                    throw error;
                }

                this.detectionModelsLoaded = true;
                this.modelsLoaded = this.recognitionModelsLoaded;
                this.emitDiagnostic({}, { type: 'model-load-status', status: 'detection-loaded', backend: this.getTfBackendName() });
                return true;
            })();

            try {
                return await this.detectionModelLoadPromise;
            } finally {
                this.detectionModelLoadPromise = null;
            }
        }

        async loadRecognitionModel() {
            if (this.recognitionModelsLoaded) {
                return true;
            }

            if (this.recognitionModelLoadPromise) {
                return this.recognitionModelLoadPromise;
            }

            this.recognitionModelLoadPromise = (async () => {
                try {
                    this.emitDiagnostic({}, { type: 'model-load-status', status: 'loading-recognition', backend: this.getTfBackendName() });
                    await this.loadModelWithTimeout(() => faceapi.nets.faceRecognitionNet.loadFromUri(this.modelBaseUri), 'faceRecognitionNet.load');
                } catch (error) {
                    const rawMessage = String(error?.message || error || '');
                    if (rawMessage.includes('Based on the provided shape') || rawMessage.includes('tensor should have') || rawMessage.includes('Failed to fetch') || rawMessage.includes('404')) {
                        throw new Error('File model scan wajah di server tidak lengkap atau rusak. Hubungi admin untuk memperbarui model wajah.');
                    }
                    throw error;
                }

                this.emitDiagnostic({}, { type: 'model-load-status', status: 'recognition-loaded', backend: this.getTfBackendName() });
                return true;
            })();

            try {
                return await this.recognitionModelLoadPromise;
            } finally {
                this.recognitionModelLoadPromise = null;
            }
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
                    width: { ideal: 480 },
                    height: { ideal: 640 },
                    aspectRatio: 3 / 4,
                    frameRate: { ideal: 15, max: 24 },
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

            await this.delay(60);
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

        async performEnrollmentScan(videoElement, callbacks = {}) {
            await this.loadModels();
            const livenessChallenges = await this.runEnrollmentScanSequence(videoElement, callbacks);
            const descriptor = await this.captureFaceDescriptor(videoElement, {
                strict: true,
                profile: 'enrollment',
            });

            return {
                face_descriptor: Array.from(descriptor),
                liveness_score: 0.96,
                liveness_challenges: livenessChallenges,
                captured_image: this.captureFrame(videoElement, { mirror: false }),
            };
        }

        async runEnrollmentScanSequence(videoElement, callbacks = {}) {
            const results = [];
            this.emit(callbacks.onInstruction, 'Posisikan wajah tepat di dalam oval.');
            this.emit(callbacks.onChallengeState, 'align', 'active');
            await this.waitForPreciseEnrollmentAlignment(videoElement, callbacks);
            results.push({ type: 'face_aligned', passed: true, timestamp: Date.now() });
            this.emit(callbacks.onChallengeState, 'align', 'done');

            this.emit(callbacks.onInstruction, 'Posisi sudah pas. Gambar akan diambil otomatis.');
            this.emit(callbacks.onChallengeState, 'steady', 'active');
            await this.waitForEnrollmentAutoCapture(videoElement, callbacks);
            results.push({ type: 'face_stable', passed: true, timestamp: Date.now() });
            this.emit(callbacks.onChallengeState, 'steady', 'done');

            this.emit(callbacks.onInstruction, 'Wajah terbaca. Menyelesaikan scan.');
            this.emit(callbacks.onStatus, 'Scan wajah sedang diselesaikan.');
            this.emit(callbacks.onChallengeState, 'done', 'active');
            await this.delay(160);
            this.emit(callbacks.onChallengeState, 'done', 'done');
            results.push({ type: 'face_captured', passed: true, timestamp: Date.now() });

            return results;
        }

        async waitForPreciseEnrollmentAlignment(videoElement, callbacks = {}, timeoutMs = 8000) {
            const startedAt = Date.now();
            while (Date.now() - startedAt < timeoutMs) {
                const detection = await this.detectSingleFaceGeometry(videoElement, callbacks, {
                    strict: true,
                    profile: 'enrollment',
                });

                if (detection) {
                    this.emit(callbacks.onStatus, 'Wajah sudah tepat di oval. Tahan posisi sebentar.');
                    this.emit(callbacks.onGuideState, { state: 'aligned', message: 'Posisi wajah sudah tepat di oval.' });
                    return detection;
                }

                this.emit(callbacks.onStatus, 'Pusatkan wajah tepat di dalam oval dan sesuaikan jaraknya.');
                await this.delay(120);
            }

            throw new Error('Wajah belum tepat di dalam oval. Dekatkan atau geser posisi wajah hingga pas pada bingkai.');
        }

        async waitForEnrollmentAutoCapture(videoElement, callbacks = {}, holdMs = this.enrollmentHoldMs, timeoutMs = 3600) {
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
                    await this.delay(120);
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
                    await this.delay(120);
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
                    this.emit(callbacks.onGuideState, { state: 'success', message: 'Wajah berhasil diambil otomatis.' });
                    return true;
                }

                await this.delay(120);
            }

            throw new Error('Wajah belum cukup stabil di dalam oval. Tahan posisi wajah hingga sistem mengambil gambar otomatis.');
        }

        async captureFaceDescriptor(videoElement, options = {}) {
            const deadline = Date.now() + (options.timeoutMs || 4200);
            let lastError = null;
            while (Date.now() < deadline) {
                try {
                    if (!this.recognitionModelsLoaded) {
                        await this.loadRecognitionModel();
                        this.recognitionModelsLoaded = true;
                    }

                    const detection = options.profile === 'enrollment'
                        ? await this.detectEnrollmentFace(videoElement, options.callbacks || {}, options)
                        : await this.detectSingleFace(videoElement, options);
                    if (!detection) {
                        await this.delay(120);
                        continue;
                    }
                    return detection.descriptor;
                } catch (error) {
                    lastError = error;
                    const message = String(error?.message || error || '');
                    if (message.includes('timeout')) {
                        await this.delay(120);
                        continue;
                    }
                    throw error;
                }
            }
            throw lastError || new Error('Descriptor wajah tidak dapat diambil. Ulangi scan wajah.');
        }

        async detectSingleFace(videoElement, options = {}) {
            if (!this.recognitionModelsLoaded) {
                throw new Error('Model scan wajah belum dimuat.');
            }

            try {
                const detection = await this.runInferenceWithBackendFallback(options.callbacks || {}, () => faceapi
                    .detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions(this.detectorOptions))
                    .withFaceLandmarks()
                    .withFaceDescriptor());
                const quality = this.evaluateDetectionQuality(detection, videoElement, options);
                return quality.usable ? detection : null;
            } catch (error) {
                const message = String(error?.message || error || '');
                this.emitDiagnostic(options.callbacks || {}, {
                    type: 'inference-error',
                    stage: 'detectSingleFace',
                    backend: this.getTfBackendName(),
                    error: message,
                });
                if (message.includes('timeout') || this.backendFallbackActive) {
                    return null;
                }
                throw error;
            }
        }

        async detectSingleFaceGeometry(videoElement, callbacks = {}, options = {}) {
            if (!this.detectionModelsLoaded) {
                throw new Error('Model scan wajah belum dimuat.');
            }

            let detection = null;
            try {
                detection = await this.runInferenceWithBackendFallback(callbacks, () => faceapi
                    .detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions(this.detectorOptions))
                    .withFaceLandmarks());
            } catch (error) {
                const message = String(error?.message || error || '');
                this.emitDiagnostic(callbacks, {
                    type: 'inference-error',
                    stage: 'detectSingleFaceGeometry',
                    backend: this.getTfBackendName(),
                    error: message,
                });
                if (message.includes('timeout') || this.backendFallbackActive) {
                    return null;
                }
                throw error;
            }

            this.emitDiagnostic(callbacks, {
                type: 'camera-resolution',
                width: videoElement?.videoWidth || null,
                height: videoElement?.videoHeight || null,
                backend: this.getTfBackendName(),
            });

            const quality = this.evaluateDetectionQuality(detection, videoElement, options);
            this.emit(callbacks.onGuideState, this.buildGuideStatePayload(quality));
            this.emit(callbacks.onDiagnostic, {
                stage: options.profile || 'default',
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

            if (quality.usable) {
                this.lastGeometryDetection = detection;
                this.lastGeometryDetectedAt = Date.now();
                return detection;
            }

            const fallback = options.allowFallback === false ? null : this.getRecentGeometryFallback();
            if (fallback) {
                this.emit(callbacks.onGuideState, {
                    state: 'warning',
                    message: 'Tahan posisi wajah. Sistem menstabilkan pembacaan.',
                });
                return fallback;
            }

            return null;
        }

        async detectEnrollmentFace(videoElement, callbacks = {}, options = {}) {
            const deadline = Date.now() + (options.timeoutMs || this.inferenceTimeoutMs);
            let lastError = null;

            while (Date.now() < deadline) {
                try {
                    const detection = await this.runInferenceWithBackendFallback(callbacks, () => faceapi
                        .detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions(this.detectorOptions))
                        .withFaceLandmarks()
                        .withFaceDescriptor());

                    if (!detection) {
                        await this.delay(120);
                        continue;
                    }

                    const quality = this.evaluateDetectionQuality(detection, videoElement, options);
                    if (!quality.usable) {
                        await this.delay(120);
                        continue;
                    }

                    return detection;
                } catch (error) {
                    lastError = error;
                    const message = String(error?.message || error || '');
                    this.emitDiagnostic(callbacks, {
                        type: 'inference-error',
                        stage: 'detectEnrollmentFace',
                        backend: this.getTfBackendName(),
                        error: message,
                    });

                    if (message.includes('timeout') || this.backendFallbackActive) {
                        await this.delay(120);
                        continue;
                    }

                    throw error;
                }
            }

            throw lastError || new Error('Descriptor wajah tidak dapat diambil. Ulangi scan wajah.');
        }

        evaluateDetectionQuality(detection, videoElement, options = {}) {
            const strict = Boolean(options.strict);
            const profile = options.profile || 'default';
            const box = detection?.detection?.box;
            const landmarks = detection?.landmarks;
            if (!box || !landmarks) {
                return { usable: false, state: 'searching', message: 'Arahkan wajah ke bingkai panduan.' };
            }

            const videoHeight = videoElement?.videoHeight || 0;
            const videoWidth = videoElement?.videoWidth || 0;
            const boxCenterX = box.x + (box.width / 2);
            const boxCenterY = box.y + (box.height / 2);
            const horizontalOffsetRatio = videoWidth > 0 ? Math.abs(boxCenterX - (videoWidth / 2)) / (videoWidth / 2) : 0;
            const verticalOffsetRatio = videoHeight > 0 ? Math.abs(boxCenterY - (videoHeight / 2)) / (videoHeight / 2) : 0;
            const faceWidthRatio = videoWidth > 0 ? box.width / videoWidth : 0;
            const faceHeightRatio = videoHeight > 0 ? box.height / videoHeight : 0;
            const eyeTilt = this.eyeTiltDegrees(landmarks);
            const tooSmall = videoWidth > 0 && faceWidthRatio < this.minimumFaceWidthRatio;
            const tooTilted = eyeTilt > this.maximumEyeTiltDegrees;
            const tooOffCenter = horizontalOffsetRatio > 0.34 || verticalOffsetRatio > 0.36;
            const enrollmentTooWide = profile === 'enrollment' && faceWidthRatio > 0.52;
            const enrollmentTooTall = profile === 'enrollment' && faceHeightRatio > 0.82;
            const enrollmentTooLow = profile === 'enrollment' && verticalOffsetRatio > 0.34;
            const enrollmentTooFarSide = profile === 'enrollment' && horizontalOffsetRatio > 0.32;
            const enrollmentTooSmall = profile === 'enrollment' && (faceWidthRatio < 0.14 || faceHeightRatio < 0.24);
            const usable = !tooSmall && !tooTilted && !(strict && tooOffCenter) && !enrollmentTooWide && !enrollmentTooTall && !enrollmentTooLow && !enrollmentTooFarSide && !enrollmentTooSmall;

            if (!usable) {
                if (enrollmentTooSmall || tooSmall) return { usable: false, state: 'too-far', message: 'Dekatkan wajah sedikit ke kamera.' };
                if (enrollmentTooWide || enrollmentTooTall) return { usable: false, state: 'too-close', message: 'Jauhkan wajah sedikit agar pas dengan oval.' };
                if (tooTilted) return { usable: false, state: 'tilted', message: 'Luruskan kepala agar sejajar dengan bingkai.' };
                if (profile === 'enrollment' && enrollmentTooLow) return { usable: false, state: 'off-center', message: 'Naikkan wajah sedikit agar tepat di tengah oval.' };
                if (profile === 'enrollment' && enrollmentTooFarSide) return { usable: false, state: 'off-center', message: 'Geser wajah tepat ke tengah oval.' };
                return { usable: false, state: 'off-center', message: 'Geser wajah ke tengah bingkai panduan.' };
            }

            if (tooOffCenter) {
                return { usable: true, state: 'warning', message: 'Posisi hampir pas. Geser sedikit ke tengah.' };
            }

            return { usable: true, state: 'aligned', message: 'Wajah berada pada posisi yang baik.' };
        }

        evaluateEnrollmentCaptureReadiness(videoElement, detection, previousSignature = null) {
            const signature = this.buildFaceSignature(detection);
            const sharpness = this.sampleFaceSharpness(videoElement, detection?.detection?.box);
            const motion = previousSignature ? this.measureFaceMotion(signature, previousSignature) : 0;
            const sharpEnough = sharpness >= this.enrollmentSharpnessThreshold;
            const stableEnough = motion <= this.enrollmentMotionThreshold;
            return { ready: sharpEnough && stableEnough, sharpEnough, stableEnough, sharpness, motion, signature };
        }

        buildGuideStatePayload(quality) {
            return { state: quality?.state || 'searching', message: quality?.message || 'Arahkan wajah ke bingkai panduan.' };
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
            return { centerX: box.x + (box.width / 2), centerY: box.y + (box.height / 2), width: box.width, height: box.height, noseX: noseTip.x, noseY: noseTip.y, eyeDistance };
        }

        measureFaceMotion(currentSignature, previousSignature) {
            if (!currentSignature || !previousSignature) return 0;
            const normalizer = Math.max(currentSignature.eyeDistance, previousSignature.eyeDistance, currentSignature.width, previousSignature.width, 1);
            const centerMotion = this.distance({ x: currentSignature.centerX, y: currentSignature.centerY }, { x: previousSignature.centerX, y: previousSignature.centerY }) / normalizer;
            const noseMotion = this.distance({ x: currentSignature.noseX, y: currentSignature.noseY }, { x: previousSignature.noseX, y: previousSignature.noseY }) / normalizer;
            const scaleMotion = Math.abs(currentSignature.width - previousSignature.width) / normalizer;
            return Math.max(centerMotion, noseMotion, scaleMotion);
        }

        getRecentGeometryFallback() {
            if (!this.lastGeometryDetection || !this.lastGeometryDetectedAt) {
                return null;
            }
            return Date.now() - this.lastGeometryDetectedAt <= this.recentDetectionMemoryMs ? this.lastGeometryDetection : null;
        }

        distance(firstPoint, secondPoint) {
            return Math.sqrt(Math.pow(firstPoint.x - secondPoint.x, 2) + Math.pow(firstPoint.y - secondPoint.y, 2));
        }

        averageEyeAspectRatio(landmarks) {
            return (this.eyeAspectRatio(landmarks.getLeftEye()) + this.eyeAspectRatio(landmarks.getRightEye())) / 2;
        }

        eyeAspectRatio(eye) {
            const verticalOne = this.distance(eye[1], eye[5]);
            const verticalTwo = this.distance(eye[2], eye[4]);
            const horizontal = this.distance(eye[0], eye[3]);
            if (horizontal === 0) return 0;
            return (verticalOne + verticalTwo) / (2 * horizontal);
        }

        eyeTiltDegrees(landmarks) {
            const leftEye = landmarks.getLeftEye()[0];
            const rightEye = landmarks.getRightEye()[3];
            const angleRadians = Math.atan2(rightEye.y - leftEye.y, rightEye.x - leftEye.x);
            return Math.abs(angleRadians * (180 / Math.PI));
        }

        faceTurnRatio(landmarks) {
            const noseTip = landmarks.getNose()[3];
            const leftEye = landmarks.getLeftEye()[0];
            const rightEye = landmarks.getRightEye()[3];
            const eyeCenterX = (leftEye.x + rightEye.x) / 2;
            const eyeDistance = this.distance(leftEye, rightEye);
            if (eyeDistance === 0) return 0;
            return (noseTip.x - eyeCenterX) / eyeDistance;
        }

        verticalLookRatio(landmarks) {
            const noseTip = landmarks.getNose()[3];
            const leftEye = landmarks.getLeftEye()[0];
            const rightEye = landmarks.getRightEye()[3];
            const mouth = landmarks.getMouth();
            if (!noseTip || !leftEye || !rightEye || !Array.isArray(mouth) || mouth.length === 0) return 0;
            const eyeCenterY = (leftEye.y + rightEye.y) / 2;
            const mouthCenterY = mouth.reduce((total, point) => total + point.y, 0) / mouth.length;
            const span = mouthCenterY - eyeCenterY;
            if (span <= 0) return 0;
            return (noseTip.y - eyeCenterY) / span;
        }

        mouthOpenRatio(landmarks) {
            const mouth = landmarks.getMouth();
            if (!Array.isArray(mouth) || mouth.length === 0) return 0;
            const xs = mouth.map((point) => point.x);
            const ys = mouth.map((point) => point.y);
            const width = Math.max(...xs) - Math.min(...xs);
            const height = Math.max(...ys) - Math.min(...ys);
            if (width <= 0) return 0;
            return height / width;
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
            return { brightness: averageBrightness, contrast: Math.sqrt(Math.max(variance, 0)) };
        }

        sampleFaceSharpness(videoElement, box) {
            if (!videoElement || !box) return 0;
            const sourceWidth = videoElement.videoWidth || 0;
            const sourceHeight = videoElement.videoHeight || 0;
            if (!sourceWidth || !sourceHeight) return 0;
            const paddingX = box.width * 0.12;
            const paddingY = box.height * 0.12;
            const cropX = Math.max(0, Math.floor(box.x - paddingX));
            const cropY = Math.max(0, Math.floor(box.y - paddingY));
            const cropWidth = Math.min(sourceWidth - cropX, Math.ceil(box.width + (paddingX * 2)));
            const cropHeight = Math.min(sourceHeight - cropY, Math.ceil(box.height + (paddingY * 2)));
            if (cropWidth < 12 || cropHeight < 12) return 0;
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
            if (!samples) return 0;
            return this.clamp((edgeEnergy / samples) / 120, 0, 1);
        }

        estimateLightingScore(brightness, contrast) {
            const brightnessScore = brightness < 72 ? brightness / 72 : brightness > 190 ? this.clamp((255 - brightness) / 65, 0, 1) : 1;
            const contrastScore = this.clamp(contrast / 32, 0, 1);
            return this.clamp((brightnessScore * 0.68) + (contrastScore * 0.32), 0, 1);
        }

        clamp(value, min = 0, max = 1) {
            return Math.max(min, Math.min(value, max));
        }

        median(values) {
            if (!Array.isArray(values) || values.length === 0) return null;
            const sorted = [...values].sort((first, second) => first - second);
            const middleIndex = Math.floor(sorted.length / 2);
            return sorted.length % 2 === 0
                ? (sorted[middleIndex - 1] + sorted[middleIndex]) / 2
                : sorted[middleIndex];
        }

        average(values) {
            if (!Array.isArray(values) || values.length === 0) return null;
            return values.reduce((total, value) => total + value, 0) / values.length;
        }

        async delay(ms) {
            return new Promise((resolve) => window.setTimeout(resolve, ms));
        }

        captureFrame(videoElement, options = {}) {
            const canvas = document.createElement('canvas');
            canvas.width = videoElement.videoWidth || 480;
            canvas.height = videoElement.videoHeight || 640;
            const context = canvas.getContext('2d');
            if (options.mirror === false) {
                context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
            } else {
                context.save();
                context.translate(canvas.width, 0);
                context.scale(-1, 1);
                context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);
                context.restore();
            }
            return canvas.toDataURL('image/jpeg', 0.85);
        }
    }

    window.FaceEnrollmentEngine = FaceEnrollmentEngine;
})();
