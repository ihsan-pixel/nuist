<?php

namespace App\Services;

use App\Models\GtkPendataan;
use App\Models\MgmpGroup;
use App\Models\MgmpMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GtkMgmpSyncService
{
    public function sync(GtkPendataan $pendataan, bool $dryRun = false): array
    {
        $normalize = fn ($name) => Str::lower(preg_replace('/\s+/u', ' ', trim($name)));
        $groups = MgmpGroup::all()->groupBy(fn ($group) => $normalize($group->name));
        $value = trim((string) $pendataan->nama_mgmp);
        // Prefer the complete name, since group names can themselves contain commas.
        $names = $groups->has($normalize($value))
            ? [$value]
            : preg_split('/[,;\r\n]+/u', $value, -1, PREG_SPLIT_NO_EMPTY);
        $ids = [];
        $unmatched = [];
        foreach ($names as $name) {
            $matches = $groups->get($normalize($name));
            if (! $matches || $matches->count() !== 1) {
                $unmatched[] = trim($name);
                continue;
            }
            $ids[] = $matches->first()->id;
        }

        // Never partially move a teacher when a name is unknown or ambiguous.
        if ($unmatched || $dryRun) {
            return $unmatched;
        }

        DB::transaction(function () use ($pendataan, $ids) {
            $user = $pendataan->user()->lockForUpdate()->first();
            if (! $user) {
                return;
            }
            MgmpMember::where('user_id', $user->id)->whereNotIn('mgmp_group_id', $ids)->delete();
            // Also remove legacy rows without a group.
            MgmpMember::where('user_id', $user->id)->whereNull('mgmp_group_id')->delete();
            foreach (array_unique($ids) as $groupId) {
                $member = MgmpMember::firstOrNew(['user_id' => $user->id, 'mgmp_group_id' => $groupId]);
                $member->fill([
                    'name' => $user->name,
                    'email' => $user->email,
                    'sekolah' => $user->madrasah?->name,
                    'madrasah_id' => $user->madrasah_id,
                ])->save();
                MgmpMember::where('user_id', $user->id)->where('mgmp_group_id', $groupId)
                    ->where('id', '!=', $member->id)->delete();
            }
        });

        return [];
    }
}
