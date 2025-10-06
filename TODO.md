# TODO: Implement Special Attendance Logic for Tenaga Pendidik

## Task Description
For users with role 'tenaga_pendidik' and 'pemenuhan_beban_kerja_lain' = false, allow attendance until 08:00, marking as late after 07:00, and block after 08:00.

## Steps
1. [x] Modify PresensiController create method to adjust timeRanges for special users.
2. [x] Modify PresensiController store method to set batasAkhirMasuk to 08:00 for special users.
3. Test the implementation.
