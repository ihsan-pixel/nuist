function FaceRecognition() {
    // Use Laravel's asset helper to get the correct path
    this.MODEL_URL = window.MODEL_PATH || '/models';
    this.videoStream = null;
    this.videoEl = null;
    this.challenges = ['blink', 'smile', 'head_turn'];
}

FaceRecognition.prototype.loadModels = async function() {
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
};

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

// compute eye aspect ratio (EAR) for blink detection
FaceRecognition.prototype._computeEAR = function(eye) {
    // eye: array of 6 points
    const dist = (p1, p2) => Math.hypot(p1.x - p2.x, p1.y - p2.y);
    const A = dist(eye[1], eye[5]);
    const B = dist(eye[2], eye[4]);
    const C = dist(eye[0], eye[3]);
    if (C === 0) return 0;
    return (A + B) / (2.0 * C);
};

FaceRecognition.prototype._waitForBlink = async function(timeoutMs = 8000) {
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
};

FaceRecognition.prototype.generateChallengeSequence = function() {
    // Generate a random sequence of 3 challenges
    const sequence = [];
    const availableChallenges = [...this.challenges];

    for (let i = 0; i < 3; i++) {
        const randomIndex = Math.floor(Math.random() * availableChallenges.length);
        sequence.push(availableChallenges.splice(randomIndex, 1)[0]);
    }

    return sequence;
};

FaceRecognition.prototype.performFullEnrollment = async function(videoElement) {
    const sequence = this.generateChallengeSequence();
    console.log('Enrollment sequence:', sequence);

    const results = {
        faceDescriptor: null,
        livenessScore: 0,
        challenges: [],
        timestamp: Date.now()
    };

    // Perform each challenge in sequence
    for (let i = 0; i < sequence.length; i++) {
        const challenge = sequence[i];
        console.log(`Performing challenge ${i + 1}: ${challenge}`);

        const challengeResult = await this.performChallenge(videoElement, challenge);
        results.challenges.push({
            type: challenge,
            success: challengeResult.success,
            score: challengeResult.score,
            timestamp: Date.now()
        });

        if (!challengeResult.success) {
            throw new Error(`Challenge ${challenge} gagal. Silakan coba lagi.`);
        }

        // Update progress dots
        this.updateProgressDots(i + 1);
    }

    // After all challenges, capture final face descriptor
    const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
    if (!detection) {
        throw new Error('Gagal mendeteksi wajah untuk pendaftaran akhir.');
    }

    results.faceDescriptor = Array.from(detection.descriptor);
    results.livenessScore = results.challenges.reduce((sum, c) => sum + c.score, 0) / results.challenges.length;

    return results;
};

FaceRecognition.prototype.performFullVerification = async function(videoElement, registeredFaceData) {
    const sequence = this.generateChallengeSequence();
    console.log('Verification sequence:', sequence);

    const results = {
        faceVerified: false,
        faceId: null,
        faceSimilarity: 0,
        livenessScore: 0,
        challenges: [],
        timestamp: Date.now()
    };

    // Perform each challenge in sequence
    for (let i = 0; i < sequence.length; i++) {
        const challenge = sequence[i];
        console.log(`Performing challenge ${i + 1}: ${challenge}`);

        const challengeResult = await this.performChallenge(videoElement, challenge);
        results.challenges.push({
            type: challenge,
            success: challengeResult.success,
            score: challengeResult.score,
            timestamp: Date.now()
        });

        if (!challengeResult.success) {
            throw new Error(`Challenge ${challenge} gagal. Silakan coba lagi.`);
        }

        // Update progress dots
        this.updateProgressDots(i + 1);
    }

    // After all challenges, verify face
    const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
    if (!detection) {
        throw new Error('Gagal mendeteksi wajah untuk verifikasi.');
    }

    const currentDescriptor = detection.descriptor;
    const registeredDescriptor = new Float32Array(registeredFaceData);

    // Calculate similarity
    const distance = faceapi.euclideanDistance(currentDescriptor, registeredDescriptor);
    results.faceSimilarity = Math.max(0, 1 - distance);

    // Face verification threshold
    results.faceVerified = results.faceSimilarity >= 0.7;
    results.livenessScore = results.challenges.reduce((sum, c) => sum + c.score, 0) / results.challenges.length;

    return results;
};

FaceRecognition.prototype.performChallenge = async function(videoElement, challengeType) {
    switch (challengeType) {
        case 'blink':
            return await this.performBlinkChallenge(videoElement);
        case 'smile':
            return await this.performSmileChallenge(videoElement);
        case 'head_turn':
            return await this.performHeadTurnChallenge(videoElement);
        default:
            throw new Error(`Unknown challenge type: ${challengeType}`);
    }
};

FaceRecognition.prototype.performBlinkChallenge = async function(videoElement) {
    // Wait for blink detection
    const blinkDetected = await this._waitForBlink(8000);
    return {
        success: blinkDetected,
        score: blinkDetected ? 1.0 : 0.0
    };
};

FaceRecognition.prototype.performSmileChallenge = async function(videoElement) {
    // Simple smile detection based on mouth landmarks
    const smileDetected = await this._waitForSmile(8000);
    return {
        success: smileDetected,
        score: smileDetected ? 1.0 : 0.0
    };
};

FaceRecognition.prototype.performHeadTurnChallenge = async function(videoElement) {
    // Simple head turn detection based on landmark movement
    const headTurnDetected = await this._waitForHeadTurn(8000);
    return {
        success: headTurnDetected,
        score: headTurnDetected ? 1.0 : 0.0
    };
};

FaceRecognition.prototype.updateProgressDots = function(completedCount) {
    // Update UI progress dots if they exist
    for (let i = 1; i <= 3; i++) {
        const dot = document.getElementById(`challenge-${i}`);
        if (dot) {
            dot.classList.remove('active', 'completed');
            if (i <= completedCount) {
                dot.classList.add('completed');
            } else if (i === completedCount + 1) {
                dot.classList.add('active');
            }
        }
    }
};

FaceRecognition.prototype._waitForSmile = async function(timeoutMs = 8000) {
    // Simple smile detector based on mouth corner positions
    const start = Date.now();
    let smileDetected = false;

    while (Date.now() - start < timeoutMs) {
        const detection = await faceapi.detectSingleFace(this.videoEl, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks();
        if (detection && detection.landmarks) {
            const mouth = detection.landmarks.getMouth();
            if (mouth && mouth.length >= 4) {
                // Simple smile detection: check if mouth corners are raised
                const leftCorner = mouth[0];
                const rightCorner = mouth[6];
                const mouthCenter = mouth[3];

                // Calculate mouth width and height
                const mouthWidth = Math.abs(rightCorner.x - leftCorner.x);
                const mouthHeight = Math.abs(mouthCenter.y - Math.min(leftCorner.y, rightCorner.y));

                // Simple smile threshold
                if (mouthHeight > mouthWidth * 0.3) {
                    smileDetected = true;
                    break;
                }
            }
        }
        await new Promise(r => setTimeout(r, 200));
    }
    return smileDetected;
};

FaceRecognition.prototype._waitForHeadTurn = async function(timeoutMs = 8000) {
    // Simple head turn detection based on nose position changes
    const start = Date.now();
    let initialNosePos = null;
    let headTurnDetected = false;

    while (Date.now() - start < timeoutMs) {
        const detection = await faceapi.detectSingleFace(this.videoEl, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks();
        if (detection && detection.landmarks) {
            const nose = detection.landmarks.getNose();
            if (nose && nose.length > 0) {
                const currentNosePos = nose[3]; // nose tip

                if (!initialNosePos) {
                    initialNosePos = { x: currentNosePos.x, y: currentNosePos.y };
                } else {
                    // Check for significant horizontal movement
                    const movementX = Math.abs(currentNosePos.x - initialNosePos.x);
                    if (movementX > 30) { // pixels threshold
                        headTurnDetected = true;
                        break;
                    }
                }
            }
        }
        await new Promise(r => setTimeout(r, 200));
    }
    return headTurnDetected;
};

FaceRecognition.prototype.enrollFace = async function(videoElement, options = { samples: 3 }) {
    // Legacy method - kept for compatibility
    return await this.performFullEnrollment(videoElement);
};

// Export for usage in pages that include this script
window.FaceRecognition = FaceRecognition;
