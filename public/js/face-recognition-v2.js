(function () {
    if (!window.FaceRecognition) {
        throw new Error('face-recognition.js must load before face-recognition-v2.js');
    }

    const captureBurstFrames = window.FaceRecognition.prototype.captureBurstFrames;

    // Python receives unmirrored camera frames. The mirror remains presentation-only.
    window.FaceRecognition.prototype.captureBurstFrames = function (videoElement, options = {}) {
        return captureBurstFrames.call(this, videoElement, {
            ...options,
            mirror: options.mirror === true,
        });
    };
})();
