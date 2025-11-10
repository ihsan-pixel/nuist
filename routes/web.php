<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MadrasahController;
use App\Http\Controllers\TenagaPendidikController;
use App\Http\Controllers\StatusKepegawaianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PresensiAdminController;
use App\Http\Controllers\DevelopmentHistoryController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\TeachingScheduleController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route utama aplikasi NUIST.
|
*/

// ======================================================
// 🔹 AUTH ROUTES
// ======================================================
Auth::routes(['verify' => true]);

// Logout route (pasti pakai LoginController agar tidak error)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Redirect setelah login langsung ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('root');

// Redirect /home bawaan Laravel → dashboard
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->middleware('auth');

// ======================================================
// 🔹 PRESENSI ADMIN
// ======================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/presensi-admin/settings', [PresensiAdminController::class, 'settings'])->name('presensi_admin.settings');
    Route::post('/presensi-admin/settings', [PresensiAdminController::class, 'updateSettings'])->name('presensi_admin.updateSettings');
    Route::get('/presensi-admin', [PresensiAdminController::class, 'index'])->name('presensi_admin.index');
    Route::get('/presensi-admin/data', [PresensiAdminController::class, 'getData'])->name('presensi_admin.data');
    Route::get('/presensi-admin/summary', [PresensiAdminController::class, 'getSummary'])->name('presensi_admin.summary');
    Route::get('/presensi-admin/detail/{userId}', [PresensiAdminController::class, 'getDetail'])->name('presensi_admin.detail');
    Route::get('/presensi-admin/madrasah-detail/{madrasahId}', [PresensiAdminController::class, 'getMadrasahDetail'])->name('presensi_admin.madrasah_detail');
    Route::get('/presensi-admin/export', [PresensiAdminController::class, 'export'])->name('presensi_admin.export');
    Route::get('/presensi-admin/export-madrasah/{madrasahId}', [PresensiAdminController::class, 'exportMadrasah'])->name('presensi_admin.export_madrasah');
});

// ======================================================
// 🔹 TEACHING PROGRESS & DEVELOPMENT HISTORY
// ======================================================
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/teaching-progress', [App\Http\Controllers\TeachingProgressController::class, 'index'])->name('admin.teaching_progress');
});

Route::middleware(['auth', 'role:super_admin,pengurus'])->group(function () {
    Route::get('/development-history', [DevelopmentHistoryController::class, 'index'])->name('development-history.index');
    Route::get('/development-history/sync', [DevelopmentHistoryController::class, 'syncMigrations'])->name('development-history.sync');
    Route::get('/development-history/export/{format}', [DevelopmentHistoryController::class, 'export'])->name('development-history.export');
    Route::post('/admin/run-commit-tracking', [DevelopmentHistoryController::class, 'runCommitTracking'])->name('admin.run-commit-tracking');
    Route::post('/admin/regenerate-documentation', [DevelopmentHistoryController::class, 'regenerateDocumentation'])->name('admin.regenerate-documentation');
    Route::get('/active-users', [App\Http\Controllers\ActiveUsersController::class, 'index'])->name('active-users.index');
});

// ======================================================
// 🔹 TEACHING SCHEDULES
// ======================================================
Route::middleware(['auth', 'role:super_admin,admin,pengurus,tenaga_pendidik'])->group(function () {
    Route::resource('teaching-schedules', App\Http\Controllers\TeachingScheduleController::class);
    Route::get('teaching-schedules/get-teachers/{schoolId}', [App\Http\Controllers\TeachingScheduleController::class, 'getTeachersBySchool'])->name('teaching-schedules.get-teachers');
    Route::get('teaching-schedules/import', [App\Http\Controllers\TeachingScheduleController::class, 'import'])->name('teaching-schedules.import');
    Route::post('teaching-schedules/import', [App\Http\Controllers\TeachingScheduleController::class, 'processImport'])->name('teaching-schedules.process-import');
    Route::get('teaching-schedules/school/{schoolId}/schedules', [App\Http\Controllers\TeachingScheduleController::class, 'showSchoolSchedules'])->name('teaching-schedules.school-schedules');
    Route::get('teaching-schedules/school/{schoolId}/classes', [App\Http\Controllers\TeachingScheduleController::class, 'showSchoolClasses'])->name('teaching-schedules.school-classes');
    Route::post('teaching-schedules/filter', [App\Http\Controllers\TeachingScheduleController::class, 'filter'])->name('teaching-schedules.filter');
});

// ======================================================
// 🔹 TEACHING ATTENDANCE
// ======================================================
Route::middleware(['auth', 'role:tenaga_pendidik'])->group(function () {
    Route::get('/teaching-attendances', [App\Http\Controllers\TeachingAttendanceController::class, 'index'])->name('teaching-attendances.index');
    Route::post('/teaching-attendances', [App\Http\Controllers\TeachingAttendanceController::class, 'store'])->name('teaching-attendances.store');
    Route::post('/teaching-attendances/check-location', [App\Http\Controllers\TeachingAttendanceController::class, 'checkLocation'])->name('teaching-attendances.check-location');
});

// ======================================================
// 🔹 FAKE LOCATION MONITOR
// ======================================================
Route::middleware(['auth', 'role:super_admin'])->get('/fake-location', [App\Http\Controllers\FakeLocationController::class, 'index'])->name('fake-location.index');

// ======================================================
// 🔹 CHAT ADMIN
// ======================================================
Route::middleware(['auth', 'role:super_admin,admin'])->get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');

// ======================================================
// 🔹 ADMIN FACE ENROLLMENT
// ======================================================
Route::prefix('admin')->middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/face-enrollment', [App\Http\Controllers\Admin\FaceEnrollmentController::class, 'index'])->name('face.enrollment.list');
    Route::get('/face-enrollment/{userId}', [App\Http\Controllers\Admin\FaceEnrollmentController::class, 'show'])->name('face.enrollment');
    Route::delete('/face-enrollment/{userId}', [App\Http\Controllers\Admin\FaceEnrollmentController::class, 'destroy'])->name('face.enrollment.destroy');
    Route::post('/face-enrollment/{userId}/toggle-verification', [App\Http\Controllers\Admin\FaceEnrollmentController::class, 'toggleVerification'])->name('face.enrollment.toggle-verification');
});

// ======================================================
// 🔹 DASHBOARD UTAMA
// ======================================================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth']);

// ======================================================
// 🔹 MOBILE ROUTES
// ======================================================
Route::middleware(['auth', 'role:tenaga_pendidik,admin'])
    ->prefix('mobile')
    ->name('mobile.')
    ->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Mobile\Dashboard\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'presensi'])->name('presensi');
    Route::post('/presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'storePresensi'])->name('presensi.store');
    Route::get('/selfie-presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'selfiePresensi'])->name('selfie-presensi');
    Route::post('/selfie-presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'storeSelfiePresensi'])->name('selfie-presensi.store');
    Route::get('/riwayat-presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'riwayatPresensi'])->name('riwayat-presensi');
    Route::get('/riwayat-presensi-alpha', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'riwayatPresensiAlpha'])->name('riwayat-presensi-alpha');
    Route::get('/jadwal', [App\Http\Controllers\Mobile\Jadwal\JadwalController::class, 'jadwal'])->name('jadwal');
    Route::get('/profile', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update-profile', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'updateProfile'])->name('profile.update-profile');
    Route::post('/profile/update-avatar', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::post('/profile/update-password', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/izin', [App\Http\Controllers\Mobile\Izin\IzinController::class, 'izin'])->name('izin');
    Route::post('/izin', [App\Http\Controllers\Mobile\Izin\IzinController::class, 'storeIzin'])->name('izin.store');
    Route::get('/laporan', [App\Http\Controllers\Mobile\Laporan\LaporanController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/mengajar', [App\Http\Controllers\Mobile\Laporan\LaporanController::class, 'laporanMengajar'])->name('laporan.mengajar');
    Route::get('/teaching-attendances', [App\Http\Controllers\Mobile\Laporan\LaporanController::class, 'teachingAttendances'])->name('teaching-attendances');
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
});

// ======================================================
// 🔹 PANDUAN
// ======================================================
Route::get('/panduan', [PanduanController::class, 'index'])
    ->middleware(['auth', 'role:super_admin,pengurus'])
    ->name('panduan.index');

// ======================================================
// 🔹 CSRF TOKEN ENDPOINT
// ======================================================
Route::get('/csrf-token', fn() => response()->json(['token' => csrf_token()]));

// ======================================================
// 🔹 FALLBACK ROUTE (404 SAFE)
// ======================================================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
