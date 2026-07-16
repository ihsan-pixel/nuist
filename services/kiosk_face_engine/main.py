from __future__ import annotations

import base64
import binascii
import math
import os
import time
from dataclasses import dataclass
from functools import lru_cache
from typing import Any

import cv2
import numpy as np
from fastapi import Depends, FastAPI, Header, HTTPException
from pydantic import BaseModel, Field

try:
    from insightface.app import FaceAnalysis
except Exception:  # pragma: no cover - dependency may be absent in local parse-only checks
    FaceAnalysis = None


APP_VERSION = "0.1.0"


def env_bool(name: str, default: bool) -> bool:
    return os.getenv(name, "1" if default else "0").strip().lower() in {"1", "true", "yes", "on"}


def clamp(value: float, minimum: float = 0.0, maximum: float = 1.0) -> float:
    return max(minimum, min(maximum, value))


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
class FaceFrameAnalysis:
    embedding: np.ndarray
    bbox: tuple[float, float, float, float]
    det_score: float
    blur_score: float
    brightness: float
    contrast: float
    frame_data: str


class Settings:
    api_key = os.getenv("KIOSK_FACE_SERVICE_KEY", "").strip()
    require_api_key = env_bool("KIOSK_FACE_REQUIRE_KEY", False)
    provider = os.getenv("KIOSK_FACE_PROVIDER", "insightface")
    ctx_id = int(os.getenv("KIOSK_FACE_CTX_ID", "0"))
    det_width = int(os.getenv("KIOSK_FACE_DET_WIDTH", "640"))
    det_height = int(os.getenv("KIOSK_FACE_DET_HEIGHT", "640"))
    min_similarity = float(os.getenv("KIOSK_FACE_MIN_SIMILARITY", "0.55"))
    min_liveness = float(os.getenv("KIOSK_FACE_MIN_LIVENESS", "0.68"))


settings = Settings()
app = FastAPI(title="NUIST Kiosk Face Engine", version=APP_VERSION)


def require_api_key(x_kiosk_service_key: str | None = Header(default=None)) -> None:
    if not settings.require_api_key:
        return

    if settings.api_key == "" or x_kiosk_service_key != settings.api_key:
        raise HTTPException(status_code=401, detail="Unauthorized kiosk face service key.")


class EngineUnavailable(RuntimeError):
    pass


@lru_cache(maxsize=1)
def face_engine() -> FaceAnalysis:
    if FaceAnalysis is None:
        raise EngineUnavailable(
            "InsightFace dependency is not installed. Install requirements.txt in the Python service environment."
        )

    app_instance = FaceAnalysis(name="buffalo_l", providers=["CPUExecutionProvider"])
    app_instance.prepare(ctx_id=settings.ctx_id, det_size=(settings.det_width, settings.det_height))
    return app_instance


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
    vector = vector.astype(np.float32)
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


def analyze_frames(frame_payloads: list[str]) -> list[FaceFrameAnalysis]:
    try:
        engine = face_engine()
    except EngineUnavailable as exc:
        raise HTTPException(status_code=503, detail=str(exc)) from exc

    analyses: list[FaceFrameAnalysis] = []

    for frame_data in frame_payloads:
        image = decode_data_url(frame_data)
        faces = engine.get(image)
        if len(faces) != 1:
            continue

        face = faces[0]
        embedding = getattr(face, "normed_embedding", None)
        if embedding is None:
            raw_embedding = getattr(face, "embedding", None)
            if raw_embedding is None:
                continue
            embedding = normalize_embedding(np.asarray(raw_embedding, dtype=np.float32))
        else:
            embedding = normalize_embedding(np.asarray(embedding, dtype=np.float32))

        bbox_values = tuple(float(value) for value in np.asarray(face.bbox).tolist())
        crop = crop_with_padding(image, bbox_values)
        blur_score, brightness, contrast = sample_crop_metrics(crop)

        analyses.append(
            FaceFrameAnalysis(
                embedding=embedding,
                bbox=bbox_values,
                det_score=float(getattr(face, "det_score", 0.0) or 0.0),
                blur_score=blur_score,
                brightness=brightness,
                contrast=contrast,
                frame_data=frame_data,
            )
        )

    return analyses


def choose_best_analysis(analyses: list[FaceFrameAnalysis]) -> FaceFrameAnalysis:
    if not analyses:
        raise HTTPException(status_code=422, detail="Tidak ditemukan tepat satu wajah yang valid pada burst frame.")

    return max(
        analyses,
        key=lambda item: (
            item.det_score * 0.45
            + clamp(item.blur_score / 220.0) * 0.35
            + clamp(item.contrast / 42.0) * 0.12
            + clamp(item.brightness / 160.0) * 0.08
        ),
    )


def compute_liveness(analyses: list[FaceFrameAnalysis]) -> tuple[float, list[dict[str, Any]], dict[str, Any]]:
    if not analyses:
        return 0.0, [], {"valid_frames": 0}

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

    if liveness_score < settings.min_liveness:
        raise HTTPException(
            status_code=422,
            detail={
                "message": "Registrasi wajah ditolak karena liveness belum meyakinkan.",
                "notes": "liveness_below_threshold",
                "liveness_score": liveness_score,
                "liveness_challenges": challenges,
                "metadata": metadata,
            },
        )

    return {
        "success": True,
        "message": "Data wajah berhasil diproses oleh engine Python.",
        "notes": "face_enrolled_python",
        "face_embedding": best.embedding.astype(float).tolist(),
        "liveness_score": liveness_score,
        "liveness_challenges": challenges,
        "captured_image": best.frame_data,
        "quality_score": round(clamp(best.blur_score / 220.0), 4),
        "metadata": metadata,
    }


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
                "liveness_score": liveness_score,
                "liveness_challenges": challenges,
                "metadata": metadata,
            },
        )

    best_candidate: CandidatePayload | None = None
    best_similarity = -1.0

    for candidate in request.candidates:
        for vector in candidate.vectors:
            similarity = candidate_similarity(best.embedding, vector.values)
            if similarity > best_similarity:
                best_similarity = similarity
                best_candidate = candidate

    if best_candidate is None:
        raise HTTPException(
            status_code=422,
            detail={
                "message": "Belum ada kandidat wajah yang kompatibel untuk dibandingkan.",
                "notes": "no_compatible_candidates",
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
                "similarity": round(best_similarity, 4),
                "face_distance": round(1.0 - best_similarity, 4),
                "liveness_score": liveness_score,
                "liveness_challenges": challenges,
                "metadata": metadata,
            },
        )

    return {
        "success": True,
        "message": "Identitas wajah berhasil dikenali oleh engine Python.",
        "notes": "face_identified_python",
        "user_id": best_candidate.user_id,
        "face_id_used": best_candidate.face_id,
        "similarity": round(best_similarity, 4),
        "face_distance": round(1.0 - best_similarity, 4),
        "liveness_score": liveness_score,
        "liveness_challenges": challenges,
        "captured_image": best.frame_data,
        "face_embedding": best.embedding.astype(float).tolist(),
        "metadata": metadata,
    }


@app.exception_handler(HTTPException)
async def http_exception_handler(_, exc: HTTPException) -> Any:
    detail = exc.detail
    if isinstance(detail, dict):
        payload = {"success": False, **detail}
    else:
        payload = {
            "success": False,
            "message": str(detail),
            "notes": "engine_request_failed",
        }

    return fastapi_json_response(payload, exc.status_code)


@app.exception_handler(Exception)
async def generic_exception_handler(_, exc: Exception) -> Any:
    return fastapi_json_response(
        {
            "success": False,
            "message": str(exc) or "Unhandled kiosk face engine error.",
            "notes": "engine_internal_error",
        },
        500,
    )


def fastapi_json_response(payload: dict[str, Any], status_code: int) -> Any:
    from fastapi.responses import JSONResponse

    return JSONResponse(payload, status_code=status_code)
