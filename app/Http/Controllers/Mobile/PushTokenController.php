<?php

namespace App\Http\Controllers\Mobile;

use App\Models\PushDeviceToken;
use Illuminate\Http\Request;

class PushTokenController extends \App\Http\Controllers\Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'max:4096'],
            'platform' => ['nullable', 'string', 'max:32'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        PushDeviceToken::query()->updateOrCreate(
            ['token' => trim((string) $validated['token'])],
            [
                'user_id' => $request->user()->id,
                'platform' => trim((string) ($validated['platform'] ?? 'web')) ?: 'web',
                'device_name' => trim((string) ($validated['device_name'] ?? '')) ?: null,
                'last_seen_at' => now(),
            ]
        );

        return response()->json(['message' => 'Push token berhasil disimpan.']);
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'max:4096'],
        ]);

        PushDeviceToken::query()
            ->where('user_id', $request->user()->id)
            ->where('token', trim((string) $validated['token']))
            ->delete();

        return response()->json(['message' => 'Push token berhasil dihapus.']);
    }
}
