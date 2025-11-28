<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        // Pastikan kolom ppdb_status di-select
        $madrasahs = Madrasah::select('*')->with(['ppdbSettings' => function($query) use ($tahun) {
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

            // Get ppdb_status from ppdb_settings
            $ppdbStatus = $ppdbSetting->ppdb_status ?? 'tidak_aktif';

            // Buat slug fallback jika tidak ada ppdb_setting
            $slug = $ppdbSetting?->slug ?? Str::slug($madrasah->name . '-' . $madrasah->id);

            $data = [
                'sekolah' => $madrasah,
                'ppdb_setting' => $ppdbSetting,
                'slug' => $slug, // Slug untuk link (dari ppdb_setting atau fallback)
                'ppdb_status' => $ppdbStatus, // Ambil dari kolom ppdb_status di madrasahs table
                'total' => 0,
                'lulus' => 0,
                'tidak_lulus' => 0,
                'pending' => 0,
                'verifikasi' => 0,
                'status_ppdb' => $ppdbStatus, // Ambil dari kolom ppdb_status di madrasahs table
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
                if ($ppdbStatus === 'buka') {
                    $totalBuka++;
                }
            } else {
                // Jika tidak ada ppdb_setting tapi status buka, tetap hitung sebagai buka
                if ($ppdbStatus === 'buka') {
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
        // Get PPDB setting for current year
        $tahun = now()->year;
        $ppdbSetting = PPDBSetting::where('sekolah_id', $id)
            ->where('tahun', $tahun)
            ->firstOrFail();

        // Hitung jumlah guru dari tenaga pendidik
        $jumlahGuru = $ppdbSetting->sekolah->tenagaPendidikUsers()->count();

        return view('ppdb.dashboard.lp-edit', compact('ppdbSetting', 'jumlahGuru'));
    }

    /**
     * Update school profile
     */
    public function update(Request $request, $sekolahId)
    {
        $request->validate([
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
            'fasilitas.*' => 'nullable',
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
            'galeri_foto' => 'nullable|array|min:1',
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
            // PPDB settings fields
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

        // Get PPDB setting for current year
        $tahun = now()->year;
        $ppdbSetting = PPDBSetting::where('sekolah_id', $sekolahId)
            ->where('tahun', $tahun)
            ->firstOrFail();

        // Get the associated madrasah
        $madrasah = $ppdbSetting->sekolah;

        $data = $request->except(['galeri_foto', 'brosur_pdf', 'name']);

        // Set readonly fields from madrasah table
        $data['nama_sekolah'] = $madrasah->name;
        $data['kabupaten'] = $madrasah->kabupaten;
        $data['alamat'] = $madrasah->alamat;

        // Handle deletion of brosur
        if ($request->input('delete_brosur') == '1') {
            if ($ppdbSetting->brosur_pdf) {
                $brosurPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/brosur/' . $ppdbSetting->brosur_pdf;
                if (file_exists($brosurPath)) {
                    unlink($brosurPath);
                }
            }
            $data['brosur_pdf'] = null;
        }

        // Handle deletion of galeri images
        if ($request->filled('deleted_galeri_foto')) {
            $deletedImages = explode(',', $request->input('deleted_galeri_foto'));
            $currentGaleri = $ppdbSetting->galeri_foto ?? [];
            $currentGaleri = array_diff($currentGaleri, $deletedImages);
            // Delete physical files
            foreach ($deletedImages as $delImage) {
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/images/madrasah/galeri/' . $delImage;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $data['galeri_foto'] = array_values($currentGaleri);
        }

        // Handle array fields (excluding fasilitas which has special handling)
        $arrayFields = ['misi', 'keunggulan', 'jurusan', 'prestasi', 'program_unggulan', 'ekstrakurikuler', 'testimoni', 'faq', 'alur_pendaftaran', 'ppdb_jalur'];
        foreach ($arrayFields as $field) {
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

        // Handle facility photos
        if ($request->hasFile('fasilitas_foto')) {
            $fasilitasFoto = [];
            $galeriPath = public_path('images/madrasah/galeri');

            // Ensure directory exists
            if (!is_dir($galeriPath)) {
                mkdir($galeriPath, 0755, true);
            }

            foreach ($request->file('fasilitas_foto') as $index => $file) {
                if ($file && $file->isValid()) {
                    $filename = time() . '_fasilitas_' . $index . '.' . $file->getClientOriginalExtension();
                    $file->move($galeriPath, $filename);
                    $fasilitasFoto[$index] = $filename;
                }
            }

            // Update fasilitas array with foto field
            if (!empty($fasilitasFoto) && isset($data['fasilitas'])) {
                $fasilitas = $data['fasilitas'];
                foreach ($fasilitas as $key => $fasilitasItem) {
                    if (isset($fasilitasFoto[$key])) {
                        $fasilitas[$key]['foto'] = $fasilitasFoto[$key];
                    }
                }
                $data['fasilitas'] = $fasilitas;
            }
        }

        // Handle file uploads
        if ($request->hasFile('galeri_foto')) {
            $galeriFiles = [];
            $galeriPath = public_path('images/madrasah/galeri');

            // Ensure directory exists
            if (!is_dir($galeriPath)) {
                mkdir($galeriPath, 0755, true);
            }

            foreach ($request->file('galeri_foto') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($galeriPath, $filename);
                    $galeriFiles[] = $filename;
                }
            }
            if (!empty($galeriFiles)) {
                $currentGaleri = $ppdbSetting->galeri_foto ?? [];
                $data['galeri_foto'] = array_merge($currentGaleri, $galeriFiles);
            }
        }

        if ($request->hasFile('brosur_pdf')) {
            $brosurFile = $request->file('brosur_pdf');
            if ($brosurFile->isValid()) {
                $brosurPath = public_path('uploads/brosur');

                // Ensure directory exists
                if (!is_dir($brosurPath)) {
                    mkdir($brosurPath, 0755, true);
                }

                $filename = time() . '_brosur.' . $brosurFile->getClientOriginalExtension();
                $brosurFile->move($brosurPath, $filename);
                $data['brosur_pdf'] = $filename;
            }
        }

        $ppdbSetting->update($data);

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
        $madrasah = Madrasah::findOrFail($id);

        // Get PPDB setting for current year
        $tahun = now()->year;
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasah->id)
            ->where('tahun', $tahun)
            ->first();

        return view('ppdb.dashboard.ppdb-settings', compact('madrasah', 'ppdbSetting'));
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

        $data = $request->except(['galeri_foto', 'brosur_pdf']);

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

        // Find or create PPDB setting for current year
        $tahun = now()->year;
        $ppdbSetting = PPDBSetting::firstOrCreate(
            [
                'sekolah_id' => $madrasah->id,
                'tahun' => $tahun
            ],
            [
                'slug' => Str::slug($madrasah->name . '-' . $madrasah->id . '-' . $tahun),
                'nama_sekolah' => $madrasah->name
            ]
        );

        $ppdbSetting->update($data);

        return redirect()->route('ppdb.lp.ppdb-settings', $madrasah->id)
            ->with('success', 'Pengaturan PPDB berhasil diperbarui');
    }
}
