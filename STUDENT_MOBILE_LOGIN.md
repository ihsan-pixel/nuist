# Login Siswa NUIST Mobile

## Alur pengguna

Halaman login NUIST Mobile menyediakan dua pilihan:

- **Siswa** — masukkan **NISN** dan password siswa.
- **Tenaga Pendidik** — masukkan **email** dan password akun tenaga pendidik.

Pilihan peran dikirim ke `POST /api/mobile/login` melalui `login_as`. Server menolak akun yang tidak sesuai dengan peran yang dipilih.

## Password awal siswa

Siswa aktif yang memiliki NISN dan belum mempunyai password akan memperoleh password awal:

```text
Nuistddmmyyyy
```

`ddmmyyyy` adalah tanggal saat password dibuat. Misalnya, password yang dibuat pada 14 Agustus 2026 adalah `Nuist14082026`.

Password hanya disimpan sebagai hash. Password yang sudah ada tidak akan tertimpa oleh pengeditan data atau import siswa.

## Provisioning dan reset

- Migrasi `2026_08_14_000000_seed_missing_student_mobile_passwords.php` mengisi password untuk siswa aktif yang memiliki NISN tetapi belum memiliki password.
- Input manual dan import siswa juga otomatis memberi password awal hanya jika password sebelumnya kosong.
- Admin dapat mereset password satu siswa dari **Data Sekolah → Data Siswa** melalui tombol ikon kunci. Reset menghasilkan pola password awal dengan tanggal reset saat itu.
- Siswa tanpa NISN tidak dapat login hingga NISN dilengkapi.

## API aplikasi

Request baru:

```json
{
  "identifier": "0123456789",
  "password": "Nuist14082026",
  "login_as": "siswa"
}
```

Untuk tenaga pendidik, `identifier` berisi email dan `login_as` bernilai `tenaga_pendidik`.

Selama masa pembaruan aplikasi, format request lama (`email`, `password` tanpa `login_as`) tetap diterima oleh API.

## Keamanan operasional

Password berbasis tanggal mudah ditebak dan hanya dimaksudkan sebagai kredensial awal atau hasil reset admin. Berikan password tersebut secara pribadi kepada siswa/wali dan dorong siswa menggantinya segera. Jangan menampilkan password di tabel, log, atau file import.
