<?php

namespace App\Http\Controllers\Mobile\Presensi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PythonFaceAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['web', 'auth']);
    }

    public function show(Request $request, string $mode)
    {
        $user = $request->user();
        abort_unless($user && $user->role === 'tenaga_pendidik', 403, 'Unauthorized.');
        abort_unless(in_array($mode, ['masuk', 'keluar'], true), 404);

        if (!$this->hasKiosk2Enrollment($user)) {
            return redirect()->route('mobile.presensi')->with(
                'error',
                'Profil verifikasi wajah belum tersedia. Gunakan alur presensi yang tersedia.'
            );
        }

        return view('mobile.python-face-attendance', [
            'mode' => $mode,
            'user' => $user,
            'presensiStoreUrl' => route('mobile.presensi.store'),
            'presensiUrl' => route('mobile.presensi'),
        ]);
    }

    private function hasKiosk2Enrollment(User $user): bool
    {
        return $user->biometricProfiles()
            ->where('status', 'active')
            ->where('engine', 'onnxruntime')
            ->where('model', config('kiosk_face_v2.model', 'arcface'))
            ->where('model_version', config('kiosk_face_v2.model_version', 'buffalo_l_w600k_r50'))
            ->where('dimension', 512)
            ->exists();
    }
}
