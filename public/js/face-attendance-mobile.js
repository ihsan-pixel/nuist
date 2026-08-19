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
    }

    window.MobileFaceRecognition = MobileFaceRecognition;
})();
