<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        // Check if the request contains a valid token
        $token = $request->bearerToken();
        $current_route = \join('::', \explode('@', Route::currentRouteAction()));
        $current_route_node = Cache::get('routes')
            ->where('properties.value.route_function', $current_route)->first();
        if (\in_array($current_route_node->authentication_level['value'], [0, 2])) {
            return $next($request); // Continue to the
        } else {
            if ($token) {
                $personalAccessToken = PersonalAccessToken::findToken($token);
                if ($personalAccessToken && $personalAccessToken->tokenable instanceof \App\Models\User) {
                    // Set the authenticated user
                    Auth::setUser($personalAccessToken->tokenable);
                    return $next($request);
                }

            }
            // If no valid token or not authenticated, return unauthorized response
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
