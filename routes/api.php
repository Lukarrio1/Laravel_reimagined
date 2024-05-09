<?php

use App\Models\Node\Node;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;

if (!Cache::has('routes')) {
    Cache::add('routes', Node::query()->where('node_status', 1)->where('node_type', 1)->get());
}

$routes = Cache::get('routes');
$routes->each(function ($route) {
    $method = $route->properties['value']->route_method;
    $node_route = $route->properties['value']->node_route;
    $route_function = collect(explode('::', $route->properties['value']->route_function));
    Route::$method($node_route, [$route_function->first(), $route_function->last()])
        ->middleware(AuthMiddleware::class);
});
