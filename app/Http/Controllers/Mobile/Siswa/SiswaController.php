<?php

namespace App\Http\Controllers\Mobile\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Notification;
use App\Models\Siswa;
use App\Models\SppSiswaBill;
use App\Models\SppSiswaTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $data = $this->buildSiswaData();

        return view('mobile.siswa.dashboard', $data);
    }

    public function tagihan()
    {
        $data = $this->buildSiswaData();

        return view('mobile.siswa.tagihan', $data);
    }

    public function pembayaran()
    {
        $data = $this->buildSiswaData();

        return view('mobile.siswa.pembayaran', $data);
    }

    public function riwayat(Request $request)
    {
        $data = $this->buildSiswaData();
        $payments = $data['payments'];

        if ($request->filled('status')) {
            $payments = $payments->filter(function ($payment) use ($request) {
                return $payment->status_verifikasi === $request->string('status')->toString();
            });
        }

        if ($request->filled('bulan')) {
            $bulan = (int) $request->input('bulan');
            $payments = $payments->filter(function ($payment) use ($bulan) {
                return optional($payment->tanggal_bayar)->month === $bulan;
            });
        }

        $data['filteredPayments'] = $payments->values();

        return view('mobile.siswa.riwayat', $data);
    }

    public function detail(int $tagihanId)
    {
        $data = $this->buildSiswaData();
        $tagihan = $data['tagihans']->firstWhere('id', $tagihanId);

        abort_if(!$tagihan, 404);

        $data['selectedTagihan'] = $tagihan;
        $data['selectedPayment'] = $data['payments']
            ->where('bill_id', $tagihan->id)
            ->sortByDesc('tanggal_bayar')
            ->first();

        return view('mobile.siswa.detail', $data);
    }

    public function bukti(int $paymentId)
    {
        $data = $this->buildSiswaData();
        $payment = $data['payments']->firstWhere('id', $paymentId);

        abort_if(!$payment, 404);

        $data['selectedPayment'] = $payment;
        $data['selectedTagihan'] = $data['tagihans']->firstWhere('id', $payment->bill_id) ?? $payment->bill;

        return view('mobile.siswa.bukti', $data);
    }

    public function notifications()
    {
        $data = $this->buildSiswaData();

        return view('mobile.siswa.notifikasi', $data);
    }

    public function profile()
    {
        $data = $this->buildSiswaData();

        return view('mobile.siswa.profile', $data);
    }

    public function chat()
    {
        $data = $this->buildSiswaData();

        return view('mobile.siswa.chat', $data);
    }

    public function sendChat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $user = Auth::user();
        $admin = $this->resolveAdminContact($user);

        if (!$admin) {
            return redirect()->route('mobile.siswa.chat')->with('error', 'Admin sekolah belum tersedia.');
        }

        Chat::create([
            'sender_id' => $user->id,
            'receiver_id' => $admin->id,
            'message' => $request->input('message'),
            'is_read' => false,
        ]);

        return redirect()->route('mobile.siswa.chat')->with('success', 'Pesan berhasil dikirim.');
    }

    private function buildSiswaData(): array
    {
        $user = Auth::user();
        $siswa = $this->resolveSiswaRecord($user);
        $madrasahId = $siswa?->madrasah_id ?? $user->madrasah_id;

        $tagihans = $siswa
            ? SppSiswaBill::query()
                ->with(['setting', 'transactions'])
                ->where('siswa_id', $siswa->id)
                ->orderByRaw('CASE WHEN status = "belum_lunas" THEN 0 WHEN status = "sebagian" THEN 1 ELSE 2 END')
                ->orderBy('jatuh_tempo')
                ->get()
            : collect();

        $payments = $siswa
            ? SppSiswaTransaction::query()
                ->with('bill')
                ->where('siswa_id', $siswa->id)
                ->latest('tanggal_bayar')
                ->get()
            : collect();

        $activeTagihan = $tagihans->firstWhere('status', 'belum_lunas')
            ?? $tagihans->firstWhere('status', 'sebagian')
            ?? $tagihans->first();

        $lastPayment = $payments->first();
        $chartSummary = [
            'lunas' => $tagihans->where('status', 'lunas')->count(),
            'belum' => $tagihans->whereIn('status', ['belum_lunas', 'sebagian'])->count(),
        ];

        $generatedReminders = $this->generateReminderNotifications($tagihans);

        $notifications = collect(
            Notification::query()
                ->forUser($user->id)
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'id' => 'db-' . $item->id,
                        'title' => $item->title,
                        'message' => $item->message,
                        'type' => $item->type,
                        'is_read' => (bool) $item->is_read,
                        'created_at' => $item->created_at,
                    ];
                })
                ->all()
        )
            ->merge($generatedReminders)
            ->sortByDesc('created_at')
            ->values();

        $adminContact = $this->resolveAdminContact($user);
        $chats = collect();

        if ($adminContact) {
            $chats = Chat::query()
                ->betweenUsers($user->id, $adminContact->id)
                ->with(['sender', 'receiver'])
                ->orderBy('created_at')
                ->get();
        }

        return [
            'studentUser' => $user,
            'studentRecord' => $siswa,
            'studentSchool' => $siswa?->madrasah ?? $user->madrasah,
            'tagihans' => $tagihans,
            'payments' => $payments,
            'activeTagihan' => $activeTagihan,
            'lastPayment' => $lastPayment,
            'chartSummary' => $chartSummary,
            'notifications' => $notifications,
            'adminContact' => $adminContact,
            'chats' => $chats,
            'paymentCompletionRate' => $tagihans->count() > 0
                ? (int) round(($chartSummary['lunas'] / $tagihans->count()) * 100)
                : 0,
            'totalTagihanNominal' => $tagihans->sum('total_tagihan'),
            'totalTerbayarNominal' => $payments->where('status_verifikasi', 'diverifikasi')->sum('nominal_bayar'),
            'upcomingReminder' => $generatedReminders->first(),
        ];
    }

    private function generateReminderNotifications(Collection $tagihans): Collection
    {
        return $tagihans
            ->filter(function ($tagihan) {
                if (!$tagihan->jatuh_tempo || $tagihan->status === 'lunas') {
                    return false;
                }

                $days = now()->startOfDay()->diffInDays(Carbon::parse($tagihan->jatuh_tempo)->startOfDay(), false);

                return $days >= 0 && $days <= 3;
            })
            ->map(function ($tagihan) {
                $days = now()->startOfDay()->diffInDays(Carbon::parse($tagihan->jatuh_tempo)->startOfDay(), false);
                $label = $days === 0 ? 'Hari ini' : 'H-' . $days;

                return (object) [
                    'id' => 'reminder-' . $tagihan->id,
                    'title' => 'Reminder jatuh tempo ' . $label,
                    'message' => 'Tagihan ' . ($tagihan->nomor_tagihan ?? '-') . ' jatuh tempo pada ' . optional($tagihan->jatuh_tempo)->translatedFormat('d M Y') . '.',
                    'type' => 'reminder',
                    'is_read' => false,
                    'created_at' => now()->subMinutes(5),
                ];
            })
            ->values();
    }

    private function resolveSiswaRecord(User $user): ?Siswa
    {
        return Siswa::query()
            ->with('madrasah')
            ->where('email', $user->email)
            ->where('madrasah_id', $user->madrasah_id)
            ->first();
    }

    private function resolveAdminContact(User $user): ?User
    {
        return User::query()
            ->where('madrasah_id', $user->madrasah_id)
            ->whereIn('role', ['admin', 'pengurus'])
            ->where('id', '!=', $user->id)
            ->first()
            ?? User::query()->where('role', 'super_admin')->first();
    }
}
