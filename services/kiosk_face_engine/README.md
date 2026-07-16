# Kiosk Face Engine

Service ini adalah engine wajah terpisah untuk halaman `school-kiosk`.

Tujuan:
- Python menangani face detection, face recognition, dan heuristic liveness.
- Laravel tetap menjadi backend utama untuk validasi bisnis presensi.
- UI kiosk cukup mengirim burst frame otomatis, lalu menerima hasil verifikasi final.

## Stack

- FastAPI
- OpenCV
- InsightFace
- ONNX Runtime

## Endpoint

- `GET /health`
- `POST /api/v1/enroll`
- `POST /api/v1/identify`

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
          "type": "face_embedding",
          "dimension": 512,
          "values": [0.1, 0.2]
        }
      ]
    }
  ]
}
```

## Menjalankan service

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
KIOSK_FACE_CTX_ID=0
KIOSK_FACE_DET_WIDTH=640
KIOSK_FACE_DET_HEIGHT=640
KIOSK_FACE_MIN_SIMILARITY=0.55
KIOSK_FACE_MIN_LIVENESS=0.68
```

## Catatan penting

- Liveness saat ini berbasis heuristic burst-frame, blur, texture, contrast, motion, dan replay-risk score.
- Untuk anti-spoof yang lebih kuat, langkah berikutnya sebaiknya menambah model dedicated anti-spoof/liveness.
- Laravel sekarang sudah mendukung driver `browser` dan `python` melalui `config/kiosk_face.php`.
