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
                [
                    'name' => 'Performa Tinggi',
                    'content' => 'Website loading cepat dengan optimasi SEO otomatis untuk meningkatkan visibilitas.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Responsif Penuh',
                    'content' => 'Tampilan yang sempurna di semua perangkat, dari desktop hingga mobile.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Keamanan Terjamin',
                    'content' => 'Sistem keamanan tingkat tinggi dengan enkripsi data dan backup otomatis.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Template Modern',
                    'content' => 'Koleksi template yang elegan dan mudah dikustomisasi sesuai kebutuhan.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Analytics Terintegrasi',
                    'content' => 'Laporan analitik mendalam untuk memahami performa dan pengunjung.',
                    'status' => 'active'
                ],
                [
                    'name' => 'Dukungan 24/7',
                    'content' => 'Tim support profesional siap membantu kapan saja Anda butuhkan.',
                    'status' => 'active'
                ]
            ],
        ]);
    }
}
