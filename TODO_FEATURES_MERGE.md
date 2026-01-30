# TODO - Merge Features in Landing Blade

## Task
Gabungkan fitur yang sudah jadi (active) dan coming soon menjadi satu section di landing.blade.php

## Plan
- [x] Baca dan pahami struktur kode landing.blade.php
- [x] Konfirmasi plan dengan user
- [x] Edit bagian features section untuk menggabungkan kedua grid
- [x] Hapus section pemisah "Coming Soon"
- [x] Pertahankan visual distinction (warna border berbeda)
- [ ] Test tampilan

## Detail Perubahan
File: resources/views/landing/landing.blade.php

### Perubahan:
1. Hapus section "Coming Soon" badge dan pemisah
2. Gabungkan kedua grid (@foreach active dan @foreach coming_soon) menjadi satu grid
3. Setiap card tetap memiliki styling berbeda berdasarkan status (active = border hijau, coming_soon = border kuning + ribbon)

## Status: SELESAI ✓
Perubahan sudah diimplementasikan. Semua fitur (aktif dan coming soon) sekarang ditampilkan dalam satu grid.

