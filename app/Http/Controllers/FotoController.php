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
    public function show(Request $request, $type, $id)
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

        return $this->buildPhotoResponse($path, $request->query('format'));
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

    private function buildPhotoResponse(string $path, ?string $format = null)
    {
        if (strtolower((string) $format) !== 'webp') {
            return Response::file($path);
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === 'webp') {
            return Response::file($path, ['Content-Type' => 'image/webp']);
        }

        $webpPath = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);

        if ($webpPath && File::exists($webpPath)) {
            return Response::file($webpPath, ['Content-Type' => 'image/webp']);
        }

        $webpBinary = $this->convertImageToWebp($path, $extension, $webpPath);

        if ($webpBinary === null) {
            return Response::file($path);
        }

        return Response::make($webpBinary, 200, ['Content-Type' => 'image/webp']);
    }

    private function convertImageToWebp(string $path, string $extension, ?string $webpPath = null): ?string
    {
        if (!function_exists('imagewebp')) {
            return null;
        }

        $image = match ($extension) {
            'jpg', 'jpeg' => function_exists('imagecreatefromjpeg') ? @imagecreatefromjpeg($path) : false,
            'png' => function_exists('imagecreatefrompng') ? @imagecreatefrompng($path) : false,
            default => false,
        };

        if ($image === false) {
            return null;
        }

        if (function_exists('imagepalettetotruecolor')) {
            @imagepalettetotruecolor($image);
        }

        @imagealphablending($image, true);
        @imagesavealpha($image, true);

        if ($webpPath && @imagewebp($image, $webpPath, 82)) {
            @imagedestroy($image);
            return File::get($webpPath);
        }

        ob_start();
        $converted = @imagewebp($image, null, 82);
        $binary = ob_get_clean();
        @imagedestroy($image);

        if (!$converted || $binary === false) {
            return null;
        }

        return $binary;
    }
}
