<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Madrasah;
use App\Models\Siswa;
use App\Models\SppSiswaBill;
use App\Models\SppSiswaTransaction;
use App\Models\UppmPaymentUpdate;
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
            $verifiedPayments = DB::table('spp_siswa_transactions')
                ->selectRaw('bill_id, SUM(nominal_bayar) as paid_amount')
                ->where('status_verifikasi', 'diverifikasi')
                ->groupBy('bill_id');

            // `outstanding_amount` is an Eloquent accessor, not a database column.
            // Calculate it in SQL so this dashboard does not load every bill and its
            // transactions into memory.
            $finance = (clone $openBills)
                ->leftJoinSub($verifiedPayments, 'verified_payments', function ($join) {
                    $join->on('verified_payments.bill_id', '=', 'spp_siswa_bills.id');
                })
                ->selectRaw('COUNT(spp_siswa_bills.id) as open_bills')
                ->selectRaw('COALESCE(SUM(GREATEST(spp_siswa_bills.total_tagihan - COALESCE(verified_payments.paid_amount, 0), 0)), 0) as outstanding_amount')
                ->first();

            return [
                'summary' => [
                    'schools' => Madrasah::query()->count(),
                    'students' => Siswa::query()->where('is_active', true)->count(),
                    'teachers' => User::query()->where('role', 'tenaga_pendidik')->where('is_active', true)->count(),
                    'attendance_today' => DB::table('presensis')->where('tanggal', $today)->distinct('user_id')->count('user_id'),
                ],
                'finance' => [
                    'open_bills' => (int) ($finance->open_bills ?? 0),
                    'outstanding_amount' => (float) ($finance->outstanding_amount ?? 0),
                ],
                'recent_uppm_updates' => $this->uppmUpdateItems(3),
                'recent_spp_updates' => $this->sppUpdateItems(3),
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

            return Madrasah::query()
            ->whereNotNull('kabupaten')
            ->where('kabupaten', '!=', '')
            ->orderByRaw("COALESCE(kabupaten, '')")
            ->orderBy('scod')
            ->orderBy('name')
            ->get(['id', 'name', 'scod', 'kabupaten', 'logo'])
            ->map(fn (Madrasah $school) => [
                'id' => $school->id, 'name' => $school->name, 'scod' => $school->scod,
                'kabupaten' => $school->kabupaten,
                'logo_url' => filled($school->logo) ? url('storage/' . ltrim($school->logo, '/')) : null,
                'students' => (int) ($studentCounts[$school->id] ?? 0),
                'teachers' => (int) ($teacherCounts[$school->id] ?? 0),
            ])->values()->all();
        });
        return response()->json(['data' => ['items' => $items]]);
    }

    public function uppmUpdates(Request $request): JsonResponse
    {
        $this->authorizePengurus($request);

        return response()->json(['data' => [
            'items' => $this->uppmUpdateItems(50),
        ]]);
    }

    public function sppUpdates(Request $request): JsonResponse
    {
        $this->authorizePengurus($request);

        return response()->json(['data' => [
            'items' => $this->sppUpdateItems(50),
        ]]);
    }

    private function uppmUpdateItems(int $limit): array
    {
        return UppmPaymentUpdate::query()
            ->with('madrasah:id,name')
            ->latest('transfer_date')
            ->latest('id')
            ->limit($limit)
            ->get()
            ->map(fn (UppmPaymentUpdate $update) => [
                'id' => $update->id,
                'title' => $update->madrasah?->name ?? 'Sekolah',
                'subtitle' => 'UPPM • ' . $update->payment_period_label,
                'amount' => (float) $update->amount,
                'status' => 'Pembaruan pembayaran',
                'date' => optional($update->transfer_date)->toDateString() ?? optional($update->created_at)->toDateString(),
            ])
            ->all();
    }

    private function sppUpdateItems(int $limit): array
    {
        return SppSiswaTransaction::query()
            ->with('madrasah:id,name')
            ->latest('tanggal_bayar')
            ->latest('id')
            ->limit($limit)
            ->get()
            ->map(fn (SppSiswaTransaction $transaction) => [
                'id' => $transaction->id,
                'title' => $transaction->madrasah?->name ?? 'Sekolah',
                'subtitle' => 'SPP Siswa • ' . ($transaction->metode_pembayaran ?: 'Pembayaran'),
                'amount' => (float) $transaction->nominal_bayar,
                'status' => match ($transaction->status_verifikasi) {
                    'diverifikasi' => 'Terverifikasi',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu verifikasi',
                },
                'date' => optional($transaction->tanggal_bayar)->toDateString() ?? optional($transaction->created_at)->toDateString(),
            ])
            ->all();
    }

    private function authorizePengurus(Request $request): User
    {
        $user = $request->user();
        abort_unless($user && $user->role === 'pengurus' && $user->is_active, 403);
        return $user;
    }
}
