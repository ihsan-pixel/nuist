<?php

namespace App\Services;

use App\Models\DataSekolah;
use App\Models\Madrasah;
use App\Models\Tagihan;
use App\Models\UppmPaymentUpdate;
use App\Models\UppmSetting;
use Illuminate\Support\Collection;

class UppmPaymentStatusService
{
    public function resolveDefaultYear(): int
    {
        return (int) (
            UppmSetting::query()->where('aktif', true)->max('tahun_anggaran')
            ?: UppmPaymentUpdate::query()->max('tahun_anggaran')
            ?: now()->year
        );
    }

    public function availableYears(): Collection
    {
        return collect([
            now()->year,
            ...UppmSetting::query()->pluck('tahun_anggaran')->all(),
            ...UppmPaymentUpdate::query()->pluck('tahun_anggaran')->all(),
        ])->filter()->map(fn ($year) => (int) $year)->unique()->sortDesc()->values();
    }

    public function shouldEnforceSkGenerateGate(?int $tahunAnggaran = null): bool
    {
        $tahunAnggaran ??= $this->resolveDefaultYear();

        return UppmSetting::query()->where('tahun_anggaran', $tahunAnggaran)->where('aktif', true)->exists()
            || Tagihan::query()->where('tahun_anggaran', $tahunAnggaran)->exists()
            || UppmPaymentUpdate::query()->where('tahun_anggaran', $tahunAnggaran)->exists();
    }

    public function eligibleSchoolIdsForYear(Collection $schoolIds, int $tahunAnggaran): Collection
    {
        $summaries = $this->summariesForYear($schoolIds, $tahunAnggaran);

        return $summaries
            ->filter(fn (array $summary) => $summary['is_lunas'])
            ->keys()
            ->map(fn ($schoolId) => (int) $schoolId)
            ->values();
    }

    public function summaryForSchoolYear(int $madrasahId, int $tahunAnggaran): array
    {
        return $this->summariesForYear(collect([$madrasahId]), $tahunAnggaran)->get($madrasahId, $this->emptySummary());
    }

    public function summariesForYear(Collection $schools, int $tahunAnggaran): Collection
    {
        $schoolIds = $schools
            ->map(function ($school) {
                if ($school instanceof Madrasah) {
                    return (int) $school->id;
                }

                return (int) $school;
            })
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values();

        if ($schoolIds->isEmpty()) {
            return collect();
        }

        $setting = UppmSetting::query()
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('aktif', true)
            ->first();

        $schoolDataMap = DataSekolah::query()
            ->where('tahun', $tahunAnggaran)
            ->whereIn('madrasah_id', $schoolIds->all())
            ->get()
            ->keyBy('madrasah_id');

        $tagihanMap = Tagihan::query()
            ->where('tahun_anggaran', $tahunAnggaran)
            ->whereIn('madrasah_id', $schoolIds->all())
            ->get()
            ->keyBy('madrasah_id');

        $paymentsBySchool = UppmPaymentUpdate::query()
            ->where('tahun_anggaran', $tahunAnggaran)
            ->whereIn('madrasah_id', $schoolIds->all())
            ->orderBy('transfer_date')
            ->orderBy('id')
            ->get()
            ->groupBy('madrasah_id');

        return $schoolIds->mapWithKeys(function (int $schoolId) use ($paymentsBySchool, $tagihanMap, $schoolDataMap, $setting) {
            $payments = $paymentsBySchool->get($schoolId, collect());
            $tagihan = $tagihanMap->get($schoolId);
            $schoolData = $schoolDataMap->get($schoolId);

            $targetNominal = $this->resolveTargetNominal($schoolData, $setting, $tagihan);
            $totalPaid = (float) $payments->sum(fn (UppmPaymentUpdate $payment) => (float) $payment->amount);
            $remaining = max($targetNominal - $totalPaid, 0);
            $hasTarget = $targetNominal > 0;
            $latestPaymentDate = $payments->max('transfer_date');
            $isLunas = $hasTarget ? $totalPaid >= $targetNominal : $totalPaid > 0;

            $status = 'belum_lunas';
            if ($isLunas) {
                $status = 'lunas';
            } elseif ($totalPaid > 0) {
                $status = 'sebagian';
            }

            return [
                $schoolId => [
                    'target_nominal' => $targetNominal,
                    'total_paid' => $totalPaid,
                    'remaining' => $remaining,
                    'status' => $status,
                    'status_label' => $this->statusLabel($status),
                    'is_lunas' => $isLunas,
                    'has_target' => $hasTarget,
                    'latest_payment_date' => $latestPaymentDate,
                    'payments' => $payments,
                    'payments_by_period' => $payments->groupBy('payment_period'),
                    'tagihan' => $tagihan,
                ],
            ];
        });
    }

    public function syncTagihanForSchoolYear(int $madrasahId, int $tahunAnggaran): Tagihan
    {
        $summary = $this->summaryForSchoolYear($madrasahId, $tahunAnggaran);
        $setting = UppmSetting::query()
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('aktif', true)
            ->first();

        $tagihan = Tagihan::query()->firstOrNew([
            'madrasah_id' => $madrasahId,
            'tahun_anggaran' => $tahunAnggaran,
        ]);

        if (!$tagihan->exists && $summary['target_nominal'] <= 0 && $summary['total_paid'] <= 0) {
            return $tagihan;
        }

        if (!$tagihan->exists && empty($tagihan->jenis_tagihan)) {
            $tagihan->jenis_tagihan = 'uppm';
        }

        if (!$tagihan->keterangan) {
            $tagihan->keterangan = 'Disinkronkan dari menu Update UPPM.';
        }

        $tagihan->nominal = $summary['target_nominal'];
        $tagihan->nominal_dibayar = $summary['total_paid'];
        $tagihan->status = $summary['status'];
        $tagihan->jatuh_tempo = $setting?->jatuh_tempo;
        $tagihan->tanggal_pembayaran = $summary['is_lunas']
            ? $summary['latest_payment_date']
            : null;

        $tagihan->save();

        return $tagihan;
    }

    private function resolveTargetNominal($schoolData, $setting, ?Tagihan $tagihan): float
    {
        if ($setting && $schoolData) {
            return $this->computeAnnualNominal($schoolData, $setting);
        }

        return (float) ($tagihan?->nominal ?? 0);
    }

    private function computeAnnualNominal(DataSekolah $schoolData, UppmSetting $setting): float
    {
        $monthlyNominal = 0;
        $monthlyNominal += ((int) ($schoolData->jumlah_siswa ?? 0)) * (float) $setting->nominal_siswa;
        $monthlyNominal += ((int) ($schoolData->jumlah_pns_sertifikasi ?? 0)) * (float) $setting->nominal_pns_sertifikasi;
        $monthlyNominal += ((int) ($schoolData->jumlah_pns_non_sertifikasi ?? 0)) * (float) $setting->nominal_pns_non_sertifikasi;
        $monthlyNominal += ((int) ($schoolData->jumlah_gty_sertifikasi ?? 0)) * (float) $setting->nominal_gty_sertifikasi;
        $monthlyNominal += ((int) ($schoolData->jumlah_gty_sertifikasi_inpassing ?? 0)) * (float) $setting->nominal_gty_sertifikasi_inpassing;
        $monthlyNominal += ((int) ($schoolData->jumlah_gty_non_sertifikasi ?? 0)) * (float) $setting->nominal_gty_non_sertifikasi;
        $monthlyNominal += ((int) ($schoolData->jumlah_gtt ?? 0)) * (float) $setting->nominal_gtt;
        $monthlyNominal += ((int) ($schoolData->jumlah_pty ?? 0)) * (float) $setting->nominal_pty;
        $monthlyNominal += ((int) ($schoolData->jumlah_ptt ?? 0)) * (float) $setting->nominal_ptt;

        return $monthlyNominal * 12;
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'lunas' => 'Lunas',
            'sebagian' => 'Sebagian',
            default => 'Belum Lunas',
        };
    }

    private function emptySummary(): array
    {
        return [
            'target_nominal' => 0,
            'total_paid' => 0,
            'remaining' => 0,
            'status' => 'belum_lunas',
            'status_label' => 'Belum Lunas',
            'is_lunas' => false,
            'has_target' => false,
            'latest_payment_date' => null,
            'payments' => collect(),
            'payments_by_period' => collect(),
            'tagihan' => null,
        ];
    }
}
