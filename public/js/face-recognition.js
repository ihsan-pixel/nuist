class FaceRecognition {
    constructor() {
        this.MODEL_URL = '/models';
        this.videoStream = null;
        this.videoEl = null;
    }

    async loadModels() {
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri(this.MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(this.MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(this.MODEL_URL);
            return true;
        } catch (e) {
            console.error('loadModels error', e);
            return false;
        }
    }

    async initializeCamera(videoElement) {
        this.videoEl = videoElement;
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            throw new Error('Browser does not support camera API');
        }
        this.videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
        videoElement.srcObject = this.videoStream;
        return new Promise((resolve) => {
            videoElement.onloadedmetadata = () => {
                videoElement.play();
                resolve();
            };
        });
    }

    stopCamera() {
        if (this.videoStream) {
            this.videoStream.getTracks().forEach(t => t.stop());
            this.videoStream = null;
        }
        if (this.videoEl) {
            this.videoEl.pause();
            this.videoEl.srcObject = null;
            this.videoEl = null;
        }
    }

    // compute eye aspect ratio (EAR) for blink detection
    _computeEAR(eye) {
        // eye: array of 6 points
        const dist = (p1, p2) => Math.hypot(p1.x - p2.x, p1.y - p2.y);
        const A = dist(eye[1], eye[5]);
        const B = dist(eye[2], eye[4]);
        const C = dist(eye[0], eye[3]);
        if (C === 0) return 0;
        return (A + B) / (2.0 * C);
    }

    async _waitForBlink(timeoutMs = 8000) {
        // simple blink detector: watch EAR and detect a drop below threshold
        const EAR_THRESHOLD = 0.22; // tuned for face-api landmark coords; may need adjustment
        const SAMPLE_INTERVAL = 150;
        const start = Date.now();
        let blinkDetected = false;
        let lastEAR = null;

        while (Date.now() - start < timeoutMs) {
            const detection = await faceapi.detectSingleFace(this.videoEl, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks();
            if (detection && detection.landmarks) {
                const lm = detection.landmarks;
                const leftEye = lm.getLeftEye();
                const rightEye = lm.getRightEye();
                const leftEAR = this._computeEAR(leftEye);
                const rightEAR = this._computeEAR(rightEye);
                const ear = (leftEAR + rightEAR) / 2.0;
                // detect blink as quick drop
                if (lastEAR !== null && ear < EAR_THRESHOLD && lastEAR > EAR_THRESHOLD + 0.02) {
                    blinkDetected = true;
                    break;
                }
                lastEAR = ear;
            }
            await new Promise(r => setTimeout(r, SAMPLE_INTERVAL));
        }
        return blinkDetected;
    }

    async enrollFace(videoElement, options = { samples: 3 }) {
        // Capture a small set of descriptors while ensuring liveness via blink
        const samples = options.samples || 3;
        const descriptors = [];
        let progressStep = 0;

        // simple instruction: ask user to blink once to prove liveness
        const blinkOk = await this._waitForBlink(9000);
        if (!blinkOk) {
            throw new Error('Tidak terdeteksi kedipan. Pastikan Anda berkedip saat diminta.');
        }

        // capture multiple descriptors
        for (let i = 0; i < samples; i++) {
            // wait until face detected
            let det = null;
            const start = Date.now();
            while (!det && (Date.now() - start) < 5000) {
                det = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
                if (!det) await new Promise(r => setTimeout(r, 200));
            }
            if (!det) throw new Error('Gagal mendeteksi wajah untuk pendaftaran. Pastikan wajah terlihat jelas dan pencahayaan cukup.');
            descriptors.push(Array.from(det.descriptor));
            progressStep++;
            // small pause between samples
            await new Promise(r => setTimeout(r, 500));
        }

        return {
            descriptors: descriptors,
            enrolled_at: new Date().toISOString(),
            liveness: { blink: true }
        };
    }
}

// Export for usage in pages that include this script
window.FaceRecognition = FaceRecognition;