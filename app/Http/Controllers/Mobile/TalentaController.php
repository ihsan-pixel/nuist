<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Talenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TalentaController extends Controller
{
    public function __construct()
    {
        // No role restrictions - accessible to all authenticated users
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $talenta = Talenta::where('user_id', $user->id)->first();

        return view('mobile.talenta.index', compact('talenta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $existingTalenta = Talenta::where('user_id', $user->id)->first();

        if ($existingTalenta) {
            return redirect()->route('mobile.talenta.show', $existingTalenta->id);
        }

        $data = [
            'tahun_pelaporan' => date('Y'),
        ];

        return view('mobile.talenta.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check if user has madrasah_id
        if (!$user->madrasah_id) {
            return redirect()->back()
                ->with('error', 'Data madrasah tidak ditemukan. Silakan hubungi administrator.');
        }

        // Check if user already has a talenta record
        $existingTalenta = Talenta::where('user_id', $user->id)->first();
        if ($existingTalenta) {
            return redirect()->route('mobile.talenta.show', $existingTalenta->id)
                ->with('error', 'Anda sudah memiliki data talenta.');
        }

        $validator = Validator::make($request->all(), [
            // TPT Level 1
            'nomor_talenta_1' => 'nullable|string|max:255',
            'skor_penilaian_1' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_1' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_1' => 'nullable|file|mimes:pdf|max:10240',

            // TPT Level 2
            'nomor_talenta_2' => 'nullable|string|max:255',
            'skor_penilaian_2' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_2' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_2' => 'nullable|file|mimes:pdf|max:10240',

            // TPT Level 3
            'nomor_talenta_3' => 'nullable|string|max:255',
            'skor_penilaian_3' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_3' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_3' => 'nullable|file|mimes:pdf|max:10240',

            // TPT Level 4
            'nomor_talenta_4' => 'nullable|string|max:255',
            'skor_penilaian_4' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_4' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_4' => 'nullable|file|mimes:pdf|max:10240',

            // TPT Level 5
            'nomor_talenta_5' => 'nullable|string|max:255',
            'skor_penilaian_5' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_5' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_5' => 'nullable|file|mimes:pdf|max:10240',

            // Pendidikan Kader
            'pkpnu_status' => 'nullable|in:sudah,belum',
            'pkpnu_sertifikat' => 'nullable|file|mimes:pdf|max:10240',
            'mknu_status' => 'nullable|in:sudah,belum',
            'mknu_sertifikat' => 'nullable|file|mimes:pdf|max:10240',
            'pmknu_status' => 'nullable|in:sudah,belum',
            'pmknu_sertifikat' => 'nullable|file|mimes:pdf|max:10240',

            // Proyeksi Diri
            'jabatan_saat_ini' => 'nullable|string|max:255',
            'proyeksi_akademik' => 'nullable|string|max:255',
            'proyeksi_jabatan_level2_umum' => 'nullable|string|max:255',
            'proyeksi_jabatan_level2_khusus' => 'nullable|string|max:255',
            'proyeksi_jabatan_level1' => 'nullable|string|max:255',
            'proyeksi_jabatan_top_leader' => 'nullable|string|max:255',
            'studi_lanjut' => 'nullable|string|max:500',
            'leader_mgmp' => 'nullable|string|max:500',
            'produk_ajar' => 'nullable|string|max:500',
            'prestasi_kompetitif' => 'nullable|string|max:500',

            // Data Diri - Personal
            'nama_lengkap_gelar' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'nomor_ktp' => 'required|string|max:20',
            'nip_maarif' => 'required|string|max:50',
            'nomor_talenta' => 'required|string|max:50',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'email_aktif' => 'required|email|max:255',
            'nomor_wa' => 'required|string|max:20',
            'alamat_ktp' => 'required|string|max:500',
            'alamat_tinggal' => 'required|string|max:500',
            'link_fb' => 'nullable|url|max:255',
            'link_tiktok' => 'nullable|url|max:255',
            'link_instagram' => 'nullable|url|max:255',
            'foto_resmi' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_bebas' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_keluarga' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',

            // Data Diri - Education
            'asal_sekolah_sd' => 'nullable|string|max:255',
            'asal_sekolah_smp' => 'nullable|string|max:255',
            'asal_sekolah_sma' => 'nullable|string|max:255',
            'asal_sekolah_s1' => 'nullable|string|max:255',
            'asal_sekolah_s2' => 'nullable|string|max:255',
            'asal_sekolah_s3' => 'nullable|string|max:255',
            'ijazah_s1' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'ijazah_s2' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'ijazah_s3' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',

            // Data Diri - Income
            'level_pendapatan_internal' => 'required|string|max:50',
            'pekerjaan_eksternal' => 'required|string|max:100',
            'pendapatan_eksternal' => 'required|string|max:100',

            // Data Diri - Work History
            'gtt_ptt_tanggal' => 'required|string|max:50',
            'gtt_ptt_sk' => 'nullable|file|mimes:pdf|max:10240',
            'gty_tanggal' => 'required|string|max:50',
            'gty_sk' => 'nullable|file|mimes:pdf|max:10240',
            'masa_kerja_lpmnu' => 'required|integer|min:0',
            'riwayat_jabatan_pemula' => 'required|string|max:100',
            'riwayat_jabatan_terampil' => 'required|string|max:100',
            'riwayat_jabatan_mahir' => 'required|string|max:100',
            'riwayat_jabatan_ahli' => 'required|string|max:100',

            // File attachments
            'lampiran_step_1' => 'nullable|file|mimes:pdf|max:10240',
            'lampiran_step_2' => 'nullable|file|mimes:pdf|max:10240',
            'lampiran_step_3' => 'nullable|file|mimes:pdf|max:10240',
            'lampiran_step_4' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['user_id'] = $user->id;
        $data['madrasah_id'] = $user->madrasah_id;
        $data['status'] = $request->input('status', 'draft');

        // Handle file uploads
        $fileFields = [
            'sertifikat_tpt_1', 'produk_unggulan_1',
            'sertifikat_tpt_2', 'produk_unggulan_2',
            'sertifikat_tpt_3', 'produk_unggulan_3',
            'sertifikat_tpt_4', 'produk_unggulan_4',
            'sertifikat_tpt_5', 'produk_unggulan_5',
            'pkpnu_sertifikat', 'mknu_sertifikat', 'pmknu_sertifikat',
            'foto_resmi', 'foto_bebas', 'foto_keluarga',
            'ijazah_s1', 'ijazah_s2', 'ijazah_s3',
            'gtt_ptt_sk', 'gty_sk',
            'lampiran_step_1', 'lampiran_step_2', 'lampiran_step_3', 'lampiran_step_4'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                try {
                    $path = $file->storeAs('talenta', $filename, 'public');
                    $data[$field] = $path;
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withErrors(['file_upload' => 'Gagal mengupload file: ' . $e->getMessage()])
                        ->withInput();
                }
            }
        }

        try {
            $talenta = Talenta::create($data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['database' => 'Gagal menyimpan data: ' . $e->getMessage()])
                ->withInput();
        }

        $message = $data['status'] === 'published' ? 'Data talenta berhasil disimpan dan dipublikasikan.' : 'Data talenta berhasil disimpan sebagai draft.';

        return redirect()->route('mobile.talenta.index', $talenta->id)
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Talenta $talenta)
    {
        // Accessible to all authenticated users - no role restrictions

        return view('mobile.talenta.show', compact('talenta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Talenta $talenta)
    {
        // Allow access to all authenticated users

        // Prevent editing if published
        if ($talenta->status === 'published') {
            return redirect()->route('mobile.talenta.show', $talenta->id)
                ->with('error', 'Data talenta yang sudah dipublikasikan tidak dapat diubah.');
        }

        return view('mobile.talenta.edit', compact('talenta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Talenta $talenta)
    {
        // Allow access to all authenticated users

        // Prevent updating if published
        if ($talenta->status === 'published') {
            return redirect()->route('mobile.talenta.show', $talenta->id)
                ->with('error', 'Data talenta yang sudah dipublikasikan tidak dapat diubah.');
        }

        $validator = Validator::make($request->all(), [
            // TPT Level 1
            'nomor_talenta_1' => 'nullable|string|max:255',
            'skor_penilaian_1' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_1' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_1' => 'nullable|file|mimes:pdf|max:10240',

            // TPT Level 2
            'nomor_talenta_2' => 'nullable|string|max:255',
            'skor_penilaian_2' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_2' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_2' => 'nullable|file|mimes:pdf|max:10240',

            // TPT Level 3
            'nomor_talenta_3' => 'nullable|string|max:255',
            'skor_penilaian_3' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_3' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_3' => 'nullable|file|mimes:pdf|max:10240',

            // TPT Level 4
            'nomor_talenta_4' => 'nullable|string|max:255',
            'skor_penilaian_4' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_4' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_4' => 'nullable|file|mimes:pdf|max:10240',

            // TPT Level 5
            'nomor_talenta_5' => 'nullable|string|max:255',
            'skor_penilaian_5' => 'nullable|integer|min:0|max:100',
            'sertifikat_tpt_5' => 'nullable|file|mimes:pdf|max:10240',
            'produk_unggulan_5' => 'nullable|file|mimes:pdf|max:10240',

            // Pendidikan Kader
            'pkpnu_status' => 'nullable|in:sudah,belum',
            'pkpnu_sertifikat' => 'nullable|file|mimes:pdf|max:10240',
            'mknu_status' => 'nullable|in:sudah,belum',
            'mknu_sertifikat' => 'nullable|file|mimes:pdf|max:10240',
            'pmknu_status' => 'nullable|in:sudah,belum',
            'pmknu_sertifikat' => 'nullable|file|mimes:pdf|max:10240',

            // Proyeksi Diri
            'jabatan_saat_ini' => 'nullable|string|max:255',
            'proyeksi_akademik' => 'nullable|string|max:255',
            'proyeksi_jabatan_level2_umum' => 'nullable|string|max:255',
            'proyeksi_jabatan_level2_khusus' => 'nullable|string|max:255',
            'proyeksi_jabatan_level1' => 'nullable|string|max:255',
            'proyeksi_jabatan_top_leader' => 'nullable|string|max:255',
            'studi_lanjut' => 'nullable|string|max:500',
            'leader_mgmp' => 'nullable|string|max:500',
            'produk_ajar' => 'nullable|string|max:500',
            'prestasi_kompetitif' => 'nullable|string|max:500',

            // Data Diri - Personal
            'nama_lengkap_gelar' => 'required|string|max:255',
            'nama_panggilan' => 'required|string|max:255',
            'nomor_ktp' => 'required|string|max:20',
            'nip_maarif' => 'required|string|max:50',
            'nomor_talenta' => 'required|string|max:50',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'email_aktif' => 'required|email|max:255',
            'nomor_wa' => 'required|string|max:20',
            'alamat_ktp' => 'required|string|max:500',
            'alamat_tinggal' => 'required|string|max:500',
            'link_fb' => 'nullable|url|max:255',
            'link_tiktok' => 'nullable|url|max:255',
            'link_instagram' => 'nullable|url|max:255',
            'foto_resmi' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_bebas' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'foto_keluarga' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',

            // Data Diri - Education
            'asal_sekolah_sd' => 'nullable|string|max:255',
            'asal_sekolah_smp' => 'nullable|string|max:255',
            'asal_sekolah_sma' => 'nullable|string|max:255',
            'asal_sekolah_s1' => 'nullable|string|max:255',
            'asal_sekolah_s2' => 'nullable|string|max:255',
            'asal_sekolah_s3' => 'nullable|string|max:255',
            'ijazah_s1' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'ijazah_s2' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',
            'ijazah_s3' => 'nullable|file|mimes:pdf,jpg,jpeg|max:10240',

            // Data Diri - Income
            'level_pendapatan_internal' => 'required|string|max:50',
            'pekerjaan_eksternal' => 'required|string|max:100',
            'pendapatan_eksternal' => 'required|string|max:100',

            // Data Diri - Work History
            'gtt_ptt_tanggal' => 'required|string|max:50',
            'gtt_ptt_sk' => 'nullable|file|mimes:pdf|max:10240',
            'gty_tanggal' => 'required|string|max:50',
            'gty_sk' => 'nullable|file|mimes:pdf|max:10240',
            'masa_kerja_lpmnu' => 'required|integer|min:0',
            'riwayat_jabatan_pemula' => 'required|string|max:100',
            'riwayat_jabatan_terampil' => 'required|string|max:100',
            'riwayat_jabatan_mahir' => 'required|string|max:100',
            'riwayat_jabatan_ahli' => 'required|string|max:100',

            // File attachments
            'lampiran_step_1' => 'nullable|file|mimes:pdf|max:10240',
            'lampiran_step_2' => 'nullable|file|mimes:pdf|max:10240',
            'lampiran_step_3' => 'nullable|file|mimes:pdf|max:10240',
            'lampiran_step_4' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['status'] = $request->input('status', 'draft');

        // Handle file uploads (same as store method)
        $fileFields = [
            'sertifikat_tpt_1', 'produk_unggulan_1',
            'sertifikat_tpt_2', 'produk_unggulan_2',
            'sertifikat_tpt_3', 'produk_unggulan_3',
            'sertifikat_tpt_4', 'produk_unggulan_4',
            'sertifikat_tpt_5', 'produk_unggulan_5',
            'pkpnu_sertifikat', 'mknu_sertifikat', 'pmknu_sertifikat',
            'foto_resmi', 'foto_bebas', 'foto_keluarga',
            'ijazah_s1', 'ijazah_s2', 'ijazah_s3',
            'gtt_ptt_sk', 'gty_sk',
            'lampiran_step_1', 'lampiran_step_2', 'lampiran_step_3', 'lampiran_step_4'
        ];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($talenta->$field && Storage::disk('public')->exists($talenta->$field)) {
                    Storage::disk('public')->delete($talenta->$field);
                }

                $file = $request->file($field);
                $filename = time() . '_' . $field . '_' . $talenta->user_id . '.' . $file->getClientOriginalExtension();
                try {
                    $path = $file->storeAs('talenta', $filename, 'public');
                    $data[$field] = $path;
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withErrors(['file_upload' => 'Gagal mengupload file: ' . $e->getMessage()])
                        ->withInput();
                }
            }
        }

        try {
            $talenta->update($data);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['database' => 'Gagal memperbarui data: ' . $e->getMessage()])
                ->withInput();
        }

        $message = $data['status'] === 'published' ? 'Data talenta berhasil diperbarui dan dipublikasikan.' : 'Data talenta berhasil diperbarui sebagai draft.';

        return redirect()->route('mobile.talenta.show', $talenta->id)
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Talenta $talenta)
    {
        // Accessible to all authenticated users - no role restrictions

        // Delete associated files
        $fileFields = [
            'sertifikat_tpt_1', 'produk_unggulan_1',
            'sertifikat_tpt_2', 'produk_unggulan_2',
            'sertifikat_tpt_3', 'produk_unggulan_3',
            'sertifikat_tpt_4', 'produk_unggulan_4',
            'sertifikat_tpt_5', 'produk_unggulan_5',
            'pkpnu_sertifikat', 'mknu_sertifikat', 'pmknu_sertifikat',
            'foto_resmi', 'foto_bebas', 'foto_keluarga',
            'ijazah_s1', 'ijazah_s2', 'ijazah_s3',
            'gtt_ptt_sk', 'gty_sk',
            'lampiran_step_1', 'lampiran_step_2', 'lampiran_step_3', 'lampiran_step_4'
        ];

        foreach ($fileFields as $field) {
            if ($talenta->$field && Storage::disk('public')->exists($talenta->$field)) {
                Storage::disk('public')->delete($talenta->$field);
            }
        }

        $talenta->delete();

        return redirect()->route('mobile.talenta.index')
            ->with('success', 'Data talenta berhasil dihapus.');
    }
}
