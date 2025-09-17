<?php

namespace App\Imports;

use App\Models\TenagaPendidik;
use App\Models\User;
use App\Models\Madrasah;
use App\Models\StatusKepegawaian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TenagaPendidikImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['nama']) || empty($row['email'])) {
            return null;
        }

        // Generate password using NIP or default pattern
        $defaultPassword = !empty($row['nip']) ? $row['nip'] : 'nuist123';

        Log::info('Importing tenaga pendidik: ' . $row['nama'] . ' - ' . $row['email'] . ' with password: ' . $defaultPassword);

        try {
            // Validate required fields
            $requiredFields = [
                'nama', 'email', 'tempat_lahir', 'tanggal_lahir', 'no_hp',
                'kartanu', 'nip', 'nuptk', 'npk', 'madrasah_id',
                'pendidikan_terakhir', 'tahun_lulus', 'program_studi',
                'status_kepegawaian_id', 'tmt', 'ketugasan', 'alamat'
            ];

            foreach ($requiredFields as $field) {
                if (empty($row[$field])) {
                    throw new \Exception("Field {$field} is required but empty");
                }
            }

            // Validate email format
            if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \Exception("Invalid email format: " . $row['email']);
            }

            // Validate madrasah_id exists
            if (!empty($row['madrasah_id']) && !Madrasah::find($row['madrasah_id'])) {
                throw new \Exception("Madrasah ID {$row['madrasah_id']} not found");
            }

            // Validate status_kepegawaian_id exists
            if (!empty($row['status_kepegawaian_id']) && !StatusKepegawaian::find($row['status_kepegawaian_id'])) {
                throw new \Exception("Status Kepegawaian ID {$row['status_kepegawaian_id']} not found");
            }

            // Create corresponding User record
            User::create([
                'name' => $row['nama'],
                'email' => $row['email'],
                'password' => Hash::make($defaultPassword),
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'no_hp' => $row['no_hp'],
                'kartanu' => $row['kartanu'],
                'nip' => $row['nip'],
                'nuptk' => $row['nuptk'],
                'npk' => $row['npk'],
                'madrasah_id' => $row['madrasah_id'],
                'pendidikan_terakhir' => $row['pendidikan_terakhir'],
                'tahun_lulus' => $row['tahun_lulus'],
                'program_studi' => $row['program_studi'],
                'status_kepegawaian_id' => $row['status_kepegawaian_id'],
                'tmt' => $row['tmt'],
                'ketugasan' => $row['ketugasan'],
                'alamat' => $row['alamat'],
                'role' => 'tenaga_pendidik',
            ]);

            Log::info('Successfully imported: ' . $row['nama']);
        } catch (\Exception $e) {
            Log::error('Failed to import ' . $row['nama'] . ': ' . $e->getMessage());
            throw $e;
        }

        return null;
    }
}
