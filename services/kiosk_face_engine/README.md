# Kiosk Face Engine

Service ini adalah engine wajah terpisah untuk halaman `school-kiosk`.

Tujuan:
- Python menangani face detection, face recognition, dan heuristic liveness.
- Laravel tetap menjadi backend utama untuk validasi bisnis presensi.
- UI kiosk cukup mengirim burst frame otomatis, lalu menerima hasil verifikasi final.

## Stack

- FastAPI
- OpenCV headless
- ONNX Runtime
- Model ONNX YuNet + InsightFace ArcFace (`buffalo_l/w600k_r50.onnx`)

## Kenapa versi ini dipakai

Recognizer memakai ONNX Runtime langsung. Service tidak memiliki fallback ke SFace: jika recognizer ArcFace tidak tersedia, gagal dimuat, atau outputnya bukan 512D, endpoint readiness dan operasi wajah gagal.

## Endpoint

- `GET /health`
- `POST /api/v1/enroll`
- `POST /api/v1/analyze`
- `POST /api/v1/identify`
- `POST /api/v1/cache/refresh`
- `POST /api/v1/cache/invalidate`

## Request ringkas

`/api/v1/enroll`

```json
{
  "teacher_id": 10,
  "teacher_name": "Guru Contoh",
  "frames": ["data:image/jpeg;base64,..."],
  "device_info": "Mozilla/5.0"
}
```

`/api/v1/identify`

```json
{
  "frames": ["data:image/jpeg;base64,..."],
  "device_info": "Mozilla/5.0",
  "candidates": [
    {
      "user_id": 10,
      "name": "Guru Contoh",
      "face_id": "uuid",
      "vectors": [
        {
          "type": "face_embedding:insightface_arcface",
          "dimension": 512,
          "values": [0.1, 0.2]
        }
      ]
    }
  ]
}
```

## File model yang wajib ada

Letakkan file berikut di folder `services/kiosk_face_engine/models/`:

- `face_detection_yunet_2023mar.onnx`
- `w600k_r50.onnx` dari paket resmi InsightFace `buffalo_l`

Jika Anda menaruh model di lokasi lain, atur path lewat environment variable.

## Menjalankan service

Pada macOS ARM dengan Python 3.14, gunakan ONNX Runtime `1.24.1` atau lebih baru dan Pydantic `2.12.5` atau lebih baru. Versi lama tidak menyediakan wheel yang kompatibel dengan kombinasi platform tersebut.

```bash
cd services/kiosk_face_engine
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt
uvicorn main:app --host 0.0.0.0 --port 8800
```

## Environment variable

```bash
KIOSK_FACE_SERVICE_KEY=
KIOSK_FACE_REQUIRE_KEY=false
KIOSK_FACE_PROVIDER=insightface_arcface
KIOSK_FACE_MODEL_DIR=models
KIOSK_FACE_DETECTOR_MODEL=models/face_detection_yunet_2023mar.onnx
KIOSK_FACE_RECOGNIZER_MODEL=models/w600k_r50.onnx
KIOSK_FACE_ALLOW_LEGACY_EMBEDDINGS=false
KIOSK_FACE_DET_WIDTH=640
KIOSK_FACE_DET_HEIGHT=640
KIOSK_FACE_DET_SCORE_THRESHOLD=0.88
KIOSK_FACE_DET_NMS_THRESHOLD=0.30
KIOSK_FACE_DET_TOP_K=500
KIOSK_FACE_MIN_FACE_SIZE=96
KIOSK_FACE_MIN_SIMILARITY=0.55
KIOSK_FACE_MIN_LIVENESS=0.68
```

## Catatan migrasi

- Registrasi wajah Python baru sekarang diberi `face_provider`.
- Engine aktif hanya akan membandingkan embedding dari provider yang sama.
- Artinya, setelah pindah dari engine lama atau dari `browser` ke `insightface_arcface`, guru perlu registrasi ulang wajah agar pencocokan tetap akurat.
- Jika memang ingin mengizinkan embedding lama yang tidak memiliki provider, set `KIOSK_FACE_ALLOW_LEGACY_EMBEDDINGS=true`, tetapi ini tidak saya sarankan untuk produksi.

## Catatan penting

- Liveness saat ini masih berbasis heuristic burst-frame, blur, texture, contrast, motion, dan replay-risk score.
- Untuk anti-spoof yang lebih kuat, langkah berikutnya sebaiknya menambah model dedicated anti-spoof/liveness.
- Laravel menggunakan driver `python` sebagai default melalui `config/kiosk_face.php`.
- Metadata embedding v2 adalah `provider=insightface_arcface`, `model=arcface`, `model_version=buffalo_l_w600k_r50`, `dimension=512`.
- Flutter hanya mengirim frame ke Laravel; embedding tidak dikembalikan ke Flutter.
- Cache aktif berada di Python sebagai matrix `N x 512`; similarity dihitung dengan operasi NumPy vectorized.
