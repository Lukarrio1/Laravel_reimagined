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
        $rules = ["required","string","integer","min:3", "min:5", "min:10",'sometimes'];

        // Get the reflection class instance of the Validator
        // $validatorReflection = new ReflectionClass(Validator::class);

        // // Get all methods from the Validator class
        // $methods = $validatorReflection->getMethods();

        // foreach ($methods as $method) {
        //     // Look for methods starting with "validate"
        //     if (strpos($method->name, 'validate') === 0 && $method->isPublic()) {
        //         // Get the rule name by removing "validate" and converting camelCase to snake_case
        //         $ruleName = Str::snake(substr($method->name, 8));
        //         $rules[] = $ruleName;
        //     }
        // }

        // Return the list of rules
        return $rules;
    }
}
