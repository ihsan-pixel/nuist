<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MadrasahController;
use App\Http\Controllers\TenagaPendidikController;
use App\Http\Controllers\StatusKepegawaianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PresensiAdminController;
use App\Http\Controllers\DevelopmentHistoryController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\Mobile\Izin\IzinController;
use App\Http\Controllers\Mobile\LaporanAkhirTahunKepalaSekolahController;
use App\Http\Controllers\TeachingScheduleController;

use App\Http\Controllers\PPDB\{
    PPDBController,
    PendaftarController,
    AdminSekolahController,
    AdminLPController
};


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Account Setting Routes
Route::get('/account/login', [App\Http\Controllers\AccountSettingController::class, 'login'])->name('account.login');
Route::post('/account/login', [App\Http\Controllers\AccountSettingController::class, 'authenticate'])->name('account.authenticate');
Route::middleware(['auth'])->group(function () {
    Route::get('/account/dashboard', [App\Http\Controllers\AccountSettingController::class, 'dashboard'])->name('account.dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Presensi Admin routes - authorization handled by controller
    Route::get('/presensi-admin/settings', [PresensiAdminController::class, 'settings'])->name('presensi_admin.settings');
    Route::post('/presensi-admin/settings', [PresensiAdminController::class, 'updateSettings'])->name('presensi_admin.updateSettings');
    Route::get('/presensi-admin', [PresensiAdminController::class, 'index'])->name('presensi_admin.index');
    Route::get('/presensi-admin/data', [PresensiAdminController::class, 'getData'])->name('presensi_admin.data');
    Route::get('/presensi-admin/summary', [PresensiAdminController::class, 'getSummary'])->name('presensi_admin.summary');
    Route::get('/presensi-admin/detail/{userId}', [PresensiAdminController::class, 'getDetail'])->name('presensi_admin.detail');
    Route::get('/presensi-admin/madrasah-detail/{madrasahId}', [PresensiAdminController::class, 'getMadrasahDetail'])->name('presensi_admin.madrasah_detail');
    Route::get('/presensi-admin/madrasah/{madrasahId}', [PresensiAdminController::class, 'showMadrasahDetail'])->name('presensi_admin.show_detail');
    Route::get('/presensi-admin/export', [PresensiAdminController::class, 'export'])->name('presensi_admin.export');
    Route::get('/presensi-admin/export-madrasah/{madrasahId}', [PresensiAdminController::class, 'exportMadrasah'])->name('presensi_admin.export_madrasah');
    Route::get('/presensi-admin/export-excel', [PresensiAdminController::class, 'exportExcel'])->name('presensi_admin.export_excel');
    Route::get('/presensi/rekap/pdf/{madrasahId}/{bulan}', [PresensiController::class, 'pdfRekap'])->name('presensi.pdf_rekap');

    // Teaching Progress Routes - Super Admin Only
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/teaching-progress', [App\Http\Controllers\TeachingProgressController::class, 'index'])->name('admin.teaching_progress');
        Route::get('/teaching-progress/madrasah/{madrasahId}/teachers', [App\Http\Controllers\TeachingProgressController::class, 'getMadrasahTeachers'])->name('admin.teaching_progress.teachers');
    });

    // Development History Routes - Super Admin Only
    Route::middleware(['role:super_admin,pengurus'])->group(function () {
        Route::get('/development-history', [DevelopmentHistoryController::class, 'index'])->name('development-history.index');
        Route::get('/development-history/sync', [DevelopmentHistoryController::class, 'syncMigrations'])->name('development-history.sync');
        Route::get('/development-history/export/{format}', [DevelopmentHistoryController::class, 'export'])->name('development-history.export');
        Route::post('/admin/run-commit-tracking', [DevelopmentHistoryController::class, 'runCommitTracking'])->name('admin.run-commit-tracking');
        Route::post('/admin/regenerate-documentation', [DevelopmentHistoryController::class, 'regenerateDocumentation'])->name('admin.regenerate-documentation');
        Route::get('/active-users', [App\Http\Controllers\ActiveUsersController::class, 'index'])->name('active-users.index');
    });

    // Teaching Schedules Routes
    Route::middleware(['role:super_admin,admin,pengurus,tenaga_pendidik'])->group(function () {
        Route::resource('teaching-schedules', App\Http\Controllers\TeachingScheduleController::class);
        Route::get('teaching-schedules/get-teachers/{schoolId}', [App\Http\Controllers\TeachingScheduleController::class, 'getTeachersBySchool'])->name('teaching-schedules.get-teachers');
        Route::get('teaching-schedules/import', [App\Http\Controllers\TeachingScheduleController::class, 'import'])->name('teaching-schedules.import');
        Route::post('teaching-schedules/import', [App\Http\Controllers\TeachingScheduleController::class, 'processImport'])->name('teaching-schedules.process-import');
        // Super admin and admin specific routes
        Route::get('teaching-schedules/school/{schoolId}/schedules', [App\Http\Controllers\TeachingScheduleController::class, 'showSchoolSchedules'])->name('teaching-schedules.school-schedules');
        Route::get('teaching-schedules/school/{schoolId}/classes', [App\Http\Controllers\TeachingScheduleController::class, 'showSchoolClasses'])->name('teaching-schedules.school-classes');
        Route::post('teaching-schedules/filter', [App\Http\Controllers\TeachingScheduleController::class, 'filter'])->name('teaching-schedules.filter');
    });

    // Teaching Attendances Routes
    Route::middleware(['role:tenaga_pendidik'])->group(function () {
        Route::get('/teaching-attendances', [App\Http\Controllers\TeachingAttendanceController::class, 'index'])->name('teaching-attendances.index');
        Route::post('/teaching-attendances', [App\Http\Controllers\TeachingAttendanceController::class, 'store'])->name('teaching-attendances.store');
        Route::post('/teaching-attendances/check-location', [App\Http\Controllers\TeachingAttendanceController::class, 'checkLocation'])->name('teaching-attendances.check-location');
    });

    // Fake Location Detection Routes - Super Admin Only
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/fake-location', [App\Http\Controllers\FakeLocationController::class, 'index'])->name('fake-location.index');
        Route::get('/admin/simfoni', [App\Http\Controllers\Admin\SimfoniAdminController::class, 'index'])->name('admin.simfoni.index');
        Route::get('/admin/simfoni/pdf/{id}', [App\Http\Controllers\Admin\SimfoniAdminController::class, 'pdfSimfoni'])->name('admin.simfoni.pdf');
    });

    // Chat Routes - Super Admin and Admin
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    });

    // Laporan Presensi Mingguan - Super Admin Only
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/admin/presensi_admin/laporan-mingguan', [PresensiAdminController::class, 'laporanMingguan'])->name('presensi_admin.laporan_mingguan');
    });

    // Super Admin - consolidated MGMP dashboard and user management
    Route::middleware(['auth','role:super_admin,pengurus'])->group(function () {
        Route::get('/admin/mgmp-dashboard', [App\Http\Controllers\MGMPController::class, 'superAdminDashboard'])->name('admin.mgmp_dashboard');
        Route::get('/admin/create-mgmp-user', [App\Http\Controllers\MGMPController::class, 'createMgmpUser'])->name('admin.create_mgmp_user');
        Route::post('/admin/create-mgmp-user', [App\Http\Controllers\MGMPController::class, 'storeMgmpUser'])->name('admin.store_mgmp_user');
    });
});

// Admin face enrollment management (only admin and super_admin)
Route::prefix('admin')->middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::get('/face-enrollment', [App\Http\Controllers\Admin\FaceEnrollmentController::class, 'index'])->name('face.enrollment.list');
    Route::get('/face-enrollment/{userId}', [App\Http\Controllers\Admin\FaceEnrollmentController::class, 'show'])->name('face.enrollment');
    Route::delete('/face-enrollment/{userId}', [App\Http\Controllers\Admin\FaceEnrollmentController::class, 'destroy'])->name('face.enrollment.destroy');
    Route::post('/face-enrollment/{userId}/toggle-verification', [App\Http\Controllers\Admin\FaceEnrollmentController::class, 'toggleVerification'])->name('face.enrollment.toggle-verification');
});

Auth::routes(['verify' => true]);

// CSRF token endpoint for JavaScript updates
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Foto routes for accessing images from nuist folder
Route::get('/foto/{type}/{id}', [App\Http\Controllers\FotoController::class, 'show'])->name('foto.show');

// Clear cache endpoint (accessible without authentication)
Route::post('/clear-cache', function () {
    try {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        return response()->json([
            'success' => true,
            'message' => 'Cache berhasil dibersihkan'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
        ], 500);
    }
})->name('clear-cache');

// Email Verification Routes
Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');

// setelah login langsung ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('root');

// Landing page - public access
Route::get('/landing', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');
Route::get('/sekolah', [App\Http\Controllers\LandingController::class, 'sekolah'])->name('landing.sekolah');
Route::get('/sekolah/{id}', [App\Http\Controllers\LandingController::class, 'sekolahDetail'])->name('landing.sekolah.detail');
Route::get('/tentang', [App\Http\Controllers\LandingController::class, 'tentang'])->name('landing.tentang');
Route::get('/kontak', [App\Http\Controllers\LandingController::class, 'kontak'])->name('landing.kontak');

// Contact form submission
Route::post('/sekolah/{id}/contact', [App\Http\Controllers\LandingController::class, 'sendContactMessage'])->name('landing.sekolah.contact');
Route::post('/kontak', [App\Http\Controllers\LandingController::class, 'sendContactMessageGeneral'])->name('landing.kontak.submit');

// Jika akses link nuist.id/index maka akan tertuju halaman login
Route::get('/index', function () {
    return redirect()->route('login');
})->name('index-redirect');

// dashboard route - accessible by super_admin, admin, tenaga_pendidik
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth']);

// Mobile routes for all authenticated users
Route::middleware(['auth'])->prefix('mobile')->name('mobile.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Mobile\Dashboard\DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/calendar-data', [App\Http\Controllers\Mobile\Dashboard\DashboardController::class, 'getCalendarData'])->name('dashboard.calendar-data');
    Route::get('/dashboard/stats-data', [App\Http\Controllers\Mobile\Dashboard\DashboardController::class, 'getStatsData'])->name('dashboard.stats-data');

    // Pengurus routes
    Route::prefix('pengurus')->name('pengurus.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'dashboard'])->name('dashboard');
        Route::get('/kelengkapan-data', [App\Http\Controllers\Mobile\Pengurus\SekolahController::class, 'kelengkapanData'])->name('kelengkapan-data');
        Route::get('/sekolah', [App\Http\Controllers\Mobile\Pengurus\SekolahController::class, 'index'])->name('sekolah');
        Route::get('/sekolah/{id}', [App\Http\Controllers\Mobile\Pengurus\SekolahController::class, 'show'])->name('sekolah.show');
        Route::get('/sekolah/{id}/monthly-attendance-data', [App\Http\Controllers\Mobile\Pengurus\SekolahController::class, 'getMonthlyAttendanceData'])->name('sekolah.monthly-attendance-data');
        Route::get('/sekolah/{id}/monthly-teaching-attendance-data', [App\Http\Controllers\Mobile\Pengurus\SekolahController::class, 'getMonthlyTeachingAttendanceData'])->name('sekolah.monthly-teaching-attendance-data');
        Route::get('/barcode', [App\Http\Controllers\Mobile\Pengurus\BarcodeController::class, 'index'])->name('barcode');
        Route::get('/data-presensi-mengajar', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'dataPresensiMengajar'])->name('data-presensi-mengajar');
        Route::get('/presensi-kehadiran', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'presensiKehadiran'])->name('presensi-kehadiran');
        Route::get('/uppm', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'uppm'])->name('uppm');
        Route::get('/data-sekolah', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'dataSekolah'])->name('data-sekolah');
        Route::get('/riwayat-pengembangan', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'riwayatPengembangan'])->name('riwayat-pengembangan');
        Route::get('/pengguna-aktif', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'penggunaAktif'])->name('pengguna-aktif');
        Route::get('/profile', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'profile'])->name('profile');
        Route::get('/ubah-password', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'ubahPassword'])->name('ubah-password');
        Route::post('/ubah-password', [App\Http\Controllers\Mobile\Pengurus\PengurusController::class, 'updatePassword'])->name('update-password');
    });

    // Presensi
    Route::get('/presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'presensi'])->name('presensi');
    // Backwards-compatible route names used by some mobile views
    Route::get('/data-presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'presensi'])->name('data-presensi');
    Route::post('/presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'storePresensi'])->name('presensi.store');

    // Selfie Presensi (separate page)
    Route::get('/selfie-presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'selfiePresensi'])->name('selfie-presensi');
    Route::post('/selfie-presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'storeSelfiePresensi'])->name('selfie-presensi.store');
    Route::get('/riwayat-presensi', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'riwayatPresensi'])->name('riwayat-presensi');
    Route::get('/riwayat-presensi-alpha', [App\Http\Controllers\Mobile\Presensi\PresensiController::class, 'riwayatPresensiAlpha'])->name('riwayat-presensi-alpha');

    // Face enrollment (mobile)
    Route::get('/face-enrollment', function () { return view('mobile.face-enrollment'); })->name('face.enrollment');
    Route::post('/face-enroll', [App\Http\Controllers\Api\FaceController::class, 'enroll'])->name('face.enroll');

    // Jadwal
    Route::get('/jadwal', [App\Http\Controllers\Mobile\Jadwal\JadwalController::class, 'jadwal'])->name('jadwal');
    Route::get('/data-jadwal', [App\Http\Controllers\Mobile\Jadwal\JadwalController::class, 'jadwal'])->name('data-jadwal');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'profile'])->name('profile');
    Route::post('/profile/update-profile', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'updateProfile'])->name('profile.update-profile');
    Route::post('/profile/update-avatar', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'updateAvatar'])->name('profile.update-avatar');
    Route::post('/profile/update-password', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/ubah-akun', [App\Http\Controllers\Mobile\Profile\ProfileController::class, 'ubahAkun'])->name('ubah-akun');

    // Izin
    Route::post('/izin', [App\Http\Controllers\Mobile\Izin\IzinController::class, 'storeIzin'])->name('izin.store');
    Route::get('/izin', [App\Http\Controllers\Mobile\Izin\IzinController::class, 'izin'])->name('izin');
    Route::get('/kelola-izin', [App\Http\Controllers\Mobile\Izin\IzinController::class, 'kelolaIzin'])->name('kelola-izin');

    // Laporan
    Route::get('/laporan', [App\Http\Controllers\Mobile\Laporan\LaporanController::class, 'laporan'])->name('laporan');
    // Mobile laporan presensi mengajar (riwayat)
    Route::get('/laporan/mengajar', [App\Http\Controllers\Mobile\Laporan\LaporanController::class, 'laporanMengajar'])->name('laporan.mengajar');
    // Mobile presensi mengajar (mobile-optimized view)
    Route::get('/teaching-attendances', [App\Http\Controllers\Mobile\Laporan\LaporanController::class, 'teachingAttendances'])->name('teaching-attendances');

    // Laporan Akhir Tahun Kepala Sekolah
    Route::resource('laporan-akhir-tahun', App\Http\Controllers\Mobile\LaporanAkhirTahunKepalaSekolahController::class);

    // Menu Talenta
    Route::get('/talenta', [App\Http\Controllers\Mobile\TalentaController::class, 'index'])->name('talenta.index');
    Route::get('/talenta/create', [App\Http\Controllers\Mobile\TalentaController::class, 'create'])->name('talenta.create');
    Route::post('/talenta', [App\Http\Controllers\Mobile\TalentaController::class, 'store'])->name('talenta.store');
    Route::get('/talenta/{talenta}', [App\Http\Controllers\Mobile\TalentaController::class, 'show'])->name('talenta.show');
    Route::get('/talenta/{talenta}/edit', [App\Http\Controllers\Mobile\TalentaController::class, 'edit'])->name('talenta.edit');
    Route::put('/talenta/{talenta}', [App\Http\Controllers\Mobile\TalentaController::class, 'update'])->name('talenta.update');
    Route::delete('/talenta/{talenta}', [App\Http\Controllers\Mobile\TalentaController::class, 'destroy'])->name('talenta.destroy');
    Route::get('/talenta/file/{filename}', [App\Http\Controllers\Mobile\TalentaController::class, 'lihatFile'])->name('talenta.file');

    // Monitoring (kepala madrasah)
    Route::get('/monitor-presensi', [App\Http\Controllers\Mobile\Monitoring\MonitoringController::class, 'monitorPresensi'])->name('monitor-presensi');
    Route::get('/monitor-map', [App\Http\Controllers\Mobile\Monitoring\MonitoringController::class, 'monitorMap'])->name('monitor-map');
    Route::get('/monitor-jadwal-mengajar', [App\Http\Controllers\Mobile\Monitoring\MonitoringController::class, 'monitorJadwalMengajar'])->name('monitor-jadwal-mengajar');

    // Notification routes
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');

    // Simfoni (Data SK Tenaga Pendidik)
    Route::get('/simfoni', [App\Http\Controllers\Mobile\Simfoni\SimfoniController::class, 'show'])->name('simfoni.show');
    Route::post('/simfoni', [App\Http\Controllers\Mobile\Simfoni\SimfoniController::class, 'store'])->name('simfoni.store');
});

// Instumen Talenta routes - monitoring, upload and evaluation pages
Route::middleware(['auth'])->group(function () {
    Route::prefix('instumen-talenta')->name('instumen-talenta.')->group(function () {
        Route::get('/kelengkapan', [App\Http\Controllers\InstumenTalentaController::class, 'kelengkapan'])->name('kelengkapan');
        Route::get('/upload-tugas', [App\Http\Controllers\InstumenTalentaController::class, 'uploadTugas'])->name('upload-tugas');
        Route::get('/instrumen-penilaian', [App\Http\Controllers\InstumenTalentaController::class, 'instrumenPenilaian'])->name('instrumen-penilaian');
        Route::get('/nilai-tugas', [App\Http\Controllers\InstumenTalentaController::class, 'nilaiTugas'])->name('nilai-tugas');
        Route::get('/upload-sertifikat', [App\Http\Controllers\InstumenTalentaController::class, 'uploadSertifikat'])->name('upload-sertifikat');
    });
});

// panduan route - accessible by super_admin and pengurus
Route::get('/panduan', [PanduanController::class, 'index'])->name('panduan.index')->middleware(['auth', 'role:super_admin,pengurus']);

Route::prefix('masterdata')->middleware(['auth', 'role:super_admin,admin,pengurus'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');
    Route::post('/admin/import', [AdminController::class, 'import'])->name('admin.import');
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{admin}', [AdminController::class, 'destroy'])->name('admin.destroy');

    Route::get('/pengurus', [App\Http\Controllers\PengurusController::class, 'index'])->name('pengurus.index');
    Route::post('/pengurus/store', [App\Http\Controllers\PengurusController::class, 'store'])->name('pengurus.store');
    Route::put('/pengurus/update/{id}', [App\Http\Controllers\PengurusController::class, 'update'])->name('pengurus.update');
    Route::delete('/pengurus/{pengurus}', [App\Http\Controllers\PengurusController::class, 'destroy'])->name('pengurus.destroy');

    Route::get('/madrasah', [App\Http\Controllers\MadrasahController::class, 'index'])->name('madrasah.index');
    Route::get('/tenaga-pendidik', [App\Http\Controllers\TenagaPendidikController::class, 'index'])->name('tenaga-pendidik.index');

    // Yayasan routes
    Route::get('/yayasan', [App\Http\Controllers\YayasanController::class, 'index'])->name('yayasan.index');
    Route::post('/yayasan/store', [App\Http\Controllers\YayasanController::class, 'store'])->name('yayasan.store');
    Route::put('/yayasan/update/{id}', [App\Http\Controllers\YayasanController::class, 'update'])->name('yayasan.update');
    Route::delete('/yayasan/destroy/{id}', [App\Http\Controllers\YayasanController::class, 'destroy'])->name('yayasan.destroy');
});

Route::prefix('admin-masterdata')->middleware(['auth', 'role:super_admin,pengurus'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin_masterdata.admin.index');
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin_masterdata.admin.store');
    Route::post('/admin/import', [AdminController::class, 'import'])->name('admin_masterdata.admin.import');
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin_masterdata.admin.update');
    Route::delete('/admin/{admin}', [AdminController::class, 'destroy'])->name('admin_masterdata.admin.destroy');

    Route::get('/madrasah', [App\Http\Controllers\MadrasahController::class, 'index'])->name('admin_masterdata.madrasah.index');
    Route::post('/madrasah/store', [App\Http\Controllers\MadrasahController::class, 'store'])->name('admin_masterdata.madrasah.store');
    Route::put('/madrasah/update/{id}', [App\Http\Controllers\MadrasahController::class, 'update'])->name('admin_masterdata.madrasah.update');
    Route::delete('/madrasah/destroy/{id}', [App\Http\Controllers\MadrasahController::class, 'destroy'])->name('admin_masterdata.madrasah.destroy');
    Route::post('/madrasah/import', [App\Http\Controllers\MadrasahController::class, 'import'])->name('admin_masterdata.madrasah.import');

    Route::get('/data-madrasah', [App\Http\Controllers\DataMadrasahController::class, 'index'])->name('admin.data_madrasah');
    Route::get('/data-madrasah/export', [App\Http\Controllers\DataMadrasahController::class, 'export'])->name('admin.data_madrasah.export');

    Route::get('/tenaga-pendidik', [App\Http\Controllers\TenagaPendidikController::class, 'index'])->name('admin_masterdata.tenaga-pendidik.index');
    Route::post('/tenaga-pendidik/store', [App\Http\Controllers\TenagaPendidikController::class, 'store'])->name('admin_masterdata.tenaga-pendidik.store');
    Route::put('/tenaga-pendidik/update/{id}', [App\Http\Controllers\TenagaPendidikController::class, 'update'])->name('admin_masterdata.tenaga-pendidik.update');
    Route::delete('/tenaga-pendidik/destroy/{id}', [App\Http\Controllers\TenagaPendidikController::class, 'destroy'])->name('admin_masterdata.tenaga-pendidik.destroy');
    Route::post('/tenaga-pendidik/import', [App\Http\Controllers\TenagaPendidikController::class, 'import'])->name('admin_masterdata.tenaga-pendidik.import');

    Route::get('/status-kepegawaian', [App\Http\Controllers\StatusKepegawaianController::class, 'index'])->name('admin_masterdata.status-kepegawaian.index');
    Route::post('/status-kepegawaian/store', [App\Http\Controllers\StatusKepegawaianController::class, 'store'])->name('admin_masterdata.status-kepegawaian.store');
    Route::put('/status-kepegawaian/update/{id}', [App\Http\Controllers\StatusKepegawaianController::class, 'update'])->name('admin_masterdata.status-kepegawaian.update');
    Route::delete('/status-kepegawaian/destroy/{id}', [App\Http\Controllers\StatusKepegawaianController::class, 'destroy'])->name('admin_masterdata.status-kepegawaian.destroy');
    Route::post('/status-kepegawaian/import', [App\Http\Controllers\StatusKepegawaianController::class, 'import'])->name('admin_masterdata.status-kepegawaian.import');

    Route::get('/tahun-pelajaran', [App\Http\Controllers\TahunPelajaranController::class, 'index'])->name('admin_masterdata.tahun-pelajaran.index');
    Route::post('/tahun-pelajaran/store', [App\Http\Controllers\TahunPelajaranController::class, 'store'])->name('admin_masterdata.tahun-pelajaran.store');
    Route::put('/tahun-pelajaran/update/{id}', [App\Http\Controllers\TahunPelajaranController::class, 'update'])->name('admin_masterdata.tahun-pelajaran.update');
    Route::delete('/tahun-pelajaran/destroy/{id}', [App\Http\Controllers\TahunPelajaranController::class, 'destroy'])->name('admin_masterdata.tahun-pelajaran.destroy');
    Route::post('/tahun-pelajaran/import', [App\Http\Controllers\TahunPelajaranController::class, 'import'])->name('admin_masterdata.tahun-pelajaran.import');

    Route::get('/broadcast-numbers', [App\Http\Controllers\BroadcastNumberController::class, 'index'])->name('admin_masterdata.broadcast-numbers.index');
    Route::post('/broadcast-numbers/store', [App\Http\Controllers\BroadcastNumberController::class, 'store'])->name('admin_masterdata.broadcast-numbers.store');
    Route::put('/broadcast-numbers/update/{id}', [App\Http\Controllers\BroadcastNumberController::class, 'update'])->name('admin_masterdata.broadcast-numbers.update');
    Route::delete('/broadcast-numbers/destroy/{id}', [App\Http\Controllers\BroadcastNumberController::class, 'destroy'])->name('admin_masterdata.broadcast-numbers.destroy');
    Route::post('/broadcast-numbers/send-broadcast', [App\Http\Controllers\BroadcastNumberController::class, 'sendBroadcast'])->name('admin_masterdata.broadcast-numbers.send-broadcast');
    Route::post('/broadcast-numbers/test-connection', [App\Http\Controllers\BroadcastNumberController::class, 'testConnection'])->name('admin_masterdata.broadcast-numbers.test-connection');
});

Route::prefix('masterdata')->middleware(['auth', 'role:super_admin,admin,pengurus'])->group(function () {
    // Madrasah routes
    Route::get('/madrasah', [MadrasahController::class, 'index'])->name('madrasah.index');
    Route::post('/madrasah/store', [MadrasahController::class, 'store'])->name('madrasah.store');
    Route::put('/madrasah/update/{id}', [MadrasahController::class, 'update'])->name('madrasah.update');
    Route::delete('/madrasah/destroy/{id}', [MadrasahController::class, 'destroy'])->name('madrasah.destroy');
    Route::post('/madrasah/import', [MadrasahController::class, 'import'])->name('madrasah.import');

    // Profile Madrasah routes - restricted to super_admin and pengurus (controller handles authorization)
    Route::get('/madrasah/profile', [MadrasahController::class, 'profile'])->name('madrasah.profile');
    Route::get('/madrasah/{id}/detail', [MadrasahController::class, 'detail'])->name('madrasah.detail');
});

Route::prefix('masterdata')->middleware(['auth', 'role:super_admin,admin,pengurus'])->group(function () {
    Route::get('/tenaga-pendidik', [TenagaPendidikController::class, 'index'])->name('tenaga-pendidik.index');
    Route::post('/tenaga-pendidik/store', [TenagaPendidikController::class, 'store'])->name('tenaga-pendidik.store');
    Route::put('/tenaga-pendidik/update/{id}', [TenagaPendidikController::class, 'update'])->name('tenaga-pendidik.update');
    Route::delete('/tenaga-pendidik/destroy/{id}', [TenagaPendidikController::class, 'destroy'])->name('tenaga-pendidik.destroy');
    Route::post('/tenaga-pendidik/import', [TenagaPendidikController::class, 'import'])->name('tenaga-pendidik.import');

    // Status Kepegawaian routes
    Route::get('/status-kepegawaian', [StatusKepegawaianController::class, 'index'])->name('status-kepegawaian.index');
    Route::post('/status-kepegawaian/store', [StatusKepegawaianController::class, 'store'])->name('status-kepegawaian.store');
    Route::put('/status-kepegawaian/update/{id}', [StatusKepegawaianController::class, 'update'])->name('status-kepegawaian.update');
    Route::delete('/status-kepegawaian/destroy/{id}', [StatusKepegawaianController::class, 'destroy'])->name('status-kepegawaian.destroy');
    Route::post('/status-kepegawaian/import', [StatusKepegawaianController::class, 'import'])->name('status-kepegawaian.import');

    // Tahun Pelajaran routes
    Route::get('/tahun-pelajaran', [App\Http\Controllers\TahunPelajaranController::class, 'index'])->name('tahun-pelajaran.index');
    Route::post('/tahun-pelajaran/store', [App\Http\Controllers\TahunPelajaranController::class, 'store'])->name('tahun-pelajaran.store');
    Route::put('/tahun-pelajaran/update/{id}', [App\Http\Controllers\TahunPelajaranController::class, 'update'])->name('tahun-pelajaran.update');
    Route::delete('/tahun-pelajaran/destroy/{id}', [App\Http\Controllers\TahunPelajaranController::class, 'destroy'])->name('tahun-pelajaran.destroy');
    Route::post('/tahun-pelajaran/import', [App\Http\Controllers\TahunPelajaranController::class, 'import'])->name('tahun-pelajaran.import');
});

// customers route
Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.list');

// Presensi Routes - accessible by tenaga_pendidik, admin, super_admin
Route::middleware(['auth'])->group(function () {
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create')->middleware('role:tenaga_pendidik,admin,super_admin');
    Route::post('/presensi/store', [PresensiController::class, 'store'])->name('presensi.store')->middleware('role:tenaga_pendidik,admin,super_admin');
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan'])->name('presensi.laporan');
});

Route::prefix('izin')->middleware(['auth'])->group(function () {
    Route::middleware(['role:tenaga_pendidik'])->group(function () {
        Route::get('/create', [IzinController::class, 'create'])->name('izin.create');
        Route::post('/store', [IzinController::class, 'store'])->name('izin.store');
    });

    Route::middleware(['role:admin,super_admin,pengurus,tenaga_pendidik'])->group(function () {
        Route::get('/', [IzinController::class, 'index'])->name('izin.index');

        // Presensi-based izin
        Route::post('/{presensi}/approve', [IzinController::class, 'approve'])->name('izin.approve');
        Route::post('/{presensi}/reject', [IzinController::class, 'reject'])->name('izin.reject');

        // Izin tugas luar (table izins)
        Route::post('/model/{izin}/approve', [IzinController::class, 'approveIzinModel'])->name('izin.model.approve');
        Route::post('/model/{izin}/reject', [IzinController::class, 'rejectIzinModel'])->name('izin.model.reject');

        // Batch approve/reject routes for kepala madrasah/sekolah
        Route::post('/approve-all', [IzinController::class, 'approveAll'])->name('izin.approve.all');
        Route::post('/reject-all', [IzinController::class, 'rejectAll'])->name('izin.reject.all');
    });
});

// Direct route for /izin/store
Route::post('/izin/store', [IzinController::class, 'store'])->middleware(['auth', 'role:tenaga_pendidik'])->name('izin.store');

// Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// Sitemap route
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

use App\Http\Controllers\Auth\LoginController;

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// Data Sekolah Routes
Route::middleware(['auth', 'role:super_admin,admin,pengurus'])->prefix('data-sekolah')->name('data-sekolah.')->group(function () {
    Route::get('/siswa', [App\Http\Controllers\DataSekolahController::class, 'siswa'])->name('siswa');
    Route::get('/guru', [App\Http\Controllers\DataSekolahController::class, 'guru'])->name('guru');
    Route::post('/update-siswa/{madrasahId}', [App\Http\Controllers\DataSekolahController::class, 'updateSiswa'])->name('update-siswa');
    Route::post('/update-guru/{madrasahId}', [App\Http\Controllers\DataSekolahController::class, 'updateGuru'])->name('update-guru');
});

// Check tagihan route outside middleware group to avoid authentication issues
Route::get('/uppm/pembayaran/check-tagihan', [App\Http\Controllers\PembayaranController::class, 'checkTagihan'])->name('pembayaran.check-tagihan');

// Payment result route outside middleware to handle Midtrans callbacks
Route::post('/uppm/pembayaran/midtrans/result', [App\Http\Controllers\PembayaranController::class, 'paymentResult'])->name('uppm.pembayaran.midtrans.result');
Route::post('/uppm/pembayaran/success', [App\Http\Controllers\PembayaranController::class, 'paymentSuccess'])->name('uppm.pembayaran.success');
Route::post('/uppm/pembayaran/check-status', [App\Http\Controllers\PembayaranController::class, 'checkPaymentStatus'])->name('uppm.pembayaran.check-status');


// UPPM Routes
Route::middleware(['auth', 'role:super_admin,pengurus'])->prefix('uppm')->name('uppm.')->group(function () {
    Route::get('/', [App\Http\Controllers\UppmController::class, 'index'])->name('index');
    Route::get('/data-sekolah', [App\Http\Controllers\UppmController::class, 'dataSekolah'])->name('data-sekolah');
    Route::get('/perhitungan-iuran', [App\Http\Controllers\UppmController::class, 'perhitunganIuran'])->name('perhitungan-iuran');
    Route::get('/tagihan', [App\Http\Controllers\UppmController::class, 'tagihan'])->name('tagihan');
    Route::get('/invoice', [App\Http\Controllers\UppmController::class, 'invoice'])->name('invoice');
    Route::get('/invoice/download', [App\Http\Controllers\UppmController::class, 'downloadInvoice'])->name('invoice.download');
    Route::get('/pengaturan', [App\Http\Controllers\UppmController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan', [App\Http\Controllers\UppmController::class, 'storePengaturan'])->name('pengaturan.store');
    Route::put('/pengaturan/{id}', [App\Http\Controllers\UppmController::class, 'updatePengaturan'])->name('pengaturan.update');
    Route::delete('/pengaturan/{id}', [App\Http\Controllers\UppmController::class, 'destroyPengaturan'])->name('pengaturan.destroy');
    Route::post('/store-tagihan', [App\Http\Controllers\UppmController::class, 'storeTagihan'])->name('store-tagihan');

    // Pembayaran Routes
    Route::get('/pembayaran', [App\Http\Controllers\PembayaranController::class, 'index'])->name('pembayaran');
    Route::get('/pembayaran/{madrasah_id}', [App\Http\Controllers\PembayaranController::class, 'detail'])->name('pembayaran.detail');
    Route::post('/pembayaran/cash', [App\Http\Controllers\PembayaranController::class, 'pembayaranCash'])->name('pembayaran.cash');
    Route::post('/pembayaran/midtrans', [App\Http\Controllers\PembayaranController::class, 'pembayaranMidtrans'])->name('pembayaran.midtrans');
});

// Instrument Talenta Routes
Route::prefix('instumen-talenta')->name('instumen-talenta.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\InstumenTalentaController::class, 'index'])->name('index');

    Route::get('/peserta', function () {
        return view('instumen-talenta.peserta');
    })->name('peserta');

    Route::get('/pemateri', function () {
        return view('instumen-talenta.pemateri');
    })->name('pemateri');

    Route::get('/fasilitator', function () {
        return view('instumen-talenta.fasilitator');
    })->name('fasilitator');

    Route::get('/admin', function () {
        return view('instumen-talenta.admin');
    })->name('admin')->middleware(['role:super_admin,admin,pemateri,fasilitator']);

    // Input Data Routes
    Route::get('/input-peserta', [App\Http\Controllers\InstumenTalentaController::class, 'inputPeserta'])->name('input-peserta')->middleware(['role:super_admin,admin']);
    Route::post('/input-peserta', [App\Http\Controllers\InstumenTalentaController::class, 'storePeserta'])->name('store-peserta')->middleware(['role:super_admin,admin']);
    Route::post('/store-kelompok', [App\Http\Controllers\InstumenTalentaController::class, 'storeKelompok'])->name('store-kelompok')->middleware(['role:super_admin,admin']);

    Route::get('/input-pemateri', [App\Http\Controllers\InstumenTalentaController::class, 'inputPemateri'])->name('input-pemateri')->middleware(['role:super_admin,admin']);
    Route::post('/input-pemateri', [App\Http\Controllers\InstumenTalentaController::class, 'storePemateri'])->name('store-pemateri')->middleware(['role:super_admin,admin']);

    Route::get('/input-fasilitator', [App\Http\Controllers\InstumenTalentaController::class, 'inputFasilitator'])->name('input-fasilitator')->middleware(['role:super_admin,admin']);
    Route::post('/input-fasilitator', [App\Http\Controllers\InstumenTalentaController::class, 'storeFasilitator'])->name('store-fasilitator')->middleware(['role:super_admin,admin']);

    Route::get('/input-materi', [App\Http\Controllers\InstumenTalentaController::class, 'inputMateri'])->name('input-materi')->middleware(['role:super_admin,admin']);
    Route::post('/input-materi', [App\Http\Controllers\InstumenTalentaController::class, 'storeMateri'])->name('store-materi')->middleware(['role:super_admin,admin']);

    Route::get('/input-layanan-teknis', [App\Http\Controllers\InstumenTalentaController::class, 'inputLayananTeknis'])->name('input-layanan-teknis')->middleware(['role:super_admin,admin']);
    Route::post('/input-layanan-teknis', [App\Http\Controllers\InstumenTalentaController::class, 'storeLayananTeknis'])->name('store-layanan-teknis')->middleware(['role:super_admin,admin']);

    // Penilaian Routes
    Route::get('/penilaian-pemateri', [App\Http\Controllers\InstumenTalentaController::class, 'penilaianPemateri'])->name('penilaian-pemateri');
    Route::get('/penilaian-fasilitator', [App\Http\Controllers\InstumenTalentaController::class, 'penilaianFasilitator'])->name('penilaian-fasilitator');
    Route::get('/penilaian-teknis', [App\Http\Controllers\InstumenTalentaController::class, 'penilaianTeknis'])->name('penilaian-teknis');

    // Create User for Pemateri
    Route::post('/create-user-pemateri', [App\Http\Controllers\InstumenTalentaController::class, 'createUserForPemateri'])->name('create-user-pemateri')->middleware(['role:super_admin,admin']);

    // Create User for Fasilitator
    Route::post('/create-user-fasilitator', [App\Http\Controllers\InstumenTalentaController::class, 'createUserForFasilitator'])->name('create-user-fasilitator')->middleware(['role:super_admin,admin']);
});

// Talenta Routes
Route::get('/talenta/login', [App\Http\Controllers\TalentaController::class, 'login'])->name('talenta.login');
Route::post('/talenta/login', [App\Http\Controllers\TalentaController::class, 'authenticate'])->name('talenta.authenticate');
Route::middleware(['auth'])->group(function () {
    Route::get('/talenta/dashboard', [App\Http\Controllers\TalentaController::class, 'dashboard'])->name('talenta.dashboard');
    Route::get('/talenta/data', [App\Http\Controllers\TalentaController::class, 'data'])->name('talenta.data');
    Route::get('/talenta/instrumen-penilaian', [App\Http\Controllers\TalentaController::class, 'instrumenPenilaian'])->name('talenta.instrumen-penilaian');

    // Instrumen Penilaian Routes - Save and Get
    Route::post('/talenta/penilaian-trainer', [App\Http\Controllers\TalentaController::class, 'simpanPenilaianTrainer'])->name('talenta.penilaian-trainer.simpan');
    Route::get('/talenta/penilaian-trainer', [App\Http\Controllers\TalentaController::class, 'getPenilaianTrainer'])->name('talenta.penilaian-trainer.get');
    Route::post('/talenta/penilaian-fasilitator', [App\Http\Controllers\TalentaController::class, 'simpanPenilaianFasilitator'])->name('talenta.penilaian-fasilitator.simpan');
    Route::get('/talenta/penilaian-fasilitator', [App\Http\Controllers\TalentaController::class, 'getPenilaianFasilitator'])->name('talenta.penilaian-fasilitator.get');
    Route::post('/talenta/penilaian-teknis', [App\Http\Controllers\TalentaController::class, 'simpanPenilaianTeknis'])->name('talenta.penilaian-teknis.simpan');
    Route::get('/talenta/penilaian-teknis', [App\Http\Controllers\TalentaController::class, 'getPenilaianTeknis'])->name('talenta.penilaian-teknis.get');
    Route::post('/talenta/penilaian-peserta', [App\Http\Controllers\TalentaController::class, 'simpanPenilaianPeserta'])->name('talenta.penilaian-peserta.simpan');
    Route::get('/talenta/penilaian-peserta', [App\Http\Controllers\TalentaController::class, 'getPenilaianPeserta'])->name('talenta.penilaian-peserta.get');

    Route::get('/talenta/tugas-level-1', [App\Http\Controllers\TalentaController::class, 'tugasLevel1'])->name('talenta.tugas-level-1');
    Route::get('/talenta/penilaian-tugas', [App\Http\Controllers\TalentaController::class, 'penilaianTugas'])->name('talenta.penilaian-tugas');
    Route::post('/talenta/penilaian-tugas/nilai', [App\Http\Controllers\TalentaController::class, 'simpanNilaiTugas'])->name('talenta.simpan-nilai-tugas');
    Route::post('/talenta/logout', [App\Http\Controllers\TalentaController::class, 'logout'])->name('talenta.logout');
});
// Move POST route outside auth middleware to handle AJAX requests properly
Route::post('/talenta/tugas-level-1', [App\Http\Controllers\TalentaController::class, 'simpanTugasLevel1'])->name('talenta.tugas-level-1.simpan');
// Reset / delete uploaded tugas file (allow form POST or AJAX)
Route::post('/talenta/tugas-level-1/reset', [App\Http\Controllers\TalentaController::class, 'resetTugasLevel1'])->name('talenta.tugas-level-1.reset');

// Soal management (admin)
Route::middleware(['auth','role:super_admin,admin'])->group(function () {
    Route::get('/talenta/soals', [App\Http\Controllers\TalentaController::class, 'soalsIndex'])->name('talenta.soals.index');
    Route::post('/talenta/soals', [App\Http\Controllers\TalentaController::class, 'soalsStore'])->name('talenta.soals.store');
    Route::get('/talenta/soals/{soal}/edit', [App\Http\Controllers\TalentaController::class, 'soalsEdit'])->name('talenta.soals.edit');
    Route::put('/talenta/soals/{soal}', [App\Http\Controllers\TalentaController::class, 'soalsUpdate'])->name('talenta.soals.update');
    Route::delete('/talenta/soals/{soal}', [App\Http\Controllers\TalentaController::class, 'soalsDestroy'])->name('talenta.soals.destroy');
});

// MGMP Routes - Protected by auth and mgmp role
Route::middleware(['auth', 'role:super_admin,admin,pengurus,mgmp'])->prefix('mgmp')->name('mgmp.')->group(function () {
    // Landing page for MGMP (public but inside mgmp namespace)
    Route::get('/', [App\Http\Controllers\MGMPController::class, 'index'])->name('index');

    // Protected MGMP Dashboard routes (for logged in users with mgmp role)
    Route::get('/dashboard', [App\Http\Controllers\MGMPController::class, 'dashboard'])->name('dashboard');
    Route::get('/data-anggota', [App\Http\Controllers\MGMPController::class, 'dataAnggota'])->name('data-anggota');
    Route::get('/data-mgmp', [App\Http\Controllers\MGMPController::class, 'manage'])->name('data-mgmp');
    Route::get('/laporan', [App\Http\Controllers\MGMPController::class, 'laporan'])->name('laporan');

    // CRUD routes for MGMP (store, update, destroy, import)
    Route::post('/', [App\Http\Controllers\MGMPController::class, 'store'])->name('store');
    Route::put('/{id}', [App\Http\Controllers\MGMPController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\MGMPController::class, 'destroy'])->name('destroy');
    Route::post('/import', [App\Http\Controllers\MGMPController::class, 'import'])->name('import');

    // Member management routes
    Route::post('/store-member', [App\Http\Controllers\MGMPController::class, 'storeMember'])->name('store-member');

    // Academica proposals page and upload
    Route::get('/academica', [App\Http\Controllers\MGMPController::class, 'academica'])->name('academica');
    Route::post('/academica/upload', [App\Http\Controllers\MGMPController::class, 'uploadAcademica'])->name('academica.upload');

    // Logout route for MGMP
    Route::post('/logout', [App\Http\Controllers\MGMPController::class, 'logout'])->name('logout');
});

// Public MGMP landing page (no auth required)
Route::get('/mgmp', [App\Http\Controllers\MGMPController::class, 'index'])->name('mgmp.public');

// fallback, jangan ganggu dashboard & lainnya
Route::fallback([App\Http\Controllers\HomeController::class, 'index'])->name('index');
// App Settings Routes - Super Admin Only
Route::middleware(['role:super_admin'])->group(function () {
    Route::get('/app-settings', [App\Http\Controllers\AppSettingsController::class, 'index'])->name('app-settings.index');
    Route::put('/app-settings', [App\Http\Controllers\AppSettingsController::class, 'update'])->name('app-settings.update');
    Route::post('/app-settings/update-version', [App\Http\Controllers\AppSettingsController::class, 'updateVersion'])->name('app-settings.update-version');
    Route::post('/app-settings/check-updates', [App\Http\Controllers\AppSettingsController::class, 'checkForUpdates'])->name('app-settings.check-updates');
    Route::post('/app-settings/turn-off-debug', [App\Http\Controllers\AppSettingsController::class, 'turnOffDebug'])->name('app-settings.turn-off-debug');
});

// HALAMAN UMUM
Route::prefix('ppdb')->group(function () {
    Route::get('/', [PPDBController::class, 'index'])->name('ppdb.index');
    // Place explicit routes BEFORE the parameterized /{slug} route so they are matched correctly
    Route::get('/cek-status', [PendaftarController::class, 'cekStatus'])->name('ppdb.cek-status');
    Route::post('/cek-status', [PendaftarController::class, 'cekStatus'])->name('ppdb.cek-status.post');
    Route::post('/verify-otp/{pendaftarId}', [PendaftarController::class, 'verifyOTP'])->name('ppdb.verify-otp');
    Route::put('/update-data/{pendaftar}', [PendaftarController::class, 'updateData'])->name('ppdb.update-data');
    Route::get('/{slug}', [PPDBController::class, 'showSekolah'])->name('ppdb.sekolah');
    Route::get('/{slug}/daftar', [PendaftarController::class, 'create'])->name('ppdb.daftar');
    Route::post('/{slug}/daftar', [PendaftarController::class, 'store'])->name('ppdb.store');
    Route::get('/check-nisn/{nisn}', [PendaftarController::class, 'checkNISN'])->name('ppdb.check-nisn');
});

// ADMIN SEKOLAH
Route::middleware(['auth', 'role:admin'])->prefix('ppdb/sekolah')->group(function () {
    Route::get('/dashboard', [AdminSekolahController::class, 'index'])->name('ppdb.sekolah.dashboard');
    Route::get('/pendaftar', [AdminSekolahController::class, 'pendaftar'])->name('ppdb.sekolah.pendaftar');
    Route::get('/verifikasi', [AdminSekolahController::class, 'verifikasi'])->name('ppdb.sekolah.verifikasi');
    Route::get('/seleksi', [AdminSekolahController::class, 'seleksi'])->name('ppdb.sekolah.seleksi');
    Route::get('/export', [AdminSekolahController::class, 'export'])->name('ppdb.sekolah.export');
});

// ADMIN LP. MA'ARIF
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('ppdb/lp')->group(function () {
    Route::get('/dashboard', [AdminLPController::class, 'index'])->name('ppdb.lp.dashboard');
    Route::get('/edit/{id}', [AdminLPController::class, 'edit'])->name('ppdb.lp.edit');
    Route::put('/update/{id}', [AdminLPController::class, 'update'])->name('ppdb.lp.update');
    Route::get('/ppdb-settings/{id}', [AdminLPController::class, 'ppdbSettings'])->name('ppdb.lp.ppdb-settings');
    Route::put('/ppdb-settings/{id}', [AdminLPController::class, 'updatePPDBSettings'])->name('ppdb.lp.update-ppdb-settings');
    Route::post('/jalur/{id}', [AdminLPController::class, 'storeJalur'])->name('ppdb.lp.store-jalur');
    Route::put('/jalur/{jalurId}', [AdminLPController::class, 'updateJalur'])->name('ppdb.lp.update-jalur');
    Route::delete('/jalur/{jalurId}', [AdminLPController::class, 'deleteJalur'])->name('ppdb.lp.delete-jalur');
    Route::get('/pendaftar/{slug}', [AdminLPController::class, 'pendaftar'])->name('ppdb.lp.pendaftar');
    Route::get('/pendaftar/{madrasahId}/check', [AdminLPController::class, 'checkPendaftar'])->name('ppdb.lp.check-pendaftar');
    Route::get('/pendaftar/{madrasahId}/export', [AdminLPController::class, 'export'])->name('ppdb.lp.export');
    Route::get('/pendaftar-detail/{id}', [AdminLPController::class, 'showPendaftarDetail'])->name('ppdb.lp.pendaftar-detail');
    Route::post('/pendaftar/{id}/update-status', [AdminLPController::class, 'updateStatus'])->name('ppdb.lp.update-status');
});

// PPDB Admin routes for pendaftar management
Route::middleware(['auth', 'role:super_admin'])->prefix('ppdb/admin')->group(function () {
    Route::get('/detail/{pendaftar}', [AdminLPController::class, 'detail'])->name('ppdb.admin.detail');
    Route::post('/upload-berkas', [AdminLPController::class, 'uploadBerkas'])->name('ppdb.admin.upload-berkas');
});

// PPDB SETTINGS
Route::middleware(['auth', 'role:super_admin'])->prefix('ppdb/settings')->group(function () {
    Route::get('/', [App\Http\Controllers\PPDB\PPDBController::class, 'settingsIndex'])->name('ppdb.settings.index');
    Route::get('/edit/{id}', [App\Http\Controllers\PPDB\PPDBController::class, 'settingsEdit'])->name('ppdb.settings.edit');
    Route::put('/update/{id}', [App\Http\Controllers\PPDB\PPDBController::class, 'settingsUpdate'])->name('ppdb.settings.update');
});

// DEBUG ROUTES - REMOVE IN PRODUCTION
if (env('APP_DEBUG') === true) {
    Route::get('/debug/ppdb-status', function() {
        $madrasahs = \App\Models\Madrasah::select('id', 'name', 'ppdb_status')
            ->with(['ppdbSettings' => function($q) {
                $q->where('tahun', now()->year)->select('id', 'sekolah_id', 'slug', 'tahun', 'status');
            }])
            ->limit(10)
            ->get();

        return response()->json([
            'total' => count($madrasahs),
            'data' => $madrasahs->map(function($m) {
                return [
                    'id' => $m->id,
                    'name' => $m->name,
                    'ppdb_status_db' => $m->ppdb_status,
                    'ppdb_setting_exists' => $m->ppdbSettings->count() > 0,
                    'ppdb_setting_slug' => $m->ppdbSettings->first()?->slug,
                    'is_buka' => $m->ppdb_status === 'buka',
                ];
            })->toArray(),
        ], 200);
    })->name('debug.ppdb-status');
}

// Midtrans Webhook Callback - TANPA AUTH & CSRF
Route::post('/midtrans/callback', [App\Http\Controllers\PembayaranController::class, 'midtransCallback'])->name('midtrans.callback');

// Pending Registration Routes - Super Admin Only
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pending-registrations', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'index'])->name('pending-registrations.index');
    Route::post('/pending-registrations/{id}/approve', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'approve'])->name('pending-registrations.approve');
    Route::post('/pending-registrations/{id}/reject', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'reject'])->name('pending-registrations.reject');

    // Laporan Akhir Tahun Admin Routes
    Route::get('/laporan-akhir-tahun', [App\Http\Controllers\Admin\LaporanAkhirTahunAdminController::class, 'index'])->name('laporan-akhir-tahun.index');
    Route::get('/laporan-akhir-tahun/pdf/{id}', [App\Http\Controllers\Admin\LaporanAkhirTahunAdminController::class, 'pdf'])->name('laporan-akhir-tahun.pdf');
});


