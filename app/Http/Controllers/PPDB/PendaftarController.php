<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use App\Models\PPDBJalur;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PPDBRegistrationConfirmation;

class PendaftarController extends Controller
{
    /**
     * Menampilkan form pendaftaran
     */
    public function create($slug)
    {
        // Coba cari berdasarkan slug PPDB setting
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->with('sekolah')
            ->first();

        // Jika tidak ditemukan berdasarkan slug, coba cari berdasarkan ID madrasah
        if (!$ppdbSetting && is_numeric($slug)) {
            $madrasah = Madrasah::find($slug);
            if ($madrasah) {
                $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasah->id)
                    ->where('tahun', now()->year)
                    ->with('sekolah')
                    ->first();
            }
        }

        // Jika masih tidak ditemukan, buat objek temporary untuk tampilan
        if (!$ppdbSetting) {
            $madrasah = is_numeric($slug) ? Madrasah::find($slug) : null;
            if (!$madrasah) {
                abort(404, 'Sekolah tidak ditemukan');
            }

            $ppdbSetting = (object) [
                'id' => null,
                'slug' => $slug,
                'nama_sekolah' => $madrasah->name,
                'tahun' => now()->year,
                'status' => 'tutup',
                'sekolah' => $madrasah,
                'jalurs' => collect()
            ];
        }

        // Ambil jalur dari ppdb_jalur table berdasarkan ID yang tersimpan di ppdb_settings
        $jalurs = collect();
        if ($ppdbSetting && $ppdbSetting->ppdb_jalur) {
            $jalurIds = is_array($ppdbSetting->ppdb_jalur) ? $ppdbSetting->ppdb_jalur : json_decode($ppdbSetting->ppdb_jalur, true);
            if (is_array($jalurIds)) {
                $jalurRecords = PPDBJalur::whereIn('id', $jalurIds)->orderBy('nama_jalur')->get();
                foreach ($jalurRecords as $jalur) {
                    $jalurs->push((object) [
                        'id' => $jalur->id,
                        'nama_jalur' => $jalur->nama_jalur,
                        'keterangan' => $jalur->keterangan ?? '',
                    ]);
                }
            }
        }

        // Jika masih kosong, buat jalur default
        if ($jalurs->isEmpty()) {
            $jalurs = collect([
                (object) ['id' => 1, 'nama_jalur' => 'Jalur Reguler', 'keterangan' => 'Jalur pendaftaran biasa'],
                (object) ['id' => 2, 'nama_jalur' => 'Jalur Prestasi', 'keterangan' => 'Untuk siswa berprestasi'],
                (object) ['id' => 3, 'nama_jalur' => 'Jalur Afirmasi', 'keterangan' => 'Untuk siswa dari keluarga kurang mampu'],
            ]);
        }

        return view('ppdb.daftar', compact('ppdbSetting', 'jalurs'));
    }

    /**
     * Menyimpan data pendaftar baru
     */
    public function store(Request $request, $slug)
    {
        // Coba cari PPDB setting berdasarkan slug
        $ppdbSetting = PPDBSetting::where('slug', $slug)
            ->where('tahun', now()->year)
            ->first();

        // Jika tidak ditemukan, coba cari berdasarkan ID madrasah
        if (!$ppdbSetting && is_numeric($slug)) {
            $madrasah = Madrasah::find($slug);
            if ($madrasah) {
                $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasah->id)
                    ->where('tahun', now()->year)
                    ->first();
            }
        }

        // Jika PPDB setting tidak ditemukan atau tidak aktif, redirect dengan error
        if (!$ppdbSetting || $ppdbSetting->ppdb_status !== 'buka') {
            return redirect()->back()
                ->with('error', 'Pendaftaran belum dibuka atau telah ditutup untuk sekolah ini.')
                ->withInput();
        }

        // Cek waktu pendaftaran
        if ($ppdbSetting->ppdb_jadwal_tutup && now()->isAfter($ppdbSetting->ppdb_jadwal_tutup)) {
            return redirect()->back()
                ->with('error', 'Pendaftaran telah ditutup')
                ->withInput();
        }

        // Validasi input
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'nik' => 'required|string|max:16|min:16|regex:/^[0-9]+$/',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'nisn' => 'required|string|max:20|unique:ppdb_pendaftar,nisn',
            'asal_sekolah' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string|max:255',
            'ppdb_jalur_id' => 'required|integer|exists:ppdb_jalur,id',
            'jurusan_pilihan' => 'required|string|max:50',
            'ppdb_nomor_whatsapp_siswa' => 'required|string|max:25',
            'ppdb_email_siswa' => 'required|email|max:100',
            // Optional second-option flag and conditional fields
            'use_opsi_ke_2' => 'nullable|in:0,1',
            'ppdb_opsi_pilihan_ke_2' => 'required_if:use_opsi_ke_2,1|nullable|integer|exists:madrasahs,id',
            'ppdb_jurusan_pilihan_alt' => 'required_if:use_opsi_ke_2,1|nullable|array',
            'ppdb_jurusan_pilihan_alt.*' => 'required_if:use_opsi_ke_2,1|nullable|string',
            'berkas_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_ijazah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            // Optional fields for step 4
            'nama_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'nomor_hp_orangtua' => 'nullable|string|max:25',
            'npsn_sekolah_asal' => 'nullable|string|max:20',
            'tahun_lulus' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),

            'rata_rata_nilai_raport' => 'nullable|numeric|min:0|max:100',
            'nomor_ijazah' => 'nullable|string|max:50',
            'nomor_skhun' => 'nullable|string|max:50',
            'rencana_lulus' => 'required|in:kuliah,kerja',
            // Optional file uploads
            'berkas_akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_sertifikat_prestasi' => 'nullable|array',
            'berkas_sertifikat_prestasi.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_kip_pkh' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_ktp_ayah' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_ktp_ibu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'nik.required' => 'NIK harus diisi',
            'nik.max' => 'NIK maksimal 16 digit',
            'nik.min' => 'NIK minimal 16 digit',
            'nik.regex' => 'NIK hanya boleh berisi angka',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'agama.required' => 'Agama harus dipilih',
            'nisn.required' => 'NISN harus diisi',
            'nisn.unique' => 'NISN sudah terdaftar',
            'asal_sekolah.required' => 'Asal sekolah harus diisi',
            'provinsi.required' => 'Provinsi harus dipilih',
            'kabupaten.required' => 'Kabupaten/Kota harus dipilih',
            'kecamatan.required' => 'Kecamatan harus dipilih',
            'desa.required' => 'Desa/Kelurahan harus dipilih',
            'alamat_lengkap.required' => 'Alamat lengkap harus diisi',
            'ppdb_jalur_id.required' => 'Jalur pendaftaran harus dipilih',
            'jurusan_pilihan.required' => 'Pilihan jurusan harus diisi',
            'ppdb_nomor_whatsapp_siswa.required' => 'Nomor WhatsApp siswa harus diisi',
            'ppdb_email_siswa.required' => 'Email siswa harus diisi',
            'berkas_kk.required' => 'Berkas Kartu Keluarga harus diunggah',
            'berkas_ijazah.required' => 'Berkas Ijazah harus diunggah',
            'rencana_lulus.required' => 'Rencana setelah lulus harus dipilih',
            'rencana_lulus.in' => 'Rencana setelah lulus tidak valid',
        ]);

        // Ambil jalur dari ppdb_jalur table berdasarkan ID yang tersimpan di ppdb_settings
        $jalurs = collect();
        if ($ppdbSetting && $ppdbSetting->ppdb_jalur) {
            $jalurIds = is_array($ppdbSetting->ppdb_jalur) ? $ppdbSetting->ppdb_jalur : json_decode($ppdbSetting->ppdb_jalur, true);
            if (is_array($jalurIds)) {
                $jalurRecords = PPDBJalur::whereIn('id', $jalurIds)->orderBy('nama_jalur')->get();
                foreach ($jalurRecords as $jalur) {
                    $jalurs->push((object) [
                        'id' => $jalur->id,
                        'nama_jalur' => $jalur->nama_jalur,
                        'keterangan' => $jalur->keterangan ?? '',
                    ]);
                }
            }
        }

        // Jika masih kosong, buat jalur default
        if ($jalurs->isEmpty()) {
            $jalurs = collect([
                (object) ['id' => 1, 'nama_jalur' => 'Jalur Reguler', 'keterangan' => 'Jalur pendaftaran biasa'],
                (object) ['id' => 2, 'nama_jalur' => 'Jalur Prestasi', 'keterangan' => 'Untuk siswa berprestasi'],
                (object) ['id' => 3, 'nama_jalur' => 'Jalur Afirmasi', 'keterangan' => 'Untuk siswa dari keluarga kurang mampu'],
            ]);
        }

        try {
            // Upload all file berkas to public_html directory using DOCUMENT_ROOT
            $berkasKK = $this->saveFileToPublic($request->file('berkas_kk'), 'ppdb/berkas_kk');
            $berkasIjazah = $request->hasFile('berkas_ijazah') ? $this->saveFileToPublic($request->file('berkas_ijazah'), 'ppdb/berkas_ijazah') : null;

            // Buat nomor pendaftaran unik
            $nomorPendaftaran = $this->generateNomorPendaftaran($ppdbSetting);

            // Cari nama jalur berdasarkan ppdb_jalur_id
            $jalurDipilih = $jalurs->firstWhere('id', $validated['ppdb_jalur_id']);
            $namaJalur = $jalurDipilih ? $jalurDipilih->nama_jalur : 'Jalur Tidak Diketahui';

            // Upload all optional files to public_html
            $berkasAkta = $request->hasFile('berkas_akta_kelahiran') ? $this->saveFileToPublic($request->file('berkas_akta_kelahiran'), 'ppdb/berkas_akta') : null;
            $berkasKIP = $request->hasFile('berkas_kip_pkh') ? $this->saveFileToPublic($request->file('berkas_kip_pkh'), 'ppdb/berkas_kip') : null;
            $berkasKTPAyah = $request->hasFile('berkas_ktp_ayah') ? $this->saveFileToPublic($request->file('berkas_ktp_ayah'), 'ppdb/berkas_ktp_ayah') : null;
            $berkasKTPIbu = $request->hasFile('berkas_ktp_ibu') ? $this->saveFileToPublic($request->file('berkas_ktp_ibu'), 'ppdb/berkas_ktp_ibu') : null;

            // Handle multiple sertifikat prestasi files
            $berkasSertifikat = [];
            if ($request->hasFile('berkas_sertifikat_prestasi')) {
                foreach ($request->file('berkas_sertifikat_prestasi') as $file) {
                    $berkasSertifikat[] = $this->saveFileToPublic($file, 'ppdb/berkas_sertifikat');
                }
            }

            // Simpan data pendaftar - hanya field yang sudah ada di database
            // Buat array data dasar yang selalu ada
            $dataPendaftar = [
                'ppdb_setting_id' => $ppdbSetting->id,
                'ppdb_jalur_id' => $validated['ppdb_jalur_id'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'nisn' => $validated['nisn'],
                'asal_sekolah' => $validated['asal_sekolah'],
                'ppdb_nomor_whatsapp_siswa' => $validated['ppdb_nomor_whatsapp_siswa'],
                'ppdb_email_siswa' => $validated['ppdb_email_siswa'],
                'jurusan_pilihan' => $validated['jurusan_pilihan'],
                'berkas_kk' => $berkasKK,
                'berkas_ijazah' => $berkasIjazah,
                'berkas_akta_kelahiran' => $berkasAkta,
                'berkas_sertifikat_prestasi' => !empty($berkasSertifikat) ? json_encode($berkasSertifikat) : null,
                'berkas_kip_pkh' => $berkasKIP,
                'berkas_ktp_ayah' => $berkasKTPAyah,
                'berkas_ktp_ibu' => $berkasKTPIbu,
                'status' => 'pending',
                'nomor_pendaftaran' => $nomorPendaftaran,
            ];

            // Tambahkan field opsional jika kolom ada di database
            $optionalFields = [
                'nik' => $validated['nik'] ?? null,
                'agama' => $validated['agama'] ?? null,
                'provinsi' => $validated['provinsi'] ?? null,
                'kabupaten' => $validated['kabupaten'] ?? null,
                'kecamatan' => $validated['kecamatan'] ?? null,
                'desa' => $validated['desa'] ?? null,
                'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
                'nama_ayah' => $validated['nama_ayah'] ?? null,
                'nama_ibu' => $validated['nama_ibu'] ?? null,
                'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
                'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
                'nomor_hp_orangtua' => $validated['nomor_hp_orangtua'] ?? null,
                'npsn_sekolah_asal' => $validated['npsn_sekolah_asal'] ?? null,
                'tahun_lulus' => $validated['tahun_lulus'] ?? null,
                'rata_rata_nilai_raport' => $validated['rata_rata_nilai_raport'] ?? null,
                'nomor_ijazah' => $validated['nomor_ijazah'] ?? null,
                'nomor_skhun' => $validated['nomor_skhun'] ?? null,
                'rencana_lulus' => $validated['rencana_lulus'] ?? null,
            ];
            // skor_nilai akan diisi nanti oleh admin saat seleksi

            // Cek dan tambahkan field yang ada di schema database, kecuali skor_nilai
            $tableColumns = Schema::getColumnListing('ppdb_pendaftar');
            foreach ($optionalFields as $field => $value) {
                if (in_array($field, $tableColumns) && $field !== 'skor_nilai') {
                    $dataPendaftar[$field] = $value;
                }
            }

            $pendaftar = PPDBPendaftar::create($dataPendaftar);

            // Kirim email konfirmasi pendaftaran
            try {
                Mail::to($pendaftar->ppdb_email_siswa)->send(new PPDBRegistrationConfirmation($pendaftar));
            } catch (\Exception $e) {
                // Log error jika pengiriman email gagal, tapi jangan hentikan proses pendaftaran
                \Log::error('Gagal mengirim email konfirmasi PPDB: ' . $e->getMessage());
            }

            return redirect()->route('ppdb.sekolah', $slug)
                ->with('success', "✅ Pendaftaran berhasil! Nomor pendaftaran Anda: <strong>{$pendaftar->nomor_pendaftaran}</strong>");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan pendaftaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Check NISN availability via AJAX
     */
    public function checkNISN($nisn)
    {
        $exists = PPDBPendaftar::where('nisn', $nisn)->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Cek status pendaftaran berdasarkan NISN saja
     */
    public function cekStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'nisn' => 'required|string|max:20',
            ]);

            $pendaftar = PPDBPendaftar::where('nisn', $request->nisn)
                ->with(['ppdbSetting.sekolah', 'ppdbJalur'])
                ->first();

            if (!$pendaftar) {
                return view('ppdb.cek-status')->with('error', 'NISN tidak ditemukan dalam sistem.');
            }

            // Langsung tampilkan status tanpa OTP
            return view('ppdb.cek-status', compact('pendaftar'));
        }

        return view('ppdb.cek-status');
    }

    /**
     * Verify OTP code
     */
    public function verifyOTP(Request $request, $pendaftarId)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $pendaftar = PPDBPendaftar::find($pendaftarId);

        if (!$pendaftar) {
            return response()->json(['success' => false, 'message' => 'Data pendaftar tidak ditemukan']);
        }

        if ($pendaftar->verifyOTP($request->otp)) {
            return response()->json(['success' => true, 'message' => 'OTP berhasil diverifikasi']);
        }

        return response()->json(['success' => false, 'message' => 'Kode OTP tidak valid atau sudah kadaluarsa']);
    }

    /**
     * Update data pendaftar yang belum lengkap
     */
    public function updateData(Request $request, $pendaftarId)
    {
        $pendaftar = PPDBPendaftar::findOrFail($pendaftarId);

        // Validasi input berdasarkan field yang dikirim
        $rules = [];

        // Personal data validation
        if ($request->has('nik')) {
            $rules['nik'] = 'required|string|max:16|min:16|regex:/^[0-9]+$/';
        }
        if ($request->has('agama')) {
            $rules['agama'] = 'required|string|max:50';
        }
        if ($request->has('provinsi')) {
            $rules['provinsi'] = 'required|string|max:100';
        }
        if ($request->has('kabupaten')) {
            $rules['kabupaten'] = 'required|string|max:100';
        }
        if ($request->has('kecamatan')) {
            $rules['kecamatan'] = 'required|string|max:100';
        }
        if ($request->has('desa')) {
            $rules['desa'] = 'required|string|max:100';
        }
        if ($request->has('alamat_lengkap')) {
            $rules['alamat_lengkap'] = 'required|string|max:255';
        }

        // Parent data validation
        if ($request->has('nama_ayah')) {
            $rules['nama_ayah'] = 'nullable|string|max:100';
        }
        if ($request->has('nama_ibu')) {
            $rules['nama_ibu'] = 'nullable|string|max:100';
        }
        if ($request->has('pekerjaan_ayah')) {
            $rules['pekerjaan_ayah'] = 'nullable|string|max:100';
        }
        if ($request->has('pekerjaan_ibu')) {
            $rules['pekerjaan_ibu'] = 'nullable|string|max:100';
        }
        if ($request->has('nomor_hp_ayah')) {
            $rules['nomor_hp_ayah'] = 'nullable|string|max:25';
        }
        if ($request->has('nomor_hp_ibu')) {
            $rules['nomor_hp_ibu'] = 'nullable|string|max:25';
        }

        // Academic data validation
        if ($request->has('tahun_lulus')) {
            $rules['tahun_lulus'] = 'nullable|integer|min:2000|max:' . (date('Y') + 1);
        }
        if ($request->has('npsn_sekolah_asal')) {
            $rules['npsn_sekolah_asal'] = 'nullable|string|max:20';
        }
        if ($request->has('rata_rata_nilai_raport')) {
            $rules['rata_rata_nilai_raport'] = 'nullable|numeric|min:0|max:100';
        }
        if ($request->has('nomor_ijazah')) {
            $rules['nomor_ijazah'] = 'nullable|string|max:50';
        }
        if ($request->has('nomor_skhun')) {
            $rules['nomor_skhun'] = 'nullable|string|max:50';
        }

        // Semester grades validation
        for ($i = 1; $i <= 5; $i++) {
            if ($request->has("nilai_semester_{$i}")) {
                $rules["nilai_semester_{$i}"] = 'nullable|numeric|min:0|max:100';
            }
        }

        // File validation
        if ($request->hasFile('berkas_kk')) {
            $rules['berkas_kk'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }
        if ($request->hasFile('berkas_ijazah')) {
            $rules['berkas_ijazah'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);

        try {
            // Handle file uploads using DOCUMENT_ROOT for public_html compatibility
            if ($request->hasFile('berkas_kk')) {
                $validated['berkas_kk'] = $this->saveFileToPublic($request->file('berkas_kk'), 'ppdb/berkas_kk');
            }
            if ($request->hasFile('berkas_ijazah')) {
                $validated['berkas_ijazah'] = $this->saveFileToPublic($request->file('berkas_ijazah'), 'ppdb/berkas_ijazah');
            }

            // Handle additional file uploads for step 4
            if ($request->hasFile('berkas_akta_kelahiran')) {
                $validated['berkas_akta_kelahiran'] = $this->saveFileToPublic($request->file('berkas_akta_kelahiran'), 'ppdb/berkas_akta');
            }
            if ($request->hasFile('berkas_sertifikat_prestasi')) {
                $berkasSertifikat = [];
                foreach ($request->file('berkas_sertifikat_prestasi') as $file) {
                    $berkasSertifikat[] = $this->saveFileToPublic($file, 'ppdb/berkas_sertifikat');
                }
                $validated['berkas_sertifikat_prestasi'] = json_encode($berkasSertifikat);
            }
            if ($request->hasFile('berkas_kip_pkh')) {
                $validated['berkas_kip_pkh'] = $this->saveFileToPublic($request->file('berkas_kip_pkh'), 'ppdb/berkas_kip');
            }
            if ($request->hasFile('berkas_ktp_ayah')) {
                $validated['berkas_ktp_ayah'] = $this->saveFileToPublic($request->file('berkas_ktp_ayah'), 'ppdb/berkas_ktp_ayah');
            }
            if ($request->hasFile('berkas_ktp_ibu')) {
                $validated['berkas_ktp_ibu'] = $this->saveFileToPublic($request->file('berkas_ktp_ibu'), 'ppdb/berkas_ktp_ibu');
            }

            // Calculate average if semester grades are provided
            if ($request->hasAny(['nilai_semester_1', 'nilai_semester_2', 'nilai_semester_3', 'nilai_semester_4', 'nilai_semester_5'])) {
                $semesterGrades = [];
                for ($i = 1; $i <= 5; $i++) {
                    $grade = $request->input("nilai_semester_{$i}");
                    if ($grade !== null && $grade !== '') {
                        $semesterGrades[] = (float) $grade;
                        $validated["nilai_semester_{$i}"] = $grade;
                    }
                }

                if (!empty($semesterGrades)) {
                    $validated['rata_rata_nilai_raport'] = array_sum($semesterGrades) / count($semesterGrades);
                }
            }

            // Update pendaftar data
            $pendaftar->update($validated);

            return redirect()->back()
                ->with('success', 'Data pendaftaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Save file to public directory using DOCUMENT_ROOT for production compatibility
     */
    private function saveFileToPublic($file, $directory)
    {
        // Path to public_html/storage using DOCUMENT_ROOT for production compatibility
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $directory;

        // Ensure directory exists
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($fullPath, $filename);

        return $directory . '/' . $filename;
    }

    /**
     * Generate nomor pendaftaran unik
     * Format: SMKM-2025-0001
     */
    private function generateNomorPendaftaran($ppdbSetting)
    {
        $year = now()->year;
        // Ambil 4 huruf pertama dari slug, uppercase
        $schoolCode = strtoupper(substr($ppdbSetting->slug, 0, 4));

        // Hitung jumlah pendaftar tahun ini untuk sekolah ini
        $count = PPDBPendaftar::where('ppdb_setting_id', $ppdbSetting->id)
            ->whereYear('created_at', $year)
            ->count() + 1;

        return "{$schoolCode}-{$year}-" . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
