<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\Siswa;
use App\Models\SppSiswaBill;
use App\Models\SppSiswaSetting;
use App\Models\SppSiswaTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SppSiswaController extends Controller
{
    public function dashboard(Request $request)
    {
        $scope = $this->resolveScope($request);

        $stats = $this->buildStats($scope['selectedMadrasahId']);

        $recentBills = $this->billQuery($scope['selectedMadrasahId'])
            ->with(['siswa', 'madrasah'])
            ->latest()
            ->take(8)
            ->get();

        $recentTransactions = $this->transactionQuery($scope['selectedMadrasahId'])
            ->with(['bill', 'siswa', 'madrasah'])
            ->latest('tanggal_bayar')
            ->take(8)
            ->get();

        return view('spp-siswa.dashboard', [
            'madrasahOptions' => $scope['madrasahOptions'],
            'selectedMadrasahId' => $scope['selectedMadrasahId'],
            'stats' => $stats,
            'recentBills' => $recentBills,
            'recentTransactions' => $recentTransactions,
            'userRole' => auth()->user()->role,
        ]);
    }

    public function tagihan(Request $request)
    {
        $scope = $this->resolveScope($request);
        $selectedMadrasahId = $scope['selectedMadrasahId'];
        $studentBaseQuery = $this->studentQuery($selectedMadrasahId);
        $user = auth()->user();

        $bills = $this->billQuery($selectedMadrasahId)
            ->with(['siswa', 'madrasah', 'setting', 'transactions' => fn ($query) => $query->latest('id')])
            ->when($request->filled('kelas'), function ($query) use ($request) {
                $query->whereHas('siswa', fn ($siswa) => $siswa->where('kelas', 'like', '%' . trim((string) $request->kelas) . '%'));
            })
            ->when($request->filled('jurusan'), function ($query) use ($request) {
                $query->whereHas('siswa', fn ($siswa) => $siswa->where('jurusan', 'like', '%' . trim((string) $request->jurusan) . '%'));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = trim((string) $request->q);
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('nomor_tagihan', 'like', '%' . $keyword . '%')
                        ->orWhere('periode', 'like', '%' . $keyword . '%')
                        ->orWhereHas('siswa', function ($siswa) use ($keyword) {
                            $siswa->where('nama_lengkap', 'like', '%' . $keyword . '%')
                                ->orWhere('nis', 'like', '%' . $keyword . '%');
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $students = (clone $studentBaseQuery)->orderBy('nama_lengkap')->get();
        $settings = $this->settingQuery($selectedMadrasahId)
            ->where('is_active', true)
            ->when($user->role === 'admin', fn ($query) => $query->where('payment_provider', 'bni_va'))
            ->orderByDesc('tahun_ajaran')
            ->get();
        $jurusanOptions = (clone $studentBaseQuery)
            ->select('jurusan')
            ->whereNotNull('jurusan')
            ->distinct()
            ->orderBy('jurusan')
            ->pluck('jurusan');
        $kelasOptions = (clone $studentBaseQuery)
            ->select('kelas')
            ->whereNotNull('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $hasActiveBniVaSetting = $this->hasActiveBniVaSettingForMadrasah($selectedMadrasahId);

        return view('spp-siswa.tagihan', [
            'madrasahOptions' => $scope['madrasahOptions'],
            'selectedMadrasahId' => $selectedMadrasahId,
            'bills' => $bills,
            'students' => $students,
            'settings' => $settings,
            'jurusanOptions' => $jurusanOptions,
            'kelasOptions' => $kelasOptions,
            'userRole' => $user->role,
            'hasActiveBniVaSetting' => $hasActiveBniVaSetting,
        ]);
    }

    public function storeTagihan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'madrasah_id' => $this->madrasahRules(),
            'siswa_id' => ['required', 'integer', Rule::exists('siswa', 'id')],
            'setting_id' => ['nullable', 'integer', Rule::exists('spp_siswa_settings', 'id')],
            'periode' => ['required', 'date_format:Y-m'],
            'jatuh_tempo' => ['required', 'date'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'denda' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['belum_lunas', 'sebagian', 'lunas'])],
            'catatan' => ['nullable', 'string'],
        ]);

        $this->ensureMadrasahAccess((int) $validated['madrasah_id']);
        $this->ensureTagihanCreationAllowed((int) $validated['madrasah_id'], $validated['setting_id'] ?? null);

        $siswa = Siswa::query()
            ->whereKey($validated['siswa_id'])
            ->where('madrasah_id', $validated['madrasah_id'])
            ->firstOrFail();

        $setting = null;
        if (!empty($validated['setting_id'])) {
            $setting = SppSiswaSetting::query()
                ->whereKey($validated['setting_id'])
                ->where('madrasah_id', $validated['madrasah_id'])
                ->firstOrFail();
        }

        $periodeDate = Carbon::createFromFormat('Y-m', $validated['periode'])->startOfMonth();
        $nominal = (float) $validated['nominal'];
        $denda = (float) ($validated['denda'] ?? 0);

        SppSiswaBill::create([
            'siswa_id' => $siswa->id,
            'madrasah_id' => $siswa->madrasah_id,
            'setting_id' => $setting?->id,
            'nomor_tagihan' => $this->generateBillNumber($siswa, $periodeDate),
            'periode' => $periodeDate->format('Y-m'),
            'jatuh_tempo' => $validated['jatuh_tempo'],
            'nominal' => $nominal,
            'denda' => $denda,
            'total_tagihan' => $nominal + $denda,
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return back()->with('success', 'Tagihan SPP siswa berhasil dibuat.');
    }

    public function storeBulkTagihan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'madrasah_id' => $this->madrasahRules(),
            'setting_id' => ['nullable', 'integer', Rule::exists('spp_siswa_settings', 'id')],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'periode' => ['required', 'date_format:Y-m'],
            'jatuh_tempo' => ['required', 'date'],
            'nominal' => ['required', 'numeric', 'min:0'],
            'denda' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['belum_lunas', 'sebagian', 'lunas'])],
            'catatan' => ['nullable', 'string'],
        ]);

        $this->ensureMadrasahAccess((int) $validated['madrasah_id']);
        $this->ensureTagihanCreationAllowed((int) $validated['madrasah_id'], $validated['setting_id'] ?? null);

        $setting = null;
        if (!empty($validated['setting_id'])) {
            $setting = SppSiswaSetting::query()
                ->whereKey($validated['setting_id'])
                ->where('madrasah_id', $validated['madrasah_id'])
                ->firstOrFail();
        }

        $periodeDate = Carbon::createFromFormat('Y-m', $validated['periode'])->startOfMonth();
        $nominal = (float) $validated['nominal'];
        $denda = (float) ($validated['denda'] ?? 0);

        $students = $this->studentQuery((int) $validated['madrasah_id'])
            ->when(!empty($validated['jurusan']), fn ($query) => $query->where('jurusan', trim((string) $validated['jurusan'])))
            ->when(!empty($validated['kelas']), fn ($query) => $query->where('kelas', trim((string) $validated['kelas'])))
            ->orderBy('nama_lengkap')
            ->get();

        if ($students->isEmpty()) {
            return back()->withErrors([
                'jurusan' => 'Tidak ada siswa yang cocok dengan filter madrasah, jurusan, dan kelas tersebut.',
            ])->withInput();
        }

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($students, $validated, $setting, $periodeDate, $nominal, $denda, &$created, &$skipped) {
            foreach ($students as $siswa) {
                $exists = SppSiswaBill::query()
                    ->where('siswa_id', $siswa->id)
                    ->where('periode', $periodeDate->format('Y-m'))
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                SppSiswaBill::create([
                    'siswa_id' => $siswa->id,
                    'madrasah_id' => $siswa->madrasah_id,
                    'setting_id' => $setting?->id,
                    'nomor_tagihan' => $this->generateBillNumber($siswa, $periodeDate),
                    'periode' => $periodeDate->format('Y-m'),
                    'jatuh_tempo' => $validated['jatuh_tempo'],
                    'nominal' => $nominal,
                    'denda' => $denda,
                    'total_tagihan' => $nominal + $denda,
                    'status' => $validated['status'],
                    'catatan' => $validated['catatan'] ?? null,
                ]);

                $created++;
            }
        });

        return back()->with(
            'success',
            "Tagihan massal selesai. {$created} tagihan dibuat, {$skipped} dilewati karena periode yang sama sudah ada."
        );
    }

    public function transaksi(Request $request)
    {
        $scope = $this->resolveScope($request);
        $selectedMadrasahId = $scope['selectedMadrasahId'];

        $transactions = $this->transactionQuery($selectedMadrasahId)
            ->with(['bill', 'siswa', 'madrasah', 'creator'])
            ->when($request->filled('status_verifikasi'), fn ($query) => $query->where('status_verifikasi', $request->status_verifikasi))
            ->when($request->filled('q'), function ($query) use ($request) {
                $keyword = trim((string) $request->q);
                $query->where(function ($builder) use ($keyword) {
                    $builder->where('nomor_transaksi', 'like', '%' . $keyword . '%')
                        ->orWhereHas('siswa', function ($siswa) use ($keyword) {
                            $siswa->where('nama_lengkap', 'like', '%' . $keyword . '%')
                                ->orWhere('nis', 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('bill', fn ($bill) => $bill->where('nomor_tagihan', 'like', '%' . $keyword . '%'));
                });
            })
            ->latest('tanggal_bayar')
            ->paginate(12)
            ->withQueryString();

        $bills = $this->billQuery($selectedMadrasahId)
            ->with('siswa')
            ->orderByDesc('jatuh_tempo')
            ->get();

        return view('spp-siswa.transaksi', [
            'madrasahOptions' => $scope['madrasahOptions'],
            'selectedMadrasahId' => $selectedMadrasahId,
            'transactions' => $transactions,
            'bills' => $bills,
            'userRole' => auth()->user()->role,
        ]);
    }

    public function storeTransaksi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bill_id' => ['required', 'integer', Rule::exists('spp_siswa_bills', 'id')],
            'tanggal_bayar' => ['required', 'date'],
            'nominal_bayar' => ['required', 'numeric', 'min:0'],
            'metode_pembayaran' => ['required', 'string', 'max:100'],
            'status_verifikasi' => ['required', Rule::in(['menunggu', 'diverifikasi', 'ditolak'])],
            'keterangan' => ['nullable', 'string'],
        ]);

        $bill = SppSiswaBill::query()->with('siswa')->findOrFail($validated['bill_id']);
        $this->ensureMadrasahAccess((int) $bill->madrasah_id);

        DB::transaction(function () use ($validated, $bill) {
            SppSiswaTransaction::create([
                'bill_id' => $bill->id,
                'siswa_id' => $bill->siswa_id,
                'madrasah_id' => $bill->madrasah_id,
                'nomor_transaksi' => $this->generateTransactionNumber($bill),
                'tanggal_bayar' => $validated['tanggal_bayar'],
                'nominal_bayar' => $validated['nominal_bayar'],
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_verifikasi' => $validated['status_verifikasi'],
                'keterangan' => $validated['keterangan'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $verifiedPaid = SppSiswaTransaction::query()
                ->where('bill_id', $bill->id)
                ->where('status_verifikasi', 'diverifikasi')
                ->sum('nominal_bayar');

            $status = 'belum_lunas';
            if ($verifiedPaid >= $bill->total_tagihan) {
                $status = 'lunas';
            } elseif ($verifiedPaid > 0) {
                $status = 'sebagian';
            }

            $bill->update(['status' => $status]);
        });

        return back()->with('success', 'Transaksi SPP siswa berhasil disimpan.');
    }

    public function laporan(Request $request)
    {
        $scope = $this->resolveScope($request);
        $selectedMadrasahId = $scope['selectedMadrasahId'];

        $reportRows = $this->studentQuery($selectedMadrasahId)
            ->withCount([
                'sppBills as total_tagihan',
                'sppBills as tagihan_lunas' => fn ($query) => $query->where('status', 'lunas'),
                'sppBills as tagihan_belum_lunas' => fn ($query) => $query->whereIn('status', ['belum_lunas', 'sebagian']),
            ])
            ->withSum('sppBills as total_nominal_tagihan', 'total_tagihan')
            ->withSum(['sppTransactions as total_nominal_terbayar' => fn ($query) => $query->where('status_verifikasi', 'diverifikasi')], 'nominal_bayar')
            ->orderBy('nama_lengkap')
            ->paginate(15)
            ->withQueryString();

        $classSummary = $this->studentQuery($selectedMadrasahId)
            ->select('kelas', DB::raw('COUNT(*) as jumlah_siswa'))
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();

        return view('spp-siswa.laporan', [
            'madrasahOptions' => $scope['madrasahOptions'],
            'selectedMadrasahId' => $selectedMadrasahId,
            'reportRows' => $reportRows,
            'classSummary' => $classSummary,
            'userRole' => auth()->user()->role,
        ]);
    }

    public function pengaturan(Request $request)
    {
        $scope = $this->resolveScope($request);
        $selectedMadrasahId = $scope['selectedMadrasahId'];

        $settings = $this->settingQuery($selectedMadrasahId)
            ->with('madrasah')
            ->orderByDesc('tahun_ajaran')
            ->paginate(10)
            ->withQueryString();

        return view('spp-siswa.pengaturan', [
            'madrasahOptions' => $scope['madrasahOptions'],
            'selectedMadrasahId' => $selectedMadrasahId,
            'settings' => $settings,
            'userRole' => auth()->user()->role,
        ]);
    }

    public function storePengaturan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'madrasah_id' => $this->madrasahRules(),
            'tahun_ajaran' => ['required', 'string', 'max:20'],
            'payment_provider' => ['required', Rule::in($this->allowedPaymentProviders())],
            'va_expired_hours' => ['nullable', 'integer', 'min:1', 'max:720'],
            'is_active' => ['nullable', 'boolean'],
            'catatan' => ['nullable', 'string'],
            'payment_notes' => ['nullable', 'string'],
        ]);

        $this->ensureMadrasahAccess((int) $validated['madrasah_id']);

        SppSiswaSetting::updateOrCreate(
            [
                'madrasah_id' => $validated['madrasah_id'],
                'tahun_ajaran' => $validated['tahun_ajaran'],
            ],
            [
                'payment_provider' => $validated['payment_provider'],
                'va_expired_hours' => $validated['va_expired_hours'] ?? 24,
                'is_active' => $request->boolean('is_active', true),
                'catatan' => $validated['catatan'] ?? null,
                'payment_notes' => $validated['payment_notes'] ?? null,
            ]
        );

        return back()->with('success', 'Pengaturan SPP siswa berhasil disimpan.');
    }

    private function resolveScope(Request $request): array
    {
        $user = auth()->user();
        $madrasahOptions = $user->role === 'admin'
            ? Madrasah::query()->whereKey($user->madrasah_id)->orderBy('name')->get()
            : Madrasah::query()->orderBy('name')->get();

        $selectedMadrasahId = $user->role === 'admin'
            ? (int) $user->madrasah_id
            : $request->integer('madrasah_id');

        return [
            'madrasahOptions' => $madrasahOptions,
            'selectedMadrasahId' => $selectedMadrasahId,
        ];
    }

    private function buildStats(?int $madrasahId): array
    {
        $studentQuery = $this->studentQuery($madrasahId);
        $billQuery = $this->billQuery($madrasahId);
        $transactionQuery = $this->transactionQuery($madrasahId);

        return [
            'total_siswa' => (clone $studentQuery)->count(),
            'total_tagihan' => (clone $billQuery)->count(),
            'tagihan_lunas' => (clone $billQuery)->where('status', 'lunas')->count(),
            'tagihan_belum_lunas' => (clone $billQuery)->whereIn('status', ['belum_lunas', 'sebagian'])->count(),
            'total_transaksi' => (clone $transactionQuery)->count(),
            'nominal_tagihan' => (clone $billQuery)->sum('total_tagihan'),
            'nominal_terbayar' => (clone $transactionQuery)->where('status_verifikasi', 'diverifikasi')->sum('nominal_bayar'),
            'pengaturan_aktif' => $this->settingQuery($madrasahId)->where('is_active', true)->count(),
        ];
    }

    private function studentQuery(?int $madrasahId)
    {
        return Siswa::query()->when($madrasahId, fn ($query) => $query->where('madrasah_id', $madrasahId));
    }

    private function billQuery(?int $madrasahId)
    {
        return SppSiswaBill::query()->when($madrasahId, fn ($query) => $query->where('madrasah_id', $madrasahId));
    }

    private function transactionQuery(?int $madrasahId)
    {
        return SppSiswaTransaction::query()->when($madrasahId, fn ($query) => $query->where('madrasah_id', $madrasahId));
    }

    private function settingQuery(?int $madrasahId)
    {
        return SppSiswaSetting::query()->when($madrasahId, fn ($query) => $query->where('madrasah_id', $madrasahId));
    }

    private function madrasahRules(): array
    {
        $user = auth()->user();

        return [
            'required',
            'integer',
            $user->role === 'admin'
                ? Rule::in([$user->madrasah_id])
                : Rule::exists('madrasahs', 'id'),
        ];
    }

    private function ensureMadrasahAccess(int $madrasahId): void
    {
        $user = auth()->user();

        abort_if($user->role === 'admin' && (int) $user->madrasah_id !== $madrasahId, 403);
    }

    private function ensureTagihanCreationAllowed(int $madrasahId, ?int $settingId): void
    {
        $user = auth()->user();

        if ($user->role !== 'admin') {
            return;
        }

        if (!$this->hasActiveBniVaSettingForMadrasah($madrasahId)) {
            throw ValidationException::withMessages([
                'setting_id' => 'Admin hanya bisa membuat tagihan setelah tersedia pengaturan aktif dengan provider BNI Virtual Account.',
            ]);
        }

        if (!$settingId) {
            throw ValidationException::withMessages([
                'setting_id' => 'Admin wajib memilih pengaturan aktif BNI Virtual Account saat membuat tagihan.',
            ]);
        }

        $validSetting = SppSiswaSetting::query()
            ->whereKey($settingId)
            ->where('madrasah_id', $madrasahId)
            ->where('is_active', true)
            ->where('payment_provider', 'bni_va')
            ->exists();

        if (!$validSetting) {
            throw ValidationException::withMessages([
                'setting_id' => 'Pengaturan yang dipilih untuk admin harus menggunakan provider BNI Virtual Account.',
            ]);
        }
    }

    private function hasActiveBniVaSettingForMadrasah(?int $madrasahId): bool
    {
        if (!$madrasahId) {
            return false;
        }

        return SppSiswaSetting::query()
            ->where('madrasah_id', $madrasahId)
            ->where('is_active', true)
            ->where('payment_provider', 'bni_va')
            ->exists();
    }

    private function allowedPaymentProviders(): array
    {
        return auth()->user()->role === 'super_admin'
            ? ['manual', 'bni_va']
            : ['bni_va'];
    }

    private function generateBillNumber(Siswa $siswa, Carbon $periode): string
    {
        return sprintf(
            'SPP-%s-%s-%04d',
            $periode->format('Ym'),
            $siswa->id,
            SppSiswaBill::query()->whereYear('created_at', now()->year)->count() + 1
        );
    }

    private function generateTransactionNumber(SppSiswaBill $bill): string
    {
        return sprintf(
            'TRX-SPP-%s-%04d',
            now()->format('Ymd'),
            SppSiswaTransaction::query()->whereDate('created_at', today())->count() + 1 + $bill->id
        );
    }
}
