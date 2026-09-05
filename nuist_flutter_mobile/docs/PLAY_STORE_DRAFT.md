# NUIST Google Play Store Draft

Status: draft for legal, product, and Play Console review.

## Privacy Policy Draft

### Pengelola Data

Nama organisasi: [NAMA BADAN/SEKOLAH]

Alamat: [ALAMAT LENGKAP]

Kontak privasi: [EMAIL PRIVASI]

URL kebijakan privasi publik: [URL HTTPS KEBIJAKAN PRIVASI]

NUIST Mobile digunakan oleh tenaga pendidik/pegawai GTK untuk login, melihat data
presensi, dan mengirim presensi sesuai kebijakan institusi.

### Data yang Diproses

- Data akun: nama, email/identifier akun, role GTK, dan token sesi.
- Data presensi: waktu, hasil presensi, lokasi, pembacaan lokasi, informasi device,
  foto/selfie, dan frame untuk verifikasi.
- Data biometrik: verifikasi wajah melalui profil wajah yang dikelola institusi.
  Embedding tidak ditampilkan kepada aplikasi Flutter.
- Data notifikasi: token perangkat Firebase Cloud Messaging.

### Tujuan Pemrosesan

- autentikasi akun GTK;
- verifikasi identitas dan liveness untuk presensi;
- validasi lokasi presensi;
- penyimpanan bukti presensi;
- pengiriman notifikasi aplikasi.

### Penyimpanan dan Berbagi Data

Data dikirim ke API Laravel milik institusi. Foto/selfie presensi dapat disimpan
sebagai bukti presensi. Verifikasi wajah diteruskan ke Face Engine institusi.

Detail lokasi penyimpanan, masa retensi, subprosesor, dan prosedur penghapusan:
[ISI OLEH PENGELOLA DATA].

Build production wajib menggunakan HTTPS. Jangan mengklaim data dihapus setelah
pemrosesan sebelum kebijakan retensi backend ditetapkan.

### Hak Pengguna

Permintaan akses, koreksi, atau penghapusan data diarahkan ke:
[EMAIL/URL KONTAK PENGELOLA DATA].

Akun GTK diprovision oleh administrator/institusi; pembuatan akun publik dari
aplikasi tidak tersedia.

## Google Play Data Safety Draft

Final answers memerlukan konfirmasi pemilik backend dan reviewer legal.

| Data type | Collected/shared | Purpose | Required | Notes |
| --- | --- | --- | --- | --- |
| Name and email | Yes | Account management | Yes | Institutional account |
| Authentication information | Yes | App functionality | Yes | Session token; password is not stored locally |
| Precise location | Yes | Attendance verification | Yes for attendance | Runtime permission |
| Photos/videos | Yes | Attendance selfie and face verification | Yes for face attendance | Sent to API |
| Biometrics | Yes, backend flow | Identity verification | Yes for face attendance | Confirm classification and retention |
| Device identifiers | Yes | Push notifications and diagnostics | Feature-dependent | FCM token/device information |

Declarations to confirm:

- Data in transit: Yes, for production HTTPS.
- Data deletion request: [URL OR EMAIL PROCEDURE].
- Encryption at rest: [CONFIRM WITH HOSTING/BACKEND OWNER].
- Whether data is sold: [CONFIRM WITH LEGAL OWNER].

## App Access / Reviewer Instructions

App requires an institutional GTK account. Do not use a fake account.

- Reviewer login email: [REVIEWER ACCOUNT]
- Reviewer password: [DELIVER THROUGH PLAY CONSOLE ONLY]
- Institution/school: [NAMA INSTITUSI]
- Required test location: [LOCATION OR TEST MODE]
- Face profile enrollment: [ADMIN-PROVISIONED TEST USER]
- Production Face Engine: [MUST BE DEPLOYED BEFORE REVIEW]

Reviewer must grant Camera, Precise Location, and Notifications permissions. Face
attendance requires an enrolled test profile and active production Face Engine.

## Permissions

- `CAMERA`: capture selfie and frames for attendance verification.
- `ACCESS_FINE_LOCATION`: verify attendance location precisely.
- `ACCESS_COARSE_LOCATION`: location fallback supported by Android APIs.
- `POST_NOTIFICATIONS`: attendance and operational notifications.
- `INTERNET`: Laravel API and notification services.

No microphone, contacts, storage, background location, or phone permissions are
declared in the main Android manifest.

## Target Audience Recommendation

Target audience: adults/working-age GTK and employees of the institution. Do not
target children. Confirm final age targeting and Families declarations in Play
Console before submission.

## Store Listing Draft

App name: NUIST Mobile

Short description: Presensi dan layanan kerja digital untuk tenaga pendidik NUIST.

Full description:

NUIST Mobile membantu tenaga pendidik dan pegawai mengakses layanan kerja institusi
melalui Android. Fitur meliputi login akun institusi, presensi berbasis lokasi,
verifikasi wajah dengan liveness, riwayat presensi, pengajuan izin, jadwal, jurnal
mengajar, dan notifikasi.

Akun disediakan administrator/institusi. Kamera dan lokasi hanya diperlukan saat
fitur presensi yang relevan digunakan. Ketersediaan fitur bergantung pada layanan
institusi.

Category: Productivity (confirm with product owner; Education is an alternative).

Screenshots required:

- Login tenaga pendidik.
- Dashboard GTK.
- Halaman presensi dan status lokasi.
- Camera face verification flow.
- Hasil presensi berhasil.
- Riwayat presensi.
- Jadwal/jurnal atau izin.

Use production screenshots only. Do not show local IPs, test accounts, debug
banners, tokens, or real personal/biometric data.
