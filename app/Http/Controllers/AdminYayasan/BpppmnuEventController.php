<?php

namespace App\Http\Controllers\AdminYayasan;

use App\Exports\BpppmnuAttendanceExport;
use App\Http\Controllers\Controller;
use App\Models\BpppmnuEvent;
use App\Models\User;
use App\Services\BpppmnuReportService;
use App\Services\BpppmnuAttendanceService;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class BpppmnuEventController extends Controller
{
    public function index()
    {
        $events = BpppmnuEvent::withCount(['invitations', 'attendances'])->orderByDesc('start_at')->paginate(20);

        return view('admin.bpppmnu.index', compact('events'));
    }

    public function create()
    {
        return $this->form(new BpppmnuEvent);
    }

    public function edit(BpppmnuEvent $event)
    {
        abort_if($event->isLocked() || $event->status === 'cancelled', 403, 'Agenda sudah dikunci.');

        return $this->form($event);
    }

    private function form(BpppmnuEvent $event)
    {
        $members = User::whereIn('role', ['pengurus_bpppmnu', 'tenaga_pendidik'])
            ->where('is_active', true)
            ->whereHas('bpppmnuMember', fn ($q) => $q->where('is_active', true))
            ->with('bpppmnuMember')
            ->orderBy('name')->get();
        $selected = $event->exists ? $event->invitations()->pluck('user_id')->all() : [];

        return view('admin.bpppmnu.form', compact('event', 'members', 'selected'));
    }

    public function store(Request $request)
    {
        return $this->save($request, new BpppmnuEvent);
    }

    public function update(Request $request, BpppmnuEvent $event)
    {
        return $this->save($request, $event);
    }

    public function destroy(BpppmnuEvent $event)
    {
        abort_if($event->attendances()->exists(), 403, 'Agenda yang sudah memiliki presensi tidak dapat dihapus.');
        $attachment = $event->attachment;
        DB::transaction(function () use ($event) {
            $event->qrTokens()->delete();
            $event->invitations()->delete();
            $event->delete();
        });
        if ($attachment) Storage::disk('local')->delete($attachment);
        return redirect()->route('admin.bpppmnu.events.index')->with('success', 'Agenda berhasil dihapus.');
    }

    private function save(Request $request, BpppmnuEvent $event)
    {
        $rules = [];
        foreach (['name', 'type', 'organizer', 'location_name'] as $field) {
            $rules[$field] = 'required|string|max:255';
        }
        $rules += [
            'meeting_url' => 'nullable|url:http,https|max:2000',
            'start_at' => 'required|date', 'end_at' => 'required|date|after:start_at',
            'attendance_open_at' => 'required|date|before:end_at',
            'attendance_close_at' => 'required|date|after:attendance_open_at|after_or_equal:start_at',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'invitees' => 'required|array|min:1|max:5000',
            'invitees.*' => ['required', 'integer', 'distinct', Rule::exists('users', 'id')->whereIn('role', ['pengurus_bpppmnu', 'tenaga_pendidik'])->where('is_active', true)->whereExists(fn ($q) => $q->from('bpppmnu_members')->whereColumn('bpppmnu_members.user_id', 'users.id')->where('is_active', true))],
        ];
        $data = $request->validate($rules);
        $invitees = $data['invitees'];
        unset($data['invitees'], $data['attachment']);
        $uploaded = null;
        try {
            if ($request->hasFile('attachment')) {
                $uploaded = $request->file('attachment')->store('bpppmnu/invitations', 'local');
            }
            $event = DB::transaction(function () use ($event, $data, $invitees, $uploaded, $request) {
                if ($event->exists) {
                    $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
                    if ($event->isLocked() || $event->status === 'cancelled') {
                        throw ValidationException::withMessages(['event' => 'Agenda dan undangan sudah dikunci karena presensi telah dibuka atau kegiatan dibatalkan.']);
                    }
                }
                $event->fill($data);
                if (! $event->exists) {
                    // Retain compatibility with the existing non-null database column.
                    $event->person_in_charge = '';
                    $event->fill(['created_by' => $request->user()->id, 'status' => 'draft']);
                }
                if ($uploaded) {
                    $event->attachment = $uploaded;
                }
                $event->save();
                $event->invitations()->whereNotIn('user_id', $invitees)->delete();
                foreach ($invitees as $id) {
                    $event->invitations()->firstOrCreate(['user_id' => $id]);
                }
                // Schedule changes invalidate all previously issued QR codes.
                $event->qrTokens()->whereNull('revoked_at')->update(['revoked_at' => now()]);

                return $event;
            });
        } catch (\Throwable $error) {
            if ($uploaded) {
                Storage::disk('local')->delete($uploaded);
            }
            throw $error;
        }

        return redirect()->route('admin.bpppmnu.events.show', $event)->with('success', 'Agenda berhasil disimpan.');
    }

    public function show(BpppmnuEvent $event, BpppmnuReportService $reports)
    {
        return view('admin.bpppmnu.show', ['event' => $event, 'recap' => $reports->recap($event)]);
    }

    public function scanMember(Request $request, BpppmnuEvent $event, BpppmnuAttendanceService $service)
    {
        $data = $request->validate(['nuist_id' => 'required|string|max:100']);
        $member = User::where('nuist_id', $data['nuist_id'])->where('role', 'pengurus_bpppmnu')->first();
        abort_unless($member, 404, 'Barcode peserta tidak dikenali.');
        $result = $service->recordMember($member, $event);
        return response()->json(['message' => $result['duplicate'] ? 'Peserta sudah tercatat hadir.' : 'Presensi peserta berhasil dicatat.', 'name' => $member->name, 'attended_at' => $result['attendance']->attended_at->format('d-m-Y H:i:s').' WIB', 'duplicate' => $result['duplicate']]);
    }

    public function publish(BpppmnuEvent $event)
    {
        DB::transaction(function () use ($event) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            if ($event->status !== 'draft' || $event->isFinished() || ! $event->invitations()->exists()) {
                throw ValidationException::withMessages(['event' => 'Agenda tidak dapat diterbitkan. Periksa status, jadwal, dan undangan.']);
            }
            $event->update(['status' => 'published']);
        });

        return back()->with('success', 'Agenda diterbitkan.');
    }

    public function cancel(BpppmnuEvent $event)
    {
        DB::transaction(function () use ($event) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            if ($event->attendances()->exists() || $event->isFinished()) {
                throw ValidationException::withMessages(['event' => 'Kegiatan dengan kehadiran atau histori selesai tidak dapat dibatalkan.']);
            }
            $event->update(['status' => 'cancelled']);
            $event->qrTokens()->whereNull('revoked_at')->update(['revoked_at' => now()]);
        });

        return back()->with('success', 'Kegiatan dibatalkan.');
    }

    public function qr(BpppmnuEvent $event, BpppmnuAttendanceService $service)
    {
        $token = $service->issue($event);
        $payload = json_encode(['type' => 'bpppmnu', 'event_id' => $event->id, 'token' => $token]);
        $svg = (new Writer(new ImageRenderer(new RendererStyle(360), new SvgImageBackEnd)))->writeString($payload);

        return response()->view('admin.bpppmnu.qr', compact('event', 'svg'))->header('Cache-Control', 'private, no-store');
    }

    public function revoke(BpppmnuEvent $event)
    {
        DB::transaction(function () use ($event) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $event->qrTokens()->whereNull('revoked_at')->update(['revoked_at' => now()]);
        });

        return back()->with('success', 'QR dicabut.');
    }

    public function attachment(BpppmnuEvent $event)
    {
        abort_unless($event->attachment && Storage::disk('local')->exists($event->attachment), 404);

        return Storage::disk('local')->download($event->attachment);
    }

    public function export(BpppmnuEvent $event, BpppmnuReportService $reports)
    {
        return Excel::download(new BpppmnuAttendanceExport($reports->recap($event)['rows']), 'presensi-bpppmnu-'.$event->id.'.xlsx');
    }
}
