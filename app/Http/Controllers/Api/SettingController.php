<?php

namespace App\Http\Controllers\Api;

use App\Models\Export;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{

    public function settings()
    {

        $settings =
            // Cache::get('settings')->whereIn('id',[3,11]);
            Setting::select('key', 'properties', 'id', 'allowed_for_api_use')
            ->where('allowed_for_api_use', 1)->get()
            ->map(function ($item) {
                $item->properties =['key'=> $item->getSettingValue('first'),'value' => $item->getSettingValue('last')];
                return $item;
            });
        return \response()->json(['settings' => $settings]);
    }
}
