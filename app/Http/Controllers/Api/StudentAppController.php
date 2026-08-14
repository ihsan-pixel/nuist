<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Siswa;
use App\Models\SppSiswaBill;
use App\Models\SppSiswaTransaction;
use App\Models\User;
use App\Services\BniVirtualAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class StudentAppController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        [$user, $siswa] = $this->resolveStudentContext($request);
        [$bills, $payments] = $this->loadStudentFinanceData($siswa);

        $summary = $this->buildSummary($bills, $payments);
        $activeBill = $this->resolveActiveBill($bills);
        $lastPayment = $payments->first();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'greeting' => 'Selamat datang, ' . Str::of($siswa->nama_lengkap ?: $user->name)->trim()->before(' '),
                'student' => $this->serializeStudent($siswa, $user),
                'school' => $this->serializeSchool($siswa),
                'summary' => $summary,
                'active_bill' => $activeBill ? $this->serializeBill($activeBill) : null,
                'upcoming_reminder' => $this->buildUpcomingReminder($activeBill),
                'last_payment' => $lastPayment ? $this->serializeTransaction($lastPayment) : null,
                'recent_payments' => $payments->take(3)->map(fn (SppSiswaTransaction $payment) => $this->serializeTransaction($payment))->values(),
            ],
        ]);
    }

    public function bills(Request $request): JsonResponse
    {
        [, $siswa] = $this->resolveStudentContext($request);
        [$bills, $payments] = $this->loadStudentFinanceData($siswa);

        return response()->json([
            'message' => 'OK',
            'data' => [
                'student' => $this->serializeStudent($siswa),
                'summary' => $this->buildSummary($bills, $payments),
                'items' => $bills->map(fn (SppSiswaBill $bill) => $this->serializeBill($bill))->values(),
            ],
        ]);
    }

    public function payments(Request $request): JsonResponse
    {
        [, $siswa] = $this->resolveStudentContext($request);
        [$bills, $payments] = $this->loadStudentFinanceData($siswa);

        $activeBill = $this->resolveActiveBill($bills);
        $pendingPayment = $this->resolvePendingPayment($payments, $activeBill?->id);

        return response()->json([
            'message' => 'OK',
            'data' => [
                'student' => $this->serializeStudent($siswa),
                'summary' => $this->buildSummary($bills, $payments),
                'bni_va_enabled' => (bool) AppSetting::getSettings()->bni_va_enabled,
                'active_bill' => $activeBill ? $this->serializeBill($activeBill) : null,
                'active_payment' => $pendingPayment ? $this->serializeTransaction($pendingPayment) : null,
                'eligible_bills' => $bills
                    ->filter(fn (SppSiswaBill $bill) => in_array($bill->status, ['belum_lunas', 'sebagian'], true))
                    ->map(fn (SppSiswaBill $bill) => $this->serializeBill($bill))
                    ->values(),
                'recent_payments' => $payments->take(5)->map(fn (SppSiswaTransaction $payment) => $this->serializeTransaction($payment))->values(),
            ],
        ]);
    }

    public function createVirtualAccount(
        Request $request,
        SppSiswaBill $bill,
        BniVirtualAccountService $service
    ): JsonResponse {
        [$user, $siswa] = $this->resolveStudentContext($request);

        abort_unless((int) $bill->siswa_id === (int) $siswa->id, Response::HTTP_NOT_FOUND);

        $bill->loadMissing(['setting', 'madrasah', 'siswa', 'transactions']);
        $provider = $bill->setting->payment_provider ?? 'manual';

        if ($provider !== 'bni_va') {
            return response()->json([
                'message' => 'Tagihan ini belum menggunakan metode BNI Virtual Account.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $payment = $service->createOrReuseForBill($bill, $user->id);
        } catch (\Throwable $throwable) {
            return response()->json([
                'message' => $throwable->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $payment->loadMissing(['bill.setting']);
        $refreshedBill = $bill->fresh(['setting', 'transactions']);

        return response()->json([
            'message' => 'Virtual Account siap digunakan.',
            'data' => [
                'bill' => $refreshedBill ? $this->serializeBill($refreshedBill) : null,
                'payment' => $this->serializeTransaction($payment),
            ],
        ]);
    }

    public function paymentHistory(Request $request): JsonResponse
    {
        [, $siswa] = $this->resolveStudentContext($request);
        [, $payments] = $this->loadStudentFinanceData($siswa);

        $verifiedCount = $payments->where('status_verifikasi', 'diverifikasi')->count();
        $pendingCount = $payments->where('status_verifikasi', 'menunggu')->count();
        $rejectedCount = $payments->where('status_verifikasi', 'ditolak')->count();

        return response()->json([
            'message' => 'OK',
            'data' => [
                'student' => $this->serializeStudent($siswa),
                'summary' => [
                    'verified_count' => $verifiedCount,
                    'pending_count' => $pendingCount,
                    'rejected_count' => $rejectedCount,
                    'total_paid' => (int) round(
                        $payments
                            ->where('status_verifikasi', 'diverifikasi')
                            ->sum('nominal_bayar')
                    ),
                ],
                'items' => $payments->map(fn (SppSiswaTransaction $payment) => $this->serializeTransaction($payment))->values(),
            ],
        ]);
    }

    public function profile(Request $request): JsonResponse
    {
        [$user, $siswa] = $this->resolveStudentContext($request);
        [$bills, $payments] = $this->loadStudentFinanceData($siswa);

        return response()->json([
            'message' => 'OK',
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'nuist_id' => $user->nuist_id,
                ],
                'student' => $this->serializeStudent($siswa, $user),
                'school' => $this->serializeSchool($siswa),
                'summary' => $this->buildSummary($bills, $payments),
            ],
        ]);
    }

    private function resolveStudentContext(Request $request): array
    {
        /** @var User $user */
        $user = $request->user();

        abort_unless($user && $user->role === 'siswa', Response::HTTP_FORBIDDEN);

        $siswa = $this->resolveStudentFromLinkedUser($user);

        abort_unless($siswa, Response::HTTP_NOT_FOUND, 'Data siswa tidak ditemukan untuk akun ini.');

        return [$user, $siswa];
    }

    private function resolveStudentFromLinkedUser(User $user): ?Siswa
    {
        // NISN login links the user as S + base36(student ID). Resolve this
        // first because email is optional for student records.
        $linkedId = null;
        if (preg_match('/^S([0-9A-Z]+)$/', (string) $user->nuist_id, $matches)) {
            $linkedId = (int) base_convert($matches[1], 36, 10);
        }

        $query = Siswa::query()->with('madrasah');
        if ($linkedId) {
            $linked = (clone $query)
                ->whereKey($linkedId)
                ->where('madrasah_id', $user->madrasah_id)
                ->first();

            if ($linked) {
                return $linked;
            }
        }

        // Compatibility for student accounts made before NISN login existed.
        return (clone $query)
            ->where('email', $user->email)
            ->where('madrasah_id', $user->madrasah_id)
            ->first();
    }

    private function loadStudentFinanceData(Siswa $siswa): array
    {
        $bills = SppSiswaBill::query()
            ->with(['setting', 'transactions'])
            ->where('siswa_id', $siswa->id)
            ->orderByRaw('CASE WHEN status = "belum_lunas" THEN 0 WHEN status = "sebagian" THEN 1 ELSE 2 END')
            ->orderByDesc('periode')
            ->orderByDesc('id')
            ->get();

        $payments = SppSiswaTransaction::query()
            ->with(['bill.setting'])
            ->where('siswa_id', $siswa->id)
            ->latest('created_at')
            ->get();

        return [$bills, $payments];
    }

    private function buildSummary(Collection $bills, Collection $payments): array
    {
        $totalBills = $bills->count();
        $paidBills = $bills->where('status', 'lunas')->count();
        $unpaidBills = $bills->whereIn('status', ['belum_lunas', 'sebagian'])->count();
        $totalBilled = (int) round($bills->sum('total_tagihan'));
        $totalPaid = (int) round(
            $payments
                ->where('status_verifikasi', 'diverifikasi')
                ->sum('nominal_bayar')
        );
        $outstanding = max(0, $totalBilled - $totalPaid);

        return [
            'total_bills' => $totalBills,
            'paid_bills' => $paidBills,
            'unpaid_bills' => $unpaidBills,
            'pending_payments' => $payments->where('status_verifikasi', 'menunggu')->count(),
            'total_billed' => $totalBilled,
            'total_paid' => $totalPaid,
            'outstanding' => $outstanding,
            'payment_completion_rate' => $totalBills > 0
                ? (int) round(($paidBills / $totalBills) * 100)
                : 0,
        ];
    }

    private function resolveActiveBill(Collection $bills): ?SppSiswaBill
    {
        return $bills->firstWhere('status', 'belum_lunas')
            ?? $bills->firstWhere('status', 'sebagian')
            ?? $bills->first();
    }

    private function resolvePendingPayment(Collection $payments, ?int $billId): ?SppSiswaTransaction
    {
        if ($billId !== null) {
            $activePayment = $payments
                ->where('bill_id', $billId)
                ->where('status_verifikasi', 'menunggu')
                ->sortByDesc(function (SppSiswaTransaction $payment) {
                    return optional($payment->va_expired_at)->timestamp ?? 0;
                })
                ->first();

            if ($activePayment) {
                return $activePayment;
            }
        }

        return $payments->firstWhere('status_verifikasi', 'menunggu');
    }

    private function buildUpcomingReminder(?SppSiswaBill $bill): ?array
    {
        if (!$bill || !$bill->jatuh_tempo || $bill->status === 'lunas') {
            return null;
        }

        $days = now()->startOfDay()->diffInDays($bill->jatuh_tempo->copy()->startOfDay(), false);

        if ($days < 0 || $days > 7) {
            return null;
        }

        return [
            'title' => $days === 0 ? 'Jatuh tempo hari ini' : 'Tagihan jatuh tempo H-' . $days,
            'message' => 'Tagihan ' . ($bill->nomor_tagihan ?? '-') . ' jatuh tempo pada ' . $bill->jatuh_tempo->format('Y-m-d') . '.',
            'days_left' => $days,
            'due_date' => $bill->jatuh_tempo->format('Y-m-d'),
        ];
    }

    private function serializeStudent(Siswa $siswa, ?User $user = null): array
    {
        return [
            'id' => $siswa->id,
            'name' => $siswa->nama_lengkap,
            'email' => $siswa->email ?: $user?->email,
            'nis' => $siswa->nis,
            'nisn' => $siswa->nisn,
            'kelas' => $siswa->kelas,
            'jurusan' => $siswa->jurusan,
            'phone' => $siswa->no_hp,
            'parent_name' => $siswa->nama_orang_tua_wali ?: $siswa->nama_ayah ?: $siswa->nama_ibu,
            'entry_year' => $siswa->tahun_masuk,
            'gender' => $siswa->jenis_kelamin,
            'birth_place' => $siswa->tempat_lahir,
            'birth_date' => optional($siswa->tanggal_lahir)->format('Y-m-d'),
            'address' => $siswa->alamat,
        ];
    }

    private function serializeSchool(Siswa $siswa): array
    {
        return [
            'id' => $siswa->madrasah?->id,
            'name' => $siswa->madrasah?->name,
            'scod' => $siswa->madrasah?->scod,
            'address' => $siswa->madrasah?->alamat,
            'phone' => $siswa->madrasah?->telepon,
            'email' => $siswa->madrasah?->email,
        ];
    }

    private function serializeBill(SppSiswaBill $bill): array
    {
        $provider = $bill->setting->payment_provider ?? 'manual';

        return [
            'id' => $bill->id,
            'nomor_tagihan' => $bill->nomor_tagihan,
            'jenis_tagihan' => $bill->jenis_tagihan,
            'periode' => $bill->periode,
            'jatuh_tempo' => optional($bill->jatuh_tempo)->format('Y-m-d'),
            'total_tagihan' => (int) round((float) $bill->total_tagihan),
            'outstanding_amount' => (int) round((float) $bill->outstanding_amount),
            'status' => $bill->status,
            'status_label' => $this->billStatusLabel($bill->status),
            'payment_provider' => $provider,
            'can_generate_va' => $provider === 'bni_va'
                && (float) $bill->outstanding_amount > 0
                && (bool) AppSetting::getSettings()->bni_va_enabled,
            'note' => $bill->catatan,
        ];
    }

    private function serializeTransaction(SppSiswaTransaction $payment): array
    {
        return [
            'id' => $payment->id,
            'bill_id' => $payment->bill_id,
            'nomor_transaksi' => $payment->nomor_transaksi,
            'tanggal_bayar' => optional($payment->tanggal_bayar)->format('Y-m-d'),
            'nominal_bayar' => (int) round((float) $payment->nominal_bayar),
            'metode_pembayaran' => $payment->metode_pembayaran,
            'payment_channel' => $payment->payment_channel,
            'status_verifikasi' => $payment->status_verifikasi,
            'status_label' => $this->paymentStatusLabel($payment->status_verifikasi),
            'keterangan' => $payment->keterangan,
            'va_number' => $payment->va_number,
            'va_expired_at' => optional($payment->va_expired_at)->toIso8601String(),
            'bill' => $payment->bill ? [
                'id' => $payment->bill->id,
                'nomor_tagihan' => $payment->bill->nomor_tagihan,
                'jenis_tagihan' => $payment->bill->jenis_tagihan,
                'status' => $payment->bill->status,
            ] : null,
        ];
    }

    private function billStatusLabel(?string $status): string
    {
        return match ($status) {
            'lunas' => 'Lunas',
            'sebagian' => 'Sebagian',
            default => 'Belum lunas',
        };
    }

    private function paymentStatusLabel(?string $status): string
    {
        return match ($status) {
            'diverifikasi' => 'Terverifikasi',
            'ditolak' => 'Ditolak',
            default => 'Menunggu',
        };
    }
}
