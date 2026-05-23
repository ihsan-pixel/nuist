<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendarEvent;
use App\Models\Madrasah;
use App\Services\AcademicCalendarEventService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AcademicCalendarEventController extends Controller
{
    public function __construct(private AcademicCalendarEventService $academicCalendarEventService)
    {
    }

    public function index()
    {
        $user = Auth::user();
        $schoolId = $this->resolveAdminSchoolId($user);
        $school = Madrasah::findOrFail($schoolId);

        $events = AcademicCalendarEvent::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('start_date')
            ->orderByDesc('start_time')
            ->get();

        return view('academic-calendar-events.index', compact('events', 'school'));
    }

    public function create()
    {
        $school = Madrasah::findOrFail($this->resolveAdminSchoolId(Auth::user()));

        return view('academic-calendar-events.form', [
            'event' => new AcademicCalendarEvent([
                'is_all_day' => true,
                'is_active' => true,
                'start_date' => now('Asia/Jakarta')->toDateString(),
                'end_date' => now('Asia/Jakarta')->toDateString(),
            ]),
            'school' => $school,
            'isEdit' => false,
            'typeOptions' => AcademicCalendarEvent::typeOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $schoolId = $this->resolveAdminSchoolId($user);
        $validated = $this->validatePayload($request);

        $this->ensureNoConflict($schoolId, $validated);

        $event = AcademicCalendarEvent::create(array_merge($validated, [
            'school_id' => $schoolId,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]));

        $this->academicCalendarEventService->syncEvent($event);

        return redirect()
            ->route('academic-calendar-events.index')
            ->with('success', 'Kalender akademik berhasil ditambahkan dan sinkron ke presensi mengajar.');
    }

    public function edit(AcademicCalendarEvent $academicCalendarEvent)
    {
        $this->authorizeEvent($academicCalendarEvent);

        return view('academic-calendar-events.form', [
            'event' => $academicCalendarEvent,
            'school' => $academicCalendarEvent->school,
            'isEdit' => true,
            'typeOptions' => AcademicCalendarEvent::typeOptions(),
        ]);
    }

    public function update(Request $request, AcademicCalendarEvent $academicCalendarEvent)
    {
        $this->authorizeEvent($academicCalendarEvent);
        $validated = $this->validatePayload($request);

        $this->ensureNoConflict($academicCalendarEvent->school_id, $validated, $academicCalendarEvent->id);

        $academicCalendarEvent->update(array_merge($validated, [
            'updated_by' => Auth::id(),
        ]));

        $this->academicCalendarEventService->syncEvent($academicCalendarEvent->fresh());

        return redirect()
            ->route('academic-calendar-events.index')
            ->with('success', 'Kalender akademik berhasil diperbarui.');
    }

    public function destroy(AcademicCalendarEvent $academicCalendarEvent)
    {
        $this->authorizeEvent($academicCalendarEvent);

        $this->academicCalendarEventService->removeGeneratedAttendancesForEvent($academicCalendarEvent);
        $academicCalendarEvent->delete();

        return redirect()
            ->route('academic-calendar-events.index')
            ->with('success', 'Kalender akademik berhasil dihapus.');
    }

    private function validatePayload(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'event_type' => ['required', Rule::in(array_keys(AcademicCalendarEvent::typeOptions()))],
            'custom_type_label' => ['nullable', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_all_day' => ['nullable', 'boolean'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_all_day'] = $request->boolean('is_all_day', true);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['custom_type_label'] = trim((string) ($validated['custom_type_label'] ?? ''));

        if ($validated['event_type'] !== AcademicCalendarEvent::TYPE_CUSTOM) {
            $validated['custom_type_label'] = null;
        }

        if ($validated['event_type'] === AcademicCalendarEvent::TYPE_CUSTOM && $validated['custom_type_label'] === '') {
            throw ValidationException::withMessages([
                'custom_type_label' => 'Jenis kegiatan khusus wajib diisi.',
            ]);
        }

        if ($validated['is_all_day']) {
            $validated['start_time'] = null;
            $validated['end_time'] = null;
        } else {
            if (empty($validated['start_time']) || empty($validated['end_time'])) {
                throw ValidationException::withMessages([
                    'start_time' => 'Jam mulai dan jam selesai wajib diisi jika kegiatan tidak seharian.',
                ]);
            }

            if ($validated['start_time'] >= $validated['end_time']) {
                throw ValidationException::withMessages([
                    'end_time' => 'Jam selesai harus setelah jam mulai.',
                ]);
            }
        }

        return $validated;
    }

    private function ensureNoConflict(int $schoolId, array $validated, ?int $ignoreId = null): void
    {
        if (!$validated['is_active']) {
            return;
        }

        $startDate = Carbon::parse($validated['start_date'], 'Asia/Jakarta')->startOfDay();
        $endDate = Carbon::parse($validated['end_date'], 'Asia/Jakarta')->startOfDay();

        $conflict = AcademicCalendarEvent::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->get()
            ->first(function (AcademicCalendarEvent $event) use ($validated, $startDate, $endDate) {
                return $event->conflictsWithWindow(
                    $startDate,
                    $endDate,
                    (bool) $validated['is_all_day'],
                    $validated['start_time'] ?? null,
                    $validated['end_time'] ?? null,
                );
            });

        if ($conflict) {
            throw ValidationException::withMessages([
                'conflict' => 'Jadwal kegiatan bentrok dengan event "' . $conflict->name . '" pada periode ' . $conflict->date_range_label . '.',
            ]);
        }
    }

    private function authorizeEvent(AcademicCalendarEvent $academicCalendarEvent): void
    {
        $schoolId = $this->resolveAdminSchoolId(Auth::user());

        if ($academicCalendarEvent->school_id !== $schoolId) {
            abort(403);
        }
    }

    private function resolveAdminSchoolId($user): int
    {
        if (!$user || $user->role !== 'admin' || !$user->madrasah_id) {
            abort(403);
        }

        return (int) $user->madrasah_id;
    }
}
