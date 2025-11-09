function FaceRecognition() {
    // Use Laravel's asset helper to get the correct path
    this.MODEL_URL = window.MODEL_PATH || '/models';
    this.videoStream = null;
    this.videoEl = null;
    this.challenges = [
        { type: 'blink', instruction: 'Silakan berkedip mata Anda' },
        { type: 'smile', instruction: 'Silakan tersenyum' },
        { type: 'head_turn', instruction: 'Silakan putar kepala ke kiri lalu ke kanan' }
    ];
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

FaceRecognition.prototype.showChallengeInstruction = function(challenge) {
    // Update UI with challenge instruction
    const instructionElement = document.getElementById('face-instruction-text');
    if (instructionElement) {
        instructionElement.innerText = challenge.instruction;
    }

    // Update progress dots
    const dots = document.querySelectorAll('.challenge-dot');
    dots.forEach((dot, index) => {
        if (index < this.completedChallenges.length) {
            dot.classList.add('completed');
            dot.classList.remove('active');
        } else if (index === this.completedChallenges.length) {
            dot.classList.add('active');
            dot.classList.remove('completed');
        } else {
            dot.classList.remove('active', 'completed');
        }
    });
};

FaceRecognition.prototype.waitForChallengeCompletion = async function(videoElement, challenge, timeout = 10000) {
    return new Promise((resolve, reject) => {
        const startTime = Date.now();

        const checkChallenge = async () => {
            try {
                const result = await this.performLivenessCheck(videoElement, challenge);

                if (result.completed) {
                    // Add to completed challenges
                    this.completedChallenges.push(challenge);
                    resolve(result);
                    return;
                }

                if (Date.now() - startTime > timeout) {
                    reject(new Error(`Waktu habis. Silakan coba lagi: ${challenge.instruction}`));
                    return;
                }

                // Continue checking
                setTimeout(checkChallenge, 100);
            } catch (error) {
                reject(error);
            }
        };

        checkChallenge();
    });
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

    // Reset completed challenges
    this.completedChallenges = [];

    // Perform each challenge in sequence
    for (let i = 0; i < sequence.length; i++) {
        const challenge = sequence[i];
        console.log(`Performing challenge ${i + 1}: ${challenge.type}`);

        // Show instruction to user
        this.showChallengeInstruction(challenge);

        // Wait for user to complete challenge
        const challengeResult = await this.waitForChallengeCompletion(videoElement, challenge);
        results.challenges.push({
            type: challenge.type,
            success: challengeResult.completed,
            score: challengeResult.confidence,
            timestamp: Date.now()
        });

        if (!challengeResult.completed) {
            throw new Error(`Challenge ${challenge.type} gagal. Silakan coba lagi.`);
        }
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

    // Reset completed challenges
    this.completedChallenges = [];

    // Perform each challenge in sequence
    for (let i = 0; i < sequence.length; i++) {
        const challenge = sequence[i];
        console.log(`Performing challenge ${i + 1}: ${challenge.type}`);

        // Show instruction to user
        this.showChallengeInstruction(challenge);

        // Wait for user to complete challenge
        const challengeResult = await this.waitForChallengeCompletion(videoElement, challenge);
        results.challenges.push({
            type: challenge.type,
            success: challengeResult.completed,
            score: challengeResult.confidence,
            timestamp: Date.now()
        });

        if (!challengeResult.completed) {
            throw new Error(`Challenge ${challenge.type} gagal. Silakan coba lagi.`);
        }
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

FaceRecognition.prototype.performLivenessCheck = async function(videoElement, challenge) {
    const detections = await faceapi.detectAllFaces(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceExpressions();

    if (detections.length === 0) {
        throw new Error('Tidak ada wajah terdeteksi');
    }

    if (detections.length > 1) {
        throw new Error('Hanya satu wajah yang boleh terdeteksi');
    }

    const detection = detections[0];
    let challengeCompleted = false;

    switch (challenge.type) {
        case 'blink':
            challengeCompleted = await this._detectBlink(detection.landmarks);
            break;
        case 'smile':
            challengeCompleted = detection.expressions.happy > 0.7;
            break;
        case 'head_turn':
            challengeCompleted = await this._detectHeadTurn(detection.landmarks);
            break;
        default:
            throw new Error(`Unknown challenge type: ${challenge.type}`);
    }

    return {
        completed: challengeCompleted,
        confidence: challengeCompleted ? 0.9 : 0.1,
        timestamp: Date.now()
    };
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

FaceRecognition.prototype._detectBlink = async function(landmarks) {
    // Simple blink detection based on eye aspect ratio
    const leftEye = landmarks.getLeftEye();
    const rightEye = landmarks.getRightEye();

    const leftEAR = this._computeEAR(leftEye);
    const rightEAR = this._computeEAR(rightEye);
    const avgEAR = (leftEAR + rightEAR) / 2.0;

    // EAR below 0.25 indicates blink
    return avgEAR < 0.25;
};

FaceRecognition.prototype._detectHeadTurn = async function(landmarks) {
    // Simple head turn detection based on nose position relative to eyes
    const nose = landmarks.getNose()[3]; // Nose tip
    const leftEye = landmarks.getLeftEye()[0];
    const rightEye = landmarks.getRightEye()[3];

    const faceCenterX = (leftEye.x + rightEye.x) / 2;
    const offsetX = nose.x - faceCenterX;

    // Significant horizontal offset indicates head turn
    return Math.abs(offsetX) > 15;
};

FaceRecognition.prototype.enrollFace = async function(videoElement, options = { samples: 3 }) {
    // Legacy method - kept for compatibility
    return await this.performFullEnrollment(videoElement);
};

// Export for usage in pages that include this script
window.FaceRecognition = FaceRecognition;
