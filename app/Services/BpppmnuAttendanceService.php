<?php

namespace App\Services;

use App\Models\BpppmnuEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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

        return DB::transaction(function () use ($user, $event, $token) {
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
        abort_unless($member->is_active !== false && in_array($member->role, ['pengurus_bpppmnu', 'tenaga_pendidik'], true), 403);

        return DB::transaction(function () use ($member, $event) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $this->check($event->invitations()->where('user_id', $member->id)->exists(), 'Pengurus tidak terdaftar sebagai peserta kegiatan ini.');
            $this->check($event->status === 'published', 'Kegiatan belum diterbitkan.');
            $this->check(now()->gte($event->attendance_open_at), 'Presensi kegiatan belum dibuka.');
            $this->check(now()->lte($event->attendance_close_at), 'Waktu presensi kegiatan telah berakhir.');
            $existing = $event->attendances()->where('user_id', $member->id)->first();
            if ($existing) return ['attendance' => $existing, 'duplicate' => true];
            $attendance = $event->attendances()->create(['user_id' => $member->id, 'attended_at' => now(), 'method' => 'barcode']);
            return ['attendance' => $attendance, 'duplicate' => false];
        }, 3);
    }

    private function check(bool $condition, string $message): void
    {
        if (! $condition) {
            throw ValidationException::withMessages(['qr' => $message]);
        }
    }
}
