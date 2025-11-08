# Rencana Implementasi Validasi Face ID untuk Presensi Mobile

## Tujuan
Menambahkan validasi Face ID agar setiap guru harus melakukan presensi dengan wajah mereka sendiri, mencegah praktik titip presensi menggunakan device rekan guru. Termasuk fitur liveness detection dengan instruksi berkedip untuk mencegah penggunaan foto tidak bergerak.

## Analisis Sistem Saat Ini
- Sistem presensi menggunakan Laravel dengan validasi lokasi berbasis polygon
- Sudah ada deteksi fake location dan multiple GPS readings
- Interface mobile menggunakan Leaflet untuk map dan SweetAlert untuk notifikasi
- Data presensi disimpan di tabel `presensis` dengan relasi ke `users`

## Rencana Implementasi

### 1. Database Schema Updates
- [x] Buat migration untuk menambahkan kolom face_data, face_id, face_registered_at, face_verification_required ke tabel users
- [x] Buat migration untuk menambahkan kolom face_id_used, face_similarity_score, liveness_score, liveness_challenges, face_verified, face_verification_notes ke tabel presensis

### 2. Model Updates
- [x] Update model `User` untuk menambahkan fillable face_data, face_id, face_registered_at, face_verification_required
- [x] Update model `Presensi` untuk menambahkan fillable face_id_used, face_similarity_score, liveness_score, liveness_challenges, face_verified, face_verification_notes
- [x] Tambahkan casting untuk kolom JSON dan boolean

### 3. Face Recognition Setup
- [x] Integrasikan face-api.js untuk deteksi dan pengenalan wajah di sisi client
- [x] Implementasi liveness detection dengan deteksi berkedip (blink detection) dan random instructions
- [x] Tambahkan multiple liveness challenges (kedip mata, senyum, gerakkan kepala) secara acak
- [x] Implementasi sequence-based validation untuk mencegah video loop replay
- [x] Buat endpoint API untuk enroll face (pendaftaran wajah) di admin panel
- [x] Buat endpoint API untuk verifikasi face dengan liveness check saat presensi

### 4. Mobile Interface Updates
- [x] Tambahkan section face capture di halaman presensi mobile
- [x] Implementasi kamera untuk capture foto wajah saat presensi
- [x] Tambahkan instruksi liveness detection dengan multiple challenges (kedip mata, senyum, gerakkan kepala) secara acak
- [x] Implementasi real-time feedback untuk setiap challenge yang harus diselesaikan
- [x] Tambahkan validasi face recognition dan liveness sebelum submit presensi
- [x] Update UI untuk menampilkan status face validation dan liveness check

### 5. Backend Validation Logic
- [x] Update `PresensiController@storePresensi` untuk memproses face data dan liveness score
- [x] Implementasi face comparison logic di backend
- [x] Implementasi liveness validation dengan threshold score dan sequence verification
- [x] Validasi bahwa semua challenges dalam sequence telah diselesaikan dengan benar
- [x] Tambahkan threshold similarity score (misal 0.8) dan liveness score (misal 0.7) untuk validasi
- [x] Handle error cases (face not recognized, multiple faces, no blink detected, dll)

### 6. Admin Panel Features
- [x] Buat halaman untuk manage face enrollment per user
- [x] Tambahkan fitur re-enroll face jika diperlukan
- [ ] Buat laporan presensi dengan status face validation dan liveness
- [ ] Tambahkan monitoring untuk presensi yang gagal liveness check

### 7. Security & Privacy
- [x] Pastikan face data disimpan dengan aman (encrypted)
- [ ] Implementasi consent untuk penggunaan face data
- [ ] Tambahkan GDPR compliance untuk face data handling
- [ ] Implementasi rate limiting untuk mencegah brute force attempts

### 8. Testing & Deployment
- [x] Test face recognition accuracy di berbagai kondisi lighting
- [x] Test liveness detection dengan berbagai skenario (berkedip, senyum, gerakan kepala, video loop, foto statis)
- [x] Test sequence validation untuk memastikan semua challenges terdeteksi dengan benar
- [x] Test di multiple devices (Android/iOS browsers)
- [x] Test edge cases (sunglasses, mask, different angles, fast blink, slow blink)
- [x] Deploy dengan fallback ke presensi tanpa face jika face recognition gagal

## Dependencies yang Diperlukan
- face-api.js untuk client-side face recognition dan liveness detection
- Tambahkan library tambahan untuk advanced liveness detection jika diperlukan
- Tambahkan package Laravel untuk image processing jika diperlukan
- Update composer.json dan package.json jika ada dependency baru

## Timeline Estimasi
- Database & Model: 2 hari
- Face Recognition & Liveness Setup: 4 hari
- Mobile Interface: 5 hari
- Backend Logic: 4 hari
- Admin Panel: 2 hari
- Testing: 4 hari
- **Total: 21 hari**

## Risiko & Mitigasi
- **Risiko**: Face recognition tidak akurat di kondisi buruk
  - **Mitigasi**: Tetapkan threshold rendah dan berikan opsi manual override
- **Risiko**: Liveness detection bisa di-bypass dengan video loop
  - **Mitigasi**: Implementasi multiple random challenges dalam sequence yang harus diselesaikan secara berurutan
- **Risiko**: Deepfake atau advanced spoofing techniques
  - **Mitigasi**: Kombinasikan dengan behavioral analysis dan device fingerprinting
- **Risiko**: Browser compatibility issues
  - **Mitigasi**: Fallback ke presensi tanpa face untuk browser yang tidak support
- **Risiko**: Privacy concerns
  - **Mitigasi**: Implementasi consent dan data encryption

## Next Steps
1. [x] Mulai dengan database migration dan model updates
2. [x] Setup face-api.js integration dengan liveness detection
3. [x] Develop face capture interface dengan blink instruction
4. [x] Implement validation logic
5. [x] Testing secara bertahap
6. [x] Implement self-enrollment for face verification
7. [x] Update mobile presensi UI to support self-enrollment
8. [x] Add enrollment interface directly in presensi page
9. [x] Update FaceController to support self-enrollment
10. [x] Update PresensiController to indicate when face enrollment is needed
11. [x] Test self-enrollment flow
