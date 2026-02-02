<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Madrasah;

class SekolahController extends \App\Http\Controllers\Controller
{
    /**
     * Menampilkan daftar sekolah (madrasah)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check if user is pengurus
        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get search query
        $search = $request->get('search', '');

        // Get madrasah data with search functionality
        $query = Madrasah::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('kabupaten', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        $madrasahs = $query->orderBy('scod', 'asc')
                          ->paginate(10);

        // Get statistics
        $totalSekolah = Madrasah::count();
        $sekolahAktif = Madrasah::count();

        return view('mobile.pengurus.sekolah', compact('madrasahs', 'search', 'totalSekolah', 'sekolahAktif'));
    }

    /**
     * Menampilkan detail sekolah
     */
    public function show($id)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        $madrasah = Madrasah::findOrFail($id);

        return view('mobile.pengurus.sekolah-detail', compact('madrasah'));
    }
}

