<?php

use App\Http\Controllers\AdminYayasan\BpppmnuEventController;
use App\Http\Controllers\AdminYayasan\BpppmnuMemberController;
use App\Http\Controllers\Mobile\BpppmnuController;
use App\Http\Controllers\Mobile\Profile\ProfileController;
use App\Http\Middleware\BpppmnuRole;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', BpppmnuRole::class.':admin_yayasan'])->prefix('admin-yayasan/bpppmnu')->name('admin.bpppmnu.')->group(function () {
    Route::get('pengurus', [BpppmnuMemberController::class, 'index'])->name('members.index');
    Route::post('pengurus', [BpppmnuMemberController::class, 'store'])->name('members.store');
    Route::put('pengurus/{member}', [BpppmnuMemberController::class, 'update'])->name('members.update');
    Route::get('kegiatan', [BpppmnuEventController::class, 'index'])->name('events.index');
    Route::get('kegiatan/create', [BpppmnuEventController::class, 'create'])->name('events.create');
    Route::post('kegiatan', [BpppmnuEventController::class, 'store'])->name('events.store');
    Route::get('kegiatan/{event}', [BpppmnuEventController::class, 'show'])->name('events.show');
    Route::get('kegiatan/{event}/edit', [BpppmnuEventController::class, 'edit'])->name('events.edit');
    Route::put('kegiatan/{event}', [BpppmnuEventController::class, 'update'])->name('events.update');
    foreach (['publish', 'cancel', 'qr', 'revoke'] as $action) {
        Route::post('kegiatan/{event}/'.$action, [BpppmnuEventController::class, $action])->name('events.'.$action);
    }
    Route::get('kegiatan/{event}/attachment', [BpppmnuEventController::class, 'attachment'])->name('events.attachment');
    Route::get('kegiatan/{event}/export', [BpppmnuEventController::class, 'export'])->name('events.export');
});

Route::middleware(['auth', BpppmnuRole::class.':pengurus_bpppmnu'])->prefix('mobile/bpppmnu')->name('mobile.bpppmnu.')->group(function () {
    Route::get('presensi', [BpppmnuController::class, 'index'])->name('presensi');
    Route::get('barcode', [BpppmnuController::class, 'barcode'])->name('barcode');
    Route::get('riwayat-presensi', [BpppmnuController::class, 'history'])->name('history');
    Route::get('profil', [BpppmnuController::class, 'profile'])->name('profile');
    Route::post('profil/password', [ProfileController::class, 'updatePassword'])->middleware('throttle:5,1')->name('password');
    Route::post('logout', [BpppmnuController::class, 'logout'])->name('logout');
    Route::get('kegiatan/{event}', [BpppmnuController::class, 'show'])->name('events.show');
    Route::get('kegiatan/{event}/attachment', [BpppmnuController::class, 'attachment'])->name('events.attachment');
    Route::post('kegiatan/{event}/scan', [BpppmnuController::class, 'scan'])->middleware('throttle:15,1')->name('events.scan');
});
