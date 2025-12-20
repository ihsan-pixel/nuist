<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            // 2024 Indonesian National Holidays
            ['date' => '2024-01-01', 'name' => 'Tahun Baru 2024 Masehi', 'type' => 'national'],
            ['date' => '2024-02-08', 'name' => 'Isra Mi\'raj Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2024-02-10', 'name' => 'Tahun Baru Imlek 2575 Kongzili', 'type' => 'national'],
            ['date' => '2024-03-11', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1946', 'type' => 'national'],
            ['date' => '2024-03-29', 'name' => 'Wafat Isa Almasih', 'type' => 'national'],
            ['date' => '2024-03-31', 'name' => 'Hari Paskah', 'type' => 'national'],
            ['date' => '2024-04-10', 'name' => 'Idul Fitri 1445 Hijriah (Cuti Bersama)', 'type' => 'national'],
            ['date' => '2024-04-11', 'name' => 'Idul Fitri 1445 Hijriah', 'type' => 'national'],
            ['date' => '2024-04-12', 'name' => 'Idul Fitri 1445 Hijriah (Cuti Bersama)', 'type' => 'national'],
            ['date' => '2024-05-01', 'name' => 'Hari Buruh Internasional', 'type' => 'national'],
            ['date' => '2024-05-09', 'name' => 'Kenaikan Isa Almasih', 'type' => 'national'],
            ['date' => '2024-05-23', 'name' => 'Hari Raya Waisak 2568 BE', 'type' => 'national'],
            ['date' => '2024-06-01', 'name' => 'Hari Lahir Pancasila', 'type' => 'national'],
            ['date' => '2024-06-17', 'name' => 'Idul Adha 1445 Hijriah', 'type' => 'national'],
            ['date' => '2024-07-07', 'name' => 'Tahun Baru Islam 1446 Hijriah', 'type' => 'national'],
            ['date' => '2024-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia', 'type' => 'national'],
            ['date' => '2024-09-16', 'name' => 'Maulid Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2024-12-25', 'name' => 'Natal', 'type' => 'national'],
            ['date' => '2024-12-26', 'name' => 'Cuti Bersama Natal', 'type' => 'national'],

            // 2025 Indonesian National Holidays (preliminary)
            ['date' => '2025-01-01', 'name' => 'Tahun Baru 2025 Masehi', 'type' => 'national'],
            ['date' => '2025-01-27', 'name' => 'Isra Mi\'raj Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2025-01-29', 'name' => 'Tahun Baru Imlek 2576 Kongzili', 'type' => 'national'],
            ['date' => '2025-03-29', 'name' => 'Hari Suci Nyepi Tahun Baru Saka 1947', 'type' => 'national'],
            ['date' => '2025-04-01', 'name' => 'Wafat Isa Almasih', 'type' => 'national'],
            ['date' => '2025-04-18', 'name' => 'Jumat Agung', 'type' => 'national'],
            ['date' => '2025-04-20', 'name' => 'Hari Paskah', 'type' => 'national'],
            ['date' => '2025-04-30', 'name' => 'Idul Fitri 1446 Hijriah (Cuti Bersama)', 'type' => 'national'],
            ['date' => '2025-05-01', 'name' => 'Idul Fitri 1446 Hijriah', 'type' => 'national'],
            ['date' => '2025-05-02', 'name' => 'Idul Fitri 1446 Hijriah (Cuti Bersama)', 'type' => 'national'],
            ['date' => '2025-05-05', 'name' => 'Hari Buruh Internasional', 'type' => 'national'],
            ['date' => '2025-05-29', 'name' => 'Kenaikan Isa Almasih', 'type' => 'national'],
            ['date' => '2025-06-01', 'name' => 'Hari Lahir Pancasila', 'type' => 'national'],
            ['date' => '2025-06-11', 'name' => 'Hari Raya Waisak 2569 BE', 'type' => 'national'],
            ['date' => '2025-06-06', 'name' => 'Idul Adha 1446 Hijriah', 'type' => 'national'],
            ['date' => '2025-06-26', 'name' => 'Tahun Baru Islam 1447 Hijriah', 'type' => 'national'],
            ['date' => '2025-08-17', 'name' => 'Hari Kemerdekaan Republik Indonesia', 'type' => 'national'],
            ['date' => '2025-09-04', 'name' => 'Maulid Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2025-12-25', 'name' => 'Natal', 'type' => 'national'],

            // 2026 Indonesian National Holidays
            ['date' => '2026-01-01', 'name' => 'Tahun Baru Masehi', 'type' => 'national'],
            ['date' => '2026-01-16', 'name' => 'Isra’ Mi’raj Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2026-02-17', 'name' => 'Tahun Baru Imlek 2577 Kongzili', 'type' => 'national'],
            ['date' => '2026-03-19', 'name' => 'Hari Suci Nyepi (Tahun Baru Saka)', 'type' => 'national'],
            ['date' => '2026-03-21', 'name' => 'Hari Raya Idul Fitri 1477 H', 'type' => 'national'],
            ['date' => '2026-03-22', 'name' => 'Hari Raya Idul Fitri 1477 H', 'type' => 'national'],
            ['date' => '2026-04-03', 'name' => 'Wafat Yesus Kristus (Good Friday)', 'type' => 'national'],
            ['date' => '2026-04-05', 'name' => 'Kebangkitan Yesus Kristus (Paskah)', 'type' => 'national'],
            ['date' => '2026-05-01', 'name' => 'Hari Buruh Internasional', 'type' => 'national'],
            ['date' => '2026-05-14', 'name' => 'Kenaikan Yesus Kristus', 'type' => 'national'],
            ['date' => '2026-05-27', 'name' => 'Hari Raya Idul Adha 1447 H', 'type' => 'national'],
            ['date' => '2026-05-31', 'name' => 'Hari Raya Waisak 2570 BE', 'type' => 'national'],
            ['date' => '2026-06-01', 'name' => 'Hari Lahir Pancasila', 'type' => 'national'],
            ['date' => '2026-06-16', 'name' => '1 Muharram (Tahun Baru Islam 1448 H)', 'type' => 'national'],
            ['date' => '2026-08-17', 'name' => 'Hari Proklamasi Kemerdekaan RI', 'type' => 'national'],
            ['date' => '2026-08-25', 'name' => 'Maulid Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2026-12-25', 'name' => 'Hari Natal', 'type' => 'national'],

            // 2027 Indonesian National Holidays
            ['date' => '2027-01-01', 'name' => 'Tahun Baru Masehi', 'type' => 'national'],
            ['date' => '2027-01-05', 'name' => 'Isra Mi’raj Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2027-02-06', 'name' => 'Tahun Baru Imlek 2578 Kongzili', 'type' => 'national'],
            ['date' => '2027-03-09', 'name' => 'Hari Suci Nyepi (Tahun Baru Saka)', 'type' => 'national'],
            ['date' => '2027-03-10', 'name' => 'Hari Raya Idul Fitri', 'type' => 'national'],
            ['date' => '2027-03-26', 'name' => 'Wafat Yesus Kristus (Jumat Agung)', 'type' => 'national'],
            ['date' => '2027-05-01', 'name' => 'Hari Buruh Internasional', 'type' => 'national'],
            ['date' => '2027-05-06', 'name' => 'Kenaikan Yesus Kristus', 'type' => 'national'],
            ['date' => '2027-05-17', 'name' => 'Hari Raya Idul Adha', 'type' => 'national'],
            ['date' => '2027-05-20', 'name' => 'Hari Raya Waisak', 'type' => 'national'],
            ['date' => '2027-06-01', 'name' => 'Hari Lahir Pancasila', 'type' => 'national'],
            ['date' => '2027-06-06', 'name' => 'Tahun Baru Islam', 'type' => 'national'],
            ['date' => '2027-08-15', 'name' => 'Maulid Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2027-08-17', 'name' => 'Hari Kemerdekaan RI', 'type' => 'national'],
            ['date' => '2027-12-25', 'name' => 'Hari Raya Natal', 'type' => 'national'],
            ['date' => '2027-12-26', 'name' => 'Isra Mi’raj Nabi Muhammad SAW', 'type' => 'national'],

            // 2028 Indonesian National Holidays
            ['date' => '2028-01-01', 'name' => 'Tahun Baru Masehi', 'type' => 'national'],
            ['date' => '2028-01-26', 'name' => 'Tahun Baru Imlek', 'type' => 'national'],
            ['date' => '2028-02-26', 'name' => 'Hari Raya Idul Fitri (mulai)', 'type' => 'national'],
            ['date' => '2028-02-27', 'name' => 'Hari Raya Idul Fitri (lanjutan)', 'type' => 'national'],
            ['date' => '2028-03-26', 'name' => 'Jumat Agung / Wafat Yesus Kristus', 'type' => 'national'],
            ['date' => '2028-05-01', 'name' => 'Hari Buruh Internasional', 'type' => 'national'],
            ['date' => '2028-05-05', 'name' => 'Hari Raya Idul Adha', 'type' => 'national'],
            ['date' => '2028-05-25', 'name' => 'Tahun Baru Islam (1 Muharram)', 'type' => 'national'],
            ['date' => '2028-06-01', 'name' => 'Hari Lahir Pancasila', 'type' => 'national'],
            ['date' => '2028-08-03', 'name' => 'Maulid Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2028-08-17', 'name' => 'Hari Kemerdekaan RI', 'type' => 'national'],
            ['date' => '2028-12-14', 'name' => 'Isra Mi’raj Nabi Muhammad SAW', 'type' => 'national'],
            ['date' => '2028-12-25', 'name' => 'Hari Raya Natal', 'type' => 'national'],
            ['date' => '2028-12-26', 'name' => 'Cuti bersama Natal (opsional)', 'type' => 'national'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['date' => $holiday['date'], 'type' => $holiday['type']],
                $holiday
            );
        }

        $this->command->info('Holiday data seeded successfully!');
    }
}
