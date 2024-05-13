<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Audit;
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
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Force response content to be JSON
        $request->headers->set('Accept', 'application/json');

        // Extract the token from the request
        $token = $request->bearerToken();

        // Determine the current route's function name
        $currentRoute = join('::', explode('@', Route::currentRouteAction()));

        // Retrieve the route node from the cache
        $currentRouteNode = Cache::get('routes')
            ->where('properties.value.route_function', $currentRoute)
            ->first();

        if (empty($currentRouteNode)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check the required authentication level
        $authLevel = $currentRouteNode->authentication_level['value'];
        if (in_array($authLevel, [0, 2])) {
            return $this->handleAuthLevelZeroOrTwo($request, $next, $token, $authLevel);
        } else {
            return $this->handleAuthLevelOne($request, $next, $token, $currentRouteNode);
        }
    }

    /**
     * Handle routes with authentication level 0 or 2.
     */
    protected function handleAuthLevelZeroOrTwo(Request $request, Closure $next, $token, $authLevel)
    {
        $personalAccessToken = PersonalAccessToken::findToken($token);

        if ($authLevel == 0 && $personalAccessToken && $personalAccessToken->tokenable instanceof \App\Models\User) {
            Auth::setUser($personalAccessToken->tokenable);
            if (request()->user()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        return $next($request);
    }

    /**
     * Handle routes with authentication level 1.
     */
    protected function handleAuthLevelOne(Request $request, Closure $next, $token, $currentRouteNode)
    {
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $personalAccessToken = PersonalAccessToken::findToken($token);

        if ($personalAccessToken && $personalAccessToken->tokenable instanceof \App\Models\User) {
            Auth::setUser($personalAccessToken->tokenable);
            $node_audit_message = empty($currentRouteNode) ? '' : \optional(\optional($currentRouteNode)->properties['value'])->node_audit_message;
            Audit::create([
                'user_id' => \request()->user()->id,
                'node_id' => $currentRouteNode->id,
                'message' => (new Audit())->setUpMessage($node_audit_message)
                ]
            );
            // Check admin role
            $adminRoleId = optional(Setting::where('key', 'admin_role')->first())->getSettingValue();
            $adminRole = $adminRoleId ? Role::find($adminRoleId) : null;

            if ($adminRole && request()->user()->hasRole(optional($adminRole)->name)) {
                return $next($request);
            }

            // Check route-specific permissions
            if (!empty($currentRouteNode->permission) &&
                request()->user()->hasPermissionTo(optional($currentRouteNode->permission)->name)) {
                return $next($request);
            }

            // If no specific permissions are required, allow access
            if (empty($currentRouteNode->permission)) {
                return $next($request);
            }

            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
