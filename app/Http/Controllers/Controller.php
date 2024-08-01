<?php

namespace App\Http\Controllers;

use ReflectionClass;
use App\SendEmailTrait;
use Illuminate\Support\Str;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, SendEmailTrait;

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize');
    }

    public function getCurrentRoute()
    {
        $currentRoute = join('::', explode('@', Route::currentRouteAction()));
        return Cache::get('routes')
            ->where('properties.value.route_function', $currentRoute)
            ->first();
    }

    public  function getValidationRules()
    {
        $rules = ["required", "integer", "min:3", "min:5", "min:10", 'sometimes', 'present', "max:3", "max:5", "max:10"];
        return $rules;
    }
}
