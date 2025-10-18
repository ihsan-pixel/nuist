# TODO: Implementasi Akses untuk Tenaga Pendidik Kepala Madrasah

## Completed Tasks
- [x] Update routes/web.php: tambah kondisi untuk tenaga pendidik kepala pada presensi_admin routes
- [x] Update PresensiAdminController: modifikasi __construct dan index method untuk handle tenaga pendidik kepala seperti admin
- [x] Update TeachingScheduleController index: jika tenaga pendidik dan ketugasan kepala, tampilkan seperti admin
- [x] Update sidebar.blade.php: tambah kondisi untuk show presensi admin jika tenaga_pendidik dan ketugasan kepala

## Pending Tasks
- [x] Update TeachingScheduleController showSchoolClasses: izinkan akses untuk tenaga pendidik kepala
- [ ] Test akses untuk user tenaga_pendidik dengan ketugasan kepala madrasah/sekolah
- [ ] Verifikasi bisa akses data presensi dan jadwal mengajar seperti admin
- [ ] Pastikan tidak ada error atau akses yang tidak diinginkan
