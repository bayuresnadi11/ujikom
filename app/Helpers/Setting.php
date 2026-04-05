<?php

use App\Models\Setting;

if (! function_exists('app_setting')) {
    function app_setting(string $key, $default = null)
    {
        static $setting = null;

        if ($setting === null) {
            $setting = Setting::first();
        }

        return $setting->$key ?? $default;
    }
}
