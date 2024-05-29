<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = request()->user();
        $setting = \optional(Setting::where('key', 'admin_role')->first())->getSettingValue();
        $multi_tenancy = (int)\optional(Setting::where('key', 'multi_tenancy')->first())->getSettingValue('first');
        $multi_tenancy_role = $multi_tenancy == 0 ? null : Role::find(\optional(Setting::where('key', 'multi_tenancy_role')->first())->getSettingValue('last'));
        $role = !empty($setting) ? Role::find((int)$setting) : null;
        // Check if the user is authenticated and has the "Super Admin" role
        if (!empty($role) && $user->hasRole($role) || !empty($multi_tenancy_role) && $user->hasRole($multi_tenancy_role)) {
            Cache::add('tenant_id', \auth()->user()->tenant_id);
            return $next($request);
        }
        Auth::logout();
        // Redirect to a specific\ route or return an unauthorized response
        return redirect()->route('login');
    }
}
