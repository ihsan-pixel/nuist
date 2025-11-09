// resources/js/face-recognition.js
class FaceRecognition {
    constructor() {
        this.MODEL_URL = window.MODEL_PATH || '/models';
        this.videoStream = null;
        this.videoEl = null;
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

    async performFullEnrollment(videoElement) {
        const results = { faceDescriptor: null, timestamp: Date.now() };

        // Detect face and get descriptor
        const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
        if (!detection) throw new Error('Gagal mendeteksi wajah untuk pendaftaran. Pastikan wajah terlihat jelas.');

        results.faceDescriptor = Array.from(detection.descriptor);
        return results;
    }

    async performFullVerification(videoElement, registeredFaceData) {
        const results = { faceVerified: false, faceSimilarity: 0, timestamp: Date.now() };

        // Detect face and get descriptor
        const detection = await faceapi.detectSingleFace(videoElement, new faceapi.TinyFaceDetectorOptions()).withFaceLandmarks().withFaceDescriptor();
        if (!detection) throw new Error('Gagal mendeteksi wajah untuk verifikasi. Pastikan wajah terlihat jelas.');

        // Compare with registered face
        const distance = faceapi.euclideanDistance(detection.descriptor, new Float32Array(registeredFaceData));
        results.faceSimilarity = Math.max(0, 1 - distance);
        results.faceVerified = results.faceSimilarity >= 0.6; // Lower threshold for simplicity

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
