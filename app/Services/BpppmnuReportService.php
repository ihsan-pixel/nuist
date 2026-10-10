<?php

namespace App\Services;

use App\Models\BpppmnuEvent;
use Illuminate\Support\Facades\DB;

class BpppmnuReportService
{
    public function invitations()
    {
        return DB::table('bpppmnu_event_invitations as i')
            ->join('bpppmnu_events as e', 'e.id', '=', 'i.event_id')
            ->leftJoin('bpppmnu_event_attendances as a', function ($join) {
                $join->on('a.event_id', '=', 'i.event_id')->on('a.user_id', '=', 'i.user_id');
            });
    }

    public function recap(BpppmnuEvent $event): array
    {
        $rows = $this->invitations()->join('users as u', 'u.id', '=', 'i.user_id')
            ->where('i.event_id', $event->id)->orderBy('u.name')
            ->get(['u.name', 'u.nuist_id', 'u.ketugasan as jabatan', 'a.attended_at']);
        foreach ($rows as $row) {
            $row->status = $row->attended_at ? 'Hadir' : ($event->status === 'cancelled' ? 'Dibatalkan' : ($event->status === 'published' && now()->gt($event->attendance_close_at) ? 'Tidak Hadir' : 'Belum Presensi'));
        }
        $total = $rows->count();
        $present = $rows->whereNotNull('attended_at')->count();

        $guestRows = $event->guestInvitations()->with('attendance')->orderBy('name')->get()->map(function ($invitation) use ($event) {
            $attendance = $invitation->attendance;

            return (object) [
                'name' => $invitation->name,
                'nuist_id' => null,
                'jabatan' => $invitation->organization,
                'status' => $attendance ? 'Hadir' : ($event->status === 'cancelled' ? 'Dibatalkan' : ($event->status === 'published' && now()->gt($event->attendance_close_at) ? 'Tidak Hadir' : 'Belum Presensi')),
                'attended_at' => $attendance?->attended_at,
                'participant_type' => 'Tamu',
            ];
        });
        // Preserve old guest attendance records created before guest invitations
        // became mandatory, so historical recap data is never hidden.
        $legacyGuestRows = $event->guestAttendances()->whereNull('guest_invitation_id')->orderBy('guest_name')->get()->map(fn ($attendance) => (object) [
            'name' => $attendance->guest_name,
            'nuist_id' => null,
            'jabatan' => $attendance->guest_organization,
            'status' => 'Hadir',
            'attended_at' => $attendance->attended_at,
            'participant_type' => 'Tamu',
        ]);
        $guestRows = $guestRows->concat($legacyGuestRows);
        foreach ($rows as $row) {
            $row->participant_type = 'Terdaftar';
        }

        return [
            'rows' => $rows->concat($guestRows),
            'total' => $total,
            'present' => $present,
            'remaining' => ($total - $present) + $guestRows->whereNull('attended_at')->count(),
            'percentage' => $total ? round($present / $total * 100, 1) : 0,
            'guests' => $guestRows->whereNotNull('attended_at')->count(),
            'guest_invited' => $event->guestInvitations()->count(),
            'total_present' => $present + $guestRows->whereNotNull('attended_at')->count(),
        ];
    }
}
