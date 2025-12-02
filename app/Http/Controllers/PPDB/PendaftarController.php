<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PPDBSetting;
use App\Models\PPDBPendaftar;
use App\Models\PPDBJalur;
use App\Models\Madrasah;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        // Ambil jalur dari ppdb_jalur di ppdb_setting
        $jalurs = collect();
        if ($ppdbSetting && $ppdbSetting->ppdb_jalur) {
            foreach ($ppdbSetting->ppdb_jalur as $index => $jalur) {
                $jalurs->push((object) [
                    'id' => $index + 1,
                    'nama_jalur' => $jalur['nama_jalur'] ?? $jalur,
                    'keterangan' => $jalur['keterangan'] ?? '',
                ]);
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
            'nisn' => 'required|string|max:20|unique:ppdb_pendaftars,nisn',
            'asal_sekolah' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kabupaten' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'desa' => 'required|string|max:100',
            'alamat_lengkap' => 'required|string|max:255',
            'ppdb_jalur_id' => 'required|integer|min:1|max:3',
            'jurusan_pilihan' => 'required|string|max:50',
            'ppdb_nomor_whatsapp_siswa' => 'required|string|max:25',
            'ppdb_email_siswa' => 'required|email|max:100',
            // Optional second-option flag and conditional fields
            'use_opsi_ke_2' => 'nullable|in:0,1',
            'ppdb_opsi_pilihan_ke_2' => 'required_if:use_opsi_ke_2,1|nullable|integer|exists:madrasahs,id',
            'ppdb_jurusan_pilihan_alt' => 'required_if:use_opsi_ke_2,1|nullable|array',
            'ppdb_jurusan_pilihan_alt.*' => 'required_if:use_opsi_ke_2,1|nullable|string',
            'berkas_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            // Optional fields for step 4
            'nama_ayah' => 'nullable|string|max:100',
            'nama_ibu' => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'nomor_hp_orangtua' => 'nullable|string|max:25',
            'npsn_sekolah_asal' => 'nullable|string|max:20',
            'tahun_lulus' => 'nullable|integer|min:2000|max:' . (date('Y') + 1),
            'nilai_akhir_raport' => 'nullable|numeric|min:0|max:100',
            'rata_rata_nilai_raport' => 'nullable|numeric|min:0|max:100',
            'nomor_ijazah' => 'nullable|string|max:50',
            'nomor_skhun' => 'nullable|string|max:50',
            'rencana_lulus' => 'required|in:kuliah,kerja',
            // Optional file uploads
            'berkas_akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'berkas_raport' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
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

        // Ambil jalur dari ppdb_jalur di ppdb_setting
        $jalurs = collect();
        if ($ppdbSetting && $ppdbSetting->ppdb_jalur) {
            foreach ($ppdbSetting->ppdb_jalur as $index => $jalur) {
                $jalurs->push((object) [
                    'id' => $index + 1,
                    'nama_jalur' => $jalur['nama_jalur'] ?? $jalur,
                    'keterangan' => $jalur['keterangan'] ?? '',
                ]);
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
            // Upload file berkas
            $berkasKK = $request->file('berkas_kk')->store('ppdb/berkas_kk', 'public');
            $berkasIjazah = $request->file('berkas_ijazah')->store('ppdb/berkas_ijazah', 'public');

            // Buat nomor pendaftaran unik
            $nomorPendaftaran = $this->generateNomorPendaftaran($ppdbSetting);

            // Cari nama jalur berdasarkan ppdb_jalur_id
            $jalurDipilih = $jalurs->firstWhere('id', $validated['ppdb_jalur_id']);
            $namaJalur = $jalurDipilih ? $jalurDipilih->nama_jalur : 'Jalur Tidak Diketahui';

            // Upload optional files
            $berkasAkta = $request->hasFile('berkas_akta_kelahiran') ? $request->file('berkas_akta_kelahiran')->store('ppdb/berkas_akta', 'public') : null;
            $berkasRaport = $request->hasFile('berkas_raport') ? $request->file('berkas_raport')->store('ppdb/berkas_raport', 'public') : null;
            $berkasKIP = $request->hasFile('berkas_kip_pkh') ? $request->file('berkas_kip_pkh')->store('ppdb/berkas_kip', 'public') : null;
            $berkasKTPAyah = $request->hasFile('berkas_ktp_ayah') ? $request->file('berkas_ktp_ayah')->store('ppdb/berkas_ktp_ayah', 'public') : null;
            $berkasKTPIbu = $request->hasFile('berkas_ktp_ibu') ? $request->file('berkas_ktp_ibu')->store('ppdb/berkas_ktp_ibu', 'public') : null;

            // Handle multiple sertifikat prestasi files
            $berkasSertifikat = [];
            if ($request->hasFile('berkas_sertifikat_prestasi')) {
                foreach ($request->file('berkas_sertifikat_prestasi') as $file) {
                    $berkasSertifikat[] = $file->store('ppdb/berkas_sertifikat', 'public');
                }
            }

            // Simpan data pendaftar dengan semua field baru
            $pendaftar = PPDBPendaftar::create([
                'ppdb_setting_id' => $ppdbSetting->id,
                'ppdb_jalur_id' => $validated['ppdb_jalur_id'],
                'jalur' => $namaJalur,
                'nama_lengkap' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'agama' => $validated['agama'],
                'nisn' => $validated['nisn'],
                'asal_sekolah' => $validated['asal_sekolah'],
                'npsn_sekolah_asal' => $validated['npsn_sekolah_asal'] ?? null,
                'tahun_lulus' => $validated['tahun_lulus'] ?? null,
                'nama_ayah' => $validated['nama_ayah'] ?? null,
                'nama_ibu' => $validated['nama_ibu'] ?? null,
                'pekerjaan_ayah' => $validated['pekerjaan_ayah'] ?? null,
                'pekerjaan_ibu' => $validated['pekerjaan_ibu'] ?? null,
                'nomor_hp_ayah' => null, // Not in form
                'nomor_hp_ibu' => null, // Not in form
                'alamat_lengkap' => $validated['alamat_lengkap'],
                'status_keluarga' => null, // Not in form
                'ppdb_nomor_whatsapp_siswa' => $validated['ppdb_nomor_whatsapp_siswa'],
                'ppdb_nomor_whatsapp_wali' => null, // Not in form
                'ppdb_email_siswa' => $validated['ppdb_email_siswa'],
                'jurusan_pilihan' => $validated['jurusan_pilihan'],
                'rencana_lulus' => $validated['rencana_lulus'],
                'berkas_kk' => $berkasKK,
                'berkas_ijazah' => $berkasIjazah,
                'berkas_akta_kelahiran' => $berkasAkta,
                'berkas_ktp_ayah' => $berkasKTPAyah,
                'berkas_ktp_ibu' => $berkasKTPIbu,
                'berkas_raport' => $berkasRaport,
                'berkas_sertifikat_prestasi' => json_encode($berkasSertifikat),
                'berkas_kip_pkh' => $berkasKIP,
                'berkas_bukti_domisili' => null, // Not in form
                'berkas_surat_mutasi' => null, // Not in form
                'berkas_surat_keterangan_lulus' => null, // Not in form
                'berkas_skl' => null, // Not in form
                'status' => 'pending',
                'nomor_pendaftaran' => $nomorPendaftaran,
                'nilai' => null,
                'nilai_akhir_raport' => $validated['nilai_akhir_raport'] ?? null,
                'rata_rata_nilai_raport' => $validated['rata_rata_nilai_raport'] ?? null,
                'nomor_ijazah' => $validated['nomor_ijazah'] ?? null,
                'nomor_skhun' => $validated['nomor_skhun'] ?? null,
                'nomor_peserta_un' => null,
                'ranking' => null,
                'skor_nilai' => null,
                'skor_prestasi' => null,
                'skor_domisili' => null,
                'skor_dokumen' => null,
                'skor_total' => null,
                'is_inden' => false,
                'surat_keterangan_sementara' => null,
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_verified_at' => null,
                'catatan_verifikasi' => null,
                'diverifikasi_oleh' => null,
                'diverifikasi_tanggal' => null,
                'diseleksi_oleh' => null,
                'diseleksi_tanggal' => null,
            ]);

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
     * Cek status pendaftaran berdasarkan NISN dan kirim OTP
     */
    public function cekStatus(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'nisn' => 'required|string|max:20',
            ]);

            $pendaftar = PPDBPendaftar::where('nisn', $request->nisn)
                ->with(['ppdbSetting.sekolah'])
                ->first();

            if (!$pendaftar) {
                return view('ppdb.cek-status')->with('error', 'NISN tidak ditemukan dalam sistem.');
            }

            // Generate OTP
            $otp = $pendaftar->generateOTP();

            // Send OTP via email (you can implement email sending here)
            // For now, we'll just store it in session for testing
            session(['pendaftar_id' => $pendaftar->id]);

            // TODO: Send email with OTP
            // Mail::to($pendaftar->ppdb_email_siswa)->send(new OTPNotification($otp));

            return view('ppdb.cek-status')->with('otp_sent', true);
        }

        // Check if we have a verified pendaftar from OTP
        if ($request->has('pendaftar_id')) {
            $pendaftar = PPDBPendaftar::where('id', $request->pendaftar_id)
                ->whereNotNull('otp_verified_at')
                ->with(['ppdbSetting.sekolah'])
                ->first();

            if ($pendaftar) {
                return view('ppdb.cek-status', compact('pendaftar'));
            }
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
