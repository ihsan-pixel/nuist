<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubWebhookController;
use App\Http\Controllers\Api\FaceController;
use App\Http\Controllers\Api\MobileController;
use App\Http\Controllers\Api\TeacherAppController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Mobile token login/logout for Capacitor apps (token-based auth)
Route::get('/mobile/register/options', [App\Http\Controllers\Api\AuthController::class, 'registerOptions']);
Route::post('/mobile/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/mobile/forgot-password', [App\Http\Controllers\Api\AuthController::class, 'forgotPassword']);
Route::post('/mobile/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/mobile/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->prefix('/mobile')->group(function () {
    Route::get('/me', [MobileController::class, 'me']);
    Route::get('/dashboard', [MobileController::class, 'dashboard']);
    Route::get('/tagihan', [MobileController::class, 'tagihan']);
    Route::get('/izin', [MobileController::class, 'izinIndex']);
    Route::get('/izin/{izin}', [MobileController::class, 'izinShow']);
    Route::prefix('/app/teacher')->group(function () {
        Route::get('/dashboard', [TeacherAppController::class, 'dashboard']);
        Route::get('/schedule', [TeacherAppController::class, 'schedule']);
        Route::get('/schedule/options', [TeacherAppController::class, 'scheduleOptions']);
        Route::post('/schedule', [TeacherAppController::class, 'storeSchedule']);
        Route::put('/schedule/{schedule}', [TeacherAppController::class, 'updateSchedule']);
        Route::delete('/schedule/{schedule}', [TeacherAppController::class, 'destroySchedule']);
        Route::get('/attendance', [TeacherAppController::class, 'attendance']);
        Route::post('/attendance', [TeacherAppController::class, 'storeAttendance']);
        Route::get('/teaching-journal', [TeacherAppController::class, 'teachingJournal']);
        Route::post('/teaching-journal/check-location', [TeacherAppController::class, 'checkTeachingJournalLocation']);
        Route::post('/teaching-journal/attendance', [TeacherAppController::class, 'storeTeachingJournalAttendance']);
        Route::get('/reports', [TeacherAppController::class, 'attendanceReports']);
        Route::get('/reports/export/attendance', [TeacherAppController::class, 'exportAttendanceReport']);
        Route::get('/reports/export/teaching', [TeacherAppController::class, 'exportTeachingReport']);
        Route::get('/staff-attendance', [TeacherAppController::class, 'staffAttendanceMonitor']);
        Route::get('/profile', [TeacherAppController::class, 'profile']);
        Route::post('/profile/update', [TeacherAppController::class, 'updateProfile']);
        Route::post('/profile/avatar', [TeacherAppController::class, 'updateProfileAvatar']);
        Route::post('/profile/password', [TeacherAppController::class, 'updateProfilePassword']);
        Route::get('/izin', [TeacherAppController::class, 'izin']);
        Route::post('/izin', [TeacherAppController::class, 'storeIzin']);
        Route::get('/izin/manage', [TeacherAppController::class, 'manageIzin']);
        Route::post('/izin/{izin}/approve', [TeacherAppController::class, 'approveIzin']);
        Route::post('/izin/{izin}/reject', [TeacherAppController::class, 'rejectIzin']);
    });
});

Route::middleware('auth')->get('/active-users', [App\Http\Controllers\ActiveUsersController::class, 'apiIndex']);

Route::post('github-commit', [GithubWebhookController::class, 'handle']);

// Face enrollment (admin) and verification (mobile presensi) with rate-limiting
Route::middleware(['auth', 'throttle:10,1'])->post('/face/enroll', [FaceController::class, 'enroll']);
Route::middleware(['auth', 'throttle:20,1'])->post('/face/verify', [FaceController::class, 'verify']);
