# Monitoring Pengurus — Aplikasi Mobile

## Tujuan

Halaman pengurus menyediakan monitoring tingkat provinsi secara *read-only*: jumlah sekolah, siswa aktif, tenaga pendidik aktif, presensi hari ini, serta ringkasan tagihan.

## Akses dan API

Hanya pengguna dengan `role = pengurus` dan `is_active = true` yang dapat mengakses API berikut menggunakan token Sanctum:

- `GET /api/mobile/app/pengurus/dashboard`
- `GET /api/mobile/app/pengurus/schools`

API akan membalas `403` untuk peran lain atau akun tidak aktif.

## Perhitungan keuangan

Tagihan terbuka adalah tagihan berstatus `belum_lunas` atau `sebagian`.

Nilai tertunggak dihitung sebagai:

`total_tagihan - jumlah pembayaran dengan status diverifikasi`

`outstanding_amount` bukan kolom database; ia adalah accessor pada model `SppSiswaBill`. Dashboard menghitungnya melalui agregasi SQL agar tidak menimbulkan error kolom tidak ditemukan dan tidak memuat seluruh tagihan ke memori.

## Performa

- Data dashboard disimpan dalam cache selama 1 menit.
- Daftar sekolah disimpan dalam cache selama 5 menit.
- Hitungan siswa dan pendidik dibuat dengan query agregasi, bukan satu query per sekolah.
- Indeks monitoring tersedia melalui migrasi `2026_08_14_000004_add_pengurus_monitoring_indexes.php`.

## Pembaruan Keuangan di Dashboard

Dashboard menampilkan tiga pembaruan terbaru untuk masing-masing kelompok berikut:

- **Update Data UPPM**, dari `uppm_payment_updates`.
- **Update SPP Siswa**, dari transaksi `spp_siswa_transactions`.

Tombol **See All** membuka daftar hingga 50 pembaruan terbaru melalui API khusus pengurus:

- `GET /api/mobile/app/pengurus/updates/uppm`
- `GET /api/mobile/app/pengurus/updates/spp`

Data SPP yang ditampilkan pada monitoring hanya berisi sekolah, metode/status pembayaran, nominal, dan tanggal; identitas pribadi siswa tidak ditampilkan pada ringkasan.

## Katalog dan Detail Sekolah

Menu **Data Sekolah** mengelompokkan sekolah berdasarkan kabupaten dan mengurutkan sekolah berdasarkan SCOD. Jika API lama belum mengirim kabupaten, aplikasi memakai prefiks SCOD DIY sebagai fallback: `1xx` Bantul, `2xx` Gunungkidul, `3xx` Kulon Progo, `4xx` Sleman, dan `5xx` Kota Yogyakarta.

Memilih sekolah membuka detail read-only yang memuat profil sekolah, kepala sekolah, jumlah dan daftar tenaga pendidik, serta jumlah dan pratinjau maksimal 30 siswa aktif. Endpoint detailnya adalah:

- `GET /api/mobile/app/pengurus/schools/{madrasah}`

## Penanganan kegagalan

Aplikasi menampilkan pesan umum dan tombol **Coba lagi** jika server atau jaringan bermasalah. Detail teknis respons server tidak ditampilkan kepada pengguna.

## Verifikasi rilis

1. Jalankan `php artisan migrate --force` pada server produksi.
2. Bersihkan cache konfigurasi bila diperlukan: `php artisan optimize:clear`.
3. Login sebagai pengurus dan buka Beranda serta Sekolah.
4. Pastikan nilai tagihan dan jumlah data tampil tanpa respons HTTP 500.
