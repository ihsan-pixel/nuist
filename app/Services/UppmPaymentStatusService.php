<?php

namespace App\Services;

use App\Models\DataSekolah;
use App\Models\Madrasah;
use App\Models\Tagihan;
use App\Models\UppmPaymentUpdate;
use App\Models\UppmSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class UppmPaymentStatusService
{
    private ?array $tagihanColumns = null;

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

    public function resolveSkPaymentRequirement(?string $issuedDate = null): array
    {
        $date = $issuedDate ? Carbon::parse($issuedDate) : now();
        $periodKey = (int) $date->month <= 6
            ? UppmPaymentUpdate::PERIOD_JAN_JUN
            : UppmPaymentUpdate::PERIOD_JUL_DES;

        return [
            'year' => (int) $date->year,
            'period_key' => $periodKey,
            'period_label' => UppmPaymentUpdate::PERIOD_LABELS[$periodKey] ?? $periodKey,
        ];
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

    public function eligibleSchoolIdsForPeriod(Collection $schoolIds, int $tahunAnggaran, string $paymentPeriod): Collection
    {
        $summaries = $this->summariesForYear($schoolIds, $tahunAnggaran);

        return $summaries
            ->filter(function (array $summary) use ($paymentPeriod) {
                $periodSummary = $summary['period_summaries'][$paymentPeriod] ?? null;

                return (bool) ($periodSummary['is_lunas'] ?? false);
            })
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
            $periodTargetNominal = $hasTarget ? ($targetNominal / 2) : 0;
            $periodSummaries = collect(UppmPaymentUpdate::PERIOD_LABELS)->mapWithKeys(function (string $label, string $periodKey) use ($payments, $periodTargetNominal) {
                $periodPayments = $payments->where('payment_period', $periodKey)->values();
                $periodTotalPaid = (float) $periodPayments->sum(fn (UppmPaymentUpdate $payment) => (float) $payment->amount);
                $isPeriodLunas = $periodTargetNominal > 0
                    ? $periodTotalPaid >= $periodTargetNominal
                    : $periodTotalPaid > 0;

                return [
                    $periodKey => [
                        'label' => $label,
                        'payments' => $periodPayments,
                        'total_paid' => $periodTotalPaid,
                        'target_nominal' => $periodTargetNominal,
                        'is_lunas' => $isPeriodLunas,
                    ],
                ];
            });

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
                    'period_summaries' => $periodSummaries,
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

        $attributes = [];

        if (!$tagihan->exists && $this->tagihanHasColumn('jenis_tagihan') && empty($tagihan->jenis_tagihan)) {
            $attributes['jenis_tagihan'] = 'uppm';
        }

        if ($this->tagihanHasColumn('keterangan') && !$tagihan->keterangan) {
            $attributes['keterangan'] = 'Disinkronkan dari menu Update UPPM.';
        }

        if ($this->tagihanHasColumn('nominal')) {
            $attributes['nominal'] = $summary['target_nominal'];
        }

        if ($this->tagihanHasColumn('nominal_dibayar')) {
            $attributes['nominal_dibayar'] = $summary['total_paid'];
        }

        if ($this->tagihanHasColumn('status')) {
            $attributes['status'] = $summary['status'];
        }

        if ($this->tagihanHasColumn('jatuh_tempo')) {
            $attributes['jatuh_tempo'] = $setting?->jatuh_tempo;
        }

        if ($this->tagihanHasColumn('tanggal_pembayaran')) {
            $attributes['tanggal_pembayaran'] = $summary['is_lunas']
                ? $summary['latest_payment_date']
                : null;
        }

        $tagihan->fill($attributes);

        $tagihan->save();

        return $tagihan;
    }

    private function tagihanHasColumn(string $column): bool
    {
        if ($this->tagihanColumns === null) {
            $this->tagihanColumns = Schema::getColumnListing((new Tagihan())->getTable());
        }

        return in_array($column, $this->tagihanColumns, true);
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
            'period_summaries' => collect(),
            'tagihan' => null,
        ];
    }
}
