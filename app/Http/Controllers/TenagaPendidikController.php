<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Madrasah;
use App\Models\StatusKepegawaian;
use Illuminate\Http\Request;

class TenagaPendidikController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Policies\TenagaPendidikPolicy')->only('index');
        $this->middleware('can:create,App\Policies\TenagaPendidikPolicy')->only('store');
        $this->middleware('can:update,App\Policies\TenagaPendidikPolicy')->only('update');
        $this->middleware('can:delete,App\Policies\TenagaPendidikPolicy')->only('destroy');
    }

    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            $tenagaPendidiks = User::with('madrasah')
                ->where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->get();
        } elseif ($user->role === 'pengurus' || $user->role === 'super_admin') {
            $tenagaPendidiks = User::with('madrasah')
                ->where('role', 'tenaga_pendidik')
                ->get();
        } else {
            abort(403, 'Unauthorized access');
        }
        $madrasahs = Madrasah::all();
        $statusKepegawaian = StatusKepegawaian::all();
        return view('masterdata.tenaga-pendidik.index', compact('tenagaPendidiks', 'madrasahs', 'statusKepegawaian'));
    }
}
