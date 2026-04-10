<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
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
}
