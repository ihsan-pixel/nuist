<?php

namespace App\Http\Controllers;

use App\Models\DpsMember;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DpsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $madrasahs = Madrasah::query()
            ->with(['dpsMembers' => function ($query) {
                $query->orderBy('nama');
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
            ->paginate(25)
            ->withQueryString();

        return view('masterdata.dps.index', compact('madrasahs', 'q'));
    }

    public function show(Madrasah $madrasah)
    {
        $madrasah->load(['dpsMembers' => function ($query) {
            $query->orderBy('nama');
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

        DB::transaction(function () use ($validated) {
            foreach ($validated['members'] as $member) {
                DpsMember::create([
                    'madrasah_id' => $validated['madrasah_id'],
                    'nama' => trim($member['nama']),
                    'unsur' => trim($member['unsur']),
                    'periode' => trim($member['periode']),
                ]);
            }
        });

        return redirect()
            ->route('dps.show', $madrasah->id)
            ->with('success', 'Data DPS berhasil ditambahkan.');
    }

    public function edit(DpsMember $member)
    {
        $member->load('madrasah');
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

        return redirect()
            ->route('dps.show', $member->madrasah_id)
            ->with('success', 'Data DPS berhasil diperbarui.');
    }

    public function destroy(DpsMember $member)
    {
        $madrasahId = $member->madrasah_id;
        $member->delete();

        return redirect()
            ->route('dps.show', $madrasahId)
            ->with('success', 'Data DPS berhasil dihapus.');
    }
}

