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
            $row->status = $row->attended_at ? 'Hadir' : ($event->status === 'cancelled' ? 'Dibatalkan' : ($event->isFinished() && $event->status === 'published' ? 'Tidak Hadir' : 'Belum Presensi'));
        }
        $total = $rows->count();
        $present = $rows->whereNotNull('attended_at')->count();

        return ['rows' => $rows, 'total' => $total, 'present' => $present, 'remaining' => $total - $present, 'percentage' => $total ? round($present / $total * 100, 1) : 0];
    }
}
