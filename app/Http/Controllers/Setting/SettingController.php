<?php

namespace App\Http\Controllers\Setting;

use PSpell\Config;
use App\Models\Setting;
use App\Models\Node\Node;
use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Cache\CacheController;

class SettingController extends Controller
{
    public $tenancy;
    public function __construct()
    {
        $this->middleware('can:can crud settings');
        $this->tenancy = new Tenant();
    }

    public function index($setting_key = 'admin_role')
    {
        $setting = new Setting();
        $settings_for_display
            = $setting->query()
                ->latest('updated_at')->get();
        $keys
            = collect($setting->getAllSettingKeys())
                ->filter(fn($key, $idx) => \request()->get('setting_key') == $idx || !\in_array($idx, $settings_for_display->pluck('key')->toArray()));

        $setting_key = empty(\request()->get('setting_key')) ? $keys->keys()->first() : \request()->get('setting_key');
        $field_value = optional(collect(Cache::get('settings')));

        return view('Setting.View', [
            'keys' => $keys,
            'key_value' => $setting->SETTING_KEYS($setting_key, $field_value)['field'],
            'allowed_for_api_use' => \collect(Cache::get('settings', \collect(Setting::all()))
                ->firstWhere('key', $setting_key))->get('allowed_for_api_use', 0),
            'setting_key' => $setting_key,
            'settings' => [...$settings_for_display],
        ]);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), ['value' => ['required'], 'setting_key' => ['required']]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // $multi_tenancy_role_id = \optional(Setting::where('key', 'multi_tenancy_role')->first())->getSettingValue();
        // $role_for_checking = !empty($setting) ? Role::find((int)$multi_tenancy_role_id) : null;
        $value = \in_array($request->setting_key, ["allowed_login_roles", 'not_exportable_tables']) ? \collect($request->value)->map(function ($item) {
            return \collect(\explode(' ', $item))->join("--");
        })->join('|') : $request->value;

        Setting::updateOrCreate(
            [
                'key' => $request->setting_key
            ],
            $request->merge(['properties' => gettype($value) != "string" ? json_encode($value) : $value])->all()
        );
        Cache::forget('settings');
        Cache::forget('setting_allowed_login_roles');
        Cache::set('settings', Setting::latest()->get());
        $allowed_login_roles = \optional(Setting::where('key', 'allowed_login_roles')->first())->getSettingValue('last') ?? \collect([]);
        Cache::add('setting_allowed_login_roles', $allowed_login_roles->toArray());
        Session::flash('message', 'The setting value was saved successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->route('viewSettings');
    }

    public function delete($setting_key)
    {

        $setting = Setting::where('key', $setting_key)->first();
        $setting->delete();        // exportable_tables
        return \redirect()->route('viewSettings');
    }
}
