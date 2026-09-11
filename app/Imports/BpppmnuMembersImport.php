<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;

class BpppmnuMembersImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $created = 0;
    public int $updated = 0;
    public array $skipped = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $number => $row) {
            $name = trim((string) ($row['nama'] ?? $row['name'] ?? ''));
            if ($name === '' || str_contains(strtolower($name), 'bpppmnu')) continue;
            $jabatan = trim((string) ($row['jabatan'] ?? $row['ketugasan'] ?? ''));
            $instansi = trim((string) ($row['instansi_asal'] ?? $row['instansi'] ?? $row['asal'] ?? ''));
            if ($jabatan === '' || $instansi === '') {
                $this->skipped[] = 'Baris '.($number + 2).' ('.$name.')';
                continue;
            }
            $email = trim((string) ($row['email'] ?? ''));
            $member = $email !== '' ? User::where('email', $email)->where('is_active', true)->first() : null;
            if (! $member) $member = User::where('name', $name)->where('is_active', true)->first();
            if (! $member) {
                $base = 'bpppmnu.'.Str::slug($name, '.');
                $email = $email ?: $base.'@nuist.id';
                $suffix = 1;
                while (User::where('email', $email)->exists()) $email = $base.'.'.(++$suffix).'@nuist.id';
                $member = new User(['email' => $email, 'password' => Hash::make('Bpppmnu@2026')]);
                $this->created++;
            } else {
                $this->updated++;
            }
            $member->fill(['name' => $name, 'bpppmnu_jabatan' => $jabatan, 'bpppmnu_instansi_asal' => $instansi, 'is_active' => true, 'is_bpppmnu_member' => true]);
            if (! $member->exists) $member->fill(['jabatan' => $jabatan, 'ketugasan' => $jabatan, 'instansi_asal' => $instansi]);
            if (! $member->exists || ! in_array($member->role, ['tenaga_pendidik', 'pengurus_bpppmnu'], true)) $member->role = 'pengurus_bpppmnu';
            $member->save();
        }
    }
}
