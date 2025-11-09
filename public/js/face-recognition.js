// resources/js/face-recognition.js
class FaceRecognition {
    constructor() {
        this.MODEL_URL = window.MODEL_PATH || '/models';
        this.videoStream = null;
        this.videoEl = null;
        this.completedChallenges = [];
        this.challenges = [
            { type: 'blink', instruction: 'Silakan berkedip mata Anda' },
            { type: 'smile', instruction: 'Silakan tersenyum' },
            { type: 'head_turn', instruction: 'Silakan putar kepala ke kiri lalu ke kanan' }
        ];
    }

    async loadModels() {
        try {
            console.log('Loading face-api models from:', this.MODEL_URL);
            await faceapi.nets.tinyFaceDetector.loadFromUri(this.MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(this.MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(this.MODEL_URL);
            console.log('All models loaded successfully');
            return true;
        } catch (e) {
            console.error('loadModels error:', e);
            return false;
        }
    }

    async initializeCamera(videoElement) {
        this.videoEl = videoElement;
        if (!navigator.mediaDevices?.getUserMedia) {
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
        this.videoStream?.getTracks().forEach(t => t.stop());
        if (this.videoEl) {
            this.videoEl.pause();
            this.videoEl.srcObject = null;
            this.videoEl = null;
        }
    }

    _computeEAR(eye) {
        const dist = (p1, p2) => Math.hypot(p1.x - p2.x, p1.y - p2.y);
        const A = dist(eye[1], eye[5]);
        const B = dist(eye[2], eye[4]);
        const C = dist(eye[0], eye[3]);
        return C === 0 ? 0 : (A + B) / (2.0 * C);
    }

    async _waitForBlink(timeoutMs = 8000) {
        const EAR_THRESHOLD = 0.22;
        const SAMPLE_INTERVAL = 150;
        const start = Date.now();
        let blinkDetected = false;
        let lastEAR = null;

        while (Date.now() - start < timeoutMs) {
            const detection = await faceapi.detectSingleFace(this.videoEl, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks();
            if (detection?.landmarks) {
                const leftEAR = this._computeEAR(detection.landmarks.getLeftEye());
                const rightEAR = this._computeEAR(detection.landmarks.getRightEye());
                const ear = (leftEAR + rightEAR) / 2.0;
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

    generateChallengeSequence() {
        const challenges = [...this.challenges];
        for (let i = challenges.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [challenges[i], challenges[j]] = [challenges[j], challenges[i]];
        }
        return challenges;
    }

    showChallengeInstruction(challenge) {
        const instructionElement = document.getElementById('face-instruction-text');
        if (instructionElement) instructionElement.innerText = challenge.instruction;

        const dots = document.querySelectorAll('.challenge-dot');
        dots.forEach((dot, index) => {
            dot.classList.remove('active', 'completed');
            if (index < this.completedChallenges.length) dot.classList.add('completed');
            else if (index === this.completedChallenges.length) dot.classList.add('active');
        });
    }

    async waitForChallengeCompletion(videoElement, challenge, timeout = 10000) {
        const startTime = Date.now();
        while (Date.now() - startTime < timeout) {
            const result = await this.performLivenessCheck(videoElement, challenge);
            if (result.completed) {
                this.completedChallenges.push(challenge);
                return result;
            }
            await new Promise(r => setTimeout(r, 100));
        }
        throw new Error(`Waktu habis. Silakan coba lagi: ${challenge.instruction}`);
    }

    async performLivenessCheck(videoElement, challenge) {
        const detections = await faceapi.detectAllFaces(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions();
        if (detections.length === 0) throw new Error('Tidak ada wajah terdeteksi');
        if (detections.length > 1) throw new Error('Hanya satu wajah yang boleh terdeteksi');

        const detection = detections[0];
        let completed = false;

        switch (challenge.type) {
            case 'blink':
                completed = await this._detectBlink(detection.landmarks);
                break;
            case 'smile':
                completed = detection.expressions.happy > 0.7;
                break;
            case 'head_turn':
                completed = await this._detectHeadTurn(detection.landmarks);
                break;
            default:
                throw new Error(`Unknown challenge type: ${challenge.type}`);
        }

        return { completed, confidence: completed ? 0.9 : 0.1, timestamp: Date.now() };
    }

    async _detectBlink(landmarks) {
        const leftEAR = this._computeEAR(landmarks.getLeftEye());
        const rightEAR = this._computeEAR(landmarks.getRightEye());
        return (leftEAR + rightEAR) / 2.0 < 0.25;
    }

    async _detectHeadTurn(landmarks) {
        const nose = landmarks.getNose()[3];
        const leftEye = landmarks.getLeftEye()[0];
        const rightEye = landmarks.getRightEye()[3];
        const offsetX = nose.x - (leftEye.x + rightEye.x) / 2;
        return Math.abs(offsetX) > 15;
    }

    async performFullEnrollment(videoElement) {
        const sequence = this.generateChallengeSequence();
        this.completedChallenges = [];
        const results = { faceDescriptor: null, livenessScore: 0, challenges: [], timestamp: Date.now() };

        for (const challenge of sequence) {
            this.showChallengeInstruction(challenge);
            const result = await this.waitForChallengeCompletion(videoElement, challenge);
            results.challenges.push({ type: challenge.type, success: result.completed, score: result.confidence, timestamp: Date.now() });
            if (!result.completed) throw new Error(`Challenge ${challenge.type} gagal`);
        }

        const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
        if (!detection) throw new Error('Gagal mendeteksi wajah untuk pendaftaran akhir.');
        results.faceDescriptor = Array.from(detection.descriptor);
        results.livenessScore = results.challenges.reduce((sum, c) => sum + c.score, 0) / results.challenges.length;
        return results;
    }

    async performFullVerification(videoElement, registeredFaceData) {
        const sequence = this.generateChallengeSequence();
        this.completedChallenges = [];
        const results = { faceVerified: false, faceSimilarity: 0, livenessScore: 0, challenges: [], timestamp: Date.now() };

        for (const challenge of sequence) {
            this.showChallengeInstruction(challenge);
            const result = await this.waitForChallengeCompletion(videoElement, challenge);
            results.challenges.push({ type: challenge.type, success: result.completed, score: result.confidence, timestamp: Date.now() });
            if (!result.completed) throw new Error(`Challenge ${challenge.type} gagal`);
        }

        const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
        if (!detection) throw new Error('Gagal mendeteksi wajah untuk verifikasi.');

        const distance = faceapi.euclideanDistance(detection.descriptor, new Float32Array(registeredFaceData));
        results.faceSimilarity = Math.max(0, 1 - distance);
        results.faceVerified = results.faceSimilarity >= 0.7;
        results.livenessScore = results.challenges.reduce((sum, c) => sum + c.score, 0) / results.challenges.length;
        return results;
    }
}

// Instance global
if (!window.faceRecognition) {
    window.faceRecognition = new FaceRecognition();
    console.log('faceRecognition instance created ✅');
} else {
    console.log('faceRecognition already exists, reusing instance.');
}
