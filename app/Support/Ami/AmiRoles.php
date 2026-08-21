<?php

namespace App\Support\Ami;

final class AmiRoles
{
    public const SUPER_ADMIN = 'super_admin';
    public const PENGURUS = 'pengurus';
    public const KOORDINATOR_AUDITOR = 'koordinator_auditor';
    public const AUDITOR = 'auditor';
    public const ADMIN_SEKOLAH = 'admin_sekolah';

    public static function labels(): array
    {
        return [
            self::SUPER_ADMIN => 'Super Admin',
            self::PENGURUS => 'Pengurus',
            self::KOORDINATOR_AUDITOR => 'Koordinator Auditor',
            self::AUDITOR => 'Auditor',
            self::ADMIN_SEKOLAH => 'Admin Sekolah',
        ];
    }
}
