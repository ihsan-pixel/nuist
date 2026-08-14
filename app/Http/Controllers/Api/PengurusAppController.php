<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Madrasah;
use App\Models\Siswa;
use App\Models\SppSiswaBill;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengurusAppController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $user = $this->authorizePengurus($request);
        $today = now()->toDateString();

        return response()->json(['data' => [
            'greeting' => 'Selamat datang, ' . $user->name,
            'summary' => [
                'schools' => Madrasah::query()->count(),
                'students' => Siswa::query()->where('is_active', true)->count(),
                'teachers' => User::query()->where('role', 'tenaga_pendidik')->where('is_active', true)->count(),
                'attendance_today' => DB::table('presensis')->whereDate('tanggal', $today)->distinct('user_id')->count('user_id'),
            ],
            'finance' => [
                'open_bills' => SppSiswaBill::query()->whereIn('status', ['belum_lunas', 'sebagian'])->count(),
                'outstanding_amount' => (float) SppSiswaBill::query()->whereIn('status', ['belum_lunas', 'sebagian'])->sum('outstanding_amount'),
            ],
            'generated_at' => now()->toIso8601String(),
        ]]);
    }

    public function schools(Request $request): JsonResponse
    {
        $this->authorizePengurus($request);
        $items = Madrasah::query()->orderBy('name')->get(['id', 'name', 'scod'])
            ->map(fn (Madrasah $school) => [
                'id' => $school->id, 'name' => $school->name, 'scod' => $school->scod,
                'students' => Siswa::query()->where('madrasah_id', $school->id)->where('is_active', true)->count(),
                'teachers' => User::query()->where('madrasah_id', $school->id)->where('role', 'tenaga_pendidik')->where('is_active', true)->count(),
            ]);
        return response()->json(['data' => ['items' => $items]]);
    }

    private function authorizePengurus(Request $request): User
    {
        $user = $request->user();
        abort_unless($user && $user->role === 'pengurus' && $user->is_active, 403);
        return $user;
    }
}
