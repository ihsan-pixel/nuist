# Reset Password Siswa

## Alur

1. Pada login, pilih **Siswa** lalu tekan **Lupa Password?**.
2. Isi NISN, tanggal lahir, dan nama ibu kandung sesuai data sekolah.
3. Selesaikan Cloudflare Turnstile.
4. Server memverifikasi CAPTCHA dan mencocokkan data siswa aktif.
5. Password direset menjadi `Nuistddmmyyyy` berdasarkan tanggal lahir; seluruh token login siswa sebelumnya dicabut.

Contoh tanggal lahir 10 November 2009 menghasilkan `Nuist10112009`.

## Konfigurasi server

Tambahkan nilai berikut pada `.env` Laravel, lalu jalankan `php artisan config:clear`:

```env
TURNSTILE_SITE_KEY=...
TURNSTILE_SECRET_KEY=...
```

Kunci rahasia hanya dibaca server dan tidak pernah dikirim ke Flutter. Widget CAPTCHA dimuat dari halaman HTTPS `mobile/student-password-reset/captcha`; pastikan hostname halaman tersebut terdaftar pada widget Turnstile.

## Proteksi

- CAPTCHA diverifikasi server-side ke Cloudflare Siteverify.
- Maksimal tiga percobaan verifikasi gagal untuk kombinasi IP dan NISN selama 15 menit.
- Pesan kegagalan bersifat generik agar tidak membocorkan apakah NISN terdaftar.
- Siswa tanpa tanggal lahir atau nama ibu kandung di data master harus menghubungi admin sekolah.
