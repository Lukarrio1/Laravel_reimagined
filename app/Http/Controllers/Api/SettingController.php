<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{

    public function settings()
    {

        $settings =
            // Cache::get('settings')->whereIn('id',[3,11]);
            Setting::whereIn('id', [3, 11, 12])->select('key', 'properties', 'id')->get();
        return \response()->json(['settings' => $settings]);
    }
}
