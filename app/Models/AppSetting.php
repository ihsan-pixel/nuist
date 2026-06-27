<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    use HasFactory;

    protected $table = 'app_settings';

    protected $fillable = [
        'app_name',
        'app_version',
        'banner_image',
        'maintenance_mode',
        'timezone',
        'locale',
        'debug_mode',
        'cache_enabled',
        'session_lifetime',
        'midtrans_server_key',
        'midtrans_client_key',
        'midtrans_is_production',
        'bni_va_enabled',
        'bni_va_mock_mode',
        'bni_va_api_url',
        'bni_va_client_id',
        'bni_va_client_secret',
        'bni_va_merchant_id',
        'bni_va_prefix',
        'bni_va_callback_token',
        'sk_yayasan_school_year',
        'sk_yayasan_number_start',
        'sk_yayasan_signer_name',
        'sk_yayasan_signer_position',
        'sk_yayasan_established_at',
        'sk_yayasan_issued_date',
        'sk_yayasan_number_format_suffix',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'debug_mode' => 'boolean',
        'cache_enabled' => 'boolean',
        'session_lifetime' => 'integer',
        'bni_va_enabled' => 'boolean',
        'bni_va_mock_mode' => 'boolean',
        'sk_yayasan_number_start' => 'integer',
        'sk_yayasan_issued_date' => 'date',
    ];

    /**
     * Get the first (and only) app settings record
     */
    public static function getSettings()
    {
        return static::first() ?? static::create([
            'app_name' => 'NUIST',
            'app_version' => '1.0.0',
            'banner_image' => null,
            'maintenance_mode' => false,
            'timezone' => 'Asia/Jakarta',
            'locale' => 'id',
            'debug_mode' => false,
            'cache_enabled' => true,
            'session_lifetime' => 120,
            'bni_va_enabled' => false,
            'bni_va_mock_mode' => true,
            'sk_yayasan_school_year' => now()->format('Y') . '-' . now()->addYear()->format('Y'),
            'sk_yayasan_number_start' => 1,
            'sk_yayasan_signer_name' => '',
            'sk_yayasan_signer_position' => 'Ketua Yayasan',
            'sk_yayasan_established_at' => 'Yogyakarta',
            'sk_yayasan_issued_date' => now()->toDateString(),
            'sk_yayasan_number_format_suffix' => 'SK.02/LPM.DIY/{month_roman}/{year}',
        ]);
    }

    /**
     * Get banner image URL
     */
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }
}
