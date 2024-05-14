<?php

namespace App\Http\Controllers;

use App\SendEmailTrait;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests,SendEmailTrait;

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize');

    }
}
