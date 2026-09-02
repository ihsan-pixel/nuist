from __future__ import annotations

import base64
import binascii
import logging
import math
import os
import time
from dataclasses import dataclass
from functools import lru_cache
from pathlib import Path
from typing import Any

import cv2
import numpy as np
from fastapi import Depends, FastAPI, Header, HTTPException, Request
from pydantic import BaseModel, Field


APP_VERSION = "0.2.0"
logger = logging.getLogger("kiosk_face_engine")
logging.basicConfig(
    level=os.getenv("KIOSK_FACE_LOG_LEVEL", "INFO").upper(),
    format="%(asctime)s %(levelname)s %(name)s: %(message)s",
)


def env_bool(name: str, default: bool) -> bool:
    return os.getenv(name, "1" if default else "0").strip().lower() in {"1", "true", "yes", "on"}


def clamp(value: float, minimum: float = 0.0, maximum: float = 1.0) -> float:
    return max(minimum, min(maximum, value))


def normalize_provider_name(value: str | None) -> str:
    normalized = (value or "").strip().lower().replace("-", "_").replace(" ", "_")
    return normalized or "unknown"


def resolve_path(value: str, *, base_dir: Path) -> Path:
    path = Path(value).expanduser()

    if path.is_absolute():
        return path

    return (base_dir / path).resolve()


class CandidateVector(BaseModel):
    type: str = Field(default="face_embedding")
    dimension: int
    values: list[float]


class CandidatePayload(BaseModel):
    user_id: int
    name: str | None = None
    face_id: str | None = None
    vectors: list[CandidateVector] = Field(default_factory=list)


class ScanRequest(BaseModel):
    frames: list[str] = Field(min_length=1, max_length=8)
    device_info: str | None = None
    context: dict[str, Any] = Field(default_factory=dict)


class AnalyzeRequest(ScanRequest):
    expected_pose: str | None = None


class EnrollRequest(ScanRequest):
    teacher_id: int
    teacher_name: str | None = None


class IdentifyRequest(ScanRequest):
    candidates: list[CandidatePayload] = Field(default_factory=list)


class HealthResponse(BaseModel):
    success: bool
    message: str
    version: str
    model_ready: bool
    provider: str


@dataclass
class ModelBundle:
    detector: Any
    recognizer: Any
    detector_model_path: str
    recognizer_model_path: str


@dataclass
class FaceFrameAnalysis:
    embedding: np.ndarray
    bbox: tuple[float, float, float, float]
    det_score: float
    blur_score: float
    brightness: float
    contrast: float
    frame_data: str
    pose: str | None = None


class Settings:
    base_dir = Path(__file__).resolve().parent
    model_dir = resolve_path(
        os.getenv("KIOSK_FACE_MODEL_DIR", "models"),
        base_dir=base_dir,
    )
    api_key = os.getenv("KIOSK_FACE_SERVICE_KEY", "").strip()
    require_api_key = env_bool("KIOSK_FACE_REQUIRE_KEY", False)
    provider = normalize_provider_name(os.getenv("KIOSK_FACE_PROVIDER", "opencv_sface"))
    detector_model = resolve_path(
        os.getenv("KIOSK_FACE_DETECTOR_MODEL", str(model_dir / "face_detection_yunet_2023mar.onnx")),
        base_dir=base_dir,
    )
    recognizer_model = resolve_path(
        os.getenv("KIOSK_FACE_RECOGNIZER_MODEL", str(model_dir / "face_recognition_sface_2021dec.onnx")),
        base_dir=base_dir,
    )
    allow_legacy_embeddings = env_bool("KIOSK_FACE_ALLOW_LEGACY_EMBEDDINGS", False)
    det_width = int(os.getenv("KIOSK_FACE_DET_WIDTH", "640"))
    det_height = int(os.getenv("KIOSK_FACE_DET_HEIGHT", "640"))
    det_score_threshold = float(os.getenv("KIOSK_FACE_DET_SCORE_THRESHOLD", "0.88"))
    det_nms_threshold = float(os.getenv("KIOSK_FACE_DET_NMS_THRESHOLD", "0.30"))
    det_top_k = int(os.getenv("KIOSK_FACE_DET_TOP_K", "500"))
    min_similarity = float(os.getenv("KIOSK_FACE_MIN_SIMILARITY", "0.55"))
    min_liveness = float(os.getenv("KIOSK_FACE_MIN_LIVENESS", "0.68"))
    min_face_size = int(os.getenv("KIOSK_FACE_MIN_FACE_SIZE", "96"))
    min_quality = float(os.getenv("FACE_MIN_QUALITY", "0.45"))
    model = os.getenv("FACE_MODEL", "sface")
    model_version = os.getenv("FACE_MODEL_VERSION", "v1")
    min_frames = int(os.getenv("FACE_MIN_STABLE_FRAMES", "3"))


settings = Settings()
app = FastAPI(title="NUIST Kiosk Face Engine", version=APP_VERSION)


@app.middleware("http")
async def log_engine_request(request: Request, call_next):
    started_at = time.perf_counter()
    try:
        response = await call_next(request)
        elapsed_ms = (time.perf_counter() - started_at) * 1000
        logger.info(
            "REQUEST method=%s path=%s status=%s elapsed_ms=%.0f",
            request.method,
            request.url.path,
            response.status_code,
            elapsed_ms,
        )
        return response
    except Exception:
        logger.exception("REQUEST method=%s path=%s unhandled_error", request.method, request.url.path)
        raise


def require_api_key(x_kiosk_service_key: str | None = Header(default=None)) -> None:
    if not settings.require_api_key:
        return

    if settings.api_key == "" or x_kiosk_service_key != settings.api_key:
        raise HTTPException(status_code=401, detail="Unauthorized kiosk face service key.")


class EngineUnavailable(RuntimeError):
    pass


@lru_cache(maxsize=1)
def face_engine() -> ModelBundle:
    if not hasattr(cv2, "FaceDetectorYN_create"):
        raise EngineUnavailable("OpenCV build ini tidak mendukung FaceDetectorYN_create.")

    if not hasattr(cv2, "FaceRecognizerSF_create"):
        raise EngineUnavailable("OpenCV build ini tidak mendukung FaceRecognizerSF_create.")

    if not settings.detector_model.is_file():
        raise EngineUnavailable(
            f"Model detector tidak ditemukan: {settings.detector_model}. Unggah file ONNX YuNet terlebih dahulu."
        )

    if not settings.recognizer_model.is_file():
        raise EngineUnavailable(
            f"Model recognizer tidak ditemukan: {settings.recognizer_model}. Unggah file ONNX SFace terlebih dahulu."
        )

    detector = cv2.FaceDetectorYN_create(
        str(settings.detector_model),
        "",
        (settings.det_width, settings.det_height),
        settings.det_score_threshold,
        settings.det_nms_threshold,
        settings.det_top_k,
    )
    recognizer = cv2.FaceRecognizerSF_create(str(settings.recognizer_model), "")

    return ModelBundle(
        detector=detector,
        recognizer=recognizer,
        detector_model_path=str(settings.detector_model),
        recognizer_model_path=str(settings.recognizer_model),
    )


def decode_data_url(data_url: str) -> np.ndarray:
    if not data_url.startswith("data:image/"):
        raise HTTPException(status_code=422, detail="Frame payload must be a data:image data URL.")

    try:
        encoded = data_url.split(",", 1)[1]
        raw = base64.b64decode(encoded, validate=True)
    except (IndexError, ValueError, binascii.Error) as exc:
        raise HTTPException(status_code=422, detail="Frame payload is not valid base64 image data.") from exc

    image = cv2.imdecode(np.frombuffer(raw, dtype=np.uint8), cv2.IMREAD_COLOR)
    if image is None:
        raise HTTPException(status_code=422, detail="Frame payload cannot be decoded into an image.")

    return image


def crop_with_padding(image: np.ndarray, bbox: tuple[float, float, float, float], padding: float = 0.18) -> np.ndarray:
    x1, y1, x2, y2 = bbox
    width = max(1.0, x2 - x1)
    height = max(1.0, y2 - y1)

    pad_x = width * padding
    pad_y = height * padding

    start_x = max(0, int(math.floor(x1 - pad_x)))
    start_y = max(0, int(math.floor(y1 - pad_y)))
    end_x = min(image.shape[1], int(math.ceil(x2 + pad_x)))
    end_y = min(image.shape[0], int(math.ceil(y2 + pad_y)))

    return image[start_y:end_y, start_x:end_x]


def normalize_embedding(vector: np.ndarray) -> np.ndarray:
    vector = np.asarray(vector, dtype=np.float32).flatten()
    norm = float(np.linalg.norm(vector))
    if norm <= 0:
        return vector

    return vector / norm


def sample_crop_metrics(crop: np.ndarray) -> tuple[float, float, float]:
    if crop.size == 0:
        return 0.0, 0.0, 0.0

    gray = cv2.cvtColor(crop, cv2.COLOR_BGR2GRAY)
    blur_score = float(cv2.Laplacian(gray, cv2.CV_64F).var())
    brightness = float(np.mean(gray))
    contrast = float(np.std(gray))

    return blur_score, brightness, contrast


def improve_low_light(image: np.ndarray) -> np.ndarray:
    """Apply a conservative illumination lift before detection and embedding."""
    if image.size == 0:
        return image

    brightness = float(np.mean(cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)))
    if brightness >= 105.0:
        return image

    # CLAHE lifts shadow detail while limiting global overexposure. This same
    # preprocessing is used for enrollment and recognition.
    lab = cv2.cvtColor(image, cv2.COLOR_BGR2LAB)
    lightness, channel_a, channel_b = cv2.split(lab)
    clahe = cv2.createCLAHE(clipLimit=1.6, tileGridSize=(8, 8))
    lightness = clahe.apply(lightness)
    enhanced = cv2.cvtColor(
        cv2.merge((lightness, channel_a, channel_b)),
        cv2.COLOR_LAB2BGR,
    )
    return cv2.convertScaleAbs(enhanced, alpha=1.04, beta=3)


def estimate_pose(face: np.ndarray) -> str | None:
    """Estimate coarse pose from YuNet's five facial landmarks."""
    if face.shape[0] < 14:
        return None

    left_eye = np.asarray(face[4:6], dtype=np.float32)
    right_eye = np.asarray(face[6:8], dtype=np.float32)
    nose = np.asarray(face[8:10], dtype=np.float32)
    mouth_left = np.asarray(face[10:12], dtype=np.float32)
    mouth_right = np.asarray(face[12:14], dtype=np.float32)
    eye_span = max(float(np.linalg.norm(right_eye - left_eye)), 1.0)
    eye_center = (left_eye + right_eye) / 2.0
    mouth_center = (mouth_left + mouth_right) / 2.0
    yaw = float((nose[0] - eye_center[0]) / eye_span)
    pitch = float((nose[1] - eye_center[1]) / max(float(mouth_center[1] - eye_center[1]), 1.0))

    # YuNet receives the unmirrored camera frame. A user's left therefore
    # appears on the image's right side, opposite to the mirrored preview.
    if yaw <= -0.10:
        return "right"
    if yaw >= 0.10:
        return "left"
    if pitch <= 0.34:
        return "up"
    if pitch >= 0.58:
        return "down"
    return "front"


def detect_face(image: np.ndarray, engine: ModelBundle) -> tuple[np.ndarray, tuple[float, float, float, float], float, str | None] | None:
    try:
        engine.detector.setInputSize((image.shape[1], image.shape[0]))
        _, faces = engine.detector.detect(image)
    except cv2.error:
        return None

    if faces is None or len(faces) != 1:
        return None

    face = np.asarray(faces[0], dtype=np.float32)
    x, y, width, height = [float(value) for value in face[:4]]
    if min(width, height) < settings.min_face_size:
        return None

    bbox = (x, y, x + width, y + height)

    try:
        aligned_face = engine.recognizer.alignCrop(image, face)
        embedding = normalize_embedding(engine.recognizer.feature(aligned_face))
    except cv2.error:
        return None

    det_score = float(face[14]) if face.shape[0] > 14 else 0.0

    return embedding, bbox, det_score, estimate_pose(face)


def analyze_frames(frame_payloads: list[str]) -> list[FaceFrameAnalysis]:
    try:
        engine = face_engine()
    except EngineUnavailable as exc:
        raise HTTPException(status_code=503, detail=str(exc)) from exc

    analyses: list[FaceFrameAnalysis] = []

    for frame_data in frame_payloads:
        image = improve_low_light(decode_data_url(frame_data))
        # Mobile camera frames may arrive in the sensor's landscape
        # orientation even when the preview is portrait. Try the native frame
        # first, then the three rotated variants before rejecting the sample.
        oriented_image = None
        detection = None
        for candidate_image in (
            image,
            cv2.rotate(image, cv2.ROTATE_90_CLOCKWISE),
            cv2.rotate(image, cv2.ROTATE_180),
            cv2.rotate(image, cv2.ROTATE_90_COUNTERCLOCKWISE),
        ):
            detection = detect_face(candidate_image, engine)
            if detection is not None:
                oriented_image = candidate_image
                break

        if detection is None or oriented_image is None:
            continue

        embedding, bbox_values, det_score, pose = detection
        crop = crop_with_padding(oriented_image, bbox_values)
        blur_score, brightness, contrast = sample_crop_metrics(crop)

        analyses.append(
            FaceFrameAnalysis(
                embedding=embedding,
                bbox=bbox_values,
                det_score=det_score,
                blur_score=blur_score,
                brightness=brightness,
                contrast=contrast,
                frame_data=frame_data,
                pose=pose,
            )
        )

    return analyses


def choose_best_analysis(analyses: list[FaceFrameAnalysis]) -> FaceFrameAnalysis:
    if not analyses:
        raise HTTPException(status_code=422, detail="Tidak ditemukan tepat satu wajah yang valid pada burst frame.")

    return max(
        analyses,
        key=lambda item: (
            item.det_score * 0.48
            + clamp(item.blur_score / 220.0) * 0.30
            + clamp(item.contrast / 42.0) * 0.12
            + clamp(item.brightness / 160.0) * 0.10
        ),
    )


def compute_liveness(analyses: list[FaceFrameAnalysis]) -> tuple[float, list[dict[str, Any]], dict[str, Any]]:
    if not analyses:
        return 0.0, [], {"valid_frames": 0, "provider": settings.provider}

    centers_x = []
    centers_y = []
    areas = []
    blurs = []
    brightnesses = []
    contrasts = []
    det_scores = []

    for analysis in analyses:
        x1, y1, x2, y2 = analysis.bbox
        centers_x.append((x1 + x2) / 2.0)
        centers_y.append((y1 + y2) / 2.0)
        areas.append(max(1.0, (x2 - x1) * (y2 - y1)))
        blurs.append(analysis.blur_score)
        brightnesses.append(analysis.brightness)
        contrasts.append(analysis.contrast)
        det_scores.append(analysis.det_score)

    movement = float(np.std(centers_x) + np.std(centers_y))
    area_shift = float(np.std(areas) / max(np.mean(areas), 1.0))
    blur_quality = clamp(float(np.mean(blurs)) / 220.0)
    contrast_quality = clamp(float(np.mean(contrasts)) / 40.0)
    lighting_quality = clamp((float(np.mean(brightnesses)) / 165.0) * 0.8 + contrast_quality * 0.2)
    natural_motion = clamp((movement / 18.0) * 0.7 + area_shift * 3.2)
    replay_risk = clamp(1.0 - ((natural_motion * 0.55) + (blur_quality * 0.3) + (contrast_quality * 0.15)))
    detection_quality = clamp(float(np.mean(det_scores)))

    liveness_score = clamp(
        (blur_quality * 0.28)
        + (contrast_quality * 0.16)
        + (lighting_quality * 0.14)
        + (natural_motion * 0.24)
        + (detection_quality * 0.18)
        - (replay_risk * 0.15)
    )

    now_ts = int(time.time())
    challenges = [
        {
            "type": "face_detected",
            "passed": True,
            "score": round(detection_quality, 4),
            "detail": "single_face_detected",
            "timestamp": now_ts,
        },
        {
            "type": "motion_consistency",
            "passed": natural_motion >= 0.12,
            "score": round(natural_motion, 4),
            "detail": "burst_motion_signal",
            "timestamp": now_ts + 1,
        },
        {
            "type": "texture_integrity",
            "passed": blur_quality >= 0.30 and contrast_quality >= 0.25,
            "score": round((blur_quality * 0.65) + (contrast_quality * 0.35), 4),
            "detail": "laplacian_texture_score",
            "timestamp": now_ts + 2,
        },
        {
            "type": "screen_replay_risk",
            "passed": replay_risk < 0.55,
            "score": round(replay_risk, 4),
            "detail": "heuristic_replay_risk",
            "timestamp": now_ts + 3,
        },
    ]

    metadata = {
        "provider": settings.provider,
        "valid_frames": len(analyses),
        "average_blur": round(float(np.mean(blurs)), 4),
        "average_brightness": round(float(np.mean(brightnesses)), 4),
        "average_contrast": round(float(np.mean(contrasts)), 4),
        "natural_motion": round(natural_motion, 4),
        "replay_risk": round(replay_risk, 4),
    }

    return round(liveness_score, 4), challenges, metadata


def candidate_similarity(query_embedding: np.ndarray, candidate_vector: list[float]) -> float:
    candidate = normalize_embedding(np.asarray(candidate_vector, dtype=np.float32))
    if candidate.shape != query_embedding.shape:
        return -1.0

    return float(np.dot(query_embedding, candidate))


def is_vector_compatible(vector_type: str) -> bool:
    normalized_type = normalize_provider_name(vector_type)
    provider_type = f"face_embedding:{settings.provider}"

    if normalized_type == provider_type:
        return True

    if settings.allow_legacy_embeddings and normalized_type in {"face_embedding", "embedding"}:
        return True

    return False


def provider_compatibility_message() -> str:
    if settings.allow_legacy_embeddings:
        return "Belum ada kandidat wajah yang kompatibel untuk dibandingkan."

    return (
        "Belum ada data wajah guru yang kompatibel dengan engine Python aktif. "
        "Registrasikan ulang wajah guru dengan engine saat ini."
    )


@app.get("/health", response_model=HealthResponse)
def health(_: None = Depends(require_api_key)) -> HealthResponse:
    try:
        face_engine()
        model_ready = True
        message = "Kiosk face engine is ready."
    except Exception as exc:  # pragma: no cover - readiness only
        model_ready = False
        message = str(exc)

    return HealthResponse(
        success=model_ready,
        message=message,
        version=APP_VERSION,
        model_ready=model_ready,
        provider=settings.provider,
    )


@app.post("/api/v1/enroll")
def enroll(request: EnrollRequest, _: None = Depends(require_api_key)) -> dict[str, Any]:
    analyses = analyze_frames(request.frames)
    best = choose_best_analysis(analyses)
    liveness_score, challenges, metadata = compute_liveness(analyses)
    quality_score = round(clamp(
        clamp(best.blur_score / 220.0) * 0.55
        + clamp(best.contrast / 42.0) * 0.25
        + clamp(best.brightness / 160.0) * 0.20
    ), 4)
    expected_pose = request.context.get("expected_pose")
    if best.det_score < settings.det_score_threshold:
        raise HTTPException(status_code=422, detail={"message": "Deteksi wajah terlalu rendah.", "reason": "LOW_DETECTION_SCORE"})
    if quality_score < settings.min_quality:
        raise HTTPException(status_code=422, detail={"message": "Kualitas frame wajah terlalu rendah.", "reason": "LOW_QUALITY"})
    enrollment_phase = request.context.get("enrollment_phase")
    if expected_pose and best.pose and expected_pose != best.pose and not enrollment_phase:
        raise HTTPException(status_code=422, detail={"message": "Pose wajah tidak sesuai instruksi.", "reason": "INVALID_POSE", "pose": best.pose})

    # The kiosk performs the directional challenge before taking this burst.
    # Since the burst is captured after the movement, its server-side motion
    # heuristic is diagnostic only during enrollment. Attendance verification
    # still enforces the configured liveness threshold.
    if liveness_score < settings.min_liveness and not enrollment_phase and not request.context.get("rebuild", False):
        raise HTTPException(
            status_code=422,
            detail={
                "message": "Registrasi wajah ditolak karena liveness belum meyakinkan.",
                "reason": "LOW_LIVENESS",
                "notes": "liveness_below_threshold",
                "provider": settings.provider,
                "liveness_score": liveness_score,
                "liveness_challenges": challenges,
                "metadata": metadata,
            },
        )

    result = {
        "success": True,
        "message": "Data wajah berhasil diproses oleh engine Python.",
        "notes": "face_enrolled_python",
        "provider": settings.provider,
        "model": settings.model,
        "model_version": settings.model_version,
        "face_embedding": best.embedding.astype(float).tolist(),
        "liveness_score": liveness_score,
        "liveness_challenges": challenges,
        "captured_image": best.frame_data,
        "quality_score": quality_score,
        "metadata": metadata,
        "pose": best.pose,
        "detection_score": round(best.det_score, 4),
    }
    logger.info(
        "ENROLL success=true teacher_id=%s teacher=%s pose=%s detection=%.4f quality=%.4f liveness=%.4f frames=%s",
        request.teacher_id,
        request.teacher_name or "-",
        best.pose or "unknown",
        best.det_score,
        quality_score,
        liveness_score,
        len(analyses),
    )
    return result


@app.post("/api/v1/analyze")
def analyze(request: AnalyzeRequest, _: None = Depends(require_api_key)) -> dict[str, Any]:
    analyses = analyze_frames(request.frames[:1])
    if not analyses:
        raise HTTPException(status_code=422, detail={
            "message": "Tidak ditemukan tepat satu wajah yang valid pada frame.",
            "reason": "NO_FACE",
        })

    best = choose_best_analysis(analyses)
    liveness_score, challenges, metadata = compute_liveness(analyses)
    quality_score = round(clamp(
        clamp(best.blur_score / 220.0) * 0.55
        + clamp(best.contrast / 42.0) * 0.25
        + clamp(best.brightness / 160.0) * 0.20
    ), 4)
    expected_pose = request.expected_pose or request.context.get("expected_pose")
    if best.det_score < settings.det_score_threshold:
        reason = "LOW_DETECTION_SCORE"
    elif quality_score < settings.min_quality:
        reason = "LOW_QUALITY"
    elif expected_pose and best.pose and expected_pose != best.pose:
        reason = "INVALID_POSE"
    elif liveness_score < settings.min_liveness:
        reason = "LOW_LIVENESS"
    else:
        reason = None

    if reason:
        raise HTTPException(status_code=422, detail={
            "message": "Frame wajah tidak memenuhi syarat enrollment.",
            "reason": reason,
            "provider": settings.provider,
            "model": settings.model,
            "model_version": settings.model_version,
            "detection_score": round(best.det_score, 4),
            "quality_score": quality_score,
            "liveness_score": liveness_score,
            "face_bbox": list(best.bbox),
            "pose": best.pose,
            "liveness_challenges": challenges,
            "metadata": metadata,
        })

    result = {
        "success": True,
        "provider": settings.provider,
        "model": settings.model,
        "model_version": settings.model_version,
        "embedding": best.embedding.astype(float).tolist(),
        "detection_score": round(best.det_score, 4),
        "quality_score": quality_score,
        "liveness_score": liveness_score,
        "face_bbox": list(best.bbox),
        "pose": best.pose,
        "liveness_challenges": challenges,
        "metadata": metadata,
    }
    logger.info(
        "ANALYZE success=true pose=%s detection=%.4f quality=%.4f liveness=%.4f frames=%s",
        best.pose or "unknown",
        best.det_score,
        quality_score,
        liveness_score,
        len(analyses),
    )
    return result


@app.post("/api/v1/identify")
def identify(request: IdentifyRequest, _: None = Depends(require_api_key)) -> dict[str, Any]:
    analyses = analyze_frames(request.frames)
    best = choose_best_analysis(analyses)
    liveness_score, challenges, metadata = compute_liveness(analyses)

    if liveness_score < settings.min_liveness:
        raise HTTPException(
            status_code=422,
            detail={
                "message": "Presensi ditolak karena liveness belum meyakinkan.",
                "notes": "liveness_below_threshold",
                "provider": settings.provider,
                "liveness_score": liveness_score,
                "liveness_challenges": challenges,
                "metadata": metadata,
            },
        )

    best_candidate: CandidatePayload | None = None
    best_face_id: str | None = None
    best_similarity = -1.0
    compatible_vector_count = 0

    for candidate in request.candidates:
        for vector in candidate.vectors:
            if not is_vector_compatible(vector.type):
                continue

            compatible_vector_count += 1
            similarity = candidate_similarity(best.embedding, vector.values)
            if similarity > best_similarity:
                best_similarity = similarity
                best_candidate = candidate
                best_face_id = candidate.face_id

    if compatible_vector_count == 0:
        raise HTTPException(
            status_code=422,
            detail={
                "message": provider_compatibility_message(),
                "notes": "no_compatible_candidates",
                "provider": settings.provider,
                "liveness_score": liveness_score,
                "liveness_challenges": challenges,
                "metadata": {
                    **metadata,
                    "required_vector_type": f"face_embedding:{settings.provider}",
                },
            },
        )

    if best_candidate is None:
        raise HTTPException(
            status_code=422,
            detail={
                "message": "Belum ada kandidat wajah yang kompatibel untuk dibandingkan.",
                "notes": "no_compatible_candidates",
                "provider": settings.provider,
                "liveness_score": liveness_score,
                "liveness_challenges": challenges,
                "metadata": metadata,
            },
        )

    if len(analyses) >= settings.min_frames:
        frame_winners: list[int] = []
        for analysis in analyses:
            winner_id = None
            winner_similarity = -1.0
            for candidate in request.candidates:
                for vector in candidate.vectors:
                    if not is_vector_compatible(vector.type):
                        continue
                    similarity = candidate_similarity(analysis.embedding, vector.values)
                    if similarity > winner_similarity:
                        winner_similarity = similarity
                        winner_id = candidate.user_id
            if winner_id is not None:
                frame_winners.append(winner_id)

        consensus_required = max(2, math.ceil(len(analyses) * 0.6))
        consensus_count = frame_winners.count(best_candidate.user_id)
        if consensus_count < consensus_required:
            raise HTTPException(
                status_code=422,
                detail={
                    "message": "Hasil pembacaan wajah tidak konsisten. Silakan ulangi scan.",
                    "notes": "identity_unstable",
                    "reason": "IDENTITY_UNSTABLE",
                    "provider": settings.provider,
                    "model": settings.model,
                    "model_version": settings.model_version,
                    "similarity": round(best_similarity, 4),
                    "consensus_count": consensus_count,
                    "consensus_required": consensus_required,
                    "liveness_score": liveness_score,
                    "liveness_challenges": challenges,
                    "metadata": metadata,
                },
            )

    if best_similarity < settings.min_similarity:
        raise HTTPException(
            status_code=422,
            detail={
                "message": "Wajah tidak dikenali oleh engine Python.",
                "notes": "face_similarity_below_threshold",
                "provider": settings.provider,
                "similarity": round(best_similarity, 4),
                "face_distance": round(1.0 - best_similarity, 4),
                "liveness_score": liveness_score,
                "liveness_challenges": challenges,
                "metadata": metadata,
            },
        )

    result = {
        "success": True,
        "message": "Identitas wajah berhasil dikenali oleh engine Python.",
        "notes": "face_identified_python",
        "provider": settings.provider,
        "model": settings.model,
        "model_version": settings.model_version,
        "user_id": best_candidate.user_id,
        "face_id_used": best_face_id,
        "similarity": round(best_similarity, 4),
        "face_distance": round(1.0 - best_similarity, 4),
        "liveness_score": liveness_score,
        "liveness_challenges": challenges,
        "captured_image": best.frame_data,
        "face_embedding": best.embedding.astype(float).tolist(),
        "metadata": metadata,
    }
    logger.info(
        "IDENTIFY result=MATCH user_id=%s face_id=%s similarity=%.4f threshold=%.4f liveness=%.4f frames=%s",
        best_candidate.user_id,
        best_face_id or "-",
        best_similarity,
        settings.min_similarity,
        liveness_score,
        len(analyses),
    )
    return result


@app.exception_handler(HTTPException)
async def http_exception_handler(request: Request, exc: HTTPException) -> Any:
    detail = exc.detail
    if isinstance(detail, dict):
        payload = {"success": False, **detail}
    else:
        payload = {
            "success": False,
            "message": str(detail),
            "notes": "engine_request_failed",
        }

    logger.warning(
        "RESULT result=REJECTED path=%s status=%s reason=%s message=%s user_id=%s pose=%s similarity=%s liveness=%s",
        request.url.path,
        exc.status_code,
        payload.get("reason") or payload.get("notes") or "HTTP_ERROR",
        payload.get("message") or "-",
        payload.get("user_id") or payload.get("teacher_id") or "-",
        payload.get("pose") or "-",
        payload.get("similarity", "-"),
        payload.get("liveness_score", "-"),
    )
    return fastapi_json_response(payload, exc.status_code)


@app.exception_handler(Exception)
async def generic_exception_handler(request: Request, exc: Exception) -> Any:
    logger.exception("RESULT result=ENGINE_ERROR path=%s message=%s", request.url.path, exc)
    return fastapi_json_response(
        {
            "success": False,
            "message": str(exc) or "Unhandled kiosk face engine error.",
            "notes": "engine_internal_error",
            "provider": settings.provider,
        },
        500,
    )


def fastapi_json_response(payload: dict[str, Any], status_code: int) -> Any:
    from fastapi.responses import JSONResponse

    return JSONResponse(payload, status_code=status_code)
