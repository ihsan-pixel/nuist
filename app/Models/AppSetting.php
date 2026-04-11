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
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'debug_mode' => 'boolean',
        'cache_enabled' => 'boolean',
        'session_lifetime' => 'integer',
        'bni_va_enabled' => 'boolean',
        'bni_va_mock_mode' => 'boolean',
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
