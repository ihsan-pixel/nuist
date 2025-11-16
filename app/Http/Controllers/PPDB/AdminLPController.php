<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use Illuminate\Http\Request;

class AdminLPController extends Controller
{
    /**
     * Dashboard Admin LP. Ma'arif
     * Monitoring semua sekolah, statistik, dan grafik pendaftar
     */
    public function index()
    {
        $tahun = now()->year;

        // Ambil semua madrasah dan PPDB setting untuk tahun ini
        $madrasahs = \App\Models\Madrasah::with(['ppdbSettings' => function($query) use ($tahun) {
            $query->where('tahun', $tahun);
        }])->get();

        // Statistik keseluruhan
        $totalSekolah = $madrasahs->count();
        $totalPendaftar = 0;
        $totalBuka = 0;

        $statistik = [
            'total_sekolah' => $totalSekolah,
            'total_pendaftar' => 0,
            'pending' => 0,
            'verifikasi' => 0,
            'lulus' => 0,
            'tidak_lulus' => 0,
        ];

        // Detail per sekolah
        $detailSekolah = $madrasahs->map(function ($madrasah) use (&$statistik, &$totalPendaftar, &$totalBuka) {
            $ppdbSetting = $madrasah->ppdbSettings->first();

            $data = [
                'sekolah' => $madrasah,
                'ppdb_setting' => $ppdbSetting,
                'total' => 0,
                'lulus' => 0,
                'tidak_lulus' => 0,
                'pending' => 0,
                'verifikasi' => 0,
                'status_ppdb' => $madrasah->ppdb_status ?? 'tidak_aktif', // Ambil dari kolom ppdb_status di madrasahs table
            ];

            if ($ppdbSetting) {
                $pendaftars = $ppdbSetting->pendaftars();

                $data['total'] = $pendaftars->count();
                $data['lulus'] = $pendaftars->where('status', 'lulus')->count();
                $data['tidak_lulus'] = $pendaftars->where('status', 'tidak_lulus')->count();
                $data['pending'] = $pendaftars->where('status', 'pending')->count();
                $data['verifikasi'] = $pendaftars->where('status', 'verifikasi')->count();

                // Update statistik keseluruhan
                $statistik['total_pendaftar'] += $data['total'];
                $statistik['pending'] += $data['pending'];
                $statistik['verifikasi'] += $data['verifikasi'];
                $statistik['lulus'] += $data['lulus'];
                $statistik['tidak_lulus'] += $data['tidak_lulus'];

                // Hitung total_buka berdasarkan ppdb_status dari madrasahs table
                if ($data['status_ppdb'] === 'buka') {
                    $totalBuka++;
                }
            }

            return $data;
        });

        $statistik['total_buka'] = $totalBuka;

        return view('ppdb.dashboard.lp', compact('statistik', 'detailSekolah', 'madrasahs'));
    }

    /**
     * Detail PPDB per sekolah untuk LP
     */
    public function detailSekolah($slug)
    {
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->with('sekolah')
            ->firstOrFail();

        $pendaftars = $ppdbSetting->pendaftars()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistik = [
            'total' => $ppdbSetting->pendaftars()->count(),
            'lulus' => $ppdbSetting->pendaftars()->where('status', 'lulus')->count(),
            'tidak_lulus' => $ppdbSetting->pendaftars()->where('status', 'tidak_lulus')->count(),
            'pending' => $ppdbSetting->pendaftars()->where('status', 'pending')->count(),
            'verifikasi' => $ppdbSetting->pendaftars()->where('status', 'verifikasi')->count(),
        ];

        return view('ppdb.dashboard.lp-detail', compact('ppdbSetting', 'pendaftars', 'statistik'));
    }

    /**
     * Show edit form for school profile
     */
    public function edit($id)
    {
        $madrasah = \App\Models\Madrasah::findOrFail($id);

        // Hitung jumlah guru dari tenaga pendidik
        $jumlahGuru = $madrasah->tenagaPendidikUsers()->count();

        return view('ppdb.dashboard.lp-edit', compact('madrasah', 'jumlahGuru'));
    }

    /**
     * Update school profile
     */
    public function update(Request $request, $id)
    {
        $madrasah = \App\Models\Madrasah::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'alamat' => 'required|string',
            'tagline' => 'nullable|string|max:255',
            'deskripsi_singkat' => 'nullable|string',
            'tahun_berdiri' => 'nullable|integer|min:1800|max:' . (date('Y') + 1),
            'sejarah' => 'nullable|string',
            'akreditasi' => 'nullable|string|max:10',
            'nilai_nilai' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|array',
            'misi.*' => 'nullable|string',
            'keunggulan' => 'nullable|array',
            'keunggulan.*' => 'nullable|string',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'nullable|string',
            'jurusan' => 'nullable|array',
            'jurusan.*' => 'nullable|string',
            'prestasi' => 'nullable|array',
            'prestasi.*' => 'nullable|string',
            'program_unggulan' => 'nullable|array',
            'program_unggulan.*' => 'nullable|string',
            'ekstrakurikuler' => 'nullable|array',
            'ekstrakurikuler.*' => 'nullable|string',
            'testimoni' => 'nullable|array',
            'testimoni.*' => 'nullable|string',
            'kepala_sekolah_nama' => 'nullable|string|max:255',
            'kepala_sekolah_gelar' => 'nullable|string|max:255',
            'kepala_sekolah_sambutan' => 'nullable|string',
            'jumlah_siswa' => 'nullable|integer|min:0',
            'jumlah_guru' => 'nullable|integer|min:0',
            'jumlah_jurusan' => 'nullable|integer|min:0',
            'jumlah_sarana' => 'nullable|integer|min:0',
            'galeri_foto' => 'nullable|array',
            'galeri_foto.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_profile' => 'nullable|string|url',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|url',
            'jam_operasional_buka' => 'nullable|date_format:H:i',
            'jam_operasional_tutup' => 'nullable|date_format:H:i',
            'brosur_pdf' => 'nullable|file|mimes:pdf|max:5120',
            'faq' => 'nullable|array',
            'faq.*' => 'nullable|string',
            'alur_pendaftaran' => 'nullable|array',
            'alur_pendaftaran.*' => 'nullable|string',
            // Note: PPDB settings validation moved to updatePPDBSettings method
        ]);

        $data = $request->except(['galeri_foto', 'brosur_pdf', 'ppdb_status', 'ppdb_jadwal_buka', 'ppdb_jadwal_tutup', 'ppdb_kuota_total', 'ppdb_jadwal_pengumuman', 'ppdb_kuota_jurusan', 'ppdb_jalur', 'ppdb_biaya_pendaftaran', 'ppdb_catatan_pengumuman']);

        // Handle array fields
        $arrayFields = ['misi', 'keunggulan', 'fasilitas', 'jurusan', 'prestasi', 'program_unggulan', 'ekstrakurikuler', 'testimoni', 'faq', 'alur_pendaftaran'];
        foreach ($arrayFields as $field) {
            if ($request->has($field)) {
                $data[$field] = array_filter($request->input($field, []), function($value) {
                    return !empty(trim($value));
                });
            }
        }



        // Handle file uploads
        if ($request->hasFile('galeri_foto')) {
            $galeriFiles = [];
            foreach ($request->file('galeri_foto') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/madrasah/galeri'), $filename);
                    $galeriFiles[] = $filename;
                }
            }
            if (!empty($galeriFiles)) {
                $data['galeri_foto'] = array_merge($madrasah->galeri_foto ?? [], $galeriFiles);
            }
        }

        if ($request->hasFile('brosur_pdf')) {
            $brosurFile = $request->file('brosur_pdf');
            if ($brosurFile->isValid()) {
                $filename = time() . '_brosur.' . $brosurFile->getClientOriginalExtension();
                $brosurFile->move(public_path('uploads/brosur'), $filename);
                $data['brosur_pdf'] = $filename;
            }
        }

        $madrasah->update($data);

        return redirect()->route('ppdb.lp.dashboard')->with('success', 'Profil madrasah berhasil diperbarui.');
    }

    /**
     * Process kuota jurusan array into proper format
     */
    private function processKuotaJurusan($kuotaJurusan)
    {
        $processed = [];
        if (is_array($kuotaJurusan)) {
            foreach ($kuotaJurusan as $key => $value) {
                if (!empty($key) && !empty($value)) {
                    $processed[$key] = $value;
                }
            }
        }
        return $processed;
    }

    /**
     * Handle PPDB Jalur creation, update, and deletion
     * Note: This method is now deprecated as jalur is stored as JSON
     */
    private function handlePPDBJalur($ppdbSetting, $jalurArray)
    {
        // Method kept for backward compatibility but not used
        // Jalur is now stored as JSON in ppdb_jalur column
    }

    /**
     * Show PPDB settings page
     */
    public function ppdbSettings($id)
    {
        $madrasah = \App\Models\Madrasah::findOrFail($id);
        return view('ppdb.dashboard.ppdb-settings', compact('madrasah'));
    }

    /**
     * Update PPDB settings
     */
    public function updatePPDBSettings(Request $request, $id)
    {
        $madrasah = \App\Models\Madrasah::findOrFail($id);

        $validated = $request->validate([
            'ppdb_status' => 'nullable|string|in:tutup,buka',
            'ppdb_jadwal_buka' => 'nullable|date',
            'ppdb_jadwal_tutup' => 'nullable|date|after:ppdb_jadwal_buka',
            'ppdb_kuota_total' => 'nullable|integer|min:0',
            'ppdb_jadwal_pengumuman' => 'nullable|date',
            'ppdb_kuota_jurusan' => 'nullable|array',
            'ppdb_kuota_jurusan.*' => 'nullable|string',
            'ppdb_jalur' => 'nullable|array',
            'ppdb_jalur.*' => 'nullable|string',
            'ppdb_biaya_pendaftaran' => 'nullable|string',
            'ppdb_catatan_pengumuman' => 'nullable|string',
        ]);

        $data = $request->only(['ppdb_status', 'ppdb_jadwal_buka', 'ppdb_jadwal_tutup', 'ppdb_kuota_total', 'ppdb_jadwal_pengumuman', 'ppdb_biaya_pendaftaran', 'ppdb_catatan_pengumuman']);

        // Handle PPDB jalur
        $ppdbArrayFields = ['ppdb_jalur'];
        foreach ($ppdbArrayFields as $field) {
            if ($request->has($field)) {
                $data[$field] = array_filter($request->input($field, []), function($value) {
                    return !empty(trim($value));
                });
            }
        }

        // Handle PPDB kuota jurusan
        if ($request->filled('ppdb_kuota_jurusan')) {
            $data['ppdb_kuota_jurusan'] = $this->processKuotaJurusan($request->input('ppdb_kuota_jurusan', []));
        }

        $madrasah->update($data);

        return redirect()->route('ppdb.lp.ppdb-settings', $madrasah->id)
            ->with('success', 'Pengaturan PPDB berhasil diperbarui');
    }
}
