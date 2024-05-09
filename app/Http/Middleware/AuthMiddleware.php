<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        $request->headers->set('Accept', 'application/json');
        $current_route = \join('::', \explode('@', Route::currentRouteAction()));
        $current_route_node = Cache::get('routes')
            ->where('properties.value.route_function', $current_route)->first();
        if (empty($current_route_node)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if (\in_array($current_route_node->authentication_level['value'], [0, 2])) {
            $personalAccessToken = PersonalAccessToken::findToken($token);
            if ($current_route_node->authentication_level['value'] == 0 && $personalAccessToken && $personalAccessToken->tokenable instanceof \App\Models\User) {
                Auth::setUser($personalAccessToken->tokenable);
                if (!empty(request()->user())) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
            } else {
                return $next($request);
            }
        } else {
            if ($token) {
                $personalAccessToken = PersonalAccessToken::findToken($token);
                $setting = \optional(Setting::where('key', 'admin_role')->first())
                    ->getSettingValue();
                $role = !empty($setting) ? Role::find($setting) : null;
                if ($personalAccessToken && $personalAccessToken->tokenable instanceof \App\Models\User) {
                    Auth::setUser($personalAccessToken->tokenable);
                    if (!empty($role) && request()->user()->hasRole(\optional($role)->name)) {
                        return $next($request);

                    }
                    if (!empty(\optional($current_route_node)->permission) && \request()->user()->hasPermissionTo(\optional($current_route_node->permission)->name)) { // Adjust the permission name
                        return $next($request);
                    } elseif (empty($current_route_node->permission)) {
                        return $next($request);
                    }
                    return response()->json(['error' => 'Forbidden'], 403);

                }
            }
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
