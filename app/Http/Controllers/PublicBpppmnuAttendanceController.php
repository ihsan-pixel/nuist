<?php

namespace App\Http\Controllers;

use App\Models\BpppmnuEventGuestAttendance;
use App\Models\BpppmnuEventGuestInvitation;
use App\Models\BpppmnuEventInvitation;
use App\Services\BpppmnuAttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PublicBpppmnuAttendanceController extends Controller
{
    private const CLAIM_TTL_MINUTES = 10;

    public function show(Request $request, string $token, BpppmnuAttendanceService $service)
    {
        $event = $this->claimedEvent($request, $service, $token);
        if (! $event) {
            $event = $service->claimPublicToken($token);
            abort_unless($event, 404, BpppmnuAttendanceService::INVALID_QR);
            $request->session()->put($this->claimKey($token), [
                'event_id' => $event->id,
                'expires_at' => min($event->attendance_close_at->timestamp, now()->addMinutes(self::CLAIM_TTL_MINUTES)->timestamp),
            ]);
        }
        $nonce = Str::random(48);
        $request->session()->put($this->nonceKey($token), $nonce);

        return response()->view('public.bpppmnu-attendance.show', compact('event', 'token', 'nonce'))
            ->header('Cache-Control', 'no-store, private');
    }

    public function participants(Request $request, string $token, BpppmnuAttendanceService $service)
    {
        $event = $this->event($request, $service, $token);
        $data = $request->validate(['q' => 'required|string|min:2|max:100']);
        $needle = trim($data['q']);

        $participants = collect();
        if ($event->allowsRegisteredAttendance()) {
            $participants = $event->invitations()
                ->whereHas('user', fn ($query) => $query->where('is_active', true)->where('name', 'like', '%'.$needle.'%'))
                ->with(['user:id,name,role,ketugasan,jabatan,instansi_asal,madrasah_id,no_hp', 'user.madrasah:id,name', 'user.bpppmnuMember:user_id,jabatan,instansi_asal'])
                ->limit(15)->get()->map(fn ($invitation) => [
                'id' => $invitation->id,
                'type' => 'registered',
                'name' => $invitation->user->name,
                'position' => $invitation->user->bpppmnuMember?->jabatan ?: ($invitation->user->jabatan ?: $invitation->user->ketugasan),
                'organization' => $invitation->user->bpppmnuMember?->instansi_asal ?: ($invitation->user->madrasah?->name ?: $invitation->user->instansi_asal),
                'attended' => $event->attendances()->where('user_id', $invitation->user_id)->exists(),
            ]);
        }
        if ($event->allowsGuestAttendance() && $participants->count() < 15) {
            $guestParticipants = $event->guestInvitations()->where('name', 'like', '%'.$needle.'%')
                ->with('attendance:id,guest_invitation_id')->limit(15 - $participants->count())->get()->map(fn ($invitation) => [
                    'id' => $invitation->id,
                    'type' => 'guest',
                    'name' => $invitation->name,
                    'position' => 'Tamu undangan',
                    'organization' => $invitation->organization,
                    'attended' => (bool) $invitation->attendance,
                ]);
            $participants = $participants->concat($guestParticipants);
        }

        return response()->json(['data' => $participants])->header('Cache-Control', 'no-store, private');
    }

    public function store(Request $request, string $token, BpppmnuAttendanceService $service)
    {
        $event = $this->event($request, $service, $token);
        $data = $request->validate([
            'participant_type' => ['required', Rule::in(['registered', 'guest'])],
            'invitation_id' => 'required_if:participant_type,registered|nullable|integer',
            'guest_invitation_id' => 'required_if:participant_type,guest|nullable|integer',
            'verification' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'nonce' => 'required|string|size:48',
        ]);

        $expectedNonce = (string) $request->session()->get($this->nonceKey($token), '');
        if ($expectedNonce === '' || ! hash_equals($expectedNonce, $data['nonce'])) {
            throw ValidationException::withMessages(['attendance' => 'Form presensi sudah tidak berlaku. Muat ulang halaman dan coba lagi.']);
        }

        if ($data['participant_type'] === 'registered') {
            $invitation = BpppmnuEventInvitation::with('user')->whereKey($data['invitation_id'])->where('event_id', $event->id)->first();
            if (! $invitation || ! $event->allowsRegisteredAttendance()) {
                throw ValidationException::withMessages(['participant' => 'Nama peserta tidak terdaftar pada agenda ini.']);
            }
            $this->verifyParticipant($event, $invitation, (string) ($data['verification'] ?? ''));
            $result = $service->recordPublicRegistered($invitation, $event, $token, $data['latitude'] ?? null, $data['longitude'] ?? null);
            $name = $invitation->user->name;
            $confirmationCode = Str::upper(Str::random(12));
        } else {
            $guestInvitation = BpppmnuEventGuestInvitation::whereKey($data['guest_invitation_id'] ?? null)->where('event_id', $event->id)->first();
            if (! $guestInvitation || ! $event->allowsGuestAttendance()) {
                throw ValidationException::withMessages(['participant' => 'Nama tamu tidak terdaftar pada agenda ini.']);
            }
            $result = $service->recordPublicGuestInvitation($guestInvitation, $event, $token, $data, $request);
            $name = $guestInvitation->name;
            $confirmationCode = $result['attendance']->confirmation_code;
        }

        $request->session()->forget($this->nonceKey($token));
        $request->session()->put('bpppmnu_receipt_'.$confirmationCode, [
            'event' => $event->name,
            'name' => $name,
            'attended_at' => $result['attendance']->attended_at->format('d-m-Y H:i:s').' WIB',
            'duplicate' => $result['duplicate'],
        ]);

        return redirect()->route('public.bpppmnu.success', ['token' => $token, 'code' => $confirmationCode]);
    }

    public function success(Request $request, string $token, string $code, BpppmnuAttendanceService $service)
    {
        $this->event($request, $service, $token);
        $receipt = $request->session()->get('bpppmnu_receipt_'.$code);
        abort_unless($receipt, 404);

        return response()->view('public.bpppmnu-attendance.success', compact('receipt', 'code'))
            ->header('Cache-Control', 'no-store, private');
    }

    private function event(Request $request, BpppmnuAttendanceService $service, string $token)
    {
        $event = $this->claimedEvent($request, $service, $token);
        abort_unless($event, 404, BpppmnuAttendanceService::INVALID_QR);

        return $event;
    }

    private function claimedEvent(Request $request, BpppmnuAttendanceService $service, string $token)
    {
        $claim = $request->session()->get($this->claimKey($token));
        if (! is_array($claim) || empty($claim['event_id']) || (int) ($claim['expires_at'] ?? 0) < now()->timestamp) {
            return null;
        }

        $event = $service->eventForClaimedPublicToken($token);

        return $event && $event->id === (int) $claim['event_id'] ? $event : null;
    }

    private function verifyParticipant($event, BpppmnuEventInvitation $invitation, string $verification): void
    {
        if (($event->public_name_verification ?: 'none') === 'none') {
            return;
        }
        if ($event->public_name_verification === 'phone_last4') {
            $phone = preg_replace('/\D+/', '', (string) $invitation->user->no_hp);
            $provided = preg_replace('/\D+/', '', $verification);
            if (strlen($phone) < 4 || ! hash_equals(substr($phone, -4), $provided)) {
                throw ValidationException::withMessages(['verification' => '4 digit terakhir nomor HP tidak sesuai.']);
            }
            return;
        }
        if (! hash_equals((string) $invitation->user->nuist_id, trim($verification))) {
            throw ValidationException::withMessages(['verification' => 'Kode peserta tidak sesuai.']);
        }
    }

    private function nonceKey(string $token): string
    {
        return 'bpppmnu_public_nonce_'.hash('sha256', $token);
    }

    private function claimKey(string $token): string
    {
        return 'bpppmnu_public_claim_'.hash('sha256', $token);
    }
}
