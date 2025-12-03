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

        // Hitung jumlah fasilitas dari array fasilitas
        $fasilitasArray = $ppdbSetting->fasilitas ?? [];
        if (is_string($fasilitasArray)) {
            $fasilitasArray = json_decode($fasilitasArray, true) ?? [];
        }
        $jumlahFasilitas = is_array($fasilitasArray) ? count($fasilitasArray) : 0;

        // Hitung jumlah jurusan dari array jurusan
        $jurusanArray = $ppdbSetting->jurusan ?? [];
        if (is_string($jurusanArray)) {
            $jurusanArray = json_decode($jurusanArray, true) ?? [];
        }
        $jumlahJurusan = is_array($jurusanArray) ? count($jurusanArray) : 0;

        // Ambil data kepala sekolah dari users table berdasarkan madrasah_id dan ketugasan
        $kepalaSekolah = $ppdbSetting->sekolah->admins()
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->first();

        return view('ppdb.dashboard.lp-edit', compact('ppdbSetting', 'jumlahGuru', 'jumlahFasilitas', 'jumlahJurusan', 'kepalaSekolah'));
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
            'keunggulan.*' => 'nullable|array',
            'keunggulan.*.title' => 'nullable|string',
            'keunggulan.*.description' => 'nullable|string',
            'keunggulan.*.icon' => 'nullable|string',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'nullable',
            'jurusan' => 'nullable|array',
            'jurusan.*.nama' => 'nullable|string',
            'jurusan.*.prospek_karir' => 'nullable|string',
            'jurusan.*.skill_dipelajari' => 'nullable',
            'prestasi' => 'nullable|array',
            'prestasi.*.title' => 'nullable|string',
            'prestasi.*.description' => 'nullable|string',
            'prestasi.*.year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'program_unggulan' => 'nullable|array',
            'program_unggulan.*' => 'nullable|string',
            'ekstrakurikuler' => 'nullable|array',
            'ekstrakurikuler.*' => 'nullable|string',
            'testimoni' => 'nullable|array',
            'testimoni.*' => 'nullable|string',
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

        // Set kepala sekolah nama from users table
        $kepalaSekolah = $madrasah->admins()
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->first();
        $data['kepala_sekolah_nama'] = $kepalaSekolah ? $kepalaSekolah->name : null;

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

        // Handle array fields (excluding fasilitas, jurusan, prestasi, and keunggulan which have special handling)
        $arrayFields = ['misi', 'program_unggulan', 'ekstrakurikuler', 'testimoni', 'faq', 'alur_pendaftaran', 'ppdb_jalur'];
        foreach ($arrayFields as $field) {
            if ($request->has($field)) {
                $data[$field] = array_filter($request->input($field, []), function($value) {
                    return !empty(trim($value));
                });
            }
        }

        // Special handling for keunggulan (nested objects)
        if ($request->has('keunggulan')) {
            $keunggulanData = $request->input('keunggulan', []);
            $processedKeunggulan = [];

            foreach ($keunggulanData as $keunggulan) {
                // Check if keunggulan is an array (new format) or string (old format)
                if (is_array($keunggulan)) {
                    // New format: check if title is not empty
                    if (!empty(trim($keunggulan['title'] ?? ''))) {
                        $processedKeunggulan[] = [
                            'title' => trim($keunggulan['title']),
                            'description' => trim($keunggulan['description'] ?? ''),
                            'icon' => trim($keunggulan['icon'] ?? 'star')
                        ];
                    }
                } else {
                    // Old format: simple string
                    if (!empty(trim($keunggulan))) {
                        $processedKeunggulan[] = trim($keunggulan);
                    }
                }
            }

            $data['keunggulan'] = $processedKeunggulan;
        }

        // Special handling for prestasi (nested objects)
        if ($request->has('prestasi')) {
            $prestasiData = $request->input('prestasi', []);
            $processedPrestasi = [];

            foreach ($prestasiData as $prestasi) {
                // Check if prestasi is an array (new format) or string (old format)
                if (is_array($prestasi)) {
                    // New format: check if title is not empty
                    if (!empty(trim($prestasi['title'] ?? ''))) {
                        $processedPrestasi[] = [
                            'title' => trim($prestasi['title']),
                            'description' => trim($prestasi['description'] ?? ''),
                            'year' => !empty($prestasi['year']) ? (int)$prestasi['year'] : null
                        ];
                    }
                } else {
                    // Old format: simple string
                    if (!empty(trim($prestasi))) {
                        $processedPrestasi[] = trim($prestasi);
                    }
                }
            }

            $data['prestasi'] = $processedPrestasi;
        }

        // Special handling for fasilitas (keep as one facility even if description contains commas)
        if ($request->has('fasilitas')) {
            $fasilitasData = $request->input('fasilitas', []);
            $processedFasilitas = [];

            // Handle facility photos first (before processing fasilitas data)
            $fasilitasFoto = [];
            if ($request->hasFile('fasilitas_foto')) {
                $galeriPath = $_SERVER['DOCUMENT_ROOT'] . '/images/madrasah/galeri';

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
            }

            foreach ($fasilitasData as $index => $fasilitas) {
                if (is_array($fasilitas) && !empty(trim($fasilitas['name'] ?? ''))) {
                    $name = trim($fasilitas['name']);
                    $description = trim($fasilitas['description'] ?? '');

                    // Keep as one facility even if description contains commas
                    $facility = [
                        'name' => $name,
                        'description' => $description
                    ];
                    // Assign photo: new upload takes precedence, otherwise keep existing
                    if (isset($fasilitasFoto[$index])) {
                        $facility['foto'] = $fasilitasFoto[$index];
                    } elseif (isset($fasilitas['foto'])) {
                        $facility['foto'] = $fasilitas['foto'];
                    }
                    $processedFasilitas[] = $facility;
                }
            }

            $data['fasilitas'] = $processedFasilitas;
            // Update jumlah_sarana automatically
            $data['jumlah_sarana'] = count($processedFasilitas);
        }

        // Special handling for jurusan (nested objects)
        if ($request->has('jurusan')) {
            $jurusanData = $request->input('jurusan', []);
            $processedJurusan = [];

            foreach ($jurusanData as $jurusan) {
                // Check if jurusan is an array (new format) or string (old format)
                if (is_array($jurusan)) {
                    // New format: check if nama is not empty
                    if (!empty(trim($jurusan['nama'] ?? ''))) {
                        $processedJurusan[] = [
                            'nama' => trim($jurusan['nama']),
                            'prospek_karir' => trim($jurusan['prospek_karir'] ?? ''),
                            'skill_dipelajari' => $this->processSkills($jurusan['skill_dipelajari'] ?? '')
                        ];
                    }
                } else {
                    // Old format: simple string
                    if (!empty(trim($jurusan))) {
                        $processedJurusan[] = trim($jurusan);
                    }
                }
            }

            $data['jurusan'] = $processedJurusan;
            // Update jumlah_jurusan automatically
            $data['jumlah_jurusan'] = count($processedJurusan);
        }

        // Handle PPDB kuota jurusan
        if ($request->filled('ppdb_kuota_jurusan')) {
            $data['ppdb_kuota_jurusan'] = $this->processKuotaJurusan($request->input('ppdb_kuota_jurusan', []));
        }



        // Handle file uploads
        if ($request->hasFile('galeri_foto')) {
            $galeriFiles = [];
            $galeriPath = $_SERVER['DOCUMENT_ROOT'] . '/images/madrasah/galeri';

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
                // Use the already processed galeri_foto array (after deletions) or fallback to original
                $currentGaleri = $data['galeri_foto'] ?? $ppdbSetting->galeri_foto ?? [];
                $data['galeri_foto'] = array_merge($currentGaleri, $galeriFiles);
            }
        }

        if ($request->hasFile('brosur_pdf')) {
            $brosurFile = $request->file('brosur_pdf');
            if ($brosurFile->isValid()) {
                $brosurPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/brosur';

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
     * Process skills string into array
     */
    private function processSkills($skillsInput)
    {
        if (is_string($skillsInput)) {
            // Split by comma and trim each skill
            $skills = array_map('trim', explode(',', $skillsInput));
            // Filter out empty skills
            return array_filter($skills, function($skill) {
                return !empty($skill);
            });
        } elseif (is_array($skillsInput)) {
            return $skillsInput;
        }
        return [];
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
            'ppdb_jalur.*' => 'nullable|integer|exists:ppdb_jalur,id',
            'ppdb_biaya_pendaftaran' => 'nullable|string',
            'ppdb_catatan_pengumuman' => 'nullable|string',
        ]);

        $data = $request->except(['galeri_foto', 'brosur_pdf']);

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

        // Jika PPDB status diubah menjadi 'buka' dan belum ada jalur yang dipilih,
        // maka aktifkan semua jalur yang tersedia secara otomatis
        if ($request->input('ppdb_status') === 'buka' && empty($data['ppdb_jalur_active'])) {
            $availableJalur = \App\Models\PPDBJalur::pluck('id')->toArray();
            $data['ppdb_jalur_active'] = $availableJalur;
        }

        $ppdbSetting->update($data);

        return redirect()->route('ppdb.lp.ppdb-settings', $madrasah->id)
            ->with('success', 'Pengaturan PPDB berhasil diperbarui');
    }

    /**
     * Show pendaftar dashboard for a specific school
     */
    public function pendaftar($slug)
    {
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->with('sekolah')
            ->firstOrFail();

        $pendaftars = $ppdbSetting->pendaftars()
            ->with('ppdbJalur')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $statistik = [
            'total' => $ppdbSetting->pendaftars()->count(),
            'lulus' => $ppdbSetting->pendaftars()->where('status', 'lulus')->count(),
            'tidak_lulus' => $ppdbSetting->pendaftars()->where('status', 'tidak_lulus')->count(),
            'pending' => $ppdbSetting->pendaftars()->where('status', 'pending')->count(),
            'verifikasi' => $ppdbSetting->pendaftars()->where('status', 'verifikasi')->count(),
        ];

        return view('ppdb.dashboard.pendaftar', compact('ppdbSetting', 'pendaftars', 'statistik'));
    }

    /**
     * Show detail of a specific pendaftar
     */
    public function showPendaftarDetail($id)
    {
        $pendaftar = PPDBPendaftar::with('ppdbSetting.sekolah')->findOrFail($id);

        return view('ppdb.dashboard.pendaftar-detail', compact('pendaftar'))->render();
    }

    /**
     * Update status of a pendaftar
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,verifikasi,lulus,tidak_lulus'
        ]);

        $pendaftar = PPDBPendaftar::findOrFail($id);
        $pendaftar->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate'
        ]);
    }
}
