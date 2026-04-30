# Nuist Android

Scaffold awal aplikasi Android native untuk backend Laravel `nuist`.

## Tujuan

- Project mobile terpisah dari Laravel yang sudah live.
- Semua data diambil melalui API Laravel.
- Tidak ada koneksi langsung ke database dari Android.

## Stack

- Kotlin
- Jetpack Compose
- MVVM + Repository
- Hilt
- Retrofit + OkHttp
- Coroutines + Flow
- DataStore

## Struktur

- `app/src/main/java/com/nuist/android/core`: utilitas umum, network, UI state
- `app/src/main/java/com/nuist/android/data`: API service, DTO, repository
- `app/src/main/java/com/nuist/android/feature`: layar per fitur
- `app/src/main/java/com/nuist/android/navigation`: rute dan nav graph

## Konfigurasi

1. Buka `gradle.properties`
2. Ganti `NUIST_BASE_URL` menjadi domain Laravel Anda, misalnya:

```properties
NUIST_BASE_URL=https://nuist.id/
```

Base URL harus diakhiri `/`.

## Kontrak API yang dipakai scaffold ini

### Login

`POST /api/mobile/login`

Request:

```json
{
  "email": "guru@nuist.id",
  "password": "secret"
}
```

Response saat ini sudah mendekati kode backend Anda:

```json
{
  "token": "plain-text-token",
  "user": {
    "id": 1,
    "name": "Nama User",
    "email": "guru@nuist.id",
    "role": "tenaga_pendidik"
  },
  "mobile_route": "/mobile/dashboard"
}
```

### Endpoint yang disarankan untuk ditambahkan/dirapikan di Laravel

- `GET /api/mobile/me`
- `GET /api/mobile/dashboard`
- `GET /api/mobile/tagihan`
- `GET /api/mobile/izin`
- `GET /api/mobile/izin/{id}`
- `POST /api/mobile/logout`

Response direkomendasikan konsisten:

```json
{
  "message": "OK",
  "data": {}
}
```

Contoh `GET /api/mobile/tagihan`:

```json
{
  "message": "OK",
  "data": {
    "items": [
      {
        "id": 10,
        "nomor_tagihan": "INV-2026-001",
        "jenis_tagihan": "SPP",
        "periode": "2026-04",
        "jatuh_tempo": "2026-04-30",
        "total_tagihan": 350000,
        "status": "belum_lunas"
      }
    ],
    "total_unpaid": 350000
  }
}
```

## Integrasi Laravel yang aman

- Biarkan Blade mobile lama tetap berjalan.
- Tambahkan endpoint API baru tanpa mengubah route web lama.
- Gunakan token bearer dari Sanctum/mobile token.
- Mapping data screen native dilakukan di layer repository Android.

## Langkah selanjutnya

1. Rapikan endpoint API Laravel agar sesuai kontrak.
2. Buka folder ini di Android Studio.
3. Sync Gradle.
4. Uji login ke staging dulu sebelum ke production.
