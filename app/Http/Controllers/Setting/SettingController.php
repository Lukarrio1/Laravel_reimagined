<?php

namespace App\Http\Controllers\Setting;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        return view('Setting.View', [
            'keys' => $setting->getAllSettingKeys(),
            'key_value' => $setting->SETTING_KEYS($setting_key)['field'],
            'setting_key' => $setting_key,
            'settings' => $setting->query()
                ->when(
                    !empty(\auth()->user()) && !empty($role_for_checking) && \auth()->user()->hasRole($role_for_checking),
                    fn ($q) => $q->where('tenant_id', \auth()->user()->tenant_id)
                )
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
        $multi_tenancy_role_id = \optional(Setting::where('key', 'multi_tenancy_role')->first())->getSettingValue();
        $role_for_checking = !empty($setting) ? Role::find((int)$multi_tenancy_role_id) : null;
        $value = $request->setting_key == "allowed_login_roles" ? \collect($request->value)->map(function ($item) {
            return \collect(\explode(' ', $item))->join("--");
        })->join('|') : $request->value;
        Setting::updateOrCreate(
            [
                'key' => $request->setting_key
            ] + $this->tenancy->addTenantIdToCurrentItem(\auth()->user()->tenant_id),
            $request->merge(['properties' => $value])->all()
                + $this->tenancy->addTenantIdToCurrentItem(Cache::get('tenant_id'))
        );
        Session::flash('message', 'The setting value was saved successfully.');
        Session::flash('alert-class', 'alert-success');
        return \redirect()->route('viewSettings');
    }

    public function delete($setting_key)
    {
        $setting = Setting::where('key', $setting_key)->first();
        $setting->delete();
        return \redirect()->route('viewSettings');
    }
}
