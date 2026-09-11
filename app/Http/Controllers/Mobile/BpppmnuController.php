<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\BpppmnuEvent;
use App\Services\BpppmnuAttendanceService;
use App\Services\BpppmnuReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class BpppmnuController extends Controller
{
    public function index(Request $request)
    {
        $events = BpppmnuEvent::where('status', 'published')
            ->whereHas('invitations', fn ($q) => $q->where('user_id', $request->user()->id))
            ->with(['attendances' => fn ($q) => $q->where('user_id', $request->user()->id)])
            ->where(fn ($q) => $q->where('end_at', '>=', now())->orWhere('attendance_close_at', '>=', now()))
            ->orderByRaw('CASE WHEN attendance_open_at <= ? AND attendance_close_at >= ? THEN 0 ELSE 1 END', [now(), now()])
            ->orderBy('start_at')->paginate(15);

        return $request->expectsJson() ? response()->json($events) : view('mobile.bpppmnu.index', compact('events'));
    }

    public function barcode(Request $request)
    {
        abort_unless($request->user()->nuist_id, 422, 'ID NUIST belum tersedia.');
        $renderer = new ImageRenderer(new RendererStyle(320), new SvgImageBackEnd());
        $svg = (new Writer($renderer))->writeString((string) $request->user()->nuist_id);

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function show(Request $request, BpppmnuEvent $event, BpppmnuReportService $reports)
    {
        $this->authorizeEvent($request, $event);
        $attendance = $event->attendances()->where('user_id', $request->user()->id)->first();

        $recap = $reports->recap($event);
        return $request->expectsJson() ? response()->json(compact('event', 'attendance', 'recap')) : view('mobile.bpppmnu.show', compact('event', 'attendance', 'recap'));
    }

    public function recap(Request $request, BpppmnuEvent $event, BpppmnuReportService $reports)
    {
        $this->authorizeEvent($request, $event);
        $recap = $reports->recap($event);
        return response()->json(['present' => $recap['present'], 'remaining' => $recap['remaining'], 'percentage' => $recap['percentage'], 'rows' => $recap['rows']->map(fn ($row) => ['name' => $row->name, 'jabatan' => $row->jabatan, 'status' => $row->attended_at ? 'Hadir' : 'Belum Hadir'])->values()]);
    }

    public function scan(Request $request, BpppmnuEvent $event, BpppmnuAttendanceService $service)
    {
        $data = $request->validate(['qr_token' => 'required|string|size:64|regex:/^[a-f0-9]+$/'], ['qr_token.*' => BpppmnuAttendanceService::INVALID_QR]);
        $result = $service->record($request->user(), $event, $data['qr_token']);

        return response()->json([
            'message' => $result['duplicate'] ? 'Anda sudah melakukan presensi pada kegiatan ini.' : 'Presensi berhasil dicatat.',
            'event_name' => $event->name,
            'attended_at' => $result['attendance']->attended_at->format('d-m-Y H:i:s').' WIB',
            'duplicate' => $result['duplicate'],
        ]);
    }

    public function history(Request $request, BpppmnuReportService $reports)
    {
        $data = $request->validate(['month' => 'nullable|integer|between:1,12', 'year' => 'nullable|integer|between:2000,2200', 'status' => 'nullable|in:hadir,tidak_hadir']);
        $query = $reports->invitations()->where('i.user_id', $request->user()->id)
            ->where('e.status', 'published')->where('e.end_at', '<', now())->where('e.attendance_close_at', '<', now());
        if (! empty($data['month'])) {
            $query->whereMonth('e.start_at', $data['month']);
        }
        if (! empty($data['year'])) {
            $query->whereYear('e.start_at', $data['year']);
        }
        if (($data['status'] ?? null) === 'hadir') {
            $query->whereNotNull('a.attended_at');
        }
        if (($data['status'] ?? null) === 'tidak_hadir') {
            $query->whereNull('a.attended_at');
        }
        $history = $query->orderByDesc('e.start_at')->select(['e.id', 'e.name', 'e.start_at', 'a.attended_at'])->paginate(20)->withQueryString();

        return $request->expectsJson() ? response()->json($history) : view('mobile.bpppmnu.history', compact('history'));
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        return $request->expectsJson()
            ? response()->json($user->only(['name', 'nuist_id', 'email', 'no_hp', 'ketugasan', 'jabatan', 'alamat', 'avatar']))
            : view('mobile.bpppmnu.profile', compact('user'));
    }

    public function attachment(Request $request, BpppmnuEvent $event)
    {
        $this->authorizeEvent($request, $event);
        abort_unless($event->attachment && Storage::disk('local')->exists($event->attachment), 404);

        return Storage::disk('local')->download($event->attachment);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/mobile/login');
    }

    private function authorizeEvent(Request $request, BpppmnuEvent $event): void
    {
        abort_unless($event->status !== 'draft' && $event->invitations()->where('user_id', $request->user()->id)->exists(), 404);
    }
}
