<?php

namespace App\Http\Controllers\Mobile\Simfoni;

use App\Http\Controllers\Controller;
use App\Models\Simfoni;
use App\Models\StatusKepegawaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
                'no_hp' => $user->no_hp ?? '',
                'email' => $user->email ?? '',
                'alamat_lengkap' => $user->alamat ?? '',
                'strata_pendidikan' => $user->pendidikan_terakhir ?? '',
            ]);
        }

        // Calculate masa kerja to determine status kepegawaian options
        $masaKerjaYears = 0;
        $tmtDate = $simfoni->tmt ?? $user->tmt ?? null;

        if ($tmtDate) {
            $tmt = Carbon::parse($tmtDate);
            $targetDate = Carbon::create(2025, 7, 31); // July 31, 2025

            $years = $targetDate->diffInYears($tmt);
            $months = $targetDate->diffInMonths($tmt) % 12;

            // Adjust if target day is before TMT day in the same month
            if ($targetDate->day < $tmt->day && $months == 0) {
                $years--;
                $months = 11;
            }

            $masaKerjaYears = $years + ($months / 12);
        }

        // Get status kepegawaian options from database
        if ($masaKerjaYears < 2 && $masaKerjaYears > 0) {
            // Show only GTT (id 6) and PTT (id 8) for masa kerja < 2 years
            $statusKepegawaian = StatusKepegawaian::whereIn('id', [6, 8])->get();
        } else {
            // Show all options for masa kerja >= 2 years
            $statusKepegawaian = StatusKepegawaian::all();
        }

        return view('mobile.simfoni', compact('simfoni', 'user', 'statusKepegawaian'));
    }

    /**
     * Store or update the simfoni data
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Parse currency fields (remove dots) before validation
        $currencyFields = [
            'gaji_sertifikasi',
            'gaji_pokok',
            'honor_lain',
            'penghasilan_lain',
            'penghasilan_pasangan',
            'total_penghasilan'
        ];

        $parsedData = $request->all();
        foreach ($currencyFields as $field) {
            if (isset($parsedData[$field]) && $parsedData[$field] !== null && $parsedData[$field] !== '') {
                $parsedData[$field] = str_replace('.', '', $parsedData[$field]);
            }
        }

        $validated = Validator::make($parsedData, [
            // A. DATA SK
            'nama_lengkap_gelar' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:100',
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
            'masa_kerja' => 'nullable|string|max:100',
            'kategori_penghasilan' => 'nullable|string|max:100',

            // E. STATUS KEKADERAN
            'status_kader_diri' => 'nullable|string|max:255',
            'pendidikan_kader' => 'nullable|string|max:255',
            'status_kader_ayah' => 'nullable|string|max:255',
            'status_kader_ibu' => 'nullable|string|max:255',
            'status_kader_pasangan' => 'nullable|string|max:255',

            // F. DATA KELUARGA
            'nama_ayah' => 'nullable|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'nama_pasangan' => 'nullable|string|max:255',
            'jumlah_anak' => 'nullable|integer|min:0',

            // G. PROYEKSI KE DEPAN
            'akan_kuliah_s2' => 'nullable|in:iya,tidak',
            'akan_mendaftar_pns' => 'nullable|in:iya,tidak',
            'akan_mendaftar_pppk' => 'nullable|in:iya,tidak',
            'akan_mengikuti_ppg' => 'nullable|in:iya,tidak',
            'akan_menulis_buku_modul_riset' => 'nullable|in:iya,tidak',
            'akan_mengikuti_seleksi_diklat_cakep' => 'nullable|in:iya,tidak',
            'akan_membimbing_riset_prestasi_siswa' => 'nullable|in:iya,tidak',
            'akan_masuk_tim_unggulan_sekolah_madrasah' => 'nullable|in:iya,tidak',
            'akan_kompetisi_pimpinan_level_ii' => 'nullable|in:iya,tidak',
            'akan_aktif_mengikuti_pelatihan' => 'nullable|in:iya,tidak',
            'akan_aktif_mgmp_mkk' => 'nullable|in:iya,tidak',
            'akan_mengikuti_pendidikan_kader_nu' => 'nullable|in:iya,tidak',
            'akan_aktif_membantu_kegiatan_lembaga' => 'nullable|in:iya,tidak',
            'akan_aktif_mengikuti_kegiatan_nu' => 'nullable|in:iya,tidak',
            'akan_aktif_ikut_zis_kegiatan_sosial' => 'nullable|in:iya,tidak',
            'akan_mengembangkan_unit_usaha_satpen' => 'nullable|in:iya,tidak',
            'akan_bekerja_disiplin_produktif' => 'nullable|in:iya,tidak',
            'akan_loyal_nu_aktif_masyarakat' => 'nullable|in:iya,tidak',
            'akan_bersedia_dipindah_satpen_lain' => 'nullable|in:iya,tidak',
            'skor_proyeksi' => 'nullable|integer|min:0',
            'pernyataan_setuju' => 'required|accepted',
        ], [
            'required' => ':attribute wajib diisi',
            'date' => ':attribute harus berformat tanggal',
            'email' => 'Format email tidak valid',
            'numeric' => ':attribute harus berupa angka',
            'max' => ':attribute maksimal :max karakter',
            'min' => ':attribute minimal :min',
        ])->validate();

        // Update user data with editable fields from step 1
        $user->update([
            'name' => $validated['nama_lengkap_gelar'],
            'tempat_lahir' => $validated['tempat_lahir'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'nuptk' => $validated['nuptk'],
            'kartanu' => $validated['kartanu'],
            'nipm' => $validated['nipm'],
            'tmt' => $validated['tmt'],
            'pendidikan_terakhir' => $validated['strata_pendidikan'],
            'tahun_lulus' => $validated['tahun_lulus'],
            'program_studi' => $validated['program_studi'],
        ]);

        // Hitung skor proyeksi otomatis berdasarkan pilihan "Ya"
        $skor_proyeksi = 0;
        $proyeksi_fields = [
            'akan_kuliah_s2',
            'akan_mendaftar_pns',
            'akan_mendaftar_pppk',
            'akan_mengikuti_ppg',
            'akan_menulis_buku_modul_riset',
            'akan_mengikuti_seleksi_diklat_cakep',
            'akan_membimbing_riset_prestasi_siswa',
            'akan_masuk_tim_unggulan_sekolah_madrasah',
            'akan_kompetisi_pimpinan_level_ii',
            'akan_aktif_mengikuti_pelatihan',
            'akan_aktif_mgmp_mkk',
            'akan_mengikuti_pendidikan_kader_nu',
            'akan_aktif_membantu_kegiatan_lembaga',
            'akan_aktif_mengikuti_kegiatan_nu',
            'akan_aktif_ikut_zis_kegiatan_sosial',
            'akan_mengembangkan_unit_usaha_satpen',
            'akan_bekerja_disiplin_produktif',
            'akan_loyal_nu_aktif_masyarakat',
            'bersedia_dipindah_satpen_lain'
        ];

        foreach ($proyeksi_fields as $field) {
            if (isset($validated[$field]) && $validated[$field] === 'iya') {
                $skor_proyeksi++;
            }
        }

        $validated['skor_proyeksi'] = $skor_proyeksi;

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
