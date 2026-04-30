<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GithubWebhookController;
use App\Http\Controllers\Api\FaceController;
use App\Http\Controllers\Api\MobileController;

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
Route::post('/mobile/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/mobile/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->prefix('/mobile')->group(function () {
    Route::get('/me', [MobileController::class, 'me']);
    Route::get('/dashboard', [MobileController::class, 'dashboard']);
    Route::get('/tagihan', [MobileController::class, 'tagihan']);
    Route::get('/izin', [MobileController::class, 'izinIndex']);
    Route::get('/izin/{izin}', [MobileController::class, 'izinShow']);
});

Route::middleware('auth')->get('/active-users', [App\Http\Controllers\ActiveUsersController::class, 'apiIndex']);

Route::post('github-commit', [GithubWebhookController::class, 'handle']);

// Face enrollment (admin) and verification (mobile presensi) with rate-limiting
Route::middleware(['auth', 'throttle:10,1'])->post('/face/enroll', [FaceController::class, 'enroll']);
Route::middleware(['auth', 'throttle:20,1'])->post('/face/verify', [FaceController::class, 'verify']);
