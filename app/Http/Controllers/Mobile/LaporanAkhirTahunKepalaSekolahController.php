<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\LaporanAkhirTahunKepalaSekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanAkhirTahunKepalaSekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporans = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->orderBy('tahun_pelaporan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mobile.laporan-akhir-tahun.index', compact('laporans'));
    }

    /**
     * Show the form for creating a new resource or editing existing one.
     */
    public function create($id = null)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = null;
        $isEditing = false;

        // If ID is provided, we're editing an existing report
        if ($id) {
            $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
                ->findOrFail($id);
            $isEditing = true;
        } else {
            // Check if user already has a published report for current year
            $currentYear = Carbon::now()->year;
            $existingPublishedReport = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
                ->where('tahun_pelaporan', $currentYear)
                ->where('status', 'published')
                ->first();

            if ($existingPublishedReport) {
                return redirect()->route('mobile.laporan-akhir-tahun.create', $existingPublishedReport->id)
                    ->with('info', 'Anda sudah memiliki laporan untuk tahun ' . $currentYear . '. Silakan edit laporan tersebut.');
            }

            // Load existing draft if exists
            $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
                ->where('status', 'draft')
                ->first();

            if ($laporan) {
                $isEditing = true;
            }
        }

        // Hitung jumlah guru berdasarkan status kepegawaian
        $madrasah = $user->madrasah;
        if ($madrasah) {
            $guruStats = [
                'pns_sertifikasi' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 1)->count(),
                'pns_non_sertifikasi' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 2)->count(),
                'gty_sertifikasi' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 3)->count(),
                'gty_sertifikasi_inpassing' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 4)->count(),
                'gty_non_sertifikasi' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 5)->count(),
                'gtt' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 6)->count(),
                'pty' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 7)->count(),
                'ptt' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 8)->count(),
            ];
        } else {
            // If user has no madrasah linked, default counts to 0 to avoid calling methods on null
            $guruStats = [
                'pns_sertifikasi' => 0,
                'pns_non_sertifikasi' => 0,
                'gty_sertifikasi' => 0,
                'gty_sertifikasi_inpassing' => 0,
                'gty_non_sertifikasi' => 0,
                'gtt' => 0,
                'pty' => 0,
                'ptt' => 0,
            ];
        }

        // Get users with the same madrasah_id for talenta selection
        $guruKaryawan = \App\Models\User::where('madrasah_id', $user->madrasah_id)
            ->where('role', 'tenaga_pendidik')
            ->get(['id', 'name'])
            ->toArray();

        // Pre-fill data from user and madrasah or existing report
        $data = [
            'nama_satpen' => $laporan ? $laporan->nama_satpen : ($user->madrasah->name ?? ''),
            'alamat' => $laporan ? $laporan->alamat : '',
            'nama_kepala_sekolah_madrasah' => $laporan ? $laporan->nama_kepala_sekolah_madrasah : $user->name,
            'gelar' => $laporan ? $laporan->gelar : ($user->gelar ?? ''),
            'tmt_ks_kamad_pertama' => $laporan ? $laporan->tmt_ks_kamad_pertama : '',
            'tmt_ks_kamad_terakhir' => $laporan ? $laporan->tmt_ks_kamad_terakhir : '',
            'tahun_pelaporan' => $laporan ? $laporan->tahun_pelaporan : Carbon::now()->year,
            'nama_kepala_sekolah' => $laporan ? $laporan->nama_kepala_sekolah : $user->name,
            // Step 2: Capaian Utama 3 Tahun Berjalan
            'jumlah_siswa_2023' => $laporan ? $laporan->jumlah_siswa_2023 : 0,
            'jumlah_siswa_2024' => $laporan ? $laporan->jumlah_siswa_2024 : 0,
            'jumlah_siswa_2025' => $laporan ? $laporan->jumlah_siswa_2025 : 0,
            'persentase_alumni_bekerja' => $laporan ? $laporan->persentase_alumni_bekerja . '%' : '',
            'persentase_alumni_wirausaha' => $laporan ? $laporan->persentase_alumni_wirausaha . '%' : '',
            'persentase_alumni_tidak_terdeteksi' => $laporan ? $laporan->persentase_alumni_tidak_terdeteksi . '%' : '',
            'bosnas_2023' => $laporan ? $laporan->bosnas_2023 : '',
            'bosnas_2024' => $laporan ? $laporan->bosnas_2024 : '',
            'bosnas_2025' => $laporan ? $laporan->bosnas_2025 : '',
            'bosda_2023' => $laporan ? $laporan->bosda_2023 : '',
            'bosda_2024' => $laporan ? $laporan->bosda_2024 : '',
            'bosda_2025' => $laporan ? $laporan->bosda_2025 : '',
            'spp_bppp_lain_2023' => $laporan ? $laporan->spp_bppp_lain_2023 : '',
            'spp_bppp_lain_2024' => $laporan ? $laporan->spp_bppp_lain_2024 : '',
            'spp_bppp_lain_2025' => $laporan ? $laporan->spp_bppp_lain_2025 : '',
            'pendapatan_unit_usaha_2023' => $laporan ? $laporan->pendapatan_unit_usaha_2023 : '',
            'pendapatan_unit_usaha_2024' => $laporan ? $laporan->pendapatan_unit_usaha_2024 : '',
            'pendapatan_unit_usaha_2025' => $laporan ? $laporan->pendapatan_unit_usaha_2025 : '',
            'status_akreditasi' => $laporan ? $laporan->status_akreditasi : '',
            'tanggal_akreditasi_mulai' => $laporan ? $laporan->tanggal_akreditasi_mulai : '',
            'tanggal_akreditasi_berakhir' => $laporan ? $laporan->tanggal_akreditasi_berakhir : '',
            // Step 3: Layanan Pendidikan
            'model_layanan_pendidikan' => $laporan ? $laporan->model_layanan_pendidikan : '',
            'capaian_layanan_menonjol' => $laporan ? $laporan->capaian_layanan_menonjol : '',
            'masalah_layanan_utama' => $laporan ? $laporan->masalah_layanan_utama : '',
            // Step 4: SDM
            'pns_sertifikasi' => $laporan ? $laporan->pns_sertifikasi : $guruStats['pns_sertifikasi'],
            'pns_non_sertifikasi' => $laporan ? $laporan->pns_non_sertifikasi : $guruStats['pns_non_sertifikasi'],
            'gty_sertifikasi_inpassing' => $laporan ? $laporan->gty_sertifikasi_inpassing : $guruStats['gty_sertifikasi_inpassing'],
            'gty_sertifikasi' => $laporan ? $laporan->gty_sertifikasi : $guruStats['gty_sertifikasi'],
            'gty_non_sertifikasi' => $laporan ? $laporan->gty_non_sertifikasi : $guruStats['gty_non_sertifikasi'],
            'gtt' => $laporan ? $laporan->gtt : $guruStats['gtt'],
            'pty' => $laporan ? $laporan->pty : $guruStats['pty'],
            'ptt' => $laporan ? $laporan->ptt : $guruStats['ptt'],
            'jumlah_talenta' => $laporan ? $laporan->jumlah_talenta : 3,
            'nama_talenta' => $laporan ? json_decode($laporan->nama_talenta, true) : [],
            'alasan_talenta' => $laporan ? json_decode($laporan->alasan_talenta, true) : [],
            'kondisi_guru' => $laporan ? json_decode($laporan->kondisi_guru, true) : [],
            'masalah_sdm_utama' => $laporan ? json_decode($laporan->masalah_sdm_utama, true) : [],
            // Step 5: Keuangan
            'sumber_dana_utama' => $laporan ? $laporan->sumber_dana_utama : '',
            'kondisi_keuangan_akhir_tahun' => $laporan ? $laporan->kondisi_keuangan_akhir_tahun : '',
            'catatan_pengelolaan_keuangan' => $laporan ? $laporan->catatan_pengelolaan_keuangan : '',
            // Step 6: PPDB
            'metode_ppdb' => $laporan ? $laporan->metode_ppdb : '',
            'hasil_ppdb_tahun_berjalan' => $laporan ? $laporan->hasil_ppdb_tahun_berjalan : '',
            'masalah_utama_ppdb' => $laporan ? $laporan->masalah_utama_ppdb : '',
            // Step 7: Unggulan
            'nama_program_unggulan' => $laporan ? $laporan->nama_program_unggulan : '',
            'alasan_pemilihan_program' => $laporan ? $laporan->alasan_pemilihan_program : '',
            'target_unggulan' => $laporan ? $laporan->target_unggulan : '',
            'kontribusi_unggulan' => $laporan ? $laporan->kontribusi_unggulan : '',
            'sumber_biaya_program' => $laporan ? $laporan->sumber_biaya_program : '',
            'tim_program_unggulan' => $laporan ? $laporan->tim_program_unggulan : '',
            // Step 8: Refleksi
            'keberhasilan_terbesar_tahun_ini' => $laporan ? $laporan->keberhasilan_terbesar_tahun_ini : '',
            'masalah_paling_berat_dihadapi' => $laporan ? $laporan->masalah_paling_berat_dihadapi : '',
            'keputusan_sulit_diambil' => $laporan ? $laporan->keputusan_sulit_diambil : '',
            // Step 9: Risiko
            'risiko_terbesar_satpen_tahun_depan' => $laporan ? $laporan->risiko_terbesar_satpen_tahun_depan : '',
            'fokus_perbaikan_tahun_depan' => $laporan ? json_decode($laporan->fokus_perbaikan_tahun_depan, true) : [],
            // Step 10: Pernyataan
            'pernyataan_benar' => $laporan ? $laporan->pernyataan_benar : false,
            'signature_data' => $laporan ? $laporan->signature_data : '',
            'guru_karyawan' => $guruKaryawan,
            'jumlah_guru' => $user->madrasah->jumlah_guru ?? 0,
            'jumlah_siswa' => $user->madrasah->jumlah_siswa ?? 0,
            'jumlah_kelas' => $user->madrasah->jumlah_kelas ?? 0,
        ];

        return view('mobile.laporan-akhir-tahun.create', compact('data', 'laporan', 'isEditing'));
    }




    /**
     * Auto-save draft
     */
    public function autoSaveDraft(Request $request)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Check if user already has a draft
            $existingDraft = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
                ->where('status', 'draft')
                ->first();

            // Handle file uploads
            $filePaths = [];
            for ($i = 1; $i <= 9; $i++) {
                $fileKey = 'lampiran_step_' . $i;
                if ($request->hasFile($fileKey)) {
                    $file = $request->file($fileKey);
                    $fileName = time() . '_' . $user->id . '_' . $fileKey . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/laporan-akhir-tahun'), $fileName);
                    $filePaths[$fileKey] = 'uploads/laporan-akhir-tahun/' . $fileName;
                }
            }

            $data = [
                'user_id' => $user->id,
                'status' => 'draft',
                'nama_satpen' => $request->nama_satpen,
                'alamat' => $request->alamat,
                'nama_kepala_sekolah_madrasah' => $request->nama_kepala_sekolah_madrasah,
                'gelar' => $request->gelar,
                'tmt_ks_kamad_pertama' => $request->tmt_ks_kamad_pertama,
                'tmt_ks_kamad_terakhir' => $request->tmt_ks_kamad_terakhir,
                'tahun_pelaporan' => $request->tahun_pelaporan,
                'nama_kepala_sekolah' => $request->nama_kepala_sekolah,
                // Step 2: Capaian Utama 3 Tahun Berjalan
                'jumlah_siswa_2023' => $request->jumlah_siswa_2023,
                'jumlah_siswa_2024' => $request->jumlah_siswa_2024,
                'jumlah_siswa_2025' => $request->jumlah_siswa_2025,
                'persentase_alumni_bekerja' => str_replace('%', '', $request->persentase_alumni_bekerja),
                'persentase_alumni_wirausaha' => str_replace('%', '', $request->persentase_alumni_wirausaha),
                'persentase_alumni_tidak_terdeteksi' => str_replace('%', '', $request->persentase_alumni_tidak_terdeteksi),
                'bosnas_2023' => $request->bosnas_2023,
                'bosnas_2024' => $request->bosnas_2024,
                'bosnas_2025' => $request->bosnas_2025,
                'bosda_2023' => $request->bosda_2023,
                'bosda_2024' => $request->bosda_2024,
                'bosda_2025' => $request->bosda_2025,
                'spp_bppp_lain_2023' => $request->spp_bppp_lain_2023,
                'spp_bppp_lain_2024' => $request->spp_bppp_lain_2024,
                'spp_bppp_lain_2025' => $request->spp_bppp_lain_2025,
                'pendapatan_unit_usaha_2023' => $request->pendapatan_unit_usaha_2023,
                'pendapatan_unit_usaha_2024' => $request->pendapatan_unit_usaha_2024,
                'pendapatan_unit_usaha_2025' => $request->pendapatan_unit_usaha_2025,
                'status_akreditasi' => $request->status_akreditasi,
                'tanggal_akreditasi_mulai' => $request->tanggal_akreditasi_mulai,
                'tanggal_akreditasi_berakhir' => $request->tanggal_akreditasi_berakhir,
                // Step 3: Layanan Pendidikan
                'model_layanan_pendidikan' => $request->model_layanan_pendidikan,
                'capaian_layanan_menonjol' => $request->capaian_layanan_menonjol,
                'masalah_layanan_utama' => $request->masalah_layanan_utama,
                // Step 4: SDM
                'pns_sertifikasi' => $request->pns_sertifikasi,
                'pns_non_sertifikasi' => $request->pns_non_sertifikasi,
                'gty_sertifikasi_inpassing' => $request->gty_sertifikasi_inpassing,
                'gty_sertifikasi' => $request->gty_sertifikasi,
                'gty_non_sertifikasi' => $request->gty_non_sertifikasi,
                'gtt' => $request->gtt,
                'pty' => $request->pty,
                'ptt' => $request->ptt,
                'jumlah_talenta' => $request->jumlah_talenta,
                'nama_talenta' => json_encode($request->nama_talenta),
                'alasan_talenta' => json_encode($request->alasan_talenta),
                'kondisi_guru' => json_encode($request->kondisi_guru),
                'masalah_sdm_utama' => json_encode($request->masalah_sdm_utama),
                // Step 5: Keuangan
                'sumber_dana_utama' => $request->sumber_dana_utama,
                'kondisi_keuangan_akhir_tahun' => $request->kondisi_keuangan_akhir_tahun,
                'catatan_pengelolaan_keuangan' => $request->catatan_pengelolaan_keuangan,
                // Step 6: PPDB
                'metode_ppdb' => $request->metode_ppdb,
                'hasil_ppdb_tahun_berjalan' => $request->hasil_ppdb_tahun_berjalan,
                'masalah_utama_ppdb' => $request->masalah_utama_ppdb,
                // Step 7: Unggulan
                'nama_program_unggulan' => $request->nama_program_unggulan,
                'alasan_pemilihan_program' => $request->alasan_pemilihan_program,
                'target_unggulan' => $request->target_unggulan,
                'kontribusi_unggulan' => $request->kontribusi_unggulan,
                'sumber_biaya_program' => $request->sumber_biaya_program,
                'tim_program_unggulan' => $request->tim_program_unggulan,
                // Step 8: Refleksi
                'keberhasilan_terbesar_tahun_ini' => $request->keberhasilan_terbesar_tahun_ini,
                'masalah_paling_berat_dihadapi' => $request->masalah_paling_berat_dihadapi,
                'keputusan_sulit_diambil' => $request->keputusan_sulit_diambil,
                // Step 9: Risiko
                'risiko_terbesar_satpen_tahun_depan' => $request->risiko_terbesar_satpen_tahun_depan,
                'fokus_perbaikan_tahun_depan' => json_encode($request->fokus_perbaikan_tahun_depan),
                // Step 10: Pernyataan
                'pernyataan_benar' => $request->pernyataan_benar,
                'signature_data' => $request->signature_data,
                // File attachments
                'lampiran_step_1' => $filePaths['lampiran_step_1'] ?? $existingDraft->lampiran_step_1 ?? null,
                'lampiran_step_2' => $filePaths['lampiran_step_2'] ?? $existingDraft->lampiran_step_2 ?? null,
                'lampiran_step_3' => $filePaths['lampiran_step_3'] ?? $existingDraft->lampiran_step_3 ?? null,
                'lampiran_step_4' => $filePaths['lampiran_step_4'] ?? $existingDraft->lampiran_step_4 ?? null,
                'lampiran_step_5' => $filePaths['lampiran_step_5'] ?? $existingDraft->lampiran_step_5 ?? null,
                'lampiran_step_6' => $filePaths['lampiran_step_6'] ?? $existingDraft->lampiran_step_6 ?? null,
                'lampiran_step_7' => $filePaths['lampiran_step_7'] ?? $existingDraft->lampiran_step_7 ?? null,
                'lampiran_step_8' => $filePaths['lampiran_step_8'] ?? $existingDraft->lampiran_step_8 ?? null,
                'lampiran_step_9' => $filePaths['lampiran_step_9'] ?? $existingDraft->lampiran_step_9 ?? null,
            ];

            if ($existingDraft) {
                $existingDraft->update($data);
                $laporan = $existingDraft;
            } else {
                $laporan = LaporanAkhirTahunKepalaSekolah::create($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'Draft saved successfully',
                'laporan_id' => $laporan->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $isDraft = $request->input('status') === 'draft';

        $request->validate([
            'nama_satpen' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'alamat' => $isDraft ? 'nullable|string' : 'required|string',
            'nama_kepala_sekolah_madrasah' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'gelar' => 'nullable|string|max:255',
            'tmt_ks_kamad_pertama' => $isDraft ? 'nullable|date' : 'required|date',
            'tmt_ks_kamad_terakhir' => $isDraft ? 'nullable|date' : 'required|date',
            'tahun_pelaporan' => $isDraft ? 'nullable|integer|min:2020|max:' . (Carbon::now()->year + 1) : 'required|integer|min:2020|max:' . (Carbon::now()->year + 1),
            'nama_kepala_sekolah' => $isDraft ? 'nullable|string|max:255' : 'required|string|max:255',
            'lampiran_step_1' => $isDraft ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
            'lampiran_step_2' => $isDraft ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
            'lampiran_step_3' => $isDraft ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
            'lampiran_step_4' => $isDraft ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
            'lampiran_step_5' => $isDraft ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
            'lampiran_step_6' => $isDraft ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
            'lampiran_step_7' => 'nullable|file|mimes:pdf|max:10240',
            'lampiran_step_8' => $isDraft ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
            'lampiran_step_9' => $isDraft ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240',
            // Step 2 validations
            'jumlah_siswa_2023' => 'required|integer|min:0',
            'jumlah_siswa_2024' => 'required|integer|min:0',
            'jumlah_siswa_2025' => 'required|integer|min:0',
            'persentase_alumni_bekerja' => 'required|string',
            'persentase_alumni_wirausaha' => 'required|string',
            'persentase_alumni_tidak_terdeteksi' => 'required|string',
            'bosnas_2023' => 'required|string',
            'bosnas_2024' => 'required|string',
            'bosnas_2025' => 'required|string',
            'bosda_2023' => 'required|string',
            'bosda_2024' => 'required|string',
            'bosda_2025' => 'required|string',
            'spp_bppp_lain_2023' => 'required|string',
            'spp_bppp_lain_2024' => 'required|string',
            'spp_bppp_lain_2025' => 'required|string',
            'pendapatan_unit_usaha_2023' => 'required|string',
            'pendapatan_unit_usaha_2024' => 'required|string',
            'pendapatan_unit_usaha_2025' => 'required|string',
            'status_akreditasi' => 'required|string',
            'tanggal_akreditasi_mulai' => 'required|date',
            'tanggal_akreditasi_berakhir' => 'required|date',
            // Step 3 validations
            'model_layanan_pendidikan' => 'required|string',
            'capaian_layanan_menonjol' => 'required|string',
            'masalah_layanan_utama' => 'required|string',
            // Step 4 validations
            'pns_sertifikasi' => 'required|integer|min:0',
            'pns_non_sertifikasi' => 'required|integer|min:0',
            'gty_sertifikasi_inpassing' => 'required|integer|min:0',
            'gty_sertifikasi' => 'required|integer|min:0',
            'gty_non_sertifikasi' => 'required|integer|min:0',
            'gtt' => 'required|integer|min:0',
            'pty' => 'required|integer|min:0',
            'ptt' => 'required|integer|min:0',
            'jumlah_talenta' => $isDraft ? 'nullable|integer|min:3|max:9' : 'required|integer|min:3|max:9',
            'nama_talenta' => $isDraft ? 'nullable|array|min:3|max:9' : 'required|array|min:3|max:9',
            'nama_talenta.*' => $isDraft ? 'nullable|string' : 'required|string',
            'alasan_talenta' => $isDraft ? 'nullable|array|min:3|max:9' : 'required|array|min:3|max:9',
            'alasan_talenta.*' => $isDraft ? 'nullable|string' : 'required|string',
            'kondisi_guru' => 'required|array',
            'kondisi_guru.*' => 'required|string|in:baik,cukup,bermasalah',
            'masalah_sdm_utama' => 'required|array|min:3',
            'masalah_sdm_utama.*' => 'required|string',
            // Step 5 validations
            'sumber_dana_utama' => 'required|string',
            'kondisi_keuangan_akhir_tahun' => 'required|string|in:sehat,cukup,risiko,kritis',
            'catatan_pengelolaan_keuangan' => 'required|string',
            // Step 6 validations
            'metode_ppdb' => 'required|string',
            'hasil_ppdb_tahun_berjalan' => 'required|string',
            'masalah_utama_ppdb' => 'required|string',
            // Step 7 validations
            'nama_program_unggulan' => 'required|string',
            'alasan_pemilihan_program' => 'required|string',
            'target_unggulan' => 'required|string',
            'kontribusi_unggulan' => 'required|string',
            'sumber_biaya_program' => 'required|string',
            'tim_program_unggulan' => 'required|string',
            // Step 8 validations
            'keberhasilan_terbesar_tahun_ini' => 'required|string',
            'masalah_paling_berat_dihadapi' => 'required|string',
            'keputusan_sulit_diambil' => 'required|string',
            // Step 9 validations
            'risiko_terbesar_satpen_tahun_depan' => 'required|string',
            'fokus_perbaikan_tahun_depan' => 'required|array|min:1',
            'fokus_perbaikan_tahun_depan.*' => 'required|string',
            // Step 10 validations
            'pernyataan_benar' => 'required|boolean',
            'signature_data' => 'required|string',
        ]);

        // Check if report already exists for this year
        $existingReport = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->where('tahun_pelaporan', $request->tahun_pelaporan)
            ->first();

        if ($existingReport) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['tahun_pelaporan' => 'Laporan untuk tahun ini sudah ada.']);
        }

        // Handle file uploads
        $filePaths = [];
        for ($i = 1; $i <= 9; $i++) {
            $fileKey = 'lampiran_step_' . $i;
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $fileName = time() . '_' . $user->id . '_' . $fileKey . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/laporan-akhir-tahun'), $fileName);
                $filePaths[$fileKey] = 'uploads/laporan-akhir-tahun/' . $fileName;
            }
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::create([
            'user_id' => $user->id,
            'status' => $request->input('status', 'draft'),
            'nama_satpen' => $request->nama_satpen,
            'alamat' => $request->alamat,
            'nama_kepala_sekolah_madrasah' => $request->nama_kepala_sekolah_madrasah,
            'gelar' => $request->gelar,
            'tmt_ks_kamad_pertama' => $request->tmt_ks_kamad_pertama,
            'tmt_ks_kamad_terakhir' => $request->tmt_ks_kamad_terakhir,
            'tahun_pelaporan' => $request->tahun_pelaporan,
            'nama_kepala_sekolah' => $request->nama_kepala_sekolah,
            // Step 2: Capaian Utama 3 Tahun Berjalan
            'jumlah_siswa_2023' => $request->jumlah_siswa_2023,
            'jumlah_siswa_2024' => $request->jumlah_siswa_2024,
            'jumlah_siswa_2025' => $request->jumlah_siswa_2025,
            'persentase_alumni_bekerja' => str_replace('%', '', $request->persentase_alumni_bekerja),
            'persentase_alumni_wirausaha' => str_replace('%', '', $request->persentase_alumni_wirausaha),
            'persentase_alumni_tidak_terdeteksi' => str_replace('%', '', $request->persentase_alumni_tidak_terdeteksi),
            'bosnas_2023' => $request->bosnas_2023,
            'bosnas_2024' => $request->bosnas_2024,
            'bosnas_2025' => $request->bosnas_2025,
            'bosda_2023' => $request->bosda_2023,
            'bosda_2024' => $request->bosda_2024,
            'bosda_2025' => $request->bosda_2025,
            'spp_bppp_lain_2023' => $request->spp_bppp_lain_2023,
            'spp_bppp_lain_2024' => $request->spp_bppp_lain_2024,
            'spp_bppp_lain_2025' => $request->spp_bppp_lain_2025,
            'pendapatan_unit_usaha_2023' => $request->pendapatan_unit_usaha_2023,
            'pendapatan_unit_usaha_2024' => $request->pendapatan_unit_usaha_2024,
            'pendapatan_unit_usaha_2025' => $request->pendapatan_unit_usaha_2025,
            'status_akreditasi' => $request->status_akreditasi,
            'tanggal_akreditasi_mulai' => $request->tanggal_akreditasi_mulai,
            'tanggal_akreditasi_berakhir' => $request->tanggal_akreditasi_berakhir,
            // Step 3: Layanan Pendidikan
            'model_layanan_pendidikan' => $request->model_layanan_pendidikan,
            'capaian_layanan_menonjol' => $request->capaian_layanan_menonjol,
            'masalah_layanan_utama' => $request->masalah_layanan_utama,
            // Step 4: SDM
            'pns_sertifikasi' => $request->pns_sertifikasi,
            'pns_non_sertifikasi' => $request->pns_non_sertifikasi,
            'gty_sertifikasi_inpassing' => $request->gty_sertifikasi_inpassing,
            'gty_sertifikasi' => $request->gty_sertifikasi,
            'gty_non_sertifikasi' => $request->gty_non_sertifikasi,
            'gtt' => $request->gtt,
            'pty' => $request->pty,
            'ptt' => $request->ptt,
            'jumlah_talenta' => $request->jumlah_talenta,
            'nama_talenta' => json_encode($request->nama_talenta),
            'alasan_talenta' => json_encode($request->alasan_talenta),
            'kondisi_guru' => json_encode($request->kondisi_guru),
            'masalah_sdm_utama' => json_encode($request->masalah_sdm_utama),
            // Step 5: Keuangan
            'sumber_dana_utama' => $request->sumber_dana_utama,
            'kondisi_keuangan_akhir_tahun' => $request->kondisi_keuangan_akhir_tahun,
            'catatan_pengelolaan_keuangan' => $request->catatan_pengelolaan_keuangan,
            // Step 6: PPDB
            'metode_ppdb' => $request->metode_ppdb,
            'hasil_ppdb_tahun_berjalan' => $request->hasil_ppdb_tahun_berjalan,
            'masalah_utama_ppdb' => $request->masalah_utama_ppdb,
            // Step 7: Unggulan
            'nama_program_unggulan' => $request->nama_program_unggulan,
            'alasan_pemilihan_program' => $request->alasan_pemilihan_program,
            'target_unggulan' => $request->target_unggulan,
            'kontribusi_unggulan' => $request->kontribusi_unggulan,
            'sumber_biaya_program' => $request->sumber_biaya_program,
            'tim_program_unggulan' => $request->tim_program_unggulan,
            // Step 8: Refleksi
            'keberhasilan_terbesar_tahun_ini' => $request->keberhasilan_terbesar_tahun_ini,
            'masalah_paling_berat_dihadapi' => $request->masalah_paling_berat_dihadapi,
            'keputusan_sulit_diambil' => $request->keputusan_sulit_diambil,
            // Step 9: Risiko
            'risiko_terbesar_satpen_tahun_depan' => $request->risiko_terbesar_satpen_tahun_depan,
            'fokus_perbaikan_tahun_depan' => json_encode($request->fokus_perbaikan_tahun_depan),
            // Step 10: Pernyataan
            'pernyataan_benar' => $request->pernyataan_benar,
            'signature_data' => $request->signature_data,
            // File attachments
            'lampiran_step_1' => $filePaths['lampiran_step_1'] ?? null,
            'lampiran_step_2' => $filePaths['lampiran_step_2'] ?? null,
            'lampiran_step_3' => $filePaths['lampiran_step_3'] ?? null,
            'lampiran_step_4' => $filePaths['lampiran_step_4'] ?? null,
            'lampiran_step_5' => $filePaths['lampiran_step_5'] ?? null,
            'lampiran_step_6' => $filePaths['lampiran_step_6'] ?? null,
            'lampiran_step_7' => $filePaths['lampiran_step_7'] ?? null,
            'lampiran_step_8' => $filePaths['lampiran_step_8'] ?? null,
            'lampiran_step_9' => $filePaths['lampiran_step_9'] ?? null,
        ]);

        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('success', 'Laporan akhir tahun berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        return view('mobile.laporan-akhir-tahun.show', compact('laporan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        // Hitung jumlah guru berdasarkan status kepegawaian
        $madrasah = $user->madrasah;
        if ($madrasah) {
            $guruStats = [
                'pns_sertifikasi' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 1)->count(),
                'pns_non_sertifikasi' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 2)->count(),
                'gty_sertifikasi' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 3)->count(),
                'gty_sertifikasi_inpassing' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 4)->count(),
                'gty_non_sertifikasi' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 5)->count(),
                'gtt' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 6)->count(),
                'pty' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 7)->count(),
                'ptt' => $madrasah->tenagaPendidikUsers()->where('status_kepegawaian_id', 8)->count(),
            ];
        } else {
            // If user has no madrasah linked, default counts to 0 to avoid calling methods on null
            $guruStats = [
                'pns_sertifikasi' => 0,
                'pns_non_sertifikasi' => 0,
                'gty_sertifikasi' => 0,
                'gty_sertifikasi_inpassing' => 0,
                'gty_non_sertifikasi' => 0,
                'gtt' => 0,
                'pty' => 0,
                'ptt' => 0,
            ];
        }

        // Get users with the same madrasah_id for talenta selection
        $guruKaryawan = \App\Models\User::where('madrasah_id', $user->madrasah_id)
            ->where('role', 'tenaga_pendidik')
            ->get(['id', 'name'])
            ->toArray();

        // Pre-fill data from existing report
        $data = [
            'nama_satpen' => $laporan->nama_satpen,
            'alamat' => $laporan->alamat,
            'nama_kepala_sekolah_madrasah' => $laporan->nama_kepala_sekolah_madrasah,
            'gelar' => $laporan->gelar,
            'tmt_ks_kamad_pertama' => $laporan->tmt_ks_kamad_pertama ? $laporan->tmt_ks_kamad_pertama->format('Y-m-d') : '',
            'tmt_ks_kamad_terakhir' => $laporan->tmt_ks_kamad_terakhir ? $laporan->tmt_ks_kamad_terakhir->format('Y-m-d') : '',
            'tahun_pelaporan' => $laporan->tahun_pelaporan,
            'nama_kepala_sekolah' => $laporan->nama_kepala_sekolah,
            // Step 2: Capaian Utama 3 Tahun Berjalan
            'jumlah_siswa_2023' => $laporan->jumlah_siswa_2023,
            'jumlah_siswa_2024' => $laporan->jumlah_siswa_2024,
            'jumlah_siswa_2025' => $laporan->jumlah_siswa_2025,
            'persentase_alumni_bekerja' => $laporan->persentase_alumni_bekerja . '%',
            'persentase_alumni_wirausaha' => $laporan->persentase_alumni_wirausaha . '%',
            'persentase_alumni_tidak_terdeteksi' => $laporan->persentase_alumni_tidak_terdeteksi . '%',
            'bosnas_2023' => $laporan->bosnas_2023,
            'bosnas_2024' => $laporan->bosnas_2024,
            'bosnas_2025' => $laporan->bosnas_2025,
            'bosda_2023' => $laporan->bosda_2023,
            'bosda_2024' => $laporan->bosda_2024,
            'bosda_2025' => $laporan->bosda_2025,
            'spp_bppp_lain_2023' => $laporan->spp_bppp_lain_2023,
            'spp_bppp_lain_2024' => $laporan->spp_bppp_lain_2024,
            'spp_bppp_lain_2025' => $laporan->spp_bppp_lain_2025,
            'pendapatan_unit_usaha_2023' => $laporan->pendapatan_unit_usaha_2023,
            'pendapatan_unit_usaha_2024' => $laporan->pendapatan_unit_usaha_2024,
            'pendapatan_unit_usaha_2025' => $laporan->pendapatan_unit_usaha_2025,
            'status_akreditasi' => $laporan->status_akreditasi,
            'tanggal_akreditasi_mulai' => $laporan->tanggal_akreditasi_mulai ? $laporan->tanggal_akreditasi_mulai->format('Y-m-d') : '',
            'tanggal_akreditasi_berakhir' => $laporan->tanggal_akreditasi_berakhir ? $laporan->tanggal_akreditasi_berakhir->format('Y-m-d') : '',
            // Step 3: Layanan Pendidikan
            'model_layanan_pendidikan' => $laporan->model_layanan_pendidikan,
            'capaian_layanan_menonjol' => $laporan->capaian_layanan_menonjol,
            'masalah_layanan_utama' => $laporan->masalah_layanan_utama,
            // Step 4: SDM
            'pns_sertifikasi' => $laporan->pns_sertifikasi,
            'pns_non_sertifikasi' => $laporan->pns_non_sertifikasi,
            'gty_sertifikasi_inpassing' => $laporan->gty_sertifikasi_inpassing,
            'gty_sertifikasi' => $laporan->gty_sertifikasi,
            'gty_non_sertifikasi' => $laporan->gty_non_sertifikasi,
            'gtt' => $laporan->gtt,
            'pty' => $laporan->pty,
            'ptt' => $laporan->ptt,
            'jumlah_talenta' => $laporan->jumlah_talenta,
            'nama_talenta' => json_decode($laporan->nama_talenta, true) ?? [],
            'alasan_talenta' => json_decode($laporan->alasan_talenta, true) ?? [],
            'kondisi_guru' => json_decode($laporan->kondisi_guru, true) ?? [],
            'masalah_sdm_utama' => json_decode($laporan->masalah_sdm_utama, true) ?? [],
            // Step 5: Keuangan
            'sumber_dana_utama' => $laporan->sumber_dana_utama,
            'kondisi_keuangan_akhir_tahun' => $laporan->kondisi_keuangan_akhir_tahun,
            'catatan_pengelolaan_keuangan' => $laporan->catatan_pengelolaan_keuangan,
            // Step 6: PPDB
            'metode_ppdb' => $laporan->metode_ppdb,
            'hasil_ppdb_tahun_berjalan' => $laporan->hasil_ppdb_tahun_berjalan,
            'masalah_utama_ppdb' => $laporan->masalah_utama_ppdb,
            // Step 7: Unggulan
            'nama_program_unggulan' => $laporan->nama_program_unggulan,
            'alasan_pemilihan_program' => $laporan->alasan_pemilihan_program,
            'target_unggulan' => $laporan->target_unggulan,
            'kontribusi_unggulan' => $laporan->kontribusi_unggulan,
            'sumber_biaya_program' => $laporan->sumber_biaya_program,
            'tim_program_unggulan' => $laporan->tim_program_unggulan,
            // Step 8: Refleksi
            'keberhasilan_terbesar_tahun_ini' => $laporan->keberhasilan_terbesar_tahun_ini,
            'masalah_paling_berat_dihadapi' => $laporan->masalah_paling_berat_dihadapi,
            'keputusan_sulit_diambil' => $laporan->keputusan_sulit_diambil,
            // Step 9: Risiko
            'risiko_terbesar_satpen_tahun_depan' => $laporan->risiko_terbesar_satpen_tahun_depan,
            'fokus_perbaikan_tahun_depan' => json_decode($laporan->fokus_perbaikan_tahun_depan, true) ?? [],
            // Step 10: Pernyataan
            'pernyataan_benar' => $laporan->pernyataan_benar,
            'signature_data' => $laporan->signature_data,
            'guru_karyawan' => $guruKaryawan,
            'jumlah_guru' => $user->madrasah->jumlah_guru ?? 0,
            'jumlah_siswa' => $user->madrasah->jumlah_siswa ?? 0,
            'jumlah_kelas' => $user->madrasah->jumlah_kelas ?? 0,
        ];

        return view('mobile.laporan-akhir-tahun.edit', compact('data', 'laporan', 'guruStats', 'guruKaryawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        $isDraft = $laporan->status === 'draft';

        $request->validate([
            'nama_satpen' => 'required|string|max:255',
            'alamat' => 'required|string',
            'nama_kepala_sekolah_madrasah' => 'required|string|max:255',
            'gelar' => 'nullable|string|max:255',
            'tmt_ks_kamad_pertama' => 'required|date',
            'tmt_ks_kamad_terakhir' => 'required|date',
            'nama_kepala_sekolah' => 'required|string|max:255',
            'nama_madrasah' => 'nullable|string|max:255',
            'alamat_madrasah' => 'nullable|string',
            // Step 2 validations
            'jumlah_siswa_2023' => 'required|integer|min:0',
            'jumlah_siswa_2024' => 'required|integer|min:0',
            'jumlah_siswa_2025' => 'required|integer|min:0',
            'persentase_alumni_bekerja' => 'required|string',
            'persentase_alumni_wirausaha' => 'required|string',
            'persentase_alumni_tidak_terdeteksi' => 'required|string',
            'bosnas_2023' => 'required|string',
            'bosnas_2024' => 'required|string',
            'bosnas_2025' => 'required|string',
            'bosda_2023' => 'required|string',
            'bosda_2024' => 'required|string',
            'bosda_2025' => 'required|string',
            'spp_bppp_lain_2023' => 'required|string',
            'spp_bppp_lain_2024' => 'required|string',
            'spp_bppp_lain_2025' => 'required|string',
            'pendapatan_unit_usaha_2023' => 'required|string',
            'pendapatan_unit_usaha_2024' => 'required|string',
            'pendapatan_unit_usaha_2025' => 'required|string',
            'status_akreditasi' => 'required|string',
            'tanggal_akreditasi_mulai' => 'required|date',
            'tanggal_akreditasi_berakhir' => 'required|date',
            // Step 3 validations
            'model_layanan_pendidikan' => 'required|string',
            'capaian_layanan_menonjol' => 'required|string',
            'masalah_layanan_utama' => 'required|string',
            // Step 4 validations
            'pns_sertifikasi' => 'required|integer|min:0',
            'pns_non_sertifikasi' => 'required|integer|min:0',
            'gty_sertifikasi_inpassing' => 'required|integer|min:0',
            'gty_sertifikasi' => 'required|integer|min:0',
            'gty_non_sertifikasi' => 'required|integer|min:0',
            'gtt' => 'required|integer|min:0',
            'pty' => 'required|integer|min:0',
            'ptt' => 'required|integer|min:0',
            'jumlah_talenta' => $isDraft ? 'nullable|integer|min:3|max:9' : 'required|integer|min:3|max:9',
            'nama_talenta' => $isDraft ? 'nullable|array|min:3|max:9' : 'required|array|min:3|max:9',
            'nama_talenta.*' => $isDraft ? 'nullable|string' : 'required|string',
            'alasan_talenta' => $isDraft ? 'nullable|array|min:3|max:9' : 'required|array|min:3|max:9',
            'alasan_talenta.*' => $isDraft ? 'nullable|string' : 'required|string',
            'kondisi_guru' => 'required|array',
            'kondisi_guru.*' => 'required|string|in:baik,cukup,bermasalah',
            'masalah_sdm_utama' => 'required|array|min:3',
            'masalah_sdm_utama.*' => 'required|string',
            // Step 5 validations
            'sumber_dana_utama' => 'required|string',
            'kondisi_keuangan_akhir_tahun' => 'required|string|in:sehat,cukup,risiko,kritis',
            'catatan_pengelolaan_keuangan' => 'required|string',
            // Step 6 validations
            'metode_ppdb' => 'required|string',
            'hasil_ppdb_tahun_berjalan' => 'required|string',
            'masalah_utama_ppdb' => 'required|string',
            // Step 7 validations
            'nama_program_unggulan' => 'required|string',
            'alasan_pemilihan_program' => 'required|string',
            'target_unggulan' => 'required|string',
            'kontribusi_unggulan' => 'required|string',
            'sumber_biaya_program' => 'required|string',
            'tim_program_unggulan' => 'required|string',
            // Step 8 validations
            'keberhasilan_terbesar_tahun_ini' => 'required|string',
            'masalah_paling_berat_dihadapi' => 'required|string',
            'keputusan_sulit_diambil' => 'required|string',
            // Step 9 validations
            'risiko_terbesar_satpen_tahun_depan' => 'required|string',
            'fokus_perbaikan_tahun_depan' => 'required|array|min:1',
            'fokus_perbaikan_tahun_depan.*' => 'required|string',
            // Step 10 validations
            'pernyataan_benar' => 'required|boolean',
            'signature_data' => 'required|string',
        ]);

        $laporan->update([
            'nama_satpen' => $request->nama_satpen,
            'alamat' => $request->alamat,
            'nama_kepala_sekolah_madrasah' => $request->nama_kepala_sekolah_madrasah,
            'gelar' => $request->gelar,
            'tmt_ks_kamad_pertama' => $request->tmt_ks_kamad_pertama,
            'tmt_ks_kamad_terakhir' => $request->tmt_ks_kamad_terakhir,
            'nama_kepala_sekolah' => $request->nama_kepala_sekolah,
            // Step 2: Capaian Utama 3 Tahun Berjalan
            'jumlah_siswa_2023' => $request->jumlah_siswa_2023,
            'jumlah_siswa_2024' => $request->jumlah_siswa_2024,
            'jumlah_siswa_2025' => $request->jumlah_siswa_2025,
            'persentase_alumni_bekerja' => str_replace('%', '', $request->persentase_alumni_bekerja),
            'persentase_alumni_wirausaha' => str_replace('%', '', $request->persentase_alumni_wirausaha),
            'persentase_alumni_tidak_terdeteksi' => str_replace('%', '', $request->persentase_alumni_tidak_terdeteksi),
            'bosnas_2023' => $request->bosnas_2023,
            'bosnas_2024' => $request->bosnas_2024,
            'bosnas_2025' => $request->bosnas_2025,
            'bosda_2023' => $request->bosda_2023,
            'bosda_2024' => $request->bosda_2024,
            'bosda_2025' => $request->bosda_2025,
            'spp_bppp_lain_2023' => $request->spp_bppp_lain_2023,
            'spp_bppp_lain_2024' => $request->spp_bppp_lain_2024,
            'spp_bppp_lain_2025' => $request->spp_bppp_lain_2025,
            'pendapatan_unit_usaha_2023' => $request->pendapatan_unit_usaha_2023,
            'pendapatan_unit_usaha_2024' => $request->pendapatan_unit_usaha_2024,
            'pendapatan_unit_usaha_2025' => $request->pendapatan_unit_usaha_2025,
            'status_akreditasi' => $request->status_akreditasi,
            'tanggal_akreditasi_mulai' => $request->tanggal_akreditasi_mulai,
            'tanggal_akreditasi_berakhir' => $request->tanggal_akreditasi_berakhir,
            // Step 3: Layanan Pendidikan
            'model_layanan_pendidikan' => $request->model_layanan_pendidikan,
            'capaian_layanan_menonjol' => $request->capaian_layanan_menonjol,
            'masalah_layanan_utama' => $request->masalah_layanan_utama,
            // Step 4: SDM
            'pns_sertifikasi' => $request->pns_sertifikasi,
            'pns_non_sertifikasi' => $request->pns_non_sertifikasi,
            'gty_sertifikasi_inpassing' => $request->gty_sertifikasi_inpassing,
            'gty_sertifikasi' => $request->gty_sertifikasi,
            'gty_non_sertifikasi' => $request->gty_non_sertifikasi,
            'gtt' => $request->gtt,
            'pty' => $request->pty,
            'ptt' => $request->ptt,
            'jumlah_talenta' => $request->jumlah_talenta,
            'nama_talenta' => json_encode($request->nama_talenta),
            'alasan_talenta' => json_encode($request->alasan_talenta),
            'kondisi_guru' => json_encode($request->kondisi_guru),
            'masalah_sdm_utama' => json_encode($request->masalah_sdm_utama),
            // Step 5: Keuangan
            'sumber_dana_utama' => $request->sumber_dana_utama,
            'kondisi_keuangan_akhir_tahun' => $request->kondisi_keuangan_akhir_tahun,
            'catatan_pengelolaan_keuangan' => $request->catatan_pengelolaan_keuangan,
            // Step 6: PPDB
            'metode_ppdb' => $request->metode_ppdb,
            'hasil_ppdb_tahun_berjalan' => $request->hasil_ppdb_tahun_berjalan,
            'masalah_utama_ppdb' => $request->masalah_utama_ppdb,
            // Step 7: Unggulan
            'nama_program_unggulan' => $request->nama_program_unggulan,
            'alasan_pemilihan_program' => $request->alasan_pemilihan_program,
            'target_unggulan' => $request->target_unggulan,
            'kontribusi_unggulan' => $request->kontribusi_unggulan,
            'sumber_biaya_program' => $request->sumber_biaya_program,
            'tim_program_unggulan' => $request->tim_program_unggulan,
            // Step 8: Refleksi
            'keberhasilan_terbesar_tahun_ini' => $request->keberhasilan_terbesar_tahun_ini,
            'masalah_paling_berat_dihadapi' => $request->masalah_paling_berat_dihadapi,
            'keputusan_sulit_diambil' => $request->keputusan_sulit_diambil,
            // Step 9: Risiko
            'risiko_terbesar_satpen_tahun_depan' => $request->risiko_terbesar_satpen_tahun_depan,
            'fokus_perbaikan_tahun_depan' => json_encode($request->fokus_perbaikan_tahun_depan),
            // Step 10: Pernyataan
            'pernyataan_benar' => $request->pernyataan_benar,
            'signature_data' => $request->signature_data,
        ]);

        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('success', 'Laporan akhir tahun berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        // Only kepala sekolah can access this
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this feature.');
        }

        $laporan = LaporanAkhirTahunKepalaSekolah::where('user_id', $user->id)
            ->findOrFail($id);

        // Allow deletion of all reports since status field is removed

        $laporan->delete();

        return redirect()->route('mobile.laporan-akhir-tahun.index')
            ->with('success', 'Laporan akhir tahun berhasil dihapus.');
    }
}
