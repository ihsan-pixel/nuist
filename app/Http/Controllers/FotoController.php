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

        $path = $type === 'masuk'
            ? base_path('nuist/storage/app/' . $presensi->selfie_masuk_path)
            : base_path('nuist/storage/app/' . $presensi->selfie_keluar_path);

        if (!File::exists($path)) {
            abort(404);
        }

        return Response::file($path);
    }
}
