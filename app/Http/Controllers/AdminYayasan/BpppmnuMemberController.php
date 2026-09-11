<?php

namespace App\Http\Controllers\AdminYayasan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BpppmnuMemberController extends Controller
{
    public function index()
    {
        $members = User::where('role', 'pengurus_bpppmnu')->orderBy('name')->paginate(30);

        return view('admin.bpppmnu.members', compact('members'));
    }

    public function store(Request $request)
    {
        return $this->save($request, new User);
    }

    public function update(Request $request, User $member)
    {
        abort_unless($member->role === 'pengurus_bpppmnu', 404);

        return $this->save($request, $member);
    }

    private function save(Request $request, User $member)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($member->id)],
            'ketugasan' => 'nullable|string|max:255', 'is_active' => 'required|boolean',
            'password' => [$member->exists ? 'nullable' : 'required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&]/', 'confirmed'],
        ]);
        $action = $member->exists ? 'updated' : 'created';
        $reset = ! empty($data['password']);
        if ($reset) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        DB::transaction(function () use ($member, $data, $reset) {
            $member->fill($data);
            $member->role = 'pengurus_bpppmnu';
            $member->save();
            if ($reset || ! $member->is_active) {
                $member->tokens()->delete();
            }
        });
        Log::info('BPPPMNU account '.$action, ['actor_id' => $request->user()->id, 'user_id' => $member->id, 'password_reset' => $reset, 'active' => $member->is_active]);

        return back()->with('success', 'Data pengurus berhasil disimpan.');
    }
}
