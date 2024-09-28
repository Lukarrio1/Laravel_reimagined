<?php

use Illuminate\Support\Facades\Cache;


if (!function_exists('getSetting')) {
    /**
     * Method getSetting
     *
     * @param $key $key [setting key]
     * @param $value $value [setting value ("first" or "last") default value is last]
     * @param $default_value $default_value [used if value does not exist]
     *
     * @return mixed
     */
    function getSetting($key = '', $value = "last", $default_value = null)
    {
        return \optional(Cache::get('settings')
            ->where('key', $key)->first())
            ->getSettingValue($value) ?? $default_value;
    }
}
