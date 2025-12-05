<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Simfoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SimfoniController extends Controller
{
    /**
     * Show the form for creating or editing simfoni
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get existing simfoni record or create new
        $simfoni = Simfoni::where('user_id', $user->id)->first();
        
        if (!$simfoni) {
            $simfoni = new Simfoni([
                'user_id' => $user->id,
                'nama_lengkap_gelar' => $user->name ?? '',
                'tempat_lahir' => $user->tempat_lahir ?? '',
                'tanggal_lahir' => $user->tanggal_lahir ?? null,
                'nuptk' => $user->nuptk ?? '',
                'kartanu' => $user->kartanu ?? '',
                'nipm' => $user->nipm ?? '',
                'tmt' => $user->tmt ?? null,
                'program_studi' => $user->program_studi ?? '',
                'no_hp' => $user->phone ?? '',
                'email' => $user->email ?? '',
                'alamat_lengkap' => $user->alamat ?? '',
                'strata_pendidikan' => $user->pendidikan_terakhir ?? '',
            ]);
        }
        
        return view('mobile.simfoni', compact('simfoni', 'user'));
    }

    /**
     * Store or update the simfoni data
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            // A. DATA SK
            'nama_lengkap_gelar' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nuptk' => 'nullable|string|max:20',
            'kartanu' => 'nullable|string|max:50',
            'nipm' => 'nullable|string|max:50',
            'nik' => 'required|string|max:20',
            'tmt' => 'required|date',
            'strata_pendidikan' => 'required|string|max:100',
            'pt_asal' => 'nullable|string|max:255',
            'tahun_lulus' => 'required|integer|min:1900|max:2100',
            'program_studi' => 'required|string|max:255',
            
            // B. RIWAYAT KERJA
            'status_kerja' => 'required|string|max:100',
            'tanggal_sk_pertama' => 'required|date',
            'nomor_sk_pertama' => 'required|string|max:100',
            'nomor_sertifikasi_pendidik' => 'nullable|string|max:100',
            'riwayat_kerja_sebelumnya' => 'nullable|string',
            
            // C. KEAHLIAN DAN DATA LAIN
            'keahlian' => 'nullable|string',
            'kedudukan_lpm' => 'nullable|string|max:100',
            'prestasi' => 'nullable|string',
            'tahun_sertifikasi_impassing' => 'nullable|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'status_pernikahan' => 'required|string|max:50',
            'alamat_lengkap' => 'required|string',
            
            // D. DATA KEUANGAN/KESEJAHTERAAN
            'bank' => 'nullable|string|max:100',
            'nomor_rekening' => 'nullable|string|max:50',
            'gaji_sertifikasi' => 'nullable|numeric|min:0',
            'gaji_pokok' => 'nullable|numeric|min:0',
            'honor_lain' => 'nullable|numeric|min:0',
            'penghasilan_lain' => 'nullable|numeric|min:0',
            'penghasilan_pasangan' => 'nullable|numeric|min:0',
            'total_penghasilan' => 'nullable|numeric|min:0',
        ], [
            'required' => ':attribute wajib diisi',
            'date' => ':attribute harus berformat tanggal',
            'email' => 'Format email tidak valid',
            'numeric' => ':attribute harus berupa angka',
            'max' => ':attribute maksimal :max karakter',
            'min' => ':attribute minimal :min',
        ]);

        // Get or create simfoni record
        $simfoni = Simfoni::where('user_id', $user->id)->first();
        
        if ($simfoni) {
            $simfoni->update($validated);
        } else {
            $validated['user_id'] = $user->id;
            $simfoni = Simfoni::create($validated);
        }

        return redirect()->back()->with('success', 'Data SK berhasil disimpan!');
    }
}
