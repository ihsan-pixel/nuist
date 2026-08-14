# Kebijakan Pembuatan Akun

## Registrasi publik ditutup

Akun **tenaga pendidik** dan **pengurus** tidak dapat dibuat melalui formulir publik, aplikasi Flutter, atau API publik. Endpoint mobile untuk pendaftaran telah dihapus dan halaman pendaftaran web mengembalikan `404`.

## Sumber akun yang sah

- **Siswa**: berasal dari data siswa resmi dan masuk dengan NISN.
- **Tenaga pendidik**: dibuat oleh admin berwenang dari data guru resmi atau hasil import terverifikasi.
- **Pengurus**: dibuat hanya oleh administrator berwenang berdasarkan penugasan lembaga.

## Aktivasi dan kredensial

Admin wajib memastikan nama, peran, madrasah, dan email berasal dari data resmi sebelum membuat akun. Password awal atau tautan aktivasi harus disampaikan melalui saluran terverifikasi; password tidak boleh ditampilkan di data publik.

## Operasional keamanan

- Gunakan reset password hanya untuk akun yang telah terdaftar.
- Tinjau akun dan peran secara berkala, lalu nonaktifkan akun yang sudah tidak bertugas.
- Catat pembuatan, perubahan peran, reset password, serta penonaktifan akun dalam audit log.
- Jangan membuka kembali endpoint registrasi publik tanpa verifikasi identitas terhadap data master dan persetujuan admin madrasah.
