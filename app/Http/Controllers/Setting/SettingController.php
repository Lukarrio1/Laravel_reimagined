<?php

namespace App\Http\Controllers\Setting;

use App\Models\Setting;
use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index($setting_key = 'admin_role')
    {
        $setting = new Setting();
        $setting_key = empty(\request()->get('setting_key')) ? $setting_key : \request()->get('setting_key');
        return view('Setting.View', [
            'keys' => $setting->getAllSettingKeys(),
            'key_value' => $setting->SETTING_KEYS($setting_key)['field'],
            'setting_key' => $setting_key,
            'settings'=>$setting->all()
        ]);
    }

    public function save(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), ['value' => ['required'], 'setting_key' => ['required']]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        Setting::updateOrCreate(['key'=>$request->setting_key],$request->merge(['properties'=>$request->value])->all());

        return \redirect()->route('viewSettings');
    }
}
