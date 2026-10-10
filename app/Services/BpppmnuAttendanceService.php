<?php

namespace App\Services;

use App\Models\BpppmnuEvent;
use App\Models\BpppmnuEventGuestAttendance;
use App\Models\BpppmnuEventInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BpppmnuAttendanceService
{
    public const INVALID_QR = 'QR presensi tidak valid atau sudah tidak berlaku.';

    public function issue(BpppmnuEvent $event): string
    {
        return DB::transaction(function () use ($event) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $this->check($event->status === 'published' && now()->lte($event->attendance_close_at), 'QR hanya tersedia untuk kegiatan terbit yang belum ditutup.');
            $event->qrTokens()->whereNull('revoked_at')->update(['revoked_at' => now()]);
            $raw = bin2hex(random_bytes(32));
            $event->qrTokens()->create(['token_hash' => hash('sha256', $raw), 'expires_at' => $event->attendance_close_at]);

            return $raw;
        });
    }

    public function record(User $user, BpppmnuEvent $event, string $token, ?float $latitude = null, ?float $longitude = null): array
    {
        abort_unless(
            $user->is_active !== false
            && in_array($user->role, ['pengurus_bpppmnu', 'tenaga_pendidik'], true)
            && ($user->role === 'pengurus_bpppmnu' || $user->bpppmnuMember?->is_active === true),
            403
        );

        return DB::transaction(function () use ($user, $event, $token, $latitude, $longitude) {
            // Every mutation locks the event first, serializing scan, revoke, and admin changes.
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $this->check($event->invitations()->where('user_id', $user->id)->exists(), 'Anda tidak terdaftar sebagai peserta kegiatan ini.');
            $this->check($event->status !== 'cancelled', 'Kegiatan telah dibatalkan.');
            $this->check($event->status === 'published', self::INVALID_QR);
            $qr = $event->qrTokens()->where('token_hash', hash('sha256', $token))->first();
            $this->check($qr && ! $qr->revoked_at, self::INVALID_QR);
            $this->check(now()->gte($event->attendance_open_at), 'Presensi kegiatan belum dibuka.');
            $this->check(now()->lte($event->attendance_close_at), 'Waktu presensi kegiatan telah berakhir.');
            $this->check(now()->lte($qr->expires_at), self::INVALID_QR);
            if ($event->location_validation_enabled) {
                $this->check($event->latitude !== null && $event->longitude !== null && $latitude !== null && $longitude !== null, 'Lokasi kegiatan atau GPS perangkat belum tersedia. Aktifkan GPS lalu coba lagi.');
                $distance = 6371000 * 2 * asin(sqrt(pow(sin(deg2rad($latitude - $event->latitude) / 2), 2) + cos(deg2rad($event->latitude)) * cos(deg2rad($latitude)) * pow(sin(deg2rad($longitude - $event->longitude) / 2), 2)));
                $this->check($distance <= $event->location_radius_meters, 'Presensi ditolak. Anda berada '.round($distance).' meter dari lokasi kegiatan (batas '.$event->location_radius_meters.' meter).');
            }
            $existing = $event->attendances()->where('user_id', $user->id)->first();
            if ($existing) {
                return ['attendance' => $existing, 'duplicate' => true];
            }
            $attendance = $event->attendances()->create(['user_id' => $user->id, 'attended_at' => now(), 'method' => 'qr']);

            return ['attendance' => $attendance, 'duplicate' => false];
        }, 3);
    }

    public function recordMember(User $member, BpppmnuEvent $event): array
    {
        abort_unless($member->is_active !== false, 403);

        return DB::transaction(function () use ($member, $event) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $this->check($event->invitations()->where('user_id', $member->id)->exists(), 'Pengguna tidak terdaftar sebagai peserta kegiatan ini.');
            $this->check($event->status === 'published', 'Kegiatan belum diterbitkan.');
            $this->check(now()->gte($event->attendance_open_at), 'Presensi kegiatan belum dibuka.');
            $this->check(now()->lte($event->attendance_close_at), 'Waktu presensi kegiatan telah berakhir.');
            $existing = $event->attendances()->where('user_id', $member->id)->first();
            if ($existing) return ['attendance' => $existing, 'duplicate' => true];
            $attendance = $event->attendances()->create(['user_id' => $member->id, 'attended_at' => now(), 'method' => 'barcode']);
            return ['attendance' => $attendance, 'duplicate' => false];
        }, 3);
    }

    public function eventForPublicToken(string $token): ?BpppmnuEvent
    {
        if (! preg_match('/^[a-f0-9]{64}$/', $token)) {
            return null;
        }

        return BpppmnuEvent::whereHas('qrTokens', function ($query) use ($token) {
            $query->where('token_hash', hash('sha256', $token))
                ->whereNull('revoked_at');
        })->first();
    }

    public function recordPublicRegistered(BpppmnuEventInvitation $invitation, BpppmnuEvent $event, string $token, ?float $latitude = null, ?float $longitude = null): array
    {
        return DB::transaction(function () use ($invitation, $event, $token, $latitude, $longitude) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $this->validatePublicEvent($event, $token);
            $this->check($event->allowsRegisteredAttendance(), 'Agenda ini tidak menerima peserta terdaftar.');
            $this->check($invitation->event_id === $event->id && $invitation->user?->is_active !== false, 'Nama peserta tidak terdaftar pada agenda ini.');
            $existing = $event->attendances()->where('user_id', $invitation->user_id)->first();
            if ($existing) {
                return ['attendance' => $existing, 'duplicate' => true];
            }
            if ($event->capacity) {
                $total = $event->attendances()->count() + $event->guestAttendances()->count();
                $this->check($total < $event->capacity, 'Kapasitas peserta kegiatan sudah penuh.');
            }
            $this->validateLocation($event, $latitude, $longitude);
            $attendance = $event->attendances()->create([
                'user_id' => $invitation->user_id,
                'attended_at' => now(),
                'method' => 'public_qr_registered',
            ]);

            return ['attendance' => $attendance, 'duplicate' => false];
        }, 3);
    }

    public function recordPublicGuest(BpppmnuEvent $event, string $token, array $data, Request $request): array
    {
        return DB::transaction(function () use ($event, $token, $data, $request) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $this->validatePublicEvent($event, $token);
            $this->check($event->allowsGuestAttendance(), 'Agenda ini tidak menerima peserta tamu.');
            $this->check(! $event->guest_phone_required || ! empty($data['guest_phone']), 'Nomor HP wajib diisi.');
            $this->check(! $event->guest_organization_required || ! empty($data['guest_organization']), 'Asal instansi wajib diisi.');

            if ($event->capacity) {
                $total = $event->attendances()->count() + $event->guestAttendances()->count();
                $this->check($total < $event->capacity, 'Kapasitas peserta kegiatan sudah penuh.');
            }

            $distance = $this->validateLocation($event, $data['latitude'] ?? null, $data['longitude'] ?? null);
            $fingerprint = hash('sha256', $event->id.'|'.$data['nonce']);
            $existing = $event->guestAttendances()->where('request_fingerprint', $fingerprint)->first();
            if ($existing) {
                return ['attendance' => $existing, 'duplicate' => true];
            }

            $attendance = $event->guestAttendances()->create([
                'guest_name' => trim($data['guest_name']),
                'guest_phone' => isset($data['guest_phone']) ? trim($data['guest_phone']) : null,
                'guest_organization' => isset($data['guest_organization']) ? trim($data['guest_organization']) : null,
                'attended_at' => now(),
                'method' => 'public_qr_guest',
                'confirmation_code' => Str::upper(Str::random(12)),
                'request_fingerprint' => $fingerprint,
                'ip_hash' => $request->ip() ? hash_hmac('sha256', $request->ip(), (string) config('app.key')) : null,
                'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'distance_meters' => $distance,
            ]);

            return ['attendance' => $attendance, 'duplicate' => false];
        }, 3);
    }

    private function validatePublicEvent(BpppmnuEvent $event, string $token): void
    {
        $this->check($event->status === 'published', self::INVALID_QR);
        $qr = $event->qrTokens()->where('token_hash', hash('sha256', $token))->first();
        $this->check($qr && ! $qr->revoked_at && now()->lte($qr->expires_at), self::INVALID_QR);
        $this->check(now()->gte($event->attendance_open_at), 'Presensi kegiatan belum dibuka.');
        $this->check(now()->lte($event->attendance_close_at), 'Waktu presensi kegiatan telah berakhir.');
    }

    private function validateLocation(BpppmnuEvent $event, ?float $latitude, ?float $longitude): ?int
    {
        if (! $event->location_validation_enabled) {
            return null;
        }
        $this->check($event->latitude !== null && $event->longitude !== null && $latitude !== null && $longitude !== null, 'Lokasi kegiatan atau GPS perangkat belum tersedia. Aktifkan GPS lalu coba lagi.');
        $distance = 6371000 * 2 * asin(sqrt(pow(sin(deg2rad($latitude - $event->latitude) / 2), 2) + cos(deg2rad($event->latitude)) * cos(deg2rad($latitude)) * pow(sin(deg2rad($longitude - $event->longitude) / 2), 2)));
        $this->check($distance <= $event->location_radius_meters, 'Presensi ditolak. Anda berada '.round($distance).' meter dari lokasi kegiatan (batas '.$event->location_radius_meters.' meter).');

        return (int) round($distance);
    }

    private function check(bool $condition, string $message): void
    {
        if (! $condition) {
            throw ValidationException::withMessages(['qr' => $message]);
        }
    }
}
