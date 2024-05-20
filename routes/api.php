<?php

use App\Models\Setting;
use App\Models\Node\Node;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Http\Middleware\AuthMiddleware;

// Ensure routes are cached
if (!Cache::has('routes')) {
    $nodes = Node::where('node_status', 1)
        ->where('node_type', 1)
        ->get();

    // Add routes to cache
    Cache::add('routes', $nodes, now()->addMinutes(30)); // Cache with expiration (optional)
}

if (!Cache::has('settings')) {
    Cache::add('settings', Setting::all());
}

if(!Cache::has('nodes')){
    Cache::add('nodes',Node::where('node_status', 1)
        ->where('node_type','>', 1)
        ->get());
}

// Retrieve cached routes
$routes = Cache::get('routes');
// Register each route with its corresponding method and function
$routes->each(function ($route) {
    $properties = $route->properties['value'];

    // Validate required properties to avoid errors
    if (empty($properties->route_method) || empty($properties->node_route) || empty($properties->route_function)) {
        return; // Skip invalid or incomplete routes
    }

    // Determine route method, path, and function
    $method = strtolower($properties->route_method); // Ensure method is lowercase
    $node_route =\collect(\explode('/', $properties->node_route))
        ->filter(function($dt,$key)use($properties){
            if(array_search('api',\explode('/', $properties->node_route))<$key){
               return true;
            }
            return false;
        })->join('/');
    // Extract controller and method from the route function
    $routeFunctionParts = explode('::', $properties->route_function);
    if (count($routeFunctionParts) !== 2) {
        return; // Skip if route function format is invalid
    }

    list($controller, $methodName) = $routeFunctionParts;

    // Register the route with the specified method, path, and middleware
    Route::$method($node_route, [$controller, $methodName])
        ->middleware(AuthMiddleware::class);
});
