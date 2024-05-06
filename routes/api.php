<?php

use App\Models\Node\Node;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

$routes = Node::query()->where('node_status', 1)->where('node_type', 1)->get();
Cache::add('routes',$routes);

$routes->each(function ($route) {
    $method = $route->properties['value']->route_method;
    $node_route = $route->properties['value']->node_route;
    $route_function = collect(explode('::', $route->properties['value']->route_function));
    // if ($route->node_status['value']== 1) {
    //     Route::middleware(['auth:sanctum'])->group(function ()use($method,$node_route,$route_function) {
    //         Route::$method($node_route, [$route_function->first(), $route_function->last()]);
    //     });
    // } else {
        Route::$method($node_route, [$route_function->first(), $route_function->last()])
        ->middleware(AuthMiddleware::class);
//    }

});
