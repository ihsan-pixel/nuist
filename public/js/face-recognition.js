// Face Recognition and Liveness Detection Module
class FaceRecognition {
    constructor() {
        this.modelsLoaded = false;
        this.currentUserFaceData = null;
        this.challenges = [
            { type: 'blink', instruction: 'Silakan berkedip mata Anda', detector: this.detectBlink.bind(this) },
            { type: 'smile', instruction: 'Silakan tersenyum', detector: this.detectSmile.bind(this) },
            { type: 'head_turn', instruction: 'Silakan putar kepala ke kiri lalu ke kanan', detector: this.detectHeadTurn.bind(this) }
        ];
        this.challengeSequence = [];
        this.completedChallenges = [];
    }

    async loadModels() {
        try {
            // Load face-api.js models
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                faceapi.nets.faceExpressionNet.loadFromUri('/models')
            ]);

            this.modelsLoaded = true;
            console.log('Face recognition models loaded successfully');
            return true;
        } catch (error) {
            console.error('Error loading face recognition models:', error);
            return false;
        }
    }

    async initializeCamera(videoElement) {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: 640,
                    height: 480,
                    facingMode: 'user'
                }
            });

            videoElement.srcObject = stream;
            return new Promise((resolve) => {
                videoElement.onloadedmetadata = () => {
                    videoElement.play();
                    resolve(true);
                };
            });
        } catch (error) {
            console.error('Error accessing camera:', error);
            throw new Error('Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.');
        }
    }

    generateChallengeSequence(length = 5) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    async detectFaces(videoElement) {
        if (!this.modelsLoaded) {
            throw new Error('Models belum dimuat');
        }

        const detections = await faceapi
            .detectAllFaces(videoElement, new faceapi.TinyFaceDetectorOptions())
            .withFaceLandmarks()
            .withFaceExpressions()
            .withFaceDescriptors();

        return detections;
    }

    async detectBlink(landmarks) {
        // Simple blink detection based on eye aspect ratio
        const leftEye = landmarks.getLeftEye();
        const rightEye = landmarks.getRightEye();

        const leftEAR = this.calculateEyeAspectRatio(leftEye);
        const rightEAR = this.calculateEyeAspectRatio(rightEye);
        const avgEAR = (leftEAR + rightEAR) / 2;

        // EAR below 0.25 indicates blink
        return avgEAR < 0.25;
    }

    calculateEyeAspectRatio(eye) {
        // Calculate eye aspect ratio
        const A = this.distance(eye[1], eye[5]);
        const B = this.distance(eye[2], eye[4]);
        const C = this.distance(eye[0], eye[3]);
        return (A + B) / (2.0 * C);
    }

    distance(point1, point2) {
        return Math.sqrt(Math.pow(point1.x - point2.x, 2) + Math.pow(point1.y - point2.y, 2));
    }

    async detectSmile(expressions) {
        return expressions.happy > 0.7;
    }

    async detectHeadTurn(landmarks) {
        // Detect head turn by analyzing nose position relative to eyes
        const nose = landmarks.getNose()[3]; // Nose tip
        const leftEye = landmarks.getLeftEye()[0];
        const rightEye = landmarks.getRightEye()[3];

        const faceCenterX = (leftEye.x + rightEye.x) / 2;
        const offsetX = nose.x - faceCenterX;

        // Significant horizontal offset indicates head turn
        return Math.abs(offsetX) > 15;
    }

    async performLivenessCheck(videoElement, challenge) {
        const detections = await this.detectFaces(videoElement);

        if (detections.length === 0) {
            throw new Error('Tidak ada wajah terdeteksi');
        }

        if (detections.length > 1) {
            throw new Error('Hanya satu wajah yang boleh terdeteksi');
        }

        const detection = detections[0];
        const challengeCompleted = await challenge.detector(detection.landmarks, detection.expressions);

        return {
            completed: challengeCompleted,
            confidence: challengeCompleted ? 0.9 : 0.1,
            timestamp: Date.now()
        };
    }

    async captureFaceDescriptor(videoElement) {
        const detections = await this.detectFaces(videoElement);

        if (detections.length === 0) {
            throw new Error('Tidak ada wajah terdeteksi');
        }

        if (detections.length > 1) {
            throw new Error('Hanya satu wajah yang boleh terdeteksi');
        }

        return detections[0].descriptor;
    }

    async compareFaces(descriptor1, descriptor2) {
        const distance = faceapi.euclideanDistance(descriptor1, descriptor2);
        // Convert distance to similarity score (0-1, where 1 is perfect match)
        const similarity = Math.max(0, 1 - distance);
        return similarity;
    }

    async enrollFace(videoElement) {
        try {
            const descriptor = await this.captureFaceDescriptor(videoElement);
            const faceId = this.generateFaceId();

            return {
                faceId: faceId,
                faceData: Array.from(descriptor),
                enrolledAt: new Date().toISOString()
            };
        } catch (error) {
            console.error('Error enrolling face:', error);
            throw error;
        }
    }

    generateFaceId() {
        return 'face_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    async verifyFace(videoElement, registeredFaceData) {
        try {
            const currentDescriptor = await this.captureFaceDescriptor(videoElement);
            const registeredDescriptor = new Float32Array(registeredFaceData);

            const similarity = await this.compareFaces(currentDescriptor, registeredDescriptor);

            return {
                verified: similarity >= 0.8, // Threshold for face verification
                similarity: similarity,
                faceId: this.generateFaceId()
            };
        } catch (error) {
            console.error('Error verifying face:', error);
            throw error;
        }
    }

    async performFullVerification(videoElement, registeredFaceData) {
        try {
            // Generate challenge sequence
            const challenges = this.generateChallengeSequence();

            let totalLivenessScore = 0;
            const challengeResults = [];

            // Perform each challenge in sequence
            for (const challenge of challenges) {
                // Show instruction to user
                this.showChallengeInstruction(challenge);

                // Wait for user to complete challenge
                const result = await this.waitForChallengeCompletion(videoElement, challenge);
                challengeResults.push(result);
                totalLivenessScore += result.confidence;
            }

            const avgLivenessScore = totalLivenessScore / challenges.length;

            // Perform face verification
            const faceVerification = await this.verifyFace(videoElement, registeredFaceData);

            return {
                faceVerified: faceVerification.verified,
                faceSimilarity: faceVerification.similarity,
                livenessScore: avgLivenessScore,
                livenessVerified: avgLivenessScore >= 0.7, // Threshold for liveness
                challenges: challengeResults,
                faceId: faceVerification.faceId,
                timestamp: Date.now()
            };

        } catch (error) {
            console.error('Error in full verification:', error);
            throw error;
        }
    }

    async performFullEnrollment(videoElement) {
        try {
            // Generate challenge sequence for enrollment
            const challenges = this.generateChallengeSequence();

            let totalLivenessScore = 0;
            const challengeResults = [];

            // Perform each challenge in sequence
            for (const challenge of challenges) {
                // Show instruction to user
                this.showChallengeInstruction(challenge);

                // Wait for user to complete challenge
                const result = await this.waitForChallengeCompletion(videoElement, challenge);
                challengeResults.push(result);
                totalLivenessScore += result.confidence;
            }

            const avgLivenessScore = totalLivenessScore / challenges.length;

            // Capture face descriptor for enrollment
            const faceDescriptor = await this.captureFaceDescriptor(videoElement);

            return {
                faceDescriptor: Array.from(faceDescriptor),
                livenessScore: avgLivenessScore,
                livenessVerified: avgLivenessScore >= 0.7, // Threshold for liveness
                challenges: challengeResults,
                faceId: this.generateFaceId(),
                timestamp: Date.now()
            };

        } catch (error) {
            console.error('Error in full enrollment:', error);
            throw error;
        }
    }

    showChallengeInstruction(challenge) {
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
    }

    async waitForChallengeCompletion(videoElement, challenge, timeout = 10000) {
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
    }
}

// Export for use in other modules
window.FaceRecognition = FaceRecognition;
