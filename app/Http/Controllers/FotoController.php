<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Models\MgmpAttendance;
use App\Models\Presensi;

class FotoController extends Controller
{
    public function show($type, $id)
    {
        $presensi = Presensi::findOrFail($id);

        $relativePath = $type === 'masuk'
            ? $presensi->selfie_masuk_path
            : $presensi->selfie_keluar_path;

        if (empty($relativePath)) {
            abort(404, 'File foto tidak ditemukan.');
        }

        $path = storage_path('app/public/' . ltrim($relativePath, '/'));

        if (!File::exists($path)) {
            abort(404, 'File foto tidak ditemukan.');
        }

        return Response::file($path);
    }

    public function showMgmpAttendance(MgmpAttendance $attendance)
    {
        $user = Auth::user();
        $attendance->loadMissing('mgmpGroup');

        $canView = $user && (
            (int) $attendance->user_id === (int) $user->id
            || in_array($user->role, ['super_admin', 'admin', 'pengurus'])
            || ($user->role === 'mgmp' && $attendance->mgmpGroup && (int) $attendance->mgmpGroup->user_id === (int) $user->id)
        );

        if (!$canView) {
            abort(403, 'Anda tidak memiliki akses untuk melihat foto ini.');
        }

        if (empty($attendance->selfie_path)) {
            abort(404, 'File foto tidak ditemukan.');
        }

        $relativePath = ltrim($attendance->selfie_path, '/');
        $path = storage_path('app/public/' . $relativePath);

        if (!File::exists($path)) {
            abort(404, 'File foto tidak ditemukan.');
        }

        return Response::file($path);
    }
}
