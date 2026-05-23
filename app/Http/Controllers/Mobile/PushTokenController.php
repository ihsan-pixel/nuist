<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\PushDeviceToken;
use Illuminate\Http\Request;

class PushTokenController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'token' => ['required', 'string', 'max:4096'],
            'platform' => ['nullable', 'string', 'max:32'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        PushDeviceToken::query()->updateOrCreate(
            ['token' => trim((string) $validated['token'])],
            [
                'user_id' => $user->id,
                'platform' => trim((string) ($validated['platform'] ?? '')) ?: null,
                'device_name' => trim((string) ($validated['device_name'] ?? '')) ?: null,
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Push token berhasil disimpan.',
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'token' => ['required', 'string', 'max:4096'],
        ]);

        PushDeviceToken::query()
            ->where('user_id', $user->id)
            ->where('token', trim((string) $validated['token']))
            ->delete();

        return response()->json([
            'message' => 'Push token berhasil dihapus.',
        ]);
    }
}
