<?php

namespace App\Exports;

use App\Models\DpsAccountPassword;
use App\Models\DpsMember;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DpsAccountsExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            'Nama DPS',
            'Email',
            'Password (awal)',
            'SCOD',
            'Nama Sekolah',
            'Unsur (per sekolah)',
            'Periode (per sekolah)',
        ];
    }

    public function collection()
    {
        $users = User::query()
            ->where('role', 'dps')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $passwords = collect();
        if (Schema::hasTable('dps_account_passwords')) {
            try {
                $passwords = DpsAccountPassword::whereIn('user_id', $users->pluck('id'))
                    ->get()
                    ->keyBy('user_id');
            } catch (\Throwable $e) {
                $passwords = collect();
            }
        }

        // Preload DPS assignments and school info.
        // Some environments may use 'madrasah' vs 'madrasahs'; try both.
        $madrasahTable = 'madrasahs';
        try {
            // no-op; fallback below if join fails at runtime
        } catch (\Throwable $e) {}

        $assignmentsQuery = DpsMember::query();
        if (Schema::hasTable('madrasah')) {
            $madrasahTable = 'madrasah';
        }

        $assignments = $assignmentsQuery
            ->join($madrasahTable, 'dps_members.madrasah_id', '=', "{$madrasahTable}.id")
            ->whereIn('dps_members.user_id', $users->pluck('id'))
            ->select([
                'dps_members.user_id',
                'dps_members.unsur',
                'dps_members.periode',
                "{$madrasahTable}.scod",
                "{$madrasahTable}.name as school_name",
            ])
            ->orderByRaw("CAST({$madrasahTable}.scod AS UNSIGNED) ASC")
            ->get()
            ->groupBy('user_id');

        $rows = collect();

        foreach ($users as $u) {
            $pw = $passwords->get($u->id);
            $plain = '';
            if ($pw) {
                try {
                    $plain = Crypt::decryptString($pw->password_encrypted);
                } catch (\Throwable $e) {
                    $plain = '';
                }
            }

            $userAssignments = $assignments->get($u->id, collect());
            if ($userAssignments->isEmpty()) {
                $rows->push([
                    $u->name,
                    $u->email,
                    $plain,
                    '',
                    '',
                    '',
                    '',
                ]);
                continue;
            }

            // One row per school for readability in Excel.
            $bySchool = $userAssignments->groupBy('scod');
            foreach ($bySchool as $scod => $items) {
                $rows->push([
                    $u->name,
                    $u->email,
                    $plain,
                    $scod,
                    (string)($items->first()->school_name ?? ''),
                    $items->pluck('unsur')->filter()->unique()->implode(', '),
                    $items->pluck('periode')->filter()->unique()->implode(', '),
                ]);
            }
        }

        return $rows;
    }
}
