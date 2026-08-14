# Login Siswa NUIST Mobile

## Alur pengguna

Halaman login NUIST Mobile menyediakan dua pilihan:

- **Siswa** — masukkan **NISN** dan password siswa.
- **Tenaga Pendidik** — masukkan **email** dan password akun tenaga pendidik.

Pilihan peran dikirim ke `POST /api/mobile/login` melalui `login_as`. Server menolak akun yang tidak sesuai dengan peran yang dipilih.

Setiap siswa yang memiliki NISN memperoleh email internal pada `siswa.email_nuist` dengan format `NISN@nuist.id`, misalnya `0094908079@nuist.id`. Kolom `siswa.email` tetap khusus email pribadi dan tidak diisi otomatis. Ini tidak mengubah cara siswa login: siswa tetap memakai NISN.

## Password awal siswa

Siswa aktif yang memiliki NISN dan belum mempunyai password akan memperoleh password awal:

```text
Nuistddmmyyyy
```

`ddmmyyyy` adalah tanggal lahir siswa. Misalnya, siswa yang lahir pada 10 November 2009 memiliki password awal `Nuist10112009`.

Password hanya disimpan sebagai hash. Password yang sudah ada tidak akan tertimpa oleh pengeditan data atau import siswa.

## Provisioning dan reset

- Migrasi `2026_08_14_000000_seed_missing_student_mobile_passwords.php` mengisi password untuk siswa aktif yang memiliki NISN tetapi belum memiliki password.
- Input manual dan import siswa juga otomatis memberi password awal hanya jika password sebelumnya kosong.
- Admin dapat mereset password satu siswa dari **Data Sekolah → Data Siswa** melalui tombol ikon kunci. Reset memakai tanggal lahir siswa.
- Siswa tanpa NISN atau tanggal lahir tidak dapat memperoleh password default hingga datanya dilengkapi.

## API aplikasi

Request baru:

```json
{
  "identifier": "0123456789",
  "password": "Nuist10112009",
  "login_as": "siswa"
}
```

Untuk tenaga pendidik, `identifier` berisi email dan `login_as` bernilai `tenaga_pendidik`.

Selama masa pembaruan aplikasi, format request lama (`email`, `password` tanpa `login_as`) tetap diterima oleh API.

## Keamanan operasional

Password berbasis tanggal mudah ditebak dan hanya dimaksudkan sebagai kredensial awal atau hasil reset admin. Berikan password tersebut secara pribadi kepada siswa/wali dan dorong siswa menggantinya segera. Jangan menampilkan password di tabel, log, atau file import.
