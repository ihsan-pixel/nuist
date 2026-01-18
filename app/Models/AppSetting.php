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
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'debug_mode' => 'boolean',
        'cache_enabled' => 'boolean',
        'session_lifetime' => 'integer',
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
