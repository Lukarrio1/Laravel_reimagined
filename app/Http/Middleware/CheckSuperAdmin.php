<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();
        $setting = \optional(Setting::where('key', 'admin_role')->first())->getSettingValue();
        $multi_tenancy = (int)\optional(Setting::where('key', 'multi_tenancy')->first())->getSettingValue('first');
        $multi_tenancy_role = $multi_tenancy == 0 ?: Role::find(\optional(Setting::where('key', 'multi_tenancy_role')->first())->getSettingValue('last'));
        $role = !empty($setting) ? Role::find((int)$setting) : null;

        // Check if the user is authenticated and has the "Super Admin" role
        if ($user->hasRole($role) || $user->hasRole($multi_tenancy_role)) {
            return $next($request);
        }
        Auth::logout();
        // Redirect to a specific\ route or return an unauthorized response
        return redirect()->route('login');
    }
}
