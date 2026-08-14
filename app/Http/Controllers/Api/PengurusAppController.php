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
use Illuminate\Support\Facades\Cache;

class PengurusAppController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $user = $this->authorizePengurus($request);
        $today = now()->toDateString();

        $data = Cache::remember('pengurus:dashboard:' . $today, now()->addMinute(), function () use ($today) {
            $openBills = SppSiswaBill::query()->whereIn('status', ['belum_lunas', 'sebagian']);

            return [
                'summary' => [
                    'schools' => Madrasah::query()->count(),
                    'students' => Siswa::query()->where('is_active', true)->count(),
                    'teachers' => User::query()->where('role', 'tenaga_pendidik')->where('is_active', true)->count(),
                    'attendance_today' => DB::table('presensis')->where('tanggal', $today)->distinct('user_id')->count('user_id'),
                ],
                'finance' => [
                    'open_bills' => (clone $openBills)->count(),
                    'outstanding_amount' => (float) $openBills->sum('outstanding_amount'),
                ],
                'generated_at' => now()->toIso8601String(),
            ];
        });

        return response()->json(['data' => array_merge($data, [
            'greeting' => 'Selamat datang, ' . $user->name,
        ])]);
    }

    public function schools(Request $request): JsonResponse
    {
        $this->authorizePengurus($request);
        $items = Cache::remember('pengurus:schools', now()->addMinutes(5), function () {
            $studentCounts = Siswa::query()->where('is_active', true)->selectRaw('madrasah_id, COUNT(*) as total')->groupBy('madrasah_id')->pluck('total', 'madrasah_id');
            $teacherCounts = User::query()->where('role', 'tenaga_pendidik')->where('is_active', true)->selectRaw('madrasah_id, COUNT(*) as total')->groupBy('madrasah_id')->pluck('total', 'madrasah_id');

            return Madrasah::query()->orderBy('name')->get(['id', 'name', 'scod'])
            ->map(fn (Madrasah $school) => [
                'id' => $school->id, 'name' => $school->name, 'scod' => $school->scod,
                'students' => (int) ($studentCounts[$school->id] ?? 0),
                'teachers' => (int) ($teacherCounts[$school->id] ?? 0),
            ])->values()->all();
        });
        return response()->json(['data' => ['items' => $items]]);
    }

    private function authorizePengurus(Request $request): User
    {
        $user = $request->user();
        abort_unless($user && $user->role === 'pengurus' && $user->is_active, 403);
        return $user;
    }
}
