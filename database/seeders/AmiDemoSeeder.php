<?php

namespace Database\Seeders;

use App\Models\AmiAssignment;
use App\Models\AmiComponent;
use App\Models\AmiIndicator;
use App\Models\AmiInstrument;
use App\Models\AmiItem;
use App\Models\AmiPeriod;
use App\Models\AmiPeriodSchool;
use App\Models\AmiSchoolResponse;
use App\Models\Madrasah;
use App\Models\User;
use App\Support\Ami\AmiRoles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AmiDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! config('ami.seed_dummy_data')) {
            return;
        }

        $users = [
            'superadmin.ami@nuist.test' => AmiRoles::SUPER_ADMIN,
            'pengurus.ami@nuist.test' => AmiRoles::PENGURUS,
            'koordinator.ami@nuist.test' => AmiRoles::KOORDINATOR_AUDITOR,
            'auditor1.ami@nuist.test' => AmiRoles::AUDITOR,
            'auditor2.ami@nuist.test' => AmiRoles::AUDITOR,
            'sekolah.ami@nuist.test' => AmiRoles::ADMIN_SEKOLAH,
        ];

        $createdUsers = [];
        foreach ($users as $email => $role) {
            $createdUsers[$email] = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => match ($email) {
                        'superadmin.ami@nuist.test' => 'Super Admin AMI',
                        'pengurus.ami@nuist.test' => 'Pengurus AMI',
                        'koordinator.ami@nuist.test' => 'Koordinator Auditor AMI',
                        'auditor1.ami@nuist.test' => 'Auditor 1 AMI',
                        'auditor2.ami@nuist.test' => 'Auditor 2 AMI',
                        'sekolah.ami@nuist.test' => 'Admin Sekolah AMI',
                        default => 'AMI User',
                    },
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'is_active' => true,
                ]
            );
        }

        $schools = [];
        foreach ([
            ['name' => 'SMK NUIST 1', 'scod' => 1001, 'kabupaten' => 'Sleman'],
            ['name' => 'SMK NUIST 2', 'scod' => 1002, 'kabupaten' => 'Bantul'],
            ['name' => 'SMK NUIST 3', 'scod' => 1003, 'kabupaten' => 'Kulon Progo'],
        ] as $schoolData) {
            $schools[] = Madrasah::updateOrCreate(['scod' => $schoolData['scod']], $schoolData);
        }

        $period = AmiPeriod::updateOrCreate(
            ['year' => 2026],
            ['name' => 'AMI 2026', 'status' => 'dibuka']
        );

        $instrument = AmiInstrument::updateOrCreate(
            ['code' => 'IA2024V2025'],
            ['ami_period_id' => $period->id, 'name' => 'Instrumen AMI SMK/MAK IA2024 Versi 2025', 'description' => 'Instrumen awal AMI', 'is_active' => true]
        );

        $component = AmiComponent::updateOrCreate(
            ['code' => 'K1'],
            ['ami_instrument_id' => $instrument->id, 'name' => 'Kinerja Pendidik', 'sort_order' => 1]
        );

        $item = AmiItem::updateOrCreate(
            ['code' => 'B1'],
            ['ami_component_id' => $component->id, 'name' => 'Butir 1', 'sort_order' => 1]
        );

        $indicator = AmiIndicator::updateOrCreate(
            ['code' => 'B1.I1'],
            [
                'ami_item_id' => $item->id,
                'name' => 'Indikator 1',
                'operational_definition' => 'Definisi operasional',
                'fulfillment_criteria' => 'Kriteria pemenuhan',
                'rubric' => 'Rubrik',
                'requires_evidence' => true,
                'minimum_evidence_count' => 1,
            ]
        );

        foreach ($schools as $school) {
            $periodSchool = AmiPeriodSchool::updateOrCreate(
                ['ami_period_id' => $period->id, 'madrasah_id' => $school->id],
                ['status' => 'draft']
            );

            AmiSchoolResponse::updateOrCreate(
                ['ami_period_school_id' => $periodSchool->id, 'ami_indicator_id' => $indicator->id],
                [
                    'user_id' => $createdUsers['sekolah.ami@nuist.test']->id,
                    'self_assessment_score' => 3,
                    'school_performance_description' => 'Deskripsi kinerja sekolah.',
                    'internal_notes' => 'Catatan internal.',
                    'status' => 'draft',
                ]
            );
        }

        AmiAssignment::updateOrCreate(
            ['ami_period_id' => $period->id, 'madrasah_id' => $schools[0]->id, 'auditor_id' => $createdUsers['auditor1.ami@nuist.test']->id],
            [
                'assigned_by' => $createdUsers['koordinator.ami@nuist.test']->id,
                'role_in_team' => 'ketua',
                'is_lead' => true,
                'status' => 'active',
            ]
        );
    }
}
