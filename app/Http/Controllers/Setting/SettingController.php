<?php

namespace App\Http\Controllers\Setting;

use PSpell\Config;
use App\Models\Setting;
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

        $setting_key = empty(\request()->get('setting_key')) ? $setting_key : \request()->get('setting_key');
        $multi_tenancy_role_id = \optional(Setting::where('key', 'multi_tenancy_role')->first())->getSettingValue();
        $role_for_checking = !empty($setting) ? Role::find((int)$multi_tenancy_role_id) : null;
        $field_value = optional(collect(Cache::get('settings')));
        return view('Setting.View', [
            'keys' => $setting->getAllSettingKeys(),
            'key_value' => $setting->SETTING_KEYS($setting_key, $field_value)['field'],
            'setting_key' => $setting_key,
            'settings' => $setting->query()
                ->when(
                    !empty(\auth()->user()) && !empty($role_for_checking) && \auth()->user()->hasRole($role_for_checking),
                    fn ($q) => $q->where('tenant_id', \auth()->user()->tenant_id)
                )
                ->latest('updated_at')
                ->get(),
        ]);
    }

    public function save(Request $request)
    {
        // dd($request->all());
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
            ] + $this->tenancy->addTenantIdToCurrentItem(\auth()->user()->tenant_id),
            $request->merge(['properties' => $value])->all()
                + $this->tenancy->addTenantIdToCurrentItem(Cache::get('tenant_id'))
        );
        Cache::forget('settings');
        Cache::forget('setting_allowed_login_roles');
        Cache::forget('not_exportable_tables');
        // exportable_tables
        Cache::set('settings', Setting::latest()->get());
        $allowed_login_roles = \optional(Setting::where('key', 'allowed_login_roles')->first())->getSettingValue('last') ?? \collect([]);
        Cache::set('not_exportable_tables', \optional(Setting::where('key', 'not_exportable_tables')->first())->getSettingValue('last'));
        Cache::add('setting_allowed_login_roles', $allowed_login_roles->toArray());
        Session::flash('message', 'The setting value was saved successfully.');
        Session::flash('alert-class', 'alert-success');
        // (new CacheController())->clearCache();
        return \redirect()->route('viewSettings');
    }

    public function delete($setting_key)
    {

        $setting = Setting::where('key', $setting_key)->first();
        $setting->delete();        // exportable_tables
        return \redirect()->route('viewSettings');
    }
}
