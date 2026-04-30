<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Siswa;
use App\Models\SppSiswaBill;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MobileController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'message' => 'OK',
            'data' => $this->serializeUser($user),
        ]);
    }

    public function dashboard(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $periodStart = now()->startOfMonth();
        $periodEnd = now()->endOfMonth();

        $attendanceQuery = $user->presensis()
            ->whereBetween('tanggal', [$periodStart, $periodEnd]);

        $attendanceCount = (clone $attendanceQuery)->count();
        $attendancePresentCount = (clone $attendanceQuery)
            ->whereIn('status', ['hadir', 'izin'])
            ->count();

        $siswa = $this->findSiswaForUser($user);
        $unpaidBillCount = 0;

        if ($siswa) {
            $unpaidBillCount = SppSiswaBill::query()
                ->where('siswa_id', $siswa->id)
                ->whereIn('status', ['belum_lunas', 'sebagian'])
                ->count();
        }

        return response()->json([
            'message' => 'OK',
            'data' => [
                'greeting' => 'Selamat datang, ' . Str::of($user->name)->trim()->before(' '),
                'summary' => [
                    'attendance_percent' => $attendanceCount > 0
                        ? round(($attendancePresentCount / $attendanceCount) * 100, 1)
                        : 0,
                    'pending_izin_count' => Izin::query()
                        ->where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->count(),
                    'unpaid_bill_count' => $unpaidBillCount,
                ],
            ],
        ]);
    }

    public function tagihan(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $siswa = $this->findSiswaForUser($user);

        if (!$siswa) {
            return response()->json([
                'message' => 'OK',
                'data' => [
                    'items' => [],
                    'total_unpaid' => 0,
                ],
            ]);
        }

        $bills = SppSiswaBill::query()
            ->with('transactions')
            ->where('siswa_id', $siswa->id)
            ->orderByDesc('periode')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'items' => $bills->map(fn (SppSiswaBill $bill) => [
                    'id' => $bill->id,
                    'nomor_tagihan' => $bill->nomor_tagihan,
                    'jenis_tagihan' => $bill->jenis_tagihan,
                    'periode' => $bill->periode,
                    'jatuh_tempo' => optional($bill->jatuh_tempo)->format('Y-m-d'),
                    'total_tagihan' => (int) round((float) $bill->total_tagihan),
                    'status' => $bill->status,
                ])->values(),
                'total_unpaid' => (int) round($bills->sum(
                    fn (SppSiswaBill $bill) => $bill->outstanding_amount
                )),
            ],
        ]);
    }

    public function izinIndex(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $items = Izin::query()
            ->where('user_id', $user->id)
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Izin $izin) => $this->serializeIzin($izin))
            ->values();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'items' => $items,
            ],
        ]);
    }

    public function izinShow(Request $request, Izin $izin): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless((int) $izin->user_id === (int) $user->id, 404);

        return response()->json([
            'message' => 'OK',
            'data' => [
                'item' => $this->serializeIzin($izin),
            ],
        ]);
    }

    private function findSiswaForUser(User $user): ?Siswa
    {
        if ($user->role !== 'siswa') {
            return null;
        }

        return Siswa::query()
            ->where('email', $user->email)
            ->first();
    }

    private function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'nuist_id' => $user->nuist_id,
            'photo_url' => null,
        ];
    }

    private function serializeIzin(Izin $izin): array
    {
        $title = match ($izin->type) {
            'tugas_luar' => 'Izin Tugas Luar',
            'tidak_masuk' => 'Izin Tidak Masuk',
            'mengajar_sekolah_lain' => 'Izin Mengajar di Sekolah Lain',
            default => 'Izin ' . Str::title(str_replace('_', ' ', (string) $izin->type)),
        };

        return [
            'id' => $izin->id,
            'type' => $izin->type,
            'title' => $title,
            'status' => $izin->status,
            'reason' => $izin->alasan ?: $izin->deskripsi_tugas,
            'submitted_at' => optional($izin->tanggal)->format('Y-m-d'),
        ];
    }
}
