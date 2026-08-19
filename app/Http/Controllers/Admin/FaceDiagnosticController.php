<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaceDiagnostic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class FaceDiagnosticController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'super_admin', 403, 'Unauthorized');

        $query = FaceDiagnostic::query()->with('user', 'actor')->latest();

        if ($request->filled('outcome')) {
            $query->where('outcome', $request->string('outcome'));
        }

        if ($request->filled('source')) {
            $query->where('source', $request->string('source'));
        }

        if ($request->filled('q')) {
            $term = '%' . $request->string('q') . '%';
            $query->where(function ($subQuery) use ($term) {
                $subQuery->whereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', $term))
                    ->orWhere('reason', 'like', $term)
                    ->orWhere('device', 'like', $term)
                    ->orWhere('browser', 'like', $term);
            });
        }

        $diagnostics = $query->paginate(30)->withQueryString();

        return view('admin.face_diagnostics.index', [
            'diagnostics' => $diagnostics,
            'summary' => [
                'total' => FaceDiagnostic::count(),
                'success' => FaceDiagnostic::where('outcome', 'success')->count(),
                'failed' => FaceDiagnostic::where('outcome', 'failed')->count(),
                'stuck' => FaceDiagnostic::where('outcome', 'stuck')->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user, 403);

        $validated = $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'source' => 'required|string|in:enrollment,presensi',
            'outcome' => 'required|string|in:success,failed,stuck',
            'stage' => 'nullable|string|max:64',
            'reason' => 'nullable|string|max:255',
            'device' => 'nullable|string|max:191',
            'android_version' => 'nullable|string|max:32',
            'browser' => 'nullable|string|max:128',
            'gpu' => 'nullable|string|max:191',
            'webgl' => 'nullable|string|max:32',
            'tf_backend' => 'nullable|string|max:32',
            'video_size' => 'nullable|string|max:32',
            'ready_state' => 'nullable|string|max:16',
            'camera_state' => 'nullable|string|max:16',
            'details' => 'nullable|array',
        ]);

        FaceDiagnostic::create($validated + [
            'actor_id' => $user->id,
        ]);

        return response()->json(['success' => true]);
    }
}
