<?php

namespace App\Services;

use App\Models\GtkPendataan;
use App\Models\MgmpGroup;
use App\Models\MgmpMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GtkMgmpSyncService
{
    public function createMissingGroups(bool $dryRun = false): array
    {
        $existing = MgmpGroup::all()->map(fn ($group) => $this->normalize($group->name));
        $targets = [];
        foreach (config('mgmp.creatable_groups', []) as $target) {
            foreach (array_merge([$target], config('mgmp.aliases.'.$target, [])) as $alias) {
                $targets[$this->normalize($alias)] = $target;
            }
        }
        $needed = [];
        foreach (GtkPendataan::whereNotNull('nama_mgmp')->select('nama_mgmp')->distinct()->cursor() as $row) {
            foreach (preg_split('/[,;&\r\n]+|\s+sama\s+/iu', $row->nama_mgmp, -1, PREG_SPLIT_NO_EMPTY) as $name) {
                $key = $this->normalize($name);
                if ($existing->contains($key)) {
                    continue;
                }
                $target = $targets[$key] ?? null;
                if ($target && ! $existing->contains($this->normalize($target))) {
                    $needed[$target] = $target;
                }
            }
        }
        if (! $dryRun) {
            foreach ($needed as $target) {
                MgmpGroup::firstOrCreate(['name' => $target], ['user_id' => null, 'member_count' => 0]);
            }
        }

        return array_values($needed);
    }

    private function normalize(string $name): string
    {
        $name = Str::lower(preg_replace('/\s+/u', ' ', trim($name)));

        return preg_replace('/^mgmp\s+/u', '', $name);
    }

    public function sync(GtkPendataan $pendataan, bool $dryRun = false): array
    {
        $normalize = fn ($name) => $this->normalize($name);
        $groups = MgmpGroup::all()->groupBy(fn ($group) => $normalize($group->name));
        $aliases = [];
        foreach (config('mgmp.aliases', []) as $target => $variants) {
            $targets = $groups->get($normalize($target));
            if (! $targets || $targets->count() !== 1) {
                continue;
            }
            foreach ($variants as $variant) {
                $key = $normalize($variant);
                $aliases[$key] = ($aliases[$key] ?? collect())->merge($targets)->unique('id');
            }
        }
        $resolve = fn ($name) => $groups->get($normalize($name)) ?? ($aliases[$normalize($name)] ?? null);
        $value = trim((string) $pendataan->nama_mgmp);
        // Prefer the complete name, since group names can themselves contain commas.
        $names = $resolve($value) !== null
            ? [$value]
            : preg_split('/[,;&\r\n]+|\s+sama\s+/iu', $value, -1, PREG_SPLIT_NO_EMPTY);
        $ids = [];
        $unmatched = [];
        foreach ($names as $name) {
            $matches = $resolve($name);
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
