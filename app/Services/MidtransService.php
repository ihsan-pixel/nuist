<?php

namespace App\Services;

use App\Models\AppSetting;
use Midtrans\Config;

class MidtransService
{
    public static function init()
    {
        $setting = AppSetting::find(1);

        Config::$serverKey = $setting->midtrans_server_key ?? config('services.midtrans.server_key');
        Config::$isProduction = $setting->midtrans_is_production ?? false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public static function serverKey()
    {
        $setting = AppSetting::find(1);
        return $setting->midtrans_server_key ?? config('services.midtrans.server_key');
    }

    public static function clientKey()
    {
        $setting = AppSetting::find(1);
        return $setting->midtrans_client_key ?? config('services.midtrans.client_key');
    }

    public static function isProduction()
    {
        $setting = AppSetting::find(1);
        return $setting->midtrans_is_production ?? false;
    }
}
