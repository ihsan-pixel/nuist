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

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:super_admin,admin,pengurus'])->group(function () {
        Route::get('/presensi-admin/settings', [PresensiAdminController::class, 'settings'])->name('presensi_admin.settings');
        Route::post('/presensi-admin/settings', [PresensiAdminController::class, 'updateSettings'])->name('presensi_admin.updateSettings');
        Route::get('/presensi-admin', [PresensiAdminController::class, 'index'])->name('presensi_admin.index');
        Route::get('/presensi-admin/data', [PresensiAdminController::class, 'getData'])->name('presensi_admin.data');
        Route::get('/presensi-admin/summary', [PresensiAdminController::class, 'getSummary'])->name('presensi_admin.summary');
        Route::get('/presensi-admin/detail/{userId}', [PresensiAdminController::class, 'getDetail'])->name('presensi_admin.detail');
        Route::get('/presensi-admin/madrasah-detail/{madrasahId}', [PresensiAdminController::class, 'getMadrasahDetail'])->name('presensi_admin.madrasah_detail');
        Route::get('/presensi-admin/export', [PresensiAdminController::class, 'export'])->name('presensi_admin.export');
    });

    // Development History Routes - Super Admin Only
    Route::middleware(['role:super_admin,pengurus'])->group(function () {
        Route::get('/development-history', [DevelopmentHistoryController::class, 'index'])->name('development-history.index');
        Route::get('/development-history/sync', [DevelopmentHistoryController::class, 'syncMigrations'])->name('development-history.sync');
        Route::get('/active-users', [App\Http\Controllers\ActiveUsersController::class, 'index'])->name('active-users.index');
    });

    // Teaching Schedules Routes
    Route::middleware(['role:super_admin,admin,tenaga_pendidik'])->group(function () {
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
});

Auth::routes(['verify' => true]);

// Email Verification Routes
Route::get('/email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('/email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// setelah login langsung ke dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
})->middleware('auth')->name('root');

// dashboard route - accessible by super_admin, admin, tenaga_pendidik
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth']);

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

Route::prefix('izin')->middleware(['auth'])->name('izin.')->group(function () {
    Route::middleware(['role:tenaga_pendidik'])->group(function () {
        Route::get('/create', [IzinController::class, 'create'])->name('create');
        Route::post('/store', [IzinController::class, 'store'])->name('store');
    });

    Route::middleware(['role:admin,super_admin,pengurus'])->group(function () {
        Route::get('/', [IzinController::class, 'index'])->name('index');
        Route::post('/{presensi}/approve', [IzinController::class, 'approve'])->name('approve');
        Route::post('/{presensi}/reject', [IzinController::class, 'reject'])->name('reject');
    });
});

// Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// fallback, jangan ganggu dashboard & lainnya
Route::fallback([App\Http\Controllers\HomeController::class, 'index'])->name('index');
