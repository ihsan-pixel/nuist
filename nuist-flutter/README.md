# Nuist Flutter

Client mobile native Flutter untuk backend Laravel `nuist`.

## Prinsip

- Laravel tetap menjadi backend utama.
- App Flutter hanya mengakses API Laravel.
- App Flutter tidak pernah koneksi langsung ke database MySQL.
- Flow web/Blade lama tetap dibiarkan berjalan.

## Status Scaffold

Folder ini berisi source Flutter:

- arsitektur aplikasi
- routing
- auth token
- login
- dashboard
- tagihan
- izin
- profil

Environment saat scaffold ini dibuat belum memiliki CLI `flutter`, jadi folder runner native `android/` dan `ios/` belum digenerate otomatis.

## Dependensi yang dipakai

Versi di bawah saya ambil dari halaman resmi pub.dev:

- `dio: ^5.9.2`
- `flutter_riverpod: ^3.2.1`
- `flutter_secure_storage: ^10.0.0`
- `go_router: ^16.3.0`
- `intl: ^0.20.2`
- `flutter_lints: ^5.0.0`

Sumber:

- https://pub.dev/packages/dio
- https://pub.dev/packages/flutter_riverpod/versions
- https://pub.dev/packages/flutter_secure_storage
- https://pub.dev/packages/go_router/versions
- https://pub.dev/packages/intl
- https://pub.dev/packages/flutter_lints/versions

## Base URL

App ini memakai `--dart-define`:

```bash
flutter run --dart-define=BASE_URL=https://nuist.id/
```

`BASE_URL` harus diakhiri `/`.

Kalau tidak diisi, default fallback di source adalah `https://example.com/`.

## Endpoint Laravel yang dipakai

Sudah ada:

- `POST /api/mobile/login`
- `POST /api/mobile/logout`

Disarankan ditambahkan/dirapikan:

- `GET /api/mobile/me`
- `GET /api/mobile/dashboard`
- `GET /api/mobile/tagihan`
- `GET /api/mobile/izin`
- `GET /api/mobile/izin/{id}`

## Kontrak Response yang disarankan

Selain login, response sebaiknya konsisten:

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
        "id": 1,
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

## Menjalankan Setelah Flutter Terpasang

Pilihan paling aman:

1. Buat project Flutter baru lewat Android Studio atau `flutter create`.
2. Pakai nama project `nuist_flutter`.
3. Salin isi folder `lib/`, `pubspec.yaml`, dan `analysis_options.yaml` dari scaffold ini ke project Flutter tersebut.
4. Jalankan:

```bash
flutter pub get
flutter run --dart-define=BASE_URL=https://nuist.id/
```

Kalau Anda memang ingin runner dihasilkan langsung di folder ini, lakukan hanya setelah Flutter CLI tersedia, lalu review hasil generate sebelum commit.

## Struktur

- `lib/src/core`: config, network, storage, widgets umum
- `lib/src/models`: model data dari Laravel
- `lib/src/repositories`: repository untuk auth dan data mobile
- `lib/src/features`: auth, home, dashboard, billing, izin, profile

## Catatan Integrasi Laravel

- `POST /api/mobile/login` di backend saat ini masih menjadi fondasi auth utama.
- Mobile Flutter membaca token bearer dari secure storage.
- Semua request berikutnya menambahkan header `Authorization: Bearer ...`.
