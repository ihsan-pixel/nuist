# TODO: Implement Teaching Attendance for Teachers

## Steps:
- [x] Create migration for teaching_attendances table with fields: teaching_schedule_id (foreign key), user_id (foreign key), tanggal (date), waktu (time), status (enum: hadir, alpha), latitude (decimal), longitude (decimal), lokasi (string nullable).
- [x] Create TeachingAttendance model with relationships to TeachingSchedule and User.
- [x] Create TeachingAttendanceController with index (show today's schedules for teacher) and store (mark attendance with location/time validation).
- [x] Create view: resources/views/teaching-attendances/index.blade.php to list schedules with attendance buttons.
- [x] Add routes for teaching-attendances resource in routes/web.php (middleware for tenaga_pendidik).
- [x] Update sidebar.blade.php to include "Presensi Mengajar" menu for tenaga_pendidik role.
- [x] Fix view layout issues (changed to layouts.master, fixed school name field, added vendor-script and page-script sections).
- [x] Update create.blade.php to allow multiple subjects per day with dynamic add/remove functionality.
- [x] Update TeachingScheduleController validation to allow same time slots on different days (changed overlap check from school-wide to teacher-specific).
- [ ] Run the migration (Database connection issue - needs to be run manually).
- [ ] Test: Login as tenaga_pendidik, check menu, view schedules, attempt attendance inside/outside time/location.
