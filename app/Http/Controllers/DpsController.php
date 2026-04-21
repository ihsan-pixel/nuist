<?php

namespace App\Http\Controllers;

use App\Models\DpsMember;
use App\Models\Madrasah;
use App\Services\DpsAccountService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DpsMembersImport;

class DpsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $madrasahs = Madrasah::query()
            ->with(['dpsMembers' => function ($query) {
                $query->with('user')->orderBy('nama');
            }])
            ->whereHas('dpsMembers')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('scod', 'like', "%{$q}%")
                        ->orWhere('name', 'like', "%{$q}%")
                        ->orWhereHas('dpsMembers', function ($m) use ($q) {
                            $m->where('nama', 'like', "%{$q}%")
                                ->orWhere('unsur', 'like', "%{$q}%")
                                ->orWhere('periode', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByRaw('CAST(scod AS UNSIGNED) ASC')
            ->get();

        return view('masterdata.dps.index', compact('madrasahs', 'q'));
    }

    public function show(Madrasah $madrasah)
    {
        $madrasah->load(['dpsMembers' => function ($query) {
            $query->with('user')->orderBy('nama');
        }]);

        return view('masterdata.dps.show', compact('madrasah'));
    }

    public function create(Request $request)
    {
        $madrasahs = Madrasah::orderByRaw('CAST(scod AS UNSIGNED) ASC')->get(['id', 'scod', 'name']);
        $prefillMadrasahId = $request->query('madrasah_id');

        return view('masterdata.dps.create', compact('madrasahs', 'prefillMadrasahId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'madrasah_id' => 'required|integer',
            'members' => 'required|array|min:1',
            'members.*.nama' => 'required|string|max:255',
            'members.*.unsur' => 'required|string|max:255',
            'members.*.periode' => 'required|string|max:50',
        ]);

        $madrasah = Madrasah::findOrFail($validated['madrasah_id']);

        $createdCredentials = [];
        $accountService = new DpsAccountService();

        DB::transaction(function () use ($validated, &$createdCredentials, $accountService) {
            foreach ($validated['members'] as $member) {
                $dps = DpsMember::create([
                    'madrasah_id' => $validated['madrasah_id'],
                    'nama' => trim($member['nama']),
                    'unsur' => trim($member['unsur']),
                    'periode' => trim($member['periode']),
                ]);

                $dps->loadMissing('madrasah');
                $cred = $accountService->ensureUser($dps);
                if ($cred) {
                    $createdCredentials[] = [
                        'nama' => $dps->nama,
                        'email' => $cred['email'],
                        'password' => $cred['password'],
                    ];
                }
            }
        });

        $redirect = redirect()
            ->route('dps.show', $madrasah->id)
            ->with('success', 'Data DPS berhasil ditambahkan.');

        if (!empty($createdCredentials)) {
            $redirect->with('dps_created_credentials', $createdCredentials);
        }

        return $redirect;
    }

    public function edit(DpsMember $member)
    {
        $member->load(['madrasah', 'user']);
        return view('masterdata.dps.edit', compact('member'));
    }

    public function update(Request $request, DpsMember $member)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'unsur' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
        ]);

        $member->update($validated);

        // Keep linked DPS user in sync (name/school).
        if ($member->user_id) {
            $user = User::find($member->user_id);
            if ($user && $user->role === 'dps') {
                $user->name = $member->nama;
                $user->madrasah_id = $member->madrasah_id;
                $user->save();
            }
        }

        return redirect()
            ->route('dps.show', $member->madrasah_id)
            ->with('success', 'Data DPS berhasil diperbarui.');
    }

    public function destroy(DpsMember $member)
    {
        $madrasahId = $member->madrasah_id;

        // Remove linked DPS login account too (only if it's a DPS account).
        if ($member->user_id) {
            $user = User::find($member->user_id);
            if ($user && $user->role === 'dps') {
                $user->delete();
            }
        }
        $member->delete();

        return redirect()
            ->route('dps.show', $madrasahId)
            ->with('success', 'Data DPS berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $import = new DpsMembersImport();
        Excel::import($import, $request->file('file'));

        $msg = "Import selesai. Ditambahkan: {$import->createdCount}. Dilewati: {$import->skippedCount}.";

        $redirect = redirect()->route('dps.index')->with('success', $msg);

        if (!empty($import->errors)) {
            $redirect->with('error', 'Ada beberapa baris yang gagal diproses (lihat detail di bawah).')
                     ->with('dps_import_errors', array_slice($import->errors, 0, 30));
        }

        if (!empty($import->createdCredentials)) {
            $token = (string) Str::uuid();
            $dir = 'dps_credentials';
            $path = "{$dir}/{$token}.csv";

            $lines = [];
            $lines[] = "nama,email,password";
            foreach ($import->createdCredentials as $row) {
                $nama = str_replace('"', '""', (string)($row['nama'] ?? ''));
                $email = str_replace('"', '""', (string)($row['email'] ?? ''));
                $password = str_replace('"', '""', (string)($row['password'] ?? ''));
                $lines[] = "\"{$nama}\",\"{$email}\",\"{$password}\"";
            }

            Storage::disk('local')->put($path, implode("\n", $lines));

            $redirect->with('dps_credentials_token', $token);
        }

        return $redirect;
    }

    public function downloadCredentials(string $token)
    {
        if (!Str::isUuid($token)) {
            abort(404);
        }

        $path = "dps_credentials/{$token}.csv";
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->download($path, "dps-akun-{$token}.csv");
    }
}
