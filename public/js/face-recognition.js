// ==============================
// FaceRecognition.js - Final
// ==============================

function FaceRecognition() {
    this.MODEL_URL = window.MODEL_PATH || '/models';
    this.videoStream = null;
    this.videoEl = null;
    this.challenges = [
        { type: 'blink', instruction: 'Silakan berkedip mata Anda' },
        { type: 'smile', instruction: 'Silakan tersenyum' },
        { type: 'head_turn', instruction: 'Silakan putar kepala ke kiri lalu ke kanan' }
    ];
    this.completedChallenges = [];
}

// ------------------------------
// Load face-api models
// ------------------------------
FaceRecognition.prototype.loadModels = async function() {
    try {
        console.log('Loading face-api models from:', this.MODEL_URL);
        await faceapi.nets.tinyFaceDetector.loadFromUri(this.MODEL_URL);
        await faceapi.nets.faceLandmark68Net.loadFromUri(this.MODEL_URL);
        await faceapi.nets.faceRecognitionNet.loadFromUri(this.MODEL_URL);
        await faceapi.nets.faceExpressionNet.loadFromUri(this.MODEL_URL);
        console.log('All models loaded successfully');
        return true;
    } catch (e) {
        console.error('loadModels error:', e);
        return false;
    }
};

// ------------------------------
// Initialize camera
// ------------------------------
FaceRecognition.prototype.initializeCamera = async function(videoElement) {
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
};

// ------------------------------
// Stop camera
// ------------------------------
FaceRecognition.prototype.stopCamera = function() {
    if (this.videoStream) {
        this.videoStream.getTracks().forEach(t => t.stop());
        this.videoStream = null;
    }
    if (this.videoEl) {
        this.videoEl.pause();
        this.videoEl.srcObject = null;
        this.videoEl = null;
    }
};

// ------------------------------
// Compute EAR for blink detection
// ------------------------------
FaceRecognition.prototype._computeEAR = function(eye) {
    const dist = (p1, p2) => Math.hypot(p1.x - p2.x, p1.y - p2.y);
    const A = dist(eye[1], eye[5]);
    const B = dist(eye[2], eye[4]);
    const C = dist(eye[0], eye[3]);
    if (C === 0) return 0;
    return (A + B) / (2.0 * C);
};

// ------------------------------
// Wait for blink
// ------------------------------
FaceRecognition.prototype._waitForBlink = async function(timeoutMs = 8000) {
    const EAR_THRESHOLD = 0.22;
    const SAMPLE_INTERVAL = 150;
    const start = Date.now();
    let lastEAR = null;

    while (Date.now() - start < timeoutMs) {
        const detection = await faceapi.detectSingleFace(this.videoEl, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks();
        if (detection && detection.landmarks) {
            const lm = detection.landmarks;
            const leftEAR = this._computeEAR(lm.getLeftEye());
            const rightEAR = this._computeEAR(lm.getRightEye());
            const ear = (leftEAR + rightEAR) / 2.0;
            if (lastEAR !== null && ear < EAR_THRESHOLD && lastEAR > EAR_THRESHOLD + 0.02) {
                return true;
            }
            lastEAR = ear;
        }
        await new Promise(r => setTimeout(r, SAMPLE_INTERVAL));
    }
    return false;
};

// ------------------------------
// Generate random challenge sequence
// ------------------------------
FaceRecognition.prototype.generateChallengeSequence = function() {
    const sequence = [...this.challenges];
    for (let i = sequence.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [sequence[i], sequence[j]] = [sequence[j], sequence[i]];
    }
    console.log('Challenge sequence generated:', sequence);
    return sequence;
};

// ------------------------------
// Show challenge instruction
// ------------------------------
FaceRecognition.prototype.showChallengeInstruction = function(challenge) {
    const instructionEl = document.getElementById('face-instruction-text');
    if (instructionEl) instructionEl.innerText = challenge.instruction;

    const dots = document.querySelectorAll('.challenge-dot');
    dots.forEach((dot, idx) => {
        if (idx < this.completedChallenges.length) {
            dot.classList.add('completed');
            dot.classList.remove('active');
        } else if (idx === this.completedChallenges.length) {
            dot.classList.add('active');
            dot.classList.remove('completed');
        } else {
            dot.classList.remove('active', 'completed');
        }
    });
};

// ------------------------------
// Wait for challenge completion
// ------------------------------
FaceRecognition.prototype.waitForChallengeCompletion = async function(videoElement, challenge, timeout = 10000) {
    return new Promise((resolve, reject) => {
        const startTime = Date.now();

        const checkChallenge = async () => {
            try {
                const result = await this.performLivenessCheck(videoElement, challenge);
                if (result.completed) {
                    this.completedChallenges.push(challenge);
                    resolve(result);
                    return;
                }
                if (Date.now() - startTime > timeout) {
                    reject(new Error(`Waktu habis. Silakan coba lagi: ${challenge.instruction}`));
                    return;
                }
                setTimeout(checkChallenge, 100);
            } catch (error) {
                reject(error);
            }
        };
        checkChallenge();
    });
};

// ------------------------------
// Liveness check
// ------------------------------
FaceRecognition.prototype.performLivenessCheck = async function(videoElement, challenge) {
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
};

// ------------------------------
// Challenge detectors
// ------------------------------
FaceRecognition.prototype._detectBlink = async function(landmarks) {
    const leftEAR = this._computeEAR(landmarks.getLeftEye());
    const rightEAR = this._computeEAR(landmarks.getRightEye());
    return (leftEAR + rightEAR) / 2.0 < 0.25;
};

FaceRecognition.prototype._detectHeadTurn = async function(landmarks) {
    const nose = landmarks.getNose()[3];
    const leftEye = landmarks.getLeftEye()[0];
    const rightEye = landmarks.getRightEye()[3];
    const faceCenterX = (leftEye.x + rightEye.x) / 2;
    return Math.abs(nose.x - faceCenterX) > 15;
};

// ------------------------------
// Full Enrollment
// ------------------------------
FaceRecognition.prototype.performFullEnrollment = async function(videoElement) {
    const sequence = this.generateChallengeSequence();
    const results = { faceDescriptor: null, livenessScore: 0, challenges: [], timestamp: Date.now() };
    this.completedChallenges = [];

    for (const challenge of sequence) {
        this.showChallengeInstruction(challenge);
        const res = await this.waitForChallengeCompletion(videoElement, challenge);
        results.challenges.push({ type: challenge.type, success: res.completed, score: res.confidence, timestamp: Date.now() });
        if (!res.completed) throw new Error(`Challenge ${challenge.type} gagal`);
    }

    const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks().withFaceDescriptor();
    if (!detection) throw new Error('Gagal mendeteksi wajah untuk pendaftaran akhir');

    results.faceDescriptor = Array.from(detection.descriptor);
    results.livenessScore = results.challenges.reduce((sum, c) => sum + c.score, 0) / results.challenges.length;
    return results;
};

// ------------------------------
// Full Verification
// ------------------------------
FaceRecognition.prototype.performFullVerification = async function(videoElement, registeredFaceData) {
    const sequence = this.generateChallengeSequence();
    const results = { faceVerified: false, faceId: null, faceSimilarity: 0, livenessScore: 0, challenges: [], timestamp: Date.now() };
    this.completedChallenges = [];

    for (const challenge of sequence) {
        this.showChallengeInstruction(challenge);
        const res = await this.waitForChallengeCompletion(videoElement, challenge);
        results.challenges.push({ type: challenge.type, success: res.completed, score: res.confidence, timestamp: Date.now() });
        if (!res.completed) throw new Error(`Challenge ${challenge.type} gagal`);
    }

    const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions())
        .withFaceLandmarks().withFaceDescriptor();
    if (!detection) throw new Error('Gagal mendeteksi wajah untuk verifikasi');

    const currentDescriptor = detection.descriptor;
    const registeredDescriptor = new Float32Array(registeredFaceData);
    const distance = faceapi.euclideanDistance(currentDescriptor, registeredDescriptor);

    results.faceSimilarity = Math.max(0, 1 - distance);
    results.faceVerified = results.faceSimilarity >= 0.7;
    results.livenessScore = results.challenges.reduce((sum, c) => sum + c.score, 0) / results.challenges.length;

    return results;
};

// ------------------------------
// Global instance
// ------------------------------
if (!window.faceRecognition) {
    window.faceRecognition = new FaceRecognition();
    console.log("faceRecognition instance created ✅");
} else {
    console.log("faceRecognition already exists, reusing instance.");
}
