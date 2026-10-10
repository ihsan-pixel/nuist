# Rancangan Fitur Presensi Agenda melalui QR Publik

## 1. Tujuan

Fitur ini memungkinkan peserta melakukan presensi tanpa login dengan alur:

1. Admin membuat agenda dan menentukan peserta.
2. Sistem membuat QR berisi URL HTTPS yang dapat dibaca kamera, Google Lens, dan aplikasi pemindai lain.
3. Peserta membuka URL dari QR.
4. Peserta mencari dan memilih namanya, atau mengisi nama sebagai tamu jika agenda mengizinkan.
5. Peserta mengonfirmasi identitas, lalu sistem mencatat waktu kehadiran.

Fitur dibangun sebagai pengembangan modul agenda BPPPMNU yang sudah ada agar agenda, peserta, QR, jendela waktu, rekap, dan ekspor tetap berada dalam satu sumber data.

## 2. Temuan dari aplikasi saat ini

Komponen yang sudah tersedia:

- CRUD agenda pada `BpppmnuEventController`.
- Pemilihan banyak peserta melalui `bpppmnu_event_invitations`.
- QR bertoken, masa berlaku, pencabutan QR, dan status agenda.
- Batas waktu buka/tutup presensi.
- Validasi lokasi opsional.
- Pencegahan presensi ganda dengan unique key `(event_id, user_id)`.
- Rekap kehadiran dan ekspor Excel.

Kesenjangan terhadap kebutuhan baru:

- QR agenda saat ini berisi JSON internal, bukan URL web, sehingga hasil Google Lens tidak langsung membuka halaman presensi.
- Endpoint presensi peserta masih berada di balik autentikasi.
- Peserta belum dapat mencari/memilih nama pada halaman publik.
- Belum ada peserta tamu/nama manual.
- Route pengelolaan agenda saat ini hanya menerima `admin_yayasan`; `super_admin` perlu diberi akses eksplisit.
- Memilih nama tanpa pengaman sama sekali memungkinkan seseorang mengabsenkan peserta lain.

## 3. Keputusan produk

### 3.1 Mode peserta per agenda

Admin memilih salah satu mode berikut:

- **Peserta terdaftar**: hanya nama yang telah dimasukkan admin yang dapat presensi.
- **Peserta terdaftar + tamu**: peserta resmi dapat memilih nama, sedangkan peserta yang tidak ditemukan dapat mengetik nama.
- **Tamu saja**: semua peserta mengetik nama; cocok untuk kegiatan umum.

Mode yang disarankan sebagai default adalah **Peserta terdaftar**.

### 3.2 Verifikasi tanpa login

Tidak ada email/password dan tidak ada sesi akun. Untuk peserta terdaftar, setelah memilih nama sistem meminta konfirmasi ringan berupa 4 digit terakhir nomor HP atau kode peserta. Pengaturan ini dapat dinonaktifkan admin untuk kegiatan berisiko rendah, tetapi layar tetap menampilkan konfirmasi: “Saya benar-benar [nama peserta]”.

Untuk tamu, minimal wajib diisi:

- Nama lengkap.
- Asal instansi/unit (opsional atau wajib sesuai pengaturan agenda).
- Nomor HP (opsional atau wajib sesuai pengaturan agenda).

Nama manual tidak otomatis membuat akun `users` dan tidak boleh dikaitkan ke user hanya berdasarkan kemiripan nama.

### 3.3 Isi QR

QR harus berisi URL HTTPS biasa, misalnya:

```text
https://nuist.id/hadir/7f1c...token-acak...
```

Token mentah hanya ada di URL dan QR. Database menyimpan hash token. QR dapat dicabut dan dibuat ulang. Setelah dibuat ulang, QR lama tidak berlaku.

## 4. Alur pengguna

### Admin/super admin

1. Buka menu **Agenda Kegiatan**.
2. Klik **Buat Agenda**.
3. Isi nama agenda, penyelenggara, lokasi, tanggal/jam agenda, dan waktu presensi.
4. Pilih mode peserta.
5. Masukkan peserta dengan salah satu atau gabungan cara:
   - pilih semua;
   - cari dan centang nama;
   - filter unit/madrasah/jabatan;
   - impor Excel;
   - gunakan kelompok peserta tersimpan.
6. Aktifkan opsi yang diperlukan: verifikasi 4 digit HP, validasi lokasi, izinkan tamu, dan batasi kapasitas.
7. Simpan sebagai draft, periksa ringkasan, lalu terbitkan.
8. Klik **Tampilkan/Unduh QR**.
9. Pantau jumlah hadir secara real-time dan unduh rekap.

### Peserta

1. Scan QR dengan Google Lens/kamera.
2. Browser membuka halaman publik berisi nama agenda, lokasi, dan status waktu presensi.
3. Cari nama minimal 2–3 karakter. Daftar tidak ditampilkan seluruhnya saat halaman pertama dibuka.
4. Pilih nama dan konfirmasi identitas, atau pilih **Nama saya tidak ditemukan** jika tamu diizinkan.
5. Tekan **Catat Kehadiran**.
6. Halaman sukses menampilkan nama agenda, nama peserta, dan waktu presensi.

### Kondisi khusus

- Belum dibuka: tampilkan waktu pembukaan, form dinonaktifkan.
- Sudah ditutup: tampilkan bahwa presensi berakhir, form dinonaktifkan.
- QR dicabut/tidak valid: tampilkan pesan umum tanpa membocorkan detail token.
- Sudah hadir: jangan membuat data kedua; tampilkan waktu presensi sebelumnya.
- Nama salah dipilih: perubahan hanya dapat dilakukan admin dan dicatat dalam audit log.

## 5. Rancangan tampilan

### Halaman QR admin

- Judul dan waktu agenda.
- QR besar dengan kontras tinggi dan quiet zone yang cukup.
- URL pendek di bawah QR sebagai cadangan.
- Tombol **Unduh PNG**, **Unduh SVG**, **Cetak**, **Buat Ulang QR**, dan **Cabut QR**.
- Status aktif/kedaluwarsa dan hitung mundur waktu penutupan.

### Halaman publik peserta

- Header sederhana dengan logo, tanpa menu login/dashboard.
- Kartu informasi agenda.
- Indikator “Presensi dibuka sampai … WIB”.
- Kolom pencarian besar: **Ketik nama Anda**.
- Hasil pencarian berupa kartu nama, unit, dan jabatan; data sensitif tidak ditampilkan.
- Tombol **Nama saya tidak ditemukan** hanya muncul jika tamu diizinkan.
- Dialog konfirmasi sebelum penyimpanan.
- Halaman sukses yang jelas dan tidak otomatis menampilkan daftar peserta lain.

Tampilan harus mobile-first, tetap nyaman pada layar 360 px, dan tidak bergantung pada pemindai QR di dalam aplikasi.

## 6. Perubahan basis data

### Tambahan pada `bpppmnu_events`

- `attendance_access_mode`: `registered`, `hybrid`, atau `guest`.
- `public_name_verification`: `none`, `phone_last4`, atau `participant_code`.
- `guest_phone_required`: boolean.
- `guest_organization_required`: boolean.
- `capacity`: nullable integer.

### Perubahan `bpppmnu_event_attendances`

Jadikan `user_id` nullable untuk tamu, lalu tambahkan:

- `invitation_id`: nullable foreign key untuk peserta terdaftar.
- `guest_name`: nullable string.
- `guest_phone`: nullable string (disimpan terenkripsi atau diminimalkan).
- `guest_organization`: nullable string.
- `method`: gunakan nilai `public_qr_registered`, `public_qr_guest`, `admin_barcode`, atau `authenticated_qr`.
- `ip_hash`: nullable string; hash dengan secret aplikasi, bukan IP mentah untuk retensi jangka panjang.
- `user_agent`: nullable text dengan panjang dibatasi.
- `latitude`, `longitude`, `distance_meters`: nullable untuk validasi lokasi/audit.
- `confirmation_code`: kode referensi acak yang tampil pada layar sukses.

Constraint yang diperlukan:

- Tetap unik untuk `(event_id, user_id)` ketika `user_id` tidak null.
- Untuk tamu, cegah klik ganda menggunakan idempotency key/request nonce, bukan unique berdasarkan nama.

### Tabel audit baru `bpppmnu_attendance_audits`

Mencatat pembuatan, koreksi, dan pembatalan presensi: `attendance_id`, aksi, pelaku admin (nullable untuk aksi publik), alasan, snapshot sebelum/sesudah, dan timestamp.

## 7. Route dan endpoint

Route publik tidak memakai middleware `auth`, tetapi tetap memakai throttle dan token acak:

```php
GET  /hadir/{token}                 // halaman landing agenda
GET  /hadir/{token}/peserta         // pencarian peserta terdaftar
POST /hadir/{token}/konfirmasi      // simpan presensi
GET  /hadir/{token}/sukses/{code}   // bukti sukses minimal
```

Aturan endpoint pencarian:

- Minimal 2–3 karakter.
- Maksimal 10–20 hasil.
- Hanya peserta pada agenda tersebut.
- Tidak mengembalikan email, nomor HP penuh, alamat, atau data pribadi lain.
- Throttle per IP/token dan respons diberi `Cache-Control: no-store`.

Route admin tetap terlindungi autentikasi dan role `super_admin,admin_yayasan`.

## 8. Validasi server

Pada setiap submit, server wajib memeriksa ulang:

1. Token ada, belum dicabut, dan belum kedaluwarsa.
2. Agenda berstatus `published`.
3. Waktu sekarang berada dalam jendela presensi.
4. Mode agenda mengizinkan jenis peserta yang dikirim.
5. Peserta terdaftar benar-benar memiliki undangan pada agenda.
6. Verifikasi ringan cocok jika diaktifkan.
7. Peserta belum tercatat hadir.
8. Lokasi memenuhi radius jika validasi lokasi aktif.
9. Kapasitas belum penuh jika kapasitas dibatasi.

Penyimpanan dilakukan dalam transaksi database dengan lock pada agenda. Frontend tidak boleh menjadi sumber keputusan validasi.

## 9. Keamanan dan privasi

- Gunakan token acak minimal 256 bit dan simpan hanya hash SHA-256.
- Jangan memasukkan ID agenda berurutan sebagai satu-satunya kunci akses.
- Tambahkan throttle bertingkat pada landing, pencarian, dan submit.
- Gunakan CSRF untuk form browser; untuk URL publik tetap buat session anonim secara normal.
- Hindari menampilkan semua nama sekaligus agar daftar peserta tidak mudah disalin.
- Masking data pendukung, misalnya nomor HP hanya `****1234` bila perlu.
- Jangan mengizinkan nama manual pada agenda tertutup tanpa opsi admin.
- Catat koreksi/penghapusan oleh admin di audit log.
- Sediakan tombol tutup presensi dan cabut QR darurat.
- Pertimbangkan CAPTCHA hanya setelah terdeteksi trafik mencurigakan agar alur normal tetap cepat.

Catatan: tanpa login atau verifikasi tambahan, sistem tidak dapat menjamin bahwa orang yang memilih nama adalah pemilik nama tersebut. Karena itu konfirmasi 4 digit nomor HP/kode peserta direkomendasikan untuk agenda resmi.

## 10. Rekap admin

Rekap menampilkan:

- Total diundang, hadir terdaftar, belum hadir, tamu, dan persentase hadir.
- Nama, kategori peserta, unit/jabatan, waktu hadir, metode, dan status validasi lokasi.
- Pencarian dan filter status/kategori.
- Aksi koreksi nama tamu, pindahkan pencatatan ke peserta yang benar, batalkan presensi beserta alasan.
- Ekspor Excel/PDF dengan pemisahan peserta resmi dan tamu.

Peserta publik tidak dapat melihat rekap nama peserta lain.

## 11. Tahapan implementasi

### Fase 1 — MVP

- Akses `super_admin` dan `admin_yayasan`.
- QR berisi URL publik.
- Mode peserta terdaftar dan hybrid.
- Pencarian/pilih nama, input tamu, konfirmasi, dan halaman sukses.
- Pencegahan duplikat, throttle, masa berlaku, serta rekap tamu.
- Tes fitur untuk token, waktu, role, undangan, duplikasi, dan guest mode.

### Fase 2 — Operasional

- Impor Excel dan filter peserta per unit/jabatan.
- Unduh PNG/cetak poster QR.
- Audit koreksi presensi.
- Ekspor PDF dan statistik yang lebih lengkap.

### Fase 3 — Penguatan

- Kode peserta/OTP opsional.
- QR dinamis berganti periodik untuk kegiatan berisiko tinggi.
- Notifikasi dan integrasi agenda lain jika dibutuhkan.

## 12. Kriteria penerimaan MVP

- QR dapat dikenali Google Lens dan membuka halaman HTTPS yang benar.
- Halaman dapat digunakan tanpa login.
- Admin yayasan dan super admin dapat membuat, menerbitkan, dan menutup agenda.
- Admin dapat memasukkan peserta sekaligus dan mengizinkan/menolak tamu.
- Peserta terdaftar dapat dicari dan dipilih tanpa membuka seluruh daftar.
- Nama manual hanya tersedia pada agenda yang mengizinkannya.
- Satu peserta terdaftar tidak dapat tercatat dua kali.
- Refresh atau klik ganda tidak menghasilkan presensi ganda.
- QR lama gagal setelah dibuat ulang atau dicabut.
- Submit ditolak sebelum waktu buka dan setelah waktu tutup.
- Rekap membedakan peserta resmi dan tamu dengan benar.
- Semua aturan penting diuji di sisi server.

## 13. Rekomendasi final

Gunakan modul BPPPMNU yang sudah ada sebagai fondasi dan pertahankan alur lama untuk kompatibilitas. Tambahkan kanal `public_qr` sebagai pilihan per agenda. Konfigurasi default yang disarankan adalah peserta terdaftar, pencarian nama, verifikasi 4 digit nomor HP, tamu nonaktif, dan validasi lokasi opsional. Untuk kegiatan umum, admin dapat memilih mode hybrid agar orang yang belum terdaftar tetap bisa mengetik nama.
