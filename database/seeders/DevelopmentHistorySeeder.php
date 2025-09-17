<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DevelopmentHistory;
use Carbon\Carbon;

class DevelopmentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $histories = [
            // Laravel Foundation (2014-2019)
            [
                'title' => 'Instalasi Framework Laravel',
                'description' => 'Instalasi awal framework Laravel dengan sistem autentikasi dasar, manajemen user, dan reset password',
                'type' => 'migration',
                'development_date' => Carbon::create(2014, 10, 12),
                'migration_file' => '2014_10_12_000000_create_users_table.php',
                'details' => ['phase' => 'foundation', 'category' => 'authentication']
            ],
            [
                'title' => 'Sistem Reset Password',
                'description' => 'Implementasi sistem reset password untuk keamanan akun pengguna',
                'type' => 'feature',
                'development_date' => Carbon::create(2014, 10, 12),
                'migration_file' => '2014_10_12_100000_create_password_resets_table.php',
                'details' => ['phase' => 'foundation', 'category' => 'security']
            ],
            [
                'title' => 'Sistem Queue dan Job Management',
                'description' => 'Implementasi sistem antrian untuk menangani tugas-tugas background dan failed jobs',
                'type' => 'feature',
                'development_date' => Carbon::create(2019, 8, 19),
                'migration_file' => '2019_08_19_000000_create_failed_jobs_table.php',
                'details' => ['phase' => 'foundation', 'category' => 'performance']
            ],
            [
                'title' => 'API Authentication dengan Sanctum',
                'description' => 'Implementasi Laravel Sanctum untuk autentikasi API dan personal access tokens',
                'type' => 'feature',
                'development_date' => Carbon::create(2019, 12, 14),
                'migration_file' => '2019_12_14_000001_create_personal_access_tokens_table.php',
                'details' => ['phase' => 'foundation', 'category' => 'api']
            ],

            // Customer Module (2023)
            [
                'title' => 'Modul Manajemen Customer',
                'description' => 'Pengembangan sistem manajemen customer sebagai fondasi awal aplikasi bisnis',
                'type' => 'feature',
                'development_date' => Carbon::create(2023, 7, 10),
                'migration_file' => '2023_07_10_112535_create_customers_table.php',
                'details' => ['phase' => 'business', 'category' => 'customer_management']
            ],

            // Major Development Phase (September 2025)
            [
                'title' => 'Sistem Administrasi',
                'description' => 'Implementasi sistem administrasi dengan role-based access control untuk mengelola admin aplikasi',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_004646_create_admins_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'administration']
            ],
            [
                'title' => 'Sistem Role Management',
                'description' => 'Penambahan sistem role pada tabel users untuk mengatur hak akses pengguna (super_admin, admin, tenaga_pendidik)',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_004947_add_role_to_users_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'authorization']
            ],
            [
                'title' => 'Profil Pengguna Lengkap',
                'description' => 'Penambahan field profil lengkap untuk pengguna termasuk informasi personal dan avatar',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_014244_add_fields_to_users_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'user_profile']
            ],
            [
                'title' => 'Optimasi Avatar Pengguna',
                'description' => 'Membuat field avatar menjadi nullable untuk fleksibilitas profil pengguna',
                'type' => 'update',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_020311_update_users_table_nullable_avatar.php',
                'details' => ['phase' => 'major_development', 'category' => 'optimization']
            ],
            [
                'title' => 'Sistem Manajemen Madrasah/Sekolah',
                'description' => 'Implementasi sistem manajemen madrasah dan sekolah dengan informasi lengkap institusi pendidikan',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_070636_create_madrasahs_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'education_management']
            ],
            [
                'title' => 'Optimasi Logo Madrasah',
                'description' => 'Membuat field logo madrasah menjadi nullable untuk fleksibilitas data',
                'type' => 'update',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_071514_make_logo_nullable_in_madrasahs_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'optimization']
            ],
            [
                'title' => 'Sistem Manajemen Tenaga Pendidik',
                'description' => 'Implementasi sistem manajemen tenaga pendidik dengan informasi lengkap guru dan staff pendidikan',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_074634_create_tenaga_pendidiks_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'staff_management']
            ],
            [
                'title' => 'Integrasi Data Tenaga Pendidik',
                'description' => 'Penambahan field tenaga pendidik pada tabel users untuk integrasi data',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 5),
                'migration_file' => '2025_09_05_085835_add_tenaga_pendidik_fields_to_users_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'data_integration']
            ],
            [
                'title' => 'Sistem Status Kepegawaian',
                'description' => 'Implementasi sistem manajemen status kepegawaian untuk kategorisasi tenaga pendidik',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 7),
                'migration_file' => '2025_09_07_134201_create_status_kepegawaian_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'hr_management']
            ],
            [
                'title' => 'Relasi Status Kepegawaian',
                'description' => 'Implementasi foreign key relationship antara tenaga pendidik dan status kepegawaian',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 7),
                'migration_file' => '2025_09_07_135204_update_status_kepegawaian_to_foreign_key_in_tenaga_pendidiks_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'database_optimization']
            ],
            [
                'title' => 'Sistem Tahun Pelajaran',
                'description' => 'Implementasi sistem manajemen tahun pelajaran untuk periodisasi akademik',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 7),
                'migration_file' => '2025_09_07_140000_create_tahun_pelajaran_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'academic_management']
            ],
            [
                'title' => 'Optimasi Role System',
                'description' => 'Peningkatan panjang kolom role untuk mendukung role yang lebih kompleks',
                'type' => 'update',
                'development_date' => Carbon::create(2025, 9, 7),
                'migration_file' => '2025_09_07_150000_update_role_column_length_in_users_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'system_optimization']
            ],
            [
                'title' => 'Super Admin Role',
                'description' => 'Penambahan role super_admin untuk akses penuh sistem dan manajemen tingkat tertinggi',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 8),
                'migration_file' => '2025_09_08_000000_add_super_admin_role_to_users_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'authorization']
            ],
            [
                'title' => 'Sistem Presensi',
                'description' => 'Implementasi sistem presensi/absensi untuk tracking kehadiran tenaga pendidik',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 8),
                'migration_file' => '2025_09_08_000001_create_presensis_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'attendance_management']
            ],
            [
                'title' => 'Sistem Lokasi Madrasah',
                'description' => 'Penambahan koordinat latitude dan longitude untuk sistem presensi berbasis lokasi',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 8),
                'migration_file' => '2025_09_08_120000_add_latitude_longitude_to_madrasahs_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'location_services']
            ],
            [
                'title' => 'Informasi Alamat Madrasah',
                'description' => 'Penambahan field alamat lengkap untuk informasi lokasi madrasah',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 11),
                'migration_file' => '2025_09_11_100000_add_alamat_to_madrasahs_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'location_services']
            ],
            [
                'title' => 'Integrasi Google Maps',
                'description' => 'Penambahan link Google Maps untuk navigasi ke lokasi madrasah',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 11),
                'migration_file' => '2025_09_11_110000_add_map_link_to_madrasahs_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'navigation']
            ],
            [
                'title' => 'Pengaturan Presensi',
                'description' => 'Implementasi sistem pengaturan presensi untuk konfigurasi jam kerja dan parameter absensi',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 11),
                'migration_file' => '2025_09_11_120000_create_presensi_settings_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'attendance_configuration']
            ],
            [
                'title' => 'Radius Presensi',
                'description' => 'Penambahan pengaturan radius untuk validasi presensi berbasis lokasi',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 12),
                'migration_file' => '2025_09_12_000000_add_radius_presensi_to_presensi_settings_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'geofencing']
            ],
            [
                'title' => 'Optimasi Database Presensi',
                'description' => 'Penambahan unique constraint untuk mencegah duplikasi pengaturan presensi',
                'type' => 'update',
                'development_date' => Carbon::create(2025, 9, 12),
                'migration_file' => '2025_09_12_010000_add_unique_constraint_to_presensi_settings_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'database_optimization']
            ],
            [
                'title' => 'Jam Kerja Fleksibel',
                'description' => 'Implementasi pengaturan rentang waktu kerja yang fleksibel untuk berbagai jenis shift',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 12),
                'migration_file' => '2025_09_12_034745_add_time_ranges_to_presensi_settings_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'flexible_scheduling']
            ],
            [
                'title' => 'Pembersihan Database Legacy',
                'description' => 'Penghapusan kolom-kolom lama yang tidak terpakai untuk optimasi database',
                'type' => 'update',
                'development_date' => Carbon::create(2025, 9, 13),
                'migration_file' => '2025_09_13_000000_drop_legacy_presensi_columns.php',
                'details' => ['phase' => 'major_development', 'category' => 'database_cleanup']
            ],
            [
                'title' => 'Sistem ID NUIST',
                'description' => 'Penambahan ID NUIST untuk integrasi dengan sistem eksternal',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 13),
                'migration_file' => '2025_09_13_120000_add_nuist_id_to_users_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'external_integration']
            ],
            [
                'title' => 'Sistem Manajemen Hari Libur',
                'description' => 'Implementasi sistem manajemen hari libur untuk kalkulasi presensi yang akurat',
                'type' => 'feature',
                'development_date' => Carbon::create(2025, 9, 14),
                'migration_file' => '2025_09_14_000000_create_holidays_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'calendar_management']
            ],
            [
                'title' => 'Peningkatan Sistem Role',
                'description' => 'Update enum role untuk mendukung berbagai jenis pengguna dalam sistem',
                'type' => 'update',
                'development_date' => Carbon::create(2025, 9, 15),
                'migration_file' => '2025_09_15_000000_update_role_enum_in_users_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'role_management']
            ],
            [
                'title' => 'Integrasi Status Kepegawaian User',
                'description' => 'Implementasi foreign key relationship antara users dan status kepegawaian',
                'type' => 'enhancement',
                'development_date' => Carbon::create(2025, 9, 15),
                'migration_file' => '2025_09_15_044613_change_status_kepegawaian_to_foreign_key_in_users_table.php',
                'details' => ['phase' => 'major_development', 'category' => 'data_integrity']
            ]
        ];

        foreach ($histories as $history) {
            DevelopmentHistory::create($history);
        }
    }
}
