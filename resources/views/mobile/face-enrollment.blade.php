@extends('layouts.mobile')

@section('title', 'Pendaftaran Wajah')
@section('subtitle', 'Daftarkan Wajah Anda')

@section('content')
<div class="container py-3" style="max-width: 420px; margin: auto;">
    <style>
        body {
            background: #f8f9fb;
            font-family: 'Poppins', sans-serif;
        }

        .enrollment-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 16px;
        }

        .camera-container {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #000;
            margin: 16px 0;
        }

        .camera-preview {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .camera-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0,0,0,0.7);
            color: white;
            text-align: center;
            padding: 20px;
        }

        .instruction-text {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .sub-instruction {
            font-size: 12px;
            opacity: 0.8;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            width: 100%;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 16px 0;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 4px;
        }

        .status-dot.active {
            background: #0e8549;
        }

        .status-dot.inactive {
            background: #dee2e6;
        }

        .progress-bar-custom {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            overflow: hidden;
            margin: 16px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #004b4c 0%, #0e8549 100%);
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>

    <div class="enrollment-card">
        <h6 class="text-center mb-3">Pendaftaran Wajah</h6>
        <p class="text-muted small text-center mb-4">
            Pastikan wajah Anda terlihat jelas dan pencahayaan cukup untuk pendaftaran wajah.
        </p>

        <div class="camera-container">
            <video id="camera-preview" class="camera-preview" autoplay playsinline muted></video>
            <div id="camera-overlay" class="camera-overlay">
                <div>
                    <div class="instruction-text" id="instruction-text">Memuat kamera...</div>
                    <div class="sub-instruction" id="sub-instruction">Pastikan izin kamera telah diberikan</div>
                </div>
            </div>
        </div>

        <div class="progress-bar-custom">
            <div class="progress-fill" id="progress-fill"></div>
        </div>

        <div class="status-indicator">
            <div class="status-dot" id="step-1"></div>
            <div class="status-dot" id="step-2"></div>
            <div class="status-dot" id="step-3"></div>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="consent" />
            <label class="form-check-label small" for="consent">
                Saya menyetujui penggunaan data wajah untuk keperluan presensi sesuai kebijakan privasi.
            </label>
        </div>

        <button id="start-enrollment" class="btn btn-primary-custom" disabled>
            Mulai Pendaftaran
        </button>

        <button id="retry-enrollment" class="btn btn-outline-secondary w-100 mt-2" style="display: none;">
            Coba Lagi
        </button>
    </div>
</div>

<script>
    window.MODEL_PATH = '{{ asset('models') }}';
</script>
<script>
    // Add challenge instruction display and progress updates
    let currentChallengeIndex = 0;

    // Override the showChallengeInstruction method to update UI
    FaceRecognition.prototype.showChallengeInstruction = function(challenge) {
        const instructionElement = document.getElementById('instruction-text');
        const subInstructionElement = document.getElementById('sub-instruction');

        if (instructionElement) {
            instructionElement.innerText = challenge.instruction;
        }
        if (subInstructionElement) {
            subInstructionElement.innerText = 'Lakukan gerakan yang diminta';
        }

        // Update progress dots
        updateProgress(currentChallengeIndex + 1);
    };

    // Override waitForChallengeCompletion to show progress
    FaceRecognition.prototype.waitForChallengeCompletion = function(videoElement, challenge, timeout = 10000) {
        return new Promise((resolve, reject) => {
            const startTime = Date.now();

            const checkChallenge = async () => {
                try {
                    const result = await this.performLivenessCheck(videoElement, challenge);

                    if (result.completed) {
                        // Add to completed challenges
                        this.completedChallenges.push(challenge);
                        currentChallengeIndex++;
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
</script>

<script>
let faceRecognition = null;
let enrollmentStep = 0;
const totalSteps = 3;

// current user id for enrollment payload
window.CURRENT_USER_ID = {{ Auth::user()->id ?? 'null' }};

document.addEventListener('DOMContentLoaded', async function() {
    try {
        faceRecognition = new FaceRecognition();

        // Load models
        updateInstruction('Memuat model pengenalan wajah...', 'Mohon tunggu sebentar');
        const modelsLoaded = await faceRecognition.loadModels();

        if (!modelsLoaded) {
            throw new Error('Gagal memuat model pengenalan wajah. Pastikan file model tersedia di /models/');
        }

        // Initialize camera
        updateInstruction('Mengakses kamera...', 'Pastikan Anda memberikan izin akses kamera');
        const videoElement = document.getElementById('camera-preview');
        await faceRecognition.initializeCamera(videoElement);

        // Hide overlay and show enrollment button
        document.getElementById('camera-overlay').style.display = 'none';
        document.getElementById('start-enrollment').disabled = false;
        updateInstruction('Siap untuk pendaftaran', 'Klik tombol di bawah untuk memulai');

    } catch (error) {
        console.error('Initialization error:', error);
        showError(error.message);
    }
});

    document.getElementById('start-enrollment').addEventListener('click', startEnrollment);
document.getElementById('retry-enrollment').addEventListener('click', retryEnrollment);

async function startEnrollment() {
    try {
        document.getElementById('start-enrollment').disabled = true;
        document.getElementById('start-enrollment').textContent = 'Mendaftarkan...';
        currentChallengeIndex = 0; // Reset challenge index

        const videoElement = document.getElementById('camera-preview');
        // Ensure consent checked
        const consentEl = document.getElementById('consent');
        if (!consentEl || !consentEl.checked) {
            throw new Error('Anda harus menyetujui penggunaan data wajah untuk melanjutkan pendaftaran.');
        }

        const enrollmentResult = await faceRecognition.performFullEnrollment(videoElement);

        // Build payload expected by FaceController@enroll
        const payload = {
            user_id: window.CURRENT_USER_ID,
            face_data: enrollmentResult.faceDescriptor,
            liveness_score: enrollmentResult.livenessScore,
            liveness_challenges: enrollmentResult.challenges.map(c => c.type || 'unknown')
        };

        // Send enrollment data to server
        const response = await fetch('/api/face/enroll', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (result.success) {
            showSuccess('Pendaftaran wajah berhasil!');
            setTimeout(() => {
                window.location.href = '{{ route("mobile.presensi") }}';
            }, 2000);
        } else {
            throw new Error(result.message || 'Pendaftaran gagal');
        }

    } catch (error) {
        console.error('Enrollment error:', error);
        showError(error.message);
        document.getElementById('retry-enrollment').style.display = 'block';
    } finally {
        document.getElementById('start-enrollment').disabled = false;
        document.getElementById('start-enrollment').textContent = 'Mulai Pendaftaran';
    }
}

function retryEnrollment() {
    document.getElementById('retry-enrollment').style.display = 'none';
    document.getElementById('start-enrollment').disabled = false;
    currentChallengeIndex = 0; // Reset challenge index
    updateInstruction('Siap untuk pendaftaran', 'Klik tombol di bawah untuk memulai');
}

function updateInstruction(mainText, subText = '') {
    document.getElementById('instruction-text').textContent = mainText;
    document.getElementById('sub-instruction').textContent = subText;
}

function updateProgress(step) {
    const progress = (step / totalSteps) * 100;
    document.getElementById('progress-fill').style.width = progress + '%';

    // Update status dots
    for (let i = 1; i <= totalSteps; i++) {
        const dot = document.getElementById('step-' + i);
        if (i <= step) {
            dot.classList.add('active');
            dot.classList.remove('inactive');
        } else {
            dot.classList.add('inactive');
            dot.classList.remove('active');
        }
    }
}

function showError(message) {
    updateInstruction('Error', message);
    // You can implement a more sophisticated error display here
    alert('Error: ' + message);
}

function showSuccess(message) {
    updateInstruction('Berhasil!', message);
    // You can implement a more sophisticated success display here
    alert(message);
}
</script>
@endsection
