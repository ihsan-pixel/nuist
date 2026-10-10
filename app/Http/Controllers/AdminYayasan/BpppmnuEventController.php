<?php

namespace App\Http\Controllers\AdminYayasan;

use App\Exports\BpppmnuAttendanceExport;
use App\Exports\BpppmnuGuestInvitationTemplateExport;
use App\Http\Controllers\Controller;
use App\Models\BpppmnuEvent;
use App\Models\User;
use App\Imports\BpppmnuGuestInvitationsImport;
use App\Services\BpppmnuReportService;
use App\Services\BpppmnuAttendanceService;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Common\ErrorCorrectionLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BpppmnuEventController extends Controller
{
    public function index()
    {
        $events = BpppmnuEvent::withCount(['invitations', 'guestInvitations', 'attendances', 'guestAttendances'])->orderByDesc('start_at')->paginate(20);

        return view('admin.bpppmnu.index', compact('events'));
    }

    public function create()
    {
        return $this->form(new BpppmnuEvent);
    }

    public function edit(BpppmnuEvent $event)
    {
        abort_if($event->status === 'cancelled', 403, 'Agenda yang dibatalkan tidak dapat diedit.');

        return $this->form($event);
    }

    private function form(BpppmnuEvent $event)
    {
        $members = User::where('is_active', true)
            ->with(['bpppmnuMember', 'madrasah:id,name'])
            ->orderBy('name')->get();
        $selected = $event->exists ? $event->invitations()->pluck('user_id')->all() : [];
        $guestInvitees = $event->exists ? $event->guestInvitations()->orderBy('name')->get() : collect();

        return view('admin.bpppmnu.form', compact('event', 'members', 'selected', 'guestInvitees'));
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
        $attachment = $event->attachment;
        DB::transaction(function () use ($event) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();

            // Attendance rows must be removed before their invitations because
            // both relationships intentionally use restrictive foreign keys.
            $event->guestAttendances()->delete();
            $event->attendances()->delete();
            $event->qrTokens()->delete();
            $event->invitations()->delete();
            $event->guestInvitations()->delete();
            $event->delete();
        }, 3);
        if ($attachment) {
            Storage::disk('local')->delete($attachment);
        }

        return redirect()->route('admin.agenda.index')->with('success', 'Agenda berhasil dihapus.');
    }

    private function save(Request $request, BpppmnuEvent $event)
    {
        $rules = [];
        $rules += ['location_validation_enabled' => 'nullable|boolean', 'latitude' => 'nullable|numeric|between:-90,90', 'longitude' => 'nullable|numeric|between:-180,180', 'location_radius_meters' => 'nullable|integer|between:10,1000'];
        foreach (['name', 'type', 'organizer', 'location_name'] as $field) {
            $rules[$field] = 'required|string|max:255';
        }
        $rules += [
            'meeting_url' => 'nullable|url:http,https|max:2000',
            'start_at' => 'required|date', 'end_at' => 'required|date|after:start_at',
            'attendance_open_at' => 'required|date|before:end_at',
            'attendance_close_at' => 'required|date|after:attendance_open_at|after_or_equal:start_at',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'guest_import' => 'nullable|file|mimes:xlsx,xls,csv|max:5120',
            'attendance_access_mode' => 'sometimes|in:registered,hybrid,guest',
            'public_name_verification' => 'sometimes|in:none,phone_last4,participant_code',
            'guest_phone_required' => 'nullable|boolean',
            'guest_organization_required' => 'nullable|boolean',
            'capacity' => 'nullable|integer|min:1|max:100000',
            'invitees' => 'required_unless:attendance_access_mode,guest|array|max:5000',
            'invitees.*' => ['required', 'integer', 'distinct', Rule::exists('users', 'id')->where('is_active', true)],
            'guest_invitees' => 'nullable|array|max:5000',
            'guest_invitees.*.id' => 'nullable|integer',
            'guest_invitees.*.name' => 'required_with:guest_invitees|string|min:3|max:255',
            'guest_invitees.*.organization' => 'nullable|string|max:255',
            'guest_invitees.*.phone' => 'nullable|string|max:30',
        ];
        $data = $request->validate($rules);
        // Kolom lama pada database masih NOT NULL, meskipun field-nya sudah
        // dihilangkan dari form. Pastikan selalu dikirim saat insert/update.
        $data['description'] = (string) ($data['description'] ?? '');
        $data['person_in_charge'] = (string) ($data['person_in_charge'] ?? '');
        // Checkbox yang tidak dicentang tidak dikirim browser; ubah eksplisit
        // menjadi false agar status validasi lokasi benar-benar nonaktif.
        $data['location_validation_enabled'] = $request->boolean('location_validation_enabled');
        $data['location_radius_meters'] = $data['location_radius_meters'] ?? 50;
        $data['guest_phone_required'] = $request->boolean('guest_phone_required');
        $data['guest_organization_required'] = $request->boolean('guest_organization_required');
        $invitees = $data['invitees'] ?? [];
        $guestInvitees = collect($data['guest_invitees'] ?? [])->filter(fn ($guest) => trim((string) ($guest['name'] ?? '')) !== '');
        if ($request->hasFile('guest_import')) {
            $import = new BpppmnuGuestInvitationsImport;
            try {
                Excel::import($import, $request->file('guest_import'));
            } catch (\Throwable) {
                throw ValidationException::withMessages(['guest_import' => 'File tidak dapat dibaca. Gunakan template Excel yang telah disediakan.']);
            }
            if ($import->invalidRows) {
                throw ValidationException::withMessages([
                    'guest_import' => 'Nama tamu wajib diisi pada baris: '.implode(', ', array_slice($import->invalidRows, 0, 20)).'.',
                ]);
            }
            $guestInvitees = $guestInvitees->concat($import->guests);
        }
        $guestInvitees = $guestInvitees
            ->unique(fn ($guest) => mb_strtolower(trim((string) $guest['name'])).'|'.mb_strtolower(trim((string) ($guest['organization'] ?? ''))).'|'.preg_replace('/\D+/', '', (string) ($guest['phone'] ?? '')))
            ->values()->all();
        if (count($guestInvitees) > 5000) {
            throw ValidationException::withMessages(['guest_import' => 'Jumlah tamu maksimal 5.000 nama untuk satu agenda.']);
        }
        Validator::make(['guests' => $guestInvitees], [
            'guests.*.name' => 'required|string|min:3|max:255',
            'guests.*.organization' => 'nullable|string|max:255',
            'guests.*.phone' => 'nullable|string|max:30',
        ], [], [
            'guests.*.name' => 'nama tamu',
            'guests.*.organization' => 'instansi tamu',
            'guests.*.phone' => 'nomor HP tamu',
        ])->validate();
        $accessMode = $data['attendance_access_mode'] ?? ($event->attendance_access_mode ?: 'registered');
        if (count($guestInvitees) > 0 && $accessMode === 'registered') {
            $accessMode = 'hybrid';
            $data['attendance_access_mode'] = 'hybrid';
        }
        if ($accessMode === 'guest' && count($guestInvitees) === 0) {
            throw ValidationException::withMessages(['guest_invitees' => 'Tambahkan minimal satu nama tamu undangan.']);
        }
        foreach ($guestInvitees as $index => $guest) {
            if ($data['guest_phone_required'] && empty($guest['phone'])) {
                throw ValidationException::withMessages(["guest_invitees.$index.phone" => 'Nomor HP tamu wajib diisi.']);
            }
            if ($data['guest_organization_required'] && empty($guest['organization'])) {
                throw ValidationException::withMessages(["guest_invitees.$index.organization" => 'Instansi tamu wajib diisi.']);
            }
        }
        $validMemberIds = User::whereIn('id', $invitees)->where('is_active', true)->pluck('id')->all();
        if (count($validMemberIds) !== count(array_unique($invitees))) {
            throw ValidationException::withMessages(['invitees' => 'Semua peserta undangan harus merupakan pengguna aktif.']);
        }
        unset($data['invitees'], $data['guest_invitees'], $data['guest_import'], $data['attachment']);
        $uploaded = null;
        try {
            if ($request->hasFile('attachment')) {
                $uploaded = $request->file('attachment')->store('agenda/invitations', 'local');
            }
            $event = DB::transaction(function () use ($event, $data, $invitees, $guestInvitees, $uploaded, $request) {
                if ($event->exists) {
                    $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
                    if ($event->status === 'cancelled') {
                        throw ValidationException::withMessages(['event' => 'Agenda yang dibatalkan tidak dapat diedit.']);
                    }
                }
                $event->fill($data);
                if (! $event->exists) {
                    // Retain compatibility with the existing non-null database column.
                    $event->created_by = $request->user()->id;
                    $event->status = 'draft';
                }
                if ($uploaded) {
                    $event->attachment = $uploaded;
                }
                $event->save();
                // Undangan yang sudah memiliki presensi tidak boleh dihapus karena
                // menjadi parent foreign key untuk riwayat kehadiran.
                if (! $event->attendances()->exists() && ! $event->guestAttendances()->exists()) {
                    $event->invitations()->whereNotIn('user_id', $invitees)->delete();
                }
                foreach ($invitees as $id) {
                    $event->invitations()->firstOrCreate(['user_id' => $id]);
                }
                $keptGuestIds = [];
                foreach ($guestInvitees as $guest) {
                    $guestId = isset($guest['id']) ? (int) $guest['id'] : null;
                    $invitation = $guestId
                        ? $event->guestInvitations()->whereKey($guestId)->first()
                        : null;
                    $invitation ??= $event->guestInvitations()->make();
                    $invitation->fill([
                        'name' => trim($guest['name']),
                        'organization' => trim((string) ($guest['organization'] ?? '')) ?: null,
                        'phone' => trim((string) ($guest['phone'] ?? '')) ?: null,
                    ])->save();
                    $keptGuestIds[] = $invitation->id;
                }
                $event->guestInvitations()->whereNotIn('id', $keptGuestIds)
                    ->whereDoesntHave('attendance')->delete();
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

        return redirect()->route('admin.agenda.show', $event)->with('success', 'Agenda berhasil disimpan.');
    }

    public function show(BpppmnuEvent $event, BpppmnuReportService $reports)
    {
        return view('admin.bpppmnu.show', ['event' => $event, 'recap' => $reports->recap($event)]);
    }

    public function loginQr(BpppmnuEvent $event)
    {
        $loginUrl = route('mobile.login');
        $svg = (new Writer(new ImageRenderer(new RendererStyle(360), new SvgImageBackEnd)))->writeString($loginUrl, 'UTF-8', ErrorCorrectionLevel::H());

        return view('admin.bpppmnu.login-qr', compact('event', 'loginUrl', 'svg'));
    }

    public function scanMember(Request $request, BpppmnuEvent $event, BpppmnuAttendanceService $service)
    {
        $data = $request->validate(['nuist_id' => 'required|string|max:100']);
        $member = User::where('nuist_id', $data['nuist_id'])->where('is_active', true)->first();
        abort_unless($member, 404, 'Barcode peserta tidak dikenali.');
        $result = $service->recordMember($member, $event);
        return response()->json(['message' => $result['duplicate'] ? 'Peserta sudah tercatat hadir.' : 'Presensi peserta berhasil dicatat.', 'name' => $member->name, 'attended_at' => $result['attendance']->attended_at->format('d-m-Y H:i:s').' WIB', 'duplicate' => $result['duplicate']]);
    }

    public function scanner(BpppmnuEvent $event)
    {
        abort_unless($event->status === 'published' && now()->betweenIncluded($event->attendance_open_at, $event->attendance_close_at), 403);
        return view('admin.bpppmnu.scanner', compact('event'));
    }

    public function publish(BpppmnuEvent $event)
    {
        DB::transaction(function () use ($event) {
            $event = BpppmnuEvent::whereKey($event->id)->lockForUpdate()->firstOrFail();
            $mode = $event->attendance_access_mode ?: 'registered';
            $hasAllowedInvitees = match ($mode) {
                'guest' => $event->guestInvitations()->exists(),
                'hybrid' => $event->invitations()->exists() || $event->guestInvitations()->exists(),
                default => $event->invitations()->exists(),
            };
            if ($event->status !== 'draft' || $event->isFinished() || ! $hasAllowedInvitees) {
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
            if ($event->attendances()->exists() || $event->guestAttendances()->exists() || $event->isFinished()) {
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
        $publicUrl = route('public.bpppmnu.show', ['token' => $token]);
        $svg = $this->qrSvg($publicUrl);
        $version = (string) $event->qrTokens()->where('token_hash', hash('sha256', $token))->value('id');

        return response()->view('admin.bpppmnu.qr', compact('event', 'svg', 'publicUrl', 'version'))->header('Cache-Control', 'private, no-store');
    }

    public function qrStatus(BpppmnuEvent $event, BpppmnuAttendanceService $service)
    {
        $current = $service->activeToken($event);
        $publicUrl = route('public.bpppmnu.show', ['token' => $current['token']]);

        return response()->json([
            'version' => $current['version'],
            'url' => $publicUrl,
            'svg' => $this->qrSvg($publicUrl),
        ])->header('Cache-Control', 'private, no-store');
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
        return Excel::download(new BpppmnuAttendanceExport($reports->recap($event)['rows']), 'presensi-agenda-'.$event->id.'.xlsx');
    }

    public function guestImportTemplate()
    {
        return Excel::download(new BpppmnuGuestInvitationTemplateExport, 'template-import-tamu-agenda.xlsx');
    }

    private function qrSvg(string $value): string
    {
        return (new Writer(new ImageRenderer(new RendererStyle(360), new SvgImageBackEnd)))
            ->writeString($value, 'UTF-8', ErrorCorrectionLevel::H());
    }
}
