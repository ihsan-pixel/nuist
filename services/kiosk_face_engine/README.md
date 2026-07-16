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
- Model ONNX YuNet + SFace

## Kenapa versi ini dipakai

Versi ini sengaja tidak lagi memakai `insightface`, karena shared hosting umumnya tidak menyediakan `gcc/g++` dan header build yang dibutuhkan saat install dependency berat. Dengan OpenCV headless + model ONNX, peluang jalan di hosting produksi jauh lebih baik.

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
          "type": "face_embedding:opencv_sface",
          "dimension": 128,
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
- `face_recognition_sface_2021dec.onnx`

Jika Anda menaruh model di lokasi lain, atur path lewat environment variable.

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
KIOSK_FACE_PROVIDER=opencv_sface
KIOSK_FACE_MODEL_DIR=models
KIOSK_FACE_DETECTOR_MODEL=models/face_detection_yunet_2023mar.onnx
KIOSK_FACE_RECOGNIZER_MODEL=models/face_recognition_sface_2021dec.onnx
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
- Artinya, setelah pindah dari engine lama atau dari `browser` ke `opencv_sface`, guru perlu registrasi ulang wajah agar pencocokan tetap akurat.
- Jika memang ingin mengizinkan embedding lama yang tidak memiliki provider, set `KIOSK_FACE_ALLOW_LEGACY_EMBEDDINGS=true`, tetapi ini tidak saya sarankan untuk produksi.

## Catatan penting

- Liveness saat ini masih berbasis heuristic burst-frame, blur, texture, contrast, motion, dan replay-risk score.
- Untuk anti-spoof yang lebih kuat, langkah berikutnya sebaiknya menambah model dedicated anti-spoof/liveness.
- Laravel sudah mendukung driver `browser` dan `python` melalui `config/kiosk_face.php`.
