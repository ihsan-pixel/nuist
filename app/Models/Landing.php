<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;

    protected $table = 'landings';

    protected $fillable = [
        'image_1_hero',
        'title_hero',
        'sub_title_hero',
        'content_hero',
        'image_2_hero',
        'title_profile',
        'content_1_profile',
        'image_1_profile',
        'image_2_profile',
        'content_2_profile',
        'content_3_profile',
        'title_features',
        'content_features',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    /**
     * Get the first (and only) landing settings record
     */
    public static function getLanding()
    {
        return static::first() ?? static::create([
            'title_hero' => 'Nuist - Sistem Informasi Digital',
            'sub_title_hero' => 'LP. Ma\'arif NU PWNU DIY',
            'content_hero' => 'Kelola data kelembagaan, aktivitas, sistem informasi dan layanan dalam satu aplikasi yang modern, aman, dan mudah digunakan.',
            'title_profile' => 'Profile Nuist',
            'content_1_profile' => 'Nuist menghadirkan ekosistem aplikasi terintegrasi yang dirancang untuk mendukung pengelolaan administrasi sekolah secara menyeluruh. Melalui Nuist Desktop dan Nuist Mobile, sekolah dapat mengelola data, aktivitas, dan kehadiran secara terpusat, akurat, serta mudah diakses oleh administrator, tenaga pendidik, dan kepala sekolah dalam satu sistem yang saling terhubung.',
            'content_2_profile' => 'Aplikasi khusus untuk administrator sekolah dalam mengelola data sekolah dan data tenaga pendidik secara terpusat, aman, dan efisien. Dirancang untuk mendukung kebutuhan administrasi modern, Nuist Desktop membantu menyederhanakan pengelolaan data, meningkatkan akurasi informasi, serta mendukung pengambilan keputusan berbasis data.',
            'content_3_profile' => 'Aplikasi berbasis mobile yang dirancang khusus untuk tenaga pendidik dan kepala sekolah dalam melakukan presensi, presensi mengajar, pengajuan izin, serta penyesuaian data pribadi secara praktis dan real-time. Aplikasi ini mendukung kemudahan akses, akurasi data, dan efisiensi administrasi dalam satu platform terpadu.',
            'title_features' => 'Fitur Unggulan',
            'content_features' => 'Nikmati berbagai fitur canggih yang dirancang untuk memaksimalkan efisiensi dan keamanan dalam pengelolaan sekolah Anda.',
            'features' => [
                // FITUR YANG SUDAH JADI
                [
                    'name' => 'Presensi Kehadiran Guru & Pegawai',
                    'content' => 'Sistem presensi real-time dengan tracking lokasi dan foto untuk memastikan kehadiran guru dan pegawai tercatat dengan akurat.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Presensi Mengajar Guru',
                    'content' => 'Monitoring kehadiran mengajar berdasarkan jadwal yang ditetapkan, lengkap dengan laporan keterlambatan dan ketidakhadiran.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Hak Akses Kepala Sekolah',
                    'content' => 'Akses lengkap untuk kepala sekolah dalam monitoring, approve data presensi, tenaga pendidik, dan laporan sekolah.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Kelola Data Tenaga Pendidik',
                    'content' => 'Pengelolaan data tenaga pendidik secara komprehensif dengan fitur CRUD, import/export, dan pencarian cepat.',
                    'status' => 'active'
                ],
                [
                    'name' => 'SPMB Online',
                    'content' => 'Sistem penerimaan peserta didik baru secara online dengan pendaftaran, verifikasi, dan pengumuman terintegrasi.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Laporan Rekap Kehadiran Bulanan',
                    'content' => 'Otomatisasi pembuatan laporan kehadiran dengan filter per bulan, guru, dan sekolah. Support export PDF dan Excel.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Simfoni Update',
                    'content' => 'Sinkronisasi otomatis data sekolah dan tenaga pendidik dengan sistem Simfoni LPMNU DIY untuk memastikan data valid.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Laporan Akhir Tahun Kepala Sekolah',
                    'content' => 'Template laporan tahunan terintegrasi dengan data sekolah, presensi, dan tenaga pendidik untuk memudahkan pelaporan.',
                    'status' => 'active'
                ],
                [
                    'name' => 'UPPM',
                    'content' => 'Dashboard pengelolaan iuran, tagihan dan pembayaran UPPM per sekolah dengan sistem invoice dan laporan keuangan.',
                    'status' => 'active'
                ],
                // FITUR COMING SOON
                [
                    'name' => 'MGMP',
                    'content' => 'Musyawarah Guru Mata Pelajaran - Platform kolaborasi dan diskusi antar guru mata pelajaran di lingkungan LP Ma\'arif.',
                    'status' => 'coming_soon'
                ],
                [
                    'name' => 'MKKSM',
                    'content' => 'Musyawarah Kepala Sekolah Madrasah - Forum komunikasi dan koordinasi antar kepala sekolah untuk pengembangan madrasah.',
                    'status' => 'coming_soon'
                ],
                [
                    'name' => 'DPS',
                    'content' => 'Dana Pendidikan Sekolah - Sistem pengelolaan dan transparansi dana pendidikan dari berbagai sumber untuk setiap sekolah.',
                    'status' => 'coming_soon'
                ]
            ],
        ]);
    }
}
